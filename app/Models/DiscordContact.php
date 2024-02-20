<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscordContact extends Model
{
    use HasFactory;

    protected $table = 'discord_contacts';

    protected $fillable = [
        'auto_mode',
        'webhook',
    ];
}
