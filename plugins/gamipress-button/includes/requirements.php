<?php
/**
 * Requirements
 *
 * @package GamiPress\Button\Requirements
 * @since 1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Add the button fields to the requirement object
 *
 * @param $requirement
 * @param $requirement_id
 *
 * @return array
 */
function gamipress_button_requirement_object( $requirement, $requirement_id ) {

    if( isset( $requirement['trigger_type'] ) ) {

        if( $requirement['trigger_type'] === 'gamipress_specific_id_button_click'
            || $requirement['trigger_type'] === 'gamipress_user_specific_id_button_click' ) {
            // The button id
            $requirement['button_id'] = gamipress_get_post_meta( $requirement_id, '_gamipress_button_id', true );
        }

        if( $requirement['trigger_type'] === 'gamipress_specific_class_button_click'
            || $requirement['trigger_type'] === 'gamipress_user_specific_class_button_click' ) {
            // The button class
            $requirement['button_class'] = gamipress_get_post_meta( $requirement_id, '_gamipress_button_class', true );
        }
    }

    return $requirement;
}
add_filter( 'gamipress_requirement_object', 'gamipress_button_requirement_object', 10, 2 );

/**
 * Button fields on requirements UI
 *
 * @param $requirement_id
 * @param $post_id
 */
function gamipress_button_requirement_ui_fields( $requirement_id, $post_id ) {

    $button_id = gamipress_get_post_meta( $requirement_id, '_gamipress_button_id', true );
    $button_class = gamipress_get_post_meta( $requirement_id, '_gamipress_button_class', true ); ?>

    <input type="text" name="button_id" class="input-button-id" value="<?php echo $button_id; ?>" placeholder="<?php echo __( 'Button id attribute', 'gamipress-button' ); ?>">
    <input type="text" name="button_class" class="input-button-class" value="<?php echo $button_class; ?>" placeholder="<?php echo __( 'Button class attribute', 'gamipress-button' ); ?>">

    <?php
}
add_action( 'gamipress_requirement_ui_html_after_achievement_post', 'gamipress_button_requirement_ui_fields', 10, 2 );

/**
 * Custom handler to save the button fields on requirements UI
 *
 * @param $requirement_id
 * @param $requirement
 */
function gamipress_button_ajax_update_requirement( $requirement_id, $requirement ) {

    if( isset( $requirement['trigger_type'] ) ) {

        if( $requirement['trigger_type'] === 'gamipress_specific_id_button_click'
            || $requirement['trigger_type'] === 'gamipress_user_specific_id_button_click' ) {
            // The button id
            update_post_meta( $requirement_id, '_gamipress_button_id', $requirement['button_id'] );
        }

        if( $requirement['trigger_type'] === 'gamipress_specific_class_button_click'
            || $requirement['trigger_type'] === 'gamipress_user_specific_class_button_click' ) {
            // The button class
            update_post_meta( $requirement_id, '_gamipress_button_class', $requirement['button_class'] );
        }
    }
}
add_action( 'gamipress_ajax_update_requirement', 'gamipress_button_ajax_update_requirement', 10, 2 );

/**
 * Shortcode preview on requirements UI
 *
 * @since 1.0.0
 *
 * @param integer $requirement_id
 * @param integer $post_id
 */
function gamipress_button_shortcode_preview( $requirement_id, $post_id ) {
    $shortcode = GamiPress()->shortcodes['gamipress_button'];
    ?>
    <div class="gamipress-button-shortcode-preview" style="margin-top: 5px;">
        <label for="gamipress-button-shortcode-<?php echo $requirement_id; ?>"><?php _e( 'Code:', 'gamipress-button' ); ?></label>
        <input type="text" id="gamipress-button-shortcode-<?php echo $requirement_id; ?>" class="gamipress-button-shortcode" value="[gamipress_button]" readonly style="width: 400px;"/>
        <a href="#" style="display: block;margin-top: 5px;" onclick="jQuery(this).next().slideToggle('fast');return false;"><?php _e( 'See shortcode attributes', 'gamipress-button' ); ?></a>
        <ul style="display: none; list-style: disc; margin-left: 20px; margin-top: 5px;">
            <?php echo gamipress_shortcode_help_render_fields( $shortcode->fields ); ?>
        </ul>
    </div>
    <?php
}
add_action( 'gamipress_requirement_ui_html_after_requirement_title', 'gamipress_button_shortcode_preview', 10, 2 );