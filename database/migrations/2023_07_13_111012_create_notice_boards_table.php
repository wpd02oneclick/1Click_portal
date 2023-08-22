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
        Schema::create('notice_boards', static function (Blueprint $table) {
            $table->id();
            $table->string('Notice_Title');
            $table->text('Notice_Desc')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('created_by')->unsigned();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('notice_boards');
    }
};
