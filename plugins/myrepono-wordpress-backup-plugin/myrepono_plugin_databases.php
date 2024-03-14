<?php
/**
 * @package myRepono
 * @version 2.0.12
 */
/*
Copyright 2016 ionix Limited (email: support@myRepono.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if ((defined('WP_MYREPONO_PLUGIN')) && (function_exists('is_admin')) && (is_admin())) {

} else {

	print 'myRepono WordPress Backup Plugin can not load.';
	exit;

}


function myrepono_plugin_databases_func() {

	global $myrepono;

	$init_request = false;
	$init_request_second = false;

	$input_domain_id = '';
	$response = '';
	if (!isset($myrepono['tmp']['success'])) {
		$myrepono['tmp']['success'] = array();
	}
	if (!isset($myrepono['tmp']['error'])) {
		$myrepono['tmp']['error'] = array();
	}
	if (!isset($myrepono['tmp']['critical'])) {
		$myrepono['tmp']['critical'] = array();
	}

	$input_databases_host = 'localhost';
	$input_databases_name = '';
	$input_databases_user = '';
	$input_databases_no_cache = '0';

	if ((isset($_POST['myrepono_domain_id'])) && (is_numeric($_POST['myrepono_domain_id']))) {

		$input_domain_id = $_POST['myrepono_domain_id'];

		if ((isset($_POST['myrepono_databases'])) && (isset($_POST['myrepono_databases_remove']))) {

			$input_databases = $_POST['myrepono_databases'];
			if (!is_array($input_databases)) {
				$input_databases = array($input_databases);
			}
			$input_databases_count = count($input_databases);

			for ($i=0; $i<$input_databases_count; $i++) {

				if ((isset($input_databases[$i])) && (is_numeric($input_databases[$i]))) {

					if ($init_request===false) {
						$init_request = array();
					}
					$init_request['domain-dbs-delete'][$input_domain_id][$input_databases[$i]] = '';

					if (!isset($init_request['domain-dbs'][$input_domain_id])) {
						$init_request['domain-dbs'][$input_domain_id] = '';
					}
				}
			}
		} elseif ((isset($_POST['myrepono_databases_host'])) && (isset($_POST['myrepono_databases_name'])) && (isset($_POST['myrepono_databases_user'])) && (isset($_POST['myrepono_databases_pass'])) && (isset($_POST['myrepono_databases_add']))) {

			$input_databases_host = $_POST['myrepono_databases_host'];
			$input_databases_name = $_POST['myrepono_databases_name'];
			$input_databases_user = $_POST['myrepono_databases_user'];
			$input_databases_pass = $_POST['myrepono_databases_pass'];
			$input_databases_pass = str_replace("\\'", "'", $input_databases_pass);
			$input_databases_pass = str_replace('\\"', '"', $input_databases_pass);

			$input_databases_put = '0';
			if (isset($_POST['myrepono_databases_put'])) {
				if (($_POST['myrepono_databases_put']=='1') || ($_POST['myrepono_databases_put']=='2')) {
					$input_databases_put = $_POST['myrepono_databases_put'];
				}
			}

			if (($input_databases_host!='') && ($input_databases_name!='') && ($input_databases_user!='')) {

				if ($db_test = new wpdb($input_databases_user, $input_databases_pass, $input_databases_name, $input_databases_host)) {

					if ($init_request===false) {
						$init_request = array();
					}
					$init_request['domain-dbs-add'][$input_domain_id][0] = array(
						'host' => $input_databases_host,
						'name' => $input_databases_name,
						'user' => $input_databases_user,
						'pass' => $input_databases_pass,
						'backup_all' => '1'
					);

					if ($db_test_result = $db_test->get_results('show tables from `'.myrepono_escape_string($input_databases_name).'`;', ARRAY_A )) {

						$db_test_number = count($db_test_result);

						if (($db_test_number<=256) || (($db_test_number<=2048) && ($input_databases_put=='1'))) {

							for ($i=0; $i<$db_test_number; $i++) {

								$db_test_name = array_keys($db_test_result[$i]);
								$db_test_name = $db_test_result[$i][$db_test_name[0]];

								$init_request['domain-dbs-add'][$input_domain_id][0]['tables'][] = array(
									'name' => $db_test_name
								);

							}
						} else {

							$input_databases_no_cache = '1';

						}
					}

					if (!isset($init_request['domain-dbs'][$input_domain_id])) {
						$init_request['domain-dbs'][$input_domain_id] = '';
					}
				} else {

					$myrepono['tmp']['error'][] = "Unable to connect to database, please verify your database host address, name, username and password are correct.";

				}
			}
		}
	}

	$output = myrepono_plugin_init('databases', $init_request);

	$databases_url = admin_url('admin.php?page=myrepono-databases');
	$icon_url = $myrepono['plugin']['url'].'img/icons';

	if ($output===false) {

		if (isset($myrepono['papi']['domain'])) {

			$domain_id = $myrepono['papi']['domain'];

			if ((isset($myrepono['domain'][$domain_id]['dbs'])) && (is_array($myrepono['domain'][$domain_id]['dbs']))) {

				if ((isset($_POST['myrepono_databases'])) && (isset($_POST['myrepono_databases_edit']))) {

					$input_databases = $_POST['myrepono_databases'];
					if (!is_array($input_databases)) {
						$input_databases = array($input_databases);
					}
					$input_databases_count = count($input_databases);
					if ($input_databases_count>1) {
						$input_databases_count = '1';
					}

					for ($i=0; $i<$input_databases_count; $i++) {

						if ((isset($input_databases[$i])) && (is_numeric($input_databases[$i]))) {

							$database_id = $input_databases[$i];

							if (isset($myrepono['domain'][$domain_id]['dbs'][$database_id])) {

								$input_databases_host = '';
								if (isset($myrepono['domain'][$domain_id]['dbs'][$database_id]['host'])) {
									$input_databases_host = $myrepono['domain'][$domain_id]['dbs'][$database_id]['host'];
								}

								$input_databases_name = '';
								if (isset($myrepono['domain'][$domain_id]['dbs'][$database_id]['name'])) {
									$input_databases_name = $myrepono['domain'][$domain_id]['dbs'][$database_id]['name'];
								}

								$input_databases_user = '';
								if (isset($myrepono['domain'][$domain_id]['dbs'][$database_id]['user'])) {
									$input_databases_user = $myrepono['domain'][$domain_id]['dbs'][$database_id]['user'];
								}

								$input_databases_pass = '';

								$selected_databases_tables_keys = array_keys($myrepono['domain'][$domain_id]['dbs'][$database_id]['tables']);
								$selected_databases_tables_count = count($selected_databases_tables_keys);

								$selected_databases_tables = array();

								for ($i=0; $i<$selected_databases_tables_count; $i++) {
									$selected_databases_tables_key = $selected_databases_tables_keys[$i];
									if (isset($myrepono['domain'][$domain_id]['dbs'][$database_id]['tables'][$selected_databases_tables_key]['name'])) {

										$selected_databases_table = $myrepono['domain'][$domain_id]['dbs'][$database_id]['tables'][$selected_databases_tables_key]['name'];
										$selected_databases_tables[$selected_databases_table] = $selected_databases_tables_key;
									}
								}

								$edit_database = '1';
								$tables = '';

								if ((isset($_POST['myrepono_databases_host'])) && (isset($_POST['myrepono_databases_name'])) && (isset($_POST['myrepono_databases_user'])) && (isset($_POST['myrepono_databases_pass']))) {

									$edit_database = '0';

									$input_databases_host = $_POST['myrepono_databases_host'];
									$input_databases_name = $_POST['myrepono_databases_name'];
									$input_databases_user = $_POST['myrepono_databases_user'];
									$input_databases_pass = $_POST['myrepono_databases_pass'];
									$input_databases_pass = str_replace("\\'", "'", $input_databases_pass);
									$input_databases_pass = str_replace('\\"', '"', $input_databases_pass);
									$input_databases_tables = array();

									if (($input_databases_host!='') && ($input_databases_name!='') && ($input_databases_user!='')) {

										if ($db_test = new wpdb($input_databases_user, $input_databases_pass, $input_databases_name, $input_databases_host)) {

											if ($db_test_result = $db_test->get_results('show tables from `'.myrepono_escape_string($input_databases_name).'`;', ARRAY_A )) {

												$db_test_number = count($db_test_result);

												$edit_database = '2';

												if (isset($_POST['myrepono_tables'])) {

													$input_database_tables_count = count($_POST['myrepono_tables']);
													for ($i=0; $i<$input_database_tables_count; $i++) {

														$input_database_table = $_POST['myrepono_tables'][$i];
														$input_databases_tables[$input_database_table] = '';

													}

													$edit_database = '3';

												}

												if (($db_test_number<=256) || (($db_test_number<=2048) && ($myrepono['papi']['connect']['put']=='1'))) {

													$row_colour = '';

													for ($i=0; $i<$db_test_number; $i++) {

														$db_test_name = array_keys($db_test_result[$i]);
														$db_test_name = $db_test_result[$i][$db_test_name[0]];

														if (isset($input_databases_tables[$db_test_name])) {

															if (isset($selected_databases_tables[$db_test_name])) {

																$selected_databases_table = $selected_databases_tables[$db_test_name];

																$init_request['domain-dbs-update'][$input_domain_id][$database_id]['tables'][$selected_databases_table] = array(
																	'name' => $db_test_name
																);

															} else {

																$init_request['domain-dbs-update'][$input_domain_id][$database_id]['tables'][] = array(
																	'name' => $db_test_name
																);

															}
														} elseif (isset($selected_databases_tables[$db_test_name])) {

															$selected_databases_table = $selected_databases_tables[$db_test_name];

															$init_request['domain-dbs-update'][$input_domain_id][$database_id]['tables'][$selected_databases_table] = array(
																'delete' => '1'
															);

															$init_request['domain-dbs-update'][$input_domain_id][$database_id]['backup_all'] = '0';

														} else {

															$init_request['domain-dbs-update'][$input_domain_id][$database_id]['backup_all'] = '0';

														}

														$table_checked = '';
														if (isset($selected_databases_tables[$db_test_name])) {
															$table_checked = ' checked="checked"';
														}

														if ($row_colour=='') {
															$row_colour = ' class="alternate"';
														} else {
															$row_colour = '';
														}

														$tables .= <<<END
   <tr$row_colour>
     <td><input type="checkbox" name="myrepono_tables[]" id="myrepono_tables_$i" value="$db_test_name"$table_checked /></td>
     <td>$db_test_name</td>
   </tr>

END;

													}
												} else {

													$input_databases_no_cache = '1';
													$edit_database = '3';

													$tables = <<<END
	<tr>
		<td colspan="2" align="center">&nbsp;<br /><b>This database has too many tables to manage using this plugin.  Please log-in to your myRepono.com account and proceed to the 'Domains' -&gt; 'Databases' section to manage this database.</b><br />&nbsp;</td>
	</tr>
END;

												}

												$init_request['domain-dbs-update'][$input_domain_id][$database_id]['host'] = $input_databases_host;
												$init_request['domain-dbs-update'][$input_domain_id][$database_id]['name'] = $input_databases_name;
												$init_request['domain-dbs-update'][$input_domain_id][$database_id]['user'] = $input_databases_user;
												$init_request['domain-dbs-update'][$input_domain_id][$database_id]['pass'] = $input_databases_pass;

												if (!isset($init_request['domain-dbs-update'][$input_domain_id][$database_id]['backup_all'])) {
													$init_request['domain-dbs-update'][$input_domain_id][$database_id]['backup_all'] = '1';
												}
											}

											if (!isset($init_request['domain-dbs'][$input_domain_id])) {
												$init_request['domain-dbs'][$input_domain_id] = '';
											}

											//@mysql_close($db_test);

										} else {

											$edit_database = '1';

											$myrepono['tmp']['error'][] = "Unable to connect to database, please verify your database host address, name, username and password are correct.";

										}
									} else {

										$edit_database = '1';

										$myrepono['tmp']['error'][] = "Unable to connect to database, please verify your database host address, name, username and password are correct.";

									}
								}

								if ($edit_database=='1') {

									$response = myrepono_response('success', $myrepono['tmp']['success'], $response);
									$response = myrepono_response('error', $myrepono['tmp']['error'], $response);
									$response = myrepono_response('critical', $myrepono['tmp']['critical'], $response);

									$input_databases_host = htmlentities($input_databases_host);
									$input_databases_name = htmlentities($input_databases_name);
									$input_databases_user = htmlentities($input_databases_user);

									$output = <<<END

$response

<br class="clear" />

<div id="col-container">

	<form action="$databases_url" method="POST">
	<input type="hidden" name="myrepono_domain_id" value="$domain_id" />
	<input type="hidden" name="myrepono_databases" value="$database_id" />

	<div id="col-right">
		<div class="col-wrap">

		</div>
	</div>

	<div id="col-left">
		<div class="col-wrap">

			<div class="form-wrap">

				<h3>Edit Database: $input_databases_name</h3>

				<p>Please complete the following form to edit your database.</p>

				<div class="form-field form-required">
					<label for="myrepono_databases_host">Database Host:</label>
					<input name="myrepono_databases_host" id="myrepono_databases_host" type="text" value="$input_databases_host" size="40" />
					<p>The database host address, e.g. localhost</p>
				</div>

				<div class="form-field form-required">
					<label for="myrepono_databases_name">Database Name:</label>
					<input name="myrepono_databases_name" id="myrepono_databases_name" type="text" value="$input_databases_name" size="40" />
					<p>The name of your database.</p>
				</div>

				<div class="form-field form-required">
					<label for="myrepono_databases_user">Database Username:</label>
					<input name="myrepono_databases_user" id="myrepono_databases_user" type="text" value="$input_databases_user" size="40" />
					<p>The username for your database.</p>
				</div>

				<div class="form-field form-required">
					<label for="myrepono_databases_pass">Database Password:</label>
					<input name="myrepono_databases_pass" id="myrepono_databases_pass" type="password" value="" size="40" />
					<p>The password for your database.  Please note, you must enter your database password - for security reasons this plugin can not retrieve your current password from your myRepono account.</p>
				</div>

				<p class="submit" style="padding-top:0px;"><input type="submit" name="myrepono_databases_edit" id="myrepono_databases_edit" class="button" value="Edit Database"  /></p>

			</div>

		</div>
	</div>

	</form>
</div>

END;


								} elseif ($edit_database=='2') {

									$input_databases_host = htmlentities($input_databases_host);
									$input_databases_name = htmlentities($input_databases_name);
									$input_databases_user = htmlentities($input_databases_user);

									if ($tables=='') {

										$tables .= <<<END
	<tr>
		<td colspan="2" align="center">&nbsp;<br /><b>Database does not have any tables.</b><br />&nbsp;</td>
	</tr>
END;

									}

									$output = <<<END

$response

<br class="clear" />

<div id="col-container">

	<form action="$databases_url" method="POST">
	<input type="hidden" name="myrepono_domain_id" value="$domain_id" />
	<input type="hidden" name="myrepono_databases" value="$database_id" />

	<div id="col-right">
		<div class="col-wrap">

			<table class="widefat">
			<thead>
				<tr>
					<td scope="col" class="manage-column check-column" width="16"><input type="checkbox" name="myrepono_tables_all" onclick="myrepono_select_all('tables', this.checked);" /></td>
					<td scope="col" class="manage-column">Tables</td>
				</tr>
			</thead>
			<tbody>
			$tables
			</tbody>
			</table>

		</div>
	</div>

	<div id="col-left">

		<div class="col-wrap">

			<div class="form-wrap">

				<h3>Edit Database: $input_databases_name</h3>

				<p>Please select which database tables you would to backup.</p>
				<br />

				<input name="myrepono_databases_host" id="myrepono_databases_host" type="hidden" value="$input_databases_host" />
				<input name="myrepono_databases_name" id="myrepono_databases_name" type="hidden" value="$input_databases_name" />
				<input name="myrepono_databases_user" id="myrepono_databases_user" type="hidden" value="$input_databases_user" />
				<input name="myrepono_databases_pass" id="myrepono_databases_pass" type="hidden" value="$input_databases_pass" />

				<p class="submit" style="padding-top:0px;"><input type="submit" name="myrepono_databases_edit" id="myrepono_databases_edit" class="button" value="Edit Database" /></p>

			</div>
		</div>
	</div>

	</form>
</div>

END;

								} elseif ($edit_database=='3') {

									$output = myrepono_plugin_init('databases', $init_request);

									if ($output===false) {

										if ($input_databases_no_cache=='1') {

											$myrepono['tmp']['success'][] = 'Your database was updated successfully!  Please note, due to the quantity of database tables your table names could not be cached.';

										} else {

											$myrepono['tmp']['success'][] = 'Your database was updated successfully!';

										}
									}
								}
							}
						}
					}
				}
			}
		}
	}

	if ($output===false) {

		if ($input_domain_id!='') {

			if (isset($myrepono['tmp']['papi_response']['domain-dbs-delete'][$input_domain_id])) {

				$domain_databases_success = '0';
				$domain_databases_error = '0';

				$domain_databases_keys = array_keys($myrepono['tmp']['papi_response']['domain-dbs-delete'][$input_domain_id]);
				$domain_databases_count = count($domain_databases_keys);

				for ($i=0; $i<$domain_databases_count; $i++) {

					$domain_databases_key = $domain_databases_keys[$i];

					if (isset($myrepono['tmp']['papi_response']['domain-dbs-delete'][$input_domain_id][$domain_databases_key]['success'])) {

						$domain_databases_success++;

					} elseif (isset($myrepono['tmp']['papi_response']['domain-dbs-delete'][$input_domain_id][$domain_databases_key]['error'])) {

						$domain_databases_error++;

					}
				}

				if ($domain_databases_success=='1') {

					$myrepono['tmp']['success'][] = $domain_databases_success.' database was removed successfully.';

				} elseif ($domain_databases_success>0) {

					$myrepono['tmp']['success'][] = $domain_databases_success.' databases were removed successfully.';

				}

				if ($domain_databases_error=='1') {

					$myrepono['tmp']['error'][] = $domain_databases_error.' database could not be removed.';

				} elseif ($domain_databases_error>0) {

					$myrepono['tmp']['error'][] = $domain_databases_error.' databases could not be removed.';
				}

			} elseif (isset($myrepono['tmp']['papi_response']['domain-dbs-add'][$input_domain_id])) {

				$domain_databases_success = '0';
				$domain_databases_error = '0';

				$domain_databases_keys = array_keys($myrepono['tmp']['papi_response']['domain-dbs-add'][$input_domain_id]);
				$domain_databases_count = count($domain_databases_keys);

				for ($i=0; $i<$domain_databases_count; $i++) {

					$domain_databases_key = $domain_databases_keys[$i];

					if (isset($myrepono['tmp']['papi_response']['domain-dbs-add'][$input_domain_id][$domain_databases_key]['id'])) {

						$domain_databases_success++;

					} elseif (isset($myrepono['tmp']['papi_response']['domain-dbs-add'][$input_domain_id][$domain_databases_key]['error'])) {

						$domain_databases_error++;

					}
				}

				if ($domain_databases_success=='1') {

					if ($input_databases_no_cache=='1') {

						$myrepono['tmp']['success'][] ='Your database was added successfully!  Please note, due to the quantity of database tables your table names could not be cached.';

					} else {

						$myrepono['tmp']['success'][] ='Your database was added successfully!';

					}

				} elseif ($domain_databases_success>0) {

					$myrepono['tmp']['success'][] = $domain_databases_success.' databases were added successfully!';

				}

				if ($domain_databases_error=='1') {

					$myrepono['tmp']['error'][] = 'Your database could not be added.';

				} elseif ($domain_databases_error>0) {

					$myrepono['tmp']['error'][] = $domain_databases_error.' databases could not be added.';
				}

			}

		}


		$databases = '';
		$databases_buttons = '<p><input class="button" value="Remove" name="myrepono_databases_remove" type="submit" />&nbsp; <input class="button" value="Edit Database" name="myrepono_databases_edit" type="submit" /></p>';

		$databases_wordpress_found = '0';

		if (isset($myrepono['papi']['domain'])) {

			$domain_id = $myrepono['papi']['domain'];

			if ((isset($myrepono['domain'][$domain_id]['dbs'])) && (is_array($myrepono['domain'][$domain_id]['dbs']))) {

				$databases_keys = array_keys($myrepono['domain'][$domain_id]['dbs']);
				$databases_count = count($databases_keys);

				$row_colour = '';

				for ($i=0; $i<$databases_count; $i++) {

					$databases_key = $databases_keys[$i];

					if ((isset($myrepono['domain'][$domain_id]['dbs'][$databases_key]['host'])) && (isset($myrepono['domain'][$domain_id]['dbs'][$databases_key]['name'])) && (isset($myrepono['domain'][$domain_id]['dbs'][$databases_key]['user']))) {

						$databases_host = $myrepono['domain'][$domain_id]['dbs'][$databases_key]['host'];
						$databases_name = $myrepono['domain'][$domain_id]['dbs'][$databases_key]['name'];
						$databases_user = $myrepono['domain'][$domain_id]['dbs'][$databases_key]['user'];
						$databases_notes = '';

						$databases_tables = '';
						if ((isset($myrepono['domain'][$domain_id]['dbs'][$databases_key]['backup_all'])) && ($myrepono['domain'][$domain_id]['dbs'][$databases_key]['backup_all']=='1')) {

							$databases_tables = '<i>All Tables</i>';

						} elseif ((isset($myrepono['domain'][$domain_id]['dbs'][$databases_key]['tables'])) && (is_array($myrepono['domain'][$domain_id]['dbs'][$databases_key]['tables']))) {

							$databases_tables_keys = array_keys($myrepono['domain'][$domain_id]['dbs'][$databases_key]['tables']);
							$databases_tables_count = count($databases_tables_keys);

							for ($j=0; $j<$databases_tables_count; $j++) {

								$databases_tables_key = $databases_tables_keys[$j];

								if (isset($myrepono['domain'][$domain_id]['dbs'][$databases_key]['tables'][$databases_tables_key]['name'])) {

									if ($databases_tables!='') {
										$databases_tables .= ', ';
									}

									$databases_tables .= $myrepono['domain'][$domain_id]['dbs'][$databases_key]['tables'][$databases_tables_key]['name'];

									if (strlen($databases_tables)>64) {
										$j = $databases_tables_count;
										$databases_tables .= '...';
									}
								}
							}
						}

						if ($databases_tables=='') {

							$databases_tables = '<i>Unknown</i>';

						}

						if (($databases_host==DB_HOST) && ($databases_name==DB_NAME) && ($databases_user==DB_USER)) {

							$databases_notes .= '&nbsp;<span><img src="'.$icon_url.'/wordpress.png" width="14" height="14" alt="WordPress Database" title="WordPress Database" /> WordPress Database</span>';
							$databases_wordpress_found = '1';

						}

						if ($row_colour=='') {
							$row_colour = ' class="alternate"';
						} else {
							$row_colour = '';
						}

						$databases .= <<<END
   <tr$row_colour>
     <td><input type="checkbox" name="myrepono_databases[]" id="myrepono_databases_$databases_key" value="$databases_key" /></td>
     <td>$databases_host</td>
     <td>$databases_name</td>
     <td>$databases_user</td>
     <td width="45%"><small>$databases_tables</small>$databases_notes</td>
   </tr>

END;

					}
				}
			}
		}

		if ($databases=='') {

			$databases_buttons = '';
			$databases = <<<END
   <tr>
     <td colspan="5" align="center">&nbsp;<br /><b>No databases selected.</b><br />&nbsp;</td>
   </tr>

END;
		}

		$databases_put = '0';
		if (isset($myrepono['papi']['connect']['put'])) {
			$databases_put = $myrepono['papi']['connect']['put'];
		}

		$response = myrepono_response('success', $myrepono['tmp']['success'], $response);
		$response = myrepono_response('error', $myrepono['tmp']['error'], $response);
		$response = myrepono_response('critical', $myrepono['tmp']['critical'], $response);

		$output = <<<END

$response

<br class="clear" />

<div id="col-container">

	<div id="col-right">
		<div class="col-wrap">

			<h3>Selected Databases</h3>

			<form action="$databases_url" method="POST">
			<input type="hidden" name="myrepono_domain_id" value="$domain_id" />
			<table class="widefat" id="myrepono_dbs">
			<thead>
				<tr>
					<td scope="col" class="manage-column check-column" width="16"><input type="checkbox" name="myrepono_databases_all" onclick="myrepono_select_all('databases', this.checked);" /></td>
					<td scope="col" class="manage-column">Host</td>
					<td scope="col" class="manage-column">Name</td>
					<td scope="col" class="manage-column">Username</td>
					<td scope="col" class="manage-column">Selected Tables</td>
				</tr>
			</thead>
			<tbody>
			$databases
			</tbody>
			</table>
			$databases_buttons
			</form>

		</div>
	</div>

	<div id="col-left">
		<div class="col-wrap">

			<div class="form-wrap">

				<h3>Add Database</h3>

				<form action="$databases_url" method="POST">
				<input type="hidden" name="myrepono_domain_id" value="$domain_id" />
				<input type="hidden" name="myrepono_databases_put" value="$databases_put" />

				<div class="form-field form-required">
					<label for="myrepono_databases_host">Database Host:</label>
					<input name="myrepono_databases_host" id="myrepono_databases_host" type="text" value="$input_databases_host" size="40" />
					<p>The database host address, e.g. localhost</p>
				</div>

				<div class="form-field form-required">
					<label for="myrepono_databases_name">Database Name:</label>
					<input name="myrepono_databases_name" id="myrepono_databases_name" type="text" value="$input_databases_name" size="40" />
					<p>The name of your database.</p>
				</div>

				<div class="form-field form-required">
					<label for="myrepono_databases_user">Database Username:</label>
					<input name="myrepono_databases_user" id="myrepono_databases_user" type="text" value="$input_databases_user" size="40" />
					<p>The username for your database.</p>
				</div>

				<div class="form-field form-required">
					<label for="myrepono_databases_pass">Database Password:</label>
					<input name="myrepono_databases_pass" id="myrepono_databases_pass" type="password" value="" size="40" />
					<p>The password for your database.</p>
				</div>

				<p class="submit" style="padding-top:0px;"><input type="submit" name="myrepono_databases_add" id="myrepono_databases_add" class="button" value="Add Database"  /></p>

				</form>

			</div>

		</div>
	</div>

</div>

END;

	}

	myrepono_plugin_output('databases', $output);

}


?>