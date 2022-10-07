# use Paginator in cakephp

## Create paginator in Controller

```php
    public function index()
    {
        $this->m_auth = $this->getTableLocator()->get('Auth'); //Cake3

        $settings =  [
            'limit' => 4, // Số lượng các giá trị hiện thị lên màn hình
            'maxLimit' => 2 // Số trang hiện thị
        ];

        $auth = $this->paginate($this->m_auth, $settings);

        $this->set(compact('auth'));
    }
```

## Custom view paginator with view helper

```php

<?= $this->Paginator->sort('ID') ?> // xét sự sắp xếp tăng hay giảm trên view
    // xét các giá trị hiện thị trên pagination
    <ul class="pagination">
        <?= $this->Paginator->first('<< ' . __('first')) ?>
        <?= $this->Paginator->prev('< ' . __('previous')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('next') . ' >') ?>
        <?= $this->Paginator->last(__('last') . ' >>') ?>
    </ul>
<p><?= $this->Paginator->counter(['format' => __('Page {{ page }} of {{ pages }}, showing {{ current }} record(s) out of {{ count }} total')]) ?>
        </p>
```

#### custom pagination

```php
<?php
$this->Paginator->setTemplates([
'prevActive' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
'prevDisabled' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
'number' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
'nextActive ' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
'nextDisabled' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
'current' => '<li class="page-item active"><a class="page-link" href="{{url}}">{{text}}</a></li>'
]);
?>
```

        <?php echo (isset($paginate)) ? $paginate : ''; ?>
