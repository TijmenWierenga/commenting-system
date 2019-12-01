<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use GuzzleHttp\Psr7\Response;
use function Http\Response\send;
$response = new Response(200, ['Content-Type' => 'application/json'], json_encode(['name' => 'Tijmen']));
send($response);
