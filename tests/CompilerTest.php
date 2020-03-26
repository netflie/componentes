<?php declare(strict_types=1);

namespace Netflie\Componentes\Tests;

use Netflie\Componentes\Compiler;
use Netflie\Componentes\Componentes;
use Netflie\Componentes\Filesystem\Filesystem;

use PHPUnit\Framework\TestCase;

class CompilerTest extends TestCase
{
    public function testCompilerWithSelfClosingTags()
    {

        $componentes = new Componentes;
        $componentes->setFilesystem($this->getFilesystem());
        $registeredComponent = $componentes->component('components.alerts.myAlert');

        $compiler = new Compiler($componentes, $this->getFilesystem());


        $viewContents = '<html><my-alert class="red" message="I\'m an Alert" /></html>';
        $compiledContents = $compiler->compile($viewContents);

        $compiledContents = preg_replace('/\s+/', '', $compiledContents);
        $this->assertSame("<html><div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4' role='alert'><p>I'm an Alert</p></div></html>", $compiledContents);
    }

    public function testCompilerWithOpeningAndClosingTags()
    {

        $componentes = new Componentes;
        $componentes->setFilesystem($this->getFilesystem());
        $registeredComponent = $componentes->component('components.alerts.myAlert');

        $compiler = new Compiler($componentes, $this->getFilesystem());


        $viewContents = '<html><my-alert class="orange" message="I\'m an Alert"></my-alert></html>';
        $compiledContents = $compiler->compile($viewContents);
        $compiledContents = preg_replace('/\s\s+/', '', $compiledContents);

        $this->assertSame("<html><div class='bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4' role='alert'><p>I'm an Alert</p></div></html>", $compiledContents);
    }

    protected function getFilesystem(): Filesystem
    {
        $viewsFilesystemPath = realpath(__DIR__.'/../views');

        return new Filesystem($viewsFilesystemPath);
    }
}
