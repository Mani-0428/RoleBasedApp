<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('marks', function (Blueprint $table) {
            $table->string('class_name')->after('user_id'); // ✅ Adds class_name column
        });
    }

    public function down()
    {
        Schema::table('marks', function (Blueprint $table) {
            $table->dropColumn('class_name'); // ✅ Removes class_name column
        });
    }
};
