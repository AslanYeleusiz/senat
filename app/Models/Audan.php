<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audan extends Model
{
    use HasFactory;
    protected $guarded=[];
    public $timestamps=false;
    public $table='audans';
    protected $casts = [
                'projects_sum_invest_sum' => 'float',
    ];
    
    
    public function projects() {
        return $this->hasMany(Project::class);
    }
    
    public function stages()
    {
        return $this->hasManyThrough(Stage::class, Project::class);
    }
}
