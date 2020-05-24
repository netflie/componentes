<?php declare(strict_types=1);

namespace Netflie\Componentes\Provider;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;

class ServiceProvider
{
    /**
     * @var Illuminate\Contracts\Container\Container
     */
    protected $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function register()
    {
        $this->registerEventProvider();

        $this->registerViewProvider();
    }

    protected function registerEventProvider()
    {
        (new \Illuminate\Events\EventServiceProvider($this->app))->register();
    }

    protected function registerViewProvider()
    {
        (new ViewServiceProvider($this->app))->register();

        $this->app->bind(ViewFactoryContract::class, function ($app) {
            return $app['view'];
        });
    }
}