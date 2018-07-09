<?php

class actionComgenHook extends cmsAction {

    public function run(){

        $template = cmsTemplate::getInstance();
        
        $form = $this->getForm('hook');
        
        $options = array();
        $errors = false;
        
        if ($this->request->has('submit')){

            $options = $form->parse($this->request, true);
            $errors = $form->validate($this, $options);

            if (!$errors){
                $this->generateHook($options);
                cmsUser::addSessionMessage('Хук создан', 'success');
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
