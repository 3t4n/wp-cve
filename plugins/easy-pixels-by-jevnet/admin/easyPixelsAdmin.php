<?php

add_filter( 'plugin_action_links', 'jn_easypixels_actionLinks' , 10, 2 );

function jn_easypixels_actionLinks( $links, $file ) 
{
	if ( $file == 'easy-pixels-by-jevnet/easyPixels.php' )
	{
		 array_unshift( $links, sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=easypixels' ), __( 'Settings', 'easypixels' ) ) );
	}
	return $links;
}

function jn_easypixels_createMenuOption()
{
	add_menu_page('Easy Pixels Settings','Easy Pixels','administrator','easypixels','jn_easypixels_initTrackingOptions',JN_EasyPixels_URL.'/img/icon20x20.png');
 	if(( class_exists( 'WPCF7_ContactForm' ) )&&(!class_exists('jn_CF7tracking'))&&(isset($_GET['page'])))
	{
		add_submenu_page('easypixels', 'Contact form tracking', 'CF7 tracking', 'administrator', 'CF7easytracking', 'jn_EPCF7NotInstalled' );
	}
 	if(( class_exists( 'WooCommerce' ) )&&(!class_exists('jn_WCtracking'))&&(isset($_GET['page'])))
	{
		add_submenu_page('easypixels', 'Contact form tracking', 'WooCommerce tracking', 'administrator', 'WCeasytracking', 'jn_EPWCNotInstalled' );
	}
}

function jn_easypixels_admintabs_basic()
{
     ?>
     <a href="<?php echo admin_url( 'admin.php?page=easypixels' ); ?>" class="nav-tab<?php if ('easypixels' == $_GET['page'] ) echo ' nav-tab-active'; ?>"><?php echo __( 'Basic tracking','easy-pixels-by-jevnet'); ?></a>
 <?php
 	if(( class_exists( 'WPCF7_ContactForm' ) )&&(!class_exists( 'jn_CF7tracking' ))&&(isset($_GET['page'])))
	{
		echo '<a href="'.esc_url( add_query_arg( array( 'action' => 'CF7' ), admin_url( 'admin.php?page=CF7easytracking' ) ) ).'" class="nav-tab'.(('CF7easytracking' == $_GET['page'] )?' nav-tab-active':'').'">Contact Form 7</a>'; 
	}
 	if(( class_exists( 'WooCommerce' ) )&&(!class_exists( 'jn_easyGAdsWC' ))&&(isset($_GET['page'])))
	{
		echo '<a href="'.esc_url( add_query_arg( array( 'action' => 'WC' ), admin_url( 'admin.php?page=WCeasytracking' ) ) ).'" class="nav-tab'.(('WCeasytracking' == $_GET['page'] )?' nav-tab-active':'').'">WooCommerce</a>'; 
	}
}

function jn_easypixels_initTrackingOptions()
{
	if ( class_exists( 'jn_easypixels' ) ){$easyPixels=new jn_easypixels();}
	require(JN_EasyPixels_PATH . '/admin/page-easyPixelsAdmin.php');
}

function jn_easypixels_saveSettings()
{

	if ( false == get_option( 'jnEasyPixelsSettings-group' ) ) {add_option( 'jnEasyPixelsSettings-group' );}
	if ( class_exists( 'jn_easypixels' ) )
	{
		$easyPixels=new jn_easypixels();
		$easyPixels->save();
	}
}

/* Sanitize Callback Function */
function my_settings_sanitize( $input ){return isset( $input ) ? true : false;}

function jn_EPCF7NotInstalled()
{
	require(JN_EasyPixels_PATH . '/admin/page-cf7NotInstalled.php');
}

function jn_EPWCNotInstalled()
{
	require(JN_EasyPixels_PATH . '/admin/page-WCNotInstalled.php');
}