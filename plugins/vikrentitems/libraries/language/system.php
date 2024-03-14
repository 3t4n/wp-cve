<?php
/** 
 * @package   	VikRentItems - Libraries
 * @subpackage 	language
 * @author    	E4J s.r.l.
 * @copyright 	Copyright (C) 2018 E4J s.r.l. All Rights Reserved.
 * @license  	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link 		https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

JLoader::import('adapter.language.handler');

/**
 * Switcher class to translate the VikRentItems plugin system languages.
 *
 * @since 	1.0
 */
class VikRentItemsLanguageSystem implements JLanguageHandler
{
	/**
	 * Checks if exists a translation for the given string.
	 *
	 * @param 	string 	$string  The string to translate.
	 *
	 * @return 	string 	The translated string, otherwise null.
	 */
	public function translate($string)
	{
		$result = null;

		/**
		 * Translations go here.
		 * @tip Use 'TRANSLATORS:' comment to attach a description of the language.
		 */

		switch ($string)
		{
			/**
			 * MVC ERRORS
			 */

			case 'FATAL_ERROR':
				$result = __('Error', 'vikrentitems');
				break;

			case 'CONTROLLER_FILE_NOT_FOUND_ERR':
				$result = __('The controller does not exist.', 'vikrentitems');
				break;

			case 'CONTROLLER_CLASS_NOT_FOUND_ERR':
				$result = __('The controller [%s] classname does not exist.', 'vikrentitems');
				break;

			case 'CONTROLLER_INVALID_INSTANCE_ERR':
				$result = __('The controller must be an instance of JController.', 'vikrentitems');
				break;

			case 'CONTROLLER_PROTECTED_METHOD_ERR':
				$result = __('You cannot call JController reserved methods.', 'vikrentitems');
				break;

			case 'TEMPLATE_VIEW_NOT_FOUND_ERR':
				$result = __('Template view not found.', 'vikrentitems');
				break;

			case 'RESOURCE_AUTH_ERROR':
				$result = __('You are not authorised to access this resource.', 'vikrentitems');
				break;

			/**
			 * Invalid token for CSRF protection.
			 * 
			 * @see  	this key will actually terminate the whole process.
			 * @since 	1.0.2
			 */
			case 'JINVALID_TOKEN':
				wp_nonce_ays(JSession::getFormTokenAction());
				break;

			/**
			 * NATIVE ACL RULES
			 */

			case 'VRIACLMENUTITLE':
				$result = __('Vik Rent Items - Access Control List', 'vikrentitems');
				break;

			case 'JACTION_ADMIN':
				$result = __('Configure ACL & Options', 'vikrentitems');
				break;

			case 'JACTION_ADMIN_COMPONENT_DESC':
				$result = __('Allows users in the group to edit the options and permissions of this plugin.', 'vikrentitems');
				break;

			case 'JACTION_MANAGE':
				$result = __('Access Administration Interface', 'vikrentitems');
				break;

			case 'JACTION_MANAGE_COMPONENT_DESC':
				$result = __('Allows users in the group to access the administration interface for this plugin.', 'vikrentitems');
				break;

			case 'JACTION_CREATE':
				$result = __('Create', 'vikrentitems');
				break;

			case 'JACTION_CREATE_COMPONENT_DESC':
				$result = __('Allows users in the group to create any content in this plugin.', 'vikrentitems');
				break;

			case 'JACTION_DELETE':
				$result = __('Delete', 'vikrentitems');
				break;

			case 'JACTION_DELETE_COMPONENT_DESC':
				$result = __('Allows users in the group to delete any content in this plugin.', 'vikrentitems');
				break;

			case 'JACTION_EDIT':
				$result = __('Edit', 'vikrentitems');
				break;

			case 'JACTION_EDIT_COMPONENT_DESC':
				$result = __('Allows users in the group to edit any content in this plugin.', 'vikrentitems');
				break;

			case 'CONNECTION_LOST':
				// translation provided by wordpress
				$result = __('Connection lost or the server is busy. Please try again later.');
				break;

			/**
			 * ACL Form
			 */

			case 'ACL_SAVE_SUCCESS':
				$result = __('ACL saved.', 'vikrentitems');
				break;

			case 'ACL_SAVE_ERROR':
				$result = __('An error occurred while saving the ACL.', 'vikrentitems');
				break;

			case 'JALLOWED':
				$result = __('Allowed', 'vikrentitems');
				break;

			case 'JDENIED':
				$result = __('Denied', 'vikrentitems');
				break;

			case 'JACTION':
				$result = __('Action', 'vikrentitems');
				break;

			case 'JNEW_SETTING':
				$result = __('New Setting', 'vikrentitems');
				break;

			case 'JCURRENT_SETTING':
				$result = __('Current Setting', 'vikrentitems');
				break;

			/**
			 * TOOLBAR BUTTONS
			 */

			case 'JTOOLBAR_NEW':
				$result = __('New', 'vikrentitems');
				break;

			case 'JTOOLBAR_EDIT':
				$result = __('Edit', 'vikrentitems');
				break;

			case 'JTOOLBAR_BACK':
				$result = __('Back', 'vikrentitems');
				break;

			case 'JTOOLBAR_PUBLISH':
				$result = __('Publish', 'vikrentitems');
				break;

			case 'JTOOLBAR_UNPUBLISH':
				$result = __('Unpublish', 'vikrentitems');
				break;

			case 'JTOOLBAR_ARCHIVE':
				$result = __('Archive', 'vikrentitems');
				break;

			case 'JTOOLBAR_UNARCHIVE':
				$result = __('UnArchive', 'vikrentitems');
				break;

			case 'JTOOLBAR_DELETE':
				$result = __('Delete', 'vikrentitems');
				break;

			case 'JTOOLBAR_TRASH':
				$result = __('Trash', 'vikrentitems');
				break;

			case 'JTOOLBAR_APPLY':
				$result = __('Save', 'vikrentitems');
				break;

			case 'JTOOLBAR_SAVE':
				$result = __('Save & Close', 'vikrentitems');
				break;

			case 'JTOOLBAR_SAVE_AND_NEW':
				$result = __('Save & New', 'vikrentitems');
				break;

			case 'JTOOLBAR_SAVE_AS_COPY':
				$result = __('Save as Copy', 'vikrentitems');
				break;

			case 'JTOOLBAR_CANCEL':
				$result = __('Cancel', 'vikrentitems');
				break;

			case 'JTOOLBAR_OPTIONS':
				$result = __('Permissions', 'vikrentitems');
				break;

			case 'JTOOLBAR_SHORTCODES':
				$result = __('Shortcodes', 'vikrentitems');
				break;

			/**
			 * FILTERS
			 */

			case 'JOPTION_SELECT_LANGUAGE':
				$result = __('- Select Language -', 'vikrentitems');
				break;

			case 'JOPTION_SELECT_TYPE':
				$result = __('- Select Type -', 'vikrentitems');
				break;

			case 'JSEARCH_FILTER_SUBMIT':
				$result = __('Search', 'vikrentitems');
				break;

			/**
			 * PAGINATION
			 */

			case 'JPAGINATION_ITEMS':
				$result = __('%d items', 'vikrentitems');
				break;

			case 'JPAGINATION_PAGE_OF_TOT':
				// @TRANSLATORS: e.g. 1 of 12
				$result = _x('%d of %s', 'e.g. 1 of 12', 'vikrentitems');
				break;

			/**
			 * MENU ITEMS - FIELDSET TITLES
			 */

			case 'COM_MENUS_REQUEST_FIELDSET_LABEL':
				$result = __('Details', 'vikrentitems');
				break;

			/**
			 * GENERIC
			 */
			
			case 'JYES':
				$result = __('Yes');
				break;

			case 'JNO':
				$result = __('No');
				break;

			case 'JALL':
				$result = __('All', 'vikrentitems');
				break;

			case 'JID':
			case 'JGRID_HEADING_ID':
				$result = __('ID', 'vikrentitems');
				break;

			case 'JCREATEDBY':
				$result = __('Created By', 'vikrentitems');
				break;

			case 'JCREATEDON':
				$result = __('Created On', 'vikrentitems');
				break;

			case 'JNAME':
				$result = __('Name', 'vikrentitems');
				break;

			case 'JTYPE':
				$result = __('Type', 'vikrentitems');
				break;

			case 'JSHORTCODE':
				$result = __('Shortcode', 'vikrentitems');
				break;

			case 'JLANGUAGE':
				$result = __('Language', 'vikrentitems');
				break;

			case 'JPOST':
				$result = __('Post', 'vikrentitems');
				break;

			case 'PLEASE_MAKE_A_SELECTION':
				$result = __('Please first make a selection from the list.', 'vikrentitems');
				break;

			case 'JSEARCH_FILTER_CLEAR':
				$result = __('Clear', 'vikrentitems');
				break;

			case 'NO_ROWS_FOUND':
				$result = __('No rows found.', 'vikrentitems');
				break;

			case 'VRISHORTCDSMENUTITLE':
				$result = __('Vik Rent Items - Shortcodes', 'vikrentitems');
				break;

			case 'VRINEWSHORTCDMENUTITLE':
				$result = __('Vik Rent Items - New Shortcode', 'vikrentitems');
				break;

			case 'VRIEDITSHORTCDMENUTITLE':
				$result = __('Vik Rent Items - Edit Shortcode', 'vikrentitems');
				break;

			/**
			 * Media manager.
			 */

			case 'JMEDIA_PREVIEW_TITLE':
				$result = __('Image preview', 'vikrentitems');
				break;

			case 'JMEDIA_CHOOSE_IMAGE':
				$result = __('Choose an image', 'vikrentitems');
				break;

			case 'JMEDIA_CHOOSE_IMAGES':
				$result = __('Choose one or more images', 'vikrentitems');
				break;

			case 'JMEDIA_SELECT':
				$result = __('Select', 'vikrentitems');
				break;

			case 'JMEDIA_UPLOAD_BUTTON':
				$result = __('Pick or upload an image', 'vikrentitems');
				break;

			case 'JMEDIA_CLEAR_BUTTON':
				$result = __('Clear selection', 'vikrentitems');
				break;

			/**
			 * Pro version warning
			 */
			
			case 'VRIPROVEXPWARNUPD':
				$result = __('The Pro license for VikRentItems has expired. Do not install any updates or you will downgrade the plugin to the Free version.');
				break;
		}

		return $result;
	}
}
