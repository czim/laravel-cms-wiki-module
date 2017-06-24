<?php

use Czim\CmsCore\Support\Database\CmsMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWikiPagesTable extends CmsMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->prefixCmsTable('wiki_pages'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->text('body')->nullable();
            $table->nullableTimestamps();

            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->prefixCmsTable('wiki_pages'));
    }
}
