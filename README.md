# Components
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/netflie/componentes/PHP%20Composer?label=tests)](https://github.com/netflie/componentes/actions?query=workflow%3A%22PHP+Composer%22) [![Quality Score](https://img.shields.io/scrutinizer/g/netflie/componentes.svg)](https://scrutinizer-ci.com/g/netflie/componentes)

This PHP package provides a server-side compiler to render custom HTML components. This package is inspired by the [BladeX](https://github.com/spatie/laravel-blade-x/) package for Laravel.

You can write this:
```php
<my-alert type="error" message="I'm an Alert" />
```
And the output will be:
```php
<div class="alert error">I'm an alert</div>
```
You can place the content of that alert in a simple Twig view that needs to be registered before using the my-alert component:
```php
<div class="alert {{ $type }}">
  {{ $message }}
</div>
```
# Installation

    composer require netflie/componentes

# Usage

### Setup
Before writting your first component you have to setup the view filesystem, the place where you component templates will be stored:

```php
<?php declare(strict_types=1);

namespace Foo\Bar;

use Netflie\Componentes\Filesystem\Filesystem;
use Netflie\Componentes\Componentes;

# Testing

$viewPath = realpath(__DIR__ . '/views'); // your project views folder
$filesystem = new Filesystem($viewPath);

$componentes = Componentes::create();
$componentes->setFilesystem($filesystem);
```

### Writing your first component

Write the content of you component in a twig template and store it in your project views folder:
```php
{{-- your_project/views/components/myAlert.twig --}}

<div class="alert {{ $type }}">
  {{ $message }}
</div>
```
And register it:
```php
$componentes->component('components.myAlert');
```

Componentes will automatically kebab-case your twig view name and use that as the tag for your component. So for the example above the tag to use your component would be `my-alert`.

You can provide a custom tag for the component:
```php
$componentes
    ->component('components.myAlert', 'my-custom-tag');
```
You also can register an entire directory of components:

```php
// This will register all views that are stored in the view path registered in the setup step
$componentes->component('components.*');

// Or multiple directories
$componentes->component([
    'components.alerts.myAlert',
    'components.myElement',
]);

// And subdirectories
$componentes->component('componentes.**.*');
```

And now you can use your custom HTML elements in your views:
```php
<h1>My view</h1>

<my-alert type="error" message="I'm an Alert" />
```
### Compiling
You need compile the HTML to render the HTML elements registered. This HTML code can come already precompilated by a framework or be read directly from an html file:

```php
<?php

namespace Foo\Bar;

use Netflie\Componentes\Compiler;

$compiler = new Compiler($componentes, $filesystem);

$ouput_html = $compiler->compile($input_html);
```
# Testing
    composer test
# Disclaimer
This is a beta version. Please, all issues and improvements are welcome!

# In progress

- Components with children
- Facade pattern
- Transforming data with view models