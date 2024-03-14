<?php

namespace WP_VGWORT;

// include the wp table dependency if not yet loaded
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Messages Page View Class
 *
 * shows all posts / pages with attached pixels and information if message has been created already
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class Page_Messages extends Page {
	/**
	 * @var array allowed order by values
	 */
	const ALLOWED_ORDER_BY = [ 'STATE' ];

	/**
	 * @var object instance of the messages table class
	 */
	public object $messages_table;

	/**
	 * constructor
	 */
	public function __construct( object $plugin ) {
		parent::__construct( $plugin );

		// add submenu item
		add_action( 'admin_menu', [ $this, 'add_messages_submenu' ] );
	}

	/**
	 * add the submenu for the messages overview
	 *
	 * @return void
	 */
	public function add_messages_submenu() {
		$page_metis_messages_hook = add_submenu_page( 'metis-dashboard', esc_html__( 'VG WORT METIS Meldungen / Beiträge und Seiten mit Zählmarken', 'vgw-metis' ), esc_html__( 'Meldungen', 'vgw-metis' ), 'manage_options', 'metis-messages', array(
			$this,
			'render'
		), 4 );

		add_action( "load-$page_metis_messages_hook", [ $this, 'add_page_messages_screen_options' ] );

	}

	public function add_page_messages_screen_options(): void {
		$args = array(
			'label'   => __( 'Beiträge mit Zählmarken pro Seite', 'vgw-metis' ),
			'default' => 20,
			'option'  => 'metis_messages_per_page'
		);
		add_screen_option( 'per_page', $args );

		$this->messages_table = new List_Table_Messages();
	}

	/**
	 * Loads the template of the view > render page
	 *
	 * @return void
	 */
	public function render(): void {
		$this->plugin->notifications->display_notices();
		$this->messages_table->prepare_items();
		require_once 'partials/messages.php';
	}
}


