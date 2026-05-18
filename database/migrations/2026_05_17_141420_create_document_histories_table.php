<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_histories', function (Blueprint $table) {

            $table->id();

            $table->foreignId('dokumen_id')
                ->constrained('dokumens')
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->text('content');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_histories');
    }
};