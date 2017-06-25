<?php
namespace Czim\CmsWikiModule\Contracts\Support;

interface WikiMemoryInterface
{

    /**
     * Returns a list of page slugs in order from old to new.
     *
     * @return string[]
     */
    public function getPageHistory();

    /**
     * Pushes a page onto the history stack.
     *
     * When resetting, the history will be reset to the oldest match found, if any.
     *
     * @param string $slug
     * @param bool   $reset
     * @return $this
     */
    public function pushPageToHistory($slug, $reset = true);

    /**
     * Returns the most recent page from the history and resaves the history without it.
     *
     * @return string
     */
    public function popPageFromHistory();

}
