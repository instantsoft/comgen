<?php

class formComgenHook extends cmsForm {

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
                'title' => 'Хук',
                'childs' => array(

                    new fieldString('name', array(
                        'title' => 'Системное имя хука',
                        'rules' => array(
                            array('sysname'),
                            array('required')
                        )
                    )),
                    
                )
            ),
            
        );

    }

}
