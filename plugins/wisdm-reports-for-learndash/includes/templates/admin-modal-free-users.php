<?php
/**
 * This file contains the onboarding modal html structure.
 *
 * @package learndash-reports-by-wisdmlabs
 */

?>
<div id="wrld-la-custom-modal" class="wrld-la-custom-popup-modal" wp_nonce=<?php echo esc_html( $wp_nonce ); ?>>
	<div class="wrld-la-modal-content">
		<div class="wrld-la-modal-content-container2">
			<a class="wrld-dismiss-link" href="<?php echo esc_html( $dismiss_link ); ?>"><span class="dashicons dashicons-no-alt wrld-close-modal-btn2"></span></a>
			<div class="wrld-la-modal-head">
				<img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/wisdmlabs.png' ); ?>">
				<span>WISDM Reports For LearnDash</span>
			</div>
			<div class="wrld-la-modal-text2">
				<h3>What's new in Version 1.4.2</h3>
				<div><strong>Introducing Learner Activity reports in Reports PRO</strong> we have introduced two new reporting blocks:
					<ul>
						<li><strong>Inactive users list and Learner activity log</strong> to help you to improve your learner completion rate.</li>
					</ul>
				</div>
				<div>
					<div><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/list.png' ); ?>"/><strong>Fixed:</strong> Group leaders with no groups/courses assigned will not be shown reports for any courses/learners on the Reports dashboard</div>
					<div><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/list.png' ); ?>"/><strong>Fixed:</strong> Issue resolved for Date picker calendar getting cropped and not showing properly</div>
				</div>
			</div>
			<div class="wrld-la-modal-second-head2">
				<h2>Want more such Insightful Reports? We have an <span>Amazing Offer</span> for you!</h2>
			</div>
			<div class="special-section">
				<div class="styled-div">
					Upgrade to Pro at just <s>$120</s> $99
				</div>
				<div class="arrow-right"></div>
				<div class="button2"><a href="<?php echo esc_url( 'https://wisdmlabs.com/checkout/?edd_action=add_to_cart&download_id=707478&edd_options%5Bprice_id%5D=7&discount=upgradetopro&utm_source=wrld&utm_medium=wrld_update_popup&utm_campaign=wrld_in_plugin_settings_tab' ); ?>" target="_blank">Upgrade Now</a></div>
			</div>
			<div class="table-section">
				<table class="features-table">
					<thead>
						<tr>
							<th>Power of the WISDM Reports Free vs Pro</th>
							<th>Free</th>
							<th>Pro</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>All Free Features</td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/yes.png' ); ?>" /></td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/yes.png' ); ?>" /></td>
						</tr>	
						<tr>
							<td>All Learners Progress Summary Report</td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/no.png' ); ?>" /></td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/yes.png' ); ?>" /></td>
						</tr>	
						<tr>
							<td>Learner-specific Progress Summary Report</td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/no.png' ); ?>" /></td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/yes.png' ); ?>" /></td>
						</tr>	
						<tr>
							<td>Learner's Time spent on courses report</td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/no.png' ); ?>" /></td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/yes.png' ); ?>" /></td>
						</tr>	
						<tr>
							<td>All Quiz Attempts Report</td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/no.png' ); ?>" /></td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/yes.png' ); ?>" /></td>
						</tr>	
						<tr>
							<td>Course-wise Quiz attempt Reports</td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/no.png' ); ?>" /></td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/yes.png' ); ?>" /></td>
						</tr>	
						<tr>
							<td>Group-wise Quiz Attempts Reports</td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/no.png' ); ?>" /></td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/yes.png' ); ?>" /></td>
						</tr>	
						<tr>
							<td>Quiz-wise Quiz Attempts reports</td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/no.png' ); ?>" /></td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/yes.png' ); ?>" /></td>
						</tr>	
						<tr>
							<td>Learner-wise Quiz Attempts Reports</td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/no.png' ); ?>" /></td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/yes.png' ); ?>" /></td>
						</tr>	
						<tr>
							<td>Detailed Quiz Attempt Report</td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/no.png' ); ?>" /></td>
							<td><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL . '/assets/images/yes.png' ); ?>" /></td>
						</tr>	
					</tbody>
				</table>
				<div class="table-overlay"></div>
			</div>
			<div><span class="less">View More Features</span></div>
		</div>
	</div>
</div>
