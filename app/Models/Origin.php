<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Origin extends Model
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
