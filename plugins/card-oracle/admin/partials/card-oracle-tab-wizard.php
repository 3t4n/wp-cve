<?php
/**
 * Admin View: Page - Status Report.
 *
 * @package CardOracle
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( 'wizard' === $active_tab ) {
	global $co_logs, $co_notices;

	$co_wizard_settings = get_option( 'co_wizard', array() );

	// Reading Input.
	if ( isset( $_POST['co_wizard_reading'] ) && check_admin_referer( 'co_wizard_nonce' ) ) {
		$co_wizard_settings['reading'] = sanitize_text_field( wp_unslash( $_POST['co_wizard_reading'] ) );
		update_option( 'co_wizard', $co_wizard_settings );
	}

	// Positions Table.
	// If the table has been sorted then update the variable positions.
	if ( isset( $_POST['position'] ) ) {
		$co_wizard_settings['positions'] = array_map( 'sanitize_text_field', wp_unslash( $_POST['position'] ) );
		update_option( 'co_wizard', $co_wizard_settings );
	}

	// The add_position button has been pressed, add the text to the array.
	if ( isset( $_POST['add_position'] ) && ! empty( $_POST['co_wizard_add_position'] ) && check_admin_referer( 'co_wizard_nonce' ) ) {
		// The variable doesn't exist, initialize it.
		if ( ! isset( $co_wizard_settings['positions'] ) ) {
			$co_wizard_settings['positions'] = array();
		}

		array_push( $co_wizard_settings['positions'], sanitize_text_field( wp_unslash( $_POST['co_wizard_add_position'] ) ) );
		update_option( 'co_wizard', $co_wizard_settings );
	}

	// Clear All Positions button pressed, reset the variable.
	if ( isset( $_POST['clear_position'] ) && check_admin_referer( 'co_wizard_nonce' ) ) {
		$co_wizard_settings['positions'] = array();
		update_option( 'co_wizard', $co_wizard_settings );
	}

	// Delete position button pressed, remove it from the variable and reorder the list.
	if ( isset( $_POST['delete_position'] ) && check_admin_referer( 'co_wizard_nonce' ) ) {
		$co_wizard_settings['positions'] = array_merge( array_diff( $co_wizard_settings['positions'], array( sanitize_text_field( wp_unslash( $_POST['delete_position'] ) ) ) ) );
		update_option( 'co_wizard', $co_wizard_settings );
	}

	// Cards Table.
	if ( isset( $_POST['add_card'] ) && ! empty( $_POST['co_wizard_add_card'] ) && check_admin_referer( 'co_wizard_nonce' ) ) {
		if ( ! isset( $co_wizard_settings['cards'] ) ) {
			$co_wizard_settings['cards'] = array();
		}

		array_push( $co_wizard_settings['cards'], sanitize_text_field( wp_unslash( $_POST['co_wizard_add_card'] ) ) );
		update_option( 'co_wizard', $co_wizard_settings );
	}

	// Clear All Cards button pressed, reset the variable.
	if ( isset( $_POST['clear_card'] ) && check_admin_referer( 'co_wizard_nonce' ) ) {
		$co_wizard_settings['cards'] = array();
		update_option( 'co_wizard', $co_wizard_settings );
	}

	// Delete card button pressed, remove it from the variable and reorder the list.
	if ( isset( $_POST['delete_card'] ) && check_admin_referer( 'co_wizard_nonce' ) ) {
		$co_wizard_settings['cards'] = array_merge( array_diff( $co_wizard_settings['cards'], array( sanitize_text_field( wp_unslash( $_POST['delete_card'] ) ) ) ) );
		update_option( 'co_wizard', $co_wizard_settings );
	}

	// Create the Reading.
	if ( isset( $_POST['create_reading'] ) && check_admin_referer( 'co_wizard_nonce' ) ) {
		if ( CardOracleAdminWizard::wizard_validate() ) {
			CardOracleAdminWizard::create_reading();

			// Show success admin notice.
			$co_notices->add_display( 'wizard_created', esc_html__( 'Reading created.', 'card-oracle' ), 'info' );

			// Clear the variables after creating Reading.
			$co_wizard_settings = array();
			update_option( 'co_wizard', $co_wizard_settings );
		}
	}

	// Clear All Cards button pressed, reset the variable.
	if ( isset( $_POST['clear_form'] ) && check_admin_referer( 'co_wizard_nonce' ) ) {
		$co_wizard_settings = array();
		update_option( 'co_wizard', $co_wizard_settings );
	}

	?>

	<div class="card-oracle-wizard">
		<h2><?php esc_html_e( 'Setup Wizard', 'card-oracle' ); ?></h2>
		<p><?php esc_html_e( 'This Wizard will setup a Reading based on the Positions and Cards you add here. The Descriptions post will be created with the title of "Card - Position".', 'card-oracle' ); ?></p>
		<p><?php esc_html_e( 'Note: You will still need to add the images for your cards and the text you want for your cards and descriptions.', 'card-oracle' ); ?></p>
		<div class="card-oracle-wizard-section">
			<table class="card_oracle_status_table widefat" cellspacing="0">
				<thead>
					<th>
						<h2>
							<?php esc_html_e( 'Reading', 'card-oracle' ); ?>
							<?php echo card_oracle_tool_tip( esc_html__( 'This is the title of the reading you wish to create.', 'card-oracle' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
						</h2>
				</th>
				</thead>
				<tbody>
					<tr>
						<td>
							<form method="post">
								<?php wp_nonce_field( 'co_wizard_nonce' ); ?>
								<input id="co_wizard_reading" type="text" name="co_wizard_reading" value="<?php echo isset( $co_wizard_settings['reading'] ) ? esc_html( $co_wizard_settings['reading'] ) : ''; ?>" />
								<?php submit_button( esc_attr( __( 'Save Reading', 'card-oracle' ) ), 'primary', 'save_reading', '' ); ?>
							</form>
						</td>
					</tr>
				</tbody>
				<?php if ( empty( $co_wizard_settings['reading'] ) ) : ?>
					<tfoot>
						<tr>
							<td>
								<?php echo esc_html( __( 'Save the Reading.', 'card-oracle' ) ); ?>
							</td>
						</tr>
					</tfoot>
				<?php endif; ?>
			</table>
		</div>
		<div class="card-oracle-wizard-section">
			<table class="card_oracle_wizard_table widefat sortable" cellspacing="0">
				<thead>
					<th width="32"><span title="Drag and Drop to move item up or down.">&udarr;</span></th>
					<th>
						<h2>
							<?php esc_html_e( 'Positions', 'card-oracle' ); ?>
							<?php echo card_oracle_tool_tip( esc_html__( 'Add all the Positions for this Reading. You can drag and drop items to change the Order.' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
						</h2>
					</th>
					<th><?php esc_html_e( 'Order', 'card-oracle' ); ?></th>
					<th title="Delete this position."><?php esc_html_e( 'Delete?', 'card-oracle' ); ?></th>
				</thead>
				<tbody class="row_position">
			<?php if ( ! empty( $co_wizard_settings['positions'] ) ) : ?>
				<?php $count = 0; ?>
				<?php $count_positions = count( $co_wizard_settings['positions'] ); ?>
				<?php foreach ( $co_wizard_settings['positions'] as $position ) : ?>
					<?php $count++; ?>
					<tr id="<?php echo esc_attr( $position ); ?>">
					<?php if ( $count_positions > 1 ) : ?>
						<td class="card-oracle-color-3">
							<?php if ( 1 === $count ) : ?>
								<span title="Drag and Drop to move item down.">&darr;</span>
							<?php elseif ( $count === $count_positions ) : ?>
								<span title="Drag and Drop to move item up.">&uarr;</span>
							<?php else : ?>
								<span title="Drag and Drop to move item up or down.">&udarr;</span>
							<?php endif; ?>
						</td>
						<?php else : ?>
							<td></td>
						<?php endif; ?>
						<td>
						<?php echo esc_attr( $position ); ?>
						</td>
						<td>
							<?php echo esc_attr( $count ); ?>
						</td>
						<td class="card-oracle-icon-button">
							<form action="" method="post">
								<?php wp_nonce_field( 'co_wizard_nonce' ); ?>
								<?php echo card_oracle_delete_button( __( 'Delete this position.', 'card-oracle' ), 'delete_position', $position ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr>
					<td colspan="4">
						<?php echo esc_html__( 'Enter the Positions for this Reading.', 'card-oracle' ); ?>
					</td>
				</tr>
			<?php endif; ?>
				</tbody>
				<tfoot>
					<form action="" method="post">
						<tr colspan="4">
							<td colspan="3">
									<input type="hidden" value="true" name="co_wizard_nonce" />
									<?php wp_nonce_field( 'co_wizard_nonce' ); ?>
									<input id="co_wizard_add_position" type="text" name="co_wizard_add_position" value="" />
									<?php submit_button( esc_attr( __( 'Add Position', 'card-oracle' ) ), 'primary', 'add_position', '' ); ?>
							</td>
							<td class="card-oracle-icon-button">
									<?php submit_button( esc_attr( __( 'Clear All', 'card-oracle' ) ), 'delete', 'clear_position', '' ); ?>
							</td>
						</tr>
					</form>
				</tfoot>
			</table>
		</div>
		<div class="card-oracle-wizard-section">
			<table class="card_oracle_wizard_table widefat" cellspacing="0">
				<thead>
					<th>
						<h2>
							<?php esc_html_e( 'Cards', 'card-oracle' ); ?>
							<?php echo card_oracle_tool_tip( esc_html__( 'Add all the Cards for this Reading.' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
						</h2>
					</th>
					<th title="<?php esc_html_e( 'Click the Trash icon to delete a card.', 'card-oracle' ); ?>"><?php esc_html_e( 'Delete?', 'card-oracle' ); ?></th>
				</thead>
				<tbody>
			<?php if ( ! empty( $co_wizard_settings['cards'] ) ) : ?>
				<?php foreach ( $co_wizard_settings['cards'] as $card ) : ?>
					<tr id="<?php echo esc_attr( $card ); ?>">
						<td>
						<?php echo esc_attr( $card ); ?>
						</td>
						<td class="card-oracle-icon-button">
							<form action="" method="post">
								<?php wp_nonce_field( 'co_wizard_nonce' ); ?>
								<?php echo card_oracle_delete_button( __( 'Delete this card.', 'card-oracle' ), 'delete_card', $card ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr>
					<td>
						<?php echo esc_html__( 'Enter the Cards for this Reading.', 'card-oracle' ); ?>
					</td>
				</tr>
			<?php endif; ?>
				</tbody>
				<tfoot>
					<form action="" method="post">
						<tr colspan="2">
							<td>
									<input type="hidden" value="true" name="co_wizard_card_button" />
									<?php wp_nonce_field( 'co_wizard_nonce' ); ?>
									<input id="co_wizard_add_card" type="text" name="co_wizard_add_card" value="" />
									<?php submit_button( esc_attr( __( 'Add Card', 'card-oracle' ) ), 'primary', 'add_card', '' ); ?>
							</td>
							<td class="card-oracle-icon-button">
									<?php submit_button( esc_attr( __( 'Clear All', 'card-oracle' ) ), 'delete', 'clear_card', '' ); ?>
							</td>
						</tr>
					</form>
				</tfoot>
			</table>
		</div>
			<form action="" onSubmit="" method="post">
				<input type="hidden" value="true" name="co_wizard_create_reading" />
				<?php wp_nonce_field( 'co_wizard_nonce' ); ?>
				<?php submit_button( esc_attr( __( 'Create Reading', 'card-oracle' ) ), 'primary', 'create_reading', '' ); ?>
				<?php submit_button( esc_attr( __( 'Clear All', 'card-oracle' ) ), 'delete', 'clear_form', '' ); ?>
			</form>
	</div>

<?php } ?>
