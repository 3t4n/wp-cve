<?php
/**
 * Contains code for the configuration failure notice class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Notice
 */

namespace Boxtal\BoxtalConnectWoocommerce\Notice;

/**
 * Configuration failure notice class.
 *
 * Configuration failure notice used to display setup error.
 */
class Configuration_Failure_Notice extends Abstract_Notice {

	/**
	 * Construct function.
	 *
	 * @param string $key key for notice.
	 * @void
	 */
	public function __construct( $key ) {
		parent::__construct( $key );
		$this->type         = 'configuration-failure';
		$this->autodestruct = false;
		$this->template     = 'html-configuration-failure-notice';
	}
}
