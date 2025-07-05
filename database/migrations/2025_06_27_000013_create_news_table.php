<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void {
        Schema::create('news', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->text('title')->nullable(false);
            $table->text('content')->nullable(false);
            $table->string('author_id', 36)->nullable(false);
            $table->string('thumbnail_id', 36)->nullable(true);
            $table->timestamps();
        });
    }

    // public function down(): void {
    //     Schema::dropIfExists('news');
    // }
}



?>