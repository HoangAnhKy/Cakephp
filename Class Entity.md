dùng để chỉnh attributes sau đó hiện thị trên view, mục đích là để ít xửa lý trên view.
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
