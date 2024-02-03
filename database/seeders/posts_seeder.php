<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;

class posts_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::create([
            'title' => 'サッサの夏休み',
            'content' => '<p>あうあうあう</p>',
            'user_id' => 1,
            'category_id' => 1,
        ]);
    }
}
