<?php

namespace DigitalCloud\NovaBlogTool\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use DigitalCloud\NovaBlogTool\BlogResponder;

class MigrationController extends BlogBaseController
{
    protected function processTask() : void
    {
        foreach ($this->migrations as $tableName => $migrationClass) {
            if (Schema::hasTable($tableName)) {
                $this->messages[] = BlogResponder::tableAlreadyCreated($tableName);
            }

            try {
                $migrationClass->up();
                $this->messages[] = BlogResponder::migrationSuccess($tableName);
            } catch (\Exception $e) {
                $this->messages[] = BlogResponder::migrationFailure($tableName);
            }
        }
    }
}
