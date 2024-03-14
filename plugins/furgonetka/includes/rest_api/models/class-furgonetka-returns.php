<?php
/**
 * Class Furgonetka_Update_Order_Model - Managing connection to database and saves / load data to wp options
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

    public static function get_target_option_name() :string
    {
        return self::$tatget;
    }

    public static function get_route_option_name(): string
    {
        return self::$route;
    }

    public static function get_active_option_name(): string
    {
        return self::$active;
    }

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

    public function delete_rewrite_options(): void
    {
        delete_option( self::get_target_option_name() );
        delete_option( self::get_route_option_name() );
        delete_option( self::get_active_option_name() );
    }

    public function get_rewrite_options(): array
    {
        $return = array();
        $return['target'] = ! get_option( self::get_target_option_name() ) ?
            '' : get_option( self::get_target_option_name() );
        $return['route'] = ! get_option( self::get_route_option_name() ) ?
            '' : get_option( self::get_route_option_name() );
        $return['active'] = ! get_option( self::get_active_option_name() ) ?
            0 : intval( get_option( self::get_active_option_name() ));
        return $return;
    }

    private function save_or_update_option( $option_name, $value ): bool
    {
        return update_option( $option_name, sanitize_text_field( $value ) );
    }
}
