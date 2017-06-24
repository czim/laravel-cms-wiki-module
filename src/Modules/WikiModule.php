<?php
namespace Czim\CmsWikiModule\Modules;

use Czim\CmsCore\Contracts\Core\CoreInterface;
use Czim\CmsCore\Support\Enums\MenuPresenceType;
use Czim\CmsWikiModule\Models\WikiPage;
use Czim\CmsWikiModule\Support\Route\ApiRouteMapper;
use Czim\CmsWikiModule\Support\Route\WebRouteMapper;
use Czim\CmsCore\Contracts\Modules\ModuleInterface;
use Czim\CmsCore\Support\Enums\AclPresenceType;
use Illuminate\Routing\Router;

class WikiModule implements ModuleInterface
{

    /**
     * @var string
     */
    const VERSION = '1.0.0';


    /**
     * @var CoreInterface
     */
    protected $core;

    /**
     * @param CoreInterface $core
     */
    public function __construct(CoreInterface $core)
    {
        $this->core = $core;
    }


    /**
     * Returns unique identifying key for the module.
     * This should also be able to perform as a slug for it.
     *
     * @return string
     */
    public function getKey()
    {
        return 'wiki';
    }

    /**
     * Returns display name for the module.
     *
     * @return string
     */
    public function getName()
    {
        return 'Media Library';
    }

    /**
     * Returns release/version number of module.
     *
     * @return string
     */
    public function getVersion()
    {
        return static::VERSION;
    }

    /**
     * Returns the FQN for a class mainly associated with this module.
     *
     * @return string|null
     */
    public function getAssociatedClass()
    {
        return WikiPage::class;
    }

    /**
     * Generates web routes for the module given a contextual router instance.
     * Note that the module is responsible for ACL-checks, including route-based.
     *
     * @param Router $router
     */
    public function mapWebRoutes(Router $router)
    {
        (new WebRouteMapper())->mapRoutes($router);
    }

    /**
     * Generates API routes for the module given a contextual router instance.
     * Note that the module is responsible for ACL-checks, including route-based.
     *
     * @param Router $router
     */
    public function mapApiRoutes(Router $router)
    {
        (new ApiRouteMapper())->mapRoutes($router);
    }

    /**
     * @return array
     */
    public function getAclPresence()
    {
        return [
            [
                'id'          => 'wiki',
                'label'       => 'Wiki',
                'type'        => AclPresenceType::GROUP,
                'permissions' => [
                    'wiki.page.show',
                    'wiki.page.create',
                    'wiki.page.edit',
                    'wiki.page.delete',
                ],
            ],
        ];
    }

    /**
     * Returns data for CMS menu presence.
     *
     * @return null
     */
    public function getMenuPresence()
    {
        return [
            [
                'id'               => 'wiki-home',
                'label'            => config('cms-wiki-module.menu.home.label', 'Wiki'),
                'label_translated' => config('cms-wiki-module.menu.home.label_translated'),
                'icon'             => config('cms-wiki-module.menu.home.icon'),
                'type'             => MenuPresenceType::ACTION,
                'permissions'      => 'wiki.page.*',
                'action'           => $this->core->prefixRoute('wiki.home'),
                'parameters'       => [],
            ],
        ];
    }

}
