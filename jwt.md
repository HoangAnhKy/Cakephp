[Tham khảo JWT Cakephp 4](https://book.cakephp.org/4/en/tutorials-and-examples/cms/authentication.html)

Để tạo JWT cho website với cakephp thì phải làm như sau

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
