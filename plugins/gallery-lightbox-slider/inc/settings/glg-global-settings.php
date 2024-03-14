<?php

if ( ! defined( 'ABSPATH' ) ) exit;


function glg_menu_page() {
	
	add_menu_page( 'Gallery Lightbox Settings', 'Gallery Lightbox', 'manage_options', 'gallery-lightbox-settings', 'glg_settings_page', 'dashicons-format-gallery', 20 );
	
}


function glg_settings_page() {
	
	if( has_filter( 'glg_admin_settings_filter' ) ) apply_filters( 'glg_admin_settings_filter', '' );

	wp_enqueue_style( 'glg-settings' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'glg-settings-tab' );
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'glg-checkbox' );
	wp_enqueue_style( 'glg-checkbox-style' );
	add_thickbox();
	
	glg_trial_notify();
	
ob_start();
	
?>
<div class="wrap">
<div style="height:250px;display:block;" class="panelloader ploader"></div>
<div class="settings-wrap" id="page-settings" style="display:none;">
    <div id="option-tree-header-wrap">
        <ul id="option-tree-header">
            <li id="option-tree-version"><span><?php echo GLG_ITEM_NAME; ?></span></li>
            <li style="float:right;" id="option-tree-version"><span>v <?php echo GLG_VERSION; ?></span></li>
        </ul>
    </div>
    <div id="option-tree-settings-api">
        <div class="ui-tabs ui-widget ui-widget-content ui-corner-all">
            <ul >
                <li class="glg_tab_items"><a href="#section_lightbox">Lightbox Settings</a></li>
                <li class="glg_tab_items" id="glg-docs"><a href="#section_documentation">Documentation</a></li>
                <li data-act="glg_free_plugins_page" data-nonce="<?php echo wp_create_nonce( "glg_free_plugins_nonce" ); ?>" class="glg_tab_items glg_ajax_caller"><a href="#section_freeplugins">Free Install Plugins</a></li>
                <li data-act="glg_pro_plugins_page" data-nonce="<?php echo wp_create_nonce( "glg_pro_plugins_nonce" ); ?>" class="glg_tab_items glg_ajax_caller"><a href="#section_premiumplugins">Premium Plugins</a></li>
            <?php    
			if( has_filter( 'glg_admin_settings_menu_filter' ) ) {
				
				apply_filters( 'glg_admin_settings_menu_filter', '' );
				
				}
			?>
                
            </ul>
    <div id="poststuff" class="metabox-holder">
        <div id="post-body">
			<div id="post-body-content">
			
			<?php
            
			require_once( 'glg-lightbox-settings.php');
			require_once( 'pages/glg-docs.php');
			require_once( 'pages/glg-free-plugins.php');
			require_once( 'pages/glg-premium-plugins.php');
			
			if( has_filter( 'glg_admin_settings_container_filter' ) ) {
				
				apply_filters( 'glg_admin_settings_container_filter', '' );
				
				}
				
				?>
            
            
    			</div>
    		</div>
    	</div>
    <div class="clear"></div>
    </div>
   </div>
</div>
</div>

<?php

$content = ob_get_clean();
echo $content;

}

?>