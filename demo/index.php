<?php

require __DIR__ . '/../vendor/autoload.php';

use Netflie\Componentes\Filesystem\Filesystem;
use Netflie\Componentes\Componentes;
use Netflie\Componentes\Compiler;

$viewPath = realpath(__DIR__ . '/../views'); // your project views folder
$filesystem = new Filesystem($viewPath);

$componentes = Componentes::create();
$componentes->setFilesystem($filesystem);

$componentes->component('components.alerts.myAlert');


$compiler = new Compiler($componentes, $filesystem);

$input_html = "
  <my-alert class='orange' title='Be warned' message='Something not ideal might be happening.'></my-alert>
  <my-alert class='blue' title='Information message' message='Some additional text to explain said message.'></my-alert>
  <my-alert class='red' title='Danger' message='Something <strong>not ideal</strong> might be happening.'></my-alert>
  <my-alert class='teal' message='This is a success alert <strong>without a title</strong>'></my-alert>
";
$ouput_html = $compiler->compile($input_html);

?>

<html>
  <head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.2.0/tailwind.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <div class="w-full max-w-screen-xl mx-auto px-6 mt-8">
      <h1 class="mb-8 text-3xl text-center font-bold">Hello Components!</h1>
      <?= $ouput_html ?>
    </div>
  </body>
</html>
