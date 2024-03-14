<?php
/**
 * Custom modules
 *
 * @package Xpro Helper
 */

if ( ! class_exists( 'XPRO_Plugins_Helper' ) ) {

    /**
     * This class initializes Xpro Helper
     *
     * @class XPRO_Plugins_Helper
     */
    class XPRO_Plugins_Helper {

        /**
         * Holds any category strings of modules.
         *
         * @since 1.0.0
         * @var $advance_module Category Strings
         */
        public static $advance_module = '';

        /**
         * Holds any category strings of modules.
         *
         * @since 1.0.0
         * @var $creative_modules Category Strings
         */
        public static $creative_modules = '';
        /**
         * Holds any category strings of modules.
         *
         * @since 1.0.0
         * @var $content_modules Category Strings
         */
        public static $content_modules = '';
        /**
         * Holds any category strings of modules.
         *
         * @since 1.0.0
         * @var $media_modules Category Strings
         */
        public static $media_modules = '';


        /**
         * Holds any category strings of modules.
         *
         * @since 1.0.0
         * @var $branding_modules Group Strings
         */
        public static $branding_modules = '';

        /**
         * Holds any category strings of modules.
         *
         * @since 1.5.0.1
         * @var $formstyler_module Category Strings
         */
        public static $formstyler_module = '';

        /**
         * Holds any category strings of modules.
         *
         * @since 1.5.0.1
         * @var $social_modules Category Strings
         */
        public static $social_modules = '';

        /**
         * Holds any category strings of modules.
         *
         * @since 1.5.0.1
         * @var $woo_modules Category Strings
         */
        public static $woo_modules = '';

        /**
         * Holds any category strings of modules.
         *
         * @since 1.5.0.1
         * @var $themer_modules Category Strings
         */
        public static $themer_modules = '';

        /**
         * Constructor function that initializes required actions and hooks
         *
         * @since 1.0.0
         */
        function __construct() {

            $this->set_constants();
        }

        /**
         * Function that set constants for XPRO
         *
         * @since 1.0.0
         */
        public static function set_constants() {

            self::$advance_module    = __( 'Advanced Modules - Xpro Addons', 'xpro-bb-addons' );
            self::$creative_modules  = __( 'Creative Modules - Xpro Addons', 'xpro-bb-addons' );
            self::$content_modules   = __( 'Content Modules - Xpro Addons', 'xpro-bb-addons' );
            self::$media_modules     = __( 'Media Modules - Xpro Addons', 'xpro-bb-addons' );
            self::$social_modules    = __( 'Social Modules - Xpro Addons', 'xpro-bb-addons' );
            self::$formstyler_module = __( 'Form Styler Modules - Xpro Addons', 'xpro-bb-addons' );
            self::$woo_modules       = __( 'Woocommerce Modules - Xpro Addons', 'xpro-bb-addons' );
            self::$themer_modules    = __( 'Themer Modules - Xpro Addons', 'xpro-bb-addons' );

            self::$branding_modules = __( 'Xpro Addons', 'xpro-bb-addons' );
        }


        /**
         * Get Templates based on category
         */
        public static function get_post_template( $type = 'layout' ) {
            $posts = get_posts(
                array(
                    'post_type'      => 'fl-builder-template',
                    'orderby'        => 'title',
                    'order'          => 'ASC',
                    'posts_per_page' => '-1',
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'fl-builder-template-type',
                            'field'    => 'slug',
                            'terms'    => $type,
                        ),
                    ),
                )
            );

            $templates = array();

            foreach ( $posts as $post ) {

                $templates[] = array(
                    'id'     => $post->ID,
                    'name'   => $post->post_title,
                    'global' => get_post_meta( $post->ID, '_fl_builder_template_global', true ),
                );
            }

            return $templates;
        }

        /**
         *  Get - Saved row templates
         *
         * @return  $option_array
         * @since   1.0.0
         */
        public static function get_saved_page_template() {
            if ( FLBuilderModel::node_templates_enabled() ) {

                $page_templates = self::get_post_template( 'layout' );

                $options = array();

                if ( count( $page_templates ) ) {
                    foreach ( $page_templates as $page_template ) {
                        $options[ $page_template['id'] ] = $page_template['name'];
                    }
                } else {
                    $options['no_template'] = 'It seems that, you have not saved any template yet.';
                }
                return $options;
            }
        }


        /**
         *  Get - Saved row templates
         *
         * @return  $option_array
         * @since   1.0.0
         */
        public static function get_saved_row_template() {
            if ( FLBuilderModel::node_templates_enabled() ) {

                $saved_rows = self::get_post_template( 'row' );

                $options = array();
                if ( count( $saved_rows ) ) {
                    foreach ( $saved_rows as $saved_row ) {
                        $options[ $saved_row['id'] ] = $saved_row['name'];
                    }
                } else {
                    $options['no_template'] = 'It seems that, you have not saved any template yet.';
                }
                return $options;
            }
        }


        /**
         *  Get - Saved module templates
         *
         * @return  $option_array
         * @since   1.0.0
         */
        public static function get_saved_module_template() {
            if ( FLBuilderModel::node_templates_enabled() ) {

                $saved_modules = self::get_post_template( 'module' );

                $options = array();
                if ( count( $saved_modules ) ) {
                    foreach ( $saved_modules as $saved_module ) {
                        $options[ $saved_module['id'] ] = $saved_module['name'];
                    }
                } else {
                    $options['no_template'] = 'It seems that, you have not saved any template yet.';
                }
                return $options;
            }
        }

        public static function get_xpro_saved_templates (){
            $templates_select = array();

            // Get All Templates
            $templates = get_posts(
                array(
                    'post_type'   => array( 'xpro_bb_templates' ),
                    'post_status' => array( 'publish' ),
                    'numberposts' => -1,
                )
            );

            if ( ! empty( $templates ) ) {
                $templates_select['no_template'] = 'Select';
                foreach ( $templates as $template ) {
                    $templates_select[ $template->ID ] = $template->post_title;
                }
            }else{
                $templates_select['no_template'] = 'It seems that, you have not saved any template yet.';
            }

            return $templates_select;

        }

    }

    new XPRO_Plugins_Helper();
}
