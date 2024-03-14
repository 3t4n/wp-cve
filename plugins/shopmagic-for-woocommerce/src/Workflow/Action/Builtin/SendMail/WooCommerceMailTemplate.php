<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Action\Builtin\SendMail;

use ShopMagicVendor\Psr\Log\LoggerAwareInterface;
use ShopMagicVendor\Psr\Log\LoggerAwareTrait;
use ShopMagicVendor\Pelago\Emogrifier\CssInliner;
use ShopMagicVendor\Symfony\Component\CssSelector\Exception\ParseException;
use ShopMagicVendor\WPDesk\View\Renderer\Renderer;
use ShopMagicVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use WPDesk\ShopMagic\Helper\TemplateResolver;


/**
 * WooCommerce mail template.
 * TODO: Integration with \WC_Email class
 */
final class WooCommerceMailTemplate implements MailTemplate, LoggerAwareInterface {
	use LoggerAwareTrait;

	/**
	 * @var string
	 */
	public const NAME = 'woocommerce';
	/**
	 * @var string
	 */
	private const TEMPLATE_PATH = '';

	/** @var string */
	private $heading_value;

	/** @var string|null */
	private $unsubscribe_url;

	/** @var Renderer */
	private $renderer;

	public function __construct(
		string $heading_value,
		string $unsubscribe_url = null,
		?Renderer $renderer = null
	) {
		$this->heading_value   = $heading_value;
		$this->unsubscribe_url = $unsubscribe_url;
		$this->renderer        = $renderer ?? new SimplePhpRenderer( TemplateResolver::for_public() );
	}

	/**
	 * Wrap given content in a WooCommerce mail template.
	 */
	public function wrap_content( string $html_content, array $args = [] ): string {
		$html_content = $this->wrap_html( $html_content );
		$css          = $this->render_css();

		$html_content = $this->encode_inline_css( $html_content, $css );

		return /**
		 * @ignore WooCommerce hook.
		 */
			apply_filters( 'woocommerce_mail_content', $html_content );
	}

	/**
	 * Wrap html into WC template.
	 */
	private function wrap_html( string $html ): string {
		ob_start();

		$this->print_template_part(
			'email-header.php',
			[
				'email_heading' => $this->heading_value,
			]
		);
		echo $html;

		if ( null !== $this->unsubscribe_url ) {
			$append_unsubscribe_link = function ( $content ): string {
				return $content . sprintf( " &middot; <a href='%s'>", $this->unsubscribe_url ) . __(
					'Unsubscribe',
					'shopmagic-for-woocommerce'
				) . '</a>';
			};
			add_filter( 'woocommerce_email_footer_text', $append_unsubscribe_link );
		}

		$this->print_template_part( 'email-footer.php' );

		if ( null !== $this->unsubscribe_url ) {
			remove_filter( 'woocommerce_email_footer_text', $append_unsubscribe_link );
		}

		return (string) ob_get_clean();
	}

	/**
	 * Prints given WooCommerce template.
	 */
	private function print_template_part( string $file, array $args = [] ): void {
		extract( $args, EXTR_SKIP );

		$template_name = 'emails/' . $file;

		$located = wc_locate_template( 'emails/' . $file, self::TEMPLATE_PATH );

		$located =
			/**
			 * @ignore WooCommerce hook.
			 */
			apply_filters( 'wc_get_template', $located, $template_name, $args, self::TEMPLATE_PATH, '' );

		/**
		 * @ignore WooCommerce hook.
		 */
		do_action( 'woocommerce_before_template_part', $template_name, self::TEMPLATE_PATH, $located, $args );

		include $located;
		/**
		 * @ignore WooCommerce hook.
		 */
		do_action( 'woocommerce_after_template_part', $template_name, self::TEMPLATE_PATH, $located, $args );
	}

	/**
	 * Renders WC css.
	 */
	private function render_css(): string {
		ob_start();
		$this->print_template_part( 'email-styles.php' );
		$this->renderer->output_render( 'emails/email-styles' );

		return /**
		 * @ignore WooCommerce hook.
		 */
			apply_filters( 'woocommerce_email_styles', ob_get_clean(), $this );
	}

	/**
	 * Insert css into html in a best way possible.
	 *
	 * @return string HTML with encoded inline css.
	 */
	private function encode_inline_css( string $html, string $css ): string {
		try {
			return CssInliner::fromHtml( $html )->inlineCss( $css )->render();
		} catch ( ParseException $e ) {
			$this->logger->error( $e->getMessage(), [ 'exception' => $e ] );

			return '<style type="text/css">' . $css . '</style>' . $html;
		}
	}

}
