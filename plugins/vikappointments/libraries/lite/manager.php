<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  lite
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Manager class used to setup the LITE version of the plugin.
 *
 * @since 1.2.3
 */
abstract class VikAppointmentsLiteManager
{
	/**
	 * Flag used to avoid initializing the setup more than once.
	 * 
	 * @var boolean
	 */
	private static $setup = false;

	/**
	 * Accessor used to start the setup.
	 * 
	 * @param 	mixed  $helper  The implementor instance or a static class.
	 * 
	 * @return 	void
	 */
	final public static function setup($helper = null)
	{
		if (!static::$setup && !static::guessPro())
		{
			if (!$helper)
			{
				// use the default implementor
				VikAppointmentsLoader::import('lite.helper');
				$helper = new VikAppointmentsLiteHelper();
			}

			// set up only once and in case of missing PRO version
			static::$setup = static::doSetup($helper);
		}
	}

	/**
	 * Helper method used to assume whether the PRO version is
	 * installed or not, because it is not enough to check whether
	 * a PRO license is registered. In example, we cannot automatically
	 * re-enable the LITE restrictions after a PRO license expires.
	 * 
	 * @return 	boolean
	 */
	public static function guessPro()
	{
		// immediately check whether we have a valid PRO license
		if (VikAppointmentsLicense::isPro())
		{
			return true;
		}

		// Missing PRO license or expired... First make sure the
		// license key was specified.
		if (!VikAppointmentsLicense::getKey())
		{
			// missing license key, never allow usage of PRO features
			return false;
		}

		// Check whether the PRO license was ever installed, which
		// can be easily done by looking for the PayPal integration.
		return VikAppointmentsLoader::import('payments.paypal');
	}

	/**
	 * Setup implementor.
	 * 
	 * @param 	mixed  $helper  The implementor instance or a static class.
	 * 
	 * @return 	boolean
	 */
	protected static function doSetup($helper)
	{
		/**
		 * Filters which capabilities a role has.
		 *
		 * @since 2.0.0
		 *
		 * @param 	bool[]  $capabilities  Array of key/value pairs where keys represent a capability name and boolean values
		 *                                 represent whether the role has that capability.
		 * @param 	string  $cap           Capability name.
		 * @param 	string  $name          Role name.
		 */
		add_filter('role_has_cap', array($helper, 'restrictCapabilities'));

		/**
		 * Dynamically filter a user's capabilities.
		 *
		 * @since 2.0.0
		 * @since 3.7.0 Added the `$user` parameter.
		 *
		 * @param 	bool[]    $allcaps  Array of key/value pairs where keys represent a capability name
		 *                              and boolean values represent whether the user has that capability.
		 * @param 	string[]  $caps     Required primitive capabilities for the requested capability.
		 * @param 	array     $args     Arguments that accompany the requested capability check.
		 * @param 	WP_User   $user     The user object.
		 */
		add_filter('user_has_cap', array($helper, 'restrictCapabilities'));

		/**
		 * Fires after WordPress has finished loading but before any headers are sent.
		 *
		 * Most of WP is loaded at this stage, and the user is authenticated. WP continues
		 * to load on the {@see 'init'} hook that follows (e.g. widgets), and many plugins instantiate
		 * themselves on it for all sorts of reasons (e.g. they need a user, a taxonomy, etc.).
		 *
		 * @since 1.5.0
		 */
		add_action('init', array($helper, 'preventEditReservationAccess'), 5);

		/**
		 * Fires before the controller of VikAppointments is dispatched.
		 * Useful to require libraries and to check user global permissions.
		 *
		 * @since 1.0
		 */
		add_action('vikappointments_before_dispatch', array($helper, 'listenTosFieldSavingTask'));
		add_action('vikappointments_before_dispatch', array($helper, 'displayBanners'));

		/**
		 * Fires after the controller of VikAppointments is dispatched.
		 * Useful to include web resources (CSS and JS).
		 * 
		 * If the controller terminates the process (exit or die),
		 * this hook won't be fired.
		 *
		 * @since 1.0
		 */
		add_action('vikappointments_after_dispatch', array($helper, 'includeLiteAssets'));

		/**
		 * Trigger event after completing the wizard setup.
		 * This is useful, in example, to rearrange the registered steps.
		 *
		 * @param 	boolean    $status  True on success, false otherwise.
		 * @param 	VAPWizard  $wizard  The wizard instance.
		 *
		 * @since 	1.2.3
		 */
		add_action('vikappointments_after_setup_wizard', array($helper, 'removeWizardSteps'), 15, 2);

		/**
		 * Fires before the controller displays the view.
		 *
		 * @param 	JView  $view  The view instance.
		 *
		 * @since 	1.0
		 */
		add_action('vikappointments_before_display_customf', array($helper, 'disableCustomFieldsGroupFilter'));

		/**
		 * Fires after the controller displays the view.
		 *
		 * @param 	JView  $view  The view instance.
		 *
		 * @since 	1.0
		 */
		add_action('vikappointments_after_display_customf', array($helper, 'displayTosFieldManagementForm'));

		/**
		 * Fires after the controller displays the view.
		 *
		 * @param 	JView  $view  The view instance.
		 *
		 * @since 	1.0
		 */
		add_action('vikappointments_after_display_managereservation', array($helper, 'adjustToolbarFromReservationManagement'));

		/**
		 * Fires after the controller displays the view.
		 *
		 * @param 	JView  $view  The view instance.
		 *
		 * @since 	1.0
		 */
		add_action('vikappointments_after_display_caldays', array($helper, 'disableEditFromOrderinfoModal'));
		add_action('vikappointments_after_display_calendar', array($helper, 'disableEditFromOrderinfoModal'));
		add_action('vikappointments_after_display_findreservation', array($helper, 'disableEditFromOrderinfoModal'));
		add_action('vikappointments_after_display_vikappointments', array($helper, 'disableEditFromOrderinfoDashboardModal'));

		/**
		 * Fires after the controller displays the view.
		 *
		 * @param 	JView  $view  The view instance.
		 *
		 * @since 	1.0
		 */
		add_action('vikappointments_after_display_editconfig', array($helper, 'removeMultilingualSettingFromConfiguration'));
		add_action('vikappointments_after_display_editconfig', array($helper, 'removeConversionsSettingFromConfiguration'));
		add_action('vikappointments_after_display_editconfig', array($helper, 'removeMailTextSettingFromConfiguration'));
		add_action('vikappointments_after_display_editconfig', array($helper, 'removeShopCartSettingsFromConfiguration'));
		add_action('vikappointments_after_display_editconfig', array($helper, 'removeShopWaitingListTabFromConfiguration'));
		add_action('vikappointments_after_display_editconfig', array($helper, 'removeShopRecurrenceTabFromConfiguration'));
		add_action('vikappointments_after_display_editconfig', array($helper, 'removeShopReviewsTabFromConfiguration'));
		add_action('vikappointments_after_display_editconfig', array($helper, 'removeShopPackagesTabFromConfiguration'));
		add_action('vikappointments_after_display_editconfig', array($helper, 'removeShopSubscriptionsTabFromConfiguration'));
	}
}
