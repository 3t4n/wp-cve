<?php 
// If this file was called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php if (isset($_GET['settings-updated'])) : ?>
	<div class="notice notice-success is-dismissible"><p><?php _e('Changes saved! Please clear your cache if the changes are not reflected on the website.'); ?></p></div>
<?php endif; ?>

<div class="wrap">
<h2><?php _e( 'Add Custom Codes by Mak', 'add-custom-codes' ) ?></h2>
<div class="full_row">
	<div class="accodes_cols">
		<form method="post" action="options.php">
			<?php settings_fields( 'accodes-settings-group' ); ?>
			<?php do_settings_sections( 'accodes-settings-group' ); ?>
			
			<?php 
			$global_css = "";
			$global_css = get_option('custom-css-codes-input'); 
			?>
			<p><label for="custom-css-codes-input" class="accodes-label green-label"><?php _e( 'Custom CSS (Global)', 'add-custom-codes' ) ?></label><br/>
You can add custom css (global) codes below. DO NOT INCLUDE <em>&lt;style&gt;</em> and <em>&lt;/style&gt;</em> tags:</p>    
				<textarea class="codemirror-accodes-css" id="custom-css-codes-input" name="custom-css-codes-input"><?php echo esc_attr( $global_css ); ?></textarea>
			
			<?php 
				$css_on_footer ='';
				$css_on_footer = esc_attr( get_option('accodes_global_css_on_footer') ); 
			?>
			<p>
                <label for="accodes_global_css_on_footer" class="accodes-checkbox-label">
                            <input type="checkbox" <?php echo checked( $css_on_footer, 'on', false ) ?>
                                   name="accodes_global_css_on_footer" id="accodes_global_css_on_footer"/>
							<?php _e( "Insert Custom CSS before <em>&lt;/body&gt;</em> of website.", 'add-custom-codes' ); ?>
				</label><br/>
				<?php _e( "By default, Custom CSS will be added before <em>&lt;/head&gt;</em> section of your website.", 'add-custom-codes' ); ?>
			</p>
			<?php submit_button(); ?>
			

			<div class="accodes-spacer"></div>
			
    			<p><label for="custom-header-codes-input" class="accodes-label green-label">
					<?php _e( 'Global Header Codes', 'add-custom-codes' ) ?></label><br/>
Global Codes or scripts to add before <em>&lt;/head&gt;</em> section of your website. Google Search Console Verification, Bing Verification and any other codes. Include <em>&lt;script&gt;</em> and <em>&lt;/script&gt;</em> tags when necessary: </p>
     			 <textarea class="codemirror" id="custom-verification-codes-input" name="custom-header-codes-input"><?php echo esc_attr(get_option('custom-header-codes-input') ); ?></textarea>    
    			<?php submit_button(); ?>
    
			<div class="accodes-spacer" style="border: ;background: #dce4e6;width: 100%;height: 1px;margin: 30px 0px;"></div>
			
			<p><label for="custom-footer-codes-input" class="accodes-label green-label">
				<?php _e( 'Global Footer Codes', 'add-custom-codes' ) ?></label><br/>
Global Codes or scripts to add before <em>&lt;/body&gt;</em> section of your website. You can add Google Anaylytics Tracking Code, Facebook Scripts, third party ad scripts etc here. Include <em>&lt;script&gt;</em> and <em>&lt;/script&gt;</em> tags when necessary: </p>
      			<textarea class="codemirror" placeholder="" id="custom-analytics-codes-input" name="custom-footer-codes-input"><?php echo esc_attr( get_option('custom-footer-codes-input') ); ?></textarea>    
   			 <?php submit_button(); ?>

		</form>
	</div>
	<div class="accodes_cols2">
		<div class="accodes_box">
			<h3>Hey, How is the plugin?</h3>
			<p>Thank you for using <em>Add Custom Codes by Mak</em>. It will be free forever to use, pinky promise! To report bugs & issues or to request additional features, please visit plugin's <a href="https://wordpress.org/support/plugin/add-custom-codes/" target="_blank" >support forum</a> or <a href="https://maktalseo.com/contact-us/" target="_blank" >contact us</a>. <br/><br/>If you found this plugin useful, please donate something or hire us for your next project!</p>

			<center><a class="accodes_donate blue" href="https://donate.stripe.com/9AQdRz5xJ87c9i0bIS" target="_blank">Donate now</a></center>
			
			<center><a class="accodes_donate orange" href="https://maktalseo.com/" target="_blank">Hire us!</a></center>
		</div>
	</div>

</div>