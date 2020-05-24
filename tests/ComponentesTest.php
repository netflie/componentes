<?php declare(strict_types=1);

namespace Netflie\Componentes\Tests;

use Netflie\Componentes\Componentes;
use PHPUnit\Framework\TestCase;

class ComponentesTest extends TestCase
{
    private static $componentes;

    public static function setUpBeforeClass(): void
    {
        static::$componentes = Componentes::create(
            __DIR__ . '/../views/'
        );
    }

    public function test_components_with_anonymous_component()
    {

        $html = '<html><x-alert type="error" message="Hi there!"/></html>';

        $compiled = static::$componentes->render($html);

        $this->assertEquals(
            '<html> <div class="alert alert-error">Hi there!</div> </html>',
            $compiled
        );
    }

    public function test_componentes_with_nested_components()
    {
        $html = '<html>'
                  .'<x-grid.row>'
                    .'<x-grid.column>'
                      .'<x-grid.row>Hi Componentes!</x-grid.row>'
                    .'</x-grid.column>'
                  .'</x-grid.row>'
              .'</html>';


        $compiled = static::$componentes->render($html);

        $this->assertEquals(
            '<html> <div class="row"><div class="column"><div class="row">Hi Componentes!</div></div></div> </html>',
            $compiled
        );
    }
}