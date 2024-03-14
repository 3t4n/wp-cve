<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Action\Builtin\SendMail;

use WPDesk\ShopMagic\FormField\Field\TextAreaField;


/**
 * Action to send emails using plain text. No HTML is allowed.
 */
final class SendPlainTextMailAction extends AbstractSendMailAction {
	public function get_id(): string {
		return 'shopmagic_plain_text_email_action';
	}

	public function get_name(): string {
		return __( 'Send Email - Plain Text', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return esc_html__('Send simple email without HTML tags. Perfect for short, concise messages.', 'shopmagic-for-woocommerce');
	}

	public function get_fields(): array {
		return $this->postmark_integration->append_fields_if_enabled(
			array_merge(
				parent::get_fields(),
				[
					( new TextAreaField() )
						->set_label( __( 'Message', 'shopmagic-for-woocommerce' ) )
						->set_name( self::PARAM_MESSAGE_TEXT ),
					$this->get_attachment_field(),
				]
			)
		);
	}

	public function get_mail_content_type(): string {
		return 'text/plain';
	}

	protected function get_message_content(): string {
		return apply_filters( 'shopmagic/core/action/sendmail/raw_message', sanitize_textarea_field( $this->placeholder_processor->process( $this->fields_data->get( self::PARAM_MESSAGE_TEXT ) ) ) );
	}
}
