<?php

class formComgenEntity extends cmsForm {

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
                'title' => 'Объект',
                'childs' => array(

                    new fieldString('name', array(
                        'title' => 'Системное имя объекта',
                        'rules' => array(
                            array('sysname'),
                            array('required')
                        )
                    )),
                    
                    new fieldString('name_plural', array(
                        'title' => 'Системное имя во множественном числе',
                        'rules' => array(
                            array('sysname'),
                            array('required')
                        )
                    )),
                    
                    new fieldString('title', array(
                        'title' => 'Название объекта',
                        'rules' => array(
                            array('required')
                        )
                    )),                   

                    new fieldString('title_plural', array(
                        'title' => 'Название объекта во множественном числе',
                        'rules' => array(
                            array('required')
                        )
                    )),                   

                )
            ),

            array(
                'type' => 'fieldset',
                'title' => 'Стандартные поля объекта',
                'childs' => array(

                    new fieldCheckbox('std_fields:date_pub', array(
                        'title' => 'Дата публикации (date_pub)',
                        'default' => true
                    )), 
                    new fieldCheckbox('std_fields:is_enabled', array(
                        'title' => 'Флаг активности (is_enabled)',
                        'default' => true
                    )), 
                    new fieldCheckbox('std_fields:user_id', array(
                        'title' => 'ID автора (user_id)',
                        'default' => true
                    )), 
                    new fieldCheckbox('std_fields:parent_id', array(
                        'title' => 'ID родителя (parent_id)',
                    )), 
                    new fieldCheckbox('std_fields:category_id', array(
                        'title' => 'ID категории (category_id)',
                    )), 
                    new fieldCheckbox('std_fields:ordering', array(
                        'title' => 'Сортировка (ordering)',
                    ))
                    
                )
            ),

            array(
                'type' => 'fieldset',
                'title' => 'Собственные поля объекта',
                'childs' => array(

                    new fieldFields('fields')
                    
                )
            ),

            array(
                'type' => 'fieldset',
                'title' => 'Модель',
                'childs' => array(

                    new fieldCheckbox('is_model', array(
                        'title' => 'Создать методы в модели',
                        'default' => true
                    )),                    
                    
                    new fieldCheckbox('model:add', array(
                        'title' => 'Метод Add',
                        'default' => true
                    )),                    
                    new fieldCheckbox('model:update', array(
                        'title' => 'Метод Update',
                        'default' => true
                    )),                    
                    new fieldCheckbox('model:delete', array(
                        'title' => 'Метод Delete',
                        'default' => true
                    )),                    
                    new fieldCheckbox('model:get', array(
                        'title' => 'Метод Get',
                        'default' => true
                    )),                    
                    new fieldCheckbox('model:get_count', array(
                        'title' => 'Метод Get Count',
                        'default' => true
                    )),                    
                    new fieldCheckbox('model:get_all', array(
                        'title' => 'Метод Get All',
                        'default' => true
                    )),                    
                    
                )
            ),

            array(
                'type' => 'fieldset',
                'title' => 'Фронтенд',
                'childs' => array(

                    new fieldCheckbox('frontend:form', array(
                        'title' => 'Создать форму',
                        'default' => true
                    )),                    
                    new fieldCheckbox('frontend:list', array(
                        'title' => 'Создать экшен просмотра списка',
                        'default' => true
                    )),                    
                    new fieldCheckbox('frontend:item', array(
                        'title' => 'Создать экшен просмотра записи',
                        'default' => true
                    )),                    
                    new fieldCheckbox('frontend:add', array(
                        'title' => 'Создать экшен добавления записи',
                        'default' => true                        
                    )),                  
                    new fieldCheckbox('frontend:еdit', array(
                        'title' => 'Создать экшен редактирования записи',
                        'default' => true
                    )),                    
                    new fieldCheckbox('frontend:delete', array(
                        'title' => 'Создать экшен удаления записи',
                        'default' => true
                    )),                    
                )
            ),

            array(
                'type' => 'fieldset',
                'title' => 'Админка',
                'childs' => array(

                    new fieldCheckbox('backend:form', array(
                        'title' => 'Создать форму',
                        'default' => true
                    )),                    
                    new fieldCheckbox('backend:list', array(
                        'title' => 'Создать экшен просмотра списка',
                        'default' => true
                    )),
                    new fieldCheckbox('backend:menu', array(
                        'title' => 'Добавить экшен просмотра списка в меню',
                        'default' => true
                    )),
                    new fieldCheckbox('backend:reorder', array(
                        'title' => 'Создать экшен сортировки списка',
                        'default' => true
                    )),                    
                    new fieldCheckbox('backend:add', array(
                        'title' => 'Создать экшен добавления записи',
                        'default' => true                        
                    )),                  
                    new fieldCheckbox('backend:еdit', array(
                        'title' => 'Создать экшен редактирования записи',
                        'default' => true
                    )),                    
                    new fieldCheckbox('backend:delete', array(
                        'title' => 'Создать экшен удаления записи',
                        'default' => true
                    )),                    
                    
                )
            ),
            
            array(
                'type' => 'fieldset',
                'title' => 'База данных',
                'childs' => array(

                    new fieldCheckbox('is_db', array(
                        'title' => 'Создать таблицу в базе',
                        'default' => true
                    )),
                                        
                )
            ),

        );

    }

}
