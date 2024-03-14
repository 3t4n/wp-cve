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
if ( isset( $_POST['id'] ) && isset( $_POST['delete'] ) ) {
	$this->deleteReservationByID( sanitize_text_field( $_POST['id'] ) );
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php
require 'courtres-notice-upgrade.php';
?>
<!--    SOFT CHANGES START -->
<?php
	$get_order_by              = isset( $_GET['order_by'] ) ? sanitize_text_field( $_GET['order_by'] ) : false;
	$get_way                   = isset( $_GET['way'] ) ? sanitize_text_field( $_GET['way'] ) : false;
	$get_datepicker_start_date = isset( $_GET['datepicker-start-date'] ) ? sanitize_text_field( $_GET['datepicker-start-date'] ) : false;
	$get_datepicker_final_date = isset( $_GET['datepicker-final-date'] ) ? sanitize_text_field( $_GET['datepicker-final-date'] ) : false;
	$get_gid                   = isset( $_GET['gid'] ) ? sanitize_text_field( $_GET['gid'] ) : false;

	$is_view_expired = isset( $_REQUEST['view-expired'] ) && $_REQUEST['view-expired'] == 1;
if ( $is_view_expired ) {
	$reservations = $this->get_expired( 'reservations' );
} else {
	$reservations = $this->getReservations( $get_order_by, $get_way, $get_datepicker_start_date, $get_datepicker_final_date, $get_gid );
}
	$reservations = $this->joinReservations( $reservations );
?>

<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo esc_html__( 'Upcoming Reservations', 'court-reservation' ); ?></h1>
	<div class="cr-head-right">
		<form method="post" action="<?php echo esc_url(admin_url( 'admin-ajax.php' )); ?>">
		   <?php wp_nonce_field( 'export_expired', 'export_expired_nonce' ); ?>
			<input type="hidden" name="target" value="reservations" />
			<input type="hidden" name="action" value="download_csv" />
		   <?php submit_button( __( 'Export Expired', 'court-reservation' ) ); ?>
		</form>
	</div>
	<div id="datepicker-form-wrapper">
		<p> </p>
		<form method="get" action="">
			<input name="page" type="hidden" value="courtres-reservations">
			<label for="datepicker-start-date">Date from</label>
			<input id="datepicker-start-date" name="datepicker-start-date" class="datepicker" type="text" placeholder="Enter start date" value="<?php echo ( isset( $get_datepicker_start_date ) && $get_datepicker_start_date != '' ) ? esc_html($get_datepicker_start_date) : ''; ?>" autocomplete="off">
			<label for="datepicker-final-date">Date to</label>
			<input id="datepicker-final-date" name="datepicker-final-date" class="datepicker" type="text" placeholder="Enter final date" value="<?php echo ( isset( $get_datepicker_final_date ) && $get_datepicker_final_date != '' ) ? esc_html($get_datepicker_final_date) : ''; ?>" autocomplete="off">
			&emsp;
			<label for="datepicker-final-date">Gid</label>
			<input id="gid" name="gid" class="" type="text" placeholder="Enter gid" value="<?php echo ( isset( $get_gid ) && $get_gid != '' ) ? esc_attr( $get_gid ) : ''; ?>" autocomplete="off">

			<input type="submit" id="datepicker-doaction" class="button action" value="<?php esc_html_e( 'Filter', 'courtres' ); ?>">
			<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres-reservations&view-expired=1' )); ?>" class="button button-secondary action<?php echo ( $is_view_expired ? ' active' : '' ); ?>"><?php esc_html_e( 'View Expired', 'courtres' ); ?></a>
			<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres-reservations' )); ?>" class="button button-secondary action"><span class="dashicons dashicons-no-alt" style="vertical-align: -5px;"></span><?php esc_html_e( 'Clear Filter', 'courtres' ); ?></a>
		</form>
		<p> </p>
	</div>
	<!--    SOFT CHANGES END -->
	<hr class="wp-header-end">

	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<!--          SOFT CHANGES START -->
				<!-- Added changes: links with parameters to filter data -->
				<th class="manage-column column-title column-primary ">Gid</th>
				<th class="manage-column column-title column-primary ">

					<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres-reservations' )); ?>&order_by=court<?php echo ( $get_order_by == 'court' ) ? ( $get_way == 'DESC' ? '' : '&way=DESC' ) : ''; ?>&datepicker-start-date=<?php echo esc_html($get_datepicker_start_date); ?>&datepicker-final-date=<?php echo esc_html($get_datepicker_final_date); ?>">
						<?php echo esc_html__( 'Court', 'court-reservation' ); ?> <?php echo ( $get_order_by == 'court' ) ? ( $get_way == 'DESC' ? '<span class="dashicons dashicons-arrow-down"></span>' : '<span class="dashicons dashicons-arrow-up"></span>' ) : ''; ?>
					</a>
				</th>
				<th class="manage-column column-title column-primary">
					<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres-reservations' )); ?>&order_by=player<?php echo ( $get_order_by == 'player' ) ? ( $get_way == 'DESC' ? '' : '&way=DESC' ) : ''; ?>&datepicker-start-date=<?php echo esc_html($get_datepicker_start_date); ?>&datepicker-final-date=<?php echo esc_html($get_datepicker_final_date); ?>">
						<?php echo esc_html__( 'Player', 'court-reservation' ); ?> <?php echo ( $get_order_by == 'player' ) ? ( $get_way == 'DESC' ? '<span class="dashicons dashicons-arrow-down"></span>' : '<span class="dashicons dashicons-arrow-up"></span>' ) : ''; ?>
					</a></th>
				<th class="manage-column column-title column-primary"><?php echo esc_html__( 'Teammates', 'court-reservation' ); ?></th>
				<th class="manage-column column-title column-primary">
					<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres-reservations' )); ?>&order_by=type<?php echo ( $get_order_by == 'type' ) ? ( $get_way == 'DESC' ? '' : '&way=DESC' ) : ''; ?>&datepicker-start-date=<?php echo esc_html($get_datepicker_start_date); ?>&datepicker-final-date=<?php echo esc_html($get_datepicker_final_date); ?>">
						<?php echo esc_html__( 'Type', 'court-reservation' ); ?> <?php echo ( $get_order_by == 'type' ) ? ( $get_way == 'DESC' ? '<span class="dashicons dashicons-arrow-down"></span>' : '<span class="dashicons dashicons-arrow-up"></span>' ) : ''; ?>
					</a>
				</th>
				<th class="manage-column column-title column-primary">
					<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres-reservations' )); ?>&order_by=date<?php echo ( $get_order_by == 'date' ) ? ( $get_way == 'DESC' ? '' : '&way=DESC' ) : ''; ?>&datepicker-start-date=<?php echo esc_html($get_datepicker_start_date); ?>&datepicker-final-date=<?php echo esc_html($get_datepicker_final_date); ?>">
						<?php echo esc_html__( 'Date', 'court-reservation' ); ?> <?php echo ( $get_order_by == 'date' ) ? ( $get_way == 'DESC' ? '<span class="dashicons dashicons-arrow-down"></span>' : '<span class="dashicons dashicons-arrow-up"></span>' ) : ''; ?>
					</a></th>
				<th class="manage-column column-title column-primary"><?php echo esc_html__( 'Time', 'court-reservation' ); ?></th>
				<th class="manage-column column-title"><?php echo esc_html__( 'Action', 'court-reservation' ); ?></th>
				<!--          SOFT CHANGES END-->
			</tr>
		</thead>
		<tbody>
			<!--    SOFT CHANGES START -->
			<!--      --><?php // for($i=0;$i<sizeof($reservations);$i++) { $item = $reservations[$i]; ?>
			<!-- Changed for to foreach to make correct pagination -->
			<?php
			$nb_elem_per_page = 30; // total elements on page
			$page             = isset( $_GET['page_no'] ) ? intval( $_GET['page_no'] - 1 ) : 0; // current page
			$number_of_pages  = intval( count( $reservations ) / $nb_elem_per_page ) + 1; // total pages

			foreach ( array_slice( $reservations, $page * $nb_elem_per_page, $nb_elem_per_page ) as $item ) :
				?>
				<tr>
					<td><?php echo esc_html( $item->gid ); ?></td>
					<td><?php echo esc_html( $item->courtname ); ?></td>
					<td><?php echo esc_html( ( new WP_User( $item->userid ) )->display_name ); ?></td>
					<td>
						<!--   List of Partners   -->
						<?php echo esc_html( implode( ', ', $item->players_names ) ); ?>
					</td>
					<td><?php echo esc_html( $item->type ); ?></td>
					<td>
						<?php echo esc_html(date_i18n( get_option( 'date_format' ), strtotime( $item->date ) )); ?>
					</td>
					<td>
						<?php echo esc_html(date_i18n( 'H:i', $item->start_ts )); ?>&ndash;<?php echo esc_html(date_i18n( 'H:i', $item->end_ts )); ?>
					</td>
					<td>
						<form method="POST">
							<input type="hidden" name="id" value="<?php echo esc_attr( $item->id ); ?>"/>
							<input class="button" type="submit" name="delete" value="<?php echo esc_attr__( 'Delete', 'court-reservation' ); ?>"/>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
			<!--    SOFT CHANGES END -->
		</tbody>
	</table>
	<!--    SOFT CHANGES START -->
	<!-- Pagination buttons -->
	<ul id='paginator'>
		<?php if ( $page != '0' ) : // Previous btn ?>
			<li><a class="page"
					 href='./admin.php?page=courtres-reservations&page_no=<?php echo esc_attr( $page ); ?>&order_by=<?php echo esc_attr( $get_order_by ); ?>&way=<?php echo esc_attr( $get_way ); ?>&datepicker-start-date=<?php echo esc_attr( $get_datepicker_start_date ); ?>&datepicker-final-date=<?php echo esc_attr( $get_datepicker_final_date ); ?>'><?php echo esc_html__( 'Previous', 'court-reservation' ); ?></a>
			</li>
			<?php
		endif; // First page
		if ( $page + 1 > '5' ) :
			?>
			<li><a class="page"
					href='./admin.php?page=courtres-reservations&page_no=1&order_by=<?php echo esc_attr( $get_order_by ); ?>&way=<?php echo esc_attr( $get_way ); ?>&datepicker-start-date=<?php echo esc_attr( $get_datepicker_start_date ); ?>&datepicker-final-date=<?php echo esc_attr( $get_datepicker_final_date ); ?>'>1</a></li>
			<li>...</li>
			<?php
		endif; // 5 pages on two sides
		for ( $i = max( 1, $page - 5 ); $i <= min( $page + 5, $number_of_pages ); $i++ ) :
			?>
			<li><a class="page <?php echo $page + 1 == $i ? 'active' : ''; ?>"
					href='./admin.php?page=courtres-reservations&page_no=<?php echo esc_attr( $i ); ?>&order_by=<?php echo esc_attr( $get_order_by ); ?>&way=<?php echo esc_attr( $get_way ); ?>&datepicker-start-date=<?php echo esc_attr( $get_datepicker_start_date ); ?>&datepicker-final-date=<?php echo esc_attr( $get_datepicker_final_date ); ?>'><?php echo esc_html( $i ); ?></a>
			</li>
			<?php
		endfor; // Last page + Next btn
		if ( $page + 1 != $number_of_pages ) :
			?>
			<li>...</li>
			<li><a class="page"
					href='./admin.php?page=courtres-reservations&page_no=<?php echo esc_attr( $number_of_pages ); ?>&order_by=<?php echo esc_attr( $get_order_by ); ?>&way=<?php echo esc_attr( $get_way ); ?>&datepicker-start-date=<?php echo esc_attr( $get_datepicker_start_date ); ?>&datepicker-final-date=<?php echo esc_attr( $get_datepicker_final_date ); ?>'><?php echo esc_html( $number_of_pages ); ?></a>
			</li>
			<li><a class="page"
					href='./admin.php?page=courtres-reservations&page_no=<?php echo esc_attr( $page + 2 ); ?>&order_by=<?php echo esc_attr( $get_order_by ); ?>&way=<?php echo esc_attr( $get_way ); ?>&datepicker-start-date=<?php echo esc_attr( $get_datepicker_start_date ); ?>&datepicker-final-date=<?php echo esc_attr( $get_datepicker_final_date ); ?>'><?php echo esc_html__( 'Next', 'court-reservation' ); ?></a>
			</li>
		<?php endif; ?>
	</ul>
	<!--    SOFT CHANGES END-->
	<p></p>
</div>
