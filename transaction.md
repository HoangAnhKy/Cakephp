```php
            $connection = ConnectionManager::get('default'); //get database default
            if(!empty($_FILES['importFile']))
            {
                $connection->begin();
                $result = $this->importFileExcel($_FILES); // callback function different, this function result boolean true or false
            }
            if($result){
                $this->Flash->success('Import success');
                $connection->commit();
            }else{
                $connection->rollback();
            }
```

-   nếu code chạy save thành công thì sẽ lưu ngược lại
