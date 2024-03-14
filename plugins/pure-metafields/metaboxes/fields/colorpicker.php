<?php
/**
 * Colorpicker
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php if(!isset($row_db_value)): ?>
<input 
    type="text" 
    class="tm-input tm-input-sm tm-colorpicker-input" 
    id="<?php echo esc_attr($id); ?>" 
    name="<?php echo esc_attr($id); ?>" 
    value="<?php echo esc_html(tpmeta_field($id))?? esc_html($default)?? esc_html('#2271b1'); ?>"
/>
<?php else: ?>
<input 
    type="text" 
    class="tm-input tm-input-sm tm-colorpicker-input" 
    id="<?php echo esc_attr($id); ?>" 
    name="<?php echo esc_attr($id); ?>[]" 
    value="<?php echo esc_html($row_db_value); ?>"
/>
<?php endif; ?>