-   Vào đường dẫn sau để cấu hình lại apacher
-   `C:\xampp\apache\conf\extra\httpd-vhosts.conf`

```sh
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/internphp/kyha/webroot"
    ServerName testapp.local
</VirtualHost>

```

-   sau đó vào host lưu lại tên serverName

-   `C:\Windows\System32\drivers\etc\hosts` > thêm mới đường dẫn
