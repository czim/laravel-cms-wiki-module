<?php

use Czim\CmsCore\Support\Database\CmsMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWikiPageEditsTable extends CmsMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->prefixCmsTable('wiki_page_edits'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wiki_page_id')->unsigned();
            $table->string('author', 255)->nullable();
            $table->timestamp('date')->nullable();

            $table->foreign('wiki_page_id')
                  ->references('id')->on($this->prefixCmsTable('wiki_pages'))
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->prefixCmsTable('wiki_page_edits'));
    }
}
