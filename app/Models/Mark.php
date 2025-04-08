<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'score',
        'user_id',
        'class_name', // ✅ Add this to allow mass assignment
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
