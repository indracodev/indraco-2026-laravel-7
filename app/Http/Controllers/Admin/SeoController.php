<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Brand;
use App\Models\News;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SeoController extends Controller
{
    /**
     * Field SEO yang tersedia untuk setiap halaman.
     */
    protected array $fields = [
        'title'          => 'Meta Title',
        'description'    => 'Meta Description',
        'keywords'       => 'Meta Keywords',
        'og_title'       => 'OG Title',
        'og_description' => 'OG Description',
        'og_image'       => 'OG Image URL',
        'canonical'      => 'Canonical URL',
    ];

    public function index()
    {
        // 1. Static Pages
        $pagesWithPaths = [
            'home'        => ['label' => 'Home Page',          'path' => '/',           'type' => 'page'],
            'about'       => ['label' => 'About Us',           'path' => '/about',       'type' => 'page'],
            'products'    => ['label' => 'Products Listing',   'path' => '/products',    'type' => 'page'],
            'news'        => ['label' => 'News Listing',       'path' => '/news',        'type' => 'page'],
            'businesses'  => ['label' => 'Our Businesses',     'path' => '/businesses',  'type' => 'page'],
            'stores'      => ['label' => 'Our Stores',         'path' => '/stores',      'type' => 'page'],
            'career'      => ['label' => 'Career',             'path' => '/career',      'type' => 'page'],
            'contact'     => ['label' => 'Contact Us',         'path' => '/contact',     'type' => 'page'],
            'equipment'   => ['label' => 'Equipment',          'path' => '/equipment',   'type' => 'page'],
            'foodservice' => ['label' => 'Foodservice',        'path' => '/foodservice', 'type' => 'page'],
            'download'    => ['label' => 'Download Center',    'path' => '/download',    'type' => 'page'],
            'privacy'     => ['label' => 'Privacy Policy',     'path' => '/privacy',     'type' => 'page'],
            'terms'       => ['label' => 'Terms & Conditions', 'path' => '/terms',       'type' => 'page'],
        ];

        // 2. Fetch Brands (Treat as [Product] in UI as per user screenshot)
        $brands = Brand::all();
        foreach ($brands as $b) {
            $pagesWithPaths["product_{$b->id}"] = [
                'label' => "[Product] " . ($b->nama_merek ?: $b->slug),
                'path'  => "/products/{$b->slug}",
                'type'  => 'product',
                'id'    => $b->id
            ];
        }

        // 3. Fetch Dynamic News
        $newsItems = News::all();
        foreach ($newsItems as $n) {
            $pagesWithPaths["news_{$n->id}"] = [
                'label' => "[News] " . ($n->judul ?: $n->slug),
                'path'  => "/news/{$n->slug}",
                'type'  => 'news',
                'id'    => $n->id
            ];
        }

        // Ambil semua setting SEO
        $allSettings = Setting::where('setting_key', 'like', 'seo_%')
            ->pluck('setting_value', 'setting_key')
            ->toArray();

        // Susun data terstruktur
        $seoData = [];
        $tableData = [];

        foreach ($pagesWithPaths as $pageKey => $pageInfo) {
            $pageSeo = [];
            $isConfigured = false;
            $type = $pageInfo['type'];
            
            foreach ($this->fields as $fieldKey => $fieldLabel) {
                $key = "seo_{$type}_{$pageKey}_{$fieldKey}";
                $val = $allSettings[$key] ?? '';
                $pageSeo[$fieldKey] = $val;
                
                if ($fieldKey !== 'keywords' && !empty($val)) {
                    $isConfigured = true;
                }
            }
            
            $seoData[$pageKey] = $pageSeo;
            
            $tableData[] = (object)[
                'key'         => $pageKey,
                'label'       => $pageInfo['label'],
                'path'        => $pageInfo['path'],
                'meta_title'  => $pageSeo['title'],
                'status'      => $isConfigured ? 'Configured' : 'Default',
                'type'        => $type
            ];
        }

        return view('admin.seo.index', [
            'tableData' => $tableData,
            'fields'    => $this->fields,
            'seoData'   => $seoData,
        ]);
    }

    public function update(Request $request, string $page)
    {
        $validated = $request->validate([
            'title'          => 'nullable|string|max:255',
            'description'    => 'nullable|string|max:500',
            'keywords'       => 'nullable|string|max:500',
            'og_title'       => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image'       => 'nullable|string|max:500',
            'canonical'      => 'nullable|url|max:500',
        ]);

        // Determine prefix from the $page key
        $prefix = 'page';
        if (str_starts_with($page, 'product_')) $prefix = 'product';
        elseif (str_starts_with($page, 'news_')) $prefix = 'news';

        foreach ($this->fields as $fieldKey => $fieldLabel) {
            $key = "seo_{$prefix}_{$page}_{$fieldKey}";
            Setting::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => $validated[$fieldKey] ?? '']
            );
        }

        Cache::forget('site_settings');

        return redirect()
            ->route('admin.seo.index')
            ->with('success', "SEO berhasil disimpan.");
    }
}
