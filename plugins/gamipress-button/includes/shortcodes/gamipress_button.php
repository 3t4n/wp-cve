<?php
/**
 * GamiPress Button Shortcode
 *
 * @package     GamiPress\Shortcodes\Shortcode\GamiPress_Button
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the [gamipress_button] shortcode.
 *
 * @since 1.0.0
 */
function gamipress_register_button_shortcode() {
    gamipress_register_shortcode( 'gamipress_button', array(
        'name'            => __( 'Button', 'gamipress' ),
        'description'     => __( 'Render a button.', 'gamipress' ),
        'output_callback' => 'gamipress_button_shortcode',
        'fields'      => array(
            'label' => array(
                'name'        => __( 'Label', 'gamipress' ),
                'description' => __( 'The button label text.', 'gamipress-button' ),
                'type' 	=> 'text',
            ),
            'type' => array(
                'name'        => __( 'Button Type', 'gamipress' ),
                'description' => __( 'The button type.', 'gamipress-button' ),
                'type'        => 'select',
                'options'     => array(
                    'submit' => __( 'Submit', 'gamipress' ),
                    'button' => __( 'Button', 'gamipress' ),
                    'reset' => __( 'Reset', 'gamipress' ),
                )
            ),
            'id' => array(
                'name'        => __( 'Button ID', 'gamipress' ),
                'description' => __( 'The button identifier.', 'gamipress-button' ),
                'type'        => 'text',
            ),
            'class' => array(
                'name'        => __( 'CSS Classes', 'gamipress' ),
                'description' => __( 'The button CSS classes.', 'gamipress-button' ),
                'type'        => 'text',
            ),
            'form' => array(
                'name'        => __( 'Button Form', 'gamipress' ),
                'description' => __( 'The button form attribute. If you don\'t know exactly what is the purpose of this attribute leave it blank.', 'gamipress-button' ),
                'type'        => 'text',
            ),
            'name' => array(
                'name'        => __( 'Button Name', 'gamipress' ),
                'description' => __( 'The button name attribute. If you don\'t know exactly what is the purpose of this attribute leave it blank.', 'gamipress-button' ),
                'type'        => 'text',
            ),
            'value' => array(
                'name'        => __( 'Button Value', 'gamipress' ),
                'description' => __( 'The button value attribute. If you don\'t know exactly what is the purpose of this attribute leave it blank.', 'gamipress-button' ),
                'type'        => 'text',
            ),
            'url' => array(
                'name'        => __( 'URL', 'gamipress' ),
                'description' => __( 'The URL to redirect the user after click the button.', 'gamipress-button' ),
                'type'        => 'text',
            ),
        ),
    ) );
}
add_action( 'init', 'gamipress_register_button_shortcode' );

/**
 * Button Shortcode.
 *
 * @since  1.0.0
 *
 * @param  array $atts Shortcode attributes.
 * @return string 	   HTML markup.
 */
function gamipress_button_shortcode( $atts = array() ) {

    global $post, $comment;

    // Get the received shortcode attributes
    $atts = shortcode_atts( array(
        'label'     => '',
        'type'      => 'submit',
        'id'        => '',
        'class'     => '',
        'form'      => '',
        'name'      => '',
        'value'     => '',
        'url'       => ''
    ), $atts, 'gamipress_button' );

    $post_id = ( $post ? $post->ID : 0 );
    $comment_id = ( $comment ? $comment->comment_ID : 0 );

    gamipress_button_enqueue_scripts();

    ob_start(); ?>
        <button type="<?php echo esc_attr( $atts['type'] ); ?>"
                id="<?php echo esc_attr( $atts['id'] ); ?>"
                class="gamipress-button <?php echo esc_attr( $atts['class'] ); ?>"
                form="<?php echo esc_attr( $atts['form'] ); ?>"
                name="<?php echo esc_attr( $atts['name'] ); ?>"
                value="<?php echo esc_attr( $atts['value'] ); ?>"
                data-url="<?php echo esc_attr( $atts['url'] ); ?>"
                data-post="<?php echo $post_id; ?>"
                data-comment="<?php echo $comment_id; ?>"
        ><?php echo esc_html( $atts['label'] ); ?></button>
    <?php $output = ob_get_clean();

    // Return our rendered button
    return $output;
}
