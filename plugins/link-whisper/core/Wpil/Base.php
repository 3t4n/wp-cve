<?php

/**
 * Base controller
 */
class Wpil_Base
{
    public static $report_menu;
    public static $action_tracker = array();

    /**
     * Register services
     */
    public function register()
    {
        add_action('admin_init', [$this, 'init']);
        add_action('admin_menu', [$this, 'addMenu']);
        add_action('add_meta_boxes', [$this, 'addMetaBoxes']);
        add_action('admin_enqueue_scripts', [$this, 'addScripts']);
        add_action('plugin_action_links_' . WPIL_PLUGIN_NAME, [$this, 'showSettingsLink']);
        add_action('admin_notices', [$this, 'addEmailSignupNotice'], 7);
        add_action('admin_notices', [$this, 'add_notice_for_review'], 20);
        add_action('upgrader_process_complete', [$this, 'upgrade_complete'], 10, 2);
        add_action('wp_ajax_dismiss_email_offer_notice', [$this, 'ajax_dismiss_email_offer_notice']);
        add_action('wp_ajax_signed_up_email_offer_notice', [$this, 'ajax_signed_up_email_offer_notice']);
        add_action('wp_ajax_dismiss_premium_notice', [$this, 'ajax_dismiss_premium_notice']);
        add_action('wp_ajax_dismiss_review_notice', [$this, 'ajax_dismiss_review_notice']);
        add_action('wp_ajax_perm_dismiss_review_notice', [$this, 'ajax_perm_dismiss_review_notice']);
        add_action('wp_ajax_get_post_suggestions', ['Wpil_Suggestion','ajax_get_post_suggestions']);
        add_action('wp_ajax_update_suggestion_display', ['Wpil_Suggestion','ajax_update_suggestion_display']);
        add_action('wp_ajax_wpil_csv_export', ['Wpil_Export','ajax_csv']);
        foreach(Wpil_Settings::getPostTypes() as $post_type){
            add_filter( "manage_{$post_type}_posts_columns", array(__CLASS__, 'add_columns'), 11 );
            add_action( "manage_{$post_type}_posts_custom_column", array(__CLASS__, 'columns_contents'), 11, 2);
        }
    }

    /**
     * Initial function
     */
    function init()
    {
        $capability = apply_filters('wpil_filter_main_permission_check', 'manage_categories');
        if (!current_user_can($capability)) {
            return;
        }

        $post = self::getPost();

        if (!empty($_GET['csv_export'])) {
            Wpil_Export::csv();
        }

        if (!empty($_GET['area'])) {
            switch ($_GET['area']) {
                case 'wpil_export':
                    Wpil_Export::getInstance()->export($post);
                    break;
                case 'wpil_excel_export':
                    $post = self::getPost();
                    if (!empty($post)) {
                        Wpil_Excel::exportPost($post);
                    }
                    break;
            }
        }

        if (!empty($_POST['hidden_action'])) {
            switch ($_POST['hidden_action']) {
                case 'wpil_save_settings':
                    Wpil_Settings::save();
                    break;
            }
        }

        //add screen options
        add_action("load-" . self::$report_menu, function () {
            add_screen_option( 'report_options', array(
                'option' => 'report_options',
            ) );
        });
    }

    /**
     * This function is used for adding menu and submenus
     *
     *
     * @return  void
     */
    public function addMenu()
    {
        add_menu_page(
            'Link Whisper',
            'Link Whisper',
            'edit_posts',
            'link_whisper',
            [Wpil_Report::class, 'init'],
            plugin_dir_url(__DIR__). '../images/lw-icon-16x16.png'
        );

        self::$report_menu = add_submenu_page(
            'link_whisper',
            'Internal Links Report',
            'Report',
            'edit_posts',
            'link_whisper',
            [Wpil_Report::class, 'init']
        );

        add_submenu_page(
            'link_whisper',
            'Settings',
            'Settings',
            'manage_categories',
            'link_whisper_settings',
            [Wpil_Settings::class, 'init']
        );

        add_submenu_page(
            'link_whisper',
            'Premium',
            '<a class="link-whisper-get-premium-link" href="' . WPIL_STORE_URL . '" target="blank">Get Premium <span style="font-size: 16px; margin: 2px 0 -2px 0;" class="dashicons dashicons-admin-links"></span></a>',
            'manage_categories',
            WPIL_STORE_URL
        );
    }

    /**
     * Get post or term by ID from GET or POST request
     *
     * @return Wpil_Model_Post|null
     */
    public static function getPost()
    {
        if (!empty($_REQUEST['term_id'])) {
            $post = new Wpil_Model_Post((int)$_REQUEST['term_id'], 'term');
        } elseif (!empty($_REQUEST['post_id'])) {
            $post = new Wpil_Model_Post((int)$_REQUEST['post_id']);
        } else {
            $post = null;
        }

        return $post;
    }

    /**
     * Show plugin version
     *
     * @return string
     */
    public static function showVersion()
    {
        $plugin_data = get_plugin_data(WP_INTERNAL_LINKING_PLUGIN_DIR . 'link-whisper.php');

        return "<p style='float: right'>version <b>".$plugin_data['Version']."</b></p>";
    }

    /**
     * Show extended error message
     *
     * @param $errno
     * @param $errstr
     * @param $error_file
     * @param $error_line
     */
    public static function handleError($errno, $errstr, $error_file, $error_line)
    {
        if (stristr($errstr, "WordPress could not establish a secure connection to WordPress.org")) {
            return;
        }

        $file = 'n/a';
        $func = 'n/a';
        $line = 'n/a';
        $debugTrace = debug_backtrace();
        if (isset($debugTrace[1])) {
            $file = isset($debugTrace[1]['file']) ? $debugTrace[1]['file'] : 'n/a';
            $line = isset($debugTrace[1]['line']) ? $debugTrace[1]['line'] : 'n/a';
        }
        if (isset($debugTrace[2])) {
            $func = $debugTrace[2]['function'] ? $debugTrace[2]['function'] : 'n/a';
        }

        $out = "call from <b>$file</b>, $func, $line";

        $trace = '';
        $bt = debug_backtrace();
        $sp = 0;
        foreach($bt as $k=>$v) {
            extract($v);

            $args = '';
            if (isset($v['args'])) {
                $args2 = array();
                foreach($v['args'] as $k => $v) {
                    if (!is_scalar($v)) {
                        $args2[$k] = "Array";
                    }
                    else {
                        $args2[$k] = $v;
                    }
                }
                $args = implode(", ", $args2);
            }

            $file = substr($file,1+strrpos($file,"/"));
            $trace .= str_repeat("&nbsp;",++$sp);
            $trace .= "file=<b>$file</b>, line=$line,
									function=$function(".
                var_export($args, true).")<br>";
        }

        $out .= $trace;

        echo "<b>Error:</b> [$errno] $errstr - $error_file:$error_line<br><br><hr><br><br>$out";
    }

    /**
     * Add meta box to the post edit page
     */
    public static function addMetaBoxes()
    {
        $capability = apply_filters('wpil_filter_main_permission_check', 'manage_categories');
        if (!current_user_can($capability)) {
            return;
        }

        add_meta_box('wpil_link-articles', 'Link Whisper Suggested Links', array(__CLASS__, 'showSuggestionsBox'), Wpil_Settings::getPostTypes());
    }

    /**
     * Show meta box on the post edit page
     */
    public static function showSuggestionsBox()
    {
        $post_id = isset($_REQUEST['post']) ? (int)$_REQUEST['post'] : '';
        $user = wp_get_current_user();
        if ($post_id) {
            // clear any old links that may still be hiding in the meta
            delete_post_meta($post_id, 'wpil_links');
            include WP_INTERNAL_LINKING_PLUGIN_DIR . '/templates/link_list_v2.php';
        }else{
            include WP_INTERNAL_LINKING_PLUGIN_DIR . '/templates/link_list_please_save_post.php';
        }
    }

    /**
     * Add scripts to the admin panel
     *
     * @param $hook
     */
    public static function addScripts($hook)
    {
        $current_screen = null;
        if(function_exists('get_current_screen')){
            $current_screen = get_current_screen();
        }

        if (strpos($_SERVER['REQUEST_URI'], '/post.php') !== false || strpos($_SERVER['REQUEST_URI'], '/term.php') !== false || (!empty($_GET['page']) && $_GET['page'] == 'link_whisper')) {
            if(function_exists('wp_enqueue_editor')){
                wp_enqueue_editor();
            }
        }

        wp_register_script('wpil_sweetalert_script_min', WP_INTERNAL_LINKING_PLUGIN_URL . 'js/sweetalert.min.js', array('jquery'), $ver=false, true);
        wp_enqueue_script('wpil_sweetalert_script_min');

        $js_path = 'js/wpil_admin.js';
        $f_path = WP_INTERNAL_LINKING_PLUGIN_DIR.$js_path;
        $ver = filemtime($f_path);
        $current_screen = get_current_screen();

        wp_register_script('wpil_admin_script', WP_INTERNAL_LINKING_PLUGIN_URL.$js_path, array('jquery'), $ver, true);
        wp_enqueue_script('wpil_admin_script');

        if(isset($_GET['page']) && ($_GET['page'] == 'link_whisper_settings')){
            $js_path = 'js/wpil_admin_settings.js';
            $ver = filemtime(WP_INTERNAL_LINKING_PLUGIN_DIR.$js_path);
    
            wp_register_script('wpil_admin_settings_script', WP_INTERNAL_LINKING_PLUGIN_URL.$js_path, array('jquery', 'wpil_select2'), $ver, true);
            wp_enqueue_script('wpil_admin_settings_script');

            wp_register_style('wpil_select2_css', WP_INTERNAL_LINKING_PLUGIN_URL . 'css/select2.min.css');
            wp_enqueue_style('wpil_select2_css');
            wp_register_script('wpil_select2', WP_INTERNAL_LINKING_PLUGIN_URL . 'js/select2.full.min.js', array('jquery'), $ver, true);
            wp_enqueue_script('wpil_select2');
        }

        $style_path = 'css/wpil_admin.css';
        $f_path = WP_INTERNAL_LINKING_PLUGIN_DIR.$style_path;
        $ver = filemtime($f_path);

        wp_register_style('wpil_admin_style', WP_INTERNAL_LINKING_PLUGIN_URL.$style_path, array(), $ver);
        wp_enqueue_style('wpil_admin_style');

        $disable_fonts = apply_filters('wpil_disable_fonts', false); // we've only got one font ATM
        if(empty($disable_fonts)){
            $style_path = 'css/wpil_fonts.css';
            $f_path = WP_INTERNAL_LINKING_PLUGIN_DIR.$style_path;
            $ver = filemtime($f_path);

            wp_register_style('wpil_admin_fonts', WP_INTERNAL_LINKING_PLUGIN_URL.$style_path, $deps=[], $ver);
            wp_enqueue_style('wpil_admin_fonts');
        }

        // if we're on a post edit screen
        if (!empty($current_screen) && ('post' === $current_screen->base || 'page' === $current_screen->base)){
            wp_register_style('wpil_select2_css', WP_INTERNAL_LINKING_PLUGIN_URL . 'css/select2.min.css');
            wp_enqueue_style('wpil_select2_css');
            wp_register_script('wpil_select2', WP_INTERNAL_LINKING_PLUGIN_URL . 'js/select2.full.min.js', array('jquery'), $ver, true);
            wp_enqueue_script('wpil_select2');
        }

        $ajax_url = admin_url('admin-ajax.php');

        $script_params = array();
        $script_params['ajax_url'] = $ajax_url;
        $script_params['completed'] = __('completed', 'wpil');
        $script_params['site_linking_enabled'] = 0;

        $script_params["WPIL_OPTION_REPORT_LAST_UPDATED"] = get_option(WPIL_OPTION_REPORT_LAST_UPDATED);

        if(null !== $current_screen && 'dashboard' === $current_screen->base){
            wp_register_script('wpil_convertkit_script', 'https://f.convertkit.com/ckjs/ck.5.js', array(), false, true);
            wp_enqueue_script('wpil_convertkit_script');

            wp_register_script('wpil_email_signup_script', WP_INTERNAL_LINKING_PLUGIN_URL . 'js/email_signup.js', array('jquery'), false, true);
            wp_enqueue_script('wpil_email_signup_script');

            $user = wp_get_current_user();
            $script_params['wpil_email_dismiss_nonce']  = wp_create_nonce('wpil_email_dismiss_nonce' . (int)$user->ID);
            $script_params['current_user']              = (int)$user->ID;

            wp_register_style('wpil_convertkit_style', WP_INTERNAL_LINKING_PLUGIN_URL . 'css/email_signup.css');
            wp_enqueue_style('wpil_convertkit_style');
        }

        if(self::show_review_notice()){
            wp_register_script('wpil_review_notice_script', WP_INTERNAL_LINKING_PLUGIN_URL . 'js/review_notice.js', array('jquery'), false, true);
            wp_enqueue_script('wpil_review_notice_script');

            $user = wp_get_current_user();
            $script_params['wpil_review_dismiss_nonce'] = wp_create_nonce('wpil_review_notice_nonce' . (int)$user->ID);
            $script_params['wpil_review_nonce']         = wp_create_nonce('wpil_review_nonce' . (int)$user->ID);
            $script_params['current_user']              = (int)$user->ID;

            wp_register_style('wpil_convertkit_style', WP_INTERNAL_LINKING_PLUGIN_URL . 'css/email_signup.css');
            wp_enqueue_style('wpil_convertkit_style');
        }

        wp_localize_script('wpil_admin_script', 'wpil_ajax', $script_params);
    }

    /**
     * Show settings link on the plugins page
     *
     * @param $links
     * @return array
     */
    public static function showSettingsLink($links)
    {
        $links[] = '<a href="admin.php?page=link_whisper_settings">Settings</a>';
        return $links;
    }

    /**
     * Displays the email sign up offer in the wp dashboard
     **/
    public static function addEmailSignupNotice(){
        $page = get_current_screen();
        if(empty($page) || !isset($page->base) || (false === strpos($page->base, 'link-whisper') && false === strpos($page->base, 'link_whisper'))){
//            return;
        }

        include WP_INTERNAL_LINKING_PLUGIN_DIR . '/templates/dashboard_email_signup_notice.php';
    }

    /**
     * Stores the admin's choice of dismissing our email offer if he should decide to do so
     **/
    public static function ajax_dismiss_email_offer_notice(){

        // if the current user id or nonce isn't set, or the nonce doesn't check out
        if( !isset($_POST['current_user']) || 
            !isset($_POST['nonce']) || 
            !wp_verify_nonce($_POST['nonce'], 'wpil_email_dismiss_nonce' . (int)$_POST['current_user']))
        {
            // send back an error
            wp_send_json('It seems there\'s been an error, please refresh the page and try again.');
        }

        // get the user id
        $user_id = (int)$_POST['current_user'];

        // update the dismissed notice status with the admin's id
        update_option(WPIL_EMAIL_OFFER_DISMISSED, $user_id);

        wp_send_json('Notice dismissed!');

    }

    /**
     * Stores the admin's choice of dismissing the premium upgrade notice in the report screen
     **/
    public static function ajax_dismiss_premium_notice(){
        // hide the premium notice
        update_option(WPIL_PREMIUM_NOTICE_DISMISSED, true);
        wp_send_json('Notice dismissed!');
    }

    /**
     * Stores the admin's choice of signing up for the email offer
     **/
    public static function ajax_signed_up_email_offer_notice(){

        // if the current user id or nonce isn't set, or the nonce doesn't check out
        if( !isset($_POST['current_user']) || 
            !isset($_POST['nonce']) || 
            !wp_verify_nonce($_POST['nonce'], 'wpil_email_dismiss_nonce' . (int)$_POST['current_user']))
        {
            // send back an error
            wp_send_json('It seems there\'s been an error, please refresh the page and try again.');
        }

        // get the user id
        $user_id = (int)$_POST['current_user'];

        // get the current list of sign ups
        $signups = get_option(WPIL_SIGNED_UP_EMAIL_OFFER, array());
        $signups = maybe_unserialize($signups);

        // if the current user isn't already on the list
        if(!isset($signups[$user_id])){
            // add the user's id to the signup list
            $signups[$user_id] = $user_id;

            // update the dismissed notice status with the admin's id
            update_option(WPIL_SIGNED_UP_EMAIL_OFFER, $signups);
        }

        wp_send_json('Subscribed for Emails!');
    }

    /**
     * Checks to see if the time is right to ask for a review!
     **/
    public static function show_review_notice(){

        // exit if the user can't use LW
        if(!current_user_can('edit_posts')){
            return false;
        }

        $install_time = get_option('wpil_free_install_date', current_time('mysql', true));
        $current_time = current_time('timestamp', true);
        $update_count = get_option('wpil_free_update_count', 0);

        // if the activation count or time limit hasn't been reached yet, exit
        if($update_count < 2 || (strtotime($install_time) + WEEK_IN_SECONDS * 3) > $current_time){
            return false;
        }

        // check if the user has already given a review or has dismissed the notice entirely
        $user = wp_get_current_user();
        $left_review = get_user_meta($user->ID, 'wpil_review_left', true);
        $perm_dismissed = get_user_meta($user->ID, 'wpil_review_notice_perm_dismissed', true);

        // if he has, exit
        if(!empty($left_review) || !empty($perm_dismissed)){
            return false;
        }

        // finally check to see if the review has been temp disabled
        $temp_disabled = get_user_meta($user->ID, 'wpil_review_notice_temp_dismissed', true);

        // if it has, exit
        if(!empty($temp_disabled) && $current_time < ($temp_disabled + WEEK_IN_SECONDS * 3)){
            return false;
        }

        // if we've made it past all the checks, it's time to show the notice!
        return true;
    }

    /**
     * Displays the notice asking the user for a review
     **/
    public static function add_notice_for_review(){
        $page = get_current_screen();
        if(empty($page) || !isset($page->base) || (false === strpos($page->base, 'link-whisper') && false === strpos($page->base, 'link_whisper'))){
//            return;
        }

        include WP_INTERNAL_LINKING_PLUGIN_DIR . '/templates/dashboard_review_request_notice.php';
    }

    /**
     * Stores the admin's choice of dismissing the request for review temporarily
     **/
    public static function ajax_dismiss_review_notice(){

        // if the current user id or nonce isn't set, or the nonce doesn't check out
        if( !isset($_POST['current_user']) || 
            !isset($_POST['nonce']) || 
            !wp_verify_nonce($_POST['nonce'], 'wpil_review_nonce' . (int)$_POST['current_user']))
        {
            // send back an error
            wp_send_json('It seems there\'s been an error, please refresh the page and try again.');
        }

        // get the user id
        $user_id = (int)$_POST['current_user'];

        // update the notice for the user with the current timestamp
        update_user_meta($user_id, 'wpil_review_notice_temp_dismissed', current_time('timestamp', true));

        wp_send_json('Notice dismissed!');

    }

    /**
     * Permanently hides the review notice from the user.
     **/
    public static function ajax_perm_dismiss_review_notice(){
        // if the current user id or nonce isn't set, or the nonce doesn't check out
        if( !isset($_POST['current_user']) || 
            !isset($_POST['nonce']) || 
            !wp_verify_nonce($_POST['nonce'], 'wpil_review_notice_nonce' . (int)$_POST['current_user']))
        {
            // send back an error
            wp_send_json('It seems there\'s been an error, please refresh the page and try again.');
        }

        // get the user id
        $user_id = (int)$_POST['current_user'];

        if(isset($_POST['leaving_review']) && !empty($_POST['leaving_review'])){
            update_user_meta($user_id, 'wpil_review_left', true);
        }

        update_user_meta($user_id, 'wpil_review_notice_perm_dismissed', true);

        wp_send_json('Notice dismissed!');
    }

    /**
     * Fill data to DB on plugin activate
     */
    public static function activate()
    {
        update_option(WPIL_EMAIL_OFFER_DISMISSED, '');
        update_option(WPIL_PREMIUM_NOTICE_DISMISSED, '');

        if('' === get_option(WPIL_OPTION_IGNORE_NUMBERS, '')){
            update_option(WPIL_OPTION_IGNORE_NUMBERS, '1');
        }
        if('' === get_option(WPIL_OPTION_POST_TYPES, '')){
            update_option(WPIL_OPTION_POST_TYPES, ['post', 'page']);
        }
        if('' === get_option(WPIL_OPTION_IGNORE_WORDS, '')){
            // if there's no ignore words, configure the language settings
            update_option('wpil_selected_language', Wpil_Settings::getSiteLanguage());
            $ignore = "-\r\n" . implode("\r\n", Wpil_Settings::getIgnoreWords()) . "\r\n-";
            update_option(WPIL_OPTION_IGNORE_WORDS, $ignore);
        }
        if('' === get_option(WPIL_LINK_TABLE_IS_CREATED, '')){
            Wpil_Report::setupWpilLinkTable(true);
        }
        if('' === get_option('wpil_free_install_date', '')){
            // set the install date so we can tell how long the user has been with us
            update_option('wpil_free_install_date', current_time('mysql', true));
        }
        if('' === get_option('wpil_free_update_count', '')){
            // start counting the updates
            update_option('wpil_free_update_count', 0);
        }else{
            $update_count = get_option('wpil_free_update_count', 0);
            update_option('wpil_free_update_count', $update_count += 1);
        }

        Wpil_Link::removeLinkClass();
    }

	/**
	 * Add new columns for SEO title, description and focus keywords.
	 *
	 * @param array $columns Array of column names.
	 *
	 * @return array
	 */
	public static function add_columns($columns){
		global $post_type;

        if(!in_array($post_type, Wpil_Settings::getPostTypes())){
            return $columns;
        }
        
		$columns['wpil-link-stats'] = esc_html__('Link Stats', 'wpil');

		return $columns;
	}

    /**
	 * Add content for custom column.
	 *
	 * @param string $column_name The name of the column to display.
	 * @param int    $post_id     The current post ID.
	 */
	public static function columns_contents($column_name, $post_id){
        if('wpil-link-stats' === $column_name){
            $post_status = get_post_status($post_id);
            // exit if the current post is in a status we don't process
            if(!in_array($post_status, Wpil_Settings::getPostStatuses())){
                $status_obj = get_post_status_object($post_status);
                $status = (!empty($status_obj)) ? $status_obj->label: ucfirst($post_status);
                ?>
                <span class="wpil-link-stats-column-display wpil-link-stats-content">
                    <strong><?php _e('Links: ', 'wpil'); ?></strong>
                    <span><span><?php echo sprintf(__('%s post processing %s.', 'wpil'), $status, '<a href="' . admin_url("admin.php?page=link_whisper_settings") . '">' . __('not set', 'wpil') . '</a>'); ?></span></span>
                </span>
                <?php
                return;
            }

            $post = new Wpil_Model_Post($post_id);
            $post_scanned = !empty(get_post_meta($post_id, 'wpil_sync_report3', true));
            $inbound_internal = (int)get_post_meta($post_id, 'wpil_links_inbound_internal_count', true);
            $outbound_internal = (int)get_post_meta($post_id, 'wpil_links_outbound_internal_count', true);
            $outbound_external = (int)get_post_meta($post_id, 'wpil_links_outbound_external_count', true);

            ?>
            <span class="wpil-link-stats-column-display wpil-link-stats-content">
                <?php if($post_scanned){ ?>
                <strong><?php _e('Links: ', 'wpil'); ?></strong>
                <span title="<?php _e('Inbound Internal Links', 'wpil'); ?>"><span class="dashicons dashicons-arrow-down-alt <?php echo (!empty($inbound_internal)) ? 'wpil-has-inbound': ''; ?>"></span><span><?php echo $inbound_internal; ?></span></span>
                <span class="divider"></span>
                <span title="<?php _e('Outbound Internal Links', 'wpil'); ?>"><a href="<?php echo esc_url(get_edit_post_link($post_id)); ?>"><span class="dashicons dashicons-external  <?php echo (!empty($outbound_internal)) ? 'wpil-has-outbound': ''; ?>"></span> <span><?php echo $outbound_internal; ?></span></a></span>
                <span class="divider"></span>
                <span title="<?php _e('Outbound External Links', 'wpil'); ?>"><span class="dashicons dashicons-admin-site-alt3 <?php echo (!empty($outbound_external)) ? 'wpil-has-outbound': ''; ?>"></span> <span><?php echo $outbound_external; ?></span></span>
                <?php }else{ ?>
                    <?php $scan_link = $post->getLinks()->refresh; ?>
                    <strong><?php _e('Links: Not Scanned', 'wpil'); ?></strong>
                    <span title="<?php _e('Scan Links', 'wpil'); ?>"><a target=_blank href="<?php echo esc_url($scan_link); ?>"><span><?php _e('Scan Links', 'wpil'); ?></span> <span class="dashicons dashicons-update-alt wpil-refresh-links"></span></a></span>
                <?php } ?>
            </span>
        <?php
        }
	}

    public static function fixCollation($table)
    {
        global $wpdb;
        $table_status = $wpdb->get_results("SHOW TABLE STATUS where name like '$table'");
        if (!empty($table_status) && (empty($table_status[0]->Collation) || $table_status[0]->Collation != 'utf8mb4_unicode_ci')) {
            $wpdb->query("alter table $table convert to character set utf8mb4 collate utf8mb4_unicode_ci");
        }
    }

    public static function verify_nonce($key)
    {
        $user = wp_get_current_user();
        if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], $user->ID . $key)){
            wp_send_json(array(
                'error' => array(
                    'title' => __('Data Error', 'wpil'),
                    'text'  => __('There was an error in processing the data, please reload the page and try again.', 'wpil'),
                )
            ));
        }
    }

    /**
     * Runs the update rountines when the plugin is updated.
     */
    function upgrade_complete($upgrader_object, $options){
        // If an update has taken place and the updated type is plugins and the plugins element exists
        if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
            // Go through each plugin to see if Link Whisper was updated
            foreach( $options['plugins'] as $plugin ) {
                if( $plugin == WPIL_PLUGIN_NAME ) {
                    // refire the activate routine if it was
                    $this::activate();
                    // 
                }
            }
        }
    }

    /**
     * Removes a hooked function from the wp hook or filter.
     * We have to flip through the hooked functions because a lot of the methods use instantiated objects
     *
     * @param string $tag The hook/filter name that the function is hooked to
     * @param string $object The object who's method we're removing from the hook/filter
     * @param string $function The object method that we're removing from the hook/filter
     * @param int $priority The priority of the function that we're removing
     **/
    public static function remove_hooked_function($tag, $object, $function, $priority){
        global $wp_filter;
        $priority = intval($priority);

        // if the hook that we're looking for does exist and at the priority we're looking for
        if( isset($wp_filter[$tag]) &&
            isset($wp_filter[$tag]->callbacks) &&
            !empty($wp_filter[$tag]->callbacks) &&
            isset($wp_filter[$tag]->callbacks[$priority]) &&
            !empty($wp_filter[$tag]->callbacks[$priority]))
        {
            // look over all the callbacks in the priority we're looking in
            foreach($wp_filter[$tag]->callbacks[$priority] as $key => $data)
            {
                // if the current item is the callback we're looking for
                if(isset($data['function']) && (is_a($data['function'][0], $object) || $data['function'][0] === $object) && $data['function'][1] === $function){
                    // remove the callback
                    unset($wp_filter[$tag]->callbacks[$priority][$key]);
                }
            }
        }
    }

    /**
     * Removes all functions that are of a lower priority than the one we supply.
     * If we're working on a main stack process, and we need to loop back for something,
     * there shouldn't be a need to re-call all the other functions that have gone before
     *
     * @param string $tag The hook/filter name that the functions are hooked to
     * @param int $priority_limit The limit on how high of priority hooks we should remove
     **/
    public static function remove_lower_priority_hooked_functions($tag, $priority_limit = 0){
        global $wp_filter;
        $priority_limit = intval($priority_limit);

        // if the hook that we're looking for does exist and at the priority we're looking for
        if( isset($wp_filter[$tag]) &&
            isset($wp_filter[$tag]->callbacks) &&
            !empty($wp_filter[$tag]->callbacks))
        {
            foreach($wp_filter[$tag]->callbacks as $priority => $callbacks){
                if($priority < $priority_limit){
                    unset($wp_filter[$tag]->callbacks[$priority]);
                }
            }
        }
    }

    /**
     * Checks to see if one of the calling ancestors of the current function is what we're looking for
     **/
    public static function has_ancestor_function($function_name = '', $class_name = ''){
        if(empty($function_name)){
            return false;
        }

        $call_stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        if(!empty($call_stack)){
            foreach($call_stack as $call){
                if( isset($call['function']) && $call['function'] === $function_name &&
                    (empty($class_name) || isset($call['class']) && $call['class'] === $class_name)
                ){
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Updates the WP option cache independently of the update_options functionality.
     * I've found that for some users the cache won't update and that keeps some option based processing from working.
     * The code is mostly pulled from the update_option function
     *
     * @param string $option The name of the option that we're saving.
     * @param mixed $value The option value that we're saving.
     **/
    public static function update_option_cache($option = '', $value = ''){
        $option = trim( $option );
        if ( empty( $option ) ) {
            return false;
        }

        $serialized_value = maybe_serialize( $value );
        $alloptions = wp_load_alloptions( true );
        if ( isset( $alloptions[ $option ] ) ) {
            $alloptions[ $option ] = $serialized_value;
            wp_cache_set( 'alloptions', $alloptions, 'options' );
        } else {
            wp_cache_set( $option, $serialized_value, 'options' );
        }
    }

    /**
     * Makes sure that the transients are set and that the option cache is updated when data is saved.
     * There are some cases of the transients not sticking, even though they are supposed to be active.
     * I believe the issue is object caching catching the update information, and then not passing it back when we ask for it.
     * 
     * Uses the same arguments as the WP transient function
     **/
    public static function set_transient($transient, $value, $expiration = 0) {

        $expiration         = (int) $expiration;
        $transient_timeout  = '_transient_timeout_' . $transient;
        $transient_option   = '_transient_' . $transient;

        if(false === get_option($transient_option)){
            $autoload = 'yes';
            if($expiration){
                $autoload = 'no';
                add_option($transient_timeout, time() + $expiration, '', 'no');
            }
            $result = add_option($transient_option, $value, '', $autoload);
        }else{
            /*
            * If expiration is requested, but the transient has no timeout option,
            * delete, then re-create transient rather than update.
            */
            $update = true;

            if($expiration){
                if(false === get_option($transient_timeout)){
                    delete_option($transient_option);
                    add_option($transient_timeout, time() + $expiration, '', 'no');
                    $result = add_option($transient_option, $value, '', 'no');
                    $update = false;
                }else{
                    update_option($transient_timeout, time() + $expiration);
                    self::update_option_cache($transient_timeout, time() + $expiration);
                }
            }

            if($update){
                $result = update_option($transient_option, $value);
                self::update_option_cache($transient_option, $value);
            }
        }

        return $result;
    }

    /**
     * Deletes all Link Whisper related data on plugin deletion
     **/
    public static function delete_link_whisper_data(){
        global $wpdb;

        // if we're not really sure that the user wants to delete all data, exit
        if('1' !== get_option('wpil_delete_all_data', false)){
            return;
        }

        // create a list of all possible tables
        $tables = self::getDatabaseTableList();

        // go over the list of tables and delete all tables that exist
        foreach($tables as $table){
            $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$table}'");
            if($table_exists === $table){
                $wpdb->query("DROP TABLE {$table}");
            }
        }

        // delete all of the settings from the options table
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE `option_name` LIKE 'wpil_%' OR `option_name` LIKE 'wpil_2_%'");

        // clear all of the transients
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE `option_name` LIKE '_transient_wpil_%' OR `option_name` LIKE '_transient_timeout_wpil_%'");

        // delete all of the link metafields
        Wpil_Report::clearMeta();
    }

    /**
     * Checks to see if we're over the time limit.
     * 
     * @param int $time_pad The amount of time in advance of the PHP time limit that is considered over the time limit
     * @param int $max_time The absolute time limit that we'll wait for the current process to complete
     * @return bool
     **/
    public static function overTimeLimit($time_pad = 0, $max_time = null){
        $limit = ini_get( 'max_execution_time' );

        // if there is no limit or the limit is larger than 90 seconds
        if(empty($limit) || $limit === '-1' || $limit > 90){
            // create a self imposed limit so the user know LW is still working on looped actions
            $limit = 90;
        }

        // filter the limit so users with special constraints can make adjustments
        $limit = apply_filters('wpil_filter_processing_time_limit', $limit);

        // if the exit time pad is less than the limit
        if($limit < $time_pad){
            // default to a 5 second pad
            $time_pad = 5;
        }

        // get the current time
        $current_time = microtime(true);

        // if we've been running for longer than the PHP time limit minus the time pad, OR
        // a max time has been set and we've passed it
        if( ($current_time - WPIL_STATUS_PROCESSING_START) > ($limit - $time_pad) || 
            $max_time !== null && ($current_time - WPIL_STATUS_PROCESSING_START) > $max_time)
        {
            // signal that we're over the time limit
            return true;
        }else{
            return false;
        }
    }

    /**
     * Returns an array of all the tables created by Link Whisper.
     * @param bool $should_prefix Should the returned tables have the site's database prefix attached?
     * @return array
     **/
    public static function getDatabaseTableList($should_prefix = true){
        global $wpdb;

        if($should_prefix){
            $prefix = $wpdb->prefix;
        }else{
            $prefix = '';
        }

        return array(
            "{$prefix}wpil_report_links",
        );
    }

    /**
     * Helper function to set WP to not use external object caches when doing AJAX
     **/
    public static function ignore_external_object_cache($ignore_ajax = false){
        if( (defined('DOING_AJAX') && DOING_AJAX || $ignore_ajax) &&
            function_exists('wp_using_ext_object_cache') &&
            file_exists( WP_CONTENT_DIR . '/object-cache.php') &&
            wp_using_ext_object_cache())
        {
            if(!defined('WP_REDIS_DISABLED') && defined('WP_REDIS_FILE')){
                define('WP_REDIS_DISABLED', true);
            }
            wp_using_ext_object_cache(false);
        }
    }

    /**
     *  Helper function to remove any problem hooks interfering with our AJAX requests
     * 
     * @param bool $ignore_ajax True allows the removing of hooks when ajax is not running
     **/
    public static function remove_problem_hooks($ignore_ajax = false){
        $admin_ajax = is_admin() && defined('DOING_AJAX') && DOING_AJAX;

        if( ($admin_ajax || $ignore_ajax) && defined('TOC_VERSION')){
            remove_all_actions('wp_enqueue_scripts');
        }
    }

    /**
     * Tracks actions that have taken place so we can tell if something in a distantly connected part of Link Whisper happened
     * 
     * @param string $action The name we've given to the action that's happened
     * @param mixed $value The value of the action that we're watching
     * @param bool $overwrite_true Should we overwrite TRUE results with whatever we currently have? By default, we don't so we can track if a result happened somewhere
     **/
    public static function track_action($action = '', $value = null, $overwrite_true = false){
        if(empty($action) || !is_string($action)){
            return;
        }

        // if the action has happened AND we should overwrite that status with the most recent one
        if(isset(self::$action_tracker[$action]) && !empty(self::$action_tracker[$action]) && $overwrite_true){
            self::$action_tracker[$action] = $value;
        }elseif(!array_key_exists($action, self::$action_tracker)){ // if the event has not happened yet
            self::$action_tracker[$action] = $value;
        }elseif(array_key_exists($action, self::$action_tracker) && empty(self::$action_tracker[$action]) && !empty($value)){ // if the event has been attempted and hasn't succeeded yet, but we now have a record of it happening!
            self::$action_tracker[$action] = $value;
        }
    }

    public static function action_happened($action = '', $return_result = true){
        if(empty($action) || !is_string($action)){
            return false;
        }

        $logged = array_key_exists($action, self::$action_tracker);

        if(!$logged){
            return false;
        }

        return ($return_result) ? self::$action_tracker[$action]: $logged;
    }

    public static function clear_tracked_action($action = ''){
        if(empty($action) || !is_string($action)){
            return;
        }

        if(array_key_exists($action, self::$action_tracker)){
            unset(self::$action_tracker[$action]);
        }
    }
}
