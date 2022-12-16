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

-   Tạo validator ngang cấp với controller, khi dùng chỉ việc gọi `new Validation `

```php
 <?php

namespace App\Validation;

use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class EditCourseValidation extends Validator
{

    public function __construct(array $config)
    {
        parent::__construct();
        $this->m_course = TableRegistry::getTableLocator()->get('course');
        $id = $config['id'];
        $this
            ->notEmptyString('name_course', 'field can not empty')
            ->add('name_course', 'extends', [
                'rule' => function ($value, $context) use ($id) {
                    $query_course = $this->m_course->find()->where(['AND' =>[
                        'id <>' => $id,
                        ['name_course' => $value]
                    ]])->toList();
                    return empty($query_course);
                },
                'message' => 'The name_course already exist'
            ]);

    }
}

//   <?php if(!empty($error['name']))
//        foreach ($error['name'] as $msg) {
//             echo $msg.'<br>';
//   }?>
```

---

# validate dùng chung

```php
<?php

namespace App\Validation;

use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class ValidationCreateMaterial extends Validator
{
    public function __construct($id = null)
    {
        parent::__construct();
        $this->m_material = TableRegistry::getTableLocator()->get('Material');
        // code
        $this->notBlank('code', 'Please fill Code field!')
            ->add('code', 'unique', [
                'rule' => function ($value, $context) {
                    $data = $this->m_material->find()->where(['code' => $value])->first();
                    return empty($data);
                },
                'message' => 'This code already exists'
            ])
            ->add('code', 'maxLength', [
                'rule' => ['maxLength', 255],
                'message' => 'Code cannot be too long.',
            ]);
        // Supplier
        $this->notBlank('supplier_id', 'Please fill Supplier field!')
        ->unique('supplier_id', 'This supplier does not exist');
        // part_no_id
        $this->notBlank('part_no_id', 'Please fill PartNo field!')
            ->unique('part_no_id', 'This Part no does not exist');
        // Price
        $this->notBlank('price', 'Please fill price field!')
        ->numeric('price',
            'Price must be numeric'
        );

        // unit_id
        $this->notBlank('unit_id', 'Please fill Unit field!')
            ->unique('unit_id', 'This unit does not exist');
        // part_no_id
        $this->notBlank('currency_id', 'Please fill Currency field!')
            ->unique('currency_id', 'This currency does not exist');
    }
    private function unique($field = null, $message = null){
        $this->add($field, 'unique', [
            'rule' => function ($value, $context) use($field) {
                $data = $this->m_material->find()->where([$field => $value])->first();
                return !empty($data);
            },
            'message' => $message
        ]);
        return $this;
    }
}

```
