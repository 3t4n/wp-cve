<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://atikul99.github.io/atikul
 * @since      1.0.0
 *
 * @package    Ai_Preloader
 * @subpackage Ai_Preloader/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ai_Preloader
 * @subpackage Ai_Preloader/admin
 * @author     Atikul Islam <atikulislam94@gmail.com>
 */
class Ai_Preloader_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'admin_menu', array( $this, 'register_my_custom_menu_page' ) );
		
		add_action( 'admin_init', array( $this, 'display_options' ) );

	}

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
		 * defined in Ai_Preloader_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ai_Preloader_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ai-preloader-admin.css', array(), $this->version, 'all' );

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
		 * defined in Ai_Preloader_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ai_Preloader_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'picker-alpha', plugin_dir_url( __FILE__ ) . 'js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), $this->version, false );
		wp_enqueue_script( 'picker', plugin_dir_url( __FILE__ ) . 'js/color-picker.js', array( 'wp-color-picker' ), $this->version, false );

		wp_enqueue_media();

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ai-preloader-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register a custom menu page.
	 */
	function register_my_custom_menu_page() {
		add_menu_page(
			__( 'AI Preloader', 'ai-preloader' ),
			'AI Preloader',
			'manage_options',
			'ai-menu-page',
			array( $this, 'myAdminPage' ),
			'dashicons-marker',
			6
		);
	}

	public function myAdminPage() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/admin-menu-page.php';
	}

    /* WordPress Settings API */

    function display_options()
    {
        //section name, display name, callback to print description of section, page to which section is attached.
        add_settings_section("header_section", "", array( $this, 'display_header_options_content' ), "ai-menu-page");

        //setting name, display name, callback to print form element, page in which field is displayed, section to which it belongs.
        //last field section is optional.
        add_settings_field("demo-select", "Preloader Style", array( $this, 'demo_select_display' ), "ai-menu-page", "header_section");
        add_settings_field("loader_bg", "Preloader BG", array( $this, 'loader_bg_field_callback' ), "ai-menu-page", "header_section");
        add_settings_field("primary_color", "Primary Color", array( $this, 'primary_color_field_callback' ), "ai-menu-page", "header_section");
        add_settings_field("loader_image", "Preloader Image", array( $this, 'loader_img_field_callback' ), "ai-menu-page", "header_section");
        add_settings_field("display_on", "Display Preloader", array( $this, 'display_on_field_callback' ), "ai-menu-page", "header_section");

        //section name, form element name, callback for sanitization
        register_setting("header_section", "demo-select");
        register_setting("header_section", "loader_bg");
        register_setting("header_section", "primary_color");
        register_setting("header_section", "loader_image");
        register_setting("header_section", "display_on");
    }

    function display_header_options_content(){
    	//echo "The header of the theme";
    }
    
	function demo_select_display(){
		?>
		<select name="demo-select" class="regular-text">
			<option>Select Style</option>
			<option value="style1" <?php selected(get_option('demo-select'), "style1"); ?>>Style One</option>
			<option value="style2" <?php selected(get_option('demo-select'), "style2"); ?>>Style Two</option>
			<option value="style3" <?php selected(get_option('demo-select'), "style3"); ?>>Style Three</option>
			<option value="style4" <?php selected(get_option('demo-select'), "style4"); ?>>Style Four</option>
			<option value="style5" <?php selected(get_option('demo-select'), "style5"); ?>>Style Five</option>
			<option value="style6" <?php selected(get_option('demo-select'), "style6"); ?>>Style Six</option>
			<option value="style7" <?php selected(get_option('demo-select'), "style7"); ?>>Style Seven</option>
		</select>
		<p class="description">Choose CSS loader style here.</p>
		<?php
	}

	function loader_bg_field_callback() { 

		echo '<input type="text" name="loader_bg" value="' . get_option('loader_bg') . '" class="cpa-color-picker" data-alpha-enabled="true" data-default-color="rgba(17, 17, 17, 1)" >';
		echo '<p class="description">Choose background color, default color is #111.</p>';
	}

	function primary_color_field_callback() { 

		echo '<input type="text" name="primary_color" value="' . get_option('primary_color') . '" class="cpa-color-picker" data-alpha-enabled="true" data-default-color="rgb(238, 64, 64)" >';
		echo '<p class="description">Choose primary color, default color is #EE4040.</p>';
	}

	function loader_img_field_callback(){

		$picture = esc_attr( get_option('loader_image') );

		if( empty($picture) ){
			?>
			<input type="button" class="button button-secondary" value="Upload Picture" id="ai-upload-btn" />
			<input type="hidden" id="profile-picture" name="loader_image" value="" />
			<p class="description">Preloader Image will override the CSS loader.</p>
			<div id="preview-img" style="line-height: 0;">
				<img src="<?php echo esc_url($picture); ?>" alt="">
			</div>
			<?php
		} else {
			?>
			<input type="button" class="button button-secondary" value="Upload Picture" id="ai-upload-btn" />
			<input type="hidden" id="profile-picture" name="loader_image" value="<?php echo esc_url($picture); ?>" />
			<input type="button" class="button button-secondary" value="Remove" id="ai-remove-btn" />
			<p class="description">Preloader Image will override the CSS loader</p>
			<div id="preview-img" style="margin-top:20px">
				<img src="<?php echo esc_url($picture); ?>" alt="preview-img">
			</div>
			<?php
		}
	}

	function display_on_field_callback(){

		$display_preloader = get_option( 'display_on' );

		?>

		<fieldset>
			<label title="Display Preloader in full website like home page, posts, pages, categories, tags, attachment, etc..">
				<input type="radio" name="display_on" value="full" <?php checked( $display_preloader, 'full' ); ?>>In The Entire Website.
			</label>
			<br>
			<label title="Display Preloader in home page">
				<input type="radio" name="display_on" value="homepage" <?php checked( $display_preloader, 'homepage' ); ?>>In Home Page only.
			</label>
			<br>
			<label title="Display Preloader in front page">
				<input type="radio" name="display_on" value="frontpage" <?php checked( $display_preloader, 'frontpage' ); ?>>In Front Page only.
			</label>
			<br>
			<label title="Display Preloader in posts only">
				<input type="radio" name="display_on" value="posts" <?php checked( $display_preloader, 'posts' ); ?>>In Posts only.
			</label>
			<br>
			<label title="Display Preloader in pages only">
				<input type="radio" name="display_on" value="pages" <?php checked( $display_preloader, 'pages' ); ?>>In Pages only.
			</label>
			<br>
			<label title="Display Preloader in categories only">
				<input type="radio" name="display_on" value="cats" <?php checked( $display_preloader, 'cats' ); ?>>In Categories only.
			</label>
			<br>
			<label title="Display Preloader in tags only">
				<input type="radio" name="display_on" value="tags" <?php checked( $display_preloader, 'tags' ); ?>>In Tags only.
			</label>
			<br>
			<label title="Display Preloader in attachment only">
				<input type="radio" name="display_on" value="attachment" <?php checked( $display_preloader, 'attachment' ); ?>>In Attachment only.
			</label>
			<br>
			<label title="Display Preloader in 404 error page">
				<input type="radio" name="display_on" value="404error" <?php checked( $display_preloader, '404error' ); ?>>In 404 Error Page only.
			</label>
		</fieldset>

		<?php
	}


}
