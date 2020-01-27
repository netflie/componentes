<?php declare(strict_types=1);

namespace Netflie\Componentes;

class Renderer extends \Twig\Environment
{
    public function __construct(string $views_path)
    {
        $loader = new \Twig\Loader\FilesystemLoader(realpath($views_path));

        parent::__construct($loader);
    }
}