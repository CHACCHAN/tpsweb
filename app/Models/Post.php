<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'public',
        'category_id',
    ];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $date = Carbon::parse($value);

                if ($date->diffInMinutes(Carbon::now()) < 60) {
                    return $date->diffForHumans(); 
                } else if ($date->diffInHours(Carbon::now()) < 24) {
                    return $date->diffForHumans();
                } else if ($date->diffInDays(Carbon::now()) < 31) {
                    return $date->diffForHumans(); 
                } else {
                    return $date->format('Y年m月d日');
                }
            }
        );
    }

    protected function updatedAt(): Attribute 
    {
        return Attribute::make(
            get: function ($value) {
                $date = Carbon::parse($value);

                if ($date->diffInMinutes(Carbon::now()) < 60) {
                    return $date->diffForHumans(); 
                } else if ($date->diffInHours(Carbon::now()) < 24) {
                    return $date->diffForHumans();
                } else if ($date->diffInDays(Carbon::now()) < 31) {
                    return $date->diffForHumans(); 
                } else {
                    return $date->format('Y年m月d日');
                }
            }
        );
    }

    public function post_images()
    {
        return $this->belongsTo('App\Models\PostImage');
    }
}
