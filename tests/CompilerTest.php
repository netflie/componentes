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


        $viewContents = '<html><my-alert type="error" message="I\'m an Alert" /></html>';
        $compiledContents = $compiler->compile($viewContents);

        $this->assertSame('<html><div class="error">I\'m an Alert</div></html>', $compiledContents);
    }

    public function testCompilerWithOpeningAndClosingTags()
    {

        $componentes = new Componentes;
        $componentes->setFilesystem($this->getFilesystem());
        $registeredComponent = $componentes->component('components.alerts.myAlert');

        $compiler = new Compiler($componentes, $this->getFilesystem());


        $viewContents = '<html><my-alert type="error" message="I\'m an Alert"></my-alert></html>';
        $compiledContents = $compiler->compile($viewContents);

        $this->assertSame('<html><div class="error">I\'m an Alert</div></html>', $compiledContents);
    }

    protected function getFilesystem(): Filesystem
    {
        $viewsFilesystemPath = realpath(__DIR__.'/../views');

        return new Filesystem($viewsFilesystemPath);
    }
}