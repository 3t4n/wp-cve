<?php

	if( !is_admin() )
		wp_die(__('Access denied!', $this->textdomain));

	$this->verify_status_file();

	include_once('class-cyan-utilities.php');

	$CYANUtil = new CYAN_Utilities;

	$nonce_field = 'option_update';

	$option          = (array)get_option($this->option_name);
	$archive_path    = $this->get_archive_path($option);
	$archive_prefix  = $this->get_archive_prefix($option);
	$excluded_dir    = $this->get_excluded_dir($option, array());
	$content_dir     = $this->chg_directory_separator(WP_CONTENT_DIR . "/", FALSE);
	$abspath  	     = $this->chg_directory_separator(ABSPATH, FALSE);
	$admin_dir       = $this->chg_directory_separator($abspath . 'wp-admin/', FALSE);
	$archive_methods = $this->get_archive_methods();

	// Create the .htaccess or WebConfig files
	if (isset($_POST['CreateWebConfig']) || isset($_POST['Createhtaccess'])) {
		if ( $this->wp_version_check('2.5') && function_exists('check_admin_referer') )
			check_admin_referer($nonce_field, self::NONCE_NAME);

		if( isset($_POST['CreateWebConfig']) )
			{
			if( $abspath == $archive_path || $admin_dir == $archive_path) {
				$CYANUtil->record_notes( "<strong>". __('Archive path set to WordPress root or admin folder, Web.Config not written!', $this->textdomain)."</strong>", 2);
			} else {
				$access_filename = $archive_path . 'Web.config';

				if( !file_exists( $access_filename ) )
					{
					$access_file = fopen( $access_filename, 'w' );

					fwrite( $access_file, '<?xml version="1.0" encoding="utf-8" ?>' . "\n");
					fwrite( $access_file, '<configuration>' . "\n");
					fwrite( $access_file, '	<system.webServer>' . "\n");
					fwrite( $access_file, '		<security>' . "\n");
					fwrite( $access_file, '			<authorization>' . "\n");
					fwrite( $access_file, '				<remove users="*" roles="" verbs="" />' . "\n");
					fwrite( $access_file, '				<add accessType="Allow" roles="Administrators" />' . "\n");
					fwrite( $access_file, '			</authorization>' . "\n");
					fwrite( $access_file, '		</security>' . "\n");
					fwrite( $access_file, '	</system.webServer>' . "\n");
					fwrite( $access_file, '</configuration>' . "\n");

					fclose( $access_file );

					$CYANUtil->record_notes( __('Web.Config written!', $this->textdomain), 0);
					}
				else
					{
					$CYANUtil->record_notes( __('Web.Config already exists, please edit it manually!', $this->textdomain), 1);
					}
				}
			}

		if( isset($_POST['Createhtaccess']) )
			{
			if( $abspath == $archive_path || $admin_dir == $archive_path ) {
				$CYANUtil->record_notes( __('Archive path set to WordPress root or admin folder, .htaccess not written!', $this->textdomain), 2);
			} else {
				$access_filename = $archive_path . '.htaccess';

				if( !file_exists( $access_filename ) )
					{
					$access_file = fopen( $access_filename, 'w' );

					fwrite( $access_file, '<FilesMatch ".*">' . "\n" );
					fwrite( $access_file, '  Order Allow,Deny' . "\n" );
					fwrite( $access_file, '  Deny from all' . "\n" );
					fwrite( $access_file, '</FilesMatch>' . "\n" );

					fclose( $access_file );

					$CYANUtil->record_notes( __('.htaccess written!', $this->textdomain), 0);
					}
				else
					{
					$CYANUtil->record_notes( __('.htaccess already exists, please edit it manually!', $this->textdomain), 1);
					}
				}
			}
	}

	// option update
	if (isset($_POST['options_update'])) {
		if( $this->wp_version_check('2.5') && function_exists('check_admin_referer') )
			check_admin_referer($nonce_field, self::NONCE_NAME);

		if( isset( $_POST['clear_backup'] ) ) {
			@unlink( $archive_path . 'backup.active' );
			@unlink( $archive_path . 'status.log' );
			$CYANUtil->record_notes( __('Active backup state cleared!', $this->textdomain), 0);
		}

		if( isset( $_POST['clear_files'] ) ) {
			$archive_pre = basename(ABSPATH);
			$current_files = scandir($archive_path);

			foreach( $current_files as $this_file ) {
				$this_file_ext = strtolower(substr( $this_file, -4 ));
				if( substr( $this_file, 0, strlen( $archive_pre ) ) == $archive_pre && $this_file_ext != ".zip" && $this_file_ext != ".log" ) {
					$CYANUtil->recursive_remove( $archive_path . $this_file );
					$CYANUtil->record_notes( sprintf( __('%s deleted from the archive directory.', $this->textdomain), $this_file ), 0);
				}
			}
		}

		if( isset( $_POST['forcessl'] ) ) {
			$options['forcessl'] = $_POST['forcessl'];
		}

		if( isset( $_POST['archive_method'] ) ) {
			if( array_key_exists( $_POST['archive_method'], $archive_methods ) ) {
				$options['archive_method'] = $_POST['archive_method'];
			}
		}

		if( isset( $_POST['disabledbbackup'] ) ) {
			$options['disabledbbackup'] = $_POST['disabledbbackup'];
		}

		if( isset( $_POST['splitdbbackup'] ) ) {
			$options['splitdbbackup'] = $_POST['splitdbbackup'];
		}

		if( isset( $_POST['artificialdelay'] ) ) {
			$options['artificialdelay'] = $_POST['artificialdelay'];
		}

		if( isset( $_POST['lowiomode'] ) ) {
			$options['lowiomode'] = $_POST['lowiomode'];
			$options['artificialdelay'] = $_POST['lowiomode'];
		}

		$postdata = $this->get_real_post_data();

		if ( isset($postdata['archive_path']) ) {
			$dir = trim($postdata['archive_path']);

			if ( ($realpath = realpath($dir)) !== FALSE) {
				$realpath = $this->chg_directory_separator($realpath, FALSE);
				if ( is_dir($realpath) )
					$realpath = $this->trailingslashit($realpath, FALSE);

				if( $realpath == $abspath || $realpath == $admin_dir ) {
					$CYANUtil->record_notes( __('Archive path set to WordPress root or admin folder, this is not a valid option!', $this->textdomain), 2);
				} else {
					$options['archive_path'] = $realpath;

					if( substr( $realpath, 0, strlen( $abspath) ) == $abspath ) {
						$test_name = $realpath . "test.zip";
						$test_text = "This is a test file\n";
						$test_file = fopen( $test_name, 'w' );

						if( $test_file ) {
							fwrite($test_file, $test_text);
							fclose($test_file);

							$test_url = $this->wp_site_url( substr( $realpath, strlen( $abspath ) ) . 'test.zip' );

							$test_read = @file_get_contents($test_url);

							@unlink( $test_name );

							if( $test_read == $test_text ) {
								$CYANUtil->record_notes( sprintf(__('Archive directory ("%s") is a subdirectory in the WordPress root and is accessible via the web, this is an insecure configuration!', $this->textdomain), $realpath), 1);
							}
						} else {
							$CYANUtil->record_notes( sprintf(__('Archive directory ("%s") is not writeable!', $this->textdomain), $realpath), 2);
						}
					}
				}
			} else {
				$CYANUtil->record_notes( sprintf(__('Archive directory ("%s") does not exist!', $this->textdomain), $realpath), 2);
			}
		}

		if ( isset($postdata['archive_prefix']) ) {
			$temp_prefix = str_replace(DIRECTORY_SEPARATOR, '-', untrailingslashit($postdata['archive_prefix']));

			if( $temp_prefix != '' ) {
				$backup_files = $this->get_backup_files();

				if (count($backup_files) > 0) {
					foreach ($backup_files as $backup_file) {
						$pinfo = pathinfo( $backup_file );

						$new_bname = $temp_prefix . substr( $pinfo['filename'], strlen( $archive_prefix ) );

						$new_fname = $pinfo['dirname'] . '/' . $new_bname . '.' . $pinfo['extension'];
						rename( $backup_file, $new_fname );

						$logname = $pinfo['dirname'] . '/' . $pinfo['filename'] . '.log';
						$new_lname = $pinfo['dirname'] . '/' . $new_bname . '.log';
						rename( $logname, $new_lname );
					}
				}

				$archive_prefix = $temp_prefix;
				$options['archive_prefix'] = $temp_prefix;
			}
		}

		if ( isset($postdata['excluded']) ) {
			$excluded = $excluded_dir = array();
			$check_archive_excluded = FALSE;
			$archive_path_found = FALSE;

			if( substr( $archive_path, 0, strlen( $abspath) ) == $abspath ) { $check_archive_excluded = TRUE; }

			foreach ( explode("\n", $postdata['excluded']) as $dir ) {
				$dir = trim($dir);
				if ( !empty($dir) ) {
					if ( ($realpath = realpath($dir)) !== FALSE) {
						$realpath = $this->chg_directory_separator($realpath, FALSE);
						$dir = str_replace($abspath, '', $realpath);
						if ( is_dir($realpath) )
							$dir = $this->trailingslashit($dir, FALSE);
						$excluded[] = $dir;
						$excluded_dir[] = str_replace($abspath, '', $dir);

						$realpath = $this->trailingslashit($realpath, FALSE);
						if( $check_archive_excluded && $realpath == $archive_path ) { $archive_path_found = TRUE; }
					} else {
						$CYANUtil->record_notes(sprintf(__('Excluded directory ("%s") is not found, removed from exclusions.', $this->textdomain), $dir), 1);
					}
				}
			}

			if( $check_archive_excluded == TRUE && $archive_path_found == FALSE ) {
				$archive_dir = str_replace($abspath, '', $archive_path);
				$excluded[] = $archive_dir;
				$excluded_dir[] = $archive_dir;

				$CYANUtil->record_notes( __('Archive path is in the WordPress directory tree but was not found in the exclusions, it has automatically been added.', $this->textdomain), 0);
			}

			$options['excluded'] = $excluded;
		}

		if( isset( $_POST['emaillog'] ) ) {
			$options['emaillog'] = $_POST['emaillog'];
		}

		if( isset( $_POST['sendto'] ) ) {
			$options['sendto'] = $_POST['sendto'];
		}

		if ( isset($_POST['schedule']) ) {
			if( is_array( $_POST['schedule'] ) ) {
				$options['schedule'] = $_POST['schedule'];
				$options['schedule']['tod'] = $options['schedule']['hours'] . ':' . $options['schedule']['minutes'] . $options['schedule']['ampm'];
			}
		}

		if ( isset($_POST['remote']) ) {
			if( is_array( $_POST['remote'] ) ) {
				$options['remote'] = $_POST['remote'];

				if( !function_exists( 'mcrypt_encrypt' ) ) {
					$CYANUtil->record_notes( __('mcrypt library is not installed so passwords cannot be encrypted before being stored in the database.  Your remote storage password will be stored in clear text!  Please install mcrypt and re-save your configuration.', $this->textdomain), 1);
				}

				// WordPress escapes form data no matter what the setting of magic quotes is in PHP (http://www.theblog.ca/wordpress-addslashes-magic-quotes).
				$options['remote']['password'] = stripslashes( $options['remote']['password'] );

				// Encrpyt the password for storage in the database.
				$options['remote']['password'] = $this->encrypt_password( $options['remote']['password'] );
			}
		}

		// Remove the backup schedule if we've change it recurrence.
		if( wp_next_scheduled('cyan_backup_hook') && ( $options['schedule']['type'] != $option['schedule']['type'] || $options['schedule']['interval'] != $option['schedule']['interval'] || $options['schedule']['tod'] != $option['schedule']['tod'] || $options['schedule']['dom'] != $option['schedule']['dom'] || $options['schedule']['dow'] != $option['schedule']['dow'] ) ) {

			wp_unschedule_event(wp_next_scheduled('cyan_backup_hook'), 'cyan_backup_hook');
		}

		// Add the backup schedule if it doesn't exist and is enabled.
		if( !wp_next_scheduled('cyan_backup_hook') && array_key_exists('enabled', $options['schedule'] ) && $options['schedule']['enabled'] ) {
			$next_backup_time = $this->calculate_initial_backup( $options['schedule'] );

			if( $next_backup_time > time() ) {
				wp_schedule_single_event($next_backup_time, 'cyan_backup_hook');
				$options['next_backup_time'] = $next_backup_time;
			} else {
				$CYANUtil->record_notes( __('Schedule not set, failed to determine the next scheduled time to backup!', $this->textdomain), 2);
			}
		}

		// Remove the backup schedule if it does exist and is disabled.
		if( wp_next_scheduled('cyan_backup_hook') && !$options['schedule']['enabled'] ) {

			wp_unschedule_event(wp_next_scheduled('cyan_backup_hook'), 'cyan_backup_hook');
		}

		if ( isset($_POST['prune']) ) {
			if( is_array( $_POST['prune'] ) ) {
				$options['prune'] = $_POST['prune'];
			}
		}
		update_option($this->option_name, $options);

		$option = $options;
		$archive_path = $this->get_archive_path($option);
		$excluded_dir = $this->get_excluded_dir($option, array());

		// Done!
		$CYANUtil->record_notes(__('Configuration saved!', $this->textdomain), 0);
	}

	// Decrypt the password for use on the form.
	$decrypted_pw = $this->decrypt_password( $option['remote']['password'] );
	$option['remote']['password'] = $decrypted_pw;

	$schedule_types = array( 'Once', 'Hourly', 'Daily', 'Weekly', 'Monthly' );

	if( self::DEBUG_MODE == TRUE ) {
		$schedule_types[] = 'debug';
	}

	$display_settings = array();
	$display_type_settings = array(
								'Once' => array(
									'schedule_debug' => 'display: none;',
									'schedule_once' => '',
									'schedule_before' => 'display: none;',
									'schedule_interval' => 'display: none;',
									'schedule_hours_label' => 'display: none;',
									'schedule_days' => 'display: none;',
									'schedule_weeks' => 'display: none;',
									'schedule_months' => 'display: none;',
									'schedule_on' => '',
									'schedule_dow' => '',
									'schedule_the' => '',
									'schedule_dom' => '',
									'schedule_at' => '',
									'schedule_hours' => '',
									'schedule_minutes' => '',
									'schedule_ampm' => ''
									),
								'Hourly' => array(
									'schedule_debug' => 'display: none;',
									'schedule_once' => 'display: none;',
									'schedule_before' => '',
									'schedule_interval' => '',
									'schedule_hours_label' => '',
									'schedule_days' => 'display: none;',
									'schedule_weeks' => 'display: none;',
									'schedule_months' => 'display: none;',
									'schedule_on' => 'display: none;',
									'schedule_dow' => 'display: none;',
									'schedule_the' => 'display: none;',
									'schedule_dom' => 'display: none;',
									'schedule_at' => '',
									'schedule_hours' => 'display: none;',
									'schedule_minutes' => '',
									'schedule_ampm' => 'display: none;'
									),
								'Daily' => array(
									'schedule_debug' => 'display: none;',
									'schedule_once' => 'display: none;',
									'schedule_before' => '',
									'schedule_interval' => '',
									'schedule_hours_label' => 'display: none;',
									'schedule_days' => '',
									'schedule_weeks' => 'display: none;',
									'schedule_months' => 'display: none;',
									'schedule_on' => 'display: none;',
									'schedule_dow' => 'display: none;',
									'schedule_the' => 'display: none;',
									'schedule_dom' => 'display: none;',
									'schedule_at' => '',
									'schedule_hours' => '',
									'schedule_minutes' => '',
									'schedule_ampm' => ''
									),
								'Weekly' => array(
									'schedule_debug' => 'display: none;',
									'schedule_once' => 'display: none;',
									'schedule_before' => '',
									'schedule_interval' => '',
									'schedule_hours_label' => 'display: none;',
									'schedule_days' => 'display: none;',
									'schedule_weeks' => '',
									'schedule_months' => 'display: none;',
									'schedule_on' => '',
									'schedule_dow' => '',
									'schedule_the' => 'display: none;',
									'schedule_dom' => 'display: none;',
									'schedule_at' => '',
									'schedule_hours' => '',
									'schedule_minutes' => '',
									'schedule_ampm' => ''
									),
								'Monthly' => array(
									'schedule_debug' => 'display: none;',
									'schedule_once' => 'display: none;',
									'schedule_before' => '',
									'schedule_interval' => '',
									'schedule_hours_label' => 'display: none;',
									'schedule_days' => 'display: none;',
									'schedule_weeks' => 'display: none;',
									'schedule_months' => '',
									'schedule_on' => '',
									'schedule_dow' => 'display: none;',
									'schedule_the' => '',
									'schedule_dom' => '',
									'schedule_at' => '',
									'schedule_hours' => '',
									'schedule_minutes' => '',
									'schedule_ampm' => ''
									)
								);

	if( self::DEBUG_MODE == TRUE ) {
		$display_type_settings['debug'] = array(
									'schedule_debug' => '',
									'schedule_once' => 'display: none;',
									'schedule_before' => 'display: none;',
									'schedule_interval' => 'display: none;',
									'schedule_hours_label' => 'display: none;',
									'schedule_days' => 'display: none;',
									'schedule_weeks' => 'display: none;',
									'schedule_months' => 'display: none;',
									'schedule_on' => 'display: none;',
									'schedule_dow' => 'display: none;',
									'schedule_the' => 'display: none;',
									'schedule_dom' => 'display: none;',
									'schedule_at' => 'display: none;',
									'schedule_hours' => 'display: none;',
									'schedule_minutes' => 'display: none;',
									'schedule_ampm' => 'display: none;'
									);
	}

	echo '<script type="text/javascript">//<![CDATA[' . "\n";

	echo 'function set_schedule_display() {' . "\n";
	echo '        var display_type_settings = new Array() ' . "\n\n";

	foreach( $display_type_settings as $key => $value ) {
		echo '        display_type_settings[\'' . $key . '\'] = new Array();' . "\n";
	}

	foreach( $display_type_settings as $key => $value ) {
		foreach( $value as $subkey => $subvalue ) {
			echo '        display_type_settings[\'' . $key . '\'][\'' . $subkey . '\'] = \'';
			if( $subvalue == "display: none;" ) { echo '0'; } else { echo '1'; }
			echo '\';' . "\n";
		}
	}

	echo "\n";

	echo '        var type = jQuery("#schedule_type").val();' . "\n";
	echo "\n";
	echo '        for( var i in display_type_settings[type] ) {' . "\n";
	echo '                if( display_type_settings[type][i] == 0 ) { jQuery("#" + i).css( "display", "none" ); } else { jQuery("#" + i).css( "display", "" ); }' . "\n";
	echo '        }' . "\n";
	echo '}' . "\n";
	echo "\n";

	echo 'jQuery(document).ready(function() {' . "\n";
	echo '        jQuery("#clear_files").change(function(){' . "\n";
	echo "\n";
	echo '                if(!this.checked)' . "\n";
	echo '                        return;' . "\n";
	echo "\n";
	echo "                var agree = confirm('" . __('Are you sure you want to delete all temporary files in the archive directory?', $this->textdomain) . "')\n";
	echo "\n";
	echo '                if(!agree)' . "\n";
	echo '                        jQuery("#clear_files").attr("checked", false);' . "\n";
	echo "\n";
	echo '        });' . "\n";
	echo "\n";
	echo '        jQuery("#clear_backup").change(function(){' . "\n";
	echo "\n";
	echo '                if(!this.checked)' . "\n";
	echo '                        return;' . "\n";
	echo "\n";
	echo "                var agree = confirm('" . __('Are you sure you want to clear the active state?', $this->textdomain) . "')\n";
	echo "\n";
	echo '                if(!agree)' . "\n";
	echo '                        jQuery("#clear_backup").attr("checked", false);' . "\n";
	echo "\n";
	echo '        });' . "\n";
	echo "\n";
	echo '        jQuery("#tabs").tabs();' . "\n";
	echo "\n";
	echo '});' . "\n";

	echo '//]]></script>' . "\n";

	// Find the next scheduled backup in WP Cron.
	$next_schedule = wp_next_scheduled('cyan_backup_hook');
	$current_time = time();

	// If the next scheduled backup is over an hour in the past, it's probably broken, let the user know.
	if( ( $next_schedule < $current_time - 3600 ) && is_array( $option['schedule'] ) && array_key_exists( 'enabled', $option['schedule'] ) && $option['schedule']['enabled'] ) {
		$CYANUtil->record_notes( __('The next scheduled backup job is in the past, WP Cron may be broken.  If it does not execute shortly, you may want to disable and then re-enable scheduled backup jobs to re-create the WP Cron entry.', $this->textdomain), 1 );
	}

	echo $CYANUtil->output_notes();
	$CYANUtil->clear_notes();
?>

<div class="wrap">

	<div id="icon-options-general" class="icon32"><br /></div>

	<h2><?php _e('CYAN Backup Options', $this->textdomain);?></h2>

	<form method="post" id="option_update" action="<?php echo $this->admin_action;?>-options">
	<div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active ui-state-focus"><a class="ui-tabs-anchor" href="#fragment-1"><span><?php _e('General', $this->textdomain);?></span></a></li>
			<li class="ui-state-default ui-corner-top"><a class="ui-tabs-anchor" href="#fragment-2"><span><?php _e('Logging', $this->textdomain);?></span></a></li>
			<li class="ui-state-default ui-corner-top"><a class="ui-tabs-anchor" href="#fragment-3"><span><?php _e('Schedules', $this->textdomain);?></span></a></li>
			<li class="ui-state-default ui-corner-top"><a class="ui-tabs-anchor" href="#fragment-4"><span><?php _e('Storage Maintenance', $this->textdomain);?></span></a></li>
			<li class="ui-state-default ui-corner-top"><a class="ui-tabs-anchor" href="#fragment-5"><span><?php _e('Remote Storage', $this->textdomain);?></span></a></li>
			<li class="ui-state-default ui-corner-top"><a class="ui-tabs-anchor" href="#fragment-6"><span><?php _e('Clear Active Backup', $this->textdomain);?></span></a></li>
		</ul>

		<div id="fragment-1">
<?php if ($this->wp_version_check('2.5') && function_exists('wp_nonce_field') )
		echo wp_nonce_field($nonce_field, self::NONCE_NAME, true, false);
?>
			<table class="optiontable form-table" style="margin-top:0;">
				<tbody>
					<tr>
						<th><?php _e('Force SSL', $this->textdomain);?></th>

						<td>
							<input type=checkbox id="forcessl" name="forcessl"<?php if( array_key_exists( 'forecssl', $option ) && $option['forcessl'] == 'on' ) { echo ' CHECKED'; }?>>
						</td>
					</tr>
					<tr>
						<th><?php _e('Artificial Delay', $this->textdomain);?></th>

						<td>
							<input type=checkbox id="artificialdelay" name="artificialdelay"<?php if( array_key_exists( 'artificialdelay', $option ) && $option['artificialdelay'] == 'on' ) { echo ' CHECKED'; }?>>
						</td>
					</tr>
					<tr>
						<th><?php _e('Low I/O Mode', $this->textdomain);?></th>

						<td>
							<input type=checkbox id="lowiomode" name="lowiomode"<?php if( array_key_exists( 'lowiomode', $option ) && $option['lowiomode'] == 'on' ) { echo ' CHECKED'; }?>>
						</td>
					</tr>
					<tr>
						<th><?php _e('Archive method', $this->textdomain);?></th>

						<td>
							<select id="archive_method" name="archive_method">
<?php
		if( !array_key_exists( 'archive_method', $option ) || !array_key_exists( $option['archive_method'], $archive_methods ) ) {
			if( array_key_exists( 'ZipArchive', $archive_methods ) ) {
				$option['archive_method'] = 'ZipArchive';
			} else {
				$option['archive_method'] = 'PclZip';
			}
		}

		foreach( $archive_methods as $key => $method ) {
			echo "\t\t\t\t\t\t<option value=\"" . $key . '"';

			if( $option['archive_method'] == $key ) { echo ' SELECTED'; }

			echo '>' . $method . '</option>' . "\r\n";
		}
?>
							</select>
						</td>
					</tr>
					<tr>
						<th><?php _e('Split DB backup file', $this->textdomain);?></th>

						<td>
							<input type=checkbox id="splitdbbackup" name="splitdbbackup"<?php if( array_key_exists( 'splitdbbackup', $option ) && $option['splitdbbackup'] == 'on' ) { echo ' CHECKED'; }?>>
						</td>
					</tr>
					<tr>
						<th><?php _e('Disable DB Backup', $this->textdomain);?></th>

						<td>
							<input type=checkbox id="disabledbbackup" name="disabledbbackup"<?php if( array_key_exists( 'disabledbbackup', $option ) && $option['disabledbbackup'] == 'on' ) { echo ' CHECKED'; }?>>
						</td>
					</tr>
					<tr>
						<th><?php _e('Archive prefix', $this->textdomain);?></th>

						<td>
							<input type="text" name="archive_prefix" id="archive_prefix" size="20" value="<?php echo htmlentities($archive_prefix);?>" /><br><br>
							<?php _e( 'Remember to include a separator at the end of the prefix (like a period or a dash) to make your filenames look nice!', $this->textdomain );?><br><br>
							<?php _e( 'Note: Existing backups will be renamed to the new prefix.', $this->textdomain );?>
						</td>
					</tr>
					<tr>
						<th><?php _e('Archive path', $this->textdomain);?></th>

						<td>
							<input type="text" name="archive_path" id="archive_path" size="100" value="<?php echo htmlentities($archive_path);?>" /><br><br>
<?php GLOBAL $is_iis; if( !$is_iis ) {?>
							<input class="button" id="Createhtaccess" name="Createhtaccess" type="submit" value="<?php _e('Create .htaccess File', $this->textdomain);?>">
<?php } else {?>
							<input class="button" id="CreateWebConfig" name="CreateWebConfig" type="submit" value="<?php _e('Create WebConfig File', $this->textdomain);?>">
<?php }?>
						</td>
					</tr>

					<tr>
						<th><?php _e('Excluded dir', $this->textdomain);?></th>

						<td><textarea name="excluded" id="excluded" rows="5" cols="100">
<?php
	foreach ($excluded_dir as $dir) {
		echo htmlentities($this->chg_directory_separator($abspath.$dir,FALSE)) . "\n";
	}
?></textarea><br><br>

							<input class="button" id="AddArchiveDir" name="AddArchiveDir" type="button" value="<?php _e('Add Archive Dir', $this->textdomain);?>" onClick="excluded.value = jQuery.trim( excluded.value ) + '\n<?php echo addslashes( $archive_path ); ?>';">&nbsp;
							<input class="button" id="AddWPContentDir" name="AddWPContentDir" type="button" value="<?php _e('Add WP-Content Dir', $this->textdomain);?>" onClick="excluded.value = jQuery.trim( excluded.value ) + '\n<?php echo addslashes( $content_dir ); ?>';">&nbsp;
							<input class="button" id="AddWPContentDir" name="AddWPUpgradeDir" type="button" value="<?php _e('Add WP-Upgrade Dir', $this->textdomain);?>" onClick="excluded.value = jQuery.trim( excluded.value ) + '\n<?php echo addslashes( $this->chg_directory_separator( $content_dir . "upgrade/", FALSE ) ); ?>';">&nbsp;
							<input class="button" id="AddWPAdminDir" name="AddWPAdminDir" type="button" value="<?php _e('Add WP-Admin Dir', $this->textdomain);?>" onClick="excluded.value = jQuery.trim( excluded.value ) + '\n<?php echo addslashes( $admin_dir ); ?>';">&nbsp;
							<input class="button" id="AddWPIncludesDir" name="AddWPIncludesDir" type="button" value="<?php _e('Add WP-Includes Dir', $this->textdomain);?>" onClick="excluded.value = jQuery.trim( excluded.value ) + '\n<?php echo addslashes($this->chg_directory_separator($abspath . "wp-includes/", FALSE) )?>';">&nbsp;
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div id="fragment-2">
			<table class="optiontable form-table" style="margin-top:0;">
				<tbody>
					<tr>
						<th><?php _e('E-Mail the log file', $this->textdomain);?></th>

						<td>
							<input type=checkbox id="emaillog_enabled" name="emaillog"<?php if( array_key_exists( 'emaillog', $option ) && $option['emaillog'] == 'on' ) { echo ' CHECKED'; }?>>
						</td>
					</tr>

					<tr>
						<th><?php _e('Send to addresses', $this->textdomain);?></th>

						<td>
							<input type="input" size="60" id="emaillog_sendto" name="sendto" value="<?php echo $option['sendto'];?>"><br>
							<br>
							<?php _e('This is a comma separated list of e-mail addresses to send the log file to, if blank it will be sent to the WordPress admin user.', $this->textdomain);?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div id="fragment-3">
			<table style=\"margin-top:0; width: auto;\">
				<tbody>
					<tr>
						<td class="description" style="width: auto; text-align: right; vertical-align: top;"><span class="description"><?php _e('Current server time', $this->textdomain);?></span>:</td><td style="width: auto; text-align: left; vertical-align: top;"><code>
<?php
			echo date( get_option('date_format'), $current_time ) . ' @ ' . date( get_option('time_format'), $current_time );
?></code>
						</td>
					</tr>
<?php if( array_key_exists( 'schedule', $option ) && array_key_exists( 'enabled', $option['schedule'] ) && $option['schedule']['enabled'] == 'on' ) { ?>
					<tr>

					<td style="width: auto; text-align: right; vertical-align: top;"><span class="description"><?php _e('Next backup scheduled for', $this->textdomain);?></span>:</td><td style="width: auto; text-align: left; vertical-align: top;"><code>
<?php
			if( $next_schedule ) {
				echo date( get_option('date_format'), $next_schedule ) . ' @ ' . date( get_option('time_format'), $next_schedule );
			}
			else {
				_e('None', $this->textdomain );
			}
?></code>
					</td>
				</tr>
<?php	}?>
				</tbody>
			</table>

			<table class="optiontable form-table" style="margin-top:0;">
				<tbody>
					<tr>
						<th><?php _e('Enable', $this->textdomain);?></th>

						<td><input type=checkbox id="schedule_enabled" name="schedule[enabled]"<?php if( array_key_exists( 'schedule', $option ) && array_key_exists( 'enabled', $option['schedule'] ) && $option['schedule']['enabled'] == 'on' ) { echo ' CHECKED'; }?>></td>
					</tr>

					<tr>
						<th><?php _e('Type', $this->textdomain);?></th>

						<td>
							<select id="schedule_type" onChange="set_schedule_display();" name="schedule[type]">
<?php
		foreach( $schedule_types as $type ) {
			echo "\t\t\t\t\t\t<option value=\"" . $type . '"';

			if( array_key_exists( 'schedule', $option ) && array_key_exists( 'type', $option['schedule'] ) && $option['schedule']['type'] == $type ) { echo ' SELECTED'; $display_settings = $display_type_settings[$type]; }

			echo '>' . __($type, $this->textdomain) . '</option>';
		}
?>
							</select>
						</td>
					</tr>

					<tr>
						<th><?php _e('Schedule', $this->textdomain);?></th>

						<td>
<?php
		if( self::DEBUG_MODE == TRUE ) {
			echo "\t\t\t\t\t\t" . '<span id="schedule_debug" style="' . $display_settings['schedule_debug'] . '">' . __('Every minute, for debugging only', $this->textdomain) . '</span>';
		}
?>
							<span id="schedule_once" style="<?php echo $display_settings['schedule_once'];?>"><?php _e('Only once', $this->textdomain);?></span>
							<span id="schedule_before" style="<?php echo $display_settings['schedule_before'];?>"><?php _e('Run backup every', $this->textdomain);?> </span>
<?php
		echo "\t\t\t\t\t\t<select id=" . '"schedule_interval" name="schedule[interval]">';
		for( $i = 1; $i < 32; $i++ )
			{
			echo '<option value="' . $i . '"';
			if( array_key_exists( 'schedule', $option ) && array_key_exists( 'interval', $option['schedule'] ) && $i == (int)$option['schedule']['interval'] ) { echo ' SELECTED'; }
			echo '>' . $i . '</option>';
			}
		echo "</select>\n";
?>
							<span id="schedule_hours_label" style="<?php echo $display_settings['schedule_hours_label'];?>"> <?php _e('hour(s)', $this->textdomain);?></span><span id="schedule_days" style="<?php echo $display_settings['schedule_days'];?>"> <?php _e('day(s)', $this->textdomain);?></span><span id="schedule_weeks" style="<?php echo $display_settings['schedule_weeks'];?>"> <?php _e('week(s)', $this->textdomain);?></span><span id="schedule_months" style="<?php echo $display_settings['schedule_months'];?>"> <?php _e('month(s)', $this->textdomain);?></span>
							<span id="schedule_on" style="<?php echo $display_settings['schedule_on'];?>"> <?php _e('on', $this->textdomain);?></span>

							<select id="schedule_dow" name="schedule[dow]" style="<?php echo $display_settings['schedule_dow'];?>">
								<option value=""></option>
<?php
		$weekdays = array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' );
		foreach( $weekdays as $day ) {
			echo "\t\t\t\t\t\t\t\t" . '<option value="' . $day . '"';

			if( array_key_exists( 'schedule', $option ) && array_key_exists( 'dow', $option['schedule'] ) && $option['schedule']['dow'] == $day ) { echo' SELECTED'; }

			echo '>' . __($day, $this->textdomain) . '</option>';
		}
?>
							</select>

							<span id="schedule_the" style="<?php echo $display_settings['schedule_the'];?>"> <?php _e('the', $this->textdomain);?></span>

							<select id="schedule_dom" name="schedule[dom]" style="<?php echo $display_settings['schedule_dom'];?>">
								<option value=""></option>
<?php
		for( $i = 1; $i < 28; $i++ ) {
			echo "\t\t\t\t\t\t\t\t" . '<option value="' . $i . '"';

			if( array_key_exists( 'schedule', $option ) && array_key_exists( 'dom', $option['schedule'] ) && $option['schedule']['dom'] == $i ) { echo' SELECTED'; }

			echo '>' . $i . '</option>';
		}
?>
							</select>

							<span id="schedule_at" style="<?php echo $display_settings['schedule_at'];?>"> <?php _e('at', $this->textdomain);?></span>
<?php
		echo "\t\t\t\t\t\t<select id=" . '"schedule_hours" name="schedule[hours]"><option value=""></option>';
		for( $i = 1; $i < 13; $i++ )
			{
			echo '<option value="' . $i . '"';
			if( array_key_exists( 'schedule', $option ) && array_key_exists( 'hours', $option['schedule'] ) && $i == (int)$option['schedule']['hours'] ) { echo ' SELECTED'; }
			echo '>' . $i . '</option>';
			}
		echo "</select>\n";

		echo "\t\t\t\t\t\t<select id=" . '"schedule_minutes" name="schedule[minutes]"><option value=""></option>';
		for( $i = 0; $i < 60; $i++ )
			{
			echo '<option value="' . $i . '"';
			if( array_key_exists( 'schedule', $option ) && array_key_exists( 'minutes', $option['schedule'] ) && $i == (int)$option['schedule']['minutes'] ) { echo ' SELECTED'; }
			echo '>:';
			if( $i < 10 ) { echo '0'; }
			echo $i . '</option>';
			}
		echo "</select>\n";
?>
							<select id="schedule_ampm" name="schedule[ampm]"><option value="am"<?php if( array_key_exists( 'schedule', $option ) && array_key_exists( 'ampm', $option['schedule'] ) && $option['schedule']['ampm'] == 'am' ) { echo ' SELECTED'; } ?>>am</option><option value="pm"<?php if( array_key_exists( 'schedule', $option ) && array_key_exists( 'ampm', $option['schedule'] ) && $option['schedule']['ampm'] == 'pm' ) { echo ' SELECTED'; } ?>>pm</option></select>.
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div id="fragment-4">
			<table class="optiontable form-table" style="margin-top:0;">
				<tbody>
					<tr>
						<th><?php _e('Enable backup pruning', $this->textdomain);?></th>

						<td><input type="checkbox" name="prune[enabled]"<?php	if( array_key_exists( 'prune', $option ) && array_key_exists( 'enabled', $option['prune'] ) && $option['prune']['enabled'] == 'on' ) { echo' CHECKED'; }?>></td>
					</tr>

					<tr>
						<th><?php _e('Number of backups to keep', $this->textdomain);?></th>

						<td><input type="text" name="prune[number]" size="5" value="<?php echo $option['prune']['number'];?>"></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div id="fragment-5">
			<table class="optiontable form-table" style="margin-top:0;">
				<tbody>
					<tr>
						<th><?php _e('Enable remote storage', $this->textdomain);?></th>

						<td><input type="checkbox" name="remote[enabled]"<?php	if( array_key_exists( 'remote', $option ) && array_key_exists( 'enabled', $option['remote'] ) && $option['remote']['enabled'] == 'on' ) { echo' CHECKED'; }?>></td>
					</tr>

					<tr>
						<th><?php _e('Protocol', $this->textdomain);?></th>

						<td>
<?php
		echo "\t\t\t\t\t\t<select id=" . '"remote_protocol" name="remote[protocol]"><option value=""></option>';

		$wrappers = stream_get_wrappers();

		//									'dropbox' => __('DropBox', $this->textdomain),
		if( in_array( 'ftp', $wrappers ) ) 			{ $remoteprotocols['ftpwrappers'] 	= __('FTP Wrappers', $this->textdomain); }
		if( function_exists( 'ftp_connect' ) ) 		{ $remoteprotocols['ftplibrary'] 	= __('FTP Library', $this->textdomain); }
		if( in_array( 'ftps', $wrappers ) ) 		{ $remoteprotocols['ftpswrappers'] 	= __('FTPS Wrappers', $this->textdomain); }
		if( function_exists( 'ftp_ssl_connect' ) ) 	{ $remoteprotocols['ftpslibrary'] 	= __('FTPS Library', $this->textdomain); }
		if( in_array( 'ssh2', $wrappers ) ) 		{ $remoteprotocols['sftpwrappers'] 	= __('SFTP Wrappers', $this->textdomain); }
		if( function_exists( 'ssh2_connect' ) ) 	{ $remoteprotocols['sftplibrary'] 	= __('SFTP Library', $this->textdomain); }
		$remoteprotocols['sftpphpseclib'] = __('SFTP phpseclib', $this->textdomain);

		foreach( $remoteprotocols as $key => $protocol )
			{
			echo '<option value="' . $key . '"';
			if( array_key_exists( 'remote', $option ) && array_key_exists( 'protocol', $option['remote'] ) && $key == $option['remote']['protocol'] ) { echo ' SELECTED'; }
			echo '>'. $protocol . '</option>';
			}

		echo "</select>\n";
?>
						</td>
					</tr>

					<tr>
						<th><?php _e('Host', $this->textdomain);?></th>

						<td><input type="text" size="40" name="remote[host]" value="<?php echo $option['remote']['host'];?>"></td>
					</tr>

					<tr>
						<th><?php _e('Username', $this->textdomain);?></th>

						<td><input type="text" size="20" name="remote[username]" value="<?php echo $option['remote']['username'];?>"></td>
					</tr>

					<tr>
						<th><?php _e('Password', $this->textdomain);?></th>

						<td><input type="password" size="20" name="remote[password]" value="<?php echo $option['remote']['password'];?>"></td>
					</tr>

					<tr>
						<th><?php _e('Remote path', $this->textdomain);?></th>

						<td>
							<input type="text" size="40" name="remote[path]" value="<?php echo $option['remote']['path'];?>"><br>
							<br>
							<?php _e( "You many use the follow place holders: %m = month (01-12), %d = day (01-31), %Y = year (XXXX), %M = month (Jan...Dec), %F = month (January...December)" );?>
						</td>
					</tr>

					<tr>
						<th><?php _e('Include log file', $this->textdomain);?></th>

						<td><input type="checkbox" name="remote[sendlog]"<?php	if( array_key_exists( 'remote', $option ) && array_key_exists( 'sendlog', $option['remote'] ) && $option['remote']['sendlog'] == 'on' ) { echo' CHECKED'; }?>></td>
					</tr>

					<tr>
						<th><?php _e('Delete local copy during scheduled backup', $this->textdomain);?></th>

						<td><input type="checkbox" name="remote[deletelocalschedule]"<?php	if( array_key_exists( 'remote', $option ) && array_key_exists( 'deletelocalschedule', $option['remote'] ) && $option['remote']['deletelocalschedule'] == 'on' ) { echo' CHECKED'; }?>></td>
					</tr>

					<tr>
						<th><?php _e('Delete local copy during manual backup', $this->textdomain);?></th>

						<td><input type="checkbox" name="remote[deletelocalmanual]"<?php	if( array_key_exists( 'remote', $option ) && array_key_exists( 'deletelocalmanual', $option['remote'] ) && $option['remote']['deletelocalmanual'] == 'on' ) { echo' CHECKED'; }?>></td>
					</tr>

				</tbody>
			</table>
		</div>

		<div id="fragment-6">
			<table class="optiontable form-table" style="margin-top:0;">
				<tbody>
					<tr>
						<th><?php _e('Clear active backup status', $this->textdomain);?></th>

						<td><input type="checkbox" id="clear_backup" name="clear_backup"><br><br><?php _e('WARNING: Only check this if a backup has hung and you can no longer execute backups.', $this->textdomain);?></td>
					</tr>

					<tr>
						<th><?php _e('Delete temporary files and directories', $this->textdomain);?></th>

						<td><input type="checkbox" id="clear_files" name="clear_files"><br><br><?php echo sprintf(__('WARNING: Only check this if no backup is running.  This will delete any file in the archive path that starts with "%s" but is not a zip/log file.', $this->textdomain), basename(ABSPATH) );?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<p style="margin-top:1em;"><input type="submit" name="options_update" class="button-primary" value="<?php _e('Update Options', $this->textdomain);?>" class="button" /></p>

	</form>

</div>
