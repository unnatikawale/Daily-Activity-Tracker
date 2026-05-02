<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyActivityCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'monthly_activity_id',
        'user_id',
        'date',
        'completed',
    ];

    protected $casts = [
        'date' => 'date',
        'completed' => 'boolean',
    ];

    public function monthlyActivity()
    {
        return $this->belongsTo(MonthlyActivity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
