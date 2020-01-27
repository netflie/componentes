<?php declare(strict_types=1);

namespace Netflie\Componentes\Tests;

use PHPUnit\Framework\TestCase;
use Netflie\Componentes\Component;
use Netflie\Componentes\Componentes;
use Netflie\Componentes\Filesystem\Filesystem;

class ComponentesTest extends TestCase
{
    public function testRegisterComponentWithTag()
    {
        $componentes = new Componentes;
        $componentes->setFilesystem($this->getFilesystem());

        $registeredComponent = $componentes->component('components.myElement', 'my-tag');

        $this->assertEquals(new Component('components.myElement', 'my-tag'), $registeredComponent);
        $this->assertEquals([new Component('components.myElement', 'my-tag')], $componentes->registeredComponents());
    }

    public function testRegisterComponentWithoutTemplate()
    {
        $componentes = new Componentes;
        $componentes->setFilesystem($this->getFilesystem());

        $this->expectException(\Exception::class);
        $registeredComponent = $componentes->component('components.myFakeComponent');
    }

    public function testRegisterComponentWithoutTag()
    {
        $componentes = new Componentes;
        $componentes->setFilesystem($this->getFilesystem());
        $registeredComponent = $componentes->component('components.myElement');

        $this->assertEquals(new Component('components.myElement'), $registeredComponent);
        $this->assertEquals([new Component('components.myElement')], $componentes->registeredComponents());
    }

    public function testRegisterComponentFromADirectory()
    {
        $componentes = new Componentes;
        $componentes->setFilesystem($this->getFilesystem());
        $registeredComponent = $componentes->component('components.*');

        $this->assertNull($registeredComponent);
        $this->assertNotEmpty($componentes->registeredComponents());
        $this->assertEquals([new Component('components.myElement')], $componentes->registeredComponents());
    }

    public function testRegisterComponentWithSubdirectories()
    {
        $componentes = new Componentes;
        $componentes->setFilesystem($this->getFilesystem());
        $registeredComponent = $componentes->component('components.**.*');

        $this->assertNull($registeredComponent);
        $this->assertNotEmpty($componentes->registeredComponents());
        $this->assertEquals([
            new Component('components.alerts.myAlert'),
            new Component('components.myElement'),
        ], $componentes->registeredComponents());
    }

    public function testRegisterComponentFromAComponentSubDirectory()
    {
        $componentes = new Componentes;
        $componentes->setFilesystem($this->getFilesystem());
        $registeredComponent = $componentes->component('components.alerts.myAlert');

        $this->assertNotEmpty($componentes->registeredComponents());

        $this->assertEquals(new Component('components.alerts.myAlert'), $registeredComponent);
        $this->assertEquals([new Component('components.alerts.myAlert')], $componentes->registeredComponents());
    }

    public function testRegisterArrayOfComponents()
    {
        $componentes = new Componentes;
        $componentes->setFilesystem($this->getFilesystem());
        $registeredComponent = $componentes->component([
            'components.alerts.myAlert',
            'components.myElement',
        ]);

        $this->assertNull($registeredComponent);
        $this->assertNotEmpty($componentes->registeredComponents());
        $this->assertEquals([
            new Component('components.alerts.myAlert'),
            new Component('components.myElement'),
        ], $componentes->registeredComponents());
    }

    public function testRegisterComponentFromAComponentInstance()
    {
        $componentes = new Componentes;
        $componentes->setFilesystem($this->getFilesystem());
        $component = new Component('components.myElement');

        $registeredComponent = $componentes->component($component);

        $this->assertEquals($registeredComponent, $component);
        $this->assertEquals([$registeredComponent], $componentes->registeredComponents());
    }

    protected function getFilesystem(): Filesystem
    {
        $viewsFilesystemPath = realpath(__DIR__.'/../views');

        return new Filesystem($viewsFilesystemPath);
    }
}