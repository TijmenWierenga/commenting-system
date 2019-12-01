<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use GuzzleHttp\Psr7\ServerRequest;
use TijmenWierenga\Commenting\Kernel;
use function Http\Response\send;

$request = ServerRequest::fromGlobals();

$response = (new Kernel())->handle($request);

send($response);
