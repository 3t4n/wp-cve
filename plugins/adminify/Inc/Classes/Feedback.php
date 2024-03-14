<?php
namespace WPAdminify\Inc\Classes;

use WPAdminify\Inc\Classes\Notifications\Base\User_Data;

// No, Direct access Sir !!!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Feedback
 *
 * @author Jewel Theme <support@jeweltheme.com>
 */
class Feedback {

	use User_Data;

	/**
	 * Construct Method
	 *
	 * @return void
	 * @author Jewel Theme <support@jeweltheme.com>
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_suvery_scripts' ) );
		add_action( 'admin_footer', array( $this, 'deactivation_footer' ) );
		add_action( 'wp_ajax_jltwp_adminify_deactivation_survey', array( $this, 'jltwp_adminify_deactivation_survey' ) );
	}


	public function proceed() {

		global $current_screen;
		if (
			isset( $current_screen->parent_file )
			&& $current_screen->parent_file == 'plugins.php'
			&& isset( $current_screen->id )
			&& $current_screen->id == 'plugins'
		) {
			return true;
		}
		return false;
	}

	public function admin_suvery_scripts( $handle ) {
		if ( 'plugins.php' === $handle ) {
			wp_enqueue_style( 'jltwp_adminify-survey', WP_ADMINIFY_ASSETS . 'css/plugin-survey.css' );
		}
	}

	/**
	 * Deactivation Survey
	 */
	public function jltwp_adminify_deactivation_survey() {
		check_ajax_referer( 'jltwp_adminify_deactivation_nonce' );

		$deactivation_reason = ! empty( $_POST['deactivation_reason'] ) ? sanitize_text_field( wp_unslash( $_POST['deactivation_reason'] ) ) : '';

		if ( empty( $deactivation_reason ) ) {
			return;
		}

		$email      = get_bloginfo( 'admin_email' );
		$author_obj = get_user_by( 'email', $email );
		$user_id    = $author_obj->ID;
		$full_name  = $author_obj->display_name;

		$response = $this->get_collect_data(
			$user_id,
			array(
				'first_name'          => $full_name,
				'email'               => $email,
				'deactivation_reason' => $deactivation_reason,
			)
		);

		return $response;
	}


	public function get_survey_questions() {

		return array(
			'no_longer_needed'               => array(
				'title'             => esc_html__( 'I no longer need the plugin', 'adminify' ),
				'input_placeholder' => '',
			),
			'found_a_better_plugin'          => array(
				'title'             => esc_html__( 'I found a better plugin', 'adminify' ),
				'input_placeholder' => esc_html__( 'Please share which plugin', 'adminify' ),
			),
			'couldnt_get_the_plugin_to_work' => array(
				'title'             => esc_html__( 'I couldn\'t get the plugin to work', 'adminify' ),
				'input_placeholder' => '',
			),
			'temporary_deactivation'         => array(
				'title'             => esc_html__( 'It\'s a temporary deactivation', 'adminify' ),
				'input_placeholder' => '',
			),
			'jltwp_adminify_pro'             => array(
				'title'             => sprintf( esc_html__( 'I have %1$s Pro', 'adminify' ), WP_ADMINIFY ),
				'input_placeholder' => '',
				'alert'             => sprintf( esc_html__( 'Wait! Don\'t deactivate %1$s. You have to activate both %1$s and %1$s Pro in order for the plugin to work.', 'adminify' ), WP_ADMINIFY ),
			),
			'need_better_design'             => array(
				'title'             => esc_html__( 'I need better design and presets', 'adminify' ),
				'input_placeholder' => esc_html__( 'Let us know your thoughts', 'adminify' ),
			),
			'other'                          => array(
				'title'             => esc_html__( 'Other', 'adminify' ),
				'input_placeholder' => esc_html__( 'Please share the reason', 'adminify' ),
			),
		);
	}


		/**
		 * Deactivation Footer
		 */
	public function deactivation_footer() {

		if ( ! $this->proceed() ) {
			return;
		}

		?>
		<div class="wp-adminify-deactivate-survey-overlay" id="wp-adminify-deactivate-survey-overlay"></div>
		<div class="wp-adminify-deactivate-survey-modal" id="wp-adminify-deactivate-survey-modal">
			<header>
				<div class="wp-adminify-deactivate-survey-header">
					<img src="<?php echo esc_url( WP_ADMINIFY_ASSETS_IMAGE . 'logos/menu-icon.svg' ); ?>" />
					<h3><?php echo wp_sprintf( '%1$s <strong>%2$s</strong>', WP_ADMINIFY, __( '- Feedback', 'adminify' ) ); ?></h3>
				</div>
			</header>
			<div class="wp-adminify-deactivate-info">
			<?php echo wp_sprintf( '%1$s %2$s', __( 'If you have a moment, please share why you are deactivating', 'adminify' ), WP_ADMINIFY ); ?>
			</div>
			<div class="wp-adminify-deactivate-content-wrapper">
				<form action="#" class="wp-adminify-deactivate-form-wrapper">
				<?php foreach ( $this->get_survey_questions() as $reason_key => $reason ) { ?>
						<div class="wp-adminify-deactivate-input-wrapper">
							<input id="wp-adminify-deactivate-feedback-<?php echo esc_attr( $reason_key ); ?>" class="wp-adminify-deactivate-feedback-dialog-input" type="radio" name="reason_key" value="<?php echo $reason_key; ?>">
							<label for="wp-adminify-deactivate-feedback-<?php echo esc_attr( $reason_key ); ?>" class="wp-adminify-deactivate-feedback-dialog-label"><?php echo esc_html( $reason['title'] ); ?></label>
							<?php if ( ! empty( $reason['input_placeholder'] ) ) : ?>
								<input class="wp-adminify-deactivate-feedback-text" type="text" name="reason_<?php echo esc_attr( $reason_key ); ?>" placeholder="<?php echo esc_attr( $reason['input_placeholder'] ); ?>" />
							<?php endif; ?>
						</div>
					<?php } ?>
					<div class="wp-adminify-deactivate-footer">
						<button id="wp-adminify-dialog-lightbox-submit" class="wp-adminify-dialog-lightbox-submit"><?php echo esc_html__( 'Submit &amp; Deactivate', 'adminify' ); ?></button>
						<button id="wp-adminify-dialog-lightbox-skip" class="wp-adminify-dialog-lightbox-skip"><?php echo esc_html__( 'Skip & Deactivate', 'adminify' ); ?></button>
					</div>
				</form>
			</div>
		</div>

		<script>
			var deactivate_url = '#';

			jQuery(document).on('click', '#deactivate-adminify', function(e) {
				e.preventDefault();
				deactivate_url = e.target.href;
				jQuery('#wp-adminify-deactivate-survey-overlay').addClass('wp-adminify-deactivate-survey-is-visible');
				jQuery('#wp-adminify-deactivate-survey-modal').addClass('wp-adminify-deactivate-survey-is-visible');
			});

			jQuery('#wp-adminify-dialog-lightbox-skip').on('click', function (e) {
				e.preventDefault();
				window.location.replace(deactivate_url);
			});


			jQuery(document).on('click', '#wp-adminify-dialog-lightbox-submit', async function(e) {
				e.preventDefault();

				jQuery('#wp-adminify-dialog-lightbox-submit').addClass('wp-adminify-loading');

				var $dialogModal = jQuery('.wp-adminify-deactivate-input-wrapper'),
					radioSelector = '.wp-adminify-deactivate-feedback-dialog-input';
				$dialogModal.find(radioSelector).on('change', function () {
					$dialogModal.attr('data-feedback-selected', jQuery(this).val());
				});
				$dialogModal.find(radioSelector + ':checked').trigger('change');


				// Reasons for deactivation
				var deactivation_reason = '';
				var reasonData = jQuery('.wp-adminify-deactivate-form-wrapper').serializeArray();

				jQuery.each(reasonData, function (reason_index, reason_value) {
					if ('reason_key' == reason_value.name && reason_value.value != '') {
						const reason_input_id = '#wp-adminify-deactivate-feedback-' + reason_value.value,
							reason_title = jQuery(reason_input_id).siblings('label').text(),
							reason_placeholder_input = jQuery(reason_input_id).siblings('input').val(),
							format_title_with_key = reason_value.value + ' - '  + reason_placeholder_input,
							format_title = reason_title + ' - '  + reason_placeholder_input;

						deactivation_reason = reason_value.value;

						if ('found_a_better_plugin' == reason_value.value ) {
							deactivation_reason = format_title_with_key;
						}

						if ('need_better_design' == reason_value.value ) {
							deactivation_reason = format_title_with_key;
						}

						if ('other' == reason_value.value) {
							deactivation_reason = format_title_with_key;
						}
					}
				});

				await jQuery.ajax({
						url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
						method: 'POST',
						// crossDomain: true,
						async: true,
						// dataType: 'jsonp',
						data: {
							action: 'jltwp_adminify_deactivation_survey',
							_wpnonce: '<?php echo esc_js( wp_create_nonce( 'jltwp_adminify_deactivation_nonce' ) ); ?>',
							deactivation_reason: deactivation_reason
						},
						success:function(response){
							window.location.replace(deactivate_url);
						}
				});
				return true;
			});

			jQuery('#wp-adminify-deactivate-survey-overlay').on('click', function () {
				jQuery('#wp-adminify-deactivate-survey-overlay').removeClass('wp-adminify-deactivate-survey-is-visible');
				jQuery('#wp-adminify-deactivate-survey-modal').removeClass('wp-adminify-deactivate-survey-is-visible');
			});
		</script>
		<?php
	}
}
