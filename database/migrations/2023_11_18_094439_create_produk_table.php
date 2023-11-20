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
        Schema::create('produk', function (Blueprint $table) {
            $table->increments('id_produk');
            $table->unsignedInteger('id_kategori');
            $table->integer('id_subkategori')->default(0);
            $table->integer('id_supplier');
            $table->string('kode_produk')->unique();
            $table->string('nama_produk')->unique();
            $table->string('merk')->nullable();
            $table->text('description')->nullable();
            $table->integer('harga_beli');
            $table->integer('harga_agen');
            $table->integer('harga_reseller');
            $table->integer('harga_jual');
            $table->integer('stok')->default(0);
            $table->integer('diskon_rp')->nullable()->default(0);
            $table->integer('diskon_persen')->nullable()->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->text('image')->nullable();
            $table->integer('min_order')->default(1);
            $table->tinyInteger('preorder')->default(0);
            $table->integer('berat')->default(1000);
            $table->string('dimensi', 30)->nullable();
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
        Schema::dropIfExists('produk');
    }
};
