<?php
/*
Plugin Name:    Notely
Plugin URI:     https://wordpress.org/plugins/notely/
Description:    Adds a new metabox into the Posts, Pages and Woo Commerce Products admin sidebar for making notes.
Version:        1.8.0
Author: 		Rocket Apps
Author URI:     https://rocketapps.com.au/
Text Domain:    notely
License:        GPL2
Domain Path:    /languages/
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Look for translation file.
function load_notely_textdomain() {
    load_plugin_textdomain( 'notely', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'load_notely_textdomain' );

class mo_notely {

    function  __construct() {

        /* metabox types */
        add_action( 'add_meta_boxes', array( $this, 'notelypost_meta_box' ) );
        add_action( 'save_post', array($this, 'save_data') );
    }

    
	// Add the meta boxes
    function notelypost_meta_box() {

        $options            = get_option( 'notely_settings' ); 
        $post_types         = isset($options['post_types']) ? $options['post_types'] : '';
        $metabox_context    = isset($options['metabox_context']) ? $options['metabox_context'] : '';

        if ($post_types) {
            add_meta_box(
                'notes',
                sprintf( __( 'Notes', 'notely' )),
                array( &$this, 'meta_box_content' ),
                $post_types,
                $metabox_context,
                'high'
            );
        }
    }

    function meta_box_content(){
        global $post;

        $options        = get_option( 'notely_settings' ); 
        $metabox_height = isset($options['metabox_height']) ? $options['metabox_height'] : '';
        $text_size      = isset($options['text_size']) ? $options['text_size'] : '';
        $font_family    = isset($options['font_family']) ? $options['font_family'] : '';

        if($metabox_height) {
            $metabox_height = $metabox_height;
        } else {
            $metabox_height = '120';
        }

        if($text_size) {
            $text_size = $text_size;
        } else {
            $text_size = '14';
        }

        if($font_family == 'mono') {
            $font_family = 'font-family: Courier, Monaco, monospace';
        } else 
        $font_family = '';

        // Use nonce for verification
        wp_nonce_field( plugin_basename( __FILE__ ), 'mo_notely_nounce' );

        // The actual fields for data entry
        echo '<textarea id="notelyfield" name="notelyfield" size="20" style="margin: 13px 0 0 0; width:100%; height:' . esc_html($metabox_height) . 'px; font-size: '  . esc_html($text_size) . 'px; line-height: inherit;' . esc_html($font_family) . '">' . esc_html(get_post_meta($post->ID, 'notely', TRUE)) . '</textarea>';
    }

    function save_data($post_id){

        $mo_notely_nounce = isset($_POST['mo_notely_nounce']) ? $_POST['mo_notely_nounce'] : '';

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;

        if ( !wp_verify_nonce( $mo_notely_nounce, plugin_basename( __FILE__ ) ) )
            return;

        // Check permissions
        if ( 'page' == $_POST['post_type'] ){
            if ( !current_user_can( 'edit_page', $post_id ) )
                return;
        }else{
            if ( !current_user_can( 'edit_post', $post_id ) )
                return;
        }
        $data = wp_strip_all_tags($_POST['notelyfield']);
        update_post_meta($post_id, 'notely', $data, get_post_meta($post_id, 'notely', TRUE));
        return $data;
    }
}
$mo_notely = new mo_notely;


add_action( 'admin_enqueue_scripts', 'load_admin_styles' );
function load_admin_styles() {
    wp_enqueue_style( 'admin_notely_css', plugins_url() . '/notely/css/notely.css', false, '1.0.0' );
}

// Add JS to admin
function my_custom_js() { ?>
    <script>
    // Toggle notes (Notely plugin)
    jQuery(function($) {
        $(".notely-icon").click(function(){
            $(this).next(".notely-preserve").slideToggle(150);
            $(this).toggleClass("notely-open");
        });
    });
    </script>
<?php }
// Add hook for admin <head></head>
add_action('admin_head', 'my_custom_js');


// Add notely to POST admin columns
add_filter('manage_posts_columns', 'notely_post_columns');
function notely_post_columns($columns) {
    
    unset( $columns['comments'] );
    unset( $columns['tags'] );
    unset( $columns['notely'] );
    unset( $columns['title'] );
    unset( $columns['categories'] );
    unset( $columns['author'] );
    unset( $columns['date'] );

    $columns['title'] = __( 'Title', 'notely' );
    $columns['author'] = __( 'Author', 'notely' );
    $columns['notely'] = __( 'Notes', 'notely' );
    $columns['categories'] = __( 'Categories', 'notely' );
    $columns['tags'] = __( 'Tags', 'notely' );
    $columns['comments'] = '<span class="vers comment-grey-bubble" title="' . __( 'Author', 'notely' ) . '" aria-hidden="true"></span>';
    $columns['date'] = __( 'Date', 'notely' );

    return $columns;
}

add_action('manage_posts_custom_column',  'notely_show_post_columns');
function notely_show_post_columns($name) {
    global $post;
    switch ($name) {
        case 'notely':
        $notely         = get_post_meta($post->ID, 'notely', true);
        $options        = get_option( 'notely_settings' );
        $note_color     = isset($options['note_color']) ? $options['note_color'] : '';
        $visibility     = isset($options['visibility']) ? $options['visibility'] : '';

        if ($notely !="") { ?>

            <?php if($visibility == "hidden") { ?>

                <span class="notely-icon note-icon-<?php echo esc_html($note_color); ?>" title="<?php echo esc_html_e('Show Notes', 'notely'); ?>">&#9780;</span>
                <pre class="notely-preserve <?php if($note_color) { echo esc_html($note_color); } ?>"><?php echo esc_html($notely); ?></pre>

            <?php } else { ?>
                <pre class="notely-preserve notely-preserve-shown"><?php echo esc_html($notely); ?></pre>
            <?php } ?>

        <?php } else {
            echo '—';
        }
    }
}

// Add notely to PAGE admin columns
add_filter('manage_pages_columns', 'notely_page_columns');
function notely_page_columns($columns) {
    
    unset( $columns['title'] );
    unset( $columns['author'] );
    unset( $columns['comments'] );
    unset( $columns['notely'] );
    unset( $columns['date'] );

    $columns['title'] = __( 'Title', 'notely' );
    $columns['author'] = __( 'Author', 'notely' );
    $columns['notely'] = __( 'Notes', 'notely' );
    $columns['comments'] = '<span class="vers comment-grey-bubble" title="' . __( 'Author', 'notely' ) . '" aria-hidden="true"></span>';
    $columns['date'] = __( 'Date', 'notely' );
    
    return $columns;
}

add_action('manage_pages_custom_column',  'notely_show_page_columns');
function notely_show_page_columns($name) {
    global $post;
    switch ($name) {

        case 'notely':
        $notely         = get_post_meta($post->ID, 'notely', true);
        $options        = get_option( 'notely_settings' );
        $visibility     = isset($options['visibility']) ? $options['visibility'] : '';
        $note_color     = isset($options['note_color']) ? $options['note_color'] : '';

        if ($notely !='') { ?>

            <?php if($visibility == "hidden") { ?>

            <span class="notely-icon note-icon-<?php echo esc_html($note_color); ?>" title="<?php echo esc_html_e('Show Notes', 'notely'); ?>">&#9780;</span>
            <pre class="notely-preserve <?php if($note_color) { echo esc_html($note_color); } ?>"><?php echo esc_html($notely); ?></pre>

            <?php } else { ?>
            <pre class="notely-preserve notely-preserve-shown"><?php echo esc_html($notely); ?></pre>
            <?php } ?>

        <?php } else {
            echo '—';
        }
    }
}

/* Start settings page UI */
require_once('inc/settings-ui.php');

// Show message upon plugin activation
register_activation_hook( __FILE__, 'notely_admin_notice_activation_hook' );
 
// Runs only when the plugin is activated
function notely_admin_notice_activation_hook() {
 
    /* Create transient data */
    set_transient( 'notely-admin-notice', true, 1000 );
}

/* Add admin notice */
add_action( 'admin_notices', 'notely_admin_notice' );
 
// Admin Notice on Activation
function notely_admin_notice() {
 
    /* Check transient, if available display notice */
    if( get_transient( 'notely-admin-notice' ) ){
        ?>
        <div class="updated notice is-dismissible ir-admin-message">
            <?php $presentation_options_url = admin_url() . 'options-general.php?page=notely-settings'; ?>
            <p><?php printf( __( 'Pro tip: You can tweak Notely options <a href="%s">here</a>.', 'notely' ), $presentation_options_url); ?></p>
        </div>
        <?php
        /* Delete transient, only display this notice once. */
        delete_transient( 'notely-admin-notice' );
    }
}