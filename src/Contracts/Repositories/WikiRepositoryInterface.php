<?php
namespace Czim\CmsWikiModule\Contracts\Repositories;

use Czim\CmsWikiModule\Models\WikiPage;
use Illuminate\Support\Collection;

interface WikiRepositoryInterface
{

    /**
     * Returns all wiki pages.
     *
     * @return Collection|WikiPage[]
     */
    public function getAll();

    /**
     * Returns a wiki page by ID.
     *
     * @param int $id
     * @return WikiPage|null
     */
    public function findById($id);

    /**
     * Returns uploaded files by reference.
     *
     * @param string $slug
     * @return WikiPage|null
     */
    public function findBySlug($slug);

    /**
     * Creates a new wiki page.
     *
     * @param string      $title
     * @param string      $body
     * @param string      $slug
     * @param string|null $author
     * @return WikiPage
     */
    public function create($title, $body, $slug, $author = null);

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
    public function update($id, $title, $body, $slug = null, $author = null);

    /**
     * Deletes a wiki page.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id);

}
