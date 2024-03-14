<?php
/**
 *  Display the content on the plugin settings page
 */

if ( ! class_exists( 'Gglnltcs_Settings_Tabs' ) ) {
	class Gglnltcs_Settings_Tabs extends Bws_Settings_Tabs {
	    private $analytics, $curl_enabled, $client;

		/**
		 *  Constructor
		 *
		 * @access public
		 *
		 * @see Bws_Settings_Tabs::__constructor() for more information in default arguments.
		 *
		 * @param string $plugin_basename
		 */

		public function __construct( $plugins_basename ) {
			global $gglnltcs_options, $gglnltcs_plugin_info;

			$tabs = array(
				'settings'    => array( 'label' => __( 'Settings', 'bws-google-analytics' ) ),
				'statistics'  => array( 'label' => __( 'Statistics', 'bws-google-analytics' ) ),
				'misc'        => array( 'label' => __( 'Misc', 'bws-google-analytics' ) ),
				'custom_code' => array( 'label' => __( 'Custom Code', 'bws-google-analytics' ) ),
				'license'     => array( 'label' => __( 'Licence Key', 'bws-google-analytics' ) )
			);

			parent::__construct( array(
				'plugin_basename' 	 => $plugins_basename,
				'plugins_info'		 => $gglnltcs_plugin_info,
				'prefix' 			 => 'gglnltcs',
				'default_options' 	 => gglnltcs_default_options(),
				'options' 			 => $gglnltcs_options,
				'is_network_options' => is_network_admin(),
				'tabs' 				 => $tabs,
				'wp_slug'			 => 'bws-google-analytics',
                'link_key'          => '0ceb29947727cb6b38a01b29102661a3',
                'link_pn'           => '125',
                'doc_link'          => 'https://docs.google.com/document/d/1crUDzT-SASTmoj3M6lJcR4CyRzCp9Ge1l2-BcsUotZY/'
			) );

			$this->analytics = gglnltcs_get_analytics();
			$this->curl_enabled = function_exists( 'curl_init' );
			$this->client = gglnltcs_get_client();

			add_action( get_parent_class( $this ) . '_display_custom_messages', array( $this, 'display_custom_messages' ) );
		}

		/**
         * Display custom error\message\notice
         * @access public
         * @param  $save_results - array with error\message\notice
         * @return void
         */
        public function display_custom_messages( $save_results ) {
            if ( empty( $this->options['tracking_id'] ) ) { ?>
                <div class="error inline">
                    <p><?php _e( 'To enable tracking and collect statistic from your site please enter Tracking ID.' , 'bws-google-analytics' ); ?></p>
                </div>
            <?php } ?>
            <noscript>
                <div class="error below-h2"><p><strong><?php _e( "Please enable JavaScript in your browser.", 'bws-google-analytics' ); ?></strong></p></div>
            </noscript>
        <?php }

		/**
		 *  Save plugin options to the database
		 * @access public
		 * @param void
		 * @return array   The action results
		 */
		public function save_options() {

            $message = $notice = $error = '';           

            $this->options['tracking_id']   = sanitize_text_field( $_POST['gglnltcs_tracking_id'] );
            $this->options['client_id']     = sanitize_text_field( $_POST['gglnltcs_client_id'] );
            $this->options['client_secret'] = sanitize_text_field( $_POST['gglnltcs_client_secret'] );
            $this->options['api_key']       = sanitize_text_field( $_POST['gglnltcs_api_key'] );

            // saving code user gave us for authentication
            if ( isset( $_POST['gglnltcs_code'] ) ) {
                $this->options['code'] = sanitize_text_field( $_POST['gglnltcs_code'] );
            }
			if ( isset( $_POST['gglnltcs_log_out'] ) ) {
			    unset( $this->options['token'], $this->options['settings'], $this->options['code'] );
            }
			update_option( 'gglnltcs_options', $this->options );
			$message = __( "Settings saved", 'bws-google-analytics' );

			return compact( 'message', 'notice', 'error' );
		}

		public function tab_settings() { ?>
            <h3 class="bws_tab_label"><?php _e( 'Analytics Settings', 'bws-google-analytics' ); ?></h3>
			<?php $this->help_phrase(); ?>
            <hr>
            <div class="bws_tab_sub_label"><?php _e( 'Authentication', 'bws-google-analytics' ); ?></div>
            <table class="form-table gglnltcs">
                <tr>
                    <th><?php _e( 'Tracking ID', 'bws-google-analytics' ); ?></th>
                    <td>
                        <input type="text" name="gglnltcs_tracking_id" value="<?php echo $this->options['tracking_id']; ?>" /><br />
                        <span class="bws_info"><?php _e( 'Want to know how to add your tracking ID?', 'bws-google-analytics' ); ?> <a href="https://support.bestwebsoft.com/hc/en-us/articles/202352589"><?php _e( 'Learn More', 'bws-google-analytics' ); ?></a></span>
                    </td>
                </tr>
                <tr>
                    <th><?php _e( 'Client ID', 'bws-google-analytics' ); ?></th>
                    <td>
                        <input<?php echo $this->change_permission_attr; ?> type="text" name="gglnltcs_client_id" value="<?php echo $this->options['client_id']; ?>" /><br />
                        <span class="bws_info"><?php _e( 'Want to know how to get client ID and client secret?', 'bws-google-analytics' ); ?> <a href="https://support.bestwebsoft.com/hc/en-us/articles/360038954252"><?php _e( 'Learn More', 'bws-google-analytics' ); ?></a></span>
                    </td>
                </tr>
                <tr>
                    <th><?php _e( 'Client Secret', 'bws-google-analytics' ); ?></th>
                    <td>
                        <input<?php echo $this->change_permission_attr; ?> type="text" name="gglnltcs_client_secret" value="<?php echo $this->options['client_secret']; ?>" />
                    </td>
                </tr>
                <tr>
                    <th><?php _e( 'API Key (optinal)', 'bws-google-analytics' ); ?></th>
                    <td>
                        <input<?php echo $this->change_permission_attr; ?> type="text" maxlength="100" name="gglnltcs_api_key" value="<?php echo $this->options['api_key']; ?>" />
                    </td>
                </tr>
            </table>
		<?php }

		public function tab_statistics() { ?>
            <h3 class="bws_tab_label"><?php _e( 'Analytics Statistics', 'bws-google-analytics' ); ?></h3>
			<?php $this->help_phrase(); ?>
            <hr>
			<?php $form_loaded = false;
			$redirect = '';
			if ( empty( $this->options['token'] ) ) {
				if ( ! empty( $this->options['code'] ) ) {
					// If user submit form
					if ( $this->curl_enabled ) {
						if ( isset( $this->options['code'] ) ) {
							if ( empty( $this->options['code'] ) ) {
								$redirect = false;
							} else {
								try {
									$this->client->authenticate( $this->options['code'] );
									$redirect = true;
								} catch ( Google_auth_exception $e ) {
								    $error = '<div class="error"><strong><p> ' .
                                             __( 'Warning: ', 'bws-google-analytics' ) .
                                             '</strong>' .  __( 'Authentication Token expired. Authenticate with your Google Account once again.', 'bws-google-analytics' ) .
                                    '</p></div>';
								    echo $error;
									$redirect = false;
								}
								if ( $redirect ) {
									$this->options['token'] = $this->client->getAccessToken();
									update_option( 'gglnltcs_options', $this->options );
								}
								if ( ! empty( $error ) ) { ?>
                                    <table class="form-table gglnltcs" id="gglnltcs-log-out-field">
                                        <tr>
                                            <th><?php _e( 'Deauthorize', 'bws-google-analytics' ); ?></th>
                                            <td>
                                                <input type="submit" name="gglnltcs_log_out" class="button-secondary" value="<?php _e( 'Log Out', 'bws-google-analytics' ); ?>">
                                            </td>
                                        </tr>
                                    </table>
                                <?php } else { ?>
                                    <div class="gglnltcs-text-information">
                                        <input id="gglnltcs-get-statistics" type="submit" class="button-secondary" value="<?php _e( 'Get Statistic', 'bws-google-analytics' ); ?>" onClick="window.location.reload()">
                                    </div>
                                <?php }
							}
						}
					}
				} elseif ( ! isset( $_POST['gglnltcs_code'] ) ) {
				    $form_loaded = true;
		            /*The post['gglnltcs_code'] has not been passed yet, so let us offer the user to enter the Google Authentication Code.
					 * First we need to redirect user to the Google Authorization page.
					 * For this reason we create an URL to obtain user authorization. */
		            if ( $this->curl_enabled ) {
			            $authUrl  = $this->client->createAuthUrl();
			            $disabled = '';
		            } else {
			            $authUrl  = '#';
			            $disabled = ' disabled="disabled"';
		            }
		            if ( isset( $_POST['gglnltcs_code'] ) && false === $redirect ) { ?>
                        <div class="error">
                            <p><?php _e( 'Invalid code. Please, try again.', 'bws-google-analytics' ); ?></p>
                        </div>
		            <?php } ?>
                    <div class="gglnltcs-text-information">
                        <p><?php _e( "In order to use Analytics by BestWebSoft plugin, you must be signed in with a registered Google Account email address and password. If you don't have Google Account you can create it", 'bws-google-analytics' ); ?>
                            <a href="https://www.google.com/accounts/NewAccount"
                               target="_blank"><?php _e( 'here', 'bws-google-analytics' ); ?>.</a></p>
                        <input id="gglnltcs-google-sign-in" type="button" class="button-secondary"
                               onclick="window.open( '<?php echo $authUrl; ?>', 'activate','width=640, height=480, menubar=0, status=0, location=0, toolbar=0' )"
                               value="<?php _e( 'Authenticate with your Google Account', 'bws-google-analytics' ); ?>"<?php echo $disabled; ?>>
                        <noscript>
                            <div class="button-primary gglnltcs-google-sign-in">
                                <a href="<?php echo $authUrl; ?>"
                                   target="_blanket"><?php _e( 'Or Click Here If You Have Disabled Javascript', 'bws-google-analytics' ); ?></a>
                            </div>
                        </noscript>
                        <p class="gglnltcs-authentication-instructions"><?php _e( 'When you finish authorization process you will get Google Authentication Code. You must enter this code in the field below and press "Save Changes" button. This code will be used to get an Authentication Token so you can access your website stats.', 'bws-google-analytics' ); ?></p>
                            <p><input id="gglnltcs-authentication-code-input" type="text" name="gglnltcs_code" <?php echo $disabled; ?>>
                            </p>
                    </div>
	            <?php }
            } else {
	            // functionality for showing main table on statistics tab
	            try {
		            $settings = isset( $this->options['settings'] ) ? $this->options['settings'] : '';
		            /* Load metrics data */
		            $metrics_data = gglnltcs_load_metrics();
		            $output     = '';
		            $accounts   = $this->analytics->management_accounts->listManagementAccounts();
		            $items      = $accounts->getItems();
		            $default_id = preg_replace( '/(UA-)(\d+)(-\d+)/i', '${2}', $this->options['tracking_id'] );
		            if ( count( $items ) ) {
			            foreach ( $items as $account ) {
				            $name     = $account->getName();
				            $id       = $account->getId();
				            $selected = '';
				            if ( ( isset( $settings['gglnltcs_accounts'] ) && $settings['gglnltcs_accounts'] == $name ) || $default_id == $id ) {
					            $selected = ' selected="selected"';
				            }
				            $output .= "<option{$selected} value=\"{$id}\">{$name}</option>";
				            $profile_accounts[ $id ]['name'] = $name;
				            $accounts_id[] = $id;
			            }
                        $start_date = empty( $settings['gglnltcs_start_date'] ) ? date( 'Y-m-d', strtotime( "-1 year" ) ) : $settings['gglnltcs_start_date'];
                        $end_date   = empty( $settings['gglnltcs_end_date'] ) ? date( 'Y-m-d', time() ) : $settings['gglnltcs_end_date'];
			            /* Main Form */ ?>
                            <table class="form-table gglnltcs">
                                <tr>
                                    <th><?php _e( 'Accounts', 'bws-google-analytics' ); ?></th>
                                    <td>
                                        <select id="gglnltcs-accounts" class="gglnltcs-select" name="gglnltcs_accounts">
								            <?php echo $output; ?>
                                        </select>
                                    </td>
                                </tr>
					            <?php $this->print_webproperties( $profile_accounts, $accounts_id, $settings ); ?>
                            </table>
				            <?php
			                if ( ! $this->hide_pro_tabs ) { ?>
                                <div class="bws_pro_version_bloc">
                                    <div class="bws_pro_version_table_bloc">
                                        <button type="submit" name="bws_hide_premium_options" class="notice-dismiss bws_hide_premium_options" title="<?php _e( 'Close', 'bws-google-analytics' ); ?>"></button>
                                        <div class="bws_table_bg"></div>
    						            <table class="form-table gglnltcs bws_pro_version">
                                            <tr>
                                                <th><?php _e( 'Reporting', 'bws-google-analytics' ); ?></th>
                                                <td><!-- Reporting -->
                                                    <select disabled="disabled" multiple="multiple" size="2" style="height: 50px;width: 150px;">
                                                        <option><?php _e( 'Visits', 'bws-google-analytics' ); ?></option>
                                                        <option><?php _e( 'Goals', 'bws-google-analytics' ); ?></option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
    					            <?php $this->bws_pro_block_links(); ?>
                                </div>
			                <?php } ?>
                            <table  class="form-table gglnltcs">
                                <tr id="gglnltcs-metrics">
                                    <th><?php _e( 'Metrics', 'bws-google-analytics' ); ?></th>
                                    <td>
                                        <?php $curr_category = '';
                                        foreach ( $metrics_data as $item ) {                            
                                            if ( $curr_category != $item['category'] ) {
                                                echo '<hr><strong>' . $item['category'] . '</strong><hr>';
                                                $curr_category = $item['category'];
                                            } /* Build checkboxes for metrics options. */
                                            echo '<p><input id="' . $item['id'] . '" class="gglnltcs_metrics_checkbox" name="' . $item['name'] . '" type="checkbox" value="' . $item['value'] .'"';
                                            if ( isset( $settings[ $item['name'] ] ) || ( ! $settings && 'gglnltcs-ga-users' == $item['name'] ) ) {
                                                echo ' checked = "checked">';
                                            } else {
                                                echo '>';
                                            }
                                            echo '<label title="' . $item['title'] . '" for="' . $item['for'] . '"> ' . $item['label'] . '</label></p>';
                                        } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php _e( 'Time Range', 'bws-google-analytics' ); ?></th>
                                    <td>
                                        <label for="gglnltcs-start-date" class="gglnltcs-date">
								            <?php _e( 'From', 'bws-google-analytics' ); ?>&nbsp;
                                            <input id="gglnltcs-start-date" class="gglnltcs_to_disable" size="4" name="gglnltcs_start_date" type="text" value="<?php echo $start_date; ?>" />
                                        </label>&nbsp;
                                        <label for="gglnltcs-end-date" class="gglnltcs-date">
								            <?php _e( 'to', 'bws-google-analytics' ); ?>&nbsp;
                                            <input id="gglnltcs-end-date" class="gglnltcs_to_disable" size="4" name="gglnltcs_end_date" type="text" value="<?php echo $end_date; ?>" />
                                        </label>
							            <?php echo bws_add_help_box(
								            sprintf( __( 'Date values must match the pattern %s.', 'bws-google-analytics' ), 'YYYY-MM-DD' ) .
								            '<br/>' .
								            __( 'The gap between dates must not be more than 999 days.', 'bws-google-analytics' )
							            ); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php _e( 'View Mode', 'bws-google-analytics' ); ?></th>
                                    <td>
                                        <fieldset>
                                            <label for="gglnltcs-chart-mode">
                                                <input type="radio" id="gglnltcs-chart-mode" class="gglnltcs_to_disable" name="gglnltcs_view_mode" value="chart"<?php if ( ! isset( $settings['gglnltcs_view_mode'] ) || 'chart' == $settings['gglnltcs_view_mode'] ) echo ' checked="checked"'; ?>/>
    								            <?php _e( 'Line chart', 'bws-google-analytics' ); ?>
                                            </label>
                                            <br/>
                                            <label for="gglnltcs-table-mode">
                                                <input type="radio" id="gglnltcs-table-mode" class="gglnltcs_to_disable" name="gglnltcs_view_mode" value="table"<?php if ( isset( $settings['gglnltcs_view_mode'] ) && 'table' == $settings['gglnltcs_view_mode'] ) echo ' checked="checked"'; ?>/>
    								            <?php _e( 'Table', 'bws-google-analytics' ); ?>
                                            </label>
                                        </fieldset>
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td>
                                        <input id="gglnltcs-get-statistics-button" type="submit" class="button-secondary" value="<?php _e( 'Get Statistic', 'bws-google-analytics' ); ?>">
                                    </td>
                                </tr>
                            </table>
			            <?php if ( isset( $settings['gglnltcs_view_mode'] ) && 'table' == $settings['gglnltcs_view_mode'] ) {
				            gglnltcs_get_statistic( $this->analytics, $settings, $metrics_data );
			            } else { ?>
                            <div id="gglnltcs-results-wrapper">
                                <div id="gglnltcs-chart"></div>
                            </div>
			            <?php }
		            }
	            } catch ( Google_Service_Exception $e ) {
		            echo __( 'There was an API error', 'bws-google-analytics' ) . ': ' . $e->getCode() . ' : ' . $e->getMessage();
	            } catch ( Exception $e ) {
		            $error = '<div class="error"><strong><p> ' .
		                     __( 'Warning: ', 'bws-google-analytics' ) .
		                     '</strong>' . __( 'Authentication Token expired. Authenticate with your Google Account once again.', 'bws-google-analytics' ) .
		                     '</p></div>';
		            echo $error;
	            }
	            if ( ! empty( $this->options['token'] ) ) { ?>
                    <table class="form-table gglnltcs" id="gglnltcs-log-out-field">
                        <tr>
                            <th><?php _e( 'Deauthorize', 'bws-google-analytics' ); ?></th>
                            <td>
                                <input type="submit" name="gglnltcs_log_out" class="button-secondary" value="<?php _e( 'Log Out', 'bws-google-analytics' ); ?>">
                            </td>
                        </tr>
                    </table>
	            <?php }
            }
            // functionality to show form when its wasn't shown due to unknown error
			if ( ! $form_loaded && empty( $this->options['code'] ) && empty( $this->options['token'] ) ) {
				if ( $this->curl_enabled ) {
					$authUrl  = $this->client->createAuthUrl();
					$disabled = '';
				} else {
					$authUrl  = '#';
					$disabled = ' disabled="disabled"';
				}
				if ( isset( $_POST['gglnltcs_code'] ) && false === $redirect ) { ?>
                    <div class="error">
                        <p><?php _e( 'Invalid code. Please, try again.', 'bws-google-analytics' ); ?></p>
                    </div>
				<?php } ?>
                <div class="gglnltcs-text-information">
                    <p><?php _e( "In order to use Analytics by BestWebSoft plugin, you must be signed in with a registered Google Account email address and password. If you don't have Google Account you can create it", 'bws-google-analytics' ); ?>
                        <a href="https://www.google.com/accounts/NewAccount"
                           target="_blank"><?php _e( 'here', 'bws-google-analytics' ); ?>.</a></p>
                    <input id="gglnltcs-google-sign-in" type="button" class="button-primary"
                           onclick="window.open( '<?php echo $authUrl; ?>', 'activate','width=640, height=480, menubar=0, status=0, location=0, toolbar=0' )"
                           value="<?php _e( 'Authenticate with your Google Account', 'bws-google-analytics' ); ?>"<?php echo $disabled; ?>>
                    <noscript>
                        <div class="button-primary gglnltcs-google-sign-in">
                            <a href="<?php echo $authUrl; ?>"
                               target="_blanket"><?php _e( 'Or Click Here If You Have Disabled Javascript', 'bws-google-analytics' ); ?></a>
                        </div>
                    </noscript>
                    <p class="gglnltcs-authentication-instructions"><?php _e( 'When you finish authorization process you will get Google Authentication Code. You must enter this code in the field below and press "Save Changes" button. This code will be used to get an Authentication Token so you can access your website stats.', 'bws-google-analytics' ); ?></p>
                    <p><input id="gglnltcs-authentication-code-input" type="text" name="gglnltcs_code" <?php echo $disabled; ?>>
                    </p>
                </div>
            <?php }
		}

        /* Prints Webproperties List */
        private function print_webproperties( $profile_accounts, $accounts_id, $settings ) {
            $profile_webproperties = array();
            /* Web Properties: list
             * https://developers.google.com/analytics/devguides/config/mgmt/v3/mgmtReference/management/webproperties/list */
            if ( !empty( $this->analytics ) ) {
                try {
                    $output = '';
                    $webproperties = $this->analytics->management_webproperties->listManagementWebproperties( '~all' );

                    $items = $webproperties->getItems();
                    if ( ! count( $items ) ) {
                        return false;
                    }

                    $selected_account = $output = $selected = '';

                    foreach ( $items as $webproperty ) {
                        $account_id  = $webproperty->getAccountId();
                        $property_id = $webproperty->getId();

                        $profiles = $this->analytics->management_profiles->listManagementProfiles( $account_id, $property_id );
                        $profiles = $profiles->getItems();

                        if ( ! count( $profiles ) ) {
                            continue;
                        }
                        if ( ! $selected_account && ( ! $this->options['tracking_id'] || ( $this->options['tracking_id'] && $this->options['tracking_id'] == $property_id ) ) ) {
                            $selected_account = $account_id;
                        }
                        $profile_accounts[ $account_id ]['webproperties'][ $property_id ]['name'] = $webproperty->getName();

                        foreach ( $profiles as $profile ) {
                            $profile_accounts[ $account_id ]['webproperties'][ $property_id ]['profiles'][ $profile->getId() ] = $profile->getName();
                        }
                    }
                    /* if tracking ID has not been found in the list of accounts, the first account`s data will be displayed */
                    $current_account = array_key_exists( $selected_account, $profile_accounts ) ? $profile_accounts[ $selected_account ] : reset( $profile_accounts );
                    foreach ( $current_account['webproperties'] as $property_id => $property ) {
                        $allowed = true;
                        foreach ( $property['profiles'] as $profile_id => $profile_name ) {
                            if ( $allowed && $this->options['tracking_id'] === $property_id ) {
                                $selected = ' selected="selected"';
                                $allowed  = false;
                            } else {
                                $selected = '';
                            }
                            $output .= "<option{$selected} value=\"ga:{$profile_id}\">{$property['name']} ( {$profile_name} )</option>";
                        }
                    } ?>
                    <tr>
                        <th><?php _e( 'Webproperties', 'bws-google-analytics' ); ?></th>
                        <td>
                            <select id="gglnltcs-webproperties" class="gglnltcs-select" name="gglnltcs_webproperties">
                                <?php echo $output; ?>
                            </select>
                        </td>
                    </tr>
                    <?php $script = "var profileAccounts = " . json_encode( $profile_accounts ) . ";";
                    wp_register_script( 'gglnltcs-profileAccounts', '', array(), null, true );
                    wp_enqueue_script( 'gglnltcs-profileAccounts' );
                    wp_add_inline_script( 'gglnltcs-profileAccounts', sprintf( $script ) );

                } catch ( Google_Service_Exception $e ) {
                    echo __( 'There was an Analytics API service error', 'bws-google-analytics' ) . ' ' . $e->getCode() . ':' . $e->getMessage();
                } catch ( Exception $e ) {
                    echo __( 'There was a general API error', 'bws-google-analytics' ) . ' ' . $e->getCode() . ':' . $e->getMessage();
                }
            }
        }
    }
}