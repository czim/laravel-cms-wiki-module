<?php
namespace Czim\CmsWikiModule\Support\Memory;

use Czim\CmsWikiModule\Contracts\Support\WikiMemoryInterface;

class WikiMemory implements WikiMemoryInterface
{
    const SESSION_HISTORY_KEY = 'cms-wiki-page-history';

    /**
     * The (fake) slug to use to refer to the index page.
     */
    const INDEX_SLUG = '::INDEX::';

    /**
     * Returns a list of page slugs in order from old to new.
     *
     * @return string[]
     */
    public function getPageHistory()
    {
        return session(static::SESSION_HISTORY_KEY, []);
    }

    /**
     * Pushes a page onto the history stack.
     *
     * When resetting, the history will be reset to the oldest match found, if any.
     *
     * @param string $slug
     * @param bool   $reset
     * @return $this
     */
    public function pushPageToHistory($slug, $reset = true)
    {
        $history = $this->getPageHistory();

        if ($reset) {
            $index = array_search($slug, $history);

            if (false !== $index) {

                $history = array_slice($history, 0, $index + 1);

                $this->storePageHistory($history);

                return $this;
            }
        }

        $history[] = $slug;

        $this->storePageHistory($history);

        return $this;
    }

    /**
     * Returns the most recent page from the history and resaves the history without it.
     *
     * @return string
     */
    public function popPageFromHistory()
    {
        $history = $this->getPageHistory();

        $slug = array_pop($history);

        $this->storePageHistory($history);

        return $slug;
    }

    /**
     * Stores an array of slugs as the history.
     *
     * @param string[] $history
     */
    protected function storePageHistory(array $history)
    {
        session()->put(static::SESSION_HISTORY_KEY, array_values($history));
    }

}
