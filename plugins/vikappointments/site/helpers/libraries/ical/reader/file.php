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

/**
 * Implementor used to extract an iCalendar from a file.
 * 
 * @since 1.7.3 
 */
class VAPIcalReaderFile implements VAPIcalReader
{
	/**
	 * The file path.
	 * 
	 * @var string
	 */
	protected $path;

	/**
	 * Class constructor.
	 * 
	 * @param 	string  $path
	 */
	public function __construct($path)
	{
		$this->path = $path;
	}

	/**
	 * Extracts the iCalendar buffer from a file.
	 * 
	 * @return  string  The iCalendar string.
	 */
	public function load()
	{
		if (!JFile::exists($this->path))
		{
			throw new Exception(sprintf('File [%s] not found', $this->path), 404);
		}

		$buffer = '';

		$fp = fopen($this->path, 'r');

		while (!feof($fp))
		{
			$buffer .= fread($fp, 8192);
		}

		fclose($fp);

		return $buffer;
	}
}
