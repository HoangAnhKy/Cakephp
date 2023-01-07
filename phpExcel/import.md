```php
    public function import()
    {
        try{
            if($this->getRequest()->is('post')){
                if ($_FILES["file_import"]['error'] != 0)
                {
                    $this->Flash->error(__('Data upload error,please again'));
                    return $this->redirect(BASE_URL.'part-no/index');
                }
                //Vị trí file lưu tạm trong server (file sẽ lưu trong uploads, với tên giống tên ban đầu)
                $target_dir = "upload/file_import/";
                $tmp_file = $_FILES['file_import']['tmp_name'];
                $target_file   = $target_dir . basename($_FILES["file_import"]["name"]);
                if(file_exists($target_file))
                {
                    unlink($target_file);
                }

                $objReader = new Xlsx();
                $objPHPExcel = $objReader->load($tmp_file);
                $wordsheet = $objPHPExcel->getSheet(0);
                $highRow = $wordsheet->getHighestRow();
                $data_input = $wordsheet->toArray(null, true,true,true);
            }
        }catch (Exception $e){
            $this->Flash->error('File excel error or wrong format, please again!');
            return $this->redirect(BASE_URL.'part-no/index');
        }
    }
```
