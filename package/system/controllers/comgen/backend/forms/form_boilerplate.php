<?php

class formComgenBoilerplate extends cmsForm {

    public function init() {

        return array(

            array(
                'type' => 'fieldset',
                'title' => 'Компонент',
                'childs' => array(

                    new fieldString('name', array(
                        'title' => 'Системное имя компонента',
                        'rules' => array(
                            array('sysname'),
                            array('required')
                        )
                    )),
                    
                    new fieldString('title', array(
                        'title' => 'Название компонента',
                        'rules' => array(
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
                'title' => 'Общие параметры',
                'childs' => array(
                    new fieldCheckbox('is_lang', array(
                        'title' => 'Создать языковой файл',
                        'default' => true
                    )),
                    new fieldCheckbox('is_tpl_folder', array(
                        'title' => 'Создать папку для шаблонов',
                        'default' => true
                    )),
                    new fieldCheckbox('is_model', array(
                        'title' => 'Создать модель',
                        'default' => true
                    )),
                    new fieldCheckbox('is_manifest', array(
                        'title' => 'Создать манифест',
                        'default' => true
                    )),                    
                    new fieldCheckbox('is_widgets', array(
                        'title' => 'Создать папку для виджетов',
                    )),
                )
            ),
            
            array(
                'type' => 'fieldset',
                'title' => 'Фронтэнд',
                'childs' => array(
                    
                    new fieldCheckbox('is_frontend', array(
                        'title' => 'Создать фронтэнд',
                        'default' => true
                    )),

                    new fieldCheckbox('frontend:is_index', array(
                        'title' => 'Создать экшен index',
                        'default' => true
                    )),

                )
            ),
            
            array(
                'type' => 'fieldset',
                'title' => 'Админка',
                'childs' => array(
                    new fieldCheckbox('is_backend', array(
                        'title' => 'Создать админку',
                        'default' => true
                    )),
                    new fieldCheckbox('backend:is_def_opts', array(
                        'title' => 'Включить стандартный экшен опций',
                        'default' => true
                    )),
                    new fieldList('backend:index', array(
                        'title' => 'Экшен index',
                        'items' => array(
                            'redirect' => 'Создать с редиректом на options',
                            'external' => 'Создать внешний',
                            'none' => 'Не создавать'
                        )
                    )),      
                    new fieldCheckbox('backend:is_menu', array(
                        'title' => 'Создать меню админки',
                        'default' => true
                    )),
                    new fieldText('backend:menu', array(
                        'title' => 'Пункты меню админки',
                        'hint' => 'В формате <strong>Заголовок:action</strong>, каждый пункт с новой строки',
                        'default' => 'Опции:options'
                    )),
                )
            ),     
            
            array(
                'type' => 'fieldset',
                'title' => 'Регистрация в базе',
                'childs' => array(
                    new fieldCheckbox('is_db', array(
                        'title' => 'Добавить компонент в базу',
                        'default' => false
                    )),
                    new fieldString('author', array(
                        'title' => 'Автор компонента',
                    )),
                    new fieldString('url', array(
                        'title' => 'URL сайта автора',
                        'hint' => 'Начинается на http://',
                    )),
                    new fieldString('version', array(
                        'title' => 'Версия компонента',
                        'default' => '1.0.0',
                    )),
                )
            ),

        );

    }

}
