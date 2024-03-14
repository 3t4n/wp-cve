<?php
/**
 * Frontend for navigation bar containing 2fa tabs.
 *
 * @package miniorange-login-security/views/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
} ?>
<div class="nav-tab-wrapper">
	<button class="nav-tab" onclick="openTab2fa(this)" id="setup_2fa">Setup Two Factor</button>
	<?php
	if ( current_user_can( 'administrator' ) ) {
		if ( ! get_site_option( 'mo2f_is_NC' ) ) {
			?>
			<button class="nav-tab" onclick="openTab2fa(this)" id="login_option_2fa">Login Option</button>
			<button class="nav-tab" onclick="openTab2fa(this)" id="custom_form_2fa">Integration</button>  
			<?php
		} else {
			?>
			<button class="nav-tab" onclick="openTab2fa(this)" id="rba_2fa">Advanced Features</button>
			<button class="nav-tab" onclick="openTab2fa(this)" id="custom_login_2fa">Addons</button>
			<?php
		}
		?>
		<?php
	}
	if ( ! get_site_option( 'mo2f_is_NC' ) ) {
		?>
		<button class="nav-tab" onclick="openTab2fa(this)" id="video_guide_2fa">Video Guide</button>
		<?php
	}
	if ( current_user_can( 'administrator' ) ) {
		?>
		<?php
	}
	?>
</div>
<div id="mo_scan_message" style=" padding-top:8px"></div>
<div class="momls_wpns_divided_layout" id="setup_2fa_div">
	<?php require_once $mo2f_dir_name . 'controllers' . DIRECTORY_SEPARATOR . 'twofa' . DIRECTORY_SEPARATOR . 'setup-twofa.php'; ?>
</div>
<div class="momls_wpns_divided_layout" id="rba_2fa_div">
	<?php
		require_once $mo2f_dir_name . 'controllers' . DIRECTORY_SEPARATOR . 'twofa' . DIRECTORY_SEPARATOR . 'two-fa-rba.php';
	?>
</div>
<div class="momls_wpns_divided_layout" id="custom_login_2fa_div">
	<?php
	if ( get_site_option( 'mo2f_personalization_installed' ) ) {
		momls_personalization_description( $mo2f_user_email );
	} else {
		require_once $mo2f_dir_name . 'controllers' . DIRECTORY_SEPARATOR . 'twofa' . DIRECTORY_SEPARATOR . 'two-fa-custom-login.php';
	}
	?>
</div>
<div class="momls_wpns_divided_layout" id="login_option_2fa_div">
	<?php require_once $mo2f_dir_name . 'controllers' . DIRECTORY_SEPARATOR . 'twofa' . DIRECTORY_SEPARATOR . 'two-fa-login-option.php'; ?>
</div>
<div class="momls_wpns_divided_layout" id="pswdless_2fa_div">
	<?php require_once $mo2f_dir_name . 'controllers' . DIRECTORY_SEPARATOR . 'twofa' . DIRECTORY_SEPARATOR . 'two-fa-passwordless.php'; ?>
</div>

<div class="momls_wpns_divided_layout" id="video_guide_2fa_div">
	<?php require_once $mo2f_dir_name . 'controllers' . DIRECTORY_SEPARATOR . 'twofa' . DIRECTORY_SEPARATOR . 'two-fa-video-guide.php'; ?>
</div>
<div class="momls_wpns_divided_layout" id="unlimittedUser_2fa_div">
	<?php require_once $mo2f_dir_name . 'controllers' . DIRECTORY_SEPARATOR . 'twofa' . DIRECTORY_SEPARATOR . 'two-fa-unlimitteduser.php'; ?>
</div>
<script>
	jQuery("#setup_2fa_div").css("display", "block");
	jQuery("#rba_2fa_div").css("display", "none");
	jQuery("#pswdless_2fa_div").css("display", "none");
	jQuery("#custom_login_2fa_div").css("display", "none");
	jQuery("#shortcode_2fa_div").css("display", "none");
	jQuery("#login_option_2fa_div").css("display", "none");
	jQuery("#custom_form_2fa_div").css("display", "none");
	jQuery("#video_guide_2fa_div").css("display", "none");

	jQuery("#setup_2fa").addClass("active");
	function openTab2fa(elmt){
		var tabname = elmt.id;
		var tabarray = ["setup_2fa","rba_2fa","pswdless_2fa","custom_login_2fa","shortcode_2fa","login_option_2fa", "custom_form_2fa", "video_guide_2fa","unlimittedUser_2fa"];
		for (var i = 0; i < tabarray.length; i++) {
			if(tabarray[i] === tabname){
				jQuery("#"+tabarray[i]).addClass("nav-tab-active");
				jQuery("#"+tabarray[i]+"_div").css("display", "block");
			}else{
				jQuery("#"+tabarray[i]).removeClass("nav-tab-active");
				jQuery("#"+tabarray[i]+"_div").css("display", "none");
			}
		}
		localStorage.setItem("lastTab2fa", tabname);
	}
	var tab 		= localStorage.getItem("lastTab2fa");
	var is_onprem 	= '<?php echo esc_js( get_site_option( 'is_onprem' ) ); ?>';
	if(tab === "setup_twofa"){
		document.getElementById("setup_2fa").click();
	}
	else if(tab === "rba_2fa"){
		document.getElementById("rba_2fa").click();
	}
	else if(tab === "pswdless_2fa"){
		document.getElementById("pswdless_2fa").click();
	}
	else if(tab === "custom_login_2fa"){
		document.getElementById("custom_login_2fa").click();
	}
	else if(tab === "shortcode_2fa"){
		document.getElementById("shortcode_2fa").click();
	}
	else if(tab === "login_option_2fa"){
		document.getElementById("login_option_2fa").click();
	}
	else if(tab === "custom_form_2fa"){
		document.getElementById("custom_form_2fa").click();
	}
	else if(tab === "video_guide_2fa"){
		document.getElementById("video_guide_2fa").click();		
	}
	else if(tab === "unlimittedUser_2fa")
	{
		document.getElementById("unlimittedUser_2fa").click();	
	}
	else{
		document.getElementById("setup_2fa").click();
	}
</script>
