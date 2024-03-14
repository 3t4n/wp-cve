<?php

/**
 * The file that defines model for return endpoint
 *
 * @link  https://furgonetka.pl
 * @since 1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controller/models
 */

/**
 * Class Furgonetka_Returns_Model - Managing connection to database and saves / load data to wp options
 *
 * @since      1.0.0
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controller/models
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class Furgonetka_Returns_Model
{
    /**
     * Target options name
     *
     * @var string
     */
    private static $tatget = 'furgonetka_returns_target';

    /**
     * Route options name
     *
     * @var string
     */
    private static $route = 'furgonetka_returns_route';

    /**
     * Active options name
     *
     * @var string
     */
    private static $active = 'furgonetka_returns_active';

    /**
     * Undocumented function
     *
     * @return string
     */
    public static function get_target_option_name() :string
    {
        return self::$tatget;
    }

    /**
     * Get route name
     *
     * @return string
     */
    public static function get_route_option_name(): string
    {
        return self::$route;
    }

    /**
     * Get active option name
     *
     * @return string
     */
    public static function get_active_option_name(): string
    {
        return self::$active;
    }

    /**
     * Save rewrite options
     *
     * @param string $target - target url.
     * @param string $route  - werbiste route.
     * @param int    $active - active/inactive.
     *
     * @return bool
     */
    public function save_rewrite_options( string $target, string $route, int $active ): bool
    {
        $succes_target = $this->save_or_update_option( self::get_target_option_name(), $target );
        $succes_active = $this->save_or_update_option( self::get_active_option_name(), $active );
        $succes_route  = $this->save_or_update_option( self::get_route_option_name(), $route );
        if ( ! $succes_target || ! $succes_active || ! $succes_route ) {
            return false;
        }
        return true;
    }

    /**
     * Delete options
     *
     * @return void
     */
    public function delete_rewrite_options(): void
    {
        delete_option( self::get_target_option_name() );
        delete_option( self::get_route_option_name() );
        delete_option( self::get_active_option_name() );
    }

    /**
     * Get all rewrite options
     *
     * @return array
     */
    public function get_rewrite_options(): array
    {
        $return           = array();
        $return['target'] = ! get_option( self::get_target_option_name() ) ?
            '' : get_option( self::get_target_option_name() );
        $return['route']  = ! get_option( self::get_route_option_name() ) ?
            '' : get_option( self::get_route_option_name() );
        $return['active'] = ! get_option( self::get_active_option_name() ) ?
            0 : intval( get_option( self::get_active_option_name() ) );

        return $return;
    }

    /**
     * Save options in DB
     *
     * @param mixed $option_name - option name to save in DB.
     * @param mixed $value       - saved value.
     *
     * @return bool
     */
    private function save_or_update_option( $option_name, $value ): bool
    {
        return update_option( $option_name, sanitize_text_field( $value ) );
    }
}
