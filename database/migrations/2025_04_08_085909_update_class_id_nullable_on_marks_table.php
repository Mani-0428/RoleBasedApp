<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('marks', function (Blueprint $table) {
            // ✅ Make class_id nullable
            $table->unsignedBigInteger('class_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('marks', function (Blueprint $table) {
            $table->unsignedBigInteger('class_id')->nullable(false)->change();
        });
    }
};
