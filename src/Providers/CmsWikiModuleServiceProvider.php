<?php
namespace Czim\CmsWikiModule\Providers;

use Czim\CmsCore\Contracts\Core\CoreInterface;
use Czim\CmsCore\Support\Enums\Component;
use Czim\CmsWikiModule\Contracts\Markdown\ParserFactoryInterface;
use Czim\CmsWikiModule\Contracts\Repositories\WikiRepositoryInterface;
use Czim\CmsWikiModule\Markdown\MarkdownParserFactory;
use Czim\CmsWikiModule\Repositories\WikiRepository;
use Illuminate\Support\ServiceProvider;

class CmsWikiModuleServiceProvider extends ServiceProvider
{

    /**
     * @var CoreInterface
     */
    protected $core;


    public function boot()
    {
        $this->bootConfig();
    }

    public function register()
    {
        $this->core = app(Component::CORE);

        $this->registerConfig()
             ->loadViews()
             ->registerInterfaceBindings()
             ->publishMigrations();
    }


    /**
     * @return $this
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            realpath(dirname(__DIR__) . '/../config/cms-wiki-module.php'),
            'cms-wiki-module'
        );

        return $this;
    }

    /**
     * Loads basic CMS views.
     *
     * @return $this
     */
    protected function loadViews()
    {
        $this->loadViewsFrom(
            realpath(dirname(__DIR__) . '/../resources/views'),
            'cms-wiki'
        );

        return $this;
    }

    /**
     * @return $this
     */
    protected function registerInterfaceBindings()
    {
        $this->app->singleton(WikiRepositoryInterface::class, WikiRepository::class);
        $this->app->singleton(ParserFactoryInterface::class, MarkdownParserFactory::class);

        return $this;
    }

    /**
     * @return $this
     */
    protected function publishMigrations()
    {
        $this->publishes([
            realpath(dirname(__DIR__) . '/../migrations') => $this->getMigrationPath(),
        ], 'migrations');

        return $this;
    }

    /**
     * @return string
     */
    protected function getMigrationPath()
    {
        return database_path($this->core->config('database.migrations.path'));
    }

    /**
     * @return $this
     */
    protected function bootConfig()
    {
        $this->publishes([
            realpath(dirname(__DIR__) . '/../config/cms-wiki-module.php') => config_path('cms-wiki-module.php'),
        ]);

        return $this;
    }

}
