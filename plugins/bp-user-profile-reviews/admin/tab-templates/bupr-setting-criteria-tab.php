<?php
/**
 * BuddyPress Member Review criteria tab.
 *
 * @package BuddyPress_Member_Reviews
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/* admin setting on dashboard */
global $bupr;
$bupr_multi_rating_allowed_class = 'bupr-show-if-allowed';
if ( '1' == $bupr['multi_criteria_allowed'] ) {
	$bupr_multi_rating_allowed_class = '';
}
?>
<div class="wbcom-tab-content">
	<form method="post" action="options.php">
		<?php
		settings_fields( 'bupr_admin_settings' );
		do_settings_sections( 'bupr_admin_settings' );
		?>
		<div class="wbcom-admin-title-section">
		<h3 class="title">
			<?php esc_html_e( 'Review Criteria(s)', 'bp-member-reviews' ); ?>
		</h3>
		</div>
		<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">
			<div class="form-table">
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label>
								<?php esc_html_e( 'Allow Multiple Criteria(s)?', 'bp-member-reviews' ); ?>
							</label>
							<p class="description"><?php esc_html_e( "Enable this option,if you want to allow members to be rated by 'Criteria(s)'.", 'bp-member-reviews' ); ?></p>
						</div>
						<label class="bupr-switch">
							<input type="checkbox" value='1' name="bupr_admin_settings[profile_multi_rating_allowed]" id="bupr_allow_multiple_criteria" <?php checked( esc_attr( $bupr['multi_criteria_allowed'] ), '1' ); ?>>
							<div class="bupr-slider bupr-round"></div>
						</label>
					</div>
			</div>
			<div class="wbcom-settings-section-wrap <?php echo esc_attr( $bupr_multi_rating_allowed_class ); ?>">
				<div class="bupr-admin-settings-block">
					<div id="bupr-settings-tbl" class="bupr-table bupr-criteria-settings-tbl">
					<div id="buprTextBoxContainer">
						<?php
						if ( ! empty( $bupr['active_rating_fields'] ) ) {
							$key = 0;
							foreach ( $bupr['active_rating_fields'] as $profile_rating_field => $bupr_criteria_setting ) :
								$key_val = rand();
								if ( function_exists( 'icl_register_string' ) ) {
									icl_register_string( 'bp-member-reviews', 'rating_field_name_' . $key++, $profile_rating_field );
								}
								?>
								<div id="bupr-admin-row-criteria" class="bupr-admin-row bupr-criteria bupr-criteria-fields border draggable">
									<div class="bupr-admin-col-6">
										<span class="move-icons">&equiv;</span>
										<input name="bupr_admin_settings[rating_field_name][<?php echo esc_attr( $key_val ); ?>]" class="buprDynamicTextBox" type="text" value="<?php echo esc_attr( $profile_rating_field ); ?>" />
									</div>

									<div class="bupr-admin-col-6 buprcriteria">
										<p class="bupr-delete-tag">
											<input type="button" value="<?php esc_html_e( 'Delete', 'bp-member-reviews' ) ?>" class="bupr-criteria-remove-button bupr-remove button button-secondary" />
											<!-- <span class="description">
											<?php //esc_html_e( 'Remove criteria fields permanently.', 'bp-member-reviews' ); ?>
											</span> -->
										</p>
										<label>
											<input name="bupr_admin_settings[rating_field_name_display][<?php echo esc_attr( $key_val ); ?>]" type="checkbox" class="bupr_enable_criteria" value="<?php echo 'yes'; ?>" <?php checked( $bupr_criteria_setting, 'yes' ); ?>>
											<span class="description">
											<?php esc_html_e( 'Enable/Disable criteria fields from review form.', 'bp-member-reviews' ); ?>
											</span>
										</label>
									</div>
								</div>
								<?php
							endforeach;
						}
						?>
						</div>
					</div>

				<div id="bupr-add-criteria-action" class="bupr-admin-row border">
					<div class="bupr-admin-col-12">
						<input id="bupr-btnAdd" type="button" value="<?php esc_html_e('Add Criteria' , 'bp-member-reviews') ?>" class="button button-secondary"/>
						<p class="description"><?php esc_html_e( 'This option will allow you to add multiple rating criteria. By default, no citeria will be shown until you activate it.', 'bp-member-reviews' ); ?></p>
					</div>
				</div>
				</div>
			</div>
			<?php submit_button(); ?>
		</div>	
	</form>
</div>
