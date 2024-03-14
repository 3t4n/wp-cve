<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
function foxtool_code_options_page() {
	global $foxtool_code_options;
	ob_start(); 
	?>
	<div class="wrap ft-wrap">
	  <div class="ft-box">
	  
		<div class="ft-menu">
			<div class="ft-logo"><?php foxtool_logo(); ?></div>
			<button class="sotab sotab-select" onclick="fttab(event, 'tab1')"><i class="fa-brands fa-css3"></i> <?php _e('CSS', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab2')"><i class="fa-regular fa-code"></i> <?php _e('WP HEAD', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab3')"><i class="fa-regular fa-code"></i> <?php _e('WP BODY', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab4')"><i class="fa-regular fa-code"></i> <?php _e('WP FOOTER', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab5')"><i class="fa-regular fa-arrow-right-to-bracket"></i> <?php _e('WP LOGIN', 'foxtool'); ?></button>
		</div>

		<div class="ft-main">
			<?php 
			if( isset($_GET['settings-updated']) ) { 
				echo '<div class="ft-updated">'. __('Settings saved', 'foxtool'). '</div>';   
			}
			?>
			<form method="post" action="options.php">
			<?php settings_fields('foxtool_code_settings_group'); ?> 
			<!-- CSS -->
			<div class="sotab-box ftbox" id="tab1">
			<h2><?php _e('CSS', 'foxtool'); ?></h2>
			<div class="ft-card">
			  <h3><i class="fa-regular fa-code"></i> <?php _e('Add CSS to your website', 'foxtool') ?></h3>
				<p>
				<textarea class="ft-code-textarea fox-codex" name="foxtool_code_settings[code1]" placeholder="<?php _e('Enter CSS here', 'foxtool'); ?>"><?php if(!empty($foxtool_code_options['code1'])){echo esc_textarea($foxtool_code_options['code1']);} ?></textarea>
				</p>
			  <h3><i class="fa-regular fa-code"></i> <?php _e('Add CSS for tablet size', 'foxtool') ?></h3>
				<p>
				<textarea class="ft-code-textarea fox-codex" name="foxtool_code_settings[code11]" placeholder="<?php _e('Enter CSS here', 'foxtool'); ?>"><?php if(!empty($foxtool_code_options['code11'])){echo esc_textarea($foxtool_code_options['code11']);} ?></textarea>
				</p>
			  <h3><i class="fa-regular fa-code"></i> <?php _e('Add CSS for mobile size', 'foxtool') ?></h3>
				<p>
				<textarea class="ft-code-textarea fox-codex" name="foxtool_code_settings[code12]" placeholder="<?php _e('Enter CSS here', 'foxtool'); ?>"><?php if(!empty($foxtool_code_options['code12'])){echo esc_textarea($foxtool_code_options['code12']);} ?></textarea>
				</p>
			</div>	
			</div>
			<!-- Javascript 1 -->
			<div class="sotab-box ftbox" id="tab2" style="display:none">
			<h2><?php _e('WP HEAD', 'foxtool'); ?></h2>
			<div class="ft-card">
			  <h3><i class="fa-regular fa-code"></i> <?php _e('Add code to WP head', 'foxtool') ?></h3>
				<p>
				<textarea class="ft-code-textarea" name="foxtool_code_settings[code2]" placeholder="<?php _e('Enter code here', 'foxtool'); ?>"><?php if(!empty($foxtool_code_options['code2'])){echo esc_textarea($foxtool_code_options['code2']);} ?></textarea>
				</p>
			</div>
			</div>
			<!-- Javascript 2 -->
			<div class="sotab-box ftbox" id="tab3" style="display:none">
			<h2><?php _e('WP BODY', 'foxtool'); ?></h2>
			<div class="ft-card">
			  <h3><i class="fa-regular fa-code"></i> <?php _e('Add code to WP body', 'foxtool') ?></h3>
				<p>
				<textarea class="ft-code-textarea" name="foxtool_code_settings[code3]" placeholder="<?php _e('Enter code here', 'foxtool'); ?>"><?php if(!empty($foxtool_code_options['code3'])){echo esc_textarea($foxtool_code_options['code3']);} ?></textarea>
				</p>
			</div>
			</div>
			<!-- Javascript 3 -->
			<div class="sotab-box ftbox" id="tab4" style="display:none">
			<h2><?php _e('WP FOOTER', 'foxtool'); ?></h2>
			<div class="ft-card">
			  <h3><i class="fa-regular fa-code"></i> <?php _e('Add code to WP footer', 'foxtool') ?></h3>
				<p>
				<textarea class="ft-code-textarea" name="foxtool_code_settings[code4]" placeholder="<?php _e('Enter code here', 'foxtool'); ?>"><?php if(!empty($foxtool_code_options['code4'])){echo esc_textarea($foxtool_code_options['code4']);} ?></textarea>
				</p>
			</div>	
			</div>
			<!-- login 1 -->
			<div class="sotab-box ftbox" id="tab5" style="display:none">
			<h2><?php _e('WP LOGIN', 'foxtool'); ?></h2>
			<div class="ft-card">
			  <h3><i class="fa-regular fa-code"></i> <?php _e('Add code to WP login', 'foxtool') ?></h3>
				<p>
				<textarea class="ft-code-textarea" name="foxtool_code_settings[code5]" placeholder="<?php _e('Enter code here', 'foxtool'); ?>"><?php if(!empty($foxtool_code_options['code5'])){echo esc_textarea($foxtool_code_options['code5']);} ?></textarea>
				</p>
			</div>	
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
	<?php
	// style foxtool
	require_once( FOXTOOL_DIR . 'main/style.php');
	echo ob_get_clean();
}
function foxtool_code_options_link() {
	add_submenu_page ('foxtool-options', 'Code', 'Code', 'manage_options', 'foxtool-code-options', 'foxtool_code_options_page');
}
add_action('admin_menu', 'foxtool_code_options_link');
function foxtool_code_register_settings() {
	register_setting('foxtool_code_settings_group', 'foxtool_code_settings');
}
add_action('admin_init', 'foxtool_code_register_settings');

