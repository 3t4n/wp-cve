<?php
namespace CTXFeed\V5\Structure;

/**
 * Interface StructureInterface
 *
 * The StructureInterface defines the contract that all structure classes must adhere to.
 * It specifies the methods that should be implemented by any class that wants to act as a structure for generating feeds in various formats.
 *
 * @package CTXFeed
 * @subpackage CTXFeed\V5\Structure
 */
interface StructureInterface {
	/**
	 * Constructor
	 *
	 * This method initializes the structure instance with the provided configuration.
	 *
	 * @param \Config $config The configuration object containing information needed for feed generation.
	 */
	public function __construct( $config );

	/**
	 * Get XML Structure
	 *
	 * This method returns the structure for generating feeds in XML format.
	 *
	 * @return mixed The XML structure for feed generation.
	 */
	public function get_xml_structure();

	/**
	 * Get CSV Structure
	 *
	 * This method returns the structure for generating feeds in CSV format.
	 *
	 * @return mixed The CSV structure for feed generation.
	 */
	public function get_csv_structure();

	/**
	 * Get TSV Structure
	 *
	 * This method returns the structure for generating feeds in TSV format.
	 *
	 * @return mixed The TSV structure for feed generation.
	 */
	public function get_tsv_structure();

	/**
	 * Get TXT Structure
	 *
	 * This method returns the structure for generating feeds in TXT format.
	 *
	 * @return mixed The TXT structure for feed generation.
	 */
	public function get_txt_structure();

	/**
	 * Get XLS Structure
	 *
	 * This method returns the structure for generating feeds in XLS format.
	 *
	 * @return mixed The XLS structure for feed generation.
	 */
	public function get_xls_structure();

	/**
	 * Get JSON Structure
	 *
	 * This method returns the structure for generating feeds in JSON format.
	 *
	 * @return mixed The JSON structure for feed generation.
	 */
	public function get_json_structure();
}


