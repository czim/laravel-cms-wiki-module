<?php
namespace Czim\CmsWikiModule\Repositories;

use Carbon\Carbon;
use Czim\CmsWikiModule\Contracts\Repositories\WikiRepositoryInterface;
use Czim\CmsWikiModule\Models\WikiPage;
use Czim\CmsWikiModule\Models\WikiPageEdit;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use RuntimeException;

class WikiRepository implements WikiRepositoryInterface
{

    /**
     * Returns all pages.
     *
     * @return Collection|WikiPage[]
     */
    public function getAll()
    {
        return WikiPage::all();
    }

    /**
     * Returns a page by ID.
     *
     * @param int $id
     * @return WikiPage|null
     */
    public function findById($id)
    {
        return WikiPage::find($id);
    }

    /**
     * Returns uploaded files by reference.
     *
     * @param string $slug
     * @return WikiPage|null
     */
    public function findBySlug($slug)
    {
        return WikiPage::where('slug', $slug)->first();
    }

    /**
     * Creates a new wiki page.
     *
     * @param string      $title
     * @param string      $body
     * @param string      $slug
     * @param string|null $author
     * @return WikiPage
     */
    public function create($title, $body, $slug, $author = null)
    {
        if (null === $slug) {
            // todo generate unique slug
        }

        $page = new WikiPage([
            'title' => $title,
            'body'  => $body,
            'slug'  => $slug,
        ]);

        if ( ! $page->save() || ! $page->exists) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Failed to create new wiki page');
            // @codeCoverageIgnoreEnd
        }

        $this->markPageEdited($page);

        return $page;
    }

    /**
     * Updates existing wiki page by ID.
     *
     * @param string      $id
     * @param string      $title
     * @param string      $body
     * @param string|null $slug
     * @param string|null $author
     * @return bool
     */
    public function update($id, $title, $body, $slug = null, $author = null)
    {
        if ( ! ($page = $this->findById($id))) {
            throw (new ModelNotFoundException('Could not find WikiPage'))
                ->setModel(WikiPage::class, [$id]);
        }

        $page->title = $title;
        $page->body  = $body;

        if (null !== $slug) {
            $page->slug = $slug;
        }

        if ( ! $page->save()) {
            return false;
        }

        $this->markPageEdited($page, $author);

        return true;
    }

    /**
     * Deletes a page.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        if ( ! ($file = $this->findById($id))) {
            return true;
        }

        return $file->delete();
    }

    /**
     * Marks an existing page as edited now, by the CMS user.
     *
     * @param WikiPage    $page
     * @param string|null $author
     */
    protected function markPageEdited(WikiPage $page, $author = null)
    {
        if (null === $author) {
            if ($user = cms()->auth()->user()) {
                $author = $user->getUsername();
            }
        }

        $page->edits()->save(
            new WikiPageEdit([
                'author' => $author,
                'date'   => Carbon::now(),
            ])
        );
    }

}
