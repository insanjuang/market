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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->increments('id_penjualan');
            $table->string('device', 100)->nullable();
            $table->date('tgl_transaksi')->nullable();
            $table->string('nota', 100)->nullable();
            $table->integer('id_member')->nullable();
            $table->string('nama_buyer', 100)->nullable();
            $table->string('no_telp', 15)->nullable();
            $table->string('alamat_kirim', 200)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kota', 100)->nullable();
            $table->integer('kode_pos')->nullable();
            $table->string('longlat', 100)->nullable();
            $table->date('tgl_kirim')->nullable();
            $table->integer('status_kirim')->nullable()->default(0);
            $table->text('notes')->nullable();
            $table->integer('total_item')->default(0);
            $table->integer('total_harga')->default(0);
            $table->integer('diskon')->nullable()->default(0);
            $table->integer('ongkos_kirim')->nullable()->default(0);
            $table->integer('bayar')->default(0);
            $table->integer('diterima')->default(0);
            $table->string('status_bayar')->default('BELUM LUNAS');
            $table->date('tgl_lunas')->nullable();
            $table->integer('status_order')->default(0);
            $table->integer('id_user')->nullable()->default(0);
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualan');
    }
};
