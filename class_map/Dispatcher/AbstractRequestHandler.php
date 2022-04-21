<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/http-dispatcher-enhance.
 *
 * @link     https://github.com/friendsofhyperf/http-dispatcher-enhance
 * @document https://github.com/friendsofhyperf/http-dispatcher-enhance/blob/1.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Hyperf\Dispatcher;

use Hyperf\Dispatcher\Exceptions\InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use function is_string;
use function sprintf;

abstract class AbstractRequestHandler
{
    /**
     * @var array
     */
    protected $middlewares = [];

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var MiddlewareInterface|object
     */
    protected $coreHandler;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param array $middlewares All middlewares to dispatch by dispatcher
     * @param MiddlewareInterface|object $coreHandler The core middleware of dispatcher
     */
    public function __construct(array $middlewares, $coreHandler, ContainerInterface $container)
    {
        $this->middlewares = array_values($middlewares);
        $this->coreHandler = $coreHandler;
        $this->container = $container;
    }

    protected function handleRequest($request)
    {
        $parameters = [];

        if (! isset($this->middlewares[$this->offset]) && ! empty($this->coreHandler)) {
            $handler = $this->coreHandler;
        } else {
            $handler = $this->middlewares[$this->offset];
            // If the middleware is a array, extract the class and parameters. ex: [FooMiddleware::class, $value1, $value2]
            is_array($handler) && [$handler, $parameters] = [array_shift($handler), $handler];
            // If the middleware is a string, we will resolve it out of the container.
            is_string($handler) && $handler = $this->container->get($handler);
        }

        if (! method_exists($handler, 'process')) {
            throw new InvalidArgumentException(sprintf('Invalid middleware, it has to provide a process() method.'));
        }

        return $handler->process($request, $this->next(), ...$parameters);
    }

    protected function next(): self
    {
        ++$this->offset;
        return $this;
    }
}
