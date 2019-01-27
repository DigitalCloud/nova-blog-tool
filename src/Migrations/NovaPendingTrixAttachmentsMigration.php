<?php

namespace DigitalCloud\NovaBlogTool\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class NovaPendingTrixAttachmentsMigration
{
    public function up()
    {
        Schema::create('nova_pending_trix_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('draft_id')->index();
            $table->string('attachment');
            $table->string('disk');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nova_pending_trix_attachments');
    }
}
