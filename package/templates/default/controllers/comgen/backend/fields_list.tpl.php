<div id="comgen-fields-list">
    
    <input id="comgen-fields-list-json" type="hidden" name="<?php echo $name; ?>">
    
    <?php foreach($fields as $field) { ?>
        <?php 
            $this->renderChild('backend/fields_field', array(
                'field' => $field,
                'field_types' => $field_types,
                'is_show_targets' => $is_show_targets
            ));
        ?>
    <?php } ?>
    
</div>

<a id="btn-add-field" href="#">Добавить поле</a>

<script>
    $('#btn-add-field').click(function(e){
        e.preventDefault();
        var $tpl = $('.cg-field').last().clone();
        $('.input', $tpl).val('');
        $('.type', $tpl).val('string');
        $tpl.appendTo($('#comgen-fields-list'));
    });
    $('#comgen-fields-list').on('click', '.btn-remove-field', function(e){
        e.preventDefault();
        if ($('.cg-field').length == 1){
            return;
        }
        $(this).parent('.cg-field').remove();
    });
    $('form').submit(function(e){
        
        var data = [];
        
        $('.cg-field').each(function(index, field){
           
            var $field = $(field);
            var props = {};
            
            $('.input', $field).each(function(index, input){
                var $input = $(input);
                props[$input.attr('name')] = $input.val();
                $input.prop('disabled', true);
            });  
            $('select', $field).each(function(index, input){
                var $input = $(input);
                props[$input.attr('name')] = $input.val();
                $input.prop('disabled', true);
            });  
            $('.checkbox input', $field).each(function(index, input){
                var $input = $(input);
                props[$input.attr('name')] = $input.val();
                $input.prop('disabled', true);
            });
            
            data.push(props);
           
        });
        
        $('#comgen-fields-list-json').val(JSON.stringify(data));
        
    });
</script>