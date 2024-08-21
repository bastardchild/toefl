<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    public $timestamps = false;

    protected $fillable = [
        'name', 'role_id', 'username', 'password', 'exam_token', 'exam_status'
    ];
}
