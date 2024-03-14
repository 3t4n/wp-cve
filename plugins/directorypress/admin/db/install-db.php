<?php 
function directorypress_install_directory() {
	global $wpdb;
	$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}
	if (!get_option('directorypress_installed_directory')) {
		$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->directorypress_fields_groups} (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`name` varchar(255) NOT NULL,
					`on_tab` tinyint(1) NOT NULL DEFAULT '0',
					`group_style` varchar(255) NOT NULL DEFAULT '0',
					`hide_anonymous` tinyint(1) NOT NULL DEFAULT '0',
					PRIMARY KEY (`id`)
					) $collate ;");
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->directorypress_fields_groups} WHERE name = 'Contact Information'"))
			$wpdb->query("INSERT INTO {$wpdb->directorypress_fields_groups} (`name`, `on_tab`, `group_style`, `hide_anonymous`) VALUES ('Contact Information', 0, 1, 0)");

		$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->directorypress_fields} (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`is_core_field` tinyint(1) NOT NULL DEFAULT '0',
					`order_num` int(11) NOT NULL,
					`name` varchar(255) NOT NULL,
					`field_search_label` varchar(255) NOT NULL,
					`slug` varchar(255) NOT NULL,
					`description` text NOT NULL,
					`fieldwidth` varchar(255) NOT NULL,
					`fieldwidth_archive` varchar(255) NOT NULL,
					`type` varchar(255) NOT NULL,
					`icon_image` varchar(255) NOT NULL,
					`is_required` tinyint(1) NOT NULL DEFAULT '0',
					`is_configuration_page` tinyint(1) NOT NULL DEFAULT '0',
					`is_search_configuration_page` tinyint(1) NOT NULL DEFAULT '0',
					`is_ordered` tinyint(1) NOT NULL DEFAULT '0',
					`is_hide_name` tinyint(1) NOT NULL DEFAULT '0',
					`is_hide_name_on_grid` varchar(255) NOT NULL DEFAULT 'hide',
					`is_hide_name_on_list` varchar(255) NOT NULL DEFAULT 'hide',
					`is_hide_name_on_search` tinyint(1) NOT NULL DEFAULT '0',
					`is_field_in_line` tinyint(1) NOT NULL DEFAULT '0',
					`on_exerpt_page` tinyint(1) NOT NULL DEFAULT '0',
					`on_exerpt_page_list` tinyint(1) NOT NULL DEFAULT '0',
					`on_listing_page` tinyint(1) NOT NULL DEFAULT '0',
					`on_search_form` tinyint(1) NOT NULL DEFAULT '0',
					`on_map` tinyint(1) NOT NULL DEFAULT '0',
					`advanced_search_form` tinyint(1) NOT NULL,
					`categories` text NOT NULL,
					`options` text NOT NULL,
					`checkbox_icon_type` varchar(255) NOT NULL,
					`search_options` text NOT NULL,
					`group_id` int(11) NOT NULL DEFAULT '0',
					PRIMARY KEY (`id`),
					KEY `group_id` (`group_id`)
					) $collate;");
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->directorypress_fields} WHERE slug = 'exerpt'"))
			$wpdb->query("INSERT INTO {$wpdb->directorypress_fields} (`is_core_field`, `order_num`, `name`, `field_search_label`, `slug`, `description`, `type`, `icon_image`, `is_required`, `is_configuration_page`, `is_search_configuration_page`, `is_ordered`, `is_hide_name`, `is_hide_name_on_grid`, `is_hide_name_on_list`, `is_hide_name_on_search`, `is_field_in_line`, `on_exerpt_page`, `on_exerpt_page_list`, `on_listing_page`, `on_search_form`, `on_map`, `advanced_search_form`, `categories`, `options`, `search_options`, `group_id`) VALUES(1, 1, 'Exerpt', '', 'exerpt', '', 'summary', '', 0, 0, 0, 0, 0, 'hide', 'hide', 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '0');");
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->directorypress_fields} WHERE slug = 'address'"))
			$wpdb->query("INSERT INTO {$wpdb->directorypress_fields} (`is_core_field`, `order_num`, `name`, `field_search_label`, `slug`, `description`, `type`, `icon_image`, `is_required`, `is_configuration_page`, `is_search_configuration_page`, `is_ordered`, `is_hide_name`, `is_hide_name_on_grid`, `is_hide_name_on_list`, `is_hide_name_on_search`, `is_field_in_line`, `on_exerpt_page`, `on_exerpt_page_list`, `on_listing_page`, `on_search_form`, `on_map`, `advanced_search_form`, `categories`, `options`, `search_options`, `group_id`) VALUES(1, 2, 'Address', '', 'address', '', 'address', 'fa-map-marker', 0, 0, 0, 0, 0, 'hide', 'hide', 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '0');");
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->directorypress_fields} WHERE slug = 'content'"))
			$wpdb->query("INSERT INTO {$wpdb->directorypress_fields} (`is_core_field`, `order_num`, `name`, `field_search_label`, `slug`, `description`, `type`, `icon_image`, `is_required`, `is_configuration_page`, `is_search_configuration_page`, `is_ordered`, `is_hide_name`, `is_hide_name_on_grid`, `is_hide_name_on_list`, `is_hide_name_on_search`, `is_field_in_line`, `on_exerpt_page`, `on_exerpt_page_list`, `on_listing_page`, `on_search_form`, `on_map`, `advanced_search_form`, `categories`, `options`, `search_options`, `group_id`) VALUES(1, 3, 'Description', '', 'content', '', 'content', '', 0, 0, 0, 0, 0, 'hide', 'hide', 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '0');");
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->directorypress_fields} WHERE slug = 'categories_list'"))
			$wpdb->query("INSERT INTO {$wpdb->directorypress_fields} (`is_core_field`, `order_num`, `name`, `field_search_label`, `slug`, `description`, `type`, `icon_image`, `is_required`, `is_configuration_page`, `is_search_configuration_page`, `is_ordered`, `is_hide_name`, `is_hide_name_on_grid`, `is_hide_name_on_list`, `is_hide_name_on_search`, `is_field_in_line`, `on_exerpt_page`, `on_exerpt_page_list`, `on_listing_page`, `on_search_form`, `on_map`, `advanced_search_form`, `categories`, `options`, `search_options`, `group_id`) VALUES(1, 4, 'Categories', '', 'categories_list', '', 'categories', '', 0, 1, 0, 0, 0, 'hide', 'hide', 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '0');");
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->directorypress_fields} WHERE slug = 'listing_tags'"))
			$wpdb->query("INSERT INTO {$wpdb->directorypress_fields} (`is_core_field`, `order_num`, `name`, `field_search_label`, `slug`, `description`, `type`, `icon_image`, `is_required`, `is_configuration_page`, `is_search_configuration_page`, `is_ordered`, `is_hide_name`, `is_hide_name_on_grid`, `is_hide_name_on_list`, `is_hide_name_on_search`, `is_field_in_line`, `on_exerpt_page`, `on_exerpt_page_list`, `on_listing_page`, `on_search_form`, `on_map`, `advanced_search_form`, `categories`, `options`, `search_options`, `group_id`) VALUES(1, 5, 'Tags', '', 'listing_tags', '', 'tags', '', 0, 0, 0, 0, 0, 'hide', 'hide', 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '0');");
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->directorypress_fields} WHERE slug = 'status'"))
			$wpdb->query("INSERT INTO {$wpdb->directorypress_fields} (`is_core_field`, `order_num`, `name`, `field_search_label`, `slug`, `description`, `fieldwidth`, `fieldwidth_archive`, `type`, `icon_image`, `is_required`, `is_configuration_page`, `is_search_configuration_page`, `is_ordered`, `is_hide_name`, `is_hide_name_on_grid`, `is_hide_name_on_list`, `is_hide_name_on_search`, `is_field_in_line`, `on_exerpt_page`, `on_exerpt_page_list`, `on_listing_page`, `on_search_form`, `on_map`, `advanced_search_form`, `categories`, `options`, `checkbox_icon_type`, `search_options`, `group_id`) VALUES(1, 6, 'Status', '', 'status', '', '', '', 'status', '', 0, 1, 1, 0, 0, 'hide', 'hide', 0, 0, 0, 0, 0, 0, 0, 0, '', 'a:2:{s:15:\"selection_items\";a:3:{i:1;s:8:\"For Sale\";i:2;s:8:\"For Rent\";i:3;s:6:\"Wanted\";}s:11:\"color_codes\";a:3:{i:1;s:7:\"#81d742\";i:2;s:7:\"#1e73be\";i:3;s:7:\"#dd9933\";}}', '', '', 0);");
	
		$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->directorypress_directorytypes} (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`name` varchar(255) NOT NULL,
					`single` varchar(255) NOT NULL,
					`plural` varchar(255) NOT NULL,
					`listing_slug` varchar(255) NOT NULL,
					`category_slug` varchar(255) NOT NULL,
					`location_slug` varchar(255) NOT NULL,
					`tag_slug` varchar(255) NOT NULL,
					`categories` text NOT NULL,
					`locations` text NOT NULL,
					`packages` text NOT NULL,
					PRIMARY KEY (`id`)
					) $collate ;");
		
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->directorypress_directorytypes} WHERE name = 'Business'"))
			$wpdb->query("INSERT INTO {$wpdb->directorypress_directorytypes} (`name`, `single`, `plural`, `listing_slug`, `category_slug`, `location_slug`, `tag_slug`, `categories`, `locations`, `packages`) VALUES ('Businesses', 'business', 'businesses', 'business-listing', 'business-category', 'business-place', 'business-tag', '', '', '')");

		
		$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->directorypress_packages} (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`order_num` tinyint(1) NOT NULL,
					`name` varchar(255) NOT NULL,
					`description` text NOT NULL,
					`who_can_submit` text NOT NULL,
					`package_duration` tinyint(1) NOT NULL,
					`package_duration_unit` varchar(255) NOT NULL,
					`package_no_expiry` tinyint(1) NOT NULL DEFAULT '1',
					`number_of_listings_in_package` varchar(255) NOT NULL,
					`number_of_package_renew_allowed` varchar(255) NOT NULL,
					`change_package_id` INT(11) NOT NULL DEFAULT '0',
					`can_be_bumpup` tinyint(1) NOT NULL,
					`has_sticky` tinyint(1) NOT NULL,
					`has_featured` tinyint(1) NOT NULL,
					`featured_package` tinyint(1) NOT NULL DEFAULT '0',
					`category_number_allowed` tinyint(1) NOT NULL,
					`location_number_allowed` tinyint(1) NOT NULL,
					`images_allowed` tinyint(1) NOT NULL,
					`videos_allowed` tinyint(1) NOT NULL,
					`selected_categories` text NOT NULL,
					`selected_locations` text NOT NULL,
					`fields` text NOT NULL,
					`options` mediumtext NOT NULL,
					`upgrade_meta` text NOT NULL,
					PRIMARY KEY (`id`),
					KEY `order_num` (`order_num`)
					) $collate ;");
					
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->directorypress_packages} WHERE name = 'Basic Package'"))
			$wpdb->query("INSERT INTO `wp_directorypress_packages` (`id`, `order_num`, `name`, `description`,`who_can_submit`, `package_duration`, `package_duration_unit`, `package_no_expiry`, `change_package_id`, `number_of_listings_in_package`, `number_of_package_renew_allowed`, `can_be_bumpup`, `has_sticky`, `has_featured`, `category_number_allowed`, `location_number_allowed`, `featured_package`, `images_allowed`, `videos_allowed`, `selected_categories`, `selected_locations`, `fields`, `options`, `upgrade_meta`) VALUES(1, 1, 'Basic Package', '', '', 0, '', 1, 0, '', '', 1, 0, 0, 97, 5, 0, 7, 1, 'a:1:{i:0;s:0:\"\";}', 'a:1:{i:0;s:1:\"2\";}', 'a:1:{i:0;s:0:\"\";}', '', 'a:3:{i:1;a:3:{s:5:\"price\";i:0;s:8:\"disabled\";b:1;s:7:\"raiseup\";b:1;}i:2;a:3:{s:5:\"price\";i:0;s:8:\"disabled\";b:0;s:7:\"raiseup\";b:0;}i:3;a:3:{s:5:\"price\";i:0;s:8:\"disabled\";b:0;s:7:\"raiseup\";b:0;}}');");
		
		$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->directorypress_packages_relation} (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`post_id` int(11) NOT NULL,
					`package_id` int(11) NOT NULL,
					PRIMARY KEY (`id`),
					UNIQUE KEY `post_id` (`post_id`,`package_id`)
					) $collate ;");

		$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->directorypress_locations_depths} (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`name` varchar(255) NOT NULL,
					`in_widget` tinyint(1) NOT NULL,
					`in_address_line` tinyint(1) NOT NULL,
					PRIMARY KEY (`id`),
					KEY `in_select_widget` (`in_widget`,`in_address_line`)
					) $collate ;");
	
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->directorypress_locations_depths} WHERE name = 'Country'"))
			$wpdb->query("INSERT INTO {$wpdb->directorypress_locations_depths} (`name`, `in_widget`, `in_address_line`) VALUES ('Country', 1, 1);");

		$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->directorypress_locations_relation} (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`post_id` int(11) NOT NULL,
					`location_id` int(11) NOT NULL,
					`address_line_1` varchar(255) NOT NULL,
					`address_line_2` varchar(255) NOT NULL,
					`zip_or_postal_index` varchar(25) NOT NULL,
					`additional_info` text NOT NULL,
					`manual_coords` tinyint(1) NOT NULL,
					`map_coords_1` float(10,6) NOT NULL,
					`map_coords_2` float(10,6) NOT NULL,
					`map_icon_file` varchar(255) NOT NULL,
					PRIMARY KEY (`id`),
					KEY `location_id` (`location_id`),
					KEY `post_id` (`post_id`)
					) $collate ;");
	
		if (!is_array(get_terms(DIRECTORYPRESS_LOCATIONS_TAX)) || !count(get_terms(DIRECTORYPRESS_LOCATIONS_TAX))) {
			wp_insert_term('USA', DIRECTORYPRESS_LOCATIONS_TAX);
		}
		
		add_option('directorypress_installed_directory', true);
	}
	
	global $directorypress_object;
	$directorypress_object->directorypress_init_classes();
}