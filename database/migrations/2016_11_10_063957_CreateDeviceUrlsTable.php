<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_urls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('short_code', 100);
            $table->string('device_type');
            $table->string('long_url', 1000);
            $table->integer('redirect_count')->default(0);
            $table->timestamps();

            $table->foreign('short_code')
                ->references('short_code')->on('short_urls')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('device_urls');
    }
}
