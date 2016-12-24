<?php
require_once __DIR__.'/vendor/autoload.php';

use SomeName\Example\ExampleClass;

$message = (new ExampleClass())->hiFromComposer();
echo $message;