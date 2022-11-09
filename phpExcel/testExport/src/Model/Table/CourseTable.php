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
