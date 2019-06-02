<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("user_company", function(Blueprint $table) {
            $table->bigInteger("user_id")->unsigned();
            $table->bigInteger("company_id")->unsigned();
            $table->timestamps();
            
            $table->foreign("user_id")->references("id")->on("users");
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
        Schema::dropIfExists("user_company");
    }
}
