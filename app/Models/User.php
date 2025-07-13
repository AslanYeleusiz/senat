<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    protected $guarded=[];
    public $timestamps=false;
    public $table='users';
    const FILE_PATH = 'autobiographys/';
    const AVATAR_PATH = 'avatars/';
    const CERTIFICATE_PATH = 'certificates/';
    const document_PATH = 'documents/';
    protected $hidden = [
        'password',
        'real_password',
    ];
    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function audan(){
        return $this->belongsTo(Audan::class, 'audan_id');
    }
    
}
