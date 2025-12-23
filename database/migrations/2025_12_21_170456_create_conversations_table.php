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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            // who created the conversation
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('user_id1')->constrained('users');
            $table->foreignId('user_id2')->constrained('users');

            // for 1-to-1 chat
            $table->foreignId('receiver_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // for group chat
            $table->foreignId('group_id')
                ->nullable()
                ->constrained('groups')
                ->nullOnDelete();

            // do NOT add FK for last_message yet
            $table->unsignedBigInteger('last_message_id')->nullable();

            $table->timestamps();
        });


        // Schema::Create('groups',function(Blueprint $table){
        //     $table->foreignId('last_message_id')->nullable()->constrained('messages');
        // });

        // Schema::Create('conversations',function(Blueprint $table){
        //     $table->foreignId('last_message_id')->nullable()->constrained('messages');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
