<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('like_counts', function (Blueprint $table) {
            $table->id();
            $table->integer('likeCount')->default(0);
            $table->timestamps();
        });
        
        DB::table('like_counts')->insert(
            array(
                'id' => '1',
                'likeCount' => '0',
                'created_at' => NULL, 
                'updated_at' => NULL
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('like_counts');
    }
};
