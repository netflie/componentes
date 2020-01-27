<?php declare(strict_types=1);

namespace Netflie\Componentes\Tests;

use PHPUnit\Framework\TestCase;
use Netflie\Componentes\Filesystem\Directory;
use Netflie\Componentes\Filesystem\Filesystem;
use Netflie\Componentes\Filesystem\View;
use Symfony\Component\Finder\SplFileInfo;

class ViewTest extends TestCase
{
    public function testGetNameFromAString()
    {
        $viewName = 'components.alerts.myAlert';
        $directory = new Directory('components.*', false, $this->getFilesystem());
        $view = View::createFromViewName($viewName, $directory);
        $this->assertEquals($viewName, $view->getName());

        $viewName = 'components.myElement';
        $view = View::createFromViewName($viewName, $directory);
        $this->assertEquals($viewName, $view->getName());
    }

    public function testGetNameFromAFile()
    {
        $directory = new Directory('components.*', false, $this->getFilesystem());
        $file = $this->createFile(
            'components/myElement.twig',
            '',
            'myElement.twig'
        );
        $view = View::createFromFile($file, $directory);

        $this->assertEquals('components.myElement', $view->getName());
    }

    protected function getFilesystem()
    {
        $viewsFilesystemPath = realpath(__DIR__.'/../../views');

        return new Filesystem($viewsFilesystemPath);
    }

    private function createFile($name, $relativePath, $relativePathName)
    {
        return new SplFileInfo($name, $relativePath, $relativePathName);
    }
}