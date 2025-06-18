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
        Schema::create('friendships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade'); // Người dùng gửi yêu cầu kết bạn
            $table->foreignId('friend_id')->references('id')->on('users')->onDelete('cascade'); // Người bạn nhận yêu cầu kết bạn
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending'); // Trạng thái kết bạn
            $table->timestamps(); // created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friendships');
    }
};
