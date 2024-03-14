<?php
/**
 * class Hangeul_Web_Fonts_admin
 */

// Exit if accessed directely
if ( ! defined( 'ABSPATH' ) ) exit;

class Hangeul_Web_Fonts_admin {

	private $options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_hwfonts_page' ) );
		add_action( 'admin_init', array( $this, 'fonts_page_init' ) );
	}

	public function add_hwfonts_page() {
		add_options_page(
			__( 'Hangeul Web Fonts', 'hangeul-web-fonts' ), // $page_title
			__( 'Hangeul Web Fonts', 'hangeul-web-fonts' ), // $menu_title
			'manage_options', // $capability
			'hangeul-web-fonts', // $menu_slug
			array( $this, 'create_hwfonts_page' ) // function
		);
	}

	public function create_hwfonts_page() {
	?>
		<div class="wrap">
			<h1><?php _e( 'Hangeul Web Fonts', 'hangeul-web-fonts' );?></h1>

			<?php
				$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'fonts_options';
			?>

			<h2 class="nav-tab-wrapper">
				<a href="?page=hangeul-web-fonts&tab=fonts_options" class="nav-tab <?php echo $active_tab == 'fonts_options' ? 'nav-tab-active' : ''; ?>" ><?php _e( 'Web Fonts To Use', 'hangeul-web-fonts' ); ?></a>
			</h2>          

			<form method="post" action="options.php">
			<?php
				if( $active_tab == 'fonts_options' ) {
					settings_fields('fonts_group'); // $option_group
					do_settings_sections('hwfonts_fonts_page'); // $page
				}
				submit_button();
			?>
			</form>
		</div>
	<?php
	}

	public function fonts_page_init() {

		// tab
		add_settings_section(
			'fonts_section', // $id
			'', // $title
			array( $this, 'fonts_section_desc' ), // $callback
			'hwfonts_fonts_page' // $page
		);

		add_settings_field(
			'sdmisaeng_font', // $id
			__( 'SDMiSaeng Font', 'hangeul-web-fonts' ), // $title
			array( $this, 'sdmisaeng_font_cb' ), // $callback
			'hwfonts_fonts_page', // $page
			'fonts_section' // $section
			 // $args
		);

		add_settings_field(
			'jeju_fonts', // $id
			__( 'Jeju Fonts', 'hangeul-web-fonts' ) . '<br />- by Google Fonts Early Access', // $title
			array( $this, 'jeju_fonts_cb' ), // $callback
			'hwfonts_fonts_page', // $page
			'fonts_section' // $section
			 // $args
		);

		add_settings_field(
			'nanum_fonts', // $id
			__( 'Nanum Fonts', 'hangeul-web-fonts' ) . ' (Naver)<br />- by Google Fonts Early Access', // $title
			array( $this, 'nanum_fonts_cb' ), // $callback
			'hwfonts_fonts_page', // $page
			'fonts_section' // $section
			 // $args
		);

		register_setting(
			'fonts_group', // $option_group
			'fonts_options' // $option_name
			// $sanitize_callback
		);
	}

	public function fonts_section_desc() {
		/* */
	}

	public function sdmisaeng_font_cb() {
		$sdmisaeng = array(
			'name'				=> 'sdmisaeng',
			'title'				=> '미생체',
			'font-family'	=> 'SDMiSaeng',
			'font-size'		=> '1.3em',
			'options'			=> 'fonts_options',
		);
		$this->register_checkbox_fonts( $sdmisaeng );
	}

	public function jeju_fonts_cb() {
		$jejuhallasan = array(
			'name'				=> 'jejuhallasan',
			'title'				=> '제주한라산체',
			'font-family'	=> 'Jeju Hallasan',
			'options'			=> 'fonts_options',
		);
		$this->register_checkbox_fonts( $jejuhallasan );
		$jejugothic = array(
			'name'				=> 'jejugothic',
			'title'				=> '제주고딕체',
			'font-family'	=> 'Jeju Gothic',
			'options'			=> 'fonts_options',
		);
		$this->register_checkbox_fonts( $jejugothic );
		$jejumyeongjo = array(
			'name'				=> 'jejumyeongjo',
			'title'				=> '제주명조체',
			'font-family'	=> 'Jeju Myeongjo',
			'options'			=> 'fonts_options',
		);
		$this->register_checkbox_fonts( $jejumyeongjo );
	}

	public function nanum_fonts_cb() {
		$nanumbrushscript = array(
			'name'				=> 'nanumbrushscript',
			'title'				=> '나눔손글씨 붓체',
			'font-family'	=> 'Nanum Brush Script',
			'font-size'		=> '1.15em',
			'options'			=> 'fonts_options',
		);
		$this->register_checkbox_fonts( $nanumbrushscript );
		$nanumpenscript = array(
			'name'				=> 'nanumpenscript',
			'title'				=> '나눔손글씨 펜체',
			'font-family'	=> 'Nanum Pen Script',
			'font-size'		=> '1.15em',
			'options'			=> 'fonts_options',
		);
		$this->register_checkbox_fonts( $nanumpenscript );
		$nanumgothic = array(
			'name'				=> 'nanumgothic',
			'title'				=> '나눔고딕',
			'font-family'	=> 'Nanum Gothic',
			'options'			=> 'fonts_options',
		);
		$this->register_checkbox_fonts( $nanumgothic );
		$nanummyeongjo = array(
			'name'				=> 'nanummyeongjo',
			'title'				=> '나눔명조',
			'font-family'	=> 'Nanum Myeongjo',
			'options'			=> 'fonts_options',
		);
		$this->register_checkbox_fonts( $nanummyeongjo );
	}

	public function register_checkbox_fonts( $args ) {
		$this->options = get_option($args['options']);
		$is_name = 'is_' . $args['name'];
		printf(
			'<input %s type="checkbox" name="%s" id="' . $is_name . '" value="1" /> <label class="lbl_' . $args['name'] . '">%s</label> <label>{ font-family: %s; %s}</label><p></p>',
			( isset( $this->options[$is_name] ) && $this->options[$is_name] === '1' ) ? 'checked' : '', // checked
			$args['options'] . '[' . $is_name . ']', // name
			$args['title'], // title
			$args['font-family'], // font-family
			isset( $args['font-size'] ) ? 'font-size: ' . $args['font-size'] . '; ' : '' // font-size
		);
	}
}
