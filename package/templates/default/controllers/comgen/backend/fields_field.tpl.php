<div class="cg-field">
    <a href="#" class="btn-remove-field">[X]</a>
    <div class="row">
        <div class="prop">
            <label>Системное имя:</label> <?php echo html_input('text', 'name', empty($field['name'])?'':$field['name'], array('class' => 'name')); ?>
        </div>
        <div class="prop">
            <label>Тип поля:</label> <?php echo html_select('type', $field_types, $field['type'], array('class' => 'type')); ?>
        </div>
        <div class="prop">
            <label>Название поля:</label> <?php echo html_input('text', 'title', empty($field['title'])?'':$field['title'], array('class' => 'title')); ?>
        </div>
        <div class="prop">
            <label>Пояснение к полю:</label> <?php echo html_input('text', 'hint', empty($field['hint'])?'':$field['hint'], array('class' => 'hint')); ?>
        </div>
        <div class="prop">
            <label>Обязательное:</label>
            <label class="checkbox">
                <?php echo html_checkbox('is_req', !empty($field['is_req']), 1, array('class' => 'is_req')); ?>
            </label>
        </div>
    </div>
    <?php if ($is_show_targets){ ?>
    <div class="row">
        <div class="prop">
            <label class="checkbox">
                <?php echo html_checkbox('is_ff', !empty($field['is_ff']), 1, array('class' => 'is_ff')); ?> 
                Фронтенд: Форма
            </label>
            <label class="checkbox">
                <?php echo html_checkbox('is_fl', !empty($field['is_fl']), 1, array('class' => 'is_fl')); ?> 
                Фронтенд: Список
            </label>
            <label class="checkbox">
                <?php echo html_checkbox('is_fi', !empty($field['is_fi']), 1, array('class' => 'is_fi')); ?> 
                Фронтенд: Запись
            </label>
            <label class="checkbox">
                <?php echo html_checkbox('is_bf', !empty($field['is_bf']), 1, array('class' => 'is_bf')); ?> 
                Aдминка: Форма
            </label>
            <label class="checkbox">
                <?php echo html_checkbox('is_bl', !empty($field['is_bl']), 1, array('class' => 'is_bl')); ?> 
                Админка: Список
            </label>
        </div>
    </div>
    <?php } ?>
</div>