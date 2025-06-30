<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
class Accounts extends Model{
    protected $table = 'accounts';
    protected $primaryKey = 'uid';
    protected $keyType = 'string';
    protected $fillable = ['username', 'password', 'role', 'email', 'name'];
    public $timestamps = false;


    protected static function booted(){
        static::creating(function ($account){
            if(empty($account->uid)){
                $account->uid = Uuid::uuid4()->toString();
            }
        });
    }

    public function news(){
        return $this->hasMany(News::class, 'uid', 'uid');
    }

}
?>