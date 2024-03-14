<?php
defined('ABSPATH') or die('Exit');
add_action('admin_enqueue_scripts', 'truepush_load_truepush_script');
function truepush_load_truepush_script()
{
    global $post;
    if ($post) {
        wp_register_script('notice_active_script', plugins_url('receiver.js', __FILE__), array('jquery'), '1.2', true);
        wp_enqueue_script('notice_active_script');
        wp_localize_script('notice_active_script', 'ajax_object_tp', array('ajax_url' => admin_url('admin-ajax.php'), 'post_id' => $post->ID));
    }
}
add_action('wp_ajax_published_data', 'truepush_published_data');
function truepush_published_data()
{
    $post_id = isset($_GET['post_id']) ? 
            (filter_var($_GET['post_id'], FILTER_SANITIZE_NUMBER_INT))
            : '';   
    if (is_null($post_id)) {
        $data = array('error' => 'could not get post id');
    } else {
  
        $truepush_response = get_post_meta($post_id, 'truepush_response');
        if ($truepush_response && is_array($truepush_response)) {
            $truepush_response = $truepush_response[0];
        }

        delete_post_meta($post_id, 'truepush_response');

        $data = array( 'truepush_response' => $truepush_response);
    }
    echo wp_json_encode($data);
    exit;
}
class Truepush_Install
{
	public static $wpVersion = '1.0.7';
	public static $wpNoncekey = 'truepush_meta_box_nonce';
    public static $wpNonceaction = 'truepush_meta_box';
    public static $wpConfigNonceKey = 'truepush_config_page_nonce';
	public static $wpConfigNonceAction = 'truepush_config_page';
	public function __construct()
    {    	
    }
	public static function admin_css()
    {
    self::error_init();
   	 add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_required_files'));
   	 add_action('admin_menu', array(__CLASS__, 'admin_menu_add'));

   	 if (current_user_can('delete_users')) {
            add_action('admin_menu', array(__CLASS__, 'admin_menu_add'));
        }

    wp_enqueue_style('truepush-admin-menu-styles', plugin_dir_url(__FILE__).'views/css/truepush-menu-styles.css', false, Truepush_Install::$wpVersion);

       if (current_user_can('publish_posts') || current_user_can('edit_published_posts')) {
            add_action('admin_init', array(__CLASS__, 'truepush_options_add'));
        }

         if (current_user_can('delete_users')) {
            add_action('admin_menu', array(__CLASS__, 'admin_menu_add'));
        }
    }
    public static function admin_required_files()
    {
    	wp_enqueue_style('truepush-admin-styles', plugin_dir_url(__FILE__).'views/css/truepush-styles.css', false, Truepush_Install::$wpVersion);
        wp_enqueue_script('site', plugin_dir_url(__FILE__).'views/javascript/site.js', array('jquery'), Truepush_Install::$wpVersion);
        wp_enqueue_style('admin-main', plugin_dir_url(__FILE__).'views/css/styles.css', false, Truepush_Install::$wpVersion);

    }
    public static function admin_menu_add()
        {
			  $TruePush_menu = add_menu_page('Truepush Push',
                                        'Truepush Push',
                                        'manage_options',
                                        'truepush-push',               
                                        array(__CLASS__, 'admin_menu'
                                        )
            );
            Truepush_main::tp_config_settings_form();
            add_action('load-'.$TruePush_menu, array(__CLASS__, 'truepush_setup_status'));
              
        }
    public static function admin_menu()
    {
        require_once plugin_dir_path(__FILE__).'/views/index.php';
    }
    public static function truepush_setup_status()
    {

        $tpSettings = Truepush_Initialize::getTpSettings();
        if (
        $tpSettings['platform_id'] === '' ||
        $tpSettings['truepush_api_key'] === ''
        ) {
            function truepush_admin_notice_setup_not_complete()
            {
                ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    activateSetupTab('setup/0');
                });
            </script>
            <div class="error notice truepush-error-notice">
                <p><strong>Truepush :</strong> <em>Required fields are missing. Login to <a href="https://app.truepush.com/" target="_blank" class="link" >app.truepush.com</a> and select wordpress to find your App id and API token</em></p>
            </div>
            <?php
            }
            add_action('admin_notices', 'truepush_admin_notice_setup_not_complete');
        }
        if (!function_exists('curl_init')) {
            function truepush_admin_notice_curl_not_installed()
            {
                ?>
            <div class="error notice truepush-error-notice">
                <p><strong>Truepush :</strong> <em>cURL is not installed on this server. cURL is required to send notifications. Please make sure cURL is installed on your server before continuing.</em></p>
            </div>
            <?php
            }
            add_action('admin_notices', 'truepush_admin_notice_curl_not_installed');
        }
    }
    public static function truepush_options_add()
    {
    
        function truepush_api_error()
	    {
            $allowed_html = [
            'div' => [
            'class' => []
            ],
            'strong' => [],
            'a' => [],
            'p' => [],
            'em' => []
            ];
            	$truepush_success_message = get_transient('truepush_success_message');
                if (!empty($truepush_success_message)) {
                    delete_transient('truepush_success_message');
                    echo wp_kses($truepush_success_message, $allowed_html);
                }

                $truepush_error_message = get_transient('truepush_error_message');
                if (!empty($truepush_error_message)) {
                    delete_transient('truepush_error_message');
                    echo wp_kses($truepush_error_message, $allowed_html);
                }                
        }
        add_action('admin_notices', 'truepush_api_error');

        self::trupush_add_metabox();
      
    }

    public static function truepush_notif_on_post_html_view($post)
    {
        $post_type = $post->post_type;
        $tpSettings = Truepush_Initialize::getTpSettings();

        wp_nonce_field(Truepush_Install::$wpNonceaction, Truepush_Install::$wpNoncekey, true);

        $tp_settings_send_notification_on_wp_editor_post = $tpSettings['tp_publishNotification'];

        if ((get_post_meta($post->ID, 'truepush_send_notification', true) === '1')) {
            $tp_meta_box_checkbox_send_notification = true;
        } else {
            $tp_meta_box_checkbox_send_notification = ($tp_settings_send_notification_on_wp_editor_post &&  
                $post->post_type === 'post' &&  
                !in_array($post->post_status, array('publish', 'private', 'trash', 'inherit'), true)); 
        }

        if (has_filter('truepush_meta_box_send_notification_checkbox_state')) {
            $tp_meta_box_checkbox_send_notification = apply_filters('truepush_meta_box_send_notification_checkbox_state', $post, $tpSettings);
        }
        ?>
    
        <input type="hidden" name="truepush_meta_box_present" value="true"></input>
        <input type="checkbox" name="send_truepush_notification" value="true" <?php if ($tp_meta_box_checkbox_send_notification) {
                echo 'checked';
            } ?>></input>
        <label>
        <?php if ($post->post_status === 'publish') {
            echo esc_attr('Send notification on '.$post_type.' update');
        } else {
            echo esc_attr('Send notification on '.$post_type.' publish');
        } ?>
        </label><br/><br/>
        <input type="hidden" name="notify_opt" value="true"></input>
        <input type="checkbox" name="site_titlee" id="site_titlee" <?php echo esc_html( $site_title_checked ); ?>>
        <label >Override Default Title and Message of current post and use below</label><br/ ><br/ >
        <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="tp-svg_icon_title">
                <label >Title</label>
            </div>
            <input type="text" name="notify_title" id="notify_title" class="tp-form-control" maxlength="60" value="<?php echo esc_attr(Truepush_Initialize::string_to_html(get_bloginfo('name'))); ?>">
            <div class="row" id="site_title_row" style="display: none;">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="tp-site-title-msg">Site title does not exist.</div>
                </div>
            </div>
            <span id="title_limit"></span>
        </div>
        </div> <br/>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="notify_content">Message</label>
                <textarea name="notify_content" id="notify_content" class="tp-form-control tp-form-textarea" maxlength="140" rows="3"></textarea>
                <span id="content_limit"></span>
            </div>
        </div>
        <?php
    }


    public static function error_init()
    {
        $truepush = new self();

        if (class_exists('WDS_Log_Post')) {
            function truepush_exception_error_handler($errno, $errstr, $errfile, $errline)
            {
                try {
                    switch ($errno) {
                      case E_USER_ERROR:
                          exit(1);
                          break;

                      case E_USER_WARNING:
                          break;

                      case E_USER_NOTICE || E_NOTICE:
                          break;

                      case E_STRICT:
                          break;

                      default:
                          break;
                  }

                    return true;
                } catch (Exception $ex) {
                    return true;
                }
            }

            function truepush_fatal_exception_error_handler()
            {
                $error = error_get_last();
                try {
                    switch ($error['type']) {
                      case E_ERROR:
                      case E_CORE_ERROR:
                      case E_COMPILE_ERROR:
                      case E_USER_ERROR:
                      case E_RECOVERABLE_ERROR:
                      case E_CORE_WARNING:
                      case E_COMPILE_WARNING:
                      case E_PARSE:
                        return false;
                    }
                } catch (Exception $ex) {
                    return true;
                }
            }

            register_shutdown_function('truepush_fatal_exception_error_handler');
        }

        add_action('save_post', array(__CLASS__, 'save_post_action'), 1, 3);
        add_action('transition_post_status', array(__CLASS__, 'send_truepush_post'), 10, 3);
      
        return $truepush;
    }

    public static function save_post_action($post_id, $post, $updated)
    {

        if ($post->post_type === 'wdslp-wds-log') {
            return;
        }
     
        if (!isset($_POST[Truepush_Install::$wpNoncekey])) {
            return $post_id;
        }

	    if (!wp_verify_nonce((isset($_POST[Truepush_Install::$wpNoncekey]) ? 
                sanitize_text_field($_POST[Truepush_Install::$wpNoncekey]) :
                 ''
            ), Truepush_Install::$wpNonceaction)) {
            return $post_id;
        }

  
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        if (array_key_exists('truepush_meta_box_present', $_POST)) {
            update_post_meta($post_id, 'truepush_meta_box_present', true);
        } else {
            update_post_meta($post_id, 'truepush_meta_box_present', false);
        }

       if (array_key_exists('send_truepush_notification', $_POST)) {
            update_post_meta($post_id, 'truepush_send_notification', true);
        } else {
            update_post_meta($post_id, 'truepush_send_notification', false);
        }
    }

    public static function send_truepush_post($new_status, $old_status, $post)
        {
            if ($post->post_type === 'wdslp-wds-log' ||
            Truepush_main::post_restore_status($old_status, $new_status)) {
               return;
            }
            if (has_filter('truepush_include_post')) {
                if (apply_filters('truepush_include_post', $new_status, $old_status, $post)) {
                    Truepush_main::send_notification_to_server($new_status, $old_status, $post);

                    return;
                }
            }
            if (has_filter('truepush_exclude_post')) {
                if (apply_filters('truepush_exclude_post', $new_status, $old_status, $post)) {
                    return;
                }
            }
            if (!(empty($post) ||
            $new_status !== 'publish' ||
            $post->post_type === 'page')) {
                Truepush_main::send_notification_to_server($new_status, $old_status, $post);
            }

            if (!(empty($post)  ||
            $new_status !== 'future' ||
            $post->post_type === 'page')) {
                Truepush_main::save_truepush_message_to_server($post);
               
            }
        }

    public static function trupush_add_metabox(){

    	add_meta_box('truepush_notif_on_post',
                 'Truepush Push Notifications',
                 array(__CLASS__, 'truepush_notif_on_post_html_view'),
                 'post',
                 'side',
                 'high');

        $args = array(
      'public' => true,
      '_builtin' => false,
        );
        $output = 'names';
        $operator = 'and';
        $post_types = get_post_types($args, $output, $operator);
        foreach ($post_types  as $post_type) {
            add_meta_box(
        'truepush_notif_on_post',
        'Truepush Push Notifications',
        array(__CLASS__, 'truepush_notif_on_post_html_view'),
        $post_type,
        'side',
        'high'
        );
        }

    }


}