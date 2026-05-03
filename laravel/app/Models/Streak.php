<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Streak extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'current_streak',
        'longest_streak',
        'last_activity_date'
    ];

    protected $casts = [
        'last_activity_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get or create streak record for user
     */
    public static function getForUser($userId)
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'current_streak' => 0,
                'longest_streak' => 0
            ]
        );
    }

    /**
     * Update streak based on activity completion (works for both daily and monthly)
     */
    public function updateStreakForDate($date)
    {
        $userId = $this->user_id;
        $activityDate = \Carbon\Carbon::parse($date);
        
        // Always recalculate from scratch to ensure accuracy
        $this->recalculate();
    }

    /**
     * Update streak based on daily activity completion
     */
    public function updateDailyActivity($date, $hasCompletedActivity)
    {
        $this->updateStreakForDate($date);
    }

    /**
     * Update streak based on monthly activity completion
     */
    public function updateMonthlyActivity($date, $hasCompletedActivity)
    {
        $this->updateStreakForDate($date);
    }

    /**
     * Recalculate streak from scratch (useful for data correction)
     */
    public function recalculate()
    {
        $userId = $this->user_id;
        
        // Get all dates with daily activities
        $dailyDates = \App\Models\Activity::where('user_id', $userId)
            ->where('completed', true)
            ->orderBy('activity_date')
            ->pluck('activity_date')
            ->map(function($date) {
                return $date->format('Y-m-d');
            })
            ->unique()
            ->toArray();

        // Get all dates with monthly activity completions
        $monthlyDates = \App\Models\MonthlyActivityCompletion::where('user_id', $userId)
            ->where('completed', true)
            ->orderBy('date')
            ->pluck('date')
            ->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('Y-m-d');
            })
            ->unique()
            ->toArray();

        // Merge and sort all dates
        $allDates = array_unique(array_merge($dailyDates, $monthlyDates));
        sort($allDates);

        if (empty($allDates)) {
            $this->current_streak = 0;
            $this->longest_streak = 0;
            $this->last_activity_date = null;
            $this->save();
            return;
        }

        // Calculate streaks
        $currentStreak = 0;
        $longestStreak = 0;
        $tempStreak = 0;
        $prevDate = null;

        foreach ($allDates as $dateStr) {
            $currentDate = \Carbon\Carbon::parse($dateStr);
            
            if ($prevDate === null) {
                $tempStreak = 1;
            } else {
                $prevCarbon = \Carbon\Carbon::parse($prevDate);
                $daysDiff = $prevCarbon->diffInDays($currentDate);
                
                if ($daysDiff === 1) {
                    $tempStreak++;
                } else {
                    $tempStreak = 1;
                }
            }

            if ($tempStreak > $longestStreak) {
                $longestStreak = $tempStreak;
            }

            $prevDate = $dateStr;
        }

        // Calculate current streak (from most recent date backwards)
        $currentStreak = 0;
        $today = \Carbon\Carbon::today();
        $checkDate = $today;

        // Check backwards from today
        for ($i = 0; $i < 365; $i++) {
            $dateStr = $checkDate->format('Y-m-d');
            if (in_array($dateStr, $allDates)) {
                $currentStreak++;
                $checkDate->subDay();
            } else {
                // If today has no activity, check if yesterday had activity
                if ($i === 0) {
                    $checkDate->subDay();
                    continue;
                }
                break;
            }
        }

        $this->current_streak = $currentStreak;
        $this->longest_streak = $longestStreak;
        $this->last_activity_date = !empty($allDates) ? \Carbon\Carbon::parse(end($allDates)) : null;
        $this->save();
    }
}
