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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->integer('user_id')->nullable();
            $table->text('abstract')->nullable();
            $table->text('doi')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('type_of_paper_id')->nullable();
            $table->date('publication_date')->nullable();
            $table->string('issue_numbers')->nullable();
            $table->year('year')->nullable();
            $table->integer('status')->default(0);
            $table->integer('admin_published')->nullable();
            $table->integer('admin_archive')->nullable();
            $table->integer('upload_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
