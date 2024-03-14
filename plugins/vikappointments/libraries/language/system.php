<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  language
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

JLoader::import('adapter.language.handler');

/**
 * Switcher class to translate the VikAppointments plugin system languages.
 *
 * @since 	1.0
 */
class VikAppointmentsLanguageSystem implements JLanguageHandler
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
		$config = VAPFactory::getConfig();
		
		$result = null;

		/**
		 * Translations go here.
		 * @tip Use 'TRANSLATORS:' comment to attach a description of the language.
		 */

		switch ($string)
		{
			/**
			 * MVC errors.
			 */

			case 'ERROR':
			case 'FATAL_ERROR':
				$result = __('Error', 'vikappointments');
				break;

			case 'JERROR_ALERTNOAUTHOR':
				$result = __('You are not authorised to view this resource.', 'vikappointments');
				break;

			case 'CONTROLLER_FILE_NOT_FOUND_ERR':
				$result = __('The controller does not exist.', 'vikappointments');
				break;

			case 'CONTROLLER_CLASS_NOT_FOUND_ERR':
				$result = __('The controller [%s] classname does not exist.', 'vikappointments');
				break;

			case 'CONTROLLER_INVALID_INSTANCE_ERR':
				$result = __('The controller must be an instance of JController.', 'vikappointments');
				break;

			case 'CONTROLLER_PROTECTED_METHOD_ERR':
				$result = __('You cannot call JController reserved methods.', 'vikappointments');
				break;

			case 'TEMPLATE_VIEW_NOT_FOUND_ERR':
				$result = __('Template view not found.', 'vikappointments');
				break;

			case 'RESOURCE_AUTH_ERROR':
				$result = __('You are not authorised to access this resource.', 'vikappointments');
				break;

			case 'JINVALID_TOKEN':
				$result = __('The most recent request was denied because it had an invalid security token. Please refresh the page and try again.', 'vikappointments');
				break;

			case 'JINVALID_TOKEN_NOTICE':
				$result = __('The security token did not match. The request was aborted to prevent any security breach. Please try again.', 'vikappointments');
				break;

			case 'PLG_RECAPTCHA_ERROR_INCORRECT_CAPTCHA_SOL':
				$result = __('The CAPTCHA was incorrect.', 'vikappointments');
				break;

			case 'CONNECTION_LOST':
				// translation provided by wordpress
				$result = __('Connection lost or the server is busy. Please try again later.');
				break;

			/**
			 * Native ACL rules.
			 */

			case 'VAPACLMENUTITLE':
				$result = __('VikAppointments - Access Control List', 'vikappointments');
				break;

			case 'JACTION_ADMIN':
				$result = __('Configure ACL & Options', 'vikappointments');
				break;

			case 'JACTION_ADMIN_COMPONENT_DESC':
				$result = __('Allows users in the group to edit the options and permissions of this plugin.', 'vikappointments');
				break;

			case 'JACTION_MANAGE':
				$result = __('Access Administration Interface', 'vikappointments');
				break;

			case 'JACTION_MANAGE_COMPONENT_DESC':
				$result = __('Allows users in the group to access the administration interface for this plugin.', 'vikappointments');
				break;

			case 'JACTION_CREATE':
				$result = __('Create', 'vikappointments');
				break;

			case 'JACTION_CREATE_COMPONENT_DESC':
				$result = __('Allows users in the group to create any content in this plugin.', 'vikappointments');
				break;

			case 'JACTION_DELETE':
				$result = __('Delete', 'vikappointments');
				break;

			case 'JACTION_DELETE_COMPONENT_DESC':
				$result = __('Allows users in the group to delete any content in this plugin.', 'vikappointments');
				break;

			case 'JACTION_EDIT':
				$result = __('Edit', 'vikappointments');
				break;

			case 'JACTION_EDIT_COMPONENT_DESC':
				$result = __('Allows users in the group to edit any content in this plugin.', 'vikappointments');
				break;

			case 'JACTION_EDITSTATE':
				$result = __('Edit State', 'vikappointments');
				break;

			case 'JACTION_EDITSTATE_COMPONENT_DESC':
				$result = __('Allows users in the group to change the state of any content in this extension.', 'vikappointments');
				break;

			/**
			 * ACL form.
			 */

			case 'ACL_SAVE_SUCCESS':
				$result = __('ACL saved.', 'vikappointments');
				break;

			case 'ACL_SAVE_ERROR':
				$result = __('An error occurred while saving the ACL.', 'vikappointments');
				break;

			case 'JALLOWED':
				$result = __('Allowed', 'vikappointments');
				break;

			case 'JDENIED':
				$result = __('Denied', 'vikappointments');
				break;

			case 'JACTION':
				$result = __('Action', 'vikappointments');
				break;

			case 'JNEW_SETTING':
				$result = __('New Setting', 'vikappointments');
				break;

			case 'JCURRENT_SETTING':
				$result = __('Current Setting', 'vikappointments');
				break;

			/**
			 * Toolbar buttons.
			 */

			case 'JTOOLBAR_NEW':
				$result = __('New', 'vikappointments');
				break;

			case 'JTOOLBAR_EDIT':
				$result = __('Edit', 'vikappointments');
				break;

			case 'JTOOLBAR_BACK':
				$result = __('Back', 'vikappointments');
				break;

			case 'JTOOLBAR_PUBLISH':
				$result = __('Publish', 'vikappointments');
				break;

			case 'JTOOLBAR_UNPUBLISH':
				$result = __('Unpublish', 'vikappointments');
				break;

			case 'JTOOLBAR_ARCHIVE':
				$result = __('Archive', 'vikappointments');
				break;

			case 'JTOOLBAR_UNARCHIVE':
				$result = __('UnArchive', 'vikappointments');
				break;

			case 'JTOOLBAR_DELETE':
				$result = __('Delete', 'vikappointments');
				break;

			case 'JTOOLBAR_TRASH':
				$result = __('Trash', 'vikappointments');
				break;

			case 'JSAVE':
			case 'JAPPLY':
			case 'JTOOLBAR_APPLY':
				$result = __('Save', 'vikappointments');
				break;

			case 'JTOOLBAR_SAVE':
				$result = __('Save & Close', 'vikappointments');
				break;

			case 'JTOOLBAR_SAVE_AND_NEW':
				$result = __('Save & New', 'vikappointments');
				break;

			case 'JTOOLBAR_SAVE_AS_COPY':
				$result = __('Save as Copy', 'vikappointments');
				break;

			case 'JTOOLBAR_CANCEL':
			case 'JCANCEL':
				$result = __('Cancel', 'vikappointments');
				break;

			case 'JTOOLBAR_CLOSE':
				$result = __('Close', 'vikappointments');
				break;

			case 'JTOOLBAR_OPTIONS':
				$result = __('Permissions', 'vikappointments');
				break;

			case 'JTOOLBAR_SHORTCODES':
				$result = __('Shortcodes', 'vikappointments');
				break;

			/**
			 * Filters.
			 */

			case 'JOPTION_SELECT_LANGUAGE':
				$result = __('- Select Language -', 'vikappointments');
				break;

			case 'JOPTION_SELECT_PUBLISHED':
				$result = __('- Select Status -', 'vikappointments');
				break;

			case 'JOPTION_SELECT_TYPE':
				$result = __('- Select Type -', 'vikappointments');
				break;

			case 'JSEARCH_FILTER_SUBMIT':
				$result = __('Search', 'vikappointments');
				break;

			case 'JSEARCH_TOOLS':
				$result = __('Search Tools', 'vikappointments');
				break;

			case 'JSEARCH_FILTER_CLEAR':
				$result = __('Clear', 'vikappointments');
				break;

			/**
			 * Access options.
			 */

			case 'JOPTION_ACCESS_SHOW_ALL_ACCESS':
				$result = __('Show All Access', 'vikappointments');
				break;

			case 'JOPTION_ACCESS_PUBLIC':
				$result = __('Public', 'vikappointments');
				break;

			case 'JOPTION_ACCESS_GUEST':
				$result = __('Guest', 'vikappointments');
				break;

			case 'JOPTION_ACCESS_REGISTERED':
				$result = __('Registered', 'vikappointments');
				break;

			case 'JOPTION_ACCESS_SPECIAL':
				$result = __('Special', 'vikappointments');
				break;

			case 'JOPTION_ACCESS_SUPERUSER':
				$result = __('Super User', 'vikappointments');
				break;

			/**
			 * Fields.
			 */

			case 'JFIELD_ALIAS_LABEL':
				$result = __('Alias', 'vikappointments');
				break;

			case 'JFIELD_ACCESS_LABEL':
				$result = __('Access', 'vikappointments');
				break;

			case 'JFIELD_ACCESS_DESC':
				$result = __('The access level group that is allowed to view this item.', 'vikappointments');
				break;

			case 'JFIELD_META_DESCRIPTION_LABEL':
				$result = __('Meta Description', 'vikappointments');
				break;

			case 'JFIELD_META_DESCRIPTION_DESC':
				$result = __('An optional paragraph to be used as the description of the page in the HTML output. This will generally display in the results of search engines.', 'vikappointments');
				break;

			case 'JFIELD_META_KEYWORDS_LABEL':
				$result = __('Meta Keywords', 'vikappointments');
				break;

			case 'JFIELD_META_KEYWORDS_DESC':
				$result = __('An optional comma-separated list of keywords and/or phrases to be used in the HTML output.', 'vikappointments');
				break;

			case 'COM_CONTENT_FIELD_BROWSER_PAGE_TITLE_LABEL':
				$result = __('Browser Page Title', 'vikappointments');
				break;

			case 'COM_CONTENT_FIELD_BROWSER_PAGE_TITLE_DESC':
				$result = __('Optional text for the "Browser page title" element to be used when the post is viewed with a non-post menu item. If blank, the post\'s title is used instead.', 'vikappointments');
				break;

			/**
			 * Pagination.
			 */

			case 'JPAGINATION_ITEMS':
				$result = __('%d items', 'vikappointments');
				break;

			case 'JPAGINATION_PAGE_OF_TOT':
				// @TRANSLATORS: e.g. 1 of 12
				$result = _x('%d of %s', 'e.g. 1 of 12', 'vikappointments');
				break;

			/**
			 * Menu items - fieldset titles.
			 */

			case 'COM_MENUS_REQUEST_FIELDSET_LABEL':
				$result = __('Details', 'vikappointments');
				break;

			/**
			 * Commons.
			 */
			
			case 'JYES':
				$result = __('Yes');
				break;

			case 'JNO':
				$result = __('No');
				break;

			case 'JADMINISTRATOR':
				// @TRANSLATORS: The back-end section of WP.
				$result = _x('Admin', 'The back-end section of WP.', 'vikappointments');
				break;

			case 'JSITE':
				// @TRANSLATORS: The front-end section of WP.
				$result = _x('Site', 'The front-end section of WP.',
				 'vikappointments');
				break;

			case 'JALL':
				$result = __('All', 'vikappointments');
				break;

			case 'JPUBLISHED':
				$result = __('Published', 'vikappointments');
				break;

			case 'JUNPUBLISHED':
				$result = __('Unpublished', 'vikappointments');
				break;

			case 'JTRASHED':
				$result = __('Trashed', 'vikappointments');
				break;

			case 'JID':
			case 'JGRID_HEADING_ID':
				$result = __('ID', 'vikappointments');
				break;

			case 'JGRID_HEADING_ORDERING':
				$result = __('Ordering', 'vikappointments');
				break;

			case 'JORDERINGDISABLED':
				$result = __('Please sort by order to enable reordering', 'vikappointments');
				break;

			case 'JCREATEDBY':
				$result = __('Created By', 'vikappointments');
				break;

			case 'JCREATEDON':
				$result = __('Created On', 'vikappointments');
				break;

			case 'JNAME':
				$result = __('Name', 'vikappointments');
				break;

			case 'JDETAILS':
				$result = __('Details', 'vikappointments');
				break;

			case 'JTYPE':
				$result = __('Type', 'vikappointments');
				break;

			case 'JSHORTCODE':
				$result = __('Shortcode', 'vikappointments');
				break;

			case 'JLANGUAGE':
				$result = __('Language', 'vikappointments');
				break;

			case 'JPOST':
				$result = __('Post', 'vikappointments');
				break;

			case 'JGLOBAL_USERNAME':
				$result = __('Username');
				break;

			case 'JNEXT':
				$result = __('Next');
				break;

			case 'JPREV':
				$result = __('Previous');
				break;

			case 'PLEASE_MAKE_A_SELECTION':
			case 'JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST':
				$result = __('Please first make a selection from the list.', 'vikappointments');
				break;

			case 'JGLOBAL_SELECT_AN_OPTION':
				$result = __('Select an option', 'vikappointments');
				break;

			case 'NO_ROWS_FOUND':
			case 'JGLOBAL_NO_MATCHING_RESULTS':
				$result = __('No rows found.', 'vikappointments');
				break;

			case 'JOPTION_USE_DEFAULT':
				$result = __('- Use Default -', 'vikappointments');
				break;

			case 'JGLOBAL_FIELDSET_BASIC':
			case 'COM_MENUS_BASIC_FIELDSET_LABEL':
				$result = __('Options', 'vikappointments');
				break;

			case 'JGLOBAL_FIELDSET_ADVANCED':
				$result = __('Advanced', 'vikappointments');
				break;

			case 'JGLOBAL_FIELDSET_PUBLISHING':
				$result = __('Publishing', 'vikappointments');
				break;

			case 'JGLOBAL_FIELDSET_METADATA_OPTIONS':
				$result = __('Metadata', 'vikappointments');
				break;

			case 'JGLOBAL_MAXIMUM_UPLOAD_SIZE_LIMIT':
				$result = __('Maximum upload size: <strong>%s</strong>', 'vikappointments');
				break;

			case 'JFIELD_ALT_LAYOUT_LABEL':
				$result = __('Layout', 'vikappointments');
				break;

			case 'JFIELD_ALT_MODULE_LAYOUT_DESC':
				$result = __('Use a layout from the supplied widget or overrides in the themes.', 'vikappointments');
				break;

			case 'COM_MODULES_FIELD_MODULECLASS_SFX_LABEL':
				$result = __('Widget Class Suffix', 'vikappointments');
				break;

			case 'COM_MODULES_FIELD_MODULECLASS_SFX_DESC':
				$result = __('A suffix to be applied to the CSS class of the widget. This allows for individual styling.', 'vikappointments');
				break;

			case 'COM_USERS_REGISTRATION_SAVE_SUCCESS':
				$result = __('New user created.');
				break;

			case 'JLIB_APPLICATION_SAVE_SUCCESS':
				$result = __('Item saved.', 'vikappointments');
				break;

			case 'JLIB_APPLICATION_ERROR_SAVE_FAILED':
				$result = __('Save failed with the following error: %s', 'vikappointments');
				break;

			/**
			 * Users.
			 */

			case 'COM_USERS_LOGIN_REMEMBER_ME':
				// translation provided by wordpress
				$result = __('Remember me');
				break;

			case 'COM_USERS_LOGIN_RESET':
				// translation provided by wordpress
				$result = __('Lost your password?');
				break;

			/**
			 * Media manager.
			 */

			case 'JMEDIA_PREVIEW_TITLE':
				$result = __('Image preview', 'vikappointments');
				break;

			case 'JMEDIA_CHOOSE_IMAGE':
				$result = __('Choose an image', 'vikappointments');
				break;

			case 'JMEDIA_CHOOSE_IMAGES':
				$result = __('Choose one or more images', 'vikappointments');
				break;

			case 'JMEDIA_SELECT':
				$result = __('Select', 'vikappointments');
				break;

			case 'JMEDIA_UPLOAD_BUTTON':
				$result = __('Pick or upload an image', 'vikappointments');
				break;

			case 'JMEDIA_CLEAR_BUTTON':
				$result = __('Clear selection', 'vikappointments');
				break;

			/**
			 * Dates.
			 */

			case 'DATE_FORMAT_LC':
				$result = get_option('date_format', 'l, d F Y');
				break;

			case 'DATE_FORMAT_LC1':
				// $result = __('l, d F Y', 'vikappointments');
				$result = get_option('date_format', 'l, d F Y');
				break;

			case 'DATE_FORMAT_LC2':
				// $result = __('l, d F Y H:i', 'vikappointments');
				$result = get_option('date_format', 'l, d F Y') . ' ' . get_option('time_format', 'H:i');
				break;

			case 'DATE_FORMAT_LC3':
				// $result = __('d F Y', 'vikappointments');
				$result = get_option('date_format', 'l, d F Y');
				break;

			case 'DATE_FORMAT_LC4':
				// $result = __('Y-m-d', 'vikappointments');
				$result = $config->get('dateformat');
				break;

			case 'DATE_FORMAT_LC5':
				// $result = __('Y-m-d H:i', 'vikappointments');
				$result = $config->get('dateformat') . ' ' . $config->get('timeformat');
				break;

			case 'DATE_FORMAT_LC6':
				// $result = __('Y-m-d H:i:s', 'vikappointments');
				$result = $config->get('dateformat') . ' ' . str_replace(':i', ':i:s', $config->get('timeformat'));
				break;
				
			case 'JANUARY':
				$result = __('January');
				break;

			case 'FEBRUARY':
				$result = __('February');
				break;

			case 'MARCH':
				$result = __('March');
				break;

			case 'APRIL':
				$result = __('April');
				break;

			case 'MAY':
				$result = __('May');
				break;

			case 'JUNE':
				$result = __('June');
				break;

			case 'JULY':
				$result = __('July');
				break;

			case 'AUGUST':
				$result = __('August');
				break;

			case 'SEPTEMBER':
				$result = __('September');
				break;

			case 'OCTOBER':
				$result = __('October');
				break;

			case 'NOVEMBER':
				$result = __('November');
				break;

			case 'DECEMBER':
				$result = __('December');
				break;

			case 'JANUARY_SHORT':
				$result = _x('Jan', 'January abbreviation');
				break;

			case 'FEBRUARY_SHORT':
				$result = _x('Feb', 'February abbreviation');
				break;

			case 'MARCH_SHORT':
				$result = _x('Mar', 'March abbreviation');
				break;

			case 'APRIL_SHORT':
				$result = _x('Apr', 'April abbreviation');
				break;

			case 'MAY_SHORT':
				$result = _x('May', 'May abbreviation');
				break;

			case 'JUNE_SHORT':
				$result = _x('Jun', 'June abbreviation');
				break;

			case 'JULY_SHORT':
				$result = _x('Jul', 'July abbreviation');
				break;

			case 'AUGUST_SHORT':
				$result = _x('Aug', 'August abbreviation');
				break;

			case 'SEPTEMBER_SHORT':
				$result = _x('Sep', 'September abbreviation');
				break;

			case 'OCTOBER_SHORT':
				$result = _x('Oct', 'October abbreviation');
				break;

			case 'NOVEMBER_SHORT':
				$result = _x('Nov', 'November abbreviation');
				break;

			case 'DECEMBER_SHORT':
				$result = _x('Dec', 'December abbreviation');
				break;

			case 'MONDAY':
				$result = __('Monday');
				break;

			case 'TUESDAY':
				$result = __('Tuesday');
				break;

			case 'WEDNESDAY':
				$result = __('Wednesday');
				break;

			case 'THURSDAY':
				$result = __('Thursday');
				break;

			case 'FRIDAY':
				$result = __('Friday');
				break;

			case 'SATURDAY':
				$result = __('Saturday');
				break;

			case 'SUNDAY':
				$result = __('Sunday');
				break;

			case 'MON':
				$result = __('Mon');
				break;

			case 'TUE':
				$result = __('Tue');
				break;

			case 'WED':
				$result = __('Wed');
				break;

			case 'THU':
				$result = __('Thu');
				break;

			case 'FRI':
				$result = __('Fri');
				break;

			case 'SAT':
				$result = __('Sat');
				break;

			case 'SUN':
				$result = __('Sun');
				break;

			/**
			 * Relative dates.
			 */
			
			case 'JLIB_HTML_DATE_RELATIVE_LESSTHANAMINUTE':
				$result = __('Less than a minute ago.', 'vikappointments');
				break;

			case 'JLIB_HTML_DATE_RELATIVE_MINUTES':
				$result = __('%d minutes ago.', 'vikappointments');
				break;

			case 'JLIB_HTML_DATE_RELATIVE_MINUTES_1':
				$result = __('a minute ago.', 'vikappointments');
				break;

			case 'JLIB_HTML_DATE_RELATIVE_HOURS':
				$result = __('%d hours ago.', 'vikappointments');
				break;

			case 'JLIB_HTML_DATE_RELATIVE_HOURS_1':
				$result = __('an hour ago.', 'vikappointments');
				break;

			case 'JLIB_HTML_DATE_RELATIVE_DAYS':
				$result = __('%d days ago.', 'vikappointments');
				break;

			case 'JLIB_HTML_DATE_RELATIVE_DAYS_1':
				$result = __('a day ago.', 'vikappointments');
				break;

			case 'JLIB_HTML_DATE_RELATIVE_WEEKS':
				$result = __('%d weeks ago.', 'vikappointments');
				break;

			case 'JLIB_HTML_DATE_RELATIVE_WEEKS_1':
				$result = __('a week ago.', 'vikappointments');
				break;

			/**
			 * Natives.
			 */

			case 'VAPSHORTCDSMENUTITLE':
				$result = __('VikAppointments - Shortcodes', 'vikappointments');
				break;

			case 'VAPNEWSHORTCDMENUTITLE':
				$result = __('VikAppointments - New Shortcode', 'vikappointments');
				break;

			case 'VAPEDITSHORTCDMENUTITLE':
				$result = __('VikAppointments - Edit Shortcode', 'vikappointments');
				break;

			case 'VAP_SHORTCODE_VIEW_FRONT':
				$result = __('View page in front-end', 'vikappointments');
				break;

			case 'VAP_SHORTCODE_VIEW_TRASHED':
				$result = __('View trashed post', 'vikappointments');
				break;

			case 'VAP_SHORTCODE_CREATE_PAGE':
				$result = __('Create page', 'vikappointments');
				break;

			case 'VAP_SHORTCODE_CREATE_PAGE_CONFIRM':
				$result = __('You can always manually create a custom page/post and use this shortcode text inside it. By proceeding, a page containing this shortcode will be automatically created. Do you want to go ahead?', 'vikappointments');
				break;

			case 'VAP_SHORTCODE_CREATE_PAGE_SUCCESS':
				$result = __('The shortcode was successfully added to a new page of your website. Visit the new page in the front-end to see its content (if any).', 'vikappointments');
				break;

			case 'VAP_SHORTCODE_PARENT_FIELD':
				$result = __('Parent Shortcode', 'vikappointments');
				break;

			/**
			 * License.
			 */

			case 'VAPINVALIDPROKEY':
				$result = __('Please, enter a valid license key.', 'vikappointments');
				break;

			case 'VAPINVALIDRESPONSE':
				$result = __('Invalid response: %s', 'vikappointments');
				break;

			case 'VAPUPDCOMPLOKCLICK':
				$result = __('Update completed, click here to continue', 'vikappointments');
				break;

			case 'VAPUPDCOMPLNOKCLICK':
				$result = __('Update failed, click here to continue', 'vikappointments');
				break;
		}

		return $result;
	}
}
