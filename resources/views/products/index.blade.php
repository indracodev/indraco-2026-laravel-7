@extends('layouts.app')

@section('title', 'Produk Kami – INDRACO')

@section('content')
    <main id="konten">
        <h1 class="visually-hidden">halaman produk</h1>

        @if ($banners->count() > 0)
            <section>
                <div id="carouselBanner" class="carousel carousel-fade slide" data-bs-ride="carousel" data-bs-theme="light">
                    <div class="carousel-indicators">
                        @foreach ($banners as $index => $banner)
                            <button type="button" data-bs-target="#carouselBanner" data-bs-slide-to="{{ $index }}"
                                class="{{ $index === 0 ? 'active' : '' }}"
                                aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach ($banners as $index => $banner)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <div
                                    class="carousel-caption position-static d-lg-flex align-items-lg-center column-gap-lg-5 justify-content-lg-around">
                                    <img src="{{ asset($banner->image_path) }}" alt="" loading="lazy"
                                        aria-hidden="true" class="carousel-img w-100 h-auto order-lg-2">
                                    <div class="caption-text text-start order-lg-1">
                                        <h2 class="fw-bold fs-1 text-capitalize">{!! app()->getLocale() == 'en' ? $banner->title_en : $banner->title_id !!}</h2>
                                        <hr>
                                        <p class="fs-4 fw-bold mb-4">{!! app()->getLocale() == 'en' ? $banner->subtitle_en : $banner->subtitle_id !!}</p>
                                        <a href="{{ $banner->link }}" target="_blank"
                                            class="btn btn-outline-invert text-capitalize">{{ app()->getLocale() == 'en' ? $banner->button_text_en : $banner->button_text_id }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselBanner"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselBanner"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </section>
        @endif
        <hr class="m-0">

        <div class="container">
            <section class="py-5 text-center" aria-labelledby="brands">
                <div class="py-lg-5">
                    <h2 id="brands" class="fs-3 fw-bold text-capitalize mb-5" data-i18n="product_brands_title">
                        {{ __('product_brands_title') }}</h2>

                    <div class="daftar-kategori-produk text-start text-capitalize row row-cols-1 row-gap-5">
                        <div class="col">
                            <h3 class="fs-4 fw-bold mb-4" data-i18n="coffee">{{ __('coffee') }}</h3>
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-3 g-sm-4 g-md-5">
                                <div class="col">
                                    <a href="{{ route('products.show', 'supresso') }}"
                                        class="text-reset text-decoration-none opacity-100">
                                        <article class="ratio ratio-16x9 card bg-light-subtle p-4 rounded-4 border-0">
                                            <img src="{{ asset('images/logo-supresso.png') }}"
                                                data-light="{{ asset('images/logo-supresso.png') }}"
                                                data-dark="{{ asset('images/logo-supresso.png') }}"
                                                class="theme-image object-fit-contain w-50 h-50 top-50 start-50 translate-middle"
                                                alt="" loading="lazy" aria-hidden="true">
                                        </article>
                                    </a>
                                </div>
                                <div class="col">
                                    <a href="{{ route('products.show', 'balicafe') }}"
                                        class="text-reset text-decoration-none opacity-100">
                                        <article class="ratio ratio-16x9 card bg-light-subtle p-4 rounded-4 border-0">
                                            <img src="{{ asset('images/logo-balicafe.png') }}"
                                                data-light="{{ asset('images/logo-balicafe.png') }}"
                                                data-dark="{{ asset('images/logo-balicafe.png') }}"
                                                class="theme-image object-fit-contain w-50 h-50 top-50 start-50 translate-middle"
                                                alt="" loading="lazy" aria-hidden="true">
                                        </article>
                                    </a>
                                </div>
                                <div class="col">
                                    <a href="{{ route('products.show', 'ucafe') }}"
                                        class="text-reset text-decoration-none opacity-100">
                                        <article class="ratio ratio-16x9 card bg-light-subtle p-4 rounded-4 border-0">
                                            <img src="{{ asset('images/logo-ucafe-invert.png') }}"
                                                data-light="{{ asset('images/logo-ucafe.png') }}"
                                                data-dark="{{ asset('images/logo-ucafe-invert.png') }}"
                                                class="theme-image object-fit-contain w-50 h-50 top-50 start-50 translate-middle"
                                                alt="" loading="lazy" aria-hidden="true">
                                        </article>
                                    </a>
                                </div>
                                <div class="col">
                                    <a href="{{ route('products.show', 'rasa-sayang') }}"
                                        class="text-reset text-decoration-none opacity-100">
                                        <article class="ratio ratio-16x9 card bg-light-subtle p-4 rounded-4 border-0">
                                            <img src="{{ asset('images/logo-rasa-sayang.png') }}"
                                                data-light="{{ asset('images/logo-rasa-sayang.png') }}"
                                                data-dark="{{ asset('images/logo-rasa-sayang.png') }}"
                                                class="theme-image object-fit-contain w-50 h-50 top-50 start-50 translate-middle"
                                                alt="" loading="lazy" aria-hidden="true">
                                        </article>
                                    </a>
                                </div>
                                <div class="col">
                                    <a href="{{ route('products.show', 'tugu-buaya') }}"
                                        class="text-reset text-decoration-none opacity-100">
                                        <article class="ratio ratio-16x9 card bg-light-subtle p-4 rounded-4 border-0">
                                            <img src="{{ asset('images/logo-tugu-buaya-invert.png') }}"
                                                data-light="{{ asset('images/logo-tugu-buaya.png') }}"
                                                data-dark="{{ asset('images/logo-tugu-buaya-invert.png') }}"
                                                class="theme-image object-fit-contain w-50 h-50 top-50 start-50 translate-middle"
                                                alt="" loading="lazy" aria-hidden="true">
                                        </article>
                                    </a>
                                </div>
                                <div class="col">
                                    <a href="{{ route('products.show', 'uang-emas') }}"
                                        class="text-reset text-decoration-none opacity-100">
                                        <article class="ratio ratio-16x9 card bg-light-subtle p-4 rounded-4 border-0">
                                            <img src="{{ asset('images/logo-uang-emas-invert.png') }}"
                                                data-light="{{ asset('images/logo-uang-emas.png') }}"
                                                data-dark="{{ asset('images/logo-uang-emas-invert.png') }}"
                                                class="theme-image object-fit-contain w-50 h-50 top-50 start-50 translate-middle"
                                                alt="" loading="lazy" aria-hidden="true">
                                        </article>
                                    </a>
                                </div>
                                <div class="col">
                                    <a href="{{ route('products.show', 'hao-cafe') }}"
                                        class="text-reset text-decoration-none opacity-100">
                                        <article class="ratio ratio-16x9 card bg-light-subtle p-4 rounded-4 border-0">
                                            <img src="{{ asset('images/logo-hao-cafe.png') }}"
                                                data-light="{{ asset('images/logo-hao-cafe.png') }}"
                                                data-dark="{{ asset('images/logo-hao-cafe.png') }}"
                                                class="theme-image object-fit-contain w-50 h-50 top-50 start-50 translate-middle"
                                                alt="" loading="lazy" aria-hidden="true">
                                        </article>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <h3 class="fs-4 fw-bold mb-4 d-none d-xl-block">
                                <span data-i18n="ginger">{{ __('ginger') }}</span> | <span
                                    data-i18n="choconutmilk">{{ __('choconutmilk') }}</span> |
                                <span data-i18n="chocolate">{{ __('chocolate') }}</span>
                            </h3>
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-3 g-sm-4 g-md-5">
                                <div class="col">
                                    <h3 class="fs-4 fw-bold mb-4 d-xl-none" data-i18n="ginger">{{ __('ginger') }}</h3>
                                    <a href="{{ route('products.show', 'jaheku') }}"
                                        class="text-reset text-decoration-none opacity-100">
                                        <article class="ratio ratio-16x9 card bg-light-subtle p-4 rounded-4 border-0">
                                            <img src="{{ asset('images/logo-jaheku.png') }}"
                                                data-light="{{ asset('images/logo-jaheku.png') }}"
                                                data-dark="{{ asset('images/logo-jaheku.png') }}"
                                                class="theme-image object-fit-contain w-50 h-50 top-50 start-50 translate-middle"
                                                alt="" loading="lazy" aria-hidden="true">
                                        </article>
                                    </a>
                                </div>
                                <div class="col">
                                    <h3 class="fs-4 fw-bold mb-4 d-xl-none" data-i18n="choconutmilk">
                                        {{ __('choconutmilk') }}
                                    </h3>
                                    <a href="{{ route('products.show', 'intirasa') }}"
                                        class="text-reset text-decoration-none opacity-100">
                                        <article class="ratio ratio-16x9 card bg-light-subtle p-4 rounded-4 border-0">
                                            <img src="{{ asset('images/logo-intirasa.png') }}"
                                                data-light="{{ asset('images/logo-intirasa.png') }}"
                                                data-dark="{{ asset('images/logo-intirasa.png') }}"
                                                class="theme-image object-fit-contain w-50 h-50 top-50 start-50 translate-middle"
                                                alt="" loading="lazy" aria-hidden="true">
                                        </article>
                                    </a>
                                </div>
                                <div class="col">
                                    <h3 class="fs-4 fw-bold mb-4 d-xl-none" data-i18n="chocolate">{{ __('chocolate') }}
                                    </h3>
                                    <a href="{{ route('products.show', 'brochoco') }}"
                                        class="text-reset text-decoration-none opacity-100">
                                        <article class="ratio ratio-16x9 card bg-light-subtle p-4 rounded-4 border-0">
                                            <img src="{{ asset('images/logo-brochoco.png') }}"
                                                data-light="{{ asset('images/logo-brochoco.png') }}"
                                                data-dark="{{ asset('images/logo-brochoco.png') }}"
                                                class="theme-image object-fit-contain w-50 h-50 top-50 start-50 translate-middle"
                                                alt="" loading="lazy" aria-hidden="true">
                                        </article>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <hr class="m-0">

        <section class="py-5 text-bg-primary text-center" aria-labelledby="marketplace">
            <div class="container py-lg-5">
                <h2 id="marketplace" class="fs-3 fw-bold text-capitalize mb-5" data-i18n="product_order_title">
                    {{ __('product_order_title') }}</h2>
                <div class="mb-5">
                    <p class="small">Website INDRACO Store</p>
                    <a href="https://indracostore.com/" target="_blank" class="text-reset text-decoration-none">
                        <img src="{{ asset('images/logo-indracostore-invert.png') }}" alt="Logo INDRACO Store"
                            loading="lazy" class="w-100 h-auto" style="max-width: 18rem;">
                    </a>
                </div>
                <div>
                    <p class="small" data-i18n="product_available_at">{{ __('product_available_at') }} :</p>
                    <nav aria-label="online store" class="d-flex flex-wrap justify-content-center align-items-center"
                        style="gap: 3rem 5rem;">
                        <a href="https://www.tokopedia.com/indracoofficial" target="_blank"
                            class="text-reset text-decoration-none">
                            <img src="{{ asset('images/logo-tokopedia.png') }}" alt="Logo Tokopedia" loading="lazy"
                                class="w-100 h-auto" style="max-width: 10rem;">
                        </a>
                        <a href="https://shopee.co.id/indracoofficial" target="_blank"
                            class="text-reset text-decoration-none">
                            <img src="{{ asset('images/logo-shopee.png') }}" alt="Logo Shopee" loading="lazy"
                                class="w-100 h-auto" style="max-width: 3rem;">
                        </a>
                        <a href="https://www.blibli.com/merchant/indraco/INT-60044" target="_blank"
                            class="text-reset text-decoration-none">
                            <img src="{{ asset('images/logo-blibli.png') }}" alt="Logo Blibli" loading="lazy"
                                class="w-100 h-auto" style="max-width: 8rem;">
                        </a>
                        <a href="https://www.lazada.co.id/shop/indraco/" target="_blank"
                            class="text-reset text-decoration-none">
                            <img src="{{ asset('images/logo-lazada.png') }}" alt="Logo Lazada" loading="lazy"
                                class="w-100 h-auto" style="max-width: 4rem;">
                        </a>
                        <a href="https://www.tiktok.com/@indracostore" target="_blank"
                            class="text-reset text-decoration-none">
                            <img src="{{ asset('images/logo-tiktokshop.png') }}" alt="Logo TikTok Shop" loading="lazy"
                                class="w-100 h-auto" style="max-width: 10rem;">
                        </a>
                    </nav>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('styles')
    <style>
        #carouselBanner,
        #carouselBanner .carousel-item {
            background-color: #1a1a1a;
        }

        #carouselBanner .carousel-caption {
            padding: 0 15% 15% 15%;
        }

        #carouselBanner .carousel-img {
            aspect-ratio: 1/1;
            object-fit: contain;
        }

        @media (min-width: 992px) {
            #carouselBanner .carousel-caption {
                padding: 2% 15%;
            }

            #carouselBanner .carousel-caption>* {
                max-width: 26rem;
            }
        }
    </style>
@endsection
