<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Scripts Class
 *
 * Html for eclg form
 *
 * @package Email Capture & Lead Generation
 * @since 1.0.0
 */
class Eclg_Shortcodes {


	public function __construct() {
		// Shortcode to print newletter form
		// Shortcode : [eclg_capture lastname="yes" firstname="yes" button_text="Send"]
		add_shortcode( 'eclg_capture', array( $this, 'eclg_email_form_shortcode' ) );

		// Use shortcode in widget
		add_filter( 'widget_text', 'do_shortcode' );
	}

	/**
	 * Adding Html
	 *
	 * Adding html for front end side.
	 *
	 * @package Email Capture & Lead Generation
	 * @since 1.0.0
	 */
	function eclg_email_form_shortcode( $atts, $content ) {
		// Getting attributes of shortcode
		$attributes = shortcode_atts(
			array(
				'button_text' => __( 'Submit', 'email-capture-lead-generation' ),
				'firstname'   => 'yes',
				'lastname'    => 'yes',
				'template'    => false, // @since 1.0.3
			),
			$atts
		);

		$content .= $this->get_form_template( $attributes );

		return $content;
	}

	/**
	 * Returns the form template according to the form type selected by the admin from backend.
	 * If admin provides "template" parameter in shortcode then that parameter will override the backend selected form type.
	 *
	 * @since 1.0.3
	 * @param array $attributes Shortcode attributes.
	 */
	public function get_form_template( $attributes ) {
		$all_options   = get_option( 'eclg_options' );
		$eclg_settings = ! empty( $all_options['eclg_settings'] ) ? $all_options['eclg_settings'] : array();
		$template_type = ! empty( $eclg_settings['templateType'] ) ? $eclg_settings['templateType'] : false;
		$template      = ! empty( $attributes['template'] ) ? $attributes['template'] : $template_type;

		/**
		 * We will pass this array to form templates that will be extracted into variables.
		 */
		$form_data = array(
			'form_title'       => isset( $eclg_settings['formTitle'] ) ? $eclg_settings['formTitle'] : '',
			'form_description' => isset( $eclg_settings['formDescription'] ) ? $eclg_settings['formDescription'] : '',
			'firstname'        => isset( $attributes['firstname'] ) ? $attributes['firstname'] : '',
			'lastname'         => isset( $attributes['lastname'] ) ? $attributes['lastname'] : '',
			'button_text'      => isset( $attributes['button_text'] ) ? $attributes['button_text'] : '',
		);

		$forms = array(
			'default'  => $this->template_default( $form_data ),
			'classic'  => $this->template_classic( $form_data ),
			'standard' => $this->template_standard( $form_data ),
			'aurora'   => $this->template_aurora( $form_data ),
		);

		return isset( $forms[ $template ] ) ? $forms[ $template ] : $forms['default'];
	}

	/**
	 * The legacy default form for newsletter.
	 */
	public function template_default( $form_data ) {
		/**
		 * We are extracting array for below variables only.
		 */
		extract( $form_data );

		ob_start();
		?>
		<div class="eclg-email-capture">
			<form id="eclg-form">
				<?php
				if ( $firstname == 'yes' ) {
					?>
					<div class="input-field">
						<label><?php echo __( 'First Name', 'email-capture-lead-generation' ); ?></label>
						<input type="text" name="first_name" class="eclg_firstname" />
					</div>
				<?php } ?>

				<?php
				if ( $lastname == 'yes' ) {
					?>
					<div class="input-field">
						<label><?php echo __( 'Last Name', 'email-capture-lead-generation' ); ?></label>
						<input type="text" name="last_name" class="eclg_lastname">
					</div>
				<?php } ?>

				<div class="input-field">
					<label><?php echo __( 'Email', 'email-capture-lead-generation' ); ?></label>
					<input type="text" name="email" class="eclg_email">
				</div>

				<div class="input-field input-submit">
					<button type="button" id="eclg-submit-btn"><?php echo $button_text; ?> </button>

					<div class="eclg_ajax_loader" style="display: none;"><img src="<?php echo ECLG_PLUGIN_URL; ?>/images/ajax_loader.gif"></div>
				</div>
				<div class="eclg-message-container"></div>
			</form>
		</div>
		<?php
		$content = ob_get_clean();
		return $content;
	}

	/**
	 * Classic form template.
	 *
	 * @since 1.0.3
	 */
	public function template_classic( $form_data ) {
		/**
		 * We are extracting array for below variables only.
		 */
		extract( $form_data );

		ob_start();
		?>
		<main class="main">
			<div class="main__cnt">
				<div class="email-capture email-capture--bg-primary">
					<?php if ( $form_title || $form_description ) { ?>
						<header class="email-capture__header">
							<?php echo $form_title ? wp_kses_post( "<h2>{$form_title}</h2>" ) : null; ?>
							<?php echo $form_description ? wp_kses_post( "<h5>{$form_description}</h5>" ) : null; ?>
						</header>
					<?php } ?>
					<form id="eclg-form">
						<div class="email-capture__ele">

							<?php if ( 'yes' === $firstname ) { ?>
								<fieldset class="email-capture__ele__fieldset">
									<legend><?php echo esc_html__( 'First Name', 'email-capture-lead-generation' ); ?></legend>
									<input type="text" name="first_name" class="eclg_firstname">
								</fieldset>
							<?php } ?>

							<?php if ( 'yes' === $lastname ) { ?>
								<fieldset class="email-capture__ele__fieldset">
									<legend><?php echo esc_html__( 'Last Name', 'email-capture-lead-generation' ); ?></legend>
									<input type="text" name="last_name" class="eclg_lastname">
								</fieldset>
							<?php } ?>

							<fieldset class="email-capture__ele__fieldset">
								<legend><?php echo esc_html__( 'Email', 'email-capture-lead-generation' ); ?></legend>
								<input type="email" name="email" class="eclg_email">
							</fieldset>

							<fieldset class="email-capture__ele__fieldset email-capture__ele__fieldset--btn-wrap">
								<legend>&nbsp;</legend>
								<button type="button" id="eclg-submit-btn"><?php echo esc_html( $button_text ); ?></button>
								<div class="eclg_ajax_loader" style="display: none;">
									<img src="<?php echo esc_url( ECLG_PLUGIN_URL ); ?>/images/ajax_loader.gif">
								</div>
								<div class="eclg-message-container"></div>
							</fieldset>
						</div>
					</form>
				</div>
			</div>
		</main>
		<?php
		$content = ob_get_clean();
		return $content;
	}

	/**
	 * Standard form template.
	 *
	 * @since 1.0.3
	 */
	public function template_standard( $form_data ) {
		/**
		 * We are extracting array for below variables only.
		 */
		extract( $form_data );

		ob_start();
		?>
		<main class="main">
			<div class="main__cnt">
				<div class="email-capture email-capture--full-width email-capture--bg-secondary">
					<?php if ( $form_title || $form_description ) { ?>
						<header class="email-capture__header">
							<?php echo $form_title ? wp_kses_post( "<h2>{$form_title}</h2>" ) : null; ?>
							<?php echo $form_description ? wp_kses_post( "<h5>{$form_description}</h5>" ) : null; ?>
						</header>
					<?php } ?>
					<form id="eclg-form">
						<div class="email-capture__ele">

							<?php if ( 'yes' === $firstname ) { ?>
								<fieldset class="email-capture__ele__fieldset">
									<legend><?php echo esc_html__( 'First Name', 'email-capture-lead-generation' ); ?></legend>
									<input type="text" name="first_name" class="eclg_firstname">
								</fieldset>
							<?php } ?>

							<?php if ( 'yes' === $lastname ) { ?>
								<fieldset class="email-capture__ele__fieldset">
									<legend><?php echo esc_html__( 'Last Name', 'email-capture-lead-generation' ); ?></legend>
									<input type="text" name="last_name" class="eclg_lastname">
								</fieldset>
							<?php } ?>

							<fieldset class="email-capture__ele__fieldset">
								<legend><?php echo esc_html__( 'Email', 'email-capture-lead-generation' ); ?></legend>
								<input type="email" name="email" class="eclg_email">
							</fieldset>

							<fieldset class="email-capture__ele__fieldset email-capture__ele__fieldset--btn-wrap">
								<legend>&nbsp;</legend>
								<button type="button" id="eclg-submit-btn"><?php echo esc_html( $button_text ); ?></button>
								<div class="eclg_ajax_loader" style="display: none;">
									<img src="<?php echo esc_url( ECLG_PLUGIN_URL ); ?>/images/ajax_loader.gif">
								</div>
								<div class="eclg-message-container"></div>
							</fieldset>
						</div>
					</form>
				</div>
			</div>
		</main>
		<?php
		$content = ob_get_clean();
		return $content;
	}

	/**
	 * Aurora form template.
	 *
	 * @since 1.0.3
	 */
	public function template_aurora( $form_data ) {
		/**
		 * We are extracting array for below variables only.
		 */
		extract( $form_data );

		ob_start();
		?>
		<main class="main">
			<div class="main__cnt">
				<div class="email-capture email-capture--center email-capture--bg-secondary">
					<?php if ( $form_title || $form_description ) { ?>
						<header class="email-capture__header">
							<?php echo $form_title ? wp_kses_post( "<h2>{$form_title}</h2>" ) : null; ?>
							<?php echo $form_description ? wp_kses_post( "<h5>{$form_description}</h5>" ) : null; ?>
						</header>
					<?php } ?>
					<form id="eclg-form">
						<div class="email-capture__ele">

							<?php if ( 'yes' === $firstname ) { ?>
								<fieldset class="email-capture__ele__fieldset">
									<legend><?php echo esc_html__( 'First Name', 'email-capture-lead-generation' ); ?></legend>
									<input type="text" name="first_name" class="eclg_firstname">
								</fieldset>
							<?php } ?>

							<?php if ( 'yes' === $lastname ) { ?>
								<fieldset class="email-capture__ele__fieldset">
									<legend><?php echo esc_html__( 'Last Name', 'email-capture-lead-generation' ); ?></legend>
									<input type="text" name="last_name" class="eclg_lastname">
								</fieldset>
							<?php } ?>

							<fieldset class="email-capture__ele__fieldset">
								<legend><?php echo esc_html__( 'Email', 'email-capture-lead-generation' ); ?></legend>
								<input type="email" name="email" class="eclg_email">
							</fieldset>

							<fieldset class="email-capture__ele__fieldset email-capture__ele__fieldset--btn-wrap">
								<legend>&nbsp;</legend>
								<button type="button" id="eclg-submit-btn"><?php echo esc_html( $button_text ); ?></button>
								<div class="eclg_ajax_loader" style="display: none;">
									<img src="<?php echo esc_url( ECLG_PLUGIN_URL ); ?>/images/ajax_loader.gif">
								</div>
								<div class="eclg-message-container"></div>
							</fieldset>
						</div>
					</form>
				</div>
			</div>
		</main>
		<?php
		$content = ob_get_clean();
		return $content;
	}
}

return new Eclg_Shortcodes();
