<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Helper;

use DomainException;
use ShopMagicVendor\WPDesk\View\Renderer\Renderer;
use ShopMagicVendor\WPDesk\View\Resolver\DirResolver;
use ShopMagicVendor\WPDesk\View\Resolver\Exception\CanNotResolve;
use ShopMagicVendor\WPDesk\View\Resolver\WPThemeResolver;
use ShopMagicVendor\WPDesk\View\Resolver\Resolver;

/**
 * ShopMagic specific resolver for template files used in plugin. This
 * class allows to seek template files in theme directory, our own
 * plugin directory and provides an access point to extend search path
 * by other plugins.
 *
 * When referencing template files, use path relative to root templates
 * directory and omit file extension, i.e. referencing `emails/sign_up_confirmation` would match `shopmagic-for-woocommerce/templates/emails/sign_up_confirmation.php`.
 */
final class TemplateResolver implements Resolver {
	/** @var string */
	public const THEME_DIR = 'shopmagic';

	/** @var Resolver[] */
	private $resolvers;

	/** @var ?string */
	private static $root_path;

	public function __construct() {
		$this->resolvers = [
			new WPThemeResolver( self::THEME_DIR ),
		];
	}

	public function add_resolver( Resolver $resolver ): void {
		$this->resolvers[] = $resolver;
	}

	/**
	 * @deprecated 3.0.16
	 */
	public static function set_root_path( string $root_path ): void {
		self::$root_path = $root_path;
	}

	/**
	 * @deprecated 3.0.16
	 */
	public static function for_placeholder( string $subdir = '' ): \WPDesk\ShopMagic\Helper\TemplateResolver {
		return self::for_public( 'placeholder' . DIRECTORY_SEPARATOR . $subdir );
	}

	/**
	 * @deprecated 3.0.16 Regular object access should be preferred, as
	 * this method is missing proper external plugin extension.
	 */
	public static function for_public( string $relative_path = '' ): \WPDesk\ShopMagic\Helper\TemplateResolver {
		if ( empty( self::$root_path ) ) {
			throw new DomainException( 'Template root path not set!' );
		}

		$resolver = new self();
		// Those resolvers are deprecated, as resolver should receive
		// relative path from templates folder, instead of being created
		// with specific path 'in mind'.
		$resolver->add_resolver( new WPThemeResolver( self::THEME_DIR . DIRECTORY_SEPARATOR . $relative_path ) );
		$resolver->add_resolver( new DirResolver( self::$root_path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $relative_path ) );

		return $resolver;
	}

	/**
	 * Resolve name to full path
	 *
	 * @param string $name
	 * @param Renderer|null $renderer
	 *
	 * @return string
	 */
	public function resolve( $name, ?Renderer $renderer = null ): string {
		foreach ( $this->resolvers as $resolver ) {
			try {
				return $resolver->resolve( $name );
			} catch ( CanNotResolve $e ) {
				// not interested.
			}
		}
		throw new CanNotResolve( "Cannot resolve {$name}" );
	}

}
