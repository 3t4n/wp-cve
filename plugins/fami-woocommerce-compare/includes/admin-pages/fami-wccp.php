<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WooCommerce' ) ) {
	echo '<div class="notice notice-error"><p>' . esc_html__( 'This plugin required WooCommerce installed and activate', 'fami-woocommerce-compare' ) . '</p></div>';
	
	return;
}

$tabs_args = array(
	'settings'        => esc_html__( 'General Settings', 'fami-woocommerce-compare' ),
	'compare-table'   => esc_html__( 'Compare Table', 'fami-woocommerce-compare' ),
	'enqueue-scripts' => esc_html__( 'Enqueue Scripts', 'fami-woocommerce-compare' ),
	'import-export'   => esc_html__( 'Import/Export', 'fami-woocommerce-compare' )
);

$active_tab = 'settings';
if ( isset( $_REQUEST['tab'] ) ) {
	if ( array_key_exists( $_REQUEST['tab'], $tabs_args ) ) {
		$active_tab = Fami_Woocompare_Helper::clean( $_REQUEST['tab'] );
	}
}

$tab_head_html = '';
foreach ( $tabs_args as $tab_id => $tab_name ) {
	$nav_class     = $tab_id == $active_tab ? 'nav-tab nav-tab-active' : 'nav-tab';
	$tab_head_html .= '<a data-tab_id="' . esc_attr( $tab_id ) . '" href="?page=fami-wccp&tab=' . esc_attr( $tab_id ) . '" class="' . $nav_class . '">' . $tab_name . '</a>';
}

$all_settings     = Fami_Woocompare_Helper::get_all_settings();
$enqueue_owl_js   = trim( $all_settings['enqueue_owl_js'] ) == 'yes';
$enqueue_slick_js = trim( $all_settings['enqueue_slick_js'] ) == 'yes';
?>

<div class="wrap">
    <h1><?php esc_html_e( 'Fami Compare Settings', 'fami-woocommerce-compare' ); ?></h1>

    <div class="fami-wccp-admin-page-content-wrap">
        <div class="fami-wccp-tabs fami-all-settings-form">
            <h2 class="nav-tab-wrapper"><?php echo $tab_head_html; ?></h2>

            <div id="settings" class="fami-wccp-tab-content tab-content">
                <div class="fami-wccp-tab-content-inner">
                    <table>
                        <tr>
                            <th>
                                <label><?php esc_html_e( 'Compare Page', 'fami-woocommerce-compare' ) ?></label>
                            </th>
                            <td>
								<?php Fami_Woocompare_Helper::all_pages_select_html( $all_settings['compare_page'], 'fami-wccp-field', 'compare_page', 'compare-page' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label><?php esc_html_e( 'Show button in products list', 'fami-woocommerce-compare' ) ?></label>
                            </th>
                            <td>
                                <label for="show-in-products-list">
                                    <input name="show_in_products_list"
                                           id="show-in-products-list" type="checkbox" class="fami-wccp-field"
                                           value="yes"
										<?php checked( $all_settings['show_in_products_list'], 'yes' ); ?>> <?php esc_html_e( 'Check it if you want to show the button in the products list', 'fami-woocommerce-compare' ); ?>
                                </label>
                                <br>
                                <p>
                                    <label for="products-loop-hook"><?php esc_html_e( 'Products List Hook', 'fami-woocommerce-compare' ) ?></label>
                                </p>
								<?php Fami_Woocompare_Helper::all_products_list_hooks_select_html( $all_settings['products_loop_hook'], 'fami-wccp-field', 'products_loop_hook', 'products-loop-hook' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label><?php esc_html_e( 'Show button in single product', 'fami-woocommerce-compare' ) ?></label>
                            </th>
                            <td>
                                <label for="show-in-single-product">
                                    <input name="show_in_single_product"
                                           id="show-in-single-product" type="checkbox" class="fami-wccp-field"
                                           value="yes"
										<?php checked( $all_settings['show_in_single_product'], 'yes' ); ?>> <?php esc_html_e( 'Check it if you want to show the button in the single product page', 'fami-woocommerce-compare' ); ?>
                                </label>
                                <br>
                                <p>
                                    <label for="single-product-hook"><?php esc_html_e( 'Single Product Hook', 'fami-woocommerce-compare' ) ?></label>
                                </p>
								<?php Fami_Woocompare_Helper::all_single_product_hooks_select_html( $all_settings['single_product_hook'], 'fami-wccp-field', 'single_product_hook', 'single-product-hook' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><label><?php esc_html_e( 'Compare bottom panel', 'fami-woocommerce-compare' ) ?></label>
                            </th>
                            <td>
                                <label for="show-compare-panel">
                                    <input name="show_compare_panel"
                                           id="show-compare-panel" type="checkbox" class="fami-wccp-field"
                                           value="yes"
										<?php checked( $all_settings['show_compare_panel'], 'yes' ); ?>> <?php esc_html_e( 'Check it if you want to show the panel when adding products to the comparison', 'fami-woocommerce-compare' ); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th><label><?php esc_html_e( 'Panel image size', 'fami-woocommerce-compare' ) ?></label>
                            </th>
                            <td>
                                <div class="input-group">
                                    <input type="number" name="panel_img_size_w" id="panel-img-size-w"
                                           class="panel-img-size-w fami-wccp-field fami-wccp-field-small" step="1"
                                           min="0"
                                           value="<?php echo esc_attr( $all_settings['panel_img_size_w'] ); ?>"/>
                                    x
                                    <input type="number" name="panel_img_size_h" id="panel-img-size-h"
                                           class="panel-img-size-h fami-wccp-field fami-wccp-field-small" step="1"
                                           min="0"
                                           value="<?php echo esc_attr( $all_settings['panel_img_size_h'] ); ?>"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th><label><?php esc_html_e( 'Compare page image size', 'fami-woocommerce-compare' ) ?></label>
                            </th>
                            <td>
                                <div class="input-group">
                                    <input type="number" name="compare_img_size_w" id="compare-img-size-w"
                                           class="compare-img-size-w fami-wccp-field fami-wccp-field-small" step="1"
                                           min="0"
                                           value="<?php echo esc_attr( $all_settings['compare_img_size_w'] ); ?>"/>
                                    x
                                    <input type="number" name="compare_img_size_h" id="compare-img-size-h"
                                           class="compare-img-size-h fami-wccp-field fami-wccp-field-small" step="1"
                                           min="0"
                                           value="<?php echo esc_attr( $all_settings['compare_img_size_h'] ); ?>"/>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div id="compare-table" class="fami-wccp-tab-content tab-content">
                <div class="fami-wccp-tab-content-inner">
                    <table>
                        <tr>
                            <th>
                                <label><?php esc_html_e( 'Compare what?', 'fami-woocommerce-compare' ) ?></label>
                            </th>
                            <td>
                                <p class="description"><?php esc_html_e( 'Select the fields to show in the comparison table and order them by drag&drop (are included also the woocommerce attributes)', 'fami-woocommerce-compare' ); ?></p>
								<?php echo Fami_Woocompare_Helper::compare_admin_fields_cb_html(); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label><?php esc_html_e( 'Compare Slider', 'fami-woocommerce-compare' ) ?></label>
                            </th>
                            <td>
                                <select name="compare_slider"
                                        class="fami-wccp-select fami-wccp-slider-select fami-wccp-field">
                                    <option <?php selected( $all_settings['compare_slider'] == 'owl' ); ?>
                                            value="owl"><?php esc_html_e( 'OWL carousel', 'fami-woocommerce-compare' ); ?></option>
                                    <option <?php selected( $all_settings['compare_slider'] == 'slick' ); ?>
                                            value="slick"><?php esc_html_e( 'Slick slider', 'fami-woocommerce-compare' ); ?></option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div id="enqueue-scripts" class="fami-wccp-tab-content tab-content">
                <div class="fami-wccp-tab-content-inner">
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th><?php esc_html_e( 'Load OWL carousel JS', 'fami-woocommerce-compare' ); ?></th>
                            <td>
                                <label class="fami-wccp-switch">
                                    <input type="hidden" name="enqueue_owl_js"
                                           id="enqueue_owl_js" class="fami-wccp-field"
                                           value="<?php echo( $enqueue_owl_js ? 'yes' : 'no' ); ?>">
                                    <input name="enqueue_owl_js_cb"
                                           type="checkbox" <?php echo( $enqueue_owl_js ? 'checked' : '' ); ?> >
                                    <span class="fami-wccp-slider round"></span>
                                </label>
                                <p class="description"><?php esc_html_e( 'Enqueue OWL JS on frontend? OWL JS is required if you want to display products as a carousel (OWL) on the compare page. If you are sure OWL JS is loaded via the theme or other plugin, you can turn it off.', 'fami-woocommerce-compare' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Load Slick slider JS', 'fami-woocommerce-compare' ); ?></th>
                            <td>
                                <label class="fami-wccp-switch">
                                    <input type="hidden" name="enqueue_slick_js"
                                           id="enqueue_slick_js" class="fami-wccp-field"
                                           value="<?php echo( $enqueue_slick_js ? 'yes' : 'no' ); ?>">
                                    <input name="enqueue_slick_js_cb"
                                           type="checkbox" <?php echo( $enqueue_slick_js ? 'checked' : '' ); ?> >
                                    <span class="fami-wccp-slider round"></span>
                                </label>
                                <p class="description"><?php esc_html_e( 'Enqueue Slick slider JS on frontend? Slick slider JS is required if you want to display products as a carousel (Slick) on the compare page. If you are sure Slick slider JS is loaded via the theme or other plugin, you can turn it off.', 'fami-woocommerce-compare' ); ?></p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="import-export" class="fami-wccp-tab-content tab-content">
                <div class="fami-wccp-tab-content-inner">
                    <h3><?php esc_html_e( 'Export Settings', 'fami-woocommerce-compare' ); ?></h3>
                    <a href="<?php echo fami_wccp_export_settings_link(); ?>" target="_blank"
                       class="button fami-wccp-export-settings"><?php esc_html_e( 'Export Settings', 'fami-woocommerce-compare' ); ?></a>
                    <h3><?php esc_html_e( 'Import Settings', 'fami-woocommerce-compare' ); ?></h3>
                    <div class="fami-wccp-import-settings-wrap">
                        <form action="<?php echo fami_wccp_import_settings_action_link(); ?>"
                              name="fami_wccp_import_settings_form" method="post"
                              enctype="multipart/form-data">
                            <label><?php esc_html_e( 'Select json file:', 'fami-woocommerce-compare' ); ?></label>
                            <input type="file" name="fami_wccp_import_file" id="fami_wccp_import_file">
                            <button type="submit"
                                    class="button"><?php esc_html_e( 'Upload And Import', 'fami-woocommerce-compare' ); ?></button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <button type="button"
                class="button-primary fami-wccp-save-all-settings"><?php esc_html_e( 'Save All Settings', 'fami-woocommerce-compare' ); ?></button>
    </div>

</div>