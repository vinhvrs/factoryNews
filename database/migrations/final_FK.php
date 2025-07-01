<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void {
        Schema::table('news', function(Blueprint $table) {
            $table->foreign('author_id')
                  ->references('id')
                  ->on('accounts')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('thumbnail_id')
                    ->references('id')
                    ->on('images')
                    ->onDelete('set null')
                    ->onUpdate('cascade');
        });
    }
}

?>