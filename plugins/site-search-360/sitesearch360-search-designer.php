<?php
    $ss360_plugin = new SiteSearch360Plugin();
    $ss360_siteId = urlencode($ss360_plugin->getSiteId());
    $ss360_client = new SiteSearch360Client();
?>

<?php
	$ss360_designer_url = $ss360_client->getIframeSearchDesignUrl();
	$ss360_separator_char = str_contains($ss360_designer_url, '?') ? '&' : '?';
    $iframeURL = $ss360_designer_url . $ss360_separator_char . 'integration=wordpress&currentUrl=' . $_SERVER['SERVER_NAME'] . '&integrationMode=' . get_option('ss360_sr_type') . '&pluginConfigurationId=' . get_option('ss360_pluginConfigId', null);
    if ($iframeURL != ''){
        echo '<section style="border-style: solid;border-color: #3D8FFF;margin: 15px 15px 0px 0px;">';
        echo '<iframe src="'.$iframeURL.'" style="border: 0;width: 100%;min-height: 850px;min-height:85vh"></iframe>';
        echo '</section>';
    }
?>

<script src="<?php echo plugins_url('assets/sitesearch360_admin_scripts.js',  __FILE__)  ?>" async></script>