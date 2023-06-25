<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "body",
        "thumbnail"
    ];

    protected $appends = [
        'creator',
        'coments'
    ];

    public function creator(){
        return $this->belongsTo(User::class,"user_id");
    }

    public function getCreatorAttribute(){
        return $this->creator()->first("name","id");
    }


    public function coments(){
        return $this->hasMany(Coments::class,"post_id");
    }

    public function getComentsAttribute(){
        return $this->coments()->get("message");
    }


}
