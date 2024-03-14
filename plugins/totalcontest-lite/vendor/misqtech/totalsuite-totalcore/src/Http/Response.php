<?php

namespace TotalContestVendors\TotalCore\Http;

use TotalContestVendors\TotalCore\Contracts\Http\Response as ResponseContract;

/**
 * Class Response
 * @package TotalContestVendors\TotalCore\Http
 */
class Response implements ResponseContract {
	/**
	 * @var string $content
	 */
	public $content;
	/**
	 * @var int $status
	 */
	public $status = 200;
	/**
	 * @var Headers $headers
	 */
	public $headers;

	/**
	 * Response constructor.
	 *
	 * @param string $content
	 * @param int    $status
	 * @param array  $headers
	 */
	public function __construct( $content = '', $status = 200, $headers = [] ) {
		$this->content = $content;
		$this->status  = $status;
		$this->headers = new Headers( $headers, $status );
	}

	/**
	 * Send response.
	 *
	 * @return $this
	 */
	public function send() {
		if ( ! headers_sent() ):

			if ( ! isset( $this->headers['Date'] ) ):
				$this->headers['Date'] = \DateTime::createFromFormat( 'U', time() )->format( 'c' );
			endif;

			$this->headers->send();
		endif;

		echo $this->content;

		return $this;

	}

}