<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscordPost extends Model
{
    use HasFactory;

    protected $table = 'discord_posts';

    protected $fillable = [
        'auto_mode',
        'notifical_link',
        'content',
        'webhook',
    ];
}
