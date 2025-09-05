<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'finances';

    protected $fillable = [
        'period',
        'value',
    ];

}
