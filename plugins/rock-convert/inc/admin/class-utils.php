<?php //phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
/**
 * Util is the class to facilitate the implementation of more generic features
 *
 * @package    Rock_Convert\Inc\Admin
 * @link       https://rockcontent.com
 * @since      2.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\Inc\Admin;

/**
 * Utils class
 */
class Utils {

	/**
	 * Get data analytics
	 *
	 * @param int  $post_id The id of post.
	 * @param bool $enabled This data informs if analytics are enable or not.
	 *
	 * @return array
	 */
	public static function get_post_analytics( $post_id, $enabled = true ) {
		$custom_fields = get_post_custom( $post_id );

		if ( $enabled ) {
			$views  = intval( self::getArrayValue( $custom_fields, '_rock_convert_cta_views', 0 ) );
			$clicks = intval( self::getArrayValue( $custom_fields, '_rock_convert_cta_clicks', 0 ) );
			$ctr    = 0 === $views ? 0 : ( ( $clicks / $views ) * 100 );
		} else {
			$views  = 100000;
			$clicks = 2000;
			$ctr    = 10.20;
		}

		return array(
			'views'  => $views,
			'clicks' => $clicks,
			'ctr'    => $ctr,
		);
	}

	/**
	 * Safelly get index from array
	 *
	 * @param array  $array Array of data.
	 * @param string $index The index of arrays.
	 * @param null   $subindex The subindex of array children.
	 * @param null   $default Default value.
	 *
	 * @return null
	 */
	public static function getArrayValue( $array, $index, $subindex = null, $default = null ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
		if ( isset( $subindex ) ) {
			return isset( $array[ $index ] ) && isset( $array[ $index ][ $subindex ] ) ?
				$array[ $index ][ $subindex ] : $default;
		}

		return isset( $array[ $index ] ) ? sanitize_text_field( wp_unslash( $array[ $index ] ) ) : $default;
	}

	/**
	 * Currency formatter
	 *
	 * @param mixed $num A number to be formatted.
	 *
	 * @return float|string
	 */
	public static function thousandsCurrencyFormat( $num ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

		if ( $num > 1000 ) {

			$x               = round( $num );
			$x_number_format = number_format( $x );
			$x_array         = explode( ',', $x_number_format );
			$x_parts         = array( 'k', 'm', 'b', 't' );
			$x_count_parts   = count( $x_array ) - 1;
			$x_display       = $x;
			$x_display       = $x_array[0] . ( 0 !== (int) $x_array[1][0] ? '.' . $x_array[1][0] : '' );
			$x_display      .= $x_parts[ $x_count_parts - 1 ];

			return $x_display;

		}

		return $num;
	}

	/**
	 * Builds an http query string.
	 *
	 * @param array $query // of key value pairs to be used in the query.
	 *
	 * @return string      // http query string.
	 *
	 * @since 2.1.2
	 **/
	public static function build_http_query( $query ) {
		return http_build_query( $query );
	}

	/**
	 * Read backward line
	 *
	 * @param string  $filename Name of file.
	 * @param mixed   $lines Quantity.
	 * @param boolean $revers Recursive.
	 * @return mixed
	 */
	public static function read_backward_line( $filename, $lines, $revers = false ) {
		$offset = -1;
		$c      = '';
		$read   = '';
		$i      = 0;

		$fp = @fopen( $filename, 'r' ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_fopen
		while ( $lines && fseek( $fp, $offset, SEEK_END ) >= 0 ) {
			$c = fgetc( $fp );
			if ( "\n" === $c || "\r" === $c ) {
				$lines--;
				if ( $revers ) {
					$read[ $i ] = strrev( $read[ $i ] );
					$i++;
				}
			}
			if ( $revers ) {
				$read[ $i ] .= $c;
			} else {
				$read .= $c;
			}
			$offset--;
		}
		fclose( $fp ); //phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose
		if ( $revers ) {
			if ( "\n" === $read[ $i ] || "\r" === $read[ $i ] ) {
				array_pop( $read );
			} else {
				$read[ $i ] = strrev( $read[ $i ] );
			}

			return implode( '', $read );
		}

		return strrev( rtrim( $read, "\n\r" ) );
	}

	/**
	 * Logger
	 *
	 * @param string $message Message to be logged.
	 */
	public static function logError( $message ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
		$date      = gmdate( 'Y-m-d h:m:s' );
		$file_path = plugin_dir_path( __FILE__ ) . 'logs' . DIRECTORY_SEPARATOR;
		$file_name = 'debug.log';
		$full_path = $file_path . $file_name;
		if ( ! file_exists( $file_path ) ) {
			mkdir( $file_path, 0755 );
		}
		$level = 'warning';

		$message = "[{$date}] [{$level}] " . $message . PHP_EOL;

		error_log( $message, 3, $full_path );//phpcs:ignore
	}

	/**
	 * Build the Rock Convert link based on the chosen WordPress language
	 *
	 * @return string
	 */
	public static function build_convert_link() {
		$current_lang = get_locale();
		$link         = '';
		switch ( $current_lang ) {
			case 'pt_BR':
				$link = 'https://stage.rockcontent.com/br/plugin-de-conversao/?';
				break;

			case 'en_US':
				$link = 'https://stage.rockcontent.com/conversion-plugin/?';
				break;

			case 'es_MX':
				$link = 'https://stage.rockcontent.com/es/complementos-de-conversion/?';
				break;

			default:
				$link = 'https://stage.rockcontent.com/conversion-plugin/?';
				break;
		}

		return $link;
	}

	/**
	 * A custom sanitization function that will take the incoming input, and sanitize
	 * the input before handing it back to WordPress to save to the database.
	 *
	 * @since    3.0.0
	 *
	 * @param  array $input      The address input.
	 * @return array $new_input  The sanitized input.
	 */
	public static function sanitize_array( $input ) {

		$new_input = array();

		foreach ( $input as $key => $val ) {

			$new_input[ $key ] = ( isset( $input[ $key ] ) ) ?
			sanitize_text_field( $val ) :
			null;
		}

		return $new_input;

	}
}
