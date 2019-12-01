<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting;

use League\Container\Container;
use League\Container\ReflectionContainer;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Strategy\StrategyInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Kernel
{
    private Router $router;

    public function __construct()
    {
        $container = (new Container())->delegate(new ReflectionContainer);

        // Load all services
        require_once dirname(__DIR__) . '/config/services.php';

        /** @var StrategyInterface $strategy */
        $strategy = (new ApplicationStrategy())->setContainer($container);
        /** @var Router $router */
        $router = (new Router())->setStrategy($strategy);

        // Load all routes
        require_once dirname(__DIR__) . '/config/routes.php';

        $this->router = $router;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->router->dispatch($request);
    }
}
