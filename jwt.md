[Tham khảo JWT Cakephp 4](https://book.cakephp.org/4/en/tutorials-and-examples/cms/authentication.html)

# Để tạo JWT cho website với cakephp thì phải làm như sau

B1: Trong `src/Application.php`, thêm các mục nhập sau:
- Sử dụng các thư viện sau:
```php
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Routing\Router;
use Psr\Http\Message\ServerRequestInterface
```
- Thêm xác thực `implements AuthenticationServiceProviderInterface` vào class
```php
class Application extends BaseApplication
    implements AuthenticationServiceProviderInterface
{

}
```
- Tại function `middleware` thêm quyền cho Authentication
```php
  public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
{
    $middlewareQueue
        // ...
        ->add(new AuthenticationMiddleware($this));

    return $middlewareQueue;
}
```
- Thêm function `getAuthenticationService`
```php
public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
{
    $resolver = [
        'className' => 'Authentication.Orm',
        'userModel' => 'Auth',
    ];

    $fields =  [
        'username' => 'email',
        'password' => 'password',
    ];

    $authenticationService = new AuthenticationService([
        'unauthenticatedRedirect' => Router::url('/users/login'),
        'queryParam' => 'redirect',
    ]);

    $authenticationService->loadIdentifier('Authentication.Password', [
//        'resolver' => $resolver, // dùng khi khác table Users
        'fields' => $fields
    ]);

    $authenticationService->loadAuthenticator('Authentication.Session');

    $authenticationService->loadAuthenticator('Authentication.Form', [
        'fields' => $fields
        'loginUrl' => Router::url('/users/login'),
    ]);

    return $authenticationService;
}
```

B2: Trong `AppController` thêm đoạn mã sau để thêm thư viện vào web

```php
public function initialize(): void
{
    parent::initialize();
    // ...

    $this->loadComponent('Authentication.Authentication');
}
```
B3: Trong `UsersController` thêm đoạn mã sau:

```php
public function beforeFilter(\Cake\Event\EventInterface $event)
{
    parent::beforeFilter($event);
    // bỏ qua bước kiểm tra tài khoản
    $this->Authentication->addUnauthenticatedActions(['login']);
}

public function login()
{
    $this->request->allowMethod(['get', 'post']);
    $result = $this->Authentication->getResult();
    // nếu có dữ và hợp lệ
    if ($result && $result->isValid()) {

        $redirect = $this->request->getQuery('redirect', [
            'controller' => 'Articles',
            'action' => 'index',
        ]);

        return $this->redirect($redirect);
    }
    // không hợp lệ
    if ($this->request->is('post') && !$result->isValid()) {
        $this->Flash->error(__('Invalid username or password'));
    }
}

public function logout()
{
    $result = $this->Authentication->getResult();

    if ($result && $result->isValid()) {
        $this->Authentication->logout();
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }
}
```
**Lưu ý**
- $this->Authentication->getResult() : dùng để lấy accound đang đăng nhập
- $this->Authentication->logout()    : dùng để xóa tài khoản đang đăng nhập
***
 # **SSO**
- Để thêm SSO đăng nhập một lần sài cho các ứng dụng con thì chúng ta sẽ thêm token
B1: tạo key mã hóa
```php
    hash('sha256', 'key') // để tạo mã hóa dùng chung
```
B2: Trong hàm `Login` thêm các thông tin của user đăng nhập vào và mã hóa nó. Có thể cùng chung với [jwt](#để-tạo-jwt-cho-website-với-cakephp-thì-phải-làm-như-sau)
```php
// use Firebase\JWT\JWT;

// mã hóa lại user tokent
$token_user = JWT::encode([
                'code' => time().uniqid(),
                'login_success' => 1,
            ], 'key','HS256');

// mã hóa token để gắn vào cookie
$token = Security::encrypt($token_user, Key);  // với Key là key mã hóa ở bước 1

// set nó vào Domain mong muốn
setcookie("auth_sys", $token, time() + 86400 * 3600, "/", DOMAIN); // cookie hoạt động trong 1 tiếng

/*
    setcookie(
        string $name,
        string $value = "",
        int $expires_or_options = 0,
        string $path = "",
        string $domain = "",
        bool $secure = false,
        bool $httponly = false
    ): bool
*/
```
B3: Bên phía Domain con muốn đọc tài khoản thì phải kiểm tra có `cookie` không, nếu có thì lấy không thì cho người ta đăng nhập lại
```php
        if ($_COOKIE['auth_sys']){
            $token_decrypt = Security::decrypt($_COOKIE['auth_sys'], Key); // với Key là key mã hóa ở bước 1
        }
```
**Lưu ý**

- Muốn đăng xuất (`logout`) thì phải xóa bỏ toàn bộ `cookie`
```php
    setcookie("auth_sys", "", time() - 3600, "/", DOMAIN);
    $session = $this->request->getSession();
    $session->destroy();
```
- Nếu chưa cài JWT (JSON WEB TOKENT) thì cài thêm
```sh
composer require firebase/php-jwt
```
