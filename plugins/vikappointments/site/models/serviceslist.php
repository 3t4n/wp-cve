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
 * VikAppointments services list view model.
 *
 * @since 1.7
 */
class VikAppointmentsModelServiceslist extends JModelVAP
{
	/**
	 * Loads a list of services to be displayed within the
	 * services list site view.
	 *
	 * @param 	array  $filters  
	 *
	 * @return 	array  A list of services, grouped by category.
	 */
	public function getItems(array $filters = array())
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select('s.*');
		$q->select(array(
			$dbo->qn('g.id', 'group_id'),
			$dbo->qn('g.name', 'group_name'),
			$dbo->qn('g.description', 'group_description'),
		));
		$q->select('(' . $this->getRatingQuery($dbo) . ') AS ' . $dbo->qn('ratingAVG'));
		$q->select('(' . $this->getReviewsQuery($dbo) . ') AS ' . $dbo->qn('reviewsCount'));

		$q->from($dbo->qn('#__vikappointments_service', 's'));
		$q->leftjoin($dbo->qn('#__vikappointments_group', 'g') . ' ON ' . $dbo->qn('s.id_group') . ' = ' . $dbo->qn('g.id'));

		// get only the published services
		$q->where($dbo->qn('s.published') . ' = 1');

		/**
		 * Retrieve only the services that belong to the view
		 * access level of the current user.
		 *
		 * @since 1.6
		 */
		$levels = JFactory::getUser()->getAuthorisedViewLevels();

		if ($levels)
		{
			$q->where($dbo->qn('s.level') . ' IN (' . implode(', ', $levels) . ')');
		}

		if (!empty($filters['id_group']))
		{
			// retrieve only the services that belong to the specified group
			$q->where($dbo->qn('g.id') . ' = ' . (int) $filters['id_group']);
		}

		$now = JFactory::getDate();

		// get the services with a matching end publishing date
		$q->andWhere(array(
			$dbo->qn('s.end_publishing') . ' IS NULL',
			$dbo->qn('s.end_publishing') . ' = ' . $dbo->q($dbo->getNullDate()),
			$dbo->qn('s.end_publishing') . ' > ' . $dbo->q($now->toSql()),
		), 'OR');

		$q->order(array(
			$dbo->qn('g.ordering') . ' ASC',
			$dbo->qn('s.ordering') . ' ASC',
			$dbo->qn('s.name') . ' ASC',
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
		$dispatcher->trigger('onBuildServicesListQuery', array(&$q, $filters));
		
		$dbo->setQuery($q);
		$groups = $dbo->loadObjectList();

		if ($groups)
		{
			$groups = $this->groupServices($groups);
		}

		// translate groups
		$this->translate($groups);

		/**
		 * Trigger hook to manipulate the query response at runtime. Third party
		 * plugins can alter the resulting list of services (and groups).
		 *
		 * @param 	array   &$groups  An array of groups and the related children.
		 * @param 	JModel  $model    The current model.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onBuildServicesListData', array(&$groups, $this));

		return $groups;
	}

	/**
	 * Returns the inner query that should be used to calculate the
	 * average rating of the services.
	 *
	 * @param 	mixed 	$dbo 	The database object.
	 *
	 * @return 	mixed 	The database query.
	 */
	protected function getRatingQuery($dbo)
	{
		return $dbo->getQuery(true)
			->select('AVG(' . $dbo->qn('re.rating') . ')')
			->from($dbo->qn('#__vikappointments_reviews', 're'))
			->where(array(
				$dbo->qn('s.id') . ' = ' . $dbo->qn('re.id_service'),
				$dbo->qn('re.published') . ' = 1',
			));
	}

	/**
	 * Returns the inner query that should be used to calculate the
	 * number of reviews of the services.
	 *
	 * @param 	mixed 	$dbo 	The database object.
	 *
	 * @return 	mixed 	The database query.
	 */
	protected function getReviewsQuery($dbo)
	{
		return $dbo->getQuery(true)
			->select('COUNT(' . $dbo->qn('re.rating') . ')')
			->from($dbo->qn('#__vikappointments_reviews', 're'))
			->where(array(
				$dbo->qn('s.id') . ' = ' . $dbo->qn('re.id_service'),
				$dbo->qn('re.published') . ' = 1',
			));
	}

	/**
	 * Groups the list of services within parent blocks.
	 * The resulting list will be an array of groups, which
	 * contain the list of children services.
	 *
	 * The services with no group will be placed at the end
	 * of the list, within an empty group.
	 *
	 * @param 	array 	$services 	The list of services to group.
	 *
	 * @return 	array 	The grouped list.
	 */
	protected function groupServices(array $services)
	{
		$groups = array();

		foreach ($services as $s)
		{
			// if the service doesn't belong to a group,
			// the ID will be equals to 0 (as it is casted as INT).
			$id_group = (int) $s->id_group;

			if ($id_group < 0)
			{
				// force to 0 for backward compatibility
				$id_group = 0;
			}

			if (!isset($groups[$id_group]))
			{
				$g = new stdClass;
				$g->id          = $s->group_id;
				$g->name        = $s->group_name;
				$g->description = $s->group_description;
				$g->services    = array();

				$groups[$id_group] = $g;
			}

			// round rating to the closest .0 or .5
			$s->rating = VikAppointments::roundHalfClosest($s->ratingAVG);

			$groups[$id_group]->services[] = $s;
		}

		// check if there is the "uncategorized" group
		if (isset($groups[0]))
		{
			// get the group containing the services with no group
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
	 * Translates the groups and the services.
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

		$service_ids = array();
		$group_ids   = array();

		foreach ($rows as $group)
		{
			$group_ids[] = $group->id;

			foreach ($group->services as $service)
			{
				$service_ids[] = $service->id;
			}
		}

		// pre-load services translations
		$serLang = $translator->load('service', array_unique($service_ids), $langtag);
		// pre-load services groups translations
		$groupLang = $translator->load('group', array_unique($group_ids), $langtag);

		foreach ($rows as $k => $group)
		{
			// translate group for the given language
			$grp_tx = $groupLang->getTranslation($group->id, $langtag);

			if ($grp_tx)
			{
				$rows[$k]->name        = $grp_tx->name;
				$rows[$k]->description = $grp_tx->description;
			}

			foreach ($group->services as $j => $service)
			{
				// translate service for the given language
				$ser_tx = $serLang->getTranslation($service->id, $langtag);

				if ($ser_tx)
				{
					$rows[$k]->services[$j]->name        = $ser_tx->name;
					$rows[$k]->services[$j]->description = $ser_tx->description;
				}
			}
		}
	}
}
