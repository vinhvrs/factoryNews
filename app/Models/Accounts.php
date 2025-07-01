<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
class Accounts extends Model{
    protected $table = 'accounts';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $fillable = ['username', 'password', 'role', 'email', 'name'];
    public $timestamps = false;


    protected static function booted(){
        static::creating(function ($account){
            if(empty($account->id)){
                $account->id = Uuid::uuid4()->toString();
            }
        });
    }

    public function news(){
        return $this->hasMany(News::class, 'author_id', 'id');
    }

}
?>