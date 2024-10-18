<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantesTable extends Migration
{
    public function up()
    {
        Schema::create('participantes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 255);
            $table->string('email', 255);
            $table->string('cpf', 255);
            $table->unsignedBigInteger('evento_id');
            $table->foreign('evento_id')->references('id')->on('eventos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('participantes');
    }
}
