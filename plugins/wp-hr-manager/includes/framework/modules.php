<?php
namespace WPHR\HR_MANAGER\Framework;
use WPHR\HR_MANAGER\Framework\Traits\Hooker;

/**
 * Modules Class
 *
 * @package WPHR\HR_MANAGER\Framework
 */
class Modules {

    use Hooker;

    /**
     * Hold the modules
     *
     * @var
     */
    protected $modules;

    /**
     * initialize
     */
    public function __construct() {
        $this->init_modules();

        $this->action( 'wphr_switch_redirect_to', 'module_switch_redirect', 10, 2 );
    }

    /**
     * Initialize the modules
     *
     * @return void
     */
    public function init_modules() {
        $this->modules = [
            'hrm' => [
                'title'       => __( 'HR Management', 'wphr' ),
                'slug'        => 'wphr-hrm',
                'description' => __( 'Human Resource Management', 'wphr' ),
                'callback'    => '\WPHR\HR_MANAGER\HRM\Human_Resource',
                'modules'     => apply_filters( 'wphr_hr_modules', [ ] )
            ],
            'wphr-hr-frontend' => [
                'title'       => __( 'HR Frontend', 'wphr' ),
                'slug'        => 'wphr-hr-frontend',
                'description' => __( 'Human Resource Frontend', 'wphr' ),
                'callback'    => '\WPHR\HR_MANAGER\WP_HR_FRONTEND\Human_Resource',
                'modules'     => apply_filters( 'wphr_hr_modules', [ ] )
            ],
        ];
    }

    /**
     * Get all the registered modules
     *
     * @return mixed|void
     */
    public function get_modules() {
        return apply_filters( 'wphr_get_modules', $this->modules );
    }

    /**
     * Get a module
     *
     * @param $module
     *
     * @return bool
     */
    public function get_module( $module ) {
        if ( array_key_exists( $module, $this->modules ) ) {
            return $this->modules[ $module ];
        }

        return false;
    }

    /**
     * Get the first module as default module
     *
     * @return mixed
     */
    public function get_default_module() {
        return reset( $this->modules );
    }

    /**
     * Get the current mode of the admin UI
     *
     * @return bool|mixed
     */
    public function get_current_module() {
        global $current_user;

        $mode = get_user_meta( $current_user->ID, '_wphr_mode', true );

        if ( !empty( $mode ) && $this->get_module( $mode ) ) {
            return $this->get_module( $mode );
        }

        return $this->get_default_module();
    }

    /**
     * Redirect to the CRM/HRM dashboard page when switching from admin menu bar
     *
     * @param $url
     * @param $new_mode
     *
     * @return string|void
     */
    public function module_switch_redirect( $url, $new_mode ) {
        if ( 'crm' == $new_mode ) {
            return admin_url( 'admin.php?page=wphr-sales' );
        } elseif ( 'hrm' == $new_mode ) {
            return admin_url( 'admin.php?page=wphr-hr' );
        }

        return $url;
    }

    /**
     * Get module status
     *
     * @param string $module_key
     *
     * @since 0.1
     *
     * @return boolen
     */
    public function is_module_active( $module_key ) {
        $modules = $this->get_active_modules();

        if ( array_key_exists( $module_key, $modules ) ) {
            return true;
        }

        return false;
    }

    /**
     * Get active modules
     *
     * @since 0.1
     *
     * @return array
     */
    public function get_active_modules() {
        return get_option( 'wphr_modules', [] );
    }

    /**
     * Get inactive modules
     *
     * @since 0.1
     *
     * @return array
     */
    public function get_inactive_modules() {
        $all_modules    = $this->get_modules();
        $active_modules = $this->get_active_modules();

        foreach ( $active_modules as $key => $module ) {
            unset( $all_modules[$key] );
        }

        return $all_modules;
    }

    /**
     * Get modules by query pram
     *
     * @param string $tab
     *
     * @since 0.1
     *
     * @return array
     */
    public function get_query_modules( $tab = false ) {

        switch ( $tab ) {
            case 'active':
                return $this->get_active_modules();
                break;

            case 'inactive':
                return $this->get_inactive_modules();
                break;

            default:
                return $this->get_modules();
                break;
        }
    }

    /**
     * Check for valid wphr modules
     *
     * @since 1.2.2
     *
     * @param array $modules Name of the module or modules
     *
     * @return mixed WP_Error if an invalid name is given or true on success
     */
    public function is_valid_module( $modules ) {
        if ( ! is_array( $modules ) ) {
            $modules = [ $modules ];
        }

        $wphr_module_names = array_keys( $this->modules );

        foreach ( $modules as $module_name ) {
            if ( ! in_array( $module_name , $wphr_module_names ) ) {
                return new \WP_Error( 'invalid-module-name', sprintf( __( 'Invalid module name %s', 'wphr' ), $module_name ) );
            }
        }

        return true;
    }

    /**
     * Activate single or multiple modules
     *
     * @since 1.2.2
     *
     * @param array $modules Name of the module or modules
     *
     * @return mixed WP_Error if an invalid name is given or true on success
     */
    public function activate_modules( $modules ) {
        if ( ! is_array( $modules ) ) {
            $modules = [ $modules ];
        }

        $is_valid_module = $this->is_valid_module( $modules );

        if ( is_wp_error( $is_valid_module ) ) {
            return $is_valid_module;
        }

        $active_modules = $this->get_active_modules();

        foreach ( $modules as $module_name ) {
            if ( ! in_array( $module_name, $active_modules ) ) {
                $active_modules[ $module_name ] = $this->modules[ $module_name ];
            }
        }

        update_option( 'wphr_modules', $active_modules );

        return true;
    }

    /**
     * Deactivate single or multiple modules
     *
     * @since 1.2.2
     *
     * @param array $modules Name of the module or modules
     *
     * @return mixed WP_Error if an invalid name is given or true on success
     */
    public function deactivate_modules( $modules ) {
        if ( ! is_array( $modules ) ) {
            $modules = [ $modules ];
        }

        $is_valid_module = $this->is_valid_module( $modules );

        if ( is_wp_error( $is_valid_module ) ) {
            return $is_valid_module;
        }

        $active_modules = $this->get_active_modules();

        foreach ( $active_modules as $module_name => $active_module ) {
            if ( in_array( $module_name, $modules ) ) {
                unset( $active_modules[ $module_name ] );
            }
        }

        update_option( 'wphr_modules', $active_modules );

        return true;
    }

}
