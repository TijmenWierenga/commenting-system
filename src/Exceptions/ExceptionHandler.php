<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;

final class ExceptionHandler
{

    /**
     * @var array|Handler[]
     */
    private array $handlers;

    public function __construct(Handler ...$handlers)
    {
        $this->handlers = $handlers;
    }

    public function __invoke(Exception $e): ResponseInterface
    {
        $handler = $this->getSupportedHandler($e);

        return $handler->handle($e);
    }

    private function getSupportedHandler(Exception $e): Handler
    {
        foreach ($this->handlers as $handler) {
            if (!$handler->handles($e)) {
                continue;
            }

            return $handler;
        }

        throw $e;
    }
}
