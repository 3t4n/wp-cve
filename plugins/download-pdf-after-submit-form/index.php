<?php
/*
 * Plugin Name: Download PDF After Submit Form
 * Plugin URI: https://wordpress.org/plugins/download-pdf-after-submit-form/
 * Description: Download any pdf After submit form. This plugin offers the premium feature of ready popup design & provides quick access to beautiful all fields that can be edit in your popup easily.
 * Author: Md. Shahinur Islam
 * Author URI: https://profiles.wordpress.org/shahinurislam
 * Version: 2.2.2
 * Text Domain: dpbsf
 * Domain Path: /lang
 * Network: True
 * License: GPLv2
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */
global $session;
session_start();
// don't load directly
defined( 'ABSPATH' ) || exit;
/**
 * Including Plugin file
 *
 * @since 1.0
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
//--------------------- Create css and js ---------------------------//
define( 'DPBSF_PLUGIN', __FILE__ );
define( 'DPBSF_PLUGIN_DIR', untrailingslashit( dirname( DPBSF_PLUGIN ) ) );
require_once DPBSF_PLUGIN_DIR . '/include/enqueue.php';
require_once DPBSF_PLUGIN_DIR . '/include/posttype.php'; 
//-------------All post show------------//
function dpbsf_shortcode_wrapper($atts) {
ob_start(); 
//set attributies
$atts = shortcode_atts(
	array(
		'urlname' => '',
		'title' => ''
	), $atts, 'helloshahin'); 
 //check all input fileds
 if(isset($_POST['submitted'])) {
     
    //not premium
	if(trim($_POST['contactName']) === '') { 
		$hasError = true;
	} else {
	    $name_field = 'Name: ';
		$name = sanitize_text_field(trim($_POST['contactName']))."\n";
	}
	if(trim($_POST['email']) === '')  { 
		$hasError = true;
	} else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['email']))) {		 
		$hasError = true;
	} else {
	    $email_field = 'Email: ';
		$email = sanitize_email(trim($_POST['email']))."\n";
	}
	if(trim($_POST['comments']) === '') { 
		$hasError = true;
	} else {
		if(function_exists('stripslashes')) {
		    $comments_field = "Company: ";
			$comments = sanitize_text_field(stripslashes(trim($_POST['comments'])))."\n";
		} else {
		    $comments_field = "Company: ";
			$comments = sanitize_text_field(trim($_POST['comments']))."\n";
		}
	}
	if(trim($_POST['jobtitle']) === '') { 
		$hasError = true;
	} else {
	    $jobtitle_field = 'Job Title: ';
		$jobtitle = sanitize_text_field(trim($_POST['jobtitle']))."\n";
	}
	if(trim($_POST['dpbsf_check']) === '') { 
		$hasError = true;
	} else {
	    $check_field = 'Check: ';
		$checkn = sanitize_text_field(trim($_POST['dpbsf_check']))."\n";
	}
		
	if(!isset($hasError)) {
		$emailTo = get_option('tz_email');
		if (!isset($emailTo) || ($emailTo == '') ){
			$emailTo = get_option('admin_email');
		}
		$donloadurl = sanitize_url(trim($_POST['urlnameinput'])); //get input url 
		$subject = '[PHP Snippets] From '.$name;
		
		$body = "$name_field$name$email_field$email$comments_field$comments$jobtitle_field$jobtitle$check_field$checkn\nURL: $donloadurl";
		
		$headers = 'From: '.$name.' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;
		//wp_mail($emailTo, $subject, $body, $headers);
		$emailSent = true; 
		$_SESSION['arrayImg'] = "shahin"; //start session
	}
} ?> 
 <!-- Trigger/Open The Modal <button id="myBtn">Download</button>-->
 <?php 
 //session validation
 if($_SESSION['arrayImg'] == "shahin"){  ?>
	<style>
	/*display none when session true*/
		.download{display:none}
		#contactForm{display:none}
	</style>
	<?php
		$urlname = sanitize_url($atts['urlname']); 
	?>
<!-- new btn by jquery -->
<button class="dwn_btn_<?php echo get_option('formstyle') ?>" id="downloadButton<?php esc_html_e($atts['title']);?>">Download</button>
<!--click to download-->
   <script>  
   jQuery('#downloadButton<?php esc_html_e($atts['title']);?>').click(function () {
	  axios({
		  url:'<?php esc_html_e($urlname); ?>',
		  method:'GET',
		  responseType: 'blob'
      })
      .then((response) => {
		 const url = window.URL
		 .createObjectURL(new Blob([response.data],{
			 "type": "text/pdf;charset=utf8;"
		 }));
		const link = document.createElement('a');
		link.href = url;
		link.setAttribute('download', '<?php esc_html_e($atts['title']);?>.pdf');
		document.body.appendChild(link);
		link.click();
      })
   });
   </script> 	
<?php }else{  ?>
	<!-- session validation failed to start modal-->
	<?php $echos = "myModal".esc_html($atts['title']);?>
	<button id="<?php  esc_html_e($echos);?>1" class="dwn_btn_<?php echo get_option('formstyle') ?>">Download</button>
<?php	}  ?>
<!-- main content-->
<div class="entry-content-1">
	<?php if(isset($emailSent) && $emailSent == true) {  ?>
		<div class="thanks"> 			
				<style>
					.download{display:none}
					#contactForm{display:none}
				</style>
				<script>
				//browser form history remove every refresh
					if ( window.history.replaceState ) {
						window.history.replaceState( null, null, window.location.href );
					}
				</script>  
				<?php
					$urlname = esc_html($atts['urlname']); 
					 $title = esc_html($atts['title']);  
				?>  
				<?php if($donloadurl == $urlname ){ ?> 
					<script>	 
						var urlName = "<?php  esc_html_e($donloadurl); ?>";
						var urltitle = "<?php esc_html_e($atts['title']); ?>";
						//window.open(urlName, '_blank', 'new.pdf'); 						 
					</script>   
                   <script> 					
					jQuery(document).ready(function() {
						axios({
                              url:'<?php esc_html_e($donloadurl); ?>',
                              method:'GET',
                              responseType: 'blob'
						  })
						  .then((response) => {
							 const url = window.URL
							 .createObjectURL(new Blob([response.data],{
								 "type": "text/pdf;charset=utf8;"
							 }));
								const link = document.createElement('a');
								link.href = url;
								link.setAttribute('download', '<?php esc_html_e($atts['title']); ?>.pdf');
								document.body.appendChild(link);
								link.click();
						  })
					});
                   </script>			        
					<?php
					// Create post object to save data
					$my_post = array(
						'post_title'    => wp_strip_all_tags( $dpbsf_name_title.$name ),
						'post_content'  => $body,
						'post_status'   => 'draft',
						'post_author'   => 1,
						'post_category' => array( 8,39 ),
						'post_type'		=> 'infomat'
					);
					// Insert the post into the database
					wp_insert_post( $my_post );
					?>
				<?php   }  ?>
		</div>
	<?php } else { ?>		
		<?php if(isset($hasError) || isset($captchaError)) { ?>
			<p class="error">Sorry, an error occured.<p>
		<?php } ?> 
		<!-- The Modal -->
		<div id="<?php esc_html_e($echos);?>" class="modal-DPBSF">
		<!-- Modal content -->
		<div class="modal-dialog-DPBSF">
			<div class="modal-content-DPBSF customModalDpbsp_<?php echo get_option('formstyle') ?>">
				
				<div class="modal-header-DPBSF headerDpbsp_<?php echo get_option('formstyle') ?>">
					<h4>Restricted download</h4>
					<span class="close-DPBSF close<?php esc_html_e($echos);?>">&times;</span>					
				</div> 
				
				<div class="modal-body-DPBSF bodyDpbsp_<?php echo get_option('formstyle') ?>">		
				<!--form--> 
				<form action="" id="contactForm" method="post">		
				
				<?php if(get_option('formstyle') == 'classic' || get_option('formstyle') == 'mordan'){?>
					
					<ul class="contactform contactformDpbsp_<?php echo get_option('formstyle') ?>">			
						<b>To download this file, please fill in this form</b>	
							<li>
								<label for="email">Email</label>
								<input type="text" name="email" id="email" value="" class="input-from-control" required/>	
							</li>				
							<li>
								<label for="contactName">Name</label>
								<input type="text" name="contactName" id="contactName" value="" class="input-from-control" required/>
							</li>
							<li>
								<label for="jobtitle"> Job title</label>
								<input type="text" name="jobtitle" id="jobtitle" value="" class="input-from-control" required/>								 
							</li>
							<input type="hidden" name="urlnameinput" value="<?php esc_html_e($atts['urlname']);?>" required/>
							<li><label for="commentsText">Company</label>
								<input type="text" name="comments" id="commentsText" class="input-from-control" value=""  required/>
							</li>
							<li class="checkbox_<?php echo get_option('formstyle') ?>">			
								<span>
								    <input type="checkbox" id="vehicle1" class="checkboxes" name="dpbsf_check" value="Yes" required/>
								Check In</span>
							</li>
							<li class="getyourfile">
								<button type="submit" class="dwn_btn_<?php echo get_option('formstyle') ?>">GET YOUR FILE</button>
							</li>
					</ul> 		
					<input type="hidden" name="submitted" id="submitted" value="true" />
					
				<?php } ?>
					
				</form>
				<!--end form-->   
				</div>    
			<div class="modal-footer-DPBSF"></div>
		</div>
		</div>
	<?php } ?>
</div><!-- .entry-content --> 
<script> 
// Get the modal 
var <?php esc_html_e($echos);?> = document.getElementById("<?php esc_html_e($echos);?>"); 
// Get the button that opens the modal
var btn = document.getElementById("<?php esc_html_e($echos);?>1");
// Get the <span> element that closes the modal
var span<?php esc_html_e($echos);?> = document.getElementsByClassName("close<?php esc_html_e($echos);?>")[0];
// When the user clicks the button, open the modal 
btn.onclick = function() {
	<?php esc_html_e($echos);?>.style.display = "block";
}
// When the user clicks on <span> (x), close the modal
span<?php esc_html_e($echos);?>.onclick = function() {
	<?php esc_html_e($echos);?>.style.display = "none";
}
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == <?php esc_html_e($echos);?>) {
    <?php esc_html_e($echos);?>.style.display = "none";
  }
} 
</script>
<?php   
 return ob_get_clean();
}
add_shortcode('formtodownload','dpbsf_shortcode_wrapper');
 
// Dashboard Front Show settings page
register_activation_hook(__FILE__, 'dpbsf_plugin_activate');
add_action('admin_init', 'dpbsf_plugin_redirect');
function dpbsf_plugin_activate() {
    add_option('dpbsf_plugin_do_activation_redirect', true);
}
function dpbsf_plugin_redirect() {
    if (get_option('dpbsf_plugin_do_activation_redirect', false)) {
        delete_option('dpbsf_plugin_do_activation_redirect');
        if(!isset($_GET['activate-multi']))
        {
            wp_redirect("edit.php?post_type=infomat&page=settings");
        }
    }
}
//side setting link
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'dpbsf_plugin_action_links' );
function dpbsf_plugin_action_links( $actions ) {
   $actions[] = '<a href="'. esc_url( get_admin_url(null, 'edit.php?post_type=infomat&page=settings') ) .'">Settings</a>';
   $actions[] = '<a href="http://plugins.nasheed.xyz/" class="get_dpasf_pro" target="_blank">Get WP DPASF Pro</a>';
   return $actions;
}
add_action('admin_menu', 'dpbsf_register_my_custom_submenu_page'); 
function dpbsf_register_my_custom_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=infomat',
        'Settings',
        'Settings',
        'manage_options',
        'settings',
        'dpbsf_my_custom_submenu_page_callback' );
} 
function dpbsf_my_custom_submenu_page_callback() {
    ?>
<h1>
<?php esc_html_e( 'Welcome to Download PDF After Submit Form.', 'dpbsf' ); ?>
</h1>
<h3><?php esc_html_e( 'Copy and paste this shortcode here:', 'dpbsf' );?></h3>
<div class="shortcodeClass"> 
	<span class="input">
		<input type="text" id="myInput" value="<?php esc_html_e( '[formtodownload urlname="url" title="1"]', 'dpbsf' );?>" readonly>
	</span>
	<div class="tooltip">
	<button onclick="myFunction()" onmouseout="outFunc()" class="button-85">
	  <span class="tooltiptext" id="myTooltip">Copy to clipboard</span>
	  Click to Copy
	  </button>
	</div>

	<script>
	function myFunction() {
	  var copyText = document.getElementById("myInput");
	  copyText.select();
	  copyText.setSelectionRange(0, 99999);
	  navigator.clipboard.writeText(copyText.value);
	  
	  var tooltip = document.getElementById("myTooltip");
	  tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function outFunc() {
	  var tooltip = document.getElementById("myTooltip");
	  tooltip.innerHTML = "Copy to clipboard";
	}
	</script>
	
</div>
 
<!-- extra options  -->
<p><?php ///echo get_option('formstyle') ?></p>

<form method="post" action="options.php">
	<?php wp_nonce_field('update-options') ?>	

	<h3><strong><?php esc_html_e( 'Please choose style:', 'dpbsf' );?></strong><br /></h3>

	<!-- partial:index.partial.html -->
	<section class="formstyle_admin">
	<div class="group-one groupStyle">
		<input name="formstyle" type="radio" id="one" value="classic" <?php echo $new = get_option('formstyle') == "classic" ? 'checked': ''; ?>>
		<label for="one" class="one formstyleLabel" style="background: url(<?php echo plugin_dir_url( __FILE__ ). '/images/classic.png'?>);"><span class="labelSpan">Classic Style</span></label>
	</div>
	<div class="group-two groupStyle">
		<input name="formstyle" type="radio" id="two" value="mordan" <?php echo $new = get_option('formstyle') == "mordan" ? 'checked': ''; ?>>
		<label for="two" class="two formstyleLabel" style="background: url(<?php echo plugin_dir_url( __FILE__ ). '/images/mordan.png'?>);"><span class="labelSpan">Mordan Style</span></label>
	</div>
	<div class="group-three groupStyle">
		<input type="radio" id="three">
		<label for="three" class="three formstyleLabel custom" style="background: url(<?php echo plugin_dir_url( __FILE__ ). '/images/premium.png'?>);"><a href="http://plugins.nasheed.xyz/"><span  class="labelSpan upgratetopro">Upgrade to Pro</span></a></label>
	</div>
	</section>
	<br />
	<br />
	<br />
	<h3><strong class="upgratetopro"><?php esc_html_e( 'Upgrade to Pro for edit your form.', 'dpbsf' );?></strong><br /></h3>
	
    <section class="contact-us-dpbsf formstyle_admin" id="contact-section-dpbsf"> 
        <div class="groupStyle">
    	<img src="<?php echo plugin_dir_url( __FILE__ ). '/images/custom-gif.gif'?>)">
    	</div>
    	<div class="groupStyle">
    	     <h3><strong><?php esc_html_e( 'Tutorial Video:', 'dpbsf' );?></strong><br /></h3>
        <iframe width="560" height="315" src="https://www.youtube.com/embed/G-FYx8vDnDY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
<iframe width="560" height="315" src="https://www.youtube.com/embed/Q6J6LMn5D18" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    	</div >
    </section>  
	
	<!-- partial --> 
	<br />  
	<button class="button-85" role="button" type="submit">Save Settings</button>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="formstyle, dpbsf_title, dpbsf_subtitle, dpbsf_name,  dpbsf_email,  dpbsf_phone, dpbsf_address, dpbsf_job_title, dpbsf_company, dpbsf_more_1, dpbsf_country, dpbsf_message, dpbsf_check, dpbsf_check_text, dpbsf_submit_button" />
</form> 
<?php
}
