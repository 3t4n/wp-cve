<?php

/**
 * 
 * Tabs
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!isset($default)){
    $default = false;
}
?>
<?php if(!isset($row_db_value)): ?>
<div class="tm-button-groups">
    <?php foreach($choices as $key => $val): ?>
    <label class="tm-button-radio">
        <?php if(tpmeta_field($id) == ''): ?>
        <input 
        type="radio" 
        name="<?php echo esc_attr($id); ?>" <?php checked(esc_html($default), $key); ?> 
        value="<?php echo esc_html($key); ?>"
        class="<?php echo esc_attr($id); ?>-tab">
        <?php else: ?>
        <input 
        type="radio" 
        name="<?php echo esc_attr($id); ?>" <?php checked(tpmeta_field($id), $key); ?> 
        value="<?php echo esc_html($key); ?>"
        class="<?php echo esc_attr($id); ?>-tab">
        <?php endif; ?>
        <span><?php echo esc_html($val); ?></span>
    </label>
    <?php endforeach; ?>
</div>
<?php else: 
$bind_keys = isset($bind)? $bind : '';     
?>
<div class="tm-button-groups">
    <?php foreach($choices as $key => $val): ?>
    <label class="tm-button-radio">
        <input
        data-key="<?php echo esc_attr($bind_keys); ?>" 
        type="radio" 
        name="<?php echo esc_attr($id); ?>[]" <?php checked(esc_html($row_db_value), $key); ?> 
        value="<?php echo esc_html($key); ?>"
        class="<?php echo esc_attr($id); ?>-tab tm-repeater-conditional">
        <span><?php echo esc_html($val); ?></span>
    </label>
    <?php endforeach; ?>
</div>
<?php endif; ?>