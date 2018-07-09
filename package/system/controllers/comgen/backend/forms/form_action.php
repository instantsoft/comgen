<?php

class formComgenAction extends cmsForm {

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
                        'title' => 'Системное имя экшена',
                        'rules' => array(
                            array('sysname'),
                            array('required')
                        )
                    )),
                    
                    new fieldList('type', array(
                        'title' => 'Размещение экшена',
                        'items' => array(
                            'frontend' => 'frontend',
                            'backend' => 'backend'
                        )
                    )),
                    
                    new fieldList('result', array(
                        'title' => 'Результат',
                        'items' => array(
                            'tpl' => 'Шаблон',
                            'json' => 'JSON',
                            'halt' => 'Завершение работы',
                            'none' => 'Ничего'
                        )
                    )),
                    
                )
            ),
            
            array(
                'type' => 'fieldset',
                'title' => 'Проверки',
                'childs' => array(

                    new fieldCheckbox('is_ajax', array(
                        'title' => 'Только AJAX',                        
                    )),
                    new fieldCheckbox('is_auth', array(
                        'title' => 'Только авторизованный пользователь',                        
                    )),
                    new fieldCheckbox('is_admin', array(
                        'title' => 'Только администратор',
                    )),
                    
                )
            ),            

        );

    }

}
