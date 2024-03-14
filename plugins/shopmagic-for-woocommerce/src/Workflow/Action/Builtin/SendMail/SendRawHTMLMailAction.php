<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Action\Builtin\SendMail;

use WPDesk\ShopMagic\FormField\Field\TextAreaField;


/**
 * Action to send emails using HTML. No WYSWIG is provided and the data will be sent without any additional filtering or modifications.
 */
final class SendRawHTMLMailAction extends AbstractSendMailAction {
	public function get_id(): string {
		return 'shopmagic_raw_html_email_action';
	}

	public function get_name(): string {
		return __( 'Send Email - Raw HTML', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return esc_html__('Compose your own email from scratch in raw HTML.', 'shopmagic-for-woocommerce');
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

	protected function get_message_content(): string {
		return /**
			 * @ignore
			 * @see SendPlainTextMailAction
			 */
			apply_filters( 'shopmagic/core/action/sendmail/raw_message', $this->placeholder_processor->process( $this->fields_data->get( self::PARAM_MESSAGE_TEXT ) ) );
	}
}
