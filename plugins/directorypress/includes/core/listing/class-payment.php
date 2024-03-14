<?php

class directorypress_listings_packages {
	public $listings_numbers = array();
	
	public function __construct() {
		add_action('show_user_profile', array($this, 'add_user_profile_fields'));
		add_action('edit_user_profile', array($this, 'add_user_profile_fields'));
		add_action('personal_options_update', array($this, 'save_user_profile_fields'));
		add_action('edit_user_profile_update', array($this, 'save_user_profile_fields'));
		
		add_action('directorypress_listing_package_process_activation', array($this, 'listing_activate_package'), 10, 1);
		
		// first of all if user has pre-paid listing(s) - simply activate it after creation
		add_filter('directorypress_listing_creation_front', array($this, 'activate_if_possible'), 1);

		add_action('directorypress_renew_html', array($this, 'renew_html'));
		add_filter('directorypress_listing_renew', array($this, 'renew_listing_order'), 1, 3);

		add_action('directorypress_raise_up_html', array($this, 'bumpup_html'));
		add_filter('directorypress_listing_bumpup', array($this, 'listing_raiseup_order'), 1, 3);

		add_filter('directorypress_package_upgrade_option', array($this, 'package_upgrade_option'), 10, 3);
		add_filter('directorypress_listing_upgrade', array($this, 'listing_upgrade_order'), 1, 3);
	}
	
	public function get_listings_of_user($user_id = false) {
		global $directorypress_object;

		if (!$user_id) {
			$user_id = get_current_user_id();
		}
	
		foreach ($directorypress_object->packages->packages_array as $package) {
			$this->listings_numbers[$package->id]['unlimited'] = false;
			$this->listings_numbers[$package->id]['number'] = 0;
			if (get_user_meta($user_id, '_listings_unlimited_'.$package->id, true)) {
				$this->listings_numbers[$package->id]['unlimited'] = true;
			}
			elseif ($listings_number = get_user_meta($user_id, '_listings_number_'.$package->id, true)) {
				$this->listings_numbers[$package->id]['number'] = (int)$listings_number;
			}
		}
		return $this->listings_numbers;
	}
	
	public function is_any_listing_to_create($user_id = false) {
		if (!$user_id) {
			$user_id = get_current_user_id();
		}
		
		$numbers = $this->get_listings_of_user($user_id);
		
		foreach ($numbers AS $package_id=>$listings_number) {
			if ($numbers[$package_id]['unlimited'] || $numbers[$package_id]['number'] > 0) {
				return $package_id;
				break;
			}
		}
	}
	
	public function can_user_create_listing_in_package($package_id, $user_id = false) {
		if (!$user_id) {
			$user_id = get_current_user_id();
		}
	
		$numbers = $this->get_listings_of_user($user_id);
	
		if ($numbers[$package_id]['unlimited'] || $numbers[$package_id]['number'] > 0) {
			return true;
		}
	}
	
	public function process_listing_creation_for_user($package_id, $user_id = false) {
		if (!$user_id) {
			$user_id = get_current_user_id();
		}
	
		$numbers = $this->get_listings_of_user($user_id);
		if (!$numbers[$package_id]['unlimited']) {
			update_user_meta($user_id, '_listings_number_'. esc_attr($package_id), ($numbers[$package_id]['number'] - 1));
			$this->listings_numbers[$package_id]['number'] = $numbers[$package_id]['number'] - 1;
		}
	}
	
	public function listing_activate_package($listing) {
		$package_id = $listing->package->id;
		$user_id = $listing->post->post_author;
		$purchase_count = get_user_meta($user_id, '_renew_count_package_'.$package_id, true);
		$purchase_count = (!empty($purchase_count) && is_numeric($purchase_count))? $purchase_count : 0;
		//var_dump();
		$listings_numbers = $this->get_listings_of_user($user_id);
		if ($listing->package->number_of_listings_in_package > 1) {
			update_user_meta($user_id, '_listings_number_'.$package_id, $listings_numbers[$package_id]['number'] + $listing->package->number_of_listings_in_package);
			
			$this->process_listing_creation_for_user($package_id, $user_id);
		}
		update_user_meta($user_id, '_renew_count_package_'.$package_id, $purchase_count + 1);
	}
	
	public function add_user_profile_fields($user) {
		global $directorypress_object;
		
		if (!current_user_can('edit_user', $user->ID))
			return false;
	?>
		<h2><?php _e('Directory listings available', 'DIRECTORYPRESS'); ?></h3>

		<table class="form-table">
		<?php
		$listings_number = $this->get_listings_of_user($user->ID);

		foreach ($directorypress_object->packages->packages_array as $package):
		?>
			<tr>
				<th><label for="listings_number_<?php echo esc_attr($package->id); ?>"><?php _e('Number of listings of package "'.esc_attr($package->name).'"', 'DIRECTORYPRESS'); ?></label></th>
				<td>
					<input type="text" name="_listings_number_<?php echo esc_attr($package->id); ?>" id="_listings_number_<?php echo esc_attr($package->id); ?>" value="<?php echo esc_attr($listings_number[$package->id]['number']); ?>" class="regular-text listings_number" />
					<p>
						<input type="checkbox" name="_listings_unlimited_<?php echo esc_attr($package->id); ?>" id="_listings_unlimited_<?php echo esc_attr($package->id); ?>" <?php checked($listings_number[$package->id]['unlimited'], 1); ?> value="1" />
						<label for="_listings_unlimited_<?php echo esc_attr($package->id); ?>"><?php _e('Unlimited', 'DIRECTORYPRESS'); ?></label>
					</p>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php }
	
	public function save_user_profile_fields($user_id) {
		global $directorypress_object;

		if (!current_user_can('edit_user', $user_id))
			return false;

		foreach ($directorypress_object->packages->packages_array as $package) {
			update_user_meta($user_id, '_listings_unlimited_'.$package->id, sanitize_text_field(directorypress_get_input_value($_POST, '_listings_unlimited_'.$package->id, 0)));
			update_user_meta($user_id, '_listings_number_'.$package->id, sanitize_text_field(directorypress_get_input_value($_POST, '_listings_number_'.$package->id, 0)));
		}
	}

	public function available_listings_descr($package_id, $action_string, $directorytype = false) {
		global $directorypress_object;
		
		if (is_admin()) {
			return false;
		}

		$listings_number = $this->get_listings_of_user();
		
		$out = '';
		$number = 0;
		
		if (!$directorytype) {
			$directorytype = $directorypress_object->current_directorytype;
		} 

		if ($listings_number[$package_id]['unlimited']) {
			$number = __("unlimited", "DIRECTORYPRESS");
		} elseif ($listings_number[$package_id]['number']) {
			$number = $listings_number[$package_id]['number'];
		}
		if ($number) {
			$out = sprintf(__("You have <strong>%s</strong> free %s to %s in this package.", "DIRECTORYPRESS"), $number, _n($directorytype->single, $directorytype->plural, $number), $action_string);
		}
		
		return $out;
	}
	
	public function submitlisting_package_message($package, $directorytype = false) {
		$out = $this->available_listings_descr($package->id, __("submit", "DIRECTORYPRESS"), $directorytype);

		if ($out) {
			return '<div class="directorypress-user-package-message">' . $out . '</div>';
		}
	}

	// if user has pre-paid listing(s) - simply activate it after creation
	public function activate_if_possible($listing) {
		if ($listing) {
			if ($this->can_user_create_listing_in_package($listing->package->id)) {
				$this->process_listing_creation_for_user($listing->package->id);
				return false;
			}
		}
		return $listing;
	}

	public function renew_html($listing) {
		$out = $this->available_listings_descr($listing->package->id, __("renew", "DIRECTORYPRESS"));
		if ($out) {
			echo "<p>" . esc_html($out) . "</p>";
		}
	}
	
	public function renew_listing_order($continue, $listing, $continue_invoke_hooks) {
		$user_id = $listing->post->post_author;
	
		if ($this->can_user_create_listing_in_package($listing->package->id, $user_id)) {
			$listing->process_activation(false, false);
			$this->process_listing_creation_for_user($listing->package->id, $user_id);
			$continue_invoke_hooks[0] = false;
			if (!defined('DOING_CRON')) {
				directorypress_add_notification(__("Listing was renewed successfully.", "DIRECTORYPRESS"));
			}
			$continue_invoke_hooks[0] = false;
			return false;
		}
		return $continue;
	}
	
	public function bumpup_html($listing) {
		$out = $this->available_listings_descr($listing->package->id, __("bumpup", "DIRECTORYPRESS"));
		if ($out) {
			echo "<p>" . esc_html($out) . "</p>";
		}
	}
	
	public function listing_raiseup_order($continue, $listing, $continue_invoke_hooks) {
		if ($this->can_user_create_listing_in_package($listing->package->id)) {
			$this->process_listing_creation_for_user($listing->package->id);
			$continue_invoke_hooks[0] = false;
			return true;
		}

		return $continue;
	}
	
	public function package_upgrade_option($link_text, $old_package, $new_package) {
		$out = $link_text;

		if ($this->can_user_create_listing_in_package($new_package->id)) {
			$out .= ' ' . __("(you can upgrade to this package for free)", "DIRECTORYPRESS");
		}
		
		return $out;
	}

	public function listing_upgrade_order($continue, $listing, $continue_invoke_hooks) {
		$new_package_id = get_post_meta($listing->post->ID, '_new_package_id', true);

		if ($this->can_user_create_listing_in_package($new_package_id)) {
			$this->process_listing_creation_for_user($new_package_id);
			$continue_invoke_hooks[0] = false;
			return true;
		}

		return $continue;
	}
}
?>