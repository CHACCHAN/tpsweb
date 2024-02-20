<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBanList extends Model
{
    use HasFactory;

    protected $table = 'user_ban_lists';

    protected $fillable = [
        'user_id',
        'start_date',
        'last_date',
        'level'
    ];
}
