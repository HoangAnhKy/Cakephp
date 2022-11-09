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

    public function export($output_type = 'D')
    {
        $data = $this->m_course->get_data();
        $count = $data->count();
        $data_export = $data->toList();
        $date = date('Y-m-d');
        $this->viewBuilder()->setTemplate('export');
        $file = 'report' . $date . '.xlsx';
        $this->set(compact('data_export', 'output_type', 'file', 'date', 'count'));
        $this->render();
        return;
    }

    public function import()
    {

        if($this->getRequest()->is('post')){
            $file_name = $_FILES['file_import']['tmp_name'];
            $path_info = pathinfo($file_name, PATHINFO_EXTENSION);

            $objReader = \PHPExcel_IOFactory::createReaderForFile($file_name);
            $objPHPExcel = $objReader->load($file_name);
            $wordsheet = $objPHPExcel->getSheet(0);

            $status_code = 200;

            $data = $wordsheet->toArray(null, true,true,true);
            return $this->response->withType('application/json')
                ->withStatus($status_code)
                ->withStringBody(json_encode(['data' => $data]));
        }
    }
}
