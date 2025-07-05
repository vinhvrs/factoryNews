<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Images extends Model{
    protected $table =  'images';
    // protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $fillable = ['id', 'path', 'name', 'alt'];
    public $timestamps = true;

    protected static function booted(){
        static::creating(function ($images){
            if(empty($images->id)){
                $images->id = Uuid::uuid4()->toString();
            }
        });
    }

    public function news(){
        return $this->belongsToMany(News::class, 'news', 'id');
    }

}

?>