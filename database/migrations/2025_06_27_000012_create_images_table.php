<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void{
        Schema::create('images', function (Blueprint $table){
            $table->string('id', 36)->primary();
            $table->string('path', 255)->nullable(false);
            $table->string('name', 255)->nullable(false);
            $table->string('alt', 255)->nullable();
            $table->timestamps();
        });
    }

    // public function down(): void{
    //     Schema::dropIfExists('images');
    // }
}

?>