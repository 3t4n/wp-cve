<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_Info extends WADA_View_BaseForm
{
    const VIEW_IDENTIFIER = 'wada-info';
    public $name;
    public $email;
    public $subject;
    public $message;
    public $inclSiteUrl;
    public $inclEnvDetails;
    public $inBetaTesterMode;
    public $inAddSensorMode;
    public $inAddExtensionMode;
    public $betaTesterSource;

    public function __construct() {
        add_action('admin_footer', array($this, 'loadJavascriptActions'));
        $this->resetForm();
    }

    protected function resetForm($preFillSubjectAndBody = true){
        $this->inBetaTesterMode = isset($_GET['mode']) && (sanitize_text_field($_GET['mode']) === 'beta');
        $this->inAddSensorMode = isset($_GET['mode']) && (sanitize_text_field($_GET['mode']) === 'sensor');
        $this->inAddExtensionMode = isset($_GET['mode']) && (sanitize_text_field($_GET['mode']) === 'extension');
        $this->betaTesterSource = isset($_GET['src']) ? sanitize_text_field($_GET['src']) : null;
        $this->name = '';
        $this->email = '';
        $user = wp_get_current_user();
        if($user) { // fill in default values for form
            $this->name = $user->user_nicename;
            $this->email = $user->user_email;
        }
        $this->subject = '';
        $this->message = '';
        if($preFillSubjectAndBody && $this->inBetaTesterMode){
            $this->subject = '['.__('Join beta testers', 'wp-admin-audit').'] ';
            $this->message .= '<p>'.__('I want to join the beta testers!', 'wp-admin-audit').'</p>';
            $this->message .= '<p>&nbsp;</p>';
            $this->message .= '<p>'.__('Thanks,', 'wp-admin-audit').'<br/>'.$this->name.'</p>';
        }else if($preFillSubjectAndBody && $this->inAddSensorMode){
            $this->subject = '['.__('Sensor proposal', 'wp-admin-audit').'] ';
            $this->message .= '<p>'.__('Is the following already in the list of planned sensors?', 'wp-admin-audit').'</p>';
            $this->message .= '<ul><li><i>['.__('add your description here', 'wp-admin-audit').']</i></li></ul>';
            $this->message .= '<p>'.__('Thanks,', 'wp-admin-audit').'<br/>'.$this->name.'</p>';
        }else if($preFillSubjectAndBody && $this->inAddExtensionMode){
            $this->subject = '['.__('Support for plugin', 'wp-admin-audit').'] ';
            $this->message .= '<p>'.__('Is a WP Admin Audit extension planned for the plugin:', 'wp-admin-audit').'</p>';
            $this->message .= '<ul><li><i>['.__('add your description here', 'wp-admin-audit').']</i></li></ul>';
            $this->message .= '<p>'.__('Thanks,', 'wp-admin-audit').'<br/>'.$this->name.'</p>';
        }
        $this->inclSiteUrl = true;
        $this->inclEnvDetails = true;
    }

    protected function formReadyForSending(){
        $ready = true;
        if(!$this->name || strlen($this->name) < 1){
            $this->enqueueMessage(__('Name is missing', 'wp-admin-audit'), 'error');
            $ready = false;
        }
        if(!$this->email || strlen($this->email) < 5){
            $this->enqueueMessage(__('Email is missing', 'wp-admin-audit'), 'error');
            $ready = false;
        }
        if(!$this->subject || strlen($this->subject) < 3){
            $this->enqueueMessage(__('Subject is missing', 'wp-admin-audit'), 'error');
            $ready = false;
        }
        if(!$this->message || strlen($this->message) < 1){
            $this->enqueueMessage(__('Message is missing', 'wp-admin-audit'), 'error');
            $ready = false;
        }
        return $ready;
    }

    protected function sendMessage(){
        add_filter( 'wp_mail_content_type', function( $content_type ) {
            return 'text/html';
        });

        $to = 'info@wpadminaudit.com';

        $body = '<p><strong>From: </strong>'.$this->name.' <a href="mailto:'.$this->email.'">'.$this->email.'</a></p>';
        $body .= '<hr/>';
        if($this->inclSiteUrl){
            $url = get_site_url();
            $body .= '<p><strong>Site: </strong><a href="'.$url.'">'.get_site_url().'</a></p>';
            $body .= '<hr/>';
        }
        if($this->inclEnvDetails){
            $body .= $this->getEnvironmentInfos(true);
            $body .= '<hr/>';
        }
        $body .= $this->message;
        WADA_Log::debug('Infos->sendMessage body: '.$body);

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'Reply-To: '.$this->name.' <'.$this->email.'>'
        );

        return wp_mail($to, $this->subject, $body, $headers);
    }

    protected function handleFormSubmissions(){
        if(isset($_POST['submit'])){
            check_admin_referer(self::VIEW_IDENTIFIER);
            WADA_Log::debug('Infos->submit POST: '.print_r($_POST, true));

            $this->name = trim(sanitize_text_field($_POST['name']));
            $this->email = trim(sanitize_email($_POST['email']));
            $this->subject = trim(sanitize_text_field($_POST['subject']));
            $this->message = wp_kses_post($_POST['wada_email_message']);
            $this->inclSiteUrl = (isset($_POST['incl_site_url']));
            $this->inclEnvDetails = (isset($_POST['incl_env_details']));

            if($this->formReadyForSending()){
                if($this->sendMessage()){
                    $this->resetForm(false);
                    $this->enqueueMessage(__('Message sent successfully - thank you!', 'wp-admin-audit'), 'success');
                }else{
                    $this->enqueueMessage(__('There was a problem sending out the email', 'wp-admin-audit'), 'error');
                }
            }
        }
    }

    protected function displayForm(){
        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'tab-info';
        $envInfo = $this->getEnvironmentInfos();
        $pluginSlug = basename(realpath(__DIR__.'/../../'));
        $assetsUrl = trailingslashit( plugins_url($pluginSlug) );
    ?>
        <div class="wrap">
            <h1><?php _e('Info', 'wp-admin-audit'); ?></h1>
            <h2 class="nav-tab-wrapper">
                <a href="<?php echo admin_url('/admin.php?page=wp-admin-audit-info'); ?>&tab=tab-info" class="nav-tab<?php echo ( $tab === 'tab-info' ? ' nav-tab-active' : '' ); ?>" data-slug="tab-info"><?php esc_html_e( 'Info', 'wp-admin-audit' ); ?></a>
                <a href="<?php echo admin_url('/admin.php?page=wp-admin-audit-info'); ?>&tab=tab-docs" class="nav-tab<?php echo ( $tab === 'tab-docs' ? ' nav-tab-active' : '' ); ?>" data-slug="tab-docs"><?php esc_html_e( 'Documentation', 'wp-admin-audit' ); ?></a>
                <a href="<?php echo admin_url('/admin.php?page=wp-admin-audit-info'); ?>&tab=tab-supp" class="nav-tab<?php echo ( $tab === 'tab-supp' ? ' nav-tab-active' : '' ); ?>" data-slug="tab-supp"><?php esc_html_e( 'Support', 'wp-admin-audit' ); ?></a>
            </h2>
            <div class="nav-tab-content<?php echo ( $tab === 'tab-info' ? ' nav-tab-content-active' : '' ); ?>" id="nav-tab-content-tab-info">
                <div>
                    <div style="float:left;width:50%;margin:0;">
                        <h3><?php echo 'WP Admin Audit'; ?> <?php echo '1.2.9'; ?></h3>
                        <h4><?php _e('System diagnosis', 'wp-admim-audit'); ?></h4>
                        <div>
                            <a href="?page=wp-admin-audit-info&subpage=diagnosis"><strong><?php _e('Open the system diagnosis', 'wp-admin-audit'); ?></strong></a>
                        </div>
                        <div style="margin-top:50px;">
                            <ul>
                                <li>Copyright (C) 2021 - 2024 Holger Brandt IT Solutions</li>
                                <li>License GPL2</li>
                            </ul>
                        </div>
                    </div>
                    <div style="float:left;width:50%;margin:0;">
                        <img src="<?php echo ($assetsUrl.'assets/img/logo_small.png'); ?>" alt="<?php _e( 'Logo', 'wp-admin-audit' ); ?>" id="wada-main-logo"/>
                    </div>
                </div>
            </div>

            <div class="nav-tab-content<?php echo ( $tab === 'tab-docs' ? ' nav-tab-content-active' : '' ); ?>" id="nav-tab-content-tab-docs">
                <div>
                    <h3><?php esc_html_e( 'Documentation', 'wp-admin-audit' ); ?></h3>
                    <p><?php _e('Find the documentation at our website'); ?>:</p>
                    <p><a href="https://wpadminaudit.com/documentation?utm_source=wada-plg&utm_medium=referral&utm_campaign=info&utm_content=plugin+info+documentation" target="_blank"><strong>https://wpadminaudit.com/documentation</strong></a></p>
                </div>
            </div>

            <div class="nav-tab-content<?php echo ( $tab === 'tab-supp' ? ' nav-tab-content-active' : '' ); ?>" id="nav-tab-content-tab-supp">
                <h3><?php
                    $modeUrlParam = '';
                    $supportFormTitle = __('Get support', 'wp-admim-audit');
                    if($this->inBetaTesterMode) $supportFormTitle = __('Join our beta testers', 'wp-admin-audit');
                    if($this->inAddSensorMode) $supportFormTitle = __('Have an idea for a new sensor?', 'wp-admin-audit');
                    echo esc_html($supportFormTitle); ?></h3>
                <div class="">
                    <?php
                    $ctaText = __('Send message', 'wp-admin-audit');
                    if($this->inBetaTesterMode){
                        $modeUrlParam = '&mode=beta';
                        switch($this->betaTesterSource){
                            case 'notifications':
                                _e('Be among the first to know when our notification system is ready!', 'wp-admin-audit');
                                break;
                            default:
                                _e('Be among the first to know about our upcoming features', 'wp-admin-audit');
                        }
                        if($this->betaTesterSource){
                            $modeUrlParam .= '&src='.urlencode($this->betaTesterSource);
                        }
                    }else if($this->inAddSensorMode){
                        $modeUrlParam = '&mode=sensor';
                        _e('Please add a good description in the message so that we know what you are looking for!', 'wp-admin-audit');
                    }
                    ?>
                    <form action="<?php echo esc_url(admin_url('/admin.php?page=wp-admin-audit-info') . '&tab=tab-supp' . $modeUrlParam); ?>" id="contactForm" method="post">
                        <?php wp_nonce_field(self::VIEW_IDENTIFIER); ?>
                        <div>
                            <fieldset style="display:inline-block;vertical-align:top;">
                                <table class="form-table">
                                    <tbody>
                                    <tr>
                                        <td class="first"><label for="name"><?php _e('Name', 'wp-admin-audit'); ?></label></td>
                                        <td><input type="text" name="name" size="30" value="<?php esc_attr_e($this->name); ?>" id="name"></td>
                                    </tr>
                                    <tr>
                                        <td class="first"><label for="email"><?php _e('Email', 'wp-admin-audit'); ?></label></td>
                                        <td>
                                            <input type="text" name="email" size="30" value="<?php esc_attr_e($this->email); ?>" id="email">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="first"><label for="subject"><?php _e('Subject', 'wp-admin-audit'); ?></label></td>
                                        <td>
                                            <input class="large-text" type="text" id="subject" name="subject" size="30" value="<?php esc_attr_e($this->subject); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="first"><label for="subject"><?php _e('Message', 'wp-admin-audit'); ?></label></td>
                                        <td>
                                            <?php wp_editor($this->message, 'wada_email_message'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            &nbsp;
                                        </td>
                                        <td style="text-align: right">
                                            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_attr($ctaText); ?>" />
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                            <div style="display:inline-block;min-width:5%;max-width:50%;">&nbsp;</div>
                            <fieldset style="display:inline-block;vertical-align:top;">
                                <span><?php _e('If activated, the following information will be automatically inserted into your message to us', 'wp-admin-audit'); ?></span>
                                <table class="form-table wada-small-table">
                                    <tbody>
                                    <?php WADA_HtmlUtils::boolToggleField('incl_site_url', __('Include site URL', 'wp-admin-audit'), 1, array_merge(array('render_as_table_row' => true, 'html_suffix' => '<span id="site_url_display">'.esc_html(get_site_url()).'</span>'))); ?>
                                    <?php WADA_HtmlUtils::boolToggleField('incl_env_details', __('Include site details', 'wp-admin-audit'), 1, array_merge(array('render_as_table_row' => true, 'html_suffix' => '<div id="env_details_display">'.$envInfo.'</div>'))); ?>
                                    </tbody>
                                </table>
                            </fieldset>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
    }

    function loadJavascriptActions(){ ?>
    <script type="text/javascript">
        (function ($) {
            $('#incl_site_url').on('change', function (e) {
                $('#site_url_display').toggle();
            });
            $('#incl_env_details').on('change', function (e) {
                $('#env_details_display').toggle();
            });
        })(jQuery);
    </script>
        <?php
    }

    function getEnvironmentInfos($styleForEmail = false){
        global $wpdb, $wp_version;
        $rowHeaderStyle = $styleForEmail ? 'style="text-align:right;" ' : '';
        ob_start();
        ?>
        <table class="form-table wada-system-diagnosis wada-small-table">
            <tbody>
            <tr>
                <th><?php echo esc_html(WADA_Version::getProductName()); ?></th>
                <td><?php echo esc_html(WADA_Version::getProductVersion(true)); ?> (DB: <?php echo esc_html(WADA_Settings::getDatabaseVersion()); ?>)</td>
            </tr>
            <?php
            /*  */
            ?>
            <tr>
                <th <?php echo $rowHeaderStyle; ?>><?php _e('PHP version', 'wp-admin-audit'); ?></th>
                <td><?php echo esc_html(phpversion()); ?></td>
            </tr>
            <tr>
                <th <?php echo $rowHeaderStyle; ?>><?php _e('WordPress version', 'wp-admin-audit'); ?></th>
                <td><?php echo esc_html($wp_version); ?></td>
            </tr>
            <tr>
                <th <?php echo $rowHeaderStyle; ?>><?php _e('Database version', 'wp-admin-audit'); ?></th>
                <td><?php echo esc_html($wpdb->db_version()); ?></td>
            </tr>
            </tbody>
        </table>
    <?php
        $envInfo = ob_get_contents();
        ob_end_clean();
        return $envInfo;
    }

}