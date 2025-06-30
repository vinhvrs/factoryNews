<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Accounts;

class News extends Model{
    protected $table = 'news';
    protected $primaryKey = 'newsId';
    protected $fillable = ['newsId', 'title', 'date', 'content', 'author', 'uid'];
    public $incrementing = false;
    public $timestamps = false;

    public function images(){
        return $this->hasMany(Images::class, 'newsId', 'newsId');
    }

    public function account(){
        return $this->belongsTo(Accounts::class, 'uid', 'uid');
    }
}
?>