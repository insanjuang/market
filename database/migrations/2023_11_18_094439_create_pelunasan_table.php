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
        Schema::create('pelunasan', function (Blueprint $table) {
            $table->increments('id_pelunasan');
            $table->integer('tipe_transaksi')->nullable()->default(0);
            $table->integer('reference_id')->default(0);
            $table->string('reference_number', 30);
            $table->text('deskripsi');
            $table->integer('tagihan')->default(0);
            $table->integer('bayar')->default(0);
            $table->integer('sisa_bayar')->default(0);
            $table->integer('id_user');
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
        Schema::dropIfExists('pelunasan');
    }
};
