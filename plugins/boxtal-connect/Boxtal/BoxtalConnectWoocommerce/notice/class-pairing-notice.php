<?php
/**
 * Contains code for the pairing notice class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Notice
 */

namespace Boxtal\BoxtalConnectWoocommerce\Notice;

/**
 * Pairing notice class.
 *
 * Successful pairing notice.
 */
class Pairing_Notice extends Abstract_Notice {

	/**
	 * Construct function.
	 *
	 * @param string $key key for notice.
	 * @param array  $args additional args.
	 * @void
	 */
	public function __construct( $key, $args ) {
		parent::__construct( $key );
		$this->type         = 'pairing';
		$this->autodestruct = false;
		$this->template     = $args['result'] ? 'html-pairing-success-notice' : 'html-pairing-failure-notice';
	}
}
