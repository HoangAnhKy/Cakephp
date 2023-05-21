-   Vào đường dẫn sau để cấu hình lại apacher
-   `C:\xampp\apache\conf\extra\httpd-vhosts.conf`

```sh
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/internphp/kyha/webroot"
    ServerName testapp.local
    # thêm ssl nếu muốn dùng. Thay port 80 thành 443 và chỉnh đường đẫn vào thư mục chứ file conf ssl
    # muốn tạo ssl nhớ coi lại mkcert
    # SSLEngine on
    # SSLCertificateFile "crt/site.test/server.crt"
    # SSLCertificateKeyFile "crt/site.test/server.key"
</VirtualHost>

```

-   sau đó vào host lưu lại tên serverName

-   `C:\Windows\System32\drivers\etc\hosts` > thêm mới đường dẫn
