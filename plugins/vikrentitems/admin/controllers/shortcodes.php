<?php

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

JLoader::import('adapter.mvc.controllers.admin');

/**
 * VikRentItems plugin Shortcodes controller.
 *
 * @since 	1.0
 * @see 	JControllerAdmin
 */
class VikRentItemsControllerShortcodes extends JControllerAdmin
{
	public function create()
	{
		if (!JFactory::getUser()->authorise('core.admin', 'com_vikrentitems'))
		{
			wp_die(
				'<h1>' . JText::translate('FATAL_ERROR') . '</h1>' .
				'<p>' . JText::translate('RESOURCE_AUTH_ERROR') . '</p>',
				403
			);
		}

		$input = JFactory::getApplication()->input;

		$input->set('type', 'new');
		$input->set('view', 'shortcode');

		parent::display();
	}

	public function edit()
	{
		if (!JFactory::getUser()->authorise('core.admin', 'com_vikrentitems'))
		{
			wp_die(
				'<h1>' . JText::translate('FATAL_ERROR') . '</h1>' .
				'<p>' . JText::translate('RESOURCE_AUTH_ERROR') . '</p>',
				403
			);
		}

		$input = JFactory::getApplication()->input;

		$input->set('type', 'edit');
		$input->set('view', 'shortcode');

		parent::display();
	}

	public function delete()
	{
		$app 	= JFactory::getApplication();
		$input 	= $app->input;

		$cid 	 = $input->getUint('cid', array());
		$encoded = $input->getBase64('return', '');

		$this->model->delete($cid);

		$app->redirect('admin.php?option=com_vikrentitems&view=shortcodes&return=' . $encoded);
	}

	public function cancel()
	{
		$app = JFactory::getApplication();

		$encoded = $app->input->getBase64('return', '');

		$app->redirect('admin.php?option=com_vikrentitems&view=shortcodes&return=' . $encoded);
	}

	public function back()
	{
		$app = JFactory::getApplication();

		$return = $app->input->getBase64('return', '');

		if ($return)
		{
			$return = base64_decode($return);
		}

		$app->redirect($return);
	}
}
