<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('employee_payments');
        Schema::create('employee_payments', function (Blueprint $table) {
            $table->UnsignedBigInteger('employee_id');
            $table->UnsignedInteger('credit');
            $table->UnsignedInteger('debut');
            $table->date('date');
            $table->timestamps();
            $table->softdeletes();
            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_payments');
    }
}
