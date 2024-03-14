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
	$option_ui_link = '0';
	if ( isset( $_POST['option_ui_link'] ) ) {
		// Checkbox is selected
		$option_ui_link = '0';
	} else {
		$option_ui_link = '1';
	}
	// save option_ui_link
	if ( isset( $_POST['option_ui_link_id'] ) && (int) $_POST['option_ui_link_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => $option_ui_link,
			),
			array( 'option_id' => (int) $_POST['option_ui_link_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_ui_link',
				'option_value' => $option_ui_link,
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
	// save option_ui_tbl_brdr_clr
	if ( isset( $_POST['option_ui_tbl_brdr_clr_id'] ) && (int) $_POST['option_ui_tbl_brdr_clr_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_ui_tbl_brdr_clr'] ),
			),
			array( 'option_id' => (int) $_POST['option_ui_tbl_brdr_clr_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_ui_tbl_brdr_clr',
				'option_value' => sanitize_text_field( $_POST['option_ui_tbl_brdr_clr'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
	// save option_ui_tbl_bg_clr_1
	if ( isset( $_POST['option_ui_tbl_bg_clr_1_id'] ) && (int) $_POST['option_ui_tbl_bg_clr_1_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_ui_tbl_bg_clr_1'] ),
			),
			array( 'option_id' => (int) $_POST['option_ui_tbl_bg_clr_1_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_ui_tbl_bg_clr_1',
				'option_value' => sanitize_text_field( $_POST['option_ui_tbl_bg_clr_1'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
	// save option_ui_tbl_bg_clr_2
	if ( isset( $_POST['option_ui_tbl_bg_clr_2_id'] ) && (int) $_POST['option_ui_tbl_bg_clr_2_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_ui_tbl_bg_clr_2'] ),
			),
			array( 'option_id' => (int) $_POST['option_ui_tbl_bg_clr_2_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_ui_tbl_bg_clr_2',
				'option_value' => sanitize_text_field( $_POST['option_ui_tbl_bg_clr_2'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
	// save option_ui_tbl_bg_clr_3
	if ( isset( $_POST['option_ui_tbl_bg_clr_3_id'] ) && (int) $_POST['option_ui_tbl_bg_clr_3_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_ui_tbl_bg_clr_3'] ),
			),
			array( 'option_id' => (int) $_POST['option_ui_tbl_bg_clr_3_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_ui_tbl_bg_clr_3',
				'option_value' => sanitize_text_field( $_POST['option_ui_tbl_bg_clr_3'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
	// save option_ui_tbl_bg_clr_4
	if ( isset( $_POST['option_ui_tbl_bg_clr_4_id'] ) && (int) $_POST['option_ui_tbl_bg_clr_4_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_ui_tbl_bg_clr_4'] ),
			),
			array( 'option_id' => (int) $_POST['option_ui_tbl_bg_clr_4_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_ui_tbl_bg_clr_4',
				'option_value' => sanitize_text_field( $_POST['option_ui_tbl_bg_clr_4'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
	// save option_ui_button_clr
	if ( isset( $_POST['option_ui_button_clr_id'] ) && (int) $_POST['option_ui_button_clr_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_ui_button_clr'] ),
			),
			array( 'option_id' => (int) $_POST['option_ui_button_clr_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_ui_button_clr',
				'option_value' => sanitize_text_field( $_POST['option_ui_button_clr'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
	// save option_ui_link_clr
	if ( isset( $_POST['option_ui_link_clr_id'] ) && (int) $_POST['option_ui_link_clr_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_ui_link_clr'] ),
			),
			array( 'option_id' => (int) $_POST['option_ui_link_clr_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_ui_link_clr',
				'option_value' => sanitize_text_field( $_POST['option_ui_link_clr'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
	// save option_ui_btn_title_1
	if ( isset( $_POST['option_ui_btn_title_1_id'] ) && (int) $_POST['option_ui_btn_title_1_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_ui_btn_title_1'] ),
			),
			array( 'option_id' => (int) $_POST['option_ui_btn_title_1_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_ui_btn_title_1',
				'option_value' => sanitize_text_field( $_POST['option_ui_btn_title_1'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
	// save option_ui_btn_title_2
	if ( isset( $_POST['option_ui_btn_title_2_id'] ) && (int) $_POST['option_ui_btn_title_2_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_ui_btn_title_2'] ),
			),
			array( 'option_id' => (int) $_POST['option_ui_btn_title_2_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_ui_btn_title_2',
				'option_value' => sanitize_text_field( $_POST['option_ui_btn_title_2'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
	// save option_ui_table_cell_width
	if ( isset( $_POST['option_ui_table_cell_width_id'] ) && (int) $_POST['option_ui_table_cell_width_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_ui_table_cell_width'] ),
			),
			array( 'option_id' => (int) $_POST['option_ui_table_cell_width_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_ui_table_cell_width',
				'option_value' => sanitize_text_field( $_POST['option_ui_table_cell_width'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
	// save option_ui_table_cell_height
	if ( isset( $_POST['option_ui_table_cell_height_id'] ) && (int) $_POST['option_ui_table_cell_height_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_ui_table_cell_height'] ),
			),
			array( 'option_id' => (int) $_POST['option_ui_table_cell_height_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_ui_table_cell_height',
				'option_value' => sanitize_text_field( $_POST['option_ui_table_cell_height'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
	// save option_ui_table_cell_mouseover_background
	if ( isset( $_POST['option_ui_table_cell_mouseover_background'] ) && (int) $_POST['option_ui_table_cell_mouseover_background_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_ui_table_cell_mouseover_background'] ),
			),
			array( 'option_id' => (int) $_POST['option_ui_table_cell_mouseover_background_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_ui_table_cell_mouseover_background',
				'option_value' => sanitize_text_field( $_POST['option_ui_table_cell_mouseover_background'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
	// save option_ui_table_cell_mouseover_linktext
	if ( isset( $_POST['option_ui_table_cell_mouseover_linktext'] ) && (int) $_POST['option_ui_table_cell_mouseover_linktext_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_ui_table_cell_mouseover_linktext'] ),
			),
			array( 'option_id' => (int) $_POST['option_ui_table_cell_mouseover_linktext_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_ui_table_cell_mouseover_linktext',
				'option_value' => sanitize_text_field( $_POST['option_ui_table_cell_mouseover_linktext'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}

	// save option_dateformats
	if ( (int) $_POST['option_dateformats_id'] > 0 ) { // edit
		if ( isset( $_POST['option_dateformats'] ) && $_POST['option_dateformats'] ) {
			$wpdb->update(
				$table_name,
				array(
					'option_value' => sanitize_text_field( $_POST['option_dateformats'] ),
				),
				array( 'option_id' => (int) $_POST['option_dateformats_id'] ),
				array( '%s' )
			);
			$message = __( 'Successfully changed!', 'court-reservation' );
		}
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_dateformats',
				'option_value' => sanitize_text_field( $_POST['option_dateformats'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
	// save option_ui_dateformat
	if ( (int) $_POST['option_ui_dateformat_id'] > 0 ) { // edit
		if ( isset( $_POST['option_ui_dateformat'] ) && $_POST['option_ui_dateformat'] ) {
			$wpdb->update(
				$table_name,
				array(
					'option_value' => sanitize_text_field( $_POST['option_ui_dateformat'] ),
				),
				array( 'option_id' => (int) $_POST['option_ui_dateformat_id'] ),
				array( '%s' )
			);
			$message = __( 'Successfully changed', 'court-reservation' );
		}
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_ui_dateformat',
				'option_value' => sanitize_text_field( $_POST['option_ui_dateformat'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
}

$option_ui_tbl_brdr_clr = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_tbl_brdr_clr'" );
if ( ! isset( $option_ui_tbl_brdr_clr ) ) {
	$option_ui_tbl_brdr_clr               = new stdClass();
	$option_ui_tbl_brdr_clr->option_id    = 0;
	$option_ui_tbl_brdr_clr->option_name  = 'option_ui_tbl_brdr_clr';
	$option_ui_tbl_brdr_clr->option_value = '';
}

$option_ui_tbl_bg_clr_1 = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_tbl_bg_clr_1'" );
if ( ! isset( $option_ui_tbl_bg_clr_1 ) ) {
	$option_ui_tbl_bg_clr_1               = new stdClass();
	$option_ui_tbl_bg_clr_1->option_id    = 0;
	$option_ui_tbl_bg_clr_1->option_name  = 'option_ui_tbl_bg_clr_1';
	$option_ui_tbl_bg_clr_1->option_value = '';
}

$option_ui_tbl_bg_clr_2 = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_tbl_bg_clr_2'" );
if ( ! isset( $option_ui_tbl_bg_clr_2 ) ) {
	$option_ui_tbl_bg_clr_2               = new stdClass();
	$option_ui_tbl_bg_clr_2->option_id    = 0;
	$option_ui_tbl_bg_clr_2->option_name  = 'option_ui_tbl_bg_clr_2';
	$option_ui_tbl_bg_clr_2->option_value = '';
}

$option_ui_tbl_bg_clr_3 = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_tbl_bg_clr_3'" );
if ( ! isset( $option_ui_tbl_bg_clr_3 ) ) {
	$option_ui_tbl_bg_clr_3               = new stdClass();
	$option_ui_tbl_bg_clr_3->option_id    = 0;
	$option_ui_tbl_bg_clr_3->option_name  = 'option_ui_tbl_bg_clr_3';
	$option_ui_tbl_bg_clr_3->option_value = '';
}

$option_ui_tbl_bg_clr_4 = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_tbl_bg_clr_4'" );
if ( ! isset( $option_ui_tbl_bg_clr_4 ) ) {
	$option_ui_tbl_bg_clr_4               = new stdClass();
	$option_ui_tbl_bg_clr_4->option_id    = 0;
	$option_ui_tbl_bg_clr_4->option_name  = 'option_ui_tbl_bg_clr_4';
	$option_ui_tbl_bg_clr_4->option_value = '';
}

$option_ui_link = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_link'" );
if ( ! isset( $option_ui_link ) ) {
	$option_ui_link               = new stdClass();
	$option_ui_link->option_id    = 0;
	$option_ui_link->option_name  = 'option_ui_link';
	$option_ui_link->option_value = '0';
}

$option_ui_button_clr = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_button_clr'" );
if ( ! isset( $option_ui_button_clr ) ) {
	$option_ui_button_clr               = new stdClass();
	$option_ui_button_clr->option_id    = 0;
	$option_ui_button_clr->option_name  = 'option_ui_button_clr';
	$option_ui_button_clr->option_value = '';
}

$option_ui_link_clr = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_link_clr'" );
if ( ! isset( $option_ui_link_clr ) ) {
	$option_ui_link_clr               = new stdClass();
	$option_ui_link_clr->option_id    = 0;
	$option_ui_link_clr->option_name  = 'option_ui_link_clr';
	$option_ui_link_clr->option_value = '';
}

$option_ui_btn_title_1 = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_btn_title_1'" );
if ( ! isset( $option_ui_btn_title_1 ) ) {
	$option_ui_btn_title_1               = new stdClass();
	$option_ui_btn_title_1->option_id    = 0;
	$option_ui_btn_title_1->option_name  = 'option_ui_btn_title_1';
	$option_ui_btn_title_1->option_value = '';
}
$option_ui_btn_title_2 = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_btn_title_2'" );
if ( ! isset( $option_ui_btn_title_2 ) ) {
	$option_ui_btn_title_2               = new stdClass();
	$option_ui_btn_title_2->option_id    = 0;
	$option_ui_btn_title_2->option_name  = 'option_ui_btn_title_2';
	$option_ui_btn_title_2->option_value = '';
}
$option_ui_table_cell_width = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_table_cell_width'" );
if ( ! isset( $option_ui_table_cell_width ) ) {
	$option_ui_table_cell_width               = new stdClass();
	$option_ui_table_cell_width->option_id    = 0;
	$option_ui_table_cell_width->option_name  = 'option_ui_table_cell_width';
	$option_ui_table_cell_width->option_value = '';
}
$option_ui_table_cell_height = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_table_cell_height'" );
if ( ! isset( $option_ui_table_cell_height ) ) {
	$option_ui_table_cell_height               = new stdClass();
	$option_ui_table_cell_height->option_id    = 0;
	$option_ui_table_cell_height->option_name  = 'option_ui_table_cell_height';
	$option_ui_table_cell_height->option_value = '';
}
$option_ui_table_cell_mouseover_background = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_table_cell_mouseover_background'" );
if ( ! isset( $option_ui_table_cell_mouseover_background ) ) {
	$option_ui_table_cell_mouseover_background               = new stdClass();
	$option_ui_table_cell_mouseover_background->option_id    = 0;
	$option_ui_table_cell_mouseover_background->option_name  = 'option_ui_table_cell_mouseover_background';
	$option_ui_table_cell_mouseover_background->option_value = '';
}
$option_ui_table_cell_mouseover_linktext = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_table_cell_mouseover_linktext'" );
if ( ! isset( $option_ui_table_cell_mouseover_linktext ) ) {
	$option_ui_table_cell_mouseover_linktext               = new stdClass();
	$option_ui_table_cell_mouseover_linktext->option_id    = 0;
	$option_ui_table_cell_mouseover_linktext->option_name  = 'option_ui_table_cell_mouseover_linktext';
	$option_ui_table_cell_mouseover_linktext->option_value = '';
}
$option_dateformats = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_dateformats'" );
if ( ! isset( $option_dateformats ) ) {
	$option_dateformats               = new stdClass();
	$option_dateformats->option_id    = 0;
	$option_dateformats->option_name  = 'option_dateformats';
	$option_dateformats->option_value = "d.m. = German\r\nm.d. = USA"; // Json without {}. Date format must be in format for date_i18n()
}
$dateformats = $this->getDateformats( $option_dateformats->option_value );

$option_ui_dateformat = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_dateformat'" );
if ( ! isset( $option_ui_dateformat ) ) {
	$option_ui_dateformat               = new stdClass();
	$option_ui_dateformat->option_id    = 0;
	$option_ui_dateformat->option_name  = 'option_ui_dateformat';
	$option_ui_dateformat->option_value = $dateformats[0]['format']; // German format as default. Date format must be in format for date_i18n()
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
require 'courtres-notice-upgrade.php';
require 'courtres-notice-message.php';
?>


<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php echo esc_html__( 'Settings', 'court-reservation' ); ?>
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
				<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=3' )); ?>" class="nav-tab nav-tab-active">
					<?php echo esc_html__( 'User Interface', 'court-reservation' ); ?>
				</a>
				<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=5' )); ?>" class="nav-tab">
					<?php echo esc_html__( 'E-mail Notification', 'court-reservation' ); ?>
				</a>
				<?php if ( ! cr_fs()->is_plan( 'ultimate' ) ) { ?>
					<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=4' )); ?>" class="nav-tab">
						<?php echo esc_html__( 'Upgrade', 'court-reservation' ); ?>
					</a>
				<?php } ?>
			</h2>

			<form method="post">
				<input type="hidden" name="option_ui_tbl_brdr_clr_id" value="<?php echo esc_attr( $option_ui_tbl_brdr_clr->option_id ); ?>" />
				<input type="hidden" name="option_ui_tbl_bg_clr_1_id" value="<?php echo esc_attr( $option_ui_tbl_bg_clr_1->option_id ); ?>" />
				<input type="hidden" name="option_ui_tbl_bg_clr_2_id" value="<?php echo esc_attr( $option_ui_tbl_bg_clr_2->option_id ); ?>" />
				<input type="hidden" name="option_ui_tbl_bg_clr_3_id" value="<?php echo esc_attr( $option_ui_tbl_bg_clr_3->option_id ); ?>" />
				<input type="hidden" name="option_ui_tbl_bg_clr_4_id" value="<?php echo esc_attr( $option_ui_tbl_bg_clr_4->option_id ); ?>" />
				<input type="hidden" name="option_ui_link_id" value="<?php echo esc_attr( $option_ui_link->option_id ); ?>" />
				<input type="hidden" name="option_ui_button_clr_id" value="<?php echo esc_attr( $option_ui_button_clr->option_id ); ?>" />
				<input type="hidden" name="option_ui_link_clr_id" value="<?php echo esc_attr( $option_ui_link_clr->option_id ); ?>" />
				<input type="hidden" name="option_ui_btn_title_1_id" value="<?php echo esc_attr( $option_ui_btn_title_1->option_id ); ?>" />
				<input type="hidden" name="option_ui_btn_title_2_id" value="<?php echo esc_attr( $option_ui_btn_title_2->option_id ); ?>" />
				<input type="hidden" name="option_ui_table_cell_width_id" value="<?php echo esc_attr( $option_ui_table_cell_width->option_id ); ?>" />
				<input type="hidden" name="option_ui_table_cell_height_id" value="<?php echo esc_attr( $option_ui_table_cell_height->option_id ); ?>" />
				<input type="hidden" name="option_ui_table_cell_mouseover_background_id" value="<?php echo esc_attr( $option_ui_table_cell_mouseover_background->option_id ); ?>" />
				<input type="hidden" name="option_ui_table_cell_mouseover_linktext_id" value="<?php echo esc_attr( $option_ui_table_cell_mouseover_linktext->option_id ); ?>" />
				<input type="hidden" name="option_dateformats_id" value="<?php echo esc_attr( $option_dateformats->option_id ); ?>" />
				<input type="hidden" name="option_ui_dateformat_id" value="<?php echo esc_attr( $option_ui_dateformat->option_id ); ?>" />

				<table class="t-form">
					<tr>
						<td>
							<?php echo esc_html__( 'Table border color', 'court-reservation' ); ?>
						</td>
						<td>
							<input class="color-input" data-huebee name="option_ui_tbl_brdr_clr" value="<?php echo esc_attr( $option_ui_tbl_brdr_clr->option_value ); ?>" placeholder="<?php echo esc_attr__( 'default', 'court-reservation' ); ?>">
						</td>
					</tr>
					<tr>
						<td>
							<?php echo esc_html__( 'Table cell background color', 'court-reservation' ); ?>
						</td>
						<td>
							<input class="color-input" data-huebee name="option_ui_tbl_bg_clr_1" value="<?php echo esc_attr( $option_ui_tbl_bg_clr_1->option_value ); ?>" placeholder="<?php echo esc_attr__( 'default', 'court-reservation' ); ?>">
						</td>
					</tr>
					<tr>
						<td>
							<?php echo esc_html__( 'Table cell background color for reservation', 'court-reservation' ); ?>
						</td>
						<td>
							<input class="color-input" data-huebee name="option_ui_tbl_bg_clr_2" value="<?php echo esc_attr( $option_ui_tbl_bg_clr_2->option_value ); ?>" placeholder="<?php echo esc_attr__( 'default', 'court-reservation' ); ?>">
						</td>
					</tr>
					<tr>
						<td>
							<?php echo esc_html__( 'Table cell background color for unavailable', 'court-reservation' ); ?>
						</td>
						<td>
							<input class="color-input" data-huebee name="option_ui_tbl_bg_clr_3" value="<?php echo esc_attr( $option_ui_tbl_bg_clr_3->option_value ); ?>" placeholder="<?php echo esc_attr__( 'default', 'court-reservation' ); ?>">
						</td>
					</tr>
					<tr>
						<td>
							<?php echo esc_html__( 'Table head background color', 'court-reservation' ); ?>
						</td>
						<td>
							<input class="color-input" data-huebee name="option_ui_tbl_bg_clr_4" value="<?php echo esc_attr( $option_ui_tbl_bg_clr_4->option_value ); ?>" placeholder="<?php echo esc_attr__( 'default', 'court-reservation' ); ?>">
						</td>
					</tr>
					<tr>
						<td>
							<?php echo esc_html__( 'Button off/on', 'court-reservation' ); ?>
						</td>
						<td>
							<label class="switch">
								<input type="checkbox" name="option_ui_link" <?php echo ( $option_ui_link->option_value === '1' ) ? '' : 'checked'; ?>>
								<span class="slider round"></span>
							</label>
						</td>
					</tr>
					<tr id="option_ui_button_clr_tr" style="<?php echo ( $option_ui_link->option_value === '1' ) ? 'display:none' : ''; ?>">
						<td>
							<?php echo esc_html__( 'Button color', 'court-reservation' ); ?>
						</td>
						<td>
							<input class="color-input" data-huebee name="option_ui_button_clr" value="<?php echo esc_attr( $option_ui_button_clr->option_value ); ?>" placeholder="<?php echo esc_attr__( 'default', 'court-reservation' ); ?>">
						</td>
					</tr>
					<tr id="option_ui_link_clr_tr"  style="<?php echo ( $option_ui_link->option_value === '1' ) ? '' : 'display:none'; ?>">
						<td>
							<?php echo esc_html__( 'Link color', 'court-reservation' ); ?>
						</td>
						<td>
							<input class="color-input" data-huebee name="option_ui_link_clr" value="<?php echo esc_attr( $option_ui_link_clr->option_value ); ?>" placeholder="<?php echo esc_attr__( 'default', 'court-reservation' ); ?>">
						</td>
					</tr>
					<tr id="option_ui_btn_title_1_tr">
						<td>
							<?php echo esc_html__( 'Button title text reserve', 'court-reservation' ); ?>
						</td>
						<td>
							<input name="option_ui_btn_title_1" value="<?php echo esc_attr( $option_ui_btn_title_1->option_value ); ?>" placeholder="<?php echo esc_attr__( 'reserve', 'court-reservation' ); ?>">
						</td>
					</tr>
					<tr id="option_ui_btn_title_2_tr">
						<td>
							<?php echo esc_html__( 'Button title text delete', 'court-reservation' ); ?>
						</td>
						<td>
							<input name="option_ui_btn_title_2" value="<?php echo esc_attr( $option_ui_btn_title_2->option_value ); ?>" placeholder="<?php echo esc_attr__( 'delete', 'court-reservation' ); ?>">
						</td>
					</tr>
					<tr id="option_ui_table_cell_width_tr">
						<td>
							<?php echo esc_html__( 'Maximum cell width', 'court-reservation' ) . ' (px)'; ?>
						</td>
						<td>
							<input name="option_ui_table_cell_width" value="<?php echo esc_attr( $option_ui_table_cell_width->option_value ); ?>" placeholder="100">
						</td>
					</tr>
					<tr id="option_ui_table_cell_height_tr">
						<td>
							<?php echo esc_html__( 'Maximum cell height', 'court-reservation' ) . ' (px)'; ?>
						</td>
						<td>
							<input name="option_ui_table_cell_height" value="<?php echo esc_attr( $option_ui_table_cell_height->option_value ); ?>" placeholder="100">
						</td>
					</tr>
					<tr id="option_ui_table_cell_mouseover_background_tr">
						<td>
							<?php echo esc_html__( 'Mouseover background-color for table cell', 'court-reservation' ); ?>
						</td>
						<td>
							<input class="color-input" data-huebee name="option_ui_table_cell_mouseover_background" value="<?php echo esc_attr( $option_ui_table_cell_mouseover_background->option_value ); ?>" placeholder="<?php echo esc_attr__( 'default', 'court-reservation' ); ?>">
						</td>
					</tr>
					<tr id="option_ui_table_cell_mouseover_linktext_tr">
						<td>
							<?php echo esc_html__( 'Mouseover linktext for table cell', 'court-reservation' ); ?>
						</td>
						<td>
							<input class="color-input" data-huebee name="option_ui_table_cell_mouseover_linktext" value="<?php echo esc_attr( $option_ui_table_cell_mouseover_linktext->option_value ); ?>" placeholder="<?php echo esc_attr__( 'default', 'court-reservation' ); ?>">
						</td>
					</tr>
					<tr>
						<td>
							<?php echo esc_html__( 'List of available date formats', 'court-reservation' ); ?>
							<br>Example:<pre>d.m. = German<br>m.d. = USA</pre>
						</td>
						<td>
							<textarea name="option_dateformats" rows="3" cols="20"><?php echo esc_attr( $option_dateformats->option_value ); ?></textarea>
							<div class="tooltip">
								<div class="symbol">
									<span>?</span>
								</div>
								<span class="tooltiptext tooltip-right">
									<?php echo esc_html__( 'Each format in new line. Date format must be in format for date_i18n()', 'court-reservation' ); ?></span>
							</div>
						</td>
					</tr>
					<tr id="option_ui_dateformat">
						<td>
							<?php echo esc_html__( 'Date format for site', 'court-reservation' ); ?>
						</td>
						<td>
							<select name="option_ui_dateformat">
								<?php foreach ( $dateformats as $dateformat ) : ?>
									<option value="<?php echo esc_attr( $dateformat['format'] ); ?>" <?php selected( $option_ui_dateformat->option_value, $dateformat['format'] ); ?>><?php echo esc_attr( $dateformat['name'] ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<input class="button" type="submit" name="submit" value="<?php echo esc_html__( 'Save', 'court-reservation' ); ?>" />
						</td>
						<td></td>
					</tr>
			</form>
		</div>

	</div>

	<p></p>
</div>
