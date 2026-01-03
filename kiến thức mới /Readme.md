
# Cách truyền dữ liệu lên praram của router

```php

    // routes
    $builder->connect(
            '/{id}',
            ['controller' => 'Pages', 'action' => 'display', 'home'],
            ['id' => '\d+', 'pass' => ['id']]);
```
## đặt tên cho router
```php
        $builder->connect('/listuser', ['controller' => 'User', 'action' => 'listuser'], ['_name' => 'listuser']);
```
## sài slug

```php
// route
    $builder->connect(
            '/:slug',
            ['controller' => 'User', 'action' => 'view'],
            [
                'pass' => ['slug'],
                'slug' => '[^\/]+' // Taken from your example
            ]
        );

//controller

// dd($this->request->getParam('slug'));
```
# Cài đặt mới

`composer create-project --prefer-dist cakephp/app:~4.0 app`

# thêm define

-   tạo define trong config

-   vào bootstrap khai báo nó `require __DIR__ . '/define.php';`
-   

# dùng stack/push của laravel trên cakephp

```php
$this->start('css');
    echo $this->Html->css($customCss, ['type' => 'text/css', 'media' => 'all']);
$this->end();
$this->start('script');
    echo $this->Html->script($customJscript, ['text/javascript']);
$this->end();
```

bên file deafault phải có dữ liệu sau thì nó mới hoạt động

```php
$this->fetch('css')
$this->fetch('script')
```

# dùng yield trong cakephp

```php
   $this->extend('link');
```

# flas session

```php
    $this->Flash->error('Fail!');// khai báo ở code
    <?php echo $this->Flash->render(); ?> // lấy ở màn hình
```