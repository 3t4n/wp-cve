<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://webmuehle.at
 * @since      1.0.3
 *
 * @package    Courtres
 * @subpackage Courtres/admin/partials
 */
?>

<?php

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die();
}

global $wpdb;
$table_name = $this->getTable( 'courts' );

if ( isset( $_GET['courtID'] ) ) {
	$courtID = (int) $_GET['courtID'];
}

// 17.01.2019, astoian - if not premium, stop it
if ( isset( $courtID ) && ! $this->isCourtPremium( $courtID ) ) {
	include 'courtres-notice-upgrade.php';
	wp_die( esc_html__( 'Free version allow one Court only.', 'court-reservation' ) );
}

if ( isset( $_POST['delete'] ) && isset( $_POST['id'] ) && (int) $_POST['id'] > 0 ) { // delete
	$wpdb->delete( $table_name, array( 'id' => (int) $_POST['id'] ) );
}

if ( isset( $_POST['submit'] ) ) {
	if ( isset( $_POST['id'] ) && (int) $_POST['id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'name'  => sanitize_text_field( $_POST['name'] ),
				'open'  => sanitize_text_field( $_POST['open'] ),
				'close' => sanitize_text_field( $_POST['close'] ),
				'days'  => sanitize_text_field( $_POST['days'] ),
			),
			array( 'id' => (int) $_POST['id'] ),
			array(
				'%s',
				'%d',
				'%d',
				'%d',
			)
		);
		$courtID = (int) $_POST['id'];
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'name'  => sanitize_text_field( $_POST['name'] ),
				'open'  => sanitize_text_field( $_POST['open'] ),
				'close' => sanitize_text_field( $_POST['close'] ),
				'days'  => sanitize_text_field( $_POST['days'] ),
			),
			array(
				'%s',
				'%d',
				'%d',
				'%d',
			)
		);
		$message = __( 'Successfully created!', 'court-reservation' );
		$courtID = $wpdb->insert_id;
	}
}

if ( isset( $courtID ) && $courtID > 0 ) {
	$court = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = $courtID" );
}

if ( ! isset( $court ) ) {
	$court        = new stdClass();
	$court->id    = 0;
	$court->name  = '';
	$court->open  = 8;
	$court->close = 22;
	$court->days  = 3;
}
?>

<div class="wrap">
  <a class="page-title-action" href="<?php echo esc_url(admin_url( 'admin.php?page=courtres' )); ?>"><?php echo esc_html__( 'Back', 'court-reservation' ); ?></a>
  <h1 class="wp-heading-inline"><?php echo ( isset( $court ) && $court->id > 0 ) ? esc_html( $court->name ) . esc_html__( ' edit', 'court-reservation' ) : esc_html__( 'Create Court', 'court-reservation' ); ?></h1>
  <hr class="wp-header-end">

  <form method="post">
	<input type="hidden" name="id" value="<?php echo esc_html( $court->id ); ?>" />
	<table>
	  <tr>
		<td><?php echo esc_html__( 'Name', 'court-reservation' ); ?></td>
		<td><input type="text" name="name" maxlength="255" value="<?php echo esc_html( $court->name ); ?>" required /></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html__( 'Opens (hour)', 'court-reservation' ); ?></td>
		<td><input type="number" name="open" min="0" max="23" maxlength="2" value="<?php echo esc_html( $court->open ); ?>" required /></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html__( 'Closes (hour)', 'court-reservation' ); ?></td>
		<td><input type="number" name="close" min="0" max="24" maxlength="2" value="<?php echo esc_html( $court->close ); ?>" required /></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html__( 'Reservation Days in Advance', 'court-reservation' ); ?></td>
		<td><input type="number" name="days" min="0" max="9" maxlength="1" value="<?php echo esc_html( $court->days ); ?>" required /></td>
	  </tr>
	  <tr>
		<td></td>
		<td><input class="button" type="submit" name="submit" value=<?php echo esc_html__( 'Save', 'court-reservation' ); ?> /></td>
	  </tr>
	  <?php if ( isset( $court ) && $court->id > 0 ) { ?>
		<tr>
		  <td colspan="2"><hr/></td>
		</tr>
		<tr>
		  <td><?php echo esc_html__( 'Delete Court', 'court-reservation' ); ?></td>
		  <td><input class="button" type="submit" name="delete" value=<?php echo esc_html__( 'Delete', 'court-reservation' ); ?> /></td>
		</tr>
	  <?php } ?>
  </form>
</div>
</div>
</div>
