<?php declare(strict_types=1);

namespace Netflie\Componentes;

class ComponentTagCompiler extends \Illuminate\View\Compilers\ComponentTagCompiler
{
    /**
     * Guess the class name for the given component.
     *
     * @param  string  $component
     * @return string
     */
    public function guessClassName(string $component)
    {
        // TO-DO
        return null;
    }
}