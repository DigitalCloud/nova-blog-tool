<?php

namespace DigitalCloud\NovaBlogTool\Migrations;

class Migrator
{
    public function getMigrations()
    {
        return [
            'categories' => new CategoryMigration,
            'posts' => new PostMigration,
            'comments' => new CommentMigration,
            'tags' => new TagMigration,
            'post_category' => new PostCategoryPivotMigration,
            'nova_pending_trix_attachments' => new NovaPendingTrixAttachmentsMigration(),
            'nova_trix_attachments' => new NovaTrixAttachmentsMigration(),
        ];
    }
}
