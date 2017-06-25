<?php
namespace Czim\CmsWikiModule\Http\Controllers;

use Czim\CmsCore\Contracts\Core\CoreInterface;
use Czim\CmsCore\Support\Enums\FlashLevel;
use Czim\CmsWikiModule\Contracts\Markdown\ParserFactoryInterface;
use Czim\CmsWikiModule\Contracts\Markdown\ParserInterface;
use Czim\CmsWikiModule\Contracts\Repositories\WikiRepositoryInterface;
use Czim\CmsWikiModule\Contracts\Support\WikiMemoryInterface;
use Czim\CmsWikiModule\Http\Requests\CreateWikiPageRequest;
use Czim\CmsWikiModule\Http\Requests\UpdateWikiPageRequest;
use Czim\CmsWikiModule\Models\WikiPage;
use Czim\CmsWikiModule\Support\Memory\WikiMemory;

class WikiController extends Controller
{

    /**
     * @var WikiRepositoryInterface
     */
    protected $repository;

    /**
     * @var WikiMemoryInterface
     */
    protected $memory;

    /**
     * When visiting a page by slug that does not exist yet,
     * the create page can be shown with this slug prefilled.
     *
     * @var null|string
     */
    protected $createSlug;

    /**
     * @param CoreInterface           $core
     * @param WikiRepositoryInterface $repository
     * @param WikiMemoryInterface     $memory
     */
    public function __construct(
        CoreInterface $core,
        WikiRepositoryInterface $repository,
        WikiMemoryInterface $memory
    ) {
        parent::__construct($core);

        $this->repository = $repository;
        $this->memory     = $memory;
    }


    // ------------------------------------------------------------------------------
    //      Browsing
    // ------------------------------------------------------------------------------

    /**
     * Action: wiki page or home page.
     *
     * @param string|null $slug
     * @return mixed
     */
    public function page($slug = null)
    {
        $home = $slug === $this->getHomeSlug();
        $page = $this->repository->findBySlug($slug);

        // If we cannot find the page, and we have rights to create it,
        // allow the user to do so. Otherwise, throw a 404.
        if ( ! $page) {

            if (cms()->auth()->can('wiki.page.create')) {
                $this->createSlug = $slug;
                return $this->create();
            }

            return abort(404, "Could not find wiki page for slug '{$slug}'");
        }

        $previousUrl = $this->getPreviousUrl();
        $this->memory->pushPageToHistory($slug);

        $body = $this->makeParser()->toHtml($page->body);

        $lastEdit = $page->edits->first();

        return view('cms-wiki::wiki.page', [
            'isHome'      => $home,
            'title'       => $page->title,
            'body'        => $body,
            'page'        => $page,
            'lastEdit'    => $lastEdit,
            'history'     => $this->memory->getPageHistory(),
            'previousUrl' => $previousUrl,
        ]);
    }

    /**
     * Action: wiki home page.
     *
     * @return mixed
     */
    public function home()
    {
        $slug = $this->getHomeSlug();

        if ( ! $slug) {
            return abort(404, "No wiki home page defined");
        }

        return redirect()->to(cms_route('wiki.page', [ $slug ]));
    }


    // ------------------------------------------------------------------------------
    //      CRUD
    // ------------------------------------------------------------------------------

    /**
     * Action: all pages listing.
     */
    public function index()
    {
        $pages = $this->repository->getAll();

        $this->memory->pushPageToHistory(WikiMemory::INDEX_SLUG);

        return view('cms-wiki::wiki.index', [
            'pages' => $pages,
        ]);
    }

    /**
     * Action: form to create a new page record.
     *
     * @return mixed
     */
    public function create()
    {
        $page = new WikiPage;

        if ($this->createSlug) {
            $page->slug = $this->createSlug;
        }

        return view('cms-wiki::wiki.edit', [
            'creating'    => true,
            'page'        => $page,
            'previousUrl' => $this->getPreviousUrl(),
        ]);
    }

    /**
     * Action: store a new page record.
     *
     * @param CreateWikiPageRequest $request
     * @return mixed
     */
    public function store(CreateWikiPageRequest $request)
    {
        $page = $this->repository->create($request->input('title'), $request->input('body'), $request->input('slug'));

        if ( ! $page) {
            return abort(500, 'Failed to create page');
        }

        cms_flash(
            cms_trans(
                'wiki.flash.success-page-create',
                [ 'record' => $page->id ]
            ),
            FlashLevel::SUCCESS
        );

        return redirect()->to(cms_route('wiki.page.edit', [ $page->id ]));
    }

    /**
     * Action: form to edit a page record.
     *
     * @param int $id
     * @return mixed
     */
    public function edit($id)
    {
        $page = $this->repository->findById($id);

        if ( ! $page) {
            return abort(404);
        }

        return view('cms-wiki::wiki.edit', [
            'creating'    => false,
            'page'        => $page,
            'previousUrl' => $this->getPreviousUrl(),
        ]);
    }

    /**
     * Action: update a new page record.
     *
     * @param UpdateWikiPageRequest $request
     * @param int                   $id
     * @return mixed
     */
    public function update(UpdateWikiPageRequest $request, $id)
    {
        if ( ! $this->repository->update($id, $request->input('title'), $request->input('body'))) {
            return abort(500, 'Failed to update page');
        }

        cms_flash(
            cms_trans(
                'wiki.flash.success-page-edit',
                [ 'record' => $id ]
            ),
            FlashLevel::SUCCESS
        );

        return redirect()->to(cms_route('wiki.page.edit', [ $id ]));
    }

    /**
     * Action: delete an existing page record.
     *
     * @param int $id
     * @return mixed
     */
    public function destroy($id)
    {
        if ( ! $this->repository->delete($id)) {
            return abort(500, "Failed to delete wiki page #{$id}");
        }

        cms_flash(
            cms_trans(
                'wiki.flash.success-page-delete',
                [ 'record' => $id ]
            ),
            FlashLevel::SUCCESS
        );

        return redirect()->to(cms_route('wiki.home'));
    }

    /**
     * @return ParserInterface
     */
    protected function makeParser()
    {
        /** @var ParserFactoryInterface $factory */
        $factory = app(ParserFactoryInterface::class);

        return $factory->make(config('cms-wiki.markdown.strategy', 'github'));
    }

    /**
     * Returns the slug for the home page.
     *
     * @return string
     */
    protected function getHomeSlug()
    {
        return config('cms-wiki-module.home.slug');
    }

    /**
     * Returns URL to the previous page in the wiki history.
     *
     * @return string
     */
    protected function getPreviousUrl()
    {
        $slug = array_last($this->memory->getPageHistory());

        if (null === $slug || false === $slug) {
            return null;
        }

        if ($slug === WikiMemory::INDEX_SLUG) {
            return cms_route('wiki.page.index');
        }

        return cms_route('wiki.page', [ $slug ]);
    }

}
