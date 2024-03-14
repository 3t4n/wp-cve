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
 * VikAppointments tag table.
 *
 * @since 1.7
 */
class VAPTableTag extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_tag', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'name';
		$this->_requiredFields[] = 'group';
	}

	/**
	 * Method to bind an associative array or object to the Table instance. This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   array|object  $src     An associative array or object to bind to the Table instance.
	 * @param   array|string  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 */
	public function bind($src, $ignore = array())
	{
		$src = (array) $src;

		// fetch ordering for new separators
		if (empty($src['id']))
		{
			$dbo = JFactory::getDbo();

			// fetch next ordering
			$src['ordering'] = $this->getNextOrder($dbo->qn('group') . ' = ' . $dbo->q(@$src['group']));

			// set creation date and author
			$src['createdon'] = JFactory::getDate()->toSql();
			$src['author'] = JFactory::getUser()->id;
		}

		// validate color, if specified
		if (!empty($src['color']) && !preg_match("/^#?[0-9a-f]{6,6}$/i", $src['color']))
		{
			// invalid color
			$src['color'] = '';
		}

		if ((empty($src['id']) || isset($src['color'])) && empty($src['color']))
		{
			// use random color
			$src['color'] = JHtml::fetch('vaphtml.color.preset');
		}

		if (!empty($src['color']))
		{
			// always trim initial "#"
			$src['color'] = ltrim($src['color'], '#');
		}

		// bind the details before save
		return parent::bind($src, $ignore);
	}

	/**
	 * Method to perform sanity checks on the Table instance properties to
	 * ensure they are safe to store in the database.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 */
	public function check()
	{
		// check integrity using parent
		if (!parent::check())
		{
			return false;
		}

		if (isset($this->name))
		{
			// make sure we are not creating a duplicate entry
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select(1)
				->from($dbo->qn($this->getTableName()))
				->where($dbo->qn('name') . ' = ' . $dbo->q($this->name));

			if ($this->id)
			{
				// exclude current record
				$q->where($dbo->qn('id') . ' <> ' . (int) $this->id);
			}

			$dbo->setQuery($q, 0, 1);
			$dbo->execute();

			if ($dbo->getNumRows())
			{
				// a tag with the specified name already exists
				$this->setError('Duplicate entry.');

				return false;
			}
		}

		return true;
	}
}
