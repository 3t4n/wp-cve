<?php 
if( !defined( 'ABSPATH' ) ) exit;
if(current_user_can('manage_options') && isset($_POST['submit_option'])){
	$header_script	= htmlspecialchars($_POST['header_script']);
	$body_script	= htmlspecialchars($_POST['body_script']);
	$footer_script	= htmlspecialchars($_POST['footer_script']);
	$nonce			= sanitize_text_field($_POST['insert_script_wpnonce']);

	if(wp_verify_nonce( $nonce, 'insert_script_option_nonce' )){
		update_option('insert_header_script_gk',$header_script);
		update_option('insert_body_script_gk',$body_script);
		update_option('insert_footer_script_gk',$footer_script);
		$successmsg= ishf_success_option_msg('Settings Saved.');

	}else {
        $errormsg= ishf_failure_option_msg('Unable to save data!');
    }
}
$header_script	= ishf_get_option_header_script();
$body_script	= ishf_get_option_body_script();
$footer_script	= ishf_get_option_footer_script();
?>

<div class="wrap ishf-script-wrap">
	<h2><?php _e('Insert Script In Headers And Footers &raquo; Settings','insert-script-in-headers-and-footers'); ?></h2>

	<?php
	if ( isset( $successmsg ) ) {
		?>
		<div class="ishf_updated fade"><p><?php _e($successmsg,'insert-script-in-headers-and-footers'); ?></p></div>
		<?php
	}
	if ( isset( $errormsg ) ) {
		?>
		<div class="error fade"><p><?php _e($errormsg,'insert-script-in-headers-and-footers'); ?></p></div>
		<?php
	}
	$nonce= wp_create_nonce('insert_script_option_nonce');
	?>
	<div class="ishf-row">
		<div class='ishf-col-6'>
		<div class="ishf-inner">
			<h4 class="heading-h4"><?php _e('Settings','insert-script-in-headers-and-footers'); ?></h4>
			
			<form method="post">
				<p>
					<label for="script_in_header"> <?php _e('Scripts in Header','insert-script-in-headers-and-footers'); ?> </label>
					<textarea name="header_script" rows="8" class="ishf-header-footer-textarea" ><?php _e($header_script); ?></textarea>
					<?php _e('These scripts will be printed in the <code>&lt;head&gt;</code> section.','insert-script-in-headers-and-footers'); ?>
				</p>
				<p>
					<label for="script_in_body"> <?php _e('Scripts in Body','insert-script-in-headers-and-footers'); ?> </label>
					<textarea name="body_script" rows="8" class="ishf-header-footer-textarea" ><?php _e($body_script); ?></textarea>
					<?php _e('These scripts will be printed below the <code>&lt;body&gt;</code> section.','insert-script-in-headers-and-footers'); ?>
				</p>
				<p>
					<label for="script_in_footer"> <?php _e('Scripts in Footer','insert-script-in-headers-and-footers'); ?> </label>
					<textarea name="footer_script" rows="8" class="ishf-header-footer-textarea" ><?php _e($footer_script); ?></textarea>
					<?php _e('These scripts will be printed above the <code>&lt;body&gt;</code> section.','insert-script-in-headers-and-footers'); ?>
				</p>
				<input type="hidden" name="insert_script_wpnonce" value="<?php esc_attr_e($nonce); ?>">
				<input type="submit" class="button button-primary " name="submit_option" value="Save">
				
			</form>
			</div>
			
		</div>
		<div class="ishf-col-6">
			<div class="ishf_pro_details">
				<h2><?php esc_html_e('Insert Script In Headers And Footers Pro','insert-script-in-headers-and-footers'); ?></h2>
				<ul>
					<li><?php esc_html_e('Add script to single page priority of loading script.','insert-script-in-headers-and-footers'); ?></li>
					<li><?php esc_html_e('Add header script to single post, custom post and page.','insert-script-in-headers-and-footers'); ?></li>
					<li><?php esc_html_e('Add Footer script to single post, custom Post and page.','insert-script-in-headers-and-footers'); ?></li>
					<li><?php esc_html_e('Give Priority to Script(At Beginning or At End)','insert-script-in-headers-and-footers'); ?></li>
					<li><?php esc_html_e('Select where to Show Script - Admin or Front Side','insert-script-in-headers-and-footers'); ?></li>
					<li><?php esc_html_e('Add Script to Post, Custom Post and Page','insert-script-in-headers-and-footers'); ?></li>
					<li><?php esc_html_e('Controlling the priority of loading script code.','insert-script-in-headers-and-footers'); ?></li>
					<li>
					<?php esc_html_e('Basic code editor options','insert-script-in-headers-and-footers'); ?>
						<ul>
							<li><?php esc_html_e('Code syntax highlighting','insert-script-in-headers-and-footers'); ?></li>
							<li><?php esc_html_e('Line numbering','insert-script-in-headers-and-footers'); ?></li>
							<li><?php esc_html_e('Active line highlighting','insert-script-in-headers-and-footers'); ?></li>
							<li><?php esc_html_e('Tab indentation','insert-script-in-headers-and-footers'); ?></li>
						</ul>
					</li>
					<li><?php esc_html_e('Timely','insert-script-in-headers-and-footers'); ?> <a href="<?php echo esc_url('https://geekcodelab.com/contact/'); ?>" target="_blank"><?php esc_html_e('support','insert-script-in-headers-and-footers'); ?></a> <?php esc_html_e('24/7.','insert-script-in-headers-and-footers'); ?></li>
					<li><?php esc_html_e('Regular updates.','insert-script-in-headers-and-footers'); ?></li>
					<li><?php esc_html_e('Well documented.','insert-script-in-headers-and-footers'); ?></li>
				</ul>
    			<a href="<?php echo esc_url('https://geekcodelab.com/wordpress-plugins/insert-script-in-headers-and-footers-pro/'); ?>" title="<?php echo esc_attr('Upgrade to Premium','insert-script-in-headers-and-footers'); ?>" class="ishf_premium_btn" target="_blank"><?php esc_html_e('Upgrade to Premium','insert-script-in-headers-and-footers'); ?></a>
			</div>
		</div>
	</div>
</div>
