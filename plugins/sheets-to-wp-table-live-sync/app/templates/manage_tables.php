<?php
/**
 * Displays manage tabs template.
 *
 * @package SWPTLS
 */

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

?>
<?php if ( isset( $_GET['subpage'] ) && 'create-table' === sanitize_text_field( $_GET['subpage'] ) ) { //phpcs:ignore ?>
	<?php load_template( SWPTLS_BASE_PATH . 'app/templates/create_tables.php' ); ?>
<?php } else { ?>
<div class="gswpts_manage_table_container">

	<div class="ui segment gswpts_loader">
		<div class="ui active inverted dimmer">
			<div class="ui massive text loader"></div>
		</div>
		<p></p>
		<p></p>
		<p></p>
	</div>

	<div class="child_container mt-4 manage_table_content transition hidden">
		<div class="row heading_row">
			<div class="col-12 d-flex justify-content-start p-0 align-items-center">
				<img src="<?php echo esc_url( SWPTLS_BASE_URL . 'assets/public/images/logo_30_30.svg' ); ?>"
					alt="sheets-logo">
				<span class="ml-2">
					<strong><?php echo esc_html( SWPTLS_PLUGIN_NAME ); ?></strong>
				</span>
				<span class="gswpts_changelogs"></span>
			</div>
		</div>

		<div id="delete_button_container" class="row">
			<div class="col-12 p-0">
				<button id="delete_button" class="negative ui button mr-0 float-right transition hidden"
					data-show="false">
					<span>
						<?php esc_html_e( 'Delete Selected', 'sheetstowptable' ); ?> &nbsp; <i class="fas fa-trash-alt"></i>
					</span>
				</button>

				<button id="unselect_btn" class="ui violet button mr-2 float-right transition hidden">
					<span>
						<?php esc_html_e( 'Clear Selection', 'sheetstowptable' ); ?> &nbsp; <i class="fas fa-minus"></i>
					</span>
				</button>

			</div>
		</div>

		<div class="row">
			<div id="gswpts_tables_container" class="col-12 p-0 mt-3 position-relative" data-nonce="<?php echo esc_attr( wp_create_nonce( 'tables_related_nonce_action' ) ); ?>">
				<div class="ui segment gswpts_table_loader">
					<div class="ui active inverted dimmer">
						<div class="ui large text loader"><?php esc_html_e( 'Loading', 'sheetstowptable' ); ?></div>
					</div>
					<p></p>
					<p></p>
					<p></p>
				</div>
			</div>

			<!-- Start create table button -->
			<a class="positive ui button mr-2 float-left transition hidden create_table_btn"
				style="font-size: 1.03rem; position: relative;top: 0px;"
				href="<?php echo esc_url( admin_url( 'admin.php?page=gswpts-dashboard&subpage=create-table' ) ); ?>">
				<?php esc_html_e( 'Create Table', 'sheetstowptable' ); ?> &nbsp; <i class="fas fa-plus"></i>
			</a>
			<!-- End of create table button -->

			<!-- Start popup modal -->
			<div class="ui mini modal semntic-popup-modal" style="height: 180px; position: absolute; top: 40%; left: 50%; margin: -50px 0 0 -190px;">
				<div class="header">
					<?php esc_html_e( 'Delete Your Table', 'sheetstowptable' ); ?>
				</div>
				<div class="content">
					<p><b class="gswpts_popup_content"></b></p>
				</div>
				<div class="actions">
					<div class="ui negative button yes-btn">
						<?php esc_html_e( 'Yes', 'sheetstowptable' ); ?>
					</div>
					<div class="ui positive button cancel-btn">
						<?php esc_html_e( 'No', 'sheetstowptable' ); ?>
					</div>
				</div>
			</div>
			<!-- End of popup modal-->
		</div>
	</div>
</div>
<?php } ?>

<style>
.ui.dimmer.modals {
	background: none !important;
}
</style>
