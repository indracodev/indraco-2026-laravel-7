<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Traits\HasLogAktivitas;

class Brand extends Model
{
    use HasLogAktivitas;

    protected $table = 'master_merek';
    protected $guarded = [];

    /**
     * Return localized name: use deskripsi_eng when locale is English.
     */
    public function getNameAttribute()
    {
        return app()->getLocale() === 'en' && !empty($this->nama_merek_eng)
            ? $this->nama_merek_eng
            : $this->nama_merek;
    }

    /**
     * Return localized description: use deskripsi_eng when locale is English.
     */
    public function getDescAttribute()
    {
        return app()->getLocale() === 'en' && !empty($this->deskripsi_eng)
            ? $this->deskripsi_eng
            : $this->deskripsi;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
}
