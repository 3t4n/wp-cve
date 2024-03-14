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
 * Abstraction of an iCalendar parser.
 * 
 * @since 1.7.3
 */
abstract class VAPIcalParser
{
	/**
	 * Interface used to extract an iCalendar buffer
	 * from a specific source.
	 * 
	 * @var VAPIcalReader
	 */
	private $reader;

	/**
	 * An array of options.
	 * 
	 * @var JRegistry
	 */
	protected $options;

	/**
	 * Class constructor.
	 * 
	 * @param   VAPIcalReader  $source
	 */
	final public function __construct(VAPIcalReader $reader, array $options = [])
	{
		$this->reader  = $reader;
		$this->options = new JRegistry($options);
	}

	/**
	 * Parses the iCalendar from the buffer.
	 * 
	 * @return 	array 
	 */
	final public function parse()
	{
		// parse calendar
		return $this->parseBuffer($this->reader->load());
	}

	/**
	 * Implements the algorithm used to parse the iCalendar buffer.
	 * 
	 * @param 	string  $buffer
	 * 
	 * @return 	array
	 */
	abstract protected function parseBuffer($buffer);
}
