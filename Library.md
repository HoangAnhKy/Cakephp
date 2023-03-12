Dùng để chứa những đoạn code xử lý không liên quan tới database
lưu ý:
một function không quá 30 dòng và chỉ xử lý một chức năng, không gọi class quá 4 lớp
một class không quá 500 dòng

# cách tạo library

tạo cùng cấp với controlle, và khi dùng bên controller dùng `new class` là được

```php
<?php
namespace App\Library\Business;

class PartNo{
    public function __construct()
    {

    }


}
```
