<?php

class formComgenForm extends cmsForm {

    public function init() {

        return array(
            
            array(
                'type' => 'fieldset',
                'title' => 'Компонент',
                'childs' => array(

                    new fieldString('component', array(
                        'title' => 'Системное имя компонента',
                        'rules' => array(
                            array('sysname'),
                            array('required')
                        )
                    )),
                    
                    new fieldString('root', array(
                        'title' => 'Генерировать в папку',
                        'prefix' => cmsConfig::get('root_path'),
                    )),           

                )
            ),
            
            array(
                'type' => 'fieldset',
                'title' => 'Экшен',
                'childs' => array(

                    new fieldString('name', array(
                        'title' => 'Системное имя формы',
                        'rules' => array(
                            array('sysname'),
                            array('required')
                        )
                    )),
                    
                    new fieldList('type', array(
                        'title' => 'Размещение формы',
                        'items' => array(
                            'frontend' => 'frontend',
                            'backend' => 'backend'
                        )
                    )),
                    
                )
            ),
            
            array(
                'type' => 'fieldset',
                'title' => 'Поля формы',
                'childs' => array(

                    new fieldFields('fields', array(
                        'show_targets' => false
                    ))
                    
                )
            ),
            
        );

    }

}
