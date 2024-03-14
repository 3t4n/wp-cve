<?php
/**
 * Plugin Name: Font Size
 * Plugin URI: http://www.seosthemes.com
 * Contributors: seosbg
 * Author: seosbg
 * Description: Font Size WordPress plugin allows you to change the size of basic HTML elements.
 * Version: 1.2.0
 * License: GPL2
 */

add_action('admin_menu', 'font_size_menu');
function font_size_menu() {
    add_menu_page('Font Size', 'Font Size', 'administrator', 'font-size-settings', 'font_size_settings_page', plugins_url('font-size/images/icon.png')
    );

    add_action('admin_init', 'font_size_register_settings');
}

function font_size_register_settings() {
    register_setting('font-size-settings', 'font_size_site_title');
    register_setting('font-size-settings', 'font_size_navigation');
    register_setting('font-size-settings', 'font_size_body');
    register_setting('font-size-settings', 'font_size_sidebar');
    register_setting('font-size-settings', 'font_size_footer');
    register_setting('font-size-settings', 'font_size_h1');
}


function font_size_settings_page() {
?>

    <div class="wrap font-size">
		<h1><?php _e('Font Size', 'font-size'); ?></h1>
        <form action="options.php" method="post" role="form" name="font-size-form">
		
			<?php settings_fields( 'font-size-settings' ); ?>
			<?php do_settings_sections( 'font-size-settings' ); ?>
		
			<h2><a href="http://seosthemes.com/font-size/"><?php _e('Read more - Font Size', 'font-size'); ?></a></h2>

			
			<!-- ------------------- Site Title Font Size ------------------ -->							
			<div class="form-group">
				<label><?php _e('Site Title Font Size', 'font-size'); ?></label>
				<input placeholder="Set Font Size" class="form-control" style="width: 110px;" type="text" name="font_size_site_title" value="<?php if (esc_html(get_option( 'font_size_site_title'))) : echo esc_html(get_option( 'font_size_site_title')); endif; ?>"/>px
			</div>
								
											
			<!-- ------------------- Navigation Font Size ------------------ -->
			<div class="form-group">
				<label><?php _e('Navigation Font Size', 'font-size'); ?></label>
				<input placeholder="Set Font Size" class="form-control" style="width: 110px;" type="text" name="font_size_navigation" value="<?php if (esc_html(get_option( 'font_size_navigation'))) : echo esc_html(get_option( 'font_size_navigation')); endif; ?>"/>px
			</div>									
								
			<!-- ------------------- Body Element Font Size ------------------ -->
			<div class="form-group">
				<label><?php _e('Body Font Size', 'font-size'); ?></label>
				<input placeholder="Set Font Size" class="form-control" style="width: 110px;" type="text" name="font_size_body" value="<?php if (esc_html(get_option( 'font_size_body'))) : echo esc_html(get_option( 'font_size_body')); endif; ?>"/>px
			</div>		
			
			<!-- ------------------- Sidebar Element Font Size ------------------ -->
			<div class="form-group">
				<label><?php _e('Sidebar Font Size', 'font-size'); ?></label>
				<input placeholder="Set Font Size" class="form-control" style="width: 110px;" type="text" name="font_size_sidebar" value="<?php if (esc_html(get_option( 'font_size_sidebar'))) : echo esc_html(get_option( 'font_size_sidebar')); endif; ?>"/>px
			</div>								
								
			<!-- ------------------- Footer Element Font Size ------------------ -->
			
			<div class="form-group">
				<label><?php _e('Footer Font Size', 'font-size'); ?></label>
				<input placeholder="Set Font Size" class="form-control" style="width: 110px;" type="text" name="font_size_footer" value="<?php if (esc_html(get_option( 'font_size_footer'))) : echo esc_html(get_option( 'font_size_footer')); endif; ?>"/>px
			</div>
			
			<!-- ------------------- H1 Element Font Size ------------------ -->
			
			<div class="form-group">
				<label><?php _e('H1 Element Font Size', 'font-size'); ?></label>
				<input placeholder="Set Font Size" class="form-control" style="width: 110px;" type="text" name="font_size_h1" value="<?php if (esc_html(get_option( 'font_size_h1'))) : echo esc_html(get_option( 'font_size_h1')); endif; ?>"/>px
			</div>	
			
			<!-- ------------------- Label Element Font Size ------------------ -->					
			<!-- ------------------- Form Element Font Size ------------------ -->				
			<!-- ------------------- Inputs Element Font Size ------------------ -->
			<!-- ------------------- Stroung Element Font Size  ------------------ -->
			<!-- ------------------- Em Font Size  ------------------ -->
			<!-- ------------------- Paragraph Font Size  ------------------ -->
			<!-- ------------------- Span Font Size  ------------------ -->
			<!-- ------------------- Article Font Size ------------------ -->
			<!-- ------------------- Time Font Size ------------------ -->
``		<div class="cc-clr"></div>
		<div style="margin-top: 190px;"><?php submit_button(); ?></div>
			
		</form>	
	</div>
	
	<?php } 
	
	function font_size_language_load() {
	  load_plugin_textdomain('font_size_language_load', FALSE, basename(dirname(__FILE__)) . '/languages');
	}
	add_action('init', 'font_size_language_load');
	
	/************************** CSS Code ****************************/

	function font_size_options_css() { ?>
			<style type="text/css">
				<?php if(esc_html(get_option('font_size_site_title'))) : ?> .site-title a, .site-title, .logo a, .Logo a, #logo a, #site-title a, #logo h1, #logo, header .site-title, header h1, .site-name a,
				#site-name a, #site-name, .site-name { font-size: <?php echo esc_html(get_option('font_size_site_title')); ?>px !important;} <?php endif; ?>
				<?php if(esc_html(get_option('font_size_navigation'))) : ?> nav ul li a, nav ul li, .navmenu a, #navmenu a { font-size: <?php echo esc_html(get_option('font_size_navigation')); ?>px !important;} <?php endif; ?>
				<?php if(esc_html(get_option('font_size_body'))) : ?> body { font-size: <?php echo esc_html(get_option('font_size_body')); ?>px !important;} <?php endif; ?>
				<?php if(esc_html(get_option('font_size_sidebar'))) : ?> aside p, aside, aside a { font-size: <?php echo esc_html(get_option('font_size_sidebar')); ?>px !important;} <?php endif; ?>
				<?php if(esc_html(get_option('font_size_footer'))) : ?> footer, footer a { font-size: <?php echo esc_html(get_option('font_size_footer')); ?>px !important;} <?php endif; ?>
				<?php if(esc_html(get_option('font_size_h1'))) : ?> h1 { font-size: <?php echo esc_html(get_option('font_size_h1')); ?>px !important;} <?php endif; ?>
			</style>
		<?php
		}

	add_action('wp_head', 'font_size_options_css'); 
	
	function font_size_admin_options_css() { ?>	
			<style type="text/css">
				.font-size {
					width: 100%;
					display: block;
					clear: both;
				}
				
				.font-size label {
					font-weight: bold;
				}
				
				.cc-clr {
					display: block;
					clear: both;
					content: "";
				}
				
				.font-size .form-group  {
					margin-top: 15px;
					float: left;
					width: 200px;
					height: 50px;
					display:block;
				}
				
				 .font-size .form-group input {
					border-radius: 4px;
					padding: 10px;
				}
				
			</style>
	<?php } add_action('admin_head', 'font_size_admin_options_css'); 