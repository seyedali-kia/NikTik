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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            
            // اگر تسک شخصی باشد -> project_id = null
            // اگر تسک پروژه‌ای باشد -> project_id = id پروژه
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');

            // کی این تسک رو ساخته؟
            $table->foreignId('created_by_id')->constrained('users');

            // یوزری که "انجام‌دهنده" تسک است:
            // همیشه خالی است تا زمانی که کسی آنرا انجام دهد 
            // شاید برای تسک های شخصی لازم نباشد ولی برای تسک های گروهی قطعا لازم است
            $table->foreignId('completed_by_id')->nullable()->constrained('users');

            $table->string('title');
            $table->text('description')->nullable();

            // Estimation بر اساس فیبوناچی - نوعش عدد صحیح کوچک
            $table->unsignedSmallInteger('estimation')->nullable(); // برای تسک شخصی می‌تونه حتی null باشه اگر خواستی

            // وضعیت تسک
            $table->enum('status', ['todo', 'doing', 'done'])->default('todo');
            $table->timestamp('done_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
