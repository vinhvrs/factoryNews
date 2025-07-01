<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Accounts;

class News extends Model{
    protected $table = 'news';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'title', 'create_at', 'content', 'author_id', 'thumbnail_id'];
    public $incrementing = false;
    public $timestamps = false;

    public function images(){
        return $this->hasMany(Images::class, 'thumbnail_id', 'id');
    }

    public function account(){
        return $this->belongsTo(Accounts::class, 'author_id', 'id');
    }
}
?>