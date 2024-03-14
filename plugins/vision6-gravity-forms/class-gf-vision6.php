<?php

GFForms::include_feed_addon_framework();

/**
 * Vision6 Gravity Forms Add-On.
 *
 * @since     1.0.0
 * @package   GravityForms
 * @copyright Copyright (c) 2018, Vision6
 */
class GFVision6 extends GFFeedAddOn {

	/**
	 * Contains an instance of this class, if available.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    object $_instance If available, contains an instance of this class.
	 */
	private static $_instance = null;

	/**
	 * Defines the version of the Vision6 Add-On.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string $_version Contains the version, defined from vision6.php
	 */
	protected $_version = GF_VISION6_VERSION;

	/**
	 * Defines the minimum Gravity Forms version required.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string $_min_gravityforms_version The minimum version required.
	 */
	protected $_min_gravityforms_version = '2.2.0';

	/**
	 * Defines the plugin slug.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string $_slug The slug used for this plugin.
	 */
	protected $_slug = 'gravityformsvision6';

	/**
	 * Defines the main plugin file.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string $_path The path to the main plugin file, relative to the plugins folder.
	 */
	protected $_path = 'gravityformsvision6/vision6.php';

	/**
	 * Defines the full path to this class file.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string $_full_path The full path.
	 */
	protected $_full_path = __FILE__;

	/**
	 * Defines the URL where this Add-On can be found.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The URL of the Add-On.
	 */
	protected $_url = 'https://wordpress.org/plugins/vision6-gravity-forms/';

	/**
	 * Defines the title of this Add-On.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string $_title The title of the Add-On.
	 */
	protected $_title = 'Vision6 Gravity Forms Add-On';

	/**
	 * Defines the short title of the Add-On.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string $_short_title The short title.
	 */
	protected $_short_title = 'Vision6';

	/**
	 * Defines if Add-On should use Gravity Forms servers for update data.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    bool
	 */
	protected $_enable_rg_autoupgrade = false; // TODO: Set this locally until Gravity Forms takes over

	/**
	 * Defines the capabilities needed for the Vision6 Add-On
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array $_capabilities The capabilities needed for the Add-On
	 */
	protected $_capabilities = array( 'gravityforms_vision6', 'gravityforms_vision6_uninstall' );

	/**
	 * Defines the capability needed to access the Add-On settings page.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string $_capabilities_settings_page The capability needed to access the Add-On settings page.
	 */
	protected $_capabilities_settings_page = 'gravityforms_vision6';

	/**
	 * Defines the capability needed to access the Add-On form settings page.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string $_capabilities_form_settings The capability needed to access the Add-On form settings page.
	 */
	protected $_capabilities_form_settings = 'gravityforms_vision6';

	/**
	 * Defines the capability needed to uninstall the Add-On.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string $_capabilities_uninstall The capability needed to uninstall the Add-On.
	 */
	protected $_capabilities_uninstall = 'gravityforms_vision6_uninstall';

	/**
	 * Defines the Vision6 fields used in the current request.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array $_fields The Vision6 fields used in the current request.
	 */
	protected $_fields = array();

	/**
	 * Contains an instance of the Vision6 API library, if available.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    object $_api If available, contains an instance of the Vision6 API library.
	 */
	private $_api = null;

    /**
     * The list of accepted API hostnames
     *
     * @var array
     */
    protected $_api_hostnames = array(
        'app.vision6.com.au', // This is the default hostname
        'app.vision6.com',
        'app.vision6.in',
    );

    /**
     * Defines the latest details from API folders
     *
     * @var array
     */
	protected $_api_folders = array();

    /**
     * Defines the latest details from API lists
     *
     * @var array
     */
	protected $_api_lists = array();


	/**
	 * Get an instance of this class.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return GFVision6
	 */
	public static function get_instance() {

		if ( null === self::$_instance ) {
			self::$_instance = new self;
		}

		return self::$_instance;

	}

	/**
	 * Autoload the required libraries.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @uses GFAddOn::is_gravityforms_supported()
	 */
	public function pre_init() {

		parent::pre_init();

		if ( $this->is_gravityforms_supported() ) {

            if ( class_exists( 'GF_Field' ) ) {
                require_once( 'includes/class-gf-field-gdprconsent.php' );
            }

			// Load the Vision6 API library.
			if ( ! class_exists( 'GF_Vision6_API' ) ) {
				require_once( 'includes/class-gf-vision6-exception.php' );
				require_once( 'includes/class-gf-vision6-api.php' );
			}

		}

	}

	/**
	 * Plugin starting point. Handles hooks, loading of language files and PayPal delayed payment support.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @uses GFFeedAddOn::add_delayed_payment_support()
	 */
	public function init() {

		parent::init();

		$this->add_delayed_payment_support(
			array(
				'option_label' => esc_html__( 'Subscribe user to Vision6 only when payment is received.', 'gravityformsvision6' ),
			)
		);


        // GDPR Consent field actions and filters
        if ( version_compare( GFForms::$version, '2.5', '>=' ) ) {
            add_filter( 'gform_form_settings_fields', array( $this, 'gdpr_consent_form_settings_fields' ), 10, 2);

        } else {
            add_filter( 'gform_form_settings', array( $this, 'gdpr_consent_form_settings' ), 10, 2);
            add_filter( 'gform_tooltips', array( $this, 'gdpr_consent_tooltips' ), 10, 1);
            add_filter( 'gform_pre_form_settings_save', array( $this, 'gdpr_consent_pre_form_settings_save' ), 10, 1 );
            add_action( 'gform_editor_js_set_default_values', array( $this, 'gdpr_consent_js_defaults' ), 10 );
        }
    }

	/**
	 * Remove unneeded settings.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function uninstall() {

		parent::uninstall();

		GFCache::delete( 'vision6_plugin_settings' );
		delete_option( 'gf_vision6_settings' );
		delete_option( 'gf_vision6_version' );

	}





	// # PLUGIN SETTINGS -----------------------------------------------------------------------------------------------

	/**
	 * Configures the settings which should be rendered on the add-on settings tab.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function plugin_settings_fields() {

	    // Build the description message
	    $description = '<p>';
	    $description .= sprintf(
            esc_html__( 'Vision6 is a provider of email and SMS marketing software designed especially for marketing and agency professionals. Use Gravity Forms to collect customer information and automatically add it to your Vision6 subscriber list. If you don\'t have a Vision6 account, you can %1$ssign up for one here.%2$s', 'gravityformsvision6' ),
            '<a href="https://www.vision6.com.au/" target="_blank">', '</a>'
        );
        $description .= '</p>';


        // Show the terms and conditions acceptance request, if required
        $api_credentials = $this->get_api_credentials();
        if ( $api_credentials['api_key'] && $api_credentials['hostname'] ) {

            $v6_api = new GF_Vision6_API( $api_credentials['api_key'], $api_credentials['hostname'] );

            // Validate API
            if ( $v6_api->is_api_valid() && !$v6_api->is_terms_and_conditions_accepted() ) {

                $description .= '<div class="delete-alert alert_yellow"><strong>';
                $description .= esc_html__( 'Please ensure that your terms and conditions are accepted to successfully validate your Vision6 settings.', 'gravityformsvision6' );
                $description .= '</strong><br> ';
                $description .= sprintf(
                    esc_html__( 'You can do this by %slogging into the Vision6 account%s where the API Key was created and accepting the terms and conditions.', 'gravityformsvision6' ),
                    '<a href="https://www.vision6.com.au/" target="_blank">', '</a>'
                );
                $description .= '</div>';

            }
        }


	    // Return the fields
        $hostname_choices = array();
        foreach ( $this->_api_hostnames as $hostname ) {
            $hostname_choices[] = array(
                'label' => $hostname,
                'value' => $hostname,
            );
        }

		return array(
			array(
				'description' => $description,
				'fields'      => array(
					array(
						'name'          => 'api_key',
						'label'         => esc_html__( 'API Key', 'gravityformsvision6' ),
						'type'          => 'text',
						'class'         => 'medium',
                        'tooltip'       => sprintf(
                            '<h6>%s</h6>%s',
                            esc_html__( 'API Key', 'gravityformsvision6' ),
                            __( 'Keys can be managed within Vision6\'s Integrations > API Keys page.', 'gravityformsvision6' )
                        ),
						'feedback_callback' => function( $value, $field ) {

						    if ( rgblank( $value ) ) {
						        return null;
                            }

						    return $this->initialize_api();
                        },
                        'validation_callback' => function( $field, $value ) {

                            if ( rgblank( $value ) ) {
                                $field->set_error( __( 'The API Key must be provided', 'gravityformsvision6' ) );
                                return;
                            }

                            $is_valid = false;
                            $field->_api_hostname = '';
                            foreach ( $this->_api_hostnames as $hostname ) {
                                if ( !$is_valid && $this->initialize_api( $value, $hostname ) ) {
                                    $is_valid = true;
                                    $this->_api_hostname = $hostname;
                                }
                            }

                            if ( !$is_valid ) {
                                $field->set_error( __( 'The API Key is not valid', 'gravityformsvision6' ) );
                                return;
                            }

                            $field->do_validation( $value );
                        },
					),
                    array(
                        'name'          => 'api_hostname',
                        'label'         => esc_html__( 'API Hostname', 'gravityformsvision6' ),
                        'tooltip'       => sprintf(
                            '<h6>%s</h6>%s',
                            esc_html__( 'API Hostname', 'gravityformsvision6' ),
                            __( 'The hostname is provided within the JSON-RPC API Endpoint on Vision6\'s Integrations > API Keys page.', 'gravityformsvision6' )
                        ),
                        'type'          => 'select',
                        'default_value' => $hostname_choices[0]['value'],
                        'choices'       => $hostname_choices,
                        'hidden'        => true, // NOTE: We automate the selection of hostnames within the api_key's `validation_callback` function
                        'validation_callback' => function( $field, $value ) {

                            if ( !in_array( $value, $this->_api_hostnames )) {
                                $field->set_error( __( 'The API Hostname must be selected', 'gravityformsvision6' ) );
                                return;
                            }

                            $field->do_validation( $value );
                        },
                        'save_callback' => function( $field, $value ) {

                            if ( !empty($this->_api_hostname) && in_array( $this->_api_hostname, $this->_api_hostnames )) {
                                return $this->_api_hostname;
                            }

                            return $value;
                        },
                    ),
				),
            ),
		);

	}





	// # FEED SETTINGS -------------------------------------------------------------------------------------------------

	/**
	 * Configures the settings which should be rendered on the feed edit page.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function feed_settings_fields() {

        // Load folders
        $folder_choices = $this->get_folders_as_choices();
        $selected_folder_id = 0;


        // Load lists
        if ( ! empty( $folder_choices ) ) {
            $selected_folder_id = $this->selected_folder_id();

            if ( $selected_folder_id ) {
                $this->get_lists_as_choices($selected_folder_id);
            }
        }


        // Show the fields
		$fields = array(
			array(
				'title'  => esc_html__( 'Vision6 Feed Settings', 'gravityformsvision6' ),
				'fields' => array(
					array(
						'name'     => 'feedName',
						'label'    => esc_html__( 'Name', 'gravityformsvision6' ),
						'type'     => 'text',
						'required' => true,
						'class'    => 'medium',
						'tooltip'  => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'Name', 'gravityformsvision6' ),
							esc_html__( 'Enter a feed name to uniquely identify this setup.', 'gravityformsvision6' )
						),
					),
					array(
						'name'          => 'vision6_folder_id',
						'label'         => esc_html__( 'Vision6 Folder', 'gravityformsvision6' ),
                        'type'          => 'select',
                        'required'      => true,
                        'choices'       => $folder_choices,
                        'onchange'      => 'jQuery(this).parents("form").first().submit();',
                        'no_choices'    => sprintf(
                            esc_html__( 'No folders found. Please add one or more to your %sVision6%s account.', 'gravityformsvision6' ),
                            '<a href="http://www.vision6.com.au" target="_blank">',
                            '</a>'
                        ),
						'tooltip'  => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'Vision6 List', 'gravityformsvision6' ),
							esc_html__( 'Select the Vision6 folder you would like to select a list from.', 'gravityformsvision6' )
						),
					),
					array(
						'name'          => 'vision6_list_id',
						'label'         => esc_html__( 'Vision6 List', 'gravityformsvision6' ),
                        'type'          => 'select',
                        'required'      => true,
                        'choices'       => $this->get_lists_as_choices($selected_folder_id),
                        'onchange'      => 'jQuery(this).parents("form").first().submit();',
                        'no_choices'    => sprintf(
                            esc_html__( 'No lists found. Please add one or more to your %sVision6%s account.', 'gravityformsvision6' ),
                            '<a href="http://www.vision6.com.au" target="_blank">',
                            '</a>'
                        ),
						'tooltip'  => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'Vision6 List', 'gravityformsvision6' ),
							esc_html__( 'Select the Vision6 list you would like to add your contacts to.', 'gravityformsvision6' )
						),
					),
					array(
						'name'          => 'listFields',
						'label'         => esc_html__( 'Map Fields', 'gravityformsvision6' ),
						'type'          => 'field_map',
						'field_map'     => $this->get_list_fields_as_field_map(),
                        'merge_tags'    => [],
                        'dependency'    => array( $this, 'selected_list_id' ),
                        'no_choices'    => esc_html__( 'No fields found. Please add one or more fields to your list.', 'gravityformsvision6' ),
						'tooltip'       => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'Map Fields', 'gravityformsvision6' ),
							esc_html__( 'Associate your Vision6 fields to the appropriate Gravity Form fields by selecting the appropriate form field from the list.', 'gravityformsvision6' )
						),
					),
                    array(
                        'name'          => 'feed_method',
                        'label'         => esc_html__( 'Feed Method', 'gravityformsvision6' ),
                        'type'          => 'radio',
                        'dependency'    => array( $this, 'selected_list_id' ),
                        'default_value' => 'subscribe_contact',
                        'choices'       => array(
                            array(
                                'label' => esc_html__( 'Subscribe Contact', 'gravityformsvision6' ),
                                'name'  => 'subscribe_contact',
                                'value' => 'subscribe_contact',
                                'tooltip'  => sprintf(
                                    '<h6>%s</h6>%s<br><br> <strong>%s</strong><br><br> %s <a href="%s">%s</a>.',
                                    esc_html__( 'Add Contact', 'gravityformsvision6' ),
                                    esc_html__( 'Subscribes a single Contact to a List. New contacts will trigger associated Subscribe Events or Autoresponders.', 'gravityformsvision6' ),
                                    esc_html__( 'This is required for the GDPR Consent field.', 'gravityformsvision6' ),
                                    esc_html__( 'View the', 'gravityformsvision6' ),
                                    'https://developers.vision6.com.au/3.3/method/subscribecontact',
                                    esc_html__( 'subscribeContact API Documentation', 'gravityformsvision6' )
                                )
                            )
                         ),
                        'required' => true
                    ),
					array(
						'name'    => 'optinCondition',
						'label'   => esc_html__( 'Conditional Logic', 'gravityformsvision6' ),
						'type'    => 'feed_condition',
                        'dependency' => array( $this, 'selected_list_id' ),
						'tooltip' => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'Conditional Logic', 'gravityformsvision6' ),
							esc_html__( 'When conditional logic is enabled, form submissions will only be exported to Vision6 when the conditions are met. When disabled all form submissions will be exported.', 'gravityformsvision6' )
						),
					),
					array(
					    'type' => 'save',
                        'dependency' => array( $this, 'selected_list_id' ),
                    ),
				),
			),
		);


		// Only allow addContacts an an option if this is not a GDPR Compliant list
        if ( !$this->selected_list_id( 'is_compliant' ) ) {

            foreach ( $fields[0]['fields'] as $key => $field ) {

                if ( !isset( $field['name'] ) || $field['name'] !== 'feed_method' ) {
                    continue;
                }


                $fields[0]['fields'][$key]['choices'][] = array(
                    'label' => esc_html__( 'Add Contact', 'gravityformsvision6' ),
                    'name'  => 'add_contacts',
                    'value' => 'add_contacts',
                    'tooltip'  => sprintf(
                        '<h6>%s</h6>%s<br><br> %s <a href="%s">%s</a>.',
                        esc_html__( 'Add Contact', 'gravityformsvision6' ),
                        esc_html__( 'Subscribes a single Contact to a List. The "API Upload" Campaign Event is triggered for each Contact that is successfully added or overwritten.', 'gravityformsvision6' ),
                        esc_html__( 'View the', 'gravityformsvision6' ),
                        'https://developers.vision6.com.au/3.3/method/addcontacts',
                        esc_html__( 'addContacts API Documentation', 'gravityformsvision6' )
                    )
                );

            }

        }

        return $fields;
	}

	/**
     * Get available Vision6 folders as choices.
	 *
	 * @since  1.0.0
	 * @access public
	 *
     * @return array
     */
	public function get_folders_as_choices() {

		// If API is not initialized, return.
		if ( ! $this->initialize_api() ) {
			return array();
		}

		if ( empty( $this->_api_folders ) ) {
            try {

                // Log folders request parameters.
                $this->log_debug( __METHOD__ . '(): Retrieving folders' );

                // Get folders.
                $folders = $this->_api->get_folders();

            } catch ( Exception $e ) {

                // Log that folders could not be obtained.
                $this->log_error( __METHOD__ . '(): Could not retrieve Vision6 folders; ' . $e->getMessage() );

                // Display error message.
                printf( esc_html__( 'Could not load Vision6 folders. %sError: %s', 'gravityformsvision6' ), '<br/>', $e->getMessage() );

                return array();

            }
		} else {
            $folders = $this->_api_folders;
        }

		// If no folders were found, display error message.
		if ( 0 === count( $folders ) ) {

			// Log that no folders were found.
			$this->log_error( __METHOD__ . '(): Could not load Vision6 folders; no folders found.' );

			return array();

		}

		// Log number of folders retrieved.
		$this->log_debug( __METHOD__ . '(): Number of folders: ' . count( $folders ) );

		$this->_api_folders = $folders;


        // Initialize choices array.
        $choices = array(
            array(
                'label' => esc_html__( 'All Folders', 'gravityformsvision6' ),
                'value' => 0,
            )
        );

        // Loop through Vision6 folders.
		foreach ( $folders as $folder ) {

			// Add folder to select options.
            $choices[] = array(
				'label' => esc_html( $folder['name'] ) . ' (' . (!empty($folder['item_count']) ? (string)$folder['item_count'] : '0') . ')',
				'value' => esc_attr( $folder['id'] ),
			);

		}

		return $choices;

	}

	/**
     * Get available Vision6 lists as choices.
	 *
	 * @since  1.0.0
	 * @access public
	 *
     * @return array
     */
	public function get_lists_as_choices( $folder_id = 0 ) {

		// If API is not initialized, return.
		if ( ! $this->initialize_api() ) {
			return array();
		}

		if ( empty( $this->_api_lists[$folder_id] ) ) {
            try {

                // Log lists request parameters.
                $this->log_debug( __METHOD__ . '(): Retrieving lists' );

                // Get lists.
                $lists = $this->_api->get_lists($folder_id);

            } catch ( Exception $e ) {

                // Log that lists could not be obtained.
                $this->log_error( __METHOD__ . '(): Could not retrieve Vision6 lists; ' . $e->getMessage() );

                // Display error message.
                printf( esc_html__( 'Could not load Vision6 lists. %sError: %s', 'gravityformsvision6' ), '<br/>', $e->getMessage() );

                return array();

            }
		} else {
            $lists = $this->_api_lists[$folder_id];
        }

		// If no lists were found, display error message.
		if ( 0 === count( $lists ) ) {

			// Log that no lists were found.
			$this->log_error( __METHOD__ . '(): Could not load Vision6 lists; no lists found.' );

			return array();

		}

		// Log number of lists retrieved.
		$this->log_debug( __METHOD__ . '(): Number of lists: ' . count( $lists ) );

        $this->_api_lists[$folder_id] = $lists;


        // Initialize choices array.
        $choices = array(
            array(
                'label' => esc_html__( 'Select a List', 'gravityformsvision6' ),
                'value' => '',
            )
        );

        // Loop through Vision6 lists.
		foreach ( $lists as $list ) {

			// Add list to select options.
            $choices[] = array(
				'label' => esc_html( $list['name'] ),
				'value' => esc_attr( $list['id'] ),
			);

		}

		return $choices;

	}


    /**
     * Does the folder that is selected currently exist in the folders provided by the API?
     *
     * @return int
     */
    public function selected_folder_id() {

        $selected_folder_id = (int)$this->get_setting( 'vision6_folder_id' );

        if ( $selected_folder_id && $this->_api_folders ) {
            $selected_folder_index = array_search( $selected_folder_id, array_column( $this->_api_folders, 'id' ) );

            if ( $selected_folder_index === false ) {
                $selected_folder_id = 0;
            }
        }

        return ! empty( $selected_folder_id ) ? $selected_folder_id : 0;
    }


    /**
     * Does the list that is selected currently exist in the lists provided by the API?
     *
     * @param string $gdpr_check
     * @return int
     */
    public function selected_list_id( $gdpr_check = 'none' ) {

        $selected_list_id = (int) $this->get_setting( 'vision6_list_id' );
        $selected_folder_id = (int) $this->get_setting( 'vision6_folder_id' );

        if ( $selected_list_id && !empty( $this->_api_lists[$selected_folder_id] ) ) {
            $selected_list_index = array_search( $selected_list_id, array_column( $this->_api_lists[$selected_folder_id], 'id' ) );

            if ( $selected_list_index === false) {
                $selected_list_id = 0;

            } else if ( $selected_list_index !== false && ( $gdpr_check === 'is_compliant' || $gdpr_check === 'is_not_compliant') ) {

                $gdpr_compliant = isset( $this->_api_lists[$selected_folder_id][$selected_list_index]['is_gdpr_compliant'] ) && (bool) $this->_api_lists[$selected_folder_id][$selected_list_index]['is_gdpr_compliant'];
                if ( $gdpr_check === 'is_compliant' && !$gdpr_compliant ) {
                    $selected_list_id = 0;

                } else if ( $gdpr_check === 'is_not_compliant' && $gdpr_compliant ) {
                    $selected_list_id = 0;
                }

            }

        }

        return ! empty( $selected_list_id ) ? $selected_list_id : 0;
    }


	/**
	 * Return an array of Vision6 list fields which can be mapped to the Form fields/entry meta.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_list_fields_as_field_map() {

        // If unable to initialize API, return field map.
        if ( ! $this->initialize_api() ) {
            return array();
        }


        // Ensure that when we're changing the folders that an empty folder doesn't show the default list instead of no lists
        $posted_settings = $this->get_posted_settings();
        if ( empty( $posted_settings['vision6_list_id'] ) && !empty( $posted_settings['vision6_folder_id'] ) ) {
            return array();
        }


        // Get current list ID and fields.
        $list_id = $this->get_setting( 'vision6_list_id' );
        $fields  = $this->get_list_fields( $list_id );


		// If fields exist, add to field map.
		$field_map = [];

		if ( ! empty( $fields ) ) {

			// Loop through fields.
			foreach ( $fields as $field ) {

				// Define required field type.
				$field_type          = null;
				$exclude_field_types = [];

				switch ( $field['type'] ) {

					case 'text':
						if ( $field['address_type'] === 'email' ) {
							$field_type = 'email';
						}

						$exclude_field_types = [ 'fileupload' ];
						break;

					case 'date':
						$field_type = 'date';
						break;

					case 'decimal':
						$field_type = 'number';
						break;

					case 'file':
						$field_type = 'fileupload';
						break;

				}


				// Add to field map.
				$field_item = [
					'name'  => $field['id'],
					'label' => $field['name'],
				];

				if ( $field_type ) {
					$field_item['field_type'] = $field_type;
				}

				if ( $exclude_field_types ) {
					$field_item['exclude_field_types'] = $exclude_field_types;
				}

				if ( (bool) $field['is_mandatory'] ) {
					$field_item['required'] = true;
				}

				$field_map[ $field['id'] ] = $field_item;

			}

		}

		return $field_map;
	}

	/**
	 * Prevent feeds being listed or created if the API key isn't valid.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return bool
	 */
	public function can_create_feed() {

		return $this->initialize_api();

	}

	/**
	 * Configures which columns should be displayed on the feed list page.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function feed_list_columns() {

		return array(
			'feedName'          => esc_html__( 'Name', 'gravityformsvision6' ),
			'vision6_list_name' => esc_html__( 'Vision6 List', 'gravityformsvision6' ),
		);

	}

	/**
	 * Returns the value to be displayed in the Vision6 List column.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $feed The feed being included in the feed list.
	 *
	 * @return string
	 */
	public function get_column_value_vision6_list_name( $feed ) {

		// If unable to initialize API, return the list ID.
		if ( ! $this->initialize_api() ) {
			return rgars( $feed, 'meta/vision6_list_id' );
		}

		try {

			// Get list.
			$list = $this->_api->get_list( rgars( $feed, 'meta/vision6_list_id' ) );

			// Return list name.
			return rgar( $list, 'name' );

		} catch ( Exception $e ) {

			// Log error.
			$this->log_error( __METHOD__ . '(): Unable to get Vision6 list for feed list; ' . $e->getMessage() );

			// Return list ID.
			return rgars( $feed, 'meta/vision6_list_id' );

		}

	}


	// # FEED PROCESSING -----------------------------------------------------------------------------------------------

	/**
	 * Process the feed, subscribe the user to the list.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $feed The feed object to be processed.
	 * @param array $entry The entry object currently being processed.
	 * @param array $form The form object currently being processed.
	 *
	 * @return array
	 */
	public function process_feed( $feed, $entry, $form ) {

		// Log that we are processing feed.
		$this->log_debug( __METHOD__ . '(): Processing feed.' );


		// If unable to initialize API, log error and return.
		if ( ! $this->initialize_api() ) {
			$this->add_feed_error( esc_html__( 'Unable to process feed because API could not be initialized.', 'gravityformsvision6' ), $feed, $entry, $form );

			return $entry;
		}


		// Look for the list ID
		$list_id = rgar( $feed['meta'], 'vision6_list_id' );
		if ( ! $list_id ) {
			$this->add_feed_error( esc_html__( 'Unable to process feed because list ID could not be found.', 'gravityformsvision6' ), $feed, $entry, $form );

			return $entry;
		}

        $feed_method = rgar( $feed['meta'], 'feed_method' );


		// Get the list fields and cross check the current field IDs
        try {
            $list_fields = $this->_api->get_list_fields( $list_id );

        } catch ( \Exception $e ) {
            $this->add_feed_error( sprintf( esc_html__( 'Unable to process feed because searching for list fields failed: %s', 'gravityformsvision6' ), $e->getMessage() ), $feed, $entry, $form );

            return $entry;
        }

		$list_field_names = [];
		if ( $list_fields ) {
			foreach ( $list_fields as $field ) {
				$list_field_names[ $field['id'] ] = $field['name'];
			}
		}


		// Loop through field map.
		$field_map = $this->get_field_map_fields( $feed, 'listFields' );

		$contact_details = [];
		foreach ( $field_map as $v6_field_id => $gf_field_id ) {

			// Ensure that the field is still in the list
			$v6_field_name = rgar( $list_field_names, $v6_field_id );
			if ( ! $v6_field_name ) {
				continue;
			}


			// Get field value.
			$field_value = $this->get_field_value( $form, $entry, $gf_field_id );

			if ( rgblank( $field_value ) ) {
				continue;
			}


			$contact_details[ $v6_field_name ] = $field_value;
		}

		if ( ! sizeof( $contact_details ) ) {
			$this->add_feed_error( esc_html__( 'Unable to process feed because no fields are available.', 'gravityformsvision6' ), $feed, $entry, $form );

			return $entry;
		}


		// Get field map.
        $contact_id = 0;

		try {
            if ( $feed_method === 'add_contacts' ) {
                $response = $this->_api->add_contacts( rgars( $feed, 'meta/vision6_list_id' ), $contact_details );
                $contact_id = isset($response[0]) ? $response[0] : 0;

            } else {

                // Manage the GDPR Consent fields
                $gdpr_consent_type   = 'not_recorded';
                $gdpr_consent_text   = '';

                $gdpr_consent_field_id = $this->get_first_field_by_type( 'gdprconsent' );
                if ( $gdpr_consent_field_id ) {
                    $gdpr_consent_text  = $this->get_field_value( $form, $entry, $gdpr_consent_field_id );
                    $gdpr_consent_type  = 'direct';

                    if ( $gdpr_consent_text && is_scalar( $gdpr_consent_text ) ) {
                        $gdpr_consent_type = 'gdpr';
                    } else {
                        $gdpr_consent_text = '';
                    }
                }

                $response = $this->_api->subscribe_contact( rgars( $feed, 'meta/vision6_list_id' ), $contact_details, $gdpr_consent_type, $gdpr_consent_text, GFFormsModel::get_ip() );
                $contact_id = isset($response[0]) ? $response[0] : 0;
            }


            // Log responses
            $this->log_debug( __METHOD__ . '(): New Contact ID: ' . (string)$contact_id );

            return $entry;

		} catch ( GF_Vision6_Exception $e ) {
            $this->add_feed_error( sprintf( esc_html__( 'Contact could not be created: %s', 'gravityformsvision6' ), $e->getMessage() ), $feed, $entry, $form );


            // If there is a GDPR consent failure, use addContacts as a fallback
            if ( !$contact_id && $feed_method === 'subscribe_contact' ) {

                // Get the error code
                $client = $e->getClient();
                $error_code = 0;
                if ( $client && $client instanceof WP_HTTP_IXR_Client ) {
                    if ( $client->error && isset( $client->error->code ) && is_numeric( $client->error->code ) ) {
                        $error_code = (int)$client->error->code;
                    } elseif ( $client->message && isset( $client->message->faultCode ) && is_numeric( $client->message->faultCode ) ) {
                        $error_code = (int)$client->message->faultCode;
                    }
                }


                // Re-run if the GDPR code is provided
                if ( $error_code === 335 ) {
                    $this->log_debug(__METHOD__ . '(): Switching from subscribeContact to addContacts due to error 335.');

                    $feed['meta']['feed_method'] = 'add_contacts';
                    return $this->process_feed( $feed, $entry, $form );
                }

            }

            return [];

        } catch ( \Exception $e ) {
            $this->add_feed_error( sprintf( esc_html__( 'Contact could not be created: %s', 'gravityformsvision6' ), $e->getMessage() ), $feed, $entry, $form );

			return [];
		}
	}

	/**
	 * Returns the value of the selected field.
	 *
	 * @param array $form The form object currently being processed.
	 * @param array $entry The entry object currently being processed.
	 * @param string $field_id The ID of the field being processed.
	 *
	 * @return array
	 */
	public function get_field_value( $form, $entry, $field_id ) {

		switch ( strtolower( $field_id ) ) {

			case 'form_title':
				$field_value = rgar( $form, 'title' );
				break;

			case 'date_created':
				$date_created = rgar( $entry, strtolower( $field_id ) );
				if ( empty( $date_created ) ) {
					//the date created may not yet be populated if this function is called during the validation phase and the entry is not yet created
					$field_value = gmdate( 'Y-m-d H:i:s' );
				} else {
					$field_value = $date_created;
				}
				break;

			case 'ip':
			case 'source_url':
				$field_value = rgar( $entry, strtolower( $field_id ) );
				break;

			default:

				$field = GFFormsModel::get_field( $form, $field_id );

				if ( is_object( $field ) ) {

					$is_integer = $field_id == intval( $field_id );
					$input_type = GFFormsModel::get_input_type( $field );

					if ( $is_integer && $input_type == 'address' ) {

						$field_value = $this->get_full_address( $entry, $field_id );

					} elseif ( $is_integer && $input_type == 'name' ) {

						$field_value = $this->get_full_name( $entry, $field_id );

					} elseif ( $is_integer && $input_type == 'checkbox' ) {

						$field_values = array();
						foreach ( $field->inputs as $input ) {
							$index  = (string) $input['id'];
							$value  = $this->maybe_override_field_value( rgar( $entry, $index ), $form, $entry, $index );

							if ( $value ) {
                                $field_values[] = $value;
                            }
						}

                        $field_value = implode(',', $field_values);

					} elseif ( $input_type == 'multiselect' ) {

                        $field_value = $this->maybe_override_field_value( rgar( $entry, $field_id ), $form, $entry, $field_id );

					} elseif ( GFCommon::is_product_field( $field->type ) && $field->enablePrice ) {

						$ary         = explode( '|', rgar( $entry, $field_id ) );
						$field_value = count( $ary ) > 0 ? $ary[0] : '';

					} else {

						if ( is_callable( array( 'GF_Field', 'get_value_export' ) ) ) {
							$field_value = $field->get_value_export( $entry, $field_id );
						} else {
							$field_value = rgar( $entry, $field_id );
						}

					}

				} else {

					$field_value = $this->maybe_override_field_value( rgar( $entry, $field_id ), $form, $entry, $field_id );
				}

		}

		return $field_value;
	}





	// # HELPERS -------------------------------------------------------------------------------------------------------

	/**
	 * Initializes Vision6 API if credentials are valid.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @uses GFAddOn::get_plugin_setting()
	 * @uses GFAddOn::log_debug()
	 * @uses GFAddOn::log_error()
	 * @uses GF_Vision6_API::account_details()
	 *
     * @param string|null $api_key
     * @param string|null $hostname
     * @return bool|null
     */
	public function initialize_api($api_key = null, $hostname = null) {

		// If API is alredy initialized, return true.
		if ( ! is_null( $this->_api ) ) {
			return true;
		}

		// Collect the api credentials
        if ( $api_key !== null || $hostname !== null ) {
            $api_credentials = array(
                'api_key'   => (string)$api_key,
                'hostname'  => (string)$hostname,
            );

        } else {
            $values = $this->get_posted_settings();
            if ( rgar( $values, 'api_key' ) ) {
                $api_credentials = array(
                    'api_key'   => rgar( $values, 'api_key' ),
                    'hostname'  => rgar( $values, 'api_hostname' ),
                );

            } else {
                $api_credentials = $this->get_api_credentials();
            }
        }

        if ( !rgar( $api_credentials, 'api_key' ) ) {
		    return null;
        }

		// Setup a new Vision6 object with the API credentials.
        $this->log_debug( __METHOD__ . '(): Validating API Info.' );
		$v6_api = new GF_Vision6_API( $api_credentials['api_key'], $api_credentials['hostname'], true );

		// Validate API
		if ( $v6_api->is_api_valid() ) {

		    if ( $v6_api->is_terms_and_conditions_accepted() ) {

                // Assign API library to class.
                $this->_api = $v6_api;

                // Log that authentication test passed.
                $this->log_debug( __METHOD__ . '(): Vision6 successfully authenticated.' );

                return true;

            } else {

                // Log that terms and conditions test failed.
                $this->log_error( __METHOD__ . '(): The terms and conditions are not accepted' );

                return false;
            }

		} else {
			// Log that authentication test failed.
			$this->log_error( __METHOD__ . '(): Unable to authenticate with Vision6' );

			return false;
		}

	}

    /**
     * Return a list of API credentials
     *
     * @return string[]
     */
	protected function get_api_credentials()
    {
        // Get the API key
        $api_key = trim($this->get_plugin_setting( 'api_key' ));

        // Get and validate the API key
        $api_hostname = trim($this->get_plugin_setting( 'api_hostname' ));

        if (!$api_hostname) {

            // Use the legacy API endpoint to choose the hostname
            $api_endpoint = trim($this->get_plugin_setting( 'api_endpoint' ));
            if ($api_endpoint) {
                $api_hostname = parse_url($api_endpoint, PHP_URL_HOST);
            }
        }

        if (!in_array($api_hostname, $this->_api_hostnames)) {

            // We force the first hostname by default
            $api_hostname = $this->_api_hostnames[0];
        }

        return [
            'api_key'   => $api_key,
            'hostname'  => $api_hostname,
        ];
    }

	/**
	 * Returns the combined value of the specified Address field.
	 * Street 2 and Country are the only inputs not required by Vision6.
	 * If other inputs are missing Vision6 will not store the field value, we will pass a hyphen when an input is empty.
	 * Vision6 requires the inputs be delimited by 2 spaces.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $entry The entry currently being processed.
	 * @param string $field_id The ID of the field to retrieve the value for.
	 *
	 * @return array|null
	 */
	public function get_full_address( $entry, $field_id ) {

		// Initialize address array.
		$address = array(
			'addr1'   => str_replace( '  ', ' ', trim( rgar( $entry, $field_id . '.1' ) ) ),
			'addr2'   => str_replace( '  ', ' ', trim( rgar( $entry, $field_id . '.2' ) ) ),
			'city'    => str_replace( '  ', ' ', trim( rgar( $entry, $field_id . '.3' ) ) ),
			'state'   => str_replace( '  ', ' ', trim( rgar( $entry, $field_id . '.4' ) ) ),
			'zip'     => trim( rgar( $entry, $field_id . '.5' ) ),
			'country' => trim( rgar( $entry, $field_id . '.6' ) ),
		);

		// Get address parts.
		$address_parts = array_values( $address );

		// Remove empty address parts.
		$address_parts = array_filter( $address_parts );

		// If no address parts exist, return null.
		if ( empty( $address_parts ) ) {
			return null;
		}

		// Replace country with country code.
		if ( ! empty( $address['country'] ) ) {
			$address['country'] = GF_Fields::get( 'address' )->get_country_code( $address['country'] );
		}

		return $address;

	}

	/**
	 * Get Vision6 fields for list.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param string $list_id List ID to get fields for.
	 *
	 * @uses GFVision6::initialize_api()
	 * @uses GF_Vision6_API::get_list_fields()
	 *
	 * @return array
	 */
	public function get_list_fields( $list_id = '' ) {

		// If no list ID was provided or if API cannot be initialized, return.
		if ( rgblank( $list_id ) ) {
			return array();
		}

		// If fields have already been retrieved, return.
		if ( isset( $this->_fields[ $list_id ] ) ) {
			return $this->_fields[ $list_id ];
		}

		try {

			// Get fields.
			$fields = $this->_api->get_list_fields( $list_id );

			// Sort by display order
			usort( $fields, function ( $a, $b ) {
				$order_a = rgar( $a, 'display_order', 999 );
				$order_b = rgar( $b, 'display_order', 999 );

				if ( $order_a == $order_b ) {
					return 0;
				}

				return ( $order_a < $order_b ) ? - 1 : 1;
			} );

			$this->_fields[ $list_id ] = $fields;


		} catch ( Exception $e ) {

			// Log error.
			$this->log_error( __METHOD__ . '(): Unable to get fields for Vision6 list; ' . $e->getMessage() );

			$this->_fields[ $list_id ] = array();

		}

		return $this->_fields[ $list_id ];

	}



    // # GDPR CONSENT FIELD ------------------------------------------------------------------------------------------

    /**
     * Add the GDPR Consent Summary text settings to the form settings page
     *
     * @param $fields
     * @param $form
     * @return array
     */
    public function gdpr_consent_form_settings_fields($fields, $form) {

        $fields['form_basics']['fields'][] = array(
            'name'          => 'gdprConsentSummary',
            'type'          => 'textarea',
            'label'         => esc_html__( 'GDPR Consent Summary', 'gravityforms' ),
            'allow_html'    => true,
            'default_value' => $this->gdpr_consent_value( $form ),
            'tooltip'       => '<h6>' . __( 'GDPR Consent Summary', 'gravityforms' ) . '</h6>' . __( 'This is the text that will be shown within all GDPR Consent fields to allow users to provide data collection consent.', 'gravityformsvision6' ),
        );

        return $fields;
    }

    /**
     * Add the GDPR Consent Summary text settings to the form settings page
     *
     * @deprecated 1.0.9
     *
     * @param $form_settings
     * @param $form
     * @return mixed
     */
    public function gdpr_consent_form_settings($form_settings, $form) {

        $consent_summary = $this->gdpr_consent_value( $form );
        $editor_settings = array(
            'autop' => false,
            'editor_class' => 'merge-tag-support mt-wp_editor mt-manual_position mt-position-right',
            'textarea_rows' => 8,
        );

        ob_start();
        ?>
        <tr>
            <th>
                <?php echo __( 'GDPR Consent Summary', 'gravityforms' ) . ' ' . gform_tooltip( 'gdpr_consent_summary', '', true ); ?>
            </th>
            <td>
                <?php
                wp_editor( $consent_summary, 'gdpr_consent_summary', $editor_settings )
                ?>
            </td>
        </tr>
        <?php
        $form_settings[__( 'Form Basics', 'gravityforms' )]['gdpr_consent_summary'] = ob_get_contents();
        ob_clean();

        return $form_settings;
    }

    /**
     * Get the current value of the  GDPR Consent Summary text from a form
     *
     * @param $form
     * @return string
     */
    public function gdpr_consent_value($form) {

        $consent_summary = rgar( $form, 'gdprConsentSummary' );
        if ( !$consent_summary ) {
            $consent_summary = esc_html__('I consent to ' . get_bloginfo('name') . ' collecting and using my information for marketing purposes in accordance with their Privacy Policy.');
        }

        return $consent_summary;
    }


    /**
     * Add the tooltip text for the GDPR Consent Summary text settings
     *
     * @deprecated 1.0.9
     *
     * @param $gf_tooltips
     * @return mixed
     */
    public function gdpr_consent_tooltips($gf_tooltips) {
        $gf_tooltips['gdpr_consent_summary'] = '<h6>' . __( 'GDPR Consent Summary', 'gravityforms' ) . '</h6>' . __( 'This is the text that will be shown within all GDPR Consent fields to allow users to provide data collection consent.', 'gravityformsvision6' );

        return $gf_tooltips;
    }


    /**
     * Save the GDPR Consent Summary submitted data
     *
     * @deprecated 1.0.9
     *
     * @param $updated_form
     * @return mixed
     */
    public function gdpr_consent_pre_form_settings_save($updated_form) {
        $updated_form['gdprConsentSummary'] = rgpost( 'gdpr_consent_summary' );

        return $updated_form;
    }


    /**
     * Add javascript to set the GDPR Consent field defaults on the frontend
     *
     * @deprecated 1.0.9
     */
    public function gdpr_consent_js_defaults() {
        ?>
        case "gdprconsent" :
            if (!field.label)
                field.label = <?php echo json_encode( esc_html__( 'Information Collection', 'gravityforms' ) ); ?>;
            break;
        <?php
    }

}
