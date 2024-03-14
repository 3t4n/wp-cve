<?php

	if( !is_admin() )
		wp_die(__('Access denied!', $this->textdomain));
	
	$help_screen = WP_Screen::get($this->option_page);

	$help_screen->add_help_tab(
		array(
			'title'    => 	__('Overview', $this->textdomain),
			'id'       => 	'overview_tab',
			'content'  => 	'<p>' . __('This page allows you to set all of your options for CYAN Backup.', $this->textdomain) . '</p>' .
							'<p>' . __('There are six overall categories of options to set, you can find details on each by selecting the related tab to the left.', $this->textdomain) . '</p>' .
							'<p>' . __('CYAN Backup is a low level tool for WordPress and should be configured with care.  Where ever possible, incorrect configurations are detected and a warning or error message will be displayed.  However not all can be detected and you should be aware of the impact of your configuration on your site.', $this->textdomain) . '</p>'
			,
			'callback' => 	false
		)
	);
	
	$help_screen->add_help_tab(
		array(
			'title'    => 	__('General', $this->textdomain),
			'id'       => 	'dir_tab',
			'content'  => 	'<p>' . __('<b>Force SSL</b>: If your site uses SSL for the admin interface but the WordPress/Site URL points to the non-encrypted front end, select this option to ensure the backup will use the SSL connection.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Artificial Delay</b>: Archiving the files is a processor intensive task, some hosting providers will terminate the backup process if it looks like it has hung.  This option will introduce a .25 second delay every 10 seconds to avoid this problem.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Low I/O Mode</b>: Archiving the files is disk intensive task, some hosting providers will terminate the backup process if it accesses the disk too much.  This option will introduce a 1 second delay every 1 seconds to avoid this problem.  This option is effectively an extreme version of the artificial delay and as such with automatically enable the artificial delay option as well.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Archive Method</b>: There are several options available on what format your archive file will be stored in.  By default "zip (ZipArchive)" will be used as it is the fastest option, however some hosts do not support it or have buggy implementations so other options are available.  The second best option is "zip (PHP-Archive)".</p><p>NOTE: Changing this option after you have executed backups will not re-archive existing backups and they will not be visible from the user interface, however they will still exist on your system and simply changing this option back to the previous setting will make them visible again.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Split DB Backup File</b>: This will split the DB backup file in to multpile files, one per table.  This may be useful if your database is large and when you restore it your hosting provider to terminate the script due to size/time.  With this setting the artificial delay will be added between zipping each db file.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Disable DB Backup</b>: This will disable the backup of the database.  Only disable this if you backup your database through another utility.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Archive Prefix</b>: By default your WordPress installation directory name is used to prefix the backup files, this option allows you to override the default with a more informative name of your choosing.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Archive Path</b>: This is where you wish to store the completed backups.  This will also be used as the temporary location of working files for CYAN Backup.  This directory should not be accessible to users as your SQL table exports will be here, along with your WordPress configuration files.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Create .htaccess/WebConfig File</b>: If you must have your archive path in a web accessible location (for example, perhaps your hosting provider only allows for subdirectories inside your web root), you should make sure your web server configuration blocks access to all files in the archive directory.  These buttons will create .htaccess/Web.Config files that will do this if they do not already exist.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Exclude Directories</b>: If you wish to exclude certain directories from the backup you may enter them here.  Several buttons are provided to add commonly selected directories to the list.  Note if your archive directory is in the WordPress directory tree it will automatically be added to the exclusion list when you save the settings.', $this->textdomain) . '</p>'
			,
			'callback' => 	false
		)
	);

	$help_screen->add_help_tab(
		array(
			'title'    => 	__('Archive Methods', $this->textdomain),
			'id'       => 	'archive_methods_tab',
			'content'  => 	'<p>' . __('<b>tar (PHP-Archive)</b>: This option generates a unix TAR file without compression and should only be used as a last resort.  It is the fastest and least resource intensive option.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>tar.bz2 (PHP-Archive)</b>: This option generates a bzip2 compressed tar file with "tar.bz2" as the file extension.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>tar.gz (PHP-Archive)</b>: This option generates a gzip compresed file with "tar.gz" as the file extension .', $this->textdomain) . '</p>' .
							'<p>' . __('<b>tbz (PHP-Archive)</b>: This option generates a bzip2 compressed tar file with "tbz" as the file extension.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>tgz (PHP-Archive)</b>: This option generates a gzip compressed tar file with "tgz" as the file extension.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>zip (PHP-Archive)</b>: This option generates a zip file using the PHP-Archive library.  If you have enabled the "Artificial Delay" option and are still finding your backups do not complete, try this option as it handles the delay better than ZipArchive.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>zip (PclZip)</b>: This option generates a zip file, however it is very slow and should not be used except as a last resort.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>zip (ZipArchive)</b>: This is the default option and generates a zip file.  It is the fasted option however it can generate high CPU utilization which may cause issues on some hosting providers.', $this->textdomain) . '</p>'
			,
			'callback' => 	false
		)
	);

	$help_screen->add_help_tab(
		array(
			'title'    => 	__('Logging', $this->textdomain),
			'id'       => 	'log_tab',
			'content'  => 	'<p>' . __('<b>E-Mail the log file</b>: If this option is enabled the log file will be e-mailed after a backup has been completed.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Send to Addresses</b>: This is a comma separated list of e-mail addresses to send the log to.  If this option is left blank, the site administrators e-mail address will be used.', $this->textdomain) . '</p>'
			,
			'callback' => 	false
		)
	);

	$help_screen->add_help_tab(
		array(
			'title'    => 	__('Schedules', $this->textdomain),
			'id'       => 	'schedule_tab',
			'content'  => 	'<p>' . sprintf(__('<b>Current Server Time</b>: This displays the server time when you loaded this page, it is here for reference only.  If this does not display the time you expect your %stimezone setting%s may be incorrect.', $this->textdomain), '<a href="' . admin_url('options-general.php') . '">','</a>') . '</p>' .
							'<p>' . __('<b>Next backup scheduled for</b>: This displays the next scheduled backup in WP Cron, it is here for reference only.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Enable</b>: This enables/disables the scheduler.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Type</b>: This selects the schedule type, options are Once, Hourly, Daily, Weekly and Monthly.  Note that selecting different schedule types will change the options presented in the schedule field.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Schedule - Once</b>: You may select a day of the week OR a day of the month to run the backup on.  You may also select a time.  If both the day of the week and day of the month values have been selected, the day of the month will take precedence.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Schedule - Hourly</b>: You may run an hourly backup on a recurring interval, for example select to run every 6 hours would create a backup file 4 times a day.  You may also select at what time past the hour you wish to run the backup.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Schedule - Daily</b>: You may run a daily backup on a recurring number of days at a specific time.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Schedule - Weekly</b>: Weekly schedules can have the recurring time as well as the day of the week set.  For example you could select every two weeks on Monday at 11:15pm.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Schedule - Monthly</b>: Monthly schedules can have the recurrence as well as the day of the month set with a time.', $this->textdomain) . '</p>'
			,
			'callback' => 	false
		)
	);

		$help_screen->add_help_tab(
		array(
			'title'    => 	__('Storage Maintenance', $this->textdomain),
			'id'       => 	'storage_tab',
			'content'  => 	'<p>' . __('<b>Enable backup pruning</b>: Backup pruning will automatically delete older backup files after a new backup has completed.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Number of backups to keep</b>: This is the number of backups to keep based upon the date and time of the backup files.  If this is set to 0, all backups will be retained.  You should not set this value too low or you may lose data if you need to recover an older version of your site.', $this->textdomain) . '</p>'
			,
			'callback' => 	false
		)
	);

	$help_screen->add_help_tab(
		array(
			'title'    => 	__('Remote Storage', $this->textdomain),
			'id'       => 	'remote_tab',
			'content'  => 	'<p>' . __('<b>Enable remote storage</b>: This will enable the remote storage of your backup files.  You should ALWAYS keep copies of your backup files on a different host than your main website as if your site is compromised or has a major hardware failure you may not be able to access your files on the primary host.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Protocol</b>: Select the transfer protocol to use.  See Protocol Types to the left with details.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Username</b>: The username to login to the remote server with.  Ideally this user will only be able to write files to the remote location, not read.  This will ensure that even if your site is compromised, your remote storage cannot be used to as a distribution point for hackers.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Password</b>: The password to login to the remote server with.  The password is encrypted before being stored in the database, however if someone gets both database and file access to your server it could be recovered.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Remote path</b>: This is the remote path to use to store the backup.', $this->textdomain) . __( "You many use the follow place holders: %m = month (01-12), %d = day (01-31), %Y = year (XXXX), %M = month (Jan...Dec), %F = month (January...December)" ) . '.  ' . __('This path will be created if the protocol supports it during the transfer of the file.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Include log file</b>: By default, only the archive log is sent to the remote server, selecting this option will also send the log file.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Delete local copy during scheduled backup</b>: Once the transfer is successful after a scheduled backup, this option will automatically delete the local copy of the backup and log file.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>Delete local copy during manual backup</b>: Once the transfer is successful after a manual backup, this option will automatically delete the local copy of the backup and log file.', $this->textdomain) . '</p>'
			,
			'callback' => 	false
		)
	);

	$help_screen->add_help_tab(
		array(
			'title'    => 	__('Protocol Types', $this->textdomain),
			'id'       => 	'protocol_tab',
			'content'  => 	'<p>' . __('There are multiple protocol providers available for remote storage, <b>but only those that your system supports will be available</b> in the protocol drop down.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>FTP Wrappers</b>: FTP IS INSECURE.  DO NOT USE THIS ON PRODUCTION SYSTEMS.  FTP is included here only for testing purposes.  FTP connections will only be allowed to remote systems on your local subnet.  FTP Wrappers uses the built in wrappers code to transfer the backups.  If your hosting provider has disabled wrappers this will not work and you should use FTP Library instead.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>FTP Library</b>: FTP IS INSECURE.  DO NOT USE THIS ON PRODUCTION SYSTEMS.  FTP is included here only for testing purposes.  FTP connections will only be allowed to remote systems on your local subnet.  FTP Library uses the PHP Library to transfer the backups.  If your installation of PHP does not have the the FTP library installed you should use FTP Wrappers instead.', $this->textdomain) . '</p>' .
							'<p>' . __('<b>FTPS Wrappers</b>: FTPS Wrappers uses the built in wrappers code to transfer the backups.  FTPS is FTP over SSL/TLS.  If your hosting provider has disabled wrappers this will not work and you should use FTPS Library instead. <b>[FTPS requires the PHP FTPS Library be installed to function]</b>', $this->textdomain) . '</p>' .
							'<p>' . __('<b>FTPS Library</b>: FTPS Library uses the PHP Library to transfer the backups.  FTPS is FTP over SSL/TLS.  If your installation of PHP does not have the the FTPS library installed you should use SFTP Wrappers instead. <b>[FTPS requires the PHP FTPS Library be installed to function]</b>', $this->textdomain) . '</p>' .
							'<p>' . __('<b>SFTP/SCP Wrappers</b>: SFTP/SCP Wrappers uses the built in wrappers code to transfer the backups.  SFTP/SCP is Secure File Transfer over SSH.  If your hosting provider has disabled wrappers this will not work and you should use SFTP/SCP Library instead. <b>[FTPS requires the PHP SSH2 Library be installed to function]</b>', $this->textdomain) . '</p>' .
							'<p>' . __('<b>SFTP/SCP Library</b>: SFTP/SCP Library uses the PHP Library to transfer the backups.  SFTP/SCP is Secure File Transfer over SSH.  If your installation of PHP does not have the the SSH2 library installed you should use SFTP/SCP Wrappers instead. <b>[FTPS requires the PHP SSH2 Library be installed to function]</b>', $this->textdomain) . '</p>' .
							'<p>' . __('<b>SFTP/SCP phpseclib</b>: SFTP/SCP phpseclib uses the <a href="http://phpseclib.sourceforge.net/" target=_blank>phpseclib</a> to transfer the backups.  SFTP/SCP is Secure File Transfer over SSH.  phpseclib is a pure PHP implementation of SFTP/SCP and requires no additional libraries to work, however for performance you should have mcrypt or gmp or bcmath installed.', $this->textdomain) . '</p>'
			,
			'callback' => 	false
		)
	);

	
	$help_screen->add_help_tab(
		array(
			'title'    => 	__('Clear Active Backup', $this->textdomain),
			'id'       => 	'active_tab',
			'content'  => 	'<p>' . __("<b>Clear active backup status</b>: Only check this if a backup has hung and you can no longer execute backups.  CYAN Backup uses a status file to tell if a backup is running or not, if this file hasn't been deleted after a backup is complete you won't be able to run another backup for 10 minutes.  If you wish to force the deletion of the file check this option and save the settings.  This will force the deletion of the file.", $this->textdomain) . '</p>' .
							'<p>' . __('<b>Delete temporary files and directories</b>: If a backup has failed it will sometimes leave temporary files and subdirectories behind in the archive directory, this option will delete any file in the archive path that starts with your site directory name but is not a archive/log file.', $this->textdomain) . '</p>'
			,
			'callback' => 	false
		)
	);

?>