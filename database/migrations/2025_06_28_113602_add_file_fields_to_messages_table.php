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
        Schema::table('messages', function (Blueprint $table) {
            $table->string('file_path')->nullable()->after('content');
            $table->string('file_name')->nullable()->after('file_path');
            $table->bigInteger('file_size')->nullable()->after('file_name');
            
            // Update the type column to include new types
            $table->enum('type', ['text', 'image', 'video', 'file'])->default('text')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'file_name', 'file_size']);
            
            // Revert the type column to original values
            $table->enum('type', ['text', 'image', 'file'])->default('text')->change();
        });
    }
};