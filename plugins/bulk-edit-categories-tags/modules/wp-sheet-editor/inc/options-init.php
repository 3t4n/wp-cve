<?php defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPSE_Options_Page' ) ) {

	class WPSE_Options_Page {

		private static $instance = false;
		var $sections            = array();

		private function __construct() {

		}

		function getSections() {

			$helpers                 = WP_Sheet_Editor_Helpers::get_instance();
			$this->sections['speed'] = array(
				'icon'   => 'el-icon-cogs',
				'title'  => __( 'Speed and performance', 'vg_sheet_editor' ),
				'fields' => array(
					array(
						'id'       => 'be_posts_per_page',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => __( 'Load rows faster: Number of rows to load per batch', 'vg_sheet_editor' ),
						'desc'     => __( 'We use pagination to use few server resources. We load 20 rows first and load 20 more every time you scroll down. You can increase this number to load more rows per page. CAREFUL. Loading more than 200 rows per page might overload your server. If we detect that the server is overloaded we will automatically reset to 10 rows per page.', 'vg_sheet_editor' ),
						'default'  => 20,
					),
					array(
						'id'       => 'export_page_size',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => __( 'Export rows faster: Number of rows to export per batch', 'vg_sheet_editor' ),
						'desc'     => __( 'Here you can control the batch size for the exports. If you use a high number the exports will finish faster. You can use a high number safely because we automatically fall back to a lower number if the server is overloaded during one export. For example, export 100 rows per batch and complete the exports super fast and if we detect slowness in one export we will automatically restart the export with 10 rows per batch', 'vg_sheet_editor' ),
						'default'  => 100,
					),
					array(
						'id'       => 'be_posts_per_page_save',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => __( 'Save changes faster: Number of rows to save per batch', 'vg_sheet_editor' ),
						'desc'     => __( 'When you edit a large amount of posts in the spreadsheet editor we can\'t save all the changes at once, so we do it in batches. The recommended value is 4 , which means we will process only 4 posts at once. You can adjust it as it works best for you. If you get errors when saving you should lower the number', 'vg_sheet_editor' ),
						'default'  => 4,
					),
					array(
						'id'       => 'delete_posts_per_page',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => __( 'Delete posts faster: Number of posts to delete per batch', 'vg_sheet_editor' ),
						'desc'     => __( 'When you delete posts, pages, events, products, orders, coupons, and other post types, you can select how many will be deleted on every batch. Use a higher number to finish faster. Default is 500', 'vg_sheet_editor' ),
						'default'  => 500,
					),
				),
			);

			$this->sections['productivity'] = array(
				'icon'   => 'el-icon-cogs',
				'title'  => __( 'Increase Productivity', 'vg_sheet_editor' ),
				'fields' => array(
					array(
						'id'      => 'enable_pagination',
						'type'    => 'switch',
						'title'   => __( 'Use pagination in the spreadsheet?', 'vg_sheet_editor' ),
						'desc'    => __( 'By default we use an infinite list of rows and we load more rows every time you scroll down. You can activate this option to display pagination links and disable the infinite list.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'be_disable_automatic_loading_rows',
						'type'    => 'switch',
						'title'   => __( 'Disable the automatic loading of rows?', 'vg_sheet_editor' ),
						'desc'    => __( 'When you open the spreadsheet, we load the rows automatically so you can start editing right away. Activate this option if you want to search rows and load manually.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'be_disable_full_screen_mode_on',
						'type'    => 'switch',
						'title'   => __( 'Disable the full screen mode?', 'vg_sheet_editor' ),
						'desc'    => __( 'When the sheet loads, we open it in full screen and you have the option to exit the full screen mode. Activate this option and we wont open the sheet in full screen.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'be_load_items_on_scroll',
						'type'    => 'switch',
						'title'   => __( 'Load more items on scroll?', 'vg_sheet_editor' ),
						'desc'    => __( 'When this is enabled more items will be loaded to the bottom of the spreadsheet when you reach the end of the page. You can enable / disable in the spreadsheet too.', 'vg_sheet_editor' ),
						'default' => true,
					),
					array(
						'id'       => 'be_fix_columns_left',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => __( 'Freeze first columns at the left side?', 'vg_sheet_editor' ),
						'desc'     => __( 'Enter a number and those columns will be frozen while scrolling horizontally. You can right click on any column to freeze or unfreeze it. For example, enter 2 to freeze the first 2 columns', 'vg_sheet_editor' ),
						'default'  => 2,
					),
					array(
						'id'      => 'enable_auto_saving',
						'type'    => 'switch',
						'title'   => __( 'Enable auto saving?', 'vg_sheet_editor' ),
						'desc'    => __( 'Turn this on and the spreadsheet will save automatically all the changes made on the cells every 2 minutes. Careful, this might cause issues if the changes are saved prematurely before you finish editing all the required columns.', 'vg_sheet_editor' ),
						'default' => false,
					),
				),
			);

			if ( VGSE()->helpers->has_paid_addon_active() && ! VGSE()->helpers->is_editor_page() ) {
				$enabled_sheets = VGSE()->helpers->get_enabled_post_types();
				foreach ( $enabled_sheets as $sheet_key ) {
					$provider = VGSE()->helpers->get_data_provider( $sheet_key );
					if ( ! $provider->is_post_type ) {
						continue;
					}
					$this->sections['productivity']['fields'][] = array(
						'id'         => 'default_sortby_' . $sheet_key,
						'type'       => 'new_select',
						'title'      => VGSE()->helpers->get_post_type_label( $sheet_key ) . ': ' . __( 'Default sort order', 'vg_sheet_editor' ),
						'desc'       => __( 'We\'ll sort the rows in the spreadsheet by this field. We recommend you sort based on fields that have values in all the rows in order to get more accurate results. For example, it\'s better to sort 1k products by Title ASC because you know that all the rows will be sorted accurately, but if you sort 1k rows by SKU, the 500 rows with SKU will be sorted correctly but the 500 rows without SKU could potentially have random order as we don\'t have any value to sort them accurately.', 'vg_sheet_editor' ),
						'options'    => function () use ( $sheet_key ) {
							return VGSE()->helpers->get_sheet_sort_options( $sheet_key );
						},
						'default'    => 'DESC:post_date',
						'class_name' => 'select2',
					);
				}
			}

			$this->sections['solution_errors'] = array(
				'icon'   => 'el-icon-cogs',
				'title'  => __( 'Solution to weird errors', 'vg_sheet_editor' ),
				'fields' => array(
					array(
						'id'       => 'be_columns_limit',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => __( 'Columns limit', 'vg_sheet_editor' ),
						'desc'     => __( 'We limit the spreadsheet columns for performance reasons to avoid loading thousands of columns on the spreadsheet. You can increase this limit if you want to display more columns. Default: 310', 'vg_sheet_editor' ),
						'default'  => 310,
					),
					array(
						'id'      => 'be_taxonomy_terms_separator',
						'type'    => 'text',
						'title'   => __( 'Separator for taxonomy terms cells', 'vg_sheet_editor' ),
						'desc'    => __( 'Taxonomy columns like post categories, post tags, etc. show terms separated by comma, if you use commas in your term names, use this option to change the separator', 'vg_sheet_editor' ),
						'default' => ',',
					),
					array(
						'id'       => 'be_timeout_between_batches',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => __( 'How long do you want to wait between batches? (in seconds)', 'vg_sheet_editor' ),
						'desc'     => __( 'When you edit a large amount of posts in the spreadsheet editor we can\'t save all the changes at once, so we do it in batches. But your server can\'t handle all the batches one after another so we need to wait a few seconds after every batch to give your server a little break. The recommended value is 6 seconds, you can adjust it as it works best for you. If you get errors when saving you should increase the number to give your server a longer break after each batch', 'vg_sheet_editor' ),
						'default'  => 6,
					),
					array(
						'id'      => 'be_disable_post_actions',
						'type'    => 'switch',
						'title'   => __( 'Disable post actions while saving?', 'vg_sheet_editor' ),
						'desc'    => __( 'Some plugins execute a task after a post is created or updated. For example, there are plugins that share your new posts on your social profiles, other plugins that notify users after a post is updated, etc. There might be an issue with those plugins. For example, if you use a plugin that shares your new posts on your twitter account and update 100 posts in the spreadsheet editor you might end up with 100 tweets shared in your twitter account. So enable this option if you want to update / create posts silently without executing those functions.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'be_suspend_object_cache_invalidation',
						'type'    => 'switch',
						'title'   => __( 'Suspend object cache invalidation?', 'vg_sheet_editor' ),
						'desc'    => __( 'Disable this if you are using a object/database cache plugin. We disable this by default to make the saving faster, when you edit a lot of posts WordPress tries to "clean up" the cache even if you are not using a cache plugin, making hundreds of unnecessary database queries.', 'vg_sheet_editor' ),
						'default' => ! defined( 'WP_CACHE' ) || ! WP_CACHE,
					),
					array(
						'id'      => 'be_disable_wpautop',
						'type'    => 'switch',
						'title'   => __( 'Disable the replacement of line breaks with p tags?', 'vg_sheet_editor' ),
						'desc'    => __( 'When the sheet loads and saves post content, we run it through wpautop to prevent issues with line breaks. You can disable this if you dont want to see/save the p tags in the content.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'be_disable_data_prefetch',
						'type'    => 'switch',
						'title'   => __( 'Deactivate the data prefetch', 'vg_sheet_editor' ),
						'desc'    => __( 'When you load the spreadsheet, we get all the columns at once from the database to make it faster, this is called prefetch. This can cause issues if you have thousands of columns or rare database setups.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'    => 'keys_for_infinite_serialized_handler',
						'type'  => 'text',
						'title' => __( 'Meta keys that should use the infinite serialized fields handler', 'vg_sheet_editor' ),
						'desc'  => __( 'This is only for advanced users or if our support team asks you to use this option. We have 2 ways to handle serialized fields: the old handler (used by default, which has limitations) and the infinite serialization handler (better, it is not active by default to not break previous integrations). Use this option if you have serialized fields that save incorrectly or dont appear in the spreadsheet.', 'vg_sheet_editor' ),
					),
					array(
						'id'    => 'blacklist_columns',
						'type'  => 'text',
						'title' => __( 'Blacklist these columns', 'vg_sheet_editor' ),
						'desc'  => __( 'Enter the list of field keys separated by commas, you can enter the full meta field key or partial keywords or prefixes. This is useful because some plugins add thousands of unnecessary fields to the database and they clutter the spreadsheet', 'vg_sheet_editor' ),
					),
					array(
						'id'      => 'fix_utf8_editor_settings',
						'type'    => 'switch',
						'title'   => __( 'Enable the utf8 encoding fix', 'vg_sheet_editor' ),
						'desc'    => __( 'This might help if the spreadsheet doesnt load or it loads empty.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'       => 'remote_image_timeout',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => __( 'Maximum number of seconds to download external images?', 'vg_sheet_editor' ),
						'desc'     => __( 'Default: 4. When you enter an external image URL in any image cells (or during the import), we download the image file into the WordPress media library and save it in the field. By default, we limit the download time to 4 seconds, any download that exceeds that number of seconds will be cancelled', 'vg_sheet_editor' ),
					),
					array(
						'id'       => 'maximum_advanced_filters_fields',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => __( 'Maximum meta fields displayed in the advanced filters dropdown', 'vg_sheet_editor' ),
						'desc'     => __( 'We limit the list of fields to 1000 meta fields to avoid memory leaks/performance issues. If you want to search by a field that does not appear in the dropdown, increase this number.', 'vg_sheet_editor' ),
						'default'  => 1000,
					),
					array(
						'id'      => 'allow_line_breaks_export_import',
						'type'    => 'switch',
						'title'   => __( 'Allow line breaks in values during the export and import process', 'vg_sheet_editor' ),
						'desc'    => __( 'We will not execute our removal of duplicate rows when the line breaks are allowed, so you might see duplicate rows in the export files in rare cases.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'allow_html_in_post_titles',
						'type'    => 'switch',
						'title'   => __( 'Allow safe html in post titles?', 'vg_sheet_editor' ),
						'desc'    => __( 'We will remove all the html tags by default. Activate this option to allow safe html tags, like b, span, i, etc', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'save_every_term_in_hierarchy',
						'type'    => 'switch',
						'title'   => __( 'Attach every taxonomy term in the hierarchy to the posts?', 'vg_sheet_editor' ),
						'desc'    => __( 'By default, we only attach the last term in the hierarchy to the posts because WordPress automatically handles them correctly. But if you want to assign each term to the post, including the parent categories, activate this option', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'run_save_post_action_always',
						'type'    => 'switch',
						'title'   => __( 'Always run the save_post action after any post row is edited?', 'vg_sheet_editor' ),
						'desc'    => __( 'By default, we run the save_post action only when a post data field is updated (fields other than meta and taxonomies) for performance reasons. Enable this if other plugins aren\'t detecting our changes or webhooks aren\'t running.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'       => 'meta_fields_scan_limit',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => __( 'Maximum number of unique meta fields to scan?', 'vg_sheet_editor' ),
						'desc'     => __( 'By default, we scan a maximum of 2500 unique meta keys to generate columns for the spreadsheet editor. But if you have a huge meta table, we might miss some meta fields and not show some columns. You can increase the number to scan more fields, which will help to display some missing columns, but it will use more server resources. The scan happens every 30 minutes on small sites, or weekly on medium-large sites.', 'vg_sheet_editor' ),
						'default'  => 2500,
					),
					array(
						'id'      => 'external_files_accept_url_parameters',
						'type'    => 'switch',
						'title'   => __( 'Don\'t remove query parameters from external URLs when saving files?', 'vg_sheet_editor' ),
						'desc'    => __( 'By default, we remove the query strings from external URLs when importing external files, this improves the cache hits and helps us avoid downloading duplicate images that have slight differences in the URLs. For example, site.com/logo.png?m=1 and site.com/logo.png?m=2 are considered the same by default and we save it as site.com/logo.png. But this can break the saving of dynamic images where the parameters change the content of the image, for example, site.com/image-generator.php?image=1 can return a different image than site.com/image-generator.php?image=2. You can activate this option to make the saving of external images accept URL parameters.', 'vg_sheet_editor' ),
						'default' => false,
					),
				),
			);

			$roles                                = wp_roles();
			$this->sections['customize_features'] = array(
				'icon'   => 'el-icon-cogs',
				'title'  => __( 'Customize features', 'vg_sheet_editor' ),
				'fields' => array(
					array(
						'id'      => 'enable_spreadsheet_views_restrictions',
						'type'    => 'switch',
						'title'   => __( 'Enable option to restrict spreadsheet views per user?', 'vg_sheet_editor' ),
						'desc'    => __( 'If you enable this option, we will add fields to the user profiles where you can specify which spreadsheet views they can use, they can use all the spreadsheet views if this is deactivated.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'enable_simple_mode',
						'type'    => 'switch',
						'title'   => __( 'Enable simple mode?', 'vg_sheet_editor' ),
						'desc'    => __( 'If you enable this option, we will simplify the spreadsheet options and remove advanced examples, tips, and options rarely used in the search tool, bulk edit tool, import tool, export tool, and other places.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'disable_automatic_formatting_detection',
						'type'    => 'switch',
						'title'   => __( 'Disable the automatic formatting detection?', 'vg_sheet_editor' ),
						'desc'    => __( 'If you enable this option, some columns will appear as text. Normally we detect the date fields, image fields.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'be_disable_cells_lazy_loading',
						'type'    => 'switch',
						'title'   => __( 'Disable cells lazy loading?', 'vg_sheet_editor' ),
						'desc'    => __( 'The spreadsheet loads only the "visible rows" for performance reasons, so when you scroll up or down the rows are loaded dynamically. This way you can "open" thousands of posts in the spreadshet and it will work fast. However, if you want to use the browser search to find a specific cell, you need to disable the lazy loading in order to load all the rows at once and the browser will be able to find the cells. The browser search doesn\'t work by default because only the "visible rows" are actually created.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'be_disable_dashboard_widget',
						'type'    => 'switch',
						'title'   => __( 'Disable usage stats widget?', 'vg_sheet_editor' ),
						'desc'    => __( 'If you enable this option, the usage stats widget shown in the wp-admin dashboard will be removed.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'be_disable_serialized_columns',
						'type'    => 'switch',
						'title'   => __( 'Disable serialized columns support?', 'vg_sheet_editor' ),
						'desc'    => __( 'The spreadsheet automatically generates columns for serialized fields, but this can use a lot of CPU cycles depending on the number of serialized fields. You can disable this feature if the sheet is too slow to load or you get errors when loading the rows or you dont want to see columns with prefix "SEIS".', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'be_disable_heartbeat',
						'type'    => 'switch',
						'title'   => __( 'Disable the heartbeat api in the spreadsheet?', 'vg_sheet_editor' ),
						'desc'    => __( 'WordPress uses the heartbeat API to check the login status every few seconds. This can overload your server because it could make hundreds of requests when you are editing in the spreadsheet. You can disable it to reduce the stress on your server while editing in the sheet. However, if you keep the spreadsheet opened over multiple days your login session can expire and you wont be notified if you disable the heartbeat and this can cause issues while saving. So use this option only when you use the spreadsheet for a few hours only.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'be_allowed_user_roles',
						'title'   => __( 'User roles that can use the spreadsheet editor', 'vg_sheet_editor' ),
						'desc'    => __( 'The plugin will not initialize for the user roles not selected here.', 'vg_sheet_editor' ),
						'type'    => 'new_select',
						'multi'   => true,
						'options' => array_combine( array_keys( $roles->roles ), array_keys( $roles->roles ) ),
					),
					array(
						'id'      => 'be_enable_fancy_taxonomy_cell',
						'type'    => 'switch',
						'title'   => __( 'Enable the fancy taxonomy terms selector', 'vg_sheet_editor' ),
						'desc'    => __( 'Backwards compatibility. This setting will be removed in future updates.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'be_taxonomy_cell_renderer',
						'type'    => 'new_select',
						'title'   => __( 'What cell format to use for the taxonomy columns?', 'vg_sheet_editor' ),
						'options' => array(
							''    => __( 'New multi select dropdown (Default)', 'vg_sheet_editor' ),
							'old' => __( 'Old single select autocomplete', 'vg_sheet_editor' ),
						),
					),
					array(
						'id'      => 'show_all_custom_statuses',
						'type'    => 'switch',
						'title'   => __( 'Show all the custom post statuses?', 'vg_sheet_editor' ),
						'desc'    => __( 'By default we show the CORE statuses: published, draft, private, scheduled, trash. However, some plugins register custom statuses: job managers, woocommerce. Enable this option to show all the custom statuses in the "status" column. CAREFUL. We will show all the statuses from all the post types in the dropdown because it is impossible to know the post type of each status to we can not separate them. Do this only if you are a developer.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'hide_cell_comments',
						'type'    => 'switch',
						'title'   => __( 'Remove help messages from the cells?', 'vg_sheet_editor' ),
						'desc'    => __( 'By default we show comments in some columns indicating the value format or why they are locked. for example, the category column shows a tip indicating to separate terms with a comma and how to add child categories, variation columns have a tip indicating why they are locked for parent products. You can activate this option to disable those tips.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'manage_taxonomy_columns_term_ids',
						'type'    => 'switch',
						'title'   => __( 'Manage taxonomy column values as term ids?', 'vg_sheet_editor' ),
						'desc'    => __( 'By default we show the categories as names separated by commas. Activate this option to display and save term ids separated by commas.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'manage_taxonomy_columns_term_slugs',
						'type'    => 'switch',
						'title'   => __( 'Manage taxonomy column values as term slugs?', 'vg_sheet_editor' ),
						'desc'    => __( 'By default we show the categories as names separated by commas. Activate this option to display and save term slugs separated by commas.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'dont_auto_enable_new_fields',
						'type'    => 'switch',
						'title'   => __( 'Do you want to deactivate columns for new fields found?', 'vg_sheet_editor' ),
						'desc'    => __( 'By default we automatically show columns for all the new fields found, so everytime we detect new fields you can see them and edit right away. But this might "break" your column sorting or annoy you if you have enabled specific columns. Enable this option to generate those columns but leave them deactivated so you can enable them later.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'       => 'math_formula_roundup_decimals',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => __( 'Math formula roundup decimals', 'vg_sheet_editor' ),
						'desc'     => __( 'We automatically round up to 2 decimals. You can enter any number here, for example, 1 to round to 1 decimal, 0 to round to the nearest whole number (without decimals). Default: 2 decimals', 'vg_sheet_editor' ),
						'default'  => 2,
					),
					array(
						'id'      => 'enable_plain_select_cells',
						'type'    => 'switch',
						'title'   => __( 'Display raw value on select cells?', 'vg_sheet_editor' ),
						'desc'    => __( 'By default, we show the label in the cell instead of the raw value. But you can enable this option to display the raw value in the cells.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'wpmu_delete_account',
						'type'    => 'switch',
						'title'   => __( 'Delete user accounts in the entire network when deleting users in the spreadsheet?', 'vg_sheet_editor' ),
						'desc'    => __( 'When you use WordPress multisite and you delete a user in the users spreadsheet, by default we only remove the user from the current site but the user remains in the network. Activate this option if you want to delete the user account from the entire network', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'dont_add_id_to_image_urls',
						'type'    => 'switch',
						'title'   => __( 'Disable the addition of file ID to the image URLs?', 'vg_sheet_editor' ),
						'desc'    => __( 'By default, when you export the featured image column or gallery columns, we add the file ID to each image url so we can import them later faster. You can activate this option to not add the file id and the import will work fine later but it wont be as fast', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'dont_display_file_names_image_columns',
						'type'    => 'switch',
						'title'   => __( 'Disable the display of file names in the image columns?', 'vg_sheet_editor' ),
						'desc'    => __( 'By default, we show the file name as a preview next to the thumbnail, you can activate this to show the thumbnail and upload buttons only', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'add_html_class_status_value',
						'type'    => 'switch',
						'title'   => __( 'Add html classes to the "post status" cells based on their values?', 'vg_sheet_editor' ),
						'desc'    => __( 'You can activate this option if you want to add html classes, and use those html classes to change the cell colors for each status using custom CSS', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'       => 'tinymce_preview_characters_limit',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => __( 'Maximum number of characters displayed in the preview of the values of tinymce columns?', 'vg_sheet_editor' ),
						'desc'     => __( 'By default, we only display 30 characters. You can increase the number here to view larger previews.', 'vg_sheet_editor' ),
						'default'  => 30,
					),
					array(
						'id'      => 'manage_post_parents_with_id',
						'type'    => 'switch',
						'title'   => __( 'Manage the post parent column using IDs?', 'vg_sheet_editor' ),
						'desc'    => __( 'By default, the "parent" column displays titles and saves using titles. If you activate this option, the column will display IDs and save IDs. This is useful if you have duplicate titles and you need to save the exact parent by ID.', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'color_mode',
						'title'   => __( 'Enable the dark mode?', 'vg_sheet_editor' ),
						'default' => '',
						'type'    => 'new_select',
						'options' => array(
							''      => __( 'Auto (Default)', 'vg_sheet_editor' ),
							'light' => __( 'Light mode', 'vg_sheet_editor' ),
							'dark'  => __( 'Dark mode', 'vg_sheet_editor' ),
						),
					),
				),
			);
			$this->sections['general']            = array(
				'icon'   => 'el-icon-cogs',
				'title'  => __( 'General settings', 'vg_sheet_editor' ),
				'fields' => array(
					array(
						'id'   => 'info_normal_234343',
						'type' => 'info',
						'desc' => __( 'In this page you can quickly set up the spreadsheet editor. This all you need to use the editor. The settings on the other tabs are completely optional and allow you to tweak the performance of the editor among other things.', 'vg_sheet_editor' ),
					),
				),
			);

			$this->sections['misc'] = array(
				'icon'   => 'el-icon-plane',
				'title'  => __( 'Misc', 'vg_sheet_editor' ),
				'fields' => array(
					array(
						'id'       => 'media_preview_width',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => __( 'Image preview width inside the cell', 'vg_sheet_editor' ),
						'default'  => 25,
					),
					array(
						'id'       => 'media_preview_height',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => __( 'Image preview height inside the cell', 'vg_sheet_editor' ),
						'default'  => 22,
					),
					array(
						'id'       => 'be_initial_rows_offset',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => __( 'Initial rows offset', 'vg_sheet_editor' ),
						'desc'     => __( 'When you have 1000 posts , you might want to open the spreadsheet and start editing from post 200. This option lets you skip a lot of rows. IMPORTANT. We use the pagination, so we will display the page closest to that number. For example. If you load 10 rows per page and enter 1205 as offset, the sheet will start from page 120 (index 1200) because it is the page closest to the defined offset.', 'vg_sheet_editor' ),
						'default'  => 0,
					),
					array(
						'id'      => 'delete_attached_images_when_post_delete',
						'type'    => 'switch',
						'title'   => __( 'Delete the attached images when deleting a post?', 'vg_sheet_editor' ),
						'desc'    => __( 'For example, when deleting a post completely (not moving to the trash), delete the featured image and product gallery images from the media library. CAREFUL.If you use the same images on multiple posts, it will break the images on other posts', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'      => 'be_post_types',
						'type'    => 'new_select',
						'multi'   => true,
						'options' => array( $helpers, 'get_allowed_post_types' ),
						'title'   => __( 'Spreadsheets enabled for these post types', 'vg_sheet_editor' ),
					),
					array(
						'id'      => 'disable_help_toolbar',
						'type'    => 'switch',
						'title'   => __( 'Disable the help toolbar?', 'vg_sheet_editor' ),
						'desc'    => __( 'This will hide the "help" option in the top toolbar', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'       => 'max_value_length_locked_cells',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => __( 'Maximum visible characters in locked cells', 'vg_sheet_editor' ),
						'desc'     => sprintf( __( 'Locked cells only show a preview of the value. The default character length is %d', 'vg_sheet_editor' ), VGSE()->helpers->get_plugin_mode() === 'pro-plugin' ? 120 : 55 ),
						'default'  => VGSE()->helpers->get_plugin_mode() === 'pro-plugin' ? 120 : 55,
					),
					array(
						'id'      => 'dont_show_readonly_columns_in_advanced_search',
						'type'    => 'switch',
						'title'   => __( 'Don\'t show read-only columns in the advanced search?', 'vg_sheet_editor' ),
						'default' => false,
					),
					array(
						'id'       => 'allow_formula_remove_duplicates_meta_keys',
						'type'     => 'text',
						'title'    => __( 'Allow to remove duplicate rows by these meta fields', 'vg_sheet_editor' ),
						'desc'     => __( 'By default, our bulk edit allows you to remove duplicate posts by title+content, or by product SKU. You can add multiple meta keys separated with a comma here to allow to remove duplicate posts by a meta field. We don\'t allow this for any column because this should be used on meta fields that are supposed to contain unique values', 'vg_sheet_editor' ),
					),
				),
			);
			if ( VGSE()->helpers->get_plugin_mode() === 'pro-plugin' ) {
				$this->sections['misc']['fields'][]            = array(
					'id'      => 'be_disable_extension_offerings',
					'type'    => 'switch',
					'title'   => __( 'Disable extension offerings?', 'vg_sheet_editor' ),
					'default' => false,
				);
				$this->sections['misc']['fields'][]            = array(
					'id'      => 'exclude_non_visible_columns_from_tools',
					'type'    => 'switch',
					'title'   => __( 'Don\'t display the disabled columns in the modules?', 'vg_sheet_editor' ),
					'desc'    => __( 'By default, we show all the columns in the app modules (advanced search, bulk edit, export, import, etc), even if they\'re disabled in the columns manager. Activate this option to only display enabled columns.', 'vg_sheet_editor' ),
					'default' => false,
				);
				$this->sections['solution_errors']['fields'][] = array(
					'id'      => 'be_allow_raw_content_unfiltered_html_capability',
					'type'    => 'switch',
					'title'   => __( 'Allow users with the capability unfiltered_html to save any html in the post content column?', 'vg_sheet_editor' ),
					'desc'    => __( 'By default, we remove any unsafe html from all the spreadsheet columns before saving the values. You can activate this option if you want to allow the WordPress super admins or administrators to add iframes, ad codes, etc to the post content. Use it at your own risk.', 'vg_sheet_editor' ),
					'default' => false,
				);

				$this->sections['solution_errors']['fields'][] = array(
					'id'    => 'serialized_field_post_templates',
					'type'  => 'text',
					'title' => __( 'Generate serialized fields columns based on these posts', 'vg_sheet_editor' ),
					'desc'  => __( 'This option allows you to indicate what posts to use as templates for specific serialized fields. Enter the field key and post ID separated with a colon. For example: serialized_key1:89, serializedkey2:90', 'vg_sheet_editor' ),
				);
			}

			$this->sections = apply_filters( 'vg_sheet_editor/options_page/options', $this->sections );

			// Auto generate section keys to prevent duplicate settings tabs in case we add sections without keys
			$new_sections   = array();
			foreach ( $this->sections as $section_key => $section ) {
				if ( is_numeric( $section_key ) ) {
					$new_sections[ sanitize_html_class( $section['title'] ) ] = $section;
				} else {
					$new_sections[ $section_key ] = $section;
				}
			}
			$this->sections = $new_sections;
			// Redux filter is here for backwards compatibility
			$this->sections = apply_filters( 'redux/options/' . VGSE()->options_key . '/sections', $this->sections );
			return $this->sections;
		}

		function init() {
			add_action( 'admin_menu', array( $this, 'register_menu_page' ), 99 );
		}

		function register_menu_page() {
			$rest_api_only = apply_filters( 'vg_sheet_editor/use_rest_api_only', ! empty( VGSE()->options['be_rest_api_only'] ) );
			$parent_slug   = ( ! empty( $rest_api_only ) ) ? 'options-general.php' : 'vg_sheet_editor_setup';

			if ( ! empty( $rest_api_only ) ) {
				add_submenu_page( $parent_slug, __( 'WP Sheet Editor' ), __( 'WP Sheet Editor' ), 'manage_options', VGSE()->options_key, array( $this, 'render_settings_page' ) );
			} else {
				add_submenu_page( $parent_slug, __( 'Settings' ), __( 'Settings' ), 'manage_options', VGSE()->options_key, array( $this, 'render_settings_page' ) );
			}
			// Add it to the frontend sheet menu too
			add_submenu_page( 'vgsefe_welcome_page', __( 'Settings' ), __( 'Settings' ), 'manage_options', VGSE()->options_key, array( $this, 'render_settings_page' ) );
		}

		function render_settings_form( $provider = null ) {
			$supported_types    = array( 'text', 'textarea', 'switch', 'new_select' );
			$raw_sections       = $this->getSections();
			$sections           = array();
			$default_field_args = array(
				'id'         => '',
				'type'       => '',
				'title'      => '',
				'desc'       => '',
				'default'    => null,
				'multi'      => false,
				'options'    => array(),
				'validate'   => '',
				'class_name' => '',
			);
			foreach ( $raw_sections as $section_index => $section ) {
				foreach ( $section['fields'] as $field ) {
					if ( in_array( $field['type'], $supported_types, true ) ) {
						if ( ! isset( $sections[ $section_index ] ) ) {
							$section['fields']          = array();
							$sections[ $section_index ] = $section;
						}
						$field = wp_parse_args( $field, $default_field_args );
						$sections[ $section_index ]['fields'][ $field['id'] ] = $field;
					}
				}
			}
			require VGSE_DIR . '/views/settings-form.php';
		}

		function render_settings_page() {
			$nonce = wp_create_nonce( 'bep-nonce' );
			require VGSE_DIR . '/views/settings-page.php';
		}

		/**
		 * Creates or returns an instance of this class.
		 */
		static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new WPSE_Options_Page();
				self::$instance->init();
			}
			return self::$instance;
		}

		function __set( $name, $value ) {
			$this->$name = $value;
		}

		function __get( $name ) {
			return $this->$name;
		}

	}

}

if ( ! function_exists( 'WPSE_Options_Page_Obj' ) ) {

	function WPSE_Options_Page_Obj() {
		return WPSE_Options_Page::get_instance();
	}
}
WPSE_Options_Page_Obj();
