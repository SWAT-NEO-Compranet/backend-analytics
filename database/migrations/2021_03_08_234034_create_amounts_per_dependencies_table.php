<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmountsPerDependenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amounts_per_dependencies', function (Blueprint $table) {
            $table->id();
            $table->string('acronyms')->index()->nullable();
            $table->string('institution')->nullable();
            $table->string('uc')->nullable();
            $table->string('currency')->nullable();
            $table->date('date')->nullable();
            $table->float('import')->nullable();
            $table->integer('counter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amounts_per_dependencies');
    }
}
