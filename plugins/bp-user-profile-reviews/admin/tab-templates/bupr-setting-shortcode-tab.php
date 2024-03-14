<?php
/**
 * BuddyPress Member Review shortcode tab.
 *
 * @package BuddyPress_Member_Reviews
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="wbcom-tab-content">
	<div class="wbcom-wrapper-admin">
		<div class="bupr-tab-header">
			<div class="wbcom-admin-title-section">
				<h3>
					<?php esc_html_e( 'Member Review Shortcode', 'bp-member-reviews' ); ?>
				</h3>
				<input type="hidden" class="bupr-tab-active" value="shortcode"/>
			</div>
		</div>
		<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view"> 
			<div class="form-table">
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label>
							<?php esc_html_e( 'Profile Review From Shortcode', 'bp-member-reviews' ); ?>
						</label>
						<p class="description">
							<?php esc_html_e( 'This shortcode will display the BuddyPress member review form.', 'bp-member-reviews' ); ?>
						</p>
					</div>
					<div class="wbcom-settings-section-options">
						<p class="description">
							<?php echo esc_attr( '[add_profile_review_form]' ); ?>
						</p>
					</div>
				</div>
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label>
							<?php esc_html_e( 'Top Rated Members Shortcode', 'bp-member-reviews' ); ?>
						</label>
						<p class="description">
							<?php esc_html_e( 'This shortcode will display Top rated members.', 'bp-member-reviews' ); ?>							
						</p>
					</div>
					<div class="wbcom-settings-section-options">
						<p class="description">
							<?php echo esc_attr( '[bupr_display_top_members]' ); ?>
						</p>
					</div>
				</div>
				<div class="wbcom-admin-title-section">
					<h3><?php esc_html_e( 'Parameters', 'bp-member-reviews' ); ?></h3>
				</div>
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label>
							<?php esc_html_e( 'title', 'bp-member-reviews' ); ?>
						</label>
						<p class="description">
							<?php esc_html_e( 'Using this parameter, you can add a title before top-rated/reviewed members listing.', 'bp-member-reviews' ); ?>							
						</p>
					</div>
					<div class="wbcom-settings-section-options">
						<p class="description">
							<?php echo esc_html__( "For example: [bupr_display_top_members title='Top Rated Member Listing']", 'bp-member-reviews' ); ?>
						</p>
					</div>
				</div>
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label>
							<?php esc_html_e( 'total_member', 'bp-member-reviews' ); ?>
						</label>
						<p class="description">
							<?php esc_html_e( 'This parameter lets you limit the number of reviews on the Top Rated/Reviewed Members listing page.', 'bp-member-reviews' ); ?>							
						</p>
					</div>
					<div class="wbcom-settings-section-options">
						<p class="description">
							<?php echo esc_html__( 'For example: [bupr_display_top_members total_member=4]', 'bp-member-reviews' ); ?>
						</p>
					</div>
				</div>
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label>
							<?php esc_html_e( 'type', 'bp-member-reviews' ); ?>
						</label>
						<p class="description">
							<?php esc_html_e( 'Using this parameter you can display members according to Top Reviewed(as per the maximum number of reviews) or Top Rated(As per the highest ratings).', 'bp-member-reviews' ); ?>							
						</p>
					</div>
					<div class="wbcom-settings-section-options">
						<p class="description">
							<?php echo esc_html__( "For example: [bupr_display_top_members type='top reviewed']", 'bp-member-reviews' ); ?>
						</p>
					</div>
				</div>
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label>
							<?php esc_html_e( 'avatar', 'bp-member-reviews' ); ?>
						</label>
						<p class="description">
							<?php esc_html_e( 'Using this parameter you can hide the members avatar.', 'bp-member-reviews' ); ?>							
						</p>
					</div>
					<div class="wbcom-settings-section-options">
						<p class="description">
							<?php echo esc_html__( "For example: [bupr_display_top_members avatar='hide']", 'bp-member-reviews' ); ?>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

