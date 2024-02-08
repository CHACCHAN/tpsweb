<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscordMedia extends Model
{
    use HasFactory;

    protected $table = 'discord_medias';

    protected $fillable = [
        'auto_mode',
        'notifical_link',
        'paste_image',
        'content',
        'webhook',
    ];
}
