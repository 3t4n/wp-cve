<?php
/*
Plugin Name: Mad Mimi for WordPress
Plugin URI: http://www.seodenver.com/mad-mimi/
Description: Add a Mad Mimi signup form to your WordPress website.
Author: Katz Web Services, Inc.
Version: 1.5.1
Author URI: http://www.katzwebservices.com
*/

/*
Copyright 2013 Katz Web Services, Inc.  (email: info@katzwebservices.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


class KWSMadMimi {

    public static $version = '1.5';
    public static $instance;
    public $mimi = NULL;

    function __construct() {

        add_action( 'plugins_loaded', array(&$this, 'load_plugin_textdomain'));

        include_once(plugin_dir_path(__FILE__).'madmimi-widget.php'); // Updated 1.2.1

        if(is_admin()) {
            add_action('admin_menu', array(&$this, 'admin'));
            add_filter( 'plugin_action_links', array(&$this, 'settings_link'), 10, 2 );
            add_action('admin_init', array(&$this, 'settings_init') );
        } else {
            add_action('init', array(&$this, 'process_submissions'),1);
            add_filter('madmimi_form_description','wpautop');
            add_filter('mad_mimi_signup_form_success', 'wpautop');

            // Set up the comment subscription checkboxes
            add_action( 'comment_form', array( $this, 'comment_subscribe_add_checkbox' ) );

            // Catch comment posts and check for subscriptions.
            add_action( 'comment_post', array( $this, 'comment_subscribe_submit' ), 51, 2 );
        }

        $options = get_option('madmimi');
        $this->api = isset($options['api']) ? $options['api'] : '';
        $this->username = isset($options['username']) ? $options['username'] : '';
        $this->debug = isset($options['debug']) ? $options['debug'] : false;

        $this->new_users_list = isset($options['new_users_list']) ? $options['new_users_list'] : 0;
        $this->settings_checked = isset($options['settings_checked']) ? $options['settings_checked'] : 0;
        $this->subscribe_comments = isset($options['settings_checked']) ? $options['settings_checked'] : 0;

        // If the settings have been updated in the admin
        // or in the admin, on the mad mimi settings page, the settings still aren't right
        if(is_admin() &&
            (isset($_REQUEST['page']) && $_REQUEST['page'] == 'mad-mimi' && (empty($this->settings_checked) || empty($options['settings_checked'])) ||
            (isset($_POST['page_options']) && strpos($_POST['page_options'], 'mad_mimi_api'))))
        {
            $this->settings_checked = $options['settings_checked'] = $this->check_settings();
            update_option('madmimi', $options);
        }
        // Upgrade options from previous versions of this plugin:
        if ( (!isset($options['version']) || $options['version'] < 1) ) {
            $options['version'] = 1;
            $oldUser = get_option('mad_mimi_username', false);

            if ($oldUser !== false) {
                $options['username'] = $oldUser;
                $options['api'] = get_option('mad_mimi_api');
                $options['debug'] = false;
            }
            delete_option('mad_mimi_username');
            delete_option('mad_mimi_api');
            delete_option('mad_mimi_ty_page');
            update_option('madmimi', $options);
        }

        // and put this in a global too, so widgets can check it
        global $madmimi_settings_checked;
        $madmimi_settings_checked = isset($options['settings_checked']);

        if (!empty($this->new_users_list)) {
            add_action('user_register', array(&$this, 'user_register') );
            add_action('user_register', array(&$this, 'user_register') );
        }


        // First thing at init
        $this->process_submissions();

    }

    function load_plugin_textdomain() {
        $plugin_dir = dirname( plugin_basename( __FILE__ ) ).'/languages/';
        load_plugin_textdomain( 'mad-mimi', false, $plugin_dir );
    }

    static function getInstance() {

        if(!empty(self::$instance)) {
            return self::$instance;
        } else {
            self::$instance = new KWSMadMimi;
        }

        return self::$instance;
    }

    function settings_init() {
        register_setting( 'madmimi_options', 'madmimi', array(&$this, 'sanitize_settings') );
    }

    function admin() {
        add_options_page('Mad Mimi', 'Mad Mimi', 'administrator', 'mad-mimi', array(&$this, 'admin_page'));
    }

    function settings_link( $links, $file ) {
        static $this_plugin;
        if( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
        if ( $file == $this_plugin ) {
            $settings_link = '<a href="' . admin_url( 'options-general.php?page=mad-mimi' ) . '">' . __('Settings', 'mad-mimi') . '</a>';
            array_unshift( $links, $settings_link ); // before other links
        }
        return $links;
    }

    function admin_page() {
        ?>
        <div class="wrap">
        <h2><?php _e('Mad Mimi for WordPress Settings', 'mad-mimi'); ?></h2>

        <?php if(!get_option('hide_madmimi_message') && !isset($_REQUEST['hidemessage'])) {
            flush();
            $message = wp_remote_get('http://www.katzwebservices.com/development/mad-mimi-info.php');
            if(!is_wp_error($message) && $message) { ?>
            <div class="wrap">
                <div id="message" class="updated" style="background-color: #F7FCFE; border-color: #D1E5EE; padding-bottom:10px;">
                    <?php echo $message['body']; ?>
                    <div class="clear"></div>
                </div>
            </div>
            <?php } else { ?>
            <div class="wrap">
                <div id="message" class="updated" style="background-color: #F7FCFE; border-color: #D1E5EE; padding-bottom:10px;">
                    <h3 style="font-size:140%">Have you tried <a href="http://wordpressformplugin.com?utm_source=mmapi">Gravity Forms</a>? We recommend it.</h3>
                    <p style="font-size:110%"><a href="http://wordpressformplugin.com?utm_source=mmapi" title="Gravity Forms Contact Form Plugin for WordPress"  class="alignright" style="margin-left:1em;"><img src="http://gravityforms.s3.amazonaws.com/banners/250x250.gif" alt="Gravity Forms Contact Form Plugin for WordPress" width="250" height="250" style="border:none;" /></a>Gravity Forms is a form creation tool that does amazing things. It's not just a contact form; it's a lead-tracking tool, a CRM, and more.</p>
                    <p style="font-size:110%">If you're interested in using an amazing contact form that integrates with Mad Mimi, try the new Mad Mimi Gravity Forms Add-on!</p>

                    <ul class="ul-disc" style="font-size:110%">
                        <li><strong style="display:block;">Mad Mimi integration</strong> Using the <a href="http://wordpress.org/extend/plugins/gravity-forms-mad-mimi/">Gravity Forms Mad Mimi Add-on</a>, any of your Gravity Forms forms can be easily linked to your Mad Mimi account.</li>
                        <li><strong style="display:block;">Visual Form Editor</strong> Building simple and complex forms alike is a piece of cake with the easy to use form editor.</li>
                        <li><strong style="display:block;">Multi-Page Forms</strong> Make long forms easier to use by breaking them up into multiple pages, complete with progress bar.</li>
                        <li><strong style="display:block;">Order Forms</strong> Gravity Forms makes it easy to create order forms with product, option, shipping and total calculations.</li>
                        <li><strong style="display:block;">Conditional Fields</strong> Configure your form to show or hide fields, sections, pages or even the submit button based on user selections.</li>
                    </ul>
                    <h3><a href="http://wordpressformplugin.com?utm_source=mmapi">Learn more about Gravity Forms</a> - Starting at just $39</h3>
                <p> <a href="http://wordpressformplugin.com?utm_source=mmapi" title="Gravity Forms Contact Form Plugin for WordPress" class="button-primary button alignleft" style="font-size:120%!important;">Get Gravity Forms Today</a>
                    <a href="options-general.php?page=mad-mimi&hidemessage=true" class="button alignright">Hide this message</a>
                </p>
                <div style="clear:both;"></div>
            </div>
        </div>
            <? } // End if error
            } elseif(isset($_REQUEST['hidemessage'])) {
                update_option('hide_madmimi_message', true);
            }
        ?>

        <div class="postbox-container" style="width:65%;">
            <div class="metabox-holder">
                <div class="meta-box-sortables">
                    <form action="options.php" method="post">
                        <?php settings_fields('madmimi_options'); ?>
                    <?php
                        $this->show_configuration_check(false);

                        if(function_exists('wp_remote_get')) { // Added 1.2.2
                        $rows[] = array(
                                'id' => 'mad_mimi_username',
                                'label' => __('Mad Mimi Username', 'mad-mimi'),
                                'content' => "<input type='text' name='madmimi[username]' id='mad_mimi_username' value='".esc_attr($this->username)."' size='40' />",
                                'desc' => __('Your Mad Mimi username (your account email address)', 'mad-mimi')
                            );

                        $rows[] = array(
                                'id' => 'mad_mimi_api',
                                'label' => __('Mad Mimi API Key', 'mad-mimi'),
                                'desc' => sprintf(__('Find your API Key at %s (under Settings &amp; Billing &rarr; API tab)'),'<a href="https://madmimi.com/user/edit" target="_blank">https://madmimi.com/user/edit</a>'),
                                'content' => "<input type='text' name='madmimi[api]' id='mad_mimi_api' value='".esc_attr($this->api)."' size='40' />"
                            );
                        $rows[] = array(
                                'id' => 'mad_mimi_debug',
                                'label' => __('Debug Mad Mimi', 'mad-mimi'),
                                'desc' => __('When submitting the form, administrators will see the full data sent to Mad Mimi as well as the response.', 'mad-mimi'),
                                'content' => "<input type='checkbox' name='madmimi[debug]' id='mad_mimi_debug' value='1' ".checked($this->debug, 1, false)."' />"
                            );
                        $rows[] = array(
                                'id' => 'mad_mimi_subscribe_comments',
                                'label' => __('Add "Subscribe to Newsletter" Checkbox in Comments Form', 'mad-mimi'),
                                'desc' => __('Add a checkbox below the comments form for users to subscribe to newsletter.', 'mad-mimi'),
                                'content' => "<input type='checkbox' name='madmimi[subscribe_comments]' id='mad_mimi_subscribe_comments' value='1' ".checked($this->subscribe_comments, 1, false)."' />"
                            );

                        $this->postbox('madmimisettings',__('Mad Mimi Settings', 'mad-mimi'), $this->form_table($rows), false);

                        if ($this->settings_checked) {
                            ?><div><p class="alignright"><label class="howto" for="refresh_lists"><span><?php _e('Are the lists inaccurate?', 'mad-mimi'); ?></span> <a href="<?php echo add_query_arg('mm_refresh_lists', true, remove_query_arg(array('updated','mm_refresh_lists'))); ?>" class="button-secondary action" id="refresh_lists"><?php _e('Refresh Lists', 'mad-mimi'); ?></a></label></p><div class="clear"></div></div><?php
                            $lists = madmimi_get_user_lists();

                            if(function_exists('simplexml_load_string')) {
                                $xml = simplexml_load_string($lists);
                            } else { // Since 1.2
                                echo madmimi_make_notice_box(sprintf(__('%sThis plugin requires PHP5 for user list management%s. Your web host does not support PHP5.<br /><br />Everything else should work in the plugin except for being able to define what lists a user will be added to upon signup.<br /><br /><strong>You may contact your hosting company</strong> and ask if they can upgrade your PHP version to PHP5; generally this is done at no cost.', 'mad-mimi'), '<strong>', '</strong>'));
                            }

                            $SelList = array(); $listsSelect = '';
                            if($xml && is_object($xml) && sizeof($xml->list) > 0) { // Updated 1.2
                                $listsSelect = '<select name="madmimi[new_users_list]">';
                                $listsSelect .= '<option value="0">'.__('No, do not add new users to a MadMimi list.', 'mad-mimi').'</option>';
#                                print_r($this->new_users_list);
                                foreach($xml->list as $l) {
                                    $a = $l->attributes();
                                    $selected = (strtolower(htmlentities($a['name'])) == $this->new_users_list) ? ' selected="selected"' : '';
                                    $listsSelect .= '<option value="' . strtolower(htmlentities($a['name'])). '"' . $selected . '>' . $a['name'] . '</option>';
                                }

                                $listsSelect .= '</select>';
                            }

                            $register[] = array(
                                    'id' => 'mad_mimi_autoimport',
                                    'label' => __('Sync Users', 'mad-mimi'),
                                    'content' => $listsSelect,
                                    'desc' => __('When users are added or register themselves, should they also be added to a Mad Mimi list?', 'mad-mimi')
                                );

                            $this->postbox('madmimisettings_newusers',__('New Users', 'mad-mimi'), $this->form_table($register), false);

                    }

                    ?>

                        <input type="hidden" name="page_options" value="<?php foreach($rows as $row) { $output .= $row['id'].','; } echo substr($output, 0, -1);?>" />
                        <p class="submit">
                        <input type="submit" class="button-primary" name="save" value="<?php _e('Save Changes', 'mad-mimi') ?>" />
                        </p>
                    <?php } ?>
                    </form>
                </div>
            </div>
        </div>
        <div class="postbox-container" style="width:30%; margin-left:2%">
            <div class="metabox-holder">
                <div class="meta-box-sortables">
                <?php $this->postbox('madmimihelp',__('Setting Up Your Form', 'mad-mimi'), $this->configuration(), true);  ?>
                </div>
            </div>
        </div>

    </div>
    <?php


        #create_user_lists_list();

    }

    function sanitize_settings($input) {
        return $input;
    }

    function configuration() {
        $out = sprintf('<h4>%s</h4>
        <ul>
            <li><code>id</code> %s</li>
            <li><code>title</code> %s</li>
        </ul>

        <h4>%s</h4>
        <p><code>[madmimi id=3 title=false]</code></p>
        <p>%s</p>

        <hr />

        <p><code>[madmimi id=3 description="&lt;h4&gt;%s&lt;/h4&gt;"]</code></p>

        <p>%s</p>

        <h4>%s</h4>
        <ul style="list-style:disc outside; margin-left:2em;">
            <li>%s</li>
            <li>%s</li>
            <li>%s</li>
        </ul>', __('Shortcode Use', 'mad-mimi'), sprintf(__(': The ID of the %sMad Mimi widget%s. Each Mad Mimi widget will show you the %sMad Mimi Widget ID%s at the top of the form.', 'mad-mimi'), '<a href="widgets.php">', '</a>', '<strong>', '</strong>'), sprintf(__(': Whether to show the widget title; true or false. Default: false. use %stitle=true%s to show.', 'mad-mimi'), '<code>', '</code>'), __('Sample code:', 'mad-mimi'), __('The form generated by Mad Mimi widget ID #3 will not show the title.', 'mad-mimi'), __('Enter your information in the form below', 'mad-mimi'), __('The form generated by Mad Mimi widget ID #3 will show the title and will show the description underneath the title and above the form.', 'mad-mimi'), __('Alternate uses', 'mad-mimi'), sprintf(__('You can use %s in your template code instead of the shortcode below.', 'mad-mimi'), '<code>&lt;?php echo madmimi_show_form(array(\'id\'=&gt;3, \'title\'=>true)); ?&gt;</code>'), sprintf(__('You can also use %s if you would like.', 'mad-mimi'), '<code>&lt;?php echo do_shortcode(\'[madmimi id=3 title=true]\'); ?&gt;</code>'), __('Shortcodes work in text widgets; you can add a form to any text widget using the shortcodes.', 'mad-mimi'));
        return $out;
    }


    function show_configuration_check($link = true) {
        $content = '';
        if(!function_exists('curl_init')) { // Added 1.2.2
            $content = sprintf(__('Your server does not support %scurl_init%s. Please call your host and ask them to enable this functionality, which is required for this awesome plugin.', 'mad-mimi'), '<code>', '</code>');
            echo $this->make_notice_box($content, 'error');
        } else {
            if($this->settings_checked && !empty($this->api)) {


                $settings_message =  sprintf(__('Your %sMad Mimi account settings%s are configured properly. You\'re ready to go.', 'mad-mimi'), '<a href="' . admin_url( 'options-general.php?page=mad-mimi' ) . '">', '</a>');

                if(!$link) {
                    $settings_message = strip_tags($settings_message);
                }

                $content .= $settings_message;

                if(!$link) {
                    $content .= ' <strong><a href="widgets.php">';
                    $content .= __('Configure your forms', 'mad-mimi');
                    $content .= '</a>.</strong>';
                }

                echo $this->make_notice_box($content, 'success');
            } elseif(!empty($this->api)) {
                $content = 'Your '; if($link) { $content .= '<a href="' . admin_url( 'options-general.php?page=mad-mimi' ) . '">'; } $content .=  __('Mad Mimi account settings', 'mad-mimi') ; if($link) { $content .= '</a>'; } $content .= '  are <strong>not configured properly</strong>.';
                echo $this->make_notice_box($content, 'error');
            };
        }
    }

    function make_notice_box($content, $type="error") {
        $output = '';
        if($type!='error') { $output .= '<div class="updated inline">';
        } else {
            $output .= '<div class="error inline">';
        }
        $output .= '<p style="line-height: 1; margin: 0.5em 0px; padding: 2px;">'.$content.'</div>';
        return($output);
    }

    function process_submission_errors($post) {
        if(!is_array($post)) { return false; }
        $errors = array();

        if(!isset($post['email']) || empty($post['email'])) {
            $errors['email'] = __('Please enter your email address.', 'mad-mimi');
        } elseif(!is_email($post['email'])) {
            $errors['email'] = __('The email you entered is not valid.', 'mad-mimi');
        }

        if(!empty($post['phone']) && !preg_match('/^([0-9\(\)\/\+ \-]*)$/', $post['phone'], $matches) ) {
            $errors['phone'] = __('The phone number you entered is invalid.', 'mad-mimi');
        }
        if(!empty($errors)) { return $errors; }
        return false;
    }

    function process_submissions() {
        global $mm_debug;
        if(!is_admin()) {
            if($mm_debug) { echo '<pre style="text-align:left;">'.print_r($_POST, true).'</pre>'; }
            if(isset($_POST['signup'])) {
                $errors = $this->process_submission_errors($_POST['signup']);
                if(!$errors) {
                    if(isset($_POST['signup']['list_name'])) { // Added 1.1
                        $lists = $_POST['signup']['list_name'];
                        $lists = explode(',',$lists);
                        foreach($lists as $list) {
                            $this->add_users_to_list(array($_POST['signup']),$list);
                        }
                    } else { // Added 1.1 - lists aren't required anyway
                        $this->add_users_to_list(array($_POST['signup']));
                    }

                    if(isset($_POST['success']) && isset($_POST['signup']['redirect'])) {
                        $url = wp_sanitize_redirect(urldecode($_POST['signup']['redirect']));
                        if(!empty($url)) {
                            wp_redirect($url);
                            exit();
                        }
                    }
                } else {
                    $_POST['signuperror'] = $errors;
                }
            }
        }
    }


    function process_emails($signup, $list = false) {
        global $mm_debug;
        $i = 0;
        if(empty($signup)||!$signup) { return false; }
        if(!is_array($signup)){ $signup = array($signup); }

        foreach($signup as $s) {
            if(is_email($s['email'])) {
                $add_list = 'add_list'; if($list) { $add_list = 'add_list'; }  // Added 1.2 // Added 1.2
                if($i == 0) { $csv_data = "name,phone,company,title,address,city,state,zip,email,$add_list\n"; }

                $csv_data .= '"';
                if(!empty($s['name']) && isset($s['name'])) {       $csv_data .= htmlentities($s['name']);  }   $csv_data .= '","';
                if(!empty($s['phone']) && isset($s['phone'])) { $csv_data .= htmlentities($s['phone']); }   $csv_data .= '","';
                if(!empty($s['company']) && isset($s['company'])) { $csv_data .= htmlentities($s['company']);}  $csv_data .= '","';
                if(!empty($s['title']) && isset($s['title'])) { $csv_data .= htmlentities($s['title']); }   $csv_data .= '","';
                if(!empty($s['address']) && isset($s['address'])) {     $csv_data .= htmlentities($s['address']); } $csv_data .= '","';
                if(!empty($s['city']) && isset($s['city'])) {       $csv_data .= htmlentities($s['city']);  }   $csv_data .= '","';
                if(!empty($s['state']) && isset($s['state'])) { $csv_data .= htmlentities($s['state']); }   $csv_data .= '","';
                if(!empty($s['zip']) && isset($s['zip'])) {     $csv_data .= htmlentities($s['zip']);   }   $csv_data .= '","';
                $csv_data .= "{$s['email']}\",";
                if($list) { $csv_data .= '"'.$list.'"'; } // Added 1.2
                $csv_data .= "\n";
                $i++;
            }
        }
        if($mm_debug) {
            echo '<pre>'.print_r($csv_data,true).'</pre>';
        }

        if($i > 0) {
            $_POST['success'] = true;
            return $csv_data;
        } else {
            $_POST['success'] = false;
            return false;
        }
    }

    function check_settings() {

        $response = madmimi_get_user_lists(true);

        if(!$response) {  // Added 1.2.2
            return false;
        }
        if(!function_exists('simplexml_load_string')) { // Added 1.2
            echo $this->make_notice_box(sprintf(__('Your web host does not support PHP5, which this plugin requires for %slist management functionality%s. Please contact your host and see if they can upgrade your PHP version; generally this is done at no cost.', 'mad-mimi'), '<strong>', '</strong>'));
            if($response) {
                return true;
            }
            return false;
        }
        return true;
    }

    function add_users_to_list($signup=false, $list=false) {
        $csv_data = $this->process_emails($signup,$list);

        $url = 'http://api.madmimi.com/audience_members';

        // Converted to wp_remote_post from curl in 1.4 for better compatibility
        $body = array('username'=>$this->username,'api_key' => $this->api, 'csv_file' => $csv_data);
        $response = wp_remote_post($url, array('body'=>$body));

        if(!empty($this->debug) && current_user_can('manage_options') && !is_admin()) {
            echo '<pre>'.print_r(array(__('Form Submission Data', 'mad-mimi') => $_POST, __('Mad Mimi URL', 'mad-mimi') => $url, __('What was sent to Mad Mimi', 'mad-mimi') => $body, __('What did Mad Mimi send back?', 'mad-mimi') => $response),true).'</pre>';
            echo '<p>'.__('(You are seeing this data because you have the `Debug Mad Mimi` setting checked and are logged in as an administrator.)', 'mad-mimi').'</p>';
        }

        if(!is_wp_error($response) && $response['response']['code'] == 200) { return true; }

        return false;
    }

    // THANKS JOOST!
    function form_table($rows) {
        $content = '<table class="form-table" width="100%">';
        foreach ($rows as $row) {
            $content .= '<tr><th valign="top" scope="row" style="width:50%">';
            if (isset($row['id']) && $row['id'] != '')
                $content .= '<label for="'.$row['id'].'" style="font-weight:bold;">'.$row['label'].':</label>';
            else
                $content .= $row['label'];
            if (isset($row['desc']) && $row['desc'] != '')
                $content .= '<br/><small>'.$row['desc'].'</small>';
            $content .= '</th><td valign="top">';
            $content .= $row['content'];
            $content .= '</td></tr>';
        }
        $content .= '</table>';
        return $content;
    }

    function postbox($id, $title, $content, $padding=false) {
        ?>
            <div id="<?php echo $id; ?>" class="postbox">
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class="hndle"><span><?php echo $title; ?></span></h3>
                <div class="inside" <?php if($padding) { echo 'style="padding:10px; padding-top:0;"'; } ?>>
                    <?php echo $content; ?>
                </div>
            </div>
        <?php
    }


    function user_register($user_id) {
        if (!$this->settings_checked)
            return false;

        if(is_admin() && isset($_POST['email'])) {
            $data = array('email' => @$_POST['email'], 'name' => trim(rtrim(@$_POST['first_name'] .' '.@$_POST['last_name'])));
        } else {
            global $wpdb;

            $email = $wpdb->get_var("SELECT user_email FROM $wpdb->users WHERE ID = $user_id");

            if (!$email) { return false; }

            $data = array('email'=>$_POST['user_email']);
        }

        $options = get_option('madmimi');
        $this->add_users_to_list(array($data),$options['new_users_list']);
    }

    /**
     * KWSMadMimi::comment_subscribe_add_checkbox()
     *
     * Set up and add the comment subscription checkbox to the comment form.
     */
     function comment_subscribe_add_checkbox() {
        global $post;

        $comments_checked = '';
        $blog_checked = '';

        // Some themes call this function, don't show the checkbox again
        remove_action( 'comment_form', 'subscription_comment_form' );

        // Check if Mark Jaquith's Subscribe to Comments plugin is active - if so, suppress Jetpack checkbox

        $str = '';

        if ( 1 == get_option( 'stb_enabled', 1 ) ) {
            // Subscribe to blog checkbox
            $str .= '<p class="comment-subscription-form"><input type="checkbox" name="subscribe_mad_mimi" id="subscribe_mad_mimi" value="subscribe" style="width: auto; -moz-appearance: checkbox; -webkit-appearance: checkbox;"' . $blog_checked . ' /> ';
            $str .= '<label class="subscribe-label" id="subscribe-blog-label" for="subscribe_mad_mimi">' . __( 'Notify me of new posts by email.', 'mad-mimi' ) . '</label>';
            $str .= '</p>';
        }

        echo apply_filters( 'jetpack_comment_subscription_form', $str );
     }

     /**
     * Jetpack_Subscriptions::comment_subscribe_submit()
     *
     * When a user checks the comment subscribe box and submits a comment, subscribe them to the comment thread.
     */
     function comment_subscribe_submit( $comment_id, $approved ) {
        if ( 'spam' === $approved ) {
            return;
        }

        if ( !isset( $_REQUEST['subscribe_mad_mimi'] ) || !class_exists('Jetpack_Subscriptions') )
            return;

        $comment = get_comment( $comment_id );
        $post_ids = array();

        if ( isset( $_REQUEST['subscribe_comments'] ) )
            $post_ids[] = $comment->comment_post_ID;

        if ( isset( $_REQUEST['subscribe_blog'] ) )
            $post_ids[] = 0;

        Jetpack_Subscriptions::subscribe( $comment->comment_author_email, $post_ids );
     }

}


add_action('plugins_loaded', 'madmimi_initialize', 1);

function madmimi_initialize() {
    new KWSMadMimi();
}

function madmimi_get_user_lists($force_reset = false) {

    if(function_exists('get_transient')) {
        if(!$force_reset && !isset($_REQUEST['mm_refresh_lists'])) {
            $lists = maybe_unserialize(get_transient('madmimi_lists'));
            if($lists) {
                return $lists;
            } else {
                delete_transient('madmimi_lists');
            }
        } elseif($force_reset || isset($_REQUEST['mm_refresh_lists'])) {
            delete_transient('madmimi_lists');
        }
    }

    $options = get_option('madmimi');
    $api = isset($options['api']) ? $options['api'] : '';
    $username = isset($options['username']) ? $options['username'] : '';
    $url = 'http://api.madmimi.com/audience_lists/lists.xml?username='.$username.'&api_key='.$api;

    // Converted to wp_remote_get from curl in 1.4 for better compatibility
    $response = wp_remote_get($url);

    if(!is_wp_error($response) && isset($response['response']['code']) && $response['response']['code'] == 200) {
        set_transient('madmimi_lists', maybe_serialize($response['body']), 60 * 60 * 24 * 7);
        return $response['body'];
    }
    return false;
}

