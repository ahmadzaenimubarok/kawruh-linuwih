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
        Schema::create('student_project_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_project_id')->constrained()->onDelete('cascade');
            $table->foreignId('stage_id')->constrained('project_stages')->onDelete('cascade');
            $table->enum('status', ['not_started', 'in_progress', 'submitted', 'reviewed', 'completed'])->default('not_started');
            $table->string('submission_link')->nullable();
            $table->text('feedback')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate stage enrollments
            $table->unique(['student_project_id', 'stage_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_project_stages');
    }
};
