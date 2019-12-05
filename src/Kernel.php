<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting;

use Exception;
use League\Container\Container;
use League\Container\ReflectionContainer;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Strategy\StrategyInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TijmenWierenga\Commenting\Middleware\UnsupportedContentTypeMiddleware;
use TijmenWierenga\Commenting\Middleware\UnsupportedMediaTypeMiddleware;

class Kernel
{
    private Router $router;
    private Container $container;

    public function __construct()
    {
        $container = (new Container())->delegate(new ReflectionContainer());

        // Load all services
        require_once dirname(__DIR__) . '/config/services.php';

        /** @var StrategyInterface $strategy */
        $strategy = (new ApplicationStrategy())->setContainer($container);
        /** @var Router $router */
        $router = (new Router())->setStrategy($strategy);

        $router = $this->registerGlobalMiddleware($router, $container);

        // Load all routes
        require_once dirname(__DIR__) . '/config/routes.php';

        $this->router = $router;
        $this->container = $container;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $response = $this->router->dispatch($request);
        } catch (Exception $e) {
            $response = $this->handleException($e);
        }

        return $response;
    }

    private function registerGlobalMiddleware(Router $router, ContainerInterface $container): Router
    {
        $router->middleware($container->get(UnsupportedMediaTypeMiddleware::class));
        $router->middleware($container->get(UnsupportedContentTypeMiddleware::class));

        return $router;
    }

    private function handleException(Exception $e): ResponseInterface
    {
        if (!$this->container->has('exception_handler')) {
            throw $e;
        }

        return $this->container->get('exception_handler')($e);
    }
}
