<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'content',
        'make_by',
        'category'
    ];

    public function post_images()
    {
        return $this->belongsTo('App\Models\PostImage');
    }
}