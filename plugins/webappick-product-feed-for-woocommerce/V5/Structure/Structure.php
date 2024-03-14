<?php

namespace CTXFeed\V5\Structure;

/**
 * Class Structure
 *
 * This class acts as a wrapper for the structure generation functionality.
 * It delegates the structure generation to an object implementing the StructureInterface.
 *
 * @package CTXFeed\V5\Structure
 */

class Structure {

	/**
	 * @var StructureInterface $structure An instance of the structure generation interface.
	 */
	private $structure;

	/**
	 * Structure constructor.
	 *
	 * @param StructureInterface $structure An instance of the structure generation interface.
	 */
	public function __construct( StructureInterface $structure ) {
		$this->structure = $structure;
	}

	/**
	 * Get XML Structure
	 *
	 * Delegates the XML structure generation to the underlying structure object.
	 *
	 * @return mixed The generated XML structure.
	 */
	public function get_xml_structure() {
		return $this->structure->get_xml_structure();
	}

	/**
	 * Get CSV Structure
	 *
	 * Delegates the CSV structure generation to the underlying structure object.
	 *
	 * @return mixed The generated CSV structure.
	 */
	public function get_csv_structure() {
		return $this->structure->get_csv_structure();
	}

	/**
	 * Get TSV Structure
	 *
	 * Delegates the TSV structure generation to the underlying structure object.
	 *
	 * @return mixed The generated TSV structure.
	 */
	public function get_tsv_structure() {
		return $this->structure->get_csv_structure();
	}

	/**
	 * Get TXT Structure
	 *
	 * Delegates the TXT structure generation to the underlying structure object.
	 *
	 * @return mixed The generated TXT structure.
	 */
	public function get_txt_structure() {
		return $this->structure->get_csv_structure();
	}

	/**
	 * Get XLS Structure
	 *
	 * Delegates the XLS structure generation to the underlying structure object.
	 *
	 * @return mixed The generated XLS structure.
	 */
	public function get_xls_structure() {
		return $this->structure->get_csv_structure();
	}

	/**
	 * Get JSON Structure
	 *
	 * Delegates the JSON structure generation to the underlying structure object.
	 *
	 * @return mixed The generated JSON structure.
	 */
	public function get_json_structure() {
		return $this->structure->get_csv_structure();
	}
}
