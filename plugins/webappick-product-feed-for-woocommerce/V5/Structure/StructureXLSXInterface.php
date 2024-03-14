<?php
namespace CTXFeed\V5\Structure;

/**
 * Interface StructureXLSXInterface
 *
 * The StructureXLSXInterface defines the contract that all XLSX structure classes must adhere to.
 * It specifies the methods that should be implemented by any class that wants to act as a structure for generating feeds in XLSX format.
 *
 * @package CTXFeed
 * @subpackage CTXFeed\V5\Structure
 */
interface StructureXLSXInterface {
	/**
	 * Constructor
	 *
	 * This method initializes the XLSX structure instance with the provided configuration.
	 *
	 * @param \Config $config The configuration object containing information needed for XLSX feed generation.
	 */
	public function __construct( $config );

	/**
	 * Get XLSX Structure
	 *
	 * This method returns the structure for generating feeds in XLSX format.
	 *
	 * @return mixed The XLSX structure for feed generation.
	 */
	public function get_xlsx_structure();
}


