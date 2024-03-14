<?php

/**
 * Provide a admin area form view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    size-chart-for-woocommerce
 * @subpackage size-chart-for-woocommerce/admin/partials
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
global  $pagenow ;
?>
<div class="wrap ajax_cart size-chart-setting">
    <div class="thedotstore-main-table res-cl">
        <h2>
			<?php 
esc_html_e( 'Size Chart Settings', 'size-chart-for-woocommerce' );
?>
        </h2>
        <table class="table-outer">
            <tbody>
                <tr>
                    <td class="fr-2">
                        <form method="post" action="<?php 
admin_url( 'admin.php?page=size-chart-setting-page' );
?>" enctype="multipart/form-data">
                            <?php 
wp_nonce_field( "size_chart_page" );
$get_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS );

if ( 'edit.php' === $pagenow && 'size-chart-setting-page' === $get_page ) {
    $available_in_pro_text = __( '(Available in Pro Version) ', 'size-chart-for-woocommerce' );
    $plugin_pro_class = ' size-chart-disable';
    $is_disable = true;
    $size_chart_get_table_head_color = '';
    $size_chart_get_table_head_font_color = '';
    $size_chart_get_table_row_even_color = '';
    $size_chart_get_table_row_odd_color = '';
    $size_chart_get_button_position = '';
    $size_chart_get_title_color = '';
    $size_chart_get_button_class = '';
    $size_chart_get_custom_css = '';
    $size_chart_get_size = 'medium';
    ?>
                                <div id="size-chart-setting-fields">
                                    <fieldset class="custom-fieldset set-size-chart-fieldset<?php 
    echo  esc_attr( $plugin_pro_class ) ;
    ?>" <?php 
    disabled( $is_disable, true, true );
    ?>>
                                        <legend>
                                            <?php 
    esc_html_e( 'General Settings', 'size-chart-for-woocommerce' );
    ?>
                                            <?php 
    echo  esc_html( $available_in_pro_text ) ;
    ?>
                                        </legend>
                                        <div class="setting-description">
                                            <p>
                                                <?php 
    printf(
        "%s <a href='%s' target='_blank'>%s</a>",
        esc_html__( 'With this setting you can configure size chart table style like table head font color, table row color, table head background color etc. Note: (For this setting you will have to select custom style from particular size chart.', 'size-chart-for-woocommerce' ),
        esc_url( plugins_url( 'images/thedotstore-images/screenshots/custom-style-option.png', dirname( __FILE__ ) ) ),
        esc_html__( 'Check Screenshot', 'size-chart-for-woocommerce' )
    );
    ?>
                                            </p>
                                        </div>
                                        <table class="form-table">
                                            <tr>
                                                <th>
                                                    <label for="color-picker2">
                                                        <?php 
    esc_html_e( 'Table Head Background Color', 'size-chart-for-woocommerce' );
    ?>
                                                    </label>
                                                </th>
                                                <td>
                                                    <input type="text" name="size-chart-table-head-color" id="color-picker2" value="<?php 
    echo  esc_attr( $size_chart_get_table_head_color ) ;
    ?>" <?php 
    disabled( $is_disable, true, true );
    ?>/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <label for="color-picker3">
                                                        <?php 
    esc_html_e( 'Table Head Font Color', 'size-chart-for-woocommerce' );
    ?>
                                                    </label>
                                                </th>
                                                <td>
                                                    <input type="hidden" name="size-chart-table-head-font-color" id="color-picker3" value="<?php 
    echo  esc_attr( $size_chart_get_table_head_font_color ) ;
    ?>" <?php 
    disabled( $is_disable, true, true );
    ?>/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <label for="color-picker4">
                                                        <?php 
    esc_html_e( 'Table Even Row Color', 'size-chart-for-woocommerce' );
    ?>
                                                    </label>
                                                </th>
                                                <td>
                                                    <input type="hidden" name="size-chart-table-row-even-color" id="color-picker4" value="<?php 
    echo  esc_attr( $size_chart_get_table_row_even_color ) ;
    ?>" <?php 
    disabled( $is_disable, true, true );
    ?>/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <label for="color-picker5">
                                                        <?php 
    esc_html_e( 'Table Odd Raw Color', 'size-chart-for-woocommerce' );
    ?>
                                                    </label>
                                                </th>
                                                <td>
                                                    <input type="hidden" name="size-chart-table-row-odd-color" id="color-picker5" value="<?php 
    echo  esc_attr( $size_chart_get_table_row_odd_color ) ;
    ?>" <?php 
    disabled( $is_disable, true, true );
    ?>/>
                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                    <fieldset class="custom-fieldset set-size-chart-fieldset">
                                        <legend>
                                            <?php 
    esc_html_e( 'Global Settings', 'size-chart-for-woocommerce' );
    ?>
                                        </legend>
                                        <div class="setting-description">
                                            <p><?php 
    esc_html_e( 'With this setting you can add size chart label and it will same for all product size charts.', 'size-chart-for-woocommerce' );
    ?></p>
                                        </div>
                                        <table class="form-table global-setting">
                                            <tr>
                                                <th>
                                                    <label for="size-chart-tab-label">
                                                        <?php 
    esc_html_e( 'Tab Label', 'size-chart-for-woocommerce' );
    ?>
                                                    </label>
                                                    <div class="setting-description"><?php 
    esc_html_e( 'It is visible on product details page in the custom tab.', 'size-chart-for-woocommerce' );
    ?></div>
                                                </th>
                                                <td>
                                                    <input type="text" name="size-chart-tab-label" id="size-chart-tab-label" placeholder="<?php 
    esc_attr_e( 'Size Chart', 'size-chart-for-woocommerce' );
    ?>" value="<?php 
    esc_attr_e( scfw_size_chart_get_tab_label(), 'size-chart-for-woocommerce' );
    ?>"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <label for="size-chart-popup-label">
                                                        <?php 
    esc_html_e( 'Size Chart Link Title', 'size-chart-for-woocommerce' );
    ?>
                                                    </label>
                                                </th>
                                                <td>
                                                    <input type="text" name="size-chart-popup-label" id="size-chart-popup-label" placeholder="<?php 
    esc_attr_e( 'Size Chart', 'size-chart-for-woocommerce' );
    ?>" value="<?php 
    esc_attr_e( scfw_size_chart_get_popup_label(), 'size-chart-for-woocommerce' );
    ?>"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <label for="size-chart-popup-type">
                                                        <?php 
    esc_html_e( 'Size Chart Link Type', 'size-chart-for-woocommerce' );
    ?>
                                                    </label>
                                                </th>
                                                <td>
                                                    <select name="size-chart-popup-type" id="size-chart-popup-type">
                                                        <option value="text" <?php 
    selected( scfw_size_chart_get_popup_type(), 'text' );
    ?> ><?php 
    esc_html_e( 'Text', 'size-chart-for-woocommerce' );
    ?></option>
                                                        <option value="button" <?php 
    selected( scfw_size_chart_get_popup_type(), 'button' );
    ?> ><?php 
    esc_html_e( 'Button', 'size-chart-for-woocommerce' );
    ?></option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <label for="size-chart-sub-title-text">
                                                        <?php 
    esc_html_e( 'Sub Title', 'size-chart-for-woocommerce' );
    ?>
                                                    </label>
                                                </th>
                                                <td>
                                                    <input type="text" name="size-chart-sub-title-text" id="size-chart-sub-title-text" placeholder="<?php 
    esc_attr_e( 'How To Measure', 'size-chart-for-woocommerce' );
    ?>" value="<?php 
    esc_attr_e( scfw_size_chart_get_sub_title_text(), 'size-chart-for-woocommerce' );
    ?>"/>
                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                    <fieldset class="custom-fieldset set-size-chart-fieldset<?php 
    echo  esc_attr( $plugin_pro_class ) ;
    ?>" <?php 
    disabled( $is_disable, true, true );
    ?>>
                                        <legend>
                                            <?php 
    esc_html_e( 'Pop Up Settings', 'size-chart-for-woocommerce' );
    ?>
                                            <?php 
    echo  esc_html( $available_in_pro_text ) ;
    ?>
                                        </legend>
                                        <div class="setting-description">
                                            <p><?php 
    esc_html_e( 'With this setting you can configure size chart popup link position, color and you can apply custom class also.', 'size-chart-for-woocommerce' );
    ?></p>
                                        </div>
                                        <table class="form-table">
                                            <tr>
                                                <th>
                                                    <h4>
                                                        <label for="size-chart-button-position">
                                                            <?php 
    esc_html_e( 'Chart Popup Label Position', 'size-chart-for-woocommerce' );
    ?>
                                                        </label>
                                                    </h4>
                                                </th>
                                                <td>
                                                    <select name="size-chart-button-position" id="size-chart-button-position" <?php 
    disabled( $is_disable, true, true );
    ?>>
                                                        <option value="before-summary-text" <?php 
    selected( $size_chart_get_button_position, 'before-summary-text', true );
    ?> ><?php 
    esc_html_e( 'Before Summary Text', 'size-chart-for-woocommerce' );
    ?></option>
                                                        <?php 
    ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <label for="color-picker1">
                                                        <?php 
    esc_html_e( 'Label Text Color', 'size-chart-for-woocommerce' );
    ?>
                                                    </label>
                                                </th>
                                                <td>
                                                    <input type="hidden" name="size-chart-title-color" id="color-picker1" value="<?php 
    echo  esc_attr( $size_chart_get_title_color ) ;
    ?>" <?php 
    disabled( $is_disable, true, true );
    ?>/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <label for="size-chart-button-class">
                                                        <?php 
    esc_html_e( 'Label Class', 'size-chart-for-woocommerce' );
    ?>
                                                    </label>
                                                </th>
                                                <td>
                                                    <input type="text" name="size-chart-button-class" id="size-chart-button-class" placeholder="<?php 
    esc_attr_e( 'scfw-size-chart-link', 'size-chart-for-woocommerce' );
    ?>" value="<?php 
    echo  esc_attr( $size_chart_get_button_class ) ;
    ?>" <?php 
    disabled( $is_disable, true, true );
    ?>/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <h4>
                                                        <label for="size-chart-size">
                                                            <?php 
    esc_html_e( 'Chart Popup Size', 'size-chart-for-woocommerce' );
    ?>
                                                        </label>
                                                    </h4>
                                                </th>
                                                <td>
                                                    <select name="size-chart-size" id="size-chart-size" <?php 
    disabled( $is_disable, true, true );
    ?>>
                                                        <?php 
    
    if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
        ?>
                                                            <option value="small" <?php 
        selected( $size_chart_get_size, 'small', true );
        ?>><?php 
        esc_html_e( 'Small', 'size-chart-for-woocommerce' );
        ?></option>
                                                            <?php 
    }
    
    ?>
                                                        <option value="medium" <?php 
    selected( $size_chart_get_size, 'medium', true );
    ?> ><?php 
    esc_html_e( 'Medium', 'size-chart-for-woocommerce' );
    ?></option>
                                                        <?php 
    
    if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
        ?>
                                                            <option value="large" <?php 
        selected( $size_chart_get_size, 'large', true );
        ?>><?php 
        esc_html_e( 'Large', 'size-chart-for-woocommerce' );
        ?></option>
                                                            <?php 
    }
    
    ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                    <fieldset class="custom-fieldset set-size-chart-fieldset<?php 
    echo  esc_attr( $plugin_pro_class ) ;
    ?>" <?php 
    disabled( $is_disable, true, true );
    ?>>
                                        <legend>
                                            <?php 
    esc_html_e( 'Size Chart User Permissions', 'size-chart-for-woocommerce' );
    ?>
                                            <?php 
    echo  esc_html( $available_in_pro_text ) ;
    ?>
                                        </legend>
                                        <div class="setting-description">
                                            <p><?php 
    esc_html_e( 'Select user roles to whom grant the access to manage (Add, Update, Delete) size chart. ', 'size-chart-for-woocommerce' );
    ?></p>
                                        </div>
                                        <table class="form-table">
                                            <tr>
                                                <label for="scsf_user_role"></label>
                                                <select id="scsf_user_role" name="scsf_user_role[]" <?php 
    disabled( $is_disable, true, true );
    ?> multiple="multiple">
                                                    <?php 
    global  $wp_roles ;
    $all_roles = $wp_roles->roles;
    if ( !empty($all_roles) && isset( $all_roles ) ) {
        foreach ( $all_roles as $role_key => $details ) {
            
            if ( 'administrator' !== $role_key ) {
                $pw = get_role( $role_key )->capabilities;
                
                if ( array_key_exists( 'manage_woocommerce', $pw ) && array_key_exists( 'edit_posts', $pw ) ) {
                    
                    if ( !empty($size_chart_get_user_roles) ) {
                        
                        if ( in_array( $role_key, $size_chart_get_user_roles, true ) ) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                    
                    } else {
                        $selected = '';
                    }
                    
                    ?>
                                                                            <option <?php 
                    echo  esc_attr( $selected ) ;
                    ?> value="<?php 
                    echo  esc_attr( $role_key ) ;
                    ?>"><?php 
                    echo  esc_html( $details['name'] ) ;
                    ?></option>
                                                                        <?php 
                }
            
            }
        
        }
    }
    ?>
                                                </select>
                                            </tr>
                                        </table>
                                    </fieldset>
                                    <fieldset class="custom-fieldset set-size-chart-fieldset<?php 
    echo  esc_attr( $plugin_pro_class ) ;
    ?>" <?php 
    disabled( $is_disable, true, true );
    ?>>
                                        <legend>
                                            <?php 
    esc_html_e( 'Custom CSS', 'size-chart-for-woocommerce' );
    ?>
                                            <?php 
    echo  esc_html( $available_in_pro_text ) ;
    ?>
                                        </legend>
                                        <div class="setting-description">
                                            <p>
                                                <?php 
    printf(
        "%s <a href='%s' target='_blank'>%s</a>%s",
        esc_html__( 'Apply your custom CSS here to personalize and customize the size chart to align with your preferences.', 'size-chart-for-woocommerce' ),
        esc_url( 'https://docs.thedotstore.com/article/729-how-to-customize-the-size-chart-with-custom-css' ),
        esc_html__( 'Click here', 'size-chart-for-woocommerce' ),
        esc_html__( ' for more information.', 'size-chart-for-woocommerce' )
    );
    ?> 
                                            </p>
                                        </div>
                                        <table class="form-table">
                                            <tr>
                                                <label for="custom_css"></label>
                                                <textarea id="custom_css" name="custom_css" rows="3" placeholder="<?php 
    echo  esc_attr( '.scfw-size-chart-main .md-size-chart-btn{}
table#size-chart tr th, table#size-chart tr td{}' ) ;
    ?>" <?php 
    disabled( $is_disable, true, true );
    ?>><?php 
    echo  esc_html( $size_chart_get_custom_css ) ;
    ?></textarea>
                                            </tr>
                                        </table>
                                    </fieldset>
                                    <?php 
    submit_button( esc_attr__( 'Save Changes', 'size-chart-for-woocommerce' ), 'primary', 'size_chart_submit' );
    ?>
                                </div>
                                <?php 
}

?>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>



