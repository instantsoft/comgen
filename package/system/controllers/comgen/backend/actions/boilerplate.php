<?php

class actionComgenBoilerplate extends cmsAction {

    public function run(){

        $form = $this->getForm('boilerplate');
        
        $options = array();
        $errors = false;
        
        if ($this->request->has('submit')){

            $options = $form->parse($this->request, true);
            $errors = $form->validate($this, $options);

            if (!$errors){
                
                $this->generateComponent($options);
                
                if ($options['is_db']){
                    $this->addComponentToDB($options);
                }
                
                cmsUser::addSessionMessage('Скелет компонента создан', 'success');
                
            }

            if ($errors){
                cmsUser::addSessionMessage(LANG_FORM_ERRORS, 'error');
            }

        }
        
        return cmsTemplate::getInstance()->render('backend/form', array(
            'form' => $form,
            'options' => $options,
            'errors' => $errors
        ));

    }
    
    private function addComponentToDB($options){
        
        $model = new cmsModel();
        
        $is_exists = $model->filterEqual('name', $options['name'])->getCount('controllers');
        
        if ($is_exists) { return; }
        
        $data = array(
            'name' => $options['name'],
            'title' => $options['title'],            
            'author' => $options['author'],
            'url' => $options['url'],
            'version' => $options['version'],
            'is_external' => true,
            'is_enabled' => true,
            'is_backend' => $options['is_backend']
        );
        
        $model->insert('controllers', $data);
        
    }

}
