<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
function foxtool_options_page() {
	global $foxtool_options;
	ob_start(); 
	?>
	<div class="wrap ft-wrap">
	  <div class="ft-box">
	  
		<div class="ft-menu">
			<div class="ft-logo"><?php foxtool_logo(); ?></div>
			<button class="sotab sotab-select" onclick="fttab(event, 'tab1')"><i class="fa-regular fa-gauge-max"></i> <?php _e('OPTIMIZE', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab2')"><i class="fa-regular fa-shield-halved"></i> <?php _e('SECURITY', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab3')"><i class="fa-regular fa-toolbox"></i> <?php _e('TOOL', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab4')"><i class="fa-regular fa-desktop"></i> <?php _e('DISPLAY', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab5')"><i class="fa-regular fa-image"></i> <?php _e('MEDIA', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab6')"><i class="fa-regular fa-thumbtack"></i> <?php _e('POST', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab7')"><i class="fa-regular fa-envelope"></i> <?php _e('MAIL', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab8')"><i class="fa-regular fa-cart-shopping"></i> <?php _e('WOO', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab9')"><i class="fa-regular fa-user"></i> <?php _e('USER', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab10')"><i class="fa-brands fa-wordpress-simple"></i> <?php _e('CUSTOM', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab11')"><i class="fa-brands fa-google"></i> <?php _e('GOOGLE', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab12')"><i class="fa-regular fa-message-lines"></i> <?php _e('CHAT', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab13')"><i class="fa-regular fa-brackets-square"></i> <?php _e('SHORTCODE', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab14')"><i class="fa-regular fa-bell"></i> <?php _e('NOTIFY', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab15')"><i class="fa-regular fa-rectangle-ad"></i> <?php _e('ADS', 'foxtool'); ?></button>
			<button <?php if(isset($foxtool_options['foxsethi'])){echo 'style="display:none"';} ?> class="sotab" onclick="fttab(event, 'tab-ft1')"><i class="fa-regular fa-gear-complex"></i> <?php _e('SETTING', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab-ft2')"><i class="fa-regular fa-bomb"></i> <?php _e('ABOUT', 'foxtool'); ?></button>
		</div>

		<div class="ft-main">
			<?php 
			if( isset($_GET['settings-updated']) ) { 
				echo '<div class="ft-updated">'. __('Settings saved', 'foxtool'). '</div>';   
			}
			?>
			<form method="post" action="options.php">
			<?php settings_fields('foxtool_settings_group'); ?> 
			<!-- trang toi uu -->
			<div class="sotab-box ftbox" id="tab1">
				<?php include( FOXTOOL_DIR . 'main/page/1speed.php'); ?>
			</div>
			<!-- trang bao mat -->
			<div class="sotab-box ftbox" id="tab2" style="display:none">	
				<?php include( FOXTOOL_DIR . 'main/page/2scuri.php'); ?>
			</div>
			<!-- trang cong cu -->
			<div class="sotab-box ftbox" id="tab3" style="display:none">	
				<?php include( FOXTOOL_DIR . 'main/page/3tool.php'); ?>
			</div>
			<!-- trang hien thi -->
			<div class="sotab-box ftbox" id="tab4" style="display:none">
				<?php include( FOXTOOL_DIR . 'main/page/4main.php'); ?>
			</div>
			<!-- trang media -->
			<div class="sotab-box ftbox" id="tab5" style="display:none">	
				<?php include( FOXTOOL_DIR . 'main/page/5media.php'); ?>
			</div>
			<!-- trang chuyen muc bai viet -->
			<div class="sotab-box ftbox" id="tab6" style="display:none">	
				<?php include( FOXTOOL_DIR . 'main/page/6post.php'); ?>
			</div>
			<!-- trang mail -->
			<div class="sotab-box ftbox" id="tab7" style="display:none">	
				<?php include( FOXTOOL_DIR . 'main/page/7mail.php'); ?>
			</div>
			<!-- trang woocommere -->
			<div class="sotab-box ftbox" id="tab8" style="display:none">	
				<?php include( FOXTOOL_DIR . 'main/page/8woo.php'); ?>
			</div>
			<!-- trang user -->
			<div class="sotab-box ftbox" id="tab9" style="display:none">	
				<?php include( FOXTOOL_DIR . 'main/page/9user.php'); ?>
			</div>
			<!-- trang custom -->
			<div class="sotab-box ftbox" id="tab10" style="display:none">
				<?php include( FOXTOOL_DIR . 'main/page/10custom.php'); ?>
			</div>
			<!-- trang goole -->
			<div class="sotab-box ftbox" id="tab11" style="display:none">
				<?php include( FOXTOOL_DIR . 'main/page/11google.php'); ?>
			</div>
			<!-- trang chat -->
			<div class="sotab-box ftbox" id="tab12" style="display:none">
				<?php include( FOXTOOL_DIR . 'main/page/12chat.php'); ?>
			</div>
			<!-- Shortcode -->
			<div class="sotab-box ftbox" id="tab13" style="display:none">
				<?php include( FOXTOOL_DIR . 'main/page/13shortcode.php'); ?>
			</div>
			<!-- thong bao -->
			<div class="sotab-box ftbox" id="tab14" style="display:none">
				<?php include( FOXTOOL_DIR . 'main/page/14notify.php'); ?>
			</div>
			<!-- quang cao -->
			<div class="sotab-box ftbox" id="tab15" style="display:none">
				<?php include( FOXTOOL_DIR . 'main/page/15ads.php'); ?>
			</div>
			<!-- trang cai dat -->
			<div class="sotab-box ftbox" id="tab-ft1" style="display:none">
				<?php include( FOXTOOL_DIR . 'main/page/ft-setting.php'); ?> 
			</div>
			<!-- trang thong tin -->
			<div class="sotab-box ftbox" id="tab-ft2" style="display:none">
				<?php include( FOXTOOL_DIR . 'main/page/ft-about.php'); ?> 
			</div>
			<div class="ft-submit">
				<button type="submit"><i class="fa-regular fa-floppy-disk"></i> <?php _e('SAVE CONTENT', 'foxtool'); ?></button>
			</div>
				<button id="ft-save-fast" type="submit"><i class="fa-regular fa-floppy-disk"></i></button>
			</form>
		</div>
		
	  </div>
	  <div class="ft-sidebar">
		<?php include( FOXTOOL_DIR . 'main/page/ft-aff.php'); ?>
	  </div>
		
	</div>
	<script>
	jQuery(document).ready(function($) {
		$('form input[type="checkbox"]').change(function() {
			var currentForm = $(this).closest('form');
			$.ajax({
				type: 'POST',
				url: currentForm.attr('action'), 
				data: currentForm.serialize(), 
				success: function(response) {
					console.log('Turn on successfully');
				},
				error: function() {
					console.log('Error in AJAX request');
				}
			});
		});
	});
	</script>
	<?php
	// style foxtool
	require_once( FOXTOOL_DIR . 'main/style.php');
	echo ob_get_clean();
}
function foxtool_tool_add_options_link() {
	global $foxtool_options;
	$icon = foxtool_icon();
	$name = !empty($foxtool_options['foxtool6']) ? $foxtool_options['foxtool6'] : 'Foxtool';
	add_menu_page($name, $name, 'manage_options', 'foxtool-options', 'foxtool_options_page', $icon, 70);
}
add_action('admin_menu', 'foxtool_tool_add_options_link');
function foxtool_tool_register_settings() {
	register_setting('foxtool_settings_group', 'foxtool_settings');
}
add_action('admin_init', 'foxtool_tool_register_settings');

