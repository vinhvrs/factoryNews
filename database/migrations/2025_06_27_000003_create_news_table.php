<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void {
        Schema::create('news', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->text('title')->nullable(false);
            $table->dateTime('create_at')->nullable(false);
            $table->text('content')->nullable(false);
            $table->string('author_id')->nullable(false);
            $table->string('thumbnail_id')->nullable(true);
        });
    }

    // public function down(): void {
    //     Schema::dropIfExists('news');
    // }
}



?>