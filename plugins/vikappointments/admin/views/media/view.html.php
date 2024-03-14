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
 * VikAppointments media manager view.
 *
 * @since 1.2
 */
class VikAppointmentsViewmedia extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		$path = $input->getBase64('path', null);

		if ($path)
		{
			$path = rtrim(base64_decode($path), DIRECTORY_SEPARATOR);
		}
		else
		{
			$path = VAPMEDIA;
		}

		if ($input->get('layout') != 'modal')
		{
			$this->ordering = $app->getUserStateFromRequest('vap.media.ordering', 'filter_order', 'date', 'string');
			$this->orderDir = $app->getUserStateFromRequest('vap.media.orderdir', 'filter_order_Dir', 'DESC', 'string');
		}
		else
		{
			// always sort by descending creation date when
			// accessing the media manager modal
			$this->ordering = 'date';
			$this->orderDir = 'DESC';
		}
		
		// retrieve all images and apply filters
		$all_img = AppointmentsHelper::getMediaFromPath($path, array($this->ordering, $this->orderDir));

		if ($input->get('layout') != 'modal')
		{
			// set the toolbar
			$this->addToolBar();
			
			$filters = array();
			$filters['keysearch'] = $app->getUserStateFromRequest('vap.media.keysearch', 'keysearch', '', 'string');

			// pagination
			$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'int');
			$lim0 	= $app->getUserStateFromRequest('vap.media.limitstart', 'limitstart', 0, 'uint');
			$navbut	= "";

			if (!empty($filters['keysearch']))
			{
				$app = array();

				foreach ($all_img as $img)
				{
					$file_name = basename($img);

					if (strpos($file_name, $filters['keysearch']) !== false)
					{
						array_push($app, $img);
					}
				}
				$all_img = $app;
				unset($app);
			}
			
			$tot_count = count($all_img);

			if ($tot_count)
			{
				if ($lim0 % $lim)
				{
					/**
					 * The current offset is not divisible by the selected limit. For this reason,
					 * we need to reset the offset in order to properly display all the items.
					 * 
					 * @since 1.8
					 */
					$lim0 = 0;
				}

				if ($lim0 >= $tot_count)
				{
					/**
					 * We exceeded the pagination, probably because we deleted all the images of the last page
					 * or we changed the search parameters. For this reason, we need to go back to the last
					 * available page.
					 *
					 * @since 1.8
					 */
					$lim0 = floor($tot_count / $lim) * $lim;
				}

				$all_img = array_slice($all_img, $lim0, $lim);

				jimport('joomla.html.pagination');
				$pageNav = new JPagination($tot_count, $lim0, $lim);
				$navbut = JLayoutHelper::render('blocks.pagination', ['pageNav' => $pageNav]);
			}

			$this->navbut  = $navbut;
			$this->filters = $filters;
		}
		else
		{
			/**
			 * Added support for 'modal' layout.
			 *
			 * @since 1.7
			 */
			$this->setLayout('modal');

			// retrieve selected media
			$this->selected = $input->get('media', array(), 'string');
			// check if multi-selection is allowed
			$this->multiple = $input->get('multiple', false, 'bool');
			// check if we should accept also documents
			$this->noFilter = $input->get('nofilter', false, 'bool');

			// unset images that don't exist
			$this->selected = array_filter($this->selected, function($elem) use ($path)
			{
				return $elem && is_file($path . DIRECTORY_SEPARATOR . $elem);
			});

			if ($path === VAPMEDIA)
			{
				// check if we are uploading the media files for the first time
				$this->firstConfig = count($all_img) == 0;
			}
			else
			{
				// disable first config when we are outside the default media path,
				// because we are not going to create any thumbnails
				$this->firstConfig = false;
			}
		}

		$attr = AppointmentsHelper::getDefaultFileAttributes();

		foreach ($all_img as $i => $f)
		{
			$all_img[$i] = AppointmentsHelper::getFileProperties($f, $attr);
		}

		if (VikAppointments::isMultilanguage() && $input->get('layout') != 'modal')
		{
			$translator = VAPFactory::getTranslator();

			// find available translations
			$lang = $translator->getAvailableLang(
				'media',
				array_map(function($row) {
					return $row['name'];
				}, $all_img)
			);

			// assign languages found to the related elements
			foreach ($all_img as $k => $row)
			{
				$all_img[$k]['languages'] = isset($lang[$row['name']]) ? $lang[$row['name']] : array();
			}
		}

		$this->rows = $all_img;
		$this->path = ($path === VAPMEDIA ? '' : $path);
		
		// display the template (default.php)
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	protected function addToolBar()
	{
		// add menu title and some buttons to the page
		JToolBarHelper::title(JText::translate('VAPMAINTITLEVIEWMEDIA'), 'vikappointments');

		$user = JFactory::getUser();
		
		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::addNew('media.add', JText::translate('VAPNEW'));
			JToolBarHelper::divider();	
		}

		if ($user->authorise('core.edit', 'com_vikappointments'))
		{
			JToolbarHelper::editList('media.edit', JText::translate('VAPEDIT'));
			JToolbarHelper::spacer();
		}

		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolBarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'media.delete', JText::translate('VAPDELETE'));
		}
	}
}
