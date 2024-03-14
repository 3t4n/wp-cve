<?php
/**
 * Text
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!isset($default)){
    $default = false;
}
if(!isset($placeholder)){
    $placeholder = '';
}
?>
<?php if(isset($row_db_value)): ?>
<input 
    type="text" 
    class="tm-input tm-input-sm" 
    id="<?php echo esc_attr($id); ?>" 
    name="<?php echo esc_attr($id); ?>[]"
    value="<?php echo esc_html($row_db_value); ?>"
    placeholder="<?php echo esc_html($placeholder) != ''? esc_html($placeholder): '';?>"
/>
<?php else: 
    $_value = is_array(tpmeta_field($id))? tpmeta_field($id)[0] : tpmeta_field($id);
?>
<input 
    type="text" 
    class="tm-input tm-input-sm" 
    id="<?php echo esc_attr($id); ?>" 
    name="<?php echo esc_attr($id); ?>"
    value="<?php echo  esc_html($_value)?? esc_html($_value)?? esc_html($default)?? ''; ?>"
    placeholder="<?php echo esc_html($placeholder) != ''? esc_html($placeholder): '';?>"
/>
<?php endif; ?>