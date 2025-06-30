<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void {
        Schema::create('news', function (Blueprint $table) {
            $table->string('newsId')->primary();
            $table->text('title')->nullable(false);
            $table->date('date')->nullable(false);
            $table->text('content')->nullable(false);
            $table->string('author')->nullable(false);
            $table->string('uid')->nullable(false);
            $table->foreign('uid')
                  ->references('uid')
                  ->on('accounts')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            
        });
    }

    public function down(): void {
        Schema::dropIfExists('news');
    }
}



?>