<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Images extends Model{
    protected $table =  'images';
    protected $primaryKey = 'id';
    protected $fillable = ['imageId', 'id', 'path', 'name', 'alt'];
    public $incrementing = false;
    public $timestamps = false;

    public function news(){
        return $this->belongsToMany(News::class, 'news', 'id');
    }

}

?>