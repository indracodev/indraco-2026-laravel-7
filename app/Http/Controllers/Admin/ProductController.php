<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $products = \App\Models\Product::with('brand', 'category')
            ->when($search, function($query) use ($search) {
                $query->where('nama_produk', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
            })->latest()->get();
            
        $brands = \App\Models\Brand::all();
        $categories = \App\Models\Category::all();
        return view('admin.products.index', compact('products', 'brands', 'categories', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'brand_id' => 'nullable|exists:master_merek,id',
            'category_id' => 'nullable|exists:master_kategori,id',
            'harga_reguler' => 'nullable|numeric',
            'status' => 'required|in:active,inactive,draft',
        ]);
        $data['slug'] = \Illuminate\Support\Str::slug($data['name']) . '-' . time();
        
        \App\Models\Product::create($data);
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'brand_id' => 'nullable|exists:master_merek,id',
            'category_id' => 'nullable|exists:master_kategori,id',
            'harga_reguler' => 'nullable|numeric',
            'status' => 'required|in:active,inactive,draft',
        ]);
        
        $product->update($data);
        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function toggleStatus(string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $product->status = $product->status === 'active' ? 'inactive' : 'active';
        $product->save();
        
        return redirect()->back()->with('success', 'Status produk berhasil diubah menjadi ' . $product->status . '.');
    }

    public function destroy(string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $product->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}
