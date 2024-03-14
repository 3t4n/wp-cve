<?php
/**
 * Demographic Chart view.
 *
 * @package GoogleAnalytics
 */
?>
<?php if ( false === $ga4_demo_enabled ) : ?>
	<div class="demo-ad ga-panel ga-panel-default" style="margin-top: 2rem; width:100%; max-width:1210px;">
		<div class="ga-panel-heading">
			<strong>
				<?php esc_html_e( 'Get Demographic Data!', 'googleanalytics' ); ?>
				<button id="demographic-popup">
					<?php esc_html_e( 'Click Here To Enable', 'googleanalytics' ); ?>
				</button>
			</strong>
		</div>
		<img src="<?php echo esc_url( trailingslashit( get_home_url() ) . 'wp-content/plugins/googleanalytics/assets/images/demographics-ad.png' ); ?>"/>
	</div>
<?php elseif (true === empty($gender_count_data)) : ?>
	<div class="demo-ad ga-panel ga-panel-default" style="margin-top: 2rem; width:100%; max-width:1210px;">
		<div class="ga-panel-heading">
			<strong>
				<?php
				esc_html_e(
					"If no demographics data is shown, you'll most likely need to do one of the following:",
					'googleanalytics'
				);
				?>
				<br>
				<ol>
					<li>
						<?php esc_html_e(
								'Enable "Demographics" within Google Analytics.',
								'googleanalytics'
						); ?>
					</li>
					<li>
						<?php
						esc_html_e(
							'If you just enabled this feature, it may require a refresh prior to seeing data.',
							'googleanalytics'
						);
						?>
					</li>
					<li>
						<?php
						esc_html_e(
							'Wait until there\'s sufficient Demographic data to display.',
							'googleanalytics'
						);
						?>
					</li>
				</ol>
			</strong>
		</div>
	</div>
<?php else : ?>
	<div class="demo-ad ga-panel ga-panel-default" style="width:100%; max-width:1210px; margin-top: 2rem;">
		<div class="ga-panel-heading">
			<strong>
				<?php esc_html_e( 'Demographic by users' ); ?>
			</strong>
		</div>
		<div class="ga-demo-chart">
			<div class="ga-panel-body ga-chart gender">
				<div id="ga4_demo_chart_gender_div" style="width: 100%;"></div>
				<div class="ga-loader-wrapper stats-page">
					<div class="ga-loader stats-page-loader"></div>
				</div>
			</div>
			<div class="ga-panel-body ga-chart age">
				<div id="ga4_demo_chart_age_div" style="width: 100%;"></div>
				<div class="ga-loader-wrapper stats-page">
					<div class="ga-loader stats-page-loader"></div>
				</div>
			</div>
		</div>
		<div class="ga-panel-body ga-chart device" style="max-width: 550px;">
			<div id="demo_ga4_chart_device_div" style="width: 100%;"></div>
			<div class="ga-loader-wrapper stats-page">
				<div class="ga-loader stats-page-loader"></div>
			</div>
		</div>
	</div>
<hr>
	<?php
endif;
