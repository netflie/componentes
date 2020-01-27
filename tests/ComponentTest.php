<?php declare(strict_types=1);

namespace Netflie\Componentes\Tests;

use PHPUnit\Framework\TestCase;
use Netflie\Componentes\Component;

class ComponentTest extends TestCase
{
    public function testComponentWithExplicitTag()
    {
        $view = "myAlert";
        $tag = "my-alert";
        $component = new Component($view, $tag);

        $this->assertSame($component->getTag(), $tag);
        $this->assertSame($component->getView(), $view);
    }

    public function testComponentWithImplicitTag()
    {
        $view = "myAlert";
        $component = new Component($view);

        $this->assertSame($component->getTag(), 'my-alert');
        $this->assertSame($component->getView(), $view);
    }

    public function testmakeComponent()
    {
        $view = "myNotice";
        $tag = "my-custom-alert-tag";
        $component = Component::make($view, $tag);

        $this->assertSame($component->getTag(), $tag);
        $this->assertSame($component->getView(), $view);
    }
}