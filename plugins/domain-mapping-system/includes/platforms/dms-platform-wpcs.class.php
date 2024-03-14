<?php

/**
 * WPCS platform related class.
 * Mainly designed to manage domains etc ...
 *
 * @since 1.7
 */
class DMS_Wpcs extends DMS_Platform {

	const NAME = 'WPCS';

	public $dms;
	private $api_key;
	private $api_secret;
	public $api_url;
	public $delay_after_save = 180;

	/**
	 * @param  DMS  $DMS
	 */
	public function __construct( $DMS ) {
		$this->dms = $DMS;
		$this->addActions();
		$this->setApiUrl();
	}

	/**
	 * Check weather it is allowed to save mapping from the platform
	 *
	 * @return bool
	 */
	public function allowMappingSave() {
		return ! self::isVersion();
	}

	/**
	 * Set API url
	 */
	private function setApiUrl() {
		if ( ! empty( getenv( 'WPCS_REGION' ) ) ) {
			$this->api_url = 'https://api.' . getenv( 'WPCS_REGION' ) . '.wpcs.io/v1/';
		}
	}

	/**
	 * Define save actions related to platform configs
	 * NOTE Important!!! Here we can add only actions running after admin_init
	 */
	public function addActions() {
		add_action( 'admin_post_save_dms_wpcs', array( $this, 'saveCredentials' ) );
		add_action( 'admin_post_save_dms_wpcs_set_tenant_main_domain', array( $this, 'setTenantMainDomain' ) );
		add_action( 'current_screen', array( $this, 'checkPossibleDomainsSubstitution' ) );
		add_action( 'current_screen', array( $this, 'getExternalDomains' ) );
		add_action( 'current_screen', array( $this, 'changeApiKeys' ) );
	}

	/**
	 * Api keys update request designed for version owners to provide tenants
	 *
	 * @param $current_screen
	 */
	public function changeApiKeys( $current_screen ) {
		if ( $current_screen->base === 'toplevel_page_' . $this->dms->plugin_name && self::isTenant() ) {
			$key_prefix = strtolower( self::NAME );
			if ( isset( $_GET[ $key_prefix . '-api-key' ] ) && isset( $_GET[ $key_prefix . '-api-secret' ] ) ) {
				update_option( 'dms_' . $key_prefix . '_api_secret',
					sanitize_text_field( $_GET[ $key_prefix . '-api-secret' ] ) );
				update_option( 'dms_' . $key_prefix . '_api_key',
					sanitize_text_field( $_GET[ $key_prefix . '-api-key' ] ) );
				// Move to clear page
				wp_safe_redirect( admin_url() . '?page=' . $this->dms->plugin_name );
				exit();
			}
		}
	}

	/**
	 * Check domains possible substitution existence.
	 * Designed to correctly save/remove new main and old main domains, which ideally should have been done
	 * after successful response in "setTenantMainDomain"  method. But seems WPCS unable to save that correctly after successful response
	 *
	 * @param  WP_Screen  $current_screen
	 */
	public function checkPossibleDomainsSubstitution( $current_screen ) {
		if ( $current_screen->base === 'toplevel_page_' . $this->dms->plugin_name && self::isTenant() ) {
			$possible_substitution = get_option( 'dms_platform_wpcs_domains_possible_substitution' );
			if ( ! empty( $possible_substitution['new_main'] )
			     && ! empty( $possible_substitution['old_main'] )
			     && trim( base64_decode( $possible_substitution['new_main'] ) ) == DMS_Helper::getActualBaseHost()
			) {
				$new_main = base64_decode( $possible_substitution['new_main'] );
				$old_main = base64_decode( $possible_substitution['old_main'] );

				// Get mapping id by host
				$map_id = DMS_Helper::getMapIdByDomain( $new_main, $this->dms->wpdb );

				if ( ! empty( $map_id ) ) {
					// Remove mapping in our side, because this will become new base
					DMS_Helper::removeDomainByMapId( $map_id, $this->dms->wpdb );
					// Update retrieved domains fetched flag. In order to retrieve again and list in our side
					// or simply add base host as new mapping
					$this->saveExternalDomains( [ $old_main ] );
					// Empty the domains fetched flag to do fetch again
					update_option( 'dms_platform_wpcs_domains_retrieved', 0 );
				}
				// Remove option to do this once. No matter the above result
				delete_option( 'dms_platform_wpcs_domains_possible_substitution' );
			}
		}
	}

	/**
	 * Fetch external domains list
	 *
	 * @param  WP_Screen  $current_screen
	 */
	public function getExternalDomains( $current_screen ) {
		if ( $current_screen->base === 'toplevel_page_' . $this->dms->plugin_name && self::isTenant() ) {
			if ( empty( get_option( 'dms_platform_wpcs_domains_retrieved' ) ) ) {
				if ( empty( session_id() ) ) {
					session_start();
				}
				try {
					$externalDomains = $this->getTenantDomains();
					// Fetch hosts
					foreach ( $externalDomains as $domain ) {
						if ( ! $domain->isMainDomain ) {
							$domains[] = $domain->domainName;
						}
					}
					// Check and save in our side
					if ( ! empty( $domains ) ) {
						$this->saveExternalDomains( $domains );
						// Now saving without any condition to avoid server down or any bad impact
						update_option( 'dms_platform_wpcs_domains_retrieved', 1 );
					}
				} catch ( \Exception $e ) {
					$_SESSION['dms_admin_warning'][] = __( $e->getMessage(),
						$this->dms->plugin_name );
				}
			}
		}
	}

	/**
	 * Save credentials
	 */
	public function saveCredentials() {
		// Check nonce
		check_admin_referer( 'save_dms_wpcs_action', 'save_dms_wpcs_nonce' );
		$referer = wp_get_referer();
		if ( empty( session_id() ) ) {
			session_start();
		}
		$api_key    = isset( $_POST['api_key'] ) ? sanitize_text_field( $_POST['api_key'] ) : '';
		$api_secret = isset( $_POST['api_secret'] ) ? sanitize_text_field( $_POST['api_secret'] ) : '';
		if ( ! empty( $api_key ) && ! empty( $api_secret ) ) {
			update_option( 'dms_' . strtolower( self::NAME ) . '_api_key', $api_key );
			update_option( 'dms_' . strtolower( self::NAME ) . '_api_secret', $api_secret );
			$_SESSION['dms_admin_success'][] = __( 'Successfully saved.', $this->dms->plugin_name );
		} else {
			$_SESSION['dms_admin_warning'][] = __( 'Api key and secret are required',
				$this->dms->plugin_name );
		}

		wp_safe_redirect( $referer );
		exit();
	}

	/**
	 * Save tenant domain as main
	 *
	 * @throws Exception
	 */
	public function setTenantMainDomain() {
		// Check nonce
		check_admin_referer( 'save_dms_wpcs_set_tenant_main_domain_action',
			'save_dms_wpcs_set_tenant_main_domain_nonce' );
		$referer = wp_get_referer();
		if ( empty( session_id() ) ) {
			session_start();
		}
		$wpdb                  = $this->dms->wpdb;
		$map_id                = isset( $_POST['map_id'] ) ? (int) sanitize_text_field( $_POST['map_id'] ) : '';
		$domain                = DMS_Helper::getDomainByMapId( $map_id, $wpdb );
		$currentMain           = DMS_Helper::getBaseHost();
		$set                   = false;
		$possible_substitution = array(
			'old_main' => base64_encode( $currentMain ),
			'new_main' => base64_encode( $domain ),
		);
		// Keep domains encoded in order to revert them later after main domain setup
		update_option( 'dms_platform_wpcs_domains_possible_substitution', $possible_substitution );
		if ( ! empty( $domain ) ) {
			$this->checkSetCredentials();
			if ( $this->ifConfigsAreOk() ) {
				$tenant_id = $this->getTenant();
				if ( ! empty( $tenant_id ) ) {
					$response = wp_remote_request( $this->api_url . 'tenants/domains/main?tenantId=' . $tenant_id,
						array(
							'headers' => [
								'Authorization' => 'Basic ' . base64_encode( $this->api_key . ':' . $this->api_secret ),
								'Content-Type'  => 'application/json'
							],
							'method'  => 'PUT',
							'body'    => json_encode( [
								'domainName' => $domain
							] ),
							'timeout' => 30,
						) );
					if ( ! empty( $response ) && ! ( $response instanceof WP_Error ) ) {
						$body = wp_remote_retrieve_body( $response );
						if ( ! empty( $body ) ) {
							$body_obj = @json_decode( $body );
							if ( ! empty( $body_obj->domainName ) ) {
								// Set save timeout
								update_option( 'dms_' . strtolower( self::NAME ) . '_last_save_delay',
									time() + $this->delay_after_save );
								$set = true;
							}
						}
					} elseif ( ! empty( $response ) && $response instanceof WP_Error ) {
						$error_message = __( $response->get_error_message(), $this->dms->plugin_name );
					}
				}
			} else {
				$error_message = __( 'Missing required hosting configuration' );
			}
		}
		// Store result
		if ( $set ) {
			$_SESSION['dms_admin_success'][] = __( 'Successfully set main.', $this->dms->plugin_name );
		} else {
			// Remove possible subs
			delete_option( 'dms_platform_wpcs_domains_possible_substitution' );
			if ( empty( $error_message ) ) {
				$error_message = __( 'Failed to set as main', $this->dms->plugin_name );
			}
			$_SESSION['dms_admin_warning'][] = $error_message;
		}

		wp_safe_redirect( $referer );
		exit();
	}

	/**
	 * Draw form for credentials save
	 *
	 * @return void
	 */
	public function drawForm() {
		$DMS        = $this->dms;
		$api_key    = get_option( 'dms_' . strtolower( self::NAME ) . '_api_key' );
		$api_secret = get_option( 'dms_' . strtolower( self::NAME ) . '_api_secret' );
		?>
        <form action="<?= admin_url( 'admin-post.php' ); ?>" method="post">
            <h4 class="dms-conf-title"><?= __( 'Configuration Details', $DMS->plugin_name ) ?></h4>
            <div class="dms-conf-label-wrapper">
                <label for="api_key"><?= __( 'Api key:', $DMS->plugin_name ) ?></label>
                <input id="api_key" type="text" size="30" value="<?= ! empty( $api_key ) ? esc_attr( $api_key ) : '' ?>"
                       name="api_key"
                       placeholder="">
            </div>
            <div class="dms-conf-label-wrapper">
                <label for="api_secret"><?= __( 'Api secret:', $DMS->plugin_name ) ?></label>
                <input id="api_secret" type="password" size="30"
                       value="<?= ! empty( $api_secret ) ? esc_attr( $api_secret ) : '' ?>"
                       name="api_secret" placeholder="">
            </div>
            <div class="updated">
                <p>
					<?= sprintf( __( 'To create your API Key and Secret, visit %sWPCS Products%s, select a Product, and then click API Keys. 
Please note that only Tenants can manage domains. We’ve detected this is a Version, so the Domains tab is disabled. The Hosting Config tab will not be visible to Tenants.
%s %s Warning: %s If you delete the API Key from WPCS, any Tenants created from this Version will lose the ability to manage domains. Read our %sDocumentation%s for details.',
						$DMS->plugin_name ), '<a target="_blank" href="https://console.eu1.wpcs.io/products">', '</a>',
						'<br><br>', '<strong>', '</strong>',
						'<a target="_blank" href="https://docs.domainmappingsystem.com/integrations/hosting-platforms/wpcs">',
						'</a>' )
					?>
                </p>
            </div>
            <div class="dms-n-row-submit">
                <input type="submit" value="<?php _e('Save', $DMS->plugin_name) ?>"/>
            </div>
            <input name="action" value="save_dms_wpcs" type="hidden">
			<?php wp_nonce_field( 'save_dms_wpcs_action', 'save_dms_wpcs_nonce' ) ?>
        </form>
		<?php
	}

	/**
	 * Draw set tenant domain as main form
	 *
	 * @return void
	 */
	public function drawSetTenantDomainAsMainForm() {
		?>
        <form action="<?= admin_url( 'admin-post.php' ); ?>" method="post"
              id="dms_platform_wpcs_set_tenant_main_domain_form">
            <input type="hidden" id="dms_platform_wpcs_domain_map_id_value" name="map_id" value="">
            <input name="action" value="save_dms_wpcs_set_tenant_main_domain" type="hidden">
			<?php wp_nonce_field( 'save_dms_wpcs_set_tenant_main_domain_action',
				'save_dms_wpcs_set_tenant_main_domain_nonce' ) ?>
        </form>
		<?php
	}

	/**
	 * Draw main domain button
	 */
	public function drawSetTenantMainDomainButton( $map_id, $save_button_disable = false ) {
		$DMS = $this->dms;
		?>
        <button class="button-primary dms-n-config-table-wpcs-set-main-domain"
			<?= $save_button_disable ? 'disabled data-disabled_delay="' . $save_button_disable . '"' : '' ?>
                data-map_id="<?= $map_id ?>"><?= __( 'Set as Main', $DMS->plugin_name ) ?></button>
		<?php
	}

	/*
	 * Print general notification
	 */
	public function printGeneralNotice() {
		_e( 'Please contact your platform provider for the DNS records required to configure the domains below. After configuring a new domain, 
		please wait up to 5 minutes for the domain to be resolving properly.',
			$this->dms->plugin_name );
	}

	/**
	 * Get platform name
	 *
	 * @return string
	 */
	public function getName() {
		return self::NAME;
	}

	/**
	 * In case platform is active , then mapping is allowed to be saved only in case
	 * credentials exits;
	 *
	 * @return bool
	 */
	public function isAllowedToSaveMapping() {
		return ! empty( get_option( 'dms_' . strtolower( self::NAME ) . '_api_key' ) ) && ! empty( get_option( 'dms_' . strtolower( self::NAME ) . '_api_secret' ) );
	}

	/**
	 * Platform mappings related messages
	 *
	 * @return array
	 */
	public function getMessages() {
		return array(
			'is_not_allowed_to_save_mapping' => __( 'Mappings cannot be saved because the hosting platform API key is misconfigured. Please contact your administrator.',
				$this->dms->plugin_name ),

			'is_not_allowed_to_save_mapping_user_case' => __( 'Mappings cannot be saved.',
				$this->dms->plugin_name )
		);
	}

	/**
	 * Check weather WPCS is running WordPress
	 *
	 * @return bool
	 */
	public static function isActive() {
		return self::isVersion() || self::isTenant();
	}

	/**
	 * Delete domain
	 *
	 * @param $domain
	 *
	 * @return bool|null
	 * @throws Exception
	 */
	public function deleteDomain( $domain ) {
		$this->checkSetCredentials();
		if ( $this->ifConfigsAreOk() ) {
			$tenant_id = $this->getTenant();
			if ( empty( $tenant_id ) ) {
				return null;
			}
			$response = wp_remote_request( $this->api_url . 'tenants/domains?tenantId=' . $tenant_id . '&domainName=' . $domain,
				array(
					'headers' => [
						'Authorization' => 'Basic ' . base64_encode( $this->api_key . ':' . $this->api_secret ),
						'Content-Type'  => 'application/json'
					],
					'method'  => 'DELETE',
					'timeout' => 30,
				) );
			if ( ! empty( $response ) && ! ( $response instanceof WP_Error ) ) {
				$body = wp_remote_retrieve_body( $response );
				if ( ! empty( $body ) ) {
					$body_obj = @json_decode( $body );
					// Not found case could be marked as normal cause. Tenant domain could be deleted from external dashboard
					if ( ! empty( $body_obj->domainName ) || ( ! empty( $body_obj->statusCode ) && $body_obj->statusCode == 404 ) ) {
						return true;
					}
				}
			} else {
				if ( $response instanceof WP_Error ) {
					throw new \Exception( __( $response->get_error_message(), $this->dms->plugin_name ) );
				}
			}

			return null;
		} else {
			throw new \Exception( __( 'Missing required hosting configuration' ), $this->dms->plugin_name );
		}
	}

	/**
	 * Delete domains
	 *
	 * @param  array  $domains
	 *
	 * @return array|null
	 * @throws Exception
	 */
	public function deleteDomains( $domains ) {
		if ( ! empty( $domains ) && is_array( $domains ) ) {
			$deleted = array();
			foreach ( $domains as $item ) {
				$res = $this->deleteDomain( $item->host );
				if ( ! empty( $res ) ) {
					$deleted[] = $item->id;
				} else {
					break;
				}
			}

			return $deleted;
		}

		return null;
	}

	/**
	 * Add domain to main
	 *
	 * @param $domain
	 *
	 * @return bool|null
	 * @throws Exception
	 */
	public function addDomain( $domain ) {
		$this->checkSetCredentials();
		if ( $this->ifConfigsAreOk() ) {
			$tenant_id = $this->getTenant();
			if ( empty( $tenant_id ) ) {
				return null;
			}
			$response = wp_remote_post( $this->api_url . 'tenants/domains?tenantId=' . $tenant_id, array(
				'headers' => [
					'Authorization' => 'Basic ' . base64_encode( $this->api_key . ':' . $this->api_secret ),
					'Content-Type'  => 'application/json'
				],
				'body'    => json_encode( [
					'domainName' => $domain
				] ),
				'timeout' => 30,
			) );
			if ( ! empty( $response ) && ! ( $response instanceof WP_Error ) ) {
				$body = wp_remote_retrieve_body( $response );
				if ( ! empty( $body ) ) {
					$body_obj = @json_decode( $body );
					if ( ! empty( $body_obj->tenantId ) ) {
						return true;
					}
				}
			} else {
				if ( $response instanceof WP_Error ) {
					throw new \Exception( __( $response->get_error_message(), $this->dms->plugin_name ) );
				}
			}

			return null;
		} else {
			throw new \Exception( __( 'Missing required hosting configuration', $this->dms->plugin_name ) );
		}
	}

	/**
	 * Get tenant domains
	 *
	 * @return array|mixed|null
	 * @throws Exception
	 */
	public function getTenantDomains() {
		$this->checkSetCredentials();
		if ( $this->ifConfigsAreOk() ) {
			$tenant_id = $this->getTenant();
			if ( empty( $tenant_id ) ) {
				return null;
			}
			$response = wp_remote_get( $this->api_url . 'tenants/domains?tenantId=' . $tenant_id, array(
				'headers' => [
					'Authorization' => 'Basic ' . base64_encode( $this->api_key . ':' . $this->api_secret ),
					'Content-Type'  => 'application/json'
				],
				'timeout' => 30,
			) );
			if ( ! empty( $response ) && ! ( $response instanceof WP_Error ) ) {
				$body = wp_remote_retrieve_body( $response );
				if ( ! empty( $body ) ) {
					$body_obj = @json_decode( $body );
					if ( is_array( $body_obj ) ) {
						return $body_obj;
					}

					return null;
				}
			} else {
				if ( $response instanceof WP_Error ) {
					throw new \Exception( __( $response->get_error_message(), $this->dms->plugin_name ) );
				}
			}

			return null;
		} else {
			throw new \Exception( __( 'Missing required hosting configuration', $this->dms->plugin_name ) );
		}
	}

	/**
	 * Get current tenant id.
	 *
	 * @return string|null
	 * @throws Exception
	 */
	public function getTenant() {
		$existing_saved = get_option( 'dms_' . strtolower( self::NAME ) . '_tenant_id' );
		if ( empty( $existing_saved ) ) {
			$this->checkSetCredentials();
			if ( $this->ifConfigsAreOk() ) {
				$response  = wp_remote_get( $this->api_url . 'tenants', array(
					'headers' => [
						'Authorization' => 'Basic ' . base64_encode( $this->api_key . ':' . $this->api_secret ),
						'Content-Type'  => 'application/json'
					],
					'timeout' => 30,
				) );
				$base_host = DMS_Helper::getBaseHost();
				if ( ! empty( $response ) && ! ( $response instanceof WP_Error ) ) {
					$body = wp_remote_retrieve_body( $response );

					if ( ! empty( $body ) ) {
						$body_obj = @json_decode( $body );
						if ( ! empty( $body_obj ) ) {
							foreach ( $body_obj as $item ) {
								if ( $base_host == $item->domainName ) {
									update_option( 'dms_' . strtolower( self::NAME ) . '_tenant_id', $item->tenantId );
									$tenant_id = $item->tenantId;
									break;
								}
							}
							if ( ! empty( $tenant_id ) ) {
								return $tenant_id;
							}
						}
					}
				}

				return null;
			} else {
				throw new \Exception( __( 'Missing required hosting configuration' ), $this->dms->plugin_name );
			}
		}

		return $existing_saved;
	}

	/**
	 * Check weather Version related website
	 *
	 * @return false|int
	 */
	public static function isVersion() {
		$hostname = ! empty( $_SERVER['HTTP_HOST'] ) ? trim( $_SERVER['HTTP_HOST'], '/' ) : null;
		if ( ! empty( $hostname ) ) {
			return preg_match( '/\.snapshots\.([a-zA-Z0-9]*)\.wpcs\.io$/', $hostname );
		}

		return false;
	}

	/**
	 * Check weather Tenant related website
	 *
	 * @return bool
	 */
	public static function isTenant() {
		return ! empty( getenv( 'WPCS_IS_TENANT' ) );
	}

	/**
	 * Set API credentials
	 */
	private function checkSetCredentials() {
		if ( empty( $this->api_key ) || empty( $this->api_secret ) ) {
			$this->api_key    = get_option( 'dms_' . strtolower( self::NAME ) . '_api_key' );
			$this->api_secret = get_option( 'dms_' . strtolower( self::NAME ) . '_api_secret' );
		}
	}

	/**
	 * Check if required configs are not empty
	 */
	private function ifConfigsAreOk() {
		return ! empty( $this->api_key ) && ! empty( $this->api_secret ) && ! empty( $this->api_url );
	}

	/**
	 * Decide weather to show mapping form
	 *
	 * @return bool
	 */
	public function showMappingForm() {
		if ( self::isVersion() ) {
			return false;
		}

		return true;
	}

	/**
	 * Decide weather to show navigation
	 *
	 * @return bool
	 */
	public function showNavigation() {
		if ( self::isTenant() ) {
			return false;
		}

		return true;
	}

	/**
	 * Decide weather to show config form
	 *
	 * @return bool
	 */
	public function showConfigForm() {
		if ( self::isTenant() ) {
			return false;
		}

		return true;
	}

}