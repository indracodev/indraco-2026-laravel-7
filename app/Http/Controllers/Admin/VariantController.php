<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Variant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class VariantController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $variants = Variant::with('type')
            ->when($search, function($query) use ($search) {
                $query->where('variant_name', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%");
            })
            ->orderBy('sort_order')
            ->latest()
            ->get();
            
        // Get all assets for icon picker
        $assetsPath = public_path('images');
        $assetFiles = [];
        if (File::exists($assetsPath)) {
            $this->collectImages($assetsPath, 'images', $assetFiles);
        }

        return view('admin.variants.index', compact('variants', 'search', 'assetFiles'));
    }

    private function collectImages($dir, $relPath, &$result, $depth = 0)
    {
        if ($depth > 3) return;
        foreach (File::files($dir) as $file) {
            $ext = strtolower($file->getExtension());
            if (in_array($ext, ['jpg','jpeg','png','gif','webp','svg'])) {
                $filePath = $relPath . '/' . $file->getFilename();
                $result[] = [
                    'name' => $file->getFilename(),
                    'path' => $filePath,
                    'url'  => asset($filePath),
                ];
            }
        }
        foreach (File::directories($dir) as $subDir) {
            $this->collectImages($subDir, $relPath . '/' . basename($subDir), $result, $depth + 1);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'type_id'     => 'nullable|exists:master_type,id',
            'status'      => 'nullable|in:active,inactive',
            'description' => 'nullable|string',
            'taste'       => 'nullable|string',
            'acidity'     => 'nullable|numeric|min:0|max:10',
            'body'        => 'nullable|numeric|min:0|max:10',
            'roast'       => 'nullable|string|max:255',
            'ingredient'  => 'nullable|string',
            'bg_color'    => 'nullable|string|max:20',
            'text_color'  => 'nullable|string|max:20',
            'map_opacity' => 'nullable|numeric|min:0|max:1',
            'map_size'    => 'nullable|integer',
            'map_top'     => 'nullable|integer',
            'map_right'   => 'nullable|integer',
            'sort_order'  => 'nullable|integer',
        ]);
        
        $data['slug'] = Str::slug($data['name']) . '-' . time();
        $data['description'] = $this->cleanHtml($data['description']);
        $data['taste'] = $this->cleanHtml($data['taste']);
        $data['ingredient'] = $this->cleanHtml($data['ingredient']);

        // Handle map_image upload
        if ($request->hasFile('map_image')) {
            $file = $request->file('map_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/variants'), $filename);
            $data['map_image'] = 'images/variants/' . $filename;
        }

        // Handle icon_path: either uploaded file or selected asset
        if ($request->hasFile('icon_file')) {
            $file = $request->file('icon_file');
            $filename = time() . '_icon_' . $file->getClientOriginalName();
            $file->move(public_path('images/variants'), $filename);
            $data['icon_path'] = 'images/variants/' . $filename;
        } elseif ($request->filled('icon_path_asset')) {
            $data['icon_path'] = $request->input('icon_path_asset');
        }
        
        Variant::create($data);
        return redirect()->back()->with('success', 'Varian berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $variant = Variant::findOrFail($id);
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'type_id'     => 'nullable|exists:master_type,id',
            'status'      => 'nullable|in:active,inactive',
            'description' => 'nullable|string',
            'taste'       => 'nullable|string',
            'acidity'     => 'nullable|numeric|min:0|max:10',
            'body'        => 'nullable|numeric|min:0|max:10',
            'roast'       => 'nullable|string|max:255',
            'ingredient'  => 'nullable|string',
            'bg_color'    => 'nullable|string|max:20',
            'text_color'  => 'nullable|string|max:20',
            'map_opacity' => 'nullable|numeric|min:0|max:1',
            'map_size'    => 'nullable|integer',
            'map_top'     => 'nullable|integer',
            'map_right'   => 'nullable|integer',
            'sort_order'  => 'nullable|integer',
        ]);
        
        $data['slug'] = Str::slug($data['name']);
        $data['description'] = $this->cleanHtml($data['description']);
        $data['taste'] = $this->cleanHtml($data['taste']);
        $data['ingredient'] = $this->cleanHtml($data['ingredient']);

        // Handle map_image upload
        if ($request->hasFile('map_image')) {
            $file = $request->file('map_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/variants'), $filename);
            $data['map_image'] = 'images/variants/' . $filename;
        }

        // Handle icon_path
        if ($request->hasFile('icon_file')) {
            $file = $request->file('icon_file');
            $filename = time() . '_icon_' . $file->getClientOriginalName();
            $file->move(public_path('images/variants'), $filename);
            $data['icon_path'] = 'images/variants/' . $filename;
        } elseif ($request->filled('icon_path_asset')) {
            $data['icon_path'] = $request->input('icon_path_asset');
        }
        
        $variant->update($data);
        return redirect()->back()->with('success', 'Varian berhasil diperbarui.');
    }

    private function cleanHtml($html)
    {
        if (empty($html)) return $html;
        // Strip background-color and color styles from all tags
        $html = preg_replace('/style="[^"]*background-color:[^;"]*;?[^"]*"|style="[^"]*color:[^;"]*;?[^"]*"/', '', $html);
        // Also cleanup empty spans that might be left over if we only had those styles
        $html = preg_replace('/<span>(.*?)<\/span>/', '$1', $html);
        return $html;
    }

    public function destroy(string $id)
    {
        $variant = Variant::findOrFail($id);
        $variant->delete();
        return redirect()->back()->with('success', 'Varian berhasil dihapus.');
    }
}
