<?php
/**
 * Content for wp magazine modules options
 * 
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 * 
 */
?>
<div id="cvmm-options" style="display:none">
    <h2 class="cvmm-admin-title">
        <?php esc_html_e( 'General options', 'wp-magazine-modules-lite' ); ?>
    </h2>
    <div class="cvmm-admin-content">
        <div class="cvmm-admin-settings-tab">
            <h2><?php esc_html_e( 'Category Color Settings', 'wp-magazine-modules-lite' ); ?></h2>
            <div class="cvmm-admin-group-fields">
                <form id="cvmm-wpmagazine-modules-lite-options-form">
                    <div class="cvmm-admin-field-options-wrapper">
                        <?php
                            $dynamic_allcss_class = new Wpmagazine_Modules_Lite_Dynamic_AllCss();
                            $defaults = $dynamic_allcss_class->get_defaults();
                            if ( get_option( "wpmagazine_modules_lite_category_options" ) ) {
                                $category_old_values = get_option( "wpmagazine_modules_lite_category_options" );
                                set_theme_mod( "wpmagazine_modules_lite_category_options", $category_old_values );
                            }
                            $categories_values = get_theme_mod( "wpmagazine_modules_lite_category_options", $defaults );
                            $categories =  get_categories();
                            foreach ( $categories as $category ) {
                                $cat_slug = $category->slug;
                                $cat_name = $category->name;
                                $background_colorvalue = isset( $categories_values[ "cvmm_category_".esc_html( $cat_slug )."_background_color" ] ) ? sanitize_hex_color( $categories_values[ "cvmm_category_".esc_html( $cat_slug )."_background_color" ] ): "#ffffff";
                                $background_hover_colorvalue = isset( $categories_values[ "cvmm_category_".esc_html( $cat_slug )."_background_hover_color" ] ) ? sanitize_hex_color( $categories_values[ "cvmm_category_".esc_html( $cat_slug )."_background_hover_color" ] ): "#ffffff";
                        ?>
                                <div class="cvmm-admin-single-field">
                                    <div class="cvmm-admin-field-heading">
                                        <?php echo '"'.esc_html( $cat_name ).'" '.esc_html__( "Category Color", 'wp-magazine-modules-lite' ); ?>
                                        <span class="dashicons dashicons-arrow-down-alt2">
                                        </span>
                                    </div>
                                    <div class="cvmm-admin-field-options" style="display:none">
                                        <div class="cvmm-admin-field-single-options">
                                            <label for="cvmm_category_<?php echo esc_html( $cat_slug ); ?>_background_color"><?php esc_html_e( 'Color', 'wp-magazine-modules-lite' ); ?></label>
                                            <input type="text" value="<?php echo esc_attr( $background_colorvalue ); ?>" name="cvmm_category_<?php echo esc_html( $cat_slug ); ?>_background_color" class="cvmm-wpmagazine-modules-lite-color-field" />
                                        </div>
                                        <div class="cvmm-admin-field-single-options">
                                            <label for="cvmm_category_<?php echo esc_html( $cat_slug ); ?>_background_hover_color"><?php esc_html_e( 'Hover Color', 'wp-magazine-modules-lite' ); ?></label>
                                            <input type="text" value="<?php echo esc_attr( $background_hover_colorvalue ); ?>" name="cvmm_category_<?php echo esc_html( $cat_slug ); ?>_background_hover_color" class="cvmm-wpmagazine-modules-lite-color-field" />
                                        </div>
                                    </div><!-- .cvmm-admin-field-options -->
                                </div><!-- .cvmm-admin-single-field -->
                        <?php
                            }
                        ?>
                    </div><!-- .cvmm-admin-field-options-wrapper -->
                    <div class="cvmm-form-button-wrapper">
                        <div class="cvmm-form-button">
                            <input type="submit" class="button button-primary" data-saving="<?php esc_html_e( 'Saving..', 'wp-magazine-modules-lite' ); ?>" data-saved="<?php esc_html_e( 'Saved', 'wp-magazine-modules-lite' ); ?>" data-save="<?php esc_html_e( 'Save Changes', 'wp-magazine-modules-lite' ); ?>" value="<?php esc_html_e( 'Saved', 'wp-magazine-modules-lite' ); ?>" disabled="disabled">
                        </div>
                        <div class="cvmm-form-button">
                            <button type="button" class="button button-reset"><?php esc_html_e( 'Reset colors to default', 'wp-magazine-modules-lite' ); ?></button>
                        </div>
                    </div><!-- .cvmm-form-button-wrapper -->
                </form>
            </div><!-- .cvmm-admin-group-fields -->
        </div><!-- .cvmm-admin-settings-tab -->
    </div><!-- .cvmm-admin-content -->
</div><!-- #cvmm-options -->