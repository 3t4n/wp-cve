<?php

namespace TotalContestVendors\TotalCore\Options;

use TotalContestVendors\TotalCore\Contracts\Foundation\Environment as EnvironmentContract;
use TotalContestVendors\TotalCore\Contracts\Options\Repository as RepositoryContract;
use TotalContestVendors\TotalCore\Helpers\Arrays;
use TotalContestVendors\TotalCore\Helpers\Misc;


/**
 * Class Repository
 * @package TotalContestVendors\TotalCore\Options
 */
class Repository implements RepositoryContract {
	protected $options = [];

	/**
	 * @var EnvironmentContract $environment
	 */
	protected $environment;

	/**
	 * Repository constructor.
	 *
	 * @param EnvironmentContract $environment
	 */

	public function __construct( EnvironmentContract $environment ) {
		$this->environment = $environment;
		$this->options     = Misc::getJsonOption( $this->environment->get( 'options-key' ) );
	}

	/**
	 * Check option existence.
	 *
	 * @param mixed $offset
	 *
	 * @return bool
	 */
    #[\ReturnTypeWillChange]
	public function offsetExists( $offset ) {
		return isset( $this->options[ $offset ] );
	}

	/**
	 * Get option.
	 *
	 * @param mixed $offset
	 *
	 * @return mixed
	 */
    #[\ReturnTypeWillChange]
	public function offsetGet( $offset ) {
		return $this->get( $offset );
	}

	/**
	 * Get option.
	 *
	 * @param $option
	 * @param $default
	 *
	 * @return mixed
	 */
	public function get( $option, $default = null ) {
		return Arrays::getDotNotation( $this->options, $option, $default );
	}

	/**
	 * Set option.
	 *
	 * @param mixed $offset
	 * @param mixed $value
	 *
	 * @return void
	 */
    #[\ReturnTypeWillChange]
	public function offsetSet( $offset, $value ) {
		$this->set( $offset, $value );
	}

	/**
	 * Set option.
	 *
	 * @param      $option
	 * @param      $value
	 *
	 * @param bool $persistent
	 *
	 * @return void
	 */
	public function set( $option, $value, $persistent = true ) {
		Arrays::setDotNotation( $this->options, $option, $value );

		if ( $persistent ):
			$this->save();
		endif;
	}

	/**
	 * Unset option.
	 *
	 * @param mixed $offset
	 */
    #[\ReturnTypeWillChange]
	public function offsetUnset( $offset ) {
		unset( $this->options[ $offset ] );
	}

	/**
	 * Get JSON representation.
	 *
	 * @return array|mixed|object
	 */
    #[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return $this->getOptions();
	}

	/**
	 * Get options.
	 *
	 * @return array
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * Set options.
	 *
	 * @param array $options
	 * @param bool  $persistent
	 *
	 * @return void
	 */
	public function setOptions( $options, $persistent = true ) {
		$this->options = $options;

		if ( $persistent ):
			$this->save();
		endif;
	}

	/**
	 * Get the instance as an array.
	 *
	 * @return array
	 */
	public function toArray() {
		return $this->getOptions();
	}

	/**
	 * Save options.
	 *
	 * @return bool
	 */
	public function save() {
		return update_option( $this->environment->get( 'options-key' ), json_encode( $this->getOptions() ) );
	}

	/**
	 * @param bool $persistent
	 *
	 * @return bool
	 */
	public function deleteOptions( $persistent = true ) {
		$this->options = [];

		if ( $persistent ):
			return delete_option( $this->environment->get( 'options-key' ) );
		endif;

		return true;
	}
}
