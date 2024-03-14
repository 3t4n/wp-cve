<?php
/*
 * Backend Class for use in all shortcodely plugins
 * Version 0.1
 *
 * @package Shortcodely
 */

// ------------------------------------------------------------------------------------------------------------------
if ( ! class_exists( 'Shortcodely_Saw_Plugin_Admin' ) ) {
	/*
    * Shortcodely_Saw_Plugin_Admin Class Doc Comment
    *
    * @ category Class
    * @ package	shortcodely
    * @ author	 patilswapnilv
    * @ license	http://www.gnu.org/copyleft/gpl.html GNU General Public License
    * @ link	http://www.github.com/patilswapnilv/shortcodely
    */

	class Shortcodely_Saw_Plugin_Admin {



		public $hook = 'shortcodely_saw';
		public $filename        = 'shortcodely_shortcode_any_widget/shortcodely_shortcode_any_widget.php';
		public $longname        = 'Shortcode any widget - insert widgets or widget areas into a page.';
		public $shortname = 'Shortcode any widget';
		public $optionname = '';
		/**
		 * Homepage variable Doc Comment
		 *
		 * @category variable
		 * @package  shortcodely
		 * @author   patilswapnilv
		 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
		 * @link     http://www.github.com/patilswapnilv/shortcodely
		 * /
				 /**
		 * This is a "Docblock Comment," also known as a "docblock."    The class'
		 * docblock, below, contains a complete description of how to write these.
		 */
		public $homepage = '';
		public $parent_slug = 'plugin_listings_menu';
		public $accesslvl = 'manage_options';
		public function __construct() {
				add_action( 'admin_menu', array( &$this, 'register_settings_page' ) );
				add_filter( 'plugin_action_links', array( &$this, 'add_action_link' ), 10, 2 );
		}
		public function register_settings_page() {
				add_options_page( $this->longname, $this->shortname, $this->accesslvl, $this->hook, array( &$this, 'config_page' ) );
		}
		public function plugin_options_url() {
				return admin_url( 'options-general.php?page=' . $this->hook );
		}
		/**
		 * Add a link to the settings page to the plugins list
		 */
		public function add_action_link( $links, $file ) {
			static $this_plugin;
			if ( empty( $this_plugin ) ) {
				$this_plugin = $this->filename;
			}
			if ( $file == $this_plugin ) {
				$settings_link = '<a href="' . $this->plugin_options_url() . '">' . __( 'Settings', 'shortcodely-shortcode-any-widget' ) . '</a>';
				array_unshift( $links, $settings_link );
			}
				return $links;
		}

		/**
		 * @param string $title
		 */
		public function admin_heading( $title ) {
				echo '<div class="wrap" >
			<div id="icon-options-general" class="icon32"><br />
			</div>
			<h2>' . $title . ' </h2>';
		}

		public function admin_subheading( $title ) {
				echo '<h2>' . $title . '</h2>';
		}
		public function config_page() {
				$this->admin_heading( $this->longname );
				echo '<h3>More detailed instructions at the wordpress plugin <a target="_new" href="http://wordpress.org/plugins/shortcodely/installation">installation and faq pages.</a></h3>';
				echo '<ol>';
				echo '<li>';
				_e( 'Test your widget in a normal sidebar first.', 'shortcodely-shortcode-any-widget' );
				echo ' <a title="Go to widget area" href="' . get_admin_url( '', 'widgets.php' ) . '">';
				_e( 'Go to widgets', 'shortcodely-shortcode-any-widget' );
				echo '</a>';
				echo '<li>';
				_e( 'Drag the widgets you want to use to the shortcodes sidebar.', 'shortcodely-shortcode-any-widget' );
				echo '</li>';
				echo '<li>';
				_e( 'Add a do_widget or do_widget_area shortcode to a page.', 'shortcodely-shortcode-any-widget' );
				echo ' <a title="Create a page" href="'
				. get_admin_url( '', 'post-new.php?post_type=page&content=&#91;do_widget Archives &#93;' )
				. '">';
				_e( 'Create a page with example do_widget shortcode', 'shortcodely-shortcode-any-widget' );
				echo '</a>';
				echo '</li>';
				echo '</ol>';

				echo '<h2>';
				_e( 'To add a single widget to a page', 'shortcodely-shortcode-any-widget' );
				echo '</h2>';
				echo '<ul>';
				echo '<li>';
				_e( 'Add the shortcode [do_widget widgetname] to a page:', 'shortcodely-shortcode-any-widget' );
				echo '</li>';
				echo '<li>';
				echo '<li>';
				echo '[do_widget categories] or [do_widget name=categories] ';
				echo '</li>';
				echo '<li>';
				_e( '[do_widget "tag cloud"] or [do_widget id=widgetid]', 'shortcodely-shortcode-any-widget' );
				echo '</li>';
				echo '<li>';
				echo 'To see a list of your widgets in their sidebars, add <b>?do_widget_debug</b> to the url of page with the do_widget shortcode.';
				echo '</li>';
				echo '</ul>';

				echo '<br />';
				echo '<h2>';
				_e( 'More advanced options:', 'shortcodely-shortcode-any-widget' );
				echo '</h2>';
				echo '<ul><li>';
				echo 'Use title=false to hide a widget title. ';
				echo '</li>';
				echo '<li>';
				echo '[do_widget pages title=false]	will hide the widget title';
				echo '</li></ul>';
				echo '<h3>';
				_e( 'To change the style, change the html:', 'shortcodely-shortcode-any-widget' );
				echo '</h3>';
				echo '<ul>';

				echo '<li>';
				echo 'Use title=somehtmltag and wrap=somehtmltag	to change the html used.	This may change how your theme\'s css affects the widget when it is in	page.	It all depends what what html selectors your theme uses.';
				echo '</li>';
				echo '<li>';
				echo ' Use class=yourclassname to add a class - maybe to override your themes widget styling?	Obviously you must have css that applies to that class.';
				echo '</li>';

				echo '<li>';
				echo '[do_widget pages title=h3]	give the title a heading 3 html tag.';
				echo '</li>';
				echo '<li>';
				echo '[do_widget "tag cloud" wrap=aside]	 will wrap the widget in an "aside" html tag.';
				echo '</li>';

				echo '</ul>';

				echo '<h4>';
				echo 'Valid title html tags are : </h4><ul>';
				echo '<li>h1</li>';
				echo '<li>h2</li>';
				echo '<li>h3</li>';
				echo '<li>h4</li>';
				echo '<li>h5</li>';
				echo '<li>header</li>';
				echo '<li>strong</li>';
				echo '<li>em</li>';
				echo '</ul>';

				echo '<h4>Valid html wrap tags are :</h4><ul>';
				echo '<li>div</li>';
				echo '<li>p</li>';
				echo '<li>aside</li>';
				echo '<li>section</li>';
				echo '</ul>';

				echo '<h2>';
				_e( 'To add multiple instances of the same widget:', 'shortcodely-shortcode-any-widget' );

				echo '</h2>';
				echo '<ul>';
				echo '<li>';
				echo '[do_widget id=widgetid1] [do_widget id=widgetid2]';
				echo '</li>';
				echo '</ul>';

				echo '<h2>';
				_e( 'To add a widget area - all widgets in the widget area:', 'shortcodely-shortcode-any-widget' );

				echo '</h2>';
				echo '<ul>';
				echo '<li>';
				echo '<a title="Create a page" href="'
				. get_admin_url( '', 'post-new.php?post_type=page&content=&#91;do_widget_area&#93;' )
				. '"> ';
				_e( 'Create a page with do_widget_area shortcode', 'shortcodely-shortcode-any-widget' );
				echo '</a> Hoping to use theme styling.';
				echo '</li>';
				echo '<li>';
				echo '<a title="Create a page" href="'
				. get_admin_url(
					'',
					'post-new.php?post_type=page&content=&#91;do_widget_area widget_area_class=none&#93;'
				)
				. '"> ';
				_e( 'Create a page with do_widget_area shortcode	without the widget_area class', 'shortcodely-shortcode-any-widget' );
				echo '</a> Hoping to avoid theme sidebar styling.';
				echo '</li>';
				echo '<li>';
				_e( 'Examples:', 'shortcodely-shortcode-any-widget' );
				echo '</li>';
				echo '<li>';
				_e( '[do_widget_area] or [do_widget_area widget_area=sidebar-1]', 'shortcodely-shortcode-any-widget' );
				echo '</li>';
				echo '<li>';
				_e( 'NB: Using something like the twenty-fourteen theme? you might end up with white text on a white background.	Tweak the widget classes or the html of the wrap or title. If that fails, adjust your css.', 'shortcodely-shortcode-any-widget' );
				echo '</li>';
				echo '</ul>';
				echo '<br />';
		}


		/**
		 * Info box with link to the support forums.
		 */
		public function plugin_support() {
				$content = '<p>' . __( 'If you have any problems with this plugin or good ideas for improvements or new features, please talk about them in the', 'shortcodely-shortcode-any-widget' ) . ' <a href="http://wordpress.org/tags/' . $this->hook . '">' . __( 'Support forums', 'shortcodely-shortcode-any-widget' ) . '</a>.</p>';
				$this->postbox( $this->hook . 'support', 'Need support?', $content );
		}

		public function text_limit( $text, $limit, $finish = ' [&hellip;]' ) {
			if ( strlen( $text ) > $limit ) {
				$text = substr( $text, 0, $limit );
				$text = substr( $text, 0, - (strlen( strrchr( $text, ' ' ) )) );
				$text .= $finish;
			}
				return $text;
		}
	}
}
