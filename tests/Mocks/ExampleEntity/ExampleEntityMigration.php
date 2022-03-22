<?php

namespace EscolaLms\Core\Tests\Mocks\ExampleEntity;

use EscolaLms\Core\Enums\StatusEnum;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExampleEntityMigration
{
    public static function run()
    {
        if (Schema::hasTable('example_entities')) {
            Schema::drop('example_entities');
        }

        Schema::create('example_entities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('status')->default(StatusEnum::ACTIVE);
            $table->dateTime('date_time');
            $table->timestamps();
        });
    }
}
