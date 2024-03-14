<?php
/**
 * EMAIL TEMPLATE: Send when GSC performance report from last week is ready.
 *
 * @package SurferSEO
 */

?>

<div style="background-color: #F8FAFB; padding: 40px;">

	<img src="<?php echo esc_url( Surfer()->get_baseurl() ); ?>/assets/images/emails/surfer_logo.png" alt="Surfer" style="display: block; width: 114px; height: auto; margin: 0px auto 40px;">

	<div style="padding: 32px; width: 720px; box-sizing: border-box; background-color: #ffffff; margin: 0px auto;">
		<?php /* translators: %s: Site URL. */ ?>
		<h1 style="font-size: 24px; font-weight: 600; line-height: 32px; font-family: Helvetica; margin-bottom: 48px;"><?php printf( esc_html__( 'Weekly Surfer Performance for %s', 'surferseo' ), esc_html( preg_replace( '/^https?:\/\//', '', home_url() ) ) ); ?></h1>

		<p style="font-size: 20px; font-weight: 400; line-height: 28px; font-family: Helvetica;">
			<?php if ( isset( $update_date ) ) : ?>
				<?php /* translators: %s: date. */ ?>
				<?php printf( esc_html__( 'Performance report for %s', 'surferseo' ), esc_html( $update_date ) ); ?>  
			<?php endif; ?>
		</p>

		<table style="width: 100%; border: 0px;" cellspacing="0" cellpadding="0">
			<tr>
				<td style="width: 312px; padding: 16px; font-size: 24px; font-weight: 500; font-family: Helvetica; line-height: 32px; color: #222A3A; border-bottom: 1px solid #E2E8F0;">
					<span>
						<?php echo isset( $summary_impressions ) ? intval( $summary_impressions ) : 0; ?>
					</span>
					<?php if ( isset( $summary_impressions ) && isset( $summary_impressions_prev ) ) : ?>
						<?php if ( $summary_impressions > $summary_impressions_prev ) : ?> 
							<span style="color: #338F61; vertical-align: middle; font-size: 16px; font-weight: 400; line-height: 24px; font-family: Helvetica;">
								+<?php echo intval( abs( $summary_impressions - $summary_impressions_prev ) ); ?>
							</span>
						<?php elseif ( $summary_impressions < $summary_impressions_prev ) : ?> 
							<span style="color: #E53E3E; vertical-align: middle; font-size: 16px; font-weight: 400; line-height: 24px; font-family: Helvetica;">
								-<?php echo intval( abs( $summary_impressions - $summary_impressions_prev ) ); ?>
							</span>						
						<?php endif; ?>
					<?php endif; ?>

					<br/>

					<span style="font-size: 16px; font-weight: 400; line-height: 24px; margin-top: 8px; display: block;">
					<img src="<?php echo esc_url( Surfer()->get_baseurl() ); ?>/assets/images/emails/eye.png" alt="Impressions" style="width: 24px; vertical-align: top;" />
						<?php esc_html_e( 'Total Impressions', 'surferseo' ); ?>
					<span>
				</td>
				<td style="width: 32px;">
					&nbsp;
				</td>
				<td style="width: 312px; padding: 16px; font-size: 24px; font-weight: 500; font-family: Helvetica; line-height: 32px; color: #222A3A; border-bottom: 1px solid #E2E8F0;">
					<span>
						<?php echo isset( $summary_clicks ) ? intval( $summary_clicks ) : 0; ?>
					</span>
					<?php if ( isset( $summary_clicks ) && isset( $summary_clicks_prev ) ) : ?>
						<?php if ( $summary_clicks > $summary_clicks_prev ) : ?> 
							<span style="color: #338F61; vertical-align: middle; font-size: 16px; font-weight: 400; line-height: 24px; font-family: Helvetica;">
								+<?php echo intval( abs( $summary_clicks - $summary_clicks_prev ) ); ?>
							</span>
						<?php elseif ( $summary_clicks < $summary_clicks_prev ) : ?> 
							<span style="color: #E53E3E; vertical-align: middle; font-size: 16px; font-weight: 400; line-height: 24px; font-family: Helvetica;">
								-<?php echo intval( abs( $summary_clicks - $summary_clicks_prev ) ); ?>
							</span>
						<?php endif; ?>
					<?php endif; ?>
						
					<br/>

					<span style="font-size: 16px; font-weight: 400; line-height: 24px; margin-top: 8px; display: block;">
						<img src="<?php echo esc_url( Surfer()->get_baseurl() ); ?>/assets/images/emails/cursor-arrow-rays.png" alt="Clicks" style="width: 24px; vertical-align: top;" />
						<?php esc_html_e( 'Total Clicks', 'surferseo' ); ?>
					</span>
				</td>
			</tr>

			<tr>
				<td style="padding: 16px; font-size: 24px; font-weight: 500; font-family: Helvetica; line-height: 32px; color: #222A3A;">
					<?php /* translators: %d number of posts. */ ?>
					<?php printf( esc_html__( '%d posts', 'surferseo' ), isset( $posts_down ) ? intval( $posts_down ) : 0 ); ?> <br/>

					<span style="font-size: 16px; font-weight: 400; line-height: 24px; margin-top: 8px; display: block;">
						<?php esc_html_e( 'Dropped in ranking', 'surferseo' ); ?>
					</span>
				</td>
				<td style="width: 32px;">
					&nbsp;
				</td>
				<td style="padding: 16px; font-size: 24px; font-weight: 500; font-family: Helvetica; line-height: 32px; color: #222A3A;">
					<?php /* translators: %d number of posts. */ ?>
					<?php printf( esc_html__( '%d posts', 'surferseo' ), isset( $posts_up ) ? intval( $posts_up ) : 0 ); ?> <br/>

					<span style="font-size: 16px; font-weight: 400; line-height: 24px; margin-top: 8px; display: block;">
						<?php esc_html_e( 'Rank higher', 'surferseo' ); ?>
					</span>
				</td>
			</tr>
		</table>


		<div style="width: 100%; margin-top: 24px; padding-bottom: 48px; border-bottom: 1px dashed #E2E8F0; text-align: center;">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=surfer-performance-report' ) ); ?><?php isset( $tracking_enabled ) && $tracking_enabled ? '&utm_surfer=surfr-email-performance-report-click' : ''; ?>" target="_blank" style="padding: 8px 24px; border-radius: 8px; text-decoration: none; background-color: #222A3A; color: #ffffff; font-size: 16px; font-weight: 600; line-height: 24px; font-family: Helvetica;"><?php esc_html_e( 'View this week’s report', 'surferseo' ); ?></a>
		</div>

		<?php if ( isset( $posts_drops_in_top_10 ) && isset( $posts_drops_that_droped_to_next_10 ) && isset( $posts_out_of_index ) && count( $posts_drops_in_top_10 ) + count( $posts_drops_that_droped_to_next_10 ) + count( $posts_out_of_index ) > 0 ) : ?>

		<h2 style="font-size: 20px; font-weight: 600; line-height: 28px; font-family: Helvetica; margin-top: 48px;">
			<?php esc_html_e( 'Dropped in ranking', 'surferseo' ); ?>
		</h2>

		<?php endif; ?>

		<?php if ( isset( $posts_drops_in_top_10 ) && count( $posts_drops_in_top_10 ) > 0 ) : ?>
			<h3 style="font-size: 16px; font-weight: 600; line-height: 24px; font-family: Helvetica; margin-top: 24px;">
				<?php esc_html_e( 'Drops from top 10 SERP results', 'surferseo' ); ?>
			</h3>

			<ul style="list-style: none; padding-left: 0px;">
				<?php foreach ( $posts_drops_in_top_10 as $surfer_i => $surfer_post ) : ?>
					<li style="margin-bottom: 16px;">
						<span style="font-size: 16px; font-weight: 600; line-height: 24px; font-family: Helvetica; color: #E53E3E;">
							-<?php echo intval( $surfer_post->position_change ); ?>
						</span>
						<a href="<?php echo esc_url( the_permalink( $surfer_post->post_id ) ); ?><?php $tracking_enabled ? '?utm_surfer=email-performance-report-top10-drop-click' : ''; ?>" style="font-size: 16px; font-weight: 400; line-height: 24px; font-family: Helvetica; color: #2B6CB0;"><?php echo esc_html( $surfer_post->post_title ); ?></a>
					</li>
					<?php if ( $surfer_i >= 9 ) : ?>
						<li style="margin-bottom: 16px; font-size: 16px; font-weight: 400; line-height: 24px; font-family: Helvetica;">
							<?php /* translators: %d number of posts. */ ?>
							<?php printf( esc_html__( '...and %d more', 'surferseo' ), intval( count( $posts_drops_in_top_10 ) - 10 ) ); ?>
						</li>
						<?php break; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( isset( $posts_drops_that_droped_to_next_10 ) && count( $posts_drops_that_droped_to_next_10 ) > 0 ) : ?>
			<h3 style="font-size: 16px; font-weight: 600; line-height: 24px; font-family: Helvetica; margin-top: 24px;">
				<?php esc_html_e( 'Other drops in SERPs', 'surferseo' ); ?>
			</h3>

			<ul style="list-style: none; padding-left: 0px;">
				<?php foreach ( $posts_drops_that_droped_to_next_10 as $surfer_i => $surfer_post ) : ?>
					<li style="margin-bottom: 16px;">
						<span style="font-size: 16px; font-weight: 600; line-height: 24px; font-family: Helvetica; color: #E53E3E;">
							-<?php echo intval( $surfer_post->position_change ); ?>
						</span>
						<a href="<?php echo esc_url( the_permalink( $surfer_post->post_id ) ); ?><?php $tracking_enabled ? '?utm_surfer=email-performance-report-other-drop-click' : ''; ?>" style="font-size: 16px; font-weight: 400; line-height: 24px; font-family: Helvetica; color: #2B6CB0;"><?php echo esc_html( $surfer_post->post_title ); ?></a>
					</li>
					<?php if ( $surfer_i >= 4 ) : ?>
						<li style="margin-bottom: 16px; font-size: 16px; font-weight: 400; line-height: 24px; font-family: Helvetica;">
							<?php /* translators: %d number of posts. */ ?>
							<?php printf( esc_html__( '...and %d more', 'surferseo' ), intval( count( $posts_drops_that_droped_to_next_10 ) - 5 ) ); ?>
						</li>
						<?php break; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( isset( $posts_out_of_index ) && count( $posts_out_of_index ) > 0 ) : ?>
			<h3 style="font-size: 16px; font-weight: 600; line-height: 24px; font-family: Helvetica; margin-top: 24px;">
				<?php esc_html_e( 'Deindexed pages', 'surferseo' ); ?>
			</h3>

			<ul style="list-style: none; padding-left: 0px;">
				<?php foreach ( $posts_out_of_index as $surfer_i => $surfer_post ) : ?>
					<li style="margin-bottom: 16px;">
						<span style="font-size: 16px; font-weight: 600; line-height: 24px; font-family: Helvetica; color: #E53E3E;">
							0th
						</span>
						<a href="<?php echo esc_url( the_permalink( $surfer_post->post_id ) ); ?><?php $tracking_enabled ? '?utm_surfer=email-performance-report-deindexed-click' : ''; ?>" style="font-size: 16px; font-weight: 400; line-height: 24px; font-family: Helvetica; color: #2B6CB0;"><?php echo esc_html( $surfer_post->post_title ); ?></a>
					</li>
					<?php if ( $surfer_i >= 4 ) : ?>
						<li style="margin-bottom: 16px; font-size: 16px; font-weight: 400; line-height: 24px; font-family: Helvetica;">
							<?php /* translators: %d number of posts. */ ?>
							<?php printf( esc_html__( '...and %d more', 'surferseo' ), intval( count( $posts_out_of_index ) - 5 ) ); ?>
						</li>
						<?php break; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( isset( $posts_growth ) && isset( $posts_indexed ) && count( $posts_growth ) + count( $posts_indexed ) > 0 ) : ?>

			<h2 style="font-size: 20px; font-weight: 600; line-height: 28px; font-family: Helvetica; margin-top: 48px;">
				<?php esc_html_e( 'Increased in ranking', 'surferseo' ); ?>
			</h2>

		<?php endif; ?>

		<?php if ( isset( $posts_growth ) && count( $posts_growth ) > 0 ) : ?>

			<h3 style="font-size: 16px; font-weight: 600; line-height: 24px; font-family: Helvetica; margin-top: 24px;">
				<?php esc_html_e( 'Organic growth', 'surferseo' ); ?>
			</h3>

			<ul style="list-style: none; padding-left: 0px; margin-bottom: 0px;">
				<?php foreach ( $posts_growth as $surfer_i => $surfer_post ) : ?>
					<li style="margin-bottom: 16px;">
						<span style="font-size: 16px; font-weight: 600; line-height: 24px; font-family: Helvetica; color: #338F61;">
							+<?php echo intval( abs( $surfer_post->position_change ) ); ?>
						</span>
						<a href="<?php echo esc_url( the_permalink( $surfer_post->post_id ) ); ?><?php $tracking_enabled ? '?utm_surfer=email-performance-report-growth-click' : ''; ?>" style="font-size: 16px; font-weight: 400; line-height: 24px; font-family: Helvetica; color: #2B6CB0;"><?php echo esc_html( $surfer_post->post_title ); ?></a>
					</li>
					<?php if ( $surfer_i >= 4 ) : ?>
						<li style="margin-bottom: 16px; font-size: 16px; font-weight: 400; line-height: 24px; font-family: Helvetica;">
							<?php /* translators: %d number of posts. */ ?>
							<?php printf( esc_html__( '...and %d more', 'surferseo' ), intval( count( $posts_growth ) - 5 ) ); ?>
						</li>
						<?php break; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( isset( $posts_indexed ) && count( $posts_indexed ) > 0 ) : ?>
			<h3 style="font-size: 16px; font-weight: 600; line-height: 24px; font-family: Helvetica; margin-top: 24px;">
				<?php esc_html_e( 'New indexed pages', 'surferseo' ); ?>
			</h3>
			
			<ul style="list-style: none; padding-left: 0px;">
				<?php foreach ( $posts_indexed as $surfer_i => $surfer_post ) : ?>
					<li style="margin-bottom: 16px;">
						<span style="font-size: 16px; font-weight: 600; line-height: 24px; font-family: Helvetica;">
							<?php echo esc_html( surfer_add_numerical_suffix( $surfer_post->position ) ); ?>
						</span>
						<a href="<?php echo esc_url( the_permalink( $surfer_post->post_id ) ); ?><?php $tracking_enabled ? '?utm_surfer=email-performance-report-indexed-click' : ''; ?>" style="font-size: 16px; font-weight: 400; line-height: 24px; font-family: Helvetica; color: #2B6CB0;"><?php echo esc_html( $surfer_post->post_title ); ?></a>
					</li>
					<?php if ( $surfer_i >= 4 ) : ?>
						<li style="margin-bottom: 16px; font-size: 16px; font-weight: 400; line-height: 24px; font-family: Helvetica;">
							<?php /* translators: %d number of posts. */ ?>
							<?php printf( esc_html__( '...and %d more', 'surferseo' ), intval( count( $posts_indexed ) - 5 ) ); ?>
						</li>
						<?php break; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( isset( $posts_drops_in_top_10 ) && isset( $posts_drops_that_droped_to_next_10 ) && isset( $posts_out_of_index ) && isset( $posts_growth ) && isset( $posts_indexed ) && count( array_merge( $posts_drops_in_top_10, $posts_drops_that_droped_to_next_10, $posts_out_of_index, $posts_growth, $posts_indexed ) ) > 0 ) : ?>
			<p style="width: 100%; padding-top: 48px; margin-top: 48px; text-align: center; border-top: 1px dashed #E2E8F0;">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=surfer-performance-report' ) ); ?><?php $tracking_enabled ? '&utm_surfer=surfr-email-disable-click' : ''; ?>" target="_blank" style="padding: 8px 24px; border-radius: 8px; text-decoration: none; background-color: #222A3A; color: #ffffff; font-size: 16px; font-weight: 600; line-height: 24px; font-family: Helvetica;"><?php esc_html_e( 'View this week’s report', 'surferseo' ); ?></a>
			</p>
		<?php endif; ?>

		<p style="width: 100%; text-align: center; margin-top: 48px; font-size: 16px; font-weight: 400; line-height: 24px; font-family: Helvetica;">
			<?php esc_html_e( 'Too many updates from Surfer WordPress plugin?', 'surferseo' ); ?>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=surfer' ) ); ?><?php $tracking_enabled ? '&utm_surfer=surfr-email-disable-click' : ''; ?>"><?php esc_html_e( 'Mute them here', 'surferseo' ); ?></a>
		</p>
	</div>

	<p style="width: 720px; text-transform: uppercase; margin: 40px auto 0px; text-align: center; font-family: Helvetica; font-size: 11px; font-weight: 400; line-height: 14px;">
		<?php esc_html_e( '© 2023 Surfer. All rights reserved', 'surferseo' ); ?>
	</p>
</div>