<?php
/**
 * Select Posttype Posts
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 $get_posts = get_posts(array(
    'post_type' => $post_type,
    'post_status' => 'publish',
    'numberposts' => -1
 ));

?>
<?php if(!isset($row_db_value)): ?>
<select 
    name="<?php echo esc_attr($id); ?>" 
    id="<?php echo esc_attr($id); ?>-select" 
    class="<?php echo esc_attr($id); ?> tm-select-field">
    <option value="<?php echo esc_html($default?? '')?? esc_attr('-1'); ?>"><?php echo esc_html($placeholder)?? esc_html('Select...'); ?></option>
    <?php foreach($get_posts as $tp_post): ?>
        <option 
            value="<?php echo esc_html($tp_post->ID); ?>" 
            <?php selected(tpmeta_field($id), $tp_post->ID); ?>><?php echo esc_html($tp_post->post_title); ?>
        </option>
    <?php endforeach; wp_reset_postdata();?>
</select>
<?php else: 
$bind_keys = isset($bind)? $bind : '';    
?>
<select
    data-key="<?php echo esc_attr($bind_keys); ?>"
    name="<?php echo esc_attr($id); ?>[]"
    class="<?php echo esc_attr($id); ?> tm-repeater-select-field tm-repeater-conditional">
    <option value="<?php echo esc_html($default)?? esc_attr('-1'); ?>"><?php echo esc_html($placeholder)?? esc_html('Select...'); ?></option>
    <?php foreach($get_posts as $tp_post): ?>
        <option 
            value="<?php echo esc_html($tp_post->ID); ?>" 
            <?php selected(esc_html($row_db_value), $tp_post->ID); ?>><?php echo esc_html($tp_post->post_title); ?>
        </option>
    <?php endforeach; wp_reset_postdata();?>
</select>
<?php endif; ?>
