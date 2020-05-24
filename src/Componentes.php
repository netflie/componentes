<?php declare(strict_types=1);

namespace Netflie\Componentes;

use Illuminate\Container\Container;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;
use Illuminate\Filesystem\Filesystem;
use Netflie\Componentes\Provider\ServiceProvider;

class Componentes
{
    /**
     * @var Illuminate\Container\Container
     */
    protected $app;

    public function __construct(Filesystem $files, string $viewPath)
    {
        $cachePath = $viewPath . '/cache';

        $this->app = Container::getInstance();
        $this->app->files = $files;
        $this->app->config = [
            'view.paths' => [$viewPath, $cachePath],
            'view.compiled' => $cachePath,
        ];

        (new ServiceProvider($this->app))->register();
    }

    /**
     * Creates a new Componentes.
     *
     * @return static
     */
    public static function create(string $viewPath)
    {
        return new static(new Filesystem(), $viewPath);
    }

    /**
     * Register a class-based component alias directive.
     *
     * @param  string  $class
     * @param  string|null  $alias
     * @param  string  $prefix
     * @return void
     */
    public function component($class, $alias = null, $prefix = '')
    {
        $this->app['blade.compiler']->component($class, $alias, $prefix);
    }

    /**
     * Register an array of class-based components.
     *
     * @param  array  $components
     * @param  string  $prefix
     * @return void
     */
    public function components(array $components, $prefix = '')
    {
        $this->app['blade.compiler']->components($components, $prefix);
    }

    public function render(string $content)
    {
        $viewFactory = $this->app->make(ViewFactoryContract::class);

        $viewName = sha1($content);
        $viewPath = $this->app->config['view.compiled'] . '/' . $viewName . '.blade.php';

        $this->app->files->replace($viewPath, $content);

        $view = $viewFactory->make($viewName);

        return $view->render();
    }
}