<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('marks', function (Blueprint $table) {
            $table->unsignedBigInteger('class_id')->nullable()->change(); // ✅ Make it nullable
        });
    }

    public function down()
    {
        Schema::table('marks', function (Blueprint $table) {
            $table->unsignedBigInteger('class_id')->nullable(false)->change(); // For rollback
        });
    }
};
