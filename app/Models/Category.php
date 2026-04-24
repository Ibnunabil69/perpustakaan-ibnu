<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'color'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
