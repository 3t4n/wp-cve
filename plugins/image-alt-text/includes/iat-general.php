<?php

defined('ABSPATH') or die('Plugin file cannot be accessed directly.');

class iat_general
{

    public $conn;
    public $wp_posts;
    public $wp_postmeta;

    public function __construct()
    {
        global $wpdb;
        $this->conn = $wpdb;
        if (is_multisite()) {
            $get_blog_id = get_current_blog_id();
            $this->wp_posts = $this->conn->prefix . $get_blog_id . '_posts';
            $this->wp_postmeta = $this->conn->prefix . $get_blog_id . '_postmeta';
        } else {
            $this->wp_posts = $this->conn->prefix . 'posts';
            $this->wp_postmeta = $this->conn->prefix . 'postmeta';
        }
        add_action('admin_menu', array($this, 'fn_iat_add_admin_menu_page'));
        /* Remind me later */
        add_action('wp_ajax_iat_remind_me_later', array($this, 'fn_iat_remind_me_later'));
        /* Do not show again */
        add_action('wp_ajax_iat_do_not_show_again', array($this, 'fn_iat_do_not_show_again'));
        /* Admin Notice for Review */
        add_action('admin_notices', array($this, 'iat_admin_notices'));
        add_action( 'admin_enqueue_scripts', array($this,'iat_admin_scripts' ));
    }

    public function fn_iat_add_admin_menu_page()
    {
        /* add Image alt text menu page.  */
        $image_alt_text_menu_page = add_menu_page(
            esc_html(__('Image Alt Text', IMAGE_ALT_TEXT)),
            esc_html(__('Image Alt Text', IMAGE_ALT_TEXT)),
            'manage_options',
            'image-alt-text',
            array($this, 'fn_iat_image_alternative_text_handler'),
            'dashicons-format-image',
            4
        );

        /* css. */
        add_action('admin_print_styles-' . $image_alt_text_menu_page, array($this, 'fn_iat_image_alternative_text_css'));

        /* js. */
        add_action('admin_print_scripts-' . $image_alt_text_menu_page, array($this, 'fn_iat_image_alternative_text_js'));
    }

    public function fn_iat_image_alternative_text_css()
    {
        /* register. */
        wp_register_style('iat-bootstrap-css', plugins_url('/assets/css/bootstrap.min.css', dirname(__FILE__)), false, IAT_FILE_VERSION, 'all');
        wp_register_style('iat-datatable-css', plugins_url('/assets/css/datatable.min.css', dirname(__FILE__)), false, IAT_FILE_VERSION, 'all');
        wp_register_style('iat-admin-css', plugins_url('/assets/css/iat-admin.css', dirname(__FILE__)), false, IAT_FILE_VERSION, 'all');

        /* enqueue. */
        wp_enqueue_style('iat-bootstrap-css');
        wp_enqueue_style('iat-datatable-css');
        wp_enqueue_style('iat-admin-css');
    }

    public function fn_iat_image_alternative_text_js()
    {
        /* register. */
        wp_register_script('iat-bootstrap-js', plugins_url('/assets/js/bootstrap.min.js', dirname(__FILE__)), array('jquery'), IAT_FILE_VERSION, true);
        wp_register_script('iat-datatable-js', plugins_url('/assets/js/datatable.min.js', dirname(__FILE__)), array('jquery'), IAT_FILE_VERSION, true);
        wp_register_script('iat-admin-js', plugins_url('/assets/js/iat-admin.js', dirname(__FILE__)), array('jquery'), IAT_FILE_VERSION, true);

        /* enqueue. */
        wp_enqueue_script('iat-bootstrap-js');
        wp_enqueue_script('iat-datatable-js');
        wp_enqueue_script('iat-admin-js');

        /* localize script for wat-admin-js */
        wp_localize_script('iat-admin-js', 'iat_obj', array('ajaxurl' => admin_url('admin-ajax.php'), 'admin_url' => admin_url()));
    }

    public function fn_iat_image_alternative_text_handler()
    {
        /* add Image alt text view  */
        if (file_exists(IAT_FILE_PATH . '/admin/iat-missing-alt-txt-media-list.php')) {
            include_once(IAT_FILE_PATH . '/admin/iat-missing-alt-txt-media-list.php');
        }
    }

    public function fn_iat_remind_me_later()
    {

        $output = array();

        if (isset($_POST['action']) && $_POST['action'] == 'iat_remind_me_later') {

            $current_date = date('Y-m-d');
            $date = strtotime("+15 day", strtotime($current_date));
            $increment_date = strtotime(date('Y-m-d', $date));
            if ($increment_date) {
                $updated = update_option('iat_review_reminder', $increment_date);
                if ($updated) {
                    $flg = 1;
                    $output = array(
                        'flg' => $flg
                    );
                } else {
                    $flg = 0;
                    $message = __('Something is wrong', IMAGE_ALT_TEXT);
                    $output = array(
                        'flg' => $flg,
                        'message' => $message
                    );
                }
            }
        }
        echo json_encode($output);
        wp_die();
    }

    public function fn_iat_do_not_show_again()
    {

        $output = array();
        if (isset($_POST['action']) && $_POST['action'] == 'iat_do_not_show_again') {
            $updated = update_option('iat_do_not_show_again', 'yes');
            if ($updated) {
                $flg = 1;
                $output = array(
                    'flg' => $flg
                );
            } else {
                $flg = 0;
                $message = __('Something is wrong', IMAGE_ALT_TEXT);
                $output = array(
                    'flg' => $flg,
                    'message' => $message
                );
            }
        }
        echo json_encode($output);
        wp_die();
    }

    public function iat_admin_notices()
    {

        /* get current date */
        $current_date = date('Y-m-d');
        $current_date_string = strtotime($current_date);

        /* get reminde me later date value */
        $remind_me_date = get_option('iat_review_reminder');

        /* get do not show again review */
        $do_not_show = get_option('iat_do_not_show_again');

        if (isset($do_not_show) && $do_not_show != 'yes') {
            if ($remind_me_date < $current_date_string) { ?>
                <div class="notice notice-success is-dismissible review-notice mt-3">
                    <p>
                        <?php
                        _e('If you\'ve found our WordPress plugin <strong>Image Alt Text</strong> helpful, we would greatly appreciate it if you could take a moment to leave us a review. Your feedback helps us improve our plugin and also lets other users know the value of our product. Thank you for taking the time to share your thoughts!', IMAGE_ALT_TEXT);
                        ?>
                    </p>
                    <p>
                        <a role="button" href="https://wordpress.org/support/plugin/image-alt-text/reviews/#new-post" target="_blank" class="button button-primary">
                            <?php _e('Review', IMAGE_ALT_TEXT); ?>
                        </a>
                        <button class="button button-primary is-dismissible" id="remind-me-later">
                            <?php _e('Remind me later', IMAGE_ALT_TEXT); ?>
                        </button>
                        <button class="button button-primary" id="do-not-show-again">
                            <?php _e('Do not show again', IMAGE_ALT_TEXT); ?>
                        </button>
                    </p>
                </div>
        <?php }
        }
    }

    function iat_admin_scripts( $hook ) {        

        wp_enqueue_script( 'iat-admin-global', plugins_url('/assets/js/iat-global.js', dirname(__FILE__)), array('jquery'), IAT_FILE_VERSION, true);
        
    }
    
}

$iat_general = new iat_general();
