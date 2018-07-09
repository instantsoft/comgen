<?php

class fieldFields extends cmsFormField {

    public $title   = 'Поля объекта';
    public $is_public = false;

    public function getInput($value) {
        
        if ($value){
            if (!is_array($value)){
                $value = json_decode($value, true);
            }
        }
        
        $is_show_targets = !isset($this->show_targets) || !($this->show_targets === false);
        
        $fields = $value ? $value : array(
            array(
                'type' => 'string',
                'is_req' => true,
                'is_ff' => true,
                'is_fl' => true,
                'is_fi' => true,
                'is_bf' => true,
                'is_bl' => true,
            )
        );
        
        $controller = cmsCore::getController('comgen');
        
        $field_types = cmsForm::getAvailableFormFields(false);
        asort($field_types, SORT_STRING);
        
        foreach($field_types as $id=>$title){
            $field_types[$id] = $id;
        }
               
        return cmsTemplate::getInstance()->renderInternal($controller, 'backend/fields_list', array(
            'field_types' => $field_types,
            'fields' => $fields,
            'name' => $this->name,
            'is_show_targets' => $is_show_targets
        ));
        
    }


}
