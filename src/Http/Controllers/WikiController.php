<?php
namespace Czim\CmsWikiModule\Http\Controllers;

use Czim\CmsCore\Contracts\Core\CoreInterface;
use Czim\CmsWikiModule\Contracts\Repositories\WikiRepositoryInterface;

class WikiController extends Controller
{

    /**
     * @var WikiRepositoryInterface
     */
    protected $repository;

    /**
     * @param CoreInterface           $core
     * @param WikiRepositoryInterface $repository
     */
    public function __construct(
        CoreInterface $core,
        WikiRepositoryInterface $repository
    ) {
        parent::__construct($core);

        $this->repository = $repository;
    }


    /**
     * Action: wiki page or home page.
     *
     * @param string|null $slug
     * @return mixed
     */
    public function index($slug = null)
    {
        $homeSlug = config('cms-wiki-module.home.slug');

        if (null === $slug) {
            // Load the home page, if one is available.
            // Otherwise, show a placeholder page.
            $slug = $homeSlug;

            if ( ! $slug) {
                return abort(404, "No wiki home page defined");
            }
        }

        $home = $slug === $homeSlug;
        $page = $this->repository->findBySlug($slug);

        if ( ! $page) {
            if ($home) {

            }

            return abort(404, "Could not find wiki page for slug '{$slug}'");
        }

        return view('cms-wiki::wiki.index', [
            'isHome' => $home,
            'title'  => $page->title,
            'page'   => $page,
        ]);
    }

    /**
     * Action: form to create a new page record.
     *
     * @return mixed
     */
    public function create()
    {
        return null;
    }

    /**
     * Action: store a new page record.
     *
     * @return mixed
     */
    public function store()
    {
        return null;
    }

    /**
     * Action: form to edit a page record.
     *
     * @param int $id
     * @return mixed
     */
    public function edit($id)
    {
        return null;
    }

    /**
     * Action: update a new page record.
     *
     * @param int $id
     * @return mixed
     */
    public function update($id)
    {
        return null;
    }

    /**
     * Action: delete an existing page record.
     *
     * @param int $id
     * @return mixed
     */
    public function destroy($id)
    {
        return null;
    }

}
