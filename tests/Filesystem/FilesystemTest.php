<?php declare(strict_types=1);

namespace Netflie\Componentes\Tests;

use Netflie\Componentes\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
    public function testFilesystemWithPathDefined()
    {
        $viewsFilesystemPath = realpath(__DIR__.'/../../views');
        $filesystem = new Filesystem($viewsFilesystemPath);

        $this->assertEquals($viewsFilesystemPath, $filesystem->getRootPath());
    }

    public function testFilesystemWithoutPathDefined()
    {
        $filesystem = new Filesystem();

        $this->assertEquals(
            '/home/casa/projects/php-ui-elements/src/Filesystem',
            $filesystem->getRootPath()
        );
    }

    public function testSetRootPath()
    {
        $filesystem = new Filesystem();

        $this->assertEquals(
            '/home/casa/projects/php-ui-elements/src/Filesystem',
            $filesystem->getRootPath()
        );

        $viewsFilesystemPath = realpath(__DIR__.'/../../views');
        $filesystem->setRootPath($viewsFilesystemPath);

        $this->assertEquals($viewsFilesystemPath, $filesystem->getRootPath());
    }
}