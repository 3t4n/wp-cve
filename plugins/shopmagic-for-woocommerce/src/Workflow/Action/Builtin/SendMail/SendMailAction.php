<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Action\Builtin\SendMail;

use ShopMagicVendor\WPDesk\View\Renderer\Renderer;
use ShopMagicVendor\Psr\Container\NotFoundExceptionInterface;
use WPDesk\ShopMagic\Admin\Form\Fields\ProItemInfoField;
use WPDesk\ShopMagic\Components\Mailer\Mailer;
use WPDesk\ShopMagic\FormField\Field\InputTextField;
use WPDesk\ShopMagic\FormField\Field\SelectField;
use WPDesk\ShopMagic\FormField\Field\WyswigField;
use WPDesk\ShopMagic\Helper\WordPressPluggableHelper;
use WPDesk\ShopMagic\Integration\Postmark;
use WPDesk\ShopMagic\Marketing\Subscribers\PreferencesRoute;

/**
 * Action to send emails.
 */
final class SendMailAction extends AbstractSendMailAction {

	/** @var Renderer */
	private $renderer;

	public function __construct(
		Mailer $mailer,
		PreferencesRoute $preferences_route,
		Renderer $renderer,
		?Postmark $postmark = null
	) {
		$this->renderer = $renderer;
		parent::__construct( $mailer, $preferences_route, $postmark );
	}

	public function get_id(): string {
		return 'shopmagic_sendemail_action';
	}

	private const NOTICE_NAME = 'review-request';

	public function get_name(): string {
		return __( 'Send Email', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return esc_html__( 'The simplest way to create email for customer. Add topic and message without complex customization.', 'shopmagic-for-woocommerce' );
	}

	/**
	 * @return \ShopMagicVendor\WPDesk\Forms\Field[]
	 */
	public function get_fields(): array {
		$template_options = $this->get_template_options();
		$fields           = [
			( new InputTextField() )
				->set_label( __( 'Heading', 'shopmagic-for-woocommerce' ) )
				->set_name( self::PARAM_HEADING ),
			( new SelectField() )
				->set_label( __( 'Template', 'shopmagic-for-woocommerce' ) )
				->set_name( self::PARAM_TEMPLATE_TYPE )
				->set_default_value( array_keys( $template_options )[0] ?? '' )
				->set_options(
					$template_options
				),
		];
		$time_dismissed   = get_user_meta(
			get_current_user_id(),
			'shopmagic_ignore_notice_' . self::NOTICE_NAME,
			true
		);
		$show_after       = ( $time_dismissed ) ? $time_dismissed + MONTH_IN_SECONDS : ''; // Will show again after 1 month.
		if ( ( ! WordPressPluggableHelper::is_plugin_active( 'shopmagic-reviews/shopmagic-reviews.php' ) ) && ( time() > $show_after ) ) {
			$product_link = ( get_locale() === 'pl_PL' )
				? // phpcs:ignore Squiz.ControlStructures.InlineIfDeclaration.NotSingleLine
				'https://www.wpdesk.pl/sklep/shopmagic/?utm_source=review-requests&utm_medium=notice&utm_campaign=e08'
				:
				'https://shopmagic.app/products/shopmagic-review-requests/?utm_source=review-requests&utm_medium=notice&utm_campaign=e08';

			$fields[] = ( new ProItemInfoField() )
				->set_name( 'review-request-ads' )
				->set_description( '<b>Reminder</b>: with ShopMagic Review Request you can automatically ask for reviews after your customers get their order. <a href="' . $product_link . '" target="_blank">Read more</a>' );
		}

		$fields[] = ( new WyswigField() )
			->set_type( 'textarea' )
			->set_required()
			->set_label( __( 'Message', 'shopmagic-for-woocommerce' ) )
			->set_name( self::PARAM_MESSAGE_TEXT );

		$fields[] = $this->get_unsubscribe_field();

		$fields[] = $this->get_attachment_field();

		return $this->postmark_integration->append_fields_if_enabled(
			array_merge(
				parent::get_fields(),
				$fields
			)
		);
	}

	/**
	 * @return array{woocommerce?: mixed, plain: mixed}
	 */
	private function get_template_options(): array {
		$options = [];
		if ( WordPressPluggableHelper::is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$options[ WooCommerceMailTemplate::NAME ] = __( 'WooCommerce Template', 'shopmagic-for-woocommerce' );
		}

		$options[ PlainMailTemplate::NAME ] = __( 'None', 'shopmagic-for-woocommerce' );

		return $options;
	}

	protected function get_message_content(): string {
		$processed_message = wpautop( $this->placeholder_processor->process( $this->fields_data->get( self::PARAM_MESSAGE_TEXT ) ) );
		$message           =
			/**
			 * @ignore
			 * @see SendPlainTextMailAction
			 */
			apply_filters( 'shopmagic/core/action/sendmail/raw_message', $processed_message );

		try {
			if ( $this->fields_data->has( self::PARAM_TEMPLATE_TYPE ) ) {
				$template_type = $this->fields_data->get( self::PARAM_TEMPLATE_TYPE, PlainMailTemplate::NAME );
			}
		} catch ( NotFoundExceptionInterface $e ) {
			$template_type = PlainMailTemplate::NAME;
		}

		if ( empty( $template_type ) ) {
			$template_type = PlainMailTemplate::NAME;
		}

		$message = $this->create_template( $template_type )->wrap_content( $message );

		if ( $template_type === PlainMailTemplate::NAME && $this->should_append_unsubscribe() ) {
			$unsubscribe_url = $this->get_unsubscribe_url();
			$message        .= sprintf( "<br /><br /><a href='%s'>", $unsubscribe_url ) . __(
				'Click to unsubscribe',
				'shopmagic-for-woocommerce'
			) . '</a>';
		}

		return $message;
	}

	/**
	 * Creates a template class of a given type.
	 *
	 * @param string $template_type Type to create.
	 */
	private function create_template( string $template_type ): MailTemplate {
		$heading = $this->fields_data->has( self::PARAM_HEADING ) ? $this->placeholder_processor->process( $this->fields_data->get( self::PARAM_HEADING ) ) : '';
		if ( $template_type === WooCommerceMailTemplate::NAME ) {
			$unsubscribe_url = $this->should_append_unsubscribe()
				? $this->get_unsubscribe_url()
				: null;

			/** @var string $heading */
			return new WooCommerceMailTemplate( $heading, $unsubscribe_url, $this->renderer );
		}

		return new PlainMailTemplate();
	}
}
