<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Placeholder;

use ShopMagicVendor\WPDesk\View\Renderer\Renderer;
use ShopMagicVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use WPDesk\ShopMagic\FormField\Field\SelectField;
use WPDesk\ShopMagic\Helper\TemplateResolver;

/**
 * Enable placeholders to render template from file.
 */
final class TemplateRendererForPlaceholders {

	/** @var Renderer */
	private $renderer;

	public function __construct( Renderer $renderer ) {
		$this->renderer = $renderer;
	}

	/**
	 * @deprecated 3.0.16
	 */
	public static function with_template_dir( string $template_dir ): TemplateRendererForPlaceholders {
		return new self( new SimplePhpRenderer( TemplateResolver::for_public( 'placeholder/' . $template_dir ) ) );
	}

	/** @return \ShopMagicVendor\WPDesk\Forms\Field[] */
	public function get_template_selector_field(): array {
		return [
			( new SelectField() )
				->set_name( 'template' )
				->set_label( __( 'Template', 'shopmagic-for-woocommerce' ) )
				->set_options( $this->get_possible_templates() )
				->set_required(),
		];
	}

	/** @return array<string, string> */
	private function get_possible_templates(): array {
		return apply_filters(
			'shopmagic/core/placeholder/products_ordered/templates',
			[
				'comma_separated_list' => __( 'Comma separated list', 'shopmagic-for-woocommerce' ),
				'unordered_list'       => __( 'Bullet list', 'shopmagic-for-woocommerce' ),
				'grid_2_col'           => __( 'Grid - 2 columns', 'shopmagic-for-woocommerce' ),
				'grid_3_col'           => __( 'Grid - 3 columns', 'shopmagic-for-woocommerce' ),
			]
		);
	}

	/**
	 * @param string|null $template Array key passed may be empty. If so, take first template.
	 * @param array $parameters Data injected into template.
	 */
	public function render( ?string $template, array $parameters ): string {
		$template = $template ?? array_keys( $this->get_possible_templates() )[0];
		return $this->renderer->render( $template, $parameters );
	}
}
