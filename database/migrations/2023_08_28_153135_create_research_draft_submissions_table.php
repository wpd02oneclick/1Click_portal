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
    public function up()
    {
        Schema::create('research_draft_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Order_ID');
            $table->string('Order_Number');
            $table->unsignedBigInteger('Submited_By');
            $table->string('Draft_Number');
            $table->boolean('Status')->default(0);
            $table->unsignedBigInteger('approved_by')->nullable();

            $table->foreign('Order_ID')->references('id')->on('order_infos')->onDelete('cascade');
            $table->foreign('Submited_By')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('research_draft_submissions');
    }
};
