<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = [
        'deleted_at'
    ];
    protected $fillable = [
        'name',
        'description',
    ];

    public function products(){
        // belongsToMany -> only when we have many to many relationship !!!
      return  $this->belongsToMany(Product::class);
    }
}
