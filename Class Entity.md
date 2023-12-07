dùng để chỉnh attributes sau đó hiện thị trên view, mục đích là để ít xửa lý trên view. Hoặc có thể set lại data lưu vào database
ví dụ với đối tượng Course

```php
<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Course extends Entity
{
   protected $_virtual = ['created_day']; // dùng để hiện thị trên dump die
   public function initialize(array $config): void
   {

   }
   protected function _getCreatedDay(){ // dùng cho view
       return $this->created_at->format('d/m/Y');
   }
}
```

- ví dụ set lại mã hóa password
```php
   <?php
   namespace App\Model\Entity;
   
   use Authentication\PasswordHasher\DefaultPasswordHasher; // Add this line
   use Cake\ORM\Entity;
   
   class User extends Entity
   {
       // Add this method
       protected function _setPassword(string $password) : ?string
       {
           if (strlen($password) > 0) {
               return (new DefaultPasswordHasher())->hash($password);
           }
       }
   }
```
