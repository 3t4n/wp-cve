<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Bookshelves {
	private static $instance = null;
	const POST_TYPE = 'bookshelves';
	private $meta = array(
		'item_ids'    => 'isbn',
		'alt_text'    => 'alt',
		'slick'       => 'settings',
	);

	public function __construct() {
		add_action( 'init', array( &$this, 'init' ) );
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
	}

	public function init() {
		$this->create_post_type();
		add_action( 'save_post_bookshelves', array( &$this, 'save_bookshelf' ) );
		add_filter( 'single_template', array( $this, 'bookshelves_template' ), 10 );
		add_action( 'update_bookshelf', 'lbs_update_items_from_api' );
	}

	public function admin_init() {
		add_filter( 'manage_edit-bookshelves_columns', array( $this, 'add_bookshelves_columns' ) );
		add_filter( 'manage_edit-bookshelves_sortable_columns', array( $this, 'manage_sortable_columns' ) );
		add_filter( 'posts_clauses', array( $this, 'bookshelves_location_clauses' ), 10, 2 );
		add_action( 'manage_bookshelves_posts_custom_column', array( $this, 'custom_bookshelves_column' ), 10, 2 );
		add_action( 'add_meta_boxes', array( &$this, 'add_meta_boxes' ) );
		
		// Hide the custom fields metabox we had to enable since custom-fields is required for REST access to post custom meta
		remove_meta_box( 'postcustom', self::POST_TYPE, 'advanced' );
	}

	public function create_post_type() {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'                  => __( 'Bookshelves', 'library-bookshelves' ),
					'add_new'               => __( 'Add New Bookshelf', 'library-bookshelves' ),
					'add_new_item'          => __( 'Add New Bookshelf', 'library-bookshelves' ),
					'attributes'            => __( 'Bookshelf Attributes', 'library-bookshelves' ),
					'edit_item'             => __( 'Edit Bookshelf', 'library-bookshelves' ),
					'filter_items_list'     => __( 'Filter bookshelves list', 'library-bookshelves' ),
					'items_list'            => __( 'Bookshelves list', 'library-bookshelves' ),
					'items_list_navigation' => __( 'Bookshelves list navigation', 'library-bookshelves' ),
					'new_item'              => __( 'New Bookshelf', 'library-bookshelves' ),
					'not_found'             => __( 'No Bookshelves found', 'library-bookshelves' ),
					'not_found_in_trash'    => __( 'No Bookshelves found in trash', 'library-bookshelves' ),
					'search_items'          => __( 'Search Bookshelves', 'library-bookshelves' ),
					'singular_name'         => __( 'Bookshelf', 'library-bookshelves' ),
					'view_item'             => __( 'View Bookshelf', 'library-bookshelves' ),
					'view_items'            => __( 'View Bookshelves', 'library-bookshelves' ),
				),
				'public'       => true,
				'show_in_rest' => true,
				'description'  => __( 'This is a bookshelf', 'library-bookshelves' ),
				'supports'     => array( 'title', 'author', 'revisions', 'page-attributes', 'custom-fields' ),
				'menu_icon'    => plugin_dir_url(__FILE__) . '/img/icon.svg',
			)
		);

		$wp_version = get_bloginfo( 'version' );

		if( version_compare( $wp_version, '5.3.0', '>=' ) ) {
			// Register item ID meta to expose it in REST
			register_post_meta(
				self::POST_TYPE,
				$this->meta['item_ids'],
				array(
					'single'       => true,
					'type'         => 'array',
					'show_in_rest' => array(
						'schema' => array(
							'type' => 'array',
							'items' => array(
								'type' => 'string',
							),
						),
					),
				)
			);

			// Register item alt meta to expose it in REST
			register_post_meta(
				self::POST_TYPE,
				$this->meta['alt_text'],
				array(
					'single'       => true,
					'type'         => 'array',
					'show_in_rest' => array(
						'schema' => array(
							'type' => 'array',
							'items' => array(
								'type' => 'string',
							),
						),
					),
				)
			);
		}

		flush_rewrite_rules();
	}

	/**
	 * Adds shortcode column to the Bookshelves post type list
	 *
	 * Merges an array into the existing columns array to make Bookshelf shortcodes visible in the post type list.
	 *
	 * @since 1.0
	 *
	 * @param array $columns Bookshelves post type list columns.
	 *
	 * @return array $columns Modified columns array.
	*/
	public function add_bookshelves_columns( $columns ) {
		return array_merge(
			$columns,
			array(
				'shortcode' => __( 'Shortcode', 'library-bookshelves' ),
			)
		);
	}

	/**
	 * Makes columns sortable
	 *
	 * Makes Bookshelf posts sortable by taxonomy and author.
	 *
	 * @since 1.0
	 *
	 * @param array $sortable_columns Bookshelves post type sortable columns.
	 *
	 * @return array $sortable_columns Modified sortable columns array.
	*/
	public function manage_sortable_columns( $sortable_columns ) {
		$sortable_columns['taxonomy-location'] = 'location';
		$sortable_columns['author'] = 'author';
		return $sortable_columns;
	}

	/**
	 * Modifies Location column sort order
	 *
	 * Modifies SQL clauses used by WP_Query so that the Location column of the Bookshelves post list sorts alphanumerically.
	 *
	 * @since 1.0
	 *
	 * @global object $wpdb Wordpress database object.
	 *
	 * @param array $clauses SQL clauses.
	 * @param object $wp_query WordPress Query object.
	 *
	 * @return array $clauses Modified SQL clauses.
	*/
	public function bookshelves_location_clauses( $clauses, $wp_query ) {
		global $wpdb;

		if ( isset( $wp_query->query['orderby'] ) && 'location' === $wp_query->query['orderby'] ) {
			$clauses['join'] .= <<<SQL
LEFT OUTER JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID={$wpdb->term_relationships}.object_id
LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (term_taxonomy_id)
LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
SQL;
			$clauses['where'] .= " AND (taxonomy = 'location' OR taxonomy IS NULL)";
			$clauses['groupby'] = "object_id";
			$clauses['orderby'] = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC) ";
			$clauses['orderby'] .= ( 'ASC' === strtoupper( $wp_query->get( 'order' ) ) ) ? 'ASC' : 'DESC';
		}

		return $clauses;
	}

/**
 * Fill shortcode column with post shortcodes
 *
 * Writes Bookshelf shortcodes into Shortcode column in Bookshelves post list.
 *
 * @since 1.0
 *
 * @param string $column Column name.
*/	public function custom_bookshelves_column( $column, $post_id ) {
		if ( $column === 'shortcode' ) {
			echo '[bookshelf id="' . esc_attr( $post_id ) . '"]';
		}
	}

/**
 * Load template for single Bookshelf post
 *
 * Sets location of the single Bookshelf template file.
 *
 * @since 1.0
 *
 * @global object $post Wordpress post object.
 *
 * @param string $single_template Single post template location
 *
 * @return string $single_template Bookshelf single post template file location.
*/
	public function bookshelves_template( $single_template ) {
		global $post;
		if ( 'bookshelves' === $post->post_type ) {
			$single_template = dirname( __FILE__ ) . '/single-bookshelves.php';
		}
		return $single_template;
	}

/**
 * Process and store Bookshelf data
 *
 * Saves user input from the post editor into post metadata.
 * For list input method, data entered in a text area is split and saved as an array. If there is a scheduled update it is canceled. 
 * For API input method, the API request is processed.
 * Item identifiers are stored in post meta. Item images are checked for validity and an array marking invalid images is stored in post meta.
 *
 * @since 1.0
 *
 * @param string $post_id Bookshelf ID.
 */
	public function save_bookshelf( $post_id ) {
		// If this is an auto save routine, do nothing.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }

		// If this is an AJAX request, like Quick Edit, do nothing.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { return; }

		if ( ! empty( $_POST ) && wp_verify_nonce( $_POST['items_nonce'], 'add_items' ) ) {
			// Process fields into metadata.
			if ( self::POST_TYPE === $_POST['post_type'] && current_user_can( 'edit_post', $post_id ) ) {

				// Set list or API input depending on user selection.
				$list_input = isset( $_POST['list_input'] ) ? sanitize_html_class( wp_unslash( $_POST['list_input'] ) ) : '';
				update_post_meta( $post_id, 'list_input', $list_input );

				// Create placeholders array
				$placeholders = array();

				// List input method.
				if ( 'true' === $list_input ) {
					// Process item identifiers into post meta.
					$field_name = $this->meta['item_ids'];
					$textarea = isset( $_POST[ $field_name ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $field_name ] ) ) : array();

					// split delimited string into array and save, removing empty lines.
					$itemIDs = preg_split( '/[\s,]+/', $textarea, -1, PREG_SPLIT_NO_EMPTY );
					update_post_meta( $post_id, $field_name, $itemIDs );

					// Process item alt text into post meta.
					$field_name = $this->meta['alt_text'];
					$textarea = isset( $_POST[ $field_name ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $field_name ] ) ) : array();
					// Split string into array, preserving empty lines.
					$alts = preg_split( '/[\n]/', $textarea, -1 );
					update_post_meta( $post_id, $field_name, $alts );

					// Unschedule any cron jobs for this post (in case the post was switched from API to list input).
					lbs_unschedule_event( $post_id );

					// Check for valid images from given identifiers
					$placeholders = lbs_check_images( $itemIDs, $list_input );
				}

				// API input method.
				else {
					$api_meta = array(
						'wsapi'        => isset( $_POST['wsapi'] ) ? sanitize_html_class( wp_unslash( $_POST['wsapi'] ) ) : '',
						'ws-key'       => isset( $_POST['ws-key'] ) ? sanitize_text_field( wp_unslash( $_POST['ws-key'] ) ) : '',
						'ws-secret'    => isset( $_POST['ws-secret'] ) ? sanitize_text_field( wp_unslash( $_POST['ws-secret'] ) ) : '',
						'ws-token-url' => isset( $_POST['ws-token-url'] ) ? sanitize_text_field( wp_unslash( $_POST['ws-token-url'] ) ) : '',
						'ws-request'   => isset( $_POST['ws-request'] ) ? esc_url_raw( wp_unslash( $_POST['ws-request'] ) ) : '',
						'ws-json'      => isset( $_POST['ws-json'] ) ? sanitize_textarea_field( wp_unslash( $_POST['ws-json'] ) ) : '',
						'item_id_type' => isset( $_POST['item-id-type'] ) ? sanitize_html_class( wp_unslash( $_POST['item-id-type'] ) ) : '',
						'schedule'     => isset( $_POST['schedule'] ) ? sanitize_html_class( wp_unslash( $_POST['schedule'] ) ) : '',
					);

					// Process items from API.
					lbs_get_items_from_api( $post_id, $api_meta );
					$itemIDs = get_post_meta( $post_id, 'isbn', true );

					// Check for valid images from given identifiers
					$placeholders = lbs_check_images( $itemIDs, $list_input, $api_meta['wsapi'] );
				}

				// Save the array marking images to be replaced with a placeholder
				update_post_meta( $post_id, 'placeholders', $placeholders );

				// Set shuffle items depending on user selection
				$shuffle_items = isset( $_POST['shuffle_items'] ) ? sanitize_html_class( wp_unslash( $_POST['shuffle_items'] ) ) : '';
				update_post_meta( $post_id, 'shuffle_items', $shuffle_items );

				// Set links disabled depending on user selection
				$disable_links = isset( $_POST['disable_links'] ) ? sanitize_html_class( wp_unslash( $_POST['disable_links'] ) ) : '';
				update_post_meta( $post_id, 'disable_links', $disable_links );

				// Slick setting input.
				// Set Slick override true or false depending on user selection.
				$slick_override = isset( $_POST['slick_override'] ) ? sanitize_html_class( wp_unslash( $_POST['slick_override'] ) ) : '';
				update_post_meta( $post_id, 'slick_override', $slick_override );

				// if "Use plugin global settings" selected and settings exist in meta, delete them.
				if ( 'false' === $slick_override && null !== get_post_meta( $post_id, 'settings' ) ) {
					delete_post_meta( $post_id, 'settings' );
				}

				if ( 'true' === $slick_override ) {
					// Set post meta key.
					$field_name = $this->meta['slick'];
					// Get the settings definitions.
					$settings_obj = lbs_settings_obj();
					// Extract the Slick setting definitions.
					$slick_settings_obj = $settings_obj['slick']['fields'];

					$post_settings = array();

					// Roll through Slick settings and check if they exist in $_POST.
					foreach ( $slick_settings_obj as $glb_setting ) {
						// Add prefix to setting name.
						$setting_name = 'lbs_' . $glb_setting['id'];

						if ( array_key_exists( $setting_name, $_POST ) ) {
							// If exists, run sanitize callback.
							if ( isset( $glb_setting['callback'] ) ) {
								$callback = $glb_setting['callback'];
								add_filter( 'sanitize', $callback );
								$post_settings[ $setting_name ] = apply_filters( 'sanitize', wp_unslash( $_POST[ $setting_name ] ) );
							} else {
								$post_settings[ $setting_name ] = wp_unslash( $_POST[ $setting_name ] );
							}
						} else {
							// If setting not in $_POST set empty value.
							$post_settings[ $setting_name ] = '';
						}
					}
					update_post_meta( $post_id, $field_name, $post_settings );
				}

				// Save Overdrive setting
				$ebooks = isset( $_POST['ebooks'] ) ? sanitize_html_class( wp_unslash( $_POST['ebooks'] ) ) : '';
				update_post_meta( $post_id, 'ebooks', $ebooks );
			}
		}
	}

/**
 * Configure Bookshelf metaboxes
 *
 * Sets options for custom Bookshelf metaboxes:
 * Item input
 * Shortcode display
 * Preview
 * Settings input
 * eBooks selector
 *
 * @since 1.0
 */
	public function add_meta_boxes() {
		// Add ISBN metabox in place of the editor.
		add_meta_box(
			'bookshelves_items_metabox',
			/* translators: Item input metabox title */
			__( 'Items', 'library-bookshelves' ),
			array( $this, 'add_items_metabox' ),
			self::POST_TYPE,
			'advanced',
			'core'
		);

		// Add shortcode metabox.
		add_meta_box(
			'bookshelves_shortcode_metabox',
			/* translators: Shortcode metabox title */
			__( 'Shortcode', 'library-bookshelves' ),
			array( $this, 'add_shortcode_metabox' ),
			self::POST_TYPE,
			'advanced',
			'core'
		);

		// Add preview metabox.
		add_meta_box(
			'bookshelves_preview_metabox',
			/* translators: Bookshelf preview metabox title */
			__( 'Preview', 'library-bookshelves' ),
			array( $this, 'add_preview_metabox' ),
			self::POST_TYPE,
			'advanced',
			'core'
		);

		// Add Slick settings override metabox.
		add_meta_box(
			'bookshelves_settings_metabox',
			/* translators: Slick settings metabox title */
			__( 'Slick Settings Override', 'library-bookshelves' ),
			array( $this, 'add_settings_metabox' ),
			self::POST_TYPE,
			'advanced',
			'core'
		);

		// Add eBook metabox
		add_meta_box(
			'bookshelves_ebook_metabox',
			/* translators: eBook catalog selection metabox title */
			__( 'eBook Catalog', 'library-bookshelves' ),
			array( $this, 'add_ebook_metabox' ),
			self::POST_TYPE,
			'advanced',
			'core'
		);
	}

/**
 * Assembles Bookshelf item input metabox
 *
 * Gets post meta and plugin settings from database.
 * Builds item input fields and fills with any stored data.
 *
 * @since 1.0
 */
 public function add_items_metabox() {
		$post_id = get_the_ID();
		// Get the item input method from post meta.
		$list_input = get_post_meta( $post_id, 'list_input', true );
		// Get the item shuffle status from post meta
		$shuffle_items = get_post_meta( $post_id, 'shuffle_items', true );
		// Get the link activation status from post meta
		$disable_links = get_post_meta( $post_id, 'disable_links', true );
		// If it's not set assume list input method.
		if ( empty( $list_input ) ) { $list_input = 'true'; }

		// Get plugin options.
		$options = lbs_get_opts();
		// Get catalog system.
		$cat_sys = $options['lbs_cat_System'];
		// Get catalog image CDN.
		$cat_cdn = $options['lbs_cat_CDN'];

		// Create nonce for the input metabox.
		wp_nonce_field( 'add_items', 'items_nonce' );
		?>
		<div id="bookshelf-input">
		<input class="list-input" type="radio" name="list_input" value="true" <?php checked( $list_input, 'true' ); ?> >
		<?php esc_html_e( 'Input items as a list', 'library-bookshelves' ); ?>
		<br><br>
		<input class="api-input" type="radio" name="list_input" value="false" <?php checked( $list_input, 'false' ); ?> >
		<?php esc_html_e( 'Retrieve items from a web service', 'library-bookshelves' ); ?>
		<br><br>
		<input type="checkbox" name="shuffle_items" value="false" <?php checked( $shuffle_items, 'false' ); ?> >
		<?php esc_html_e( 'Shuffle items (Randomize the order in which items are displayed.)', 'library-bookshelves' ); ?>
		<br><br>
		<input type="checkbox" name="disable_links" value="false" <?php checked( $disable_links, 'false' ); ?> >
		<?php esc_html_e( 'Disable item links', 'library-bookshelves' ); ?>
		<hr>
		<?php
		// Show notices based on selected catalog system.
		echo '<p>';

		switch ( $cat_sys ) {
			case 'calibre':
			case 'cops':
				esc_html( _e( 'Calibre & COPS users must enter book ID numbers.<br>COPS users with multiple databases must append the database number to item numbers, e.g. 100&db=1.', 'library-bookshelves' ) );
				break;
			case 'ebsco_eds':
				esc_html_e( 'Enter EBSCOHost Accession Numbers.', 'library-bookshelves' );
				break;
			case 'alexandria':
			case 'aspen':
			case 'encore':
			case 'evergreen':
			case 'pika':
			case 'polaris':
			case 'polaris63':
			case 'sirsi_ent':
			case 'tlc':
			case 'vega':
			case 'webpac':
				esc_html_e( 'Enter ISBNs or UPCs.', 'library-bookshelves' );
				break;
			case 'evergreen-record':
				esc_html_e( 'Enter Evergreen item record numbers.', 'library-bookshelves' );
				break;
			case 'koha':
				esc_html( _e( 'Enter ISBNs or Biblionumbers.<br>Use Biblionumbers for items with images stored on your Koha server.', 'library-bookshelves' ) );
				break;
			case 'openlibrary':
				esc_html_e( 'Enter ISBNs or OLIDs.', 'library-bookshelves' );
				break;
			case 'atriuum':
			case 'cloudlibrary':
			case 'dbtextworks':
			case 'insignia':
			case 'overdrive':
			case 'sirsi_horizon':
			case 'spydus':
			case 'surpass':
				esc_html_e( 'Enter ISBNs.', 'library-bookshelves' );
				break;
			default:
				esc_html_e( 'Enter ISBNs, UPCs, or item record numbers depending on which your catalog supports.', 'library-bookshelves' );
				break;
		}

		echo '</p>';

		// Show notices based on selected CDN.
		switch ( $cat_cdn ) {
			case 'amazon':
				echo '<p>' . esc_html_e( "Amazon's image CDN requires 10-digit ISBNs.", 'library-bookshelves' ) . '</p>';
				break;
			case 'chilifresh':
				echo '<p>' . esc_html_e( "ChiliFresh users need to add their website domain to 'Covered hosts' in the ChiliFresh Admin Panel for images to display.", 'library-bookshelves' ) . '</p>';
				break;
		}

		// Item ID list input.
		$isbn_meta = get_post_meta( $post_id, 'isbn', true );
		$alt_text_meta = get_post_meta( $post_id, 'alt', true );

		if ( ! empty( $isbn_meta ) ) {
			$num_isbn = count( $isbn_meta );
		}

		// Set textarea height in rows.
		( isset( $num_isbn ) ? $rows = " rows='" . ( $num_isbn + 1 ) . "'" : $rows = '' );

		// Write out the textarea fields, adjusting height based on number of saved item IDs.
		?>
		<div id="list-input" style="display: <?php echo ( 'true' === $list_input ? "block" : "none" ); ?>">
			<div id="isbnlist">
				<p>
				<?php esc_html_e( 'Enter items one per line or as a comma-, space-, or tab-delimited list.', 'library-bookshelves' ); ?>
				</p>

				<div class="item-inputs">
					<span><?php esc_html_e( 'Item Numbers', 'library-bookshelves' ); ?></span>
					<br>
					<textarea name="isbn" id="isbn-textarea" class="item-input" <?php echo $rows . ">\n";

			if ( ! empty( $isbn_meta ) ) {
				foreach ( $isbn_meta as $isbn ) {
					echo esc_html( $isbn ) . "\n";
				}
			}
			?></textarea>
				</div>
				<div class="item-inputs">
					<span><?php esc_html_e( 'Image alt text', 'library-bookshelves' ); ?></span>
					<br>
					<textarea name="alt" id="alt-textarea" class="item-input" cols="50" <?php echo $rows . ">\n";

			if ( ! empty( $alt_text_meta ) ) {
				foreach ( $alt_text_meta as $alt ) {
					echo esc_html( $alt ) . "\n";
				}
			}
			?>
					</textarea>
				</div>
			</div>
		</div>

		<?php
		//Web service input.
		$wsapi        = esc_html( get_post_meta( $post_id, 'wsapi', true ) );
		$ws_key       = esc_html( get_post_meta( $post_id, 'ws-key', true ) );
		$ws_secret    = esc_html( get_post_meta( $post_id, 'ws-secret', true ) );
		$ws_token_url = esc_html( get_post_meta( $post_id, 'ws-token-url', true ) );
		$ws_request   = esc_html( get_post_meta( $post_id, 'ws-request', true ) );
		$ws_json      = esc_html( get_post_meta( $post_id, 'ws-json', true ) );
		$item_id_type = esc_html( get_post_meta( $post_id, 'item_id_type', true ) );
		$schedule     = esc_html( get_post_meta( $post_id, 'schedule', true ) );
		?>

		<div id="api-input" style="display: <?php echo ( 'false' === $list_input ? "block" : "none" ); ?>">
			<p>
			<?php esc_html_e( 'To manually edit the items returned from a web service select "Input items as a list" above after populating the Bookshelf.', 'library-bookshelves' ); ?>
			</p>
			<hr>
			<p class="lberr">
			<?php
			$err_msg = get_post_meta( $post_id, 'error_message', true );
			if( ! empty( $err_msg) ) {
				esc_html_e( $err_msg, 'library-bookshelves' );
			}
			?>
			</p>
			<label for="wsapi">
			<?php 
				echo esc_html_x(
					'Service: ',
					'API or web service',
					'library-bookshelves'
				);
			?>
			</label>
			<select id="wsapi" name="wsapi" onchange="service(this.value)">
				<option value="aspen-api" <?php selected( $wsapi, 'aspen-api' ); ?> >Aspen API</option>
				<option value="cops-api" <?php selected( $wsapi, 'cops-api' ); ?> >COPS API</option>
				<option value="eg-supercat" <?php selected( $wsapi, 'eg-supercat' ); ?> >Evergreen SuperCat</option>
				<option value="json" <?php selected( $wsapi, 'json' ); ?> >JSON Data</option>
				<option value="koha-rws" <?php selected( $wsapi, 'koha-rws' ); ?> >Koha Reports Web Service</option>
				<option value="koha-rss" <?php selected( $wsapi, 'koha-rss' ); ?> >Koha RSS Feed</option>
				<option value="nytbooks" <?php selected( $wsapi, 'nytbooks' ); ?> >New York Times Books API</option>
				<option value="openlibrary" <?php selected( $wsapi, 'openlibrary' ); ?> >OpenLibrary API</option>
				<option value="pika-api" <?php selected( $wsapi, 'pika-api' ); ?> >Pika API</option>
				<option value="sierra-api" <?php selected( $wsapi, 'sierra-api' ); ?> >Sierra API</option>
				<option value="sirsi-rss" <?php selected( $wsapi, 'sirsi-rss' ); ?> >SirsiDynix RSS Feed</option>
				<option value="symws" <?php selected( $wsapi, 'symws' ); ?> >SirsiDynix Symphony Web Service</option>
				<option value="tlcls2pac" <?php selected( $wsapi, 'tlcls2pac' ); ?> >TLC LS2 PAC API</option>
			</select>
			<p id="serviceNotice"></p>
			<div id="ws-key">
				<label for="ws-key">
				<?php 
					echo esc_html_x(
						'Client Key: ',
						'API client key input label',
						'library-bookshelves'
					);
				?>
				</label>
				<div class="inputdiv">
					<input id="ws-key" name="ws-key" type="text" size="75" value="<?php echo $ws_key; ?>">
				</div>
				<br>
			</div>
			<div id="ws-secret">
				<label for="ws-secret">
				<?php
					echo esc_html_x(
						'Client Secret: ',
						'API client secret input label',
						'library-bookshelves'
					);
				?>
				</label>
				<div class="inputdiv">
					<input id="ws-secret" name="ws-secret" type="password" size="75" value="<?php echo $ws_secret; ?>">
				</div>
				<br>
			</div>
			<div id="ws-token">
				<label for="ws-token-url">
				<?php
					echo esc_html_x(
						'Token URL: ',
						'OAuth token URL input label',
						'library-bookshelves'
					);
				?>
				</label>
				<div class="inputdiv">
					<input id="ws-token-url" name="ws-token-url" type="url" size="75" value="<?php echo $ws_token_url; ?>">
				</div>
				<br>
			</div>
			<div id="ws-div">
				<label for="ws-request">
				<?php
					echo esc_html_x(
						'Request URL: ',
						'API request URL input label',
						'library-bookshelves'
					);
				?>
				</label>
				<div class="inputdiv">
					<input id="ws-request" name="ws-request" type="url" size="75" value="<?php echo $ws_request; ?>">
				</div>
				<br>
			</div>
			<div id="json-div">
				<label for="ws-json">
				<?php
					echo esc_html_x(
						'JSON Query: ',
						'JSON query input label',
						'library-bookshelves'
					);
				?>
				</label>
				<div class="inputdiv">
					<textarea id="ws-json" name="ws-json" placeholder="Enter a query in JSON format..."><?php echo $ws_json; ?></textarea>
				</div>
				<br>
			</div>
			<div id="item-id-div">
				<label for="item-id-type">
				<?php
					echo esc_html_x(
						'Item ID Type: ',
						'item identifier type input label',
						'library-bookshelves'
					);
				?>
				</label>
				<div class="inputdiv">
				<select id="item-id-type" name="item-id-type">
					<option value="isbn" <?php selected( $item_id_type, 'isbn' ); ?> >ISBN</option>
					<option value="upc" <?php selected( $item_id_type, 'upc' ); ?> >UPC</option>
				</select>
				</div>
				<br>
			</div>
			<div id="scheduler-div">
				<label for="schedule">
				<?php
					echo esc_html_x(
						'Schedule: ',
						'schedule cron job input label',
						'library-bookshelves'
					);
				?>
				</label>
				<div class="inputdiv">
					<select id="schedule" name="schedule">
						<option value="none" <?php selected( $schedule, 'none' ); ?> >None</option>
						<?php
						foreach ( wp_get_schedules() as $interval_name => $interval ) {
							echo '<option value=' . $interval_name . ' ' . selected( $schedule, $interval_name ) . '>' . $interval['display'] . '</option>\n';
						};
						?>
					</select>
					<span>
					<?php
					echo esc_html_x(
						'Next run: ',
						'next scheduled cron job',
						'library-bookshelves'
					);
					?>
					</span>
					<?php
					$id = strval( $post_id );
					$hook = 'update_bookshelf';
					$args = array( strval( $id ) );
					$timestamp = wp_next_scheduled( $hook, $args );

					if ( $timestamp ) {
						// Get date/time formats from WP options.
						$format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );

						// Get WP timezone. Try new WP function first.
						if ( function_exists( 'wp_timezone_string' ) ) {
							$tz = wp_timezone_string();
						} else {
							$tz = get_option( 'timezone_string' );

							if ( ! $tz ) {
								$offset  = (float) get_option( 'gmt_offset' );
								$hours   = (int) $offset;
								$minutes = ( $offset - $hours );
								$sign      = ( $offset < 0 ) ? '-' : '+';
								$abs_hour  = abs( $hours );
								$abs_mins  = abs( $minutes * 60 );
								$tz = sprintf( '%s%02d:%02d', $sign, $abs_hour, $abs_mins );
							}
						}

						$offset = ( new DateTime( 'now', new DateTimeZone( $tz ) ) )->format( 'Z' );

						// Localize date/time output.
						$next_run = date_i18n( $format, $timestamp + $offset );
						echo $next_run;	
					} else {
						echo esc_html_e( 'none', 'library-bookshelves' );
					}
					?>
					</span>
				</div>
				<br>
			</div>
		</div>
		<script>
		wsapi = jQuery( "#wsapi" ).val();
		service( wsapi );

		function service( value ) {
			switch ( value ) {
				case 'cops-api':
					<?php $notice = esc_html__("Enter a URL in the form: {COPS URL}/getJSON.php?page=9&db=1&scope=book. Search your catalog for the books you want to display, copy the URL, paste it below, then change 'index.php' to 'getJSON.php.'", 'library-bookshelves' ); ?>
					jQuery( '#serviceNotice' ).html("<?php echo $notice; ?>");
					jQuery( '#ws-key' ).hide();
					jQuery( '#ws-secret' ).hide();
					jQuery( '#ws-token' ).hide();
					jQuery( '#ws-div' ).show();
					jQuery( '#json-div' ).hide();
					jQuery( '#item-id-div' ).hide();
					jQuery( '#scheduler-div' ).show();
					break;
				case 'eg-supercat':
					<?php $notice = esc_html__("Enter a URL in the form: http://{CATALOG DOMAIN}/opac/extras/browse/mods/item-age/{ORG UNIT}/1/10.", 'library-bookshelves' ) . "<br>";
					$notice .= esc_html__("Use the 'mods' or 'mods3' feed type.", 'library-bookshelves' ); ?>
					jQuery( '#serviceNotice' ).html("<?php echo $notice; ?>");
					jQuery( '#ws-key' ).hide();
					jQuery( '#ws-secret' ).hide();
					jQuery( '#ws-token' ).hide();
					jQuery( '#ws-div' ).show();
					jQuery( '#json-div' ).hide();
					jQuery( '#item-id-div' ).hide();
					jQuery( '#scheduler-div' ).show();
					break;
				case 'json':
					<?php $notice = esc_html__( "Enter the URL of a JSON file. The file must be formatted with the item identifier in the 1st index. Title and author fields are optional, but, if included, must be the 2nd and 3rd indices, respectively. Duplicate items are removed.", 'library-bookshelves' ) . "<br>";
					$notice .= esc_html__( 'Example JSON: [[\"ISBN\",\"TITLE\",\"AUTHOR\"],[\"UPC\",\"TITLE\",\"AUTHOR\"]]', 'library-bookshelves' ); ?>
					jQuery( '#serviceNotice' ).html("<?php echo $notice; ?>");
					jQuery( '#ws-key' ).hide();
					jQuery( '#ws-secret' ).hide();
					jQuery( '#ws-token' ).hide();
					jQuery( '#ws-div' ).show();
					jQuery( '#json-div' ).hide();
					jQuery( '#item-id-div' ).hide();
					jQuery( '#scheduler-div' ).show();
					break;
				case 'koha-rws':
					<?php $notice = esc_html__("Create a public report in Koha with items you wish to add to this Bookshelf. The SQL query used to generate the report must return the ISBN in the 1st index of the JSON data. Title and author fields are optional, but, if included, must be the 2nd and 3rd indices, respectively. Duplicate items are removed..", 'library-bookshelves' ) . "<br>";
					$notice .= esc_html__( 'Example JSON: [[\"ISBN\",\"TITLE\",\"AUTHOR\"],[\"UPC\",\"TITLE\",\"AUTHOR\"]]', 'library-bookshelves' ); ?>
					jQuery( '#serviceNotice' ).html("<?php echo $notice; ?>");
					jQuery( '#ws-key' ).hide();
					jQuery( '#ws-secret' ).hide();
					jQuery( '#ws-token' ).hide();
					jQuery( '#ws-div' ).show();
					jQuery( '#json-div' ).hide();
					jQuery( '#item-id-div' ).hide();
					jQuery( '#scheduler-div' ).show();
					break;
				case 'koha-rss':
				case 'sirsi-rss':
					<?php $notice = esc_html__("Enter an RSS feed URL from an OPAC search results page. If no items appear in the Bookshelf it's likely your server did not respond within the 10-second timeout threshold. If this occurs try again later, try a simpler query, or one which yields fewer results.", 'library-bookshelves' ); ?>
					jQuery( '#serviceNotice' ).html("<?php echo $notice; ?>");
					jQuery( '#ws-key' ).hide();
					jQuery( '#ws-secret' ).hide();
					jQuery( '#ws-token' ).hide();
					jQuery( '#ws-div' ).show();
					jQuery( '#json-div' ).hide();
					jQuery( '#item-id-div' ).hide();
					jQuery( '#scheduler-div' ).show();
					break;
				case 'nytbooks':
					<?php $notice = esc_html__("Enter your NYT Books API key and your desired list URL. e.g. https://api.nytimes.com/svc/books/v3/lists/current/combined-print-and-e-book-fiction.json.", "library-bookshelves" ); ?>
					jQuery( '#serviceNotice' ).html("<?php echo $notice; ?>");
					jQuery( '#ws-key' ).show();
					jQuery( '#ws-secret' ).hide();
					jQuery( '#ws-token' ).hide();
					jQuery( '#ws-div' ).show();
					jQuery( '#json-div' ).hide();
					jQuery( '#item-id-div' ).hide();
					jQuery( '#scheduler-div' ).show();
					break;
				case 'openlibrary':
					<?php $notice = esc_html__( "Enter a list JSON URL.", 'library-bookshelves' ) . "<br>";
					$notice .= esc_html__( "Subject list example: https://openlibrary.org/subjects/love.json.", 'library-bookshelves' ) . "<br>";$notice .= esc_html__( "User-created list example: https://openlibrary.org/people/george08/lists/OL97L/seeds.json.", 'library-bookshelves' ) . "<br>";
					$notice .= esc_html__( "Search example: https://openlibrary.org/search.json?author=steven%20erikson", 'library-bookshelves' ); ?>
					jQuery( '#serviceNotice' ).html("<?php echo $notice; ?>");
					jQuery( '#ws-key' ).hide();
					jQuery( '#ws-secret' ).hide();
					jQuery( '#ws-token' ).hide();
					jQuery( '#ws-div' ).show();
					jQuery( '#json-div' ).hide();
					jQuery( '#item-id-div' ).hide();
					jQuery( '#scheduler-div' ).show();
					break;
				case 'aspen-api':
				case 'pika-api':
					<?php $notice = esc_html__( "Enter a Search or List API URL. Some examples:", 'library-bookshelves' ) . "<br>";
					$notice .= esc_html__( "{catalog URL}/API/ListAPI?method=getListTitles&id={list ID}", 'library-bookshelves' ) . "<br>";
					$notice .= esc_html__( "{catalog URL}/API/SearchAPI?method=search&lookfor={keyword}&pageSize={# of results}", 'library-bookshelves' ) . "<br>";
					$notice .= esc_html__( "{catalog URL}/API/SearchAPI?method=search&filter[]=literary_form:\"Fiction\"&filter[]=publishDateSort:[2022 TO *]&sort=days_since_added+asc", 'library-bookshelves' ) . "<br>";
					$notice .= esc_html__( "You can test the URL in your browser to make sure it returns the expected data.", 'library-bookshelves' ); ?>
					jQuery( '#serviceNotice' ).html("<?php echo $notice; ?>");
					jQuery( '#ws-key' ).hide();
					jQuery( '#ws-secret' ).hide();
					jQuery( '#ws-token' ).hide();
					jQuery( '#ws-div' ).show();
					jQuery( '#json-div' ).hide();
					jQuery( '#item-id-div' ).hide();
					jQuery( '#scheduler-div' ).show();
					break;
				case 'sierra-api':
					<?php $notice = esc_html__( "The Request URL can point to the Bibs or Items API. Duplicate results are removed automatically.", 'library-bookshelves' ); ?>
					jQuery( '#serviceNotice' ).html("<?php echo $notice; ?>");
					jQuery( '#ws-key' ).show();
					jQuery( '#ws-secret' ).show();
					jQuery( '#ws-token' ).show();
					jQuery( '#ws-div' ).show();
					jQuery( '#json-div' ).show();
					jQuery( '#item-id-div' ).hide();
					jQuery( '#scheduler-div' ).show();
					break;
				case 'symws':
					<?php $notice = esc_html__( "Use the JSON output option in your request URL.", 'library-bookshelves' ); ?>
					jQuery( '#serviceNotice' ).html("<?php echo $notice; ?>");
					jQuery( '#ws-key' ).hide();
					jQuery( '#ws-secret' ).hide();
					jQuery( '#ws-token' ).hide();
					jQuery( '#ws-div' ).show();
					jQuery( '#json-div' ).hide();
					jQuery( '#item-id-div' ).show();
					jQuery( '#scheduler-div' ).show();
					break;
				case 'tlcls2pac':
					jQuery( '#serviceNotice' ).html("");
					jQuery( '#ws-key' ).hide();
					jQuery( '#ws-secret' ).hide();
					jQuery( '#ws-token' ).hide();
					jQuery( '#ws-div' ).show();
					jQuery( '#json-div' ).hide();
					jQuery( '#item-id-div' ).hide();
					jQuery( '#scheduler-div' ).show();
					break;
			}
		}
		</script>
		</div>
		<?php
	}

/**
 * Assembles Bookshelf post shortcode metabox
 *
 * Writes the Bookshelf shortcode in the box.
 *
 * @since 1.0
 */
	public function add_shortcode_metabox() {
		echo '<p>[bookshelf id="' . get_the_ID() . '"]</p>';
	}

/**
 * Assembles Bookshelf post preview metabox
 *
 * Previews the Bookshelf slider once the post has been saved.
 *
 * @since 1.0
 */	public function add_preview_metabox() {
		$post_id = get_the_ID();
		$isbn_meta = get_post_meta( $post_id, 'isbn', true );

		if ( ! empty( $isbn_meta ) ) {
			$html = lbs_shelveBooks( $post_id );
			echo $html;
		} else {
			echo '<p>' . esc_html__( "Enter item identifiers and save the post to see a preview of the bookshelf.", 'library-bookshelves' ) . '</p>';
		}
	}

/**
 * Assembles Bookshelf post Slick settings metabox
 *
 * Writes post-specific Slick settings.
 *
 * @since 2.0
 */	public function add_settings_metabox() {
		$post_id = get_the_ID();
		$settings_meta = get_post_meta( $post_id, 'settings', true );
		$override = get_post_meta( $post_id, 'slick_override', true );
		?>
		<input class="slick-off" type="radio" name="slick_override" value="false" checked <?php checked( $override, 'false' ); ?> >
		<?php esc_html_e( "Use plugin global settings", 'library-bookshelves' ); ?><br>
		<input class="slick-on" type="radio" name="slick_override" value="true" <?php checked( $override, 'true' ); ?> >
		<?php esc_html_e("Use custom settings", 'library-bookshelves' ); ?><br>
		<div id="slick-custom" style="display: <?php echo ( 'true' === $override ? "block" : "none" ); ?>">
		<table class="form-table">
		<tbody>
		<?php
		// Get the settings definitions.
		$settings = lbs_settings_obj();
		// Extract the Slick settings.
		$slick_settings = $settings['slick'];
		// Write out the Slick settings fields.
		foreach ( $slick_settings['fields'] as $setting ) {
			lbs_display_setting_field( $setting, $post_id );
		}
		?>
		</tbody>
		</table>
		</div>		
		<?php
	}

/**
 * Assembles Bookshelf post ebook metabox
 *
 * Writes the Bookshelf ebook settings
 *
 * @since 4.18
 */	public function add_ebook_metabox() {
		$post_id = get_the_ID();
		$settings_meta = get_post_meta( $post_id, 'settings', true );
		$ebooks = get_post_meta( $post_id, 'ebooks', true );
		?>
		<label for="ebooks"><?php esc_html_e("Link items in this Bookshelf directly to an ebook catalog.", 'library-bookshelves' ); ?></label>
		<br><br>
		<input type="radio" name="ebooks" id="cloudlibrary" value="cloudlibrary" <?php checked( $ebooks, 'cloudlibrary' ); ?> >
		<label for="cloudlibrary">cloudLibrary</label>
		<br>
		<input type="radio" name="ebooks" id="overdrive" value="overdrive" <?php checked( $ebooks, 'overdrive' ); ?> >
		<label for="overdrive">Overdrive</label>
		<br>
		<input type="radio" name="ebooks" id="hoopla" value="hoopla" <?php checked( $ebooks, 'hoopla' ); ?> >
		<label for="hoopla">Hoopla</label>
		<br>
		<input type="radio" name="ebooks" id="none" value="none" <?php checked( $ebooks, 'none' ); checked( $ebooks, '' ); ?> >
		<label for="none"><?php esc_html_e("None", 'library-bookshelves' ); ?></label>
		<?php
		if ( ! get_option( 'lbs_cat_overdrive' ) && $ebooks === 'overdrive' && get_option( 'lbs_cat_System' ) !== 'overdrive') {
			echo "<br><p style='color: red'>" . esc_html__("Your Overdrive catalog URL is not set. Enter it in the plugin settings.", 'library-bookshelves' ) . "</p>";
		}

		if ( ! get_option( 'lbs_cat_cloudlibrary' ) && $ebooks === 'cloudlibrary' && get_option( 'lbs_cat_System' ) !== 'cloudlibrary') {
			echo "<br><p style='color: red'>" . esc_html__("Your cloudLibrary catalog URL is not set. Enter it in the plugin settings.", 'library-bookshelves' ) . "</p>";
		}
	}

	public static function instance() {
		self::$instance = new self() && null === self::$instance;
		return self::$instance;
	}
}

$bookshelves = Bookshelves::instance();
