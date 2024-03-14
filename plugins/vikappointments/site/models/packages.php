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

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments packages list view model.
 *
 * @since 1.7
 */
class VikAppointmentsModelPackages extends JModelVAP
{
	/**
	 * Loads a list of packages to be displayed within the
	 * packages list site view.
	 *
	 * @param 	array  $filters  
	 *
	 * @return 	array  A list of packages, grouped by category.
	 */
	public function getItems(array $filters = array())
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		$dbo = JFactory::getDbo();

		$groups = array();

		$q = $dbo->getQuery(true);

		$q->select('p.*');
		$q->select(array(
			$dbo->qn('g.id', 'group_id'),
			$dbo->qn('g.title', 'group_title'),
			$dbo->qn('g.description', 'group_description'),
		));

		$q->from($dbo->qn('#__vikappointments_package', 'p'));
		$q->leftjoin($dbo->qn('#__vikappointments_package_group', 'g') . ' ON ' . $dbo->qn('p.id_group') . ' = ' . $dbo->qn('g.id'));

		// get only the published packages
		$q->where($dbo->qn('p.published') . ' = 1');

		/**
		 * Retrieve only the packages that belong to the view
		 * access level of the current user.
		 *
		 * @since 1.6
		 */
		$levels = JFactory::getUser()->getAuthorisedViewLevels();

		if ($levels)
		{
			$q->where($dbo->qn('p.level') . ' IN (' . implode(', ', $levels) . ')');
		}

		if (!empty($filters['id_group']))
		{
			// retrieve only the packages that belong to the specified group
			$q->where($dbo->qn('g.id') . ' = ' . (int) $filters['id_group']);
		}

		$now = JFactory::getDate();

		// get the packages with a matching start publishing date
		$q->andWhere(array(
			$dbo->qn('p.start_ts') . ' IS NULL',
			$dbo->qn('p.start_ts') . ' = ' . $dbo->q($dbo->getNullDate()),
			$dbo->qn('p.start_ts') . ' <= ' . $dbo->q($now->toSql()),
		), 'OR');

		// get the packages with a matching end publishing date
		$q->andWhere(array(
			$dbo->qn('p.end_ts') . ' IS NULL',
			$dbo->qn('p.end_ts') . ' = ' . $dbo->q($dbo->getNullDate()),
			$dbo->qn('p.end_ts') . ' > ' . $dbo->q($now->toSql()),
		), 'OR');

		$q->order(array(
			$dbo->qn('g.ordering') . ' ASC',
			$dbo->qn('p.ordering') . ' ASC',
			$dbo->qn('p.name') . ' ASC',
		));

		/**
		 * Trigger hook to manipulate the query at runtime. Third party plugins
		 * can extend the query by applying further conditions or selecting
		 * additional data.
		 *
		 * @param 	mixed  &$query   Either a query builder or a query string.
		 * @param 	array  $filters  An array of filters.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onBuildPackagesListQuery', array(&$q, $filters));
		
		$dbo->setQuery($q);

		if ($rows = $dbo->loadObjectList())
		{
			$groups = $this->groupPackages($rows);
		}

		// translate groups
		$this->translate($groups);

		/**
		 * Trigger hook to manipulate the query response at runtime. Third party
		 * plugins can alter the resulting list of packages (and groups).
		 *
		 * @param 	array   &$groups  An array of groups and the related children.
		 * @param 	JModel  $model    The current model.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onBuildPackagesListData', array(&$groups, $this));

		return $groups;
	}

	/**
	 * Groups the list of packages within parent blocks.
	 * The resulting list will be an array of groups, which
	 * contain the list of children packages.
	 *
	 * The packages with no group will be placed at the end
	 * of the list, within an empty group.
	 *
	 * @param 	array 	$packages  The list of packages to group.
	 *
	 * @return 	array 	The grouped list.
	 */
	protected function groupPackages(array $packages)
	{
		$groups = array();

		foreach ($packages as $p)
		{
			// if the package doesn't belong to a group,
			// the ID will be equals to 0 (as it is casted as INT).
			$id_group = (int) $p->id_group;

			if (!isset($groups[$id_group]))
			{
				$g = new stdClass;
				$g->id          = $p->group_id;
				$g->title       = $p->group_title;
				$g->description = $p->group_description;
				$g->packages    = array();

				$groups[$id_group] = $g;
			}

			$groups[$id_group]->packages[] = $p;
		}

		// check if there is the "uncategorized" group
		if (isset($groups[0]))
		{
			// get the group containing the packages with no group
			$uncategorized = $groups[0];
			// unset that group
			unset($groups[0]);
			// move that group at the end of the list
			$groups[0] = $uncategorized;
		}

		// reset array keys
		return array_values($groups);
	}

	/**
	 * Translates the groups and the packages.
	 *
	 * @param 	array  &$rows  The rows to translate.
	 *
	 * @return 	void
	 */
	protected function translate(&$rows)
	{
		/**
		 * Ignore translation in case the multilingual feature is disabled.
		 * 
		 * @since 1.7.4
		 */
		if (VAPFactory::getConfig()->getBool('ismultilang') == false)
		{
			return;
		}
		
		$langtag = JFactory::getLanguage()->getTag();

		// get translator
		$translator = VAPFactory::getTranslator();

		$package_ids = array();
		$group_ids   = array();

		foreach ($rows as $group)
		{
			$group_ids[] = $group->id;

			foreach ($group->packages as $package)
			{
				$package_ids[] = $package->id;
			}
		}

		// pre-load packages translations
		$pkgLang = $translator->load('package', array_unique($package_ids), $langtag);
		// pre-load packages groups translations
		$groupLang = $translator->load('packgroup', array_unique($group_ids), $langtag);

		foreach ($rows as $k => $group)
		{
			// translate group for the given language
			$grp_tx = $groupLang->getTranslation($group->id, $langtag);

			if ($grp_tx)
			{
				$rows[$k]->title       = $grp_tx->title;
				$rows[$k]->description = $grp_tx->description;
			}

			foreach ($group->packages as $j => $package)
			{
				// translate package for the given language
				$pkg_tx = $pkgLang->getTranslation($package->id, $langtag);

				if ($pkg_tx)
				{
					$rows[$k]->packages[$j]->name        = $pkg_tx->name;
					$rows[$k]->packages[$j]->description = $pkg_tx->description;
				}
			}
		}
	}
}
