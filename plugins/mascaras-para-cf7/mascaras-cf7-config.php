<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Vamos checar por uma capacidade
if ( !current_user_can( 'manage_options' ) )
	wp_die( __( 'Insufficient access permission', 'plugin-mascaras-cf7' ) );
 

$msg = '';
 
if($_POST){
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'nonce_mascaras' ) ) die( 'Failed security check' );
	
	if(isset( $_POST['mask_phone']) ){ update_option('mask_phone', sanitize_text_field($_POST['mask_phone'])); }	$msg = '<div id="message" class="updated notice is-dismissible"><p>'. __( 'Settings saved!', 'plugin-mascaras-cf7' ) . '</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">' . __( 'Dismiss this warning.', 'plugin-mascaras-cf7' ) . '</span></button></div>';
}
?>
<div class="wrap" id="mascarascf7-settings">
	<h2><?php _e( 'Masks for the Contact Form 7 plugin', 'plugin-mascaras-cf7' ); ?></h2>
	
	
	<div id="poststuff">
		<div id="post-body">
			<div class="mascarascf7-settings-grid mascarascf7-settings-main-cont">
				<?php echo $msg; ?>
				
				
				<div class="postbox">
					<h3 class="hndle"><label for="title"><?php _e( 'Settings', 'plugin-mascaras-cf7' ); ?></label></h3>
					<div class="inside">
			
						<form action="" method="post">
							<?php wp_nonce_field('nonce_mascaras'); ?>
							<ul>
								<li><strong><?php _e( 'Phone Number', 'plugin-mascaras-cf7' ); ?></strong></li>
								<li style="padding-left: 10px;">
									<select id="mask_phone" name="mask_phone">
											<option value="" ><?php _e( 'Select an option', 'plugin-mascaras-cf7' ); ?></option>
											<option value="pt_BR" <?php if(get_option('mask_phone')=="pt_BR" || get_option('mask_phone')==""){echo "selected='selected'";} ?>><?php _e( 'Brazil: (XX) ?XXXX-XXXX', 'plugin-mascaras-cf7' ); ?></option>
											<option value="en_US" <?php if(get_option('mask_phone')=="en_US"){echo "selected='selected'";} ?>><?php _e( 'US: (XXX) XXX-XXXX', 'plugin-mascaras-cf7' ); ?></option>
											<option value="en_US2" <?php if(get_option('mask_phone')=="en_US2"){echo "selected='selected'";} ?>><?php _e( 'US: XXX-XXX-XXXX', 'plugin-mascaras-cf7' ); ?></option>
									</select>
								</li>
							</ul>
							<input type="submit" name="Submit" value="<?php _e( 'Save changes', 'plugin-mascaras-cf7' ); ?>" class="button-primary" />
						</form>
		
		
					</div>
				</div>
			</div>
			
			<p>&nbsp;</p>
			<p style="">* <?php _e( 'To use the phone mask in Contact Form 7, just select which mask you want to use in the configuration above and save. And also use the <strong> tel </strong> type field of Contact Form 7.', 'plugin-mascaras-cf7' ); ?></p>
			<p style="">* <?php _e( 'To use the CPF mask, just use the class "cpf" in the field.', 'plugin-mascaras-cf7' ); ?></p>
			<p style="">* <?php _e( 'To use the CPF mask without the dots, just use the class "cpf2" in the field.', 'plugin-mascaras-cf7' ); ?></p>
			<p style="">* <?php _e( 'To use the CNPJ mask, just use the class "cnpj" in the field.', 'plugin-mascaras-cf7' ); ?></p>
			<p style="">* <?php _e( 'To use the CNPJ mask without the dots, just use the class "cnpj2" in the field.', 'plugin-mascaras-cf7' ); ?></p>
			<p style="">* <?php _e( 'To use the zip code mask (00.000-000), just use the "cep" class in the field.', 'plugin-mascaras-cf7' ); ?></p>
			<p style="">* <?php _e( 'To use the Zipcode mask (00000), just use the "zipcode" class in the field.', 'plugin-mascaras-cf7' ); ?></p>
			<p style="">* <?php _e( 'To use the Money mask (00.000.000,00), just use the "dinheiro" class in the field.', 'plugin-mascaras-cf7' ); ?></p>
			<p style="">* <?php _e( 'To use the Money mask (00,000,000.00), just use the "money" class in the field.', 'plugin-mascaras-cf7' ); ?></p>
			<p style="">&nbsp;</p>
			<div class="swpsmtp-yellow-box"><?php _e( 'If you have questions, see', 'plugin-mascaras-cf7' ); ?> <a href="https://murilopereira.com.br/plugins/plugin-mascaras-cf7/" title="<?php _e( 'Open in new tab', 'plugin-mascaras-cf7' ); ?>" target="_blank"><?php _e( 'Plugin page', 'plugin-mascaras-cf7' ) ?> <span aria-hidden="true" class="dashicons dashicons-external" style="text-decoration:none;" title="<?php _e( 'Open in new tab', 'plugin-mascaras-cf7' ); ?>"></span></a> </div>
		</div>
	</div>
	
	<div class="clear"></div>
	

</div>
<div class="clear"></div>