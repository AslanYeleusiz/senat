<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    protected $guarded=[];
    public $timestamps=false;
    public $table='applications';
    const FILE_PATH = 'autobiographys/';
    const CERTIFICATE_PATH = 'certificates/';
    const DOCUMENT_PATH = 'documents/';
    
    public function project(){
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function sendUser(){
        return $this->belongsTo(User::class, 'send_to');
    }
    
}
