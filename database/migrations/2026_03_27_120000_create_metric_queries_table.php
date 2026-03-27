<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('metric_queries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('token')->unique();
            $table->text('prompt');
            $table->text('generated_sql');
            $table->string('display_type', 20)->default('table');
            $table->json('display_config')->nullable();
            $table->string('source', 20)->default('llm');
            $table->string('template_id', 100)->nullable();
            $table->boolean('is_saved')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->foreignUuid('user_id')->constrained();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'is_saved'], 'idx_user_saved');
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('metric_queries');
    }
};
