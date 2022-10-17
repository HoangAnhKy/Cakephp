#### nên kiểm tra điều kiện tồn tại, flag của giá trị trong database

#### Cách truyền dữ liệu lên praram của router

```php
    $builder->connect(
            '/{id}',
            ['controller' => 'Pages', 'action' => 'display', 'home'],
            ['id' => '\d+', 'pass' => ['id']]);
```

#### Cài đặt mới

`composer create-project --prefer-dist cakephp/app:~4.0 app`

#### Cài đặt debug

`php composer.phar require cakephp/debug_kit`

#### Cài đặt controller, model với bake

-   khi tạo bằng bake nó sẽ hỗ trợ nhiều thứ như có sẵn các hàm cần thiết đối với controller, validation cũng như khai báo các chi tiết quan trọng ở database ở model, ...

-   lệnh tạo controller: `bin/cake bake controller `
-   lệnh tạo model: `bin/cake bake model `

#### dùng stack/push của laravel trên cakephp

```php
$this->start('css');
    echo $this->Html->css($customCss, ['type' => 'text/css', 'media' => 'all']);
$this->end();
$this->start('script');
    echo $this->Html->script($customJscript, ['text/javascript']);
$this->end();
```

#### dùng yield trong cakephp

```php
   $this->extend('link');
```

#### Đặt tên route

```php
        $builder->connect('/listuser', ['controller' => 'User', 'action' => 'listuser'], ['_name' => 'listuser']);
```

#### Tạo form với heper

```php
<?= $this->Form->create($option); ?>
// <?= $this->Form->Control('Email',

//     [
//          'class' => ($this->Form->isFieldError('Email')) ? 'form-control is-invalid' : 'form-control', // dùng boostrap để cấu hình nó đẹp hơn
//          'type' => 'email',
//          'placeholder' => "Enter your email"
//      ]) ?>
<?= $this->Form->end(); ?>

// custom form validation in view
 <?= $this->Form->create($users) ?>

```

#### sài slug

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

#### flas session

```php
    $this->Flash->error('Fail!');// khai báo
    <?php echo $this->Flash->render(); ?> // lấy
```
