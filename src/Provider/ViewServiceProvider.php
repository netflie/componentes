<?php declare(strict_types=1);

namespace Netflie\Componentes\Provider;

use Netflie\Componentes\Compiler;

class ViewServiceProvider extends \Illuminate\View\ViewServiceProvider
{
    /**
     * Register the Blade compiler implementation.
     *
     * @return void
     */
    public function registerBladeCompiler()
    {
        $this->app->singleton('blade.compiler', function ($app) {
            return new Compiler($app['files'], $app['config']['view.compiled']);
        });
    }
}