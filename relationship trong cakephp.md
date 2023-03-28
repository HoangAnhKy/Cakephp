-   liên kết 2 bảng sử dụng

    -   belongsto <=> hasOne
    -   belongstoMany <=> hasMany

-   khao báo trong initialize

```php
// user
 $this->belongsTo('business_title', // tên liên kết
        [
            'foreignKey' => 'business_id' // khóa liên kết
        ])->setConditions(['business_title.del_flag' => UNDEL, 'business_title.active' => ACTIVE]); // where
```

```php
// business_title
$this->hasOne('users', [
            'foreignKey' => 'id'
        ]);
```

## để sử dụng chúng ta chỉ cần gọi `contain('Tên liên kết')`

```php
$query = $this->find()->contain(['business_title', 'teams']);
$query = $this->find()->contain(['business_title.User', 'teams']);
```

## sử dụng `machine` để search dữ liệu liên kết

```php
 $id = $param['team_id'];
            $query->matching('teams', function ($q) use ($id) {
                return $q->where(['teams.id' => $id]);
            });
```

## sử dụng `notMatching` để tạo dữ liệu liên kết

-   sử dụng cả 2 cũng được `notMatching` ngược lại với `Matching`

```php
public function get_data_device_join_assign()
    {
        $query = $this
            ->find()
            ->select(['Device.ID', 'Device.Name'])

            ->notMatching('Assign', function($q) {
                return $q
                    ->where(
                        ['Assign.status' => USING]
                    )
                    ->group('Assign.id');
            })->toList();

        return $query;
    }
```

## thêm điều kiện so sánh khi dùng realationship

```php
$query = $this->find()->contain(['Assign' => function($q){
            return $q->where(['Assign.status' => 2]);
        }]);
```

## join tay

```php
$query = $this->find('ALL')
            ->join([
                'table' => 'Assign',
                'alias' => 'a',
                'type' => 'LEFT ',
                'conditions' => ['a.del_flag' => UNDEL, 'a.active' => ACTIVE, 'a.device_id' => 'device.id']
            ])
            ->select(['Device.ID', 'Device.Name', 'a.status'])
        ;
```
