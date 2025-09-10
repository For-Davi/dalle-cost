<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Receipt extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'receipts';

    protected $fillable = [
        'value',
        'period',
        'member_id',
        'date_receipt',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
