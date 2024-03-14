<?php
$connected_accounts = $this->get_connected_accounts();
$connected_channels = array();
?>
<table class="form-table sbspf_connected-accounts-wrap" role="presentation">
	<tbody>
		<tr>
			<th scope="row">
				<label><?php echo sprintf( __( '%s Accounts', $text_domain ), $social_network ); ?></label>
				<span style="font-weight:normal; font-style:italic; font-size: 12px; display: block;"><?php echo sprintf( __( 'Use the button above to connect %s account', $text_domain ), $sn_with_a_an ); ?></span>
			</th>
			<td class="sbspf_connected_accounts_wrap">
				<?php if ( empty( $connected_accounts ) ) : ?>
					<p class="sbspf_no_accounts"><?php echo sprintf( __( 'No %s accounts connected. Click the button above to connect an account.', $text_domain ), $social_network ); ?></p><br />
				<?php else: ?>
					<?php foreach ( $connected_accounts as $account ) :
						$username = $account['username'] ? $account['username'] : $account['channel_id'];
						if ( isset( $account['local_avatar'] ) && $account['local_avatar'] && isset( $options['sbspf_favor_local'] ) && $options['sbspf_favor_local' ] === 'on' ) {
							$upload = wp_upload_dir();
							$resized_url = trailingslashit( $upload['baseurl'] ) . trailingslashit( SBI_UPLOADS_NAME );
							$profile_picture = '<img class="sbspf_ca_avatar" src="'.$resized_url . $account['username'].'.jpg" />'; //Could add placeholder avatar image
						} else {
							$profile_picture = $account['profile_picture'] ? '<img class="sbspf_ca_avatar" src="'.$account['profile_picture'].'" />' : ''; //Could add placeholder avatar image
						}
						$is_invalid_class = ! $account['is_valid'] ? ' sbspf_account_invalid' : '';
						$account_type = isset( $account['type'] ) ? $account['type'] : 'personal';
						$use_tagged = isset( $account['use_tagged'] ) && $account['use_tagged'] == '1';

						include $this->get_path( 'single-connected-account' );
						?>
					<?php endforeach;  ?>
				<?php endif; ?>
                <a href="JavaScript:void(0);" class="sbspf_manually_connect button-secondary"><?php _e( 'Manually Connect a Primary Account', $text_domain ); ?></a>
                <div class="sbspf_manually_connect_wrap">
                    <input name="sbspf_manual_at" id="sbspf_manual_at" type="text" value="" style="margin-top: 4px; padding: 5px 9px; margin-left: 0px;" size="64" minlength="15" maxlength="200" placeholder="<?php _e('Enter a valid Access Token', $text_domain );?>" />
                    <p class="sbspf_submit" style="display: inline-block;"><input type="sbspf_submit" name="submit" id="sbspf_manual_submit" class="button button-primary" style="text-align: center; padding: 0;" value="<?php _e('Connect This Account', $text_domain );?>"></p>
                </div>
			</td>
        </tr>
	</tbody>
</table>