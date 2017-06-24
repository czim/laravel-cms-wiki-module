<?php
namespace Czim\CmsWikiModule\Support\Route;

use Illuminate\Routing\Router;

class ApiRouteMapper
{

    /**
     * @param Router $router
     */
    public function mapRoutes(Router $router)
    {
        $router->group(
            [
                'as'        => 'wiki.',
                'prefix'    => 'wiki',
                'namespace' => '\\Czim\\CmsWikiModule\\Http\\Controllers\\Api',
            ],
            function (Router $router)  {

                $router->group(
                    [
                        'as'         => 'page.',
                        'prefix'     => 'page',
                        'middleware' => [cms_mw_permission('wiki.page.*')],
                    ],
                    function (Router $router) {
                    }
                );
            }
        );
    }

}
