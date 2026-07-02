<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $brands = Brand::when($search, function($query) use ($search) {
            $query->where('nama_merek', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        })->latest()->get();

        // Collect existing image assets for the picker (from images/ directory)
        $assetImages = $this->getAssetImages();
        
        return view('admin.brands.index', compact('brands', 'search', 'assetImages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_merek'    => 'required|string|max:100',
            'deskripsi'     => 'nullable|string',
            'deskripsi_eng' => 'nullable|string',
            'logo'          => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'logo_from_asset' => 'nullable|string',
            'status'        => 'required|in:active,inactive',
        ]);

        $data['slug'] = Str::slug($data['nama_merek']);

        // Priority: new upload > select from asset
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

            // Store in images/uploads/brand/ so it shows up in Asset Manager
            $destPath = public_path('images/uploads/brand');
            if (!File::exists($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $data['logo_path'] = 'images/uploads/brand/' . $filename;
        } elseif (!empty($data['logo_from_asset'])) {
            $data['logo_path'] = $data['logo_from_asset'];
        }

        unset($data['logo'], $data['logo_from_asset']);

        Brand::create($data);
        return redirect()->back()->with('success', 'Merek berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $brand = Brand::findOrFail($id);

        $data = $request->validate([
            'nama_merek'    => 'required|string|max:100',
            'deskripsi'     => 'nullable|string',
            'deskripsi_eng' => 'nullable|string',
            'logo'          => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'logo_from_asset' => 'nullable|string',
            'status'        => 'required|in:active,inactive',
        ]);

        $data['slug'] = Str::slug($data['nama_merek']);

        // Priority: new upload > select from asset > keep existing
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

            $destPath = public_path('images/uploads/brand');
            if (!File::exists($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $data['logo_path'] = 'images/uploads/brand/' . $filename;
        } elseif (!empty($data['logo_from_asset'])) {
            $data['logo_path'] = $data['logo_from_asset'];
        }

        unset($data['logo'], $data['logo_from_asset']);

        $brand->update($data);
        return redirect()->back()->with('success', 'Merek berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        return redirect()->back()->with('success', 'Merek berhasil dihapus.');
    }

    /**
     * Scan images/ directory recursively for image files (used by asset picker).
     */
    private function getAssetImages(): array
    {
        $basePath = public_path('images');
        $images = [];
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

        if (!File::exists($basePath)) {
            return $images;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($basePath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), $extensions)) {
                $relativePath = 'images/' . str_replace('\\', '/', $iterator->getSubPathname());
                $images[] = [
                    'path' => $relativePath,
                    'url'  => asset($relativePath),
                    'name' => $file->getFilename(),
                ];
            }
        }

        // Sort by name
        usort($images, function($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });

        return $images;
    }
}
