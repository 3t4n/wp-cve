<?php
/**
 * @package   WPEmerge
 * @author    Atanas Angelov <hi@atanas.dev>
 * @copyright 2017-2019 Atanas Angelov
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0
 * @link      https://wpemerge.com/
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace CoffeeCode\WPEmerge\Flash;

use Closure;
use CoffeeCode\WPEmerge\Requests\RequestInterface;

/**
 * Store current request data and clear old request data
 */
class FlashMiddleware {
	/**
	 * Flash service.
	 *
	 * @var Flash
	 */
	protected $flash = null;

	/**
	 * Constructor.
	 *
	 * @codeCoverageIgnore
	 * @param Flash $flash
	 */
	public function __construct( Flash $flash ) {
		$this->flash = $flash;
	}

	/**
	 * {@inheritDoc}
	 */
	public function handle( RequestInterface $request, Closure $next ) {
		$response = $next( $request );

		if ( $this->flash->enabled() ) {
			$this->flash->shift();
			$this->flash->save();
		}

		return $response;
	}
}
