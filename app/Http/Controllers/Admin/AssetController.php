<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $path = $request->get('path', 'images');
        $search = $request->get('search');
        $fullPath = public_path($path);
        
        if (!File::exists($fullPath)) {
            $path = 'images';
            $fullPath = public_path($path);
        }

        $directories = [];
        $files = [];

        $items = File::directories($fullPath);
        foreach ($items as $item) {
            $name = basename($item);
            if ($search && stripos($name, $search) === false) continue;
            
            $directories[] = [
                'name' => $name,
                'path' => $path . '/' . basename($item),
            ];
        }

        $items = File::files($fullPath);
        foreach ($items as $item) {
            $filename = $item->getFilename();
            if ($search && stripos($filename, $search) === false) continue;

            $filePath = $path . '/' . $filename;
            $fullFilePath = public_path($filePath);
            $dimensions = null;
            $isImage = in_array(strtolower($item->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
            
            if ($isImage && strtolower($item->getExtension()) !== 'svg') {
                $size = getimagesize($fullFilePath);
                if ($size) {
                    $dimensions = $size[0] . ' x ' . $size[1] . ' px';
                }
            }

            $files[] = [
                'name' => $filename,
                'path' => $filePath,
                'url' => asset($filePath),
                'size' => number_format($item->getSize() / 1024, 2) . ' KB',
                'type' => strtoupper($item->getExtension()),
                'dimensions' => $dimensions,
                'is_image' => $isImage
            ];
        }

        // Pagination
        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        
        $filesCollection = collect($files);
        $paginatedFiles = new \Illuminate\Pagination\LengthAwarePaginator(
            $filesCollection->slice($offset, $perPage)->all(),
            $filesCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $parentPath = null;
        if ($path !== 'images') {
            $parentPath = dirname($path);
        }

        // Get settings
        $settings = \App\Models\Setting::pluck('setting_value', 'setting_key')->toArray();

        $products = \App\Models\Product::orderBy('nama_produk')->get();

        return view('admin.assets.index', [
            'directories' => $directories,
            'files' => $paginatedFiles,
            'path' => $path,
            'search' => $search,
            'parentPath' => $parentPath,
            'settings' => $settings,
            'products' => $products
        ]);
    }

    public function updateSettings(Request $request)
    {
        $data = $request->only(['logo_light', 'logo_dark', 'banner_type', 'banner_value']);
        
        foreach ($data as $key => $value) {
            \App\Models\Setting::where('setting_key', $key)->update(['setting_value' => $value]);
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        $path = $request->input('path');
        $fullPath = public_path($path);

        if (File::exists($fullPath) && strpos($path, 'images') === 0) {
            File::delete($fullPath);
            return redirect()->back()->with('success', 'File berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Gagal menghapus file.');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120',
            'path' => 'required|string',
        ]);

        $path = $request->input('path');
        $file = $request->file('file');
        
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path($path), $filename);

        return redirect()->back()->with('success', 'File berhasil diupload.');
    }

    public function setProductImage(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'path' => 'required|string',
        ]);

        $product = \App\Models\Product::findOrFail($request->product_id);
        $product->gambar_utama = $request->path;
        $product->save();

        return redirect()->back()->with('success', 'Gambar utama produk ' . $product->name . ' berhasil diperbarui.');
    }
}
