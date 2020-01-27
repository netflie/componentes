<?php

namespace Netflie\Componentes\Filesystem;

use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class Directory
{
    public $name;

    protected $filesystem;
    protected $includeSubdirectories;

    public function __construct(
        string $name,
        bool $includeSubdirectories = false,
        Filesystem $filesystem = null
    ) {
        $this->name = Str::before($name, '.*');
        $this->includeSubdirectories = $includeSubdirectories;
        $this->filesystem = $filesystem ?? new Filesystem;
    }

    public function getAbsoluteDirectory(): string
    {
        $viewPath = str_replace('.', '/', $this->name);

        $absoluteDirectory = realpath($this->filesystem->getRootPath() . '/' . $viewPath);

        if (!is_dir($absoluteDirectory)) {
            throw new \Exception("Directory $viewPath not found.");
        }

        return $absoluteDirectory;
    }

    public function getFiles(): array
    {
        return $this->includeSubdirectories
            ? $this->allFiles($this->getAbsoluteDirectory())
            : $this->files($this->getAbsoluteDirectory());
    }

    private function files(string $directory)
    {
        return iterator_to_array(
            Finder::create()->files()->in($directory)->depth(0)->name('*.twig')->sortByName(),
            false
        );
    }

    private function allFiles(string $directory)
    {
        return iterator_to_array(
            Finder::create()->files()->in($directory)->name('*.twig')->sortByName(),
            false
        );
    }
}