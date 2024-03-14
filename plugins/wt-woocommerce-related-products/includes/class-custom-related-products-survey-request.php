<?php

/**
 * Survey request
 *  
 * @since 1.5.1
 */
if (!defined('ABSPATH')) {
    exit;
}
class WT_CRP_Survey_Request
{
    /**
     * config options 
     */
    private $plugin_title               =   "Related Products for WooCommerce";
    private $survey_url                 =   "https://forms.gle/8kpCawss9fpZ1ZPu5";
    private $plugin_prefix              =   "wt_crp"; /* must be unique name */
    private $activation_hook            =   "wt_crp_survey_activate"; /* hook for activation, to store activated date */
    private $deactivation_hook          =   "wt_crp_survey_deactivate"; /* hook for deactivation, to delete activated date */
    private $webtoffee_logo_url         =   CRP_PLUGIN_URL . 'admin/img/webtoffee-logo_small.png';

    private $current_banner_state       =   1; /* 1: active, 3: closed by user/not interested to survey, 4: user done the survey */
    private $banner_state_option_name   =   ''; /* WP option name to save banner state */
    private $banner_css_class           =   ''; /* CSS class name for Banner HTML element. */
    private $banner_message             =   ''; /* Banner message. */
    private $never_btn_text             =   ''; /* Never survey button text. */
    private $survey_btn_text            =   ''; /* Survey now button text. */
    private $ajax_action_name           =   ''; /* Name of ajax action to save banner state. */
    private $allowed_action_type_arr    = array(
        'never', /* never */
        'survey', /* survey now */
        'closed', /* not interested */
    );

    public function __construct()
    {
        //Set config vars
        $this->set_vars();

        if ($this->check_condition()) /* checks the banner is active now */ {
            $this->banner_message = sprintf(__("💡Your feedback matters! Help Shape the Future of 'Related Products for WooCommerce' Plugin by taking Our quick Survey. Thank you for being an essential part of our community!", 'wt-woocommerce-related-products'), '<b>', '</b>');

            /* button texts */
            $this->never_btn_text   = __("Not interested", 'wt-woocommerce-related-products');
            $this->survey_btn_text  = __("Lets take the survey", 'wt-woocommerce-related-products');

            add_action('admin_notices', array($this, 'show_banner')); /* show banner */
            add_action('admin_print_footer_scripts', array($this, 'add_banner_scripts')); /* add banner scripts */
            add_action('wp_ajax_' . $this->ajax_action_name, array($this, 'process_user_action')); /* process banner user action */
        }
    }

    /**
     *	Set config vars
     */
    public function set_vars()
    {
        $this->ajax_action_name             =   $this->plugin_prefix . '_process_user_survey_action';
        $this->banner_state_option_name     =   $this->plugin_prefix . "_survey_request";
        $this->banner_css_class             =   $this->plugin_prefix . "_survey_request";

        $banner_state                       =   absint(get_option($this->banner_state_option_name));
        $this->current_banner_state         =   ($banner_state === 0 ? $this->current_banner_state : $banner_state);
    }

    /**
     *	Update the banner state 
     */
    private function update_banner_state($val)
    {
        update_option($this->banner_state_option_name, $val);
    }

    /**
     *	Prints the banner 
     */
    public function show_banner()
    {
        if(isset($_GET['page']) && 'wt-woocommerce-related-products' === $_GET['page'] && current_user_can('manage_options')) {
            $this->update_banner_state(1); /* update banner active state */
            ?>
                <div class="<?php echo esc_attr( $this->banner_css_class ); ?> notice-info notice is-dismissible">
                    <?php
                    if ($this->webtoffee_logo_url != "") {
                    ?>
                        <h3 style="margin: 10px 0;"><?php esc_html_e($this->plugin_title, 'wt-woocommerce-related-products'); ?></h3>
                    <?php
                    }
                    ?>
                    <p>
                        <?php echo $this->banner_message; ?>
                    </p>
                    <p>
                        <a class="button button-secondary" style="color:#333; border-color:#ccc; background:#efefef;" data-type="never"><?php echo esc_html( $this->never_btn_text ); ?></a>
                        <a class="button button-primary" data-type="survey"><?php echo esc_html( $this->survey_btn_text ); ?></a>
                    </p>
                    <div class="wt-cli-survey-footer" style="position: relative;">
                        <span class="wt-cli-footer-icon" style="position: absolute;right: 0;bottom: 10px;"><img src="<?php echo esc_url( $this->webtoffee_logo_url ); ?>" style="max-width:100px;"></span>
                    </div>
                </div>
            <?php
        }
        
    }

    /**
     *	Ajax hook to process user action on the banner
     */
    public function process_user_action()
    {
        check_ajax_referer($this->plugin_prefix);
        $nonce = (isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '');

        if("" !== $nonce && wp_verify_nonce($nonce, $this->plugin_prefix)) {
            if (isset($_POST['wt_survey_action_type'])) {
                $action_type = sanitize_text_field($_POST['wt_survey_action_type']);

                /* current action is in allowed action list */
                if (in_array($action_type, $this->allowed_action_type_arr)) {
                    if ($action_type == 'never' || $action_type == 'closed') {
                        $new_banner_state = 3;
                    } elseif ($action_type == 'survey') {
                        $new_banner_state = 4;
                    } 
                    $this->update_banner_state($new_banner_state);
                }
            }
        } 

        
        exit();
    }

    /**
     *	Add banner JS to admin footer
     */
    public function add_banner_scripts()
    {
        $ajax_url = admin_url('admin-ajax.php');
        $nonce = wp_create_nonce($this->plugin_prefix);
    ?>
        <script type="text/javascript">
            (function($) {
                "use strict";

                /* prepare data object */
                var data_obj = {
                    _wpnonce: '<?php echo $nonce; ?>',
                    action: '<?php echo $this->ajax_action_name; ?>',
                    wt_survey_action_type: ''
                };

                $(document).on('click', '.<?php echo $this->banner_css_class; ?> a.button', function(e) {
                    e.preventDefault();
                    var elm = $(this);
                    var btn_type = elm.attr('data-type');
                    
                    if (btn_type == 'survey') {
                        window.open('<?php echo $this->survey_url; ?>');
                    }
                    elm.parents('.<?php echo $this->banner_css_class; ?>').hide();

                    data_obj['wt_survey_action_type'] = btn_type;
                    $.ajax({
                        url: '<?php echo $ajax_url; ?>',
                        data: data_obj,
                        type: 'POST'
                    });

                }).on('click', '.<?php echo $this->banner_css_class; ?> .notice-dismiss', function(e) {
                    e.preventDefault();
                    data_obj['wt_survey_action_type'] = 'closed';
                    
                    $.ajax({
                        url: '<?php echo $ajax_url; ?>',
                        data: data_obj,
                        type: 'POST',
                    });

                });

            })(jQuery)
        </script>
    <?php
    }

    /**
     *	Checks the condition to show the banner
     */
    private function check_condition()
    {
        if ($this->current_banner_state === 1) /* currently showing */ {
                
            $day_to_stop = strtotime('21 November 2023'); 
            
            if( strtotime(date("Y/m/d")) >= $day_to_stop ) {  /* current day is after 21th nov ,hide banner */
                
                $this->update_banner_state(4);
                return false;
            }

            return true;
        }

        return false;
    }
}
new WT_CRP_Survey_Request();
