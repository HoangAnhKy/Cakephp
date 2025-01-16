# lệnh khởi tạo

```sh
bin/cake bake plugin name
```

# kiểm tra file 

```php
// src/Application.php
public function bootstrap(): void
{
    parent::bootstrap();

    // Tải plugin
    $this->addPlugin('MyPlugin');
}
```

```json
"autoload": {
        "psr-4": {
            "App\\": "src/",
            "plugin name\\": "./plugins/plugin name/src/"
        }
    },
```
tất cả config của plugin nằm trong `name_plugin/src/plugin.php`