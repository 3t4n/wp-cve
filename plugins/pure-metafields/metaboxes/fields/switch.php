<?php
/**
 * Switch
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!isset($default)){
    $default = false;
}
?>
<?php if(isset($field_type) && $field_type == 'repeater'): 
    
$bind_keys = isset($bind)? esc_attr($bind) : '';
?>
<label class="tm-switch">
    <input 
    type="hidden"
    name="<?php echo esc_html($id); ?>[]"
    class="<?php echo esc_attr($id); ?>"
    value="<?php echo esc_html($row_db_value == ''? $default : $row_db_value); ?>">
    <input
    data-key="<?php echo esc_attr($bind_keys); ?>" 
    type="checkbox" <?php checked('on', esc_html($row_db_value) == ""? esc_html($default) : esc_html($row_db_value)); ?>
    class="<?php echo esc_attr($id); ?> tm-repeater-conditional"
    value="<?php echo esc_html($row_db_value == ''? $default : $row_db_value); ?>">
    <span class="tm-slider"></span>
</label>
<?php else: ?>
<label class="tm-switch">
    <input 
    type="checkbox" <?php checked('on', tpmeta_field($id) == ""? $default : tpmeta_field($id)); ?>
    name="<?php echo esc_html($id); ?>"
    id="<?php echo esc_attr($id); ?>"
    class="<?php echo esc_attr($id); ?>"
    value="<?php echo esc_html(tpmeta_field($id) == ''? $default : tpmeta_field($id)); ?>">
    <span class="tm-slider"></span>
</label>
<?php endif; ?>