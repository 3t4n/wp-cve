<?php
/*
* Plugin Name: Required Fields
* Plugin URI: https://wordpress.org/plugins/required-fields/
* Description: Required Fields can help you write your Posts, Pages without forgetting fields, if you forget something you'll be alerted about that!
* Version: 1.9.5
* Author: NikosTsolakos
* Author URI: https://profiles.wordpress.org/nikostsolakos/#content-plugins
* License: GPLv2
*/

/*
*	1. Plugin Activation / De-Activation
*	2. Admin Settings
*/

// =====
// 1. Plugin Activation / De-Activation
// =====

// Activation Start

register_activation_hook( __FILE__, "rf_activated");
function rf_activated()
{
	$default_settings = array(
        'rf_enabled_settings' 		=> '',
        'rf_for_page_enabled'		=> '',
        'rf_save_draft' 			=> '',
		'rf_image_for_page' 		=> '',
		'rf_title_for_page' 		=> '',
		'rf_title_settings' 		=> '',
		'rf_excerpt_settings' 		=> '',
		'rf_category_settings' 		=> '',
		'rf_tag_settings'	 		=> '',
		'rf_image_settings' 		=> '',
		'rf_title_error' 			=> 'Title is required',
		'rf_excerpt_error' 			=> 'Excerpt is required',
		'rf_cat_error' 				=> 'Categories are required',
		'rf_tag_error' 				=> 'Please set less one tag',
		'rf_img_error' 				=> 'Post Thumbnail is required'
    );
	add_option("rf_settings", $default_settings);
}
// Activation End

// De-Activation Start
register_deactivation_hook(__FILE__, 'rf_deactivated');
function rf_deactivated()
{
	delete_option( 'rf_settings' );
}
// De-Activation End

// =====
// 2. Admin Settings
// =====

function rf_settings_init()
{
	register_setting('rf_settings', 'rf_settings', 'rf_settings_validate');
	// Main Section
	add_settings_section('rf_main_section', 'Required Fields For Posts', 'rf_main_section_text', 'rf_main_section_text');
	// Fields Of Main Section
	add_settings_field('rf_title_settings', 'Set Title Required:', 'rf_title_settings', __FILE__, 'rf_main_section');
	add_settings_field('rf_excerpt_settings', 'Set Excerpt Required:', 'rf_excerpt_settings', __FILE__, 'rf_main_section');
	add_settings_field('rf_category_settings', 'Set Categories Required:', 'rf_category_settings', __FILE__, 'rf_main_section');
	add_settings_field('rf_image_settings', 'Set Featured Image Required:', 'rf_image_settings', __FILE__, 'rf_main_section');
	add_settings_field('rf_tag_settings', 'Set Tags Required:', 'rf_tag_settings', __FILE__, 'rf_main_section');
	// Save Draft
	add_settings_section('rf_save_draft_section', 'Save Drafts', 'rf_save_draft_text', 'rf_save_draft_text');
	// Fields of Save Draft
	add_settings_field('rf_save_draft', 'Save Drafts:', 'rf_save_draft', __FILE__, 'rf_save_draft_section');
	
	// Error Logs
	add_settings_section('rf_error_logs', 'Set Error Alerts', 'rf_error_logs_text', 'rf_error_logs_text');
	// Fields Of Error logs
	add_settings_field('rf_title_error', 'Set Error For Title:', 'rf_title_error', __FILE__, 'rf_error_logs');
	add_settings_field('rf_excerpt_error', 'Set Error For Excerpt:', 'rf_excerpt_error', __FILE__, 'rf_error_logs');
	add_settings_field('rf_cat_error', 'Set Error For Categories:', 'rf_cat_error', __FILE__, 'rf_error_logs');
	add_settings_field('rf_tag_error', 'Set Error For Tags:', 'rf_tag_error', __FILE__, 'rf_error_logs');
	add_settings_field('rf_img_error', 'Set Error For Post thumbnail:', 'rf_img_error', __FILE__, 'rf_error_logs');
	// For Page
	add_settings_section('rf_for_page', 'Required Fields For Page', 'rf_for_page_text', 'rf_for_page_text');
	// Fields For Page
	add_settings_field('rf_for_page_enabled', 'Set Required Fields:', 'rf_for_page_enabled', __FILE__, 'rf_for_page');
	add_settings_field('rf_image_for_page', 'Image For Page:', 'rf_image_for_page', __FILE__, 'rf_for_page');
	add_settings_field('rf_title_for_page', 'Tag For Page:', 'rf_title_for_page', __FILE__, 'rf_for_page');

}
// Add rf_settings_init to Admin Section
add_action('admin_init', 'rf_settings_init' );


// Functions Of Fields Start

function rf_settings_validate($input) {
	return $input; 
}

include('includes/functions.php');

// Functions Of Fields End

// Set Plugin To Admin Menu
add_action('admin_menu', 'rf_admin_actions');

function rf_admin_panel()
{
	if ( !current_user_can( 'manage_options' ) ) 
	{
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	$opt = get_option('rf_settings');
	
	//Get Plugins Version
	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_version = preg_replace("/\s+/","",$plugin_data['Version']);
	
	if( !isset($opt['rf_enabled_settings']) )
	{
		$bgoff 	= 'background: #f1f1f1;';
		$ES_setoff = 'style="'.$bgoff.' cursor: no-drop;"';
		$ES_pointer_events	= 'style="pointer-events: none;"';
	} else {
		$ES_setoff = '';
		$ES_pointer_events	= '';
	}
	if( !isset($opt['rf_for_page_enabled']) )
	{
		$bgoff 	= 'background: #f1f1f1;';
		$PE_setoff = $bgoff.' cursor: no-drop;';
		$PE_pointer_events	= 'style="pointer-events: none;"';
	} else {
		$PE_setoff = '';
		$PE_pointer_events	= '';
	}
?>
	
	<div class="wrap" id="required_fields">
		<style><?php require_once('css/style.css');?></style>
		<form action="options.php" method="post">
			<?php settings_fields('rf_settings'); ?>
			<div id="poststuff">
				<div class="title_box">
					<img src="<?php echo plugins_url( 'images/cover.png', __FILE__ ); ?>"/>						
				</div>	
				<div class="postbox half floatleft">
					<h2>Active / De-Active</h2>
						<div class="rf_de_active_section">
							<!-- For Posts -->	
							<div class="rf_main_section">
								<?php rf_enabled_settings();?>
							</div>
								
							<!-- For Pages -->	
							<div class="rf_main_section">
								<?php rf_for_page_enabled();?>
							</div>
						</div>
				</div>
				
				<!-- For Page -->
				<div class="postbox half floatright" style="width: 49%; <?php echo $PE_setoff;?>">
					<div class="rf_frpage_section" <?php echo $PE_pointer_events;?>>
						<?php do_settings_sections('rf_for_page_text'); ?>
					</div>
				</div>
				
				<div class="postbox floatleft widthfull">
					<div class="rf_de_active_section">
						<!-- For Drafts -->	
						<div class="rf_main_section">
							<?php rf_save_draft_text();?>
						</div>
					</div>
				</div>
				
				<div class="postbox half floatleft" <?php echo $ES_setoff; ?>>
					<div class="rf_main_section" <?php echo $ES_pointer_events; ?>>
						<?php do_settings_sections('rf_main_section_text'); ?>
					</div>
				</div>
				
				
				<div class="postbox half floatright" style="width: 49%;">
					<div class="rf_error_section">
						<?php do_settings_sections('rf_error_logs_text'); ?>
					</div>
				</div>
				<div class="postbox btns_manage">
					<div class="floatright rf_footer">
						<div class="donate">
							<a href="https://paypal.me/NikosTsolakos" target="_blank">
								<img border="0" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif"/>
							</a>
						</div>
						
						<div class="btns">
							<input id="submit-rf-options" name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
						</div>
						
						<div class="btns">
							<a class="button-secondary" href="https://wordpress.org/support/view/plugin-reviews/required-fields" target="_blank"><?php _e('Rate Plugin'); ?></a>
						</div>
						<div class="btns">
							<a class="button-secondary green" href="https://wordpress.org/support/plugin/required-fields" target="_blank"><?php _e('Found bug?'); ?></a>
						</div>
					</div>
					<div class="floatleft rf_footer">
						<div class="rf_mng_footer">
							<img id="rf_img" src="<?php echo plugins_url( 'images/logo.png', __FILE__ ); ?>"/>
							<p>Required Fields <?php echo $plugin_version;?></p>
						</div>
					</div>
				</div>
				<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
				
			</div>
		</form>
	</div>
<?php }

function rf_admin_actions() {
	add_options_page("Required Fields Options", "Required Fields", 'manage_options', "Required_Fields", "rf_admin_panel");
}

function rf_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=Required_Fields">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'rf_settings_link' );

// =====
// 3. Frontend
// =====
function wp_rf_sc()
{
	$opt = get_option('rf_settings');
	wp_enqueue_script('jquery');
}
add_action( 'wp_enqueue_script', 'wp_rf_sc' );
	// Check Functions And Run
function required_fields()
	{
		$opt = get_option('rf_settings');
		if( !isset($opt['rf_enabled_settings']) )
		{
			echo '';
		}
		else
		{
			$rf_style = "{'background':'#FFEBE8', 'border':'#CC0000 solid 1px'}";
			
			/************************************************************ TITLE ************************************************************/
			if ( isset($opt['rf_title_settings']) )
			{	
				if (isset($opt['rf_for_page_enabled']) && isset($opt['rf_title_for_page']))
				{
					global $typenow;
					if(in_array($typenow, array('post','page')))
					{	
						echo '<!-- Required Fields -->';
						echo "<script type='text/javascript'>\n";
						  echo "
						  jQuery('#publish"; if (isset($opt['rf_save_draft'])) {echo ", #save-post";} echo "').click(function(){
								var ppppost = jQuery('input[id^=\"title\"]');
								if (ppppost.val().length < 1)
								{
									jQuery('[name^=\"post_title\"]').css( ".$rf_style." );
									setTimeout(\"jQuery('#ajax-loading').css('visibility', 'hidden');\", 100);
									alert('".$opt['rf_title_error']."');
									setTimeout(\"jQuery('#publish').removeClass('button-primary-disabled');\", 100);
									return false;
								} else {
									return true;
								}
							});
						  ";
						echo "</script>\n";
					}
					
				} else {
					global $post_type;
					if($post_type == 'post')
					{	
						echo '<!-- Required Fields -->';
						echo "<script type='text/javascript'>\n";
						  echo "
						  jQuery('#publish"; if (isset($opt['rf_save_draft'])) {echo ", #save-post";} echo "').click(function(){
								var post = jQuery('input[id^=\"title\"]');
								if (post.val().length < 1)
								{
									jQuery('[name^=\"post_title\"]').css( ".$rf_style." );
									setTimeout(\"jQuery('#ajax-loading').css('visibility', 'hidden');\", 100);
									alert('".$opt['rf_title_error']."');
									setTimeout(\"jQuery('#publish').removeClass('button-primary-disabled');\", 100);
									return false;
								} else {
									return true;
								}
							});
						  ";
						echo "</script>\n";
					}
				}
			}
			/************************************************************ /TITLE ************************************************************/
			
			/************************************************************ EXCERPT ***********************************************************/
			if ( isset( $opt['rf_excerpt_settings'] ) )
			{
				global $post_type;
				if($post_type == 'post')
				{
					echo '<!-- Required Fields -->';
					echo "<script type='text/javascript'>\n";
						  echo "
						  jQuery('#publish"; if (isset($opt['rf_save_draft'])) {echo ", #save-post";} echo "').click(function(){
								var ex_testervar = jQuery('[id^=\"postexcerpt\"]').find('#excerpt');
								if (ex_testervar.val().length < 1)
								{
									jQuery('[id^=\"postexcerpt\"]').css( ".$rf_style." );
									setTimeout(\"jQuery('#ajax-loading').css('visibility', 'hidden');\", 100);
									alert('".$opt['rf_excerpt_error']."');
									setTimeout(\"jQuery('#publish').removeClass('button-primary-disabled');\", 100);
									return false;
								}
							});
						  ";
					echo "</script>\n";
				}
			}
			/************************************************************ /EXCERPT ***********************************************************/
			
			/************************************************************ CATEGORIES ********************************************************/
			if ( isset($opt['rf_category_settings']) )
			{
				global $post_type;
				if($post_type=='post')
				{
					echo '<!-- Required Fields -->';
					echo "<script>
							jQuery(function($){
								$('#publish"; if (isset($opt['rf_save_draft'])) {echo ", #save-post";} echo "').click(function(e){
									if($('#taxonomy-category input:checked').length==0){
										jQuery('[id^=\"categorydiv\"]').css( ".$rf_style." );
										alert('" . __(''.$opt['rf_cat_error'].'', 'require-post-category') . "');
										e.stopImmediatePropagation();
										return false;
									}else{
										return true;
									}
								});
								var publish_click_events = $('#publish').data('events').click;
								if(publish_click_events){
									if(publish_click_events.length>1){
										publish_click_events.unshift(publish_click_events.pop());
									}
								}
								if($('#save-post').data('events') != null){
									var save_click_events = $('#save-post').data('events').click;
									if(save_click_events){
									  if(save_click_events.length>1){
										  save_click_events.unshift(save_click_events.pop());
									  }
									}
								}
							});
							</script>";
				}
			}
			/************************************************************ /CATEGORIES ************************************************************/
			
			/************************************************************ FEATURED IMAGE *********************************************************/
			if ( isset($opt['rf_image_settings']) )
			{
				if (isset($opt['rf_for_page_enabled']) && isset($opt['rf_image_for_page']))
				{
					global $typenow;
					if(in_array($typenow, array('post','page')))
					{
						echo '<!-- Required Fields -->';
						echo "<script language='javascript' type='text/javascript'>
							jQuery(function($){
								$('#publish"; if (isset($opt['rf_save_draft'])) {echo ", #save-post";} echo "').click(function(e){
									if (jQuery('#set-post-thumbnail').find('img').size() < 1) {
										jQuery('[id^=\'postimagediv\']').css( ".$rf_style." );
										alert('".$opt['rf_img_error']."');
										jQuery('#ajax-loading').hide();
										jQuery('#publish"; if (isset($opt['rf_save_draft'])) {echo ", #save-post";} echo "').removeClass('button-primary-disabled');
										return false;
									}
								});
							});
						</script>";
					}
				}
				else
				{
					global $typenow;
					if(in_array($typenow, array('post')))
					{
						echo '<!-- Required Fields -->';
						echo "<script language='javascript' type='text/javascript'>
							jQuery(function($){
								$('#publish"; if (isset($opt['rf_save_draft'])) {echo ", #save-post";} echo "').click(function(e){
									if (jQuery('#set-post-thumbnail').find('img').size() < 1) {
										jQuery('[id^=\'postimagediv\']').css( ".$rf_style." );
										alert('".$opt['rf_img_error']."');
										jQuery('#ajax-loading').hide();
										jQuery('#publish"; if (isset($opt['rf_save_draft'])) {echo ", #save-post";} echo "').removeClass('button-primary-disabled');
										return false;
									}
								});
							});
						</script>";
					}
				}
			}
			/************************************************************ /FEATURED IMAGE *************************************************/
			
			/************************************************************ TAGS ************************************************************/
			if ( isset($opt['rf_tag_settings']) ) 
			{
				global $post_type;
				if($post_type=='post')
				{
					echo '<!-- Required Fields -->';
					echo "<script>
					jQuery(function($){
						$('#publish"; if ( isset( $opt['rf_save_draft'] ) ) {echo ", #save-post";} echo "').click(function(e){
							if($('#post_tag .tagchecklist span').length==0){
								jQuery('[id^=\"tagsdiv-post_tag\"]').css( ".$rf_style." );
								alert('".$opt['rf_tag_error']."');
								e.stopImmediatePropagation();
								return false;
							}else{
								return true;
							}
						});
						var publish_click_events = $('#publish').data('events').click;
						if(publish_click_events){
							if(publish_click_events.length>1){
								publish_click_events.unshift(publish_click_events.pop());
							}
						}
						if($('#save-post').data('events') != null){
							var save_click_events = $('#save-post').data('events').click;
							if(save_click_events){
							  if(save_click_events.length>1){
								  save_click_events.unshift(save_click_events.pop());
							  }
							}
						}
					});
					</script>";
				}
			}
			/************************************************************ /TAGS ************************************************************/
		}
	}
	add_action('admin_footer-post.php', 'required_fields');
	add_action('admin_footer-post-new.php', 'required_fields');
?>