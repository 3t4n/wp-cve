<?php

/**
 * Helper class designed to organize MDM to DMS import
 * @since 1.7.5
 */
class DMS_Mdm_Import {

	const IMPORT_REQUEST_KEY = 'mdm_to_dms_import';
	const IMPORT_REQUEST_VALUE = 'z862kqd7wqnzrv9e';

	/**
	 * @var DMS
	 */
	public $dms;

	/**
	 * Get MDM plugin instance
	 *
	 * @return FALKE_MultipleDomainMapping
	 */
	public function getMdmInstance() {
		return FALKE_MultipleDomainMapping::get_instance();
	}

	/**
	 * Checker for import notification showing or not
	 *
	 * @return bool
	 */
	public function showImportNote() {
		return get_option( 'dms_mdm_import_note' ) === false;
	}

	/**
	 * Hide import note
	 */
	public function hideImportNote() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'dms_nonce' ) ) {
			wp_send_json( [
				'status' => 0
			] );
		}

		$ok = update_option( 'dms_mdm_import_note', 0 );
		wp_send_json( [
			'status' => $ok
		] );
	}

	/**
	 * @param  DMS  $DMS
	 */
	public function __construct( $DMS ) {
		$this->dms = $DMS;
		$this->addActions();
	}

	/**
	 * Add couple actions
	 */
	public function addActions() {
		add_action( 'admin_post_dms_mdm_import', array( $this, 'import' ) );
		add_action( 'wp', array( $this, 'getDmsRelatedKeys' ), 11, 1 );
		add_action( 'wp_ajax_dms_hide_mdm_note', array( 'DMS_Mdm_Import', 'hideImportNote' ) );
	}

	/**
	 * Show import related notification with form
	 */
	public function show() {
		?>
        <div class="updated" id="dms-mdm-import">
            <p>
				<?php
				if ( empty( $platform ) ) {
					printf( __( 'We notice you are using the %sMultiple Domain Mapping%s plugin, one of our integration partners. 
                                    Would you like to import your mapping configuration into Domain Mapping System?<br>[<a href="#" class="yes" ><b>Yes</b></a>] [<a href="#" class="no"><b>No</b></a>] [%sWhat does this mean?%s]',
						$this->dms->plugin_name ),
						'<a target="_blank" href="' . admin_url( 'admin.php?page=multiple-domain-mapping-on-single-site%2Fmultidomainmapping.php' ) . '">',
						'</a>',
						'<a target="_blank" href="https://docs.domainmappingsystem.com/integrations-and-compatibility/wordpress-plugins/multiple-domain-mapping">', '</a>' );
				} else {
					$platform->printGeneralNotice();
				}
				?>
            </p>
        </div>
        <form method="post" id="dms-mdm-import-form" action="<?= admin_url( 'admin-post.php' ); ?>">
            <input name="action" value="dms_mdm_import" type="hidden">
			<?php wp_nonce_field( 'dms_mdm_import_action', 'dms_mdm_import_nonce' ) ?>
        </form>
		<?php
	}

	/**
	 * Organize mappings import from MDM to DMS
	 */
	public function import() {
		// Check nonce
		check_admin_referer( 'dms_mdm_import_action', 'dms_mdm_import_nonce' );
		$referer = wp_get_referer();
		// Check session
		if ( empty( session_id() ) ) {
			session_start();
		}
		$wpdb                    = $this->dms->wpdb;
		$data                    = get_option( 'falke_mdm_mappings' );
		$mdm_mappings_count      = count( $data['mappings'] );
		$succeeded_imports_count = 0;
		if ( ! empty( $data ) ) {
			foreach ( $data['mappings'] as $mdm_mapping ) {
				// Collect required variables
				$domain                = $mdm_mapping['domain'];
				$parsed_domain         = wp_parse_url( 'https://' . $domain );
				$host                  = ! empty( $parsed_domain['host'] ) ? trim( $parsed_domain['host'], '/' ) : null;
				$path                  = ! empty( $parsed_domain['path'] ) ? trim( $parsed_domain['path'], '/' ) : null;
				$mapping_imported      = false;
				$failed_premium_import = 0;
				// Check 
				if ( empty( $host ) ) {
					continue;
				}

				// Check premium related import
				if ( ! empty( $path ) && ! $this->dms->dms_fs->can_use_premium_code__premium_only() ) {
					$failed_premium_import ++;
					continue;
				}

				// Check if we have mapping with that domain
				$dms_mapping = DMS_Helper::getMappingByHostAndPath( $this->dms->wpdb, $host, $path );
				// Proceed remote request to get right entity of the mapping
				if ( ! empty( $mdm_mapping['path'] ) ) {
					$response = wp_remote_request( site_url() . '/' . $mdm_mapping['path'] . '?' . self::IMPORT_REQUEST_KEY . '=' . self::IMPORT_REQUEST_VALUE,
						[
							'timeout' => 10
						] );
					if ( ! empty( $response ) && ! ( $response instanceof WP_Error ) ) {
						$body = wp_remote_retrieve_body( $response );
						if ( ! empty( $body ) ) {
							try {
								$body_obj = json_decode( $body );

								if ( ! empty( $body_obj->key ) ) {
									$possible_value = $body_obj->key;
									/**
									 * Dms mapping with host + path exists
									 * or not exist. If exists, then
									 */
									if ( ! empty( $dms_mapping->id ) ) {
										// Find mapping by that key if any
										$dms_value_match = DMS_Helper::getMatchingHostByValue( $this->dms->wpdb,
											$possible_value );
										if ( empty( $dms_value_match->host_id ) || $dms_value_match->host_id != $dms_mapping->id ) {
											// There is no host with that value 
											// or there is, but not matches with the host founded earlier
											// so the key should be inserted as a new mapping for founded host
											$mapping_imported = $this->dms->prepareMappingInsert( $possible_value,
												[ 'path' => $dms_mapping->path ], $dms_mapping->id );
										} else {
											// Case when founded host already has that value as a mapping , so do nothing !
											$mapping_imported = true;
										}

									} else {
										// In this case new mapping with both host{path} and value needs to be inserted
										$dms_mapping_insert_values       = array( 'host' => $host );
										$dms_mapping_insert_where_values = array( '%s' );
										if ( ! empty( $path ) ) {
											$dms_mapping_insert_values['path'] = $path;
											$dms_mapping_insert_where_values[] = '%s';
										}

										$ok = $wpdb->insert( $wpdb->prefix . 'dms_mappings',
											$dms_mapping_insert_values,
											$dms_mapping_insert_where_values );

										if ( ! empty( $ok ) ) {
											$host_id          = $wpdb->insert_id;
											$mapping_imported = $this->dms->prepareMappingInsert( $possible_value,
												( ! empty( $path ) ? [ 'path' => $path ] : [] ), $host_id );
										}
									}
								}
							} catch ( \Exception $e ) {
								// Log error
								continue;
							}

							// Increment succeeded count
							if ( ! empty( $mapping_imported ) ) {
								$succeeded_imports_count ++;
							}
						}
					}
				}
			}
		}

		// Check results
		if ( $succeeded_imports_count !== $mdm_mappings_count ) {
			if ( $succeeded_imports_count === 0 ) {
				// Bad case
				$_SESSION['dms_admin_warning'][] = sprintf( __( 'Importing from the Multiple Domain Mapping plugin failed. Please contact %1$ssupport@domainmappingsystem.com%2$s for assistance. ',
					$this->dms->plugin_name ), '<u><b>', '</b></u>' );
			} elseif ( $succeeded_imports_count < $mdm_mappings_count ) {
				// Some mappings imported 
				$_SESSION['dms_admin_warning'][] = sprintf( __( 'Partial import succeeded. Please double check all your mapping values and contact %1$ssupport@domainmappingsystem.com%2$s if any assistance is necessary.',
					$this->dms->plugin_name ), '<u><b>', '</b></u>' );
			}
			if ( ! empty( $failed_premium_import ) ) {
				// Some mappings imported 
				$_SESSION['dms_admin_warning'][] = sprintf( __( 'Multiple Domain Mapping contains mappings which are only available in the PRO version of Domain Mapping System. Please %1$sUpgrade%2$s to import.',
					$this->dms->plugin_name ), '<a href="' . $this->dms->dms_fs->get_upgrade_url() . '" target="_blank"><b>', ' &#8594;</b></a>' );
			}
		} else {
			// Update imported option, to hide notification and other staff
			update_option( 'dms_mdm_import_note', 0 );
			$_SESSION['dms_admin_success'][] = __( 'Successfully imported.', $this->dms->plugin_name );
		}

		wp_safe_redirect( $referer );
		exit();
	}

	/**
	 * This part is totally got from MDM plugin
	 * This should remove filters in order to get clear links at the point when "getDmsRelatedKeys" will work
	 */
	public static function removeMdmFilters() {
		$mdm_instance = FALKE_MultipleDomainMapping::get_instance();
		remove_filter( 'page_link', array( $mdm_instance, 'replace_uri' ), 20 );
		remove_filter( 'post_link', array( $mdm_instance, 'replace_uri' ), 20 );
		remove_filter( 'post_type_link', array( $mdm_instance, 'replace_uri' ), 20 );
		remove_filter( 'attachment_link', array( $mdm_instance, 'replace_uri' ), 20 );

		//revoke mapping for the preview-button
		remove_filter( 'preview_post_link', array( $mdm_instance, 'unreplace_uri' ) );

		//archive views
		remove_filter( 'paginate_links', array( $mdm_instance, 'replace_uri' ), 10 );
		remove_filter( 'day_link', array( $mdm_instance, 'replace_uri' ), 20 );
		remove_filter( 'month_link', array( $mdm_instance, 'replace_uri' ), 20 );
		remove_filter( 'year_link', array( $mdm_instance, 'replace_uri' ), 20 );
		remove_filter( 'author_link', array( $mdm_instance, 'replace_uri' ), 10 );
		remove_filter( 'term_link', array( $mdm_instance, 'replace_uri' ), 10 );

		//feed url (if someone matches a domain to a feed...)
		remove_filter( 'feed_link', array( $mdm_instance, 'replace_uri' ), 10 );
		remove_filter( 'self_link', array( $mdm_instance, 'replace_uri' ), 10 );
		remove_filter( 'author_feed_link', array( $mdm_instance, 'replace_uri' ), 10 );
	}

	/**
	 * Special part that should work in case DMS runs import mechanism of MDM
	 * This should check weather certain key exists and if
	 * Yes: then it should terminate script with json response containing DMS required key
	 * No: then do nothing
	 */
	public function getDmsRelatedKeys() {
		if ( ! is_admin() && ( ! empty( $_GET[ self::IMPORT_REQUEST_KEY ] ) && $_GET[ self::IMPORT_REQUEST_KEY ] === self::IMPORT_REQUEST_VALUE ) ) {
			// Remove tmp mdm filters to get not rewrote links
			$this->removeMdmFilters();
			global $wp_query;
			if ( is_category() ) {
				$key = 'category-' . $wp_query->get_queried_object()->slug;
			} elseif ( is_single() || is_page() ) {
				$key = $wp_query->get_queried_object_id();
			} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
				$key = DMS_Helper::getShopPageAssociation();
			} else {
				global $wp_taxonomies;
				$custom_post_type = ! empty( $wp_query->get_queried_object()->taxonomy ) && isset( $wp_taxonomies[ $wp_query->get_queried_object()->taxonomy ] )
					? $wp_taxonomies[ $wp_query->get_queried_object()->taxonomy ]->object_type : null;
				if ( $wp_query->is_tax && ! empty( $custom_post_type[0] ) ) {
					$key = implode( '#', [
						$wp_query->get_queried_object()->taxonomy,
						$wp_query->get_queried_object()->slug,
						$custom_post_type[0]
					] );
				}
			}
			if ( ! empty( $key ) ) {
				wp_send_json( [
					'key' => $key
				] );
				exit;
			}
		}
	}

}