<?php

class SEO_Backlink_Monitor_Admin
{

	private $page_name = 'toplevel_page_' . SEO_BLM_PLUGIN;
	private $settings_default;
	private $refresh_link_tries = 2; # must be 1 or higher

	public function __construct()
	{
		$this->settings_default = [
			'cronFrequency' => '',
			'notifyEmail' => '',
			'dateFormat' => '',
			'displayNotes' => '',
			'resultItemsPerPage' => '10', // since 1.2.0
		];
	}

	public function get_settings_default()
	{
		return $this->settings_default;
	}

	public function load_textdomain()
	{
		load_plugin_textdomain( 'seo-backlink-monitor', false, dirname(plugin_basename(__FILE__)).'/../languages/' );
	}

	public function check_for_db_updates()
	{
		$db_plugin_version = get_option(SEO_BLM_OPTION_VERSION) ?: '1.0.0';
		if (SEO_BLM_PLUGIN_VERSION === $db_plugin_version) {
			return;
		}
		if ($db_plugin_version === '1.0.0') {
			// Initial setup
			update_option(SEO_BLM_OPTION_SETTINGS, $this->settings_default);
		}
		if (version_compare($db_plugin_version, '1.2.0', '<')) {
			// Handle changes from 1.2.0
			$settings = get_option(SEO_BLM_OPTION_SETTINGS);
			if (!$settings) {
				$settings = $this->settings_default;
			} elseif (!isset($settings['resultItemsPerPage'])) {
				$settings['resultItemsPerPage'] = $this->settings_default['resultItemsPerPage'];
			}
			update_option(SEO_BLM_OPTION_SETTINGS, $settings);
		}
		if (version_compare($db_plugin_version, '1.3.0', '<')) {
			// Handle changes from 1.3.0
			$db_links = get_option(SEO_BLM_OPTION_LINKS);
			$fixed_db_links = [];
			foreach ( $db_links as $idx => $link ) {
				$link['id'] = $idx + 1;
				if (!isset($link['followMob'])) {
					$link['followMob'] = -1;
					$link['followMobOld'] = -1;
				}
				$fixed_db_links[ $idx ] = $link;
			}
			update_option(SEO_BLM_OPTION_LINKS, $fixed_db_links);
		}
		update_option(SEO_BLM_OPTION_VERSION, SEO_BLM_PLUGIN_VERSION);
	}

	public function admin_enqueue_scripts($hook)
	{
		if ($this->page_name === $hook) {
			wp_enqueue_script(
				'SEO_Backlink_Monitor_admin_scripts',
				SEO_BLM_PLUGIN_URL . '/admin/js/seo-backlink-monitor-admin' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ) . '.js',
				array(
					'jquery'
				),
				SEO_BLM_PLUGIN_VERSION,
				true
			);
			wp_localize_script(
				'SEO_Backlink_Monitor_admin_scripts',
				'SEO_BLM_Localize',
				array(
					'ajax_url' => admin_url('admin-ajax.php'),
					'custom_list_ajax_nonce' => wp_create_nonce('seo-blm-ajax-custom-list-nonce'),
					'refresh_ajax_nonce' => wp_create_nonce('seo-blm-ajax-refresh-link-nonce'),
					'content' => __("Loading Links....", 'seo-backlink-monitor'),
					'imgsrc' => SEO_BLM_PLUGIN_URL . '/admin/img/loading.gif',
					'confirm_remove_title' => __("Remove Link!", 'seo-backlink-monitor'),
					'confirm_remove_message' => __("Are you sure you want to remove this link?", 'seo-backlink-monitor'),
					'confirm_remove_button' => __("Remove", 'seo-backlink-monitor'),
				)
			);
			wp_enqueue_script('st-jquery-confirm', SEO_BLM_PLUGIN_URL . '/admin/js/seo-backlink-monitor-jquery-confirm' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ) . '.js', array('jquery'), SEO_BLM_PLUGIN_VERSION, true);
		}
	}

	public function admin_enqueue_styles($hook)
	{

		if ($this->page_name === $hook) {
			wp_enqueue_style(
				'SEO_Backlink_Monitor_admin_styles',
				SEO_BLM_PLUGIN_URL . '/admin/css/seo-backlink-monitor-admin-style.css',
				array(),
				SEO_BLM_PLUGIN_VERSION,
				'all'
			);
			wp_enqueue_style(
				'st-admin-confirm-css',
				SEO_BLM_PLUGIN_URL . '/admin/css/seo-backlink-monitor-admin-confirm.css',
				array(),
				SEO_BLM_PLUGIN_VERSION,
				'screen'
			);
		}
	}

	public function seo_blm_admin_menu()
	{
		global $submenu;
		add_menu_page(
			__('SEO Backlink Monitor', 'seo-backlink-monitor'), // page_title
			__('SEO Backlink Monitor', 'seo-backlink-monitor'), // menu_title
			'manage_options', // capability
			SEO_BLM_PLUGIN, // menu_slug
			array($this, 'display_plugin_partial_page'), // callable fn
			'dashicons-admin-links' // icon
		);
	}

	public function session_read() {
		if ( !isset( $GLOBALS['SEO_Backlink_Monitor_session'] ) ) {
			$GLOBALS['SEO_Backlink_Monitor_session'] = get_option( SEO_BLM_OPTION_SESSION, [] );
		}
		return $GLOBALS['SEO_Backlink_Monitor_session'];
	}

	public function session_isset( $key = '' ) {
		if ( empty( $key ) ) {
			return false;
		}
		$session = $this->session_read();
		return isset( $session[ $key ] );
	}

	public function session_get( $key = '' ) {
		if ( empty( $key ) ) {
			return false;
		}
		$session = $this->session_read();
		if ( !isset( $session[ $key ] ) ) {
			return false;
		}
		return $session[ $key ];
	}

	public function session_set( $key = '', $value = '' ) {
		if ( empty( $key ) ) {
			return;
		}
		$session = $this->session_read();
		$session[ $key ] = $value;
		update_option( SEO_BLM_OPTION_SESSION, $session );
	}

	public function session_delete( $key = '' ) {
		if ( empty( $key ) ) {
			return;
		}
		$session = $this->session_read();
		if ( !isset( $session[ $key ] ) ) {
			return;
		}
		unset( $session[ $key ] );
		update_option( SEO_BLM_OPTION_SESSION, $session );
	}

	public function display_plugin_partial_page()
	{
		if (!isset($_GET['seo-backlink-monitor-refresh-all']) || !wp_verify_nonce($_GET['seo-backlink-monitor-refresh-all'], 'seo-backlink-monitor-refresh-all-nonce')) {
			$settings = get_option(SEO_BLM_OPTION_SETTINGS);
			if (!$settings) {
				$settings = $this->settings_default;
			}
			$linkData = isset($_REQUEST['edit']) ? SEO_Backlink_Monitor_Helper::get_link_by_id((int) $_REQUEST['edit']) : [];
			if ( $this->session_isset( 'edit-link' ) ) {
				$linkData = $this->session_get( 'edit-link' );
				$this->session_delete( 'edit-link' );
			}
			$multipleLinks = [];
			$editLink = isset($_REQUEST['edit']) && $linkData;
			$mode = $editLink ? 'edit' : 'add';
			$addLinkHasError = false;

			if ( $this->session_isset( 'add-link' ) ) {
				$linkData = $this->session_get( 'add-link' );
				$this->session_delete( 'add-link' );
				$addLinkHasError = true;
			} elseif ( $this->session_isset( 'add-multiple-links' ) ) {
				$links = $this->session_get( 'add-multiple-links' )['links'];
				$count_links = count( $links );
				if ( $count_links ) {
					$multipleLinks['links'] = implode("\r\n", $links);
				}
				$addLinkHasError = $count_links;
				$this->session_delete( 'add-multiple-links' );
			}

			include_once 'partials/seo-backlink-monitor.php';
		}
		else {
			echo "<h1>" . __('Checking All Links....', 'seo-backlink-monitor') . " <img src='". SEO_BLM_PLUGIN_URL . "/admin/img/loading.gif' /></h1>";
			$withMail = isset($_GET['with']) && $_GET['with'] === 'mail';
			if (!isset($_GET['action']) || $_GET['action'] !== 'run') {
				$url = admin_url('admin.php?page=seo-backlink-monitor&action=run&seo-backlink-monitor-refresh-all=' . $_GET['seo-backlink-monitor-refresh-all']);
				$timeout = 500;
			} else {
				$return = $this->seo_blm_refresh_links( $withMail );
				$status = $return ? 'success' : 'warning';
				$url = admin_url('admin.php?page=seo-backlink-monitor&msg=refresh-' . $status);
				$timeout = 0;
			}
			if ( $withMail ) {
				$url .= '&with=mail';
			}
			echo "<script>" .
					"setTimeout(function(){" .
						"window.location.replace('$url');" .
					"}, $timeout)" .
				"</script>";
		}
	}

	public function seo_blm_save_settings()
	{
		if (check_admin_referer('seo-backlink-monitor-save-settings', 'seo-backlink-monitor-save-settings-nonce') &&
			!empty($_POST)) {
			$status = 'success';
			if ( isset($_POST['notify-email']) && !empty($_POST['notify-email']) && ! is_email(sanitize_email( $_POST['notify-email'] )) ) {
				$status = 'email-error';
				unset($_POST['notify-email']);
			}

			$settings = [
				'cronFrequency' => isset($_POST['cron-frequency']) ? sanitize_text_field( $_POST['cron-frequency'] ) : $this->settings_default['cronFrequency'],
				'notifyEmail' => isset($_POST['notify-email']) ? sanitize_email( $_POST['notify-email'] ) : $this->settings_default['notifyEmail'],
				'dateFormat' => isset($_POST['date-format']) ? sanitize_text_field( $_POST['date-format'] ) : $this->settings_default['dateFormat'],
				'displayNotes' => isset($_POST['display-notes']) ? sanitize_text_field( $_POST['display-notes'] ) : $this->settings_default['displayNotes'],
				'resultItemsPerPage' => isset($_POST['result-items-per-page']) ? sanitize_text_field( $_POST['result-items-per-page'] ) : $this->settings_default['resultItemsPerPage']
			];

			update_option(SEO_BLM_OPTION_SETTINGS, $settings);
			wp_redirect(admin_url('admin.php?page=seo-backlink-monitor&msg=settings-' . $status));
		}
	}

	public function seo_blm_add_link()
	{
		if (check_admin_referer('seo-backlink-monitor-add-link', 'seo-backlink-monitor-add-link-nonce') &&
			!empty($_POST)) {

			$linkTo = (isset($_POST['link-to']) ? esc_url_raw($_POST['link-to']) : '');
			$linkFrom = (isset($_POST['link-from']) ? esc_url_raw($_POST['link-from']) : '');
			$notes = (isset($_POST['notes']) ? sanitize_textarea_field($_POST['notes']) : '');
			$isDuplicate = SEO_Backlink_Monitor_Helper::duplicate_check($linkTo, $linkFrom);
			if ( $linkTo === '' || $linkFrom === '' || $isDuplicate ) {
				$this->session_set( 'add-link', [
					'linkTo' => $linkTo,
					'linkFrom' => $linkFrom,
					'notes' => $notes
				] );
			}
			if ( $linkTo === '' || $linkFrom === '' ) {
				wp_redirect(admin_url('admin.php?page=seo-backlink-monitor&msg=error'));
				exit;
			}
			if ( $isDuplicate ) {
				wp_redirect(admin_url('admin.php?page=seo-backlink-monitor&msg=duplicate-warning'));
				exit;
			}

			$validateLink = SEO_Backlink_Monitor_Helper::link_validator($linkTo, $linkFrom);
			$validateLinkData = SEO_Backlink_Monitor_Helper::link_details($validateLink);
			$validateLinkMob = SEO_Backlink_Monitor_Helper::link_validator($linkTo, $linkFrom, true);
			$validateLinkMobData = SEO_Backlink_Monitor_Helper::link_details($validateLinkMob);
			$linkId = SEO_Backlink_Monitor_Helper::get_next_link_id();
			$timestamp = current_time( 'timestamp', 0 );

			$array_multi_array = [[
				'id' => $linkId,
				'date' => $timestamp,
				'dateRefresh' => $timestamp,
				'notes' => $notes,
				'linkTo' => $linkTo,
				'linkToHost' => wp_parse_url($linkTo)['host'],
				'linkFrom' => $linkFrom,
				'linkFromHost' => wp_parse_url($linkFrom)['host'],
				'anchorText' => $validateLinkData['text'],
				'follow' => $validateLinkData['follow'],
				'followOld' => $validateLinkData['follow'],
				'status' => $validateLinkData['status'],
				'statusOld' => $validateLinkData['status'],
				'followMob' => $validateLinkMobData['follow'],
				'followMobOld' => $validateLinkMobData['follow'],
			]];

			if ($updated_array = get_option(SEO_BLM_OPTION_LINKS)) {
				$array_multi_array = array_merge($updated_array, $array_multi_array);
			}
			update_option(SEO_BLM_OPTION_LINKS, $array_multi_array);
			wp_redirect(admin_url('admin.php?page=seo-backlink-monitor&msg=success'));
		}
	}

	public function seo_blm_add_multiple_links()
	{
		if (check_admin_referer('seo-backlink-monitor-add-multiple-links', 'seo-backlink-monitor-add-multiple-links-nonce') &&
			!empty($_POST)) {

				// remove any horizontal whitespace
				$multiLinks = isset($_POST['multiple-links']) ? preg_replace('/\h/', '', $_POST['multiple-links']) : false;

				// get next ID
				$linkId = SEO_Backlink_Monitor_Helper::get_next_link_id();

				if ($multiLinks) {
					$multiLinkErrors = [];
					$array_multi_array = [];

					$multiLinks = explode("\r\n", $multiLinks);
					foreach ($multiLinks as $linkToFrom) {
						if ( empty($linkToFrom) ) {
							continue;
						}
						if ( substr_count($linkToFrom, ';') !== 1 ) {
							$multiLinkErrors['semicolonCount'][] = $linkToFrom;
							continue;
						}

						list($linkTo, $linkFrom) = explode(';', $linkToFrom);
						if ( $linkTo === '' || $linkFrom === '' ) {
							$multiLinkErrors['missingLink'][] = $linkToFrom;
							continue;
						}
						if ( !filter_var($linkTo, FILTER_VALIDATE_URL) || !filter_var($linkFrom, FILTER_VALIDATE_URL) ) {
							$multiLinkErrors['invalidLink'][] = $linkToFrom;
							continue;
						}

						$linkTo = esc_url_raw($linkTo);
						$linkFrom = esc_url_raw($linkFrom);
						$isDuplicate = SEO_Backlink_Monitor_Helper::duplicate_check($linkTo, $linkFrom);
						if ( $isDuplicate ) {
							$multiLinkErrors['duplicate'][] = $linkToFrom;
							continue;
						}

						$validateLink = SEO_Backlink_Monitor_Helper::link_validator($linkTo, $linkFrom);
						$validateLinkData = SEO_Backlink_Monitor_Helper::link_details($validateLink);
						$validateLinkMob = SEO_Backlink_Monitor_Helper::link_validator($linkTo, $linkFrom, true);
						$validateLinkMobData = SEO_Backlink_Monitor_Helper::link_details($validateLinkMob);
						$timestamp = current_time( 'timestamp', 0 );

						$array_multi_array[] = [
							'id' => $linkId,
							'date' => $timestamp,
							'dateRefresh' => $timestamp,
							'notes' => '',
							'linkTo' => $linkTo,
							'linkToHost' => wp_parse_url($linkTo)['host'],
							'linkFrom' => $linkFrom,
							'linkFromHost' => wp_parse_url($linkFrom)['host'],
							'anchorText' => $validateLinkData['text'],
							'follow' => $validateLinkData['follow'],
							'followOld' => $validateLinkData['follow'],
							'status' => $validateLinkData['status'],
							'statusOld' => $validateLinkData['status'],
							'followMob' => $validateLinkMobData['follow'],
							'followMobOld' => $validateLinkMobData['follow'],
						];

						// increase for next
						$linkId++;
					}

					$msgWithError = false;
					if (count($multiLinkErrors)) {
						$update_session = [];
						$update_session['errors'] = $multiLinkErrors;
						foreach ($multiLinkErrors as $errorType => $multiLinks) {
							if ($errorType === 'duplicate') {
								continue;
							}
							foreach ($multiLinks as $multiLink) {
								$update_session['links'][] = $multiLink;
							}
						}
						$this->session_set( 'add-multiple-links', $update_session );
						$msgWithError = true;
					}
					if (count($array_multi_array)) {
						if ($updated_array = get_option(SEO_BLM_OPTION_LINKS)) {
							$array_multi_array = array_merge($updated_array, $array_multi_array);
						}
						update_option(SEO_BLM_OPTION_LINKS, $array_multi_array);
						wp_redirect(admin_url('admin.php?page=seo-backlink-monitor&msg=multi-success' . ($msgWithError ? '-with-errors' : '') ));
						exit;
					} else {
						wp_redirect(admin_url('admin.php?page=seo-backlink-monitor&msg=multi-info' . ($msgWithError ? '-with-errors' : '') ));
						exit;
					}
				} else {
					wp_redirect(admin_url('admin.php?page=seo-backlink-monitor&msg=multi-warning'));
					exit;
				}

		}
	}

	public function seo_blm_edit_link()
	{
		if (check_admin_referer('seo-backlink-monitor-edit-link', 'seo-backlink-monitor-edit-link-nonce') &&
			!empty($_POST)) {

			if( $links = get_option( SEO_BLM_OPTION_LINKS ) ) {
				$editId = intval( $_POST['edit_id'] );
				$editKey = false;
				$editData = false;
				foreach ( $links as $entryKey => $entry ) {
					if ($editId === (int) $entry['id'] ) {
						$editKey = $entryKey;
						$editData = $entry;
					}
				}

				if ( $editData ) {
					$linkTo = (isset($_POST['link-to']) ? esc_url_raw($_POST['link-to']) : '');
					$linkFrom = (isset($_POST['link-from']) ? esc_url_raw($_POST['link-from']) : '');
					$notes = (isset($_POST['notes']) ? sanitize_textarea_field($_POST['notes']) : '');
					$isDuplicate = SEO_Backlink_Monitor_Helper::duplicate_check($linkTo, $linkFrom, $editId);
					if ( $linkTo === '' || $linkFrom === '' || $isDuplicate ) {
						$this->session_set( 'edit-link', [
							'linkTo' => $linkTo,
							'linkFrom' => $linkFrom,
							'notes' => $notes,
							'id' => $editId
						] );
					}
					if ( $linkTo === '' || $linkFrom === '' ) {
						wp_redirect(admin_url('admin.php?page=seo-backlink-monitor&edit=' . $editId . '&msg=error'));
						exit;
					}
					if ( $isDuplicate ) {
						wp_redirect(admin_url('admin.php?page=seo-backlink-monitor&edit=' . $editId . '&msg=duplicate-warning'));
						exit;
					}

					$validateLink = SEO_Backlink_Monitor_Helper::link_validator($linkTo, $linkFrom);
					$validateLinkData = SEO_Backlink_Monitor_Helper::link_details($validateLink);
					$validateLinkMob = SEO_Backlink_Monitor_Helper::link_validator($linkTo, $linkFrom, true);
					$validateLinkMobData = SEO_Backlink_Monitor_Helper::link_details($validateLinkMob);
					$timestamp = current_time( 'timestamp', 0 );

					$edited_data = array_merge($editData, [
						'dateRefresh' => $timestamp,
						'notes' => $notes,
						'linkTo' => $linkTo,
						'linkToHost' => wp_parse_url($linkTo)['host'],
						'linkFrom' => $linkFrom,
						'linkFromHost' => wp_parse_url($linkFrom)['host'],
						'anchorText' => $validateLinkData['text'],
						'follow' => $validateLinkData['follow'],
						'followOld' => $editData['follow'],
						'status' => $validateLinkData['status'],
						'statusOld' => $editData['status'],
						'followMob' => $validateLinkMobData['follow'],
						'followMobOld' => $editData['followMob'],
					]);

					$links[$editKey] = $edited_data;

					update_option( SEO_BLM_OPTION_LINKS, $links );
					wp_redirect(admin_url('admin.php?page=seo-backlink-monitor&msg=edit-success'));
					exit;
				}
			}
		}
	}

	public function seo_blm_refresh_link_ajax()
	{
		check_ajax_referer('seo-blm-ajax-refresh-link-nonce', 'seo_blm_ajax_refresh_link_nonce');

		if( $links = get_option( SEO_BLM_OPTION_LINKS ) ) {
			if ( isset( $_REQUEST['refresh_id'] ) && ! empty( $_REQUEST['refresh_id'] ) ) {
				$refreshId = intval( $_REQUEST['refresh_id'] );
				$refreshKey = false;
				$refreshData = false;
				foreach ( $links as $entryKey => $entry ) {
					if ($refreshId === (int) $entry['id'] ) {
						$refreshKey = $entryKey;
						$refreshData = $entry;
					}
				}

				if ( $refreshData ) {
					$linkTo = $refreshData['linkTo'];
					$linkFrom = $refreshData['linkFrom'];
					$validateLink = SEO_Backlink_Monitor_Helper::link_validator($linkTo, $linkFrom);
					$validateLinkData = SEO_Backlink_Monitor_Helper::link_details($validateLink);
					$validateLinkMob = SEO_Backlink_Monitor_Helper::link_validator($linkTo, $linkFrom, true);
					$validateLinkMobData = SEO_Backlink_Monitor_Helper::link_details($validateLinkMob);
					$timestamp = current_time( 'timestamp', 0 );

					$refreshed_data = array_merge($refreshData, [
						'dateRefresh' => $timestamp,
						'anchorText' => $validateLinkData['text'],
						'follow' => $validateLinkData['follow'],
						'followOld' => $refreshData['follow'],
						'status' => $validateLinkData['status'],
						'statusOld' => $refreshData['status'],
						'followMob' => $validateLinkMobData['follow'],
						'followMobOld' => $refreshData['followMob'],
					]);

					$links[$refreshKey] = $refreshed_data;

					update_option( SEO_BLM_OPTION_LINKS, $links );

					// SEND DATA
					$send = [];
					$send['follow'] = SEO_Backlink_Monitor_Helper::return_formatted_by_type('follow', $refreshed_data['follow'], $refreshed_data) . SEO_Backlink_Monitor_Helper::return_formatted_by_type('followMob', $refreshed_data['followMob'], $refreshed_data);
					$send['status'] = SEO_Backlink_Monitor_Helper::return_formatted_by_type('status', $refreshed_data['status'], $refreshed_data);
					$send['dateRefresh'] = SEO_Backlink_Monitor_Helper::return_formatted_by_type('dateRefresh', $refreshed_data['dateRefresh'], $refreshed_data);
					$send['anchorText'] = SEO_Backlink_Monitor_Helper::return_combined_formatted_by_type('anchorText', $refreshed_data);

					wp_send_json($send);
				}
			}
		}

		// SEND ERROR
		wp_send_json_error();
	}

	public function seo_blm_refresh_links( $execute_job_with_mail = false )
	{
		global $links, $noStatus, $hasFollow, $noFollow;
		if( $links = get_option( SEO_BLM_OPTION_LINKS ) ) {

			$httpErrStatus = [];
			$errStatus = [];
			$noStatus = [];
			$hasFollow = [];
			$noFollow = [];
			$hasFollowMob = [];
			$noFollowMob = [];
			$retryLinks = [];

			# returns true on last try or successfull connection, or false on error and not last try
			function refresh_link( $entryKey, $entry, $maxTries = 1, $try = 1 ) {
				global $links, $errStatus, $noStatus, $hasFollow, $noFollow;

				$refreshKey = $entryKey;
				$refreshData = $entry;
				$refreshData['follow'] = (int) $refreshData['follow'];
				$refreshData['followOld'] = (int) $refreshData['followOld'];
				$refreshData['status'] = (int) $refreshData['status'];
				$refreshData['statusOld'] = (int) $refreshData['statusOld'];
				$refreshData['followMob'] = (int) $refreshData['followMob'];
				$refreshData['followMobOld'] = (int) $refreshData['followMobOld'];

				$linkTo = $refreshData['linkTo'];
				$linkFrom = $refreshData['linkFrom'];
				$validateLink = SEO_Backlink_Monitor_Helper::link_validator($linkTo, $linkFrom);

				if ( $maxTries > 1 && $try < $maxTries && !$validateLink ) {
					return false;
				}

				$validateLinkData = SEO_Backlink_Monitor_Helper::link_details($validateLink);
				$validateLinkMob = SEO_Backlink_Monitor_Helper::link_validator($linkTo, $linkFrom, true);
				$validateLinkMobData = SEO_Backlink_Monitor_Helper::link_details($validateLinkMob);
				$timestamp = current_time( 'timestamp', 0 );

				$status_changed = $refreshData['status'] !== $validateLinkData['status'] || $refreshData['follow'] !== $validateLinkData['follow'] || $refreshData['followMob'] !== $validateLinkMobData['follow'];

				$refreshed_data = array_merge($refreshData, [
					'dateRefresh' => $timestamp,
					'anchorText' => $validateLinkData['text'],
					'follow' => $validateLinkData['follow'],
					'followOld' => $refreshData['follow'],
					'status' => $validateLinkData['status'],
					'statusOld' => $refreshData['status'],
					'followMob' => $validateLinkMobData['follow'],
					'followMobOld' => $refreshData['followMob'],
				]);

				$links[$refreshKey] = $refreshed_data;

				if ( $status_changed ) {
					if ( $validateLinkData['status'] > 2 ) {
						$httpErrStatus[] = $refreshed_data;
					} elseif ( $validateLinkData['status'] === 2 ) {
						$errStatus[] = $refreshed_data;
					} elseif ( $validateLinkData['status'] === 0 ) {
						$noStatus[] = $refreshed_data;
					} else {
						if ( $validateLinkData['follow'] === 1 ) {
							$hasFollow[] = $refreshed_data;
						} elseif ( $validateLinkData['follow'] === 0 ) {
							$noFollow[] = $refreshed_data;
						}
						if ( $validateLinkMobData['follow'] === 1 ) {
							$hasFollowMob[] = $refreshed_data;
						} elseif ( $validateLinkMobData['follow'] === 0 ) {
							$noFollowMob[] = $refreshed_data;
						}
					}
				}
				return true;
			}

			# Refresh links, add to retryLinks for repeat.
			foreach ( $links as $entryKey => $entry ) {
				$refresh_link_success = refresh_link($entryKey, $entry, $this->refresh_link_tries, 1);
				if ( ! $refresh_link_success ) {
					$retryLinks[$entryKey] = $entry;
				}
			}
			# Retry the failed links.
			$retryLinksCount = $this->refresh_link_tries > 1 ? count($retryLinks) : 0;
			if ( $retryLinksCount > 0 ) {
				for ( $try = 2; $try <= $this->refresh_link_tries; $try++ ) {
					foreach ( $retryLinks as $entryKey => $entry ) {
						$refresh_link_success = refresh_link($entryKey, $entry, $this->refresh_link_tries, $try);
						if ( $refresh_link_success ) {
							unset( $retryLinks[$entryKey] );
						}
					}
				}
			}

			update_option( SEO_BLM_OPTION_LINKS, $links );

			$settings = get_option( SEO_BLM_OPTION_SETTINGS );

			if ( $execute_job_with_mail &&
				$settings && $settings['notifyEmail'] !== '' && is_email( $settings['notifyEmail'] ) &&
				( !empty($errStatus) || !empty($noStatus) || !empty($noFollow) || !empty($hasFollow) )
			) {
				$to = $settings['notifyEmail'];
				$subject = get_option( 'blogname' ) . ' // ' . SEO_BLM_PLUGIN_NAME . ' // ' . __('Check All Links – Report', 'seo-backlink-monitor');
				$headers = array('Content-Type: text/plain; charset=UTF-8');
				$body = '';
				$body .= SEO_BLM_PLUGIN_NAME . ' // ' . __('Check All Links – Report', 'seo-backlink-monitor') . "\n";
				$body .= __('Number of Links Total', 'seo-backlink-monitor') . ': ' . count($links) . "\n" . "\n";
				// $body .= __('Number of SERVER DOWN', 'seo-backlink-monitor') . ': ' . count($noStatus) . "\n";
				// $body .= __('Number of NO FOLLOW', 'seo-backlink-monitor') . ': ' . count($noFollow) . "\n" . "\n";

				function output_entry($entry, $mobile = false, $httpStatus = false) {
					$ret = '';
					$ret .= __('Link To', 'seo-backlink-monitor') . ':   ' . $entry['linkTo'] . "\n";
					$ret .= __('Link From', 'seo-backlink-monitor') . ': ' . $entry['linkFrom'] . "\n";
					$ret .= __('Date', 'seo-backlink-monitor') . ': ' . SEO_Backlink_Monitor_Helper::return_formatted_by_type('date', $entry['date']) . "\n";
					if ($httpStatus) {
						$ret .= __('HTTP Status', 'seo-backlink-monitor') . ': ' . $entry['status'] . "\n";
					}
					if ($mobile) {
						$ret .= __('(i) checked with mobile user agent', 'seo-backlink-monitor') . "\n";
					} else {
						$ret .= __('(i) checked with regular wordpress user agent', 'seo-backlink-monitor') . "\n";
					}
					$ret .= '– – – – – – –';
					return $ret;
				}
				if (count($errStatus) > 0) {
					$body .= __('SERVER DOWN', 'seo-backlink-monitor') . "\n";
					$body .=    '– – – – – – – – – – – – – – – – – – – – –' . "\n";
					foreach ( $errStatus as $entry ) {
						$body .= output_entry($entry) . "\n";
					}
					$body .= "\n";
				}
				if (count($httpErrStatus) > 0) {
					$body .= __('HTTP STATUS :-(', 'seo-backlink-monitor') . "\n";
					$body .=    '– – – – – – – – – – – – – – – – – – – – –' . "\n";
					foreach ( $httpErrStatus as $entry ) {
						$body .= output_entry($entry, false, true) . "\n";
					}
					$body .= "\n";
				}
				if (count($noStatus) > 0) {
					$body .= __('LINK NOT FOUND :-(', 'seo-backlink-monitor') . "\n";
					$body .=    '– – – – – – – – – – – – – – – – – – – – –' . "\n";
					foreach ( $noStatus as $entry ) {
						$body .= output_entry($entry) . "\n";
					}
					$body .= "\n";
				}
				if (count($hasFollow) > 0 || count($hasFollowMob) > 0) {
					$body .= __('FOLLOW :-)', 'seo-backlink-monitor') . "\n";
					$body .=    '– – – – – – – – – – – – – – – – – – – – –' . "\n";
					foreach ( $hasFollow as $entry ) {
						$body .= output_entry($entry) . "\n";
					}
					foreach ( $hasFollowMob as $entry ) {
						$body .= output_entry($entry, true) . "\n";
					}
					$body .= "\n";
				}
				if (count($noFollow) > 0 || count($noFollowMob) > 0) {
					$body .= __('NO FOLLOW :-(', 'seo-backlink-monitor') . "\n";
					$body .=    '– – – – – – – – – – – – – – – – – – – – –' . "\n";
					foreach ( $noFollow as $entry ) {
						$body .= output_entry($entry) . "\n";
					}
					foreach ( $noFollowMob as $entry ) {
						$body .= output_entry($entry, true) . "\n";
					}
				}
				if ($retryLinksCount > 0) {
					$body .= "\n";
					$body .= "\n";
					$body .= __('Maximum number of attempts to check a link', 'seo-backlink-monitor') . ': ' . $this->refresh_link_tries . "\n";
					$body .= __('Links that had to be re-checked', 'seo-backlink-monitor') . ': ' . $retryLinksCount . "\n";
				}
				return wp_mail( $to, $subject, $body, $headers );
			} else {
				return true;
			}
		}
		return true;
	}

	public function seo_blm_list_table_ajax()
	{
		$wp_list_table = new SEO_Backlink_Monitor_Child_WP_List_Table(SEO_BLM_PLUGIN);
		$wp_list_table->ajax_response();
	}

	public function seo_blm_cron_activation_hook()
	{
		$settings = get_option(SEO_BLM_OPTION_SETTINGS);
		$cron_timestamp = wp_next_scheduled( SEO_BLM_CRON );
		$cron_schedule = wp_get_schedule( SEO_BLM_CRON );
		if ($cron_timestamp && (!$settings || $settings['cronFrequency'] === '')) {
			seo_backlink_monitor_cron_deactivate();
		}
		elseif ($cron_timestamp && $settings && $settings['cronFrequency'] !== '' && $settings['cronFrequency'] !== $cron_schedule) {
			wp_clear_scheduled_hook( SEO_BLM_CRON );
			wp_schedule_event( time(), $settings['cronFrequency'], SEO_BLM_CRON );
		}
		elseif (!$cron_timestamp && $settings && $settings['cronFrequency'] !== '') {
			wp_schedule_event( time(), $settings['cronFrequency'], SEO_BLM_CRON );
		}
	}

	public function seo_blm_cron_cb()
	{
		$this->seo_blm_refresh_links( $execute_job_with_mail = true );
	}
}
