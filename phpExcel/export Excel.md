# Cài đặt thư viện phpExcel

```sh
    composer require dakota/cake-excel
```

hoặc

```json
"require": {
    //...
        "phpoffice/phpspreadsheet": "^1.23"
    },
composer install || composer install --ignore-platform-req=php
```

# lấy đa ta đổ vào view report

```php
public function exportCourse($output_type = 'D')
    {
        $data_export =  $this->m_course->get_data()->toArray();
        $total = array();
        $date = date('Y-m-d');
        $this->viewBuilder()->setTemplate('export_course');
        $file = 'report' . $date. '.xlsx';
        $this->set(compact('data_export','output_type', 'file','date'));
        $this->render();
        return;
    }
    //new version
    // public function exportPartNo($output_type = 'D', $file = 'my_spreadsheet.xlsx'){
    //     $data_export = [];
    //     $date = date('d M Y h:i A');
    //     $data_export = $this->m_partno->getAllDataPartNo()->toList();
    //     $file = 'partno_report-'. date('Y-m-d'). '.xlsx';
    //     $this->set(compact('data_export', 'output_type', 'file','date'));
    //     $this->viewBuilder()->setLayout('xls/default');
    //     $this->viewBuilder()->setTemplate('export_part_no');
    //     $this->response->withDownload('partno' . date('Y-m-d'). '.xlsx');
    //     $this->render();

    //     return;
    // }
```

## view

```php
<?php
//require_once(APP . 'Vendor' . DS . 'phpoffice' . DS . 'phpexcel' . DS . 'Classes' . DS . 'PHPExcel.php');

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
//$objPHPExcel = PHPExcel_IOFactory::load('template_excel/device.xlsx'); // load theme excel
$objPHPExcel->getProperties()->setCreator("Admin");

//HEADER
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('C' . 6, $date);
$i = 9;
foreach ($data_export as $key => $item) {
    //setCellValue xét hàng
    //setRow xét cột
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $key = $key + 1);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $item['id'] ?? '');
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $item['name_course'] ?? '');
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $item['created_at'] ?? '');

    if ($key < count($data_export))
        $i++;
}
//$i++;

$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);
$objPHPExcel->setActiveSheetIndex(0);

//$i++;

//vẽ đường viền
// $objPHPExcel->getActiveSheet()->getStyle("A5:D" . $i)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
//call the function in the controller with $output_type = F and $file with complete path to the file, to generate the file in the server for example attach to email
if (isset($output_type) && $output_type == 'F') {
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($file);
} else {
    // Redirect output to a client's web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $file . '"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
}

```

```php
<?php

namespace App\Model\Table;


use Cake\ORM\Table;

class CourseTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->condition = [$this->getAlias().'.del_flag' => 0];
    }

    public function get_data($id = null){
        $querry = $this->find()->select();
        if(!empty($id)){
            $querry->where(['id' => $id]);
        }
        return $querry->where($this->condition);
    }

    public function save_data($data = array()){
        $data = $this->newEntity($data);
        return $this->save($data);
    }
    public function update_data($data = array(), $request = array()){
        $new_data_course = $this->patchEntity($data, $request);
        return $this->save($new_data_course);
    }

    public function soft_delete_data($id = null){

        $data = $this->get_data($id)->first();
        $data->del_flag = 1;
        return $this->save($data);
    }
}


----------------------------------------------------------------------------------------------------

<?php

namespace App\Controller;

use App\Validation\CreateCourseValidation;
use App\Validation\EditCourseValidaition;
use Cake\Controller\Controller;
use Cake\View\ViewBuilder;

class CourseController extends Controller
{

    public function initialize()
    {
        $this->m_course = $this->loadModel('Course');
    }

    public function index()
    {
        $data_course = $this->m_course->get_data()->toList();
        $this->set('data_course', $data_course);
    }

    public function create()
    {
        if ($this->request->is('post')) {
            $res = $this->getRequest()->getData();
            $validation = new CreateCourseValidation($res);
            $error = $validation->validate($res);
            if (!empty($error)) {
                $this->set('error', $error);
                $this->set('param', $res);
            } else {
                $this->m_course->save_data($res);
                return $this->redirect([
                    'controller' => 'Course',
                    'action' => 'index'
                ]);
            }
        }
    }

    public function edit($id = null)
    {
        $data_course = $this->m_course->get_data($id)->first();
        if ($this->request->is('post')) {
            $res = $this->getRequest()->getData();
            $res['id'] = $data_course->id;
            $validation = new EditCourseValidaition($res);
            $error = $validation->validate($res);
            if (!empty($error)) {
                $this->set('error', $error);
                $this->set('param', $data_course);
            } else {
                $this->m_course->update_data($data_course, $res);
                return $this->redirect([
                    'controller' => 'Course',
                    'action' => 'index'
                ]);
            }
        }
        $this->set('data_course', $data_course);
    }

    public function delete($id = null)
    {
        if ($this->request->is('post')) {
            $this->m_course->soft_delete_data($id);
            return $this->redirect([
                'controller' => 'Course',
                'action' => 'index'
            ]);
        }
    }

    public function exportCourse($output_type = 'D')
    {
        $data = $this->m_course->get_data();
        $count = $data->count();
        $data_export = $data->toList();
        $date = date('Y-m-d');
        $this->viewBuilder()->setTemplate('export_course');
        $file = 'report' . $date . '.xlsx';
        $this->set(compact('data_export', 'output_type', 'file', 'date', 'count'));
        $this->render();
        return;
    }
}

```
