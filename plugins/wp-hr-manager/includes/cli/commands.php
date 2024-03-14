<?php
namespace WPHR\HR_MANAGER\CLI;

/**
 * wphr core CLI commands
 *
 * @since 1.2.2
 */
class Commands extends \WP_CLI_Command {

    /**
     * Activate single or multiple wphr modules
     *
     * ## OPTIONS
     *
     * <comma_separated_module_names>
     * : Comma separated wphr module names
     *
     * ## EXAMPLES
     *
     *     # Activate single module
     *     $ WPHR Manager module activate hrm
     *     Success: Activated module hrm
     *
     *     # Activate multiple modules
     *     $ WPHR Manager module activate crm,accounting
     *     Success: Activated modules crm, accounting
     *
     * @since 1.2.2
     *
     * @return void
     */
    public function module_activate( $args ) {
        list( $modules ) = $args;
        $modules         = explode( ',', $modules );

        $activated = wphr()->modules->activate_modules( $modules );

        if ( is_wp_error( $activated ) ) {
            \WP_CLI::error( $activated->get_error_message() );
        }

        $count   = count( $modules );
        $message = sprintf( _n( 'Aactivated module %s', 'Activated modules %s', $count, 'wphr' ), implode( ', ', $modules ) );

        \WP_CLI::success( $message );
    }

    /**
     * Deactivate single or multiple wphr modules
     *
     * ## OPTIONS
     *
     * <comma_separated_module_names>
     * : Comma separated wphr module names
     *
     * ## EXAMPLES
     *
     *     # Deactivate single module
     *     $ WPHR Manager module deactivate hrm
     *     Success: Deactivate module hrm
     *
     *     # Deactivate multiple modules
     *     $ WPHR Manager module deactivate crm,accounting
     *     Success: Deactivate modules crm, accounting
     *
     * @since 1.2.2
     *
     * @return void
     */
    public function module_deactivate( $args ) {
        list( $modules ) = $args;
        $modules         = explode( ',', $modules );

        $deactivated = wphr()->modules->deactivate_modules( $modules );

        if ( is_wp_error( $deactivated ) ) {
            \WP_CLI::error( $deactivated->get_error_message() );
        }

        $count   = count( $modules );
        $message = sprintf( _n( 'Deactivated module %s', 'Deactivated modules %s', $count, 'wphr' ), implode( ', ', $modules ) );

        \WP_CLI::success( $message );
    }

}

\WP_CLI::add_command( 'wphr module activate', [ '\WPHR\HR_MANAGER\CLI\Commands', 'module_activate' ] );
\WP_CLI::add_command( 'wphr module deactivate', [ '\WPHR\HR_MANAGER\CLI\Commands', 'module_deactivate' ] );
