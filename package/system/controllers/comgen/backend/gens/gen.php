<?php

class gen {

    public $root;
    private $tpl_root;

    public function __construct($output_root, $backend_root) {

        $this->root = cmsConfig::get('root_path') . trim(trim($output_root), '/') . '/';
        $this->tpl_root = $backend_root . 'tpls/';

    }

    protected function makeFromTemplate($name, $file, $template, $data = array()){

        $data['name'] = $name;
        $data['Name'] = ucfirst($name);
        $data['NAME'] = strtoupper($name);

        $content = $this->getTemplate($template, $data);

        @mkdir(dirname($file), 0777, true);
        file_put_contents($file, $content);

    }

    protected function getTemplate($template, $data = array()){

        $file = $this->tpl_root . "{$template}.txt";

        $content = file_get_contents($file);

        $search = array();
        $replace = array();

        foreach($data as $key=>$value){
            $search[] = '{'.$key.'}';
            $replace[] = $value;
        }

        $content = str_replace($search, $replace, $content);

        return $content;

    }

    protected function appendTo($file, $placeholder, $append){

        $content = file_get_contents($file);

        $placeholder = '/*{'.$placeholder.'}*/';

        $append =  $placeholder . "\n\n" . $append;

        $content = str_replace($placeholder, $append, $content);

        file_put_contents($file, $content);

    }

    public function appendToLang($name, $data){

        $file = $this->root . "system/languages/ru/controllers/{$name}/{$name}.php";

        if (!file_exists($file)) { return; }

        $current = file_get_contents($file);

        $code = array();

        foreach($data as $id => $text){
            $id = strtoupper($id);

            if (strstr($current, "'{$id}'")){ continue; }

            $code[] = $this->getTemplate('lang', array(
                'id' => $id,
                'text' => $text
            ));

        }

        if ($code){

            $code = "\n" . implode("\n", $code);

            $f = fopen($file, 'a+');

            fputs($f, $code);

            fclose($f);

        }

    }

    protected function makeDirs($dirs){
        if (!is_array($dirs)) { $dirs = array($dirs); }
        foreach($dirs as $dir){
            @mkdir($this->root . $dir, 0777, true);
        }
    }

}
