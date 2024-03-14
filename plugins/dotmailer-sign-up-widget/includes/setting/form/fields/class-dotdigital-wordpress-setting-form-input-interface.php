<?php

/**
 * An interface for the front end input model.
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Includes\Setting\Form\Fields;

interface Dotdigital_WordPress_Setting_Form_Input_Interface
{
    /**
     * Render the input.
     *
     * @return void
     */
    public function render() : void;
    /**
     * Validate the input.
     *
     * @param string $value
     *
     * @return mixed
     */
    public function validate($value);
    /**
     * Get the page of the input.
     *
     * @return mixed
     */
    public function get_page();
    /**
     * Get the name of the input.
     *
     * @return string
     */
    public function get_name() : string;
    /**
     * Get the title of the input.
     *
     * @return string
     */
    public function get_label() : string;
    /**
     * Get the type of the input.
     *
     * @return string
     */
    public function get_type() : string;
    /**
     * Get the value of the input.
     *
     * @return string
     */
    public function get_value() : string;
    /**
     * Get the group of the input.
     *
     * @return string
     */
    public function get_group() : string;
    /**
     * Is disabled input.
     *
     * @return bool
     */
    public function is_disabled() : bool;
    /**
     * Set the field to disabled.
     *
     * @return void
     */
    public function set_is_disabled() : void;
}
