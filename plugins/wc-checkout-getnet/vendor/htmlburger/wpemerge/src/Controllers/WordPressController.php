<?php /**
 * @package   WPEmerge
 * @author    Atanas Angelov <hi@atanas.dev>
 * @copyright 2017-2019 Atanas Angelov
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0
 * @link      https://wpemerge.com/
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */ /** @noinspection PhpUnusedParameterInspection */
namespace CoffeeCode\WPEmerge\Controllers;

use CoffeeCode\Psr\Http\Message\ResponseInterface;
use CoffeeCode\WPEmerge\Exceptions\ConfigurationException;
use CoffeeCode\WPEmerge\Requests\RequestInterface;
use CoffeeCode\WPEmerge\View\ViewService;

/**
 * Handles normal WordPress requests without interfering
 * Useful if you only want to add a middleware to a route without handling the output
 *
 * @codeCoverageIgnore
 */
class WordPressController {
	/**
	 * View service.
	 *
	 * @var ViewService
	 */
	protected $view_service = null;

	/**
	 * Constructor.
	 *
	 * @codeCoverageIgnore
	 * @param ViewService $view_service
	 */
	public function __construct( ViewService $view_service ) {
		$this->view_service = $view_service;
	}

	/**
	 * Default WordPress handler.
	 *
	 * @param  RequestInterface  $request
	 * @param  string            $view
	 * @return ResponseInterface
	 */
	public function handle( RequestInterface $request, $view = '' ) {
		if ( is_admin() || wp_doing_ajax() ) {
			throw new ConfigurationException(
				'Attempted to run the default WordPress controller on an ' .
				'admin or AJAX page. Did you miss to specify a custom handler for ' .
				'a route or accidentally used \App::route()->all() during admin ' .
				'requests?'
			);
		}

		if ( empty( $view ) ) {
			throw new ConfigurationException(
				'No view loaded for default WordPress controller. ' .
				'Did you miss to specify a custom handler for an ajax or admin route?'
			);
		}

		return $this->view_service->make( $view )
			->toResponse()
			->withStatus( http_response_code() );
	}
}
