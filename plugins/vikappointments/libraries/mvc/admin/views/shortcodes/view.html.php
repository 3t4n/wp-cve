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
 * VikAppointments Shortcodes view.
 *
 * @since 1.0
 */
class VikAppointmentsViewShortcodes extends JViewVAP
{
	/**
	 * @override
	 * View display method.
	 *
	 * @return 	void
	 */
	public function display($tpl = null)
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$user  = JFactory::getUser();

		if (!$user->authorise('core.admin', 'com_vikappointments'))
		{
			wp_die(
				'<h1>' . JText::translate('FATAL_ERROR') . '</h1>' .
				'<p>' . JText::translate('RESOURCE_AUTH_ERROR') . '</p>',
				403
			);
		}

		$this->returnLink = $input->getBase64('return', '');

		// get filters
		$filters = array();
		$filters['search'] = $app->getUserStateFromRequest('shortcode.filters.search', 'filter_search', '', 'string');
		$filters['lang']   = $app->getUserStateFromRequest('shortcode.filters.lang', 'filter_lang', '*', 'string');
		$filters['type']   = $app->getUserStateFromRequest('shortcode.filters.type', 'filter_type', '', 'string');

		$this->filters = $filters;

		// get shortcodes

		$this->limit  = $app->getUserStateFromRequest('shortcodes.limit', 'limit', $app->get('list_limit'), 'uint');
		$this->offset = $this->getListLimitStart($filters);
		$this->navbut = '';

		$this->shortcodes = $this->hierarchicalShortcodes();

		JLoader::import('adapter.filesystem.folder');

		$this->views = array();

		// get all the views that contain a default.xml file
		// [0] : base path
		// [1] : query
		// [2] : true for recursive search
		// [3] : true to return full paths
		$files = JFolder::files(VAPBASE . DIRECTORY_SEPARATOR . 'views', 'default.xml', true, true);

		foreach ($files as $f)
		{
			// retrieve the view ID from the path: /views/[ID]/tmpl/default.xml
			if (preg_match("/[\/\\\\]views[\/\\\\](.*?)[\/\\\\]tmpl[\/\\\\]default\.xml$/i", $f, $matches))
			{
				$id = $matches[1];
				// load the XML form
				$form = JForm::getInstance($id, $f);
				// get the view title
				$this->views[$id] = (string) $form->getXml()->layout->attributes()->title;
			}
		}

		$this->addToolbar();
		
		// display parent
		parent::display($tpl);
	}

	/**
	 * Helper method to setup the toolbar.
	 *
	 * @return 	void
	 */
	public function addToolbar()
	{
		JToolbarHelper::title(JText::translate('VAPSHORTCDSMENUTITLE'));

		JToolbarHelper::addNew('shortcodes.add');
		JToolbarHelper::editList('shortcodes.edit');
		JToolbarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'shortcodes.delete');
		JToolbarHelper::cancel('shortcodes.back');
	}

	/**
	 * Checks for advanced filters set in the request.
	 *
	 * @return 	boolean  True if active, otherwise false.
	 */
	protected function hasFilters()
	{
		return ($this->filters['lang'] != '*'
			|| !empty($this->filters['type']));
	}

	/**
	 * Retrieves the shortcodes by using a hierarchical ordering.
	 * 
	 * @return 	array  An array of shortcodes.
	 * 
	 * @since 	1.2.3
	 */
	protected function hierarchicalShortcodes()
	{
		$dbo = JFactory::getDbo();

		/**
		 * Loads all the existing shortcodes.
		 *
		 * @since 1.2.3
		 */
		
		$q = $dbo->getQuery(true)
			->select('SQL_CALC_FOUND_ROWS *')
			->from($dbo->qn('#__vikappointments_wpshortcodes'));

		/**
		 * Filters the shortcodes by using the requested values.
		 *
		 * @since 1.1.5
		 */

		if ($this->filters['search'])
		{
			$q->where($dbo->qn('name') . ' LIKE ' . $dbo->q("%{$this->filters['search']}%"));
		}

		if ($this->filters['lang'] != '*')
		{
			$q->where($dbo->qn('lang') . ' = ' . $dbo->q($this->filters['lang']));
		}

		if ($this->filters['type'])
		{
			$q->where($dbo->qn('type') . ' = ' . $dbo->q($this->filters['type']));
		}

		$dbo->setQuery($q);
		$dbo->execute();

		if (!$dbo->getNumRows())
		{
			return [];
		}

		$model = JModelVAP::getInstance('vikappointments', 'shortcode', 'admin');

		$shortcodes = [];

		foreach ($dbo->loadObjectList() as $shortcode)
		{
			// load shortcode ancestors
			$shortcode->ancestors = $model->getAncestors($shortcode);

			// create ordering leverage, based on version comparison
			$tmp = array_merge([$shortcode->id], $shortcode->ancestors);
			$shortcode->leverage = implode('.', array_reverse($tmp));

			$shortcodes[] = $shortcode;
		}

		// sort shortcodes by comparing the evaluated leverage
		usort($shortcodes, function($a, $b)
		{
			return version_compare($a->leverage, $b->leverage);
		});

		// create pagination
		jimport('joomla.html.pagination');
		$pageNav = new JPagination(count($shortcodes), $this->offset, $this->limit);
		$this->navbut = JLayoutHelper::render('blocks.pagination', ['pageNav' => $pageNav]);

		// take only the records that metch the pagination query
		$shortcodes = array_splice($shortcodes, $this->offset, $this->limit);

		return $shortcodes;
	}
}
