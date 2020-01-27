<?php declare(strict_types=1);

namespace Netflie\Componentes;

use Illuminate\Support\Str;
use Netflie\Componentes\Filesystem\Filesystem;

class Compiler
{
    protected $componentes;
    protected $filesystem;

    public function __construct(Componentes $componentes, Filesystem $filesystem = null)
    {
        $this->componentes = $componentes;
        $this->filesystem = $filesystem ?? new Filesystem;
    }

    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function compile(string $viewContents): string
    {
        return array_reduce(
            $this->componentes->registeredComponents(),
            [$this, 'parseComponentHtml'],
            $viewContents
        );
    }

    public function parseComponentHtml(string $viewContents, Component $component): string
    {
        $viewContents = $this->parseSelfClosingTags($viewContents, $component);
        $viewContents = $this->parseOpeningTags($viewContents, $component);
        $viewContents = $this->parseClosingTags($viewContents, $component);

        return $viewContents;
    }

    protected function parseSelfClosingTags(string $viewContents, Component $component): string
    {
        $pattern = "/<\s*{$component->getTag()}\s*(?<attributes>(?:\s+[\w\-:]+(=(?:\\\"[^\\\"]+\\\"|\'[^\']+\'|[^\'\\\"=<>]+))?)*\s*)\/>/";

        return preg_replace_callback($pattern, function (array $matches) use ($component) {
            $attributes = $this->getAttributesFromAttributeString($matches['attributes']);

            return $this->componentString($component, $attributes);
        }, $viewContents);
    }

    protected function parseOpeningTags(string $viewContents, Component $component): string
    {
        $pattern = "/<\s*{$component->getTag()}(?<attributes>(?:\s+[\w\-:]+(=(?:\\\"[^\\\"]*\\\"|\'[^\']*\'|[^\'\\\"=<>]+))?)*\s*)(?<![\/=\-])>/";

        return preg_replace_callback($pattern, function (array $matches) use ($component) {
            $attributes = $this->getAttributesFromAttributeString($matches['attributes']);

            return $this->componentString($component, $attributes);
        }, $viewContents);
    }

    protected function parseClosingTags(string $viewContents, Component $component): string
    {
        $pattern = "/<\/\s*{$component->getTag()}\s*>/";

        return preg_replace($pattern, '', $viewContents);
    }

    protected function componentString(Component $component, array $attributes = []): string
    {
        $viewPath = str_replace('.', '/', $component->getView());

        return (new Renderer($this->filesystem->getRootPath()))
            ->render($viewPath . '.twig', $attributes);
    }

    protected function getAttributesFromAttributeString(string $attributeString): array
    {
        $attributeString = $this->parseBindAttributes($attributeString);

        $pattern = '/(?<attribute>[\w:-]+)(=(?<value>(\"[^\"]+\"|\\\'[^\\\']+\\\'|[^\s>]+)))?/';

        if (! preg_match_all($pattern, $attributeString, $matches, PREG_SET_ORDER)) {
            return [];
        }

        return collect($matches)->mapWithKeys(function ($match) {
            $attribute = Str::camel($match['attribute']);
            $value = $match['value'] ?? null;

            if (is_null($value)) {
                $value = 'true';
                $attribute = Str::start($attribute, 'bind:');
            }

            $value = $this->stripQuotes($value);

            if (Str::startsWith($attribute, 'bind:')) {
                $attribute = Str::after($attribute, 'bind:');
            }

            return [$attribute => $value];
        })->toArray();
    }

    protected function parseBindAttributes(string $attributeString): string
    {
        return preg_replace("/\s*:([\w-]+)=/m", ' bind:$1=', $attributeString);
    }

    protected function stripQuotes(string $string): string
    {
        if (Str::startsWith($string, ['"', '\''])) {
            return substr($string, 1, -1);
        }

        return $string;
    }
}