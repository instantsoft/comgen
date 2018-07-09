<?php

class actionComgenEntities extends cmsAction {

    public function run(){

        $template = cmsTemplate::getInstance();
        
        $form = $this->getForm('entity');
        
        $options = array();
        $errors = false;
        
        if ($this->request->has('submit')){

            $options = $form->parse($this->request, true);
            $errors = $form->validate($this, $options);

            if (!$errors){
                
                $options['fields'] = json_decode($options['fields'], true);
                $this->generateEntity($options);
                
                if ($options['is_db']){
                    $this->addEntityToDB($options);
                }
                
                cmsUser::addSessionMessage('Объект создан', 'success');
            }

            if ($errors){
                cmsUser::addSessionMessage(LANG_FORM_ERRORS, 'error');
            }

        }
        
        return $template->render('backend/form', array(
            'form' => $form,
            'options' => $options,
            'errors' => $errors
        ));

    }
    
    private function addEntityToDB($opts){
     
        $fields = array(
            'id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY'
        );
        
        if ($opts['std_fields']['date_pub']){ $fields[] = 'date_pub timestamp NULL DEFAULT NULL'; }
        if ($opts['std_fields']['is_enabled']){ $fields[] = 'is_enabled TINYINT(1) UNSIGNED NULL DEFAULT NULL'; }
        if ($opts['std_fields']['user_id']){ $fields[] = 'user_id INT(11) NULL DEFAULT NULL'; }
        if ($opts['std_fields']['parent_id']){ $fields[] = 'parent_id INT(11) NULL DEFAULT NULL'; }
        if ($opts['std_fields']['category_id']){ $fields[] = 'category_id INT(11) NULL DEFAULT NULL'; }
        if ($opts['std_fields']['ordering']){ $fields[] = 'ordering INT(11) NULL DEFAULT NULL'; }
        
        if ($opts['fields']){
            foreach($opts['fields'] as $field){
                $field_class  = 'field'.string_to_camel('_', $field['type']);
                $field_parser = new $field_class(null, (isset($field['options']) ? array('options' => $field['options']) : null));
                $fields[] = $field['name']. ' ' . $field_parser->getSQL();
            }
        }

        $fields = implode(",\n", $fields);
        
        $table_name = $opts['component'] . '_' . $opts['name_plural'];
                
        $sql = "CREATE TABLE IF NOT EXISTS `{#}{$table_name}` (\n";
        $sql .= $fields;
        $sql .= ') ENGINE=MYISAM DEFAULT CHARSET=utf8';
        
        cmsDatabase::getInstance()->query($sql);
        
    }

}
