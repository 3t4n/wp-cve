<?php
/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://logichunt.com
 * @since      1.0.0
 *
 * @package    logosliderwpcarousel
 * @subpackage logosliderwpcarousel/admin/partials
 */
if (!defined('WPINC')) {
    die;
}


wp_nonce_field( 'post_meta_lgx_counter_generator', 'post_meta_lgx_counter_generator[nonce]' );


if ( ! isset( $post->ID) ) {
    return;
}

$save_meta_lgx_counter_generator = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );

$lgx_meta_showcase_type = (isset($save_meta_lgx_counter_generator['lgx_counter_showcase_type'] ) ? $save_meta_lgx_counter_generator['lgx_counter_showcase_type'] : 'grid') ;

//echo '<pre>';  print_r($lgx_meta_showcase_type); echo '</pre>'; wp_die();

?>

<div class="lgx_logo_slider_post_type_container">
    <div class="lgx_logo_slider_card">

        <?php

        /*
         * Add Header File
         */
        include_once plugin_dir_path( __FILE__ ) . '/__meta_section_header.php';

        ?>

        <div class="lgx_logo_slider_card_body">

            <?php

            /*
             * Add Shortcode usage information
             */
            include plugin_dir_path( __FILE__ ) . '/__meta_section_help_block.php';

            ?>
            <div class="lgx_row">

                <div class="lgx_col_12">
                    <div class="lgx_logo_slider_info_box lgx_logo_slider_info_box_lead " style="padding: 0; margin-bottom: -9px;">
                        <div class="lgx_logo_slider_settings_box" >
                            <table class="form-table">
                                <tbody>
                                <tr>
                                    <th scope="row">
                                        <label for="showcase_type">Select Showcase Type</label>
                                    </th>
                                    <td>
                                        <select name="post_meta_lgx_counter_generator[lgx_counter_showcase_type]" id="lgx_lswp_showcase_type" class="lgx_logo_slider_showcase_type" style="width: 100%">
                                            <option data-logo-slider-layout="grid" value="grid" <?php echo ( $lgx_meta_showcase_type == 'grid' ) ? 'selected="selected"' : '' ?>>Grid</option>
                                            <option data-logo-slider-layout="flexbox" value="flexbox" <?php echo ( $lgx_meta_showcase_type == 'flexbox' ) ? 'selected="selected"' : '' ?>>Flexbox</option>
                                        </select>
                                    </td>
                                    <td>

                                        <?php

                                        /*
                                         * Add Get Pro blocks
                                         */
                                        include_once plugin_dir_path( __FILE__ ) . '/__meta_section_get_pro.php';

                                        ?>

                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div><!-- ./lgx_logo_slider_settings_box -->
                    </div><!-- ./lgx_logo_slider_info_box -->
                </div><!-- ./lgx_col_12 -->

            </div><!-- ./lgx_row -->


            <div class="lgx_row">

                <div class="lgx_col_12">
                    <div class="lgx_logo_slider_info_box">
                        <div class="lgx_logo_slider_settings_box">

                            <div class="lgx_logo_slider_tab_box_container">
                                <div class="lgx_logo_slider_nav_tab_wrapper">
                                    <a class="lgx_logo_slider_nav_tab lgx_active" data-active-tab="lgx_tab_basic"><i class="dashicons dashicons-info-outline"></i> Basic</a>
                                    <a class="lgx_logo_slider_nav_tab" data-active-tab="lgx_tab_style"><i class="dashicons dashicons-admin-appearance"></i> Styling</a>                                    
                                    <a class="lgx_logo_slider_nav_tab" data-active-tab="lgx_tab_responsive"><i class="dashicons dashicons-smartphone"></i> Responsive</a>
                                    <a class="lgx_logo_slider_nav_tab" data-active-tab="lgx_tab_section"><i class="dashicons dashicons-editor-insertmore"></i> Section</a>
                                    <a class="lgx_logo_slider_nav_tab lgx_logo_slider_layout" data-active-tab="lgx_tab_layout" style="width: 100px;">
    
                                        <?php echo  ( ( $lgx_meta_showcase_type == 'grid' ) ? '<i class="dashicons dashicons-grid-view"></i>  Grid' : '' ) ;?>
                                        <?php echo  ( ( $lgx_meta_showcase_type == 'flexbox' ) ? '<i class="dashicons dashicons-screenoptions"></i>  Flexbox' : '' ) ;?>
                                    </a>              
                                    <a class="lgx_logo_slider_nav_tab" data-active-tab="lgx_tab_header"><i class="dashicons dashicons-archive"></i> Header</a>
                                    <a class="lgx_logo_slider_nav_tab" data-active-tab="lgx_tab_preloader"><i class="dashicons dashicons-editor-expand"></i> Preloader</a>

                                </div><!-- ./lgx_logo_slider_nav_tab_wrapper -->

                                <div class="lgx_logo_slider_tab_content_wrapper">

                                    <div class="lgx_logo_slider_tab_content lgx_active" data-tab-id="lgx_tab_basic">
                                        <table class="form-table  lgx_form_table">
                                            <tbody>
                                            <?php require_once plugin_dir_path( __FILE__ ) . '/tabs/_meta_tab_basic.php';?> </tbody>
                                        </table>
                                    </div><!-- ./tab_content -->


                                    <div class="lgx_logo_slider_tab_content" data-tab-id="lgx_tab_query">
                                        <table class="form-table  lgx_form_table">
                                            <tbody>
                                            <?php require_once plugin_dir_path( __FILE__ ) . '/tabs/_meta_tab_query.php';?> </tbody>
                                        </table>
                                    </div><!-- ./tab_content -->

                                    <div class="lgx_logo_slider_tab_content" data-tab-id="lgx_tab_layout">

                                    
                                        <div class="lgx_logo_slider_tab_inner lgx_logo_slider_tab_inner_layout_grid" <?php echo  ( ( $lgx_meta_showcase_type == 'grid' ) ? 'style="display: block"' : '' ) ;?> >
                                            <table class="form-table  lgx_form_table">
                                                <tbody><?php require_once plugin_dir_path( __FILE__ ) . '/tabs/_meta_tab_grid.php' ?></tbody>
                                            </table>

                                        </div><!--//.GRID  -->


                                        <div class="lgx_logo_slider_tab_inner lgx_logo_slider_tab_inner_layout_flexbox" <?php echo  ( ( $lgx_meta_showcase_type == 'flexbox' ) ? 'style="display: block"' : '' ) ;?> >
                                            <table class="form-table  lgx_form_table">
                                                <tbody><?php  require_once plugin_dir_path( __FILE__ ) . '/tabs/_meta_tab_flexbox.php' ?></tbody>
                                            </table>

                                        </div><!--//.FLEXBOX  -->


                                    </div><!-- .//tab_content: Dynamic -->



                                    <div class="lgx_logo_slider_tab_content" data-tab-id="lgx_tab_style">
                                        <table class="form-table  lgx_form_table">
                                            <tbody> <?php require_once plugin_dir_path( __FILE__ ) . '/tabs/_meta_tab_style.php'; ?></tbody>
                                        </table>
                                    </div><!-- .//tab_content -->


                                    <div class="lgx_logo_slider_tab_content" data-tab-id="lgx_tab_responsive">
                                        <table class="form-table  lgx_form_table">
                                            <tbody>
                                            <?php

                                            /*
                                             * Add Responsive Settings
                                             */
                                            require_once plugin_dir_path( __FILE__ ) . '/tabs/_meta_tab_responsive.php';
                                            ?>
                                            </tbody>
                                        </table>
                                    </div><!-- ./lgx_logo_slider_tab_content -->

                                    <div class="lgx_logo_slider_tab_content" data-tab-id="lgx_tab_section">
                                        <table class="form-table  lgx_form_table">
                                            <tbody> <?php  require_once plugin_dir_path( __FILE__ ) . '/tabs/_meta_tab_section.php';?> </tbody>
                                        </table>
                                    </div><!-- .//tab_content -->

                                    <div class="lgx_logo_slider_tab_content" data-tab-id="lgx_tab_header">
                                        <table class="form-table  lgx_form_table">
                                            <tbody> <?php   require_once plugin_dir_path( __FILE__ ) . '/tabs/_meta_tab_header.php'; ?> </tbody>
                                        </table>
                                    </div><!-- .//tab_content -->


                                    <div class="lgx_logo_slider_tab_content" data-tab-id="lgx_tab_preloader">
                                        <table class="form-table  lgx_form_table">
                                            <tbody> <?php   require_once plugin_dir_path( __FILE__ ) . '/tabs/_meta_tab_preloader.php'; ?> </tbody>
                                        </table>
                                    </div><!-- .//tab_content -->


                                </div><!-- ./lgx_logo_slider_tab_content_wrapper -->
                            </div><!-- ./lgx_logo_slider_tab_box_container -->
                        </div><!-- ./lgx_logo_slider_settings_box -->
                    </div><!-- ./lgx_logo_slider_info_box -->
                </div><!-- ./lgx_col_12 -->

            </div><!-- ./lgx_row -->


            <?php

            /*
             * Add Shortcode usage information
             */
          //  include plugin_dir_path( __FILE__ ) . '/__meta_fields_lsp_shortcodes_help_block.php';

            ?>
        </div><!--//.lgx_logo_slider_card_body-->

    </div><!--// lgx_logo_slider_card-->
</div><!--// lgx_logo_slider_post_type_container-->

