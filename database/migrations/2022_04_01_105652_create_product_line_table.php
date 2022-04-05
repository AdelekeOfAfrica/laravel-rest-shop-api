<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductLineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_line_store', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_line_id')
            ->constrained('product_lines')
            ->cascadeOnUpdate()
            ->restrictOnDelete();
            $table->foreignId('brands_id')
            ->constrained('brands')
            ->cascadeOnUpdate()
            ->restrictOnDelete();
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
        Schema::dropIfExists('product_line_store');
    }
}
