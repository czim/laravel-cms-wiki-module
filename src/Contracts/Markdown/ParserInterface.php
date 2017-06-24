<?php
namespace Czim\CmsWikiModule\Contracts\Markdown;

interface ParserInterface
{

    /**
     * Converts markdown to HTML.
     *
     * @param string $markdown
     * @return string
     */
    public function toHtml(string $markdown);

}
