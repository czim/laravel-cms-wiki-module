<?php
namespace Czim\CmsWikiModule\Contracts\Markdown;

interface ParserFactoryInterface
{

    /**
     * Makes a markdown parser.
     *
     * @param string $strategy
     * @return ParserInterface
     */
    public function make($strategy);

}
