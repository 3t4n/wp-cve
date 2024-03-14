<?php

require_once dirname( __FILE__ ) . '/base.ctrl.php';

class CF7_CTRL_SELECT2 extends CF7_COMPLEMENTARY_CTRL {

	protected $title = 'DropDown Menu with Search Box';
	protected $description;
	protected $image_url = 'select2-drop-down-menu/image.png';

	public function __construct( $container_obj, $slug ) {
		$this->description = __( 'Allows you to convert drop-down menus into advanced controls with search boxes.', 'cf7-datasource' );
		parent::__construct( $container_obj, $slug );

		if ( $this->container_obj->is_control_active( $slug ) ) {
			add_action( 'init', array( $this, 'init' ), 0 );
		}
	} // End __construct

	public function init() {
		remove_action( 'wpcf7_init', 'wpcf7_add_form_tag_select', 10 );
		remove_action( 'wpcf7_admin_init', 'wpcf7_add_tag_generator_menu', 25 );

		add_action( 'wpcf7_init', array( $this, 'wpcf7_init' ), 10 );
		add_action( 'wpcf7_admin_init', array( $this, 'wpcf7_add_tag_generator_menu' ), 25, 0 );
	} // End init

	public function wpcf7_init() {
		wpcf7_add_form_tag(
			array( 'select', 'select*' ),
			array( $this, 'wpcf7_select_form_tag_handler' ),
			array(
				'name-attr'         => true,
				'selectable-values' => true,
			)
		);
	} // End wpcf7_init

	public function wpcf7_select_form_tag_handler( $tag ) {
		 $html = wpcf7_select_form_tag_handler( $tag );
		if ( '' != $html ) {
			$select2 = $tag->has_option( 'select2' );
			if ( $select2 ) {
				$html = preg_replace( '/<select/i', '<select data-cf7-ds-select2="1" ', $html );

				// Enqueue resources
				wp_enqueue_script( 'jquery' );

				wp_enqueue_script( 'cf7-ds-select2-js', plugins_url( '/select2-drop-down-menu/vendor/select2.min.js', __FILE__ ), array( 'jquery' ), CF7_DATASOURCE::$version, true );
				wp_enqueue_style( 'cf7-ds-select2-css', plugins_url( '/select2-drop-down-menu/vendor/select2.min.css', __FILE__ ), array(), CF7_DATASOURCE::$version );

				wp_enqueue_script( 'cf7-ds-select2-drop-down-menu-js', plugins_url( '/select2-drop-down-menu/script.js', __FILE__ ), array( 'cf7-ds-select2-js' ), CF7_DATASOURCE::$version, true );
				wp_enqueue_style( 'cf7-ds-select2-drop-down-menu-css', plugins_url( '/select2-drop-down-menu/styles.css', __FILE__ ), array(), CF7_DATASOURCE::$version );
			}
		}
		return $html;
	} // wpcf7_select_form_tag_handler

	public function wpcf7_add_tag_generator_menu() {
		$tag_generator = WPCF7_TagGenerator::get_instance();
		$tag_generator->add(
			'menu',
			__( 'drop-down menu', 'contact-form-7' ),
			array( $this, 'wpcf7_tag_generator_menu' )
		);
	} // End wpcf7_add_tag_generator_menu

	public function wpcf7_tag_generator_menu( $contact_form, $args = '' ) {
		 ob_start(); // Turn on output buffering
		wpcf7_tag_generator_menu( $contact_form, $args );
		$buffered_contents = ob_get_contents();
		ob_end_clean(); // Clean the output buffer and turn off output buffering
		$args = wp_parse_args( $args, array() );

		$pos               = strpos( $buffered_contents, 'include_blank' );
		$pos               = stripos( $buffered_contents, 'label>', $pos );
		$buffered_contents = substr_replace( $buffered_contents, '<br><label><input type="checkbox" name="select2" class="option" /> ' . esc_html__( 'DropDown menu with search box', 'cf7-datasource' ) . '<br><span style="display:inline-block;margin-left:24px;"></span>' . esc_html__( 'For more details, see', 'cf7-datasource' ) . ' <a href="https://cf7-datasource.dwbooster.com/complementary-controls-addon#dropdown-menu-with-search-box" target="_blank">DropDown menu with search box</a>.</label>', $pos + 6, 0 );
		print $buffered_contents; // phpcs:ignore WordPress.Security.EscapeOutput
	} // End wpcf7_tag_generator_menu
}

if ( isset( $container_obj ) && $slug ) {
	new CF7_CTRL_SELECT2( $container_obj, $slug );
}
