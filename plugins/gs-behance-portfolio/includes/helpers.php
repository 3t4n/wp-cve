<?php
namespace GSBEH;

// if direct access than exit the file.
defined('ABSPATH') || exit;

class Helpers {

    /**
     * Retrives option for the settings.
     * 
     * @since 2.0.12
     * 
     * @param string $option  The option name.
     * @param string $section Settings section name.
     * @param mixed  $default The default value.
     * 
     * @return mixed Saved or the default result of the setting.
     */
    public function getOption( $option, $section, $default = '' ) {
        $options = get_option( $section );
        if ( isset( $options[ $option ] ) ) {
            return $options[ $option ];
        }

        return $default;
    }
    
    /**
     * Get plugin license status
     * 
     * @since  2.0.12
     * @return string The plugin lincense status valid or invalid.
     */
    public function get_license_status() {
        return get_option( 'GS_BEHANCE_LICENSE_STATUS', 'invalid' );
    }

    /**
     * Updates option for the settings.
     * 
     * @since 2.0.12
     * 
     * @param string $option  The option name.
     * @param string $section Settings section name.
     * @param mixed  $value   The value to save.
     * 
     * @return boolean Save status.
     */
    public function updateOption( $option, $section, $value ) {
        $options = get_option( $section );
        if ( is_array( $options ) && array_key_exists( $option, $options ) ) {
            $options[ $option ] = $value;
            update_option( $section, $options );
            return true;
        }
        return false;
    }

    /**
     * Returns each item columns.
     * 
     * @since 2.0.12
     * 
     * @param string $desktop         The option name.
     * @param string $tablet          Settings section name.
     * @param string $mobile_portrait The value to save.
     * @param string $mobile          The value to save.
     * 
     * @return string Item columns.
     */
    public function get_column_classes( $desktop = '3', $tablet = '4', $mobile_portrait = '6', $mobile = '12' ) {
        return sprintf('gs-col-lg-%s gs-col-md-%s gs-col-sm-%s gs-col-xs-%s', $desktop, $tablet, $mobile_portrait, $mobile );
    }

    /**
     * Fetch behance item categories.
     * 
     * @since 2.0.8
     * 
     * @param mixed $id The behance item id.
     * 
     * @return string Joint categories string.
     */
    public function fetch_project_categories( $id ) {
        $wpdb       = plugin()->builder->get_wp_db();
        $tableName  = plugin()->db->get_data_table();
        $fields     = $wpdb->get_results( "SELECT bfields FROM {$tableName} WHERE beid={$id}", ARRAY_A );
        
        $cats = [];

        
        foreach( $fields as $shot ) {
            $bfields = unserialize( $shot[ 'bfields' ] );
            
            foreach ( $bfields as $bcat) {
                $cat_name = isset($bcat['name']) ? trim($bcat['name']) : '';
                $cats[] = sanitize_title( $cat_name );
            }
        }


        $cats = array_unique( $cats );

        return join(' ', $cats);
    }

    /**
     * Returns shortcodes as select option.
     * 
     * @since 2.0.8
     * @param boolean $options If we want's the value as options.
     * @param boolean $default If we want's the value as the default option.
     * 
     * @return mixed Options array or the default value.
     */
    public function get_shortcode_as_options() {
        $shortcodes = plugin()->builder->get_shortcodes_as_list();
        if ( empty( $shortcodes ) ) {
            return;
        }

        return array_combine(
            wp_list_pluck( plugin()->builder->get_shortcodes_as_list(), 'id' ),
            wp_list_pluck( plugin()->builder->get_shortcodes_as_list(), 'shortcode_name' )
        );

    }

    public function gs_behance_minimize_css_simple($css) {
        // https://datayze.com/howto/minify-css-with-php
        $css = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $css); // negative look ahead
        $css = preg_replace('/\s{2,}/', ' ', $css);
        $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
        $css = preg_replace('/;}/', '}', $css);
        return $css;
    }

    /**
     * Returns shortcodes as select option just for visual composer.
     * 
     * @since 2.0.8
     * @return array Options array
     */
    public function get_vc_shortcode_options() {
        $shortcodes = $this->get_shortcode_as_options();

        if ( empty( $shortcodes ) ) {
            return;
        }

        return array_flip( $shortcodes );
    }

    /**
     * Returns generated image with attributes.
     * 
     * @since 2.0.8
     */
    public function get_shot_thumbnail( $src, $alt, $extraClasses = [] ) {
        $disable = wp_validate_boolean( plugin()->builder->get( 'disable_beh_lazy_load' ) );
        $classes = array_merge( [], $extraClasses );

        if ( $disable ) {
            $classes[] = plugin()->builder->get( 'lazy_load_class' );
        }

        return sprintf( '<img src="%s" alt="%s" class="%s" />', $src, $alt, implode( ' ', $classes ) );
    }

    /**
     * Returns default item from shortcode list.
     * 
     * @since 2.0.8
     */
    public function get_default_option() {
        $shortcodes = plugin()->builder->get_shortcodes_as_list();

        if ( ! empty( $shortcodes ) ) {
            return $shortcodes[0]['id'];
        }

        return '';
    }

    function is_pro_active() {
        return wbp_fs()->is_paying_or_trial();
    }

    /**
     * Checks for the preview.
     * 
     * @since  2.0.12
     * @return bool
     */
    public function is_preview() {
        return isset($_REQUEST['gsbeh_shortcode_preview']) && !empty($_REQUEST['gsbeh_shortcode_preview']);
    }
}