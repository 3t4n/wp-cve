<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Integration\Mailchimp;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\FormField\Field\CheckboxField;
use WPDesk\ShopMagic\FormField\Field\SelectField;
use WPDesk\ShopMagic\Integration\ContactForms\FormEntry;
use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;

/**
 * ShopMagic add to MailChimp list action.
 */
final class AddToMailChimpListAction extends Action {
	/** @var MailchimpApi */
	private $mailchimp;

	public function __construct( MailchimpApi $mailchimp ) {
		$this->mailchimp = $mailchimp;
	}

	public function get_id(): string {
		return 'shopmagic_addtomailchimplist_action';
	}

	public function get_required_data_domains(): array {
		return [ \WP_User::class, \WC_Order::class ];
	}

	public function get_name(): string {
		return __( 'Add Customer to Mailchimp List', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return esc_html__( 'Add customer email to selected Mailchimp list.', 'shopmagic-for-woocommerce' );
	}

	/** @return \ShopMagicVendor\WPDesk\Forms\Field[] */
	public function get_fields(): array {
		$fields = [];

		$fields[] = ( new SelectField() )
			->set_name( '_mailchimp_list_id' )
			->set_label( __( 'The default list ID is', 'shopmagic-for-woocommerce' ) )
			->set_options( $this->mailchimp->get_all_lists_options() );


		return array_merge(
			parent::get_fields(),
			$fields,
			[
				( new CheckboxField() )
					->set_name( '_mailchimp_doubleoptin' )
					->set_default_value( get_option( 'wc_settings_tab_mailchimp_double_optin', 'yes' ) )
					->set_label( __( 'Double opt-in', 'shopmagic-for-woocommerce' ) )
					->set_description(
						__(
							'Send customers an opt-in confirmation email when they subscribe. (Unchecking may be against Mailchimp policy.)',
							'shopmagic-for-woocommerce'
						)
					),
			]
		);
	}

	public function execute( DataLayer $resources ): bool {
		$this->resources = $resources;
		try {
			return $this->add_member_to_mailchimp();
		} catch ( \Throwable $throwable ) {
			$this->logger->error( sprintf( 'Mailchimp exception: %s', $throwable->getMessage() ), [ 'exception' => $throwable ] );

			return false;
		}
	}

	private function add_member_to_mailchimp(): bool {
		$member = new MemberParamsBag();
		if ( $this->resources->has( \WC_Order::class ) ) {
			$member = $member->with_order( $this->resources->get( \WC_Order::class ) );
		}

		if ( $this->resources->has( Customer::class ) ) {
			$member = $member->with_customer( $this->resources->get( Customer::class ) );
		}

		if ( $this->resources->has( FormEntry::class ) ) {
			$member = $member->with_form_entry( $this->resources->get( FormEntry::class ) );
		}

		$member = $member
			->with_list_id(
				Settings::get_option( 'wc_settings_tab_mailchimp_list_id' )
			)->with_double_opt_in(
				$this->fields_data->get( '_mailchimp_doubleoptin' )
			);

		return $this->mailchimp->add_member( $member );
	}

}
