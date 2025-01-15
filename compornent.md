# Khởi tạo Component

`src/Controller/component`

```php
<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

class ExampleComponent extends Component
{
    public function sayHello($name = 'World')
    {
        return "Hello, " . $name . "!";
    }
}
```

# Sử dụng

```php
// src/Controller/ArticlesController.php
namespace App\Controller;

use App\Controller\AppController;

class ArticlesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        // Load ExampleComponent
        $this->loadComponent('Example');
    }

    public function index()
    {
        $greeting = $this->Example->sayHello('CakePHP');
        $this->set(compact('greeting'));
    }
}
```
