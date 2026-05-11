<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $collections = \App\Models\Collection::with(['brand', 'types.variants'])
            ->when($search, function($query) use ($search) {
                $query->where('collection_name', 'like', "%{$search}%");
            })->latest()->get();
        
        $brands = \App\Models\Brand::all();
        return view('admin.collections.index', compact('collections', 'brands', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'brand_id' => 'nullable|exists:master_merek,id',
            'status' => 'required|in:active,inactive',
        ]);
        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        
        \App\Models\Collection::create($data);
        return redirect()->back()->with('success', 'Koleksi berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $collection = \App\Models\Collection::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'brand_id' => 'nullable|exists:master_merek,id',
            'status' => 'required|in:active,inactive',
        ]);
        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        
        $collection->update($data);
        return redirect()->back()->with('success', 'Koleksi berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $collection = \App\Models\Collection::findOrFail($id);
        $collection->delete();
        return redirect()->back()->with('success', 'Koleksi berhasil dihapus.');
    }
    public function toggleStatus(string $id)
    {
        $collection = \App\Models\Collection::findOrFail($id);
        $collection->status = $collection->status === 'active' ? 'inactive' : 'active';
        $collection->save();
        return redirect()->back()->with('success', 'Status koleksi berhasil diubah menjadi ' . $collection->status . '.');
    }
}
