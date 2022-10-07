-   validator model

```php
public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'true')
            ->notEmptyString('name', 'Name cannot be blank', 'create');
        $validator
            ->scalar('Email')
            ->maxLength('Email', 255)
            ->requirePresence('Email', 'true')
            ->notEmptyString('Email', 'Email cannot be blank');

        $validator
            ->scalar('Password')
            ->maxLength('Password', 255)
            ->minLength('Password', 3, 'Password must be at least 3 characters
')
            ->requirePresence('Password', 'true')
            ->notEmptyString('Password', 'Password cannot be blank');

        $validator
            ->notEmptyString('Confirm_password', 'Confirm_password cannot be blank')
            ->sameAs('Confirm_password', 'Password', 'Password does not match');

        return $validator;
    }
```

-   validator controller

```php
        public function login()
    {

        if ($this->request->is('post')) {
            $validator = new Validator();
            $validator
                ->notEmptyString('Email', 'Email cannot be blank')
                ->notEmptyString('Password', 'Password cannot be blank');
            $errors = $validator->errors($this->request->getData());

//            $users = $this->m_auth->newEntity($this->getRequest()->getData()); // xác thực lỗi
            // check validation

            if (empty($errors)) {
                if ($this->m_auth->handleSignin($this->getRequest()->getData())) {
                    $this->redirect('listuser');
                } else {
                    $this->set('newBug', json_encode(['Please log in again!']));
                }
            } else {
                $this->set('newBug', $this->m_auth->getArrayErrors($errors));
            }
//            $this->set('users', $users); // truyền dữ liệu đã xác thực qua view để xuất lỗi
        }
    }
```
