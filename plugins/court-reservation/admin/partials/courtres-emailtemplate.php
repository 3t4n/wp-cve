<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://webmuehle.at
 * @since      1.1.0
 *
 * @package    UI - colors and others
 * @subpackage Courtres/admin/ui
 */
?>

<?php
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die();
}

global $wpdb;
$table_name = $this->getTable( 'settings' );

if ( isset( $_POST['submit'] ) ) {

	if ( isset( $_POST['email_notify_players'] ) ) {
		// Checkbox is selected
		$email_notify_players = '1';
	} else {
		$email_notify_players = '0';
	}
	// save email_notify_players
	if ( isset( $_POST['option_email_id'] ) && (int) $_POST['option_email_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => $email_notify_players,
			),
			array( 'option_id' => (int) $_POST['option_email_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'email_notify_players',
				'option_value' => $email_notify_players,
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}

	// save option_email_template
	if ( isset( $_POST['option_email_template'] ) && (int) $_POST['option_email_template_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => wp_kses_post( $_POST['option_email_template'] ),
			),
			array( 'option_id' => (int) $_POST['option_email_template_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_email_template',
				'option_value' => wp_kses_post( $_POST['option_email_template'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}

	// save option_email_1
	if ( isset( $_POST['option_email_1'] ) && (int) $_POST['option_email_1_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_email_1'] ),
			),
			array( 'option_id' => (int) $_POST['option_email_1_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_email_1',
				'option_value' => sanitize_text_field( $_POST['option_email_1'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}

	// save option_email_2
	if ( isset( $_POST['option_email_2'] ) && (int) $_POST['option_email_2_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_email_2'] ),
			),
			array( 'option_id' => (int) $_POST['option_email_2_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_email_2',
				'option_value' => sanitize_text_field( $_POST['option_email_2'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}

	// save option_email_3
	if ( isset( $_POST['option_email_3'] ) && (int) $_POST['option_email_3_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_email_3'] ),
			),
			array( 'option_id' => (int) $_POST['option_email_3_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_email_3',
				'option_value' => sanitize_text_field( $_POST['option_email_3'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}


	// save option_email_4
	if ( isset( $_POST['option_email_4'] ) && (int) $_POST['option_email_4_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => wp_kses_post( $_POST['option_email_4'] ),
			),
			array( 'option_id' => (int) $_POST['option_email_4_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_email_4',
				'option_value' => wp_kses_post( $_POST['option_email_4'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}


	// save option_email_5
	if ( isset( $_POST['option_email_5'] ) && (int) $_POST['option_email_5_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_email_5'] ),
			),
			array( 'option_id' => (int) $_POST['option_email_5_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_email_5',
				'option_value' => sanitize_text_field( $_POST['option_email_5'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}


	// save option_email_6
	if ( isset( $_POST['option_email_6'] ) && (int) $_POST['option_email_6_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_email_6'] ),
			),
			array( 'option_id' => (int) $_POST['option_email_6_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_email_6',
				'option_value' => sanitize_text_field( $_POST['option_email_6'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}


	// save option_email_7
	if ( isset( $_POST['option_email_7'] ) && (int) $_POST['option_email_7_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_email_7'] ),
			),
			array( 'option_id' => (int) $_POST['option_email_7_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_email_7',
				'option_value' => sanitize_text_field( $_POST['option_email_7'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}


	// save option_email_8
	if ( isset( $_POST['option_email_8'] ) && (int) $_POST['option_email_8_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_email_8'] ),
			),
			array( 'option_id' => (int) $_POST['option_email_8_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_email_8',
				'option_value' => sanitize_text_field( $_POST['option_email_8'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}

}




$option_email = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'email_notify_players'" );
if ( ! isset( $option_email ) ) {
	$option_email               = new stdClass();
	$option_email->option_id    = 0;
	$option_email->option_name  = 'email_notify_players';
	$option_email->option_value = '0';
}


// option_email_template
$option_email_template            = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_email_template'" );
$email_templates_default_settings = Courtres::get_default_settings( 'email_template' );
if ( isset( $option_email_template ) ) {
	if ( $option_email_template->option_value == $email_templates_default_settings['old'] ) {
		$option_email_template->option_value = $email_templates_default_settings['1.5.0'];
	}
} else {
	$option_email_template               = new stdClass();
	$option_email_template->option_id    = 0;
	$option_email_template->option_name  = 'option_email_template';
	$option_email_template->option_value = $email_templates_settings['1.5.0'];
}


// option_email_1
$option_email_1 = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_email_1'" );
if ( !isset( $option_email_1 ) ) {
	$option_email_1               = new stdClass();
	$option_email_1->option_id    = 0;
	$option_email_1->option_name  = 'option_email_1';
	$option_email_1->option_value = '';
}


// option_email_2
$option_email_2 = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_email_2'" );
if ( !isset( $option_email_2 ) ) {
	$option_email_2               = new stdClass();
	$option_email_2->option_id    = 0;
	$option_email_2->option_name  = 'option_email_2';
	$option_email_2->option_value = '';
}


// option_email_3
$option_email_3 = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_email_3'" );
if ( !isset( $option_email_3 ) ) {
	$option_email_3               = new stdClass();
	$option_email_3->option_id    = 0;
	$option_email_3->option_name  = 'option_email_3';
	$option_email_3->option_value = '';
}


// option_email_4
$option_email_4 = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_email_4'" );
if ( !isset( $option_email_4 ) ) {
	$option_email_4               = new stdClass();
	$option_email_4->option_id    = 0;
	$option_email_4->option_name  = 'option_email_4';
	$option_email_4->option_value = '';
}



// option_email_5
$option_email_5 = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_email_5'" );
if ( !isset( $option_email_5 ) ) {
	$option_email_5               = new stdClass();
	$option_email_5->option_id    = 0;
	$option_email_5->option_name  = 'option_email_5';
	$option_email_5->option_value = '';
}


// option_email_6
$option_email_6 = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_email_6'" );
if ( !isset( $option_email_6 ) ) {
	$option_email_6               = new stdClass();
	$option_email_6->option_id    = 0;
	$option_email_6->option_name  = 'option_email_6';
	$option_email_6->option_value = '';
}


// option_email_7
$option_email_7 = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_email_7'" );
if ( !isset( $option_email_7 ) ) {
	$option_email_7               = new stdClass();
	$option_email_7->option_id    = 0;
	$option_email_7->option_name  = 'option_email_7';
	$option_email_7->option_value = '';
}



// option_email_8
$option_email_8 = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_email_8'" );
if ( !isset( $option_email_8 ) ) {
	$option_email_8               = new stdClass();
	$option_email_8->option_id    = 0;
	$option_email_8->option_name  = 'option_email_8';
	$option_email_8->option_value = '';
} ?>

<?php
require 'courtres-notice-upgrade.php';
require 'courtres-notice-message.php';
?>


<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php echo esc_html__( 'E-mail Notifications', 'court-reservation' ); ?>
	</h1>
	<hr class="wp-header-end">

	<div class="cr-tabs-wrap">
		<div class="item1">
			<div class="cr-widget-right">
				<?php
					require 'courtres-widget-upgrade.php';
				?>
			</div>
		</div>
		<div  class="item2">
			<h2 class="nav-tab-wrapper wp-clearfix">
				<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=0' )); ?>" class="nav-tab">
					<?php echo esc_html__( 'Courts', 'court-reservation' ); ?>
				</a>
				<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=1' )); ?>" class="nav-tab">
					<?php echo esc_html__( 'Pyramids', 'court-reservation' ); ?>
				</a>
				<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=2' )); ?>" class="nav-tab"><?php echo esc_html__( 'Settings', 'court-reservation' ); ?></a>
				<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=3' )); ?>" class="nav-tab">
					<?php echo esc_html__( 'User Interface', 'court-reservation' ); ?>
				</a>
				<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=5' )); ?>" class="nav-tab nav-tab-active">
					<?php echo esc_html__( 'E-mail Notifications', 'court-reservation' ); ?>
				</a>
				<?php if ( ! cr_fs()->is_plan( 'ultimate' ) ) { ?>
					<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=4' )); ?>" class="nav-tab">
						<?php echo esc_html__( 'Upgrade', 'court-reservation' ); ?>
					</a>
				<?php } ?>
			</h2>

			<form method="post">

				<input type="hidden" name="option_email_id" value="<?php echo esc_attr( $option_email->option_id ); ?>" />
				<input type="hidden" name="option_email_template_id" value="<?php echo wp_kses_post( $option_email_template->option_id ); ?>" />
				<input type="hidden" name="option_email_1_id" value="<?php echo esc_attr( $option_email_1->option_id ); ?>" />
				<input type="hidden" name="option_email_2_id" value="<?php echo esc_attr( $option_email_2->option_id ); ?>" />
				<input type="hidden" name="option_email_3_id" value="<?php echo esc_attr( $option_email_3->option_id ); ?>" />
				<input type="hidden" name="option_email_4_id" value="<?php echo esc_attr( $option_email_4->option_id ); ?>" />
				<input type="hidden" name="option_email_5_id" value="<?php echo esc_attr( $option_email_5->option_id ); ?>" />
				<input type="hidden" name="option_email_6_id" value="<?php echo esc_attr( $option_email_6->option_id ); ?>" />
				<input type="hidden" name="option_email_7_id" value="<?php echo esc_attr( $option_email_7->option_id ); ?>" />
				<input type="hidden" name="option_email_8_id" value="<?php echo esc_attr( $option_email_8->option_id ); ?>" />

				<div style="clear: both; margin-bottom: 16px;"> </div>
				<table class="t-form">
					<tr>
						<td>
							<?php echo esc_html__( 'E-Mail notifications', 'court-reservation' ); ?>
						</td>
						<td>
							<label class="switch">
								<input type="checkbox" name="email_notify_players" 
								<?php
								echo ( $option_email->option_value ===
								'1' ) ? 'checked' : ''
								?>
								>
								<span class="slider round"></span>
							</label>
							<div class="tooltip">
								<div class="symbol">
									<span>?</span>
								</div>
								<span class="tooltiptext tooltip-right">
									<?php echo esc_html__( 'Players get an E-Mail when they reserve a court.', 'court-reservation' ); ?></span>
							</div>
						</td>
					</tr>
				</table>

			    <div id="email-conf">
				<table class="t-form">
					<tr>
						<td width="30%">
							<?php echo esc_html__( 'Customize text of e-mail confirmation', 'court-reservation' ); ?><br>
							<?php echo esc_html__( 'Use the following placeholders:', 'court-reservation' ); ?><br>
							[court_name], [date_on], [hours_from_till], [player_name_creator], [players_list]<br />
							<strong><?php echo esc_html__( 'Not supported placeholders from v1.5.0:', 'court-reservation' ); ?></strong>
							[player_name_1], [player_name_2], [player_name_3], [player_name_4]
						</td>
						<td>
							<div style="width: 100%; max-width: 800px;">
								<?php wp_editor($option_email_template->option_value,"option_email_template",array('textarea_rows' => 10 )); ?>
								<?php /* <textarea name="option_email_template" rows="6" cols="60"><?php echo esc_html( $option_email_template->option_value ); ?></textarea> */ ?>
							</div>
							<div class="tooltip">
								<div class="symbol">
									<span>?</span>
								</div>
								<span class="tooltiptext tooltip-right">
									<?php echo esc_html__( 'Text of e-mail confirmation', 'court-reservation' ); ?></span>
							</div>
						</td>
					</tr>
					<tr>
						<td style="font-size: 14px; font-weight: 600;">
							<?php echo esc_html__( 'E-mail sender settings', 'court-reservation' ); ?>
						</td>
					</tr>
					<tr id="option_email_1_tr">
						<td>
							<?php echo esc_html__( 'From - Name', 'court-reservation' ); ?>
						</td>
						<td>
							<input name="option_email_1" value="<?php echo esc_attr( $option_email_1->option_value ); ?>"  placeholder="<?php echo esc_attr__( 'default', 'court-reservation' ); ?>">
						</td>
					</tr>
					<tr id="option_email_2_tr">
						<td>
							<?php echo esc_html__( 'From - Address', 'court-reservation' ); ?>
						</td>
						<td>
							<input name="option_email_2" value="<?php echo esc_attr( $option_email_2->option_value ); ?>"  placeholder="<?php echo esc_attr__( 'default', 'court-reservation' ); ?>">
						</td>
					</tr>
					<tr>
						<td style="font-size: 14px; font-weight: 600;">
							<?php echo esc_html__( 'E-mail template settings', 'court-reservation' ); ?>
						</td>
					</tr>
					<tr>
						<td style="font-weight: 600;">
							<?php echo esc_html__( 'This section allows you to customize the Court Reservation emails.', 'court-reservation' ); ?><br />
							<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=5preview' )); ?>">
								<?php echo esc_html__( 'Click here to view an email preview.', 'court-reservation' ); ?>
							</a>
						</td>
					</tr>
					<tr id="option_email_3_tr">
						<td>
							<?php echo esc_html__( 'Header image', 'court-reservation' ); ?>
						</td>
						<td>
							<input name="option_email_3" value="<?php echo esc_attr( $option_email_3->option_value ); ?>"  placeholder="<?php echo esc_attr__( 'default', 'court-reservation' ); ?>">
							<div class="tooltip">
								<div class="symbol">
									<span>?</span>
								</div>
								<span class="tooltiptext tooltip-right">
									<?php echo esc_html__( 'URL to an image to be displayed in the email header. Upload images using Media Uploader (Admin > Media).', 'court-reservation' ); ?></span>
							</div>
						</td>
					</tr>
					<tr id="option_email_4_tr">
						<td>
							<?php echo esc_html__( 'Footer text', 'court-reservation' ); ?>
						</td>
						<td>

							<div style="width: 100%; max-width: 800px;">
								<?php wp_editor($option_email_4->option_value,"option_email_4",array('textarea_rows' => 5 )); ?>
								<?php /* <textarea name="option_email_4" rows="3" cols="20"><?php echo esc_attr( $option_email_4->option_value ); ?></textarea> */ ?>
							</div>
						</td>
					</tr>
					<tr id="option_email_5_tr">
						<td>
							<?php echo esc_html__( 'Header color', 'court-reservation' ); ?>
						</td>
						<td>
							<input name="option_email_5" data-huebee value="<?php echo esc_attr( $option_email_5->option_value ); ?>"  placeholder="<?php echo esc_attr__( 'default', 'court-reservation' ); ?>">
						</td>
					</tr>
					<tr id="option_email_6_tr">
						<td>
							<?php echo esc_html__( 'Background color', 'court-reservation' ); ?>
						</td>
						<td>
							<input name="option_email_6" data-huebee value="<?php echo esc_attr( $option_email_6->option_value ); ?>"  placeholder="<?php echo esc_attr__( 'default', 'court-reservation' ); ?>">
						</td>
					</tr>
					<tr id="option_email_7_tr">
						<td>
							<?php echo esc_html__( 'Footer background color', 'court-reservation' ); ?>
						</td>
						<td>
							<input name="option_email_7" data-huebee value="<?php echo esc_attr( $option_email_7->option_value ); ?>"  placeholder="<?php echo esc_attr__( 'default', 'court-reservation' ); ?>">
						</td>
					</tr>
					<tr id="option_email_8_tr">
						<td>
							<?php echo esc_html__( 'Text color', 'court-reservation' ); ?>
						</td>
						<td>
							<input name="option_email_8" data-huebee value="<?php echo esc_attr( $option_email_8->option_value ); ?>"  placeholder="<?php echo esc_attr__( 'default', 'court-reservation' ); ?>">
						</td>
					</tr>
				</table>
			    </div>
				<table>
					<tr>
						<td>
							<input class="button" type="submit" name="submit" value="<?php echo esc_html__( 'Save', 'court-reservation' ); ?>" />
						</td>
						<td></td>
					</tr>
				</table>
			</form>
		</div>

	</div>

	<p></p>
</div>
