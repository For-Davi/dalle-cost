<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Origin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'origins';

    protected $fillable = [
        'name',
        'payday',
        'member_id'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

}
