<?php
/**
 * Displays popup modals.
 *
 * @package SWPTLS
 */

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

?>
<!-- Popup modal for table style -->
<div class="tableStyleModal">
	<div class="styleModal transition hidden">
		<?php require SWPTLS_BASE_PATH . 'assets/public/icons/times-circle-solid.svg'; ?>
		<div class="header">
			<h4><?php esc_html_e( 'Choose Table Style', 'sheetstowptable' ); ?></h4>
		</div>

		<div class="body">
			<?php swptls()->settings->tableStylesHtml(); ?>
		</div>

		<div class="actions">
			<div class="ui black deny button cancelBtn">
				<?php esc_html_e( 'Cancel', 'sheetstowptable' ); ?>
			</div>
			<div class="ui positive button selectBtn">
				<?php esc_html_e( 'Select', 'sheetstowptable' ); ?>
			</div>
		</div>
	</div>
</div>
<!-- End of table style popup modal -->

<!-- Popup modal for Hide Column feature  -->
<div class="hide-column-modal-wrapper">
	<div class="gswpts-hide-modal transition hidden">
		<?php require SWPTLS_BASE_PATH . 'assets/public/icons/times-circle-solid.svg'; ?>
		<div class="header">
			<h4><?php esc_html_e( 'Choose Column To Hide', 'sheetstowptable' ); ?></h4>
		</div>

		<div class="body">
			<!-- Column values to hide in desktop mode -->
			<div class="desktop-column">
				<span><?php esc_html_e( 'Hide columns in desktop:', 'sheetstowptable' ); ?></span>
				<div class="ui fluid multiple selection dropdown mt-2" id="desktop-hide-columns">
					<input type="hidden" name="desktop-hide-column-input" id="desktop-hide-column-input">
					<i class="dropdown icon"></i>
					<div class="default text"><?php esc_html_e( 'Choose Column', 'sheetstowptable' ); ?></div>
					<div class="menu">
					</div>
				</div>
			</div>
			<!-- End of desktop column -->

			<!-- Column values to hide in mobile mode -->
			<div class="mobile-column">
				<span><?php esc_html_e( 'Hide columns in mobile:', 'sheetstowptable' ); ?></span>
				<div class="ui fluid multiple selection dropdown mt-2" id="mobile-hide-columns">
					<input type="hidden" name="mobile-hide-column-input" id="mobile-hide-column-input">
					<i class="dropdown icon"></i>
					<div class="default text"><?php esc_html_e( 'Choose Column', 'sheetstowptable' ); ?></div>
					<div class="menu">
					</div>
				</div>
			</div>
			<!-- End of mobile column -->
		</div>

		<div class="actions">
			<div class="ui black deny button cancelBtn">
				<?php esc_html_e( 'Cancel', 'sheetstowptable' ); ?>
			</div>
			<div class="ui positive button selectBtn">
				<?php esc_html_e( 'Select', 'sheetstowptable' ); ?>
			</div>
		</div>
	</div>
</div>
<!-- End of Hide Column popup modal -->

<!-- Popup modal for Hide Rows feature  -->
<div class="hide-rows-modal-wrapper">
	<div class="gswpts-hide-modal transition hidden">
		<?php require SWPTLS_BASE_PATH . 'assets/public/icons/times-circle-solid.svg'; ?>
		<div class="header">
			<h4><?php esc_html_e( 'Activate Row Hiding Feature', 'sheetstowptable' ); ?></h4>
		</div>

		<div class="body">
			<div class="column">
				<span><?php esc_html_e( 'Hidden Rows:', 'sheetstowptable' ); ?></span>
				<div class="ui fluid multiple selection dropdown mt-2" id="hidden_rows">
					<input type="hidden" name="hidden_rows-input" id="hidden_rows-input">
					<i class="dropdown icon"></i>
					<div class="default text"><?php esc_html_e( 'Hidden Rows', 'sheetstowptable' ); ?></div>
					<div class="menu">
					</div>
				</div>
			</div>
		</div>

		<div class="actions">
			<div class="ui black deny button cancelBtn">
				<span><?php esc_html_e( 'Cancel', 'sheetstowptable' ); ?></span>
			</div>
			<div class="ui toggle checkbox">
				<?php $is_pro = swptls()->settings->tableToolsArray()['hide_rows']['is_pro']; ?> 
				<input
					type="checkbox"
					class="<?php echo ( isset( $is_pro ) && $is_pro ) ? 'pro_feature_input' : ''; ?> selectBtn"
					name="active_hide_rows" id="active_hide_rows"
				>
				<label for="active_hide_rows"></label>
			</div>
		</div>
	</div>
</div>
<!-- End of Hide Rows popup modal -->

<!-- Popup modal for Hide Cell feature  -->
<div class="hide-cell-modal-wrapper">
	<div class="gswpts-hide-modal transition hidden">
		<?php require SWPTLS_BASE_PATH . 'assets/public/icons/times-circle-solid.svg'; ?>
		<div class="header">
			<h4><?php esc_html_e( 'Activate Cell Hiding Feature', 'sheetstowptable' ); ?></h4>
		</div>

		<div class="body">
			<div class="column">
				<span><?php esc_html_e( 'Hidden Cell:', 'sheetstowptable' ); ?></span>
				<div class="ui fluid multiple selection dropdown mt-2" id="hidden_cells">
					<input type="hidden" name="hidden_cells-input" id="hidden_cells-input">
					<i class="dropdown icon"></i>
					<div class="default text"><?php esc_html_e( 'Hidden Cells', 'sheetstowptable' ); ?></div>
					<div class="menu">
					</div>
				</div>
			</div>
		</div>

		<div class="actions">
			<div class="ui black deny button cancelBtn">
				<?php esc_html_e( 'Cancel', 'sheetstowptable' ); ?>
			</div>
			<div class="ui toggle checkbox">
				<?php $is_pro = swptls()->settings->tableToolsArray()['hide_cell']['is_pro']; ?>
				<input type="checkbox"
					class="<?php echo ( isset( $is_pro ) && $is_pro ) ? 'pro_feature_input' : ''; ?> selectBtn"
					name="active_hidden_cells" id="active_hidden_cells">
				<label for="active_hidden_cells"></label>
			</div>
		</div>
	</div>
</div>
<!-- End of Hide Cell popup modal -->
