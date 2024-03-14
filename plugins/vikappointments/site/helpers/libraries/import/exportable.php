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

VAPLoader::import('libraries.import.object');

/**
 * Interface used to define the structure of a class that should
 * be able to export records with an abstract format.
 *
 * @since 1.6
 */
abstract class Exportable
{
	/**
	 * The configuration object.
	 *
	 * @var JRegistry
	 */
	protected $options;

	/**
	 * Class constructor.
	 *
	 * @param 	array 	$options 	An array of options.
	 */
	public function __construct(array $options = array())
	{
		// we must use a registry because empty strings should
		// be interpreted to return the default value
		$this->options = new JRegistry($options);
	}

	/**
	 * Attempts to return a readable name.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	public function getName()
	{
		// extract name from class
		$class = strtoupper(preg_replace("/^Exportable/i", '', get_class($this)));

		$key  = 'VAP_EXPORT_DRIVER_' . $class;
		$name = JText::translate($key);

		if ($key != $name)
		{
			// language definition found
			return $name;
		}

		// just return the final chunk of the class name
		return $class;
	}

	/**
	 * Getter to propagate options to callers.
	 *
	 * @return 	array  An array of options.
	 *
	 * @since 	1.7
	 */
	public function getOptions()
	{
		return $this->options->toArray();
	}

	/**
	 * Creates the file with the given records and downloads it
	 * using the specified filename.
	 *
	 * @param 	string 		  $name 	The file name.
	 * @param 	array 		  $records 	The records to export.
	 * @param 	ImportObject  $obj 		The object handler.
	 *
	 * @return 	void
	 */
	abstract public function download($name, array $records = array(), ImportObject $obj = null);
}
