<?php
/**
 * Checkbox
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php if(isset($row_db_value)): 
    $_options = isset($options)? $options : array();    
?>
<?php foreach($_options as $key => $value): ?>
<div class="tpmeta-checkbox">
<input 
    type="checkbox" 
    class="tm-input tm-input-sm" 
    id="<?php echo esc_attr($key); ?>" 
    name="<?php echo esc_attr($key); ?>"
    value="<?php echo esc_html($value); ?>"
    <?php checked(!empty($row_db_value)? array_key_exists($key, $row_db_value) : in_array($key, $default), 1); ?>
/>
<label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></label>
</div>
<?php endforeach; ?>
<?php else: 
    $db_value_exist     = (!empty($post) && metadata_exists('post', $post->ID, $id))? get_post_meta( $post->ID, $id, true) : '';
    $default_val        = !empty($default)? $default : '';
    $_options           = isset($options)? $options : array('');
?>
<?php foreach($_options as $key => $value): ?>
<div class="tpmeta-checkbox">
<input 
    type="checkbox" 
    class="tm-input tm-input-sm" 
    id="<?php echo esc_attr($id.'_'.$key); ?>" 
    name="<?php echo esc_attr($id.'_'.$key); ?>"
    value="<?php echo esc_html($key); ?>"
    <?php checked( ($db_value_exist? array_key_exists($key, $db_value_exist) : (is_array($default)? in_array($key, $default) : $default)), 1); ?>
/>
<label for="<?php echo esc_attr($id.'_'.$key); ?>"><?php echo esc_html($value); ?></label>
</div>
<?php endforeach; ?>
<?php endif; ?>
<script>
    ;(function($){
        "use strict";
        var combination = {};
        $( document ).on('change', '.tpmeta-checkbox input', function(){
            var name = $( this ).attr('name'),
                val = $( this ).val(),
                isChecked = $( this ).prop('checked');
                combination = $( this ).closest('.<?php echo esc_attr($id); ?>').find('.checkbox-input').val() != undefined? JSON.parse($( this ).closest('.<?php echo esc_attr($id); ?>').find('.checkbox-input').val()) : {};
            if(isChecked){
                combination = {...combination, ...{[name]:val}};
            }else{
                delete combination[name];
            }
            $( this ).closest('.<?php echo esc_attr($id); ?>').find('.checkbox-input').val(JSON.stringify(combination));
        });
    })( jQuery );
</script>