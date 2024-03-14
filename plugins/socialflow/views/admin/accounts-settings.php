<?php
/**
 * Template for displaying options page updated notice
 *
 * @package SocialFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}

global $socialflow;
// Get only publishing accounts.
$accounts          = $socialflow->accounts->get_2(
	array(
		array(
			'key'   => 'service_type',
			'value' => 'publishing',
		),
	)
);
$accounts_show     = $socialflow->options->get( 'show', array() );
$accounts_send     = $socialflow->options->get( 'send', array() );
$socialflow_params = filter_input_array( INPUT_GET );
settings_errors( $socialflow_params['page'] );

?>
<div class="wrap socialflow">
	<h2><?php esc_html_e( 'Account Settings', 'socialflow' ); ?></h2>

	<form action="options.php" method="post">
		<?php
		if ( $accounts ) :
		?>

			<table cellspacing="0" class="wp-list-table widefat fixed sf-accounts">
				<thead>
					<tr>
						<th style="width:200px" class="manage-column column-username" id="username" scope="col">
							<span><?php esc_html_e( 'Username', 'socialflow' ); ?></span>
						</th>
						<th class="manage-column column-account-type" id="account-type" scope="col">
							<span><?php esc_html_e( 'Account type', 'socialflow' ); ?></span>
						</th>
						<th scope="col">
							<span><?php esc_html_e( 'Enable Account in Plugin', 'socialflow' ); ?></span>
						</th>
						<th scope="col">
							<span><?php esc_html_e( 'Send to by Default', 'socialflow' ); ?></span>
						</th>
					</tr>
				</thead>

				<tbody class="list:user">
	<?php

	foreach ( $accounts as $account_id => $account ) :
		$show = in_array( $account_id, $accounts_show, true );
		$send = in_array( $account_id, $accounts_send, true );
		if ( ! $account->is_valid() ) {
			$show = 0;
			$send = 0;
		}
		$class    = $account->is_valid() ? '' : 'not-valid';
		$disabled = $account->is_valid() ? '' : 'disabled';

				?>

									<tr class="alternate <?php echo esc_attr( $class ); ?>">
					<td class="username column-username">
						<img width="32" height="32" class="avatar avatar-32 photo" src="<?php echo esc_url( $account->get( 'avatar' ) ); ?>" alt="" />
						<strong>
							<?php echo esc_html( $account->get_display_name( false ) ); ?>
						</strong>
					</td>
					<td class="name column-account-type">
						<?php echo esc_html( $account->get_display_type() ); ?>

					</td>
					<td style="padding: 9px 0 22px 15px;">
						<input type="checkbox" value="<?php echo esc_attr( $account->get_id() ); ?>" class="sf-account-show" name="socialflow[show][]" <?php checked( true, $show ); ?> <?php echo esc_attr( $disabled ); ?> />

						<?php
						if ( $disabled ) :
						?>

								<i>(<?php esc_html_e( 'invalid account', 'socialflow' ); ?>)</i>
							<?php endif ?>
					</td>
					<td style="padding: 9px 0 22px 15px;">
						<input type="checkbox" value="<?php echo esc_attr( $account->get_id() ); ?>" class="sf-account-send" name="socialflow[send][]" <?php checked( true, $send ); ?> <?php echo esc_attr( $disabled ); ?> />
					</td>
				</tr>
				<?php endforeach ?>
				</tbody>
			</table>
		<?php
else :
?>

			<p>
				<?php esc_html_e( "You don't have any accounts for publishing on SocialFlow.", 'socialflow' ); ?>
			</p>
		<?php endif; ?>


		<p>
			<input type="submit" name="socialflow[submit]" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'socialflow' ); ?>" />
			<input type="submit" name="socialflow[update-accounts]" class="button" value="<?php esc_attr_e( 'Update Accounts List', 'socialflow' ); ?>" />
		</p>

		<?php settings_fields( 'socialflow' ); ?>

		<input type="hidden" value="accounts" name="socialflow-page" />
	</form>
</div>
