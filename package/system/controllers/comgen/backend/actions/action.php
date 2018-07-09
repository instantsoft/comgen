<?php

class actionComgenAction extends cmsAction {

    public function run(){

        $template = cmsTemplate::getInstance();
        
        $form = $this->getForm('action');
        
        $options = array();
        $errors = false;
        
        if ($this->request->has('submit')){

            $options = $form->parse($this->request, true);
            $errors = $form->validate($this, $options);

            if (!$errors){
                $this->generateAction($options);
                cmsUser::addSessionMessage('Экшен создан', 'success');
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
