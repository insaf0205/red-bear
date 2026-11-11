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
        Schema::create('trainings', function (Blueprint $table) {
            $table->ulid("id")->primary();
            $table->string("name")->nullable();
            $table->string("description")->nullable();
            $table->string("image_url")->nullable();
            $table->integer("cost")->default(400);
            $table->integer("cost_last_user_paid")->nullable();
            $table->timestamp("start_at");
            $table->timestamp("end_at");
            $table->foreignUlid("trainer_id")->constrained("users")->nullOnDelete();
            $table->foreignUlid("student_id")->nullable()->constrained("users")->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
