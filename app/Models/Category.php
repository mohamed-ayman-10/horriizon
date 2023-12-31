<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, hasTranslations;

    public $translatable = ['title'];

    public function products() {
        return $this->hasMany(Product::class);
    }
}
