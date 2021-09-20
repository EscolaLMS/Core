<?php

use EscolaLms\Core\Migrations\EscolaMigration;

class DropAttachmentsTable extends EscolaMigration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        (new CreateAttachmentsTable())->down();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        (new CreateAttachmentsTable())->up();
    }
}
