<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * The main class for the Legend Item
 *
 */
class WPSBC_Legend_Item extends WPSBC_Base_Object {

	/**
	 * The Id of the legend item
	 *
	 * @access protected
	 * @var    int
	 *
	 */
	protected $id;

	/**
	 * The legend item type
	 *
	 * @access protected
	 * @var    string
	 *
	 */
	protected $type;

	/**
	 * The legend item name
	 *
	 * @access protected
	 * @var    string
	 *
	 */
	protected $name;

	/**
	 * The legend item background color list
	 *
	 * @access protected
	 * @var    array
	 *
	 */
	protected $color;

	/**
	 * The legend item text color
	 *
	 * @access protected
	 * @var    string
	 *
	 */
	protected $color_text;

	/**
	 * Bool value (0/1) to determine if the legend item is the default one
	 * for the legend
	 *
	 * @access protected
	 * @var    int
	 *
	 */
	protected $is_default;

	/**
	 * Bool value (0/1) to determine if the legend item is visible in the front-end
	 *
	 * @access protected
	 * @var    int
	 *
	 */
	protected $is_visible;

	/**
	 * The ID of the calendar for which the legend item is available
	 *
	 * @access protected
	 * @var    int
	 *
	 */
	protected $calendar_id;

	/**
	 * Returns the name property for the current object, or the translation for it
	 * if the the language code is provided and the translation for that language exists
	 *
	 * @param string $language_code
	 *
	 * @return string
	 *
	 */
	public function get_name( $language_code = '' ) {

		if( empty( $language_code ) )
			return $this->name;

		$translation = wpsbc_get_legend_item_meta( $this->id, 'translation_' . $language_code, true );

		return ( ! empty( $translation ) ? $translation : $this->name );

	}

}