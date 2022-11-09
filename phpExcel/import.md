```php
public function import()
    {
        if($this->getRequest()->is('post')){
            $file_name = $_FILES['file_import']['tmp_name'];
//            $file_name = $_FILES['file_import']['name'];
            $path_info = pathinfo($file_name, PATHINFO_EXTENSION);

            $objReader = \PHPExcel_IOFactory::createReaderForFile($file_name);
            $objPHPExcel = $objReader->load($file_name);
            $wordsheet = $objPHPExcel->getSheet(0);

            $data = $wordsheet->toArray(null, true,true,true);
            dd( $data);
        }
    }
```
