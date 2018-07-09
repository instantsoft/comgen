<?php

class backendComgen extends cmsBackend {

    public $gen_root;

    public function actionIndex(){
        $this->redirectToAction('boilerplate');
    }

    public function getBackendMenu(){
        return array(
            array(
                'title' => 'Скелет',
                'url' => href_to($this->root_url, 'boilerplate')
            ),
            array(
                'title' => 'Объекты',
                'url' => href_to($this->root_url, 'entities')
            ),
            array(
                'title' => 'Экшен',
                'url' => href_to($this->root_url, 'action')
            ),
            array(
                'title' => 'Хук',
                'url' => href_to($this->root_url, 'hook')
            ),
            array(
                'title' => 'Форма',
                'url' => href_to($this->root_url, 'form')
            ),
        );
    }

    public function generateComponent($opts){
        $generator = $this->getGenerator('boilerplate', $opts['root']);
        $generator->generate($opts);
    }

    public function generateEntity($opts){
        $generator = $this->getGenerator('entity', $opts['root']);
        $generator->generate($opts);
    }

    public function generateAction($opts){
        $generator = $this->getGenerator('entity', $opts['root']);
        $generator->generateAction($opts);
    }

    public function generateHook($opts){
        $generator = $this->getGenerator('entity', $opts['root']);
        $generator->generateHook($opts);
    }

    public function generateForm($opts){

        $generator = $this->getGenerator('entity', $opts['root']);
        $generator->setOpts($opts);

        $type = $opts['type'] == 'frontend' ? '' : 'backend/';

        $form_file = $this->root . "system/controllers/{$opts['component']}/{$type}forms/form_{$opts['name']}.php";

        $generator->generateForm(false, $form_file);

    }

    private function getGenerator($generator, $output_root){

        include_once $this->root_path . "gens/gen.php";
        include_once $this->root_path . "gens/{$generator}.php";

        $generatorClass = $generator . 'Gen';

        return new $generatorClass($output_root, $this->root_path);

    }

}
