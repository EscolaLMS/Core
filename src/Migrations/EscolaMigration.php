<?php

namespace EscolaLms\Core\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class EscolaMigration extends Migration
{
    protected function avoidDuplicateTable(string $table): void
    {
        if (Schema::hasTable($table)) {
            if (DB::table($table)->count() > 0) {
                throw new RuntimeException("Your database already has $table table with data.
                Disable LMS migrations by setting escolalms.core.ignore_migrations to true, and publish them.");
            } else {
                $this->down();
            }
        }
    }

    public function create(string $table, \Closure $schema)
    {
        $this->avoidDuplicateTable($table);
        return Schema::create($table, $schema);
    }
}
