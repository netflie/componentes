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

        $expectedPath = realpath(__DIR__ . '/../../src/Filesystem');

        $this->assertEquals(
            $expectedPath,
            $filesystem->getRootPath()
        );
    }

    public function testSetRootPath()
    {
        $filesystem = new Filesystem();

        $expectedPath = realpath(__DIR__ . '/../../src/Filesystem');

        $this->assertEquals(
            $expectedPath,
            $filesystem->getRootPath()
        );

        $viewsFilesystemPath = realpath(__DIR__.'/../../views');
        $filesystem->setRootPath($viewsFilesystemPath);

        $this->assertEquals($viewsFilesystemPath, $filesystem->getRootPath());
    }
}