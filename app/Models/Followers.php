<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Followers extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'follower_id'
    ];

    protected $appends = [
        "fallower"
    ];

    protected $hidden = [
        "user_id",
        "follower_id",
        "created_at",
        "updated_at",
        "id"
    ];

    public function getFallowerAttribute(){
        return User::find($this->attributes['user_id']);  
    }

    public function post()
    {
        return $this->hasMany(User::class);
    }
}