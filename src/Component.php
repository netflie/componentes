<?php declare(strict_types=1);

namespace Netflie\Componentes;

use Illuminate\Support\Str;

class Component
{
    private $tag;

    private $view;

    public static function make(string $view, string $tag = '')
    {
        return new static($view, $tag);
    }

    public function __construct(string $view, string $tag = '')
    {
        $this->tag = $tag;
        $this->view = $view;
    }

    public function getTag(): string
    {
        $tag = empty($this->tag) ? $this->defaultTag() : $this->tag;

        return $tag;
    }

    public function getView(): string
    {
        return $this->view;
    }

    private function defaultTag()
    {
        $baseComponentName = explode('.', $this->view);
        $tag = Str::kebab(end($baseComponentName));

        return $tag;
    }
}