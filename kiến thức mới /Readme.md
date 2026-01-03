
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

# kế  thừa layout khác 

khởi tạo 1 layout default khác 

```php
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>default 2</h1>
<?= $this->fetch('content2') ?>
</body>
</html>
```

sau đó bên view gọi qua để  khế  thừa

```php
<?php $this->extend('/layout/default2'); ?>
<?php $this->start('content2'); ?>
    <h1>Page 2</h1>
    <p>Nội dung trang 2</p>
<?php $this->end(); ?>
```

hoặc là gọi trong controller 

```php
public function home()
{
    $this->viewBuilder()->setLayout('default2');
}
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

# khi khởi tạo controllerm nếu có view thì view chính là tên của controller đó. Chú ý cách đặt tên
# với file error 400 hoặc 500 có thể  custom lại cho nó đẹp bằng cách chỉnh sửa giao diện trong đó.

```php
// error500.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $this->fetch('title') ?: 'Debug Internal Error' ?></title>
    <style>
        body {
            font-family: "Courier New", Courier, monospace;
            background-color: #1e1e1e;
            color: #f8f8f8;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #e74c3c;
            font-size: 36px;
        }
        .error-message {
            background-color: #2e2e2e;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        pre {
            background-color: #111;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .back-link a {
            color: #3498db;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<h1>Internal Error (500)</h1>
<div class="error-message">
    <strong>Error:</strong> <?= h($message) ?>
</div>

<?php if ($this->fetch('file')): ?>
    <h2>Debug Info:</h2>
    <?= $this->fetch('file') ?>
<?php endif; ?>

<div class="back-link">
    <p><a href="<?= $this->Url->build('/') ?>">Return to Homepage</a></p>
</div>
</body>
</html>
```