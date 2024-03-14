<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist\Service;

use FlexibleWishlistVendor\WPDesk\View\Renderer\Renderer;
use FlexibleWishlistVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use FlexibleWishlistVendor\WPDesk\View\Resolver\ChainResolver;
use FlexibleWishlistVendor\WPDesk\View\Resolver\DirResolver;
use FlexibleWishlistVendor\WPDesk\View\Resolver\Exception\CanNotResolve;
use FlexibleWishlistVendor\WPDesk\View\Resolver\WPThemeResolver;
use WPDesk\FlexibleWishlist\Exception\TemplateLoadingFailed;

/**
 * Loads the template required by the plugin.
 */
class TemplateLoader {

	/**
	 * @var string
	 */
	private $plugin_path;

	/**
	 * @var string
	 */
	private $theme_templates_path;

	/**
	 * @var Renderer|null
	 */
	private $renderer = null;

	public function __construct( string $plugin_path, string $theme_templates_path ) {
		$this->plugin_path          = $plugin_path;
		$this->theme_templates_path = $theme_templates_path;
	}

	private function get_renderer(): Renderer {
		$resolver = new ChainResolver();
		$resolver->appendResolver( new WPThemeResolver( $this->theme_templates_path ) );

		$template_paths = apply_filters(
			'flexible_wishlist/templates_paths',
			[
				untrailingslashit( $this->plugin_path ) . '/templates',
			]
		);
		foreach ( $template_paths as $template_path ) {
			$resolver->appendResolver( new DirResolver( $template_path ) );
		}

		return new SimplePhpRenderer( $resolver );
	}

	/**
	 * @param string  $template_path .
	 * @param mixed[] $params        .
	 *
	 * @return string
	 * @throws TemplateLoadingFailed
	 */
	public function get_template( string $template_path, array $params ): string {
		$this->renderer = $this->renderer ?: $this->get_renderer();

		try {
			return $this->renderer->render( $template_path, $params );
		} catch ( CanNotResolve $e ) {
			throw new TemplateLoadingFailed( $e->getMessage() );
		}
	}

	/**
	 * @param string  $template_path .
	 * @param mixed[] $params        .
	 *
	 * @return void
	 * @throws TemplateLoadingFailed
	 */
	public function load_template( string $template_path, array $params ) {
		$this->renderer = $this->renderer ?: $this->get_renderer();

		try {
			$this->renderer->output_render( $template_path, $params );
		} catch ( CanNotResolve $e ) {
			throw new TemplateLoadingFailed( $e->getMessage() );
		}
	}
}
