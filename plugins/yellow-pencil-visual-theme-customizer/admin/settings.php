<?php

// Don't run this file directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/* ---------------------------------------------------- */
/* Adding welcome screen Hook
/* ---------------------------------------------------- */
function wyp_welcome_screen_activate() {
	set_transient( 'wyp_plugin_active_notice', true, 5 );
  set_transient( 'wyp_welcome_screen_activation_redirect', true, 30 );
}

register_activation_hook( YP_PLUGIN_DIR.'yellow-pencil.php', 'wyp_welcome_screen_activate' );


/* ---------------------------------------------------- */
/* Plugin activation notice
/* ---------------------------------------------------- */
function wyp_plugin_active_notice(){
		if(file_exists(str_replace("yellow-pencil-visual-theme-customizer", "waspthemes-yellow-pencil", __FILE__)) && strstr(__FILE__, "yellow-pencil-visual-theme-customizer")){
    if( get_transient( 'wyp_plugin_active_notice' ) ){
        ?>
        <div class="error notice is-dismissible">
            <p>You already have the premium version of the YellowPencil plugin. Please <strong>activate the "YellowPencil Pro" plugin</strong>.</p>
        </div>
        <?php
        delete_transient( 'wyp_plugin_active_notice' );
    }
		}
}

add_action( 'admin_notices', 'wyp_plugin_active_notice' );



/* ---------------------------------------------------- */
/* Automatic redirect after active
/* ---------------------------------------------------- */
function welcome_screen_do_activation_redirect() {

  // Bail if no activation redirect
    if ( ! get_transient( 'wyp_welcome_screen_activation_redirect' ) ) {
    return;
  }

  // Delete the redirect transient
  delete_transient( 'wyp_welcome_screen_activation_redirect' );

  // Bail if activating from network, or bulk
  if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
    return;
  }

	if(file_exists(str_replace("yellow-pencil-visual-theme-customizer", "waspthemes-yellow-pencil", __FILE__)) && strstr(__FILE__, "yellow-pencil-visual-theme-customizer")){
		return;
	}

  // Redirect to plugin about page
  if(current_user_can("edit_theme_options")){
  	wp_safe_redirect( add_query_arg( array( 'page' => 'wyp-welcome-screen' ), admin_url( 'admin.php' ) ) );
		exit;
  }

}

add_action( 'admin_init', 'welcome_screen_do_activation_redirect' );



/* ---------------------------------------------------- */
/* Welcome Screen Content
/* ---------------------------------------------------- */
function wyp_welcome_screen_content(){
  ?>
  <div class="wrap wyp-page-welcome about-wrap">
	<h1>Welcome to YellowPencil <?php echo YP_VERSION; ?></h1>

	<div class="about-text wyp-about-text">
		Congratulations! You are about to use the most powerful design tool for WordPress ever.</div>
	<div class="wp-badge wyp-badge">Version <?php echo YP_VERSION; ?></div>
	<p>
		<a href="<?php echo admin_url('themes.php?page=yellow-pencil'); ?>" class="button button-primary button-large">let's start!</a>
	</p>
	<h2 class="nav-tab-wrapper">
			<a href="<?php echo admin_url('admin.php?page=wyp-welcome-screen'); ?>" class="nav-tab<?php if(!isset($_GET['tab'])){ ?> nav-tab-active<?php } ?>">Hello</a>
			<a href="<?php echo admin_url('admin.php?page=wyp-welcome-screen&tab=resources'); ?>" class="nav-tab<?php if(isset($_GET['tab'])){ ?> nav-tab-active<?php } ?>">Resources</a>
	</h2>

	<?php if(!isset($_GET['tab'])){ ?>
	<div class="wyp-welcome-tab">

		<svg class="wyp-featured-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7877 7877" shape-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd"><defs><clipPath id="A"><path d="M3938 0c2176 0 3939 1763 3939 3938 0 2176-1763 3939-3939 3939C1763 7877 0 6114 0 3938 0 1763 1763 0 3938 0z"/></clipPath></defs><path d="M3938 0c2176 0 3939 1763 3939 3938 0 2176-1763 3939-3939 3939C1763 7877 0 6114 0 3938 0 1763 1763 0 3938 0z" fill="#aee3d1"/><g clip-path="url(#A)"><path d="M4405.44 2921.3l480.472 538.632-6123.04 5461.95-480.472-538.632z" fill="#c9897b"/><path d="M4405.44 2921.3l480.472 538.632L3673.514 4541.44l-480.472-538.632z" fill="#e4eff7"/><path d="M4546.22 3079.13l199.023 223.114-1212.397 1081.497-199.023-223.114z" fill="#f5f8fa"/><path d="M-1576.92 8541.16l199.023 223.114 4910.643-4380.452-199.023-223.114z" fill="#d3a094"/><g fill="#396182" fill-rule="nonzero"><path d="M4451 2880l481 539c23 26 20 65-5 87l-6123 5462c-26 23-64 20-87-5l-481-539c-22-25-20-64 5-87l6124-5462c25-22 64-20 86 5zm348 575l-398-447-6032 5380 399 447 6031-5380z"/><path d="M3628 4583l-481-539 92-82 481 539zm2029-2377c25-22 64-20 87 6 22 25 20 64-5 86l-547 488c-26 23-64 21-87-5-23-25-20-64 5-87l547-488zm-2215 263c-24-25-23-64 1-87 24-24 63-23 87 1l255 263c23 25 23 63-1 87-25 24-64 23-87-1l-255-263zm525-729c-7-33 13-66 46-73 33-8 66 12 74 45l169 714c8 33-13 66-46 74-33 7-66-13-74-46l-169-714zm799 351c10-33 45-51 77-41s51 45 40 77l-110 350c-10 32-44 50-77 40-32-10-50-44-40-77l110-349zm555 2300c22 26 18 65-8 87-26 21-65 17-87-9l-232-283c-22-26-18-65 8-86 27-22 65-18 87 8l232 283zm730-477c32 12 48 47 37 79-11 31-47 48-78 37l-690-249c-32-12-48-47-37-79 12-31 47-48 78-37l690 249zm-322-838c34-7 66 15 72 48 7 34-15 66-48 72l-360 70c-34 6-66-16-72-49-7-33 15-65 49-72l359-69z"/></g></g></svg>

		<div class="wyp-right-content">
			<h3>Visual Style Editor For WordPress!</h3>
			<p>YellowPencil is a visual design editor WordPress plugin that allows you to customize your website in real-time with a few clicks. Keep your website's design under control with YellowPencil editor.</p>

			<h3>Changelog</h3>
			<p>Check out <a href="https://yellowpencil.waspthemes.com/changelog/" target="_blank">Changelog</a> for update details.</p>
		</div>
		<div class="clear"></div>

	</div>
	<?php }else{ ?>
	<div class="wyp-welcome-tab">

		<div class="wyp-resources-left">
			<h3>Resources</h3>
			<p></p>
			<ul>
				<li><a href="https://yellowpencil.waspthemes.com/documentation/" target="_blank">Documentation</a></li>
				<li><a href="https://yellowpencil.waspthemes.com/" target="_blank">Plugin Website</a></li>
				<li><a href="https://yellowpencil.waspthemes.com/changelog/" target="_blank">Changelog</a></li>
			</ul>

		</div>

		<div class="wyp-resources-right">

			<h3>Community</h3>
			<ul>
				<li><a href="https://www.facebook.com/groups/YellowPencils/" target="_blank">Facebook Community</a></li>
				<li><a href="https://www.youtube.com/channel/UCKGdPyfmphEdBWPXR91GnYQ" target="_blank">youtube Channel</a></li>
				<li><a href="https://waspthemes.ticksy.com/" target="_blank">Support Forum</a></li>
			</ul>

		</div>
		<div class="clear"></div>

	</div>
	<?php } ?>

	<?php if(!isset($_GET['tab'])){ ?>
	<div class="wyp-welcome-feature feature-section">

		<div class="wyp-column">
			<svg  class="wyp-img-center" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M193.915 150.615l-35.924 14.742-38.937-131.353c-7.144-17.4-27.05-25.733-44.46-18.6L55.68 23.177c-17.4 7.144-25.733 27.05-18.6 44.46L103.6 187.67 67.817 202.36c-17.4 7.144-25.732 27.05-18.588 44.46l8.624 21.016L247 190.22l-8.624-21.017c-7.144-17.4-27.05-25.732-44.46-18.588zM118.65 416l23.52-35.434 38.478 9.994 23.52-35.434 40.58 9.13 22.47-35.004 40.58 9.13-56.487-137.656-189.147 77.616L118.65 416zM76.985 284.54l31.524-12.936 30.184 73.557 10.508-4.3-30.184-73.56 21.017-8.624 30.184 73.558 10.508-4.3-30.184-73.56 21.016-8.624 30.184 73.56 10.508-4.313-30.184-73.56 21.017-8.624 30.184 73.558 10.508-4.3-30.184-73.56 31.525-12.936 43.893 106.968-27.053-6.1-4.296 6.7-18.174 28.313-40.453-9.102-23.368 35.202-38.476-9.996-16.157 24.344L76.985 284.54zM459 62H149.4l5.93 20h303.663c5.512 0 9.998 4.487 9.998 10v340c0 5.513-4.486 10-10 10H130c-2.234 0-4.292-.746-5.96-2l-10.943 16.774A29.85 29.85 0 0 0 130 462h329c16.568 0 30-13.432 30-30V92c0-16.568-13.432-30-30-30z"/><defs ><path  d="M61.772-71.653c.018.072.007.127-.026.2-.053.1-.112.063-.165.128a.64.64 0 0 0-.079.362c-.17.058-.01.227-.015.35-.002.05-.04.105-.045.16-.01.12.017.266.068.37.097.198.268.413.435.544.2.148.365.572.608.63.177.042.384-.104.543-.143.18-.043.397.01.57-.053.222-.08.127-.337.288-.45.104-.074.287-.01.406-.05.2-.07.34-.263.376-.46.016-.082.01-.145.04-.22.04-.103.11-.16.1-.293-.01-.062-.052-.12-.064-.187-.022-.114.002-.224 0-.337-.003-.2.017-.38-.078-.55-.38-.688-1.236-.93-1.975-.79-.18.034-.287.126-.442.207-.17.088-.14.166-.318.224-.08.026-.216.124-.215.224.001.115.005.05.012.17-.02.01-.02-.005-.03-.025"/><path  d="M.44-71.653c.018.072.008.127-.026.2-.052.1-.113.063-.165.128a.63.63 0 0 0-.079.362c-.17.058-.01.227-.015.35-.002.05-.04.105-.045.16-.01.12.017.266.068.37.097.198.268.413.435.544.2.148.365.572.608.63.177.042.384-.104.543-.143.18-.043.397.01.57-.053.222-.08.127-.337.288-.45.104-.074.287-.01.406-.05.2-.07.34-.263.376-.46.016-.082.01-.145.04-.22.038-.103.11-.16.1-.293-.01-.062-.05-.12-.064-.187-.02-.114.002-.224 0-.337-.003-.2.017-.38-.078-.55-.38-.688-1.236-.93-1.975-.79-.18.034-.287.126-.442.207-.17.088-.14.166-.318.224-.08.026-.215.124-.215.224.002.115.005.05.012.17-.02.01-.02-.005-.03-.025"/><path  d="M.495-56.348c.018.072.007.127-.026.2-.053.1-.112.063-.165.128a.64.64 0 0 0-.079.362c-.17.058-.01.227-.015.35-.002.05-.04.105-.045.16-.01.12.017.266.068.37.097.198.268.413.435.544.2.148.365.572.608.63.177.042.384-.104.543-.143.18-.043.397.01.57-.053.222-.08.127-.337.288-.45.104-.074.287-.01.406-.05.2-.07.34-.263.376-.46.016-.082.01-.145.04-.22.04-.103.11-.16.1-.293-.01-.062-.052-.12-.064-.187-.022-.114.002-.224 0-.337-.003-.2.017-.38-.078-.55-.38-.688-1.236-.93-1.975-.79-.18.034-.287.126-.442.207-.17.088-.14.166-.318.224-.08.026-.216.124-.215.224l.013.17c-.02.01-.02-.005-.03-.025"/><path  d="M8.156-41.042c.018.072.007.127-.026.2-.053.1-.112.063-.165.128a.64.64 0 0 0-.079.362c-.17.058-.01.227-.015.35-.002.05-.04.105-.045.16-.01.12.017.266.068.37.097.198.268.413.435.544.2.148.365.572.608.63.177.042.384-.104.543-.143.18-.043.397.01.57-.053.222-.08.127-.337.288-.45.104-.074.287-.01.406-.05.2-.07.34-.263.376-.46.016-.082.01-.145.04-.22.04-.103.11-.16.1-.293-.01-.062-.052-.12-.064-.187-.022-.114.002-.224 0-.337-.003-.2.017-.38-.078-.55-.38-.688-1.236-.93-1.975-.79-.18.034-.287.126-.442.207-.17.088-.14.166-.318.224-.08.026-.216.124-.215.224.001.115.005.05.012.17-.02.012-.02-.004-.03-.024"/></defs></svg>

			<div class="wyp-feature-column">
				<h4>Start Customizing!</h4>
				<p>Edit fonts, color, sizes, and more with just a few clicks. <a href="<?php echo admin_url('themes.php?page=yellow-pencil'); ?>">Start to customizing</a>.</p>
			</div>

		</div>

		<div class="wyp-column">
			<svg class="wyp-img-center" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><circle cx="7.5" cy="5.5" r=".5"/><circle cx="5.5" cy="5.5" r=".5"/><circle cx="3.5" cy="5.5" r=".5"/><path d="M3.5 29h-1A1.5 1.5 0 0 1 1 27.5v-23A1.5 1.5 0 0 1 2.5 3h18a.5.5 0 1 1 0 1h-18a.5.5 0 0 0-.5.5v23a.5.5 0 0 0 .5.5h1a.5.5 0 1 1 0 1zm26 0h-21a.5.5 0 1 1 0-1h21a.5.5 0 0 0 .5-.5v-16a.5.5 0 1 1 1 0v16a1.5 1.5 0 0 1-1.5 1.5zm-1.96-17.282a.5.5 0 0 1-.354-.853l2.12-2.12c.975-.975.975-2.56 0-3.536l-1.414-1.414c-.975-.975-2.562-.975-3.536 0l-.706.706a.5.5 0 0 1-.707-.707l.707-.707c1.365-1.365 3.586-1.364 4.95 0L30.014 4.5c1.365 1.365 1.365 3.585 0 4.95l-2.12 2.12a.5.5 0 0 1-.354.147zM5.567 28.033a.5.5 0 0 1-.476-.653l2.7-8.366a.5.5 0 0 1 .629-.322.5.5 0 0 1 .322.629l-2.407 7.435 7.034-2.277-.24-1.687-2.104-.3a.5.5 0 0 1-.424-.424l-.354-2.475a.5.5 0 1 1 .99-.142l.3 2.104 2.104.3a.5.5 0 0 1 .424.424l.354 2.475a.5.5 0 0 1-.341.546L5.72 28.008a.47.47 0 0 1-.153.025z"/><path d="M13.58 22.85a.5.5 0 0 1-.354-.853L25.77 9.45a.5.5 0 0 1 .707.707L13.933 22.703a.5.5 0 0 1-.354.147zm-2.83-2.83a.5.5 0 0 1-.354-.853L21.53 8.036a.5.5 0 0 1 .707.707L11.104 19.875a.5.5 0 0 1-.354.146z"/><circle cx="23.296" cy="6.975" r=".5"/><path d="M9.5 26.54a.5.5 0 0 1-.354-.146L7.922 25.18a.5.5 0 0 1 .707-.707l1.214 1.214a.5.5 0 0 1-.353.853zm-1.225-6.873a.5.5 0 0 1-.354-.853L21.53 5.208a.5.5 0 0 1 .707.707L8.63 19.52a.5.5 0 0 1-.354.146zm5.658 5.658a.5.5 0 0 1-.354-.853L28.246 9.804 22.943 4.5a.5.5 0 0 1 .707-.707l5.657 5.657a.5.5 0 0 1 0 .707l-15.02 15.02c-.098.098-.225.147-.353.147zM17 8H2a.5.5 0 1 1 0-1h15a.5.5 0 1 1 0 1z"/></svg>

			<div class="wyp-feature-column">
				<h4>Manage Customizations</h4>
				<p><a href="<?php echo admin_url("admin.php?page=yellow-pencil-changes"); ?>">Manage customizations</a> at any time. Take full control over the website's design.</p>
			</div>

		</div>

		<div class="wyp-column">
			<svg class="wyp-img-center" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none" stroke="#000" stroke-linejoin="round" stroke-width="2"><circle cx="32" cy="32" r="30"/><circle cx="32" cy="32" r="16.506"/><path d="M20.646 20.025l-9.542-9.54M43.97 43.352l9.55 9.548m-9.516-32.224l9.545-9.542M20.676 44.006L11.13 53.55" stroke-linecap="round" stroke-miterlimit="10"/></svg>

			<div class="wyp-feature-column">
				<h4>Community & Support!</h4>
				<p>Join our <a target="_blank" href="https://www.facebook.com/groups/YellowPencils/">Facebook community</a> and checking out <a target="_blank" href="https://yellowpencil.waspthemes.com/documentation/">Plugin Documentation</a>.</p>
			</div>

		</div>

		<div class="clear"></div>

	</div>

	<p class="wyp-thank-you">Thank you for choosing YellowPencil.</p>
	<?php } ?>

</div>
  <?php
}



/* ---------------------------------------------------- */
/* Adding plugin control menu
/* ---------------------------------------------------- */
function wyp_add_setting_menu() {

    add_menu_page (
        'YellowPencil Options',
        'YellowPencil',
        'edit_theme_options',
        'yellow-pencil-changes',
        'wyp_option_func',
        'dashicons-admin-customizer'
    );

    add_submenu_page( 'yellow-pencil-changes', "Customizations", "Customizations", 'edit_theme_options', 'yellow-pencil-changes', 'wyp_option_func' );
    add_submenu_page( 'yellow-pencil-changes', "Settings", "Settings", 'edit_theme_options', 'yellow-pencil-settings', 'wyp_option_func' );

    $filter_animation_tools = apply_filters( 'yp_animation_tools', TRUE);
    if($filter_animation_tools){
    	add_submenu_page( 'yellow-pencil-changes', "Custom Animations", "Custom Animations", 'edit_theme_options', 'yellow-pencil-animations', 'wyp_option_func' );
    }

    if(defined("WTFV")){
    	add_submenu_page( 'yellow-pencil-changes', "Product License", "Product License", 'edit_theme_options', 'yellow-pencil-license', 'wyp_option_func');
    }

    add_submenu_page( 'yellow-pencil-changes', "Import/Export", "Import/Export", 'edit_theme_options', 'yellow-pencil-export', 'wyp_option_func' );

    if(!defined("WTFV")){
    	add_submenu_page( 'yellow-pencil-changes', "Go Pro!", "Go Pro!", 'edit_theme_options', 'yellow-pencil-license', 'wyp_option_func');
    }

    add_submenu_page( 'yellow-pencil-changes', "About", "About", 'edit_theme_options', 'wyp-welcome-screen', 'wyp_welcome_screen_content' );

}

add_action('admin_menu', 'wyp_add_setting_menu');


function wyp_css_style_li($title, $href, $type, $page_id = null, $page_type = null){

	$key = "";
	$plusTitle = "";

	// Reset links
	if($type == 'single'){

		$key = $page_id;

		// Specific single types
		if($page_id == "lostpassword" || $page_id == "register" || $page_id == "login" || $page_id == "home"){
			$data = get_option("wt_".$page_id."_css");
		}else{
			$data = get_post_meta($page_id, '_wt_css', true);
		}

		if(get_option("show_on_front") == "page"){
			$frontID = get_option('page_on_front');
	    	$blogID = get_option('page_for_posts');
    	}else{
    		$frontID = 0;
	    	$blogID = 0;
    	}

		if($page_id == $frontID && $frontID != 0){
			$plusTitle = " — <span class='wyp-name-highlight'>Front Page</span>";
		}

		if($page_id == $blogID && $blogID != 0){
			$plusTitle = " — <span class='wyp-name-highlight'>Posts Page</span>";
		}

		$deleteData = "ID|".$page_id;
	}elseif($type == 'template'){
		$data = get_option("wt_".$page_type."_css");
		$key = $page_type;
		$deleteData = "TYPE|".$page_type;
	}elseif($type == 'global'){
		$data = get_option("wt_css");
		$key = "global";
		$deleteData = "GLOBAL|"."global";
	}

	$class = "";
	if(strstr($title, "Inactive")){
		$class = " class='wyp-inactive-style'";
	}

	if($title == ""){
		$title = "Unknown";
	}

	?>
	<li<?php echo $class; ?> data-delete-value="<?php echo $deleteData; ?>">

		<span class="wyp-checkbox"><input type="checkbox" name="wyp-customizations[]" value="<?php echo $deleteData; ?>" /></span>

		<span class="wyp-edited-page-title"><?php echo $title; echo $plusTitle; ?></span>

		<a class="wyp-delete-page-edits" title="Delete"></a>

		<?php if($href != null){ ?>
		<a class="wyp-open-in-editor" title="Open With YellowPencil" target="_blank" href="<?php echo admin_url('admin.php?page=yellow-pencil-editor&href='.wyp_urlencode(esc_url($href)).''); ?>&#38;wyp_page_id=<?php echo $page_id; ?>&#38;wyp_page_type=<?php echo $page_type; ?>&#38;wyp_mode=<?php echo $type; ?>"></a>
		<?php } ?>

		<span class="wyp-clear"></span>

	</li>
	<div id="wyp-inline-editor-<?php echo $key; ?>" class='wyp-inline-css'><?php echo stripslashes($data); ?></div>
	<?php
}



/* ---------------------------------------------------- */
/* Updating admin footer text
/* ---------------------------------------------------- */
function wyp_admin_footer () {

	// Get screen
	$current_screen = get_current_screen();

	// if is YellowPencil page
	$is_yellow_pencil_screen = ( $current_screen && false !== strpos( $current_screen->base, 'yellow-pencil' ) );

	// if YellowPencil page
	if($is_yellow_pencil_screen){

		if(defined('WTFV')){
			echo 'Enjoyed <strong>YellowPencil</strong>? Please leave us a <a target="_blank" href="https://codecanyon.net/downloads">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. We really appreciate your support!';
		}else{
			echo 'Enjoyed <strong>YellowPencil</strong>? Please leave us a <a target="_blank" href="https://wordpress.org/support/plugin/yellow-pencil-visual-theme-customizer/reviews/?filter=5#new-post">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. We really appreciate your support';
		}

	}

}

add_filter('admin_footer_text', 'wyp_admin_footer');



/* ---------------------------------------------------- */
/* Update changes
/* ---------------------------------------------------- */
function wyp_option_update(){

	// Can?
	if(current_user_can("import") == true){

		// Import the data
		if(isset($_POST['wyp_json_import_data']) && check_admin_referer('wyp_json_import_data')){

			$data = trim( wp_strip_all_tags ( $_POST['wyp_json_import_data'] ) );

			if(empty($data) == false){

				wyp_import_data($data);

				// Get All CSS data as ready-to-use
				$output = wyp_get_export_css("create");

				// Update custom.css file
				wyp_create_custom_css($output);

			}

		}

		// Update output format.
		if(isset($_POST['wyp-output-option']) && check_admin_referer("wyp_settings_nonce")){

			$value =  sanitize_key($_POST['wyp-output-option']);

			if(!update_option('yp-output-option',$value)){
				add_option('yp-output-option',$value);
			}

		}

		// Update draft mode.
		if(isset($_POST['wyp-draft-mode']) && check_admin_referer("wyp_settings_nonce")){

			$value = sanitize_key($_POST['wyp-draft-mode']);

			if(!update_option('yp-draft-mode',$value)){
				add_option('yp-draft-mode',$value);
			}

		}


		// Update default globa.
		if(isset($_POST['wyp-default-global']) && check_admin_referer("wyp_settings_nonce")){

			$value = sanitize_key($_POST['wyp-default-global']);

			if(!update_option('yp-default-global',$value)){
				add_option('yp-default-global',$value);
			}

		}

	}

}
add_action("admin_init","wyp_option_update");



function wyp_options_controller() {

	if(current_user_can("edit_theme_options")){

		// Get page, tab.
		global $pagenow;

		if(isset($_GET['page'])){

			// license tab
			if($pagenow == "admin.php" && $_GET['page'] == "yellow-pencil-license")  {

				// Delete license key
				if(isset($_GET["wyp-disable-license"]) && check_admin_referer("wyp_disable_license_nonce")){
					delete_option('yp_purchase_code');
				}

				// If isset product license, ie activation success.
				if(isset($_GET['purchase_code']) == true){

					// Purchase Code
					$code = sanitize_key($_GET['purchase_code']);

					// Adds Product code
					if(!update_option("yp_purchase_code",$code)){
						add_option("yp_purchase_code",$code);
					}

					// Redirect
					wp_safe_redirect(admin_url( 'admin.php?page=yellow-pencil-license&wyp-activated=1' ));
					exit;

				}elseif(defined('WTFV') == false){

					// Get purchase code from database
					$purchase_code = get_option("yp_purchase_code");

					// Has?
					if($purchase_code){
						delete_option('yp_purchase_code');
					}

				}

			}

		}

	}

}

add_action('admin_init', 'wyp_options_controller');


/* ---------------------------------------------------- */
/* Admin Control Functions
/* ---------------------------------------------------- */
function wyp_option_func() {

		// GEt page, tab.
		$screen = get_current_screen();
		$active_tab = $screen->base;
		$active_tab = str_replace("yellowpencil_page_", "", $active_tab);
		$active_tab = str_replace("toplevel_page_", "", $active_tab);

        ?>
        <div class="wrap">

            <h2>YellowPencil</h2>

            <?php

            // activated message
			if(isset($_GET['wyp-activated'])){
				echo '<div id="message" class="updated">
				        <p><strong>YellowPencil Pro successfully activated.</strong></p>
				    </div>';
			}

			// Updated message.
			if(isset($_POST['wyp_json_import_data']) && check_admin_referer('wyp_json_import_data')){
				?>
					<div id="message" class="updated">
				        <p><strong>Settings saved.</strong></p>
				    </div>
				<?php
			}


			// Updated message.
			if(isset($_POST['wyp-output-option']) || isset($_POST['wyp-draft-mode']) || isset($_POST['wyp-default-global'])){
				if(check_admin_referer("wyp_settings_nonce")){
				?>
					<div id="message" class="updated">
				        <p><strong>Settings saved.</strong></p>
				    </div>
				<?php
				}
			}

			// Reset message.
			if(isset($_POST['wyp_reset']) && check_admin_referer("wyp_reset")){
				?>
					<div id="message" class="updated">
				        <p><strong>Defined CSS comments and plugin options have been reset.</strong></p>
				    </div>
				<?php
			}


			// bulk actions
			if(isset($_POST["wyp-customizations"]) && check_admin_referer("wyp_bulk_actions") && isset($_POST["action"])){

				// Delete customizations
				if($_POST["action"] == "delete"){

				// Action: delete
				foreach ($_POST["wyp-customizations"] as $key => $value){

					// Delete customizations
					wyp_delete_customization(explode("|", $value)[0], explode("|", $value)[1]);

				}

				// Get All CSS data as ready-to-use
			    $output = wyp_get_export_css("create");

			    // Update custom.css file
			    wyp_create_custom_css($output);

				?>
					<div id="message" class="updated">
				        <p><strong><?php echo count($_POST["wyp-customizations"]); ?> customization deleted.</strong></p>
				    </div>
				<?php

				}

			}


			// bulk actions: animations
			if(isset($_POST["wyp-animations"]) && check_admin_referer("wyp_bulk_actions_animations") && isset($_POST["action"])){

				// Delete animations
				if($_POST["action"] == "delete"){

				// Action: delete
				foreach ($_POST["wyp-animations"] as $key => $value){

					// Delete animations
					delete_option(trim(wp_strip_all_tags(($value))));

				}

				?>
					<div id="message" class="updated">
				        <p><strong><?php echo count($_POST["wyp-animations"]); ?> animation deleted.</strong></p>
				    </div>
				<?php

				}

			}

		?>

            <h2 class="nav-tab-wrapper wyp-tab-wrapper">
                <a href="?page=yellow-pencil-changes" class="nav-tab <?php echo $active_tab == 'yellow-pencil-changes' ? 'nav-tab-active' : ''; ?>">Customizations</a>
                <a href="?page=yellow-pencil-settings" class="nav-tab <?php echo $active_tab == 'yellow-pencil-settings' ? 'nav-tab-active' : ''; ?>">Settings</a>

                <?php

                	// Filter animation Tools
	                $filter_animation_tools = apply_filters( 'yp_animation_tools', TRUE);

	    			if($filter_animation_tools){

    			?>
                	<a href="?page=yellow-pencil-animations" class="nav-tab <?php echo $active_tab == 'yellow-pencil-animations' ? 'nav-tab-active' : ''; ?>">Custom Animations</a>
                <?php
            		}
                ?>

                <?php if(defined('WTFV')){ // this tab available just on pro version ?>
                <a href="?page=yellow-pencil-license" class="nav-tab <?php echo $active_tab == 'yellow-pencil-license' ? 'nav-tab-active' : ''; ?>">Product License</a>
                <?php } ?>
                <a href="?page=yellow-pencil-export" class="nav-tab <?php echo $active_tab == 'yellow-pencil-export' ? 'nav-tab-active' : ''; ?>">Import / Export</a>
                <?php if(!defined('WTFV')){ // this tab available just on pro version ?>
                <a href="?page=yellow-pencil-license" class="wyp-license-tab nav-tab <?php echo $active_tab == 'yellow-pencil-license' ? 'nav-tab-active' : ''; ?>">Go Pro</a>
                <?php } ?>
            </h2>

            <?php

            	// Base directory for Ace Editor
                echo "<script>window.aceEditorBase = '".YP_PLUGIN_URL."js/ace/'; window.wyp_live_styles_delete_nonce = '".wp_create_nonce('wyp_live_styles_delete_nonce')."';</script>";

            	/* ---------------------------------------------------- */
				/* CSS CHANGES               							*/
				/* ---------------------------------------------------- */
                if( $active_tab == 'yellow-pencil-changes' ) {

                ?>

                	<div class="wyp-no-code">
                		<svg enable-background="new 0 0 512 512" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><path d="m416 80h-32v-32c0-17.672-14.328-32-32-32h-178.74c-8.492 0-16.633 3.375-22.633 9.375l-77.25 77.25c-6 6-9.375 14.141-9.375 22.625v274.75c0 17.672 14.328 32 32 32h32v32c0 17.672 14.328 32 32 32h256c17.672 0 32-14.328 32-32v-352c0-17.672-14.328-32-32-32zm-312 320c-4.422 0-8-3.578-8-8v-264h48c17.672 0 32-14.328 32-32v-48h168c4.422 0 8 3.578 8 8v336c0 4.422-3.578 8-8 8h-240zm312 56c0 4.422-3.578 8-8 8h-240c-4.422 0-8-3.578-8-8v-24h192c17.672 0 32-14.328 32-32v-288h24c4.422 0 8 3.578 8 8v336z" fill="#4D4D4D"/></svg>
                    	<p>There is no style applied. <a href="<?php echo admin_url('themes.php?page=yellow-pencil'); ?>">Let's start!</a></p>
                    </div>

                	<div class="wyp-tab-section">

            		<h2>Manage Styles</h2>
                    <p class="wyp-heading-text">All the changes you've made with YellowPencil are listed below. You can review, edit or delete them.</p>

					<form method="POST" class="wyp-code-group">

					<?php wp_nonce_field('wyp_bulk_actions'); ?>

					<div class="wyp-code-manager">

						<div class="wyp-code-action">
							<label for="wyp-bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
							<select name="action" id="wyp-bulk-action-selector-top">
								<option value="-1">Bulk Actions</option>
								<option value="delete">Delete</option>
							</select>
							<input type="submit" class="button action" value="Apply">
						</div>

						<?php $wyp_export_nonce = wp_create_nonce("wyp_export_nonce"); ?>
						<a href="<?php echo admin_url("admin.php?page=yellow-pencil-changes&wyp_exportCSS=true&_wpnonce=".$wyp_export_nonce); ?>" class="button">Export as CSS File</a>

					</div>

					<div class="wyp-global-group">
						<h3><span class="wyp-checkbox"><input type="checkbox" id="global-choose-all" /></span><label for="global-choose-all">Global Customization</label></h3>
						<ul>
						<?php

							$allCount = 0;
							$count = 0;

							// Global
							if(get_option("wt_css") != ''){
								$count++;
								$allCount++;

								if(get_option("show_on_front") == "page"){
									$frontpage_id = get_option('page_on_front');
						    	}else{
						    		$frontpage_id = 0;
						    	}

								$frontpage_type = 'home';

								// If no home page id, use only home page editing method.
								if($frontpage_id == 0 || $frontpage_id == null){
									$frontpage_id = 'home';
								}else{
									$frontpage_type = get_post_type($frontpage_id);
								}

								wyp_css_style_li("Global", get_home_url().'/', 'global', $frontpage_id, $frontpage_type);

							}

						?>
						</ul>
					</div>
					<?php
						if($count == 0){
							echo "<style>.wyp-global-group{display:none;}</style>";
						}
					?>

					<div class="wyp-template-group">
						<h3><span class="wyp-checkbox"><input type="checkbox" id="template-choose-all" /></span><label for="template-choose-all">Template Customizations</label></h3>
						<ul>
						<?php

							$count = 0;

							// Post Types
							$post_types = get_post_types();

							// Using shop post type for WooCommerce shop page.
							array_push($post_types, "shop");

							foreach ($post_types as $post_type){

								if(get_option("wt_".$post_type."_css") != ''){

									$count++;
									$allCount++;

									if($post_type == "shop" && class_exists('WooCommerce')){

										$last_post_id = wc_get_page_id('shop');

										$link = get_the_permalink($last_post_id);

										$title = ucfirst($post_type).' page';

									}else{

										// get last post
										$last_post = wp_get_recent_posts(array("post_status" => "publish","numberposts" => 1, "post_type" => $post_type));

										// checks and get
										if(empty($last_post) == false){
											$last_post_id = $last_post['0']['ID'];
											$link = get_the_permalink($last_post_id);
										}

										// if no id, leave it empty and not show a edit link
										if(isset($last_post_id) == false){
											$last_post_id = 0;
											$link = null;
										}

										$title = ucfirst($post_type).' template';

									}

									wyp_css_style_li($title, $link, 'template', $last_post_id, $post_type);

								}

							}

							// Search Template
							if(get_option("wt_search_css") != ''){

								$count++;
								$allCount++;

								wyp_css_style_li("Search template", get_home_url().'/?s='.wyp_getting_last_post_title(), 'template', 'search', 'search');

							}

							// Tag Template
							if(get_option("wt_tag_css") != ''){

								$count++;
								$allCount++;

								$tag_id = '';
								$tags = get_tags(array('orderby' => 'count', 'order' => 'DESC','number'=> 1 ));
								if(empty($tags) == false){
									$tag_id = $tags[0];
								}

								wyp_css_style_li("Tag template", get_tag_link($tag_id), 'template', 'tag', 'tag');

							}

							// Category Template
							if(get_option("wt_category_css") != ''){

								$count++;
								$allCount++;

								$cat_id = '';
								$cats = get_categories(array('orderby' => 'count', 'order' => 'DESC','number'=> 1 ));
								if(empty($cats) == false){
									$cat_id = $cats[0];
								}

								wyp_css_style_li("Category template", get_category_link($cat_id), 'template', 'category', 'category');

							}

							// Archive Template
							if(get_option("wt_archive_css") != ''){

								$count++;
								$allCount++;

								$latest_post = get_posts("post_type=post&numberposts=1");
								$latest_post_id = $latest_post[0]->ID;
								$last_post_date = get_the_date("Y",$latest_post_id);
								$archive_link = get_home_url()."/".$last_post_date;

								wyp_css_style_li("Archive template", $archive_link, 'template', 'archive', 'archive');

							}

							// Author Template
							if(get_option("wt_author_css") != ''){

								$count++;
								$allCount++;

								wyp_css_style_li("Author template", get_author_posts_url(1), 'template', 'author', 'author');

							}

							// 404 Template
							if(get_option("wt_404_css") != ''){

								$count++;
								$allCount++;

								wyp_css_style_li("404 error template", get_home_url().'/?p=987654321', 'template', '404', '404');

							}

						?>
						</ul>
					</div>
					<?php
						if($count == 0){
							echo "<style>.wyp-template-group{display:none;}</style>";
						}
					?>


					<div class="wyp-single-group">
						<h3><span class="wyp-checkbox"><input type="checkbox" id="single-choose-all" /></span><label for="single-choose-all">Single Customizations</label></h3>
						<ul>
						<?php

						$count = 0;

						// Homepage
						if(get_option("wt_home_css") != ''){

							$count++;
							$allCount++;

							if(get_option("show_on_front") == "page"){
								$frontpage_id = get_option('page_on_front');
						    }else{
						    	$frontpage_id = 0;
						    }

							if($frontpage_id == 0 || $frontpage_id == null){
								wyp_css_style_li("Non-Static Homepage", get_home_url().'/', 'single', 'home', 'home');
							}else{
								wyp_css_style_li("Non-Static Homepage".'<small>(Inactive)</small>', get_home_url().'/', 'single', 'home', 'home');
							}

						}

						// login Template
						if(get_option("wt_login_css") != ''){

							$count++;
							$allCount++;

							wyp_css_style_li("WordPress Login", wp_login_url(), 'single', 'login', 'login');

						}

						// register Template
						if(get_option("wt_register_css") != ''){

							$count++;
							$allCount++;

							wyp_css_style_li("WordPress Register", wp_registration_url(), 'single', 'register', 'register');

						}

						// lost password Template
						if(get_option("wt_lostpassword_css") != ''){

							$count++;
							$allCount++;

							wyp_css_style_li("WordPress Lost Password", wp_registration_url(), 'single', 'lostpassword', 'lostpassword');

						}

						// Unknown Customizations
						global $wpdb;
						$querystr = "SELECT * FROM `$wpdb->postmeta` WHERE `meta_key` LIKE '_wt_css'";
						$pageposts = $wpdb->get_results($querystr, OBJECT);

						if($pageposts):

							global $post;

							foreach ($pageposts as $post):

							if(isset($post->post_id)){

								$id = $post->post_id;
								$title = ucfirst(get_the_title($id));

								if($title == "''"){
									$title = '(Unknown)';
								}

								if(get_post_meta($id, '_wt_css', true) != ''){

									$count++;
									$allCount++;

									wyp_css_style_li($title, get_the_permalink($id), 'single', $id, get_post_type($id));

								}

							}

							endforeach;

						endif;

						wp_reset_query();

						?>
					</ul>
					</div>
					<?php
						if($count == 0){
							echo "<style>.wyp-single-group{display:none;}</style>";
						}
					?>

					<?php if($allCount == 0){
						echo "<style>.wyp-tab-section{display:none;}.wyp-no-code{display:block}</style>";
					}

					?>

					</form>

					</div>

					<?php


				/* ---------------------------------------------------- */
				/* SETTINGS                 							*/
				/* ---------------------------------------------------- */
                } elseif( $active_tab == 'yellow-pencil-settings' )  {

                	?>

                	<div class="wyp-tab-section">

                	<h2>CSS Print Method</h2>
					<p class="wyp-heading-text">The static external CSS file option is still in beta testing, if there is a problem please use dynamic inline CSS.</p>
					<form method="POST">
						<table class="form-table wyp-form-table">
							<tbody>
							<tr>
								<?php

									$a = '';
									$b = '';
									if(get_option('yp-output-option') == 'external'){
										$a = 'checked="checked"';
									}

									if(get_option('yp-output-option') != 'external'){
										$b = 'checked="checked"';
									}

								?>
								<th><label><input name="wyp-output-option" value="external" <?php echo $a; ?> type="radio" /> Static External CSS File</label></th>
								<td><code><?php echo get_site_url(null,'custom.css'); ?></code></td>
							</tr>
							<tr>
								<th><label><input name="wyp-output-option" value="inline" <?php echo $b; ?> type="radio" /> Dynamic Inline CSS</label></th>
								<td><code>&lt;head&gt;&lt;style&gt;.body{color:gray...</code></td>
							</tr>
							</tbody>
						</table>

						<h2 style="padding-top:1em;">Settings</h2>

						<table class="form-table wyp-form-table">
							<tbody>
							<tr>
								<?php

									$a = '';
									if(get_option('yp-default-global') == '1'){
										$a = 'checked="checked"';
									}

								?>
								<th><input name="wyp-default-global" value="0" <?php echo $a; ?> type="hidden" /><label><input name="wyp-default-global" value="1" <?php echo $a; ?> type="checkbox" /> Set Global Customization As Default</label></th>
								<td><code>single customization type is the default.</code></td>
							</tr>
							<tr>
								<?php

									$a = '';
									if(get_option('yp-draft-mode') == '1'){
										$a = 'checked="checked"';
									}

								?>
								<th><input name="wyp-draft-mode" value="0" <?php echo $a; ?> type="hidden" /><label><input name="wyp-draft-mode" value="1" <?php echo $a; ?> type="checkbox" /> Draft Mode</label></th>
								<td><code>doesn't forget to disable it when you ready!</code></td>
							</tr>
							</tbody>
						</table>

						<div class="wyp-output-css-footer">
							<input type="submit" class="button-primary" value="Save Changes" />
							<a class="button wyp-button-reset">Reset Options</a>
						</div>
						<?php wp_nonce_field('wyp_settings_nonce'); ?>
					</form>

					<!-- Virtual Post -->
					<form id="wyp-reset-form" method="POST">
						<input type="hidden" value="1" name="wyp_reset" />
						<?php wp_nonce_field('wyp_reset'); ?>
					</form>

				</div>

                	<?php


                /* ---------------------------------------------------- */
				/* ANIMATIONS               							*/
				/* ---------------------------------------------------- */
                } elseif( $active_tab == 'yellow-pencil-animations' )  {

                    ?>

                    <div class="wyp-no-animation">
						<svg enable-background="new 0 0 512 512" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><path d="m416 80h-32v-32c0-17.672-14.328-32-32-32h-178.74c-8.492 0-16.633 3.375-22.633 9.375l-77.25 77.25c-6 6-9.375 14.141-9.375 22.625v274.75c0 17.672 14.328 32 32 32h32v32c0 17.672 14.328 32 32 32h256c17.672 0 32-14.328 32-32v-352c0-17.672-14.328-32-32-32zm-312 320c-4.422 0-8-3.578-8-8v-264h48c17.672 0 32-14.328 32-32v-48h168c4.422 0 8 3.578 8 8v336c0 4.422-3.578 8-8 8h-240zm312 56c0 4.422-3.578 8-8 8h-240c-4.422 0-8-3.578-8-8v-24h192c17.672 0 32-14.328 32-32v-288h24c4.422 0 8 3.578 8 8v336z" fill="#4D4D4D"/></svg>

	                    <p>There is no generated animation. You can create animation by use Animation Generator in the editor.</a></p>
	                </div>

                    <div class="wyp-tab-section">

	                    <div class="wyp-global-group">

		                    <h2>Manage Animations</h2>
		                    <p class="wyp-heading-text">Generated animations are listed below, You can review and delete them.</p>

							<form method="POST" class="wyp-code-group wyp-animations-code-group">

							<?php wp_nonce_field('wyp_bulk_actions_animations'); ?>

							<div class="wyp-code-manager">

								<div class="wyp-code-action">
									<span class="wyp-checkbox"><input type="checkbox" id="animation-choose-all"></span>
									<label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
									<select name="action" id="bulk-action-selector-top">
										<option value="-1">Bulk Actions</option>
										<option value="delete">Delete</option>
									</select>
									<input type="submit" id="doaction" class="button action" value="Apply">
								</div>

							</div>

								<ul>

									<?php

										$countAnim = 0;

										$all_options =  wp_load_alloptions();
										foreach($all_options as $name => $value){
											if(stristr($name, 'yp_anim')){
												$countAnim = $countAnim+1;
												$name = str_replace("yp_anim_", "", $name);
												$deleteData = "yp_anim_".$name;

												$value = stripslashes($value);

												$value = str_replace("{", "{\n\t", $value);
												$value = str_replace("}", "\n}\n", $value);
												$value = str_replace(";", ";\n\t", $value);
												$value = str_replace("\t\n}", "}", $value);

												?>
												<li data-delete-value="<?php echo $deleteData; ?>">

												<span class="wyp-checkbox"><input type="checkbox" name="wyp-animations[]" value="<?php echo $deleteData; ?>"></span>

												<span class="wyp-edited-page-title"><?php echo ucwords(strtolower($name)); ?></span>

												<a class="wyp-delete-page-edits" title="Delete"></a>

												<span class="wyp-clear"></span>

												</li>
												<div id="wyp-inline-editor-<?php echo $name; ?>" class='wyp-inline-css'><?php echo $value; ?></div>
												<?php
											}
										}

									?>

								</ul>

							</form>

						</div>

						<?php

							if(0 == $countAnim){
								echo '<style>.wyp-global-group{display:none;}.wyp-no-animation{display:block;}</style>';
							}

						?>

					</div>

                    <?php


                /* ---------------------------------------------------- */
				/* LICENSE               							    */
				/* ---------------------------------------------------- */
                } elseif( $active_tab == 'yellow-pencil-license' )  {

                	// Get purchase code from database
                	$purchase_code = get_option("yp_purchase_code");

                	$isActive = false;

                	// Button Text
                	if(isset($_GET['purchase_code']) || $purchase_code){

                		// Disable license nonce
                		$wyp_disable_license_nonce = wp_create_nonce('wyp_disable_license_nonce');

                		// pCode
                		$pcode = $purchase_code;

                		// New pCode
                		if(isset($_GET['purchase_code'])){
                			$pcode = $_GET['purchase_code'];
                		}

                		$siteURL = wp_parse_url(get_home_url());

                		$isActive = true;
                		$activate_btn = "Unlink License";
                		$aclink = '<a class="wyp-disable-license" data-site="'.$siteURL["host"].'" data-code="'.$pcode.'" data-href="'.admin_url('admin.php?page=yellow-pencil-license&wyp-disable-license=true&_wpnonce='.$wyp_disable_license_nonce).'">';

                	}else{

                		$primaryButton = '';
                		if(defined('WTFV')){
                			$primaryButton = " button-primary";
                		}

                		$activate_btn = "Activate YellowPencil Pro";
                		$aclink = '<a class="button button-hero wyp-product-activation'.$primaryButton.'" href="https://waspthemes.com/yellow-pencil/auto-update/?client-redirect='.urlencode(admin_url('admin.php?page=yellow-pencil-license')).'&version=v2">';

                	}

                	?>

                	<div class="wyp-activation-section <?php if(defined('WTFV') == false && $isActive == false){ echo 'wyp-go-pro'; } ?>">

                		<?php if($isActive == false){ ?>

                			<?php if(defined('WTFV')){ ?>

                				<h2>Activate Your Copy!</h2>
	                			<p>In order to receive all benefits of YellowPencil, you need to activate your copy of the plugin. By activating YellowPencil License you will unlock <strong>premium features</strong> and <strong>direct plugin updates</strong>.</p>

	                		<?php }else{ ?>

	                			<h1>Unlock Premium Features!</h1>
	                			<p>Would you like to access all premium features? <strong>Font families</strong>, <strong>Backgrounds</strong>, <strong>Animations</strong> and much more. Go Pro today! First, buy a license and then activate it.</strong></p>

	                		<?php } ?>

	                	<?php }else{ ?>
	                		<?php if(defined('YP_PRO_DIRECTORY')){ ?>
	                			<h2>Welcome To Pro Club!</h2>
	                			<p>You have activated YellowPencil Pro version which allows you to access all the customer benefits! You will be notified when new updates are available. Thank you for choosing YellowPencil!</p>
	                			<?php }else{ ?>
	                			<h2>Welcome To Pro Club!</h2>
	                			<p>You have activated YellowPencil Pro version which allows you to access all features! <strong>Font families</strong>, <strong>Backgrounds</strong>, and other all features are now available. Thank you for choosing YellowPencil!</p>
	                		<?php } ?>
	                	<?php } ?>

	                	<br />

	                    <p style="margin:0px;padding:0px;">

	                    	<?php if(defined('WTFV') == false && $isActive == false){ ?>
	                    	<a target="_blank" class="button button-primary button-hero" href="https://waspthemes.com/yellow-pencil/buy/" style="margin-right:20px;">Buy A License</a>
	                    	<?php } ?>

	                    	<?php echo $aclink; ?><?php echo $activate_btn; ?></a>

	                    </p>

	                    <?php if($isActive == false){ ?>

	                    		<?php if(defined('WTFV')){ ?>
		                    		<p class='description'>Don't have the license yet? <a href='https://waspthemes.com/yellow-pencil/buy/' target='_blank'>Purchase a license</a>!</p>
		                    	<?php }else{ ?>
		                    		<p class='description'>Check out <a href='https://yellowpencil.waspthemes.com/' target='_blank'>plugin website</a> for more information.</p>
		                    	<?php } ?>

						<?php }else{ ?>

							<p class='description'>YellowPencil Pro is activated. <a href='<?php echo admin_url('admin.php?page=wyp-welcome-screen'); ?>'>Let's Start!</a></p>

						<?php } ?>

					</div>

					<?php


				/* ---------------------------------------------------- */
				/* EXPORT/IMPORT               							*/
				/* ---------------------------------------------------- */
                } elseif( $active_tab == 'yellow-pencil-export' )  {

                    ?>

                    <div class="wyp-tab-section">
	                    <h2>Export Project</h2>
						<p class="wyp-heading-text">Copy what appears to be a random string of alpha numeric characters in following text area<br />and paste into Import field on another web site.</p>
						<div class="wyp-export-section">
							<textarea rows="6" class="wyp-admin-textarea"><?php echo wyp_get_export_data(); ?></textarea>
						</div>

						<hr style="margin-top: 50px;margin-bottom: 25px;">

						<h2>Import Project</h2>
						<p class="wyp-heading-text">Paste the exported data and click "Import Data" button.</p>
						<form method="POST">
							<div class="wyp-import-section">
								<textarea name="wyp_json_import_data" rows="6" class="wyp-admin-textarea"></textarea>
								<?php wp_nonce_field('wyp_json_import_data'); ?>
							</div>
							<input type="submit" class="button" value="Import Data" />
						</form>
					</div>

                    <?php

                }

            ?>

        </div>

    <?php

}
