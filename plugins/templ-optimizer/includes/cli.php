<?php
defined('ABSPATH') or die();

class templOptimizerCli extends templOptimizer {

    function __construct() {

        // Include sub-modules
        $this->db = new templOptimizerDb();
        $this->tweaks = new templOptimizerTweaks();

        WP_CLI::add_command( 'templ-optimizer', array( $this, 'templ_optimizer_command' ) );
        
    }

    function templ_optimizer_command( $args, $assoc_args ) {

        // List all commands
        if( empty( $args ) ) {

            WP_CLI::line( $message = 'commands:' );
            WP_CLI::line( $message = '  - wp templ-optimizer db <command>' );
            return;
            
        }

        if( $args[0] === 'db' ) {

            // List all sub-commands of 'db'
            if( ! isset( $args[1] ) ) {

                WP_CLI::line( $message = 'commands:' );
                WP_CLI::line( $message = '  - wp templ-optimizer db optimize_all' );
                return;

            }

            // Perform all optimizations and print database size before and after
            if( $args[1] === 'optimize_all' ) {

                $before = $this->db->get_database_size();
                $this->db->optimize_all();
                $after = $this->db->get_database_size();

                WP_CLI::success( 'Before: ' . $before . ', after: ' . $after );
                return;

            }

        }
        
    }
    

}
