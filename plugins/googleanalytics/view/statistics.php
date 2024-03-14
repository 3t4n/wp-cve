<?php
/**
 * Statistics view.
 *
 * @package GoogleAnalytics
 */

$data = isset( $data ) ? $data : '';
?>
<div class="wrap ga-wrap">
	<h2>Google Analytics - <?php echo esc_html__( 'Dashboard', 'googleanalytics' ); ?></h2>
	<div class="ga_container" id="exTab2" style="width: 100%;">
		<?php
		echo wp_kses(
			$data,
			array(
				'a'      => array(
					'class' => array(),
					'href'  => array(),
				),
				'button' => array(
					'class'   => array(),
					'id'      => array(),
					'onclick' => array(),
				),
				'div'    => array(
					'class'       => array(),
					'id'          => array(),
					'data-scroll' => array(),
					'style'       => array(),
				),
				'form'   => array(
					'action' => array(),
					'method' => array(),
				),
				'input'  => array(
					'name'  => array(),
					'type'  => array(),
					'value' => array(),
				),
				'img'    => array(
					'src' => array(),
				),
				'label'  => array(
					'for' => array(),
				),
				'p'      => array(),
				'script' => array(),
				'strong' => array(),
				'table'  => array(
					'class' => array(),
				),
				'td'     => array(
					'class'   => array(),
					'colspan' => array(),
					'style'   => array(),
				),
				'th'     => array(
					'style' => array(),
				),
				'tr'     => array(),
			)
		);
		?>
	</div>

	<?php if ( true === empty( get_option( 'googleanalytics-hide-review' ) ) ) : ?>
		<div class="ga-review-us">
			<h3>
				<?php esc_html_e( 'Love this plugin?', 'googleanalytics' ); ?>
				<br>
				<a href="https://wordpress.org/support/plugin/googleanalytics/reviews/#new-post">
					<?php
					esc_html_e(
						'Please spread the word by leaving us a 5 star review!',
						'googleanalytics'
					);
					?>
				</a>
				<p><div id="close-review-us"><?php esc_html_e( 'close' ); ?></div></p>
			</h3>
		</div>
	<?php endif; ?>
</div>
<script type="text/javascript">
	const GA_NONCE = '<?php echo esc_js( wp_create_nonce( 'ga_ajax_data_change' ) ); ?>';
	const GA_NONCE_FIELD = '<?php echo esc_js( Ga_Admin_Controller::GA_NONCE_FIELD_NAME ); ?>';
</script>
