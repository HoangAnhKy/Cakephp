- [Validate Default, thêm rule cho validateDefault](#validate-default-thêm-rule-cho-validatedefault)
- [Ngoài ValidateDefault còn có ValidateCreate, ValidateEdit](#ngoài-validatedefault-còn-có-validatecreate-validateedit)
- [ValidateController](#validatecontroller)
***
# Validate Default, thêm rule cho validateDefault

```php
#model

class UsersTable extends Table
{
    // ...
    public function validationDefault(Validator $validator): Validator
    {
        $validator
        // Field phải tồn tại trong request.
        // Mặc định áp dụng cho cả create và update.
        // Có thể truyền 'create' hoặc 'update' để giới hạn ngữ cảnh.        
        ->requirePresence('name')
        ->notEmptyString('name');

        $validator->requirePresence('email')->notEmptyString('email')->email('email')->maxLength('email', 255)
        // Thêm validate tự build
        ->add('email', 'unique', [
            'rule' => function ($value, $context) {
                $usersTable = TableRegistry::getTableLocator()->get('Users');
                $conditions = ['email' => $value];
                // $context['data'] chứa toàn bộ request field
                if (!empty($context['data']['id'])) {
                    $conditions['id !='] = $context['data']['id'];
                }
                return !$usersTable->exists($conditions);
            },
            'message' => 'Email đã tồn tại'
        ]);

        return $validator;
    }
}
```

- Sử  dụng bên controller 

```php
// bỏ validate         
// $entity = $table->newEntity($request,['validate' => false]);
$entity = $table->newEntity($request);
if ($entity->getErrors()){
    dd($entity->getErrors());
}
dd($table->save($entity));
```

# Ngoài ValidateDefault còn có ValidateCreate, ValidateEdit

```php
// Table
public function validationCreate(Validator $validator): Validator
public function validationEdit(Validator $validator): Validator
// Gọi sử  dụng ở controller

$entity = $table->newEntity($data, ['validate' => 'create']);
// ...
$entity = $table->patchEntity($entity, $data, ['validate' => 'edit']);
```

# ValidateController

Tạo một `Folder Validate` cùng cấp với `Controller` là `App\Validation`

```php
namespace App\Validation;

use Cake\Validation\Validator;

class UserValidator
{
    public static function validateCreate(array $data): array
    {
        $validator = new Validator();

        $validator
            ->requirePresence('email')
            ->notEmptyString('email')
            ->email('email')
            ->maxLength('email', 255);

        return $validator->validate($data);
    }
}
```

Controller sử  dùng

```php
$request = [
// "email" => "hill.jarret@example.net",
];

$errors = UserValidator::validateCreate($request);

if (!empty($errors)) {
    dd($errors);
}
```