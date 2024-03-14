<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Action\Builtin\SendMail;

use ShopMagicVendor\Psr\Container\ContainerInterface;
use WPDesk\ShopMagic\Components\Mailer\Email;
use WPDesk\ShopMagic\Components\Mailer\Mailer;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\DataSharing\FieldValuesBag;
use WPDesk\ShopMagic\FormField\Field\CheckboxField;
use WPDesk\ShopMagic\FormField\Field\ImageInputField;
use WPDesk\ShopMagic\FormField\Field\InputTextField;
use WPDesk\ShopMagic\Integration\Postmark;
use WPDesk\ShopMagic\Marketing\Subscribers\PreferencesRoute;
use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\Action\TestableAction;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;
use WPDesk\ShopMagic\Workflow\Placeholder\PlaceholderProcessor;


/**
 * Abstract base for email actions.
 * Methods ::get_raw_message and ::get_message_field are templates to complete.
 *
 * @internal Do not extend outside ShopMagic plugin. Protected methods can be changed without
 *           notice.
 */
abstract class AbstractSendMailAction extends Action implements TestableAction {
	protected const PARAM_TEMPLATE_TYPE  = 'template_type';
	protected const PARAM_TO             = 'to_value';
	protected const PARAM_BCC            = 'bcc_value';
	protected const PARAM_SUBJECT        = 'subject_value';
	protected const PARAM_ATTACHMENT     = 'attachment';
	protected const PARAM_HEADING        = 'heading_value';
	protected const PARAM_MESSAGE_TEXT   = 'message_text';
	protected const PARAM_UNSUBSCRIBE    = 'unsubscribe';
	private const   SUPPORTED_EXTENSIONS = [ 'pdf' ];
	private const   SUPPORTED_MIMES      = [ 'application/pdf' ];

	private const EMAIL = 'email';
	/**
	 * @var Postmark Integration with Postmark Streams service.
	 *               Adds additional fields and integrates with PostMark plugin.
	 */
	protected $postmark_integration;
	/** @var Mailer */
	private $mailer;

	/** @var PreferencesRoute */
	private $preferences_route;

	public function __construct( Mailer $mailer, PreferencesRoute $preferences_route, ?Postmark $postmark = null ) {
		$this->postmark_integration = $postmark ?? new Postmark();
		$this->preferences_route    = $preferences_route;
		$this->mailer               = $mailer;
	}

	public function get_required_data_domains(): array {
		return [];
	}

	public function get_fields(): array {
		$fields = [];

		$fields[] = ( new InputTextField() )
			->set_label( __( 'To', 'shopmagic-for-woocommerce' ) )
			->set_default_value( '{{ customer.email }}' )
			->set_required()
			->set_name( self::PARAM_TO );

		$fields[] = ( new InputTextField() )
			->set_label( __( 'BCC', 'shopmagic-for-woocommerce' ) )
			->set_description(
				__(
					'Send a copy of the emails. Useful if you want to send a copy to yourself. This field is not included in action delivery reports.',
					'shopmagic-for-woocommerce'
				)
			)
			->set_name( self::PARAM_BCC );

		$fields[] = ( new InputTextField() )
			->set_label( __( 'Subject', 'shopmagic-for-woocommerce' ) )
			->set_required()
			->set_name( self::PARAM_SUBJECT );

		return array_merge(
			parent::get_fields(),
			$fields
		);
	}

	public function get_attachment_field(): \ShopMagicVendor\WPDesk\Forms\Field {
		return ( new ImageInputField() )
			->set_type( 'file' )
			->set_attribute( 'multiple', 'multiple' )
			->set_label( __( 'PDF Attachments', 'shopmagic-for-woocommerce' ) )
			->set_name( self::PARAM_ATTACHMENT );
	}

	public function execute( DataLayer $resources ): bool {
		$this->resources = $resources;
		$to              = $this->placeholder_processor->process( $this->fields_data->get( self::PARAM_TO ) );

		return $this->execute_internal( $to );
	}

	/**
	 * Used by production and testing execution.
	 */
	private function execute_internal( string $to ): bool {
		$subject = $this->placeholder_processor->process( $this->get_subject_raw() );

		$this->postmark_integration->hook_to_postmark_if_enabled( $this->fields_data );

		$mail = ( new Email() )
			->to( $to )
			->subject( $subject )
			->content_type( $this->get_mail_content_type() )
			->message( $this->get_message_content() );

		/** @var string $bcc */
		$bcc = $this->fields_data->has( self::PARAM_BCC ) ? $this->placeholder_processor->process( $this->fields_data->get( self::PARAM_BCC ) ) : '';
		if ( ! empty( $bcc ) ) {
			$mail = $mail->bcc( $bcc );
		}

		$attachments = $this->get_attachments();
		if ( ! empty( $attachments ) ) {
			$mail = $mail->attach( $attachments );
		}

		try {
			$event = apply_filters( 'shopmagic/core/action/send_mail/sending',
				new EmailSending(
					$mail,
					$this->resources->get( Automation::class ),
					$this->resources->has( Customer::class ) ? $this->resources->get( Customer::class ) : null
				)
			);
			$email = $event->get_email();
			$this->mailer->send( $email );

			return true;
		} finally {
			$this->postmark_integration->clear_hooks();
		}
	}

	/**
	 * Returns mail subject without placeholder processing.
	 */
	public function get_subject_raw(): string {
		return (string) $this->fields_data->get( self::PARAM_SUBJECT );
	}

	/**
	 * Action callback to set more complex context type for sending email
	 *
	 * @internal
	 */
	public function get_mail_content_type(): string {
		return 'text/html';
	}

	/**
	 * Should return mail content from fields. Should also process the placeholders using passed
	 * processor object.
	 */
	abstract protected function get_message_content(): string;

	/** @return string[] */
	private function get_attachments(): array {
		$attachments = $this->fields_data->has( self::PARAM_ATTACHMENT ) ? (array) $this->fields_data->get( self::PARAM_ATTACHMENT ) : [];

		$attachments = array_map(
			static function ( string $attachment ): string {
				return untrailingslashit( ABSPATH ) . wp_make_link_relative( $attachment );
			},
			$attachments
		);

		$this->logger->debug( sprintf( 'Raw attachments paths: %s', json_encode( $attachments ) ) );

		$provided_data = $this->resources;

		/**
		 * Dynamically filter the paths to the attachments, which are already on the server. Path in this filter must be absolute.
		 *
		 * @param string[]           $attachments                  Server absolute path to attachment before sanitization.
		 * @param ContainerInterface $fields_data                  Data saved in action
		 * @param ContainerInterface $provided_data                An array of objects. Can be accessed by full class name, e.g. "WPDesk\ShopMagic\Customer\Customer".
		 *                                                         Mostly indexed by the interfaces, not real classes.
		 *
		 * @return string[]
		 */
		$attachments = (array) apply_filters(
			'shopmagic/core/action/send_mail/attachments_paths',
			$attachments,
			$this->fields_data,
			$provided_data
		);

		return array_filter(
			$attachments,
			function ( string $attachment_path ): bool {
				if ( ! file_exists( $attachment_path ) ) {
					$this->logger->notice( sprintf( 'Attachment path %s does not exists', $attachment_path ) );

					return false;
				}

				if ( \function_exists( 'mime_content_type' ) && \in_array(
						mime_content_type( $attachment_path ),
						self::SUPPORTED_MIMES,
						true
					) ) {
					return true;
				}

				// Fallback for servers not supporting fileinfo PHP extension.
				$extension = pathinfo( $attachment_path, PATHINFO_EXTENSION );

				return \in_array( $extension, self::SUPPORTED_EXTENSIONS, true );
			}
		);

		$this->logger->debug( sprintf( 'Sanitized attachments paths: %s', json_encode( $attachments ) ) );

		return $attachments;
	}

	public function execute_test(
		FieldValuesBag $field_values_bag,
		DataLayer $resources,
		PlaceholderProcessor $processor
	): void {
		$this->set_placeholder_processor( $processor );
		$this->set_provided_data( $resources );

		$this->fields_data = $field_values_bag;

		$has = $this->fields_data->has( self::EMAIL );
		$to  = $has ? sanitize_email( wp_unslash( $this->fields_data->get( self::EMAIL ) ) ) : '';
		// phpcs:enable
		try {
			$success = $to && $this->execute_internal( $to );
			$this->logger->debug(
				'Sending test mail',
				[
					'to'      => $to,
					'success' => $success,
				]
			);
		} catch ( \Throwable $e ) {
			$this->logger->error(
				'Exception in ' . self::class . '::' . __METHOD__,
				[
					'exception' => $e,
					'to'        => $to,
				]
			);
			throw $e;
		}
	}

	protected function get_unsubscribe_field(): CheckboxField {
		return ( new CheckboxField() )
			->set_label( __( 'Unsubscribe link', 'shopmagic-for-woocommerce' ) )
			->set_name( self::PARAM_UNSUBSCRIBE )
			->set_sublabel( __( 'Add unsubscribe link in the message footer', 'shopmagic-for-woocommerce' ) )
			->set_description(
				__(
					"If you send the email to a list, include the unsubscribe link, so that the customers can opt out if they don't want to receive more emails.",
					'shopmagic-for-woocommerce'
				)
			);
	}

	protected function should_append_unsubscribe(): bool {
		if ( $this->fields_data->has( self::PARAM_UNSUBSCRIBE ) ) {
			return $this->fields_data->getBoolean( self::PARAM_UNSUBSCRIBE );
		}

		return false;
	}

	protected function get_unsubscribe_url(): string {
		$url = $this->preferences_route
			->create_preferences_url( $this->resources->get( Customer::class ) );

		return apply_filters( 'shopmagic/core/action/sendmail/unsubscribe_url', $url );
	}
}
