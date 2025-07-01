<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void{
        Schema::create('images', function (Blueprint $table){
            $table->string('id')->primary();
            $table->string('path')->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('alt')->nullable();
        });
    }

    // public function down(): void{
    //     Schema::dropIfExists('images');
    // }
}

?>