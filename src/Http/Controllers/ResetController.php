<?php

namespace DigitalCloud\NovaBlogTool\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use DigitalCloud\NovaBlogTool\BlogResponder;

class ResetController extends BlogBaseController
{
    protected function processTask() : void
    {
        foreach ($this->migrations as $tableName => $migrationClass) {
            if (! Schema::hasTable($tableName)) {
                $this->messages[] = BlogResponder::resetTableNotFound($tableName);
            }

            try {
                $this->truncateTable($tableName);
                $this->messages[] = BlogResponder::resetSuccess($tableName);
            } catch (\Exception $e) {
                $this->messages[] = BlogResponder::resetFailure($tableName);
            }
        }
    }

    protected function truncateTable($tableName)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table($tableName)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
