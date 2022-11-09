<?php

namespace App\Validation;

use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class CreateCourseValidation extends Validator
{

    public function __construct(array $config)
    {
        $this->m_course = TableRegistry::getTableLocator()->get('course');
        parent::__construct($config);
        $this
            ->notEmptyString('name_course', 'field can not empty')
            ->add('name_course', 'unique', [
                'rule' => function ($value, $context){
                    $query_course = $this->m_course->find()->where([
                        ['name_course' => $value]
                    ])->toList();
                    return empty($query_course);
                },
                'message' => 'The name_course already exist'
            ]);
    }
}
