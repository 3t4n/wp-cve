<?php if ( ! defined( 'ABSPATH' ) ) exit; 

/* Notely Settings */

/* Register settings */
function notely_free_settings_init(){
    register_setting(
		'notely_settings',
		'notely_settings',
		'notely_free_settings_validate'
	);
}

// Add settings page to menu
function add_notely_settings_page() {
    add_options_page( 'Notely', 'Notely', 'manage_options', 'notely-settings', 'notely_settings_page' );
}

// Add actions
add_action( 'admin_init', 'notely_free_settings_init' );
add_action( 'admin_menu', 'add_notely_settings_page' );


// Define your variables
$color_scheme = array('default','blue','green',);

// Start settings page
function notely_settings_page() {
?>
<div class="wrap">
    <h2><?php esc_html_e('Notely', 'notely'); ?></h2>
    <p><?php esc_html_e('Notely lets your create admin notes for any post, page or custom post type.', 'notely'); ?></p>

    <form method="post" action="options.php">
    	<?php settings_fields( 'notely_settings' ); ?>
        <?php 
            $options            = get_option( 'notely_settings' );
            $visibility 	    = isset($options['visibility']) ? $options['visibility'] : '';
            $metabox_context    = isset($options['metabox_context']) ? $options['metabox_context'] : '';
            $font_family        = isset($options['font_family']) ? $options['font_family'] : '';
            $metabox_height     = isset($options['metabox_height']) ? $options['metabox_height'] : '';
            $text_size          = isset($options['text_size']) ? $options['text_size'] : '';
            $note_color 	    = isset($options['note_color']) ? $options['note_color'] : '';
        ?>

        <table class="form-table notely-form-table">
            <tbody>
                <tr>
                    <td><strong><?php esc_html_e('Enable for these post types', 'notely'); ?></strong></td>
                    <td>
                        <?php
                            $post_types = get_post_types( array (
                                    'show_ui' => true,
                                    'show_in_menu' => true,
                                ), 
                                    'objects'
                                );
                                foreach ( $post_types as $post_type ) {
                                    $post_type_name = $post_type->name;
                                    $post_types = isset($options['post_types']) ? $options['post_types'] : '';
                                    if($post_type_name !== 'attachment'
                                    && $post_type_name !== 'menu_item'
                                    && $post_type_name !== 'revision'
                                    && $post_type_name !== 'nav_menu_item'
                                    && $post_type_name !== 'custom_css'
                                    && $post_type_name !== 'customize_changeset'
                                    && $post_type_name !== 'user_request'
                                    && $post_type_name !== 'wp_block') { 
                                ?>
                                
                                <input type="checkbox" name="notely_settings[post_types][]" value="<?php echo esc_html($post_type->name); ?>" <?php if ($post_types && in_array($post_type->name, $options['post_types'])) { echo 'checked'; } ?> /> <?php echo esc_html($post_type->label); ?><br />
                                
                                <?php
                                } 
                            }
                            /*
                            echo '<pre>';
                            var_dump($options);
                            echo '</pre>';
                            */
                        ?>
                    </td>
                    <td rowspan="7" class="border">
                        <?php require_once('my-tools.php'); ?>
                    </td>
                </tr>
                
                <tr>
                    <td><strong><?php esc_html_e('Admin column display', 'notely'); ?></strong></td>
                    <td>
                        <p><input type="radio" class="dash_red" name="notely_settings[visibility]" value="hidden" <?php if($visibility == 'hidden') { echo 'checked'; } ?> /> 
                        <?php esc_html_e('Hidden (tap icon to reveal note)', 'notely'); ?></p>
                        <p><input type="radio" class="dash_blue" name="notely_settings[visibility]" value="visible" <?php if($visibility == 'visible') { echo 'checked'; } ?> /> 
                        <?php esc_html_e('Always visible', 'notely'); ?></p>
                    </td>
                </tr>

                <tr>
                    <td><strong><?php esc_html_e('Default notes box position', 'notely'); ?></strong></td>
                    <td>
                        <p><input type="radio" name="notely_settings[metabox_context]" value="side" <?php if($metabox_context == 'side') { echo 'checked'; } ?> /> 
                        <?php esc_html_e('Sidebar', 'notely'); ?></p>
                        <p><input type="radio" name="notely_settings[metabox_context]" value="normal" <?php if($metabox_context == 'normal') { echo 'checked'; } ?> /> 
                        <?php esc_html_e('Below editor', 'notely'); ?></p>
                    </td>
                </tr>

                <tr>
                    <td><strong><?php esc_html_e('Font style', 'notely'); ?></strong></td>
                    <td>
                        <p><input type="radio" name="notely_settings[font_family]" value="mono" <?php if($font_family == 'mono') { echo 'checked'; } ?> /> 
                        <?php esc_html_e('Monospace', 'notely'); ?></p>
                        <p><input type="radio" name="notely_settings[font_family]" value="default" <?php if($font_family == 'default') { echo 'checked'; } ?> /> 
                        <?php esc_html_e('Default', 'notely'); ?></p>
                    </td>
                </tr>

                <tr>
                    <td><strong><?php esc_html_e('Notes box height', 'notely'); ?></strong></td>
                    <td>
                        <p><input type="number" name="notely_settings[metabox_height]" value="<?php echo $metabox_height; ?>" style="width: 70px" min="50" max="500" /> <?php esc_html_e('px', 'notely'); ?></p>
                    </td>
                </tr>

                <tr>
                    <td><strong><?php esc_html_e('Notes box text size', 'notely'); ?></strong></td>
                    <td>
                        <p><input type="number" name="notely_settings[text_size]" value="<?php echo $text_size; ?>" style="width: 70px" min="10" max="30" /> <?php esc_html_e('px', 'notely'); ?></p>
                    </td>
                </tr>

                <tr>
                    <td><strong><?php esc_html_e('Note icon colour', 'notely'); ?></strong></td>
                    <td>
                        <p><label><input type="radio" name="notely_settings[note_color]" value="default" <?php if($note_color == 'default') { echo 'checked'; } ?> /> <?php esc_html_e('Default', 'notely'); ?></label></p>
                        <p><label><input type="radio" name="notely_settings[note_color]" value="red" <?php if($note_color == 'red') { echo 'checked'; } ?> /> <?php esc_html_e('Red', 'notely'); ?></label></p>
                        <p><label><input type="radio" name="notely_settings[note_color]" value="blue" <?php if($note_color == 'blue') { echo 'checked'; } ?> /> <?php esc_html_e('Blue', 'notely'); ?></label></p>
                        <p><label><input type="radio" name="notely_settings[note_color]" value="yellow" <?php if($note_color == 'yellow') { echo 'checked'; } ?> /> <?php esc_html_e('Yellow', 'notely'); ?></label></p>
                        <p><label><input type="radio" name="notely_settings[note_color]" value="green" <?php if($note_color == 'green') { echo 'checked'; } ?> /> <?php esc_html_e('Green', 'notely'); ?></label></p>
                    </td>
                </tr>
                
            </tbody>
        </table>

        <p class="padder"><input name="submit" class="button button-primary" value="<?php esc_html_e('Save Settings', 'notely'); ?>" type="submit" /></p>

    </form>
</div>

<?php }
/* Sanitize and validate */
function notely_free_settings_validate( $input ) {
    $output = array();
    foreach ( $input as $key => $value ) {
        if ( isset( $input[$key] ) ) {
            if ( is_array( $input[$key] ) ) {
                $output[$key] = array_map( 'sanitize_text_field', $input[$key] );
            } else {
                $output[$key] = sanitize_text_field( $input[$key] );
            }
        }
    }
    return $output;
	wp_verify_nonce($_POST['og-stuff'], 'save-og-settings');
}