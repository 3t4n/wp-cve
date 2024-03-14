<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cyberfoxdigital.co.uk
 * @since      1.0.0
 *
 * @package    Cf_Christmasification
 * @subpackage Cf_Christmasification/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cf_Christmasification
 * @subpackage Cf_Christmasification/admin
 * @author     Cyber Fox <info@cyberfoxdigital.co.uk>
 */
class Cf_Christmasification_Admin {

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

		$this->initialise();
	}

	public function initialise(){
		add_action('admin_menu', function(){
			add_menu_page( __('Christmasify', 'christmasify'), __('Christmasify!', 'christmasify'), 'manage_options', 'cf-christmasification-admin', array($this, 'options'), 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iMzcuNzgzcHgiIGhlaWdodD0iMzcuNzgzcHgiIHZpZXdCb3g9IjAgMCAzNy43ODMgMzcuNzgzIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAzNy43ODMgMzcuNzgzOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+PHBhdGggZD0iTTM0Ljk3MiwyNy4yODFsLTIuNzg5LTEuNjA5bDEuNTI1LTAuODgzYzAuMzczLTAuMjE2LDAuNTAxLTAuNjkzLDAuMjg1LTEuMDY2Yy0wLjIxNy0wLjM3My0wLjY5NC0wLjQ5OS0xLjA2Ny0wLjI4M2wtMi4zMDUsMS4zM2wtMS44OTYtMS4wOTZsNC41MzYtMi42MThjMC4zNzItMC4yMTYsMC41LTAuNjkxLDAuMjg1LTEuMDY2Yy0wLjIxNy0wLjM3MS0wLjY5NS0wLjUwMS0xLjA2Ni0wLjI4M2wtNS4zMTgsMy4wNjdsLTEuMjM4LTAuNzE3YzAuNDM1LTAuOTY0LDAuNjg0LTIuMDMyLDAuNjg0LTMuMTU3YzAtMS4xMjgtMC4yNDktMi4xOTQtMC42ODUtMy4xNjFsMS4yMzgtMC43MTZsNS4zMTgsMy4wN2MwLjEyMywwLjA3MSwwLjI1NywwLjEwNiwwLjM5MSwwLjEwNmMwLjI3MSwwLDAuNTMzLTAuMTQxLDAuNjc3LTAuMzkxYzAuMjE1LTAuMzczLDAuMDg3LTAuODUxLTAuMjg1LTEuMDY2bC00LjUzOC0yLjYyMmwxLjg5OC0xLjA5NWwyLjMwNywxLjMzM2MwLjEyMywwLjA3MSwwLjI1OCwwLjEwNiwwLjM5MSwwLjEwNmMwLjI3LDAsMC41MzItMC4xNDEsMC42NzctMC4zOTFjMC4yMTYtMC4zNzMsMC4wODgtMC44NTItMC4yODUtMS4wNjdsLTEuNTI2LTAuODgybDIuNzg4LTEuNjExYzAuMzczLTAuMjE2LDAuNTAxLTAuNjk0LDAuMjg1LTEuMDY2Yy0wLjIxNi0wLjM3Mi0wLjY5NS0wLjUtMS4wNjYtMC4yODRsLTIuNzg3LDEuNjF2LTEuNzZjMC0wLjQzMS0wLjM1MS0wLjc4MS0wLjc4MS0wLjc4MWMtMC40MzIsMC0wLjc4MSwwLjM1LTAuNzgxLDAuNzgxbDAuMDAxLDIuNjYzbC0xLjg5NiwxLjA5NWwwLjAwMS01LjIzNWMwLTAuNDMyLTAuMzQ5LTAuNzgxLTAuNzgxLTAuNzgxYy0wLjQzMSwwLTAuNzgxLDAuMzUtMC43ODEsMC43ODFsLTAuMDAxLDYuMTM5TDI1LjE0LDE0LjM5Yy0xLjI1OS0xLjczOS0zLjIxOS0yLjkzOS01LjQ2Ny0zLjE2N1Y5Ljc5N2w1LjMxNi0zLjA3YzAuMzczLTAuMjE1LDAuNTAxLTAuNjkzLDAuMjg1LTEuMDY2Yy0wLjIxNi0wLjM3NC0wLjY5NC0wLjUwMS0xLjA2Ni0wLjI4NWwtNC41MzUsMi42MjFWNS44MDhsMi4zMDYtMS4zMzFjMC4zNzQtMC4yMTYsMC41LTAuNjk0LDAuMjg1LTEuMDY2Yy0wLjIxNy0wLjM3Mi0wLjY5NS0wLjUwMi0xLjA2Ni0wLjI4NWwtMS41MjUsMC44NzlWMC43ODFDMTkuNjcyLDAuMzUsMTkuMzIyLDAsMTguODkxLDBTMTguMTEsMC4zNSwxOC4xMSwwLjc4MXYzLjIyMWwtMS41MjQtMC44ODFjLTAuMzc0LTAuMjE2LTAuODUxLTAuMDg4LTEuMDY3LDAuMjg0Yy0wLjIxNiwwLjM3Mi0wLjA4OCwwLjg1LDAuMjg1LDEuMDY2bDIuMzA2LDEuMzMzdjIuMTg4bC00LjUzNi0yLjYxOWMtMC4zNzItMC4yMTUtMC44NTEtMC4wODgtMS4wNjYsMC4yODZjLTAuMjE2LDAuMzczLTAuMDg3LDAuODUxLDAuMjg1LDEuMDY2bDUuMzE3LDMuMDY5djEuNDI3Yy0yLjI0OCwwLjIyNy00LjIwOCwxLjQyOC01LjQ2NywzLjE2N2wtMS4yNDYtMC43MThWNy41MjljMC0wLjQzMS0wLjM1LTAuNzgxLTAuNzgxLTAuNzgxcy0wLjc4MSwwLjM1LTAuNzgxLDAuNzgxdjUuMjM3bC0xLjg5NC0xLjA5NUw3Ljk0LDkuMDA2YzAtMC40MzEtMC4zNS0wLjc4MS0wLjc4MS0wLjc4MXMtMC43ODEsMC4zNS0wLjc4MSwwLjc4MXYxLjc2TDMuNTg5LDkuMTU4QzMuMjE2LDguOTQyLDIuNzM4LDkuMDcsMi41MjIsOS40NDJzLTAuMDg3LDAuODUxLDAuMjg1LDEuMDY1bDIuNzg5LDEuNjEybC0xLjUyNSwwLjg4Yy0wLjM3NCwwLjIxNi0wLjUwMSwwLjY5NC0wLjI4NiwxLjA2N2MwLjE0NSwwLjI1LDAuNDA4LDAuMzkxLDAuNjc3LDAuMzkxYzAuMTMyLDAsMC4yNjctMC4wMzMsMC4zOTEtMC4xMDZsMi4zMDUtMS4zM2wxLjg5NiwxLjA5NWwtNC41MzcsMi42MTljLTAuMzc0LDAuMjE2LTAuNTAxLDAuNjk0LTAuMjg1LDEuMDY3YzAuMTQ1LDAuMjUsMC40MDcsMC4zOTEsMC42NzcsMC4zOTFjMC4xMzIsMCwwLjI2Ni0wLjAzNCwwLjM5MS0wLjEwNmw1LjMxNi0zLjA3bDEuMjQzLDAuNzE3Yy0wLjQzNiwwLjk2Ni0wLjY4MywyLjAzNC0wLjY4MywzLjE2YzAsMS4xMjgsMC4yNDgsMi4xOSwwLjY4MywzLjE1N2wtMS4yNDEsMC43MTdsLTUuMzItMy4wNjVjLTAuMzczLTAuMjE2LTAuODUxLTAuMDg4LTEuMDY2LDAuMjg0Yy0wLjIxNiwwLjM3NC0wLjA4NiwwLjg1MiwwLjI4NywxLjA2N2w0LjUzNiwyLjYxN2wtMS44OTcsMS4wOTdsLTIuMzA0LTEuMzMzYy0wLjM3My0wLjIxMy0wLjg1MS0wLjA4Ny0xLjA2NywwLjI4NWMtMC4yMTYsMC4zNzMtMC4wODgsMC44NTIsMC4yODUsMS4wNjdsMS41MjUsMC44ODJMMi44MSwyNy4yNzdjLTAuMzc0LDAuMjE2LTAuNTAxLDAuNjkzLTAuMjg1LDEuMDY1YzAuMTQ1LDAuMjUsMC40MDcsMC4zOTEsMC42NzcsMC4zOTFjMC4xMzIsMCwwLjI2Ni0wLjAzMiwwLjM5MS0wLjEwN2wyLjc4OS0xLjYwOWwwLjAwMSwxLjc2MmMwLDAuNDMyLDAuMzUsMC43ODEsMC43ODEsMC43ODFsMCwwYzAuNDMxLDAsMC43ODEtMC4zNSwwLjc4MS0wLjc4MWwtMC4wMDItMi42NjVsMS44OTgtMS4wOTVsLTAuMDAxLDUuMjM5YzAsMC40MzEsMC4zNDksMC43ODEsMC43ODEsMC43ODFjMC40MzIsMCwwLjc4MS0wLjM1MSwwLjc4MS0wLjc4MWwwLjAwMi02LjE0M2wxLjI0My0wLjcxN2MxLjI1OCwxLjc0LDMuMjE5LDIuOTQyLDUuNDY4LDMuMTY4djEuNDI2bC01LjMxNSwzLjA2OWMtMC4zNzQsMC4yMTYtMC41MDEsMC42OTMtMC4yODYsMS4wNjZjMC4yMTYsMC4zNzQsMC42OTQsMC41MDIsMS4wNjcsMC4yODZsNC41MzQtMi42MnYyLjE4OWwtMi4zMDYsMS4zM2MtMC4zNzQsMC4yMTYtMC41MDEsMC42OTQtMC4yODUsMS4wNjhjMC4yMTcsMC4zNzEsMC42OTQsMC41MDEsMS4wNjYsMC4yODRsMS41MjUtMC44ODN2My4yMTljMCwwLjQzMiwwLjM1LDAuNzgxLDAuNzgxLDAuNzgxczAuNzgxLTAuMzUsMC43ODEtMC43ODF2LTMuMjIxbDEuNTI4LDAuODg1YzAuMTIyLDAuMDY5LDAuMjU3LDAuMTA2LDAuMzkxLDAuMTA2YzAuMjcsMCwwLjUzMi0wLjE0MiwwLjY3Ny0wLjM5MWMwLjIxNi0wLjM3NCwwLjA4Ny0wLjg1My0wLjI4NS0xLjA2OGwtMi4zMDktMS4zMzJ2LTIuMTg4bDQuNTM2LDIuNjJjMC4xMjMsMC4wNjksMC4yNTgsMC4xMDQsMC4zOTEsMC4xMDRjMC4yNzEsMCwwLjUzMy0wLjE0MSwwLjY3Ny0wLjM5MWMwLjIxNi0wLjM3MywwLjA4OS0wLjg1MS0wLjI4NS0xLjA2NmwtNS4zMTctMy4wNjl2LTEuNDI2YzIuMjUtMC4yMjYsNC4yMDktMS40MjYsNS40NjktMy4xNjhsMS4yNDQsMC43MTd2Ni4xNDNjMCwwLjQzMSwwLjM1LDAuNzgxLDAuNzgxLDAuNzgxYzAuNDMsMCwwLjc4MS0wLjM1MSwwLjc4MS0wLjc4MXYtNS4yMzlsMS44OTQsMS4wOTVsMC4wMDEsMi42NjNjMCwwLjQzMiwwLjM1MSwwLjc4MSwwLjc4MSwwLjc4MWMwLjQzMiwwLDAuNzgxLTAuMzUsMC43ODEtMC43ODF2LTEuNzZsMi43ODgsMS42MTFjMC4xMjQsMC4wNzEsMC4yNTgsMC4xMDcsMC4zOTEsMC4xMDdjMC4yNzEsMCwwLjUzMy0wLjE0MSwwLjY3OC0wLjM5MUMzNS40NzQsMjcuOTc0LDM1LjM0NSwyNy40OTcsMzQuOTcyLDI3LjI4MXogTTE5LjY2OSwyNC45OTljLTAuMjU2LDAuMDMyLTAuNTE2LDAuMDU1LTAuNzgxLDAuMDU1Yy0wLjI2NiwwLTAuNTI1LTAuMDIxLTAuNzgxLTAuMDU1QzE2LjQzNiwyNC43ODUsMTQuOTc2LDIzLjksMTQsMjIuNjIyYy0wLjMxNi0wLjQxMi0wLjU4NC0wLjg2Mi0wLjc4OC0xLjM0OWMtMC4zMDgtMC43My0wLjQ3OC0xLjUzNC0wLjQ3OC0yLjM3NGMwLTAuODQ0LDAuMTcxLTEuNjQ1LDAuNDc5LTIuMzc1YzAuMjA0LTAuNDg2LDAuNDczLTAuOTM1LDAuNzg5LTEuMzVjMC45NzYtMS4yNzgsMi40MzYtMi4xNjEsNC4xMDYtMi4zNzVjMC4yNTctMC4wMzIsMC41MTctMC4wNTYsMC43ODEtMC4wNTZjMC4yNjQsMCwwLjUyNCwwLjAyMiwwLjc4MSwwLjA1NmMxLjY3LDAuMjEzLDMuMTMxLDEuMDk5LDQuMTA3LDIuMzc0YzAuMzE1LDAuNDEzLDAuNTg2LDAuODY0LDAuNzg5LDEuMzQ5YzAuMzA3LDAuNzMxLDAuNDc4LDEuNTM3LDAuNDc4LDIuMzc3YzAsMC44NDItMC4xNzEsMS42NDQtMC40NzcsMi4zNzZjLTAuMjA0LDAuNDg0LTAuNDczLDAuOTM0LTAuNzg5LDEuMzQ5QzIyLjgwMiwyMy45LDIxLjM0MSwyNC43ODUsMTkuNjY5LDI0Ljk5OXoiLz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PC9zdmc+' );
		});

		add_filter( 'plugin_action_links_christmasify/cf-christmasification.php', function( $links ){

			$url = esc_url( add_query_arg(
				'page',
				'cf-christmasification-admin',
				get_admin_url() . 'admin.php'
			) );
			$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
			array_push(
				$links,
				$settings_link
			);

			return $links;

		});

	}

	public function options(){

		if(!empty($_POST)){

			update_option( 'cf_christmasify_snowflakes', 		!empty($_POST['snowflakes']) 										? (int)$_POST['snowflakes'] 		: 0 );
			update_option( 'cf_christmasify_classy_snow', 	!empty($_POST['classy_snow']) 									? (int)$_POST['classy_snow'] 		: 0 );
			update_option( 'cf_christmasify_snow_speed', 	  !empty($_POST['snow_speed']) 									  ?      $_POST['snow_speed'] 		: 'medium' );
			update_option( 'cf_christmasify_santa', 				!empty($_POST['santa']) 												? (int)$_POST['santa'] 				  : 0 );
			update_option( 'cf_christmasify_music', 				!empty($_POST['music']) 												? 		 $_POST['music'] 				  : 0 );
			update_option( 'cf_christmasify_image_frame',		!empty($_POST['image_frame'])										? (int)$_POST['image_frame']		: 0 );
			update_option( 'cf_christmasify_font',					!empty($_POST['font'])													? (int)$_POST['font']					  : 0 );
			update_option( 'cf_christmasify_homepage_only',	!empty($_POST['homepage_only'])									? (int)$_POST['homepage_only']	: 0 );
			update_option( 'cf_christmasify_date_from',			!empty($_POST['date_from'])											? $_POST['date_from']	  				: 0 );
			update_option( 'cf_christmasify_date_to',				!empty($_POST['date_to'])												? $_POST['date_to']	  					: 0 );

		}

		include('partials/cf-christmasification-admin-display.php');
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
		 * defined in Cf_Christmasification_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cf_Christmasification_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cf-christmasification-admin.css', array(), $this->version, 'all' );

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
		 * defined in Cf_Christmasification_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cf_Christmasification_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cf-christmasification-admin.js', array( 'jquery' ), $this->version, false );

	}

}
