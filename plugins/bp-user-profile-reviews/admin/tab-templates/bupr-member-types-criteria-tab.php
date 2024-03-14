<?php
/**
 * BuddyPress Member Review member types criteria tab form.
 *
 * @package BuddyPress_Member_Reviews
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/* admin setting on dashboard */
$enabled_criterias   = array();
$bupr_admin_settings = get_option( 'bupr_admin_settings', true );
if ( ! empty( $bupr_admin_settings ) && ! empty( $bupr_admin_settings['profile_rating_fields'] ) ) {
	$profile_rating_fields    = $bupr_admin_settings['profile_rating_fields'];
	$enabled_criterias['all'] = esc_html__( 'All Criterias', 'bp-member-reviews' );
	foreach ( $profile_rating_fields as $criteria => $criteria_setting ) {
		if ( $criteria_setting == 'yes' ) {
			$enabled_criterias[ $criteria ] = $criteria;
		}
	}
}


$bupr_member_type_criteria = get_option( 'bupr_member_type_criteria' );

$member_types = bp_get_member_types( '', 'objects' );
?>
<div class="wbcom-tab-content">
	<div class="bupr-adming-setting">
		<div class="bupr-tab-header">
			<h3>
				<?php esc_html_e( 'Set review criteria according to member types', 'bp-member-reviews' ); ?>
			</h3>
			<input type="hidden" class="bupr-tab-active" value="member_type_criteria"/>
		</div>
		<div class="bupr-admin-row border">
			<form method="post" action="options.php">
				<?php
				settings_fields( 'bupr_member_type_criteria' );
				do_settings_sections( 'bupr_member_type_criteria' );

				if ( is_array( $member_types ) && ! empty( $member_types ) ) {
					foreach ( $member_types as $type => $member_obj ) {
						?>
						<div class="bupr-admin-row border">
							<div class="bupr-admin-col-6 bupr-label">
								<label for="bupr-multi-review">
									<?php echo esc_html( $member_obj->labels['name'] ); ?>
								</label>
							</div>
							<div class="bupr-admin-col-6 ">
								<select name="bupr_member_type_criteria[<?php echo esc_attr( $type ); ?>][]" multiple class="bupr_mt_criteria_select">
									<option value=''><?php esc_html_e( 'Select criterias', 'bp-member-reviews' ); ?></option>
									<?php
									foreach ( $enabled_criterias as $key => $criteria ) {
										$_criterias = isset( $bupr_member_type_criteria[ $type ] ) ? $bupr_member_type_criteria[ $type ] : '';
										if ( in_array( $key, $_criterias ) ) {
											$selected = 'selected';
										} else {
											$selected = '';
										}
										echo "<option value='" . esc_attr( $key ) . "' " . esc_attr( $selected ) . '>' . esc_html( $criteria ) . '</option>';
									}
									?>
								</select>
								<p class="description"><?php esc_html_e( 'Only selected criteria will be available for respective member types.', 'bp-member-reviews' ); ?></p>
							</div>
						</div>
						<?php
					}
				}
				?>
				<?php submit_button(); ?>
			</form>
		</div>
	</div>
</div>
