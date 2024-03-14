<?php


class InvelityPluginsAdmin
{
    private $launcher;
    private $licenseValidator;
    private $allPlugins;

    /**
     * Adds menu items and page
     * Gets options from database
     */
    public function __construct($launcher)
    {

        $this->launcher = $launcher;
        if (is_admin()) {
            add_action('admin_enqueue_scripts', array($this, 'loadMainAdminAssets'));
            add_action('admin_menu', array($this, 'add_plugin_page'));
        }
        $this->getRemotePluginsData();

    }

    private function getRemotePluginsData()
    {
        $invelityPluginsDescription = get_transient('invelity-plugins-description');
        if (!$invelityPluginsDescription) {
            $query = esc_url_raw(add_query_arg(array(), 'https://licenses.invelity.com/plugins/invelitypluginsdata.json'));
            $response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));
            $response = wp_remote_retrieve_body($response);
//            set_transient('invelity-plugins-description', $response, 86400);/*Day*/
            set_transient('invelity-plugins-description', $response, 300);/*5 min*/
            $invelityPluginsDescription = $response;
        }
        $this->allPlugins = json_decode($invelityPluginsDescription);
    }

    private function getRemoteAd(){
        $invelityPluginsad = get_transient('invelity-plugins-ad');
        if (!$invelityPluginsad) {
            $query = esc_url_raw(add_query_arg(array(), 'https://licenses.invelity.com/plugins/invelityad.json'));
            $response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));
            $response = wp_remote_retrieve_body($response);
            set_transient('invelity-plugins-ad', $response, 86400);/*Day*/
//            set_transient('invelity-plugins-ad', $response, 300);/*5 min*/
            $invelityPluginsad = $response;
        }
        return json_decode($invelityPluginsad, true);
    }




    public function loadMainAdminAssets()
    {
        wp_register_style('invelity-plugins-main-admin-css', $this->launcher->getPluginUrl() . 'assets/css/invelity-plugins-main-admin.css', array(), '1.0.0');
        wp_enqueue_style('invelity-plugins-main-admin-css');
    }


    public function add_plugin_page()
    {
        if (empty ($GLOBALS['admin_page_hooks']['invelity-plugins'])) {
            add_menu_page(
                'Invelity plugins',
                'Invelity plugins',
                'administrator',
                'invelity-plugins',
                array($this, 'generateMainPage'),
                'none',
                66
            );
        }
    }

    private function generateNotDownloadedPluginHtml($pluginName, $pluginRemoteData)
    {

        ?>
        <div class="item">
            <div class="plugin-list-item-container">
                <div class="header">
                    <div class="logo"><img src="<?= $pluginRemoteData->icon1 ?>" alt=""></div>
                    <div class="title"><?= $pluginName ?></div>
                </div>
                <div class="body">
                    <div class="info-container state-notice">
                        <div class="title">NOT ACTIVE</div>
                    </div>
                    <div class="description">
                        <p><?= $pluginRemoteData->description ?></p>
                    </div>
                </div>
                <div class="footer">
                    <div class="actions-container state-success">
                        <a href="<?= $pluginRemoteData->plugin_download_url ?>" target="_blank" class="invelity-button">Download</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    private function generateDownloadedPluginHtml($pluginName, $pluginRemoteData)
    {
        $path = WP_PLUGIN_DIR .'/'. $pluginRemoteData->wp_url;

        $pluginSlug = explode('/', $pluginRemoteData->wp_url)[0];
        $pluginData = get_plugin_data($path);
        ?>
        <div class="item">
            <div class="plugin-list-item-container">
                <div class="header">
                    <div class="logo"><img src="<?= plugins_url() ?>/<?= explode('/', $pluginRemoteData->wp_url)[0] ?>/assets/images/logo.svg" alt=""></div>
                    <div class="title"><?= $pluginName ?></div>
                </div>
                <div class="body">
                    <div class="info-container state-success">
                        <div class="title">ACTIVATED</div>
                    </div>
                    <div class="description">
                        <p><?= $pluginData['Description'] ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function generateMainPage()
    {
        $activePlugins = get_option('active_plugins');
        ?>
        <div class="wrap invelity-plugins-namespace">
        <h2>Welcome to Invelity Plugins</h2>
        <div class="plugins-list-component" style="margin-top:15px;">
        <div class="list">
        <div class="item">
            <div class="plugin-list-item-container">
                <?php
                $adData = $this->getRemoteAd();
                if($adData){
                    ?>
                    <a href="<?= $adData['adDestination'] ?>" target="_blank">
                        <img src="<?= $adData['adImage'] ?>">
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
        foreach ($this->allPlugins as $pluginName => $pluginData) {
            if (in_array($pluginData->wp_url, $activePlugins)) {
                /*Downloaded*/
                $this->generateDownloadedPluginHtml($pluginName, $pluginData);
            } else {
                /*Not Downloaded*/
                $this->generateNotDownloadedPluginHtml($pluginName, $pluginData);
            }
        }
        ?>

        <?php
    }

}