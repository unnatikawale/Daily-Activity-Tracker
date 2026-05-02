<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'activity_date',
        'completed',
        'user_id'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'activity_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
