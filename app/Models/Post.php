<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    const FILE_PATH = 'files/thumbnail/';

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'body',
        'thumbnail'
    ];

    protected $appends = [
        'creator',
        'comments'
    ];

    public function creator(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function getCreatorAttribute(){
        return $this->creator()->first('name','id');
    }

    public function comments(){
        return $this->hasMany(Comments::class,'post_id');
    }

    public function getCommentsAttribute(){
        return $this->comments()->get('message');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id');
    }

    public function getLikesAttribute(){
        return $this->likes()->get('message');
    }

    public function getThumbnailAttribute(){
        if(!$this->attributes['thumbnail']) return '';
        $url = Storage::url(self::FILE_PATH.$this->attributes['thumbnail']);
        return $url;
    }

    public function deleteThumbnail(){
        $image = self::FILE_PATH.$this->attributes['thumbnail'];
        if(Storage::exists($image)){
            if(Storage::delete($image)){
                $this->update(['thumbnail' => '']);
                return true;
            }
        }
    }
}