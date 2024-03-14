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

namespace CoffeeCode\WPEmerge\Responses;

/**
 * Converts values to a response.
 */
trait ConvertsToResponseTrait {
	/**
	 * Get a Response Service instance.
	 *
	 * @return ResponseService
	 */
	protected abstract function getResponseService();

	/**
	 * Convert a user returned response to a ResponseInterface instance if possible.
	 * Return the original value if unsupported.
	 *
	 * @param  mixed $response
	 * @return mixed
	 */
	protected function toResponse( $response ) {
		if ( is_string( $response ) ) {
			return $this->getResponseService()->output( $response );
		}

		if ( is_array( $response ) ) {
			return $this->getResponseService()->json( $response );
		}

		if ( $response instanceof ResponsableInterface ) {
			return $response->toResponse();
		}

		return $response;
	}
}
