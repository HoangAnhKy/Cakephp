# get data có paginate

```php
 public function User()
{
    // $table = new UsersTable(); // c1
    $table = $this->fetchTable(UsersTable::class); // c2
    $data = $table->query()->select()->limit(10)->page(1)->all()->toArray();
    dd($data);
    //...
}
```

# join bảng

## contain

```php
class UsersTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('users');
        $this->belongsTo("orders", [
            'className'=> 'Orders',
            'foreignKey' => 'id',
        ]);
    }
}
// ...
class OrdersTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('orders');
    }
}
// ...
public function User()
{
    $table = $this->fetchTable(UsersTable::class);
    $data = $table->query()->select()->contain(["orders"])->limit(10)->page(1)->all()->toArray();
    dd($data);
}
```

## join tay


```php
public function users()
{
    $table = $this->fetchTable(UsersTable::class);
    $data = $table->find()->select([
        'Users.id',
        'Users.name',
        'orders.id',
    ])
        ->join([
            'table' => 'orders',
            'type' => 'LEFT',
            'conditions' => [
                'orders.user_id = Users.id',
            ]
        ])
        ->limit(10)->page(1)->all()->toArray();
    dd($data);
    // ...
}
```

# tips find 

```php
public function user()
{
    $table = $this->fetchTable(UsersTable::class);
    $data = $table->find('list', [
        'fields' => ['id', 'name', 'email', 'orders.id', 'orders.user_id'],
        'conditions' => ["orders.user_id IS NOT NULL"],
        'groupField' => 'orders.user_id',
        'keyField' => 'orders.user_id',
        'valueField' => function($value) {
            return $value->toArray();
        },
    ])
        ->join([
            'table' => 'orders',
            'type' => 'LEFT',
            'conditions' => [
                'orders.user_id = Users.id',
            ]
        ])
        ->limit(10)->page(1)->all()->toArray();
}
```

# Lưu

```php
$table = $this->fetchTable(UsersTable::class);
$request = [
    "name" => "k_" . uniqid(),
    "email" => "k_" . uniqid() . "@k.k",
    "password" => password_hash("123456", PASSWORD_DEFAULT)
];
$entity = $table->newEntity($request);
dd($table->save($entity));
```

# sửa 

```php
$request = [
    "email" => "k_" . uniqid() . "@k.k",
];
$user = $table->query()->where([
    "id" => "2023159"
])->first();
$entity = $table->patchEntity($user, $request);
dd($table->save($entity));
```
