<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Images extends Model{
    protected $table =  'images';
    protected $primaryKey = 'imageId';
    protected $foreignKey = 'newsId';
    protected $fillable = ['imageId', 'newsId', 'imagePath', 'imageName', 'imageAlt'];
    public $incrementing = false;
    public $timestamps = false;

    public function news(){
        return $this->belongsTo(News::class, 'newsId', 'newsId');
    }

}

?>