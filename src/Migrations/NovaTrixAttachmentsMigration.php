<?php

namespace DigitalCloud\NovaBlogTool\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class NovaTrixAttachmentsMigration
{
    public function up()
    {
        Schema::create('nova_trix_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('attachable_type');
            $table->unsignedInteger('attachable_id');
            $table->string('attachment');
            $table->string('disk');
            $table->string('url')->index();
            $table->timestamps();

            $table->index(['attachable_type', 'attachable_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('nova_trix_attachments');
    }
}
