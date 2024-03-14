<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://logichunt.com
 * @since      1.0.0
 *
 * @package    Portfolio_Pro
 * @subpackage Portfolio_Pro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Portfolio_Pro
 * @subpackage Portfolio_Pro/admin
 * @author     LogicHunt <logichunt.info@gmail.com>
 */
class Portfolio_Pro_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	/**
	 * @var Portfolio_pro_Settings_API
	 */
	private $settings_api;

	/**
	 * The plugin plugin_base_file of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string plugin_base_file The plugin plugin_base_file of the plugin.
	 */
	protected $plugin_base_file;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->settings_api = new Portfolio_pro_Settings_API($plugin_name, $version);
		$this->plugin_base_file = plugin_basename(plugin_dir_path(__FILE__).'../' . $this->plugin_name . '.php');

	}

    /**
     * Ensure post thumbnail support is turned on.
     * Since 1.1.0
     */
    public function add_thumbnail_support() {
        if ( ! current_theme_supports( 'post-thumbnails' ) ) {
            add_theme_support( 'post-thumbnails' );
        }
        add_post_type_support( 'portfoliopro', 'thumbnail' );
    }


    /**
	 * Admin Initialization For Portfolio Pro
	 *
	 * Since 1.0.0
	 */

	public function admin_init_portfolio_pro(){

		// Portfolio Custom Column
		add_filter( 'manage_portfoliopro_posts_columns', array($this, 'portfoliopro_columns'), 10, 1);
		add_action('manage_portfoliopro_posts_custom_column', array($this, 'portfoliopro_column'), 10, 2);


	}


	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		$this->plugin_screen_hook_suffix  = add_submenu_page('edit.php?post_type=portfoliopro', __('Settings', $this->plugin_name), __('Portfolio Settings', $this->plugin_name ), 'manage_options', 'portfolioprosettings', array($this, 'display_plugin_admin_settings'));

	}


	/**
	 * Add support link to plugin description in /wp-admin/plugins.php
	 *
	 * @param  array  $plugin_meta
	 * @param  string $plugin_file
	 *
	 * @return array
	 */
	public function support_link($plugin_meta, $plugin_file) {

		if ($this->plugin_base_file == $plugin_file) {
			$plugin_meta[] = sprintf(
				'<a href="%s">%s</a>', 'http://logichunt.com/support', __('Support',  $this->plugin_name)
			);
		}

		return $plugin_meta;
	}


    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function plugin_listing_setting_link( $links ) {
        return array_merge( array(
            'settings' => '<a style="color:#00a500; font-weight: bold;" href="' . admin_url( 'edit.php?post_type=portfoliopro&page=portfolioprosettings' ) . '">' . esc_html__( 'Settings', 'portfolio-pro') . '</a>',
            'support' => '<a style="color:#ff4b39; font-weight: bold;" target="_blank" href="' .esc_url('https://logichunt.com/support/') . '" target="_blank">' . esc_html__( 'Get Support', 'logo-slider-wp') . '</a>',

        ), $links );

    }//end plugin_listing_setting_link





    /**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Portfolio_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Portfolio_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/portfoliopro-pro-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Portfolio_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Portfolio_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/portfoliopro-pro-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 *  Portfolio
	 *
	 * @param $column_name
	 * @param $post_id
	 */

	public function portfoliopro_column( $column_name, $post_id ) {

		$fieldValues = get_post_meta( $post_id, '_portfolioprometa', true );

		$project = isset( $fieldValues['project_name'] ) ? $fieldValues['project_name'] : '';
		$client  = isset( $fieldValues['client_name'] ) ? $fieldValues['client_name'] : '';

		if ($column_name == 'project_name') {
			echo $project;
		}

		if ($column_name == 'client_name') {
			echo $client;
		}

		if ($column_name == 'thumb') {
			if ('' != $image_url = $this->get_feature_image($post_id, 'teamthumbnail')) {

				echo '<a href="'. $image_url . '" class="editinline"><img style="width: 40px;height: 40px;" src="'. $image_url . '" /></a>';
			}
			else {
				echo '<a href="#" class="editinline"><img style="width: 40px; height: 40px;" src="" /></a>';
			}
		}
	}


	/**
	 * Portfolio Columns
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */

	public function portfoliopro_columns($columns ){

		unset($columns['date']);
		$columns['project_name']    = __('Project Name', $this->plugin_name);
		$columns['client_name']     = __('client Name', $this->plugin_name);
		$columns['thumb']           = __('Thumb', $this->plugin_name);
		return $columns;
	}





	/**
	 * Get Post thumbnail for custom column
	 *
	 * @param  $post_id
	 * @param string $size
	 *
	 * @return bool | image_url
	 *
	 */
	public function get_feature_image($post_id, $size = '') {

		if ($feature_image_id = get_post_thumbnail_id($post_id)) {

			$url = wp_get_attachment_image_src($feature_image_id, $size);

			return $url[0];
		}

		return false;
	}


	/**
	 * Add metabox for custom post type
	 *
	 * @since    1.0.0
	 */
	public function add_meta_boxes_metabox() {


		//portfoliopro meta box
		add_meta_box(
			'metabox_portfoliopro', __( 'Portfolio Fields', $this->plugin_name ), array(
			$this,
			'metabox_portfoliopro_display'
		), 'portfoliopro', 'normal', 'high'
		);

	}


	/**
	 * Register Custom Post Type For Portfolio Pro
	 *
	 * @since    1.0.0
	 */
	public function custom_post_type() {

		$labels_portfoliopro = array(
			'name'               => _x( 'Portfolios', 'Post Type General Name', $this->plugin_name ),
			'singular_name'      => _x( 'Portfolio', 'Post Type Singular Name', $this->plugin_name ),
			'menu_name'          => __( 'Portfolio', $this->plugin_name ),
			'parent_item_colon'  => __( 'Parent Item:', $this->plugin_name ),
			'all_items'          => __( 'All Item', $this->plugin_name ),
			'view_item'          => __( 'View Item', $this->plugin_name ),
			'add_new_item'       => __( 'Add New Item', $this->plugin_name ),
			'add_new'            => __( 'Add Item', $this->plugin_name ),
			'edit_item'          => __( 'Edit Item', $this->plugin_name ),
			'update_item'        => __( 'Update Item', $this->plugin_name ),
			'search_items'       => __( 'Search Item', $this->plugin_name ),
			'not_found'          => __( 'Not found', $this->plugin_name ),
			'not_found_in_trash' => __( 'Not found in Trash', $this->plugin_name ),
		);

		$args_portfoliopro   = array(
			'label'               => __( 'Portfolio', $this->plugin_name ),
			'description'         => __( 'Portfolio Display', $this->plugin_name ),
			'labels'              => $labels_portfoliopro,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( 'portfoliopro', $args_portfoliopro );

		// Register Taxonomy For Portfolio
		//Categories
		$portfoliopro_cat_args = array(
			'hierarchical'   => true,
			'label'          => 'Categories',
			'show_ui'        => true,
			'query_var'      => true,
			'rewrite'        => array('slug' => 'cat'),
			'singular_label' => 'Categories'
		);
		register_taxonomy('portfolioprocat', array('portfoliopro'), $portfoliopro_cat_args);

		//Tags
		$portfoliopro_tag_args = array(
			'hierarchical' => false,
			'label' => 'Tags',
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'tags'),
			'singular_label' => 'Tag'
		);
		register_taxonomy('portfolioprotag', array('portfoliopro'), $portfoliopro_tag_args);

	}




	/**
	 * Render Metabox under Portfolio
	 *
	 * portfoliopro meta field
	 *
	 * @param $post
	 *
	 * @since 1.0
	 *
	 */

	public function metabox_portfoliopro_display( $post ) {

		$fieldValues = get_post_meta( $post->ID, '_portfolioprometa', true );

		wp_nonce_field( 'metaboxportfoliopro', 'metaboxportfoliopro[nonce]' );

		echo '<div id="portfoliopro_metabox_wrapper">';

		$ext_url        = isset( $fieldValues['ext_url'] ) ? $fieldValues['ext_url'] : '';
		$project_name   = isset( $fieldValues['project_name'] ) ? $fieldValues['project_name'] : '';
		$client_name    = isset( $fieldValues['client_name'] ) ? $fieldValues['client_name'] : '';
		$custom_tag_one = isset( $fieldValues['custom_tag_one'] ) ? $fieldValues['custom_tag_one'] : '';
		$custom_tag_two = isset( $fieldValues['custom_tag_two'] ) ? $fieldValues['custom_tag_two'] : '';
		$custom_tag_three= isset( $fieldValues['custom_tag_three'] ) ? $fieldValues['custom_tag_three'] : '';
		?>


		<table class="form-table">
			<tbody>

			<?php do_action( 'portfoliopro_meta_fields_before_start', $fieldValues ); ?>

			<tr valign="top">
				<td><?php _e( 'Project Name', $this->plugin_name ) ?></td>
				<td>
					<input type="text" name="metaboxportfoliopro[project_name]" value='<?php echo $project_name; ?>'/>
					<p class="description"><?php _e( 'Portfolio Project Name', $this->plugin_name ); ?></p>
				</td>
			</tr>

			<tr valign="top">
				<td><?php _e( 'Client Name / Intro', $this->plugin_name ) ?></td>
				<td>
					<input type="text" name="metaboxportfoliopro[client_name]" value='<?php echo $client_name; ?>'/>
					<p class="description"><?php _e( 'Portfolio Client Name or Some Intro text', $this->plugin_name ); ?></p>
				</td>
			</tr>

			<tr valign="top">
				<td><?php _e( 'External Url', $this->plugin_name ) ?></td>
				<td>
					<input type="url" name="metaboxportfoliopro[ext_url]" value='<?php echo $ext_url; ?>'/>
					<p class="description"><?php _e( 'Portfolio External Url', $this->plugin_name ); ?></p>
				</td>
			</tr>


			<tr valign="top">
				<td><?php _e( 'First Custom Tag', $this->plugin_name ) ?></td>
				<td>
					<input type="text" name="metaboxportfoliopro[custom_tag_one]" value='<?php echo $custom_tag_one; ?>'/>
					<p class="description"><?php _e( 'Use as custom tag', $this->plugin_name ); ?></p>
				</td>
			</tr>

			<tr valign="top">
				<td><?php _e( 'Second Custom Tag', $this->plugin_name ) ?></td>
				<td>
					<input type="text" name="metaboxportfoliopro[custom_tag_two]" value='<?php echo $custom_tag_two; ?>'/>
					<p class="description"><?php _e( 'Use as custom tag', $this->plugin_name ); ?></p>
				</td>
			</tr>

			<tr valign="top">
				<td><?php _e( 'Third Custom Tag', $this->plugin_name ) ?></td>
				<td>
					<input type="text" name="metaboxportfoliopro[custom_tag_three]" value='<?php echo $custom_tag_three; ?>'/>
					<p class="description"><?php _e( 'Use as custom tag', $this->plugin_name ); ?></p>
				</td>
			</tr>


			<?php
			//allow others to show more custom fields at end
			do_action( 'portfoliopro_meta_fields_after_start', $fieldValues );
			?>

			</tbody>
		</table>

		<?php
		echo '</div>';


	}



	/**
	 * Determines whether or not the current user has the ability to save meta data associated with this post.
	 *
	 * Save portfoliopro Meta Field
	 *
	 * @param        int $post_id //The ID of the post being save
	 * @param         bool //Whether or not the user has the ability to save this post.
	 */
	public function save_post_metabox_portfoliopro( $post_id, $post ) {

		$post_type = 'portfoliopro';

		// If this isn't a 'book' post, don't update it.
		if ( $post_type != $post->post_type ) {
			return;
		}

		if ( ! empty( $_POST['metaboxportfoliopro'] ) ) {

			$postData = $_POST['metaboxportfoliopro'];

			$saveableData = array();

			if ( $this->user_can_save( $post_id, 'metaboxportfoliopro', $postData['nonce'] ) ) {

				$saveableData['ext_url']            = esc_url( $postData['ext_url'] );
				$saveableData['client_name']        = sanitize_text_field( $postData['client_name'] );
				$saveableData['project_name']       = sanitize_text_field( $postData['project_name'] );
				$saveableData['custom_tag_one']     = $postData['custom_tag_one'];
				$saveableData['custom_tag_two']     = $postData['custom_tag_two'];
				$saveableData['custom_tag_three']   = $postData['custom_tag_three'];

				update_post_meta( $post_id, '_portfolioprometa', $saveableData );
			}
		}
	}// End  Meta Save


	/**
	 * Determines whether or not the current user has the ability to save meta data associated with this post.
	 *
	 * user_can_save
	 *
	 * @param        int $post_id // The ID of the post being save
	 * @param        bool /Whether or not the user has the ability to save this post.
	 *
	 * @since 1.0
	 */
	public function user_can_save( $post_id, $action, $nonce ) {

		$is_autosave    = wp_is_post_autosave( $post_id );
		$is_revision    = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $nonce ) && wp_verify_nonce( $nonce, $action ) );

		// Return true if the user is able to save; otherwise, false.
		return ! ( $is_autosave || $is_revision ) && $is_valid_nonce;

	}


	/**
	 *
	 * Display Admin Settings Sidebar
	 *
	 * @since 1.0
	 *
	 */


	public function display_plugin_admin_settings() {

		global $wpdb;
		$plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_base_file);

		include('partials/portfolio-pro-admin-display.php');
	}


	/**
	 * Settings init
	 *
	 *  @since 1.0
	 */
	public function setting_init() {
		//set the settings
		$this->settings_api->set_sections($this->get_settings_sections());
		$this->settings_api->set_fields($this->get_settings_fields());

		//initialize settings
		$this->settings_api->admin_init();
	}


	/**
	 * Setings Sections
	 * @return array|mixed|void
	 *
	 *  @since 1.0
	 */

	public function get_settings_sections() {

		$sections = array(
			array(
				'id'    => 'portfoliopro_basic',
				'title' => __('Portfolio Settings', $this->plugin_name),
				'desc' => '<p class="lgx-update"><strong>'. __('This is default global value for Portfolio Pro. Also you can be override from shortcode params.', $this->plugin_name) .'<p><strong>'
			)
		);

		$sections = apply_filters('portfoliopro_settings_sections', $sections);

		return $sections;
	}

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */
	public  function get_settings_fields() {

		$hidden_class = '';

		$settings_fields = array(

			'portfoliopro_basic' => array(

				array(
					'name'             => 'portfoliopro_settings_row_item',
					'label'            => __('Item in Per Row',  $this->plugin_name),
					'desc'             => __('Number of items per row.',  $this->plugin_name),
					'type'             => 'select',
					'default'          => 'three',
					'options'          => array(
						'three'      => __('Three',  $this->plugin_name ),
						'four'      => __('Four',  $this->plugin_name ),
					),
				),

				array(
					'name'             => 'portfoliopro_settings_grid_type',
					'label'            => __('Grid Type',  $this->plugin_name),
					'desc'             => __('Grid display type.',  $this->plugin_name),
					'type'             => 'select',
					'default'          => 'three',
					'options'          => array(
						'normal'      => __('Normal',  $this->plugin_name ),
						'fluid'      => __('Fluid',  $this->plugin_name ),
					),
				),

				array(
					'name'             => 'portfoliopro_settings_grid_layout',
					'label'            => __('Grid Layout Mood',  $this->plugin_name),
					'desc'             => __('Set Grid Layout Mood.',  $this->plugin_name),
					'type'             => 'select',
					'default'          => 'masonry',
					'options'          => array(
						'masonry'      => __('Masonry',  $this->plugin_name ),
						'fitRows'      => __( 'Fit Rows',  $this->plugin_name ),
						'vertical'     => __( 'Vertical',  $this->plugin_name ),
					),
				),

				array(
					'name'     => 'portfoliopro_settings_limit',
					'label'    => __('Item Limit',  $this->plugin_name),
					'desc'     => __('Please input total number of item, that want to display front end.',  $this->plugin_name),
					'type'     => 'number',
					'default'  => '-1',
					'desc_tip' => true,
				),

				array(
					'name'             => 'portfoliopro_settings_order',
					'label'            => __('Item Order',  $this->plugin_name),
					'desc'             => __('Direction to sort item.',  $this->plugin_name),
					'type'             => 'select',
					'default'          => 'DESC',
					'options'          => array(
						'ASC' => __( 'Ascending',  $this->plugin_name ),
						'DESC'   => __( 'Descending',  $this->plugin_name ),
					),
				),

				array(
					'name'             => 'portfoliopro_settings_orderby',
					'label'            => __('Item Order By',  $this->plugin_name),
					'desc'             => __('Sort retrieved item.',  $this->plugin_name),
					'type'             => 'select',
					'default'          => 'date',
					'options'          => array(
						'date'      => __( 'Date',  $this->plugin_name ),
						'ID'        => __( 'ID',  $this->plugin_name ),
						'title'     => __( 'Title',  $this->plugin_name ),
						'modified'  => __( 'Modified',  $this->plugin_name ),
						'rand'      => __( 'Random',  $this->plugin_name ),
					),
				),

				array(
					'name'             => 'portfoliopro_settings_fulllinktype',
					'label'            => __('Enable Full Image Clickable',  $this->plugin_name),
					'desc'             => __('Making Full Image clickable with a suitable link type. If full image is clickable then inner link button will be despaired.',  $this->plugin_name),
					'type'             => 'select',
					'default'          => 'none',
					'options'          => array(
						'none'      			=> __( 'Disable',  $this->plugin_name ),
						'full-internal-link'     => __( 'Enable with Details Link',  $this->plugin_name ),
						'full-external-link'     => __( 'Enable with External Link',  $this->plugin_name ),
					),
				),


				array(
					'name'             => 'portfoliopro_settings_grayimg',
					'label'            => __('Image Gray Scale',  $this->plugin_name),
					'desc'             => __('Enable Gray Scale for portfolio image.',  $this->plugin_name),
					'type'             => 'select',
					'default'          => 'yes',
					'options'          => array(
						'yes'      => __( 'Enable',  $this->plugin_name ),
						'no'        => __( 'Disable',  $this->plugin_name ),
					),
				),


			),// Single

		);//Filed

		$settings_fields = apply_filters('portfoliopro_settings_fields', $settings_fields);

		return $settings_fields;
	}







}
