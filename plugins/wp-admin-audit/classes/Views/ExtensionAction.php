<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_ExtensionAction extends WADA_View_BaseForm
{
    const VIEW_IDENTIFIER = 'wada-extension-install';
    public $pluginPseudoId;
    public $pluginObj;
    public $extensionAction;

    public function __construct() {
        add_action('admin_footer', array($this, 'loadJavascriptActions'));
        $this->pluginPseudoId = sanitize_text_field(array_key_exists('psid', $_REQUEST) ? $_REQUEST['psid'] : null);
        $this->extensionAction = sanitize_text_field(array_key_exists('action', $_REQUEST) ? $_REQUEST['action'] : null);

        if($this->pluginPseudoId){
            $sumBackend = new WADA_BackendSum();
            $extensionRepoUnGrouped = $sumBackend->getExtensionRepository(false, true);
            foreach($extensionRepoUnGrouped  AS $plugin){
                if($plugin->psid === $this->pluginPseudoId){
                    $this->pluginObj = $plugin;
                    break;
                }
            }
        }
    }

    protected function handleFormSubmissions(){
        if(isset($this->extensionAction)){
            check_admin_referer('wada_extension_action');
        }
    }

    protected function displayForm(){
        global $wpdb;

    ?>
        <div class="wrap">
            <h1><?php _e('Extension', 'wp-admin-audit'); ?> &mdash; <?php _e('Installation', 'wp-admin-audit'); ?></h1>
            <form id="<?php echo self::VIEW_IDENTIFIER; ?>" method="post">
                <?php wp_nonce_field(self::VIEW_IDENTIFIER); ?>
                <input type="hidden" name="page" value="<?php echo esc_attr($this->getCurrentPage()); ?>" />
                <?php
                $showReturnToExtensionsLink = true;
                if($this->pluginObj && property_exists($this->pluginObj, 'wada_plugin_path') && $this->pluginObj->wada_plugin_path){
                    $wadaPluginFolder = $this->pluginObj->wada_plugin_path;

                    if(!property_exists($this->pluginObj, 'plugin')) {
                        $this->pluginObj->plugin = $wadaPluginFolder;
                    }
                    if(!property_exists($this->pluginObj, 'slug')) {
                        $this->pluginObj->slug = WADA_PluginUtils::getPluginDir($wadaPluginFolder);
                    }
                    if(!property_exists($this->pluginObj, 'version')
                        && property_exists($this->pluginObj, 'current_release_number')) {
                        $this->pluginObj->version = $this->pluginObj->current_release_number;
                    }
                    $res = null;
                    echo '<div style="padding:20px 0px;">';
                    switch($this->extensionAction){
                        case 'install':
                            $res = WADA_PluginUtils::installPlugin($this->pluginObj);
                            if($res === true){
                                $showReturnToExtensionsLink = false; // when we installed a plugin, the UI will render the "activate plugin" button which is more important!
                            }
                            break;
                        case 'update':
                            $res = WADA_PluginUtils::updatePlugin($this->pluginObj, $wadaPluginFolder);
                            break;
                        case 'uninstall':
                            $res = delete_plugins( array($wadaPluginFolder) );
                            $nowInstalled = WADA_PluginUtils::isPluginInstalledV2($wadaPluginFolder);
                            if($nowInstalled && $res !== true){
                                esc_html_e(sprintf(__('Plugin %s could not be uninstalled'), $this->pluginObj->name));
                            }else{
                                esc_html_e(sprintf(__('Plugin %s uninstalled'), $this->pluginObj->name));
                            }
                            break;
                        case 'activate':
                            $res = activate_plugins($wadaPluginFolder);
                            $nowActive = WADA_PluginUtils::isPluginActive($wadaPluginFolder);
                            if($nowActive){
                                esc_html_e(sprintf(__('Plugin %s activated'), $this->pluginObj->name));
                            }else{
                                esc_html_e(sprintf(__('Plugin %s could not be activated'), $this->pluginObj->name));
                            }
                            break;
                        case 'deactivate':
                            deactivate_plugins($wadaPluginFolder);
                            $nowActive = WADA_PluginUtils::isPluginActive($wadaPluginFolder);
                            if($nowActive){
                                esc_html_e(sprintf(__('Plugin %s could not be deactivated'), $this->pluginObj->name));
                            }else{
                                esc_html_e(sprintf(__('Plugin %s deactivated'), $this->pluginObj->name));
                            }
                            break;
                        default:
                            esc_html_e(sprintf(__('Unknown action: %s', 'wp-admin-audit'), $this->extensionAction));
                    }
                    echo '</div>';
                    if(is_wp_error($res)){
                        echo '<div style="padding:5px 0px;"><span class="wada-error">'.esc_html($res->get_error_message()).'</span></div>';
                    }

                    WADA_Log::debug('ExtensionAction res: '.print_r($res, true));

                    WADA_BackendSum::resetExtensionRepoCache(); // clear/purge the WADA repo cache transient
                    // the following is a little redundant, because this responsibility is with the WADA plugin
                    WADA_Extensions::updateExtensionStatus($wadaPluginFolder); // update extensions table with whatever status now

                }else{
                    _e('No plugin provided', 'wp-admin-audit');
                }

                if($showReturnToExtensionsLink){
                    $classStr = 'wada-ui-button button button-primary';
                    $link = admin_url('admin.php?page=wp-admin-audit-settings&tab=tab-extensi');
                    $label = __('Return to extensions list', 'wp-admin-audit');
                    echo '<div><a href="'.$link.'" class="'.esc_attr($classStr).'">'.esc_html($label).'</a></div>';
                }

                ?>
            </form>
        </div>
    <?php
    }

    function loadJavascriptActions(){ ?>
        <script type="text/javascript">
            (function ($) {

            })(jQuery);
        </script>
        <?php
    }


}