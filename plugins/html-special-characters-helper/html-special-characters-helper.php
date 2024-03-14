<?php
/**
 * Plugin Name: HTML Special Characters Helper
 * Version:     2.2
 * Plugin URI:  http://coffee2code.com/wp-plugins/html-special-characters-helper/
 * Author:      Scott Reilly
 * Author URI:  http://coffee2code.com/
 * Text Domain: html-special-characters-helper
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Description: Admin widget on the Write Post page for inserting HTML encodings of special characters into the post.
 *
 * Compatible with WordPress 4.6 through 4.7+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/html-special-characters-helper/
 *
 * @package HTML_Special_Characters_Helper
 * @author  Scott Reilly
 * @version 2.2
 */

/*
 * TODO:
 * - Front-end widget to facilitate use in comments
 * - Make it possible to attach HTML character insertion into any input field
 * - Ability to undo insertion of HTML character. (May need to reimplement
 *   send_to_editor()). See http://stackoverflow.com/questions/13597007
 * - A way to copy to clipboard instead of inserting into post content textarea.
 *   Would make it easy to grab the character for use in another input field.
 *   Perhaps a checkbox in the metabox "Copy to clipboard instead of inserting
 *   into post content". Checkbox state does not need to persist (but default
 *   state could be made filterable).
 */

/*
	Copyright (c) 2007-2017 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'c2c_HTMLSpecialCharactersHelper' ) ) :

class c2c_HTMLSpecialCharactersHelper {

	/**
	 * The metabox/widget title.
	 * @var string
	 */
	public static $title = '';

	/**
	 * The default special characters.
	 * @var array
	 */
	private static $characters = array();

	/**
	 * Returns version of the plugin.
	 *
	 * @since 1.9
	 */
	public static function version() {
		return '2.2';
	}

	/**
	 * Constructor.
	 */
	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'do_admin_init' ) );
	}

	/**
	 * Hook actions and register adding the plugins admin meta box.
	 */
	public static function do_admin_init() {
		// Don't do anything for AJAX requests.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		// Load textdomain.
		load_plugin_textdomain( 'html-special-characters-helper' );

		self::$title = __( 'HTML Special Characters', 'html-special-characters-helper' );

		add_action( 'load-post.php',     array( __CLASS__, 'enqueue_scripts_and_styles' ) );
		add_action( 'load-post-new.php', array( __CLASS__, 'enqueue_scripts_and_styles' ) );

		add_action( 'add_meta_boxes',    array( __CLASS__, 'add_meta_boxes' ), 10, 2 );
	}

	/**
	 * Adds the metabox.
	 *
	 * @since 2.2
	 *
	 * @param string  $post_type Post type.
	 * @param WP_Post $post      Post object.
	 */
	public static function add_meta_boxes( $post_type, $post ) {
		if ( post_type_supports( $post_type, 'editor' ) && in_array( $post_type, self::get_post_types() ) ) {
			add_meta_box( 'htmlspecialchars', self::$title, array( __CLASS__, 'meta_box_content' ), $post_type, 'side' );
		}
	}

	/**
	 * Enqueues scripts and styles.
	 *
	 * @since 1.9
	 */
	public static function enqueue_scripts_and_styles() {
		// Enqueues JS for admin page.
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_js' ) );
		// Register and enqueue styles for admin page.
		self::register_styles();
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_css' ) );
	}

	/**
	 * Returns post types that should have the helper available.
	 *
	 * @since 2.2
	 *
	 * @return array
	 */
	public static function get_post_types() {
		// Get a list of all post type with a UI.
		$post_types  = (array) get_post_types( array( 'show_ui' => true ) );

		unset( $post_types['attachment'] );

		// Permit filtering of the post types handled by the plugin.
		$post_types = array_values( (array) apply_filters( 'c2c_html_special_characters_helper_post_types', $post_types ) );

		return $post_types;
	}

	/**
	 * Returns an associative array of all the default categories of HTML special characters, their entities/codes, and their descriptions.
	 *
	 * Defines the data if not done so already.
	 *
	 * Done here rather than directly in class since it needs __() to be available.
	 *
	 * @param string|null $category Optional. The name of the sub-category of codes to return. Default of null returns all.
	 * @return array      Array of HTML special characters.
	 */
	public static function get_default_html_special_characters( $category = null ) {
		if ( ! self::$characters ) {

			self::$characters = array(
				'common' => array(
					'name'     => __( 'Most commonly used',              'html-special-characters-helper' ),
					'&copy;'   => __( 'copyright sign',                  'html-special-characters-helper' ),
					'&reg;'    => __( 'registered trade mark sign',      'html-special-characters-helper' ),
					'&#8482;'  => __( 'trade mark sign',                 'html-special-characters-helper' ),
					'&laquo;'  => __( 'left double angle quotes',        'html-special-characters-helper' ),
					'&raquo;'  => __( 'right double angle quotes',       'html-special-characters-helper' ),
					'&cent;'   => __( 'cent sign',                       'html-special-characters-helper' ),
					'&pound;'  => __( 'pound sign',                      'html-special-characters-helper' ),
					'&euro;'   => __( 'euro sign',                       'html-special-characters-helper' ),
					'&yen;'    => __( 'yen sign',                        'html-special-characters-helper' ),
					'&sup1;'   => __( 'superscript one',                 'html-special-characters-helper' ),
					'&sup2;'   => __( 'superscript two - squared',       'html-special-characters-helper' ),
					'&sup3;'   => __( 'superscript three - cubed',       'html-special-characters-helper' ),
					'&deg;'    => __( 'degree sign',                     'html-special-characters-helper' ),
					'&frac14;' => __( 'fraction one quarter (1/4)',      'html-special-characters-helper' ),
					'&frac12;' => __( 'fraction one half (1/2)',         'html-special-characters-helper' ),
					'&frac34;' => __( 'fraction three quarters (3/4)',   'html-special-characters-helper' ),
					'&iquest;' => __( 'inverted question mark',          'html-special-characters-helper' ),
					'&iexcl;'  => __( 'inverted exclamation mark',       'html-special-characters-helper' ),
					'&quot;'   => __( 'double quotes',                   'html-special-characters-helper' ),
					'&amp;'    => __( 'ampersand',                       'html-special-characters-helper' ),
					'&lt;'     => __( 'less than sign',                  'html-special-characters-helper' ),
					'&gt;'     => __( 'greater than sign',               'html-special-characters-helper' ),
					'&apos;'   => __( 'apostrophe',                      'html-special-characters-helper' ),
					'&sect;'   => __( 'subsection sign',                 'html-special-characters-helper' ),
					'&micro;'  => __( 'micro sign',                      'html-special-characters-helper' ),
					'&times;'  => __( 'multiplication sign',             'html-special-characters-helper' ),
					'&divide;' => __( 'division sign',                   'html-special-characters-helper' ),
					'&plusmn;' => __( 'plus/minus symbol',               'html-special-characters-helper' ),
					'&middot;' => __( 'middle dot',                      'html-special-characters-helper' ),
					'&para;'   => __( 'paragraph symbol',                'html-special-characters-helper' ),
					'&#8211;'  => __( 'en dash',                         'html-special-characters-helper' ),
					'&#8212;'  => __( 'em dash',                         'html-special-characters-helper' ),
					'&#8230;'  => __( 'horizontal ellipsis',             'html-special-characters-helper' ),
					'&bull;'   => __( 'bullet',                          'html-special-characters-helper' ),
					'&dagger;' => __( 'dagger',                          'html-special-characters-helper' ),
					'&Dagger;' => __( 'double dagger',                   'html-special-characters-helper' ),
					'&larr;'   => __( 'left arrow',                      'html-special-characters-helper' ),
					'&uarr;'   => __( 'up arrow',                        'html-special-characters-helper' ),
					'&rarr;'   => __( 'right arrow',                     'html-special-characters-helper' ),
					'&darr;'   => __( 'down arrow',                      'html-special-characters-helper' ),
				),
				'punctuation' => array(
					'name'     => __( 'Punctuation',                     'html-special-characters-helper' ),
					'&amp;'	   => __( 'ampersand',                       'html-special-characters-helper' ),
					'&apos;'   => __( 'apostrophe',                      'html-special-characters-helper' ),
					'&quot;'   => __( 'double quotes',                   'html-special-characters-helper' ),
					'&laquo;'  => __( 'left double angle quotes',        'html-special-characters-helper' ),
					'&raquo;'  => __( 'right double angle quotes',       'html-special-characters-helper' ),
					'&ldquo;'  => __( 'opening double quotes',           'html-special-characters-helper' ),
					'&rdquo;'  => __( 'closing double quotes',           'html-special-characters-helper' ),
					'&lsquo;'  => __( 'opening Single quote mark',       'html-special-characters-helper' ),
					'&rsquo;'  => __( 'closing single quote mark',       'html-special-characters-helper' ),
					'&reg;'    => __( 'registered symbol',               'html-special-characters-helper' ),
					'&copy;'   => __( 'copyright symbol',                'html-special-characters-helper' ),
					'&#8482;'  => __( 'trademark symbol',                'html-special-characters-helper' ),
					'&para;'   => __( 'paragraph symbol',                'html-special-characters-helper' ),
					'&szlig;'  => __( 'sharp s / ess-zed',               'html-special-characters-helper' ),
					'&bull;'   => __( 'bullet/big dot',                  'html-special-characters-helper' ),
					'&middot;' => __( 'middle dot',                      'html-special-characters-helper' ),
					'&sect;'   => __( 'subsection symbol',               'html-special-characters-helper' ),
					'&#8211;'  => __( 'en dash',                         'html-special-characters-helper' ),
					'&#8212;'  => __( 'em dash',                         'html-special-characters-helper' ),
					'&#8230;'  => __( 'horizontal ellipsis',             'html-special-characters-helper' ),
					'&iquest;' => __( 'inverted question mark',          'html-special-characters-helper' ),
					'&iexcl;'  => __( 'inverted exclamation mark',       'html-special-characters-helper' ),
				),
				'currency' => array(
					'name'     => __( 'Currency',                        'html-special-characters-helper' ),
					'&cent;'   => __( 'cent sign',                       'html-special-characters-helper' ),
					'&pound;'  => __( 'British Pound',                   'html-special-characters-helper' ),
					'&yen;'    => __( 'Japanese Yen',                    'html-special-characters-helper' ),
					'&euro;'   => __( 'Euro symbol',                     'html-special-characters-helper' ),
					'&fnof;'   => __( 'Dutch Florin symbol',             'html-special-characters-helper' ),
					'&curren;' => __( 'generic currency symbol',         'html-special-characters-helper' ),
				),
				'math' => array(
					'name'     => __( 'Math',                            'html-special-characters-helper' ),
					'&fnof;'   => __( 'function',                        'html-special-characters-helper' ),
					'&gt;'     => __( 'greater than',                    'html-special-characters-helper' ),
					'&lt;'     => __( 'less than',                       'html-special-characters-helper' ),
					'&ge;'     => __( 'greater than or equal to',        'html-special-characters-helper' ),
					'&le;'     => __( 'less than or equal to',           'html-special-characters-helper' ),
					'&ne;'     => __( 'not equal to',                    'html-special-characters-helper' ),
					'&asymp;'  => __( 'approximately',                   'html-special-characters-helper' ),
					'&equiv;'  => __( 'identical to',                    'html-special-characters-helper' ),
					'&minus;'  => __( 'minus sign',                      'html-special-characters-helper' ),
					'&divide;' => __( 'division sign',                   'html-special-characters-helper' ),
					'&times;'  => __( 'multiplication sign',             'html-special-characters-helper' ),
					'&deg;'    => __( 'degree symbol',                   'html-special-characters-helper' ),
					'&not;'    => __( 'not symbol',                      'html-special-characters-helper' ),
					'&plusmn;' => __( 'plus/minus symbol',               'html-special-characters-helper' ),
					'&micro;'  => __( 'Micro',                           'html-special-characters-helper' ),
					'&forall;' => __( 'for all',                         'html-special-characters-helper' ),
					'&exist;'  => __( 'there exists',                    'html-special-characters-helper' ),
					'&there4;' => __( 'therefore triangle',              'html-special-characters-helper' ),
					'&radic;'  => __( 'square root radical',             'html-special-characters-helper' ),
					'&infin;'  => __( 'infinity',                        'html-special-characters-helper' ),
					'&int;'    => __( 'integral sign',                   'html-special-characters-helper' ),
					'&part;'   => __( 'partial differential',            'html-special-characters-helper' ),
					'&sdot;'   => __( 'dot operator',                    'html-special-characters-helper' ),
					'&prime;'  => __( 'single prime',                    'html-special-characters-helper' ),
					'&Prime;'  => __( 'double prime',                    'html-special-characters-helper' ),
					'&sum;'    => __( 'n-ary summation',                 'html-special-characters-helper' ),
					'&prod;'   => __( 'n-ary product',                   'html-special-characters-helper' ),
					'&permil;' => __( 'per mil (1/1000th)',              'html-special-characters-helper' ),
					'&perp;'   => __( 'orthogonal to / perpendicular',   'html-special-characters-helper' ),
					'&ang;'    => __( 'angle',                           'html-special-characters-helper' ),
					'&and;'    => __( 'logical and',                     'html-special-characters-helper' ),
					'&or;'     => __( 'logical or',                      'html-special-characters-helper' ),
					'&cap;'    => __( 'intersection',                    'html-special-characters-helper' ),
					'&cup;'    => __( 'union',                           'html-special-characters-helper' ),
					'&empty;'  => __( 'empty set',                       'html-special-characters-helper' ),
					'&nabla;'  => __( 'nabla, backward difference',      'html-special-characters-helper' ),
					'&frasl;'  => __( 'fraction slash',                  'html-special-characters-helper' ),
					'&sup1;'   => __( 'superscript one',                 'html-special-characters-helper' ),
					'&sup2;'   => __( 'superscript two - squared',       'html-special-characters-helper' ),
					'&sup3;'   => __( 'superscript three - cubed',       'html-special-characters-helper' ),
					'&frac14;' => __( 'fraction one quarter (1/4)',      'html-special-characters-helper' ),
					'&frac12;' => __( 'fraction one half (1/2)',         'html-special-characters-helper' ),
					'&frac34;' => __( 'fraction three quarters (3/4)',   'html-special-characters-helper' ),
					'&ordf;'   => __( 'feminine ordinal indicator',      'html-special-characters-helper' ),
					'&ordm;'   => __( 'masculine ordinal indicator',     'html-special-characters-helper' ),
				),
				'symbols' => array(
					'name'     => __( 'Symbols',                         'html-special-characters-helper' ),
					'&cedil;'  => __( 'cedilla',                         'html-special-characters-helper' ),
					'&dagger;' => __( 'dagger',                          'html-special-characters-helper' ),
					'&Dagger;' => __( 'double dagger',                   'html-special-characters-helper' ),
					'&larr;'   => __( 'left arrow',                      'html-special-characters-helper' ),
					'&uarr;'   => __( 'up arrow',                        'html-special-characters-helper' ),
					'&rarr;'   => __( 'right arrow',                     'html-special-characters-helper' ),
					'&darr;'   => __( 'down arrow',                      'html-special-characters-helper' ),
					'&harr;'   => __( 'left-right arrow',                'html-special-characters-helper' ),
					'&crarr;'  => __( 'carriage return',                 'html-special-characters-helper' ),
					'&lArr;'   => __( 'left double arrow',               'html-special-characters-helper' ),
					'&uArr;'   => __( 'up double arrow',                 'html-special-characters-helper' ),
					'&rArr;'   => __( 'right double arrow',              'html-special-characters-helper' ),
					'&dArr;'   => __( 'down double arrow',               'html-special-characters-helper' ),
					'&hArr;'   => __( 'left-right double arrow',         'html-special-characters-helper' ),
					'&loz;'    => __( 'lozenge',                         'html-special-characters-helper' ),
					'&clubs;'  => __( 'clubs',                           'html-special-characters-helper' ),
					'&hearts;' => __( 'hearts',                          'html-special-characters-helper' ),
					'&diams;'  => __( 'diamonds',                        'html-special-characters-helper' ),
					'&spades;' => __( 'spades',                          'html-special-characters-helper' ),
				),
				'greek' => array(
					'name'      => __( 'Greek',                          'html-special-characters-helper' ),
					'&Alpha;'   => __( 'Greek capital letter alpha',     'html-special-characters-helper' ),
					'&Beta;'    => __( 'Greek capital letter beta',      'html-special-characters-helper' ),
					'&Gamma;'   => __( 'Greek capital letter gamma',     'html-special-characters-helper' ),
					'&Delta;'   => __( 'Greek capital letter delta',     'html-special-characters-helper' ),
					'&Epsilon;' => __( 'Greek capital letter epsilon',   'html-special-characters-helper' ),
					'&Zeta;'    => __( 'Greek capital letter zeta',      'html-special-characters-helper' ),
					'&Eta;'     => __( 'Greek capital letter eta',       'html-special-characters-helper' ),
					'&Theta;'   => __( 'Greek capital letter theta',     'html-special-characters-helper' ),
					'&Iota;'    => __( 'Greek capital letter iota',      'html-special-characters-helper' ),
					'&Kappa;'   => __( 'Greek capital letter kappa',     'html-special-characters-helper' ),
					'&Lambda;'  => __( 'Greek capital letter lambda',    'html-special-characters-helper' ),
					'&Mu;'      => __( 'Greek capital letter mu',        'html-special-characters-helper' ),
					'&Nu;'      => __( 'Greek capital letter nu',        'html-special-characters-helper' ),
					'&Xi;'      => __( 'Greek capital letter xi',        'html-special-characters-helper' ),
					'&Omicron;' => __( 'Greek capital letter omicron',   'html-special-characters-helper' ),
					'&Pi;'      => __( 'Greek capital letter pi',        'html-special-characters-helper' ),
					'&Rho;'     => __( 'Greek capital letter rho',       'html-special-characters-helper' ),
					'&Sigma;'   => __( 'Greek capital letter sigma',     'html-special-characters-helper' ),
					'&Tau;'     => __( 'Greek capital letter tau',       'html-special-characters-helper' ),
					'&Upsilon;' => __( 'Greek capital letter upsilon',   'html-special-characters-helper' ),
					'&Phi;'     => __( 'Greek capital letter phi',       'html-special-characters-helper' ),
					'&Chi;'     => __( 'Greek capital letter chi',       'html-special-characters-helper' ),
					'&Psi;'     => __( 'Greek capital letter psi',       'html-special-characters-helper' ),
					'&Omega;'   => __( 'Greek capital letter omega',     'html-special-characters-helper' ),
					'&alpha;'   => __( 'Greek small letter alpha',       'html-special-characters-helper' ),
					'&beta;'    => __( 'Greek small letter beta',        'html-special-characters-helper' ),
					'&gamma;'   => __( 'Greek small letter gamma',       'html-special-characters-helper' ),
					'&delta;'   => __( 'Greek small letter delta',       'html-special-characters-helper' ),
					'&epsilon;' => __( 'Greek small letter epsilon',     'html-special-characters-helper' ),
					'&zeta;'    => __( 'Greek small letter zeta',        'html-special-characters-helper' ),
					'&eta;'     => __( 'Greek small letter eta',         'html-special-characters-helper' ),
					'&theta;'   => __( 'Greek small letter theta',       'html-special-characters-helper' ),
					'&iota;'    => __( 'Greek small letter iota',        'html-special-characters-helper' ),
					'&kappa;'   => __( 'Greek small letter kappa',       'html-special-characters-helper' ),
					'&lambda;'  => __( 'Greek small letter lambda',      'html-special-characters-helper' ),
					'&mu;'      => __( 'Greek small letter mu',          'html-special-characters-helper' ),
					'&nu;'      => __( 'Greek small letter nu',          'html-special-characters-helper' ),
					'&xi;'      => __( 'Greek small letter xi',          'html-special-characters-helper' ),
					'&omicron;' => __( 'Greek small letter omicron',     'html-special-characters-helper' ),
					'&pi;'      => __( 'Greek small letter pi',          'html-special-characters-helper' ),
					'&rho;'     => __( 'Greek small letter rho',         'html-special-characters-helper' ),
					'&sigmaf;'  => __( 'Greek small letter final sigma', 'html-special-characters-helper' ),
					'&sigma;'   => __( 'Greek small letter sigma',       'html-special-characters-helper' ),
					'&tau;'     => __( 'Greek small letter tau',         'html-special-characters-helper' ),
					'&upsilon;' => __( 'Greek small letter upsilon',     'html-special-characters-helper' ),
					'&phi;'     => __( 'Greek small letter phi',         'html-special-characters-helper' ),
					'&chi;'     => __( 'Greek small letter chi',         'html-special-characters-helper' ),
					'&psi;'     => __( 'Greek small letter psi',         'html-special-characters-helper' ),
					'&omega;'   => __( 'Greek small letter omega',       'html-special-characters-helper' ),
				)
			);
		}

		return $category ?
			( isset( self::$characters[ $category ] ) ? self::$characters[ $category ] : array() ) :
			self::$characters;
	}

	/**
	 * Returns an associative array of all the categories of HTML special characters, their entities/codes, and their descriptions.
	 *
	 * @param string|null $category Optional. The name of the sub-category of codes to return. Default of null returns all.
	 * @return array      Array of HTML special characters.
	 */
	public static function html_special_characters( $category = null ) {
		$characters = apply_filters( 'c2c_html_special_characters', self::get_default_html_special_characters(), $category );

		if ( $category ) {
			$characters = isset( $characters[ $category ] ) ? $characters[ $category ] : array();
		}

		return $characters;
	}

	/**
	 * Adds the content for the meta box.
	 *
	 * Need this function instead of having the action directly call show_html_special_characters_content() because
	 * the action sends over multiple arguments that we don't want. Since show_html_special_characters() also calls
	 * show_html_special_characters_content() we can't just have it ignore arguments.
	 */
	public static function meta_box_content() {
		self::show_html_special_characters_content();
	}

	/**
	 * Outputs the HTML special characters listing.
	 *
	 * @param bool    $echo Optional. Echo the output? Default true.
	 * @return string The listing.
	 */
	protected static function show_html_special_characters_content( $echo = true ) {
		$codes = self::html_special_characters();
		$innards = '';
		$moreinnards = '<dl id="morehtmlspecialcharacters">';
		$i = 0;
		foreach ( array_keys( $codes ) as $cat ) {
			$label = isset( $codes[ $cat ][ 'name'] ) ? $codes[ $cat ][ 'name'] : $cat;
			if ( 'common' != $cat ) {
				$moreinnards .= "<dt>{$label}:</dt><dd>";
			}

			foreach ( $codes[ $cat ] as $code => $description ) {
				if ( 'name' === $code ) {
					continue;
				}
				$ecode = str_replace( '&', '&amp;', esc_attr( $code ) );
				$description = esc_attr( $description );
				$item = "<acronym onclick=\"send_to_editor('$ecode');\" title='$ecode $description'>$code</acronym> ";
				if ( 'common' == $cat ) {
					$innards .= $item;
				} else {
					$moreinnards .= $item;
				}
			}

			if ( 'common' != $cat ) {
				$moreinnards .= '</dd>';
			}
		}
		$moreinnards .= '</dl>';
		$innards = '<div class="htmlspecialcharacter"><span id="commoncodes">' . $innards . '</span>';
		$innards .= '<a href="#" class="htmlspecialcharacter_helplink" title="'
			. esc_attr( __( 'Click to toggle display of help', 'html-special-characters-helper' ) ) . '">'
			. __( 'Help?', 'html-special-characters-helper' )
			. '</a>';
		$innards .= ' ';
		$innards .= '<a href="#" class="htmlspecialcharacter_morelink" title="'
			. esc_attr( __( 'Click to toggle the display of more special characters', 'html-special-characters-helper' ) ) . '">'
			. __( 'See <span id="htmlhelper_more">more</span><span id="htmlhelper_less">less</span>', 'html-special-characters-helper' )
			. '</a>';
		$innards .= $moreinnards;
		$innards .= '<p id="htmlhelperhelp">'
			. __( 'Click to insert character into post. Mouse-over character for more info. Some characters may not display in older browsers.', 'html-special-characters-helper' )
			. '</p></div>';

		if ( $echo ) {
			echo $innards;
		}

		return $innards;
	}

	/**
	 * Outputs a wrapper around the HTML special characters listing.
	 */
	public static function show_html_special_characters() {
		$innards = self::show_html_special_characters_content( false );
		$title   = self::$title;

		echo <<<HTML
		<fieldset id="htmlspecialcharacterhelper" class="dbx-box">
			<h3 class="dbx-handle">{$title}</h3>
			<div class="dbx-content">
				$innards
			</div>
		</fieldset>

HTML;
	}

	/**
	 * Registers styles.
	 *
	 * @since 1.8
	 */
	public static function register_styles() {
		wp_register_style( __CLASS__ . '_admin', plugins_url( 'assets/admin.css', __FILE__ ), array(), self::version() );
	}

	/**
	 * Enqueues stylesheets.
	 *
	 * @since 1.8
	 */
	public static function enqueue_admin_css() {
		wp_enqueue_style( __CLASS__ . '_admin' );
	}

	/**
	 * Enqueues JS.
	 *
	 * @since 1.8
	 */
	public static function enqueue_admin_js() {
		wp_enqueue_script( __CLASS__ . '_admin', plugins_url( 'assets/admin.js', __FILE__ ), array( 'jquery' ), self::version(), true );
	}

} // end c2c_HTMLSpecialCharactersHelper

c2c_HTMLSpecialCharactersHelper::init();

endif; // end if !class_exists()
