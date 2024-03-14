<?php

class Sarbacane {

	public static function activation() {
		update_option( 'sarbacane_version', '1.4.9', false );
		update_option( 'sarbacane_sd_token', '', false );
		update_option( 'sarbacane_sd_id_list', array(), false );
		update_option( 'sarbacane_news_registration_button', __( 'Inscription', 'sarbacane-desktop' ) );

		global $wpdb;
		$sql = "DROP TABLE IF EXISTS `{$wpdb->prefix}sd_updates`";
		$wpdb->query( $sql );
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}sd_updates` (
			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
			`time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			`user_id` int(9) DEFAULT NULL,
			`user_email` varchar(255) DEFAULT NULL,
			`action` text NOT NULL,
			UNIQUE KEY `id` (`id`)
		) {$charset_collate};";
		$wpdb->query( $sql );
		//$sql = "DROP TABLE IF EXISTS `{$wpdb->prefix}sd_subscribers`";
		//$wpdb->query( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}sd_subscribers` (
			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
			`email` varchar(255) DEFAULT NULL,
			`columns` mediumtext DEFAULT NULL,
			`registration_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			UNIQUE KEY `id` (`id`)
		) {$charset_collate};";
		$wpdb->query( $sql );
	}

	public static function deactivation() {
		delete_option( 'sarbacane_version' );
		delete_option( 'sarbacane_sd_token' );
		$sd_ids_saved = get_option( 'sarbacane_sd_id_list', array() );
		foreach ( $sd_ids_saved as $sd_id_saved ) {
			delete_option( 'sarbacane_user_call_' . $sd_id_saved );
			delete_option( 'sarbacane_news_call_' . $sd_id_saved );
		}
		delete_option( 'sarbacane_sd_id_list' );
		delete_option( 'sarbacane_news_list' );
		delete_option( 'sarbacane_users_list' );
		delete_option( 'sarbacane_failed' );
		delete_option( 'sarbacane_theme_sync' );
		delete_option( 'sarbacane_blog_content' );
		delete_option( 'sarbacane_media_content' );
		delete_option( 'sarbacane_rss_data' );
		delete_option( 'sarbacane_news_description' );
		delete_option( 'sarbacane_news_fields' );
		delete_option( 'sarbacane_news_registration_message' );
		delete_option( 'sarbacane_news_registration_button' );
		delete_option( 'sarbacane_news_registration_mandatory_fields' );
		delete_option( 'sarbacane_news_registration_legal_notices_mentions' );
		delete_option( 'sarbacane_news_registration_legal_notices_url' );
		delete_option( 'sarbacane_news_title' );
		delete_option( 'sarbacane_users_description' );
		delete_option( 'sarbacane_users_fields' );
		delete_option( 'sarbacane_users_registration_message' );
		delete_option( 'sarbacane_users_registration_button' );
		delete_option( 'sarbacane_users_title' );
		delete_option( 'sarbacane_users_updated_structure' );
		//delete_option( 'sarbacane_newsletter_list' );
		//delete_option( 'widget_sarbacane_newsletter' );

		global $wpdb;
		$sql = "DROP TABLE IF EXISTS `{$wpdb->prefix}sd_updates`";
		$wpdb->query( $sql );
		//$sql = "DROP TABLE IF EXISTS `{$wpdb->prefix}sd_subscribers`";
		//$wpdb->query( $sql );
	}

	public function update_data_1_4_5() {
		if ( get_option( 'sarbacane_version' ) === false ) {
			update_option( 'sarbacane_version', '1.4.9', false );
			try {
				global $wpdb;
				$charset_collate = $wpdb->get_charset_collate();
				$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}sd_subscribers` (
					`id` mediumint(9) NOT NULL AUTO_INCREMENT,
					`email` varchar(255) DEFAULT NULL,
					`columns` mediumtext DEFAULT NULL,
					`registration_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					UNIQUE KEY `id` (`id`)
				) {$charset_collate};";
				$result = $wpdb->query( $sql );
				if ( $result !== false ) {
					$subscribers = get_option( 'sarbacane_newsletter_list', array() );
					$nb_subscribers = count( $subscribers );
					if ( $nb_subscribers > 0 ) {
						$insert_request = array();
						$insert_data = array();
						$sql = "SELECT COUNT(*) FROM `{$wpdb->prefix}sd_subscribers`";
						$count = $wpdb->get_var( $sql );
						if ( $count == 0) {
							foreach ( $subscribers as $key => $one_subscriber ) {
								if ( isset( $one_subscriber->email ) ) {
									$email = $one_subscriber->email;
									$object_properties = get_object_vars( $one_subscriber );
									$columns = array();
									foreach ( $object_properties as $property => $value ) {
										$label = strtolower( $property );
										if ( $property != 'email' && $property != 'registration_date' ) {
											$columns[$label] = $value;
										}
									}
									$registration_date = $one_subscriber->registration_date;
									$insert_request[] = '(%s, %s, %s)';
									$insert_data[] = $email;
									$insert_data[] = json_encode( $columns );
									$insert_data[] = $registration_date;
									if ( $key % 1000 == 0 || $key + 1 == $nb_subscribers ) {
										$sql = "
										INSERT INTO `{$wpdb->prefix}sd_subscribers` (`email`, `columns`, `registration_date`)
										VALUES " . implode( ',', $insert_request ) . ";";
										$wpdb->query( $wpdb->prepare( $sql, $insert_data ) );
										$insert_request = array();
										$insert_data = array();
									}
								}
							}
						}
					}
					$sd_ids_saved = get_option( 'sarbacane_sd_id_list', array() );
					foreach ( $sd_ids_saved as $sd_id_saved ) {
						delete_option( 'sarbacane_news_list_reset_' . $sd_id_saved );
						delete_option( 'sarbacane_user_list_reset_' . $sd_id_saved );
						delete_option( 'sarbacane_C_call_' . $sd_id_saved );
						delete_option( 'sarbacane_N_call_' . $sd_id_saved );
					}
					update_option( 'sarbacane_sd_id_list', array(), false );
					$wpdb->query( "TRUNCATE TABLE `{$wpdb->prefix}sd_updates`" );
				} else {
					delete_option( 'sarbacane_version' );
				}
			} catch ( Exception $e ) {
				delete_option( 'sarbacane_version' );
			}
		}
	}

	public function add_admin_menu() {
		add_submenu_page(
			'sarbacane',
			__( 'Connection', 'sarbacane-desktop' ),
			__( 'Connection', 'sarbacane-desktop' ),
			'administrator',
			'wp_interconnection',
			array(
				$this,
				'display_settings'
			)
		);
	}

	public function sarbacane_load_locales() {
		load_plugin_textdomain(
			'sarbacane-desktop',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/locales'
		);
	}

	private function sarbacane_params_saved() {
		return '<div class="updated">' . __( 'A new key has been generated', 'sarbacane-desktop' ) . '</div>';
	}

	private function sarbacane_get_list( $sd_id, $sd_list_id ) {

		$sd_list_news = get_option( 'sarbacane_news_list', false );
		$sd_list_users = get_option( 'sarbacane_users_list', false );

		// If first connect for this client, reset flags set to 1, sdid save
		$sd_ids_saved = get_option( 'sarbacane_sd_id_list', array() );
		if ( array_search( $sd_id, $sd_ids_saved ) === false ) {
			$sd_ids_saved[] = $sd_id;
			update_option( 'sarbacane_sd_id_list', $sd_ids_saved, false );
		}
		if ( $sd_list_id != '' ) {
			if ( $sd_list_id == 'U' ) {
				return $this->get_users_list( $sd_id );
			} else if ( $sd_list_id == 'N' ) {
				return $this->get_newsletter_list( $sd_id );
			}
		} else {
			return $this->generate_available_lists( $sd_id, $sd_list_users, $sd_list_news );
		}
	}

	private function generate_available_lists( $sd_id, $user_sync, $newsletter_sync ) {
		$content = 'list_id;name;reset;is_updated;type;version' . "\n";
		$last_call_date_N = get_option( 'sarbacane_news_call_' . $sd_id );
		$last_call_date_C = get_option( 'sarbacane_user_call_' . $sd_id );
		if ( $last_call_date_N != '' ) {
			$sd_list_news_reset = 'N';
			if ( $this->check_newsletter_list( $last_call_date_N ) ) {
				$newsletter_updated = 'Y';
			} else {
				$newsletter_updated = 'N';
			}
		} else {
			$sd_list_news_reset = 'Y';
			$newsletter_updated = 'Y';
		}
		if ( $last_call_date_C != '' ) {
			$sd_list_users_reset = 'N';
			if ( $this->check_users_list( $last_call_date_C ) ) {
				$user_updated = 'Y';
			} else {
				$user_updated = 'N';
			}
		} else {
			$sd_list_users_reset = 'Y';
			$user_updated = 'Y';
		}
		$name = get_bloginfo( 'name' );
		$name = $this->d_quote( str_replace( '&#039;', '\'', $name ) );
		if ( $user_sync ) {
			$content .= 'U;' . $name . ';' . $sd_list_users_reset . ';' . $user_updated . ';Wordpress;' . get_bloginfo( 'version' ) . "\n";
		}
		if ( $newsletter_sync ) {
			$content .= 'N;' . $name . ';' . $sd_list_news_reset . ';' . $newsletter_updated . ';Wordpress;' . get_bloginfo( 'version' ) . "\n";
		}
		return $content;
	}

	private function check_users_list( $last_call_date ) {
		global $wpdb;
		$sql = "SELECT COUNT(*) FROM `{$wpdb->prefix}users` AS `wu` WHERE `wu`.`user_registered` >= %s";
		$new_users_since_last_call = $wpdb->get_var( $wpdb->prepare( $sql, $last_call_date ) );
		$sql = "SELECT COUNT(*) FROM `{$wpdb->prefix}sd_updates` AS `wsu` WHERE `wsu`.`time` >= %s";
		$update_users_since_last_call = $wpdb->get_var( $wpdb->prepare( $sql, $last_call_date ) );
		return $new_users_since_last_call > 0 || $update_users_since_last_call > 0;
	}

	private function check_newsletter_list( $last_call_date = '' ) {
		global $wpdb;
		$sql_subscribers = "SELECT COUNT(*) FROM `{$wpdb->prefix}sd_subscribers`";
		if ( $last_call_date != '' ) {
			$sql_subscribers .= " WHERE `registration_date` > %s";
			$count = $wpdb->get_var( $wpdb->prepare( $sql_subscribers, $last_call_date ) );
		} else {
			$count = $wpdb->get_var( $sql_subscribers );
		}
		if ( $count > 0 ) {
			return true;
		}
		return false;
	}

	private function get_newsletter_list( $sd_id ) {
		if ( ! get_option( 'sarbacane_news_list' ) ) {
			return $this->generate_csv( array(), array(), 'N' );
		}
		$last_call_date = get_option( 'sarbacane_news_call_' . $sd_id, '' );
		update_option( 'sarbacane_news_call_' . $sd_id, gmdate( 'Y-m-d H:i:s' ), false );
		$new_or_updated_users = $this->get_subscribers( $last_call_date );
		$this->clear_update_history();
		return $this->generate_csv( $new_or_updated_users, array(), 'N' );
	}

	private function get_users_list( $sd_id ) {
		if ( ! get_option( 'sarbacane_users_list' ) ) {
			return $this->generate_csv( array(), array(), 'U' );
		}
		$last_call_date = get_option( 'sarbacane_user_call_' . $sd_id, '' );
		update_option( 'sarbacane_user_call_' . $sd_id, gmdate( 'Y-m-d H:i:s' ), false );
		// get or create list for SD_ID
		$all_users = $this->sarbacanedesktop_get_all_users( $last_call_date );
		$deleted_users = $this->sarbacanedesktop_get_all_deleted_users( $last_call_date );
		$this->clear_update_history();
		return $this->generate_csv( $all_users, $deleted_users, 'U' );
	}

	private function generate_csv( $new_or_updated_users, $deleted_users, $list_type ) {
		if ( "N" == $list_type ) {
			$fields = get_option( 'sarbacane_news_fields', array() );
			$csv_string = 'email;';
			foreach ( $fields as $field ) {
				if ( strtolower( $field->label ) != 'email' ) {
					$csv_string .= $this->d_quote( $field->label ) . ';';
				}
			}
			$csv_string .= 'action' . "\n";
			foreach ( $new_or_updated_users as $one_user_updated ) {
				if ( isset( $one_user_updated->email ) ) {
					$csv_string .= $this->d_quote( $one_user_updated->email ) . ';';
				} else {
					continue;
				}
				foreach ( $fields as $field ) {
					if ( strtolower( $field->label ) != 'email' ) {
						$label = strtolower( $field->label );
						if ( isset( $one_user_updated->$label ) ) {
							$csv_string .= $this->d_quote( $one_user_updated->$label ) . ';';
						} else {
							$csv_string .= ';';
						}
					}
				}
				$csv_string .= 'S' . "\n";
			}
		} else {
			$csv_string = 'email;lastname;firstname;login;role;post_count;action' . "\n";
			if ( count( $new_or_updated_users ) > 0 ) {
				foreach ( $new_or_updated_users as $one_user_updated ) {
					$role = '';
					if ( isset( $one_user_updated->user_role ) ) {
						$role_array = maybe_unserialize( $one_user_updated->user_role );
						if ( is_array( $role_array ) ) {
							$role_array_keys = array_keys( $role_array );
							if ( is_array( $role_array_keys ) && count( $role_array_keys ) > 0 ) {
								$role = $role_array_keys [0];
							}
						}
					}
					$csv_string .= $this->d_quote( $one_user_updated->email ) . ';' . $this->d_quote( $one_user_updated->lastname ) . ';';
					$csv_string .= $this->d_quote( $one_user_updated->firstname ) . ';' . $this->d_quote( $one_user_updated->user_login ) . ';';
					$csv_string .= $this->d_quote( $role ) . ';' . $this->d_quote( $one_user_updated->user_posts ) . ';S' . "\n";
				}
			}
			if ( count( $deleted_users ) > 0 ) {
				foreach ( $deleted_users as $deleted_user ) {
					$csv_string .= $deleted_user->user_email . ';;;;;;U' . "\n";
				}
			}
		}
		return $csv_string;
	}

	private function d_quote($value) {
		$value = str_replace('"', '""', $value);
		if ( strpos( $value, ' ' ) !== false || strpos( $value, ';' ) !== false ) {
			$value = '"' . $value . '"';
		}
		return $value;
	}

	private function sarbacanedesktop_get_all_users( $last_call_date ) {
		global $wpdb;
		$sql = "
		SELECT `wu`.`id` AS `user_id`, `wu`.`user_login` AS `user_login`, `wu`.`user_email` AS `email`,
		`wmf`.`meta_value` AS `firstname`, `wml`.`meta_value` AS `lastname`, `wum`.`meta_value` AS `user_role`
		FROM `{$wpdb->prefix}users` AS `wu`
		LEFT JOIN `{$wpdb->prefix}usermeta` AS `wmf`
			ON `wu`.`id` = `wmf`.`user_id`
			AND `wmf`.`meta_key` = 'first_name'
		LEFT JOIN `{$wpdb->prefix}usermeta` AS `wml`
			ON `wu`.`id` = `wml`.`user_id`
			AND `wml`.`meta_key` = 'last_name'
		LEFT JOIN `{$wpdb->prefix}usermeta` AS `wum`
			ON `wu`.`id` = `wum`.`user_id`
			AND `wum`.`meta_key` = '{$wpdb->prefix}capabilities'";
		if ( $last_call_date == '' ) {
			$all_users = $wpdb->get_results( $sql );
		} else {
			$sql = $sql . "
			LEFT JOIN `{$wpdb->prefix}sd_updates` AS `wsu`
				ON `wsu`.`user_id` = `wu`.`id`
				AND `wsu`.`action` = 'S'
			WHERE `wu`.`user_registered` >= %s OR `wsu`.`time` >= %s";
			$all_users = $wpdb->get_results( $wpdb->prepare( $sql, $last_call_date, $last_call_date ) );
		}
		if ( is_array( $all_users ) && count( $all_users ) > 0 ) {
			foreach ( $all_users as $one_user ) {
				$one_user->user_posts = count_user_posts( $one_user->user_id );
			}
		}
		return $all_users;
	}

	private function sarbacanedesktop_get_all_deleted_users( $last_call_date ) {
		global $wpdb;
		$sql = "
		SELECT `user_email`
		FROM `{$wpdb->prefix}sd_updates` AS `wsu`
		WHERE `wsu`.`action` = 'U' AND `wsu`.`time` >= %s";
		$deleted_users = $wpdb->get_results( $wpdb->prepare( $sql, $last_call_date ) );
		return $deleted_users;
	}

	private function get_subscribers( $last_call_date ) {
		global $wpdb;
		if ( $last_call_date != '' ) {
			$sql = "
			SELECT *
			FROM `{$wpdb->prefix}sd_subscribers`
			WHERE `registration_date` > %s
			ORDER BY `registration_date` ASC";
			$all_subscribers = $wpdb->get_results( $wpdb->prepare( $sql, $last_call_date ) );
		} else {
			$sql = "
			SELECT *
			FROM `{$wpdb->prefix}sd_subscribers`
			ORDER BY `registration_date` ASC";
			$all_subscribers = $wpdb->get_results( $sql );
		}
		$new_or_updated_users = array();
		foreach ( $all_subscribers as $one_subscriber ) {
			if ( isset( $one_subscriber->email ) ) {
				$values = new stdClass();
				$values->email = $one_subscriber->email;
				$columns = json_decode( $one_subscriber->columns );
				foreach ( $columns as $name => $column) {
					$name = strtolower( $name );
					if ($name != 'email' && $name != 'registration_date' ) {
						$values->$name = $column;
					}
				}
				$values->registration_date = $one_subscriber->registration_date;
				$new_entry = array(
					$one_subscriber->email => $values
				);
				$new_or_updated_users = array_merge( $new_or_updated_users, $new_entry );
			}
		}
		return $new_or_updated_users;
	}

	private function sarbacane_delete_sdid( $sd_id ) {
		delete_option( 'sarbacane_user_call_' . $sd_id );
		delete_option( 'sarbacane_news_call_' . $sd_id );

		$sd_ids_saved = get_option( 'sarbacane_sd_id_list' );
		$index = array_search( $sd_id, $sd_ids_saved );
		if ( $index !== false ) {
			unset( $sd_ids_saved [ $index ] );
			update_option( 'sarbacane_sd_id_list', $sd_ids_saved, false );
		}
	}

	public function trigger_user_update( $user_id ) {
		if ( get_option( 'sarbacane_users_list' ) ) {
			if ( count ( get_option( 'sarbacane_sd_id_list', array() ) ) > 0 ) {
				global $wpdb;
				$table_name = $wpdb->prefix . 'sd_updates';
				$wpdb->delete( $table_name, array(
					'user_id' => $user_id
				) );
				$wpdb->insert( $table_name, array(
					'time' => gmdate( 'Y-m-d H:i:s' ),
					'user_id' => $user_id,
					'action' => 'S'
				) );
			}
		}
	}

	public function trigger_user_delete( $user_id ) {
		if ( get_option( 'sarbacane_users_list' ) ) {
			if ( count ( get_option( 'sarbacane_sd_id_list', array() ) ) > 0 ) {
				global $wpdb;
				$table_name = $wpdb->prefix . 'sd_updates';
				$user_obj = get_userdata( $user_id );
				if ( isset( $user_obj->user_email ) ) {
					$wpdb->delete( $table_name, array(
						'user_email' => $user_obj->user_email
					) );
					$wpdb->insert( $table_name, array(
						'time' => gmdate( 'Y-m-d H:i:s' ),
						'user_email' => $user_obj->user_email,
						'action' => 'U'
					) );
				}
			}
		}
	}

	private function clear_update_history() {
		global $wpdb;
		$sql = "
		DELETE FROM `{$wpdb->prefix}sd_updates`
		WHERE `time` <= (
			SELECT MIN(`option_value`)
			FROM `{$wpdb->prefix}options`
			WHERE `option_name` LIKE 'sarbacane_%_call_%'
		)";
		$wpdb->query( $sql );
	}

	public function sarbacane_query_vars( $vars ) {
		$vars = array_merge( $vars, Array(
			'my-plugin',
			'sdid',
			'sd_token',
			'list',
			'action',
			'type',
			'id',
			'limit',
			'sarbacane_form_token'
		) );
		return $vars;
	}

	public function display_settings() {
		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}
		$nonce_ok = false;
		if ( isset( $_POST ['sarbacane_token'] ) ) {
			if ( wp_verify_nonce( $_POST ['sarbacane_token'], 'sarbacane_redo_token' ) ) {
				$nonce_ok = true;
			}
		}
		if ( $nonce_ok && isset ( $_POST ['sarbacane_redo_token'] ) ) {
			$sd_ids_saved = get_option( 'sarbacane_sd_id_list', array() );
			foreach ( $sd_ids_saved as $sd_id_saved ) {
				delete_option( 'sarbacane_user_call_' . $sd_id_saved );
				delete_option( 'sarbacane_news_call_' . $sd_id_saved );
			}
			update_option( 'sarbacane_sd_token', $this->generate_token(), false );
			update_option( 'sarbacane_sd_id_list', array(), false );
			global $wpdb;
			$wpdb->query( "TRUNCATE TABLE `{$wpdb->prefix}sd_updates`" );
			echo $this->sarbacane_params_saved();
		}
		$sd_token = get_option( 'sarbacane_sd_token', false );
		$key = '';
		if ( $sd_token !== false && $sd_token != '' ) {
			$key = $this->generate_key( $sd_token );
		}
		$sd_list_news = get_option( 'sarbacane_news_list', false );
		$is_connected = false;
		if ( count( get_option( 'sarbacane_sd_id_list', array() ) ) > 0 ) {
			$is_connected = true;
		}
		$is_failed = (int) get_option( 'sarbacane_failed', 0 ) < 1000000 ? false : true;
		wp_enqueue_style (
			'sarbacane_global.css',
			plugins_url ( 'css/sarbacane_global.css', __FILE__ ),
			array(),
			'1.4.9'
		);
		wp_enqueue_style (
			'sarbacane_admin_panel.css',
			plugins_url ( 'css/sarbacane_admin_panel.css', __FILE__ ),
			array(),
			'1.4.9'
		);
		require_once( 'views/sarbacane-adminpanel.php' );
	}

	private function generate_key( $token ) {
		$key = 'index.php?sd_token=' . $token;
		$key = str_rot13( $key );
		return $key;
	}

	private function generate_token() {
		$key_length = 45;
		$key = '';
		while ( strlen( $key ) < $key_length ) {
			$type_char = mt_rand( 0, 2 );
			switch ( $type_char ) {
				case 0 :
					$char = chr( mt_rand( 48, 57 ) );
					break;
				case 1 :
					$char = chr( mt_rand( 65, 90 ) );
					break;
				case 2 :
					$char = chr( mt_rand( 97, 122 ) );
					break;
			}
			$key .= $char;
		}
		return $key;
	}

	public function sarbacane_process_request( $wp ) {
		if ( ! array_key_exists( 'my-plugin', $wp->query_vars ) || $wp->query_vars ['my-plugin'] != 'sarbacane' ) {
			return;
		}
		if ( isset ( $wp->query_vars ['sarbacane_form_token'] )
			&& $wp->query_vars ['sarbacane_form_token'] != ''
			&& wp_verify_nonce( $wp->query_vars['sarbacane_form_token'], 'newsletter_registration' ) ) {
			$save_widget = true;
		}
		if ( isset ( $wp->query_vars ['sdid'] ) && $wp->query_vars ['sdid'] != '' ) {
			$sd_id = sanitize_text_field( $wp->query_vars ['sdid'] );
		}
		if ( isset ( $wp->query_vars ['sd_token'] ) && $wp->query_vars ['sd_token'] != '' ) {
			$sd_token = sanitize_text_field( $wp->query_vars ['sd_token'] );
		}
		$sd_list_id = '';
		if ( isset ( $wp->query_vars ['list'] ) && $wp->query_vars ['list'] != '' ) {
			$sd_list_id = sanitize_text_field( $wp->query_vars ['list'] );
		}
		$action = '';
		if ( isset ( $wp->query_vars ['action'] ) && $wp->query_vars ['action'] != '' ) {
			$action = sanitize_text_field( $wp->query_vars ['action'] );
		}
		if ( isset ( $wp->query_vars ['type'] ) && $wp->query_vars ['type'] != '' ) {
			$type = sanitize_text_field( $wp->query_vars ['type'] );
		}
		if ( isset ( $wp->query_vars ['id'] ) && $wp->query_vars ['id'] != '' ) {
			$id = sanitize_text_field( $wp->query_vars ['id'] );
		}
		$limit = - 1;
		if ( isset ( $wp->query_vars ['limit'] ) && $wp->query_vars ['limit'] != '' ) {
			$limit = $wp->query_vars ['limit'];
		}
		if ( isset( $save_widget ) ) {
			header( 'Content-Type: text/html; charset=utf-8' );
			$this->update_data_1_4_5();
			$widget = new SarbacaneNewsWidget();
			echo $widget->sarbacane_save_widget();
			exit;
		}
		if ( ! isset ( $sd_id ) ) {
			header( 'HTTP/1.1 404 Not Found' );
			header( 'Content-type: text/plain; charset=utf-8' );
			exit( 'FAILED_ID' );
		}
		if ( ! isset ( $sd_token ) ) {
			header( 'HTTP/1.1 403 Forbidden' );
			header( 'Content-type: text/plain; charset=utf-8' );
			exit( 'FAILED' );
		}
		$failed = (int) get_option( 'sarbacane_failed', 0 );
		$is_failed = $failed < 1000000 ? false : true;
		$sd_token_saved = get_option( 'sarbacane_sd_token', false );
		if ( $sd_token != $sd_token_saved
			|| $sd_token_saved == ''
			|| $sd_token_saved == false
			|| strlen( $sd_token_saved ) < 20
			|| $is_failed ) {
			if ( ! $is_failed ) {
				update_option( 'sarbacane_failed', $failed + 1, false );
			}
			header( 'HTTP/1.1 403 Forbidden' );
			header( 'Content-type: text/plain; charset=utf-8' );
			exit( 'FAILED' );
		} else if ( $sd_token == $sd_token_saved && strlen( $sd_token_saved ) > 20 && ! $is_failed ) {
			if ( isset ( $type ) ) {
				if ( $type == 'posts' ) {
					$content = new SarbacaneContent();
					if ( isset ( $id ) ) {
						$text = $content->get_article_rss( $id );
						if ($text === false) {
							header( 'HTTP/1.1 404 Not Found' );
							header( 'Content-Type: text/plain; charset=utf-8' );
							exit( 'FAILED_ID' );
						} else {
							header( 'Content-Type: application/rss+xml; charset=utf-8' );
							echo $text;
						}
					} else {
						header( 'Content-Type: application/rss+xml; charset=utf-8' );
						echo $content->get_articles_rss( $limit );
					}
				} else if ( $type == 'settings' ) {
					header( 'Content-Type: application/json; charset=utf-8' );
					$settings = new SarbacaneSettings();
					echo $settings->get_settings();
				} else if ( $type == 'medias' ) {
					header( 'Content-Type: application/json; charset=utf-8' );
					$medias = new SarbacaneMedias();
					echo $medias->get_medias();
				}
			} else {
				$this->update_data_1_4_5();
				header( 'Content-Type: text/plain; charset=utf-8' );
				if ( $action == 'delete' ) {
					$this->sarbacane_delete_sdid( $sd_id );
				} else {
					if ( $sd_list_id != '' && $sd_list_id != 'N' && $sd_list_id != 'U' ) {
						header( 'HTTP/1.1 404 Not found' );
						exit( 'FAILED_ID' );
					}
					echo $this->sarbacane_get_list( $sd_id, $sd_list_id );
				}
			}
		}
		exit;
	}

}
