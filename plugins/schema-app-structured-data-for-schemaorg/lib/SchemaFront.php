<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/**
 * Description of schema-editor
 *
 * @author Mark van Berkel
 */
class SchemaFront
{
	public $Settings;
	public $wpseo_meta_description = '';

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct()
	{
		$this->Settings = get_option( 'schema_option_name' );

		add_action( 'plugins_loaded', array( $this, 'hook_plugins_loaded' ) );
		add_action( 'init', array( $this, 'HandleCache' ) );
		add_action( 'rest_api_init', array( $this, 'hook_rest_api_init' ) );

		if ( ! empty( $this->Settings['SchemaLinkedOpenData'] ) ) {
			add_action( 'send_headers', array( $this, 'linked_open_data_headers' ) );
			add_action( 'wp', array( $this, 'linked_open_data_output' ), 10, 1 );
			add_action( 'wp_head', array( $this, 'linked_open_data_link_tag' ) );
		}

		// Do not change priority of following hooks as it breaks hook chaining and functions like wp_localize_script
		if ( ! empty( $this->Settings['SchemaDefaultLocation'] ) && $this->Settings['SchemaDefaultLocation'] == 'Footer' )
		{
			add_action( 'wp_footer', array( $this, 'hunch_schema_add' ) );
		}
		else
		{
			add_action( 'wp_head', array( $this, 'hunch_schema_add' ) );
		}

		if ( ! empty( $this->Settings['SchemaRemoveMicrodata'] ) )
		{
			add_action( 'template_redirect', array( $this, 'TemplateRedirect' ), 0 );
		}

		if ( ! empty( $this->Settings['ToolbarShowTestSchema'] ) )
		{
			add_action( 'admin_bar_menu', array( $this, 'AdminBarMenu' ), 999 );
		}

		// Priority 15 ensures it runs after Genesis itself has setup.
		add_action( 'genesis_setup', array( $this, 'GenesisSetup' ), 15 );

		add_action( 'amp_post_template_head', array( $this, 'AMPPostTemplateHead' ) );
		add_filter( 'amp_post_template_metadata', '__return_false', 100 );
		add_filter( 'amp_schemaorg_metadata', '__return_false', 100 );
	}


	public function hook_plugins_loaded() {
		if ( defined( 'WPSEO_VERSION' ) ) {
			// Default enabled
			if ( ! isset( $this->Settings['SchemaRemoveWPSEOMarkup'] ) || $this->Settings['SchemaRemoveWPSEOMarkup'] == 1 ) {
				//https://developer.yoast.com/features/schema/api/
				add_filter( 'wpseo_json_ld_output', '__return_false', 100 );
			}

			if ( WPSEO_VERSION >= 14 ) {
				add_filter( 'wpseo_metadesc', array( $this, 'wpseo_meta_description' ), 10, 2 );
			}
		}
	}


	public function HandleCache()
	{
		if ( isset( $_GET['Action'], $_GET['URL'] ) && $_GET['Action'] == 'HSDeleteMarkupCache' )
		{
			delete_transient( 'HunchSchema-Markup-' . md5( sanitize_text_field( $_GET['URL'] ) ) );

			header( 'HTTP/1.0 202 Accepted', true, 202 );

			exit;
		}
	}


	public function hook_rest_api_init() {
		register_rest_route( 'hunch_schema', '/cache/', array(
			array( 'methods' => 'GET', 'callback' => array( $this, 'rest_api_cache' ), 'permission_callback' => '__return_true' ),
			array( 'methods' => 'POST', 'callback' => array( $this, 'rest_api_cache_modify' ), 'permission_callback' => '__return_true' ),
		) );
	}


	public function rest_api_cache( $request ) {
		return new WP_REST_Response( array( 'status' => 'error', 'message' => 'Please use POST method to send data' ), 405 );
	}


	public function rest_api_cache_modify( $request ) {
		// Log request when debug is on
		if ( ! empty( $this->Settings['Debug'] ) ) {
			$upload_dir_param			= wp_upload_dir( null, false );
			$rest_api_cache_log_file	= $upload_dir_param['basedir'] . DIRECTORY_SEPARATOR . 'schema_app_rest_api_cache_log.txt';

			$this->create_log( $rest_api_cache_log_file, sprintf( "Body:\n%s\n\n\n", esc_attr( $request->get_body() ) ) );
		}

		$request_data	= json_decode( $request->get_body() );
		$site_domain	= str_replace( array( 'http://', 'https://' ), '', site_url() );
		$home_domain	= str_replace( array( 'http://', 'https://' ), '', home_url() );

		// custom_domain is captured via output from Filter hook. Otherwise, returns empty string
		$custom_domain = apply_filters('hunch_schema_page_path_url', '');

		if ( empty( $request->get_body() ) || empty( $request_data ) ) {
			if ( ! empty( $this->Settings['Debug'] ) ) {
				$this->create_log( $rest_api_cache_log_file, 'Result: Invalid JSON data' );
			}

			return new WP_REST_Response( array( 'status' => 'error', 'message' => 'Invalid JSON data' ), 400 );
		}

		// Check for Account Id
		$account_id			= trim( str_replace( array( 'http://schemaapp.com/db/', 'https://schemaapp.com/db/' ), '', $this->Settings['graph_uri'] ), '/' );
		$request_account_id	= trim( str_ireplace( array( 'https://data.schemaapp.com/', '__highlighter_js', $request_data->{"base64encode"} ), '', $request_data->{"url"} ), '/' );

		if ( $request_account_id != $account_id ) {
			if ( ! empty( $this->Settings['Debug'] ) ) {
				$this->create_log( $rest_api_cache_log_file, sprintf( 'Result: Invalid url property, Account Id does not match. url: %s Account Id: %s', esc_url( $request_data->{"url"} ), esc_attr( $account_id ) ) );
			}

			return new WP_REST_Response( array( 'status' => 'error', 'message' => 'Invalid url property, Account Id does not match' ), 401 );
		}

		// @graph is empty for EntityDeleted
		if ( empty( $request_data->{"@type"} ) || empty( $request_data->{"@id"} ) || ( $request_data->{"@type"} != 'EntityDeleted' && empty( $request_data->{"@graph"} ) ) ) {
			if ( ! empty( $this->Settings['Debug'] ) ) {
				$this->create_log( $rest_api_cache_log_file, 'Result: Invalid @type, @id or @graph property' );
			}

			return new WP_REST_Response( array( 'status' => 'error', 'message' => 'Invalid @type, @id or @graph property' ), 400 );
		}

		// Determine if the @id contains either the site_domain, home_domain, or the custom_domain (if specified)
		// If the @id contains none of the specified domains, reject this request
		if ( stripos( $request_data->{"@id"}, $site_domain ) === false 
			&& stripos( $request_data->{"@id"}, $home_domain ) === false 
			&& ($custom_domain ? stripos( $request_data->{"@id"}, $custom_domain) : false ) === false
		) {
			if ( ! empty( $this->Settings['Debug'] ) ) {
				$this->create_log( $rest_api_cache_log_file, 
					sprintf( 'Result: Invalid @id property, url does not match. @id: %s Site Domain: %s Home Domain: %s Custom Domain: %s', 
						esc_attr( $request_data->{"@id"} ), esc_attr( $site_domain ), esc_attr( $home_domain ) , esc_attr( $custom_domain )
					) 
				);
			}

			return new WP_REST_Response( array( 'status' => 'error', 'message' => 'Invalid @id property, url does not match' ), 400 );
		}


		// Remove anchor from permalink so that cache hash matches WP url
		if ( stripos( $request_data->{"@id"}, '#' ) !== false ) {
			$permalink = strstr( $request_data->{"@id"}, '#', true );
		} else {
			$permalink = $request_data->{"@id"};
		}

		$transient_id = 'HunchSchema-Markup-' . md5( $permalink );

		if ( ! empty( $this->Settings['Debug'] ) ) {
			$this->create_log( $rest_api_cache_log_file, sprintf( "Permalink: %s\nTransient: %s", esc_url( $permalink ), esc_attr( $transient_id ) ) );
		}

		switch ( $request_data->{"@type"} ) {
			case 'EntityCreated':
			case 'EntityUpdated':
				// First delete then set; set method only updates expiry time if transient already exists
				delete_transient( $transient_id );
                $schema_data = ['markup' => $request_data->{"@graph"}, 'source' => $request_data->{"source"}];
                set_transient( $transient_id, wp_json_encode( $schema_data ), 86400 );

				if ( ! empty( $this->Settings['Debug'] ) ) {
					$this->create_log( $rest_api_cache_log_file, 'Result: Cache updated' );
				}

				// Clear the WPEngine page cache if it exists
				if ( method_exists( 'WpeCommon', 'purge_varnish_cache' ) && $this->Settings['page_cache_delete'] ) {
					$postId = url_to_postid($permalink);
					if ( $postId ) {
						\WpeCommon::purge_varnish_cache($postId);
					}
				}

				return new WP_REST_Response( array( 'status' => 'ok', 'message' => 'Cache updated' ), 200 );
				break;
			case 'EntityDeleted':
				delete_transient( $transient_id );

				if ( ! empty( $this->Settings['Debug'] ) ) {
					$this->create_log( $rest_api_cache_log_file, 'Result: Cache deleted' );
				}

				// Clear the WPEngine page cache if it exists
				if ( method_exists( 'WpeCommon', 'purge_varnish_cache' ) && $this->Settings['page_cache_delete'] ) {
					$postId = url_to_postid($permalink);
					if ( $postId ) {
						\WpeCommon::purge_varnish_cache($postId);
					}
				}

				return new WP_REST_Response( array( 'status' => 'ok', 'message' => 'Cache deleted' ), 200 );
				break;
			default:
				if ( ! empty( $this->Settings['Debug'] ) ) {
					$this->create_log( $rest_api_cache_log_file, 'Result: Invalid @type property' );
				}

				return new WP_REST_Response( array( 'status' => 'error', 'message' => 'Invalid @type property' ), 400 );
		}
	}


	/**
	 * hunch_schema_add looks up schema.org or adds default markup, calling post_to_page_count after markup has been
	 * generated.
	 *
	 * @param $JSON boolean If true, prints JSON directly instead of printing markup as HTML elements.
	 * @return void
	*/
	public function hunch_schema_add( $JSON = false )
    {
        try {
            global $post;

            if (empty($post)) {
                return;
            }

            $PostType = get_post_type();


            if (is_singular()) {
                $global_markup = true;
                $single_markup_disable = get_post_meta($post->ID, '_HunchSchemaDisableMarkup', true);
                $single_markup_enable = get_post_meta($post->ID, '_HunchSchemaEnableMarkup', true);

                if ($PostType == 'page' && isset($this->Settings['SchemaDefaultShowOnPage']) && $this->Settings['SchemaDefaultShowOnPage'] == 0) {
                    $global_markup = false;
                }

                if ($PostType == 'post' && isset($this->Settings['SchemaDefaultShowOnPost']) && $this->Settings['SchemaDefaultShowOnPost'] == 0) {
                    $global_markup = false;
                }

                if (($global_markup && $single_markup_disable) || (!$global_markup && !$single_markup_enable)) {
                    return;
                }

                if (class_exists('WooCommerce') && (is_account_page() || is_checkout())) {
                    return;
                }
            }


            $SchemaThing = HunchSchema_Thing::factory($PostType);
            $SchemaServer = new SchemaServer();
            $SchemaMarkup = $SchemaServer->getResource();

            // If $JSON parameter is true, this is populated by the various markup sources
            $JSONSchemaMarkup = [];

            // Populated by the various markup sources, used to compare against cache to determine
            // whether to send to Page Count API
            $DefaultMarkup = [];

            $SchemaMarkupType = '';

            if (empty($SchemaMarkup)) {
                $SchemaMarkupCustom = get_post_meta($post->ID, '_HunchSchemaMarkup', true);

                if ($SchemaMarkupCustom) {
                    $SchemaMarkupType = 'Custom';
                    $SchemaMarkup = $SchemaMarkupCustom;
                } else if (isset($SchemaThing)) {
                    $SchemaMarkupType = 'Default';
                    $SchemaMarkup = $SchemaThing->getResource();
                }
            } else {
                $SchemaMarkupType = 'App';
            }

            do_action('hunch_schema_markup_render', $SchemaMarkup, $SchemaMarkupType, $post, $PostType, $JSON);

            $SchemaMarkup = apply_filters('hunch_schema_markup', $SchemaMarkup, $SchemaMarkupType, $post, $PostType);

            // If SchemaMarkupType is 'Default' or 'App', apply Filter hook
            $RenderSchemaMarkup = true;
            if (in_array($SchemaMarkupType, ['Default', 'App'])) {
                $RenderSchemaMarkup = apply_filters('hunch_schema_render_app_markup', $RenderSchemaMarkup);
            }

            if ($SchemaMarkup !== "" && !is_null($SchemaMarkup)) {
                $DecodedMarkup = json_decode($SchemaMarkup, true);

                if ($JSON) {
                    if (isset($DecodedMarkup['markup'])) {
                        // The [markup, source] is not wrapped in an array. This happens when we have only one App Markup.
                        $DecodedMarkup = [$DecodedMarkup];
                    } elseif (isset($DecodedMarkup['@id'])) {
                        // The default/custom markup is not wrapped in an array
                        $DecodedMarkup = [$DecodedMarkup];
                    }
                    foreach ($DecodedMarkup as $item) {
                        $JSONSchemaMarkup[] = isset($item['markup']) ? $item['markup'] : $item;
                    }
                } else {
                    if ((!empty($this->Settings['Debug']) && is_user_logged_in()) || isset($_GET['hunch_schema_debug'])) {
                        printf("\n<!--\nURL: %s\nTransient: %s\nTransient Id: %s\nData Sources:\n", esc_url($SchemaServer->resource_url), ($SchemaServer->transient ? 'Yes' : 'No'), esc_html($SchemaServer->transient_id));

                        foreach ($SchemaServer->data_sources as $data_source) {
                            printf("%s\n", esc_url($data_source));
                        }

                        print "-->\n";
                    }

                    if ($RenderSchemaMarkup) {
                        if ($SchemaMarkupType == "App") {
                            // App markup, i.e. Editor or Highlighter
                            $markupArrayObjects = json_decode($SchemaMarkup, true);
                            if (isset($markupArrayObjects['markup']) || !isset($markupArrayObjects[0]['markup'])) {
                                // The [markup, source] is not wrapped in an array. This happens when we have only one App Markup.
                                // Or when the cache is not cleared from the previous version, the 1st markup is not in 'markup'
                                // and that means the whole block of markup should be in the json+ld block instead of breaking it.
                                $markupArrayObjects = [$markupArrayObjects];
                            } elseif ($markupArrayObjects['@id']) {
                                // Only one app markup AND
                                // the markup was in the cache prior switching the plugin to this version.
                                $markupArrayObjects = [$markupArrayObjects];
                            }
                            foreach ($markupArrayObjects as $markupObject) {
                                $source = isset($markupObject['source']) ? $markupObject['source'] : null;
                                $encodedMarkup = isset($markupObject['markup']) ? $markupObject['markup'] : $markupObject;
                                if ($source) {
                                    printf('<script type="application/ld+json" data-source="%s" data-schema="%s-%s-%s">%s</script>' . "\n", esc_attr($source), esc_attr($post->ID), esc_attr($PostType), esc_attr($SchemaMarkupType), wp_json_encode($encodedMarkup));
                                } else {
                                    printf('<script type="application/ld+json" data-schema="%s-%s-%s">%s</script>' . "\n", esc_attr($post->ID), esc_attr($PostType), esc_attr($SchemaMarkupType), wp_json_encode($encodedMarkup));
                                }
                            }
                        } else {
                            // Any other markup
                            printf('<script type="application/ld+json" data-schema="%s-%s-%s">%s</script>' . "\n", esc_attr($post->ID), esc_attr($PostType), esc_attr($SchemaMarkupType), wp_json_encode($DecodedMarkup));
                        }
                    }
                }

                // Only include in DefaultMarkup if Default or Custom
                if ($SchemaMarkupType !== 'App') {
                    $DefaultMarkup[] = $DecodedMarkup;
                }
            }

            if (!empty($this->Settings['SchemaWebSite']) && is_front_page()) {
                $SchemaMarkupWebSite = apply_filters('hunch_schema_markup_website', $SchemaThing->getWebSite(), $PostType);

                if (!empty($SchemaMarkupWebSite)) {
                    $DecodedMarkupWebsite = json_decode($SchemaMarkupWebSite);

                    if ($JSON) {
                        $JSONSchemaMarkup[] = $DecodedMarkupWebsite;
                    } else {
                        printf('<script type="application/ld+json" data-schema="Website">%s</script>' . "\n", wp_json_encode($DecodedMarkupWebsite));
                    }

                    $DefaultMarkup[] = $DecodedMarkupWebsite;
                }
            }

            if (!empty($this->Settings['SchemaBreadcrumb']) && method_exists($SchemaThing, 'getBreadcrumb')) {
                $SchemaMarkupBreadcrumb = apply_filters('hunch_schema_markup_breadcrumb', $SchemaThing->getBreadcrumb(), $PostType);

                if (!empty($SchemaMarkupBreadcrumb)) {
                    $DecodedMarkupBreadcrumb = json_decode($SchemaMarkupBreadcrumb);

                    if ($JSON) {
                        $JSONSchemaMarkup[] = $DecodedMarkupBreadcrumb;
                    } else {
                        printf('<script type="application/ld+json" data-schema="Breadcrumb">%s</script>' . "\n", wp_json_encode($DecodedMarkupBreadcrumb));
                    }

                    $DefaultMarkup[] = $SchemaMarkupBreadcrumb;
                }
            }

            if ($JSON && !empty($JSONSchemaMarkup)) {
                if (count($JSONSchemaMarkup) == 1) {
                    $JSONSchemaMarkup = reset($JSONSchemaMarkup);

                    print wp_json_encode($JSONSchemaMarkup);
                } else {
                    print wp_json_encode($JSONSchemaMarkup);
                }
            }

            // Only POST to Page Count API if default markup has changed, and if graph URI is specified
            if ($DefaultMarkup && !empty($this->options['graph_uri'])) {
                $DefaultMarkupChanged = $this->checkCachedDefaultMarkup($post->ID, $DefaultMarkup);
                if ($DefaultMarkupChanged) {
                    $this->post_to_page_count();
                }
            }
        } catch (Throwable $exception) {
            $error = '[SchemaApp Plugin] Error: '. $exception->getMessage() . '. Trace: ' . $exception->getTraceAsString();
            error_log($error, 0);
        }
    }

	/**
	 * Checks transient cache for a post's default schema markup, updating the cache if it has changed or 
	 * if the transient does not exist.
	 * @param string $postId WordPress post ID
	 * @param array $defaultMarkup
	 * @return bool Whether the
	 * @uses get_transient()
	 * @uses set_transient()
	 * @uses delete_transient()
	 * @return bool Whether the markup has changed for this resource
	 */
	public function checkCachedDefaultMarkup(string $postId, array $defaultMarkup)
	{
		$cacheChanged = false;

		// Transient ID for default markup determined by WordPress post ID
		$transientId = sprintf( 'HunchSchema-Markup-Default-%s', md5( $postId ) );

		// Hash contents of $defaultMarkup for comparison
		$hashedMarkup = md5(serialize($defaultMarkup));

		// Retrieve cached markup
		$transient = get_transient( $transientId );

		// Set cache if transient does not exist or has changed
		if ( $transient == false || $transient !== $hashedMarkup) {
			// First delete then set; set method only updates expiry time if transient already exists
			delete_transient($transientId);

			// Set expiry to 2 weeks
			set_transient($transientId, $hashedMarkup, ( 14 * DAY_IN_SECONDS ) );

			$cacheChanged = true;
		}

		return $cacheChanged;
	}

	/**
	 * Sends POST request to Page Count API
	 * @return void
	 */
	public function post_to_page_count()
	{
		// Get AccountId/SubAccount
		$accountIdFull = trim( str_replace( array( 'http://schemaapp.com/db/', 'https://schemaapp.com/db/' ), '', $this->Settings['graph_uri'] ), '/' );
		$accountIdParts = explode('/', $accountIdFull);
		$accountId = ( count($accountIdParts) > 1 ) ? $accountIdParts[0] : $accountIdFull;
		$subAccount = ( count($accountIdParts) > 1 ) ? $accountIdParts[1] : '';

		// customUrl is captured via output from Filter hook. Otherwise, returns empty string
		$customUrl = apply_filters('hunch_schema_page_path_url', '');
		
		// Get Domain
		// Use customUrl if available, otherwise fallback to home URL
		$domain = $customUrl ?: home_url();
		
		// Get Page URL
		$url = HunchSchema_Thing::getPermalink($customUrl);
		
		$payload = [
			'AccountId' => $accountId,
			'SubAccount' => $subAccount,
			'DateSeen' => date('Y-m-d'),
			'Domain' => $domain,
			'Source' => 'DataFeed:WordPress',
			'URL' => $url,
		];

		wp_remote_post( 'https://api.schemaapp.com/pagecount', [
			'headers' => [
				'accept' => 'application/json',
				'content-type' => 'application/json',
				'x-api-key' => 'BiQcqdttWn7eunp8jvxM5oZl3DIx08J42LtTmaaj',
			],
			'body' => json_encode($payload),
			'data_format' => 'body',
		]);
	}


	public function linked_open_data_output( $wp ) {
		$request_headers = array();

		if ( function_exists( 'apache_request_headers' ) ) {	
			$request_headers = apache_request_headers();
		}

		if (  ( ! empty( $_GET['format'] ) && $_GET['format'] == 'application/ld json' )  ||  ( ! empty( $request_headers['Accept'] ) && $request_headers['Accept'] == 'application/ld+json' )  ) {
			$this->hunch_schema_add( true );

			exit;
		}
	}


	public function linked_open_data_headers( $wp ) {
		if ( ! empty( $_GET['format'] ) && $_GET['format'] == 'application/ld json' ) {
			header( 'Content-Type: application/json; charset=UTF-8', true );
		}
	}


	public function linked_open_data_link_tag() {
		printf( '<link rel="alternate" type="application/ld+json" href="%s?format=application/ld+json" title="Structured Descriptor Document (JSON-LD format)">', esc_url( HunchSchema_Thing::getPermalink() ) );
	}


	public function TemplateRedirect()
	{
		ob_start( array( $this, 'RemoveMicrodata' ) );
	}


	public function RemoveMicrodata( $Buffer )
	{
		$Buffer = preg_replace( '/[\s\n]*<(link|meta)(\s|[^>]+\s)itemprop=[\'"][^\'"]*[\'"][^>]*>[\s\n]*/imS', '', $Buffer );

		for ( $I = 1; $I <= 6; $I++ )
		{
			$Buffer = preg_replace( '/(<[^>]*)\sitem(scope|type|prop)(=[\'"][^\'"]*[\'"])?([^>]*>)/imS', '$1$4', $Buffer );
		}

		return $Buffer;
	}


	public function AdminBarMenu( $wp_admin_bar ) {
		$permalink = HunchSchema_Thing::getPermalink();

		if ( $permalink ) {
			$wp_admin_bar->add_node( array(
				'id'    => 'hunch-schema',
				'title' => 'Test Schema',
			) );

			$wp_admin_bar->add_node( array(
				'parent'=> 'hunch-schema',
				'id'    => 'hunch-schema-test-google',
				'title' => 'Google Rich Results',
				'href'  => 'https://search.google.com/test/rich-results?user_agent=2&url=' . urlencode( esc_url( $permalink ) ),
				'meta'  => array(
					'target' => '_blank',
				),
			) );

			$wp_admin_bar->add_node( array(
				'parent'=> 'hunch-schema',
				'id'    => 'hunch-schema-test-schema',
				'title' => 'Schema Markup Validator',
				'href'  => 'https://validator.schema.org/#url=' . urlencode( esc_url( $permalink ) ),
				'meta'  => array(
					'target' => '_blank',
				),
			) );
		}
	}


	public function GenesisSetup()
	{
		$Attributes = get_option( 'schema_option_name_genesis' );

		if ( $Attributes )
		{
			foreach ( $Attributes as $Key => $Value )
			{
				add_filter( 'genesis_attr_' . $Key, array( $this, 'GenesisAttribute' ), 20 );
			}
		}
	}


	public function GenesisAttribute( $Attribute )
	{
		$Attribute['itemtype'] = '';
		$Attribute['itemprop'] = '';
		$Attribute['itemscope'] = '';

		return $Attribute;
	}


	public function AMPPostTemplateHead( $Template )
	{
		$this->hunch_schema_add( false );
	}


	public function wpseo_meta_description( $meta_description, $presentation ) {
		$this->wpseo_meta_description = $meta_description;

		return $meta_description;
	}


	/**
	 * Function to create a log file. By default it limits file size to 1 Mb and prepend the passed data to file. If file does not exist then creates the file. Adds full date/time to log message.
	 *
	 * Uses file_get_contents and file_put_contents functions.
	 *
	 * @param string $file		Full path to log file.
	 * @param string $message	Log message.
	 * @param boolean $append	This parameter decides whether log message will be appende or prepend to log file. If set to true then $message is appended to log file and $length parameter is ignored. 
	 *							If set to false then $message is prepended to log file.
	 * @param integer $length	This parameter limits the log file size in byte, default to 1 Mega byte.
	 * @return file_put_contents return value: integer number of bytes that were written to the file, or boolean FALSE on failure.
	 */
	public function create_log( $file, $message = '', $append = false, $length = 1048576 ) {
		$message = sprintf( "Time: %s\n%s", date( 'c' ), $message );

		if ( file_exists( $file ) ) {
			if ( $append ) {
				return file_put_contents( $file, $message, FILE_APPEND );
			} else {
				if ( $length ) {
					$content = file_get_contents( $file, false, null, 0, $length );
				} else {
					$content = file_get_contents( $file );
				}

				return file_put_contents( $file, "$message\n\n$content" );
			}
		} else {
			return file_put_contents( $file, $message );
		}
	}

}
