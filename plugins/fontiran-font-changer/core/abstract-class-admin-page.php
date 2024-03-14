<?php

abstract class WP_Fontiran_Admin_Page {

	protected $slug = '';
	public $page_id = null;	
	private $errors = array();
	protected $fonts = array();
	

	/**
	 * WP_Fontiran_Admin_Page constructor.
	 *
	 * @param string $slug        Module slug.
	 * @param string $page_title  Page title.
	 * @param string $menu_title  Menu title.
	 * @param bool   $parent      Parent or not.
	 * @param bool   $render      Render the page.
	 */
	public function __construct( $slug, $page_title, $menu_title, $parent = false, $render = true ) {

		if ( !current_user_can( 'manage_options' ) ) return;
		
		$this->slug = $slug;
		$this->fonts = fi_get_fontlist();

		if ( ! $parent ) {
			$this->page_id = add_menu_page(
				$page_title,
				$menu_title,
				'manage_options',
				$slug,
				$render ? array( $this, 'render' ) : null,
				'none'
			);
		} else {
			$this->page_id = add_submenu_page(
				$parent,
				$page_title,
				$menu_title,
				'manage_options',
				$slug,
				$render ? array( $this, 'render' ) : null
			);
		}

		if ( $render ) {
			add_action( 'load-' . $this->page_id, array( $this, 'on_load' ) );
			add_filter( 'load-' . $this->page_id, array( $this, 'add_screen_hooks' ) );
		}


	}
	

	/**
	 * Return the admin menu slug
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Common hooks for all screens
	 */
	public function add_screen_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}


	public function enqueue_scripts( $hook ) {

		// Styles
		wp_enqueue_style( 'firan-adminn', FIRAN_URL . 'assets/css/admin.css', array(), FIRAN_VERSION );
		wp_enqueue_style( 'firan-fonts', FIRAN_URL . 'assets/css/fi-fonts.css', array(), FIRAN_VERSION );
		wp_enqueue_style( 'firan-colpick', FIRAN_URL . 'assets/css/colpick.css', array(), FIRAN_VERSION );

		//Scripts
		wp_enqueue_script('color-picker', FIRAN_URL. 'assets/js/colpick.min.js', array('jquery', 'jquery-ui-sortable') );
		wp_enqueue_script('fontiran-admin-js', FIRAN_URL . 'assets/js/admin.js' ,array(), FIRAN_VERSION);

	}


	/**
	 * Function triggered when the page is loaded
	 * before render any content
	 */
	public function on_load() {}
	

	/**
	 * Render the page
	 */
	public function render() {
		?>
		<div id="container" class="wrap fontiran-wrap <?php echo 'firan-' . $this->slug; ?>">
			<?php
			$this->render_header();
			$this->render_notices();
			$this->render_inner_content();
			?>
            
			<div class="footer-love">
				طراحی و اجرا <span class="dashicons-heart dashicons"></span> یکتادیجی
				
			</div>
		</div>


		<?php
	}
	
	/**
	 * Renders the template header that is repeated on every page.
	 */
	protected function render_header() {
		?>
		<section id="header">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		</section>

		<?php
	}
	
	protected function set_all_notices($ms = array()) {
		return $this->errors = $ms;
	}
	

	protected function render_notices() {
		
		if(!is_array($this->errors))
			return;
		
		foreach($this->errors as $t=>$m) {
			$t = (isset($m['type'])) ? 'fi-'.$m['type'] : null;
			$m = (isset($m['ms'])) ? '<div class="firan-notice-message">'.$m['ms'].'</div>' : null;
			echo $ms = '<div class="firan-notice '.$t.'">'.$m .'</div>';
		}

        
	 }

	protected function render_inner_content() {}


	/**
	 * Load an admin view
	 *
	 * @param $name
	 * @param array $args
	 * @param bool $echo
	 *
	 * @return string
	 */
	public function view( $name, $date = array(), $echo = true ) {
		
		$file = FIRAN_PATH . "core/views/$name.php";
		
		
		if ( !is_file( $file ) ) {
			return;	
		}

		$content = '';
		ob_start();
		include( $file );
		$content = ob_get_clean();
	
		if ( ! $echo ) {
			return $content;
		}

		echo $content;
	}

	protected function view_exists( $name ) {
		$file = FIRAN_URL . "core/views/$name.php";
		return is_file( $file );
	}
}