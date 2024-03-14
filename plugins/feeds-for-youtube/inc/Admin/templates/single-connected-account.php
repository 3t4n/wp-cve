<?php
$just_updated = isset( $_GET['sby_access_token'] ) && $account['access_token'] === $_GET['sby_access_token'];
$just_updated_class = $just_updated ? ' sbspf_just_updated' : '';
?>

<div class="sbspf_connected_account<?php echo $just_updated_class; ?>" id="sbspf_connected_account_<?php esc_attr_e( $account['channel_id'] ); ?>" data-accesstoken="<?php esc_attr_e( $account['access_token'] ); ?>" data-userid="<?php esc_attr_e( $account['channel_id'] ); ?>" data-username="<?php esc_attr_e( $account['username'] ); ?>">

	<div class="sbspf_ca_alert">
		<span><?php _e( 'The Access Token for this account is expired or invalid. Click the button above to attempt to renew it.', $text_domain ) ?></span>
	</div>
    <?php if ( $just_updated ) : ?>
        <div class="sbspf_ca_alert sbspf_ca_updated">
            <span><?php _e( 'Successfully Connected', $text_domain ) ?></span>
        </div>
    <?php endif; ?>
	<div class="sbspf_ca_info">

		<div class="sbspf_ca_delete">
			<a href="JavaScript:void(0);" class="sbspf_delete_account"><?php echo sby_admin_icon( 'times', 'sbspf_small_svg' ) ; ?><span class="sbspf_remove_text"><?php _e( 'Remove', $text_domain ); ?></span></a>
		</div>

		<div class="sbspf_ca_username">
			<?php echo $profile_picture; ?>
            <?php if ( ! empty( $account['channel_id'] ) ) : ?>
			<strong><?php echo $username; ?><span><?php _e('Channel ID:', $text_domain ); ?><?php echo ' ' . $account['channel_id']; ?></span></strong>
			<?php else: ?>
                <strong><?php echo $username; ?><span><?php echo sprintf( __( '%sHow to create a channel%s', $text_domain ), '<a href="https://support.google.com/youtube/answer/1646861?hl=en" target="_blank" rel="noopener noreferrer">', '</a>' ); ?></span></strong>
            <?php endif; ?>
		</div>

		<div class="sbspf_ca_actions">
			<?php if ( ! empty( $account['channel_id'] ) ) : ?>
            <a class="sbspf_ca_token_shortcode button-secondary" href="JavaScript:void(0);"><?php echo sby_admin_icon( 'chevron-right', 'sbspf_small_svg' ) ; ?><?php _e( 'Add to another Feed', $text_domain ); ?></a>
			<?php endif; ?>
            <a class="sbspf_ca_show_token button-secondary" href="JavaScript:void(0);" title="<?php _e('Show access token and account info', $text_domain ); ?>"><?php echo sby_admin_icon( 'ellipsis', 'sbspf_small_svg' ) ; ?></a>
		</div>

		<div class="sbspf_ca_shortcode">

			<p><?php _e('Copy and paste this shortcode into your page or widget area', $text_domain ); ?>:<br>
				<?php if ( !empty( $account['username'] ) ) : ?>
					<code>[<?php echo $slug; ?> channel="<?php echo $account['channel_id']; ?>"]</code>
				<?php else : ?>
					<code>[<?php echo $slug; ?> accesstoken="<?php echo $account['access_token']; ?>"]</code>
				<?php endif; ?>
			</p>

			<p><?php _e('To add multiple channels in the same feed, simply separate them using commas', $text_domain); ?>:<br>
                <code>[<?php echo $slug; ?> channel="<?php echo $account['channel_id']; ?>, a_second_channel, a_third_channel"]</code>

			<p><?php echo sprintf( __('Click on the %s tab to learn more about shortcodes', $text_domain), '<a href="admin.php?page='. esc_attr( $slug ). '&tab=display" target="_blank">'. __( 'Display Your Feed', $text_domain ) . '</a>' ); ?></p>
		</div>

		<div class="sbspf_ca_accesstoken">
			<span class="sbspf_ca_token_label"><?php _e('Access Token', $text_domain);?>:</span><input type="text" class="sbspf_ca_token" value="<?php echo $account['access_token']; ?>" readonly="readonly" onclick="this.focus();this.select()" title="<?php _e('To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac).', $text_domain);?>"><br>
            <span class="sbspf_ca_token_label"><?php _e('Channel ID', $text_domain);?>:</span><input type="text" class="sbspf_ca_user_id" value="<?php echo $account['channel_id']; ?>" readonly="readonly" onclick="this.focus();this.select()" title="<?php _e('To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac).', $text_domain);?>"><br>
		</div>

	</div>

</div>