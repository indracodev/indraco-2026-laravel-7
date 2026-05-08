@extends('layouts.app')

@section('title', 'Pencarian: ' . $query . ' – INDRACO')

@section('content')
<main id="konten">
    <h1 class="visually-hidden">halaman pencarian produk</h1>

    <div class="container py-5">
        <div class="py-lg-5">
            <header aria-label="header search" class="search-header mb-5">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('products') }}" class="text-decoration-none text-muted">Produk</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pencarian</li>
                    </ol>
                </nav>
                <h2 class="display-4 text-capitalize fw-thin">
                    <span>Hasil Pencarian</span>: <br> <b class="fw-bold">"{{ $query }}"</b>
                </h2>
                <p class="text-muted">{{ $products->count() }} produk ditemukan</p>
            </header>

            <!-- product list -->
            <ol class="list-unstyled mb-0 row product-list row-cols-1 row-gap-5 row-cols-sm-2 row-cols-lg-4 gx-sm-4 gx-lg-5 pt-lg-5">
                @forelse ($products as $prod)
                    @php
                        $img_path = !empty($prod->gambar_utama) ? $prod->gambar_utama : 'images/no-image.png';
                        // Check if link_web is available, otherwise link to detail page if we have one
                        // Based on ProductController, many products use link_web.
                        $target_link = !empty($prod->link_web) ? $prod->link_web : '#';
                    @endphp
                    <li class="product-item col">
                        <a href="{{ $target_link }}" {{ $target_link != '#' ? 'target="_blank"' : '' }} class="text-reset text-decoration-none h-100 d-block">
                            <article class="card border-0 bg-transparent h-100 pointer-hover transition-all">
                                <div class="card-header p-0 bg-transparent border-0 rounded-0">
                                    <div class="card-img ratio ratio-1x1 bg-transparent overflow-hidden rounded-4">
                                        <img src="{{ asset($img_path) }}" alt="{{ $prod->nama_produk }}" loading="lazy" class="object-fit-contain w-100 h-100 drop-shadow p-3">
                                    </div>
                                </div>
                                <div class="card-body text-center d-flex flex-column pt-4">
                                    <h4 class="card-title fs-6 fw-bold text-capitalize mb-2">{{ $prod->nama_produk }}</h4>
                                    @if(!empty($prod->tipe_packing))
                                        <p class="card-text small text-muted">{{ $prod->tipe_packing }} ({{ $prod->inner_kemasan }})</p>
                                    @endif
                                    <div class="mt-auto">
                                        <span class="btn btn-outline-primary btn-sm rounded-pill px-3 mt-2">Lihat Detail</span>
                                    </div>
                                </div>
                            </article>
                        </a>
                    </li>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="bg-light p-5 rounded-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4em" height="4em" viewBox="0 0 24 24" class="text-muted mb-3"><path fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5A6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14z"/></svg>
                            <h3 class="fw-bold">Maaf, produk tidak ditemukan</h3>
                            <p class="text-muted">Coba gunakan kata kunci lain atau periksa ejaan Anda.</p>
                            <a href="{{ route('products') }}" class="btn btn-primary rounded-pill px-4 mt-3">Kembali ke Produk</a>
                        </div>
                    </div>
                @endforelse
            </ol>
        </div>
    </div>
</main>

<style>
.pointer-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.pointer-hover:hover {
    transform: translateY(-10px);
}
.drop-shadow {
    filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));
}
.transition-all {
    transition: all 0.3s ease;
}
</style>
@endsection
