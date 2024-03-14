<?php

namespace TotalContestVendors\TotalCore\Contracts\Form;

use JsonSerializable;
use TotalContestVendors\TotalCore\Contracts\Helpers\Arrayable;

/**
 * Interface Field
 * @package TotalContestVendors\TotalCore\Contracts\Form
 */
interface Field extends Arrayable, JsonSerializable {
	/**
	 * @param $options
	 *
	 * @return mixed
	 */
	public function setOptions( $options );

	public function setOption( $key, $value );

	public function getDefault();

	public function setDefault( $default );

	public function getValue();

	public function setValue( $value );

	public function getName();

	public function setName( $name );

	public function getOption( $key, $default = null );

	public function validate( $rules = [] );

	/**
	 * @return mixed|null
	 * @deprecated
	 */
	public function render( $purgeCache = false );

	/**
	 * @return mixed
	 */
	public function getLabelHtmlElement();

	public function getInputHtmlElement();

	/**
	 * @return array
	 */
	public function getAttributes();

	/**
	 * @return array
	 */
	public function getHtmlValidationAttributes();

	/**
	 * @return array
	 */
	public function getOptions();

	/**
	 * @deprecated
	 * @return array
	 */
	public function getValidationsRules();

	public function onAttach( Page $page );

	public function onDetach( Page $page );
}