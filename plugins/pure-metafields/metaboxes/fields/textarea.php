<?php
/**
 * Textarea
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!isset($default)){
    $default = false;
}
?>
<?php if(!isset($row_db_value)): ?>
<textarea
    class="tm-textarea" 
    name="<?php echo esc_attr($id); ?>" 
    id="<?php echo esc_attr($id); ?>"
    placeholder="<?php echo esc_attr($placeholder?? 'Something...'); ?>"
><?php echo wp_kses_post(tpmeta_field($id))?? esc_html($default)?? ''; ?></textarea>
<?php else: ?>
<textarea
    class="tm-textarea" 
    name="<?php echo esc_attr($id); ?>[]" 
    id="<?php echo esc_attr($id); ?>"
    placeholder="<?php echo esc_attr($placeholder?? 'Something...'); ?>"
><?php echo esc_html($row_db_value); ?></textarea>
<?php endif; ?>