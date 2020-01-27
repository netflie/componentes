<?php declare(strict_types=1);

namespace Netflie\Componentes\Tests;

use Netflie\Componentes\Filesystem\Directory;
use Netflie\Componentes\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;
use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase
{
    public function testComponentDirectoryWithoutSubdirectory()
    {
        $componentDirectory = new Directory('components.*', false, $this->getFilesystem());
        $expectedFiles = [$this->createFile(
            'components/myElement.twig',
            '',
            'myElement.twig'
        )];

        $this->assertEquals($componentDirectory->getFiles(), $expectedFiles);

        $componentDirectory = new Directory('components.alerts.*', false, $this->getFilesystem());
        $expectedFiles = [$this->createFile(
            'components/myAlert.twig',
            '',
            'myAlert.twig'
        )];

        $this->assertEquals($componentDirectory->getFiles(), $expectedFiles);
    }

    public function testComponentDirectoryWithSubdirectory()
    {
        $componentDirectory = new Directory('components.*', true, $this->getFilesystem());
        $expectedFiles[] = $this->createFile(
            'components/myAlert.twig',
            'alerts',
            'alerts/myAlert.twig'
        );
        $expectedFiles[] = $this->createFile(
            'components/myElement.twig',
            '',
            'myElement.twig'
        );


        $this->assertEquals($componentDirectory->getFiles(), $expectedFiles);
    }

    private function createFile($name, $relativePath, $relativePathName)
    {
        return new SplFileInfo($name, $relativePath, $relativePathName);
    }

    protected function getFilesystem()
    {
        $viewsFilesystemPath = realpath(__DIR__.'/../../views');

        return new Filesystem($viewsFilesystemPath);
    }
}