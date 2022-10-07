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

-   để sử dụng chúng ta chỉ cần gọi `contain('Tên liên kết')`

```php
$query = $this->find()->contain(['business_title', 'teams']);
```

-   sử dụng `machine` để search dữ liệu liên kết

```php
 $id = $param['team_id'];
            $query->matching('teams', function ($q) use ($id) {
                return $q->where(['teams.id' => $id]);
            });
```
