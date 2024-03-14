<?php
/**
 * Replaces links to the current page with an element of your choice.
 *
 * Adds a custom class name and a title attribute.
 * Link elements are dropped completely.
 *
 * Usage:
 * <code>
 * $Remove_Redundant_Links = new Remove_Redundant_Links;
 * ob_start( array ( $Remove_Redundant_Links, 'convert' ) );
 * // Your markup here
 * </code>
 *
 * @todo Add before and after options?
 * @todo Mark the parent element?
 *
 * @author  Thomas Scholz http://toscho.de
 * @version 1.4 (24.12.2010)
 * @license GPL 2
 */
class Remove_Redundant_Links
{
	public
		$settings      = array (
			// class name for the replacment
			'class'                            => 'current'
			// new element. may be 'span', 'strong' or just 'a'
		,	'replace_a'                        => 'a'
			// if FALSE, no title attribute will be set.
		,	'title'                            => 'You are here.'
			// if you have non ASCII characters in your URI
		,	'charset'                          => 'utf-8'
			// defaults to $_SERVER['REQUEST_URI']
			// you may overwrite the value
		,	'current_request'                  => FALSE
			// defaults to $_SERVER['HTTP_HOST']
		,	'host_name'                        => FALSE
			// defaults to '', set 's' to force the value to TRUE
		,	'https'                            => FALSE
			// combination of the three former values
			// will overwrite all of them
		,	'server_prefix'                    => FALSE
			// strip the prefix on POST or PUT?
			// Note that the request URI will be stripped on GET only.
		,	'always_strip_server_prefix'       => TRUE
			// strip the prefix for all links?
			// Set to FALSE to keep links to other pages as they are.
		,	'strip_server_prefix_on_all_links' => TRUE
		)
		// holds the result
		// @see get_parsed_string()
	,	$parsed_string = ''
	;

	/**
	 * Constructor
	 *
	 * @param array $settings
	 */
	public function __construct( $settings = array () )
	{
		$this->set_options( $settings );
		$this->prepare_current_uri();
	}

	/**
	 * Public access to alter the settings.
	 *
	 * @param  array $settings
	 * @return void
	 */
	public function set_options( $settings = array () )
	{
		$this->settings = array_merge( $this->settings, (array) $settings );
	}

	/**
	 * Prepares current request URI, server name and protocol.
	 *
	 * @return void
	 */
	public function prepare_current_uri()
	{
		if ( ! $this->settings['current_request'] )
		{
			// $_SERVER['REQUEST_URI'] may be wrong in some cases,
			// for example on 1 & 1
			$this->settings['current_request'] =
				isset ( $_SERVER['REDIRECT_SCRIPT_URI'] )
					? $_SERVER['REDIRECT_SCRIPT_URI']
					: $_SERVER['REQUEST_URI'];
		}

		// Preset by constructor call.
		if ( is_string( $this->settings['server_prefix'] ) )
		{
			return;
		}

		! $this->settings['host_name'] and
			$this->settings['host_name'] = $_SERVER['HTTP_HOST'];

		! $this->settings['https'] and
			$this->settings['https'] = empty ( $_SERVER['HTTPS'] ) ? '' : 's';

		$this->settings['server_prefix'] = 'http' . $this->settings['https']
			. '://' . $this->settings['host_name'];
	}

	/**
	 * Replaces links.
	 *
	 * May be used as a handler for ob_start():
	 * <code>ob_start( array ( $Remove_Redundant_Links, 'convert' ) );</code>
	 *
	 * @param  string $text Input markup
	 * @return string
	 */
	public function convert( $text )
	{
		// <a>
		// Will fail on <a><strong><a>foo</a></strong></a>
		// as allowed by the XHTML DTD.
		// Use HTML5 or validate against a schema!
		$this->parsed_string = preg_replace_callback (
			'~<a\s+(.*)</a>~Umis'
		,	array ( $this, 'el_a_callback' )
		,	$text
		);

		// <link>
		$this->parsed_string = preg_replace_callback (
			'~<link\s*([^<]*)~mis'
		,	array ( $this, 'el_link_callback' )
		,	$this->parsed_string
		);

		return $this->parsed_string;
	}

	/**
	 * Callback function for convert() on <link>.
	 *
	 * @param  string $link
	 * @return string
	 */
	public function el_link_callback( $link )
	{
		// No href attribute at all. Stop immediately.
		if ( FALSE === stristr( $link[1], 'href' ) )
		{
			return  $link[0];
		}

		$converted = $this->parse_attributes( $link[1], 'link' );

		// Link elements don’t need to be replaced. We just drop them.
		if ( ! $converted )
		{
			return '';
		}

		return '<link' . $converted;
	}

	/**
	 * Callback function for convert() on <a>.
	 *
	 * @param  string $a
	 * @return string
	 */
	public function el_a_callback( $a )
	{
		// No href attribute at all. Stop immediately.
		if ( FALSE === strpos( $a[0], 'href' ) )
		{
			return $a[0];
		}

		// Replace the original element.
		return '<' . $this->settings['replace_a'] . ' '
			. trim( $this->parse_attributes( $a[1] ) )
			. '</' . $this->settings['replace_a'] . '>';
	}

	/**
	 * Reads the attributes and changes them if needed.
	 * Used by the callback functions.
	 *
	 * @param  string $str String of attributes
	 * @param  string $type 'a' or 'link'
	 * @return string|FALSE FALSE for link elements only.
	 */
	public function parse_attributes( $str, $type='a' )
	{
		// split the string into parseable parts
		$string_arr = preg_split(
			'~(
			  \"[^\"]*\"    # "values in double quotes"
			| \'[^\']*\'    # \'values in single quotes\'
			| \W            # all other non word characters (=, /)
			)~umx'
		,	$str
		,	-1
		,	PREG_SPLIT_DELIM_CAPTURE ^ PREG_SPLIT_NO_EMPTY
		);

		// Now the parser.
		$attr_arr = array ();
		$last     = FALSE;

		foreach ( $string_arr as $index => $match )
		{
			// End of the start tag
			if ( '>' == $match )
			{
				break;
			}

			// Neither name nor value. Drop it.
			if ( '=' == $match or '' == trim( $match ) )
			{
				unset ( $string_arr[$index] );
				continue;
			}

			if ( FALSE === $last )  // Attribute name.
			{
				$last = $match;
			}
			else                    // Attribute value
			{
				$attr_arr[$last] = trim($match, '"\'');
				$last = FALSE;
			}

			unset ( $string_arr[$index] );
		}

		// We had something like: <a title=href>not an anchor</a>.
		if ( ! isset ( $attr_arr['href'] ) )
		{
			return $str;
		}

		// Don’t touch <link rel=canonical
		if ( 'link' == $type and isset ( $attr_arr['rel'] )
			and FALSE !== strpos( $attr_arr['rel'], 'canonical' ) )
		{
			return ' ' . $str;
		}

		// Don’t touch <a rel=bookmark
		if ( 'a' == $type and isset ( $attr_arr['rel'] )
			and FALSE !== strpos( $attr_arr['rel'], 'bookmark' ) )
		{
			return ' ' . $str;
		}

		// There is an empty attribute left. Should not be possible on links.
		// Anyway … we put it before the remaining string_arr.
		if ( FALSE !== $last )
		{
			array_unshift( $string_arr, " $last" );
		}

		// Check and alter the href attribute.
		$attr_arr['href'] = $this->prepare_href( $attr_arr['href'] );

		if ( ! $attr_arr['href'] )
		{
			if ( 'link' == $type )
			{
				return FALSE;
			}

			// From now on we handle <a> elements pointing to the current
			// page only.

			// Drop the reference
			unset ( $attr_arr['href'] );

			// Set title.
			if ( ! $this->settings['title'] )
			{
				unset ( $attr_arr['title'] );
			}
			else
			{
				$attr_arr['title'] = $this->settings['title'];
			}

			// Add a class name.
			if ( ! empty ( $this->settings['class'] ) )
			{
				$attr_arr['class'] = $this->add_class( $attr_arr );
			}
		}

		// Recreate the string and append the remaining data.
		return $this->attr_to_str( $attr_arr ) . implode( '', $string_arr );
	}

	/**
	 * Converts a name => value array into html attributes
	 *
	 * @param  array $attr_arr
	 * @return string
	 */
	public function attr_to_str( $attr_arr )
	{
		$out = '';

		foreach ( $attr_arr as $name => $value )
		{
			$val  = htmlspecialchars( $value, ENT_QUOTES, $this->settings['charset'], FALSE );
			$out .= " $name='$val'";
		}

		return $out;
	}

	/**
	 * Converts the value of the href attribute.
	 *
	 * @param  string $href
	 * @return string|FALSE
	 */
	public function prepare_href( $href )
	{
		$href = $this->strip_server_prefix( trim( $href ) );

			// Internal anchor.
		if ( '#' == $href[0]
		 	// Doesn’t point to this page.
			or $href !== $this->settings['current_request']
			// Wrong request method.
			or 'GET' !== $_SERVER['REQUEST_METHOD']
		)
		{
			return $href;
		}

		$href = str_replace( $this->settings['current_request'], '', $href );

		return empty ( $href ) ? FALSE : $href;
	}

	/**
	 * Changes http://example.com/foo to /foo
	 *
	 * @param  string $href
	 * @return string
	 */
	public function strip_server_prefix( $href )
	{
		// Nothing to do.
		if ( FALSE === strpos( $href, $this->settings['server_prefix'] ) )
		{
			return $href;
		}

		$original = $href;

		$prefix_length = mb_strlen(
			$this->settings['server_prefix']
		,	$this->settings['charset']
		);

		if ( 'GET' == $_SERVER['REQUEST_METHOD']
			or $this->settings['always_strip_server_prefix']
		)
		{
			$href = substr_replace( $href, '', 0, $prefix_length );

			// href='http://example.com' may be href='' now.
			empty ( $href ) and $href = '/';
		}

		// Link to another page and stripping forbidden.
		if ( FALSE == $this->settings['strip_server_prefix_on_all_links']
			and $this->settings['current_request'] !== $href
		)
		{
			return $original;
		}

		return $href;
	}

	// Adds a class name if needed.
	public function add_class( $attr_arr )
	{
		$class = isset ( $attr_arr['class'] )
			? $attr_arr['class'] . ' ' . $this->settings['class']
			: $this->settings['class'];

		return $class;
	}

	/**
	 * Merely for debugging purposes.
	 *
	 * @return string
	 */
	public function get_parsed_string()
	{
		return $this->parsed_string;
	}
}