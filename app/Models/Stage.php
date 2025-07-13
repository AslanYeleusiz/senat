<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Date;

class Stage extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    public $table = 'stages';
    protected $appends = ['colorStatus', 'textDate'];

    public function getColorStatusAttribute()
    {
        // Учитываем только записи со статусом 0

        $now = Carbon::now()->startOfDay();
        $date = Carbon::parse($this->date)->startOfDay();

        $daysDiff = $now->diffInDays($date, false);

        if ($this->status !== 0) {
            return [
                'status' => $this->status,
                'daysPassed' => $daysDiff
            ];
        }
        if ($daysDiff < 0) {
            // дата в прошлом
            return [
                'status' => 3,
                'daysPassed' => $daysDiff
            ];
        } elseif ($daysDiff <= 3) {
            // до даты осталось ≤ 3 дней
            return [
                'status' => 2,
                'daysPassed' => $daysDiff
            ];
        } else {
            // до даты > 3 дней
            return [
                'status' => 1,
                'daysPassed' => $daysDiff
            ];
        }
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function getTextDateAttribute()
    {
        return Date::dmYKZ($this->date);
    }
}
