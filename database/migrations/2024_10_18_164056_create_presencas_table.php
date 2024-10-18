<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresencasTable extends Migration
{
    public function up()
    {
        Schema::create('presencas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('data');
            $table->unsignedBigInteger('participante_id');
            $table->foreign('participante_id')->references('id')->on('participantes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('presencas');
    }
}
