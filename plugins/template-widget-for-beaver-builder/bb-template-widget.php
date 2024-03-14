<?php
/**
 * Plugin Name: Template Widget for Beaver Builder
 * Plugin URI: https://wpbeaveraddons.com
 * Description: Adds a widget to display Beaver Builder saved templates in sidebar, footer or any other area.
 * Version: 1.0.1
 * Author: Beaver Addons, Achal Jain
 * Author URI: https://wpbeaveraddons.com
 * Copyright: (c) 2016 IdeaBox Creations
 * License: GNU General Public License v2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: bb-template-widget
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TWBB_VER', '1.0.1' );
define( 'TWBB_DIR', plugin_dir_path( __FILE__ ) );
define( 'TWBB_URL', plugins_url( '/', __FILE__ ) );
define( 'TWBB_PATH', plugin_basename( __FILE__ ) );

if ( class_exists( 'FLBuilderModel' ) ) :

    /**
     * Get Beaver Builder saved templates.
     */
    function twbb_get_saved_templates( $type = 'layout' )
    {
		$args = array(
            'post_type'          => 'fl-builder-template',
            'orderby' 			 => 'title',
            'order'              => 'ASC',
            'posts_per_page'     => '-1',
            'tax_query'          => array(
                array(
                    'taxonomy' => 'fl-builder-template-type',
                    'field'    => 'slug',
                    'terms'    => $type
                )
            )
        );
        $templates = get_posts( $args );

		// Multisite support.
        // @since 1.0.1
        if ( is_multisite() ) {

            $blog_id = get_current_blog_id();

            if ( $blog_id != 1 ) {
                switch_to_blog(1);

                // Get posts from main site.
                $main_posts = get_posts( $args );

				// Loop through each main site post
                // and add site_id to post object.
                foreach ( $main_posts as $main_post ) {
                    $main_post->site_id = 1;
                }

                $templates = array_merge( $templates, $main_posts );

                restore_current_blog();
            }
			else {
                foreach ( $templates as $template ) {
                    $template->site_id = 1;
                }
			}
		}

        $options = array();

        if ( count( $templates ) ) {
            foreach ($templates as $template) {
                $options[$template->ID] = array(
					'title' => $template->post_title,
					'site'	=> isset( $template->site_id ) ? $template->site_id : null
				);
            }
        }

        return $options;
    }

    /**
     * Load widget class.
     */
    require_once TWBB_DIR . 'classes/class-template-widget.php';

endif;
