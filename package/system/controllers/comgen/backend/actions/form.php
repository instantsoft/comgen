<?php

class actionComgenForm extends cmsAction {

    public function run(){

        $template = cmsTemplate::getInstance();
        
        $form = $this->getForm('form');
        
        $options = array();
        $errors = false;
        
        if ($this->request->has('submit')){

            $options = $form->parse($this->request, true);
            $errors = $form->validate($this, $options);

            if (!$errors){
                $options['fields'] = json_decode($options['fields'], true);
                $this->generateForm($options);
                cmsUser::addSessionMessage('Форма создана', 'success');
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
    
}
