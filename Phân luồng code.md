nếu có file chạy chung mà muốn phân ra user với admin.

-   Vào plugin khởi tao thư mục chứa file
    ![image](/img/add%20dir%20admin%20in%20plugin.png)

-   Sau đó vào `src/view/Application` để khai báo thư mục đã khởi tạo bên trên

```php
 public function bootstrap()
{
    // ...
    $this->addPlugin('Dashboard', ['routes' => true]);
    // ...
}
```

-   Sau đó vào `composer` khai báo thư mục đã tạo và dẫn nó vào src

```php
"autoload": {
        "psr-4": {
            //...
            "Dashboard\\": "./plugins/Dashboard/src/"
        }
    }
```

-   Tại router trong plugin chúng ta khai báo thêm như sau

```php
Router::plugin(
    'Dashboard',
    ['path' => '/admin'],
    function (RouteBuilder $routes) {
       // vd $routes->connect('/logout', ['controller' => 'Login', 'action' => 'logout']);
    }
);
```
