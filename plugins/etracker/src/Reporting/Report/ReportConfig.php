<?php
/**
 * ReportConfig.
 *
 * @link       https://etracker.com
 * @since      2.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Reporting\Report;

use ArrayAccess;

/**
 * ReportConfig is used to build reporting filter for etrackers reporting API.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class ReportConfig implements ArrayAccess {
	/**
	 * Container for config data.
	 *
	 * @var array
	 */
	private $container = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->container = array(
			'startDate'  => gmdate( 'Y-m-d', strtotime( '31 days ago GMT' ) ),
			'endDate'    => gmdate( 'Y-m-d', strtotime( 'yesterday GMT' ) ),
			'limit'      => '5',
			'sortColumn' => '',
			'sortOrder'  => 1,
			'attributes' => array(),
			'figures'    => array(),
		);
	}

	/**
	 * Set config value.
	 *
	 * @param string $name  Field name to set $value for.
	 * @param mixed  $value New value for field $name.
	 */
	public function __set( string $name, $value ) {
		$this->container[ $name ] = $value;
	}

	/**
	 * Build ReportConfig for API request.
	 *
	 * @return array
	 */
	public function build(): array {
		// As $this->container is a plain array no clone is needed.
		$result               = $this->container;
		$result['attributes'] = join( ',', $result['attributes'] );
		$result['figures']    = join( ',', $result['figures'] );

		if ( empty( $result['sortColumn'] ) ) {
			if ( $this->hasAttributes() ) {
				$result['sortColumn'] = $this['attributes'][0];
			} elseif ( $this->hasFigures() ) {
				$result['sortColumn'] = $this['figures'][0];
			}
		}

		return $result;
	}

	/**
	 * Returns true if ReportConfig has attributes, else false.
	 *
	 * @return boolean
	 */
	public function hasAttributes(): bool {
		if ( ! empty( $this['attributes'] ) && is_array( $this['attributes'] ) ) {
			return count( $this['attributes'] ) > 0 ? true : false;
		}
		return false;
	}

	/**
	 * Returns true if ReportConfig has figures, else false.
	 *
	 * @return boolean
	 */
	public function hasFigures(): bool {
		if ( ! empty( $this['figures'] ) && is_array( $this['figures'] ) ) {
			return count( $this['figures'] ) > 0 ? true : false;
		}
		return false;
	}

	/**
	 * Convert ReportConfig into an Array.
	 *
	 * @return array
	 */
	public function toArray(): array {
		return $this->build();
	}

	/**
	 * Set an offset.
	 *
	 * @param mixed $offset ReportConfig key.
	 * @param mixed $value  ReportConfig value for $offset.
	 *
	 * @return void
	 */
	public function offsetSet( $offset, $value ) {
		if ( is_null( $offset ) ) {
			$this->container[] = $value;
		} else {
			$this->container[ $offset ] = $value;
		}
	}

	/**
	 * Whether an offset exists.
	 *
	 * @param mixed $offset ReportConfig key.
	 *
	 * @return boolean
	 */
	public function offsetExists( $offset ): bool {
		return isset( $this->container[ $offset ] );
	}

	/**
	 * Unset an offset.
	 *
	 * @param mixed $offset ReportConfig key.
	 *
	 * @return void
	 */
	public function offsetUnset( $offset ) {
		unset( $this->container[ $offset ] );
	}

	/**
	 * Offset to retrieve.
	 *
	 * @param mixed $offset ReportConfig key.
	 *
	 * @return mixed
	 */
	public function offsetGet( $offset ) {
		return isset( $this->container[ $offset ] ) ? $this->container[ $offset ] : null;
	}

	/**
	 * Set config value and return instance.
	 *
	 * @param string $name  Field name to set $value for.
	 * @param mixed  $value New value for field $name.
	 */
	public function set( string $name, $value ) {
		$this->container[ $name ] = $value;
		return $this;
	}
}
