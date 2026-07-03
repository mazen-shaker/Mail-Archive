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
        Schema::create('mails', function (Blueprint $table) {
            $table->id();
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_type');
            $table->string('file_path');
            $table->boolean('tracing')->default(false);
            $table->string('writed_by');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained();
            $table->foreignId('entity_id')->constrained();
            $table->foreignId('mail_status_id')->constrained();
            $table->foreignId('mail_privacy_id')->constrained();

            $table->softDeletes();
            $table->timestamps();
        });
    }  

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mails');
    }
};
