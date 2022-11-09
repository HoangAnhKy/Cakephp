<?php

namespace App\Validation;

use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class EditCourseValidaition extends Validator
{

    public function __construct(array $config)
    {
        $this->m_course = TableRegistry::getTableLocator()->get('course');
        parent::__construct($config);
        $id = $config['id'];
        $this
            ->notEmptyString('name_course', 'field can not empty')
            ->add('name_course', 'unique', [
                'rule' => function ($value, $context) use ($id) {
                    $query_course = $this->m_course->find()->where(['AND' =>[
                        'id <>' => $id,
                        ['name_course' => $value]
                    ]])->toList();
                    return empty($query_course);
                },
                'message' => 'The name_course already exist'
            ]);
    }
}
