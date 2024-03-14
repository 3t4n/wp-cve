<?php

// Don't run this file directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Check if has href
if(defined("YP_DEMO_MODE") == false){

	if(isset($_GET['href'])){

		// Check if href EMPTY
		if($_GET['href'] == ""){

		// Get the ID
		$id = 0;
		if(isset($_GET['wyp_page_id'])){
			$id = @intval($_GET['wyp_page_id']);
		}

		if($id != "" && $id != 0){

		// SSL
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$current_URL = $protocol.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		$current_URL = add_query_arg(array('href' => wyp_urlencode(esc_url(get_permalink($id)))), $current_URL);

		// Redirect
		wp_safe_redirect($current_URL);
		exit;

		}else{

			// Href parameter empty.
			die('<!DOCTYPE html><html lang="en-US"><head><meta charset="UTF-8"><meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no"><meta name="robots" content="noindex"></head><body>href parameter empty.</body></html>');

		}

	}

}else{

		// Href parameter empty.
		die('<!DOCTYPE html><html lang="en-US"><head><meta charset="UTF-8"><meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no"><meta name="robots" content="noindex"></head><body>href parameter empty.</body></html>');

	}

}

// Dev Editor Options
$filter_live_settings_menu = apply_filters( 'yp_live_settings_menu', TRUE);
$filter_animation_tools = apply_filters( 'yp_animation_tools', TRUE);
$filter_css_editor = apply_filters( 'yp_css_editor', TRUE);

?><!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="robots" content="noindex">
	<meta name="google" value="notranslate">
	<meta http-equiv="Pragma" content="no-cache">
	<title>YellowPencil</title>
	<style>
		body,html{overflow:hidden}.wyp-iframe-loader{display:block;width:100%;height:100%;top:0;left:0;position:fixed;background-color:#fff;z-index:2147483646}#loader{background-color:#ccc;height:2px;width:200px;position:fixed;top:50%;left:50%;margin-left:-100px;margin-top:18px;border-radius:8px}#loader i{background-color:#1592e6;width:10%;position:absolute;height:4px;margin-top:-1px;-webkit-transition:width .1s;transition:width .1s;border-radius:8px}.loading-files{width:130px;height:24px;top:50%;color:#6a6a6a;text-align:center;font-size:12px;left:50%;position:fixed;margin-left:-65px;margin-top:-12px;font-family:-apple-system,BlinkMacSystemFont,'Helvetica Neue',Helvetica,Arial,sans-serif!important;font-weight:600}
	</style>
	<link rel="icon" type="image/svg" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 175 175'%3E%3Cdefs%3E%3ClinearGradient id='A' x1='136.42' x2='39.11' y1='38.58' y2='135.89' gradientUnits='userSpaceOnUse'%3E%3Cstop stop-color='%23c09225' offset='0'/%3E%3Cstop stop-color='%23e3a718' offset='1'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='175' height='175' rx='28.04' fill='url(%23A)'/%3E%3Cpath d='M112.78 73.4V41.53L85.2 25.6 57.6 41.53V73.4l27.6 15.93zm-34.06 59.97V101.5l-27.6-15.93-27.6 15.93v31.86l27.6 15.94zm72.75.1V101.6l-27.6-15.93-27.6 15.93v31.87l27.6 15.93z' fill='rgba(255, 255, 255, 0.95)'/%3E%3C/svg%3E"/>
	<?php yp_editor_header(); ?>
</head><?php

	// Default Classes
	$classes[] = 'yp-yellow-pencil wyp-met-dis wyp-flexible-inspector-active';

	// DEMO MODE
	if(current_user_can("edit_theme_options") == false){
		if(defined("YP_DEMO_MODE")){
			$classes[] = 'yp-yellow-pencil-demo-mode';
		}
	}

	// WTFV
	if(!defined('WTFV')){
		$classes[] = 'wtfv';
	}

	// Trim classes
	$classesReturn = '';
	foreach ($classes as $class){
		$classesReturn .= ' '.$class;
	}

?>
<body class="<?php echo trim($classesReturn); ?>">

	<div class="wyp-iframe-loader">
		<div id="loader"><i></i></div>
		<div class="loading-files">Loading Editor</div>
	</div>

	<?php

		// Get Link
		$frameLink = esc_url(urldecode($_GET['href']));

		// Fix frameLink
		if(empty($frameLink)){
			$frameLink = trim( wp_strip_all_tags( urldecode( $_GET['href']) ) );
		}

		// Empty Variables
		$mode = "";
		$type = "";
		$id = "";
    	$rand = rand(136900, 963100);

		// Only normal mode
		$mode = trim( wp_strip_all_tags( $_GET['wyp_mode'] ) );
		$type = trim( wp_strip_all_tags( $_GET['wyp_page_type'] ) );
		$id = intval($_GET['wyp_page_id']);

		$frame = add_query_arg(array('yellow_pencil_frame' => '','wyp_page_type' => $type,'wyp_page_id' => $id,'wyp_mode' => $mode,'wyp_rand' => $rand), $frameLink);

		// if isset out, set wyp_out to frame
		if(isset($_GET['wyp_out'])){
			$frame = add_query_arg(array('wyp_out' => 'true'),$frame);
		}

		$protocol = is_ssl() ? 'https' : 'http';
		$frameNew = esc_url($frame,array($protocol));

		if(empty($frameNew) == true && strstr($frame,'://') == true){
			$frameNew = explode("://",$frame);
			$frameNew = $protocol.'://'.$frameNew[1];
		}elseif(empty($frameNew) == true && strstr($frame,'://') == false){
			$frameNew = $protocol.'://'.$frame;
		}

		// Cleans the links
		$frameNew = str_replace("&#038;", "&amp;", $frameNew);
		$frameNew = str_replace("&#38;", "&amp;", $frameNew);
		$frameNew = str_replace("#038;", "&amp;", $frameNew);

		// get customize type link
		if(defined("YP_DEMO_MODE")){
			$customize_type_link = add_query_arg(array('wyp_customize_type' => "true"), get_home_url("/"));
		}else{
			$customize_type_link = admin_url('admin.php?page=yellow-pencil-customize-type');
		}

	?>
	<iframe id="iframe" class="yellow_pencil_iframe" data-href="<?php echo $frameNew; ?>" tabindex="-1"></iframe>

	<iframe data-page-href="<?php echo wyp_urlencode(esc_url($_GET['href'])); ?>" data-page-id="<?php echo esc_attr($_GET['wyp_page_id']); ?>" data-page-type="<?php echo esc_attr($_GET['wyp_page_type']); ?>" data-page-visitor="<?php echo isset($_GET['wyp_out']); ?>" id="wyp-customizing-type-frame" style="border-width: 0px;display:none;position:fixed;width:100%;height:100%;top:0;left:0;z-index:2147483645;" data-src="<?php echo esc_attr($customize_type_link); ?>" tabindex="-1"></iframe>

	<div id="ed-elt-tr"><ul></ul></div>

	<div id="vsl-css-vi">
		<div class="css-view-top">
			<span class="yicon icon-filter"></span>
			<input id="visual-rule-filter" placeholder="Filter.." type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />
			<div class="visual-manager-close"></div>
		</div>
		<div id="vsl-css-co"></div>
	</div>

	<span class='yicon icon-admin-collapse wyp-panel-show'></span>

	<style id="wyp-animate-helper"></style>

	<div class="wyp-animate-manager">

		<h3 class="animation-manager-empty">There is no animation on this page.<small>Select an element to add animation.</small></h3>

		<div class="wyp-anim-list-menu"><ul></ul></div>

		<div class="wyp-anim-control-overflow">
			<div class="wyp-anim-controls">
				<div class="wyp-anim-control-left">
					<div class="wyp-anim-manager-control">
						<a class="wyp-anim-control-btn wyp-anim-control-close" data-toggle='tooltipAnim' data-placement='top' title='Close'><span class="yicon icon-no-alt"></span></a>
						<a class="wyp-anim-control-btn wyp-anim-control-pause" data-toggle='tooltipAnim' data-placement='top' title='Stop'><span class="yicon icon-controls-pause"></span></a>
						<a class="wyp-anim-control-btn wyp-anim-control-play" data-toggle='tooltipAnim' data-placement='top' title='Play'><span class="yicon icon-controls-play"></span></a>
						<span class="wyp-anim-current-duration"><span class="wyp-counter-min">00</span>:<span class="wyp-counter-second">00</span>.<span class="wyp-counter-ms">00</span></span>
					</div>
				</div>
				<div class="wyp-anim-control-right">
					<div class="wyp-anim-playing-border"></div>
					<div class="wyp-anim-metric">
					</div>
				</div>
				<div class="wyp-clear"></div>
			</div>
		</div>

		<div class="wyp-animate-manager-inner">

			<div class="wyp-anim-left-part-column">
				<div class="wyp-anim-scroll-shadow"></div>
			</div>
			<div class="wyp-anim-right-part-column">
				<div class="wyp-anim-playing-over"></div>
				<div class="wyp-anim-playing-border"></div>
			</div>
			<div class="wyp-clear"></div>

		</div>

	</div>

	<div class="responsive-right-handle"></div>
	<div class="responsive-left-handle"></div>
	<span class="responsive-add-breakpoint"><span class="yicon icon-plus"></span></span>
	<div class="responsive-size-text"><span class='device-size'></span>px and <span class='media-control' data-code='max-width'>smaller</span> screens</div>

	<?php wyp_yellow_penci_bar(); ?>

	<div class="editor-leftbar">
		<div data-toggle='tooltip-bar' data-placement='right' title='Element Inspector <span class="wyp-s-shortcut">(V)</span>' class="leftbar-button cursor-main-btn wyp-selector-mode active"><span class="no-aiming-icon"></span><span class="aiming-icon"></span><span class="sharp-selector-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='Navigator <span class="wyp-s-shortcut">(N)</span><span class="wyp-tooltip-shortcut">Find elements easily.</span>' class="leftbar-button wyp-navigation-btn"><span class="navigation-icon"></span></div>
		<?php if($filter_css_editor){ ?>
		<div data-toggle='tooltip-bar' data-placement='right' title='CSS Editor <span class="wyp-s-shortcut">(E)</span><span class="wyp-tooltip-shortcut">Edit style codes.</span>' class="leftbar-button css-editor-btn"><span class="css-editor-icon"></span></div>
		<?php } ?>
		<div data-toggle='tooltip-bar' data-placement='right' title='Responsive Mode <span class="wyp-s-shortcut">(R)</span><span class="wyp-tooltip-shortcut">Edit for specific screen sizes.</span>' class="leftbar-button wyp-responsive-btn active"><span class="responsive-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='Measuring Tool <span class="wyp-s-shortcut">(M)</span><span class="wyp-tooltip-shortcut">Measure elements.</span>' class="leftbar-button wyp-ruler-btn"><span class="ruler-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='Wireframe <span class="wyp-s-shortcut">(W)</span><span class="wyp-tooltip-shortcut">Work on the layout easily.</span>' class="leftbar-button wyp-wireframe-btn"><span class="wireframe-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='Design Information <span class="wyp-s-shortcut">(D)</span><span class="wyp-tooltip-shortcut">Typography information.</span>' class="leftbar-button info-btn"><span class="design-information-icon"></span></div>

		<?php if($filter_animation_tools){ ?>
		<div data-toggle='tooltip-bar' data-placement='right' title='Animation Manager <span class="wyp-s-shortcut">(A)</span><span class="wyp-tooltip-shortcut">Manage animations visually.</span>' class="leftbar-button animation-manager-btn"><span class="animation-manager-icon"></span></div>
		<?php } ?>

		<div data-toggle='tooltip-bar' data-placement='right' title='Undo <span class="wyp-tooltip-shortcut">CMD + Z</span>' class="leftbar-button top-area-center undo-btn"><span class="undo-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='Redo <span class="wyp-tooltip-shortcut">CMD + Y</span>' class="leftbar-button redo-btn"><span class="redo-icon"></span></div>

		<?php if($filter_live_settings_menu){ ?>
		<div class="leftbar-button left-menu-btn"><span class="setting-icon"></span></div>
		<?php } ?>

		<div class="left-menu-sublist inspector-sublist">
		<ul>
			<li class="inspector-sublist-cursor" data-cursor-action="cursor">Cursor <i>(navigate)</i></li>
			<li class="inspector-sublist-default active" data-cursor-action="default">Flexible Inspector</li>
			<li class="inspector-sublist-single" data-cursor-action="single">Single Inspector</li>
		</ul>
		</div>

		<?php if($filter_live_settings_menu){ ?>
		<div class="left-menu-sublist interface-settings">
		<ul>

			<li class="fixed_left_bar_checkbox">Pin The Left Bar <label class="interface-settings-switch"><input type="checkbox"><span class="interface-settings-slider"></span></label></li>

			<li class="fixed_right_panel_checkbox">Pin The Right Panel <label class="interface-settings-switch"><input type="checkbox"><span class="interface-settings-slider"></span></label></li>

			<li class="hide_premium_options_checkbox wyp-lite" data-toggle='tooltip-bar' data-placement='right' title='<span class="wyp-tooltip-shortcut2">Hides all premium features on the free version.</span>'>Hide Premium Features <label class="interface-settings-switch"><input type="checkbox"><span class="interface-settings-slider"></span></label></li>

			<li class="show_css_selector_checkbox" data-toggle='tooltip-bar' data-placement='right' title='<span class="wyp-tooltip-shortcut2">Always shows the selector of the selected element.</span>'>Always Show The Element Selector <label class="interface-settings-switch"><input type="checkbox"><span class="interface-settings-slider"></span></label></li>

			<li class="left-sublist-border"></li>

			<li class="smart_responsive_technology_checkbox" data-toggle='tooltip-bar' data-placement='right' title='<span class="wyp-tooltip-shortcut2">This feature automatically detects the styles that can harm the responsive layout and limits them with special screen sizes.</span>'>Auto Responsive <label class="interface-settings-switch"><input type="checkbox"><span class="interface-settings-slider"></span></label></li>

			<li class="append_auto_comments_checkbox" data-toggle='tooltip-bar' data-placement='right' title='<span class="wyp-tooltip-shortcut2">Generates CSS comments for each selector to make the CSS code readable.</span>'>Auto CSS Comments <label class="interface-settings-switch"><input type="checkbox"><span class="interface-settings-slider"></span></label></li>

			<li class="smart_important_tag_checkbox" data-toggle='tooltip-bar' data-placement='right' title='<span class="wyp-tooltip-shortcut2">Adds the important tag to CSS rules if required.</span>'>Auto Important Tag <label class="interface-settings-switch"><input type="checkbox"><span class="interface-settings-slider"></span></label></li>

			<li class="left-sublist-border manage-customizations-border"></li>
			<li class="basic manage-customizations-link"><a href="<?php echo admin_url("admin.php?page=yellow-pencil-changes"); ?>" target="_blank">Manage Customizations <span class="yicon icon-external"></span></a></li>
			<li class="basic hide-panel-hint"><strong>Hint</strong>: Hide editor panels with the H key.</li>

		</ul>
		</div>
		<?php } ?>

		<div class="advanced-info-box">
			<div class="advanced-info-box-menu">
				<span class="advance-info-btns element-btn">Element</span> <span class="advance-info-btns design-btn">Page</span>
			</div>
			<div class="advanced-info-box-inner">

				<div class="typography-content advanced-info-box-content">

					<h3>Color Scheme</h3>
					<div class="info-color-scheme-list">
					</div>

					<h3 class="no-top">Typography</h3>
					<ul class="info-basic-typography-list">
					</ul>

					<h3>Font Families</h3>
					<ul class="info-font-family-list">
					</ul>

					<h3 class="page-assets-h3">Page Assets</h3>
					<div class="info-image-list">
					</div>

					<h3 id="animations-heading">Animations</h3>
					<ul class="info-animation-list">
					</ul>

				</div>

				<div class="element-content advanced-info-box-content">

					<div class="info-element-selected-section">

						<div class="info-element-selector-section">
							<h3 class="no-top">CSS Selector</h3>
							<ul class="info-element-selector-list">
							</ul>
						</div>

						<h3>General</h3>
						<ul class="info-element-general">
						</ul>

						<div class="info-element-classes-section">
							<h3>Classes</h3>
							<ul class="info-element-class-list">
							</ul>
						</div>

						<div class="info-element-accessibility-section">
							<h3>Accessibility</h3>
							<ul class="info-element-accessibility">
							</ul>
						</div>

						<h3>DOM Code</h3>
						<textarea disabled="disabled" class="info-element-dom"></textarea>

					</div>

					<p class="info-no-element-selected">Please select an element to show information.</p>

				</div>

			</div>
		</div>

		<div id="leftAreaEditor">
			<div id="cssData"></div>
			<div id="cssEditorBar">
				<span class="yicon wyp-css-close-btn icon-no-alt" title='Hide (ESC)'></span>
				<span class="editor-tabs single-tab" data-type-value="single">Single<i></i></span>
				<span class="editor-tabs template-tab" data-type-value="template">Template<i></i></span>
				<span class="editor-tabs global-tab" data-type-value="global">Global<i></i></span>
				<div class="editor-tab-border"></div>
				<span class="wyp-css-editor-detach yicon icon-external"></span>
			</div>
			<div class="unvalid-css-error">Error: <span></span></div>
		</div>


		<div id="layer-tree">
			<div id='layer-tree-title'>Navigator <span id="search-css-selector"></span></div>
		</div>

	</div>

	<div class="wyp-right-panel-placeholder"></div>
	<div class="breakpoint-bar"></div>

	<div class="metric-left-border"></div>
	<div class="metric-top-border"></div>
	<div class="metric-top-tooltip"></div>
	<div class="metric-left-tooltip"></div>

	<div class="unvalid-css-cover"></div>

	<div id="image_uploader">
		<iframe data-url="<?php echo admin_url('media-upload.php?TB_iframe=true&reauth=1&wyp_uploader=1'); ?>"></iframe>
	</div>
	<div id="image_uploader_background"></div>

	<div id="selector-editor-box">
		<p class="selector-editor-notice">Enter select, ESC cancel.</p>
		<input autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" type='text' class='wyp-selector-editor' placeholder='Search by class, ID or HTML tag.' id='wyp-selector-editor' />
		<ul id="autocomplate-selector-list"><li>a</li></ul>
	</div>
	<div id="selector-editor-background"></div>

	<div class="wyp-popup-background"></div>
	<div class="wyp-info-modal">
		<div class="wyp-info-modal-top-inner">
			<h2>Changes Are Not Saved. Upgrade To Pro!</h2>
			<p>You are using some premium features. Upgrade to Pro or disable premium features to save changes.</p>
		</div>

		<div class="wyp-action-area">
			<p class="wyp-info-unlock-p">Unlock all premium features now!</p>
			<a class="wyp-info-modal-close">No, Thanks</a><a class="wyp-buy-link" target="_blank" href="https://waspthemes.com/yellow-pencil/buy">Upgrade Now</a>
			<p class="wyp-info-last-note">30 Days Money-back Guarantee &mdash; Lifetime License &mdash; Premium Customer Support</p>
		</div>
		<a class='activate-pro' href="<?php echo admin_url('admin.php?page=yellow-pencil-license'); ?>" target="_blank">Already have a license?</a>
	</div>

	<div class="anim-bar">
		<div class="anim-bar-title">
			<div class="anim-title">Animation Generator</div>
			<div class="anim-icons">
				<div class="wyp-anim-save wyp-anim-btn" data-toggle="tooltipAnimGenerator" data-placement="top" title="Done"><span class="yicon icon-flag"></span></div>
				<div class="wyp-anim-play wyp-anim-btn" data-toggle="tooltipAnimGenerator" data-placement="top" title="Play"><span class="yicon icon-controls-play"></span></div>
				<div class="wyp-anim-cancel wyp-anim-btn" data-toggle="tooltipAnimGenerator" data-placement="top" title="Cancel"><span class="yicon icon-no-alt"></span></div>
			</div>
		</div>
		<div class="scenes">
			<div class="scene scene-active scene-1" data-scene="scene-1"><p><span class="scene-info yicon icon-warning"></span>Scene 1 <span><input autocomplete="off" type='text' value='0' /></span></p></div>
			<div class="scene scene-2 scene-no-click-yet" data-scene="scene-2"><p><span class="scene-info yicon icon-warning"></span>Scene 2 <span><input type='text' autocomplete="off" value='100' /></span></p></div>
			<div class="scene scene-add"><span class="yicon icon-plus"></span></div>
			<div class="wyp-clear"></div>
		</div>
	</div>
	<script>

		// Vars
		var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
		var siteurl = "<?php echo get_site_url(); ?>";
		var pluginurl = "<?php echo plugins_url(plugin_basename(dirname(dirname(__FILE__))) . "/"); ?>";
		var aceEditorBase = "<?php echo (plugins_url( 'js/ace/' , __FILE__ )); ?>";

		// PLUGIN OPTIONS
		window.ypOption = {
				"fixed_right_panel"				: <?php echo wyp_get_live_option("fixed_right_panel"); ?>, // Fixed Right Panel
				"fixed_left_bar"				: <?php echo wyp_get_live_option("fixed_left_bar"); ?>, // Fixed Left Bar
				"hide_premium_options"			: <?php echo wyp_get_live_option("hide_premium_options"); ?>, // Hide Preimum features on Lite Version
				"show_css_selector"				: <?php echo wyp_get_live_option("show_css_selector"); ?>, // Always show the element selector
				"smart_responsive_technology"	: <?php echo wyp_get_live_option("smart_responsive_technology"); ?>, // Auto Responsive
				"smart_important_tag"			: <?php echo wyp_get_live_option("smart_important_tag"); ?>, // Smart Important Tag
				"append_auto_comments"			: <?php echo wyp_get_live_option("append_auto_comments"); ?>, // Append Auto comments
		};

		// Update loading notes.
		var oldP = 0;
		function wyp_load_note(text, p){
			if(window.loadStatus == false && oldP < p){
				if(text){
					document.querySelector('.loading-files').innerHTML = text;
				}
				document.querySelector('#loader i').style.width = p + "%";
				oldP = p;
			}
		}

		// USING NONCE
		window.wyp_editor_nonce = "<?php echo wp_create_nonce('wyp_editor_nonce'); ?>";

		// Reload the page after browser undo & undo
		if (!!window.performance && window.performance.navigation.type === 2) {
			wyp_load_note("Reloading Editor", "0");
			window.location.reload();
		}

    // Selector Comments
    <?php

    	$comments = get_option("yp_selector_comments");

      if($comments == null || $comments == false){

        echo "window.selectorComments = {};";

      }else{

      	// Stripslashes
        $comments = wp_unslash($comments);

        // IS Valid
		    json_decode($comments);
		    if(json_last_error() === JSON_ERROR_NONE){
        	echo "window.selectorComments = ".$comments.";";
        }else{
        	echo "window.selectorComments = {};";
        }

      }

    ?>

		// Variable
		window.loadStatus = false;

		// Document Load Note:
		wyp_load_note("Loading Editor", "20");

		setTimeout(function(){
	        wyp_load_note(null, "23");
		}, 300);

		setTimeout(function(){
	        wyp_load_note(null, "26");
		}, 600);

		setTimeout(function(){
	        wyp_load_note(null, "29");
		}, 900);

		// Document ready.
		(function() {

			var iframeNode = document.getElementById('iframe');

			// Load iframe.
			if(window.bMode){
				iframeNode.contentWindow.location.replace(window.location.href);
			}else{
	    	iframeNode.contentWindow.location.replace(iframeNode.getAttribute("data-href"));
			}

	      // 33%
	      wyp_load_note("Loading Page", "33");

		    setTimeout(function(){
		        wyp_load_note(null, "33");
		    }, 600);

		    setTimeout(function(){
		        wyp_load_note(null, "36");
		    }, 900);

	      // Frame ready
				var iframeReady = false;
				iframeNode.addEventListener("load", function() {

					// check if iframe URL is not valid.
					try {
						var iframeURL = document.getElementById("iframe").contentWindow.location.href;
					} catch(e) {
						alert("This page does not allow to use of the editor.");
						if(window.bMode){window.location.reload();}
						return false;
					}

					// b mode
					if(iframeReady && window.bMode){
						alert("This page cannot be edited as it is redirected. Please open the redirected page directly in the editor.");
						window.location.href = iframeURL;
					}

					// if iframe redirect : follow
					if(window.bMode !== true){
						if(iframeReady || iframeURL.indexOf("yellow_pencil_frame") == -1){

							// show loading
							document.querySelector(".wyp-iframe-loader").style.display = "block";
							document.querySelector(".loading-files").innerHTML = "Page was redirected!";
							window.wyp_redirect_on = true;

						  // Get parent url
						  var parentURL = window.location;

						  // delete after href.
						  parentURL = parentURL.toString().split("href=")[0] + "href=";

						  // Clean url
						  iframeURL = new URL(iframeURL);
						  iframeURL.searchParams.delete("yellow_pencil_frame");
						  iframeURL.searchParams.delete("wyp_page_id");
						  iframeURL.searchParams.delete("wyp_page_type");
						  iframeURL.searchParams.delete("wyp_mode");
						  iframeURL.searchParams.delete("wyp_load_popup");
						  iframeURL.searchParams.delete("wyp_rand");
						  iframeURL.searchParams.delete("wyp_out");

							var xhr = new XMLHttpRequest();
							xhr.open("POST", iframeURL, true);
							xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

							xhr.onreadystatechange = function () {
							    if (this.readyState != 4) return;

							    if (this.status == 200) {

											// Find page details
											var data = document.createElement("div");
											data.insertAdjacentHTML('beforeend', this.responseText);
											data = data.querySelector('#wyp_page_details').innerHTML;

											// same like fail
											if(data === undefined || data === null){
												alert("Page information cannot be retrieved.");
												return false;
											}

											// find all
											var pageID = data.split("|")[0];
											var pageTYPE = data.split("|")[1];
											var pageMODE = data.split("|")[2];

											// Update result URL
											iframeURL = iframeURL.toString().replace(/.*?:\/\//g, ""); // delete protocol
											iframeURL = encodeURIComponent(iframeURL); // encode url
											parentURL = parentURL + iframeURL + "&wyp_page_id="+pageID+"&wyp_page_type="+pageTYPE+"&wyp_mode=" + pageMODE; // update parent URL

											// GO
											window.location = parentURL;


							    }else{
										alert("Page information cannot be retrieved.");
									}

							};

							xhr.send("wyp_get_details=true");

						  return false;

						}
					}

					iframeReady = true;

					// Variables
					var iframe = (iframeNode.contentWindow.document || iframeNode.contentDocument);
					var iframeHead = iframe.head;
					var iframeBody = iframe.body;


					// Moving styles to iframe
					var editorData = document.querySelector("#yellow-pencil-iframe-data");
					if(editorData !== null){
						iframeHead.insertAdjacentHTML('beforeend', editorData.innerHTML.replace(/(^\<\!\-\-|\-\-\>$)/g, ""));
						document.body.removeChild(editorData);
						iframeBody.insertAdjacentHTML('beforeend', '<div id="wyp-animate-data">'+iframeHead.querySelector("#wyp-animate-data").innerHTML+'</div>');
						iframeHead.removeChild(iframeHead.querySelector("#wyp-animate-data"));
					}

					// CSS Loader
					function wyp_load_style(link, i, length){

						var style = document.createElement('link');
						style.rel = "stylesheet";
						style.href = link;
						style.async = false;
						document.head.appendChild(style);

						style.onload = function(){
							wyp_load_note("Loading Styles", 39 + parseInt(21*i/(length - 1)));
						};

					}

					// Loading The Styles
					var styles = [
						"<?php $prtcl = is_ssl() ? 'https' : 'http'; echo $prtcl; ?>://fonts.googleapis.com/css2?family=Roboto+Mono&family=Roboto:wght@400;500&display=swap",
						"<?php echo esc_url(plugins_url( 'css/yellow-pencil.css?wypver='.YP_VERSION.'' , __FILE__ )); ?>"
					];

					// Load styles in iframe
					iframeHead.insertAdjacentHTML('beforeend', "<link rel='stylesheet' id='yellow-pencil-frame'  href='<?php echo plugins_url('css/frame.css?wypver='.YP_VERSION.'', __FILE__); ?>' type='text/css' media='all' />");
					iframeHead.insertAdjacentHTML('beforeend', "<link rel='stylesheet' id='yellow-pencil-animate'  href='<?php echo plugins_url('../public/css/animate.css?wypver='.YP_VERSION.'', __FILE__); ?>' type='text/css' media='all' />");

					// Loading.
					for(var i = 0; i < styles.length; i++){
						wyp_load_style(styles[i], i, styles.length);
					}

					var scripts = [
						"<?php echo plugins_url( 'js/ace/editor.js?wypver='.YP_VERSION.'' , __FILE__ ); ?>",
						"<?php echo plugins_url( 'js/interface.js?wypver='.YP_VERSION.'' , __FILE__ ); ?>",
						"<?php echo plugins_url( 'js/ace/ace.js?wypver='.YP_VERSION.'' , __FILE__ ); ?>",
						"<?php echo plugins_url( 'js/ace/ext-language_tools.js?wypver='.YP_VERSION.'' , __FILE__ ); ?>",
						"<?php echo plugins_url( 'js/addons.js?wypver='.YP_VERSION.'' , __FILE__ ); ?>",
						"<?php echo plugins_url( 'js/yellow-pencil.js?wypver='.YP_VERSION.'' , __FILE__ ); ?>"
					];

					// Stop load and call editor function.
					function wyp_start_editor(){

						// Ready!:
						wyp_load_note("Ready!", "100");

						// Set true.
						window.loadStatus = true;

						if(window.bMode){
							document.querySelector("#customizing-mode .type-heading").innerHTML = window.location.hostname;
						}

						setTimeout(function(){
							var addClasses = ["yp-yellow-pencil", "yellow-pencil-ready"];

							if(window.bMode){
								addClasses.push("wyp-b-mode");
							}

							for(var i = 0; i < addClasses.length; i++){
								document.body.classList.add(addClasses[i]);
								iframeBody.classList.add(addClasses[i]);
							}

							document.querySelector(".wyp-iframe-loader").style.display = 'none';

							document.querySelector("#iframe").focus();

						}, 350);

					}

					// JS loader
					function wyp_load_script(src, i, length) {
						var script = document.createElement('script');
						script.src = src;
						script.async = false;
						document.head.appendChild(script);

						script.onload = function(){
							wyp_load_note("Loading Scripts", 60+parseInt(38*i/(length - 1)));
							if(i === (length - 1)){
								wyp_start_editor();
							}
						};
					}

					// Load scripts
					for(var i = 0; i < scripts.length; i++){
						wyp_load_script(scripts[i], i, scripts.length);
					}

				}); // iframe ready

				// Javascript hook for call in editor
				window.yp_js_hook = function() {
					<?php yp_js_hook(); ?>
				};

		})();
</script>

<?php wyp_editor_styles($id, $type, $mode, "all"); ?>
<?php yp_editor_footer(); ?>
</body>
</html>
