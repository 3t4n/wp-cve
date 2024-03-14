<?php

namespace WPDesk\ShopMagic\Marketing\Subscribers;

use ShopMagicVendor\WPDesk\View\Renderer\Renderer;
use WPDesk\ShopMagic\Components\HookProvider\HookProvider;
use WPDesk\ShopMagic\Components\HookProvider\HookTrait;
use WPDesk\ShopMagic\Helper\PluginBag;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListRepository;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\NewsletterForm;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SubscriberObjectRepository;


/**
 * Register shortcode responsible for displaying subscription form to end customers on frontend.
 */
final class SubscriptionFormShortcode implements HookProvider {
	use HookTrait;

	/** Request action used in HTML form */
	public const  ACTION        = 'sm_subscribe_user_to_list';
	private const ASSETS_HANDLE = 'shopmagic-form';
	private const SHORTCODE     = 'shopmagic_form';

	/** @var SubscriberObjectRepository */
	private $subscriber_repository;

	/** @var Renderer */
	private $renderer;

	/** @var PluginBag */
	private $plugin_bag;

	/** @var AudienceListRepository */
	private $repository;

	public function __construct(
		AudienceListRepository $repository,
		SubscriberObjectRepository $subscriber_repository,
		PluginBag $plugin_bag,
		Renderer $renderer
	) {
		$this->subscriber_repository = $subscriber_repository;
		$this->repository            = $repository;
		$this->plugin_bag            = $plugin_bag;
		$this->renderer              = $renderer;
	}

	public function hooks(): void {
		add_shortcode(
			self::SHORTCODE,
			function ( $parameters ): string {
				return $this->render_form( $parameters );
			}
		);
		$this->add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );
	}

	/**
	 * @param array{
	 *	id: string,
	 *	name?: string,
	 *	labels?: string,
	 *	doubleOptin?: string,
	 *	agreement?: string
	 * } $params
	 */
	private function render_form( $params ): string {
		try {
			$list = $this->repository->find( (int) $params['id'] );
		} catch ( \Throwable $e ) {
			return '';
		}

		if (
			$this->subscriber_repository->is_subscribed_to_list(
				$this->get_user_email(),
				$list->get_id()
			)
		) {
			return '';
		}

		$shortcode = $list->get_newsletter_form();
		$shortcode = $this->filter_for_backward_compatibility( $shortcode, $params );

		$this->print_scripts();

		return $this->renderer->render(
			'marketing-lists/lists_form',
			[
				'action'       => self::ACTION,
				'list_id'      => $list->get_id(),
				'double_optin' => $shortcode->is_double_opt_in(),
				'show_name'    => $shortcode->is_show_name(),
				'show_labels'  => $shortcode->is_show_labels(),
				'agreement'    => $shortcode->get_agreement(),
			]
		);
	}

	/**
	 * @note Here we use non-strict checking because shortcode values may differ in types.
	 *
	 * @param NewsletterForm $shortcode
	 * @param array{
	 *	id: string,
	 *	name?: string,
	 *	labels?: string,
	 *	doubleOptin?: string,
	 *	agreement?: string
	 * } $parameters
	 *
	 * @return NewsletterForm
	 */
	private function filter_for_backward_compatibility(
		NewsletterForm $shortcode,
		array $parameters
	): NewsletterForm {
		if ( in_array( 'name', $parameters, true ) ) {
			$shortcode->set_show_name( true );
		} elseif ( isset( $parameters['name'] ) && $parameters['name'] == 'false' ) {
			$shortcode->set_show_name( false );
		}

		if ( in_array( 'labels', $parameters, true ) ) {
			$shortcode->set_show_labels( true );
		} elseif ( isset( $parameters['labels'] ) && $parameters['labels'] == 'false' ) {
			$shortcode->set_show_labels( false );
		}

		if ( in_array( 'doubleOptin', $parameters, true ) ) {
			$shortcode->set_double_opt_in( true );
		} elseif ( isset( $parameters['doubleOptin'] ) && $parameters['doubleOptin'] == 'false' ) {
			$shortcode->set_double_opt_in( false );
		}

		if ( isset( $parameters['agreement'] ) ) {
			$shortcode->set_agreement( $parameters['agreement'] );
		}

		return $shortcode;
	}

	private function get_user_email(): string {
		if ( is_user_logged_in() ) {
			return wp_get_current_user()->user_email;
		}

		// TODO: Add support for guest users.

		return '';
	}

	/** @return void */
	private function print_scripts() {
		wp_enqueue_style( self::ASSETS_HANDLE );
		wp_enqueue_script( self::ASSETS_HANDLE );
		wp_localize_script(
			self::ASSETS_HANDLE,
			'shopmagic_form',
			[ 'ajax_url' => admin_url( 'admin-ajax.php' ) ]
		);
	}

	/** @return void */
	private function register_scripts() {
		wp_register_style(
			self::ASSETS_HANDLE,
			$this->plugin_bag->get_assets_url() . '/css/frontend.css',
			[],
			$this->plugin_bag->get_version()
		);
		wp_register_script(
			self::ASSETS_HANDLE,
			$this->plugin_bag->get_url() . '/dist/lists-form.js',
			[ 'wp-i18n' ],
			$this->plugin_bag->get_version(),
			true
		);
		wp_set_script_translations( self::ASSETS_HANDLE, 'shopmagic-for-woocommerce' );
	}

}
