<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsSendNotiWordpressOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wordpress_orders', function (Blueprint $table) {
            $table->boolean('is_send_notify')->default(false);
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wordpress_orders', function (Blueprint $table) {
            $table->dropColumn('is_send_notify');
        });
    }
}
