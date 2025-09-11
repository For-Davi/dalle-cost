<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Movement extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'movements';

    protected $fillable = [
        'value',
        'period',
        'member_id',
        'origin_id',
        'category_id',
        'date_buy',
        'description',
        'installment',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function origin()
    {
        return $this->belongsTo(Origin::class, 'origin_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
