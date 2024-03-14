<?php
$error = array();  

if (!empty( isset($_POST['action']) ) && isset($_POST['action']) == 'update-user' ) {

	/* Update user password. */
	if ( !empty(sanitize_text_field($_POST['pass1']) ) && !empty( sanitize_text_field($_POST['pass2']) ) ) {
		if ( sanitize_text_field($_POST['pass1']) == sanitize_text_field($_POST['pass2']) )
			wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => sanitize_text_field($_POST['pass1']) ) );
		else
			$error[] = esc_html__('The passwords you entered do not match.  Your password was not updated.', 'sakolawp');
	}

	/* Update user information. */
	if ( !empty( sanitize_text_field($_POST['url']) ) )
		wp_update_user( array( 'ID' => $current_user->ID, 'user_url' => sanitize_text_field($_POST['url']) ) );
	if ( !empty( sanitize_email($_POST['email']) ) ){
		if (!is_email( sanitize_email($_POST['email']) ))
			$error[] = esc_html__('The Email you entered is not valid.  please try again.', 'sakolawp');
		elseif(email_exists(sanitize_email($_POST['email'])) != $current_user->ID )
			$error[] = esc_html__('This email is already used by another user.  try a different one.', 'sakolawp');
		else{
			wp_update_user( array ('ID' => $current_user->ID, 'user_email' => sanitize_email($_POST['email']) ));
		}
	}

	if ( !empty( sanitize_text_field($_POST['first-name']) ) )
		update_user_meta( $current_user->ID, 'first_name', sanitize_text_field($_POST['first-name']) );
	if ( !empty( sanitize_text_field($_POST['last-name']) ) )
		update_user_meta($current_user->ID, 'last_name', sanitize_text_field($_POST['last-name']) );
	if ( !empty( sanitize_textarea_field($_POST['description']) ) )
		update_user_meta( $current_user->ID, 'description', sanitize_textarea_field($_POST['description']) );

	if ( !empty( $_FILES['user_img'] ) ) {
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
		$attach_id = media_handle_upload('user_img', $current_user->ID);
		if (is_numeric($attach_id)) {
			update_option('user_img', $attach_id);
			update_user_meta($current_user->ID, '_user_img', sanitize_text_field($attach_id));
		}  
	}

	/* Redirect so the page will show updated info.*/
  /*I am not Author of this Code- i dont know why but it worked for me after changing below line to if ( count($error) == 0 ){ */
	if ( count($error) == 0 ) {
		//action hook for plugins and extra fields saving
		//do_action('edit_user_profile_update', $current_user->ID);
		wp_safe_redirect( home_url('myaccount') );
		exit;
	}
}

get_header(); 
do_action( 'sakolawp_before_main_content' );  
?>
<div class="exams-online-page skwp-content-inner skwp-clearfix">
	<div id="post-<?php the_ID(); ?>">
		<?php //the_content(); ?>
		<?php if ( !is_user_logged_in() ) : ?>
				<p class="warning">
					<?php _e('You must be logged in to edit your profile.', 'sakolawp'); ?>
				</p><!-- .warning -->
		<?php else : ?>
			<form method="post" name="update_profile" action="" method="POST" enctype="multipart/form-data">
				<p class="form-username">
					<label for="first-name"><?php esc_html_e('First Name', 'sakolawp'); ?></label>
					<input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
				</p><!-- .form-username -->
				<p class="form-username">
					<label for="last-name"><?php esc_html_e('Last Name', 'sakolawp'); ?></label>
					<input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta( 'last_name', $current_user->ID ); ?>" />
				</p><!-- .form-username -->
				<p class="form-email">
					<label for="email"><?php esc_html_e('E-mail *', 'sakolawp'); ?></label>
					<input class="text-input" name="email" type="email" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
				</p><!-- .form-email -->
				<p class="form-url">
					<label for="url"><?php esc_html_e('Website', 'sakolawp'); ?></label>
					<input class="text-input" name="url" type="url" id="url" value="<?php the_author_meta( 'user_url', $current_user->ID ); ?>" />
				</p><!-- .form-url -->
				<p class="form-password">
					<label for="pass1"><?php esc_html_e('Password *', 'sakolawp'); ?> </label>
					<input class="text-input" name="pass1" type="password" id="pass1" />
				</p><!-- .form-password -->
				<p class="form-password">
					<label for="pass2"><?php esc_html_e('Repeat Password *', 'sakolawp'); ?></label>
					<input class="text-input" name="pass2" type="password" id="pass2" />
				</p><!-- .form-password -->
				<p class="form-textarea">
					<label for="description"><?php esc_html_e('Biographical Information', 'sakolawp') ?></label>
					<textarea name="description" id="description" rows="3" cols="50"><?php the_author_meta( 'description', $current_user->ID ); ?></textarea>
				</p><!-- .form-textarea -->

				<div class="skwp-form-group">
					<label class="col-form-label" for=""> <?php esc_html_e('Profile Image', 'sakolawp'); ?></label>
					<div class="input-group skwp-form-control mb-2">
						<input type="file" name="user_img" id="file-3" class="inputfile inputfile-3" style="display:none" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf" />
						<label for="file-3"><i class="os-icon picons-thin-icon-thin-0042_attachment"></i> <span><?php esc_html_e('Upload a File', 'sakolawp'); ?></span></label>
					</div>
				</div>
				
				<p class="form-submit skwp-submit-profile">
					<input name="updateuser" type="submit" id="updateuser" class="submit btn button skwp-form-btn btn-primary skwp-btn" value="<?php esc_html_e('Update', 'sakolawp'); ?>" />
					<?php wp_nonce_field( 'update-user' ) ?>
					<input name="action" type="hidden" id="action" value="update-user" />
				</p><!-- .form-submit -->
			</form><!-- #adduser -->
		<?php endif; ?>
	</div><!-- .hentry .post -->
</div>
<?php

do_action( 'sakolawp_after_main_content' );
get_footer();
?>