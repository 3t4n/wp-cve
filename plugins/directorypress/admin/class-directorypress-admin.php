<?php

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/admin
 * @author     Designinvento <developers@designinvento.net>
 */
class DirectoryPress_Admin {

	
	private $plugin_name;
	private $version;
	
	public function __construct() {
		global $directorypress_object;
		if(is_object($directorypress_object)){
			$directorypress_object->listings_handler_property = new directorypress_listings_admin;

			$directorypress_object->fields_handler_property = new directorypress_fields_admin;
			
			$directorypress_object->media_handler_property = new directorypress_media_handler;
			$directorypress_object->locations_handler = new directorypress_locations_manager;
			$directorypress_object->terms_validator = new directorypress_terms_validator;
		}
		// Admin Actions
		
		add_action('admin_menu', array($this, 'addChooseLevelPage'));
		add_action('load-post-new.php', array($this, 'handleLevel'));
		add_action('admin_footer', array($this, 'listing_admin_action_modal'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'), 0);
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'), 0);
		add_action('admin_notices', 'directorypress_renderMessages');
		
	}
	
	public function addChooseLevelPage() {
		add_submenu_page('options.php',
			__('Choose package of new listing', 'DIRECTORYPRESS'),
			__('Choose package of new listing', 'DIRECTORYPRESS'),
			'publish_posts',
			'directorypress_choose_package',
			array($this, 'chooseLevelsPage')
		);
	}

	public function chooseLevelsPage() {
		global $directorypress_object;

		$directorypress_object->packages_manager->displayChooseLevelTable();
	}
	
	public function handleLevel() {
		global $directorypress_object;

		if (isset($_GET['post_type']) && $_GET['post_type'] == DIRECTORYPRESS_POST_TYPE) {
			if (!isset($_GET['package_id'])) {
				// adapted for WPML
				global $sitepress;
				if (function_exists('wpml_object_id_filter') && $sitepress && isset($_GET['trid']) && isset($_GET['lang']) && isset($_GET['source_lang'])) {
					global $sitepress;
					$listing_id = $sitepress->get_original_element_id_by_trid(esc_attr($_GET['trid']));
					
					$listing = new directorypress_listing();
					$listing->directorypress_init_lpost_listing($listing_id);
					wp_redirect(add_query_arg(array('post_type' => 'dp_listing', 'package_id' => $listing->package->id, 'trid' => esc_attr($_GET['trid']), 'lang' => esc_attr($_GET['lang']), 'source_lang' => esc_attr($_GET['source_lang'])), admin_url('post-new.php')));
				} else {
					if(directorypress_is_payment_manager_active()){
						if (count($directorypress_object->packages->packages_array) != 1) {
							wp_redirect(add_query_arg('page', 'directorypress_choose_package', admin_url('options.php')));
						} else {
							$single_package = array_shift($directorypress_object->packages->packages_array);
							wp_redirect(add_query_arg(array('post_type' => 'dp_listing', 'package_id' => $single_package->id), admin_url('post-new.php')));
						}
					}else{
						$package = $directorypress_object->packages->get_default_package();
						$package_id = $package->id;
						wp_redirect(add_query_arg(array('post_type' => 'dp_listing', 'package_id' => $package_id), admin_url('post-new.php')));
					}
				}
				die();
			}
		}
	}
	public function listing_admin_action_modal() {
		global $directorypress_object;

		if (isset($_GET['post_type']) && $_GET['post_type'] == DIRECTORYPRESS_POST_TYPE) {
			?>
			<div id="listing_admin_configure" class="modal fade directorypress-admin-modal" role="dialog">
				<div class="modal-dialog modal-dialog-centered">
				<!-- Modal content-->
					<div class="modal-content">
						<div class="topline"></div>
						<div class="modal-body"></div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default cancel-btn" data-dismiss="modal"><?php echo esc_html__('Cancel', 'directorypress-extended-locations'); ?></button>
						</div>
					</div>
				</div>
			</div>
<?php
		}
	}
	public function enqueue_styles() {
		global $pagenow, $post;
		// Register Admin Styles
		
		wp_register_style('bootstrap', DIRECTORYPRESS_RESOURCES_URL . 'lib/bootstrap/css/bootstrap.min.css');
		wp_register_style('fontawesome', DIRECTORYPRESS_RESOURCES_URL . 'lib/fontawesome/css/all.min.css');
		wp_register_style('directorypress-select2', DIRECTORYPRESS_RESOURCES_URL . 'lib/select2/css/select2.css');
		wp_register_style('jquery-ui-style', DIRECTORYPRESS_RESOURCES_URL .'css/jqueryui/themes/smoothness/jquery-ui.min.css');
		wp_register_style('directorypress-backend-listing', DIRECTORYPRESS_URL . 'admin/assets/css/backend-listing.min.css');
		wp_register_style('directorypress_admin', DIRECTORYPRESS_URL . 'admin/assets/css/admin.css');
		wp_register_style('directorypress_backend_panel', DIRECTORYPRESS_URL . 'admin/assets/css/directorypress-panel.css');
		wp_register_style('directorypress_admin_notice', DIRECTORYPRESS_URL . 'admin/assets/css/admin_notice.css');
		wp_register_style('material-icons', DIRECTORYPRESS_RESOURCES_URL . 'lib/material-icons/material-icons.min.css');
		
		//wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-datepicker');
		
		
		wp_enqueue_style('directorypress_admin_notice');
		
		if(directorypress_is_admin_directory_page() || directorypress_is_listing_admin_edit_page() || directorypress_is_categoriesEditPageInAdmin() || (isset($_GET['post_type']) && $_GET['post_type'] == DIRECTORYPRESS_POST_TYPE)){
			wp_enqueue_style('bootstrap');
			wp_enqueue_style('jquery-ui-style');
			wp_enqueue_style('directorypress_backend_panel');
			wp_enqueue_style('material-icons');
			wp_enqueue_style('fontawesome');
		}
		if(directorypress_is_listing_admin_edit_page()){    
				
			wp_enqueue_style('directorypress-backend-listing');	
		}
		
	
	}
	
	public function enqueue_scripts() {
		global $pagenow;
		
		// Register Scripts
		wp_register_script('directorypress-public', DIRECTORYPRESS_RESOURCES_URL . 'js/directorypress-public.js', array('jquery'), false, true);
		wp_register_script('bootstrap', DIRECTORYPRESS_RESOURCES_URL . 'lib/bootstrap/js/bootstrap.min.js', array('jquery'));
		wp_register_script('directorypress-select2', DIRECTORYPRESS_RESOURCES_URL . 'lib/select2/js/select2.min.js', array('jquery'));
		wp_register_script('directorypress-select2-triger', DIRECTORYPRESS_RESOURCES_URL . 'lib/select2/js/select2-triger.js', array('jquery'));
		wp_register_script('directorypress_admin_script', DIRECTORYPRESS_URL . 'admin/assets/js/directorypress-admin.js', array('jquery'));
		wp_register_script('directorypress-terms', DIRECTORYPRESS_RESOURCES_URL . 'js/directorypress-terms.js', array('jquery'));
		
		if(directorypress_is_admin_directory_page() || directorypress_is_listing_admin_edit_page() || directorypress_is_categoriesEditPageInAdmin() || (isset($_GET['post_type']) && $_GET['post_type'] == DIRECTORYPRESS_POST_TYPE)){
			
			// Enqueue Scripts
			add_action('admin_head', array($this, 'enqueue_global_vars'));
			wp_enqueue_script('directorypress_admin_script');
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_script('bootstrap');
			wp_enqueue_script('directorypress-select2');
			wp_enqueue_script('directorypress-select2-triger');
		}
		if (directorypress_is_listing_admin_edit_page()) {
			
			wp_enqueue_script('directorypress-terms');
			wp_enqueue_script('directorypress-public');
			
			wp_localize_script(
				'directorypress-public',
				'directorypress_maps_function_call',
				array(
						'callback' => 'directorypress_init_backend_map_api'
				)
			);
		}
		
		
		
		
	}
	
	public function enqueue_global_vars() {
		
		global $sitepress, $DIRECTORYPRESS_ADIMN_SETTINGS;
		if (function_exists('wpml_object_id_filter') && $sitepress) {
			$ajaxurl = admin_url('admin-ajax.php?lang=' .  $sitepress->get_current_language());
		} else
			$ajaxurl = admin_url('admin-ajax.php');

		echo '<script>';
			echo 'var directorypress_js_instance = ' . json_encode(
				array(
						'ajaxurl' => $ajaxurl,
						'has_map' => directorypress_has_map(),
						'is_rtl' => is_rtl(),
						'is_admin' => (int)is_admin(),
						'img_spacer' => DIRECTORYPRESS_RESOURCES_URL .('images/media-button-image.gif'),
				)
			).';
			';
			if(directorypress_has_map()){
				global $directorypress_google_maps_styles;
		
				$mapbox_api = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_mapbox_api_key']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_mapbox_api_key']))? $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_mapbox_api_key']: '';
		
				echo 'var directorypress_maps_instance = ' . json_encode(
					array(
						'notinclude_maps_api' => ((defined('DIRECTORYPRESS_NOTINCLUDE_MAPS_API') && DIRECTORYPRESS_NOTINCLUDE_MAPS_API) ? 1 : 0),
						'google_api_key' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_google_api_key'],
						'mapbox_api_key' => $mapbox_api,
						'map_markers_type' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_map_markers_type'],
						'default_marker_color' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_marker_color'],
						'default_marker_icon' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_marker_icon'],
						'global_map_icons_path' => DIRECTORYPRESS_MAP_ICONS_URL,
						'default_geocoding_location' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_geocoding_location'],
						'map_style' => directorypress_map_style_selected(),
						'address_autocomplete_code' => (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_address_autocomplete_code']))? $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_address_autocomplete_code']: '',
						'enable_my_location_button' => 1,
					)
				) . ';
				';
			}
		echo '</script>';
	}

}
