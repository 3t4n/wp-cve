<?php
/**
 * Admin specific hooks
 */

/**
 * Triggers an action after plugin was updated to new version.
 *
 * @return void
 */
function auxpfo_after_plugin_update(){

    if( AUXPFO_VERSION !== get_transient( 'auxin_' . AUXPFO_SLUG . '_version' ) ){
        set_transient( 'auxin_' . AUXPFO_SLUG . '_version', AUXPFO_VERSION, MONTH_IN_SECONDS );

        do_action( 'auxin_plugin_updated', true, AUXPFO_SLUG, AUXPFO_VERSION, AUXPFO_BASE_NAME );
    }
}
add_action( "admin_init", "auxpfo_after_plugin_update");


/**
 * Set the uncategorized category for newest portfolios
 * 
 * @return void
 */
add_action( 'save_post_portfolio' , 'auxin_set_uncategorized_term' , 10, 2 );
