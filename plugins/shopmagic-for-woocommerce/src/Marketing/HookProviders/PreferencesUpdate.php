<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\HookProviders;

use ShopMagicVendor\WPDesk\Forms\Field\CheckboxField;
use WPDesk\ShopMagic\Components\HookProvider\HookProvider;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SubscriptionManager;
use WPDesk\ShopMagic\Marketing\Subscribers\PreferencesRoute;

/**
 * Handle updating customer marketing (communication) preferences. At the moment it works only
 * for guests and customers, though we are able to create subscriber which is not a guest entity.
 * (This shouldn't be possible, check if guest is created each time it's saved on marketing list).
 */
final class PreferencesUpdate implements HookProvider {

	/**
	 * @var string
	 */
	private const EMAIL = 'email';
	/** @var SubscriptionManager */
	private $subscription_manager;

	public function __construct( SubscriptionManager $manager ) {
		$this->subscription_manager = $manager;
	}

	public function hooks(): void {
		add_action( 'wp_ajax_' . PreferencesRoute::get_slug(), [ $this, 'process_account_preferences' ] );
		add_action( 'wp_ajax_nopriv_' . PreferencesRoute::get_slug(), [ $this, 'process_account_preferences' ] );
		add_action( 'admin_post_' . PreferencesRoute::get_slug(), [ $this, 'process_account_preferences' ] );
		add_action( 'admin_post_nopriv_' . PreferencesRoute::get_slug(), [ $this, 'process_account_preferences' ] );
	}

	public function process_account_preferences(): void {
		$sanitized_post = array_map(
			static function ( $field ) {
				if ( \is_array( $field ) ) {
					return array_map( 'sanitize_text_field', $field );
				}

				return sanitize_text_field( $field );
			},
			$_POST['shopmagic_optin'] ?? []
		);
		$email          = isset( $_POST[ self::EMAIL ] ) ? sanitize_email( wp_unslash( $_POST[ self::EMAIL ] ) ) : '';
		$this->save_opt_changes( $email, $sanitized_post );

		$back_url = add_query_arg( [ 'success' => 1 ], wp_get_referer() );
		wp_safe_redirect( $back_url );
		exit;
	}

	/**
	 * @param string[] $request
	 */
	private function save_opt_changes( string $email, array $request ): void {
		$preferences = $this->subscription_manager->get_repository()->find_by( [ self::EMAIL => $email ] );

		foreach ( $preferences as $preference ) {
			if (
				isset( $request[ $preference->get_list_id() ] ) &&
				in_array( $request[ $preference->get_list_id() ], [ CheckboxField::VALUE_TRUE, '1' ] )
			) {
				$preference->set_active( true );
			} else {
				$preference->set_active( false );
			}

			$this->subscription_manager->save( $preference );
		}
	}
}
