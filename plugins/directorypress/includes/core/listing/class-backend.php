<?php 
// check keyword [needs workaround]
class directorypress_listings_admin {
	public $current_listing;
	
	public function __construct() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS, $pagenow;
		
		add_action('add_meta_boxes', array($this, 'add_listing_info_metabox'));
		add_action('add_meta_boxes', array($this, 'add_expiry_metabox'));
		
		if (isset($DIRECTORYPRESS_ADIMN_SETTINGS['message_system']) && ($DIRECTORYPRESS_ADIMN_SETTINGS['message_system'] == 'email_messages' && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_custom_contact_email'])){
			add_action('add_meta_boxes', array($this, 'add_contact_metabox'));
		}
		if (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_status_field']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_status_field']){
			add_action('add_meta_boxes', array($this, 'add_listing_status_field_metabox'));
		}
		if (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_social_links']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_social_links']){
			add_action('add_meta_boxes', array($this, 'add_listing_social_profile_metabox'));
		}
		
		
		add_action('admin_init', array($this, 'get_current_listing'));

		add_action('admin_init', array($this, 'initialize_hooks'));
		
		add_filter('manage_'.DIRECTORYPRESS_POST_TYPE.'_posts_columns', array($this, 'add_listings_table_columns'));
		add_filter('manage_'.DIRECTORYPRESS_POST_TYPE.'_posts_custom_column', array($this, 'manage_listings_table_rows'), 10, 2);
		add_filter('post_row_actions', array($this, 'add_row_actions'), 10, 2);
		
		add_action('restrict_manage_posts', array($this, 'posts_filter_dropdown'));
		add_filter('request', array( $this, 'posts_filter'));
		
		add_action('admin_menu', array($this, 'add_bumpup_page'));
		add_action('admin_menu', array($this, 'add_renewal_page'));
		add_action('admin_menu', array($this, 'add_change_date_page'));
		add_action('admin_menu', array($this, 'add_upgrade_page'));
		add_action('admin_menu', array($this, 'add_bulk_upgrade_page'));

		add_action('admin_menu', array($this, 'add_author_notice_page'));
		
		add_action('admin_footer-edit.php', array($this, 'listing_upgrade_action_bulk'));
		add_action('load-edit.php', array($this, 'handle_listing_upgrade_action_bulk'));

		if ((isset($_POST['publish']) || isset($_POST['save']) || isset($_POST['directorypress_save_as_active'])) && (isset($_POST['post_type']) && $_POST['post_type'] == DIRECTORYPRESS_POST_TYPE)) {
			add_filter('wp_insert_post_empty_content', array($this, 'save_directorytype_meta'), 99, 2);
			add_filter('wp_insert_post_data', array($this, 'listing_validation'), 99, 2);
			add_filter('redirect_post_location', array($this, 'after_save_redirect'));
			add_action('save_post_' . DIRECTORYPRESS_POST_TYPE, array($this, 'save_listing'), 10, 3);
		}

		add_action('icl_make_duplicate', array($this, 'handle_wpml_make_duplicate'), 10, 4);
		add_action('post_updated', array($this, 'avoid_redirection_plugin'), 10, 1);
	}
	
	public function add_listing_info_metabox($post_type) {
		if ($post_type == DIRECTORYPRESS_POST_TYPE) {
			add_meta_box('directorypress_listing_info',
					__('Listing Info', 'DIRECTORYPRESS'),
					array($this, 'listing_info_metabox'),
					DIRECTORYPRESS_POST_TYPE,
					'side',
					'high');
		}
	}

	public function add_expiry_metabox($post_type) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		$listing = directorypress_pull_current_listing_admin();
		if ($post_type == DIRECTORYPRESS_POST_TYPE && !$this->current_listing->package->package_no_expiry && ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_change_expiration_date'] || current_user_can('manage_options'))) {
			add_meta_box('directorypress_listing_expiration_date',
					__('Listing expiration date', 'DIRECTORYPRESS'),
					array($this, 'listing_expiry_metabox'),
					DIRECTORYPRESS_POST_TYPE,
					'normal',
					'high');
		}
	}
	
	public function add_listing_social_profile_metabox($post_type) {
		if ($post_type == DIRECTORYPRESS_POST_TYPE) {
			add_meta_box('listing_social_profiles',
					__('Listing Social Profiles', 'DIRECTORYPRESS'),
					array($this, 'listing_social_profile_metabox'),
					DIRECTORYPRESS_POST_TYPE,
					'normal',
					'high');
		}
	}
	public function add_listing_status_field_metabox($post_type) {
		if ($post_type == DIRECTORYPRESS_POST_TYPE) {
			add_meta_box('listing_status_field',
					__('Status', 'DIRECTORYPRESS'),
					array($this, 'listing_status_metabox'),
					DIRECTORYPRESS_POST_TYPE,
					'normal',
					'high');
		}
	}
	
	
	public function add_contact_metabox($post_type) {
		if ($post_type == DIRECTORYPRESS_POST_TYPE) {
			add_meta_box('directorypress_contact_email',
					__('Contact email', 'DIRECTORYPRESS'),
					array($this, 'listing_contact_metabox'),
					DIRECTORYPRESS_POST_TYPE,
					'normal',
					'high');
		}
	}
	
	public function listing_info_metabox($post) {
		global $directorypress_object;

		$listing = directorypress_pull_current_listing_admin();
		$packages = $directorypress_object->packages;
		directorypress_display_template('partials/listing/metabox/info_metabox.php', array('listing' => $listing, 'packages' => $packages));
	}
	
	public function listing_expiry_metabox($post) {
		global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS;
		$listing = directorypress_pull_current_listing_admin();
		if ($listing->status != 'expired') {
			wp_enqueue_script('jquery-ui-datepicker');

			if ($i18n_file = directorypress_dplf(get_locale())) {
				wp_register_script('datepicker-i18n', $i18n_file, array('jquery-ui-datepicker'));
				wp_enqueue_script('datepicker-i18n');
			}

			// If new listing
			if (!$listing->expiration_date)
				$listing->expiration_date = directorypress_expiry_date(current_time('timestamp'), $listing->package);
			directorypress_display_template('partials/listing/metabox/change_date_metabox.php', array('listing' => $listing, 'dateformat' => directorypress_dpf()));
		} else {
			echo "<p>".__('Renew listing first!', 'DIRECTORYPRESS')."</p>";
			$renew_link = strip_tags(apply_filters('directorypress_renew_option', __('renew listing', 'DIRECTORYPRESS'), $listing));
			if (isset($directorypress_object->dashboard_page_url) && $directorypress_object->dashboard_page_url)
				echo '<br /><a href="' . directorypress_dashboardUrl(array('directorypress_action' => 'renew_listing', 'listing_id' => $listing->post->ID)) . '"><span class="directorypress-field-icon directorypress-icon-refresh"></span>' . esc_html($renew_link) . '</a>';
			else
				echo '<br /><a href="' . admin_url('options.php?page=directorypress_renew&listing_id=' . $listing->post->ID) . '"><span class="directorypress-field-icon directorypress-icon-refresh"></span>' . esc_html($renew_link) . '</a>';
		}
	}
	
	public function listingResurvaMetabox($post) {
		$listing = directorypress_pull_current_listing_admin();
		directorypress_display_template('partials/listing/metabox/resurva_booking.php', array('listing' => $listing));
	}
	
	public function listing_social_profile_metabox($post) {
		$listing = directorypress_pull_current_listing_admin();
		directorypress_display_template('partials/listing/metabox/social_profiles_metabox.php', array('listing' => $listing));
	}
	
	public function listing_status_metabox($post) {
		global $directorypress_object;
		$listing = directorypress_pull_current_listing_admin();
		if ($directorypress_object->fields->is_this_field_slug('status')):
			$directorypress_object->fields_handler_property->directorypress_fields_metabox_by_slug_type('status', 'status', $listing);
		endif; 
	}
	
	

	public function listing_contact_metabox($post) {
		$listing = directorypress_pull_current_listing_admin();

		directorypress_display_template('partials/listing/metabox/contact_email_metabox.php', array('listing' => $listing));
	}
	
	public function add_listings_table_columns($columns) {
		global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS;
		
		$directorypress_columns['directorypress_package'] = __('Package', 'DIRECTORYPRESS') . (($directorypress_object->directorytypes->isMultiDirectory()) ? '/' . __('Directory', 'DIRECTORYPRESS') : '');
		$directorypress_columns['directorypress_expiration_date'] = __('Expiry', 'DIRECTORYPRESS');
		$directorypress_columns['directorypress_status'] = __('Status', 'DIRECTORYPRESS');
		$directorypress_columns['directorypress_notice_to_admin'] = __('Author Note', 'DIRECTORYPRESS');
		$directorypress_columns['directorypress_listing_id'] = __('ID', 'DIRECTORYPRESS');
		return array_slice($columns, 0, 2, true) + $directorypress_columns + array_slice($columns, 2, count($columns)-2, true);
	}
	
	public function manage_listings_table_rows($column, $post_id) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
		
		switch ($column) {
			case "directorypress_package":
				$listing = new directorypress_listing();
				$listing->directorypress_init_lpost_listing($post_id);

				if ($listing->package && $listing->package->is_upgradable())
					echo '<a href="' . admin_url('options.php?page=directorypress_upgrade&listing_id=' . esc_attr($post_id)) . '" title="' . esc_attr__('Change package', 'DIRECTORYPRESS') . '">';
				echo esc_html($listing->package->name);
				if ($listing->package && $listing->package->is_upgradable())
					echo ' <span class="directorypress-field-icon directorypress-li-settings"></span></s>';

				if ($listing->package && !$listing->package->package_no_expiry)
					echo '<br />(' . esc_html($listing->package->get_active_duration_string()) . ')';
				
				if ($directorypress_object->directorytypes->isMultiDirectory()) // needs workaround
					echo '<br />' . esc_html($listing->directorytype->name);
				break;
			case "directorypress_expiration_date":
				$listing = new directorypress_listing();
				$listing->directorypress_init_lpost_listing($post_id);
				if ($listing->package && $listing->package->package_no_expiry)
					_e('No Expiry', 'DIRECTORYPRESS');
				else {
					if (($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_change_expiration_date'] || current_user_can('manage_options')) && $listing->status == 'active')
						echo '<a href="' . admin_url('options.php?page=directorypress_changedate&listing_id=' . esc_attr($post_id)) . '" title="' . esc_attr__('change expiration date', 'DIRECTORYPRESS') . '">' . date_i18n(get_option('date_format') . ' ' . get_option('time_format'), intval($listing->expiration_date)) . '</a>';
					else
						echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), intval($listing->expiration_date));

					if ($listing->status == 'expired') {
						$renew_link = apply_filters('directorypress_renew_option', __('renew listing', 'DIRECTORYPRESS'), $listing);
						echo '<br /><a href="' . admin_url('options.php?page=directorypress_renew&listing_id=' . esc_attr($post_id)) . '"><span class="directorypress-field-icon directorypress-icon-refresh"></span>' . esc_html($renew_link) . '</a>';
					} elseif ($listing->expiration_date > time()) {
						echo '<br />' . human_time_diff(time(), $listing->expiration_date) . '&nbsp;' . __('left', 'DIRECTORYPRESS');
					}
				}
				break;
			case "directorypress_status":
				$listing = new directorypress_listing();
				$listing->directorypress_init_lpost_listing($post_id);
				if ($listing->status == 'active')
					echo '<span class="label label-success">' . __('active', 'DIRECTORYPRESS') . '</span>';
				elseif ($listing->status == 'expired')
					echo '<span class="label label-danger">' . __('expired', 'DIRECTORYPRESS') . '</span>';
				elseif ($listing->status == 'unpaid')
					echo '<span class="label label-warning">' . __('unpaid', 'DIRECTORYPRESS') . '</span>';
				elseif ($listing->status == 'stopped')
					echo '<span class="label label-danger">' . __('stopped', 'DIRECTORYPRESS') . '</span>';
				do_action('directorypress_listing_status_option', $listing);
				break;
			case "directorypress_notice_to_admin":
					$listing = new directorypress_listing();
					$listing->directorypress_init_lpost_listing($post_id);
					if(metadata_exists('post', $post_id, '_notice_to_admin' ) ) {
						$content = get_post_meta($post_id, '_notice_to_admin', true );
						echo '<a href="' . admin_url('options.php?page=directorypress_author_note_to_admin&listing_id=' . esc_attr($post_id)) . '">' . __('Note', 'DIRECTORYPRESS') . '</a>';
					}
				break;
			case "directorypress_listing_id":
					$listing = new directorypress_listing();
					$listing->directorypress_init_lpost_listing($post_id);
						echo esc_html($post_id);
				break;
		}
	}
	
	public function add_row_actions($actions, $post) {
		if ($post->post_type == DIRECTORYPRESS_POST_TYPE){
			$listing = new directorypress_listing();
			$listing->directorypress_init_lpost_listing($post);
			
			if ($listing->package->can_be_bumpup && $listing->status == 'active' && $listing->post->post_status == 'publish' && directorypress_user_permission_to_edit_listing($listing->post->ID)) {
				$raise_up_link = apply_filters('directorypress_raiseup_option', __('raise up listing', 'DIRECTORYPRESS'), $listing);
				$actions['raise_up'] = '<a href="' . admin_url('options.php?page=directorypress_raise_up&listing_id=' . $post->ID) . '"><span class="directorypress-icon-arrow-circle-o-up"></span>' . $raise_up_link . '</a>';
			}
			
		}
		return $actions;
	}
	
	public function posts_filter_dropdown() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS, $pagenow, $directorypress_object;
		if ($pagenow === 'upload.php' || (isset($_GET['post_type']) && $_GET['post_type'] != DIRECTORYPRESS_POST_TYPE))
			return;
		
		echo '<select name="directorypress_post_status_filter">';
		echo '<option value="">' . __('Any post status', 'DIRECTORYPRESS') . '</option>';
		echo '<option ' . selected(directorypress_get_input_value($_GET, 'directorypress_post_status_filter'), 'publish', false ) . 'value="publish">' . __('Published', 'DIRECTORYPRESS') . '</option>';
		echo '<option ' . selected(directorypress_get_input_value($_GET, 'directorypress_post_status_filter'), 'pending', false ) . 'value="pending">' . __('Pending', 'DIRECTORYPRESS') . '</option>';
		echo '<option ' . selected(directorypress_get_input_value($_GET, 'directorypress_post_status_filter'), 'draft', false ) . 'value="draft">' . __('Draft', 'DIRECTORYPRESS') . '</option>';
		echo '</select>';
		
		echo '<select name="directorypress_listing_status_filter">';
		echo '<option value="">' . __('Any listing status', 'DIRECTORYPRESS') . '</option>';
		echo '<option ' . selected(directorypress_get_input_value($_GET, 'directorypress_listing_status_filter'), 'active', false ) . 'value="active">' . __('Active', 'DIRECTORYPRESS') . '</option>';
		echo '<option ' . selected(directorypress_get_input_value($_GET, 'directorypress_listing_status_filter'), 'expired', false ) . 'value="expired">' . __('Expired', 'DIRECTORYPRESS') . '</option>';
		echo '<option ' . selected(directorypress_get_input_value($_GET, 'directorypress_listing_status_filter'), 'unpaid', false ) . 'value="unpaid">' . __('Unpaid', 'DIRECTORYPRESS') . '</option>';
		echo '</select>';
		
		// needs workaround
		if ($directorypress_object->directorytypes->isMultiDirectory()) {
			echo '<select name="directorypress_directory_filter">';
			echo '<option value="">' . __('All directorytypes', 'DIRECTORYPRESS') . '</option>';
			foreach ($directorypress_object->directorytypes->directorypress_array_of_directorytypes AS $directorytype)
				echo '<option ' . selected(directorypress_get_input_value($_GET, 'directorypress_directory_filter'), esc_attr($directorytype->id), false ) . 'value="' . esc_attr($directorytype->id) . '">' . esc_html($directorytype->name) . '</option>';
			echo '</select>';
		}
		
		echo '<select name="directorypress_package_filter">';
		echo '<option value="">' . __('All listings packages', 'DIRECTORYPRESS') . '</option>';
		foreach ($directorypress_object->packages->packages_array AS $package)
			echo '<option ' . selected(directorypress_get_input_value($_GET, 'directorypress_package_filter'), esc_attr($package->id), false ) . 'value="' . esc_attr($package->id) . '">' . esc_html($package->name) . '</option>';
		echo '</select>';
		
		do_action('directorypress_after_post_filter_dropdown');
		
	}
	
	public function posts_filter($vars) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		if (isset($_GET['directorypress_post_status_filter']) && $_GET['directorypress_post_status_filter']) {
			$vars = array_merge(
				$vars,
				array(
						'post_status' => $_GET['directorypress_post_status_filter']
				)
			);
		}
		if (isset($_GET['directorypress_listing_status_filter']) && $_GET['directorypress_listing_status_filter']) {
			$vars = array_merge(
				$vars,
				array(
						'meta_query' => array(
								'relation' => 'AND',
								array(
										'key'     => '_listing_status',
										'value'   => $_GET['directorypress_listing_status_filter'],
								)
						)
				)
			);
		}
		if (isset($_GET['directorypress_directory_filter']) && $_GET['directorypress_directory_filter']) {
			$vars = directorypress_set_directory_args($vars, array($_GET['directorypress_directory_filter']));
		}
		if (isset($_GET['directorypress_package_filter']) && $_GET['directorypress_package_filter']) {
			add_filter('posts_join', array($this, 'package_filter_join'));
			add_filter('posts_where', array($this, 'package_filter_where'));
		}
		$vars = apply_filters('directorypress_after_post_filters', $vars);
		
		return $vars;
	}
	
	function package_filter_join($join = '') {
		global $wpdb;

		if (isset($_GET['directorypress_package_filter']) && $_GET['directorypress_package_filter'])
			$join .= " LEFT JOIN {$wpdb->directorypress_packages_relation} AS directorypress_lr ON directorypress_lr.post_id = {$wpdb->posts}.ID ";
	
		return $join;
	}
	
	public function package_filter_where($where = '') {
		if (isset($_GET['directorypress_package_filter']) && $_GET['directorypress_package_filter'])
			$where .= " AND (directorypress_lr.package_id=" . $_GET['directorypress_package_filter'] . ")";
		
		return $where;
	}

	public function add_bumpup_page() {
		add_submenu_page('options.php',
				__('BumpUp listing', 'DIRECTORYPRESS'),
				__('BumpUp listing', 'DIRECTORYPRESS'),
				'publish_posts',
				'directorypress_raise_up',
				array($this, 'raiseUpListing')
		);
	}
	
	public function raiseUpListing() {
		if (isset($_GET['listing_id']) && ($listing_id = sanitize_text_field($_GET['listing_id'])) && is_numeric($listing_id) && directorypress_user_permission_to_edit_listing($listing_id)) {
			if ($this->get_current_listing($listing_id) && $this->current_listing->status == 'active') {
				$action = 'show';
				$referer = wp_get_referer();
				if (isset($_GET['raiseup_action']) && $_GET['raiseup_action'] == 'raiseup') {
					if ($this->current_listing->process_bumpup()){
						directorypress_add_notification(__('Listing was raised up successfully!', 'DIRECTORYPRESS'));
					}else{
						directorypress_add_notification(__('An error has occurred and listing was not raised up', 'DIRECTORYPRESS'), 'error');
					}
					$action = sanitize_text_field($_GET['raiseup_action']);
					$referer = sanitize_url($_GET['referer']);
				}
				directorypress_display_template('partials/listing/metabox/raise_up.php', array('listing' => $this->current_listing, 'referer' => $referer, 'action' => $action));
			} else
				exit();
		} else
			exit();
	}

	public function add_renewal_page() {
		add_submenu_page('options.php',
				__('Renew listing', 'DIRECTORYPRESS'),
				__('Renew listing', 'DIRECTORYPRESS'),
				'publish_posts',
				'directorypress_renew',
				array($this, 'renewListing')
		);
	}
	
	public function renewListing() {
		if (isset($_GET['listing_id']) && ($listing_id = sanitize_text_field($_GET['listing_id'])) && is_numeric($listing_id) && directorypress_user_permission_to_edit_listing($listing_id)) {
			if ($this->get_current_listing($listing_id)) {
				$action = 'show';
				$referer = wp_get_referer();
				if (isset($_GET['renew_action']) && $_GET['renew_action'] == 'renew') {
					if ($this->current_listing->process_activation(true)){
						directorypress_add_notification(__('Listing was renewed successfully!', 'DIRECTORYPRESS'));
					}else{
						directorypress_add_notification(__('An error has occurred and listing was not renewed', 'DIRECTORYPRESS'), 'error');
					}
					$action = sanitize_text_field($_GET['renew_action']);
					$referer = sanitize_url($_GET['referer']);
				}
				directorypress_display_template('partials/listing/metabox/renew.php', array('listing' => $this->current_listing, 'referer' => $referer, 'action' => $action));
			} else
				exit();
		} else
			exit();
	}
	
	public function add_change_date_page() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		if (current_user_can('manage_options'))
			add_submenu_page('options.php',
					__('Change expiry', 'DIRECTORYPRESS'),
					__('Change expiry', 'DIRECTORYPRESS'),
					'publish_posts',
					'directorypress_changedate',
					array($this, 'change_listing_expiry_page')
			);
	}
	
	public function change_listing_expiry_page() {
		if (isset($_GET['listing_id']) && ($listing_id = sanitize_text_field($_GET['listing_id'])) && is_numeric($listing_id) && directorypress_user_permission_to_edit_listing($listing_id)) {
			if ($this->get_current_listing($listing_id)) {
				$action = 'show';
				$referer = wp_get_referer();
				if (isset($_GET['changedate_action']) && $_GET['changedate_action'] == 'changedate') {
					$this->change_listing_expiry();
					$action = sanitize_text_field($_GET['changedate_action']);
					$referer = sanitize_url($_GET['referer']);
				}
				wp_enqueue_script('jquery-ui-datepicker');

				directorypress_display_template('partials/listing/metabox/change_date.php', array('listing' => $this->current_listing, 'referer' => $referer, 'action' => $action, 'dateformat' => directorypress_dpf()));
			} else
				exit();
		} else
			exit();
	}
	
	public function change_listing_expiry() {
		$directorypress_form_validation = new directorypress_form_validation();
		$directorypress_form_validation->set_rules('expiration_date_tmstmp', __('Expiration date', 'DIRECTORYPRESS'), 'required|integer');
		$directorypress_form_validation->set_rules('expiration_date_hour', __('Expiration hour', 'DIRECTORYPRESS'), 'required|integer');
		$directorypress_form_validation->set_rules('expiration_date_minute', __('Expiration minute', 'DIRECTORYPRESS'), 'required|integer');

		if ($directorypress_form_validation->run()) {
			// show message when expiration date was changed and listing was already created
			if ($this->current_listing->save_listing_expiry($directorypress_form_validation->result_array()) && get_post_meta($this->current_listing->post->ID, '_listing_created', true)) {
				directorypress_add_notification(__('Expiration date of listing was changed successfully!', 'DIRECTORYPRESS'));
				$this->current_listing->directorypress_init_lpost_listing($this->current_listing->post->ID);
			}
		} elseif ($error_string = $directorypress_form_validation->error_array())
			directorypress_add_notification($error_string, 'error');
	}
	
	public function add_upgrade_page() {
		add_submenu_page('options.php',
				__('Change package of listing', 'DIRECTORYPRESS'),
				__('Change package of listing', 'DIRECTORYPRESS'),
				'publish_posts',
				'directorypress_upgrade',
				array($this, 'listing_upgrade_page')
		);
	}
	
	public function listing_upgrade_page() {
		global $directorypress_object;
		
		if (isset($_GET['listing_id']) && ($listing_id = sanitize_text_field($_GET['listing_id'])) && is_numeric($listing_id) && directorypress_user_permission_to_edit_listing($listing_id)) {
			if ($this->get_current_listing($listing_id)) {
				$action = 'show';
				$referer = wp_get_referer();
				if (isset($_GET['upgrade_action']) && $_GET['upgrade_action'] == 'upgrade') {
					$directorypress_form_validation = new directorypress_form_validation();
					$directorypress_form_validation->set_rules('new_package_id', __('New package ID', 'DIRECTORYPRESS'), 'required|integer');

					if ($directorypress_form_validation->run()) {
						if ($this->current_listing->change_listing_package($directorypress_form_validation->result_array('new_package_id')))
							directorypress_add_notification(__('Listing package was changed successfully!', 'DIRECTORYPRESS'));
						$action = sanitize_text_field($_GET['upgrade_action']);
					} else{
						directorypress_add_notification(__('New package must be selected!', 'DIRECTORYPRESS'), 'error');
					}
					$referer = sanitize_url($_GET['referer']);
				}

				directorypress_display_template('partials/listing/metabox/upgrade.php', array('listing' => $this->current_listing, 'referer' => $referer, 'action' => $action, 'packages' => $directorypress_object->packages));
			} else
				exit();
		} else
			exit();
	}
	
	public function add_bulk_upgrade_page() {
		add_submenu_page('options.php',
				__('Change package of listings', 'DIRECTORYPRESS'),
				__('Change package of listings', 'DIRECTORYPRESS'),
				'publish_posts',
				'directorypress_upgrade_bulk',
				array($this, 'listing_bulk_upgrade_page')
		);
	}
	
	public function listing_bulk_upgrade_page() {
		global $directorypress_object;
	
		if (isset($_GET['listings_ids'])) {
			$listings_ids = array_map('intval', explode(',', $_GET['listings_ids']));

			$action = 'show';
			$referer = sanitize_url($_GET['referer']);
			if (isset($_GET['upgrade_action']) && $_GET['upgrade_action'] == 'upgrade') {
				$action = sanitize_text_field($_GET['upgrade_action']);

				$directorypress_form_validation = new directorypress_form_validation();
				$directorypress_form_validation->set_rules('new_package_id', __('New package ID', 'DIRECTORYPRESS'), 'required|integer');
				if ($directorypress_form_validation->run()) {
					$new_package_id = $directorypress_form_validation->result_array('new_package_id');
					$upgraded = 0;
					foreach ($listings_ids AS $listing_id) {
						if (is_numeric($listing_id) && directorypress_user_permission_to_edit_listing($listing_id))
							if ($this->get_current_listing($listing_id)) {
								if ($this->current_listing->change_listing_package($new_package_id))
									$upgraded++;
							} else
								exit();
					}
					if ($upgraded)
						directorypress_add_notification(sprintf(_n('%d listing has changed package successfully!', '%d listings have changed packages successfully!', $upgraded, 'DIRECTORYPRESS'), $upgraded));
				} else
					exit();
			}

			directorypress_display_template('partials/listing/metabox/upgrade_bulk.php', array('listings_ids' => $listings_ids, 'referer' => esc_url($referer), 'action' => $action, 'packages' => $directorypress_object->packages));
		} else
			exit();
	}

	public function listing_upgrade_action_bulk() {
		global $post_type;

		if ($post_type == DIRECTORYPRESS_POST_TYPE) {
		?>
		<script>
			(function($) {
				"use strict";

				$(function() {
					$('<option>').val('upgrade').text('<?php echo esc_js(__('Change package', 'DIRECTORYPRESS')); ?>').appendTo("select[name='action']");
					$('<option>').val('upgrade').text('<?php echo esc_js(__('Change package', 'DIRECTORYPRESS')); ?>').appendTo("select[name='action2']");
				});
			})(jQuery);
		</script>
		<?php
		}
	}
	
	public function handle_listing_upgrade_action_bulk() {
		global $typenow;

		if ($typenow == DIRECTORYPRESS_POST_TYPE) {
			$wp_list_table = _get_list_table('WP_Posts_List_Table');
			$action = $wp_list_table->current_action();
			
			$allowed_actions = array("upgrade");
			if (!in_array($action, $allowed_actions)) return;

			check_admin_referer('bulk-posts');
			
			if (isset($_REQUEST['post']))
				$post_ids = array_map('intval', $_REQUEST['post']);
			
			if (empty($post_ids)) return;

			switch($action) {
				case 'upgrade':

				wp_redirect(admin_url('options.php?page=directorypress_upgrade_bulk&listings_ids=' . implode(',', $post_ids) . '&referer=' . urlencode(wp_get_referer())));
				die();
				break;

				default: return;
			}
		}
	}
	
	public function add_author_notice_page() {
		add_submenu_page('options.php',
				__('Author Note', 'DIRECTORYPRESS'),
				__('Author Note', 'DIRECTORYPRESS'),
				'publish_posts',
				'directorypress_author_note_to_admin',
				array($this, 'authornotetoAdmin')	
		);
	}
	
	public function authornotetoAdmin() {
		if (isset($_GET['listing_id']) && ($listing_id = sanitize_text_field($_GET['listing_id'])) && is_numeric($listing_id) && is_admin()) {
			if ($this->get_current_listing($listing_id)) {
				$referer = wp_get_referer();
				$action = 'show';
				directorypress_display_template('partials/listing/metabox/author_notice_to_admin.php', array('listing' => $this->current_listing, 'referer' => $referer, 'action' => $action));
			}
		}
	}
	
	public function get_current_listing($listing_id = null) {
		global $directorypress_object, $pagenow;

		if ($pagenow == 'post-new.php' && isset($_GET['post_type']) && $_GET['post_type'] == DIRECTORYPRESS_POST_TYPE && isset($_GET['package_id']) && is_numeric($_GET['package_id'])) {
			// New post
			$package_id = sanitize_text_field($_GET['package_id']);
			$this->current_listing = new directorypress_listing($package_id);
			$directorypress_object->current_listing = $this->current_listing;

			if ($this->current_listing->package) {
				// need to load draft post into current_listing property
				add_action('save_post', array($this, 'save_initial_draft'), 10);
			} else {
				wp_redirect(add_query_arg('page', 'directorypress_choose_package', admin_url('options.php')));
				die();
			}
		} elseif (
			$listing_id
			||
			($pagenow == 'post.php' && isset($_GET['post']) && ($post = get_post($_GET['post'])) && $post->post_type == DIRECTORYPRESS_POST_TYPE)
			||
			($pagenow == 'post.php' && isset($_POST['post_ID']) && ($post = get_post($_POST['post_ID'])) && $post->post_type == DIRECTORYPRESS_POST_TYPE)
		) {
			if (empty($post) && $listing_id) {
				$post = get_post($listing_id);
			}

			// Existed post
			$this->init_listing($post);
		}
		return $this->current_listing;
	}
	
	public function init_listing($listing_post) {
		global $directorypress_object;

		$listing = new directorypress_listing();
		if ($listing->directorypress_init_lpost_listing($listing_post)) {
			$this->current_listing = $listing;
			$directorypress_object->current_listing = $listing;
		
			return $listing;
		}
	}
	
	public function save_initial_draft($post_id) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return;

		global $directorypress_object, $wpdb;
		$this->current_listing->directorypress_init_lpost_listing($post_id);
		$directorypress_object->current_listing = $this->current_listing;

		// $directorypress_object::setup_current_page_directorytype() for the frontend or self::setup_current_page_directorytype() for the backend
		if ($directorypress_object->current_directorytype) {
			add_post_meta($post_id, '_directory_id', $directorypress_object->current_directorytype->id);
		}

		$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->directorypress_packages_relation} (post_id, package_id) VALUES(%d, %d) ON DUPLICATE KEY UPDATE package_id=%d", $this->current_listing->post->ID, $this->current_listing->package->id, $this->current_listing->package->id));
		
		return $this->current_listing->set_package_by_post_id();
	}
	
	public function save_directorytype_meta($maybe_empty, $postarr) {
		global $directorypress_object;

		if (
			directorypress_get_input_value($postarr, 'ID') &&
			directorypress_get_input_value($postarr, 'post_type') == DIRECTORYPRESS_POST_TYPE &&
			isset($_POST['directory_id']) &&
			is_numeric($_POST['directory_id']) &&
			($directorytype = $directorypress_object->directorytypes->directory_by_id($_POST['directory_id']))
		) {
			$this->get_current_listing($postarr['ID']);
			if ($this->current_listing->directorytype->id != $directorytype->id) {
				update_post_meta($this->current_listing->post->ID, '_directory_id', $directorytype->id);
				directorypress_add_notification(__("Listing directorytype was changed!", "DIRECTORYPRESS"));
			}
		}
		
		return $maybe_empty;
	}

	public function listing_validation($data, $postarr) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		global $directorypress_object;

		if ($data['post_type'] == DIRECTORYPRESS_POST_TYPE) {
			global $directorypress_object;
	
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
				return;
	
			$errors = array();
			
			if (!isset($postarr['post_title']) || !$postarr['post_title'] || $postarr['post_title'] == __('Auto Draft'))
				$errors[] = __('Listing title field required', 'DIRECTORYPRESS');

			$post_categories_ids = array();
			
			$post_categories_ids = $directorypress_object->terms_validator->validateCategoriesBackend($this->current_listing->package, $postarr, $errors);
			

			$directorypress_object->fields->save_values($this->current_listing->post->ID, $post_categories_ids, $errors, $data, $this->current_listing->package->id);

			if ($this->current_listing->package->location_number_allowed) {
				if ($validation_results = $directorypress_object->locations_handler->validate_locations($this->current_listing->package, $errors)) {
					$directorypress_object->locations_handler->save_locations($this->current_listing->package, $this->current_listing->post->ID, $validation_results);
				}
			}
	
			if ($this->current_listing->package->images_allowed || $this->current_listing->package->videos_allowed) {
				if ($validation_results = $directorypress_object->media_handler_property->validate_attachments($this->current_listing->package, $errors))
					$directorypress_object->media_handler_property->save_attachments_backend($this->current_listing->package, $this->current_listing->post->ID, $validation_results);
			}
			
			if ($DIRECTORYPRESS_ADIMN_SETTINGS['message_system'] == 'email_messages' && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_custom_contact_email']) {
				if (isset($_POST['contact_email'])) {
					if (is_email($_POST['contact_email']) || empty($_POST['contact_email'])) {
						update_post_meta($this->current_listing->post->ID, '_contact_email', $_POST['contact_email']);
					} else {
						$errors[] = __("Contact email is invalid", "DIRECTORYPRESS");
					}
				}
			}
			
			if( isset($_POST['faqtitle']) && isset($_POST['faqanswer']) ){
				$faqQuestion = sanitize_text_field($_POST['faqtitle']);
				$faqanswer = sanitize_textarea_field($_POST['faqanswer']);
				$faqs = array('faqtitle'=>$faqQuestion,'faqanswer'=>$faqanswer);
				update_post_meta($this->current_listing->post->ID, '_listing_faqs', $faqs);
			}
			
			if(isset($_POST['facebook_link'])){
				if(!metadata_exists('post', $this->current_listing->post->ID, 'facebook_link' ) ) {
					add_post_meta($this->current_listing->post->ID, 'facebook_link', esc_url($_POST['facebook_link']));
				}else{
					update_post_meta($this->current_listing->post->ID, 'facebook_link', esc_url($_POST['facebook_link']));
				}
			}
						
			if(isset($_POST['twitter_link'])){
				if(!metadata_exists('post', $this->current_listing->post->ID, 'twitter_link' ) ) {
					add_post_meta($this->current_listing->post->ID, 'twitter_link', esc_url($_POST['twitter_link']));
				}else{
					update_post_meta($this->current_listing->post->ID, 'twitter_link', esc_url($_POST['twitter_link']));
				}
			}
						
			if(isset($_POST['linkedin_link'])){
				if(!metadata_exists('post', $this->current_listing->post->ID, 'linkedin_link' ) ) {
					add_post_meta($this->current_listing->post->ID, 'linkedin_link', esc_url($_POST['linkedin_link']));
				}else{
					update_post_meta($this->current_listing->post->ID, 'linkedin_link', esc_url($_POST['linkedin_link']));
				}
			}
						
			if(isset($_POST['youtube_link'])){
				if(!metadata_exists('post', $this->current_listing->post->ID, 'youtube_link' ) ) {
					add_post_meta($this->current_listing->post->ID, 'youtube_link', esc_url($_POST['youtube_link']));
				}else{
					update_post_meta($this->current_listing->post->ID, 'youtube_link', esc_url($_POST['youtube_link']));
				}
			}
						
			if(isset($_POST['instagram_link'])){
				if(!metadata_exists('post', $this->current_listing->post->ID, 'instagram_link' ) ) {
					add_post_meta($this->current_listing->post->ID, 'instagram_link', esc_url($_POST['instagram_link']));
				}else{
					update_post_meta($this->current_listing->post->ID, 'instagram_link', esc_url($_POST['instagram_link']));
				}
			}
			do_action('backend_listing_data_validation', $this);
			// only successfully validated listings can be completed
			if ($errors) {
				//$data['post_status'] = 'draft';
	
				foreach ($errors AS $error) {
					directorypress_add_notification($error, 'error');
				}
			} else {
				directorypress_add_notification(__('Listing was saved successfully!', 'DIRECTORYPRESS'));
			}
		}
		return $data;
	}
	
	public function setup_current_page_directorytype() {
		global $directorypress_object, $pagenow;

		if (is_admin() && $pagenow == 'post-new.php' && isset($_GET['post_type']) && $_GET['post_type'] == DIRECTORYPRESS_POST_TYPE && isset($_GET['directory_id']) && is_numeric($_GET['directory_id']) && ($directorytype = $directorypress_object->directorytypes->directory_by_id(esc_attr($_GET['directory_id'])))) {
			$directorypress_object->current_directorytype = $directorytype;
		}
	}

	public function after_save_redirect($location) {
		global $post;

		if ($post) {
			if (is_numeric($post))
				$post = get_post($post);
			if ($post->post_type == DIRECTORYPRESS_POST_TYPE) {
				// Remove native success 'message'
				$uri = parse_url($location);
				$uri_array = wp_parse_args($uri['query']);
				if (isset($uri_array['message']))
					unset($uri_array['message']);
				$location = add_query_arg($uri_array, 'post.php');
			}
		}

		return $location;
	}
	
	public function save_listing($post_ID, $post, $update) {
		global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS;
		
		$this->get_current_listing($post_ID);

		if (isset($_POST['directorypress_save_as_active'])) {
			update_post_meta($this->current_listing->post->ID, '_listing_status', 'active');
		}

		
		if ($post->post_status == 'publish') {
			if (!($listing_created = get_post_meta($this->current_listing->post->ID, '_listing_created', true))) {
				if (!$this->current_listing->package->package_no_expiry && $this->current_listing->status != 'expired') {
					if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_change_expiration_date'] || current_user_can('manage_options'))
						$this->change_listing_expiry();
					else {
						$expiration_date = directorypress_expiry_date(current_time('timestamp'), $this->current_listing->package);
						add_post_meta($this->current_listing->post->ID, '_expiration_date', $expiration_date);
					}
				}
				
				add_post_meta($this->current_listing->post->ID, '_listing_created', true);
				add_post_meta($this->current_listing->post->ID, '_order_date', time());
				add_post_meta($this->current_listing->post->ID, '_listing_status', 'active');

				apply_filters('directorypress_listing_creation', $this->current_listing);
			} else {
				if (!$this->current_listing->package->package_no_expiry && $this->current_listing->status != 'expired' && ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_change_expiration_date'] || current_user_can('manage_options'))) {
					$this->change_listing_expiry();
				}
					
				if ($this->current_listing->status == 'expired') {
					directorypress_add_notification(esc_attr__("You can't publish listing until it has expired status! Renew listing first!", 'DIRECTORYPRESS'), 'error');
				}
				
				do_action('directorypress_listing_update', $this->current_listing);
			}
		}
	}
	
	public function initialize_hooks() {
		if (current_user_can('delete_posts'))
			add_action('delete_post', array($this, 'delete_listing_data'), 10);
	}
	
	public function delete_listing_data($post_id) {
		global $directorypress_object, $wpdb;

		$wpdb->delete($wpdb->directorypress_packages_relation, array('post_id' => $post_id));
		
		$directorypress_object->locations_handler->delete_locations($post_id);
		
		$ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_parent = $post_id AND post_type = 'attachment'");
		foreach ($ids as $id){
			wp_delete_attachment($id, true);
		}
			
	}

	// adapted for WPML
	
	public function handle_wpml_make_duplicate($master_post_id, $lang, $post_array, $id) {
		global $wpdb;

		$listing = new directorypress_listing();
		if (get_post_type($master_post_id) == DIRECTORYPRESS_POST_TYPE && $listing->directorypress_init_lpost_listing($master_post_id)) {
			$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->directorypress_packages_relation} (post_id, package_id) VALUES(%d, %d) ON DUPLICATE KEY UPDATE package_id=%d", $id, $listing->package->id, $listing->package->id));

			$wpdb->delete($wpdb->directorypress_locations_relation, array('post_id' => $id));
			wp_delete_object_term_relationships($id, DIRECTORYPRESS_LOCATIONS_TAX);
			foreach ($listing->locations AS $location) {
				$insert_values = array(
						'post_id' => $id,
						'location_id' => apply_filters('wpml_object_id', $location->selected_location, DIRECTORYPRESS_LOCATIONS_TAX, true, $lang),
						'address_line_1' => $location->address_line_1,
						'address_line_2' => $location->address_line_2,
						'zip_or_postal_index' => $location->zip_or_postal_index,
						'additional_info' => $location->additional_info,
				);
				$insert_values['manual_coords'] = $location->manual_coords;
				$insert_values['map_coords_1'] = $location->map_coords_1;
				$insert_values['map_coords_2'] = $location->map_coords_2;
				$insert_values['map_icon_file'] = $location->map_icon_file;

				$keys = array_keys($insert_values);
				array_walk($keys, 'directorypress_wrapKeys');
				array_walk($insert_values, 'directorypress_wrapValues');
				
				$wpdb->query("INSERT INTO {$wpdb->directorypress_locations_relation} (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $insert_values) . ")");
			}
		}
	}
	
	// adapted for WPML
	public function wpml_copy_translations($listing) {
		global $sitepress, $iclTranslationManagement;
		if (function_exists('wpml_object_id_filter') && $sitepress && get_option('directorypress_enable_automatic_translations') && ($languages = $sitepress->get_active_languages()) && count($languages) > 1) {
			$master_post_id = $listing->post->ID;

			remove_filter('wp_insert_post_data', array($this, 'listing_validation'), 99);
			remove_filter('redirect_post_location', array($this, 'after_save_redirect'));
			remove_action('save_post_' . DIRECTORYPRESS_POST_TYPE, array($this, 'save_listing'));

			$post_type = get_post_type($master_post_id);
			if ($sitepress->is_translated_post_type($post_type)) {
				foreach ($languages AS $lang_code=>$lang)
					if ($lang_code != ICL_LANGUAGE_CODE) {
						$new_listing_id = $iclTranslationManagement->make_duplicate($master_post_id, $lang_code);
						$iclTranslationManagement->reset_duplicate_flag($new_listing_id);
					}
			}
		}
	}

	/* stop wrong redirect  */
	public function avoid_redirection_plugin($post_id) {
		if (get_post_type($post_id) == DIRECTORYPRESS_POST_TYPE && isset($_POST['redirection_slug']))
			unset($_POST['redirection_slug']);
	}
}