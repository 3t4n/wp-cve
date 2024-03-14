<?php
/**
 * File Name: class-mo-saml-xpath.php
 * Description: This file will Filters an attribute name and value for save inclusion in an XPath query.
 *
 * @package miniorange-saml-20-single-sign-on\includes\lib\SAML2Core\Utils
 */

namespace RobRichards\XMLSecLibs\Utils;

/**
 * Class for Mo_SAML_XPath.
 * This Class will Filters an attribute name and value for save inclusion in an XPath query.
 */
class Mo_SAML_XPath {

	const ALPHANUMERIC          = '\w\d';
	const NUMERIC               = '\d';
	const LETTERS               = '\w';
	const EXTENDED_ALPHANUMERIC = '\w\d\s\-_:\.';

	const SINGLE_QUOTE = '\'';
	const DOUBLE_QUOTE = '"';
	const ALL_QUOTES   = '[\'"]';


	/**
	 * Filter an attribute value for save inclusion in an XPath query.
	 *
	 * @param string $value The value to filter.
	 * @param string $quotes The quotes used to delimit the value in the XPath query.
	 *
	 * @return string The filtered attribute value.
	 */
	public static function mo_saml_filter_attr_value( $value, $quotes = self::ALL_QUOTES ) {
		return preg_replace( '#' . $quotes . '#', '', $value );
	}


	/**
	 * Filter an attribute name for save inclusion in an XPath query.
	 *
	 * @param string $name The attribute name to filter.
	 * @param mixed  $allow The set of characters to allow. Can be one of the constants provided by this class, or a
	 *  custom regex excluding the '#' character (used as delimiter).
	 *
	 * @return string The filtered attribute name.
	 */
	public static function mo_saml_filter_attr_name( $name, $allow = self::EXTENDED_ALPHANUMERIC ) {
		return preg_replace( '#[^' . $allow . ']#', '', $name );
	}
}
