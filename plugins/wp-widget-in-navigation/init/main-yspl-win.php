<?php
if ( ! class_exists( 'YSPL_WIN_MAIN' ) ) {

	class YSPL_WIN_MAIN {

		/**
		 *
		 * @var string A string prefix for html element attributes 
		 */
		public $attr_prefix;

		/**
		 *
		 * @var string The text-domain
		 */
		public $domain = 'yspl-win';

		/**
		 * Hooks to the necessary actions and filters
		 */
		public function yspl_init() {
			add_filter( 'admin_body_class',array( $this, 'ysplmin_body_classes' ));
			// hook the widget Sidebar registration
			add_action( 'widgets_init', array( $this, 'yspl_win_widgetSidebar' ) );

			// add our custom js on edit menu screen
			add_action( 'admin_enqueue_scripts', array( $this, 'yspl_win_enqueue' ) );

			// add custom css and js in the frontend
			add_action('wp_enqueue_scripts', array($this, 'yspl_frontend_win_enqueue'));

			// filter the menu item display on edit screen
			add_filter( 'wp_setup_nav_menu_item', array( $this, 'yspl_win_label' ), 10, 1 );
			
			// replace the default menu add ajax
			add_action( 'admin_init', array($this, 'yspl_win_filter_ajax'));

			add_filter( 'ysplmenu_tabs', array( $this, 'add_yspl_menu_tab'), 10, 5 );
		}
		
		/**
		 * Add class in body in admin panel
		 */
		public function ysplmin_body_classes( $classes ) {
		    return "$classes ysplwin_enabled";
		}

		/**
		 * Regsiter a custom widget area for our widgets
		 */
		public function yspl_win_widgetSidebar() {

			register_sidebar( array(
				'name' => __( 'Widgets in Navigation Menu', $this->domain ),
				'id' => YSPL_WIN_PREFIX,
				"before_widget" => '<div id="%1$s" class="' . YSPL_WIN_PREFIX . '_widget_nav %2$s">',
				"after_widget" => '</div>',
				'description' => __( 'Widgets in this area will be shown on the Appearance->menu screen.', $this->domain ),
				'before_title' => '<span class="' . YSPL_WIN_PREFIX . '_title">',
				'after_title' => '</span>'
			) );
		}

		/**
		 * Add a custom metabox on edit menu screen for widgets
		 * 
		 * @globa		int			$_nav_menu_placeholder	A placeholder index for the menu item
		 * @global		int|string	$nav_menu_selected_id	(id, name or slug) of the currently-selected menu
		 * @global      array		$wp_registered_widgets	All registered widgets
		 * @global      array		$wp_registered_sidebars All registered sidebars
		 */
		public function yspl_win_nav_meta_box() {

			// initialise some global variables
			global $_nav_menu_placeholder, $nav_menu_selected_id,
			$wp_registered_widgets, $wp_registered_sidebars;


			// initialise the output variable
			$output = '';

			// get all the sidebar widgets
			$yspl_win_sidebars_widgets = wp_get_sidebars_widgets();

			// we don't have widgets
			if ( empty( $wp_registered_sidebars[ YSPL_WIN_PREFIX ] ) || empty( $yspl_win_sidebars_widgets[ YSPL_WIN_PREFIX ] ) || ! is_array( $yspl_win_sidebars_widgets[ YSPL_WIN_PREFIX ] ) ) {

				// the default output
				$no_widgets_output = '<p>';
				$no_widgets_output .= sprintf( __( '<a href="%s">Please add a '
						. 'widget</a> to the <em>Widgets in Menu</em> area', $this->domain ), admin_url( "widgets.php" ) );
				$no_widgets_output .= '</p>';

				/**
				 * Filters the html displayed if no widgets are present in the sidebar. 
				 * 
				 * @since 1.0.0
				 * 
				 * @param string $no_widgets_output The default output
				 */
				$no_widgets_output = apply_filters( 'yspl_win_no_widgets_message', $no_widgets_output );

				// add to the final output
				$output .= $no_widgets_output;
			} else {
				// we have widgets, so we'll output them in an unordered list,
				// like wordpress does
				$output .= '<ul>';

				// loop through our widgets
				foreach ( ( array ) $yspl_win_sidebars_widgets[ YSPL_WIN_PREFIX ] as $id ) {

					// bail if not set
					if ( ! isset( $wp_registered_widgets[ $id ] ) )
						continue;

					// figure the placeholder index
					$_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;

					// this widget
					$widget = $wp_registered_widgets[ $id ];

					// the widget number (for eg, calendar-3, 3 it is)
					$widget_num = $widget[ 'params' ][ 0 ][ "number" ];

					// get the widget slug from the id
					$widget_slug = rtrim( preg_replace( "|[0-9]+|i", "", $id ), '-' );

					// get the widget's settings from the options table
					$widget_saved = get_option( 'widget_' . $widget_slug, array() );

					// get the title from the saved settings
					$widget_title = $widget_saved[ $widget_num ][ 'title' ];

					// get the name
					$widget_name = $widget[ 'name' ];
					$widget_name .= (empty( $widget_title )) ? '' : ': ' . $widget_title;

					// start the list item
					$output .= '<li>';
					$output .= '<label for="' . $id . '">';

					// checkbox
					$output .= '<input name="menu-item['
						. $_nav_menu_placeholder . '][menu-item-object-id]" type="checkbox" value="'
						. $widget_num . '" id="' . $id . '" class="menu-item-checkbox ' . $id . '">';
					$output .= $widget_name;
					$output .= '</label>';

					// db-id is 0,will be created when the menu item is created in the db
					$output .= '<input type="hidden" class="menu-item-db-id" name="menu-item['
						. $_nav_menu_placeholder . '][menu-item-db-id]" value="0" />';

					// object is our prefix
					$output .= '<input type="hidden" class="menu-item-object" name="menu-item['
						. $_nav_menu_placeholder . '][menu-item-object]" value="'
						. YSPL_WIN_PREFIX . '" />';

					// no parent-id
					$output .= '<input type="hidden" class="menu-item-parent-id" name="menu-item['
						. $_nav_menu_placeholder . '][menu-item-parent-id]" value="0" />';

					// type is our prefix
					$output .= '<input type="hidden" class="menu-item-type" name="menu-item['
						. $_nav_menu_placeholder . '][menu-item-type]" value="' . YSPL_WIN_PREFIX . '" />';

					// title
					$output .= '<input type="hidden" class="menu-item-title" name="menu-item['
						. $_nav_menu_placeholder . '][menu-item-title]" value="' . $widget_name . '" />';

					// the empty stuff
					$output .= '<input type="hidden" class="menu-item-url" name="menu-item['
						. $_nav_menu_placeholder . '][menu-item-url]" value="" />';
					$output .= '<input type="hidden" class="menu-item-target" name="menu-item['
						. $_nav_menu_placeholder . '][menu-item-target]" value="" />';
					$output .= '<input type="hidden" class="menu-item-attr_title" name="menu-item['
						. $_nav_menu_placeholder . '][menu-item-attr_title]" value="" />';
					$output .= '<input type="hidden" class="menu-item-classes" name="menu-item['
						. $_nav_menu_placeholder . '][menu-item-classes]" value="" />';

					// storing our id in xfn. could have been any of the above
					$output .= '<input type="hidden" class="menu-item-xfn" name="menu-item['
						. $_nav_menu_placeholder . '][menu-item-xfn]" value="' . $id . '" />';
					$output .= '</li>';
				}

				$output .= '<p style="display:none;" class="msg-yspl_win">';
				// no text-domain, so that the Strings translated by WordPress are used
				$output .= __( 'Settings', $this->domain )
					. ': '
					. sprintf( '<a href="%s">', admin_url( "widgets.php" ) )
					. __( 'Appearance', $this->domain )
					. ' > '
					. __( 'Widgets', $this->domain ) . '</a>';
				$output .= '<p>';
				$output .= '</ul>';
			}

			// submit button
			?>
			<div class="yspl_windiv" id="yspl_windiv">
				<?php echo $output; ?>
				<p class="button-controls">
					<span class="add-to-menu">
						<input type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?> class="button button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu' ); ?>" name="add-yspl_win-menu-item" id="submit-yspl_win" />
						<span class="spinner"></span>
					</span>
				</p>

			</div><!-- /.customlinkdiv -->
			<?php
		}
		
		/**
		 * Removes default menu add function & replaces with custom
		 * 
		 * @since 0.2.0
		 */
		public function yspl_win_filter_ajax(){
		
			// add our own function
			add_action('wp_ajax_add-menu-item', array($this, 'yspl_win_add_menu_item'), 0);
			add_action('wp_ajax_yspl_win_add_menu_item', array($this, 'yspl_win_add_menu_item'), 0);

			add_action( 'wp_ajax_yspl_get_lightbox_html', array($this, 'yspl_ajax_get_lightbox_html'), 0);

		}

		/**
		 * Ajax handler for adding a menu item. Replaces wp_ajax_add_menu_item
		 *
		 * @since 0.2.0
		 */
		public function yspl_win_add_menu_item() {
			//echo 'aaaaa'; exit;
			// remove default WP function
			// first extra line in the wp_ajax_add_menu_item clone that this method actually is :(
			remove_action('wp_ajax_add-menu-item', 'wp_ajax_add_menu_item');
			
			check_ajax_referer( 'add-menu_item', 'menu-settings-column-nonce' );

			if ( ! current_user_can( 'edit_theme_options' ) )
				wp_die( -1 );

			require_once ABSPATH . 'wp-admin/includes/nav-menu.php';

			// For performance reasons, we omit some object properties from the checklist.
			// The following is a hacky way to restore them when adding non-custom items.

			$menu_items_data = array();
			
			foreach ( ( array ) $_POST[ 'menu-item' ] as $menu_item_data ) {
				if (
					! empty( $menu_item_data[ 'menu-item-type' ] ) &&
					'custom' != $menu_item_data[ 'menu-item-type' ] &&
					! empty( $menu_item_data[ 'menu-item-object-id' ]) &&
						YSPL_WIN_PREFIX != $menu_item_data[ 'menu-item-type' ] // this is the second extra line
				) {
					switch ( $menu_item_data[ 'menu-item-type' ] ) {
						case 'post_type' :
							$_object = get_post( $menu_item_data[ 'menu-item-object-id' ] );
							break;

						case 'post_type_archive' :
							$_object = get_post_type_object( $menu_item_data[ 'menu-item-object' ] );
							break;

						case 'taxonomy' :
							$_object = get_term( $menu_item_data[ 'menu-item-object-id' ], $menu_item_data[ 'menu-item-object' ] );
							break;
					}

					$_menu_items = array_map( 'wp_setup_nav_menu_item', array( $_object ) );
					$_menu_item = reset( $_menu_items );

					// Restore the missing menu item properties
					$menu_item_data[ 'menu-item-description' ] = $_menu_item->description;
				}

				$menu_items_data[] = $menu_item_data;
			}

			$item_ids = wp_save_nav_menu_items( 0, $menu_items_data );
			if ( is_wp_error( $item_ids ) )
				wp_die( 0 );

			$menu_items = array();

			foreach ( ( array ) $item_ids as $menu_item_id ) {
				$menu_obj = get_post( $menu_item_id );
				if ( ! empty( $menu_obj->ID ) ) {
					$menu_obj = wp_setup_nav_menu_item( $menu_obj );
					$menu_obj->label = $menu_obj->title; // don't show "(pending)" in ajax-added items
					$menu_items[] = $menu_obj;
				}
			}

			/** This filter is documented in wp-admin/includes/nav-menu.php */
			$walker_class_name = apply_filters( 'wp_edit_nav_menu_walker', 'Walker_Nav_Menu_Edit', $_POST[ 'menu' ] );

			if ( ! class_exists( $walker_class_name ) )
				wp_die( 0 );

			if ( ! empty( $menu_items ) ) {
				$args = array(
					'after' => '',
					'before' => '',
					'link_after' => '',
					'link_before' => '',
					'walker' => new $walker_class_name,
				);
				echo walk_nav_menu_tree( $menu_items, 0, ( object ) $args );
			}
			wp_die();
		}

		/**
		 * Enqueue our js for hooking into wpNavMenu class
		 * 
		 * @param string $hook A string to identify the current screen
		 * @return null
		 */
		public function yspl_win_enqueue( $hook ) {

			// bail if not the edit menu screen
			if ( 'nav-menus.php' != $hook ) {
				return;
			}
			$min = '';
			if ( ! WP_DEBUG ) {
				$min = '.min';
			}
			wp_enqueue_style( 'colorbox',  YSPL_WIN_URL . 'css/colorbox.css', false, YSPL_WIN_VERSION );
			wp_enqueue_style( 'yspl-style',  YSPL_WIN_URL . 'css/admin.css', false, YSPL_WIN_VERSION );
			wp_enqueue_script(
				YSPL_WIN_PREFIX, YSPL_WIN_URL . "/js/yspl-win.js", array( 'nav-menu' ), YSPL_WIN_VERSION
			);
			wp_enqueue_script( 'yspl-script', YSPL_WIN_URL . 'js/admin.js', array(
	            'jquery',
	            'jquery-ui-core',
	            'jquery-ui-sortable',
	            'jquery-ui-accordion'),
	        YSPL_WIN_VERSION );

	        wp_enqueue_script( 'colorbox',  YSPL_WIN_URL . 'js/jquery.colorbox-min.js', array( 'jquery' ), YSPL_WIN_VERSION );

	        wp_localize_script( 'yspl-script', 'ysplwin',
	            array(
	                'debug_launched' => __("Launched for Menu ID", $this->domain),
	                'launch_lightbox' => __("Widget", $this->domain),
	                'is_disabled_error' => __(""),
	                'save_menu' => __("Please save the menu structure to enable this option.", $this->domain),
	                'saving' => __("Saving", $this->domain),
	                'nonce' => wp_create_nonce('megamenu_edit'),
	                'nonce_check_failed' => __("Oops. Something went wrong. Please reload the page.", $this->domain),
	                'css_prefix' => 'aaaa',
	                'css_prefix_message' => __(""),
	                'row_is_full' => __("There is not enough space in this row to add a new column. Make space by reducing the width of the columns within the row or create a new row.", $this->domain)
	            )
	        );


		}

		/**
		 * Enqueue our js for hooking into wpNavMenu class
		 * 
		 * @param string $hook A string to identify the current screen
		 * @return null
		 */
		public function yspl_frontend_win_enqueue($hook)
		{
			wp_enqueue_style('yspl-public-style',  YSPL_WIN_URL . 'css/style.css', false, YSPL_WIN_VERSION);
		}

		/**
		 * Changes the label from 'Custom' to 'Widget' on the individual menu item
		 * 
		 * @param object $item The menu item
		 * @return object
		 */
		public function yspl_win_label( $item ) {
			
			if ( $item->object === YSPL_WIN_PREFIX ) {

				// setup our label
				$item->type_label = __( 'Widget', $this->domain );
			}
			return $item;
		}


		public function yspl_ajax_get_lightbox_html() {
			$tabs = array();

	        $tabs = apply_filters( "ysplmenu_tabs", $tabs, $this->menu_item_id, $this->menu_id, $this->menu_item_depth, $this->menu_item_meta );

	        if ( ob_get_contents() ) ob_clean(); // remove any warnings or output from other plugins which may corrupt the response

			wp_send_json_success( json_encode( $tabs ) );

			exit;
		}

		public function add_yspl_menu_tab( $tabs, $menu_item_id, $menu_id, $menu_item_depth, $menu_item_meta ) {

			// initialise some global variables
			global $_nav_menu_placeholder, $nav_menu_selected_id,
			$wp_registered_widgets, $wp_registered_sidebars;


			// initialise the output variable
			$output = '';

			$return = "<div id='widgets' class='enabled ui-sortable {$class}' style='display: {$display}' data-columns='{$menu_item_meta['panel_columns']}'>";

			// get all the sidebar widgets


			// get all the sidebar widgets
			$yspl_win_sidebars_widgets = wp_get_sidebars_widgets();

			// we don't have widgets
			if (empty($wp_registered_sidebars[YSPL_WIN_PREFIX]) || empty($yspl_win_sidebars_widgets[YSPL_WIN_PREFIX]) || !is_array($yspl_win_sidebars_widgets[YSPL_WIN_PREFIX])) {

				// the default output
				$no_widgets_output = '<p>';
				$no_widgets_output .= sprintf(__('<a href="%s">Please add a '
				. 'widget</a> to the <em>Widgets in Menu</em> area', $this->domain), admin_url("widgets.php"));
				$no_widgets_output .= '</p>';

				/**
				 * Filters the html displayed if no widgets are present in the sidebar. 
				 * 
				 * @since 1.0.0
				 * 
				 * @param string $no_widgets_output The default output
				 */
				$no_widgets_output = apply_filters('yspl_win_no_widgets_message', $no_widgets_output);

				// add to the final output
				$output .= $no_widgets_output;
			} else {
				// we have widgets, so we'll output them in an unordered list,
				// like wordpress does
				$output .= '<form id="add_widget_form">';
				$output .= '<ul id="widgets">';

				// loop through our widgets
				foreach ((array) $yspl_win_sidebars_widgets[YSPL_WIN_PREFIX] as $id) {

					// bail if not set
					if (!isset(
						$wp_registered_widgets[$id]
					))
					continue;

					// figure the placeholder index
					$_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;

					// this widget
					$widget = $wp_registered_widgets[$id];

					// the widget number (for eg, calendar-3, 3 it is)
					$widget_num = $widget['params'][0]["number"];

					// get the widget slug from the id
					$widget_slug = rtrim(preg_replace("|[0-9]+|i", "", $id), '-');

					// get the widget's settings from the options table
					$widget_saved = get_option('widget_' . $widget_slug, array());

					// echo "<pre>";
					// print_r($widget_saved);
					// echo "</pre>";
					// exit();
					// get the title from the saved settings
					$widget_title = $widget_saved[$widget_num]['title'];

					// get the name
					$widget_name = $widget['name'];
					$widget_name .= (empty($widget_title)) ? '' : ': ' . $widget_title;

					// start the list item
					$output .= '<li class="widget-top ui-sortable-handle widget">';
					$output .= '<label for="' . $id . '">';
					// checkbox
					$output .= '<input name="menu-item['
						. $_nav_menu_placeholder . '][menu-item-object-id]" type="checkbox" value="'
						. $widget_num . '" id="' . $id . '" class="menu-item-checkbox ' . $id . '">';
					$output .= $widget_name;
					$output .= '</label>';
					// db-id is 0,will be created when the menu item is created in the db
					$output .= '<input type="hidden" class="menu-item-db-id" name="menu-item['
					. $_nav_menu_placeholder . '][menu-item-db-id]" value="0" />';

					// object is our prefix
					$output .= '<input type="hidden" class="menu-item-object" name="menu-item['
					. $_nav_menu_placeholder . '][menu-item-object]" value="'
					. YSPL_WIN_PREFIX . '" />';

					// no parent-id
					$output .= '<input type="hidden" class="menu-item-parent-id" name="menu-item['
					. $_nav_menu_placeholder . '][menu-item-parent-id]" value="0" />';

					// type is our prefix
					$output .= '<input type="hidden" class="menu-item-type" name="menu-item['
						. $_nav_menu_placeholder . '][menu-item-type]" value="' . YSPL_WIN_PREFIX . '" />';

					// title
					$output .= '<input type="hidden" class="menu-item-title" name="menu-item['
					. $_nav_menu_placeholder . '][menu-item-title]" value="' . $widget_name . '" />';

					// the empty stuff
					$output .= '<input type="hidden" class="menu-item-url" name="menu-item['
					. $_nav_menu_placeholder . '][menu-item-url]" value="" />';
					$output .= '<input type="hidden" class="menu-item-target" name="menu-item['
					. $_nav_menu_placeholder . '][menu-item-target]" value="" />';
					$output .= '<input type="hidden" class="menu-item-attr_title" name="menu-item['
					. $_nav_menu_placeholder . '][menu-item-attr_title]" value="" />';
					$output .= '<input type="hidden" class="menu-item-classes" name="menu-item['
					. $_nav_menu_placeholder . '][menu-item-classes]" value="" />';

					// storing our id in xfn. could have been any of the above
					$output .= '<input type="hidden" class="menu-item-xfn" name="menu-item['
					. $_nav_menu_placeholder . '][menu-item-xfn]" value="' . $id . '" />';
					$output .= '</li>';
				}

				$output .= '<p style="display:none;" class="msg-yspl_win">';
				// no text-domain, so that the Strings translated by WordPress are used
				$output .= __('Settings', $this->domain)
				. ': '
				. sprintf('<a href="%s">', admin_url(
					"widgets.php"
				))
				. __('Appearance', $this->domain)
					. ' > '
					. __('Widgets', $this->domain) . '</a>';
				$output .= '<p>';
				$output .= '</ul>';
				$output .= '<div class="yspl_windiv" id="yspl_windivaa">
					<p class="button-controls">
						<span class="add-to-menu">';
				$output .= '<input type="submit"' . ($nav_menu_selected_id) . ' class="button button-secondary submit-add-to-menu right" value="' . __('Add to Menu') . '" name="add-yspl_win-menu-item" id="submit-yspl_winaa" />
							<span class="spinner"></span>
						</span>
					</p>

				</div><!-- /.customlinkdiv -->';
				$output .= '</form>';
			}

	        $return .= "</div>";

			$tabs['yspl_menu'] = array(
	            'title' => __('Widget',  $this->domain ),
	            'content' => $output
	        );
	        return $tabs;
		}
	}
}