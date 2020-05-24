<?php declare(strict_types=1);

namespace Netflie\Componentes;

class Compiler extends \Illuminate\View\Compilers\BladeCompiler
{
    /**
     * Compile the component tags.
     *
     * @param  string  $value
     * @return string
     */
    protected function compileComponentTags($value)
    {
        if (! $this->compilesComponentTags) {
            return $value;
        }

        return (new ComponentTagCompiler(
            $this->classComponentAliases, $this
        ))->compile($value);
    }
}