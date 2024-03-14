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
// Add an nonce field so we can check for it later.
wp_nonce_field( 'size_chart_inner_custom_box', 'size_chart_inner_custom_box' );
$size_cart_post_id = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
// Use get_post_meta to retrieve an existing value from the database.
$chart_label = scfw_size_chart_get_label_by_chart_id( $size_cart_post_id );
$chart_tab_label = scfw_size_chart_get_tab_label_by_chart_id( $size_cart_post_id );
$chart_popup_label = scfw_size_chart_get_popup_label_by_chart_id( $size_cart_post_id );
$chart_position = scfw_size_chart_get_position_by_chart_id( $size_cart_post_id );
$chart_table = scfw_size_chart_get_chart_table_by_chart_id( $size_cart_post_id, false );
$table_style = scfw_size_chart_get_chart_table_style_by_chart_id( $size_cart_post_id );

if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
    $chart_color = scfw_size_chart_color_details__premium_only( $size_cart_post_id );
    $chart_border = scfw_size_chart_border_details__premium_only( $size_cart_post_id );
    $chart_country = scfw_size_chart_country__premium_only( $size_cart_post_id );
    $chart_table_hover = scfw_size_chart_table_hover__premium_only( $size_cart_post_id );
}

$chart_popup_note = scfw_size_chart_popup_note( $size_cart_post_id );
$is_disable = true;
?>
<div id="size-chart-meta-fields">
    <div class="field">
        <div class="field-header">
            <div class="field-title">
                <h4>
                    <label for="label">
                        <?php 
esc_html_e( 'Size Chart Popup Title', 'size-chart-for-woocommerce' );
?>
                    </label>
                </h4>
            </div>
            <div class="field-description">
                <?php 
esc_html_e( 'If empty, It will take the name you provided above for this size chart.', 'size-chart-for-woocommerce' );
?>
            </div>
        </div>
        <div class="field-item">
            <input type="text" name="label" id="label" placeholder="<?php 
( isset( $chart_label ) && !empty($chart_label) ? esc_html_e( $chart_label, 'size-chart-for-woocommerce' ) : esc_html_e( 'Size Chart', 'size-chart-for-woocommerce' ) );
?>" value="<?php 
echo  esc_attr( $chart_label ) ;
?>"/>
        </div>
    </div>
    <div class="field">
        <div class="field-header">
            <div class="field-title">
                <h4>
                    <label for="position">
                        <?php 
esc_html_e( 'Size Chart Position', 'size-chart-for-woocommerce' );
?>
                    </label>
                </h4>
            </div>
            <div class="field-description">
                <?php 
esc_html_e( 'Select if the chart will display as a popup or as a additional tab', 'size-chart-for-woocommerce' );
?>
            </div>
        </div>
        <div class="field-item">
            <select name="position" id="position">
                <option value="tab" <?php 
selected( $chart_position, 'tab', true );
?>><?php 
esc_html_e( 'Additional Tab', 'size-chart-for-woocommerce' );
?></option>
                <option value="popup" <?php 
selected( $chart_position, 'popup', true );
?>><?php 
esc_html_e( 'Modal Pop Up', 'size-chart-for-woocommerce' );
?></option>
            </select>
        </div>
    </div>
    <?php 
$defalut_chart_position = ( isset( $chart_position ) && '' !== $chart_position ? $chart_position : 'tab' );
?>
    <div class="field chart-tab-field <?php 
echo  ( isset( $defalut_chart_position ) && 'tab' === $defalut_chart_position ? 'enable' : 'disable' ) ;
?>">
        <div class="field-header">
            <div class="field-title">
                <h4>
                    <label for="chart-tab-label">
                        <?php 
esc_html_e( 'Tab Label', 'size-chart-for-woocommerce' );
?>
                    </label>
                </h4>
            </div>
            <div class="field-description">
                <?php 
esc_html_e( 'Add size chart tab title. Default it will showcase from global settings.', 'size-chart-for-woocommerce' );
?>
            </div>
        </div>
        <div class="field-item">
            <input type="text" name="chart-tab-label" id="chart-tab-label" placeholder="<?php 
esc_attr_e( scfw_size_chart_get_tab_label(), 'size-chart-for-woocommerce' );
?>" value="<?php 
echo  esc_attr( $chart_tab_label ) ;
?>"/>
        </div>
    </div>
    <div class="field chart-popup-field <?php 
echo  ( isset( $defalut_chart_position ) && 'popup' === $defalut_chart_position ? 'enable' : 'disable' ) ;
?>">
        <div class="field-header">
            <div class="field-title">
                <h4>
                    <label for="chart-popup-label">
                        <?php 
esc_html_e( 'Size Chart Link Title', 'size-chart-for-woocommerce' );
?>
                    </label>
                </h4>
            </div>
            <div class="field-description">
                <?php 
esc_html_e( 'Add size chart link title. Default it will showcase from global settings.', 'size-chart-for-woocommerce' );
?>
            </div>
        </div>
        <div class="field-item">
            <input type="text" name="chart-popup-label" id="chart-popup-label" placeholder="<?php 
esc_attr_e( scfw_size_chart_get_popup_label(), 'size-chart-for-woocommerce' );
?>" value="<?php 
echo  esc_attr( $chart_popup_label ) ;
?>"/>
        </div>
    </div>
    <div class="field chart-popup-field <?php 
echo  ( isset( $defalut_chart_position ) && 'popup' === $defalut_chart_position ? 'enable' : 'disable' ) ;
?>">
        <div class="field-header">
            <div class="field-title">
                <h4>
                    <label for="chart-popup-type">
                        <?php 
esc_html_e( 'Size Chart Link Type', 'size-chart-for-woocommerce' );
?>
                    </label>
                </h4>
            </div>
            <div class="field-description">
                <?php 
esc_html_e( 'Select size chart link type; Default it will consider global settings', 'size-chart-for-woocommerce' );
?>
            </div>
        </div>
        <div class="field-item">
            <select name="chart-popup-type" id="chart-popup-type">
                <option value="global" <?php 
selected( scfw_size_chart_get_popup_type_by_chart_id( $size_cart_post_id ), 'global' );
?> ><?php 
esc_html_e( 'Global Setting', 'size-chart-for-woocommerce' );
?></option>
                <option value="text" <?php 
selected( scfw_size_chart_get_popup_type_by_chart_id( $size_cart_post_id ), 'text' );
?> ><?php 
esc_html_e( 'Text', 'size-chart-for-woocommerce' );
?></option>
                <option value="button" <?php 
selected( scfw_size_chart_get_popup_type_by_chart_id( $size_cart_post_id ), 'button' );
?> ><?php 
esc_html_e( 'Button', 'size-chart-for-woocommerce' );
?></option>
            </select>
        </div>
    </div>
    <div class="field chart-popup-field <?php 
echo  ( isset( $defalut_chart_position ) && 'popup' === $defalut_chart_position ? 'enable' : 'disable' ) ;
?>">
        <div class="field-header">
            <div class="field-title">
                <h4>
                    <label for="chart-popup-icon">
                        <?php 
esc_html_e( 'Popup Icon', 'size-chart-for-woocommerce' );
?>
                    </label>
                </h4>
            </div>
            <div class="field-description">
                <?php 
esc_html_e( 'Selected chart popup icon will show before chart popup link title.', 'size-chart-for-woocommerce' );
?>
            </div>
        </div>
        <div class="field-item">
            <input type="hidden" name="chart-popup-icon" id="chart-popup-icon" value="<?php 
echo  esc_attr_e( scfw_size_chart_get_popup_icon_by_chart_id( $size_cart_post_id ), 'size-chart-for-woocommerce' ) ;
?>"/>
            <div class="field-default-icon-wrap">
                <label>
                    <input type="radio" name="default-icons" value="dashicons-none" <?php 
checked( scfw_size_chart_get_popup_icon_by_chart_id( $size_cart_post_id ), '' );
?> />
                    <span class="dashicons dashicons-none">None</span>
                </label>
                <?php 
foreach ( glob( SCFW_PLUGIN_DIR_PATH . 'includes/chart-icons/*.svg' ) as $icon_path ) {
    $patharr = explode( '/', $icon_path );
    $filename = end( $patharr );
    $filevalue = explode( '.', $filename );
    $filevalue = ( $filevalue[0] ? $filevalue[0] : '' );
    
    if ( !empty($filevalue) ) {
        ?>
                    <label>
                        <input type="radio" name="default-icons" value="<?php 
        echo  esc_attr( $filevalue ) ;
        ?>" <?php 
        checked( scfw_size_chart_get_popup_icon_by_chart_id( $size_cart_post_id ), $filevalue );
        ?>/>
                        <span class="dashicons">
                            <img src="<?php 
        echo  esc_url( SCFW_PLUGIN_URL . 'includes/chart-icons/' . $filename ) ;
        ?>" />
                        </span>
                    </label>
                    <?php 
    }

}
?>
            </div>
        </div>
    </div>
    <div class="field">
        <div class="field-header">
            <div class="field-title">
                <h4>
                    <label for="size-chart-style">
                        <?php 
esc_html_e( 'Size Chart Style', 'size-chart-for-woocommerce' );
?>
                    </label>
                </h4>
            </div>
            <div class="field-description">
                <?php 
esc_html_e( 'Display your size chart content and table in a standard view or as a tabbed view.', 'size-chart-for-woocommerce' );
?>
            </div>
        </div>
        <div class="field-item">
            <select id="size-chart-style" name="size-chart-style">
                <option value="standard_style" <?php 
selected( scfw_size_chart_style_value_by_chart_id( $size_cart_post_id ), 'standard_style' );
?>><?php 
esc_html_e( 'Standard Chart', 'size-chart-for-woocommerce' );
?></option>
                <option value="tab_style" <?php 
selected( scfw_size_chart_style_value_by_chart_id( $size_cart_post_id ), 'tab_style' );
?>><?php 
esc_html_e( 'Tabbed Chart', 'size-chart-for-woocommerce' );
?></option>
            </select>
        </div>
    </div>
    <?php 

if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
    ?>
    <div class="field chart-popup-field <?php 
    echo  ( isset( $defalut_chart_position ) && 'popup' === $defalut_chart_position ? 'enable' : 'disable' ) ;
    ?>">
        <div class="field-header">
            <div class="field-title">
                <h4>
                    <label for="popup-position">
                        <?php 
    esc_html_e( 'Pupup Position', 'size-chart-for-woocommerce' );
    ?>
                    </label>
                </h4>
            </div>
            <div class="field-description">
                <?php 
    esc_html_e( 'Selected popup position will apply on the front side. The default position is "Center".', 'size-chart-for-woocommerce' );
    ?>
            </div>
        </div>
        <div class="field-item">
            <select id="popup-position" name="popup-position">
                <option value="center" <?php 
    selected( scfw_get_popup_postition__premium_only( $size_cart_post_id ), 'center' );
    ?>><?php 
    esc_html_e( 'Center', 'size-chart-for-woocommerce' );
    ?></option>
                <option value="left" <?php 
    selected( scfw_get_popup_postition__premium_only( $size_cart_post_id ), 'left' );
    ?>><?php 
    esc_html_e( 'Left', 'size-chart-for-woocommerce' );
    ?></option>
                <option value="right" <?php 
    selected( scfw_get_popup_postition__premium_only( $size_cart_post_id ), 'right' );
    ?>><?php 
    esc_html_e( 'Right', 'size-chart-for-woocommerce' );
    ?></option>
            </select>
        </div>
    </div>
    <div class="field">
        <div class="field-header">
            <div class="field-title">
                <h4>
                    <label for="chart-country">
                        <?php 
    esc_html_e( 'Select Countries to Show', 'size-chart-for-woocommerce' );
    ?>
                    </label>
                </h4>
            </div>
            <div class="field-description">
                <?php 
    esc_html_e( 'Leave empty to show size chart for all countries.', 'size-chart-for-woocommerce' );
    ?>
            </div>
        </div>
        <div class="field-item">
            <?php 
    $countries_obj = new WC_Countries();
    $countries = $countries_obj->__get( 'countries' );
    ?>
            <select id="chart-country" name="chart-country[]" multiple="multiple">
                <?php 
    foreach ( $countries as $country_code => $country_name ) {
        $selected = ( !empty($chart_country) && in_array( $country_code, $chart_country, true ) ? 'selected=selected' : '' );
        ?>
                    <option value="<?php 
        echo  esc_attr( $country_code ) ;
        ?>" <?php 
        echo  esc_attr( $selected ) ;
        ?>><?php 
        echo  esc_html( $country_name ) ;
        ?></option>
                <?php 
    }
    ?>
            </select>
        </div>
    </div>
    <?php 
}

?>
    <div class="field">
        <div class="field-header">
            <div class="field-title">
                <h4>
                    <label for="table-style">
                        <?php 
esc_html_e( 'Chart Table Style', 'size-chart-for-woocommerce' );
?>
                    </label>
                </h4>
            </div>
            <div class="field-description">
                <?php 
esc_html_e( 'Chart Table Styles (Default Style)', 'size-chart-for-woocommerce' );
?>
            </div>
        </div>
        <div class="field-item">
            <select name="table-style" id="table-style">
                <option value="default-style" <?php 
selected( $table_style, 'default-style', true );
?>><?php 
esc_html_e( 'Default Style', 'size-chart-for-woocommerce' );
?></option>
                <option value="minimalistic" <?php 
selected( $table_style, 'minimalistic', true );
?>><?php 
esc_html_e( 'Minimalistic', 'size-chart-for-woocommerce' );
?></option>
                <option value="classic" <?php 
selected( $table_style, 'classic', true );
?>><?php 
esc_html_e( 'Classic', 'size-chart-for-woocommerce' );
?></option>
                <option value="modern" <?php 
selected( $table_style, 'modern', true );
?>><?php 
esc_html_e( 'Modern', 'size-chart-for-woocommerce' );
?></option>
                <?php 

if ( scfw_fs()->is__premium_only() & scfw_fs()->can_use_premium_code() ) {
    ?>
                    <option value="custom-style" <?php 
    selected( $table_style, 'custom-style', true );
    ?> <?php 
    disabled( $is_disable, true, true );
    ?> ><?php 
    esc_html_e( 'Custom Style', 'size-chart-for-woocommerce' );
    ?></option>
                    <option value="advance-style" <?php 
    selected( $table_style, 'advance-style', true );
    ?> <?php 
    disabled( $is_disable, true, true );
    ?> ><?php 
    esc_html_e( 'Advance Style', 'size-chart-for-woocommerce' );
    ?></option>
                <?php 
}

?>
            </select>
        </div>
    </div>
    <?php 

if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
    ?>
    <div class="field scfw_font-size-field">
        <div class="field-header">
            <div class="field-title">
                <h4>
                    <label for="chart-table-font-size">
                        <?php 
    esc_html_e( 'Chart Table Font Size', 'size-chart-for-woocommerce' );
    ?>
                    </label>
                </h4>
            </div>
            <div class="field-description">
                <?php 
    esc_html_e( 'Select chart table font size.', 'size-chart-for-woocommerce' );
    ?>
            </div>
        </div>
        <div class="field-item">
            <input type="hidden" name="chart-table-font-size" id="chart-table-font-size" value="<?php 
    echo  esc_attr_e( scfw_size_chart_table_font_size__premium_only( $size_cart_post_id ), 'size-chart-for-woocommerce' ) ;
    ?>"/>
            <div class="field-default-icon-wrap">
                <?php 
    $scfw_table_font_size_array = array(
        's'   => 'S',
        'm'   => 'M',
        'l'   => 'L',
        'xl'  => 'XL',
        'xxl' => 'XXL',
    );
    foreach ( $scfw_table_font_size_array as $size_key => $size_value ) {
        
        if ( !empty($size_value) ) {
            ?>
                    <label>
                        <input type="radio" name="chart-table-font-sizes" value="<?php 
            echo  esc_attr( $size_key ) ;
            ?>" <?php 
            checked( scfw_size_chart_table_font_size__premium_only( $size_cart_post_id ), $size_key );
            ?>/>
                        <span class="dashicons">
                            <?php 
            esc_html_e( $size_value, 'size-chart-for-woocommerce' );
            ?>
                        </span>
                    </label>
                    <?php 
        }
    
    }
    ?>
            </div>
        </div>
    </div>
    <?php 
}

?>
    <div class="field no-column-layout">
        <div class="field-title">
            <h4>
                <label for="chart-table">
					<?php 
esc_html_e( 'Chart Table', 'size-chart-for-woocommerce' );
?>
                </label>
            </h4>
        </div>
        <div class="field-description">
			<?php 
esc_html_e( 'Add/Edit chart below', 'size-chart-for-woocommerce' );
?>
        </div>
        <div class="field-description">
            <?php 
$multitable_note = sprintf(
    '<strong>%s</strong> - %s<a href=%s target="_blank">%s</a>%s',
    esc_html__( 'Multitable', 'size-chart-for-woocommerce' ),
    esc_html__( 'Use three asterisks (***) for section titles and two asterisks (**) for new tables. ', 'size-chart-for-woocommerce' ),
    esc_url( plugins_url( 'images/size-chart-multitable.png', dirname( __FILE__ ) ) ),
    esc_html__( 'Click here', 'size-chart-for-woocommerce' ),
    esc_html__( ' for more info.', 'size-chart-for-woocommerce' )
);
echo  wp_kses_post( $multitable_note ) ;
?>
        </div>
        <div class="field-item">
            <table class="multiple_action_wrap" width="100%">
                <thead>
                    <tr>
                        <td>
                            <span class="scfw-table-action-toggle"></span>
                        </td>
                    </tr>
                </thead>
                <tbody class="scfw-table-actions-tbody">
                    <tr class="row_wrap">
                        <td>
                            <label for="scfw_add_multi_row"><?php 
esc_html_e( 'Enter Row count to insert', 'size-chart-for-woocommerce' );
?></label>
                        </td>
                        <td colspan="3">
                            <input type="number" name="scfw_add_multi_row" id="scfw_add_multi_row" placeholder="<?php 
esc_attr_e( 'Enter any number', 'size-chart-for-woocommerce' );
?>" style="width: 50% !important;" />
                            <button type="button" name="scfw_add_multi_row_action" id="scfw_add_multi_row_action" class="button"><?php 
esc_html_e( 'Add', 'size-chart-for-woocommerce' );
?></button>
                            <button type="button" name="scfw_delete_multi_row_action" id="scfw_delete_multi_row_action" class="button button-secondary"><?php 
esc_html_e( 'Delete', 'size-chart-for-woocommerce' );
?></button>
                        </td>
                    </tr>
                    <tr class="column_wrap">
                        <td>
                            <label for="scfw_delete_multi_column"><?php 
esc_html_e( 'Enter Column count to insert', 'size-chart-for-woocommerce' );
?></label>
                        </td>
                        <td colspan="3">
                            <input type="number" name ="scfw_delete_multi_column" id="scfw_delete_multi_column" placeholder="<?php 
esc_attr_e( 'Enter any number', 'size-chart-for-woocommerce' );
?>" style="width: 50% !important;" />
                            <button type="button" name="scfw_add_multi_column_action" id="scfw_add_multi_column_action" class="button"><?php 
esc_html_e( 'Add', 'size-chart-for-woocommerce' );
?></button>
                            <button type="button" name="scfw_delete_multi_column_action" id="scfw_delete_multi_column_action" class="button button-secondary"><?php 
esc_html_e( 'Delete', 'size-chart-for-woocommerce' );
?></button>
                        </td>
                    </tr>
                    <?php 

if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
    ?>
                        <tr>
                            <td colspan="4"><h3><?php 
    esc_html_e( 'Table Color', 'size-chart-for-woocommerce' );
    ?></h3></td>
                        </tr>
                        <tr>
                            <td>
                                <?php 
    esc_html_e( 'Header background', 'size-chart-for-woocommerce' );
    ?>
                            </td>
                            <td>
                                <div class="scfw_color_picker">
                                    <input type="text" name="scfw_header_bg_color" id="scfw_header_bg_color" value="<?php 
    echo  esc_attr( $chart_color['scfw_header_bg_color'] ) ;
    ?>" />
                                </div>
                            </td>
                            <td>
                                <?php 
    esc_html_e( 'Text Color', 'size-chart-for-woocommerce' );
    ?>
                            </td>
                            <td>
                                <div class="scfw_color_picker">
                                    <input type="text" name="scfw_text_color" id="scfw_text_color" value="<?php 
    echo  esc_attr( $chart_color['scfw_text_color'] ) ;
    ?>" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php 
    esc_html_e( 'Even row background', 'size-chart-for-woocommerce' );
    ?>
                            </td>
                            <td>
                                <div class="scfw_color_picker">
                                    <input type="text" name="scfw_even_row_bg_color" id="scfw_even_row_bg_color" value="<?php 
    echo  esc_attr( $chart_color['scfw_even_row_bg_color'] ) ;
    ?>" />
                                </div>                        
                            </td>
                            <td>
                                <?php 
    esc_html_e( 'Even row text', 'size-chart-for-woocommerce' );
    ?>
                            </td>
                            <td>
                                <div class="scfw_color_picker">
                                    <input type="text" name="scfw_even_text_color" id="scfw_even_text_color" value="<?php 
    echo  esc_attr( $chart_color['scfw_even_text_color'] ) ;
    ?>" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php 
    esc_html_e( 'Odd row background', 'size-chart-for-woocommerce' );
    ?>
                            </td>
                            <td>
                                <div class="scfw_color_picker">
                                    <input type="text" name="scfw_odd_row_bg_color" id="scfw_odd_row_bg_color" value="<?php 
    echo  esc_attr( $chart_color['scfw_odd_row_bg_color'] ) ;
    ?>" />
                                </div> 
                            </td>
                            <td>
                                <?php 
    esc_html_e( 'Odd row text', 'size-chart-for-woocommerce' );
    ?>
                            </td>
                            <td>
                                <div class="scfw_color_picker">
                                    <input type="text" name="scfw_odd_text_color" id="scfw_odd_text_color" value="<?php 
    echo  esc_attr( $chart_color['scfw_odd_text_color'] ) ;
    ?>" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"><h3><?php 
    esc_html_e( 'Border Color', 'size-chart-for-woocommerce' );
    ?></h3></td>
                        </tr>
                        <tr>
                            <td>
                                <?php 
    esc_html_e( 'Horizontal border style', 'size-chart-for-woocommerce' );
    ?>
                            </td>
                            <td>
                                <div class="scfw_border_setting">
                                    <select name="scfw_border_hb_style" id="scfw_border_hb_style">
                                        <option value="<?php 
    echo  esc_attr( 'solid' ) ;
    ?>" <?php 
    selected( $chart_border['scfw_border_hb_style'], 'solid', true );
    ?>><?php 
    esc_html_e( 'Solid', 'size-chart-for-woocommerce' );
    ?></option>
                                        <option value="<?php 
    echo  esc_attr( 'dotted' ) ;
    ?>" <?php 
    selected( $chart_border['scfw_border_hb_style'], 'dotted', true );
    ?>><?php 
    esc_html_e( 'Dotted', 'size-chart-for-woocommerce' );
    ?></option>
                                        <option value="<?php 
    echo  esc_attr( 'dashed' ) ;
    ?>" <?php 
    selected( $chart_border['scfw_border_hb_style'], 'dashed', true );
    ?>><?php 
    esc_html_e( 'Dashed', 'size-chart-for-woocommerce' );
    ?></option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <?php 
    esc_html_e( 'Horizontal width', 'size-chart-for-woocommerce' );
    ?>
                            </td>
                            <td>
                                <div class="scfw_border_setting">
                                    <input type="number" name="scfw_border_hw" id="scfw_border_hw" value="<?php 
    echo  esc_attr( $chart_border['scfw_border_hw'] ) ;
    ?>" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php 
    esc_html_e( 'Vertical border style', 'size-chart-for-woocommerce' );
    ?>
                            </td>
                            <td>
                                <div class="scfw_border_setting">
                                    <select name="scfw_border_vb_style" id="scfw_border_vb_style">
                                        <option value="<?php 
    echo  esc_attr( 'solid' ) ;
    ?>" <?php 
    selected( $chart_border['scfw_border_vb_style'], 'solid', true );
    ?>><?php 
    esc_html_e( 'Solid', 'size-chart-for-woocommerce' );
    ?></option>
                                        <option value="<?php 
    echo  esc_attr( 'dotted' ) ;
    ?>" <?php 
    selected( $chart_border['scfw_border_vb_style'], 'dotted', true );
    ?>><?php 
    esc_html_e( 'Dotted', 'size-chart-for-woocommerce' );
    ?></option>
                                        <option value="<?php 
    echo  esc_attr( 'dashed' ) ;
    ?>" <?php 
    selected( $chart_border['scfw_border_vb_style'], 'dashed', true );
    ?>><?php 
    esc_html_e( 'Dashed', 'size-chart-for-woocommerce' );
    ?></option>
                                    </select>
                                </div>                        
                            </td>
                            <td>
                                <?php 
    esc_html_e( 'Vertical width', 'size-chart-for-woocommerce' );
    ?>
                            </td>
                            <td>
                                <div class="scfw_border_setting">
                                    <input type="number" name="scfw_border_vw" id="scfw_border_vw" value="<?php 
    echo  esc_attr( $chart_border['scfw_border_vw'] ) ;
    ?>" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php 
    esc_html_e( 'Border color', 'size-chart-for-woocommerce' );
    ?>
                            </td>
                            <td>
                                <div class="scfw_border_setting scfw_color_picker">
                                    <input type="text" name="scfw_border_color" id="scfw_border_color" value="<?php 
    echo  esc_attr( $chart_border['scfw_border_color'] ) ;
    ?>" />
                                </div> 
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"><h3><?php 
    esc_html_e( 'Table Hover', 'size-chart-for-woocommerce' );
    ?></h3></td>
                        </tr>
                        <tr>
                            <td>
                                <?php 
    esc_html_e( 'Enable table hover effect', 'size-chart-for-woocommerce' );
    ?>
                            </td>
                            <td>
                                <div class="scfw_table_hover_setting">
                                    <select name="scfw_enable_table_hover" id="scfw_enable_table_hover">
                                        <option value="<?php 
    echo  esc_attr( 'no' ) ;
    ?>" <?php 
    selected( $chart_table_hover['scfw_enable_table_hover'], 'no', true );
    ?>><?php 
    esc_html_e( 'No', 'size-chart-for-woocommerce' );
    ?></option>
                                        <option value="<?php 
    echo  esc_attr( 'yes' ) ;
    ?>" <?php 
    selected( $chart_table_hover['scfw_enable_table_hover'], 'yes', true );
    ?>><?php 
    esc_html_e( 'Yes', 'size-chart-for-woocommerce' );
    ?></option>
                                    </select>
                                </div> 
                            </td>
                            <td>
                                <?php 
    esc_html_e( 'Table hover background color', 'size-chart-for-woocommerce' );
    ?>
                            </td>
                            <td>
                                <div class="scfw_table_hover_setting scfw_color_picker">
                                    <input type="text" name="scfw_table_hover_bg_color" id="scfw_table_hover_bg_color" value="<?php 
    echo  esc_attr( $chart_table_hover['scfw_table_hover_bg_color'] ) ;
    ?>" />
                                </div> 
                            </td>
                        </tr>
                    <?php 
}

?>
                </tbody>
            </table>
            <textarea id="chart-table" class="chart-table" name="chart-table"><?php 
echo  esc_html( $chart_table ) ;
?></textarea>
        </div>
        <div class="field-item">
            <?php 
echo  sprintf(
    '<a id="%s" href="javascript:void(0);" class="preview_chart button" title="%s" rel="permalink">%s</a>',
    esc_attr( $size_cart_post_id ),
    esc_attr__( 'Click here for preview', 'size-chart-for-woocommerce' ),
    esc_attr__( 'Preview', 'size-chart-for-woocommerce' )
) ;
?>
            <?php 
echo  sprintf(
    '<a id="%s" href="javascript:void(0);" class="import_chart button" title="%s" rel="permalink">%s</a>',
    esc_attr( $size_cart_post_id ),
    esc_attr__( 'Click to import size chart data', 'size-chart-for-woocommerce' ),
    esc_attr__( 'Import Chart Table', 'size-chart-for-woocommerce' )
) ;
?>
            <input type="file" class="scfw_import_file" accept="application/json" style="display:none;" />
            <?php 
echo  sprintf(
    '<a id="%s" href="javascript:void(0);" class="export_chart button" title="%s" rel="permalink">%s</a>',
    esc_attr( $size_cart_post_id ),
    esc_attr__( 'Click to export size chart data', 'size-chart-for-woocommerce' ),
    esc_attr__( 'Export Chart Table', 'size-chart-for-woocommerce' )
) ;
?>
        </div>
    </div>
    <div class="field no-column-layout">
        <div class="field-title">
            <h4>
                <label for="chart-popup-note">
                    <?php 
esc_html_e( 'Popup Note', 'size-chart-for-woocommerce' );
?>
                </label>
            </h4>
        </div>
        <div class="field-description">
            <?php 
esc_html_e( 'Add notes about the table sizes like it\'s in Inches, Meters, Centimeter, etc.', 'size-chart-for-woocommerce' );
?>
        </div>
        <div class="field-item">
            <input type="text" name="chart-popup-note" id="chart-popup-note" placeholder="<?php 
esc_attr_e( 'Enter your note here...', 'size-chart-for-woocommerce' );
?>" value="<?php 
echo  esc_attr( $chart_popup_note ) ;
?>"/>
        </div>
    </div>
</div>