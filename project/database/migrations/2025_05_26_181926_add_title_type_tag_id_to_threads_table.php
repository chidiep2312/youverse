<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('threads', function (Blueprint $table) {
            $table->string('title')->nullable();
             $table->enum('type',['status','topic'])->default('status');
             $table->foreignId('tag_id')->nullable()->constrained('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('threads', function (Blueprint $table) {
             $table->dropColumn('title');
              $table->dropColumn('type');
              $table->dropColumn('tag_id');
            
        });
    }
};