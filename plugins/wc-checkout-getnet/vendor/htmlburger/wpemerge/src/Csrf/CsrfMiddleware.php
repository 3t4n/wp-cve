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

namespace CoffeeCode\WPEmerge\Csrf;

use Closure;
use CoffeeCode\Psr\Http\Message\ResponseInterface;
use CoffeeCode\WPEmerge\Requests\RequestInterface;

/**
 * Store current request data and clear old request data
 */
class CsrfMiddleware {
	/**
	 * CSRF service.
	 *
	 * @var Csrf
	 */
	protected $csrf = null;

	/**
	 * Constructor.
	 *
	 * @param Csrf $csrf
	 */
	public function __construct( $csrf ) {
		$this->csrf = $csrf;
	}

	/**
	 * Reject requests that fail nonce validation.
	 *
	 * @param  RequestInterface     $request
	 * @param  Closure              $next
	 * @param  mixed                $action
	 * @return ResponseInterface
	 * @throws InvalidCsrfTokenException
	 */
	public function handle( RequestInterface $request, Closure $next, $action = -1 ) {
		if ( ! $request->isReadVerb() ) {
			$token = $this->csrf->getTokenFromRequest( $request );
			if ( ! $this->csrf->isValidToken( $token, $action ) ) {
				throw new InvalidCsrfTokenException();
			}
		}

		$this->csrf->generateToken( $action );

		return $next( $request );
	}
}
