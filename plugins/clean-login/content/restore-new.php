<?php
	if ( ! defined( 'ABSPATH' ) ) 
		exit; 
	
	$user_id = absint( $_GET['user_id'] );
	$new_password = sanitize_text_field( get_transient( 'cl_temporary_pass_' . $user_id ) );
	delete_transient( 'cl_temporary_pass_' . $user_id );
	$login_url = CleanLogin_Controller::get_login_url();
?>

<div class="cleanlogin-container">
	<form class="cleanlogin-form" method="POST">
		
		<fieldset>
			<div class="cleanlogin-field">
				<label><?php echo __( 'Your new password is', 'clean-login' ); ?></label>
				<input type="text" name="pass" value="<?php echo $new_password; ?>">
			</div>		
		</fieldset>
		
		<div class="cleanlogin-form-bottom">				
			<?php if ( $login_url != '' )
				echo "<a href='$login_url' class='cleanlogin-form-login-link'>". __( 'Log in', 'clean-login') ."</a>";
			?>						
		</div>
	</form>
</div>