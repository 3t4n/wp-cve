<?php

namespace WPDesk\FlexibleWishlist\Archive;

use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\FlexibleWishlist\Exception\FormRequestUnauthorized;
use WPDesk\FlexibleWishlist\Exception\InvalidFormData;
use WPDesk\FlexibleWishlist\Exception\InvalidFormRequestId;
use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Exception\TemplateLoadingFailed;
use WPDesk\FlexibleWishlist\Exception\UnauthorizedRequest;
use WPDesk\FlexibleWishlist\Form\CreateWishlistForm;
use WPDesk\FlexibleWishlist\Form\CreateWishlistItemForm;
use WPDesk\FlexibleWishlist\Form\RemoveWishlistForm;
use WPDesk\FlexibleWishlist\Form\RemoveWishlistItemForm;
use WPDesk\FlexibleWishlist\Form\ToggleDefaultWishlistForm;
use WPDesk\FlexibleWishlist\Form\UpdateWishlistItemQuantityForm;
use WPDesk\FlexibleWishlist\Repository\SettingsRepository;
use WPDesk\FlexibleWishlist\Repository\UserRepository;
use WPDesk\FlexibleWishlist\Repository\WishlistItemRepository;
use WPDesk\FlexibleWishlist\Repository\WishlistRepository;
use WPDesk\FlexibleWishlist\Service\FakePageGenerator;
use WPDesk\FlexibleWishlist\Service\TemplateLoader;
use WPDesk\FlexibleWishlist\Service\UrlGenerator;
use WPDesk\FlexibleWishlist\Service\UserAuthManager;
use WPDesk\FlexibleWishlist\Settings\Option\TextArchiveUrlOption;

/**
 * Generates a permalink structure for the wishlist archive.
 */
class PermalinksGenerator implements Hookable {

	const REWRITE_ENDPOINT_PREFIX = 'flexible-wishlist';

	/**
	 * @var TemplateLoader
	 */
	private $template_loader;

	/**
	 * @var UserAuthManager
	 */
	private $user_auth_manager;

	/**
	 * @var SettingsRepository
	 */
	private $settings_repository;

	/**
	 * @var WishlistRepository
	 */
	private $wishlist_repository;

	/**
	 * @var WishlistItemRepository
	 */
	private $wishlist_item_repository;

	/**
	 * @var UserRepository
	 */
	private $user_repository;

	/**
	 * @var FakePageGenerator
	 */
	private $fake_page_generator;

	/**
	 * @var TemplateDataLoader
	 */
	private $template_data_loader;

	/** @var UrlGenerator */
	private $url_generator;

	public function __construct(
		TemplateLoader $template_loader,
		UserAuthManager $user_auth_manager,
		SettingsRepository $settings_repository,
		WishlistRepository $wishlist_repository,
		WishlistItemRepository $wishlist_item_repository,
		UserRepository $user_repository,
		FakePageGenerator $fake_page_generator = null,
		TemplateDataLoader $template_data_loader = null,
		UrlGenerator $url_generator = null
	) {
		$this->template_loader          = $template_loader;
		$this->user_auth_manager        = $user_auth_manager;
		$this->settings_repository      = $settings_repository;
		$this->wishlist_repository      = $wishlist_repository;
		$this->wishlist_item_repository = $wishlist_item_repository;
		$this->user_repository          = $user_repository;
		$this->fake_page_generator      = $fake_page_generator ?: new FakePageGenerator( $settings_repository );
		$this->template_data_loader     = $template_data_loader ?: new TemplateDataLoader( $user_auth_manager, $settings_repository, $wishlist_repository );
		$this->url_generator            = $url_generator ?: new UrlGenerator( $settings_repository );
	}

	/**
	 * {@inheritdoc}
	 */
	public function hooks() {
		add_filter( 'query_vars', [ $this, 'set_rewrite_query_var' ] );
		add_action( 'init', [ $this, 'register_rewrite_endpoint' ] );
		add_filter( 'the_posts', [ $this, 'load_page_template' ], -10 );
	}

	/**
	 * @param string[] $vars .
	 *
	 * @return string[]
	 * @throws InvalidSettingsOptionKey
	 */
	public function set_rewrite_query_var( array $vars ): array {
		$vars[] = $this->settings_repository->get_value( TextArchiveUrlOption::FIELD_NAME );
		return $vars;
	}

	/**
	 * @return void
	 * @throws InvalidSettingsOptionKey
	 * @internal
	 */
	public function register_rewrite_endpoint() {
		$rewrite_endpoint = $this->settings_repository->get_value( TextArchiveUrlOption::FIELD_NAME );
		add_rewrite_endpoint( $rewrite_endpoint, EP_ROOT | EP_PAGES );

		$rules = get_option( 'rewrite_rules' );
		if ( ! $rules || ! isset( $rules[ $rewrite_endpoint . '(/(.*))?/?$' ] ) ) {
			flush_rewrite_rules( false );
		}
	}

	/**
	 * @param object[] $posts .
	 *
	 * @return object[]
	 * @throws InvalidFormData
	 * @throws InvalidFormRequestId
	 * @throws InvalidSettingsOptionKey
	 * @throws FormRequestUnauthorized
	 * @throws TemplateLoadingFailed
	 * @throws UnauthorizedRequest
	 * @internal
	 */
	public function load_page_template( array $posts ): array {
		global $wp_query;

		if ( $wp_query === null ) {
			return $posts;
		}

		$wishlist_id = get_query_var( $this->settings_repository->get_value( TextArchiveUrlOption::FIELD_NAME ), null );
		if ( $wishlist_id === null ) {
			return $posts;
		}

		// We need to remove this filter AFTER checking query vars. Otherwise, query vars are null even if we are on desired page.
		remove_filter( 'the_posts', [ $this, 'load_page_template' ], -10 );

		if ( $this->try_submit_form( $_POST ?: [] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			wp_safe_redirect(
				add_query_arg(
					'success',
					'',
					$this->url_generator->generate()
				)
			);
			exit;
		}

		remove_filter( 'the_content', 'wpautop' );
		remove_filter( 'the_excerpt', 'wpautop' );
		add_filter( 'body_class', [ $this, 'set_body_classes' ] );
		add_filter( 'user_has_cap', [ $this, 'remove_capabilities' ], 10, 3 );

		global $wp_query;
		$wp_query->is_page           = true;
		$wp_query->is_singular       = true;
		$wp_query->is_home           = false;
		$wp_query->is_archive        = false;
		$wp_query->is_category       = false;
		$wp_query->is_404            = false;
		$wp_query->queried_object_id = 0;

		$posts = [];
		if ( $wishlist_id ) {
			$wishlist = $this->wishlist_repository->get_by_token( $wishlist_id );
			if ( $wishlist ) {
				$post    = $this->fake_page_generator->generate_single_page(
					$wishlist->get_name(),
					$this->template_loader->get_template(
						'single',
						$this->template_data_loader->get_single_args( $wishlist )
					)
				);
				$posts[] = $post;
				wp_cache_set( $post->ID, $post, 'posts' );
			}
		} else {
			$post    = $this->fake_page_generator->generate_archive_page(
				$this->template_loader->get_template(
					'archive',
					$this->template_data_loader->get_archive_args()
				)
			);
			$posts[] = $post;
			wp_cache_set( $post->ID, $post, 'posts' );
		}

		return $posts;
	}

	/**
	 * @param mixed[] $form_data .
	 *
	 * @return bool
	 * @throws FormRequestUnauthorized
	 * @throws InvalidFormData
	 * @throws InvalidFormRequestId
	 * @throws InvalidSettingsOptionKey
	 * @throws UnauthorizedRequest
	 */
	private function try_submit_form( array $form_data ) {
		switch ( $form_data['fw_action'] ?? null ) {
			case CreateWishlistForm::ACTION_NAME:
				( new CreateWishlistForm( $this->user_auth_manager, $this->user_repository, $this->wishlist_repository ) )
					->process_request( $form_data );
				return true;
			case ToggleDefaultWishlistForm::ACTION_NAME:
				( new ToggleDefaultWishlistForm( $this->user_auth_manager, $this->wishlist_repository ) )
					->process_request( $form_data );
				return true;
			case RemoveWishlistForm::ACTION_NAME:
				( new RemoveWishlistForm( $this->user_auth_manager, $this->wishlist_repository ) )
					->process_request( $form_data );
				return true;
			case CreateWishlistItemForm::ACTION_NAME:
				( new CreateWishlistItemForm( $this->user_auth_manager, $this->wishlist_repository, $this->wishlist_item_repository ) )
					->process_request( $form_data );
				return true;
			case UpdateWishlistItemQuantityForm::ACTION_NAME:
				( new UpdateWishlistItemQuantityForm( $this->user_auth_manager, $this->wishlist_repository, $this->wishlist_item_repository ) )
					->process_request( $form_data );
				return true;
			case RemoveWishlistItemForm::ACTION_NAME:
				( new RemoveWishlistItemForm( $this->user_auth_manager, $this->wishlist_repository, $this->wishlist_item_repository ) )
					->process_request( $form_data );
				return true;
		}

		return false;
	}

	/**
	 * @param string[] $classes .
	 *
	 * @return string[]
	 * @internal
	 */
	public function set_body_classes( array $classes ): array {
		$classes[] = 'woocommerce-cart';
		$classes[] = 'singular';
		return $classes;
	}

	/**
	 * @param bool[]   $user_caps   .
	 * @param string[] $action_caps .
	 * @param mixed[]  $args        .
	 *
	 * @return bool[]
	 * @internal
	 */
	public function remove_capabilities( array $user_caps, array $action_caps, array $args ): array {
		return ( $args && ( end( $args ) === -999 ) ) ? [] : $user_caps;
	}
}
