<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;

    // 'username',
    // 'email',
    // 'password_hash',
    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'phone_number',
        'address',
        'user_id',
    ];


    // Customer → User: Many customers belong to one user (M:1)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
