<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMicroservicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('microservices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hostname');
            $table->string('port');
            $table->json('base_models')->nullable();
            $table->json('all_models')->nullable();
            $table->json('deleted_models')->nullable();
            $table->json('updated_models')->nullable();
            $table->json('model_errors')->nullable();
            $table->string('consumer_logs')->default(0);
            $table->string('consumer_error_logs')->default(0);
            $table->boolean('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('microservices');
    }
}
