<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdulbDeactivationSurvey' ) ) {
/**
 * Class to handle plugin deactivation survey
 *
 * @since 2.0.15
 */
class ewdulbDeactivationSurvey {

	public function __construct() {
		add_action( 'current_screen', array( $this, 'maybe_add_survey' ) );
	}

	public function maybe_add_survey() {
		if ( in_array( get_current_screen()->id, array( 'plugins', 'plugins-network' ), true) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_deactivation_scripts') );
			add_action( 'admin_footer', array( $this, 'add_deactivation_html') );
		}
	}

	public function enqueue_deactivation_scripts() {
		wp_enqueue_style( 'ulb-deactivation-css', EWD_ULB_PLUGIN_URL . '/assets/css/plugin-deactivation.css' );
		wp_enqueue_script( 'ulb-deactivation-js', EWD_ULB_PLUGIN_URL . '/assets/js/plugin-deactivation.js', array( 'jquery' ) );

		wp_localize_script( 'ulb-deactivation-js', 'ulb_deactivation_data', array( 'site_url' => site_url() ) );
	}

	public function add_deactivation_html() {
		
		$install_time = get_option( 'ulb-installation-time' );

		$options = array(
			1 => array(
				'title'   => esc_html__( 'I no longer need the plugin', 'ultimate-lightbox' ),
			),
			2 => array(
				'title'   => esc_html__( 'I\'m switching to a different plugin', 'ultimate-lightbox' ),
				'details' => esc_html__( 'Please share which plugin', 'ultimate-lightbox' ),
			),
			3 => array(
				'title'   => esc_html__( 'I couldn\'t get the plugin to work', 'ultimate-lightbox' ),
				'details' => esc_html__( 'Please share what wasn\'t working', 'ultimate-lightbox' ),
			),
			4 => array(
				'title'   => esc_html__( 'It\'s a temporary deactivation', 'ultimate-lightbox' ),
			),
			5 => array(
				'title'   => esc_html__( 'Other', 'ultimate-lightbox' ),
				'details' => esc_html__( 'Please share the reason', 'ultimate-lightbox' ),
			),
		);
		?>
		<div class="ulb-deactivate-survey-modal" id="ulb-deactivate-survey-ultimate-lightbox">
			<div class="ulb-deactivate-survey-wrap">
				<form class="ulb-deactivate-survey" method="post" data-installtime="<?php echo $install_time; ?>">
					<span class="ulb-deactivate-survey-title"><span class="dashicons dashicons-testimonial"></span><?php echo ' ' . __( 'Quick Feedback', 'ultimate-lightbox' ); ?></span>
					<span class="ulb-deactivate-survey-desc"><?php echo __('If you have a moment, please share why you are deactivating Ultimate Lightbox:', 'ultimate-lightbox' ); ?></span>
					<div class="ulb-deactivate-survey-options">
						<?php foreach ( $options as $id => $option ) : ?>
							<div class="ulb-deactivate-survey-option">
								<label for="ulb-deactivate-survey-option-ultimate-lightbox-<?php echo $id; ?>" class="ulb-deactivate-survey-option-label">
									<input id="ulb-deactivate-survey-option-ultimate-lightbox-<?php echo $id; ?>" class="ulb-deactivate-survey-option-input" type="radio" name="code" value="<?php echo $id; ?>" />
									<span class="ulb-deactivate-survey-option-reason"><?php echo $option['title']; ?></span>
								</label>
								<?php if ( ! empty( $option['details'] ) ) : ?>
									<input class="ulb-deactivate-survey-option-details" type="text" placeholder="<?php echo $option['details']; ?>" />
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="ulb-deactivate-survey-footer">
						<button type="submit" class="ulb-deactivate-survey-submit button button-primary button-large"><?php _e('Submit and Deactivate', 'ultimate-lightbox' ); ?></button>
						<a href="#" class="ulb-deactivate-survey-deactivate"><?php _e('Skip and Deactivate', 'ultimate-lightbox' ); ?></a>
					</div>
				</form>
			</div>
		</div>
		<?php
	}
}

}