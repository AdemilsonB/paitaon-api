<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use SoftDeletes;

    const FILE_PATH = "files/imagePerfil/";

    protected $table = "users";

    protected $fillable = [
        "name",
        "email",
        "password",
        "bio",
        "image_perfil"
    ];

    protected $hidden = [
        "password"
    ];

    public function followers()
    {
        return $this->belongsTo(User::class, 'followers', 'user_id', 'follower_id');
    }

    public function following()
    {
        return $this->belongsTo(User::class, 'followers', 'follower_id', 'user_id');
    }

    public function setPasswordAttribute($pass){
        $this->attributes['password'] = Hash::make($pass);
    }

    public function getImagePerfilAttribute(){
        
        if(!$this->attributes["image_perfil"]) return "";
        $url = Storage::url(self::FILE_PATH.$this->attributes["image_perfil"]);
        return $url;
    }

    public function deleteImage(){
       
        $image = self::FILE_PATH.$this->attributes["image_perfil"];
        if(Storage::exists($image)){
            if(Storage::delete($image)){
                $this->update(["image_perfil" => '']);
                return true;
            }
            return false;
        }
        return true;
    }

     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}