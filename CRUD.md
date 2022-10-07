#### Nguồn tham khảo

-   [Page](https://quynhweb.pro/doc-them-sua-xoa-trong-cakephp-4/)
-   [video](https://www.youtube.com/watch?v=TxBQF8txeeA)
-   [demo](https://github.com/ctechhindi/CakePHP-3.6-Application)

#### Thêm dữ liệu

```php
    public function register()
    {

        if ($this->request->is('post')) { // nếu theo phương thức gửi lên là post thì mới thực thi block code
            $users = $this->m_auth->newEntity($this->getRequest()->getData()); // xác thực lỗi trống
            $this->m_auth->save($users); // lưu lại vào db

            // <Cách 2>
//            $data = $this->getRequest()->getData();
//            $user = $this->m_auth->newEmptyEntity();
//            $user->name = $data['name'];
//            $user->Email = $data['Email'];
//            $user->Password = $data['Password'];
//            $this->m_auth->save($user);
        }
        $this->set('users', $users); // validation
    }
```
