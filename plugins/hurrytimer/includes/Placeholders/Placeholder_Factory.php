<?php

namespace Hurrytimer\Placeholders;

use Hurrytimer\Campaign;

class Placeholder_Factory
{

    /**
     * Parse placeholders in the given string.
     *
     * @param string $string
     * @param Campaign $campaign
     * @return string
     */
    public static function parse( $string, $campaign )
    {
        $replacements = [];

        $placeholders = static::find_placeholder( $string );

        foreach ( $placeholders as $variabele => $placeholder ) {

            $options = static::parse_placeholder( $placeholder );
            
            $option = implode('_', array_map('ucfirst', explode('_', $options[0])));

            $class = __NAMESPACE__ . '\\' . $option . '_Placeholder';

            if ( class_exists( $class ) ) {

                /**
                 * @var Placeholder $placeholder_object
                 */
                $placeholder_object = ( new $class( $campaign ) );
                $replacements[$variabele] = $placeholder_object->get_value( $options[ 1 ] );
            }
        }

        return str_replace( array_keys( $replacements ), array_values( $replacements ), $string );

    }

    /**
     * Parse placeholder and options.
     *
     * @param $placeholder
     * @return array
     */
    private static function parse_placeholder( $placeholder )
    {
        $options = explode( '|', $placeholder );
        $name = $options[ 0 ];
        array_shift( $options );
        return [ $name, $options ];
    }

    /**
     * @param string $string
     * @return array
     */
    private static function find_placeholder( $string )
    {
        preg_match_all( '/\{{1,2}([a-z\|\_?]+)\}{1,2}/', $string, $matches );
        return array_combine($matches[0], $matches[1]);
    }

}