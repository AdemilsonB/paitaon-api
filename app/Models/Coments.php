<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coments extends Model
{
    use HasFactory;

    protected $fillable = [
        "message"
    ];

    protected $appends = [
        "creator",

    ];

    public function creator(){
        return $this->belongsTo(User::class,"user_id");
    }

    public function getCreatorAttribute(){
        return $this->creator()->first("name","id");
    }


    public function post(){
        return $this->belongsTo(Post::class,"post_id");
    }

    public function getPostAttribute(){
        return $this->post()->first("title","id");
    }





}
