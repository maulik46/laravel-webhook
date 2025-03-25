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
        Schema::create('github_commits', function (Blueprint $table) {
            $table->id();
            $table->string('commit_id')->unique();
            $table->string('message');
            $table->string('author_name');
            $table->string('author_email');
            $table->timestamp('committed_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('github_commits');
    }
};
