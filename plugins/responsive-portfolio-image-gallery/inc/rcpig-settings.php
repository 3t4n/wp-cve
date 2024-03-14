<?php
/*
 *  Responsive Portfolio Image Gallery 1.2
 *  By @realwebcare - https://www.realwebcare.com/
 */
if ( ! defined( 'ABSPATH' ) ) exit;
if ( !class_exists('rcpig_settings_config' ) ):
class rcpig_settings_config {

	private $settings_api;

	function __construct() {
		$this->settings_api = new rcpig_WeDevs_Settings_API;
		add_action( 'admin_init', array($this, 'admin_init') );
		add_action( 'admin_menu', array($this, 'admin_menu') );
	}

	function admin_init() {
		//set the settings
		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );
		//initialize settings
		$this->settings_api->admin_init();
	}

	function admin_menu() {
		add_submenu_page( 'edit.php?post_type=rcpig', __( 'Portfolio Settings','rcpig' ), __( 'Portfolio Settings','rcpig' ), 'delete_posts', 'rcpig-settings', array($this, 'rcpig_plugin_page') );
	}

	// setings tabs
	function get_settings_sections() {
		$sections = array(
			array(
			'id' => 'rcpig_general',
			'title' => __( 'General Settings', 'rcpig' )
			),
			array(
			'id' => 'rcpig_advanced',
			'title' => __( 'Advanced Settings', 'rcpig' )
			),
			array(
			'id' => 'rcpig_style',
			'title' => __( 'Style Settings', 'rcpig' )
			)
		);
		return $sections;
	}

	/**
	* Returns all the settings fields
	*
	* @return array settings fields
	*/
	function get_settings_fields() {
		$settings_fields = array( 
			'rcpig_general' => array(
				array(
					'name'      => 'rcpig_enable_portfolio_',
					'label'     => __( 'Display Portfolio Gallery', 'rcpig' ),
					'desc'      => __( '<p></p>', 'rcpig' ),
					'type'      => 'radio',
					'default'   => 'show',
					'options'   => array(
						'show'  => 'On',
						'hide'  => 'Off'
					),
				),
				array(
					'name'      => 'rcpig_cat_include_',
					'label'     => __( 'Include Taxonomies', 'rcpig' ),
					'desc'      => __( '<p>Select the categories you want to show in your portfolio lists.</p>', 'rcpig' ),
					'type'      => 'multicheck',
					'options'   => rcpig_include_categories(),
				),
				array(
					'name'      => 'rcpig_number_of_post_',
					'label'     => __( 'Number of post', 'rcpig' ),
					'desc'      => __( '<p>Number of post to show. Default -1, means show all.</p>', 'rcpig' ),
					'type'      => 'number',
					'default'   => 5
				),
				array(
					'name'      => 'rcpig_post_order_',
					'label'     => __( 'Post Order', 'rcpig' ),
					'desc'      => __( '<p>Select posts order in</p>', 'rcpig' ),
					'type'      => 'select',
					'default'   => 'DESC',
					'options'   => array(
						'ASC'     => __( 'Ascending', 'rcpig' ),
						'DESC'    => __( 'Descending', 'rcpig' )
					),
				),
				array(
					'name'      => 'rcpig_post_order_by_',
					'label'     => __( 'Post Order By', 'rcpig' ),
					'desc'      => __( '<p>Select posts sorted by</p>', 'rcpig' ),
					'type'      => 'select',
					'default'   => 'date',
					'options'   => array(
						'ID'     => __( 'Post ID', 'rcpig' ),
						'name'    => __( 'Post Name (post slug)', 'rcpig' ),
						'date'    => __( 'Post Date', 'rcpig' )
					),
				),
				array(
					'name'      => 'rcpig_excerpt_length_',
					'label'     => __( 'Enter Excerpt Length', 'rcpig' ),
					'desc'      => __( '<p>Number of words to show for portfolio description.</p>', 'rcpig' ),
					'type'      => 'number',
					'default'   => 30
				),
				array(
					'name'      => 'rcpig_image_width_',
					'label'     => __( 'Portfolio Thumbnail Width', 'rcpig' ),
					'desc'      => __( '<p>Portfolio thumbnail width in Px. Minimum 200. Default 680</p>', 'rcpig' ),
					'type'      => 'number',
					'min'       => 200,
					'default'   => 250
				),
				array(
					'name'      => 'rcpig_image_height_',
					'label'     => __( 'Portfolio Thumbnail Height', 'rcpig' ),
					'desc'      => __( '<p>Portfolio thumbnail height in Px. Minimum 200. Default 680</p>', 'rcpig' ),
					'type'      => 'number',
					'min'       => 200,
					'default'   => 250
				),
			),
			'rcpig_advanced' => array(
				array(
					'name'      => 'rcpig_filter_effect_',
					'label'     => __( 'Filter Effect.', 'rcpig' ),
					'desc'      => __( '<p>Select a filter effect for Mixitup.</p>', 'rcpig' ),
					'type'      => 'select',
					'default'   => 'popup',
					'options'   => array(
						'none'     => 'None',
						'moveup'    => 'Move Up',
						'popup'    => 'Popup',
						'scaleup'   => 'Scale Up',
						'helix'    => 'Helix'
					),
				),
				array(
					'name'      => 'rcpig_hover_direction_',
					'label'     => __( 'Portfolio Hover Direction', 'rcpig' ),
					'desc'      => __( '<p>Enable portfolio hover on mouse hover. Default: On.</p>', 'rcpig' ),
					'type'      => 'radio',
					'default'   => 'true',
					'options'   => array(
						'true'  => 'On',
						'false'  => 'Off'
					),
				),
				array(
					'name'      => 'rcpig_hover_inverse_',
					'label'     => __( 'Portfolio Hover Inverse', 'rcpig' ),
					'desc'      => __( '<p>Portfolio hover in reverse direction on mouse hover. Default: Off.</p>', 'rcpig' ),
					'type'      => 'radio',
					'default'   => 'false',
					'options'   => array(
						'true'  => 'On',
						'false'  => 'Off'
					),
				),
				array(
					'name'      => 'rcpig_hover_effect_',
					'label'     => __( 'Portfolio Hover Effect.', 'rcpig' ),
					'desc'      => __( '<p>Select a hover effect for portfolio images.</p>', 'rcpig' ),
					'type'      => 'select',
					'default'   => 'none',
					'options'   => array(
						'none'		=> 'None',
						'zoompan'	=> 'Zoom and Pan',
						'zoomhide'	=> 'Zoom and Hide',
						'shrink'	=> 'Shrink',
						'slideout'	=> 'Slide Right'
					),
				),
				array(
					'name'      => 'rcpig_hide_excerpt_',
					'label'     => __( 'Hide Portfolio Description', 'rcpig' ),
					'desc'      => __( '<p>Select Yes if want to hide portfolio description in expanding preview.</p>', 'rcpig' ),
					'type'      => 'radio',
					'default'   => 'show',
					'options'   => array(
						'hide'  => 'Yes',
						'show'  => 'No'
					),
				),
				array(
					'name'      => 'rcpig_expanding_height_',
					'label'     => __( 'Portfolio Expanding Height', 'rcpig' ),
					'desc'      => __( '<p>Enter the height of the preview which expand when visitors click on a thumbnail. Minimum 300.</p>', 'rcpig' ),
					'type'      => 'number',
					'min'       => 300,
					'default'   => 500
				),
				array(
					'name'      => 'rcpig_show_wrapper_',
					'label'     => __( 'Display Portfolio Wrapper', 'rcpig' ),
					'desc'      => __( '<p>Select On if want to display carousel images in expanding preview.</p>', 'rcpig' ),
					'type'      => 'radio',
					'default'   => 'show',
					'options'   => array(
						'show'  => 'On',
						'hide'  => 'Off'
					),
				),
				/*array(
					'name'      => 'rcpig_wrapper_height_',
					'label'     => __( 'Portfolio Wrapper Height', 'rcpig' ),
					'desc'      => __( '<p>Enter the height of the wrapper where multiple images will be shown in the preview mode.</p>', 'rcpig' ),
					'type'      => 'number',
					'min'       => 150,
					'default'   => 200
				),*/
			),
			'rcpig_style' => array(
				array(
					'name'      => 'rcpig_css_style_',
					'label'     => __( 'Select Portfolio Style', 'rcpig' ),
					'desc'      => __( '<p>Select a style for portfolio color scheme.</p>', 'rcpig' ),
					'type'      => 'select',
					'default'   => 'dark',
					'options'   => array(
						'dark'	=> 'Dark',
						'light'	=> 'Light'
					),
				),
				array(
					'name' => 'rcpig_custom_css_',
					'label' => __( 'Portfolio Custom CSS', 'rcpig' ),
					'desc' => __( 'You can write you own custom css code here.</p>', 'rcpig' ),
					'type' => 'textarea',
					'rows' => 8
				),
			),
		);
		return $settings_fields;
	}

	// warping the settings
	function rcpig_plugin_page() { ?>
		<?php do_action ( 'rcpig_before_settings' ); ?>
		<div class="rcpig_settings_area">
			<div class="wrap rcpig_settings"><?php
				$this->settings_api->show_navigation();
				$this->settings_api->show_forms(); ?>
			</div>
			<div class="rcpig_settings_content">
				<?php do_action ( 'rcpig_settings_content' ); ?>
			</div>
		</div>
		<?php do_action ( 'rcpig_after_settings' ); ?>
		<?php
	}

	/**
	* Get all the pages
	*
	* @return array page names with key value pairs
	*/
	function get_pages() {
		$pages = get_pages();
		$pages_options = array();
		if ( $pages ) {
			foreach ($pages as $page) {
				$pages_options[$page->ID] = $page->post_title;
			}
		}
		return $pages_options;
	}
}
endif;

$settings = new rcpig_settings_config();

//--------- trigger setting api class---------------- //
function rcpig_get_option( $option, $section, $default = '' ) {
	$options = get_option( $section );
	if ( isset( $options[$option] ) ) {
		return $options[$option];
	}
	return $default;
}
?>