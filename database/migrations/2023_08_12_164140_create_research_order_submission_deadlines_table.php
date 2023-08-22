<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('research_order_submission_deadlines', static function (Blueprint $table) {
            $table->id();
            $table->date('DeadLines');
            $table->tinyInteger('is_Submit')->default(0);
            $table->timestamp('submit_at')->nullable();
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('submit_by')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('order_infos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('submit_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('research_order_submission_deadlines');
    }
};
