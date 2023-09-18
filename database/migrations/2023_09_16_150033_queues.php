<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default(0)->index();
            $table->json('data')->nullable();
            $table->double('process_time')->default(0)->comment("Thời gian xử lý (s)");
            $table->tinyInteger('status')->default(0)->index()->comment('0:Chờ , 1: Đang xử lý, 2: Thành công, 3: Lỗi');
            $table->json('value')->nullable();
            $table->text("error")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
