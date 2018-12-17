<?php

namespace DigitalCloud\NovaBlogTool\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use DigitalCloud\NovaBlogTool\BlogResponder;

class UninstallController extends BlogBaseController
{
    protected function processTask() : void
    {
        foreach ($this->migrations as $tableName => $migrationClass) {
            if (! Schema::hasTable($tableName)) {
                $this->messages[] = BlogResponder::deleteTableNotFound($tableName);
            }

            try {
                $this->deleteTable($migrationClass);
                $this->messages[] = BlogResponder::deleteSuccess($tableName);
            } catch (\Exception $e) {
                $this->messages[] = BlogResponder::deleteFailure($tableName);
            }
        }
    }

    protected function deleteTable($migrationClass)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $migrationClass->down();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
