<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up()
        {
            Schema::create('profiling_questions', function (Blueprint $table) {
                $table->id();
                $table->text('question_text');
                $table->json('options')->nullable();
                $table->enum('type', ['date', 'single choice', 'multiple choice']);
                $table->string('validation')->nullable();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('profiling_questions');
        }
    };
