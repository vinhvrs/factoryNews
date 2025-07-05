<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use App\Models\Accounts;

class News extends Model{
    protected $table = 'news';
    // protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $fillable = ['id', 'title', 'create_at', 'content', 'author_id', 'thumbnail_id'];
    public $timestamps = true;

    protected static function booted(){
        static::creating(function ($news){
            if(empty($news->id)){
                $news->id = Uuid::uuid4()->toString();
            }
        });
    }

    public function images(){
        return $this->belongsTo(Images::class, 'thumbnail_id', 'id');
    }

    public function account(){
        return $this->belongsTo(Accounts::class, 'author_id', 'id');
    }
}
?>