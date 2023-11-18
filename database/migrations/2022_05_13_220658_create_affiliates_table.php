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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('merchant_id');
            // TODO: Replace me with a brief explanation of why floats aren't the correct data type, and replace with the correct data type.
            /* float(): are floating-point numbers with limited precision. They are suitable for approximate values but may not provide accurate results for precise calculations due to rounding errors. If precise decimal calculations are crucial, using floats might lead to inaccuracies.*/
            /* decimal(): is often the preferred choice when precise decimal calculations are necessary. It allows you to specify the exact number of digits both to the left and right of the decimal point, ensuring accurate storage and retrieval of decimal values.*/
            $table->decimal('commission_rate');
            $table->string('discount_code');
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
        Schema::dropIfExists('affiliates');
    }
};
