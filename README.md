# Access Log

[![Latest Test](https://github.com/friendsofhyperf/http-dispatcher-enhance/workflows/tests/badge.svg)](https://github.com/friendsofhyperf/http-dispatcher-enhance/actions)
[![Latest Stable Version](https://poser.pugx.org/friendsofhyperf/http-dispatcher-enhance/version.png)](https://packagist.org/packages/friendsofhyperf/http-dispatcher-enhance)
[![Total Downloads](https://poser.pugx.org/friendsofhyperf/http-dispatcher-enhance/d/total.png)](https://packagist.org/packages/friendsofhyperf/http-dispatcher-enhance)
[![GitHub license](https://img.shields.io/github/license/friendsofhyperf/http-dispatcher-enhance)](https://github.com/friendsofhyperf/http-dispatcher-enhance)

Access log component for hyperf.

## Installation

```bash
composer require "friendsofhyperf/http-dispatcher-enhance"
```

## Usage

- Define a middleware

```php
<?php

declare(strict_types=1);

namespace App\Middleware\Auth;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FooMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler, $value1, $value2): ResponseInterface
    {
        var_dump($value1, $value2); // Suggestion: set a default for $value1 and $value2.
        return $handler->handle($request);
    }
}
```

- Set middleware parameters in route definition

```php
// config/routes.php
use App\Middleware\FooMiddleware;
use Hyperf\HttpServer\Router\Router;

Router::get('/', [\App\Controller\IndexController::class, 'index'], ['middleware' => [FooMiddleware::class, 1, 2]]);
Router::get('/', [\App\Controller\IndexController::class, 'index1'], ['middleware' => [FooMiddleware::class, 3, 4]]);
```

> Not support `@middleware` and `@middlewares` annotation yet.
