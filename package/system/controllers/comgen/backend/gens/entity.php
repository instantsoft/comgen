<?php

class entityGen extends gen {

    private $component;
    private $opts;

    public function generate($opts){

        $this->setOpts($opts);

        $this->appendToLang($this->component, array(
            'lang_'.$this->component.'_'.$this->opts['name'] => ucfirst($this->opts['title']),
            'lang_'.$this->component.'_'.$this->opts['name_plural'] => ucfirst($this->opts['title_plural']),
            'lang_'.$this->component.'_'.$this->opts['name_plural'] . '_none' => ucfirst($this->opts['title_plural']) . ' не найдены',
        ));

        if ($opts['is_model']){
            $this->generateModelMethods();
        }

        $this->generateFrontend();
        $this->generateBackend();

    }

    public function setOpts($opts){
        $this->component = $opts['component'];
        $this->opts = $opts;
    }

    private function generateModelMethods(){

        $methods = array('/* '.ucfirst($this->opts['name_plural']).' */');

        if ($this->opts['backend']['reorder']){
            $this->opts['model']['reorder'] = 1;
        }

        foreach($this->opts['model'] as $id => $m){

            $method = $this->getTemplate('entity/model/'.$id, array(
                'name' => $this->opts['name'],
                'Name' => string_to_camel('_', $this->opts['name']),
                'names' => $this->opts['name_plural'],
                'Names' => string_to_camel('_', $this->opts['name_plural']),
                'table' => $this->component . '_' . $this->opts['name_plural']
            ));

            $methods[] = $method;

        }

        $methods = implode("\n\n", $methods);

        $model_file = $this->root . "system/controllers/{$this->component}/model.php";

        $this->appendTo($model_file, 'comgen-model-methods', $methods);

    }

    private function generateFrontend(){

        if ($this->opts['frontend']['form']){
            $form_file = $this->root . "system/controllers/{$this->component}/forms/form_{$this->opts['name']}.php";
            $this->generateForm('is_ff', $form_file);
        }

        $replacements = array(
            'entity' => $this->opts['name'],
            'Entity' => string_to_camel('_', $this->opts['name']),
            'ENTITY' => strtoupper($this->opts['name']),
            'entities' => $this->opts['name_plural'],
            'Entities' => string_to_camel('_', $this->opts['name_plural']),
            'ENTITIES' => strtoupper($this->opts['name_plural']),
            'form' => $this->opts['name'],
        );

        if ($this->opts['frontend']['add']){

            $add_file = $this->root . "system/controllers/{$this->component}/actions/add_{$this->opts['name']}.php";
            $this->makeFromTemplate($this->component, $add_file, 'entity/frontend/add.php', $replacements);

            $add_tpl_file = $this->root . "templates/default/controllers/{$this->component}/form_{$this->opts['name']}.tpl.php";
            $this->makeFromTemplate($this->component, $add_tpl_file, 'entity/frontend/add.tpl', $replacements);

            $this->appendToLang($this->component, array('lang_'.$this->component.'_add_'.$this->opts['name'] => sprintf(LANG_CONTENT_ADD_ITEM, mb_strtolower($this->opts['title']))));

        }

        if ($this->opts['frontend']['еdit']){

            $edit_file = $this->root . "system/controllers/{$this->component}/actions/edit_{$this->opts['name']}.php";
            $this->makeFromTemplate($this->component, $edit_file, 'entity/frontend/edit.php', $replacements);

            if (!$this->opts['frontend']['add']){
                $add_tpl_file = $this->root . "templates/default/controllers/{$this->component}/form_{$this->opts['name']}.tpl.php";
                $this->makeFromTemplate($this->component, $add_tpl_file, 'entity/frontend/add.tpl', $replacements);
            }

            $this->appendToLang($this->component, array('lang_'.$this->component.'_edit_'.$this->opts['name'] => sprintf(LANG_CONTENT_EDIT_ITEM, mb_strtolower($this->opts['title']))));

        }

        if ($this->opts['frontend']['delete']){

            $delete_file = $this->root . "system/controllers/{$this->component}/actions/delete_{$this->opts['name']}.php";
            $this->makeFromTemplate($this->component, $delete_file, 'entity/frontend/delete.php', $replacements);

            $this->appendToLang($this->component, array('lang_'.$this->component.'_delete_'.$this->opts['name'] => sprintf(LANG_CONTENT_DELETE_ITEM, mb_strtolower($this->opts['title']))));
            $this->appendToLang($this->component, array('lang_'.$this->component.'_delete_'.$this->opts['name'].'_confirm' => sprintf('Удалить %s?', mb_strtolower($this->opts['title']))));

        }

        if ($this->opts['frontend']['list']){

            $list_file = $this->root . "system/controllers/{$this->component}/actions/{$this->opts['name_plural']}.php";
            $this->makeFromTemplate($this->component, $list_file, 'entity/frontend/list.php', $replacements);

            $fields = array();

            foreach($this->opts['fields'] as $field){
                if (empty($field['is_fl'])){ continue; }
                $fields[] = '<div class="'.$field['name'].'"><?php echo $'.$this->opts['name'].'[\''.$field['name'].'\']; ?></div>';
            }

            $fields = implode("\n", array_map(function($val){
                return str_repeat("\t", 3) . $val;
            }, $fields));

            $list_tpl_file = $this->root . "templates/default/controllers/{$this->component}/{$this->opts['name_plural']}.tpl.php";
            $this->makeFromTemplate($this->component, $list_tpl_file, 'entity/frontend/list.tpl', array_merge($replacements, array('fields'=>$fields)));

        }

        if ($this->opts['frontend']['item']){

            $item_file = $this->root . "system/controllers/{$this->component}/actions/{$this->opts['name']}.php";
            $this->makeFromTemplate($this->component, $item_file, 'entity/frontend/item.php', $replacements);

            $fields = array();

            foreach($this->opts['fields'] as $field){
                if (empty($field['is_fl'])){ continue; }
                $fields[] = '<div class="'.$field['name'].'"><?php echo $'.$this->opts['name'].'[\''.$field['name'].'\']; ?></div>';
            }

            $fields = implode("\n", array_map(function($val){
                return str_repeat("\t", 1) . $val;
            }, $fields));

            $item_tpl_file = $this->root . "templates/default/controllers/{$this->component}/{$this->opts['name']}.tpl.php";
            $this->makeFromTemplate($this->component, $item_tpl_file, 'entity/frontend/item.tpl', array_merge($replacements, array('fields'=>$fields)));

        }

    }

    private function generateBackend(){

        $replacements = array(
            'entity' => $this->opts['name'],
            'Entity' => string_to_camel('_', $this->opts['name']),
            'ENTITY' => strtoupper($this->opts['name']),
            'entities' => $this->opts['name_plural'],
            'Entities' => string_to_camel('_', $this->opts['name_plural']),
            'ENTITIES' => strtoupper($this->opts['name_plural']),
            'form' => $this->opts['name'],
        );

        if ($this->opts['backend']['form']){
            $form_file = $this->root . "system/controllers/{$this->component}/backend/forms/form_{$this->opts['name']}.php";
            $this->generateForm('is_bf', $form_file);
        }

        if ($this->opts['backend']['list']){

            $is_reorder = $this->opts['backend']['reorder'];

            $list_file = $this->root . "system/controllers/{$this->component}/backend/actions/{$this->opts['name_plural']}.php";
            $this->makeFromTemplate($this->component, $list_file, 'entity/backend/list.php', $replacements);

            $list_ajax_file = $this->root . "system/controllers/{$this->component}/backend/actions/{$this->opts['name_plural']}_ajax.php";
            $this->makeFromTemplate($this->component, $list_ajax_file, 'entity/backend/list_ajax.php', $replacements);

            $list_tpl = 'entity/backend/' . ($is_reorder ? 'list_reorder.tpl' : 'list.tpl');
            $list_tpl_file = $this->root . "templates/default/controllers/{$this->component}/backend/{$this->opts['name_plural']}.tpl.php";
            $this->makeFromTemplate($this->component, $list_tpl_file, $list_tpl, $replacements);

            if ($is_reorder){
                $reorder_file = $this->root . "system/controllers/{$this->component}/backend/actions/{$this->opts['name_plural']}_reorder.php";
                $this->makeFromTemplate($this->component, $reorder_file, 'entity/backend/reorder.php', $replacements);
            }

            $grid_file = $this->root . "system/controllers/{$this->component}/backend/grids/grid_{$this->opts['name_plural']}.php";
            $this->generateGrid('is_bl', $this->opts['backend']['reorder'], $grid_file);

        }

        if ($this->opts['backend']['menu']){

            $item = $this->getTemplate('backend/menu_item', array(
                'title' => strtoupper('lang_'.$this->opts['component'].'_'.$this->opts['name_plural']),
                'action' => $this->opts['name_plural'],
            ));

            $menu_file = $this->root . "system/controllers/{$this->component}/backend.php";

            $this->appendTo($menu_file, 'comgen-backend-menu', $item);

        }

        if ($this->opts['backend']['add']){

            $add_file = $this->root . "system/controllers/{$this->component}/backend/actions/add_{$this->opts['name']}.php";
            $this->makeFromTemplate($this->component, $add_file, 'entity/backend/add.php', $replacements);

            $add_tpl_file = $this->root . "templates/default/controllers/{$this->component}/backend/form_{$this->opts['name']}.tpl.php";
            $this->makeFromTemplate($this->component, $add_tpl_file, 'entity/backend/add.tpl', $replacements);

            $this->appendToLang($this->component, array('lang_'.$this->component.'_add_'.$this->opts['name'] => sprintf(LANG_CONTENT_ADD_ITEM, mb_strtolower($this->opts['title']))));

        }

        if ($this->opts['backend']['еdit']){

            $edit_file = $this->root . "system/controllers/{$this->component}/backend/actions/edit_{$this->opts['name']}.php";
            $this->makeFromTemplate($this->component, $edit_file, 'entity/backend/edit.php', $replacements);

            if (!$this->opts['backend']['add']){
                $add_tpl_file = $this->root . "templates/default/controllers/{$this->component}/backend/form_{$this->opts['name']}.tpl.php";
                $this->makeFromTemplate($this->component, $add_tpl_file, 'entity/backend/add.tpl', $replacements);
            }

            $this->appendToLang($this->component, array('lang_'.$this->component.'_edit_'.$this->opts['name'] => sprintf(LANG_CONTENT_EDIT_ITEM, mb_strtolower($this->opts['title']))));

        }

        if ($this->opts['backend']['delete']){

            $delete_file = $this->root . "system/controllers/{$this->component}/backend/actions/delete_{$this->opts['name']}.php";
            $this->makeFromTemplate($this->component, $delete_file, 'entity/backend/delete.php', $replacements);

            $this->appendToLang($this->component, array('lang_'.$this->component.'_delete_'.$this->opts['name'] => sprintf(LANG_CONTENT_DELETE_ITEM, mb_strtolower($this->opts['title']))));
            $this->appendToLang($this->component, array('lang_'.$this->component.'_delete_'.$this->opts['name'].'_confirm' => sprintf('Удалить %s?', mb_strtolower($this->opts['title']))));

        }

    }

    public function generateForm($trigger_field, $form_file){

        if (empty($this->opts['fields']) || !is_array($this->opts['fields'])) { die('aa'); return; }

        $labels = array();
        $fields = array();

        foreach($this->opts['fields'] as $field){

            if ($trigger_field !== false && !$field[$trigger_field]){ continue; }

            $title_lang = strtoupper('LANG_' . $this->component . '_' . $this->opts['name'] . '_' . $field['name']);
            $hint_lang = !empty($field['hint']) ? $title_lang . '_HINT' : '';

            $field_params = array();

            $labels[$title_lang] = $field['title'];

            if ($hint_lang) {
                $labels[$hint_lang] = $field['hint'];
                $field_params[] = "'hint' => {$hint_lang},";
            }

            if (!empty($field['is_req'])){
                $field_params[] = "'rules' => array(";
                $field_params[] = "\tarray('required'),";
                $field_params[] = "),";
            }

            $field_params = implode("\n", array_map(function($val){
                return str_repeat("\t", 6) . $val;
            }, $field_params));

            $field_code = $this->getTemplate('entity/form/field', array(
                'Type' => ucfirst($field['type']),
                'name' => $field['name'],
                'title_lang' => $title_lang,
                'params' => $field_params
            ));

            $fields[] = $field_code;

        }

        if ($labels){
            $this->appendToLang($this->component, $labels);
        }

        $fields = implode("\n\n", $fields);

        $form = $this->getTemplate('entity/form/form', array(
            'Component' => ucfirst($this->component),
            'Name' => string_to_camel('_', $this->opts['name']),
            'fields' => $fields
        ));

        file_put_contents($form_file, $form);

    }

    public function generateGrid($trigger_field, $is_sortable, $grid_file){

        if (empty($this->opts['fields']) || !is_array($this->opts['fields'])) { return; }

        $labels = array();
        $fields = array();

        foreach($this->opts['fields'] as $field){

            if (!$field[$trigger_field]){ continue; }

            $title_lang = strtoupper('LANG_' . $this->component . '_' . $this->opts['name'] . '_' . $field['name']);

            $labels[$title_lang] = $field['title'];

            $tpl = $field['name'] == 'title' ? 'grid_field_link' : 'grid_field';

            $field_code = $this->getTemplate('entity/backend/' . $tpl, array(
                'name' => $field['name'],
                'title_lang' => $title_lang,
                'entity' => $this->opts['name']
            ));

            $fields[] = $field_code;

        }

        if ($labels){
            $this->appendToLang($this->component, $labels);
        }

        $fields = implode("\n", $fields);

        $tpl = $is_sortable ? 'grid.php' : 'grid_static.php';

        $form = $this->getTemplate('entity/backend/' . $tpl, array(
            'NAME' => strtoupper($this->opts['component']),
            'entity' => $this->opts['name'],
            'Entity' => ucfirst($this->opts['name']),
            'ENTITY' => strtoupper($this->opts['name']),
            'entities' => $this->opts['name_plural'],
            'Entities' => ucfirst($this->opts['name_plural']),
            'ENTITIES' => strtoupper($this->opts['name_plural']),
            'fields' => $fields,
        ));

        file_put_contents($grid_file, $form);

    }

    public function generateAction($opts){

        $type = $opts['type'] == 'frontend' ? '' : 'backend/';

        $replacements = array(
            'Action' => string_to_camel('_', $opts['name']),
            'action' => $opts['name'],
            'type' => $type
        );

        $params = array();

        if ($opts['is_ajax']){
            $params[] = 'if (!$this->request->isAjax()) { cmsCore::error404(); }';
        }
        if ($opts['is_auth'] || $opts['is_admin']){
            $params[] = '$user = cmsUser::getInstance();';
        }
        if ($opts['is_auth']){
            $params[] = 'if (!$user->is_logged) { cmsCore::error404(); }';
        }
        if ($opts['is_admin']){
            $params[] = 'if (!$user->is_admin) { cmsCore::error404(); }';
        }

        $replacements['params'] = implode("\n\n", array_map(function($val){
            return str_repeat("\t", 2) . $val;
        }, $params));

        $result = array();

        if ($opts['result'] == 'tpl' || $opts['result'] == 'json'){
            $result[] = '$template = cmsTemplate::getInstance();';
        }
        if ($opts['result'] == 'tpl'){
            $result[] = 'return $template->render(\''.$opts['name'].'\', array())';
        }
        if ($opts['result'] == 'json'){
            $result[] = '$template->renderJSON(array());';
        }
        if ($opts['result'] == 'halt'){
            $result[] = '$this->halt();';
        }
        if ($opts['result'] == 'none'){
            $result[] = 'return;';
        }

        $replacements['result'] = implode("\n", array_map(function($val){
            return str_repeat("\t", 2) . $val;
        }, $result));

        $file = $this->root . "system/controllers/{$opts['component']}/{$type}actions/{$opts['name']}.php";
        $this->makeFromTemplate($opts['component'], $file, 'action.php', $replacements);

        if ($opts['result'] == 'tpl'){
            $tpl_file = $this->root . "templates/default/controllers/{$opts['component']}/{$type}{$opts['name']}.tpl.php";
            $this->makeFromTemplate($opts['component'], $tpl_file, 'action.tpl', $replacements);
        }

    }

    public function generateHook($opts){

        $replacements = array(
            'Hook' => string_to_camel('_', $opts['name']),
            'hook' => $opts['name']
        );

        $file = $this->root . "system/controllers/{$opts['component']}/hooks/{$opts['name']}.php";
        $this->makeFromTemplate($opts['component'], $file, 'hook.php', $replacements);

        $manifest_file = $this->root . "system/controllers/{$opts['component']}/manifest.php";

        $this->appendTo($manifest_file, 'comgen-hooks', "\t\t\t'{$opts['name']}',");

    }

}
