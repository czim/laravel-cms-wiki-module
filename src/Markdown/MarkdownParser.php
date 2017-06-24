<?php
namespace Czim\CmsWikiModule\Markdown;

use cebe\markdown\Markdown;
use Czim\CmsWikiModule\Contracts\Markdown\ParserInterface;

class MarkdownParser implements ParserInterface
{

    /**
     * @var Markdown
     */
    protected $strategy;

    /**
     * @param Markdown $strategy
     */
    public function __construct(Markdown $strategy)
    {
       $this->strategy = $strategy;
    }

    /**
     * Converts markdown to HTML.
     *
     * @param string $markdown
     * @return string
     */
    public function toHtml(string $markdown)
    {
        return $this->strategy->parse($markdown);
    }

}
