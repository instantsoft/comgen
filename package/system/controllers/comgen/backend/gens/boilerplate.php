<?php

class boilerplateGen extends gen {

    public function generate($opts){

        $name = $opts['name'];

        $dirs = array("system/controllers/{$name}");

        if ($opts['is_tpl_folder']){
            $dirs[] = "templates/default/controllers/{$name}";
        }

        if ($opts['is_widgets']){
            $dirs[] = "system/controllers/{$name}/widgets";
            if ($opts['is_lang']){
                $dirs[] = "system/languages/ru/controllers/{$name}/widgets";
            }
        }

        $this->makeDirs($dirs);

        if ($opts['is_lang']){
            $this->generateLangFile($name, $opts['title']);
        }

        if ($opts['is_model']){
            $this->generateModel($name);
        }

        if ($opts['is_manifest']){
            $this->generateManifest($name);
        }

        if ($opts['is_backend']){
            $backend_opts = is_array($opts['backend']) ? $opts['backend'] : array();
            $this->generateBackend($name, $backend_opts);
        }

        if ($opts['is_frontend']) {
            $frontend_opts = is_array($opts['frontend']) ? $opts['frontend'] : array();
            $this->generateFrontend($name, $frontend_opts);
        }

    }

    public function generateBackend($name, $opts){

        $this->makeDirs(array(
            "system/controllers/{$name}/backend",
            "system/controllers/{$name}/backend/actions",
            "system/controllers/{$name}/backend/forms",
            "system/controllers/{$name}/backend/grids",
        ));

        $file = $this->root . "system/controllers/{$name}/backend.php";

        $content = array();

        if ($opts['is_def_opts']){

            $content[] = $this->getTemplate('backend/def_opts');

            $form_options_file = $this->root . "system/controllers/{$name}/backend/forms/form_options.php";
            $this->makeFromTemplate($name, $form_options_file, 'options_form');

        }

        if ($opts['index'] == 'redirect'){
            $content[] = $this->getTemplate('backend/index_redirect');
        }

        if ($opts['index'] == 'external'){

            $index_file = $this->root . "system/controllers/{$name}/backend/actions/index.php";
            $this->makeFromTemplate($name, $index_file, 'backend/index.php');

            $index_tpl_file = $this->root . "templates/default/controllers/{$name}/backend/index.tpl.php";
            $this->makeFromTemplate($name, $index_tpl_file, 'backend/index.tpl.php');

        }

        if ($opts['is_menu']){

            $items_code = array();

            $items = array();
            $menu_parts = explode("\n", trim($opts['menu']));
            $menu_labels = array();

            foreach($menu_parts as $part){
                list($title, $action) = explode(':', trim($part));
                $items[$title] = $action;
            }

            foreach($items as $title=>$action){
                $title_lang = 'LANG_' . strtoupper($name) . '_BACKEND_TAB_' . strtoupper($action);
                $items_code[] = $this->getTemplate('backend/menu_item', array(
                    'title' => $title_lang,
                    'action' => $action
                ));
                $menu_labels[$title_lang] = $title;
            }

            if ($menu_labels){
                $this->appendToLang($name, $menu_labels);
            }

            $items_code = implode("\n", $items_code);

            $menu_code = $this->getTemplate('backend/menu', array('items' => $items_code));

            $content[] = $menu_code;

        }

        $content = implode("\n\n", $content);
        $this->makeFromTemplate($name, $file, 'backend.php', array('content' => $content));

    }

    public function generateFrontend($name, $opts){

        $this->makeDirs(array(
            "system/controllers/{$name}/actions",
            "system/controllers/{$name}/forms",
        ));

        $file = $this->root . "system/controllers/{$name}/frontend.php";
        $this->makeFromTemplate($name, $file, 'frontend.php');

        if ($opts['is_index']){

            $index_file = $this->root . "system/controllers/{$name}/actions/index.php";
            $this->makeFromTemplate($name, $index_file, 'frontend_index.php');

            $index_tpl_file = $this->root . "templates/default/controllers/{$name}/index.tpl.php";
            $this->makeFromTemplate($name, $index_tpl_file, 'frontend_index.tpl.php');

        }

    }

    public function generateManifest($name){

        $this->makeDirs(array(
            "system/controllers/{$name}/hooks",
        ));

        $file = $this->root . "system/controllers/{$name}/manifest.php";

        $this->makeFromTemplate($name, $file, 'manifest.php');

    }

    public function generateLangFile($name, $title){

        $file = $this->root . "system/languages/ru/controllers/{$name}/{$name}.php";

        $this->makeFromTemplate($name, $file, 'lang.php', array('title' => $title));

    }

    public function generateModel($name){

        $file = $this->root . "system/controllers/{$name}/model.php";

        $this->makeFromTemplate($name, $file, 'model.php');

    }

}
