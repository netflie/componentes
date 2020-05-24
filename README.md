
# Components
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/netflie/componentes/PHP%20Composer?label=tests)](https://github.com/netflie/componentes/actions?query=workflow%3A%22PHP+Composer%22) [![Quality Score](https://img.shields.io/scrutinizer/g/netflie/componentes.svg)](https://scrutinizer-ci.com/g/netflie/componentes)

This PHP package provides a server-side compiler to render custom HTML components. ~~This package is inspired by the [BladeX](https://github.com/spatie/laravel-blade-x/) package for Laravel.~~ From the new 1.0.0 version this package uses the **Laravel Blade Compiler**. You can now use all the functionalities (non-anonymous components are not supported yet) provided by [Laravel Blade Components](https://laravel.com/docs/7.x/blade#components).

You can write this:
```php
<my-alert type="error" message="I'm an Alert" />
```
And the output will be:
```php
<div class="alert error">I'm an alert</div>
```
You can place the content of that alert in a simple Blade view that needs to be registered before using the my-alert component:
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

use Netflie\Componentes\Componentes;

$viewPath = realpath(__DIR__ . '/views'); // your project views folder

$componentes = Componentes::create($viewPath);
```

### Writing your first component

Write the content of you component in a Blade template and store it in your project view components folder:
```php
{{-- your_project/views/components/alert.blade.php --}}

<div class="alert {{ $type }}">
  {{ $message }}
</div>
```
All anonymous components are automatically discovered within the `your_project/views/components` directory.

And now you can use your custom HTML elements in your views:
```php
<h1>My view</h1>

<x-alert type="error" message="I'm an Alert" />
```
### Compiling
You need compile the HTML to render the HTML elements registered. This HTML code can come already precompilated by a framework or be read directly from an html file:

```php
<?php

namespace Foo\Bar;
$input_html = '<html>'
               .'My webpage code precompiled by my own framework'
               .'<x-alert/>'
            .'</html>';
$ouput_html = $componentes->render($input_html);
```

### More documentation
Check the Laravel Blade Components [documentation](https://laravel.com/docs/7.x/blade#components) to full functionalities.

# Testing
    composer test

# In progress

- Class based component