<?php

use EscolaLms\Core\Migrations\EscolaMigration;
use EscolaLms\Core\Seeders\RoleTableSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class InstallPassport extends EscolaMigration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('passport:install');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
