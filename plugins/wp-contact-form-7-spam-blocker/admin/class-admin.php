<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * 
 * @since      1.0.0
 *
 * @package    Spam_Protect_for_Contact_Form7
 * @subpackage Spam_Protect_for_Contact_Form7/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Spam_Protect_for_Contact_Form7
 * @subpackage Spam_Protect_for_Contact_Form7/admin
 * @author     New York Software Lab
 * @link       https://nysoftwarelab.com
 */
class Spam_Protect_for_Contact_Form7_Admin {

    /**
     * The unique identifier of this plugin.
     */
    private $plugin_name;

    /**
     * The current version of the plugin.
     */
    private $version;

    /**
     * Constructor of the class.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        // hook into contact form 7 form
        add_filter('wpcf7_editor_panels', array($this, 'spcf7_plugin_editor_panels'));

        // hook into contact form 7 admin form save
        add_action('wpcf7_after_save', array($this, 'spcf7_plugin_save_contact_form'));
    }

    // hook into contact form 7 form
    public function spcf7_plugin_editor_panels($panels) {

        $new_page = array(
            'Block-Email' => array(
                'title' => __("Antispam Settings", "contact-form-7-spam-blocker"),
                'callback' => array($this, 'spcf7_plugin_admin_post_settings')
            )
        );

        $panels = array_merge($panels, $new_page);
        return $panels;
    }

    public function spcf7_plugin_admin_post_settings($cf7) {
        $post_id = sanitize_text_field($_GET['post']);
        
        $wpcf7_block_email_list_val = get_post_meta($post_id, "_wpcf7_block_email_list", true);
        $wpcf7_block_email_error_msg = get_post_meta($post_id, "_wpcf7_block_email_error_msg", true);
        $wpcf7_block_email_domain = get_post_meta($post_id, "_wpcf7_block_email_domain", true);
        $wpcf7_block_words = get_post_meta($post_id, "_wpcf7_block_words", true);
        $wpcf7_block_logging = get_post_meta($post_id, "_wpcf7_block_logging", true);
        $wpcf7_block_log_filename = get_post_meta($post_id, "_wpcf7_block_log_filename", true);

        // Default error message
        if (empty($wpcf7_block_email_error_msg)) {
            $wpcf7_block_email_error_msg = 'We do not accept spam emails, ADs and other type of unwanted info. If this is a false block, please contact us with a different method.';
        }
        ?>
        <div class="main-wrap">
            <h1 class="" style="display: flex; justify-content: center;align-items: end;">Spam Protect for Contact Form 7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small>Version: <?php echo SPCF7_VERSION; ?></small></h1>
            <fieldset>
                <div class="email-block-list">
                    <h3 class="blocker-7-setting">Put here e-mails that you want to block.</h3>
                    <small class="blocker-7-setting-small">Enter addresses as comma separated values : email@domain1.com, email2@domail2.com</small>
                    <textarea name="wpcf7_block_email_list" id="wpcf7-block-email-list-id" cols="100" rows="4" 
                              class="large-text-cf7hk"  placeholder="Eg: example@spamdomain.com, example2@spamdomain2.com"><?php echo esc_html(trim($wpcf7_block_email_list_val)); ?></textarea>
                    <input type='hidden' name='cf7pp_post' value='<?php echo esc_html($post_id); ?>'>
                </div>
                <div class="email-hosting-list">
                    <h3 class="blocker-7-setting second">Put here email domains that you want to block.</h3>
                    <small class="blocker-7-setting-small">Enter domains with the "@" symbol as comma separated values : @domain1.com, @domain2.com</small>
                    <textarea name="wpcf7_block_email_domain" id="wpcf7-block-email-domain-id" cols="100" rows="4" 
                              class="large-text-cf7hk"  placeholder="Eg: @spamdomain.com, @spamdomain2.com"><?php echo esc_html(trim($wpcf7_block_email_domain)); ?></textarea>
                </div>
                <div class="blocked-words-list">
                    <h3 class="blocker-7-setting second">Put here words or phrases that you want to block.</h3>
                    <small class="blocker-7-setting-small">Enter lowercase words or phrases as comma separated values : inappropriate word, drug word, other spam identifiers, etc. </small>
                    <textarea name="wpcf7_block_words" id="wpcf7-block-words-id" cols="100" rows="4" 
                              class="large-text-cf7hk"  placeholder="Eg: sexy girls, viagra, datingsite"><?php echo esc_html(trim($wpcf7_block_words)); ?></textarea>
                </div>
                <div class="block-error-msg">
                    <h3 class="blocker-7-setting second">Set your error message.</h3>
                    <small class="blocker-7-setting-small">Enter a general error message that will be displayed if any of the above values found in the form.</small>
                    <input type="text" name="wpcf7_block_email_error_msg" id="wpcf7-block-email-error-id" 
                           class="wpcf7-block-email-error-cls" placeholder="Your error message" value="<?php echo esc_html(trim($wpcf7_block_email_error_msg)); ?>">
                </div>
                <div class="block-logging">
                    <h3 class="blocker-7-setting second">Log failed messages.</h3>
                    <small class="blocker-7-setting-small">Enable logging of failed massages, good for debugging and troubleshooting false positive spam preventions.</small>
                    <select id="wpcf7-block-logging-id" name="wpcf7_block_logging">
                        <option value="yes" <?php echo ($wpcf7_block_logging=="yes") ? "selected" : ""; ?> >Yes</option>
                        <option value="no"  <?php echo ($wpcf7_block_logging=="no")  ? "selected" : ""; ?> >No</option>
                    </select>
                </div>
                <div class="block-error-msg">
                    <h3 class="blocker-7-setting second">Set your log file filename.</h3>
                    <small class="blocker-7-setting-small">
                        Enter the filename you prefer to store the log, ex. spcf_spam_block.log (recommended) or mylog.txt or [random-secret-name].html, leave blank for default value.<br>
                        IMPORTANT : make sure your server supports MIME file extension for download or view, make sure file does not exist already or used by another plugin.
                    </small>
                    <input type="text" name="wpcf7_block_log_filename" id="wpcf7-block-log-filename-id" 
                           class="wpcf7-block-email-error-cls" placeholder="Log filename, default: spcf_spam_block.log" value="<?php echo esc_html(trim($wpcf7_block_log_filename)); ?>">
                </div>
                <div class="block-get-log">
                    <?php
                    if (trim($wpcf7_block_log_filename)==""){
                        $wpcf7_block_download = "/wp-content/spcf_spam_block.log";
                    }else{
                        $wpcf7_block_download = "/wp-content/".trim($wpcf7_block_log_filename);
                    }?>
                    <h4 class="blocker-7-setting second" >To download or open or view the log file <a href="<?php echo $wpcf7_block_download; ?>" target="_blank">click here</a></h4>
                </div>
            </fieldset>
        </div>
        <?php
    }

    // hook into contact form 7 admin form save
    public function spcf7_plugin_save_contact_form($cf7) {
        $post_id = sanitize_text_field($_POST['cf7pp_post']);

        // Manual email list
        $wpcf7_block_email_list = sanitize_text_field($_POST['wpcf7_block_email_list']);
        update_post_meta($post_id, "_wpcf7_block_email_list", trim($wpcf7_block_email_list));

        // Block Email Domain
        $wpcf7_block_email_domain = sanitize_text_field($_POST['wpcf7_block_email_domain']);
        update_post_meta($post_id, "_wpcf7_block_email_domain", trim($wpcf7_block_email_domain));
        
        // Block Words
        $wpcf7_block_words = sanitize_text_field($_POST['wpcf7_block_words']);
        update_post_meta($post_id, "_wpcf7_block_words", trim($wpcf7_block_words));

        // Custom error message
        $wpcf7_block_email_error_msg = sanitize_text_field($_POST['wpcf7_block_email_error_msg']);
        update_post_meta($post_id, "_wpcf7_block_email_error_msg", trim($wpcf7_block_email_error_msg));
        
        // Enable / Disable logging
        $wpcf7_block_logging = sanitize_text_field($_POST['wpcf7_block_logging']);
        update_post_meta($post_id, "_wpcf7_block_logging", trim($wpcf7_block_logging));

        // Log filename
        $wpcf7_block_log_filename = sanitize_text_field($_POST['wpcf7_block_log_filename']);
        update_post_meta($post_id, "_wpcf7_block_log_filename", trim($wpcf7_block_log_filename));
    }

    /**
     * Register the stylesheets for the admin area.
     */
    public function spcf7_enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/spam-protect-for-contact-form7.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     */
    public function spcf7_enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/spam-protect-for-contact-form7.js', array('jquery'), $this->version, false);
    }

}
