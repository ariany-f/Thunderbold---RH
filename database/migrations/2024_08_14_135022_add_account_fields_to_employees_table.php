<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountFieldsToEmployeesTable extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('bank')->nullable();
            $table->string('agency')->nullable();
            $table->string('account')->nullable();
            $table->decimal('salary_base', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['bank', 'agency', 'account', 'salary_base']);
        });
    }
}
