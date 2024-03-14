<?php
/*
  Plugin Name: PPO Call To Actions
  Plugin URI: https://wordpress.org/plugins/ppo-call-to-actions/
  Description: Tiện ích kêu gọi hành động phù hợp cho các website về giới thiệu hoặc sản phẩm, dịch vụ. Phát triển miễn phí bởi PPO Việt Nam.
  Author: PPO Việt Nam (ppo.vn)
  Version: 0.1.3
  Author URI: https://ppo.vn
 */

class PPOCallToActions {
    
    var $ppocta_meta_box = array(
            'id' => 'ppocta-meta-box',
            'title' => 'PPO Call To Actions',
            'page' => array('page', 'post'),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array()
        );

    function __construct() {
        if(is_admin()){
            $this->ppocta_meta_box['fields'] = array(
                array(
                    'name' => __('Status', 'ppo-cta'),
                    'desc' => '',
                    'id' => 'ppocta_active',
                    'type' => 'radio',
                    'std' => '',
                    'options' => array(
                        '0' => __('Inactive', 'ppo-cta'),
                        '1' => __('Active', 'ppo-cta'),
                    )
                ),
                array(
                    'name' => __('Settings', 'ppo-cta'),
                    'desc' => '',
                    'id' => 'ppocta_using_settings',
                    'type' => 'radio',
                    'std' => '',
                    'options' => array(
                        '1' => __('Global settings'),
                        '0' => __('Custom settings'),
                    )
                ),
                array(
                    'name' => __('Display screen', 'ppo-cta'),
                    'desc' => '',
                    'id' => 'ppocta_display_screen',
                    'type' => 'radio',
                    'std' => 'mobile',
                    'options' => array(
                        'mobile' => __('Mobile', 'ppo-cta'),
                        'desktop' => __('Desktop', 'ppo-cta'),
                        'mobile_desktop' => __('Mobile & Desktop', 'ppo-cta'),
                    )
                ),
                array(
                    'name' => __('Display type', 'ppo-cta'),
                    'desc' => '',
                    'id' => 'ppocta_display_type',
                    'type' => 'radio',
                    'std' => 'horizontal',
                    'options' => array(
                        'horizontal' => __('Horizontal', 'ppo-cta'),
                        'vertical' => __('Vertical', 'ppo-cta'),
                    )
                ),
                array(
                    'name' => __('Call button', 'ppo-cta'),
                    'desc' => '',
                    'id' => 'ppocta_hide_call',
                    'type' => 'radio',
                    'std' => '',
                    'options' => array(
                        '0' => __('Show', 'ppo-cta'),
                        '1' => __('Hide', 'ppo-cta'),
                    )
                ),
                array(
                    'name' => __('Zalo button', 'ppo-cta'),
                    'desc' => '',
                    'id' => 'ppocta_hide_zalo',
                    'type' => 'radio',
                    'std' => '',
                    'options' => array(
                        '0' => __('Show', 'ppo-cta'),
                        '1' => __('Hide', 'ppo-cta'),
                    )
                ),
                array(
                    'name' => __('Messenger button', 'ppo-cta'),
                    'desc' => '',
                    'id' => 'ppocta_hide_messenger',
                    'type' => 'radio',
                    'std' => '',
                    'options' => array(
                        '0' => __('Show', 'ppo-cta'),
                        '1' => __('Hide', 'ppo-cta'),
                    )
                ),
                array(
                    'name' => __('Register button', 'ppo-cta'),
                    'desc' => '',
                    'id' => 'ppocta_hide_register',
                    'type' => 'radio',
                    'std' => '',
                    'options' => array(
                        '0' => __('Show', 'ppo-cta'),
                        '1' => __('Hide', 'ppo-cta'),
                    )
                ),
                array(
                    'name' => __('Phone number', 'ppo-cta'),
                    'desc' => '',
                    'id' => 'ppocta_phone',
                    'type' => 'text',
                    'std' => '',
                ),
                array(
                    'name' => __('Call Text', 'ppo-cta'),
                    'desc' => '',
                    'id' => 'ppocta_calltext',
                    'type' => 'text',
                    'std' => '',
                ),
                array(
                    'name' => __('Zalo Phone', 'ppo-cta'),
                    'desc' => '',
                    'id' => 'ppocta_zalo',
                    'type' => 'text',
                    'std' => '',
                ),
                array(
                    'name' => __('Messenger ID', 'ppo-cta'),
                    'desc' => '',
                    'id' => 'ppocta_messenger',
                    'type' => 'text',
                    'std' => '',
                ),
                array(
                    'name' => __('Register URL', 'ppo-cta'),
                    'desc' => '',
                    'id' => 'ppocta_reg_url',
                    'type' => 'text',
                    'std' => '',
                ),
            );

            add_action('admin_menu', array(&$this, 'add_PPOCallToActions_page'));
            // show/hide meta box
            add_action('admin_menu', array(&$this, 'ppocta_add_box'));
            add_action('save_post', array(&$this, 'ppocta_add_box'));
            add_action('save_post', array(&$this, 'ppocta_save_data'));
//        } else if(wp_is_mobile()){
        } else {
            add_filter('wp_head', array(&$this, 'add_style'), 100);
            add_filter('wp_footer', array(&$this, 'add_buttons'), 100);
        }
    }
    
    public function add_PPOCallToActions_page() {
        add_submenu_page('options-general.php', //Menu ID – Defines the unique id of the menu that we want to link our submenu to. 
                                    //To link our submenu to a custom post type page we must specify - 
                                    //edit.php?post_type=my_post_type
            __('PPO Call To Actions', 'ppo-cta'), // Page title
            __('PPO Call To Actions', 'ppo-cta'), // Menu title
            'manage_options', // Capability - see: http://codex.wordpress.org/Roles_and_Capabilities#Capabilities
            'PPOCTA', // Submenu ID – Unique id of the submenu.
            array(&$this, 'output_PPOCallToActions_page') // render output function
        );
        
        if(isset($_GET['page']) and $_GET['page'] == 'PPOCTA'){
            if(isset($_POST['do_action']) and $_POST['do_action'] == 'save'){
                $ppocta_fields = array(
                    "ppocta_display_screen", "ppocta_display_type",
                    "ppocta_hide_call", "ppocta_hide_zalo", "ppocta_hide_messenger", "ppocta_hide_register", 
                    "ppocta_phone", "ppocta_zalo", "ppocta_messenger", "ppocta_reg_url", "ppocta_calltext"
                );
                foreach ($ppocta_fields as $field) {
                    if (isset($_REQUEST[$field])) {
                        $new_value = sanitize_text_field($_REQUEST[$field]);
                        update_option($field, $new_value);
                    } else {
                        delete_option($field);
                    }
                }
            }
        }
    }
    
    public function output_PPOCallToActions_page() {
        if(isset($_POST['do_action']) and $_POST['do_action'] == 'save'){
?>
        <div class="updated"><p><strong><?php _e('Settings saved.'); ?></strong></p></div>
    <?php } ?>
        <div class="wrap">
            <div id="icon-users" class="icon32"></div>
            <h2>PPO Call To Actions</h2>
            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <td>
                            <label for="ppocta_display_screen"><?php _e('Display screen', 'ppo-cta') ?></label>
                        </td>
                        <td>
                            <input type="radio" name="ppocta_display_screen" id="ppocta_display_screen_mobile" value="mobile" 
                                <?php echo (get_settings('ppocta_display_screen') != 'desktop' and get_settings('ppocta_display_screen') != 'mobile_desktop') ? "checked" : ""; ?> />
                            <label for="ppocta_display_screen_mobile"><?php _e('Mobile', 'ppo-cta') ?></label>&nbsp;
                            <input type="radio" name="ppocta_display_screen" id="ppocta_display_screen_desktop" value="desktop" 
                                <?php echo (get_settings('ppocta_display_screen') == 'desktop') ? "checked" : ""; ?> />
                            <label for="ppocta_display_screen_desktop"><?php _e('Desktop', 'ppo-cta') ?></label>
                            <input type="radio" name="ppocta_display_screen" id="ppocta_display_screen_mobile_desktop" value="mobile_desktop" 
                                <?php echo (get_settings('ppocta_display_screen') == 'mobile_desktop') ? "checked" : ""; ?> />
                            <label for="ppocta_display_screen_mobile_desktop"><?php _e('Mobile & Desktop', 'ppo-cta') ?></label>
                        <td>
                    </tr>
                    <tr>
                        <td>
                            <label for="ppocta_display_type"><?php _e('Display type', 'ppo-cta') ?></label>
                        </td>
                        <td>
                            <input type="radio" name="ppocta_display_type" id="ppocta_display_type_horizontal" value="horizontal" 
                                <?php echo (get_settings('ppocta_display_type') != 'vertical') ? "checked" : ""; ?> />
                            <label for="ppocta_display_type_horizontal"><?php _e('Horizontal', 'ppo-cta') ?></label>&nbsp;
                            <input type="radio" name="ppocta_display_type" id="ppocta_display_type_vertical" value="vertical" 
                                <?php echo (get_settings('ppocta_display_type') == 'vertical') ? "checked" : ""; ?> />
                            <label for="ppocta_display_type_vertical"><?php _e('Vertical', 'ppo-cta') ?></label>
                        <td>
                    </tr>
                    <tr>
                        <td>
                            <label for="ppocta_hide_call"><?php _e('Call button', 'ppo-cta') ?></label>
                        </td>
                        <td>
                            <input type="radio" name="ppocta_hide_call" id="ppocta_hide_call_1" value="1" 
                                <?php echo (intval(get_settings('ppocta_hide_call')) == 1) ? "checked" : ""; ?> />
                            <label for="ppocta_hide_call_1"><?php _e('Show', 'ppo-cta') ?></label>&nbsp;
                            <input type="radio" name="ppocta_hide_call" id="ppocta_hide_call_0" value="0" 
                                <?php echo (intval(get_settings('ppocta_hide_call')) != 1) ? "checked" : ""; ?> />
                            <label for="ppocta_hide_call_0"><?php _e('Hide', 'ppo-cta') ?></label>
                        <td>
                    </tr>
                    <tr>
                        <td>
                            <label for="ppocta_hide_zalo"><?php _e('Zalo button', 'ppo-cta') ?></label>
                        </td>
                        <td>
                            <input type="radio" name="ppocta_hide_zalo" id="ppocta_hide_zalo_1" value="1" 
                                <?php echo (intval(get_settings('ppocta_hide_zalo')) == 1) ? "checked" : ""; ?> />
                            <label for="ppocta_hide_zalo_1"><?php _e('Show', 'ppo-cta') ?></label>&nbsp;
                            <input type="radio" name="ppocta_hide_zalo" id="ppocta_hide_zalo_0" value="0" 
                                <?php echo (intval(get_settings('ppocta_hide_zalo')) != 1) ? "checked" : ""; ?> />
                            <label for="ppocta_hide_zalo_0"><?php _e('Hide', 'ppo-cta') ?></label>
                        <td>
                    </tr>
                    <tr>
                        <td>
                            <label for="ppocta_hide_messenger"><?php _e('Messenger button', 'ppo-cta') ?></label>
                        </td>
                        <td>
                            <input type="radio" name="ppocta_hide_messenger" id="ppocta_hide_messenger_1" value="1" 
                                <?php echo (intval(get_settings('ppocta_hide_messenger')) == 1) ? "checked" : ""; ?> />
                            <label for="ppocta_hide_messenger_1"><?php _e('Show', 'ppo-cta') ?></label>&nbsp;
                            <input type="radio" name="ppocta_hide_messenger" id="ppocta_hide_messenger_0" value="0" 
                                <?php echo (intval(get_settings('ppocta_hide_messenger')) != 1) ? "checked" : ""; ?> />
                            <label for="ppocta_hide_messenger_0"><?php _e('Hide', 'ppo-cta') ?></label>
                        <td>
                    </tr>
                    <tr>
                        <td>
                            <label for="ppocta_hide_register"><?php _e('Register button', 'ppo-cta') ?></label>
                        </td>
                        <td>
                            <input type="radio" name="ppocta_hide_register" id="ppocta_hide_register_1" value="1" 
                                <?php echo (intval(get_settings('ppocta_hide_register')) == 1) ? "checked" : ""; ?> />
                            <label for="ppocta_hide_register_1"><?php _e('Show', 'ppo-cta') ?></label>&nbsp;
                            <input type="radio" name="ppocta_hide_register" id="ppocta_hide_register_0" value="0" 
                                <?php echo (intval(get_settings('ppocta_hide_register')) != 1) ? "checked" : ""; ?> />
                            <label for="ppocta_hide_register_0"><?php _e('Hide', 'ppo-cta') ?></label>
                        <td>
                    </tr>
                    <tr>
                        <td><label><?php _e('Phone number', 'ppo-cta') ?>:</label></td>
                        <td><input type="text" name="ppocta_phone" value="<?php echo get_option('ppocta_phone'); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <td><label><?php _e('Call Text', 'ppo-cta') ?>:</label></td>
                        <td><input type="text" name="ppocta_calltext" value="<?php echo get_option('ppocta_calltext'); ?>" class="regular-text" placeholder="<?php _e('Call Now', 'ppo-cta') ?>" /></td>
                    </tr>
                    <tr>
                        <td><label><?php _e('Zalo Phone', 'ppo-cta') ?>:</label></td>
                        <td><input type="text" name="ppocta_zalo" value="<?php echo get_option('ppocta_zalo'); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <td><label><?php _e('Messenger ID', 'ppo-cta') ?>:</label></td>
                        <td><input type="text" name="ppocta_messenger" value="<?php echo get_option('ppocta_messenger'); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <td><label><?php _e('Register URL', 'ppo-cta') ?>:</label></td>
                        <td><input type="text" name="ppocta_reg_url" value="<?php echo get_option('ppocta_reg_url'); ?>" class="regular-text" /></td>
                    </tr>
                </table>
                <hr />
                <p class="submit">
                    <input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'ppo-cta') ?>" />
                    <input type="hidden" name="do_action" value="save" />
                </p>
            </form>
        </div>
<?php
    }

    ###########################################################################
    public function ppocta_add_box() {
        foreach ($this->ppocta_meta_box['page'] as $page) {
            add_meta_box($this->ppocta_meta_box['id'], $this->ppocta_meta_box['title'], array(&$this, 'ppocta_show_box'), $page, $this->ppocta_meta_box['context'], $this->ppocta_meta_box['priority']);
        }
    }

    public function ppocta_show_box() {
        global $post;
        $ppocta_active = intval(get_post_meta($post->ID, 'ppocta_active', true));
        $ppocta_using_settings = intval(get_post_meta($post->ID, 'ppocta_using_settings', true));
        $ppocta_display_screen = get_post_meta($post->ID, 'ppocta_display_screen', true);
        $ppocta_display_type = get_post_meta($post->ID, 'ppocta_display_type', true);
        $ppocta_hide_call = intval(get_post_meta($post->ID, 'ppocta_hide_call', true));
        $ppocta_hide_zalo = intval(get_post_meta($post->ID, 'ppocta_hide_zalo', true));
        $ppocta_hide_messenger = intval(get_post_meta($post->ID, 'ppocta_hide_messenger', true));
        $ppocta_hide_register = intval(get_post_meta($post->ID, 'ppocta_hide_register', true));
        echo '<input type="hidden" name="ppocta_secure_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
?>
    <table class="form-table">
        <tr>
            <td><b><?php _e('Status', 'ppo-cta') ?></b></td>
            <td>
                <input type="radio" name="ppocta_active" id="ppocta_active_0" value="0" 
                    <?php echo ($ppocta_active != 1) ? "checked" : ""; ?> />
                <label for="ppocta_active_0">Active</label>&nbsp;
                <input type="radio" name="ppocta_active" id="ppocta_active_1" value="1" 
                    <?php echo ($ppocta_active == 1) ? "checked" : ""; ?> />
                <label for="ppocta_active_1">Inactive</label>
            <td>
        </tr>
        <tr>
            <td><b><?php _e('Settings', 'ppo-cta') ?></b></td>
            <td>
                <input type="radio" name="ppocta_using_settings" id="ppocta_using_settings_0" value="0" 
                    <?php echo ($ppocta_using_settings != 1) ? "checked" : ""; ?> />
                <label for="ppocta_using_settings_0">Use general settings</label>&nbsp;
                <input type="radio" name="ppocta_using_settings" id="ppocta_using_settings_1" value="1" 
                    <?php echo ($ppocta_using_settings == 1) ? "checked" : ""; ?> />
                <label for="ppocta_using_settings_1">Custom settings</label>
            <td>
        </tr>
    </table>
    <table id="ppocta_custom_settings" class="form-table" style="width: 100%; <?php echo ($ppocta_using_settings != 1) ? "display: none;" : ""; ?>">
        <tr>
            <td>
                <label for="ppocta_display_screen"><?php _e('Display screen', 'ppo-cta') ?></label>
            </td>
            <td>
                <input type="radio" name="ppocta_display_screen" id="ppocta_display_screen_mobile" value="mobile" 
                    <?php echo ($ppocta_display_screen != 'desktop' and $ppocta_display_screen != 'mobile_desktop') ? "checked" : ""; ?> />
                <label for="ppocta_display_screen_mobile"><?php _e('Mobile', 'ppo-cta') ?></label>&nbsp;
                <input type="radio" name="ppocta_display_screen" id="ppocta_display_screen_desktop" value="desktop" 
                    <?php echo ($ppocta_display_screen == 'desktop') ? "checked" : ""; ?> />
                <label for="ppocta_display_screen_desktop"><?php _e('Desktop', 'ppo-cta') ?></label>
                <input type="radio" name="ppocta_display_screen" id="ppocta_display_screen_mobile_desktop" value="mobile_desktop" 
                    <?php echo ($ppocta_display_screen == 'mobile_desktop') ? "checked" : ""; ?> />
                <label for="ppocta_display_screen_mobile_desktop"><?php _e('Mobile & Desktop', 'ppo-cta') ?></label>
            <td>
        </tr>
        <tr>
            <td>
                <label for="ppocta_display_type"><?php _e('Display type', 'ppo-cta') ?></label>
            </td>
            <td>
                <input type="radio" name="ppocta_display_type" id="ppocta_display_type_horizontal" value="horizontal" 
                    <?php echo ($ppocta_display_type != 'vertical') ? "checked" : ""; ?> />
                <label for="ppocta_display_type_horizontal"><?php _e('Horizontal', 'ppo-cta') ?></label>&nbsp;
                <input type="radio" name="ppocta_display_type" id="ppocta_display_type_vertical" value="vertical" 
                    <?php echo ($ppocta_display_type == 'vertical') ? "checked" : ""; ?> />
                <label for="ppocta_display_type_vertical"><?php _e('Vertical', 'ppo-cta') ?></label>
            <td>
        </tr>
        <tr>
            <td>
                <label for="ppocta_hide_call"><?php _e('Call button', 'ppo-cta') ?></label>
            </td>
            <td>
                <input type="radio" name="ppocta_hide_call" id="ppocta_hide_call_1" value="1" 
                    <?php echo ($ppocta_hide_call==1) ? "checked" : ""; ?> />
                <label for="ppocta_hide_call_1"><?php _e('Show', 'ppo-cta') ?></label>&nbsp;
                <input type="radio" name="ppocta_hide_call" id="ppocta_hide_call_0" value="0" 
                    <?php echo ($ppocta_hide_call!= 1) ? "checked" : ""; ?> />
                <label for="ppocta_hide_call_0"><?php _e('Hide', 'ppo-cta') ?></label>
            <td>
        </tr>
        <tr>
            <td>
                <label for="ppocta_hide_zalo"><?php _e('Zalo button', 'ppo-cta') ?></label>
            </td>
            <td>
                <input type="radio" name="ppocta_hide_zalo" id="ppocta_hide_zalo_1" value="1" 
                    <?php echo ($ppocta_hide_zalo==1) ? "checked" : ""; ?> />
                <label for="ppocta_hide_zalo_1"><?php _e('Show', 'ppo-cta') ?></label>&nbsp;
                <input type="radio" name="ppocta_hide_zalo" id="ppocta_hide_zalo_0" value="0" 
                    <?php echo ($ppocta_hide_zalo!= 1) ? "checked" : ""; ?> />
                <label for="ppocta_hide_zalo_0"><?php _e('Hide', 'ppo-cta') ?></label>
            <td>
        </tr>
        <tr>
            <td>
                <label for="ppocta_hide_messenger"><?php _e('Messenger button', 'ppo-cta') ?></label>
            </td>
            <td>
                <input type="radio" name="ppocta_hide_messenger" id="ppocta_hide_messenger_1" value="1" 
                    <?php echo ($ppocta_hide_messenger==1) ? "checked" : ""; ?> />
                <label for="ppocta_hide_messenger_1"><?php _e('Show', 'ppo-cta') ?></label>&nbsp;
                <input type="radio" name="ppocta_hide_messenger" id="ppocta_hide_messenger_0" value="0" 
                    <?php echo ($ppocta_hide_messenger!= 1) ? "checked" : ""; ?> />
                <label for="ppocta_hide_messenger_0"><?php _e('Hide', 'ppo-cta') ?></label>
            <td>
        </tr>
        <tr>
            <td>
                <label for="ppocta_hide_register"><?php _e('Register button', 'ppo-cta') ?></label>
            </td>
            <td>
                <input type="radio" name="ppocta_hide_register" id="ppocta_hide_register_1" value="1" 
                    <?php echo ($ppocta_hide_register== 1) ? "checked" : ""; ?> />
                <label for="ppocta_hide_register_1"><?php _e('Show', 'ppo-cta') ?></label>&nbsp;
                <input type="radio" name="ppocta_hide_register" id="ppocta_hide_register_0" value="0" 
                    <?php echo ($ppocta_hide_register!= 1) ? "checked" : ""; ?> />
                <label for="ppocta_hide_register_0"><?php _e('Hide', 'ppo-cta') ?></label>
            <td>
        </tr>
        <tr>
            <td><label><?php _e('Phone number', 'ppo-cta') ?>:</label></td>
            <td><input type="text" name="ppocta_phone" value="<?php echo get_post_meta($post->ID, 'ppocta_phone', true); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <td><label><?php _e('Call Text', 'ppo-cta') ?>:</label></td>
            <td><input type="text" name="ppocta_calltext" value="<?php echo get_post_meta($post->ID, 'ppocta_calltext', true); ?>" class="regular-text" placeholder="<?php _e('Call Now', 'ppo-cta') ?>" /></td>
        </tr>
        <tr>
            <td><label><?php _e('Zalo Phone', 'ppo-cta') ?>:</label></td>
            <td><input type="text" name="ppocta_zalo" value="<?php echo get_post_meta($post->ID, 'ppocta_zalo', true); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <td><label><?php _e('Messenger ID', 'ppo-cta') ?>:</label></td>
            <td><input type="text" name="ppocta_messenger" value="<?php echo get_post_meta($post->ID, 'ppocta_messenger', true); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <td><label><?php _e('Register URL', 'ppo-cta') ?>:</label></td>
            <td><input type="text" name="ppocta_reg_url" value="<?php echo get_post_meta($post->ID, 'ppocta_reg_url', true); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("input[name='ppocta_using_settings']").change(function(){
                if($(this).val() === '1'){
                    $("#ppocta_custom_settings").show();
                }else{
                    $("#ppocta_custom_settings").hide();
                }
            });
        });
    </script>
<?php
    }
    
    function ppocta_save_data($post_id) {
        // verify nonce
        if (!wp_verify_nonce($_POST['ppocta_secure_meta_box_nonce'], basename(__FILE__))) {
            return $post_id;
        }
        // check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        // check permissions
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
        foreach ($this->ppocta_meta_box['fields'] as $field) {
            $old = get_post_meta($post_id, $field['id'], true);
            $new = sanitize_text_field($_POST[$field['id']]);
            if (isset($_POST[$field['id']]) && $new != $old) {
                update_post_meta($post_id, $field['id'], $new);
            } elseif ('' == $new && $old) {
                delete_post_meta($post_id, $field['id'], $old);
            }
        }
    }
    ###########################################################################
    
    public function add_style() {
        $call_btn = plugins_url("/images/callbutton.png", __FILE__);
        $zalo_btn = plugins_url("/images/zalo.png", __FILE__);
        $messenger_btn = plugins_url("/images/messenger.png", __FILE__);
        $reg_btn = plugins_url("/images/regbutton.png", __FILE__);
        $ppocta_display_screen = get_option('ppocta_display_screen');
        $ppocta_display_type = get_option('ppocta_display_type');
        $active = true;
        if(is_single() or is_page()){
            $post_id = get_queried_object_id();
            if(get_post_meta($post_id, 'ppocta_active', true) != 1){
                if(get_post_meta($post_id, 'ppocta_using_settings', true) == 1){
                    $ppocta_display_screen = get_post_meta($post_id, 'ppocta_display_screen', true);
                    $ppocta_display_type = get_post_meta($post_id, 'ppocta_display_type', true);
                }
            } else {
                $active = false;
            }
        }
        if($active){
            echo <<<HTML
<style type="text/css">
    .ppocta-ft-fix{
        display:none;
        position: fixed;
        bottom: 5px;
        left: 10px;
        min-width: 120px;
        text-align: center;
        z-index: 9999
    }
    #callNowButton{
        display: inline-block;
        position: relative;
        border-radius: 50%;
        color: #fff;
        width: 50px;
        height: 50px;
        line-height: 50px;
        box-shadow: 0px 0px 10px -2px rgba(0,0,0,0.7);
    }
    #callNowButton i{
        border-radius: 50%;
        display:inline-block;
        width: 50px;
        height: 50px;
        background: url("{$call_btn}") center center no-repeat #009900
    }
    #callNowButton a{
        display: block;
        text-decoration: none;
        outline: none;
        color: #fff;
        text-align: center
    }
    #callNowButton a.txt{
        position: absolute;
        top: -40px;
        left: calc(50% - 60px);
        background: #009900;
        width: 120px;
        max-width: 120px;
        line-height: 2;
        text-transform: uppercase;
        border-radius: 5px;
        font-size: 15px
    }
    #callNowButton a.txt:after{
        position: absolute;
        bottom: -8px;
        left: 50px;
        content: "";
        width: 0;
        height: 0;
        border-top: 8px solid #009900;
        border-left: 10px solid transparent;
        border-right: 10px solid transparent
    }
    #zaloButton{
        display: inline-block;
        margin-right: 10px;
        width: 50px;
        height: 50px;
        background: #5AC5EF;
        border-radius: 50%;
        box-shadow: 0px 0px 10px -2px rgba(0,0,0,0.7)
    }
    #zaloButton>a>i{
        background: url("{$zalo_btn}") center center no-repeat;
        background-size: 57%;
        width: 50px;
        height: 50px;
        display: inline-block
    }
    #messengerButton{
        display: inline-block;
        margin-right: 10px;
        width: 50px;
        height: 50px;
        background: #4267B2;
        border-radius: 50%;
        box-shadow: 0px 0px 10px -2px rgba(0,0,0,0.7)
    }
    #messengerButton>a>i{
        background: url("{$messenger_btn}") center center no-repeat;
        background-size: 57%;
        width: 50px;
        height: 50px;
        display: inline-block
    }
    #registerNowButton{
        display: inline-block;
        color: #fff;
        height: 50px;
        width: 50px;
        border-radius: 50%;
        margin-right: 10px;
        background: url("{$reg_btn}") center center no-repeat #ff0000;
        box-shadow: 0px 0px 10px -2px rgba(0,0,0,0.7);
        text-decoration: none
    }
HTML;
    if($ppocta_display_type == 'vertical'){
    echo <<<HTML
    .ppocta-ft-fix.vertical{min-width:inherit}
    .ppocta-ft-fix.vertical #messengerButton, .ppocta-ft-fix.vertical #zaloButton,
    .ppocta-ft-fix.vertical #registerNowButton, .ppocta-ft-fix.vertical #callNowButton{
        display:block;
        margin-right:0;
        margin-top:2px
    }
    .ppocta-ft-fix.vertical #callNowButton a.txt{
        top: 9px;
        left: 60px;
        width: auto;
        white-space: nowrap;
        padding-left: 8px;
        padding-right: 10px;
    }
    .ppocta-ft-fix.vertical #callNowButton a.txt:after {
        bottom: 5px;
        left: -10px;
        border-top: 10px solid transparent;
        border-bottom: 10px solid transparent;
        border-left: none;
        border-right: 10px solid #090;
    }
HTML;
        }
        if($ppocta_display_screen != 'desktop' and $ppocta_display_screen != 'mobile_desktop'){
        echo <<<HTML
    @media (max-width: 991px){
        .ppocta-ft-fix{display: block}
    }
HTML;
        } else if($ppocta_display_screen == 'desktop'){
        echo <<<HTML
    @media (min-width: 992px){
        .ppocta-ft-fix{display: block}
    }
HTML;
        } else {
            echo '.ppocta-ft-fix{display: block}';
        }
            echo '</style>';
        }
    }
    
    public function add_buttons() {
        $ppocta_display_type = get_option('ppocta_display_type');
        $show_call = get_option('ppocta_hide_call');
        $show_zalo = get_option('ppocta_hide_zalo');
        $show_messenger = get_option('ppocta_hide_messenger');
        $show_reg = get_option('ppocta_hide_register');
        $phone = get_option('ppocta_phone');
        $zalo = get_option('ppocta_zalo');
        $messenger = get_option('ppocta_messenger');
        $callText = get_option('ppocta_calltext');
        $url = get_option('ppocta_reg_url');
        $active = true;
        if(is_single() or is_page()){
            $post_id = get_queried_object_id();
            if(get_post_meta($post_id, 'ppocta_active', true) != 1){
                if(get_post_meta($post_id, 'ppocta_using_settings', true) == 1){
                    $ppocta_display_type = get_post_meta($post_id, 'ppocta_display_type', true);
                    $show_call = intval(get_post_meta($post_id, 'ppocta_hide_call', true));
                    $show_zalo = intval(get_post_meta($post_id, 'ppocta_hide_zalo', true));
                    $show_messenger = intval(get_post_meta($post_id, 'ppocta_hide_messenger', true));
                    $show_reg = intval(get_post_meta($post_id, 'ppocta_hide_register', true));
                    $phone = get_post_meta($post_id, 'ppocta_phone', true); 
                    $zalo = get_post_meta($post_id, 'ppocta_zalo', true); 
                    $messenger = get_post_meta($post_id, 'ppocta_messenger', true); 
                    $callText = get_post_meta($post_id, 'ppocta_calltext', true);
                    $url = get_post_meta($post_id, 'ppocta_reg_url', true);
                }
            } else {
                $active = false;
            }
        }
        if($active){
            if(empty($phone)) $phone = "";
            if(empty($url)) $url = "";
            echo '<div class="ppocta-ft-fix '.$ppocta_display_type.'">';
            if(intval($show_messenger) == 1){
                echo <<<HTML
            <div id="messengerButton">
                <a href="http://fb.com/msg/{$messenger}" target="_blank" class="ppocta-btn-messenger-tracking"><i></i></a>
            </div>
HTML;
            }
            if(intval($show_zalo) == 1){
                echo <<<HTML
            <div id="zaloButton">
                <a href="http://zalo.me/{$zalo}" target="_blank" class="ppocta-btn-zalo-tracking"><i></i></a>
            </div>
HTML;
            }
            if(intval($show_reg) == 1){
                echo <<<HTML
            <a id="registerNowButton" href="{$url}" class="ppocta-btn-register-tracking"><i></i></a>
HTML;
            }
            if(intval($show_call) == 1){
                if(!empty($callText)){
                    $callText = "<a href=\"tel:{$phone}\" class=\"txt\" class=\"ppocta-text-call-tracking\"><span>{$callText}</span></a>";
                }
                echo <<<HTML
            <div id="callNowButton">
                <a href="tel:{$phone}" class="ppocta-btn-call-tracking"><i></i></a>
                {$callText}
            </div>
HTML;
            }
            echo '</div>';
        }
    }

}

$ppoACM = new PPOCallToActions();