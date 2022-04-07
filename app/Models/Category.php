<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function products(){
        // belongsToMany -> only when we have many to many relationship !!!
      return  $this->belongsToMany(Product::class);
    }
}
