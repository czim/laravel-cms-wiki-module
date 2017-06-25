<?php
namespace Czim\CmsWikiModule\Markdown;

use cebe\markdown\GithubMarkdown;
use cebe\markdown\Markdown;
use cebe\markdown\MarkdownExtra;
use Czim\CmsWikiModule\Contracts\Markdown\ParserInterface;

class MarkdownParserFactory
{

    /**
     * Makes a markdown parser.
     *
     * @param string $strategy
     * @return ParserInterface
     */
    public function make($strategy)
    {
        switch ($strategy) {

            case 'traditional':
                $strategyInstance = new Markdown;
                break;

            case 'extra':
                $strategyInstance = new MarkdownExtra;
                break;

            case 'github':
            default:
                $strategyInstance = new GithubMarkdown;
                break;
        }

        $strategyInstance->html5 = true;

        return new MarkdownParser($strategyInstance);
    }

}
