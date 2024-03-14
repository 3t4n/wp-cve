<?php
/**
 * Repeater Group
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !empty($field['conditional']) ){
    if(isset($row)){
        $compare_results = tpmeta_is_row_matched($field['conditional'], $row);
    }else{
        $compare_results = tpmeta_is_condition_matched($field['conditional'], $fields);
    }
}else{
    $compare_results = true;
}

$format = get_post_format() ? : 'standard';
$field['row_db_value']  = isset($row_db_value)? $row_db_value : '';
$field['field_type']    = isset($field_type)? esc_html($field_type) : '';
$field['repeater_id']   = isset($repeater_id)? esc_html($repeater_id) : '';

?>

<?php if(isset($post_format) && $post_format != ""): ?>
<div data-operand="<?php echo !empty($field['conditional'])? esc_attr($field['conditional'][1]) : ''; ?>" data-value="<?php echo !empty($field['conditional'])? esc_attr($field['conditional'][2]) : ''; ?>" class="tm-field-row <?php echo esc_attr(esc_html($field['id'])); ?>" style="display:<?php echo !$compare_results || ($format != $post_format)? 'none' : 'block'; ?>">
    <label><?php echo esc_html($field['label']); ?></label>
    <?php tpmeta_load_template('metaboxes/fields/'.$field['type'].'.php', $field); ?>
</div>
<?php else: ?>
<div data-operand="<?php echo !empty($field['conditional'])? esc_attr($field['conditional'][1]) : ''; ?>" data-value="<?php echo !empty($field['conditional'])? esc_attr($field['conditional'][2]) : ''; ?>" class="tm-field-row <?php echo esc_attr(esc_html($field['id'])); ?>" style="display:<?php echo !esc_html($compare_results)? 'none' : 'block'; ?>">
    <label><?php echo esc_html($field['label']); ?></label>
    <?php if($field['type'] == 'checkbox'): 
        $json_arr = array();
        $defaults = !empty($field['default'])? $field['default'] : array();
        $options_arr = !empty($field['options'])? $field['options'] : array();
        
        foreach($defaults as $val){
            if(array_key_exists($val, $options_arr)){
                $json_arr[$val] = $options_arr[$val];
            }
        }
        $json_arr =  !empty($row_db_value)? $row_db_value : $json_arr;
    ?>
        <input type="hidden" name="<?php echo esc_attr($field['id']); ?>[]" value="<?php echo esc_html(json_encode($json_arr)); ?>" class="checkbox-input <?php echo esc_attr($field['id']); ?>">
    <?php endif; ?>
    <?php tpmeta_load_template('metaboxes/fields/'.$field['type'].'.php', $field); ?>
</div>
<?php endif; ?>