<?php
namespace CbParallax\Admin\Includes;

use CbParallax\Admin\Partials as AdminPartials;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Include dependencies.
 */
if ( ! class_exists( 'AdminPartials\cb_parallax_help_tab_display' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/partials/class-help-tab-display.php';
}
if ( ! class_exists( 'AdminPartials\cb_parallax_help_sidebar_display' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/partials/class-help-sidebar-display.php';
}

/**
 * The class responsible for creating and displaying the help tab
 *
 * @since             0.9.0
 * @package           bonaire
 * @subpackage        bonaire/admin/includes
 * @author            Demis Patti <demis@demispatti.ch>
 */
class cb_parallax_contextual_help {
	
	/**
	 * The domain of the plugin.
	 *
	 * @var      string $domain
	 * @since    0.9.0
	 * @access   private
	 */
	private $domain;
	
	/**
	 * cb_parallax_contextual_help constructor.
	 *
	 * @param string $domain
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function __construct( $domain ) {
		
		$this->domain = $domain;
		
		$this->initialize();
	}
	
	/**
	 * Registers the methods that need to be hooked with WordPress.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function add_hooks() {
		
		add_action( 'in_admin_header', array( $this, 'add_contextual_help' ), 20 );
		
		add_action( 'load-post.php', array( $this, 'add_contextual_help' ), 10 );
		add_action( 'load-post-new.php', array( $this, 'add_contextual_help' ), 11 );
		add_action( "load-{$GLOBALS['pagenow']}", array( $this, 'add_contextual_help' ), 12 );
	}
	
	/**
	 * Adds the method for adding the contextual help to the queue of actions.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	private function initialize() {
		
		add_action( "load-{$GLOBALS['pagenow']}", array( $this, 'add_contextual_help' ), 15 );
	}
	
	/**
	 * Adds the help tab and the help sidebar to the current screen.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function add_contextual_help() {
		
		$current_screen = get_current_screen();
		
		$current_screen->add_help_tab(
			array(
				'id' => 'cb-parallax-help-tab',
				'title' => __( 'cbParallax Help', $this->domain ),
				'content' => AdminPartials\cb_parallax_help_tab_display::help_tab_display( $this->domain )
			)
		);
		
		$current_screen->set_help_sidebar( AdminPartials\cb_parallax_help_sidebar_display::help_sidebar_display( $current_screen ) );
	}
	
}
