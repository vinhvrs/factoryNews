<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void{
        Schema::create('accounts', function (Blueprint $table){
            $table->string('id', 36)->primary();
            $table->string('username')->unique()->nullable(false);
            $table->string('password')->nullable(false);
            $table->string('role')->default('reader');
            $table->string('email')->unique()->nullable(false);
            $table->string('name')->nullable();
        });
    }

    // public function down(): void{
    //     Schema::dropIfExists('accounts');
    // }
}


?>