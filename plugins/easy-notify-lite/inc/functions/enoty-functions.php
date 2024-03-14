<?php


/*-------------------------------------------------------------------------------*/
/* Get Control Panel Options
/*-------------------------------------------------------------------------------*/
function enoty_get_option( $name ){
    $pnp_values = get_option( 'easynotify_opt' );
    if ( is_array( $pnp_values ) && array_key_exists( $name, $pnp_values ) ) return $pnp_values[$name];
    return false;
} 

/*-------------------------------------------------------------------------------*/
/*   Register CSS & JS ( ADMIN AREA )
/*-------------------------------------------------------------------------------*/
function easynotify_reg_script() {
	
	$is_rtl = ( is_rtl() ? '-rtl' : '' );
	
	wp_register_style( 'enoty-ui-themes-redmond', plugins_url( 'css/jquery/jquery-ui/themes/smoothness/jquery-ui-1.10.0.custom.min.css' , dirname(__FILE__) ), false, ENOTIFY_VERSION );

	wp_register_style( 'enoty-multiselect-css', plugins_url( 'css/jquery/multiselect/jquery.multiselect.css' , dirname(__FILE__) ), false, ENOTIFY_VERSION );
	
	wp_register_script( 'enoty-multi-sel', plugins_url( 'js/jquery/multiselect/jquery.multiselect.js' , dirname(__FILE__) ) );	

	wp_register_style( 'enoty-colorpicker', plugins_url( 'css/colorpicker.css' , dirname(__FILE__) ), false, ENOTIFY_VERSION );
	wp_register_script( 'enoty-colorpickerjs', plugins_url( 'js/colorpicker/colorpicker.js' , dirname(__FILE__) ), false );	
	wp_register_script( 'enoty-eye', plugins_url( 'js/colorpicker/eye.js' , dirname(__FILE__) ), false );
	wp_register_script( 'enoty-utils', plugins_url( 'js/colorpicker/utils.js' , dirname(__FILE__) ), false );		
	wp_register_script( 'enoty-cookie', plugins_url( 'js/jquery/jquery.cookie.js' , dirname(__FILE__) ), false );	
	
	wp_register_style( 'enoty-cpstyles', plugins_url( 'css/funcstyle'.$is_rtl.'.css' , dirname(__FILE__) ), false, ENOTIFY_VERSION, 'all');
	wp_register_style( 'enoty-sldr', plugins_url( 'css/slider.css' , dirname(__FILE__) ), false, ENOTIFY_VERSION );
	
	wp_register_script( 'enoty-comparison-js', plugins_url( 'js/compare.js' , dirname(__FILE__) ) );
	wp_register_style( 'enoty-comparison-css', plugins_url( 'css/compare.css' , dirname(__FILE__) ), false, ENOTIFY_VERSION );
	
	wp_register_script( 'enoty-bootstrap-js', plugins_url( 'js/bootstrap/bootstrap.min.js' , dirname(__FILE__) ) );
	wp_register_style( 'enoty-bootstrap-css', plugins_url( 'css/bootstrap/css/bootstrap.min.css' , dirname(__FILE__) ), false, ENOTIFY_VERSION );
		
	}
		
add_action( 'admin_init', 'easynotify_reg_script' );

/*-------------------------------------------------------------------------------*/
/*   Register CSS & JS ( FRONT END )
/*-------------------------------------------------------------------------------*/
function easynotify_frontend_js() {

	wp_register_script( 'enoty-enotybox-js', ENOTIFY_URL. '/js/enotybox/jquery.enotybox.js' );
	wp_register_script( 'enoty-cookie-front', ENOTIFY_URL. '/js/jquery/jquery.cookie.js' );
	wp_register_style( 'enoty-enotybox-style', ENOTIFY_URL .'/css/enotybox/jquery.enotybox.css', false, ENOTIFY_VERSION );
	wp_register_style( 'enoty-frontend-style', ENOTIFY_URL .'/css/frontend.css', false, ENOTIFY_VERSION );
		
}
add_action( 'wp_enqueue_scripts', 'easynotify_frontend_js' );

/*-------------------------------------------------------------------------------*/
/*   Ajax Init
/*-------------------------------------------------------------------------------*/
add_action('wp_ajax_nopriv_easynotify_ajax_content', 'easynotify_ajax_content');
add_action('wp_ajax_easynotify_ajax_content', 'easynotify_ajax_content');


/*-------------------------------------------------------------------------------*/
/*   CHECK BROWSER VERSION ( IE ONLY )
/*-------------------------------------------------------------------------------*/
function easynotify_check_browser_version_admin( $sid ) {
	
	if ( is_admin() && get_post_type( $sid ) == 'easynotify' ){

		preg_match( '/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches );
		if ( count( $matches )>1 ){
			$version = explode(".", $matches[1]);
			switch(true){
				case ( $version[0] <= '8' ):
				$msg = 'ie8';

			break; 
			  
				case ( $version[0] > '8' ):
		  		$msg = 'gah';
			  
			break; 			  

			  default:
			}
			return $msg;
		} else {
			$msg = 'notie';
			return $msg;
			}
	}
}
 
/*-------------------------------------------------------------------------------*/
/*  RENAME POST BUTTON
/*-------------------------------------------------------------------------------*/
add_filter( 'gettext', 'easynotify_publish_button', 10, 2 );
	function easynotify_publish_button( $translation, $text ) {
	if ( 'easynotify' == get_post_type())
	if ( $text == 'Publish' ) {
		return 'Save Notify'; }
	else if ( $text == 'Update' ) {
		return 'Update Notify'; }	
	
	return $translation;
} 

/*-------------------------------------------------------------------------------*/
/*  Get the pattern/layout list 
/*-------------------------------------------------------------------------------*/
function easynotify_get_list( $list ) {
	$lst = array();
	$lst_list = scandir( ENOTIFY_DIR."/css/images/".$list );
	
	foreach( $lst_list as $lst_name ) {
		if ( $lst_name != '.' && $lst_name != '..' ) {
			$lst[] = $lst_name;
		}
	}
	return $lst;	
}

/*-------------------------------------------------------------------------------*/
/*  Strip current shortcode when using default notify
/*-------------------------------------------------------------------------------*/
function easynotify_strip_shortcode($code, $content){
    global $shortcode_tags;

    $stack = $shortcode_tags;
    $shortcode_tags = array($code => 1);

    $content = strip_shortcodes($content);

    $shortcode_tags = $stack;
    return $content;
}	

/*-------------------------------------------------------------------------------*/
/* Generate Notify Script
/*-------------------------------------------------------------------------------*/
function easynotify_ajax_script( $id, $val ) {

	$offect = explode("-", get_post_meta( $id, 'enoty_cp_open_effect', true ));
	$cffect = explode("-", get_post_meta( $id, 'enoty_cp_close_effect', true )); 
	
	if ( get_post_meta( $id, 'enoty_cp_thumbsize_swc', true ) == 'on' ) {
		$notyw = get_post_meta( $id, 'enoty_cp_thumbsize_tw', true );
		$notyh = get_post_meta( $id, 'enoty_cp_thumbsize_th', true );
		} else {
			$notyw = 740;
			$notyh = 'auto';
			}
	
	ob_start(); ?>
    
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		
		function easynotify_notify_loader() {
				var notydata = {
				action: "easynotify_ajax_content",
				security: "<?php echo wp_create_nonce( "easynotify-nonce"); ?>",	
				notyid: <?php echo $id; ?>
				};
			
				jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", notydata, function(response) {
					jQuery('#noty-<?php echo $id; ?>').append(response);
					
					var timerId;
					if(timerId != undefined){clearInterval(timerId);}
 					timerId =  setInterval(function (){
					jQuery('#launcher-<?php echo $id; ?>').fancybox({
						type: 'inline',
						padding: 0,
						margin: 60,
						width: '<?php echo $notyw; ?>',
						height: '<?php echo $notyh; ?>',
						transitionIn: '<?php echo $offect[1]; ?>', 
						transitionOut: '<?php echo $cffect[1]; ?>',
						autoScale: false,
						showNavArrows: false,
						hideOnOverlayClick: false,
						autoDimensions: false,
						fitToView: false,
						scrolling: 'no',
						centerOnScroll: true,
						onComplete: function(){
							    clearInterval(timerId);
								}
						}).trigger("click");
						}, <?php echo get_post_meta( $id, 'enoty_cp_notify_delay', true ); ?>000);
				});
			}
				
			// COOKIE CONFIG
			var check_cookie = jQuery.cookie('notify-<?php echo $id; ?>');
			var ex_cookie = <?php echo get_post_meta( $id, 'enoty_cp_cookies', true ); ?>;
			if (check_cookie == null || ex_cookie == '-1') {
				easynotify_notify_loader();
					}  <?php $ckonset = get_post_meta( $id, 'enoty_cp_cookies', true ); if ( $ckonset != '-1' || $ckonset != '0' ) { ?>
					jQuery.cookie('notify-<?php echo $id; ?>', 'true', {
					expires: <?php echo get_post_meta( $id, 'enoty_cp_cookies', true ); ?>,
					path: '/' 
					}); <?php } ?>
			});
</script>

<?php

$contnt = ob_get_clean();
echo $contnt;  

}


/*-------------------------------------------------------------------------------*/
/* Generate Notify Content
/*-------------------------------------------------------------------------------*/
function easynotify_ajax_content() {
		
		if ( !isset( $_POST['notyid'] ) || !isset( $_POST['security'] ) ) {
			die;
		}
		
		else {
			check_ajax_referer( 'easynotify-nonce', 'security' );
			$lyot = get_post_meta( $_POST['notyid'], 'enoty_cp_layoutmode', true );
			$layout = preg_replace('/\\.[^.\\s]{3,4}$/', '', $lyot);
			
			if ( $layout ) {
				include_once( ENOTIFY_DIR . '/layouts/'.str_replace('_', '-', $layout ).'.php' );
				$layoutfunc = $layout;
			}

			ob_start();
			
			$layoutfunc( $_POST['notyid'] );
			
			$contnt = ob_get_clean();
			
			echo $contnt; 
			die;		
	
		}

	}


/*-------------------------------------------------------------------------------*/
/*  Get WP Info
/*-------------------------------------------------------------------------------*/
$easymemory = (int) ini_get('memory_limit');
$easymemory = empty($easymemory) ? __('N/A') : $easymemory . __(' MB');

function easynotify_get_wpinfo() {
	
// Get Site URL	
$getwpinfo = array();
$getwpinfo[0] = "- Site URL : " .get_site_url();

// Get Multisite status
if ( is_multisite() ) { $getwpinfo[1] = '- WP Multisite : YES'; } else { $getwpinfo[1] = '- WP Multisite : NO'; }

global $wp_version, $easymemory;		
echo "- WP Version : ".$wp_version."\n";	
echo $getwpinfo[0]."\n";
echo $getwpinfo[1]."\n";
echo "- Memory Limit : ".$easymemory."\n";
$theme_name = wp_get_theme();
echo "- Active Theme : ".$theme_name->get('Name')."\n";
echo "- Active Plugins : \n";

// Get Active Plugin
if ( is_multisite() ) { 
	$the_plugs = get_site_option('active_sitewide_plugins');
	foreach($the_plugs as $key => $value) {
		$string = explode('/',$key);
		$string[0] = str_replace( "-"," ",$string[0] );
        echo " &nbsp;&nbsp;&nbsp;&nbsp;".ucwords( $string[0] ) ."\n";
	}
} else {
	$the_plugs = get_option('active_plugins');
	foreach($the_plugs as $key => $value) {
		$string = explode('/',$value);
		$string[0] = str_replace( "-"," ",$string[0] );
        echo " &nbsp;&nbsp;&nbsp;&nbsp;".ucwords( $string[0] ) ."\n";
		}
	}
}


/*-------------------------------------------------------------------------------*/
/*  AJAX RESET SETTINGS
/*-------------------------------------------------------------------------------*/
function easynotify_cp_reset() {
	
	check_ajax_referer( 'easynotify-nonce', 'security' );
	
	if ( !isset( $_POST['cmd'] ) ) {
		echo '0';
		die;
		}
		
		else {
			if ( $_POST['cmd'] == 'reset' ){
				echo '1';
				easynotify_restore_to_default($_POST['cmd']);			
				die;
				}
	}
}
add_action( 'wp_ajax_easynotify_cp_reset', 'easynotify_cp_reset' );


/*-------------------------------------------------------------------------------*/
/*  Clear Cookies from Notify List
/*-------------------------------------------------------------------------------*/
function easynotify_enqueue_script_on_notify_list( ) {
		
		global $post_type;
		
		    if( 'easynotify' == $post_type ) {
				wp_enqueue_script( 'enoty-cookie' );
				wp_enqueue_style( 'enoty-admin-styles', plugins_url('../css/admin.css' , __FILE__ ) );

				
				?>
                <script type="text/javascript">
				jQuery(document).ready(function($) { 
					jQuery('.resetcookie').bind('click', function() {
						jQuery.removeCookie(jQuery(this).attr('id'), { path: '/' }); 
						alert("Successfully cleared this Notify cookies!");						
						});
                
				    });
                    </script>
				<?php
		}
				
}

if (is_admin()) {
	add_action( 'admin_head', 'easynotify_enqueue_script_on_notify_list' );
	}


/*-------------------------------------------------------------------------------*/
/*  Create Preview Metabox
/*-------------------------------------------------------------------------------*/
function easynotify_preview_metabox () {
	$enotyprev = '<div style="text-align:center;">';
	$enotyprev .= '<img id="preview-notify" style="cursor:pointer;" src="'.plugins_url( 'images/preview.png' , dirname(__FILE__) ).'" width="65" height="65" alt="Preview" >';
	$enotyprev .= '</div>';
echo $enotyprev;	
}


/*-------------------------------------------------------------------------------*/
/*  Create New Plugin Metabox @since > 1.1.7
/*-------------------------------------------------------------------------------*/
function easynotify_new_plug_metabox () {
	$enonew = '<div style="text-align:center;">';
	$enonew .= '<a style="outline: none !important;" href="http://goo.gl/divK5t" target="_blank"><img style="cursor:pointer; margin-top: 7px;" src="'.plugins_url( 'images/new-plugin.png' , dirname(__FILE__) ).'" width="241" height="151" alt="New Plugin" ></a>';
	$enonew .= '</div>';
echo $enonew;	
}


/*-------------------------------------------------------------------------------*/
/*  Create Upgrade Metabox
/*-------------------------------------------------------------------------------*/
function easynotify_upgrade_metabox () {
	$enobuy = '<div style="text-align:center;">';
	$enobuy .= '<a id="notifyprcngtableclr" style="outline: none !important;" href="#"><img style="cursor:pointer; margin-top: 7px;" src="'.plugins_url( 'images/buy-now.png' , dirname(__FILE__) ).'" width="241" height="95" alt="Buy Now!" ></a>';
	$enobuy .= '</div>';
echo $enobuy;	
}


/*-------------------------------------------------------------------------------*/
/*  Create Preview ( AJAX )
/*-------------------------------------------------------------------------------*/
function easynotify_generate_preview () {
	
	if ( !isset( $_POST['post_ID'] ) && !isset( $_GET['noty_id'] )) {
		echo 'Failed to generate Preview! Please try again.';
		die;
		}
		
		$theval = array();
		$allval = array();
		
		if ( isset ( $_POST['post_ID'] ) ) {
			
			$thepost = intval( $_POST['post_ID'] );
			
			$_POST['enoty_meta'] = stripslashes_deep( $_POST['enoty_meta'] );
			
			 foreach ((array) $_POST['enoty_meta'] as $k => $v){
				 $allval[$k] = $v;
				 }
					easynotify_preview( $thepost, $allval );
				}
				
			elseif ( isset( $_GET['noty_id'] ) && easynotify_post_exists( intval( $_GET['noty_id'] ) ) ) {
				
				$thepost = intval( $_GET['noty_id'] ); 

				foreach ( get_post_meta( $_GET['noty_id'] ) as $k => $v){
					$theval[$k] = $v;
					
					foreach ( $theval as $k => $v){
						$tmp = get_post_meta( $_GET['noty_id'], $k, true );
						$allval[$k] = $tmp;
						}
					}

					easynotify_preview( $thepost, $allval );

				} else {
					die('Ooops!');
					}

		die('');
		
}
add_action('wp_ajax_easynotify_generate_preview', 'easynotify_generate_preview');


/*-------------------------------------------------------------------------------*/
/*  If Post/Page Exist
/*-------------------------------------------------------------------------------*/
function easynotify_post_exists( $id ) {
	return is_string( get_post_status( $id ) );
}
	
/*-------------------------------------------------------------------------------*/
/*  Slug to Name
/*-------------------------------------------------------------------------------*/
function easynotify_slug_to_name($slug) {
	$vals = array(
				"optin"=> "Opt-in ( Subscribe Form )",
				"socialbutton"=> "Social Sharing Buttons",
				"button"=> "Custom Text & Button",
				"none"=> "Disabled",
				"" => "None"
		);
	return $vals[$slug];	
}


/*-------------------------------------------------------------------------------*/
/*  Apply Individual Layout & Styles
/*-------------------------------------------------------------------------------*/	
function easynotify_apply_layout_style( $layout ) {
	wp_enqueue_style( 'enoty_enotybox_layout_'.$layout.'', ENOTIFY_URL .'/css/layouts/'.$layout.'.css' );
}

function easynotify_dynamic_styles( $id, $val = '', $type = '' ) {
	
			$getdata = array( 'pattern', 'overlaycol', 'overlayopct', 'headerback' );
			$data = easynotify_loader( $getdata, $id, $val, $type );

  			$pattopctymz = $data['overlayopct'] / 100;	

  echo '
       <style type="text/css">
            #fancybox-overlay {
				background: url('.ENOTIFY_URL.'/css/images/patterns/'.$data['pattern'].') !important; background-repeat: repeat;
				background-color:'.$data['overlaycol'].' !important;
				filter: alpha(opacity='.$data['overlayopct'].');
   				filter: progid:DXImageTransform.Microsoft.Alpha(opacity='.$data['overlayopct'].');
   				opacity:'.$pattopctymz.' !important;
   				-moz-opacity:'.$pattopctymz.'0 !important; 
				}
				
			.enoty-custom-wrapper, #fancybox-content, #fancybox-outer {
				background: #272727;
				background-image: -webkit-linear-gradient(top, #272727 0, #383838 30%, #383838 70%, #272727 100%);
				background-image: -moz-linear-gradient(top, #272727 0, #383838 30%, #383838 70%, #272727 100%);
				background-image: -ms-linear-gradient(top, #272727 0, #383838 30%, #383838 70%, #272727 100%);
				background-image: -o-linear-gradient(top, #272727 0, #383838 30%, #383838 70%, #272727 100%);
				background-image: linear-gradient(top, #272727 0, #383838 30%, #383838 70%, #272727 100%);
				}
					
			.noty-text-header {
				background: '.$data['headerback'].'; 
			}
				
       </style>
    ';
}


/*-------------------------------------------------------------------------------*/
/*   Admin Notifications
/*-------------------------------------------------------------------------------*/
function easynotify_admin_bar_menu(){
            global $wp_admin_bar;

            /* Add the main siteadmin menu item */
                $wp_admin_bar->add_menu( array(
                    'id'     => 'enoty-upgrade-bar',
                    'href' => 'https://ghozylab.com/plugins/pricing/#tab-1408601400-2-44',
                    'parent' => 'top-secondary',
					'title' => '<img src="'.plugins_url( 'images/enoty-cp-icon.png' , dirname(__FILE__) ).'" style="vertical-align:middle;margin-right:5px" alt="Upgrade Now!" title="Upgrade Now!" />Upgrade Easy Notify to PRO Version',
                    'meta'   => array('class' => 'enoty-upgrade-to-pro', 'target' => '_blank' ),
                ) );
}
if ( enoty_get_option( 'easynotify_disen_admnotify' ) == '1' ) {
	if ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'easynotify' ) {
		add_action( 'admin_bar_menu', 'easynotify_admin_bar_menu', 1000);
	}
}


/*-------------------------------------------------------------------------------*/
/*  WordPress Pointers 
/*-------------------------------------------------------------------------------*/
//add_action( 'admin_enqueue_scripts', 'easynotify_pointer_pointer_header' );
function easynotify_pointer_pointer_header() {
    $enqueue = false;

    $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

    if ( ! in_array( 'easynotify_pointer_pointer', $dismissed ) ) {
        $enqueue = true;
        add_action( 'admin_print_footer_scripts', 'easynotify_pointer_footer' );
    }

    if ( $enqueue ) {
        // Enqueue pointers
        wp_enqueue_script( 'wp-pointer' );
        wp_enqueue_style( 'wp-pointer' );
    }
}

function easynotify_pointer_footer() {
    $pointer_content = '<h3>Thank You!</h3>';
	  $pointer_content .= '<p>You&#39;ve just installed '.ENOTIFY_NAME.' Version. Click on <img src="'.plugins_url( 'images/help.png' , dirname(__FILE__) ).'" width="22" height="22" > icon to get short tutorial and user guide plugin.</p><p>To close this notify permanently just click dismiss button below.</p>';
?>

<script type="text/javascript">// <![CDATA[
jQuery(document).ready(function($) {
	
if (typeof(jQuery().pointer) != 'undefined') {	
    $('#fornotify').pointer({
        content: '<?php echo $pointer_content; ?>',
        position: {
            edge: 'right',
            align: 'center'
        },
        close: function() {
            $.post( ajaxurl, {
                pointer: 'easynotify_pointer_pointer',
               action: 'dismiss-wp-pointer'
            });
        }
    }).pointer('open');
	
}

});
// ]]></script>
<?php
}


/*-------------------------------------------------------------------------------*/
/*   DEMO Page
/*-------------------------------------------------------------------------------*/
function easynotify_demo_page() {
    $enoty_demo_page = add_submenu_page('edit.php?post_type=easynotify', 'DEMO', __('DEMO', 'easy-notify-lite'), 'edit_posts', 'easynotify_demo', 'easynotify_demo_video');
}
add_action( 'admin_menu', 'easynotify_demo_page' );


function easynotify_demo_video() {
	?>
    <div class="wrap">
        <div id="icon-edit" class="icon32 icon32-posts-easynotify"><br /></div>
        <h2><span class="dashicons dashicons-video-alt3" style="margin-right:7px;"></span><?php _e('Demo', 'easy-notify-lite'); ?></h2>
        <p><?php _e(' The Best Notify and Subscription Form Plugin to display notify popup, announcement and subscribe form with very ease, fancy and elegant.', 'easy-notify-lite'); ?></p>
        
<div class="metabox-holder" style="display:inline-block; width: 330px; max-width: 33%; float:<?php echo ( is_rtl() ? 'left' : 'right' ); ?>; vertical-align:top;">
			<div class="postbox">
            <h3><span class="dashicons dashicons-megaphone" style="margin-right:7px;"></span><?php _e( 'Check it Out!', 'easy-notify-lite' ); ?></h3>
            <?php easynotify_news_metabox(); ?>
           </div>
	</div>
 
<div class="metabox-holder" style="max-width:69%; display:block;">
			<div class="postbox">
				<h3><?php _e( 'Lite Version', 'easy-notify-lite' ); ?></h3>
        <div id="easynotify_lite_vid" style="padding:10px !important; ">
        <iframe width="640" height="360" src="//www.youtube.com/embed/Kss5x7f5DiY?rel=0" frameborder="0" allowfullscreen></iframe>
        </div>
       </div>
  </div>
  
<div class="metabox-holder" style="max-width:69%; display:block;">
			<div class="postbox">
				<h3><?php _e( 'PRO Version', 'easy-notify-lite' ); ?></h3>
        <div id="easynotify_pro_vid" style="padding:10px !important; ">
        <iframe width="640" height="360" src="//www.youtube.com/embed/Q0cW7bLzuhw?rel=0" frameborder="0" allowfullscreen></iframe>
        </div>
       </div>
  </div>
   
  </div> 
	<?php 
}


/*-------------------------------------------------------------------------------*/
/*  Create News MetaBox
/*-------------------------------------------------------------------------------*/
function easynotify_news_metabox () {
	$new = '<div style="text-align:center;">';
	$new .= '<a style="outline: none !important;" href="https://ghozylab.com/plugins/easy-media-gallery-pro/demo/best-gallery-and-photo-albums-demo/?utm_source=easynotifylite&utm_medium=demopage&utm_campaign=linkfromdemopage" target="_blank"><img style="cursor:pointer; margin-top: 7px; margin-bottom: 7px;" src="'.plugins_url( 'images/easy-media-gallery.png' , dirname(__FILE__) ).'" width="300" height="250" alt="Best Gallery Plugin!" ></a>';
	$new .= '<a style="outline: none !important;" href="http://goo.gl/divK5t" target="_blank"><img style="cursor:pointer; margin-top: 7px; margin-bottom: 7px;" src="'.plugins_url( 'images/best-contact-form-plugin.png' , dirname(__FILE__) ).'" width="300" height="188" alt="Best Gallery Plugin!" ></a>';
	$new .= '</div><br>';
echo $new;	
}

/*-------------------------------------------------------------------------------*/
/*   Comparison Page
/*-------------------------------------------------------------------------------*/
function easynotify_create_comparison_page() {
    $enoty_comparison_page = add_submenu_page('edit.php?post_type=easynotify', 'Comparison', __('UPGRADE to PRO', 'easy-notify-lite'), 'edit_posts', 'enoty_comparison', 'easynotify_comparison');
}
add_action( 'admin_menu', 'easynotify_create_comparison_page' );


/*-------------------------------------------------------------------------------*/
/*   Enqueue script/styles based on custom page
/*-------------------------------------------------------------------------------*/
function easynotify_enqueue_on_custom_page() {
	if ( is_admin() && isset( $_GET['page'] ) && $_GET['page'] == 'enoty_comparison' ){
		wp_enqueue_style( 'enoty-comparison-css' );	
		wp_enqueue_script( 'enoty-comparison-js' );
		}
}
add_action( 'admin_enqueue_scripts', 'easynotify_enqueue_on_custom_page' );

/*-------------------------------------------------------------------------------*/
/*   Generate Comparison Page
/*-------------------------------------------------------------------------------*/
function easynotify_comparison() {
?>



    <script>
        jQuery(document).ready(function ($){
            $(".column_1, .column_3, .column_2, .column_4").click(function (){
                $('html, body').animate({
                    scrollTop: $(".enotyscrollto").offset().top
                }, 1500);
            });
        });
    </script>

    <div class="wrap">
        <div id="icon-edit" class="icon32"><br /></div>
        <h2><?php _e('Comparison', 'easy-notify-lite'); ?></h2>      
  <div class="tsc_pricingtable03 tsc_pt3_style1" style="margin-bottom:110px; height:700px;">
    <div class="caption_column">
      <ul>
        <li class="header_row_1 align_center radius5_topleft"></li>
        <li class="header_row_2" style="text-align: center;"><h2 class="caption" style="display:inline-block; font-size: 25px; vertical-align:top; margin-top: 7px;">Easy Notify Lite</h2>
        </li> 
         <li class="row_style_2"><span>License</span></li>
         <li class="row_style_4"><span>Unlimited Notify</span></li>
         <li class="row_style_2"><span>Unlimited Colors</span></li>
         <li class="row_style_4"><span>Layouts</span></li>
         <li class="row_style_2"><span>Patterns</span></li>  
        <li class="row_style_4"><span>Basic Notify</span><a target="_blank" href="https://ghozylab.com/plugins/easy-notify-pro/demo/demo-basic-notify-and-a-simple-announcement/" style="text-decoration:underline !important;"> DEMO</a>&nbsp;&nbsp;<span class="newftr"></span></li>   
                      
        <li class="row_style_2"><span style="font-weight:bold; color:#1064EF">Popup Opt-in ( Subscribe Form )</span><a target="_blank" href="https://ghozylab.com/plugins/easy-notify-pro/demo/popup-opt-in-subscribe-form/" style="text-decoration:underline !important;"> DEMO</a>&nbsp;&nbsp;<span class="newftr"></span></li>
        
         <li class="row_style_4"><span style="font-weight:bold; color:#1064EF">Popup with Custom Text & Button</span><a target="_blank" href="https://ghozylab.com/plugins/easy-notify-pro/demo/demo-popup-with-custom-text-button/" style="text-decoration:underline !important;"> DEMO</a></li>
        
        <li class="row_style_2"><span style="font-weight:bold; color:#1064EF">Popup with Social Share Buttons</span><a target="_blank" href="https://ghozylab.com/plugins/easy-notify-pro/demo/demo-popup-with-social-share-buttons/" style="text-decoration:underline !important;"> DEMO</a></li>
        
        <li class="row_style_3"><span style="font-weight:bold; color:#1064EF">Popup with Video</span><a target="_blank" href="https://ghozylab.com/plugins/easy-notify-pro/demo/demo-popup-with-video/" style="text-decoration:underline !important;"> DEMO</a>&nbsp;&nbsp;<span class="newftr"></span></li>
        
        <li class="row_style_2"><span>Support all major email marketing</span><span class="newftr"></span></li>
        <li class="row_style_4"><span>WP Multisite</span></li>
        <li class="row_style_2"><span>Support</span></li>
        <li class="row_style_4"><span>Update</span></li>
        <li class="row_style_2"><span>License</span></li>
        <li class="footer_row enotyscrollto"></li>
      </ul>
    </div>
    <div class="column_1">
      <ul>
        <li class="header_row_1 align_center">
          <h2 class="col1">Lite</h2>
        </li>
        <li class="header_row_2 align_center">
          <h1 class="col1">Free</h1>
        </li>
        <li class="row_style_3 align_center">None</li>
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_3 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_1 align_center">2</li>
        <li class="row_style_3 align_center">3</li>
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_3 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_1 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_3 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_1 align_center"><span class="pricing_no"></span></li>
		<li class="row_style_3 align_center"><span class="pricing_no"></span></li>        
        <li class="row_style_1 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_3 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_3 align_center">None</li> 
        <li class="footer_row"></li>
      </ul>
    </div>
    
    <div class="column_2">
      <ul>
        <li class="header_row_1 align_center">
          <h2 class="col2">Pro</h2>
        </li>
        <li class="header_row_2 align_center">
          <h1 class="col2">$<span><?php echo ENOTY_PRO_PRICE; ?></span></h1>
        </li>
        <li class="row_style_4 align_center"><span style="font-weight: bold; color:#F77448; font-size:14px;">1 Site</span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center">6</li>
        <li class="row_style_4 align_center">18</li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
		<li class="row_style_4 align_center"><span class="pricing_yes"></span></li>        
        <li class="row_style_2 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_4 align_center"><span>1 Month</span></li>
        <li class="row_style_2 align_center"><span>1 Year</span></li>
        <li class="row_style_4 align_center"><span style="font-weight: bold; color:#F77448; font-size:14px;">1 Site</span></li>
        <li class="footer_row"><a target="_blank" href="https://ghozylab.com/plugins/ordernow.php?order=enotypro&utm_source=comparisonpage&utm_medium=pricingpage&utm_campaign=comparison" class="tsc_buttons2 blue">Upgrade Now</a></li>
      </ul>
    </div>    
    
    <div class="column_2 featured">
        <span class="bestbuy"></span>
      <ul>
        <li class="header_row_1 align_center">
          <h2 class="col2">Pro+</h2>
        </li>
        <li class="header_row_2 align_center">
          <h1 class="col2">$<span><?php echo ENOTY_PRO_PLUS_PRICE; ?></span></h1>
        </li>
        <li class="row_style_4 align_center"><span style="font-weight: bold; color:#F77448; font-size:14px;">3 Sites</span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center">6</li>
        <li class="row_style_4 align_center">18</li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
		<li class="row_style_4 align_center"><span class="pricing_yes"></span></li>        
        <li class="row_style_2 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_4 align_center"><span>1 Month</span></li>
        <li class="row_style_2 align_center"><span>1 Year</span></li>
        <li class="row_style_4 align_center"><span style="font-weight: bold; color:#F77448; font-size:14px;">3 Sites</span></li>
        <li class="footer_row"><a target="_blank" href="https://ghozylab.com/plugins/ordernow.php?order=enotyproplus&utm_source=comparisonpage&utm_medium=pricingpage&utm_campaign=comparison" class="tsc_buttons2 green">Upgrade Now</a></li>
      </ul>
    </div>
    <div class="column_2">
      <ul>
        <li class="header_row_1 align_center">
          <h2 class="col2">Pro++</h2>
        </li>
        <li class="header_row_2 align_center">
          <h1 class="col2">$<span><?php echo ENOTY_PRO_PLUS_PLUS_PRICE; ?></span></h1>
        </li>
        <li class="row_style_4 align_center"><span style="font-weight: bold; color:#F77448; font-size:14px;">5 Sites</span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center">6</li>
        <li class="row_style_4 align_center">18</li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
		<li class="row_style_4 align_center"><span class="pricing_yes"></span></li>        
        <li class="row_style_2 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_4 align_center"><span>1 Month</span></li>
        <li class="row_style_2 align_center"><span>1 Year</span></li>
        <li class="row_style_4 align_center"><span style="font-weight: bold; color:#F77448; font-size:14px;">5 Sites</span></li>
        <li class="footer_row"><a target="_blank" href="https://ghozylab.com/plugins/ordernow.php?order=enotyproplusplus&utm_source=comparisonpage&utm_medium=pricingpage&utm_campaign=comparison" class="tsc_buttons2 red">Upgrade Now</a></li>
      </ul>
    </div>    

    </div>
  </div>
<!-- DC Pricing Tables:3 End -->
<div class="tsc_clear"></div> <!-- line break/clear line -->

<?php
}


/*-------------------------------------------------------------------------------*/
/*   Rate Notice
/*-------------------------------------------------------------------------------*/
function easynotify_rate_notify() {
	global $post_type;
	
	if( 'easynotify' == $post_type ) {

		echo'<div class="updated"><div class="enoty_message"><img class="enoty_icon" title="" src="' . plugins_url( 'images/five-stars.png', dirname(__FILE__) ) . '" alt="Rate Us!"/><div class="enoty_text"><span>Have a story to share about <strong>'.ENOTIFY_NAME.'</strong> experience? We\'d love to hear your feedback and rate 5 stars would be appreciated!</span></div><a class="button enoty_button" href="http://wordpress.org/support/view/plugin-reviews/easy-notify-lite?filter=5#postform" target="_blank">RATE US NOW</a></div></div>';
	}
}

/*
if ( enoty_get_option( 'easynotify_disen_admnotify' ) == '1' ) {
	add_action( 'admin_notices', 'easynotify_rate_notify', 1 );
	}
*/


/*-------------------------------------------------------------------------------*/
/*  Update Notify
/*-------------------------------------------------------------------------------*/
function enoty_update_notify() {
	
    global $post;
		if ( !empty( $post ) && 'easynotify' === $post->post_type && is_admin() ) {
	
    ?>
    <div class="error enoty-setupdate">
        <p><?php echo 'We recommend you to enable plugin Auto Update so you\'ll get the latest features and other important updates from <strong>'.ENOTIFY_NAME.'</strong>.<br />Click <a href="#"><strong><span id="enotydoautoupdate">here</span></strong></a> to enable Auto Update.'; ?></p>
    </div>
    
<script type="text/javascript">
	/*<![CDATA[*/
	/* Easy Media Gallery */
jQuery(document).ready(function(){
	jQuery('#enotydoautoupdate').click(function(){
		var cmd = 'active';
		enoty_enable_auto_update(cmd);
	});

function enoty_enable_auto_update(act) {
	var data = {
		action: 'enoty_enable_auto_update',
		security: '<?php echo wp_create_nonce( "enoty-update-nonce"); ?>',
		cmd: act,
		};
		
		jQuery.post(ajaxurl, data, function(response) {
			if (response == 1) {
				alert('Great! Auto Update successfully activated.');
				jQuery('.enoty-setupdate').fadeOut('3000');
				}
				else {
				alert('Ajax request failed, please refresh your browser window.');
				}
				
			});
	}
	
});
	
/*]]>*/</script>
    
    <?php
	
	}
}

function enoty_enable_auto_update() {
	
	check_ajax_referer( 'enoty-update-nonce', 'security' );
	
	if ( !isset( $_POST['cmd'] ) ) {
		echo '0';
		wp_die();
		}
		
		else {
			if ( $_POST['cmd'] == 'active' ){
				$enoty_upd_opt = get_option('easynotify_opt');
				$enoty_upd_opt['easynotify_disen_autoupdt'] = '1';
				update_option('easynotify_opt', $enoty_upd_opt);	
				echo '1';			
				wp_die();
				}
	}
}
add_action( 'wp_ajax_enoty_enable_auto_update', 'enoty_enable_auto_update' );


/*-------------------------------------------------------------------------------*/
/*  Create Preview Metabox @since 1.1.13
/*-------------------------------------------------------------------------------*/
function easynotify_news_metabox_new() {
	$notyprev = '<div style="margin-left:5px;"><ul class="notycheckthisout">';
	$notyprev .= '<li><a href="https://ghozylab.com/plugins/easy-media-gallery-pro/demo/best-gallery-and-photo-albums-demo/" target="_blank">Best Gallery Plugin</a><span style="padding:2px 6px 2px 6px;background-color: #E74C3C; border-radius:9px;margin-left:7px;color:#fff;font-size:11px;">Best Seller</span></li>';
	$notyprev .= '<li><a href="http://demo.ghozylab.com/plugins/easy-contact-form-plugin/demo-form-with-image-in-header/" target="_blank">Best Contact Form Plugin</a></li>';
	$notyprev .= '<li><a href="http://demo.ghozylab.com/plugins/easy-image-slider-plugin/image-slider-with-thumbnails-at-the-bottom/" target="_blank">Best Image Slider Plugin</a></li>';
	$notyprev .= '</ul></div>';
echo $notyprev;	
}

function easynotify_css_compress( $minify ) {
	
	/* remove comments */
	$minify = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $minify );
	
	/* remove tabs, spaces, newlines, etc. */
	$minify = str_replace( array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $minify );
	
	return $minify;
		
}