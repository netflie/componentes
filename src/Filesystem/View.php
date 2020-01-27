<?php

namespace Netflie\Componentes\Filesystem;

use Illuminate\Support\Str;
use Netflie\Componentes\Filesystem\Directory;
use Symfony\Component\Finder\SplFileInfo;

class View
{
    private $name;

    private function __construct()
    {
        // to enforce to use factory methods
    }

    public static function createFromFile(SplFileInfo $file, Directory $directory)
    {
        $instance = new self;

        $subDirectory = $file->getRelativePath();
        $view = Str::replaceLast('.twig', '', $file->getFilename());

        $instance->name = implode('.', array_filter([
            $directory->name,
            $subDirectory,
            $view,
        ]));

        return $instance;
    }

    public static function createFromViewName(string $name, Directory $directory)
    {
        $instance = new self;

        $viewPath = Str::after($name, '.');
        $viewPath = str_replace('.', '/', $viewPath);
        $viewPath = $directory->getAbsoluteDirectory() . '/' . $viewPath . '.twig';

        if (!file_exists($viewPath)) {
            throw new \Exception("View \"$name\" in path \"$viewPath\" not found.");
        }

        $instance->name = $name;

        return $instance;
    }

    public function getName(): string
    {
        return $this->name;
    }
}