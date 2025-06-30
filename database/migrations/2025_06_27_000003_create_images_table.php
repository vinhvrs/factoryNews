<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void{
        Schema::create('images', function (Blueprint $table){
            $table->string('imageId')->primary();
            $table->string('imagePath')->nullable(false);
            $table->string('imageName')->nullable(false);
            $table->string('imageAlt')->nullable();
            $table->string('newsId')->nullable(false);
            $table->foreign('newsId')
                  ->references('newsId')
                  ->on('news')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void{
        Schema::dropIfExists('images');
    }
}

?>