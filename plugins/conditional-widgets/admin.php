<?php
/**
 * @author    Jason Lemahieu and Kevin Graeme (Cooperative Extension Technology Services)
 * @copyright Copyright (c) 2011 - 2015 Jason Lemahieu and Kevin Graeme (Cooperative Extension Technology Services)
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @package   CETS\Conditional_Widgets
 */

/** Actions ***********************************************************/
add_action( 'plugins_loaded',        'conditional_widgets_load_plugin_textdomain' );
add_action( 'admin_enqueue_scripts', 'conditional_widgets_enqueue_assets'         );

function conditional_widgets_enqueue_assets( $hook ) {

	$pages = apply_filters( 'conditional_widgets_admin_pages', array( 'widgets.php', 'customize.php' ) );

	if ( ! in_array( $hook, $pages ) ) {
		return;
	}

	add_action( 'admin_print_footer_scripts', 'conditional_widgets_add_js' );

	wp_enqueue_style( 'conditional_widgets_admin_styles', plugins_url( "css/conditional-widgets-admin.css", __FILE__ ), array(), '2.1.0-dev' );

} // /function conditional_widgets_enqueue_assets()

/**
 * Inject javascript into footerp
 *
 * @since	2.1.0
 *
 * @return	string
 */
function conditional_widgets_add_js() { ?>
<script type='text/javascript'>function conditional_widgets_form_toggle(divID) { jQuery("#" + divID).slideToggle("slow"); }</script>
<?php
} // /function add_js()

/**
 * Load the plugin's textdomain hooked to 'plugins_loaded'.
 *
 * @since	1.0.0
 * @access	public
 *
 * @see		load_plugin_textdomain()
 * @see		plugin_basename()
 * @action	plugins_loaded
 *
 * @return	void
 */
function conditional_widgets_load_plugin_textdomain() {

	load_plugin_textdomain(
		'conditional-widgets',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages/'
	);

}  // /function load_plugin_textdomain()
/**
 * Helper function for outputting the select boxes in the widget's form
 */
function conditional_widgets_form_show_hide_select( $name, $value = '', $only = false, $echo = false ) {
	
	$o = ''; //output

	$o .= "<select name=$name>";
		$o .= "<option value='1' ";
		$o .= selected( $value, 1, false );
		$o .= ">" . __( 'Show', 'conditional-widgets' ) . "</option>";
	
	if ( $only ) {
		$o .= "<option value='2' ";
		$o .= selected( $value, 2, false );
		$o .= ">" . __( 'Show only', 'conditional-widgets' ) . "</option>";
	}

		$o .= "<option value='0' ";
		$o .= selected( $value, 0, false );
		$o .= ">" . __( 'Hide', 'conditional-widgets' ) . "</option>";
	$o .= "</select>";

	if ( $echo ) {
		echo $o;
		return '';
	} else {
		return $o;
	}

} // /function conditional_widgets_form_show_hide_select()

/**
 * Helper function for displaying the list of checkboxes for Pages
 */
function conditional_widgets_page_checkboxes( $selected = array() ) {

	$args = array(
		'title_li' => null,
		'walker'   => new Conditional_Widgets_Walker_Page_Checklist( $selected ),
	);

	echo "<ul class='conditional-widget-selection-list'>";
	wp_list_pages( $args );
	echo "</ul>";

} // /function conditional_widgets_page_checkboxes()


function conditional_widgets_term_checkboxes( $tax, $type, $selected = array() ) {

	$args = array(
		'selected_cats' => $selected,
		'checked_ontop' => false,
		'taxonomy'      => $tax,
		'walker'        => new Conditional_Widget_Walker_Category_Checklist( $type, $tax ),
	);

	echo "<ul class='conditional-widget-selection-list'>";
		wp_terms_checklist( 0, $args );
	echo "</ul>";

} // /function conditional_widgets_term_checkboxes()
