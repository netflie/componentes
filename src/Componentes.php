<?php declare(strict_types=1);

namespace Netflie\Componentes;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;
use Netflie\Componentes\Filesystem\Directory;
use Netflie\Componentes\Filesystem\Filesystem;
use Netflie\Componentes\Filesystem\View;

class Componentes
{
    private $filesystem;
    private $registeredComponents = [];

    /**
     * Creates a new Componentes.
     *
     * @return static
     */
    public function create()
    {
        return new static();
    }

    public function component($view, string $tag = ''): ?Component
    {
        if (is_iterable($view)) {
            $this->registerViews($view);

            return null;
        }

        if ($view instanceof Component) {
            $this->registeredComponents[] = $view;

            return $view;
        }

        if (!is_string($view)) {
            throw new \Exception('Invalid view argument.');
            ;
        }

        if (Str::endsWith($view, '*')) {
            $this->registerComponents($view);
            return null;
        }

        if (!$this->viewExists($view)) {
            throw new \Exception("The view {$view} doesn't exist.");
        }

        $component = new Component($view, $tag);

        $this->registeredComponents[] = $component;

        return $component;
    }

    /**
     * @return \Netflie\Componentes\Component[]
     */
    public function registeredComponents(): array
    {
        return (new Collection($this->registeredComponents))->reverse()->unique(function (Component $component) {
            return $component->getTag();
        })->reverse()->values()->all();
    }

    /**
     * @param string $viewDirectory
     *
     * @return \Netflie\Componentes\ComponentCollection|\Netflie\Componentes\Component[]
     */
    public function registerComponents(string $viewDirectory): ComponentCollection
    {
        if (!Str::endsWith($viewDirectory, '*')) {
            throw new \Exception("View directory \"$viewDirectory\" without wildcard");
        }

        $includeSubdirectories = Str::endsWith($viewDirectory, '**.*');

        $componentDirectory = new Directory($viewDirectory, $includeSubdirectories, $this->getFilesystem());

        return $this->registerViews(
            ComponentCollection::make($componentDirectory->getFiles())
                ->map(function (SplFileInfo $file) use ($componentDirectory) {
                    return (View::createFromFile($file, $componentDirectory))->getName();
                })
        );
    }

    public function getFilesystem()
    {
        if (!$this->filesystem instanceof Filesystem) {
            $this->filesystem = new Filesystem;
        }

        return $this->filesystem;
    }

    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    private function registerViews(iterable $views): ComponentCollection
    {
        return ComponentCollection::make($views)->map(function (string $viewName) {
            return $this->component($viewName);
        });
    }

    private function viewExists($view): bool
    {
        $viewDirectory = Str::before($view, '.');
        $viewDirectory = str_replace('.', '/', $viewDirectory);

        $componentDirectory = new Directory($viewDirectory, false, $this->getFilesystem());

        return View::createFromViewName($view, $componentDirectory) instanceof View;
    }
}