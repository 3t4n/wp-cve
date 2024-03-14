<?php
class YOP_POLL_Maintenance {
    private $dbschema, $capabilities, $import_errors, $importer = null;
    public function __construct() {
        $this->dbschema     = new Yop_Poll_DbSchema;
        $this->capabilities = new YOP_POLL_Capabilities;
        $this->import_errors = false;
    }
    public function activate( $network_wide ) {
		if ( true === $network_wide ) {
			if ( function_exists( 'get_sites' ) && function_exists( 'get_current_network_id' ) ) {
				$site_ids = get_sites(
					array(
						'fields' => 'ids',
						'network_id' => get_current_network_id(),
					)
				);
			} else {
				$site_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs WHERE site_id = $wpdb->siteid;" );
			}
			foreach ( $site_ids as $site_id ) {
				switch_to_blog( $site_id );
				$this->install_single();
				restore_current_blog();
			}
		} else {
			$this->install_single();
		}
	}
    public function install_single() {
		$this->dbschema     = new Yop_Poll_DbSchema;
		$this->capabilities = new YOP_POLL_Capabilities;
		$this->import_errors = false;
		$installed_version = get_option( 'yop_poll_version' );
		if ( false !== $installed_version ) {
			if ( true === version_compare( $installed_version, '6.0.0', '<' ) ) {
				update_option( 'yop_poll_old_version', $installed_version );
				if ( false !== strpos( $installed_version, '4.' ) ) {
					$this->importer = new ClassYopPollImporter4x( 1000, 100 );
				} elseif ( false !== strpos( $installed_version, '5.' ) ) {
					$this->importer = new ClassYopPollImporter5x( 1000, 100 );
				}
			}
		}
		$this->dbschema->create_tables();
		$this->capabilities->install();
		if ( $this->importer ) {
			$this->importer->initialise();
		}
		$this->create_options();
		if ( ! wp_next_scheduled( 'yop_poll_hourly_event', array() ) ) {
			wp_schedule_event( time(), 'hourly', 'yop_poll_hourly_event', array() );
		}
        //$this->create_archive_page();
	}
    public function update_to_version_6_0_4() {
        YOP_Poll_Settings::update_settings_to_version_6_0_4();
        update_option( 'yop_poll_version', '6.0.4' );
    }
    public function update_to_version_6_0_5() {
		$db_schema_object = new Yop_Poll_DbSchema();
		$db_schema_object->create_table_skins();
		$db_schema_object->install_skins();
        $db_schema_object->update_table_templates();
        $db_schema_object->update_table_polls_add_skin_field();
		update_option( 'yop_poll_version', '6.0.5' );
    }
    public function update_to_version_6_0_6() {
        update_option( 'yop_poll_version', '6.0.6' );
    }
    public function update_to_version_6_0_7() {
		update_option( 'yop_poll_version', '6.0.7' );
    }
    public function update_to_version_6_0_8() {
		update_option( 'yop_poll_version', '6.0.8' );
	}
	public function update_to_version_6_0_9() {
		update_option( 'yop_poll_version', '6.0.9' );
	}
	public function update_to_version_6_1_0() {
		$db_schema_object = new Yop_Poll_DbSchema();
		$db_schema_object->create_table_other_answers();
		update_option( 'yop_poll_version', '6.1.0' );
	}
	public function update_to_version_6_1_1() {
		update_option( 'yop_poll_version', '6.1.1' );
	}
	public function update_to_version_6_1_2() {
		update_option( 'yop_poll_version', '6.1.2' );
	}
	public function update_to_version_6_1_4() {
		YOP_Poll_Settings::update_show_guide( 'yes' );
		update_option( 'yop_poll_version', '6.1.4' );
	}
	public function update_to_version_6_1_5() {
		update_option( 'yop_poll_version', '6.1.5' );
	}
	public function update_to_version_6_1_6() {
		update_option( 'yop_poll_version', '6.1.6' );
	}
	public function update_to_version_6_1_7() {
		YOP_Poll_Settings::update_settings_to_version_6_1_7();
		update_option( 'yop_poll_version', '6.1.7' );
	}
	public function update_to_version_6_1_8() {
		update_option( 'yop_poll_version', '6.1.8' );
	}
	public function update_to_version_6_1_9() {
		update_option( 'yop_poll_version', '6.1.9' );
	}
	public function update_to_version_6_2_0() {
		YOP_Poll_Settings::update_settings_to_version_6_2_0();
		update_option( 'yop_poll_version', '6.2.0' );
	}
	public function update_to_version_6_2_1() {
		update_option( 'yop_poll_version', '6.2.1' );
	}
	public function update_to_version_6_2_2() {
		update_option( 'yop_poll_version', '6.2.2' );
	}
	public function update_to_version_6_2_3() {
		update_option( 'yop_poll_version', '6.2.3' );
	}
	public function update_to_version_6_2_4() {
		update_option( 'yop_poll_version', '6.2.4' );
	}
	public function update_to_version_6_2_5() {
		update_option( 'yop_poll_version', '6.2.5' );
	}
	public function update_to_version_6_2_6() {
		update_option( 'yop_poll_version', '6.2.6' );
	}
	public function update_to_version_6_2_7() {
		update_option( 'yop_poll_version', '6.2.7' );
	}
	public function update_to_version_6_2_8() {
		update_option( 'yop_poll_version', '6.2.8' );
	}
	public function update_to_version_6_2_9() {
		update_option( 'yop_poll_version', '6.2.9' );
	}
	public function update_to_version_6_3_0() {
		update_option( 'yop_poll_version', '6.3.0' );
	}
	public function update_to_version_6_3_1() {
		update_option( 'yop_poll_version', '6.3.1' );
	}
	public function update_to_version_6_3_2() {
		update_option( 'yop_poll_version', '6.3.2' );
	}
	public function update_to_version_6_3_3() {
		update_option( 'yop_poll_version', '6.3.3' );
	}
	public function update_to_version_6_3_4() {
		update_option( 'yop_poll_version', '6.3.4' );
	}
	public function update_to_version_6_3_5() {
		update_option( 'yop_poll_version', '6.3.5' );
	}
	public function update_to_version_6_3_6() {
		update_option( 'yop_poll_version', '6.3.6' );
	}
	public function update_to_version_6_3_7() {
		update_option( 'yop_poll_version', '6.3.7' );
	}
	public function update_to_version_6_3_8() {
		update_option( 'yop_poll_version', '6.3.8' );
	}
	public function update_to_version_6_3_9() {
		update_option( 'yop_poll_version', '6.3.9' );
	}
	public function update_to_version_6_4_0() {
		update_option( 'yop_poll_version', '6.4.0' );
	}
	public function update_to_version_6_4_1() {
		update_option( 'yop_poll_version', '6.4.1' );
	}
	public function update_to_version_6_4_2() {
		update_option( 'yop_poll_version', '6.4.2' );
	}
	public function update_to_version_6_4_3() {
		YOP_Poll_Settings::update_settings_to_version_6_4_3();
		update_option( 'yop_poll_version', '6.4.3' );
	}
	public function update_to_version_6_4_4() {
		update_option( 'yop_poll_version', '6.4.4' );
	}
	public function update_to_version_6_4_5() {
		update_option( 'yop_poll_version', '6.4.5' );
	}
	public function update_to_version_6_4_6() {
		update_option( 'yop_poll_version', '6.4.6' );
	}
	public function update_to_version_6_4_7() {
		update_option( 'yop_poll_version', '6.4.7' );
	}
	public function update_to_version_6_4_8() {
		update_option( 'yop_poll_version', '6.4.8' );
	}
	public function update_to_version_6_4_9() {
		update_option( 'yop_poll_version', '6.4.9' );
	}
	public function update_to_version_6_5_0() {
		update_option( 'yop_poll_version', '6.5.0' );
	}
	public function update_to_version_6_5_1() {
		update_option( 'yop_poll_version', '6.5.1' );
	}
	public function update_to_version_6_5_2() {
		update_option( 'yop_poll_version', '6.5.2' );
	}
	public function update_to_version_6_5_21() {
		update_option( 'yop_poll_version', '6.5.21' );
	}
	public function update_to_version_6_5_22() {
		update_option( 'yop_poll_version', '6.5.22' );
	}
	public function update_to_version_6_5_23() {
		update_option( 'yop_poll_version', '6.5.23' );
	}
	public function update_to_version_6_5_24() {
		update_option( 'yop_poll_version', '6.5.24' );
	}
	public function update_to_version_6_5_25() {
		update_option( 'yop_poll_version', '6.5.25' );
	}
	public function update_to_version_6_5_26() {
		update_option( 'yop_poll_version', '6.5.26' );
	}
	public function update_to_version_6_5_27() {
		update_option( 'yop_poll_version', '6.5.27' );
	}
	public function update_to_version_6_5_28() {
		update_option( 'yop_poll_version', '6.5.28' );
	}
	public function update_to_version_6_5_29() {
		update_option( 'yop_poll_version', '6.5.29' );
	}
    public function create_archive_page() {
        $poll_archive_page = get_page_by_path( 'yop-poll-archive', ARRAY_A );
        if ( ! $poll_archive_page ) {
            $_p                   = array();
            $_p['post_title']     = 'Poll Archive';
            $_p['post_content']   = '[yop_poll_archive]';
            $_p['post_status']    = 'publish';
            $_p['post_type']      = 'page';
            $_p['comment_status'] = 'open';
            $_p['ping_status']    = 'open';
            $_p['post_category']  = array( 1 );
            $poll_archive_page_id = wp_insert_post( $_p );
        } else {
            $poll_archive_page_id = $poll_archive_page['ID'];
        }
        $default_options = get_option( 'yop_poll_options' );
        $default_options['archive_url'] = get_permalink( $poll_archive_page_id );
        $default_options['yop_poll_archive_page_id'] = $poll_archive_page_id;
    }
    public function create_options() {
		update_option( 'yop_poll_version', YOP_POLL_VERSION );
		$plugin_old_settings = get_option( 'yop_poll_options' );
		if ( $plugin_old_settings ) {
            update_option( 'yop_poll_settings', YOP_Poll_Settings::import_settings_from_5x( $plugin_old_settings ) );
        } else {
			$plugin_current_settings = get_option( 'yop_poll_settings' );
			if ( false === $plugin_current_settings ) {
				update_option( 'yop_poll_settings', YOP_Poll_Settings::create_settings() );
			}
        }
	}
    public function delete_options() {
        delete_option( 'yop_poll_version' );
        delete_option( 'yop_poll_old_version' );
        delete_option( 'yop_poll_settings' );
        delete_option( 'yop_poll_pro' );
        delete_option( 'external_updates-yop-poll' );
    }
    public function deactivate() {
        wp_clear_scheduled_hook( 'yop_poll_hourly_event' );
    }
    public function uninstall() {
		if ( true === is_multisite() ) {
			if ( function_exists( 'get_sites' ) && function_exists( 'get_current_network_id' ) ) {
				$site_ids = get_sites(
					array(
						'fields' => 'ids',
						'network_id' => get_current_network_id(),
					)
				);
			} else {
				$site_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs WHERE site_id = $wpdb->siteid;" );
            }
			foreach ( $site_ids as $site_id ) {
				switch_to_blog( $site_id );
				$this->uninstall_single();
				restore_current_blog();
			}
		} else {
            $this->uninstall_single();
        }
	}
    public function uninstall_single() {
		$remove_plugin_data = YOP_Poll_Settings::get_remove_data();
		if ( 'yes' === $remove_plugin_data ) {
			$this->dbschema = new Yop_Poll_DbSchema;
			$this->capabilities = new YOP_Poll_Capabilities;
			$this->dbschema->delete_tables();
			$this->capabilities->uninstall();
			$this->delete_options();
		}
	}
    public function add_activation_message() {
        add_option( 'yop_poll_ajax_importer', 'yop_poll_ajax_importer' );
        $url = admin_url( 'admin.php?page=yop-poll-import' );
        $html = '<div class="updated">';
        $html .= '<p>';
        $html .= esc_html__( 'Click <a href="', 'yop-poll' ) . $url . '" target="_blank">' . esc_html__( 'here', 'yop-poll' ) . '</a> ' . esc_html__( 'to start the import.', 'yop-poll' );
        $html .= '</p>';
        $html .= '</div><!-- /.updated -->';
        echo wp_kses_post( $html );
    }
}
