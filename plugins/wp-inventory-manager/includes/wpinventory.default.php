<?php

Class WPIMDefaultItems extends WPIMCore {
	/**
	 * @var WPIMDB
	 */
	private $db;

	/**
	 * @var wpdb
	 */
	private $wpdb;

	private $default_path;

	private $category_map = [
		1 => 1,
		2 => 2,
		3 => 3
	];

	private $item_map = [
		1 => 1,
		2 => 2,
		3 => 3,
		4 => 4
	];

	private $all_media = [];

	private $user_id = NULL;

	public function __construct() {
		add_filter( 'wpim_do_fresh_install', function () {
			return FALSE;
		} );

		parent::__construct();

		self::$path = self::get_plugin_dir();

		$this->db = WPIMDB::getInstance();

		global $wpdb;
		$this->wpdb = $wpdb;

		$this->user_id = get_current_user_id();
	}

	public function install() {
		$this->default_path = trailingslashit( self::$path ) . 'default_assets/';

		$this->add_categories();
		$this->add_items();
		$this->add_images();
		$this->add_media();
		$this->save_state();
	}

	private function add_categories() {
		$categories = [
			[
				'category_name'        => 'Laptop',
				'category_description' => '',
				'category_slug'        => 'laptop'
			],
			[
				'category_name'        => 'Tablet',
				'category_description' => '',
				'category_slug'        => 'tablet'
			]
		];

		foreach ( $categories AS $index => $category ) {
			$this->wpdb->insert( $this->db->category_table, $category );
			$this->category_map[ ( $index + 1 ) ] = $this->wpdb->insert_id;
		}
	}

	private function add_items() {
		$items = [
			[
				'inventory_number'            => '1',
				'inventory_name'              => 'Apple Macbook Pro 13"',
				'inventory_description'       => '<p class="a-spacing-mini a-color-secondary">MacBook Pro elevates the notebook to a whole new level of performance and portability. Wherever your ideas take you, you’ll get there faster than ever with cutting-edge graphics, high-performance processors, whip-smart storage, and more. With seventh-generation Intel Core processors, MacBook Pro delivers amazing performance so you can move fast — even when powering through pro-level processing jobs like rendering 3D models and encoding video. At the same time, it can conserve energy when taking on lighter tasks, like browsing the web and checking email. And 10-bit HEVC hardware acceleration comes standard on MacBook Pro, which will let you take even more advantage of the boosts in 4K video compression and streaming performance coming in macOS High Sierra.</p><p>https://www.youtube.com/watch?v=IXBD_Ex4h8Y</p>',
				'inventory_size'              => '13"',
				'inventory_manufacturer'      => 'Apple',
				'inventory_make'              => 'Laptop',
				'inventory_model'             => 'Macbook Pro (early 2013)',
				'inventory_year'              => '2013',
				'inventory_serial'            => '123456789',
				'inventory_fob'               => 'Cupertino, CA',
				'inventory_quantity'          => 15,
				'inventory_quantity_reserved' => 0,
				'inventory_price'             => 599.99,
				'inventory_slug'              => 'macbook-pro-13',
				'inventory_sort_order'        => 0,
				'category_id'                 => $this->category_map[1],
				'user_id'                     => $this->user_id,
				'inventory_date_added'        => current_time( 'mysql' ),
				'inventory_date_updated'      => current_time( 'mysql' ),
				'inventory_status'            => 2
			],
			[
				'inventory_number'            => '2',
				'inventory_name'              => 'Hewlett Packard 15.6"',
				'inventory_description'       => '<ul class="a-unordered-list a-vertical a-spacing-none"><li><span class="a-list-item">Intel Pentium processor N3710 Quad-Core processor 1.6 GHz with 2M Cache up to 2.56 GHz - four-way processing performance for HD-quality computing, 8GB DDR3L 1600 MHz, 500GB 5400 RPM HDD,</span></li> 	<li><span class="a-list-item">15.6 in HD LED Display (1366 x 768), Intel HD graphics with shared graphics memory, SuperMulti DVD/CD burner (including DVD+R/RW, DVD-R/RW, CD-R/RW, DVD-RAM and double-layer DVD)</span></li><li><span class="a-list-item">Built-in high-speed wireless LAN, Integrated webcam, SD card reader, Built-in stereo speakers with SonicMaster technology provides a crystal-clear sound experience</span></li> 	<li><span class="a-list-item">Full-sized island style keyboard with numeric keypad, 1 USB 3.0 • 2 USB 2.0 • HDMI • Headphone output/Microphone input combo • LAN (10/100),</span></li> 	<li><span class="a-list-item">Windows 10 Home 64-bit, Silver Color, Silver, Hairline finish, Up to 8 hours and 45 minutes of battery life</span></li></ul><p>https://www.youtube.com/watch?v=8FqKBmwfgTs</p>',
				'inventory_size'              => '15.6"',
				'inventory_manufacturer'      => 'Hewlett Packard',
				'inventory_make'              => 'Laptop',
				'inventory_model'             => 'Intel Pentium Quad Core',
				'inventory_year'              => '2015',
				'inventory_serial'            => '12345678234',
				'inventory_fob'               => 'Fort Collins, CO',
				'inventory_quantity'          => 5,
				'inventory_quantity_reserved' => 0,
				'inventory_price'             => 399.99,
				'inventory_slug'              => 'hewlett-packard-15',
				'inventory_sort_order'        => 1,
				'category_id'                 => $this->category_map[1],
				'user_id'                     => $this->user_id,
				'inventory_date_added'        => current_time( 'mysql' ),
				'inventory_date_updated'      => current_time( 'mysql' ),
				'inventory_status'            => 2
			],
			[
				'inventory_number'            => '3',
				'inventory_name'              => 'iPad 32GB w/Retina',
				'inventory_description'       => '<ul class="a-unordered-list a-vertical a-spacing-none"> 	<li><span class="a-list-item">9.7-inch Retina display, wide color and true tone</span></li>	<li><span class="a-list-item">A9 third-generation chip with 64-bit architecture</span></li>	<li><span class="a-list-item">M9 motion coprocessor</span></li>	<li><span class="a-list-item">1.2MP FaceTime HD camera</span></li> 	<li><span class="a-list-item">8MP iSight camera</span></li> 	<li><span class="a-list-item">Touch ID</span></li> 	<li></li> 	<li><span class="a-list-item">15.6 in HD LED Display (1366 x 768), Intel HD graphics with shared graphics memory, SuperMulti DVD/CD burner (including DVD+R/RW, DVD-R/RW, CD-R/RW, DVD-RAM and double-layer DVD)</span></li> 	<li><span class="a-list-item">Built-in high-speed wireless LAN, Integrated webcam, SD card reader, Built-in stereo speakers with SonicMaster technology provides a crystal-clear sound experience</span></li> 	<li><span class="a-list-item">Full-sized island style keyboard with numeric keypad, 1 USB 3.0 • 2 USB 2.0 • HDMI • Headphone output/Microphone input combo • LAN (10/100),</span></li> 	<li><span class="a-list-item">Windows 10 Home 64-bit, Silver Color, Silver, Hairline finish, Up to 8 hours and 45 minutes of battery life</span></li></ul><p>https://www.youtube.com/watch?v=Zvz43NgAQjY</p>',
				'inventory_size'              => '9.7"',
				'inventory_manufacturer'      => 'Apple',
				'inventory_make'              => 'Tablet',
				'inventory_model'             => '32GB',
				'inventory_year'              => '2017',
				'inventory_serial'            => 'a1234ef66',
				'inventory_fob'               => 'Cupertino, CA',
				'inventory_quantity'          => 25,
				'inventory_quantity_reserved' => 0,
				'inventory_price'             => 499.99,
				'inventory_slug'              => 'apple-ipad',
				'inventory_sort_order'        => 2,
				'category_id'                 => $this->category_map[2],
				'user_id'                     => $this->user_id,
				'inventory_date_added'        => current_time( 'mysql' ),
				'inventory_date_updated'      => current_time( 'mysql' ),
				'inventory_status'            => 2
			],
			[
				'inventory_number'            => '4',
				'inventory_name'              => 'Samsung Galaxy Tab E Lite',
				'inventory_description'       => '<ul class="a-unordered-list a-vertical a-spacing-none">	<li><span class="a-list-item">9.7-inch Retina display, wide color and true tone</span></li> 	<li><span class="a-list-item">A9 third-generation chip with 64-bit architecture</span></li> 	<li><span class="a-list-item">M9 motion coprocessor</span></li> 	<li><span class="a-list-item">1.2MP FaceTime HD camera</span></li> 	<li><span class="a-list-item">8MP iSight camera</span></li> 	<li><span class="a-list-item">Touch ID</span></li> 	<li></li> 	<li><span class="a-list-item">15.6 in HD LED Display (1366 x 768), Intel HD graphics with shared graphics memory, SuperMulti DVD/CD burner (including DVD+R/RW, DVD-R/RW, CD-R/RW, DVD-RAM and double-layer DVD)</span></li> 	<li><span class="a-list-item">Built-in high-speed wireless LAN, Integrated webcam, SD card reader, Built-in stereo speakers with SonicMaster technology provides a crystal-clear sound experience</span></li> 	<li><span class="a-list-item">Full-sized island style keyboard with numeric keypad, 1 USB 3.0 • 2 USB 2.0 • HDMI • Headphone output/Microphone input combo • LAN (10/100),</span></li> 	<li><span class="a-list-item">Windows 10 Home 64-bit, Silver Color, Silver, Hairline finish, Up to 8 hours and 45 minutes of battery life</span></li></ul><p>https://www.youtube.com/watch?v=OYjrFt4drFY</p>',
				'inventory_size'              => '7"',
				'inventory_manufacturer'      => 'Samsung',
				'inventory_make'              => 'Tablet',
				'inventory_model'             => 'E Lite',
				'inventory_year'              => '2016',
				'inventory_serial'            => '12349999x',
				'inventory_fob'               => 'Plano, TX',
				'inventory_quantity'          => 50,
				'inventory_quantity_reserved' => 0,
				'inventory_price'             => 499.99,
				'inventory_slug'              => 'samsung-galaxy-tablet',
				'inventory_sort_order'        => 3,
				'category_id'                 => $this->category_map[2],
				'user_id'                     => $this->user_id,
				'inventory_date_added'        => current_time( 'mysql' ),
				'inventory_date_updated'      => current_time( 'mysql' ),
				'inventory_status'            => 2
			]
		];

		foreach ( $items AS $index => $item ) {
			$this->wpdb->insert( $this->db->inventory_table, $item );
			$this->item_map[ ( $index + 1 ) ] = $this->wpdb->insert_id;
		}
	}

	private function add_images() {
		$images = [
			[
				'item_id' => $this->item_map[1],
				'image'   => 'macbook.jpg'
			],
			[
				'item_id' => $this->item_map[1],
				'image'   => 'macbook_1.jpg'
			],
			[
				'item_id' => $this->item_map[1],
				'image'   => 'macbook_2.jpg'
			],
			[
				'item_id' => $this->item_map[1],
				'image'   => 'macbook_3.jpg'
			],
			[
				'item_id' => $this->item_map[1],
				'image'   => 'macbook_4.jpg'
			],
			[
				'item_id' => $this->item_map[2],
				'image'   => 'hp-1.jpg'
			],
			[
				'item_id' => $this->item_map[2],
				'image'   => 'hp_1.jpg'
			],
			[
				'item_id' => $this->item_map[2],
				'image'   => 'hp_2.jpg'
			],
			[
				'item_id' => $this->item_map[2],
				'image'   => 'hp_3.jpg'
			],
			[
				'item_id' => $this->item_map[2],
				'image'   => 'hp_4.jpg'
			],
			[
				'item_id' => $this->item_map[3],
				'image'   => 'tablet_1.jpg'
			],
			[
				'item_id' => $this->item_map[3],
				'image'   => 'tablet_2.jpg'
			],
			[
				'item_id' => $this->item_map[3],
				'image'   => 'tablet_3.jpg'
			],
			[
				'item_id' => $this->item_map[4],
				'image'   => 'samsung_1.jpg'
			],
			[
				'item_id' => $this->item_map[4],
				'image'   => 'samsung_2.jpg'
			],
			[
				'item_id' => $this->item_map[4],
				'image'   => 'samsung_3.jpg'
			],
			[
				'item_id' => $this->item_map[4],
				'image'   => 'samsung_4.jpg'
			],
			[
				'item_id' => $this->item_map[4],
				'image'   => 'samsung_5.jpg'
			]
		];

		$sort_order = 0;
		foreach ( $images AS $index => $image ) {
			$post_id = $this->add_to_media( $image['image'] );

			if ( ! $post_id || is_wp_error( $post_id ) ) {
				var_dump( "UPLOAD ERROR", $index, $image );
				continue;
			}

			$full = wp_get_attachment_image_src( $post_id, 'full' );
			$full = $full[0];

			$thumbnail = wp_get_attachment_image_src( $post_id, 'thumbnail' );
			$thumbnail = ( ! empty( $thumbnail[0] ) ) ? $thumbnail[0] : $full;

			$medium = wp_get_attachment_image_src( $post_id, 'medium' );
			$medium = ( ! empty( $medium[0] ) ) ? $medium[0] : $full;

			$large = wp_get_attachment_image_src( $post_id, 'large' );
			$large = ( ! empty( $large[0] ) ) ? $large[0] : $full;

			$insert = [
				'inventory_id'     => $image['item_id'],
				'post_id'          => $post_id,
				'image'            => $full,
				'thumbnail'        => $thumbnail,
				'medium'           => $medium,
				'large'            => $large,
				'image_sort_order' => $sort_order ++
			];

			$this->wpdb->insert( $this->db->image_table, $insert );
			$this->all_media[] = $this->wpdb->insert_id;
		}
	}


	private function add_media() {
		$medias = [
			[
				'item_id' => $this->item_map[1],
				'name'    => 'Macbook User Manual',
				'media'   => 'macbook13_manual.pdf'
			],
			[
				'item_id' => $this->item_map[2],
				'name'    => 'HP User Guide',
				'media'   => 'hp_userguide.pdf'
			],
			[
				'item_id' => $this->item_map[3],
				'name'    => 'iPad User Manual',
				'media'   => 'ipad_userguide.pdf'
			],
			[
				'item_id' => $this->item_map[4],
				'name'    => 'Galaxy User Manual',
				'media'   => '432d34980ba620e46669f8403fe3b5a46b15f5f7.pdf'
			]
		];

		$sort_order = 0;
		foreach ( $medias AS $index => $media ) {
			$post_id = $this->add_to_media( $media['media'] );

			if ( ! $post_id || is_wp_error( $post_id ) ) {
				var_dump( "UPLOAD MEDIA ERROR", $index, $media );
				continue;
			}

			$url = wp_get_attachment_url( $post_id );

			$insert = [
				'inventory_id'     => $media['item_id'],
				'media_id'         => $post_id,
				'media_title'      => $media['name'],
				'media'            => $url,
				'media_sort_order' => $sort_order ++
			];

			$this->wpdb->insert( $this->db->media_table, $insert );
			$this->all_media[] = $this->wpdb->insert_id;
		}
	}

	private function save_state() {
		$state = [
			'categories' => array_values( $this->category_map ),
			'items'      => array_values( $this->item_map ),
			'media'      => array_values( $this->all_media )
		];

		update_option( 'wpim_default_data', $state );
	}

	public function delete_default_data() {
		$data = get_option( 'wpim_default_data' );

		if ( empty( $data ) || empty( $data['items'] ) ) {
			return;
		}

		foreach ( $data['items'] AS $inventory_id ) {
			$where = [
				'inventory_id' => $inventory_id
			];

			$this->wpdb->delete( $this->db->inventory_table, $where );
		}

		if ( ! empty( $data['media'] ) && empty( $data['images'] ) ) {
			$data['images'] = array_slice( $data['media'], 0, 18 );
			// keep only those IDs not put in the images array
			$data['media'] = array_diff( $data['media'], $data['images'] );
		}

		foreach ( $data['images'] AS $image_id ) {
			$image = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$this->db->image_table} WHERE image_id = %d", $image_id ) );
			$this->wpdb->delete( $this->db->image_table, [ 'image_id' => $image_id ] );

			if ( ! empty( $image->post_id ) ) {
				wp_delete_attachment( $image->post_id, TRUE );
			}
		}

		$dir = wp_upload_dir();

		foreach ( $data['media'] AS $media_id ) {
			$media = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$this->db->media_table} WHERE media_id = %d", $media_id ) );
			$this->wpdb->delete( $this->db->media_table, [ 'media_id' => $media_id ] );

			if ( ! empty( $media->media ) ) {

				$path = $media->media;

				if ( 0 === strpos( $path, $dir['baseurl'] . '/' ) ) {
					$path = substr( $path, strlen( $dir['baseurl'] . '/' ) );
				}

				$post_id = $this->wpdb->get_var( $this->wpdb->prepare( "SELECT post_id FROM {$this->wpdb->postmeta} WHERE meta_key='_wp_attached_file' AND meta_value=%s", $path ) );
				if ( ! empty( $post_id ) ) {
					wp_delete_attachment( $post_id, TRUE );
				}
			}
		}

		foreach ( $data['categories'] AS $category_id ) {
			$this->wpdb->delete( $this->db->category_table, [ 'category_id' => $category_id ] );
		}

		delete_option( 'wpim_default_data' );
	}

	private function add_to_media( $file ) {
		$filename = $file;
		// file is passed in as the single file name
		$file = $this->default_path . $file;

		$attachment_id = FALSE;

		$upload_file = wp_upload_bits( $filename, NULL, file_get_contents( $file ) );

		if ( ! $upload_file['error'] ) {
			$wp_filetype = wp_check_filetype( $filename, NULL );
			$attachment  = [
				'post_mime_type' => $wp_filetype['type'],
				'post_parent'    => 0,
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			];

			$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], 0 );

			if ( ! is_wp_error( $attachment_id ) ) {
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
				wp_update_attachment_metadata( $attachment_id, $attachment_data );
			}
		}

		return $attachment_id;
	}
}
