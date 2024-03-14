<?php

namespace WP_VGWORT;

/**
 * Column_Definition
 *
 * Class for definition of columns for metis_list_table
 *
 * @var name            the name of the column = name in items array
 * @var label           the lable of the column is displayed in the header and used for error
 * @var edit_type       the type of the input - can be input or select
 * @var select_options  key value array for options when edit_type is select
 * @var field_type      Type of input - text or number
 * @var max             On number the maximum value / on String the maximum length  / max=0 no validation
 * @var min            On number the minimum value / on String the minimum length   / min=0 no validation
 * @var required        true / false
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class Column_Definition {
	const METIS_EDIT_TYPE_INPUT = "INPUT";
	const METIS_EDIT_TYPE_SELECT = "SELECT";
	const METIS_FIELD_TYPE_NUMBER = "number";
	const METIS_FIELD_TYPE_TEXT = "text";


	public string $name;
	public string $label;
	public string $edit_type;
	public $select_options;
	public string $field_type;
	public int $max;
	public int $min;
	public bool $required;
	public string $edit;


	/**
	 * init columns
	 */
	public function __construct() {
		$this->name           = '';
		$this->label          = '';
		$this->linkid         = '';
		$this->linkattr       = '';
		$this->edit_type      = self::METIS_EDIT_TYPE_INPUT;
		$this->field_type     = self::METIS_FIELD_TYPE_TEXT;
		$this->select_options = [];
		$this->max            = 0;
		$this->min            = 0;
		$this->required       = false;
		$this->edit           = '';
	}

}
