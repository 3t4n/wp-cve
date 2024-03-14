<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

VAPLoader::import('libraries.backup.export.archive');

/**
 * Backup export rule abstraction.
 * 
 * @since 1.7.1
 */
abstract class VAPBackupExportRule implements JsonSerializable
{
	/**
	 * The instance used to manage the archive.
	 * 
	 * @var VAPBackupExportArchive
	 */
	protected $archive;

	/**
	 * Class constructor.
	 * Children classes cannot overwrite this method.
	 * @see setup()
	 * 
	 * @param 	VAPBackupExportArchive  $archive  The archive manager.
	 * @param 	mixed 	                $data     The rule setup data.
	 */
	final public function __construct(VAPBackupExportArchive $archive, $data = null)
	{
		// save a reference to the archive
		$this->archive = $archive;
		// set up the rule data
		$this->setup($data);
	}

	/**
	 * Returns the rule identifier.
	 * 
	 * @return 	string
	 */
	public function getRule()
	{
		// remove the class prefix
		$rule = preg_replace("/^VAPBackupExportRule/", '', get_class($this));
		// place an underscore between each camelCase
		return strtolower(preg_replace("/([a-z])([A-Z])/", '$1_$2', $rule));
	}

	/**
	 * Returns the rules instructions.
	 * 
	 * @return 	mixed
	 */
	abstract public function getData();

	/**
	 * Configures the rule to work according to the specified data.
	 * 
	 * @param 	mixed 	$data  The rule setup data.
	 * 
	 * @return 	void
	 */
	abstract protected function setup($data);

	/**
	 * Creates a standard object, containing all the supported properties,
	 * to be used when this class is passed to "json_encode()".
	 *
	 * @return  object
	 *
	 * @see     JsonSerializable
	 */
	#[ReturnTypeWillChange]
	public function jsonSerialize()
	{
		$rule = new stdClass;
		$rule->role        = $this->getRule();
		$rule->data        = $this->getData();
		$rule->dateCreated = JFactory::getDate()->toSql();

		return $rule;
	}
}
