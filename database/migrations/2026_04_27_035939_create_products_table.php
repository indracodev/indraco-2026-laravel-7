<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merek_id')->nullable()->constrained('master_merek')->nullOnDelete();
            $table->foreignId('kategori_id')->nullable()->constrained('master_kategori')->nullOnDelete();
            $table->foreignId('collection_id')->nullable()->constrained('master_collection')->nullOnDelete();
            $table->foreignId('type_id')->nullable()->constrained('master_type')->nullOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('master_variant')->nullOnDelete();
            $table->string('nama_produk', 150);
            $table->string('slug', 255)->unique();
            $table->string('sku', 100)->nullable();
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->string('packing_type', 100)->nullable();
            $table->string('inner_packaging', 100)->nullable();
            $table->decimal('regular_price', 15, 2)->default(0);
            $table->string('main_image', 255)->nullable();
            $table->json('gallery_images')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('link_shopee', 255)->nullable();
            $table->string('link_web', 255)->nullable();
            $table->string('link_tokopedia', 255)->nullable();
            $table->string('link_lazada', 255)->nullable();
            $table->string('link_tiktok', 255)->nullable();
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_produk');
    }
}
