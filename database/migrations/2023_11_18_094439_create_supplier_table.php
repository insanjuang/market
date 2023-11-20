<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier', function (Blueprint $table) {
            $table->increments('id_supplier');
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->string('telepon');
            $table->timestamps();
            $table->softDeletes();
            $table->string('email', 50)->nullable();
            $table->string('negara', 4)->nullable();
            $table->string('provinsi', 4)->nullable();
            $table->string('kotakab', 4)->nullable();
            $table->text('deskripsi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier');
    }
};
