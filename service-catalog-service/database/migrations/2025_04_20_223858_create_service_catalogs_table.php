<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceCatalogsTable extends Migration
{
    public function up()
    {
        Schema::create('service_catalogs', function (Blueprint $table) {
            $table->string('service_id', 50)->primary();  
            $table->string('nama_layanan');
            $table->text('deskripsi');
            $table->decimal('harga', 15, 2);
            $table->integer('durasi');
            $table->string('kategori');
            $table->timestamps();  
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_catalogs');
    }
}
