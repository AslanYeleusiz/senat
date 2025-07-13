<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $guarded=[];
    public $table='projects';
    const FILE_PATH = 'files/';
    const IMAGES_PATH = 'images/';
    const PDF_PATH = 'pdf/';
    protected $casts = [
        'images' => 'array', // или 'json', результат один
                'invest_sum' => 'float',
    ];
    public function audan() {
        return $this->belongsTo(Audan::class, 'audan_id');
    }
    public function projectType() {
        return $this->belongsTo(ProjectType::class, 'project_type');
    }
    
    public function stages() {
        return $this->hasMany(Stage::class);
    }
    
    public function questions() {
        return $this->hasMany(Question::class);
    }
    
    public function curator() {
        return $this->belongsTo(User::class, 'curator_id');
    }
    
    
    
}
