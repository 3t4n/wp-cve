<?php
/**
*
*/
namespace Reuse\Builder;
class Reuse_Builder_Template_Loader {

    function __construct() {
        add_filter( 'template_include', array($this, 'template_loader' ) );
    }

    /**
     * Load a template.
     *
     * Handles template usage so that we can use our own templates instead of the themes.
     *
     * Templates are in the 'templates' folder. woocommerce-wishlists-collections looks for theme
     * overrides in /theme/woocommerce-wishlists-collections/ by default
     *
     * For beginners, it also looks for a woocommerce-wishlists-collections.php template first. If the user adds
     * this to the theme (containing a woocommerce-wishlists-collections() inside) this will be used for all
     * woocommerce-wishlists-collections templates.
     *
     * @param mixed $template
     * @return string
     */
    public function template_loader( $template ) {
        $find = array( 'reuse-builder.php' );
        $file = '';

        $query_args = array(
            'post_type' => 'reuseb_template',
            'post_per_page' => -1,
            'numberposts'=> -1,
        );

        $the_query = get_posts( $query_args );

        if( $the_query ) {
            foreach( $the_query as $query ) {
                $template_select = get_post_meta( $query->ID, 'reuseb_template_select_type', true );

                if($template_select == 'single'){
                    $template_post_select = get_post_meta( $query->ID, 'reuseb_template_post_select', true );

                    if( $template_post_select ) {

                        // dynamically load the single page template from the plugin
                        if ( is_single() && get_post_type() == $template_post_select ) {

                            $file   = 'single-reuse-page.php';
                            $find[] = $file;
                            $find[] = reuse_builder()->template_path() . $file;

                        }
                    }
                }
                if($template_select == 'archive'){
                    $template_post_select = get_post_meta( $query->ID, 'reuseb_template_post_select', true );

                    if( $template_post_select ) {

                        // dynamically load the single page template from the plugin
                        if ( is_archive() && get_post_type() == $template_post_select ) {

                            $file   = 'archive-reuse-page.php';
                            $find[] = $file;
                            $find[] = reuse_builder()->template_path() . $file;

                        }
                    }
                }
            }
        }


        if ( $file ) {
            $template       = locate_template( array_unique( $find ) );
            if ( ! $template ) {
                $template = REUSE_BUILDER_TEMPLATE_PATH . $file;
            }
        }

        return $template;
    }

}
