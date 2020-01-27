<?php

namespace Netflie\Componentes\Filesystem;

class Filesystem
{
    protected $rootPath;

    public function __construct(string $rootPath = '')
    {
        $this->setRootPath($rootPath);
    }

    public function setRootPath(string $rootPath): void
    {
        $this->rootPath = empty($rootPath) ? __DIR__ : $rootPath;
    }

    public function getRootPath(): string
    {
        return $this->rootPath;
    }
}