<?php

/**
 * Extracts the path of an absolute URL.
 *
 * @param string $URL The URL to get the path of.
 *
 * @return string The URL's path.
 */
function extract_path_from_URL( $URL ) {
    return preg_match( '#^https?://[^/]+(.*)$#', $URL, $matches ) === 1 ? $matches[1] : null;
}

/**
 * Extracts the paths of many absolute URLs.
 *
 * @param string[] $URLs The URLs to get the paths of.
 *
 * @return string[] The URLs's paths.
 */
function extract_paths_from_URLs( array $URLs ) {
    return array_map( 'extract_path_from_URL', $URLs );
}

/**
 * Strips away the protocol of a URL.
 *
 * @param string|string[] $URL_or_URLs
 *
 * @return string|string[]
 */
function strip_protocol( $URL_or_URLs ) {
    return preg_replace( '#^https?://#', '', $URL_or_URLs );
}

/**
 * Ensures the configured site URL ends with a slash.
 *
 * @param string $URL
 * @return string
 */
function ensure_ending_slash( $URL ) {
    return rtrim($URL, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
}

/**
 * Gets a WP option as integer.
 *
 * @param string $option
 * @param int $default
 *
 * @return int
 */
function get_int_option( $option, $default = - 1 ) {
    $strval = get_option( $option );
    if ( ! is_string( $strval ) ) {
        return $default;
    }

    $strval = trim( $strval );
    if ( ! is_numeric( $strval ) ) {
        return $default;
    }

    return (int) $strval;
}

/**
 * Formats a number of requests.
 *
 * @param int $requests
 * @param int $decimals
 *
 * @return string
 */
function requests_format( $requests, $decimals = 0 ) {
    if ( $requests < 5000 ) {
        return number_format_i18n( $requests );
    }

    $quant = [
        'B' => 1e9,
        'M' => 1e6,
        'K' => 1e3,
        ''  => 1e0,
    ];

    if ( 0 === $requests ) {
        return number_format_i18n( 0, $decimals );
    }

    foreach ( $quant as $unit => $mag ) {
        if ( doubleval( $requests ) >= $mag ) {
            return number_format_i18n( $requests / $mag, $decimals ) . $unit;
        }
    }

    return false;
}

function format_difference( $first, $second, $decimals = 0 ) {
    $diff = $first - $second;
    $str  = number_format_i18n( abs( $diff ), $decimals );

    if ( $diff > 0 ) {
        return '&plus; ' . $str;
    }
    if ( $diff < 0 ) {
        return '&minus; ' . $str;
    }

    return '&plusmn; ' . $str;
}

/**
 * @param int $price
 *
 * @return string
 */
function currency_format( $price ) {
    return number_format_i18n( $price / 100, 2 ) . ' €';
}

/**
 * @param string $camel_case
 *
 * @return string
 */
function camel_case_to_human( $camel_case ) {
    if ( $camel_case === 'ttfb' ) {
        return 'TTFB';
    }

    return ucfirst( preg_replace_callback( '#([A-Z])#', function ( $args ) {
        return ' ' . $args[1];
    }, $camel_case ) );
}

/**
 * @param string $name
 *
 * @return string
 */
function abbreviate( $name ) {
    switch ( $name ) {
        case 'Time To First Byte':
            return 'TTFB';
        case 'First Meaningful Paint':
            return 'FMP';
        case 'Speed Index':
            return 'SI';
        default:
            return $name;
    }
}

/**
 * @param \DateTime|null $date
 * @param int $seconds
 *
 * @return bool
 */
function not_older_than( $date, $seconds ) {
    if ( $date instanceof \DateTime ) {
        $now = new \DateTime();

        return $now->getTimestamp() - $date->getTimestamp() <= $seconds;
    }

    return false;
}

/**
 * @param int $traffic Traffic in MB.
 *
 * @return string
 */
function traffic_format( $traffic ) {
    return size_format( $traffic * 1024 * 1024 );
}

/**
 * @param int $max
 * @param int $now
 *
 * @return string
 */
function percentage_format( $max, $now ) {
    return number_format_i18n( ( $now / $max ) * 100, 2 ) . ' %';
}

/**
 * Checks whether an array is associative.
 *
 * @param array $array The array to check.
 *
 * @return bool True, if the array is associative.
 */
function is_assoc(array $array) {
    if (array() === $array) return false;
    return array_keys($array) !== range(0, count($array) - 1);
}
