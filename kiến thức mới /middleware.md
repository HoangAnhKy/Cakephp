# khởi tạo middleware

```php
// src/Middleware/SimpleMiddleware.php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SimpleMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        \Cake\Log\Log::debug('Before controller');

        $response = $handler->handle($request);

        \Cake\Log\Log::debug('After controller');

        return $response;
    }
}
```

# khai báo global cho tất cả

```php
// src/Application.php
use App\Middleware\SimpleMiddleware;

public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
{
    $middlewareQueue->add(new SimpleMiddleware());

    return $middlewareQueue;
}
```

# khai báo cho mỗi group route

```php
<?php
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use App\Middleware\SimpleMiddleware;

return function (RouteBuilder $routes): void {
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {

        // 1. đăng ký middleware
        $builder->registerMiddleware('simple', new SimpleMiddleware());
        // 2. áp dụng cho scope này
        $builder->applyMiddleware('simple');

        $builder->connect('/', ['controller' => 'Pages','action' => 'home']);

        $builder->fallbacks();
    });
};
```

# đăng ký cho 1 URL duy nhất

```php
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use App\Middleware\SimpleMiddleware;

return function (RouteBuilder $routes): void {
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {
        // đăng ký middleware
        $builder->registerMiddleware('simple', new SimpleMiddleware());

        // áp dụng cho DUY NHẤT route này
        $builder->connect(
            '/',
            ['controller' => 'Pages', 'action' => 'home'],
            ['middleware' => ['simple']]
        );

        $builder->fallbacks();
    });
};
```