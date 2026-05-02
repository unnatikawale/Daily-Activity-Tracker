<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'month',
        'user_id'
    ];

    protected $casts = [
        'month' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
