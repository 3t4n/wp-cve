<?php
namespace tmc\revisionmanager\src\Components;

/**
 * @author jakubkuranda@gmail.com
 * Date: 15.03.2018
 * Time: 15:31
 */

use shellpress\v1_4_0\src\Shared\Components\IComponent;
use shellpress\v1_4_0\src\Shared\Front\Models\HtmlElement;
use tmc\revisionmanager\src\App;
use WP_Post;
use WP_Query;

class DashboardWidget extends IComponent {

	/**
	 * Called on creation of component.
	 *
	 * @return void
	 */
	protected function onSetUp() {

		add_action( 'wp_dashboard_setup', array( $this, '_a_setupDashboardWidget' ) );

	}

    /**
     * @param string[]|string $postTypes
     *
     * @return WP_Post[]
     */
    protected function getPostsToRevision( $postTypes = null ) {

        $query = new WP_Query( array(
            'post_type'         =>  $postTypes,
            'posts_per_page'    =>  50,             //  Don't get crazy...
            'orderby'           =>  'modified',
            'meta_query'        =>  array(
            	array(
		            'key'               =>  'linked_post_id',
		            'compare'           =>  'EXISTS'
	            )
            ),
            'post_status'       =>  array( 'pending', 'draft' )
        ) );

        return (array) $query->get_posts();

    }

    //  ================================================================================
    //  ACTIONS
    //  ================================================================================

    /**
     * Called on wp_dashboard_setup.
     */
    public function _a_setupDashboardWidget() {

        if( ! current_user_can( 'manage_options' ) ) return;

        wp_add_dashboard_widget( 'rm_tmc_widget', __( 'Revision Manager TMC', 'rm_tmc' ), array( $this, '_a_displayOfDashboardWidget' ) );

    }

    /**
     * Displays widget in dashboard arena.
     */
    public function _a_displayOfDashboardWidget() {

        //  ----------------------------------------
        //  Revisions to accept
        //  ----------------------------------------

        printf( '<p><strong>%1$s</strong></p>', __( 'Revisions to accept', 'rm_tmc' ) );

        $postTypes  = App::i()->settings->getChosenPostTypesSlugs();
        $posts      = $this->getPostsToRevision( $postTypes );

        if( empty( $posts ) ){

            printf( '<p>%1$s</p>', '---' );

        } else {

            echo '<ul>';

            foreach( $posts as $post ){ /** @var WP_Post $post */

                printf( '<li><a href="%1$s" class="dashicons-before dashicons-arrow-right" title="%2$s">%2$s</a></li>', get_edit_post_link( $post ), $post->post_title );

            }

            echo '</ul>';

        }

        //  ----------------------------------------
        //  Tools
        //  ----------------------------------------

        printf( '<p><strong>%1$s</strong></p>', __( 'Tools', 'rm_tmc' ) );

        $query = new WP_Query( array(
            'post_type'         =>  'revision',
            'post_status'       =>  'inherit'
        ) );

        $buttonObj = new HtmlElement( 'button' );
        $buttonObj->addAttributes( array(
            'class'             =>  'button',
            'id'                =>  'rm_tmc_delete_all_revisions',
            'data-action-hook'  =>  Revisions::DELETE_ALL_REVISIONS_ACTION_NAME,
            'title'             =>  __( 'Keep your database clean and lightweight', 'rm_tmc' )
        ) );
        $buttonObj->setContent( sprintf( __( 'Delete %1$s revisions', 'rm_tmc' ), $query->found_posts ) );

        printf( '<p>%1$s</p>', $buttonObj->getDisplay() );

        //  TODO - maybe separate this code into file.

        echo '<script>';
        echo <<<JS

        jQuery( document ).ready( function( $ ){
            
            $( '#rm_tmc_delete_all_revisions' ).click( function( event ){
                
                event.preventDefault();
                
                var thisButton = $( this );
                
                thisButton.append( '<i class="spinner is-active" style="margin-right: 0;"></i>' ).prop( 'disabled', true );
                
                var data = {
                    'action':       thisButton.attr( 'data-action-hook' )
                };
        
                jQuery.post( ajaxurl, data, function( response ) {
        
                    thisButton.replaceWith( response );
        
                });
                
            } );
            
        } );
    
JS;
        echo '</script>';

        //  ----------------------------------------
        //  Settings link
        //  ----------------------------------------

        printf( '<p class="wp-clearfix"><a class="alignright" href="%1$s">%2$s</a></p>', admin_url( 'options-general.php?page=rm_tmc_settings' ), __( 'Settings', 'rm_tmc' ) );

    }

}