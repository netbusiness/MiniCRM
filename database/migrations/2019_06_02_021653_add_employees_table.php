<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("employees", function(Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("first_name");
            $table->string("last_name");
            $table->bigInteger("company_id")->unsigned();
            $table->string("email")->nullable();
            $table->string("phone", 30)->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign("company_id")->references("id")->on("companies");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("employees");
    }
}
