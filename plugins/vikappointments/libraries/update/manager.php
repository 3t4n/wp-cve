<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  update
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

JLoader::import('adapter.database.helper');
VikAppointmentsLoader::import('update.changelog');
VikAppointmentsLoader::import('update.license');

/**
 * Class used to handle the upgrade of the plugin.
 *
 * @since 1.0
 */
class VikAppointmentsUpdateManager
{
	/**
	 * Checks if the current version should be updated.
	 *
	 * @param 	string 	 $version 	The version to check.
	 *
	 * @return 	boolean  True if should be updated, otherwise false.
	 */
	public static function shouldUpdate($version)
	{
		if (is_null($version))
		{
			return false;
		}

		return version_compare($version, VIKAPPOINTMENTS_SOFTWARE_VERSION, '<');
	}

	/**
	 * Executes the SQL file for the installation of the plugin.
	 *
	 * @return 	void
	 *
	 * @uses 	execSqlFile()
	 * @uses 	installAcl()
	 * @uses 	installProSettings()
	 */
	public static function install()
	{
		self::execSqlFile(VIKAPPOINTMENTS_BASE . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'install.mysql.utf8.sql');
		
		$dbo = JFactory::getDbo();

		// extract current lang tag by splitting regional code and country code
		$tag = preg_split("/[_-]/", JFactory::getLanguage()->getTag());

		// create customf field constaining rule (phone number) and country code
		$field = new stdClass;
		$field->rule   = VAPCustomFields::PHONE_NUMBER;
		$field->choose = end($tag);	

		// update all custom fields with PHONE NUMBER rule
		$dbo->updateObject('#__vikappointments_custfields', $field, 'rule');

		$config = VAPFactory::getConfig();

		// create the configuration record with the email address of the current user
		$config->set('adminemail', JFactory::getUser()->email);

		// footer must be disabled by default
		$config->set('showfooter', false);

		// auto turn off settings not supported by LITE version
		$config->set('enablecart', 0);
		$config->set('shoplink', '');
		$config->set('enablerecur', 0);
		$config->set('enablereviews', 0);
		$config->set('enablewaitlist', 0);
		$config->set('enablepackages', 0);
		$config->set('conversion_track', 0);
		$config->set('multilanguage', 0);

		// generate random key for cron and calendar sync
		$synckey = VikAppointments::generateSerialCode(12);

		$config->set('synckey', $synckey);
		$config->set('cron_secure_key', $synckey);

		// get JUri object
		$uri = JUri::getInstance();
		// get host from URI
		$domain = $uri->toString(array('host'));
		// split third-level domains
		$domain = implode(' ', explode('.', $domain));
		// make the word uppercase
		$domain = ucwords($domain);
		// update agency name with domain
		$config->set('agencyname', $domain);

		// search for the default Privacy Policy page
		$post = get_page_by_path('privacy-policy');

		if ($post)
		{
			// get Privacy Policy URL
			$pp_link = get_permalink($post->ID);

			if ($pp_link)
			{
				// update GDPR link with existing Privacy Policy
				$config->set('policylink', $pp_link);
			}
		}

		// truncate the payment gateways table
		$dbo->setQuery("TRUNCATE TABLE `#__vikappointments_gpayments`");
		$dbo->execute();

		// install terms and conditions custom fields
		JModelVAP::getInstance('customf')->save([
			'name'     => __('I read and accept the terms and conditions', 'vikappointments'),
			'type'     => 'checkbox',
			'required' => 1,
			'poplink'  => $config->get('policylink'),
			'id'       => 0,
			'group'    => 0,
		]);

		self::installAcl();
		self::installProSettings();

		// write CSS custom file
		$path = JPath::clean(VAPBASE . '/assets/css/vap-custom.css');
		JFile::write($path, "/* put below your custom css code for VikAppointments */\n\n");

		// import folder helper
		JLoader::import('adapter.filesystem.folder');

		// create overrides folder
		JFolder::create(VAP_UPLOAD_DIR_PATH . DIRECTORY_SEPARATOR . 'overrides');

		// create languages folder
		JFolder::create(VAP_UPLOAD_DIR_PATH . DIRECTORY_SEPARATOR . 'languages');

		// create media folders
		JFolder::create(VAPMEDIA);
		JFolder::create(VAPMEDIA_SMALL);

		// create customers folders
		JFolder::create(VAPCUSTOMERS_UPLOADS);
		JFolder::create(VAPCUSTOMERS_AVATAR);
		JFolder::create(VAPCUSTOMERS_DOCUMENTS);

		// create invoice templates folders
		JFolder::create(VAPINVOICE);
		JFolder::create(VAPINVOICE . DIRECTORY_SEPARATOR . 'employees');
		JFolder::create(VAPINVOICE . DIRECTORY_SEPARATOR . 'packages');

		// create mail folders
		JFolder::create(VAP_UPLOAD_DIR_PATH . DIRECTORY_SEPARATOR . 'mail' . DIRECTORY_SEPARATOR . 'tmpl');
		JFolder::create(VAP_UPLOAD_DIR_PATH . DIRECTORY_SEPARATOR . 'mail' . DIRECTORY_SEPARATOR . 'attachments');

		// create CSS folders
		JFolder::create(VAP_UPLOAD_DIR_PATH . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'customizer');
		JFolder::create(VAP_UPLOAD_DIR_PATH . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'themes');

		// create extendable classes folders
		JFolder::create(VAP_UPLOAD_DIR_PATH . DIRECTORY_SEPARATOR . 'cronjobs');
		JFolder::create(VAP_UPLOAD_DIR_PATH . DIRECTORY_SEPARATOR . 'smsapi');
		JFolder::create(VAP_UPLOAD_DIR_PATH . DIRECTORY_SEPARATOR . 'export');
	}

	/**
	 * Executes the SQL file for the uninstallation of the plugin.
	 *
	 * @param 	boolean  $drop 	True to drop the tables of VikAppointments from the database.
	 *
	 * @return 	void
	 *
	 * @uses 	execSqlFile()
	 * @uses 	uninstallAcl()
	 * @uses 	uninstallProSettings()
	 */
	public static function uninstall($drop = true)
	{
		if ($drop)
		{
			self::execSqlFile(VIKAPPOINTMENTS_BASE . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'uninstall.mysql.utf8.sql');
		}
		
		self::uninstallAcl();
		self::uninstallProSettings();

		// clear cached documentation
		VikAppointmentsScreen::clearCache();

		// import folder helper
		JLoader::import('adapter.filesystem.folder');

		// delete "vikappointments" folder in uploads dir
		// and all its children recursively
		JFolder::delete(VAP_UPLOAD_DIR_PATH);
	}

	/**
	 * Launches the process to finalise the update.
	 *
	 * @param 	string 	$version 	The current version.
	 *
	 * @uses 	getFixer()
	 * @uses 	installSql()
	 * @uses 	installAcl()
	 */
	public static function update($version)
	{
		$fixer = self::getFixer($version);

		// trigger before installation routine

		$res = $fixer->beforeInstallation();

		if ($res === false)
		{
			return false;
		}

		// install SQL statements

		$res = self::installSql($version);

		if ($res === false)
		{
			return false;
		}

		// install ACL

		$res = self::installAcl();

		if ($res === false)
		{
			return false;
		}

		// restore backed up files

		try
		{
			self::restoreBackup();
		}
		catch (Exception $e)
		{
			// raise error instead of aborting the update process
			JFactory::getApplication()->enqueueMessage("Impossible to restore backup.\n" . $e->getMessage(), 'error');
		}

		// trigger after installation routine

		$res = $fixer->afterInstallation();

		return ($res === false ? false : true);
	}

	/**
	 * Backups the specified source within the given destination.
	 *
	 * @param 	string 	 $src 	The file/folder to backup.
	 * 						  	In case of folder, only the first-level files
	 * 				 		  	will be moved within the destination path.
	 * @param 	string 	 $dest 	The destination folder.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 *
	 * @throws 	RuntimeException
	 */
	public static function doBackup($src, $dest)
	{
		// import folder helper
		JLoader::import('adapter.filesystem.folder');

		// clean paths
		$src  = JPath::clean($src);
		$dest = JPath::clean($dest);

		// make sure the destination folder exists
		if (!JFolder::exists($dest))
		{
			// throws exception in case of debug enabled
			if (VIKAPPOINTMENTS_DEBUG)
			{
				throw new RuntimeException(sprintf('Destination folder [%s] not found.', $dest), 404);
			}

			// missing destination
			return false;
		}

		// check if the source is a single file
		if (is_file($src))
		{
			$files = (array) $src;
		}
		// otherwise check if the source is a folder
		else if (JFolder::exists($src))
		{
			// folder path, filter ('.' means all), no recursive, return full path, exclude elements
			$files = JFolder::files($src, '.', false, true, array('.svn', 'CVS', '.DS_Store', '__MACOSX', 'index.html'));
		}
		else
		{
			// throws exception in case of debug enabled
			if (VIKAPPOINTMENTS_DEBUG)
			{
				throw new RuntimeException(sprintf('Invalid source path [%s].', $src), 400);
			}

			// nothing to backup
			return false;
		}

		// make sure we don't have an empty array
		if (!count($files))
		{
			// nothing to backup
			return false;
		}

		/**
		 * Define an array of files to ignore.
		 * 
		 * @since 1.2.1
		 */
		static $skip = [];

		if (!$skip)
		{
			// we need to exclude all the core e-mail templates to prevent the
			// system from restoring outdated code
			$skip[] = JPath::clean(VAP_UPLOAD_DIR_PATH . '/mail/tmpl/admin_email_tmpl.php');
			$skip[] = JPath::clean(VAP_UPLOAD_DIR_PATH . '/mail/tmpl/cancellation_email_tmpl.php');
			$skip[] = JPath::clean(VAP_UPLOAD_DIR_PATH . '/mail/tmpl/customer_email_tmpl.php');
			$skip[] = JPath::clean(VAP_UPLOAD_DIR_PATH . '/mail/tmpl/employee_email_tmpl.php');
			$skip[] = JPath::clean(VAP_UPLOAD_DIR_PATH . '/mail/tmpl/packages_email_tmpl.php');
			$skip[] = JPath::clean(VAP_UPLOAD_DIR_PATH . '/mail/tmpl/waitlist_email_tmpl.php');

			// we need to exclude all themes to always force new changes
			$skip[] = JPath::clean(VAP_UPLOAD_DIR_PATH . '/css/themes/dark.css');
			$skip[] = JPath::clean(VAP_UPLOAD_DIR_PATH . '/css/themes/light.css');
		}

		$res = true;

		foreach ($files as $file)
		{
			/**
			 * In case the file is contained within the skip list,
			 * do not restore the backup.
			 * 
			 * @since 1.2.1
			 */
			if (in_array($file, $skip))
			{
				continue;
			}

			// create full destination file
			$fileDest = rtrim($dest, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . basename($file);

			// proceed only in case the destination file doesn't exist yet
			// or the 2 files have a different bytes size
			if (!is_file($fileDest) || filesize($file) != filesize($fileDest))
			{
				// copy file
				if (!JFile::copy($file, $fileDest))
				{
					// throws exception in case of debug enabled
					if (VIKAPPOINTMENTS_DEBUG)
					{
						throw new RuntimeException(sprintf('Impossible to copy [%s] onto [%s].', $file, $fileDest), 500);
					}

					$res = false;
				}
			}
		}

		return $res;
	}

	/**
	 * Restores all the files that have been backed-up using doBackup() method.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 *
	 * @throws 	RuntimeException
	 */
	public static function restoreBackup()
	{
		$lookup = array(
			// custom CSS
			array(
				// target to restore
				VAP_UPLOAD_DIR_PATH . '/css/vap-custom.css',
				// destination folder
				VAPBASE . '/assets/css',
			),
			// customizer CSS
			array(
				// target to restore
				VAP_UPLOAD_DIR_PATH . '/css/customizer',
				// destination folder
				VAPBASE . '/assets/css/customizer',
			),
			// theme CSS
			array(
				// target to restore
				VAP_UPLOAD_DIR_PATH . '/css/themes',
				// destination folder
				VAPBASE . '/assets/css/themes',
			),
			// mail attachments
			array(
				// target to restore
				VAP_UPLOAD_DIR_PATH . '/mail/attachments',
				// destination folder
				VAPBASE . '/helpers/mail_attach',
			),
			// mail templates
			array(
				// target to restore
				VAP_UPLOAD_DIR_PATH . '/mail/tmpl',
				// destination folder
				VAPBASE . '/helpers/mail_tmpls',	
			),
			// languages
			array(
				// target to restore
				VAP_UPLOAD_DIR_PATH . '/languages',
				// destination folder
				VIKAPPOINTMENTS_BASE . '/languages',
			),
		);

		/**
		 * Removed back-up of the following files:
		 * - SMS API (@see JSmsDispatcher)
		 * - Cron Jobs (@see VAPCronDispatcher::addIncludePath())
		 * - Export drivers (@see VAPOrderExportFactory)
		 * 
		 * @since 1.2.1
		 */

		$res    = true;
		$errors = array();

		// iterate list
		foreach ($lookup as $chunk)
		{
			list($src, $dest) = $chunk;

			try
			{
				// do backup by using reversed arguments
				$res = static::doBackup($src, $dest) && $res;
			}
			catch (Exception $e)
			{
				$res = false;

				// catch any raised exceptions
				$errors[] = $e->getMessage();
			}
		}

		// re-throw exception in case of debug enabled
		if ($errors && VIKAPPOINTMENTS_DEBUG)
		{
			throw new RuntimeException(implode("\n", $errors), 500);
		}

		return $res;
	}

	/**
	 * Get the script class to run the installation methods.
	 *
	 * @param 	string 	$version 	The current version.
	 *
	 * @return 	VikAppointmentsUpdateFixer
	 */
	protected static function getFixer($version)
	{
		VikAppointmentsLoader::import('update.fixer');
	
		return new VikAppointmentsUpdateFixer($version);
	}

	/**
	 * Provides the installation of the ACL routines.
	 *
	 * @return 	boolean  True on success, otherwise false.	
	 */
	protected static function installAcl()
	{
		JLoader::import('adapter.acl.access');
		$actions = JAccess::getActions('vikappointments');

		// No actions found!
		// Probably, the main folder is not called "vikappointments".
		if (!$actions)
		{
			return false;
		}

		$roles = array(
			get_role('administrator'),
		);

		foreach ($roles as $role)
		{
			if ($role)
			{
				foreach ($actions as $action)
				{
					$cap = JAccess::adjustCapability($action->name, 'com_vikappointments');
					$role->add_cap($cap, true);
				}
			}
		}

		return true;
	}

	/**
	 * Sets up the options for using the Pro version.
	 *
	 * @return 	void
	 */
	protected static function installProSettings()
	{
		VikAppointmentsChangelog::install();
		VikAppointmentsLicense::install();
	}

	/**
	 * Sets up the options for using the Pro version.
	 *
	 * @return 	void
	 */
	protected static function uninstallProSettings()
	{
		VikAppointmentsChangelog::uninstall();
		VikAppointmentsLicense::uninstall();
	}

	/**
	 * Provides the uninstallation of the ACL routines.
	 *
	 * @return 	boolean  True on success, otherwise false.	
	 */
	protected static function uninstallAcl()
	{
		JLoader::import('adapter.acl.access');
		$actions = JAccess::getActions('vikappointments');

		// No actions found!
		// Probably, something went wrong while installing the plugin.
		if (!$actions)
		{
			return false;
		}

		$roles = array(
			get_role('administrator'),
		);

		foreach ($roles as $role)
		{
			if ($role)
			{
				foreach ($actions as $action)
				{
					$cap = JAccess::adjustCapability($action->name, 'com_vikappointments');
					$role->remove_cap($cap);
				}
			}
		}

		return true;
	}

	/**
	 * Run all the proper SQL files.
	 *
	 * @param 	string 	 $version 	The current version.
	 *
	 * @return 	boolean  True on success, otherwise false.
	 *
	 * @uses 	execSqlFile()
	 */
	protected static function installSql($version)
	{
		$dbo = JFactory::getDbo();

		$ok = true;

		$sql_base = VIKAPPOINTMENTS_BASE . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'update' . DIRECTORY_SEPARATOR . 'mysql' . DIRECTORY_SEPARATOR;

		try
		{
			foreach (glob($sql_base . '*.sql') as $file)
			{
				$name  = basename($file);
				$sql_v = substr($name, 0, strrpos($name, '.'));

				if (version_compare($sql_v, $version, '>'))
				{
					// in case the SQL version is newer, execute the queries listed in the file
					self::execSqlFile($file, $dbo);
				}
			}
		}
		catch (Exception $e)
		{
			$ok = false;
		}

		return $ok;
	}

	/**
	 * Executes all the queries contained in the specified file.
	 *
	 * @param 	string 		$file 	The SQL file to launch.
	 * @param 	JDatabase 	$dbo 	The database driver handler.
	 *
	 * @return 	void
	 */
	protected static function execSqlFile($file, $dbo = null)
	{
		if (!is_file($file))
		{
			return;
		}

		if ($dbo === null)
		{
			$dbo = JFactory::getDbo();
		}

		$handle = fopen($file, 'r');

		$bytes = '';
		while (!feof($handle))
		{
			$bytes .= fread($handle, 8192);
		}

		fclose($handle);

		foreach (JDatabaseHelper::splitSql($bytes) as $q)
		{
			$dbo->setQuery($q);
			$dbo->execute();
		}
	}
}
