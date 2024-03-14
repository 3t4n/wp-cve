<?php
/**
 * Admin View: Settings
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

?>
<div class="wrap">
	<form method="POST" action="" enctype="multipart/form-data">
		<h2 class="nav-tab-wrapper">
			<?php
				foreach ($tabs as $name => $label) {
					echo '<a href="' . admin_url('admin.php?page=reamaze-settings&tab=' . $name) . '" class="nav-tab ' . ($current_tab == $name ? 'nav-tab-active' : '') . '">' . $label . '</a>';
				}
			?>
		</h2>

		<?php
			do_action('reamaze_settings_' . $current_tab);
		?>

		<p class="submit">
			<input name="save" class="button-primary" type="submit" value="<?php _e( 'Save Changes', 'reamaze' ); ?>" />
			<a href="javascript:;" style="margin-left: 2em;" data-reamaze-lightbox="kb">Need help?</a>
			<input type="hidden" name="subtab" id="last_tab" />
			<?php wp_nonce_field( 'reamaze-settings' ); ?>
		</p>
	</form>
</div>

<script>
	( function( $ ) {
		const params = new URLSearchParams( window.location.search );
		const account = params.get( 'account' );
		const hmac = params.get( 'hmac' );
		const timestamp = params.get( 'timestamp' );
		const reamaze_error = params.get( 'reamaze_error' );

		if ( reamaze_error || ! account || ! hmac || ! timestamp ) {
			return;
		};

		const account_url_input = $( '#reamaze_account_id' );
		const sso_secret_input = $( '#reamaze_account_sso_key' );

		if ( account_url_input.val().length != 0 && sso_secret_input.val().length != 0 ) {
			return;
		}

		$.ajax({
			type: 'GET',
			url: `https://${account}.reamaze.com/data/ping/wordpress`,
			data: {
				hmac: hmac,
				timestamp: timestamp
			},
			success: function( data ) {
				account_url_input.val( account );
				sso_secret_input.val( data.sso_secret );
				$( ':submit' ).click();
			},
			error: function() {
				window.location.search += '&reamaze_error=Something went wrong while fetching your account data. Please log into your Reamaze account to find this information.';
			}
		});
  } )( jQuery );
</script>
