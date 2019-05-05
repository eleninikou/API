<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('description');
            $table->integer('status_id');
            $table->integer('type_id');
            $table->integer('project_id');
            $table->enum('priority', ['low', 'normal', 'high'])->default('low');
            $table->dateTime('due_date')->nullable();
            $table->integer('creator_id');
            $table->integer('assigned_user_id');
            $table->integer('milestone_id')->nullable();
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
        Schema::dropIfExists('tickets');
    }
}
