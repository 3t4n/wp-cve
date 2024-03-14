<div id="dh_ptp_tabs_3" class="dh_ptp_tab"> 
    <div id="dh_ptp_design_tabs_container">
        <?php include ( PTP_PLUGIN_PATH . '/includes/metaboxes/metabox-blocks/simple-flat-settings.php');
		if ( DH_PTP_LICENSE_PACKAGE !== 'Free' ) {
			include ( PTP_PLUGIN_PATH . '/includes/metaboxes/metabox-blocks/advanced-settings/fancy-flat-settings.php');
			include ( PTP_PLUGIN_PATH . '/includes/metaboxes/metabox-blocks/advanced-settings/stylish-flat-settings.php'); 
			include ( PTP_PLUGIN_PATH . '/includes/metaboxes/metabox-blocks/advanced-settings/design4-settings.php'); 
			include ( PTP_PLUGIN_PATH . '/includes/metaboxes/metabox-blocks/advanced-settings/design5-settings.php'); 
			include ( PTP_PLUGIN_PATH . '/includes/metaboxes/metabox-blocks/advanced-settings/design6-settings.php'); 
			include ( PTP_PLUGIN_PATH . '/includes/metaboxes/metabox-blocks/advanced-settings/design7-settings.php'); 
			include ( PTP_PLUGIN_PATH . '/includes/metaboxes/metabox-blocks/advanced-settings/comparison1-settings.php'); 
			include ( PTP_PLUGIN_PATH . '/includes/metaboxes/metabox-blocks/advanced-settings/comparison2-settings.php'); 
			include ( PTP_PLUGIN_PATH . '/includes/metaboxes/metabox-blocks/advanced-settings/comparison3-settings.php'); 
		}
		 ?>
    </div>
</div>
