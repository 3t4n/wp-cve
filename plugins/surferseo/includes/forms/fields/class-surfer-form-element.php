<?php
/**
 *  Abstract class as boilerplate for all form elements classes.
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Forms\Fields;

use SurferSEO\Forms\Validators\Validator_Is_Required;

/**
 * Boilerplate for all form elements classes.
 */
abstract class Surfer_Form_Element {

	/**
	 * Name of the form.
	 *
	 * @var string
	 */
	protected $name = null;

	/**
	 * Field type name.
	 *
	 * @var string
	 */
	protected $type = null;

	/**
	 * Label for the field.
	 *
	 * @var string
	 */
	protected $label = null;

	/**
	 * Value of the field.
	 *
	 * @var string
	 */
	protected $value = null;

	/**
	 * Hint visible under the field.
	 *
	 * @var string
	 */
	protected $hint = null;

	/**
	 * Placeholder for the value.
	 *
	 * @var string
	 */
	protected $placeholder = null;

	/**
	 * Array of the validators of the field
	 *
	 * @var array
	 */
	protected $validators = array();

	/**
	 * If field has a required validator.
	 *
	 * @var bool
	 */
	protected $is_required = false;

	/**
	 * CSS classes for the field.
	 *
	 * @var string
	 */
	protected $classes = null;

	/**
	 * CSS classes for the field row.
	 *
	 * @var string
	 */
	protected $row_classes = null;

	/**
	 * Custom renderer to render field in different than default way.
	 *
	 * @var mixed
	 */
	protected $renderer = null;

	/**
	 * Order of the field in the form.
	 *
	 * @var int
	 */
	protected $order = 0;

	/**
	 * List of errors of the field.
	 *
	 * @var array
	 */
	protected $errors = array();

	/**
	 * Basic construct.
	 *
	 * @param string $name - name of the field.
	 */
	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * Returns form name.
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Returns field type.
	 *
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Returns field value.
	 *
	 * @return string
	 */
	public function get_value() {
		return $this->value;
	}

	/**
	 * Sets field value
	 *
	 * @param string $value - value for the field.
	 * @return void
	 */
	public function set_value( $value ) {
		$this->value = $value;
	}

	/**
	 * Returns field label
	 *
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * Sets field label.
	 *
	 * @param string $label - label for the field.
	 * @return void
	 */
	public function set_label( $label ) {
		$this->label = $label;
	}

	/**
	 * Returns field hint.
	 *
	 * @return string
	 */
	public function get_renderer() {
		return $this->renderer;
	}

	/**
	 * Sets field hint.
	 *
	 * @param string $renderer - Callback to renderer.
	 * @return void
	 */
	public function set_renderer( $renderer ) {
		$this->renderer = $renderer;
	}

	/**
	 * Checks if field has a renderer.
	 *
	 * @return bool
	 */
	public function has_renderer() {
		if ( null !== $this->renderer ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns field hint.
	 *
	 * @return string
	 */
	public function get_hint() {
		return $this->hint;
	}

	/**
	 * Sets field hint.
	 *
	 * @param string $hint - hint for the field.
	 * @return void
	 */
	public function set_hint( $hint ) {
		$this->hint = $hint;
	}

	/**
	 * Returns field placeholder.
	 *
	 * @return string
	 */
	public function get_placeholder() {
		return $this->placeholder;
	}

	/**
	 * Sets field placeholder.
	 *
	 * @param string $placeholder - placeholder for the field.
	 * @return void
	 */
	public function set_placeholder( $placeholder ) {
		$this->placeholder = $placeholder;
	}

	/**
	 * Returns field order.
	 *
	 * @return string
	 */
	public function get_order() {
		return $this->order;
	}

	/**
	 * Sets row css classes.
	 *
	 * @param string $classes - CSS classes.
	 * @return void
	 */
	public function set_row_classes( $row_classes ) {
		$this->row_classes = $row_classes;
	}

	/**
	 * Returns row css classes.
	 *
	 * @return string
	 */
	public function get_row_classes() {
		return $this->row_classes;
	}

	/**
	 * Sets field order.
	 *
	 * @param string $classes - CSS classes.
	 * @return void
	 */
	public function set_classes( $classes ) {
		$this->classes = $classes;
	}

	/**
	 * Returns field classes.
	 *
	 * @return string
	 */
	public function get_classes() {
		return $this->classes;
	}

	/**
	 * Sets field order.
	 *
	 * @param string $order - order in the form.
	 * @return void
	 */
	public function set_order( $order ) {
		$this->order = $order;
	}

	/**
	 * Returns list of errors in the field.
	 *
	 * @return array
	 */
	public function get_errors() {
		return $this->errors;
	}

	/**
	 * Sets field hint.
	 *
	 * @param string $error - error in the field.
	 * @return void
	 */
	public function add_error( $error ) {
		$this->errors[] = $error;
	}

	/**
	 * Returns list of validators
	 *
	 * @return array
	 */
	public function get_validators() {
		return $this->validators;
	}

	/**
	 * Adds new validator to list.
	 *
	 * @param Surfer_Validator $validator - validator.
	 * @return void
	 */
	public function add_validator( $validator ) {
		if ( $validator instanceof Validator_Is_Required ) {
			$this->is_required = true;
		}

		$this->validators[] = $validator;
	}

	/**
	 * Returns information if field is required.
	 *
	 * @return bool
	 */
	public function get_is_required() {
		return $this->is_required;
	}

	/**
	 * Set if field is required.
	 *
	 * @param bool $is_required - if field is required.
	 * @return void
	 */
	public function set_is_required( $is_required ) {
		$this->is_required = $is_required;
	}

	/**
	 * Renders field.
	 *
	 * @return void
	 */
	public function render() {
		if ( null !== $this->renderer ) {
			call_user_func( $this->renderer, $this );
		} else {
			$this->default_renderer();
		}
	}

	/**
	 * Executed field default renderer.
	 *
	 * @return void
	 */
	abstract protected function default_renderer();

	/**
	 * Checks if provided value meet all requirements from validator.
	 *
	 * @param array $value - value to validate.
	 * @return bool
	 */
	public function validate( $value ) {
		$valid = true;

		foreach ( $this->validators as $validator ) {
			$validator_result = $validator->validate( $value );
			if ( ! $validator_result ) {
				$this->errors[] = $validator->get_error();
				$valid          = false;
			}
		}

		return $valid;
	}

	/**
	 * Returns array of allowed HTML for form element rendering
	 *
	 * @return array
	 */
	protected function return_allowed_html_for_forms_elements() {
		$allowed_html = array(
			'input'    => array(
				'id'       => array(),
				'name'     => array(),
				'class'    => array(),
				'type'     => array(),
				'value'    => array(),
				'checked'  => array(),
				'selected' => array(),
			),
			'select'   => array(
				'id'    => array(),
				'name'  => array(),
				'class' => array(),
			),
			'option'   => array(
				'value'    => array(),
				'selected' => array(),
			),
			'textarea' => array(
				'id'    => array(),
				'name'  => array(),
				'class' => array(),
			),
			'a'        => array(
				'href'  => array(),
				'id'    => array(),
				'class' => array(),
			),
			'small'    => array(),
			'br'       => array(),
			'label'    => array(
				'for' => array(),
			),
			'span'     => array(
				'id'    => array(),
				'class' => array(),
				'style' => array(),
			),
		);

		return $allowed_html;
	}
}
