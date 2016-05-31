<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Symfony\Cmf\Bundle\FileEditorBundle\Example\app\AppKernel;

require(__DIR__ . '/../../vendor/autoload.php');

Debug::enable();
require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
