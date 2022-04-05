<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductMatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_match', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
            ->constrained('products')
            ->cascadeOnUpdate()
            ->restrictOnDelete();
            $table->foreignId('product_line_id')
            ->constrained('product_lines')
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
        Schema::dropIfExists('product_match');
    }
}