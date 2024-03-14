<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://thebrandiD.com
 * @since      2.0.0
 *
 * @package    Social_Proof_Slider
 * @subpackage Social_Proof_Slider/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Social_Proof_Slider
 * @subpackage Social_Proof_Slider/admin
 * @author     brandiD <tech@thebrandid.com>
 */
class Social_Proof_Slider_Admin {

	/**
	 * The options name to be used in this plugin
	 *
	 * @since  	2.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Option name of this plugin
	 */
	private $option_name = 'social_proof_slider';

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Social_Proof_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Social_Proof_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// Color Picker
		wp_enqueue_style( 'wp-color-picker' );

		// Font Awesome
		$fontawesome = 'fontawesome';
		$font_awesome = 'font-awesome';
		if ( wp_script_is( $font_awesome, 'enqueued' ) || wp_script_is( $fontawesome, 'enqueued' ) ) {
			return;
		} else {
			wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css', array(), $this->version );
		}

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/social-proof-slider-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Social_Proof_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Social_Proof_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/social-proof-slider-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, false );

	}

	/**
	 * Enqueue Slick JS for the Block.
	 *
	 * @since    2.1.2
	 */
	public function spslider_block_scripts() {
		wp_enqueue_script( 'slick-js', '//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js', array( 'jquery' ), '1.8.1', true );
		wp_enqueue_script( 'slick-spslider-block-js', plugin_dir_url( __FILE__ ) . 'js/slick-block-editor.js', array( 'jquery', 'slick-js' ), $this->version, true );
		wp_enqueue_style( 'slick-spslider-block-css', '//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css', array(), '1.8.1', 'all' );
	}

	/**
	 * Enqueue Admin CSS for the Block.
	 *
	 * @since    2.1.2
	 */
	public function spslider_admin_block_styles() {
		wp_enqueue_style( 'spslider-admin-block-css', plugin_dir_url( __FILE__ ) . 'css/sp-slider-testimonials-block.css', array(), $this->version, 'all' );
	}

	/**
	 * Adds a link to the plugin settings page
	 *
	 * @since 		2.0.0
	 * @param 		array 		$links 		The current array of links
	 * @return 		array 					The modified array of links
	 */
	public function link_settings( $links ) {

		$links[] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'options-general.php?page=social-proof-slider' ) ), esc_html__( 'Settings', 'social-proof-slider' ) );

		return $links;

	} // link_settings()

	/**
	 * Creates a new custom post type
	 *
	 * @since 	2.0.0
	 * @access 	public
	 * @uses 	register_post_type()
	 */
	public static function new_cpt_testimonials() {

		$cap_type 	= 'post';
		$plural 	= 'Testimonials';
		$single 	= 'Testimonial';
		$cpt_name 	= 'socialproofslider';

		$opts['can_export']								= TRUE;
		$opts['capability_type']						= $cap_type;
		$opts['description']							= __('Social Proof Slider Testimonials', 'social-proof-slider');
		$opts['exclude_from_search']					= TRUE;
		$opts['has_archive']							= FALSE;
		$opts['hierarchical']							= FALSE;
		$opts['map_meta_cap']							= TRUE;
		$opts['menu_icon']								= 'dashicons-socialproofslider';
		$opts['menu_position']							= 25;
		$opts['public']									= TRUE;
		$opts['publicly_querable']						= FALSE;
		$opts['register_meta_box_cb']					= '';
		$opts['rewrite']								= FALSE;
		$opts['show_in_admin_bar']						= TRUE;
		$opts['show_in_menu']							= TRUE;
		$opts['show_in_nav_menu']						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['supports']								= array( 'title', 'page-attributes', 'thumbnail' );
		$opts['taxonomies']								= array( 'category' );

		$opts['capabilities']['delete_others_posts']	= "delete_others_{$cap_type}s";
		$opts['capabilities']['delete_post']			= "delete_{$cap_type}";
		$opts['capabilities']['delete_posts']			= "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']	= "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts']	= "delete_published_{$cap_type}s";
		$opts['capabilities']['edit_others_posts']		= "edit_others_{$cap_type}s";
		$opts['capabilities']['edit_post']				= "edit_{$cap_type}";
		$opts['capabilities']['edit_posts']				= "edit_{$cap_type}s";
		$opts['capabilities']['edit_private_posts']		= "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']	= "edit_published_{$cap_type}s";
		$opts['capabilities']['publish_posts']			= "publish_{$cap_type}s";
		$opts['capabilities']['read_post']				= "read_{$cap_type}";
		$opts['capabilities']['read_private_posts']		= "read_private_{$cap_type}s";

		$opts['labels']['add_new']						= esc_html__( "Add New {$single}", 'social-proof-slider' );
		$opts['labels']['add_new_item']					= esc_html__( "Add New {$single}", 'social-proof-slider' );
		$opts['labels']['all_items']					= esc_html__( $plural, 'social-proof-slider' );
		$opts['labels']['edit_item']					= esc_html__( "Edit {$single}" , 'social-proof-slider' );
		$opts['labels']['menu_name']					= esc_html__( $plural, 'social-proof-slider' );
		$opts['labels']['name']							= esc_html__( $plural, 'social-proof-slider' );
		$opts['labels']['name_admin_bar']				= esc_html__( $single, 'social-proof-slider' );
		$opts['labels']['new_item']						= esc_html__( "New {$single}", 'social-proof-slider' );
		$opts['labels']['not_found']					= esc_html__( "No {$plural} Found", 'social-proof-slider' );
		$opts['labels']['not_found_in_trash']			= esc_html__( "No {$plural} Found in Trash", 'social-proof-slider' );
		$opts['labels']['parent_item_colon']			= esc_html__( "Parent {$plural} :", 'social-proof-slider' );
		$opts['labels']['search_items']					= esc_html__( "Search {$plural}", 'social-proof-slider' );
		$opts['labels']['singular_name']				= esc_html__( $single, 'social-proof-slider' );
		$opts['labels']['view_item']					= esc_html__( "View {$single}", 'social-proof-slider' );

		// $opts['rewrite']['ep_mask']						= EP_PERMALINK;
		// $opts['rewrite']['feeds']						= FALSE;
		// $opts['rewrite']['pages']						= TRUE;
		// $opts['rewrite']['slug']						= esc_html__( strtolower( $plural ), 'social-proof-slider' );
		// $opts['rewrite']['with_front']					= FALSE;

		$opts = apply_filters( 'social-proof-slider-cpt-options', $opts );

		register_post_type( strtolower( $cpt_name ), $opts );

	} // new_cpt_testimonials()



	/**
	 * Add an "Image" column to the Testimonials list in the Admin area
	 *
	 * @since  2.0.3
	 */
	public static function set_custom_edit_socialproofslider_columns($columns) {

		// Remove the Title and Date columns now, add them back later
		unset($columns['title']);
		unset($columns['date']);

		// Add new "Image" column
		$columns['featuredimage'] = __( 'Image', 'socialproofslider' );

		// Add the Title and Date columns back in
		$columns['title'] = __( 'Title', 'socialproofslider' );
		$columns['date'] = __( 'Date', 'socialproofslider' );

		return $columns;
	}


	/**
	 * Display the "Image" column data
	 *
	 * @since  2.0.3
	 */
	public static function custom_socialproofslider_column( $column, $post_id ) {
		if ( $column == 'featuredimage') {
			$img = get_the_post_thumbnail( $post_id, 'thumbnail' );
			echo $img;
		}
	}


	/**
	 * Add an options page for Shortcodes under the Settings submenu
	 *
	 * @since  2.0.0
	 */
	public function add_options_page() {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Social Proof Slider Settings', 'social-proof-slider' ),
			__( 'Social Proof Slider', 'social-proof-slider' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_spslider_options_page' )
		);

	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  2.0.0
	 */
	public function display_spslider_options_page() {
		include_once 'partials/social-proof-slider-admin-display.php';
	}

	/**
	 * Render the text for the general section
	 *
	 * @since  2.0.0
	 */
	public function social_proof_slider_general_settings_section_cb() {
		echo '<p>' . __( 'Below are the settings for all Shortcode-based sliders.', 'social-proof-slider' ) . '</p>';
	}


	/* ==================== FORM FIELDS ==================== */

	/**
	 * Render the select input field for 'sortby' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_sortby_cb( array $args ) {
		$sortby = get_option( $this->option_name . '_sortby' );
		?>
		<select name="<?php echo esc_attr( $args['id'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>">
			<option value="RAND" <?php echo $sortby == 'RAND' ? 'selected="selected"' : ''; ?> >Random (default)</option>
			<option value="DESC" <?php echo $sortby == 'DESC' ? 'selected="selected"' : ''; ?> >Date - Descending</option>
			<option value="ASC" <?php echo $sortby == 'ASC' ? 'selected="selected"' : ''; ?> >Date - Ascending</option>
		</select>
		<?php
	}

	/**
	 * Render the checkbox for 'autoplay' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_autoplay_cb( array $args ) {
		$autoplay = get_option( $this->option_name . '_autoplay' );
		$checked = ( (int)$autoplay == 1 ) ? 'checked' : '';
		?>
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input aria-role="checkbox"
				<?php echo $checked; ?>
				class="<?php echo esc_attr( $args['id'] ); ?>"
				id="<?php echo esc_attr( $args['id'] ); ?>"
				name="<?php echo esc_attr( $args['id'] ); ?>"
				type="checkbox"
				value="1" /><br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		<?php
	}

	/**
	 * Render the textbox for 'displaytime' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_displaytime_cb( array $args ) {
		$displaytime = get_option( $this->option_name . '_displaytime' );
		if ( empty( $displaytime ) ) {
			$displaytime = esc_attr( $args['value'] );
		}
		?>
		<div class="displaytime">
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input
			class="<?php echo esc_attr( $args['id'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"
			type="text"
			value="<?php echo $displaytime; ?>" /><br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		</div>
		<?php
	}

	/**
	 * Render the select input field for 'animationstyle' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_animationstyle_cb( array $args ) {
		$animationstyle = get_option( $this->option_name . '_animationstyle' );
		if ( empty( $animationstyle ) ){
			$animationstyle = esc_attr( $args['value'] );
		}
		?>
		<select name="<?php echo esc_attr( $args['id'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>">
			<option value="fade" <?php echo $animationstyle == 'fade' ? 'selected="selected"' : ''; ?> >Fade (default)</option>
			<option value="slide" <?php echo $animationstyle == 'slide' ? 'selected="selected"' : ''; ?> >Slide</option>
		</select>
		<?php
	}

	/**
	 * Render the radio buttons for 'autoheight' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_autoheight_cb( array $args ) {
		$autoheight = get_option( $this->option_name . '_autoheight' );
		if ( empty ( $autoheight ) ) {
			// $autoheight = esc_attr( $args['value'] );
			$autoheight = '0';
		}
		?>
		<div class="autoheight">
			<div class="item">
				<input
					type="radio"
					name="<?php echo esc_attr( $args['id'] ); ?>"
					value="1"
					id="<?php echo esc_attr( $args['id'] ); ?>_variable"
					data-radio-id="<?php echo esc_attr( $args['id'] ); ?>"
					<?php echo $autoheight == '1' ? 'checked="checked"' : ''; ?> />
				<span class="<?php echo $autoheight == '1' ? 'radio-checked' : 'radio'; ?>"><?php echo __("Variable Height"); ?></span>
			</div>
			<div class="item">
				<input
					type="radio"
					name="<?php echo esc_attr( $args['id'] ); ?>"
					value="0"
					id="<?php echo esc_attr( $args['id'] ); ?>_fixed"
					data-radio-id="<?php echo esc_attr( $args['id'] ); ?>"
					<?php echo $autoheight == '0' ? 'checked="checked"' : ''; ?> />
				<span class="<?php echo $autoheight == '0' ? 'radio-checked' : 'radio'; ?>"><?php echo __("Fixed Height"); ?></span>
			</div>
		</div>
		<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		<?php
	}

	/**
	 * Render the radio buttons for 'verticalalign' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_verticalalign_cb( array $args ) {
		$verticalalign = get_option( $this->option_name . '_verticalalign' );
		if ( empty ( $verticalalign ) ) {
			$verticalalign = esc_attr( $args['value'] );
		}
		?>
		<div class="verticalalign">
		<div class="item">
			<input type="radio" name="<?php echo esc_attr( $args['id'] ); ?>" value="align_top" data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" <?php echo $verticalalign == 'align_top' ? 'checked="checked"' : ''; ?> />
			<a data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" class="icon" href="#" title="<?php echo __("Align Top"); ?>"><span class="<?php echo $verticalalign == 'align_top' ? 'radio-checked' : 'radio'; ?>"><i class="dashicons-align-top"></i></span></a>
		</div>
		<div class="item">
			<input type="radio" name="<?php echo esc_attr( $args['id'] ); ?>" value="align_middle" data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" <?php echo $verticalalign == 'align_middle' ? 'checked="checked"' : ''; ?> />
			<a data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" class="icon" href="#" title="<?php echo __("Align Middle"); ?>"><span class="<?php echo $verticalalign == 'align_middle' ? 'radio-checked' : 'radio'; ?>"><i class="dashicons-align-middle"></i></span></a>
		</div>
		<div class="item">
			<input type="radio" name="<?php echo esc_attr( $args['id'] ); ?>" value="align_bottom" data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" <?php echo $verticalalign == 'align_bottom' ? 'checked="checked"' : ''; ?> />
			<a data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" class="icon" href="#" title="<?php echo __("Align Bottom"); ?>"><span class="<?php echo $verticalalign == 'align_bottom' ? 'radio-checked' : 'radio'; ?>"><i class="dashicons-align-bottom"></i></span></a>
		</div>
		</div>
		<?php
	}

	/**
	 * Render the checkbox for 'paddingoverride' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_paddingoverride_cb( array $args ) {
		$paddingoverride = get_option( $this->option_name . '_paddingoverride' );
		$checked = ( (int)$paddingoverride == 1 ) ? 'checked' : '';
		?>
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input aria-role="checkbox"
				<?php echo $checked; ?>
				class="<?php echo esc_attr( $args['id'] ); ?>"
				id="<?php echo esc_attr( $args['id'] ); ?>"
				name="<?php echo esc_attr( $args['id'] ); ?>"
				type="checkbox"
				value="1" /><br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		<?php
	}

	/**
	 * Render the textbox for 'contentpaddingtop' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_contentpaddingtop_cb( array $args ) {
		$contentpaddingtop = get_option( $this->option_name . '_contentpaddingtop' );
		if ( empty( $contentpaddingtop ) ) {
			$contentpaddingtop = esc_attr( $args['value'] );
		}
		?>
		<div class="paddingoverride">
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input
			class="<?php echo esc_attr( $args['id'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"
			type="number"
			value="<?php echo $contentpaddingtop; ?>" />px<br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		</div>
		<?php
	}

	/**
	 * Render the textbox for 'contentpaddingbottom' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_contentpaddingbottom_cb( array $args ) {
		$contentpaddingbottom = get_option( $this->option_name . '_contentpaddingbottom' );
		if ( empty( $contentpaddingbottom ) ) {
			$contentpaddingbottom = esc_attr( $args['value'] );
		}
		?>
		<div class="paddingoverride">
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input
			class="<?php echo esc_attr( $args['id'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"
			type="number"
			value="<?php echo $contentpaddingbottom; ?>" />px<br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		</div>
		<?php
	}

	/**
	 * Render the textbox for 'featimgmargintop' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_featimgmargintop_cb( array $args ) {
		$featimgmargintop = get_option( $this->option_name . '_featimgmargintop' );
		if ( empty( $featimgmargintop ) ) {
			$featimgmargintop = esc_attr( $args['value'] );
		}
		?>
		<div class="paddingoverride">
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input
			class="<?php echo esc_attr( $args['id'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"
			type="number"
			value="<?php echo $featimgmargintop; ?>" />px<br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		</div>
		<?php
	}

	/**
	 * Render the textbox for 'featimgmarginbottom' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_featimgmarginbottom_cb( array $args ) {
		$featimgmarginbottom = get_option( $this->option_name . '_featimgmarginbottom' );
		if ( empty( $featimgmarginbottom ) ) {
			$featimgmarginbottom = esc_attr( $args['value'] );
		}
		?>
		<div class="paddingoverride">
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input
			class="<?php echo esc_attr( $args['id'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"
			type="number"
			value="<?php echo $featimgmarginbottom; ?>" />px<br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		</div>
		<?php
	}

	/**
	 * Render the textbox for 'textpaddingtop' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_textpaddingtop_cb( array $args ) {
		$textpaddingtop = get_option( $this->option_name . '_textpaddingtop' );
		if ( empty( $textpaddingtop ) ) {
			$textpaddingtop = esc_attr( $args['value'] );
		}
		?>
		<div class="paddingoverride">
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input
			class="<?php echo esc_attr( $args['id'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"
			type="number"
			value="<?php echo $textpaddingtop; ?>" />px<br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		</div>
		<?php
	}

	/**
	 * Render the textbox for 'textpaddingbottom' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_textpaddingbottom_cb( array $args ) {
		$textpaddingbottom = get_option( $this->option_name . '_textpaddingbottom' );
		if ( empty( $textpaddingbottom ) ) {
			$textpaddingbottom = esc_attr( $args['value'] );
		}
		?>
		<div class="paddingoverride">
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input
			class="<?php echo esc_attr( $args['id'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"
			type="number"
			value="<?php echo $textpaddingbottom; ?>" />px<br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		</div>
		<?php
	}

	/**
	 * Render the textbox for 'quotemarginbottom' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_quotemarginbottom_cb( array $args ) {
		$quotemarginbottom = get_option( $this->option_name . '_quotemarginbottom' );
		if ( empty( $quotemarginbottom ) ) {
			$quotemarginbottom = esc_attr( $args['value'] );
		}
		?>
		<div class="paddingoverride">
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input
			class="<?php echo esc_attr( $args['id'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"
			type="number"
			value="<?php echo $quotemarginbottom; ?>" />px<br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		</div>
		<?php
	}

	/**
	 * Render the textbox for 'dotsmargintop' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_dotsmargintop_cb( array $args ) {
		$dotsmargintop = get_option( $this->option_name . 'dotsmargintop' );
		if ( empty( $dotsmargintop ) ) {
			$dotsmargintop = esc_attr( $args['value'] );
		}
		?>
		<div class="paddingoverride">
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input
			class="<?php echo esc_attr( $args['id'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"
			type="number"
			value="<?php echo $dotsmargintop; ?>" />px<br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		</div>
		<?php
	}

	/**
	 * Render the checkbox for 'showfeaturedimg' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_showfeaturedimg_cb( array $args ) {
		$showfeaturedimg = get_option( $this->option_name . '_showfeaturedimg' );
		$checked = ( (int)$showfeaturedimg == 1 ) ? 'checked' : '';
		?>
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input aria-role="checkbox"
				<?php echo $checked; ?>
				class="<?php echo esc_attr( $args['id'] ); ?>"
				id="<?php echo esc_attr( $args['id'] ); ?>"
				name="<?php echo esc_attr( $args['id'] ); ?>"
				type="checkbox"
				value="1" /><br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		<?php
	}

	/**
	 * Render the textbox for 'imgborderradius' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_imgborderradius_cb( array $args ) {
		$imgborderradius = get_option( $this->option_name . '_imgborderradius' );
		if ( empty( $imgborderradius ) ) {
			$imgborderradius = esc_attr( $args['value'] );
		}
		?>
		<div class="imgborderradius">
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input
			class="<?php echo esc_attr( $args['id'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"
			type="text"
			value="<?php echo $imgborderradius; ?>" />px<br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		</div>
		<?php
	}

	/**
	 * Render the checkbox for 'showimgborder' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.8
	 */
	public function social_proof_slider_showimgborder_cb( array $args ) {
		$showimgborder = get_option( $this->option_name . '_showimgborder' );
		$checked = ( (int)$showimgborder == 1 ) ? 'checked' : '';
		?>
		<div class="showimgborder">
			<label for="<?php echo esc_attr( $args['id'] ); ?>">
				<input aria-role="checkbox"
					<?php echo $checked; ?>
					class="<?php echo esc_attr( $args['id'] ); ?>"
					id="<?php echo esc_attr( $args['id'] ); ?>"
					name="<?php echo esc_attr( $args['id'] ); ?>"
					type="checkbox"
					value="1" /><br>
				<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
			</label>
		</div>
		<?php
	}

	/**
	 * Render the color picker field for 'imgbordercolor' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.8
	 */
	public function social_proof_slider_imgbordercolor_cb( array $args ) {
		$imgbordercolor = get_option( $this->option_name . '_imgbordercolor' );
		?>
		<div class="imgbordercolor">
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span><br>
			<label for="<?php echo esc_attr( $args['id'] ); ?>">
				<input
				class="color-picker"
				id="<?php echo esc_attr( $args['id'] ); ?>"
				name="<?php echo esc_attr( $args['id'] ); ?>"
				type="text"
				value="<?php echo $imgbordercolor; ?>" />
			</label>
		</div>
		<?php
	}

	/**
	 * Render the textbox for 'imgborderthickness' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.8
	 */
	public function social_proof_slider_imgborderthickness_cb( array $args ) {
		$imgborderthickness = get_option( $this->option_name . '_imgborderthickness' );
		if ( empty( $imgborderthickness ) ) {
			$imgborderthickness = esc_attr( $args['value'] );
		}
		?>
		<div class="imgborderthickness">
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input
			class="<?php echo esc_attr( $args['id'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"
			type="text"
			value="<?php echo $imgborderthickness; ?>" /><br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		</div>
		<?php
	}

	/**
	 * Render the textbox for 'imgborderpadding' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.8
	 */
	public function social_proof_slider_imgborderpadding_cb( array $args ) {
		$imgborderpadding = get_option( $this->option_name . '_imgborderpadding' );
		if ( empty( $imgborderpadding ) ) {
			$imgborderpadding = esc_attr( $args['value'] );
		}
		?>
		<div class="imgborderpadding">
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input
			class="<?php echo esc_attr( $args['id'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"
			type="text"
			value="<?php echo $imgborderpadding; ?>" /><br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		</div>
		<?php
	}

	/**
	 * Render the color picker field for 'bgcolor' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_bgcolor_cb( array $args ) {
		$bgcolor = get_option( $this->option_name . '_bgcolor' );
		?>
		<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span><br>
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input
			class="color-picker"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"
			type="text"
			value="<?php echo $bgcolor; ?>" />
		</label>
		<?php
	}

	/**
	 * Render the checkbox for 'surroundquotes' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_surroundquotes_cb( array $args ) {
		$surroundquotes = get_option( $this->option_name . '_surroundquotes' );
		$checked = ( (int)$surroundquotes == 1 ) ? 'checked' : '';
		?>
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input aria-role="checkbox"
				<?php echo $checked; ?>
				class="<?php echo esc_attr( $args['id'] ); ?>"
				id="<?php echo esc_attr( $args['id'] ); ?>"
				name="<?php echo esc_attr( $args['id'] ); ?>"
				type="checkbox"
				value="1" /><br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		<?php
	}

	/**
	 * Render the select input field for 'textalign' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_textalign_cb( array $args ) {
		$textalign = get_option( $this->option_name . '_textalign' );
		if ( empty ( $textalign ) ) {
			$textalign = esc_attr( $args['value'] );
		}
		?>
		<div class="textalign">
		<div class="item">
			<input type="radio" name="<?php echo esc_attr( $args['id'] ); ?>" value="align_left" data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" <?php echo $textalign == 'align_left' ? 'checked="checked"' : ''; ?> />
			<a data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" class="icon" href="#" title="<?php echo __("Align Left"); ?>"><span class="<?php echo $textalign == 'align_left' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-align-left"></i></span></a>
		</div>
		<div class="item">
			<input type="radio" name="<?php echo esc_attr( $args['id'] ); ?>" value="align_center" data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" <?php echo $textalign == 'align_center' ? 'checked="checked"' : ''; ?> />
			<a data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" class="icon" href="#" title="<?php echo __("Align Center"); ?>"><span class="<?php echo $textalign == 'align_center' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-align-center"></i></span></a>
		</div>
		<div class="item">
			<input type="radio" name="<?php echo esc_attr( $args['id'] ); ?>" value="align_right" data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" <?php echo $textalign == 'align_right' ? 'checked="checked"' : ''; ?> />
			<a data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" class="icon" href="#" title="<?php echo __("Align Right"); ?>"><span class="<?php echo $textalign == 'align_right' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-align-right"></i></span></a>
		</div>
		</div>
		<?php
	}

	/**
	 * Render the color picker field for 'textcolor' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_textcolor_cb( array $args ) {
		$textcolor = get_option( $this->option_name . '_textcolor' );
		if ( empty ( $textcolor ) ) {
			$textcolor = esc_attr( $args['value'] );
		}
		?>
		<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span><br>
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input
			class="color-picker"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"
			type="text"
			value="<?php echo $textcolor; ?>" />
		</label>
		<?php
	}

	/**
	 * Render the checkbox for 'showarrows' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_showarrows_cb( array $args ) {
		$showarrows = get_option( $this->option_name . '_showarrows' );
		$checked = ( (int)$showarrows == 1 ) ? 'checked' : '';
		?>
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input aria-role="checkbox"
				<?php echo $checked; ?>
				class="<?php echo esc_attr( $args['id'] ); ?>"
				id="<?php echo esc_attr( $args['id'] ); ?>"
				name="<?php echo esc_attr( $args['id'] ); ?>"
				type="checkbox"
				value="1" /><br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		<?php
	}

	/**
	 * Render the select input fields for 'arrowiconstyle' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_arrowiconstyle_cb( array $args ) {
		$arrowiconstyle = get_option( $this->option_name . '_arrowiconstyle' );
		if ( empty ( $arrowiconstyle ) ) {
			$arrowiconstyle = esc_attr( $args['value'] );
		}
		?>
		<div class="arrowiconstyle">
		<div class="item">
			<input type="radio" name="<?php echo esc_attr( $args['id'] ); ?>" value="style_zero" data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" <?php echo $arrowiconstyle == 'style_zero' ? 'checked="checked"' : ''; ?> />
			<a data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" class="icon" href="#"><span class="<?php echo $arrowiconstyle == 'style_zero' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-angle-left"></i> <i class="fa fa-angle-right"></i></span></a>
		</div>
		<div class="item">
			<input type="radio" name="<?php echo esc_attr( $args['id'] ); ?>" value="style_one" data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" <?php echo $arrowiconstyle == 'style_one' ? 'checked="checked"' : ''; ?> />
			<a data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" class="icon" href="#"><span class="<?php echo $arrowiconstyle == 'style_one' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-angle-double-left"></i> <i class="fa fa-angle-double-right"></i></span></a>
		</div>
		<div class="item">
			<input type="radio" name="<?php echo esc_attr( $args['id'] ); ?>" value="style_two" data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" <?php echo $arrowiconstyle == 'style_two' ? 'checked="checked"' : ''; ?> />
			<a data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" class="icon" href="#"><span class="<?php echo $arrowiconstyle == 'style_two' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-arrow-circle-left"></i> <i class="fa fa-arrow-circle-right"></i></span></a>
		</div>
		<div class="item">
			<input type="radio" name="<?php echo esc_attr( $args['id'] ); ?>" value="style_three" data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" <?php echo $arrowiconstyle == 'style_three' ? 'checked="checked"' : ''; ?> />
			<a data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" class="icon" href="#"><span class="<?php echo $arrowiconstyle == 'style_three' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-arrow-circle-o-left"></i> <i class="fa fa-arrow-circle-o-right"></i></span></a>
		</div>
		<div class="item">
			<input type="radio" name="<?php echo esc_attr( $args['id'] ); ?>" value="style_four" data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" <?php echo $arrowiconstyle == 'style_four' ? 'checked="checked"' : ''; ?> />
			<a data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" class="icon" href="#"><span class="<?php echo $arrowiconstyle == 'style_four' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-arrow-left"></i> <i class="fa fa-arrow-right"></i></span></a>
		</div>
		<div class="item">
			<input type="radio" name="<?php echo esc_attr( $args['id'] ); ?>" value="style_five" data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" <?php echo $arrowiconstyle == 'style_five' ? 'checked="checked"' : ''; ?> />
			<a data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" class="icon" href="#"><span class="<?php echo $arrowiconstyle == 'style_five' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-caret-left"></i> <i class="fa fa-caret-right"></i></span></a>
		</div>
		<div class="item">
			<input type="radio" name="<?php echo esc_attr( $args['id'] ); ?>" value="style_six" data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" <?php echo $arrowiconstyle == 'style_six' ? 'checked="checked"' : ''; ?> />
			<a data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" class="icon" href="#"><span class="<?php echo $arrowiconstyle == 'style_six' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-caret-square-o-left"></i> <i class="fa fa-caret-square-o-right"></i></span></a>
		</div>
		<div class="item">
			<input type="radio" name="<?php echo esc_attr( $args['id'] ); ?>" value="style_seven" data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" <?php echo $arrowiconstyle == 'style_seven' ? 'checked="checked"' : ''; ?> />
			<a data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" class="icon" href="#"><span class="<?php echo $arrowiconstyle == 'style_seven' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-chevron-circle-left"></i> <i class="fa fa-chevron-circle-right"></i></span></a>
		</div>
		<div class="item">
			<input type="radio" name="<?php echo esc_attr( $args['id'] ); ?>" value="style_eight" data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" <?php echo $arrowiconstyle == 'style_eight' ? 'checked="checked"' : ''; ?> />
			<a data-radio-id="<?php echo esc_attr( $args['id'] ); ?>" class="icon" href="#"><span class="<?php echo $arrowiconstyle == 'style_eight' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-chevron-left"></i> <i class="fa fa-chevron-right"></i></span></a>
		</div>
		<p><span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span></p>
		</div>
		<?php
	}

	/**
	 * Render the color picker field for 'arrowcolor' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_arrowcolor_cb( array $args ) {
		$arrowcolor = get_option( $this->option_name . '_arrowcolor' );
		if ( empty ( $arrowcolor ) ) {
			$arrowcolor = esc_attr( $args['value'] );
		}
		?>
		<div class="arrowcolor">
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span><br>
			<label for="<?php echo esc_attr( $args['id'] ); ?>">
				<input
				class="color-picker"
				id="<?php echo esc_attr( $args['id'] ); ?>"
				name="<?php echo esc_attr( $args['id'] ); ?>"
				type="text"
				value="<?php echo $arrowcolor; ?>" />
			</label>
		</div>
		<?php
	}

	/**
	 * Render the color picker field for 'arrowhovercolor' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_arrowhovercolor_cb( array $args ) {
		$arrowhovercolor = get_option( $this->option_name . '_arrowhovercolor' );
		if ( empty ( $arrowhovercolor ) ) {
			$arrowhovercolor = esc_attr( $args['value'] );
		}
		?>
		<div class="arrowhovercolor">
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
			<label for="<?php echo esc_attr( $args['id'] ); ?>">
				<input
				class="color-picker"
				id="<?php echo esc_attr( $args['id'] ); ?>"
				name="<?php echo esc_attr( $args['id'] ); ?>"
				type="text"
				value="<?php echo $arrowhovercolor; ?>" />
			</label>
		</div>
		<?php
	}

	/**
	 * Render the checkbox for 'showdots' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_showdots_cb( array $args ) {
		$showdots = get_option( $this->option_name . '_showdots' );
		$checked = ( (int)$showdots == 1 ) ? 'checked' : '';
		?>
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input aria-role="checkbox"
				<?php echo $checked; ?>
				class="<?php echo esc_attr( $args['id'] ); ?>"
				id="<?php echo esc_attr( $args['id'] ); ?>"
				name="<?php echo esc_attr( $args['id'] ); ?>"
				type="checkbox"
				value="1" /><br>
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
		</label>
		<?php
	}

	/**
	 * Render the color picker field for 'dotscolor' option
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 * @since  2.0.0
	 */
	public function social_proof_slider_dotscolor_cb( array $args ) {
		$dotscolor = get_option( $this->option_name . '_dotscolor' );
		if ( empty ( $dotscolor ) ) {
			$dotscolor = esc_attr( $args['value'] );
		}
		?>
		<div class="dotscolor">
			<span class="description"><?php esc_html_e( $args['description'], 'social-proof-slider' ); ?></span>
			<label for="<?php echo esc_attr( $args['id'] ); ?>">
				<input
				class="color-picker"
				id="<?php echo esc_attr( $args['id'] ); ?>"
				name="<?php echo esc_attr( $args['id'] ); ?>"
				type="text"
				value="<?php echo $dotscolor; ?>" />
			</label>
		</div>
		<?php
	}

	/* ==================== SANITIZE FIELDS ==================== */

	/**
	 * Sanitize the text 'sortby' value before being saved to database
	 *
	 * @param  string $sortby $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_sortby_field( $sortby ) {
		if ( in_array( $sortby, array( 'RAND', 'DESC', 'ASC' ), true ) ) {
			return $sortby;
		}
	}

	/**
	 * Sanitize the text 'displaytime' value before being saved to database
	 *
	 * @param  string $displaytime $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_displaytime_field( $displaytime ) {
		$charlength = strlen( utf8_decode( $displaytime ) );
		if ( $charlength <= 5 ) {
			if ( is_numeric( $displaytime ) ) {
				return $displaytime;
			}
		}
	}

	/**
	 * Sanitize the text 'animationstyle' value before being saved to database
	 *
	 * @param  string $animationstyle $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_animationstyle_field( $animationstyle ) {
		if ( in_array( $animationstyle, array( 'fade', 'slide' ), true ) ) {
			return $animationstyle;
		}
	}

	/**
	 * Sanitize the text 'autoheight' value before being saved to database
	 *
	 * @param  string $autoheight $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_autoheight_field( $autoheight ) {
		if ( in_array( $autoheight, array( '1', '0' ), true ) ) {
			return $autoheight;
		}
	}

	/**
	 * Sanitize the text 'verticalalign' value before being saved to database
	 *
	 * @param  string $verticalalign $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_verticalalign_field( $verticalalign ) {
		if ( in_array( $verticalalign, array( 'align_top', 'align_middle', 'align_bottom' ), true ) ) {
			return $verticalalign;
		}
	}

	/**
	 * Sanitize the text 'contentpaddingtop' value before being saved to database
	 *
	 * @param  string $contentpaddingtop $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_contentpaddingtop_field( $contentpaddingtop ) {
		// regex: any Digit, then either 'px' or '%'
		if ( preg_match( '/(\d*)(px|%)/', $contentpaddingbottom ) ) {
			return $contentpaddingbottom;
		}
	}

	/**
	 * Sanitize the text 'contentpaddingbottom' value before being saved to database
	 *
	 * @param  string $contentpaddingbottom $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_contentpaddingbottom_field( $contentpaddingbottom ) {
		// regex: any Digit, then either 'px' or '%'
		if ( preg_match( '/(\d*)(px|%)/', $contentpaddingbottom ) ) {
			return $contentpaddingbottom;
		}
	}

	/**
	 * Sanitize the text 'featimgmargintop' value before being saved to database
	 *
	 * @param  string $featimgmargintop $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_featimgmargintop_field( $featimgmargintop ) {
		// regex: any Digit, then either 'px' or '%'
		if ( preg_match( '/(\d*)(px|%)/', $featimgmargintop ) ) {
			return $featimgmargintop;
		}
	}

	/**
	 * Sanitize the text 'featimgmarginbottom' value before being saved to database
	 *
	 * @param  string $featimgmarginbottom $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_featimgmarginbottom_field( $featimgmarginbottom ) {
		// regex: any Digit, then either 'px' or '%'
		if ( preg_match( '/(\d*)(px|%)/', $featimgmarginbottom ) ) {
			return $featimgmarginbottom;
		}
	}

	/**
	 * Sanitize the text 'textpaddingtop' value before being saved to database
	 *
	 * @param  string $textpaddingtop $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_textpaddingtop_field( $textpaddingtop ) {
		// regex: any Digit, then either 'px' or '%'
		if ( preg_match( '/(\d*)(px|%)/', $textpaddingtop ) ) {
			return $textpaddingtop;
		}
	}

	/**
	 * Sanitize the text 'textpaddingbottom' value before being saved to database
	 *
	 * @param  string $textpaddingbottom $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_textpaddingbottom_field( $textpaddingbottom ) {
		// regex: any Digit, then either 'px' or '%'
		if ( preg_match( '/(\d*)(px|%)/', $textpaddingbottom ) ) {
			return $textpaddingbottom;
		}
	}

	/**
	 * Sanitize the text 'quotemarginbottom' value before being saved to database
	 *
	 * @param  string $quotemarginbottom $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_quotemarginbottom_field( $quotemarginbottom ) {
		// regex: any Digit, then either 'px' or '%'
		if ( preg_match( '/(\d*)(px|%)/', $quotemarginbottom ) ) {
			return $quotemarginbottom;
		}
	}

	/**
	 * Sanitize the text 'dotsmargintop' value before being saved to database
	 *
	 * @param  string $dotsmargintop $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_dotsmargintop_field( $dotsmargintop ) {
		// regex: any Digit, then either 'px' or '%'
		if ( preg_match( '/(\d*)(px|%)/', $dotsmargintop ) ) {
			return $dotsmargintop;
		}
	}

	/**
	 * Sanitize the text 'imgborderradius' value before being saved to database
	 *
	 * @param  string $imgborderradius $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_imgborderradius_field( $imgborderradius ) {

		// Allow '0' as a value
		if ( (string) $imgborderradius === '0' ) {
			return '0';
		} else {
			// regex: any Digit, then either 'px' or '%'
			if ( preg_match( '/(\d*)(px|%)/', $imgborderradius ) ) {
				return $imgborderradius;
			}
		}

	}

	/**
	 * Sanitize the text 'imgbordercolor' value before being saved to database
	 *
	 * @param  string $imgbordercolor $_POST value
	 * @since  2.0.8
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_imgbordercolor_field( $imgbordercolor ) {
		if ( ! empty ( $imgbordercolor ) ) {
			if ( preg_match( '/^#[a-f0-9]{6}$/i', $imgbordercolor ) ) {
				return $imgbordercolor;
			} else{
				return '';
			}
		}
	}

	/**
	 * Sanitize the text 'imgborderthickness' value before being saved to database
	 *
	 * @param  string $imgborderthickness $_POST value
	 * @since  2.0.8
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_imgborderthickness_field( $imgborderthickness ) {

		// regex: any Digit, then either 'px' or '%'
		if ( preg_match( '/(\d*)(px|%)/', $imgborderthickness ) ) {
			return $imgborderthickness;
		}

	}

	/**
	 * Sanitize the text 'imgborderpadding' value before being saved to database
	 *
	 * @param  string $imgborderpadding $_POST value
	 * @since  2.0.8
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_imgborderpadding_field( $imgborderpadding ) {

		// Allow '0' as a value
		if ( (string) $imgborderpadding === '0' ) {
			return '0';
		} else {
			// regex: any Digit, then either 'px' or '%'
			if ( preg_match( '/(\d*)(px|%)/', $imgborderpadding ) ) {
				return $imgborderpadding;
			}
		}

	}

	/**
	 * Sanitize the text 'bgcolor' value before being saved to database
	 *
	 * @param  string $bgcolor $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_bgcolor_field( $bgcolor ) {
		if ( ! empty ( $bgcolor ) ) {
			if ( preg_match( '/^#[a-f0-9]{6}$/i', $bgcolor ) ) {
				return $bgcolor;
			} else{
				return '';
			}
		}
	}

	/**
	 * Sanitize the text 'textalign' value before being saved to database
	 *
	 * @param  string $textalign $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_textalign_field( $textalign ) {
		if ( in_array( $textalign, array( 'align_left', 'align_center', 'align_right' ), true ) ) {
			return $textalign;
		}
	}

	/**
	 * Sanitize the text 'textcolor' value before being saved to database
	 *
	 * @param  string $textcolor $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_textcolor_field( $textcolor ) {
		if ( ! empty ( $textcolor ) ) {
			if ( preg_match( '/^#[a-f0-9]{6}$/i', $textcolor ) ) {
				return $textcolor;
			} else{
				return '#333333';
			}
		}
	}

	/**
	 * Sanitize the text 'arrowiconstyle' value before being saved to database
	 *
	 * @param  string $arrowiconstyle $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_arrowiconstyle_field( $arrowiconstyle ) {
		if ( in_array( $arrowiconstyle, array(
			'style_zero',
			'style_one',
			'style_two',
			'style_three',
			'style_four',
			'style_five',
			'style_six',
			'style_seven',
			'style_eight'
			), true ) ) {
			return $arrowiconstyle;
		}
	}

	/**
	 * Sanitize the text 'arrowcolor' value before being saved to database
	 *
	 * @param  string $arrowcolor $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_arrowcolor_field( $arrowcolor ) {
		if ( ! empty ( $arrowcolor ) ) {
			if ( preg_match( '/^#[a-f0-9]{6}$/i', $arrowcolor ) ) {
				return $arrowcolor;
			} else{
				return '#000000';
			}
		}
	}

	/**
	 * Sanitize the text 'arrowhovercolor' value before being saved to database
	 *
	 * @param  string $arrowhovercolor $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_arrowhovercolor_field( $arrowhovercolor ) {
		if ( ! empty ( $arrowhovercolor ) ) {
			if ( preg_match( '/^#[a-f0-9]{6}$/i', $arrowhovercolor ) ) {
				return $arrowhovercolor;
			} else{
				return '#999999';
			}
		}
	}

	/**
	 * Sanitize the text 'dotscolor' value before being saved to database
	 *
	 * @param  string $dotscolor $_POST value
	 * @since  2.0.0
	 * @return string           Sanitized value
	 */
	public function social_proof_slider_sanitize_dotscolor_field( $dotscolor ) {
		if ( ! empty ( $dotscolor ) ) {
			if ( preg_match( '/^#[a-f0-9]{6}$/i', $dotscolor ) ) {
				return $dotscolor;
			} else{
				return '#666666';
			}
		}
	}

	/**
	 * Sanitize a number value before being saved to database
	 *
	 * @param  string $number $_POST value
	 * @since  2.2.4
	 * @return string Sanitized value
	 */
	public function social_proof_slider_sanitize_number_field( $number ) {
		if ( empty( $number ) ) {
			return;
		}
		$newnumber = intval( $number );
		return $newnumber;
	}

	/* ==================== REGISTER FIELDS ==================== */

	/**
	* Register settings area, fields, and individual settings
	*
	* @since 2.0.0
	*/
	public function register_setting(){

		// Add a General Settings section
		add_settings_section(
			$this->option_name . '_general',
			__( 'Shortcode Settings', 'social-proof-slider' ),
			array( $this, $this->option_name . '_general_settings_section_cb' ),
			$this->plugin_name
		);

		// Add some fields

		/*
		`Sort By - select
		`Auto-Play - checkbox
		`Display Time - text
		`Animation Style - select
		`Auto-Height - checkbox
		`Vertical Alignment - radio icons`
		`Padding Override - checkbox
		`Padding-Top - text
		`Padding-Bottom - text
		`Show Featured Image - checkbox
		`Featured Image Border Radius - text
		`Show Featured Image Border - checkbox
		`Image Border Color - color
		`Image Border Thickness - text
		`Image Border Padding - text
		`Background Color - color
		`Add Quote Marks - checkbox
		`Text Alignment - radio icons
		`Text Color - color
		`Show Arrows - checkbox
		`Arrow Icons - radio icons
		`Arrow Color - color
		`Arrow hover color - color
		`Show Navigation Dots - checkbox
		`Navigation Dots Color - color
		*/

		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );

		add_settings_field(
			$this->option_name . '_sortby',
			__( 'Sort By', 'social-proof-slider' ),
			array( $this, $this->option_name . '_sortby_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> 'Choose how the testimonials are arranged.',
				'id' 			=> $this->option_name . '_sortby',
				'value' 		=> 'rand',
			)
		);

		add_settings_field(
			$this->option_name . '_autoplay',
			__( 'Auto-Play', 'social-proof-slider' ),
			array( $this, $this->option_name . '_autoplay_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Automatically advance to the next testimonial.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_autoplay',
				'value' 		=> '1'
			)
		);

		add_settings_field(
			$this->option_name . '_displaytime',
			__( 'Display Time', 'social-proof-slider' ),
			array( $this, $this->option_name . '_displaytime_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Length of time (milliseconds) to show each testimonial.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_displaytime',
				'value' 		=> '3000'
			)
		);

		add_settings_field(
			$this->option_name . '_animationstyle',
			__( 'Animation Style', 'social-proof-slider' ),
			array( $this, $this->option_name . '_animationstyle_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Choose how the testimonials are animated.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_animationstyle',
				'value' 		=> 'fade',
			)
		);

		add_settings_field(
			$this->option_name . '_autoheight',
			__( 'Variable Height', 'social-proof-slider' ),
			array( $this, $this->option_name . '_autoheight_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Automatically adjust the height for each testimonial, or have a fixed height for the entire slider.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_autoheight',
				'value' 		=> '1'
			)
		);

		add_settings_field(
			$this->option_name . '_verticalalign',
			__( 'Vertical Alignment', 'social-proof-slider' ),
			array( $this, $this->option_name . '_verticalalign_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> 'Choose the vertical alignment of the testimonials.',
				'id' 			=> $this->option_name . '_verticalalign',
				'value' 		=> 'align_top'
			)
		);

		// ----------------------------------------------------------

		add_settings_field(
			$this->option_name . '_paddingoverride',
			__( 'Padding Override', 'social-proof-slider' ),
			array( $this, $this->option_name . '_paddingoverride_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Override the default content area CSS padding.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_paddingoverride',
				'value' 		=> '0'
			)
		);

		add_settings_field(
			$this->option_name . '_contentpaddingtop',
			__( 'Content Padding Top', 'social-proof-slider' ),
			array( $this, $this->option_name . '_contentpaddingtop_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Enter the value for the content area padding-top.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_contentpaddingtop',
				'value' 		=> '20'
			)
		);

		add_settings_field(
			$this->option_name . '_contentpaddingbottom',
			__( 'Content Padding Bottom', 'social-proof-slider' ),
			array( $this, $this->option_name . '_contentpaddingbottom_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Enter the value for the content area padding-bottom.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_contentpaddingbottom',
				'value' 		=> '20'
			)
		);

		add_settings_field(
			$this->option_name . '_featimgmargintop',
			__( 'Featured Image Margin Top', 'social-proof-slider' ),
			array( $this, $this->option_name . '_featimgmargintop_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Enter the value for the Featured Image margin-top.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_featimgmargintop',
				'value' 		=> '20'
			)
		);

		add_settings_field(
			$this->option_name . '_featimgmarginbottom',
			__( 'Featured Image Margin Bottom', 'social-proof-slider' ),
			array( $this, $this->option_name . '_featimgmarginbottom_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Enter the value for the Featured Image margin-bottom.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_featimgmarginbottom',
				'value' 		=> '20'
			)
		);

		add_settings_field(
			$this->option_name . '_textpaddingtop',
			__( 'Testimonial Text Padding Top', 'social-proof-slider' ),
			array( $this, $this->option_name . '_textpaddingtop_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Enter the value for the Testimonial Text padding-top.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_textpaddingtop',
				'value' 		=> '50'
			)
		);

		add_settings_field(
			$this->option_name . '_textpaddingbottom',
			__( 'Testimonial Text Padding Bottom', 'social-proof-slider' ),
			array( $this, $this->option_name . '_textpaddingbottom_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Enter the value for the Testimonial Text padding-bottom.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_textpaddingbottom',
				'value' 		=> '50'
			)
		);

		add_settings_field(
			$this->option_name . '_quotemarginbottom',
			__( 'Quote Text Margin Bottom', 'social-proof-slider' ),
			array( $this, $this->option_name . '_quotemarginbottom_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Enter the value for the Quote Text margin-bottom.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_quotemarginbottom',
				'value' 		=> '30'
			)
		);

		add_settings_field(
			$this->option_name . '_dotsmargintop',
			__( 'Dots Margin Top', 'social-proof-slider' ),
			array( $this, $this->option_name . '_dotsmargintop_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Enter the value for the Dots margin-top.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_dotsmargintop',
				'value' 		=> '10'
			)
		);

		// ----------------------------------------------------------

		add_settings_field(
			$this->option_name . '_showfeaturedimg',
			__( 'Show Featured Image', 'social-proof-slider' ),
			array( $this, $this->option_name . '_showfeaturedimg_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Show the Featured Image for each testimonial.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_showfeaturedimg',
				'value' 		=> '1'
			)
		);

		add_settings_field(
			$this->option_name . '_imgborderradius',
			__( 'Image Border Radius', 'social-proof-slider' ),
			array( $this, $this->option_name . '_imgborderradius_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Enter the border radius for the Featured Images.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_imgborderradius',
				'value' 		=> '0'
			)
		);

		add_settings_field(
			$this->option_name . '_showimgborder',
			__( 'Show Image Border', 'social-proof-slider' ),
			array( $this, $this->option_name . '_showimgborder_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Show a border around the Featured Image.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_showimgborder',
				'value' 		=> '0'
			)
		);

		add_settings_field(
			$this->option_name . '_imgbordercolor',
			__( 'Border Color', 'social-proof-slider' ),
			array( $this, $this->option_name . '_imgbordercolor_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Choose a color for the border around the featured image.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_imgbordercolor',
				'value' 		=> ''
			)
		);

		add_settings_field(
			$this->option_name . '_imgborderthickness',
			__( 'Border Thickness', 'social-proof-slider' ),
			array( $this, $this->option_name . '_imgborderthickness_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Enter the thickness of the border around the Featured Image.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_imgborderthickness',
				'value' 		=> '2'
			)
		);

		add_settings_field(
			$this->option_name . '_imgborderpadding',
			__( 'Border Padding', 'social-proof-slider' ),
			array( $this, $this->option_name . '_imgborderpadding_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Enter the padding between the border and the Featured Image.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_imgborderpadding',
				'value' 		=> '0'
			)
		);

		add_settings_field(
			$this->option_name . '_bgcolor',
			__( 'Background Color', 'social-proof-slider' ),
			array( $this, $this->option_name . '_bgcolor_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Choose a background color for the testimonials slider area. Leave empty for transparent.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_bgcolor',
				'value' 		=> ''
			)
		);

		add_settings_field(
			$this->option_name . '_surroundquotes',
			__( 'Add Quote Marks', 'social-proof-slider' ),
			array( $this, $this->option_name . '_surroundquotes_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Surround testimonial text with smart quote marks.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_surroundquotes',
				'value' 		=> ''
			)
		);

		add_settings_field(
			$this->option_name . '_textalign',
			__( 'Text Alignment', 'social-proof-slider' ),
			array( $this, $this->option_name . '_textalign_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> 'Choose the text alignment of the testimonial text.',
				'id' 			=> $this->option_name . '_textalign',
				'value' 		=> 'align_center',
			)
		);

		add_settings_field(
			$this->option_name . '_textcolor',
			__( 'Text Color', 'social-proof-slider' ),
			array( $this, $this->option_name . '_textcolor_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Choose the text color for the testimonial content.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_textcolor',
				'value' 		=> '#333333'
			)
		);

		add_settings_field(
			$this->option_name . '_showarrows',
			__( 'Show Arrows', 'social-proof-slider' ),
			array( $this, $this->option_name . '_showarrows_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Show the previous/next arrows.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_showarrows',
				'value' 		=> '1'
			)
		);

		add_settings_field(
			$this->option_name . '_arrowiconstyle',
			__( 'Arrow Icons', 'social-proof-slider' ),
			array( $this, $this->option_name . '_arrowiconstyle_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> 'Choose the style of the previous/next arrows.',
				'id' 			=> $this->option_name . '_arrowiconstyle',
				'value' 		=> 'style_zero',
			)
		);

		add_settings_field(
			$this->option_name . '_arrowcolor',
			__( 'Arrow Color', 'social-proof-slider' ),
			array( $this, $this->option_name . '_arrowcolor_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Choose the color of the previous/next arrows.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_arrowcolor',
				'value' 		=> '#000000'
			)
		);

		add_settings_field(
			$this->option_name . '_arrowhovercolor',
			__( 'Arrow Hover Color', 'social-proof-slider' ),
			array( $this, $this->option_name . '_arrowhovercolor_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Choose the hover color of the previous/next arrows.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_arrowhovercolor',
				'value' 		=> '#999999'
			)
		);

		add_settings_field(
			$this->option_name . '_showdots',
			__( 'Show Navigation Dots', 'social-proof-slider' ),
			array( $this, $this->option_name . '_showdots_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Show the navigation dots below the testimonials.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_showdots',
				'value' 		=> '1'
			)
		);

		add_settings_field(
			$this->option_name . '_dotscolor',
			__( 'Navigation Dots Color', 'social-proof-slider' ),
			array( $this, $this->option_name . '_dotscolor_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' 	=> __( 'Choose the color of the navigation dots.', 'social-proof-slider' ),
				'id' 			=> $this->option_name . '_dotscolor',
				'value' 		=> '#666666'
			)
		);

		// Register and Sanitize the fields

		// register_setting( $option_group, $option_name, $sanitize_callback );

		register_setting( $this->plugin_name, $this->option_name . '_sortby', array( $this, $this->option_name . '_sanitize_sortby_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_autoplay' );
		register_setting( $this->plugin_name, $this->option_name . '_displaytime', array( $this, $this->option_name . '_sanitize_number_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_animationstyle', array( $this, $this->option_name . '_sanitize_animationstyle_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_autoheight', array( $this, $this->option_name . '_sanitize_autoheight_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_verticalalign', array( $this, $this->option_name . '_sanitize_verticalalign_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_paddingoverride' );
		register_setting( $this->plugin_name, $this->option_name . '_contentpaddingtop', array( $this, $this->option_name . '_sanitize_number_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_contentpaddingbottom', array( $this, $this->option_name . '_sanitize_number_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_featimgmargintop', array( $this, $this->option_name . '_sanitize_number_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_featimgmarginbottom', array( $this, $this->option_name . '_sanitize_number_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_textpaddingtop', array( $this, $this->option_name . '_sanitize_number_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_textpaddingbottom', array( $this, $this->option_name . '_sanitize_number_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_quotemarginbottom', array( $this, $this->option_name . '_sanitize_number_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_dotsmargintop', array( $this, $this->option_name . '_sanitize_number_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_showfeaturedimg' );
		register_setting( $this->plugin_name, $this->option_name . '_imgborderradius', array( $this, $this->option_name . '_sanitize_number_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_showimgborder' );
		register_setting( $this->plugin_name, $this->option_name . '_imgbordercolor', array( $this, $this->option_name . '_sanitize_imgbordercolor_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_imgborderthickness', array( $this, $this->option_name . '_sanitize_number_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_imgborderpadding', array( $this, $this->option_name . '_sanitize_number_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_bgcolor', array( $this, $this->option_name . '_sanitize_bgcolor_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_surroundquotes' );
		register_setting( $this->plugin_name, $this->option_name . '_textalign', array( $this, $this->option_name . '_sanitize_textalign_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_textcolor', array( $this, $this->option_name . '_sanitize_textcolor_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_showarrows' );
		register_setting( $this->plugin_name, $this->option_name . '_arrowiconstyle', array( $this, $this->option_name . '_sanitize_arrowiconstyle_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_arrowcolor', array( $this, $this->option_name . '_sanitize_arrowcolor_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_arrowhovercolor', array( $this, $this->option_name . '_sanitize_arrowhovercolor_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_showdots' );
		register_setting( $this->plugin_name, $this->option_name . '_dotscolor', array( $this, $this->option_name . '_sanitize_dotscolor_field' ) );
	}

}
