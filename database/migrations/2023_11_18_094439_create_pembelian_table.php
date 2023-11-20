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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->increments('id_pembelian');
            $table->integer('id_supplier');
            $table->date('tgl_transaksi');
            $table->string('kode_nota', 100)->default('');
            $table->integer('total_item');
            $table->integer('total_harga');
            $table->integer('diskon')->nullable()->default(0);
            $table->integer('bayar')->default(0);
            $table->string('tipe_pembelian', 15)->default('Cash');
            $table->tinyInteger('status')->default(0);
            $table->string('status_bayar', 10)->default('Unpaid');
            $table->text('deskripsi')->nullable();
            $table->integer('ongkos_kirim')->nullable()->default(0);
            $table->dateTime('jatuh_tempo')->nullable();
            $table->integer('id_user');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembelian');
    }
};
