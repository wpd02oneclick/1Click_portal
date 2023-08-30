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
        Schema::create('research_draft_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('File_Name');
            $table->string('File_Path');
            $table->unsignedBigInteger('Draft_submission_id');
            $table->foreign('Draft_submission_id')->references('id')->on('research_draft_submissions')->onDelete('cascade');
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
        Schema::dropIfExists('research_draft_attachments');
    }
};
