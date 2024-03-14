<?php
/**
 * Adds overview widget to WordPress’ dashboard.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @since      6.2.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * An overview widget in the Dashboard.
 */
class Nelio_Content_Overview_Widget {

	protected static $instance;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	public function init() {
		add_action( 'admin_init', array( $this, 'add_overview_widget' ) );
		add_action( 'admin_head', array( $this, 'add_overview_widget_style' ) );
	}//end init()

	public function add_overview_widget() {
		if ( nelio_content()->is_ready() ) {
			require nelio_content()->plugin_path . '/admin/views/nelio-content-overview-widget.php';
		}//end if
	}//end add_overview_widget()

	public function add_overview_widget_style() {
		?>
		<style type="text/css">
		#nelio-content-dashboard-overview .inside { margin: 0; padding: 0; }
		#nelio-content-dashboard-overview h3 {
			font-weight: bold;
			border-bottom: 1px solid var(--nelio-content-color__border-light, #eee);
			padding: 0.5em 1em;
		}
		#nelio-content-dashboard-overview a { text-decoration: none; }

		#nelio-content-dashboard-overview .nelio-content-header {
			align-items: center;
			box-shadow: 0 5px 8px rgba(0, 0, 0, 0.05);
			display: flex;
			gap: 0.5em;
			padding: 0.5em 1em;
		}
		#nelio-content-dashboard-overview .nelio-content-header__icon { width: 3em; line-height: 1; }
		#nelio-content-dashboard-overview .nelio-content-header__version p {  font-size: 0.9em; margin: 0; padding: 0; }

		#nelio-content-dashboard-overview .nelio-content-posts { padding-top: 0.5em; }
		#nelio-content-dashboard-overview .nelio-content-post { margin: 0 1em 1em; }
		#nelio-content-dashboard-overview .nelio-content-post:last-child { margin-bottom: 0; }
		#nelio-content-dashboard-overview .nelio-content-post .dashicons { color: var(--nelio-content-text--dark, #666); font-size: 1.3em; }
		#nelio-content-dashboard-overview .nelio-content-post__type { color: var(--nelio-content-text--grey, #888); }
		#nelio-content-dashboard-overview .nelio-content-post__date { color: var(--nelio-content-text--grey, #888); }

		#nelio-content-dashboard-overview .nelio-content-news { padding-top: 0.5em; }
		#nelio-content-dashboard-overview .nelio-content-news .spinner { display: block; float: none; margin: 0 auto 1em; }
		#nelio-content-dashboard-overview .nelio-content-single-news { margin: 0 1em 1em; }
		#nelio-content-dashboard-overview .nelio-content-single-news:last-child { margin-bottom: 0; }
		#nelio-content-dashboard-overview .nelio-content-single-news__header { font-size: 14px; margin-bottom: 0.5em; }
		#nelio-content-dashboard-overview .nelio-content-single-news__type {
			background: #0a875a;
			color: white;
			font-size: 0.75em;
			padding: 3px 6px;
			border-radius: 3px;
			text-transform: uppercase;
		}
		#nelio-content-dashboard-overview .nelio-content-single-news__type--is-release { background: #c92c2c; }

		#nelio-content-dashboard-overview .nelio-content-actions {
			border-top: 1px solid var(--nelio-content-color__border-light, #eee);
			display: flex;
			gap: 1em;
			padding: 1em;
		}
		#nelio-content-dashboard-overview .nelio-content-actions > span:not(:last-child) {
			border-right: 1px solid var(--nelio-content-color__border-light, #eee);
			padding-right: 1em;
		}
		#nelio-content-dashboard-overview .nelio-content-actions .dashicons { color: var(--nelio-content-text--dark, #666); font-size: 1.3em; }
		</style>
		<?php
	}//end add_overview_widget_style()

}//end class
