<?php

namespace SG_Email_Marketing\Integrations\ThirdParty;

use SG_Email_Marketing\Integrations\ThirdParty\Form_Parser;
use SG_Email_Marketing\Loader\Loader;
use SG_Email_Marketing\Traits\Ip_Trait;

/**
 * Contact Form 7 integration.
 *
 * @since 1.1.0
 */
class CF7 extends \SG_Email_Marketing\Integrations\Integrations {
	use Ip_Trait;

	/**
	 * The integration id.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	public $id = 'cf7';

	/**
	 * Name of the integration.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	public $checkbox_name = 'sgwpmail_checkbox';

	const CF7_TOGGLE_META = 'sgwpmail_cf7_enable';

	const CF7_CHECKBOX_META = 'sgwpmail_cf7_checkbox_enable';

	const CF7_SELECTED_LABELS_META = 'sgwpmail_cf7_selected_labels';

	const CF7_CHECKBOX_LABEL_META = 'sgwpmail_cf7_checkbox_label';
	/**
	 * Get the integration data.
	 *
	 * @since 1.1.0
	 *
	 * @return array Array containing integration data.
	 */
	public function fetch_settings() {
		$settings = get_option(
			$this->prefix . $this->id,
			array(
				'enabled'       => 1,
				'labels'        => array(),
				'checkbox_text' => __( 'Sign me up for the newsletter!', 'siteground-email-marketing' ),
				'system'        => 1,
				'name'          => $this->id,
			)
		);

		$settings['title']       = __( 'Contact Form 7', 'siteground-email-marketing' );
		$settings['description'] = __( 'Add an optional checkbox to any form created with Contact Form 7, enabling users to sign up for your mailing list. Enable this feature from the SG Email Marketing tab in Contact Form 7 when editing an existing form.', 'siteground-email-marketing' );
		$settings['enabled']     = ! class_exists( '\WPCF7' ) ? 2 : $settings['enabled'];

		return $settings;
	}

	/**
	 * Registers the SGWPMAIL CF7 shortcode.
	 *
	 * @since 1.1.0
	 *
	 * @return boolean
	 */
	public function init() {
		if ( function_exists( 'wpcf7_add_form_tag' ) ) {
			wpcf7_add_form_tag( 'sgwpmail_checkbox', array( $this, 'get_checkbox_html' ) );
		} else {
			wpcf7_add_shortcode( 'sgwpmail_checkbox', array( $this, 'get_checkbox_html' ) );
		}

		return true;
	}

	/**
	 * Creates a new tab in the CF7 editor.
	 *
	 * @since 1.1.0
	 *
	 * @param  array $panels List of all of the already existing panels.
	 *
	 * @return array
	 */
	public function add_sg_panel( $panels ) {
		$current_form = \WPCF7_ContactForm::get_current();
		if ( null === $current_form->id() ) {
			return $panels;
		}

		$panels['sg-panel'] = array(
			'title'    => __( 'SG Email Marketing', 'siteground-email-marketing' ),
			'callback' => array(
				$this,
				'cf7_sgwpmail_panel_output',
			),
		);

		return $panels;

	}


	/**
	 * Generates form settings markup for CF7 editor SGWPMAIL integrations tab.
	 *
	 * @since 1.1.0
	 *
	 * @param \WPCF7_ContactForm $form The form.
	 */
	public function cf7_sgwpmail_panel_output( $form ) {
		$post_id                = $form->id();
		$is_integration_enabled = checked( '1', get_post_meta( $post_id, self::CF7_TOGGLE_META, true ), false );
		$is_checkbox_enabled    = checked( '1', get_post_meta( $post_id, self::CF7_CHECKBOX_META, true ), false );
		$labels_list            = Loader::get_instance()->mailer_api->get_labels();
		$saved_labels           = get_post_meta( $post_id, self::CF7_SELECTED_LABELS_META, true );
		$checkbox_label         = get_post_meta( $post_id, self::CF7_CHECKBOX_LABEL_META, true ) ?: __( 'Subscribe me to your newsletter', 'siteground-email-marketing' );

		if ( ! metadata_exists( 'post', $post_id, self::CF7_CHECKBOX_META, true ) ) {
			$is_checkbox_enabled = checked( '1', '1', false );
		}

		include_once \SG_Email_Marketing\DIR . '/templates/CF7_Integration_Tab_Template.tpl';
	}

	/**
	 * Triggers on save event, saves the meta needed for the integration to the post_meta of the form.
	 *
	 * @since 1.1.0
	 */
	public function cf7_form_save_meta() {
		global $plugin_page;

		$action = \wpcf7_current_action();

		if ( 'save' !== $action ) {
			return;
		}

		$post_id = esc_attr( $_POST['post_ID'] );
		update_post_meta( $post_id, self::CF7_TOGGLE_META, isset( $_POST['sgwpmail-cf7-enable'] ) );
		update_post_meta( $post_id, self::CF7_CHECKBOX_META, isset( $_POST['sgwpmail-cf7-checkbox-toggle'] ) );

		if ( isset ( $_POST['sgwpmail-cf7-labels'] ) ) {
			update_post_meta( $post_id, self::CF7_SELECTED_LABELS_META, $_POST['sgwpmail-cf7-labels'] );
		} else {
			delete_post_meta( $post_id, self::CF7_SELECTED_LABELS_META );
		}

		if ( isset( $_POST['sgwpmail-cf7-checkbox-label'] ) ) {
			update_post_meta( $post_id, self::CF7_CHECKBOX_LABEL_META, esc_attr( $_POST['sgwpmail-cf7-checkbox-label'] ) );
		} else {
			delete_post_meta( $post_id, self::CF7_CHECKBOX_LABEL_META );
		}
	}

	/**
	 * Retrieves POST and GET data.
	 *
	 * @since 1.1.0
	 */
	public function get_data() {
		return array_merge( (array) $_GET, (array) $_POST );
	}

	/**
	 * Checks if the SGWPMAIL checkbox was checked.
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public function checkbox_was_checked() {
		$data = $this->get_data();

		return ( isset( $data[ $this->checkbox_name ] ) && 1 === (int) $data[ $this->checkbox_name ] )
			|| ( isset( $data['sgwpmail_checkbox'] ) && 1 === (int) $data['sgwpmail_checkbox'] );
	}

	/**
	 * Alter Contact Form 7 data.
	 *
	 * @since 1.1.0
	 *
	 * @param  array $data The submitted data from CF7.
	 *
	 * @return array
	 */
	public function alter_cf7_data( $data = array() ) {
		$data['sgwpmail_checkbox'] = $this->checkbox_was_checked() ? __( 'Yes', 'siteground-email-marketing' ) : __( 'No', 'siteground-email-marketing' );
		return $data;
	}

	/**
	 * Process the CF7 Form data when submitted.
	 *
	 * @since 1.1.0.
	 *
	 * @param  WPCF7_ContactForm $cf7_form The submitted form.
	 *
	 * @return bool
	 */
	public function process( $cf7_form ) {
		$integration_toggle = get_post_meta( $cf7_form->id(), self::CF7_TOGGLE_META, true );
		$checkbox_toggle    = get_post_meta( $cf7_form->id(), self::CF7_CHECKBOX_META, true );

		if ( ! $integration_toggle ) {
			return false;
		}

		if ( $checkbox_toggle && ! $this->checkbox_was_checked() ) {
			return false;
		}

		$data = Form_Parser::extract_data( $this->get_data() );

		// do nothing if no email was found.
		if ( empty( $data['email'] ) ) {
			return false;
		}

		$data = array(
			'labels'    => $this->get_label_ids( get_post_meta( $cf7_form->id(), self::CF7_SELECTED_LABELS_META, true ) ),
			'firstName' => $data['first_name'],
			'lastName'  => $data['last_name'],
			'email'     => $data['email'],
			'timestamp' => time(),
			'ip'        => $this->get_current_user_ip(),
		);

		$this->mailer_api->send_data( array( $data ) );

		return true;
	}

	/**
	 * Generates the HTML for the shortcode.
	 *
	 * @since 1.1.0
	 *
	 * @param  array $args The arguments of the shortcode.
	 *
	 * @return string
	 */
	public function get_checkbox_html( $args ) {
		$integration_slug = $this->id;
		$form_id          = $args->labels[0];
		$label            = get_post_meta( $form_id, self::CF7_CHECKBOX_LABEL_META, true );

		ob_start();
		include \SG_Email_Marketing\DIR . '/templates/CF7_Checkbox_Markup.tpl';
		$html = ob_get_clean();
		return $html;
	}

	/**
	 * Updates the CF7 post content, if needed, in regards to subscription checkbox.
	 *
	 * @since 1.1.0.
	 *
	 * @param int|object $post_id The id or object of the post.
	 */
	public static function maybe_update_post_content( $post_id ) {
		if ( 'object' === gettype( $post_id ) && method_exists( $post_id, 'id' ) ) {
			$post_id = $post_id->id();
		}

		$is_checkbox_enabled = get_post_meta( $post_id, self::CF7_CHECKBOX_META, true );
		$form                = wpcf7_contact_form( $post_id );

		if ( 1 !== (int) get_post_meta( $post_id, self::CF7_TOGGLE_META, true ) ) {
			$is_checkbox_enabled = false;
		}

		if ( ! $form ) {
			return;
		}

		$form_content = $form->prop('form');

		if (
			! empty( $is_checkbox_enabled ) &&
			! strpos( $form_content, '[sgwpmail_checkbox id="' . $post_id . '"]' )
		) {
			$form_content = str_replace( '[submit', '[sgwpmail_checkbox id="' . $post_id . '"]<br>[submit', $form_content );
		} else if (
			empty( $is_checkbox_enabled ) &&
			strpos( $form_content, '[sgwpmail_checkbox id="' . $post_id . '"]' )
		) {
			$form_content = str_replace( '[sgwpmail_checkbox id="' . $post_id . '"]<br>', '', $form_content );
		} else {
			return;
		}

		// Update the post's content.
		wpcf7_save_contact_form(
			array(
				'id'   => $post_id,
				'form' => $form_content,
			)
		);
	}

	/**
	 * Enqueuing styles and scripts.
	 *
	 * @since 1.1.0
	 */
	public function enqueue_styles_scripts() {
		wp_enqueue_script( 'selectize.js', 'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js', array( 'jquery' ) );
		wp_enqueue_style( 'selectize.js', 'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css' );
		wp_enqueue_style( 'googleFonts', '//fonts.googleapis.com/css2?family=Roboto&display=swap', array(), null );

		wp_enqueue_script(
			'sg-email-marketing-cf7-integration',
			\SG_Email_Marketing\URL . '/assets/js/integrations/cf7/cf7-editor.js',
			array( 'selectize.js', 'jquery' ),
			\SG_Email_Marketing\VERSION,
			true
		);
		wp_enqueue_style(
			'sg-email-marketing-cf7-integration',
			\SG_Email_Marketing\URL . '/assets/css/integrations/cf7/cf7-editor.css',
			array(),
			\SG_Email_Marketing\VERSION,
			'all'
		);
	}

	/**
	 * Get label ids from label names
	 *
	 * @since 1.1.0
	 *
	 * @param  array $label_names A list with the label names.
	 *
	 * @return array              A list with label ids.
	 */
	public function get_label_ids( $label_names ) {
		$labels_list = Loader::get_instance()->mailer_api->get_labels();

		$label_ids = array();
		foreach ( $labels_list['data'] as $label ) {
			if ( in_array( $label['name'], $label_names, true ) ) {
				$label_ids[] = $label['id'];
			}
		}
		return $label_ids;
	}

}
