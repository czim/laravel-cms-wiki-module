<?php
namespace Czim\CmsWikiModule\Support\Route;

use Illuminate\Routing\Router;

class WebRouteMapper
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
                'namespace' => '\\Czim\\CmsWikiModule\\Http\\Controllers',
                'middleware' => [cms_mw_permission('wiki.page.*')],
            ],
            function (Router $router)  {

                $router->get('/', [
                    'as'   => 'home',
                    'uses' => 'WikiController@index',
                ]);

                $router->get('page/{slug}', [
                    'as'   => 'page',
                    'uses' => 'WikiController@page',
                ]);

                $router->group(
                    [
                        'as'     => 'record.',
                        'prefix' => 'record',
                    ],
                    function (Router $router) {

                        $router->get('create', [
                            'as'         => 'create',
                            'middleware' => [cms_mw_permission('wiki.page.create')],
                            'uses'       => 'WikiController@create',
                        ]);

                        $router->post('/', [
                            'as'         => 'store',
                            'middleware' => [cms_mw_permission('wiki.page.create')],
                            'uses'       => 'WikiController@store',
                        ]);

                        $router->get('edit/{id}', [
                            'as'         => 'edit',
                            'middleware' => [cms_mw_permission('wiki.page.edit')],
                            'uses'       => 'WikiController@edit',
                        ]);

                        $router->post('{id}', [
                            'as'         => 'update',
                            'middleware' => [cms_mw_permission('wiki.page.edit')],
                            'uses'       => 'WikiController@update',
                        ]);

                        $router->delete('{id}', [
                            'as'         => 'delete',
                            'middleware' => [cms_mw_permission('wiki.page.delete')],
                            'uses'       => 'WikiController@destroy',
                        ]);
                    }
                );
            }
        );
    }

}
