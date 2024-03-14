<?php
 /**
 * The admin-specific functions of the plugin.
 *
 * @link        https://github.com/KelkooGroup/woocommerce-kelkoogroup-salestracking
 * @since       1.0.0
 * Author:      Kelkoo Group
 * Author URI:  https://www.kelkoogroup.com/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * @package     Kelkoogroup_SalesTracking
 * @subpackage  Kelkoogroup_SalesTracking/admin
 */

function kelkoogroup_action_links( $links ) {
    $links = array_merge( array(
            '<a href="' . esc_url( admin_url( '/options-general.php?page=kelkoogroup-settings' ) ) . '">' . __( 'Settings', 'kelkoogroup-sales-tracking' ) . '</a>'  ), $links );
    return $links;
}

function kelkoogroup_salestracking_add_admin_menu(  ) {
    add_options_page( 'Kelkoogroup salestracking Page', 'Kelkoogroup', 'manage_options', 'kelkoogroup-settings', 'kelkoogroup_salestracking_options_page' );
}

function kelkoogroup_salestracking_settings_init(  ) {
    register_setting( 'kkSalesTrackingPlugin', 'kelkoogroup_salestracking_settings' );

    add_settings_section(
        'kelkoogroup_salestracking_kkSalesTrackingPlugin_intro_section',
        __( 'Kelkoogroup Sales tracking', 'kelkoogroup-sales-tracking' ),
        'kelkoogroup_salestracking_settings_intro_section_callback',
        'kkSalesTrackingPlugin'
    );

    add_settings_section(
        'kelkoogroup_salestracking_kkSalesTrackingPlugin_onecampaign_section',
        __( 'Kelkoogroup Sales tracking - only one campaign', 'kelkoogroup-sales-tracking' ),
        'kelkoogroup_salestracking_settings_onecampaign_section_callback',
        'kkSalesTrackingPlugin'
    );

    add_settings_field(
        'kelkoogroup_salestracking_country',
        __( 'Country', 'kelkoogroup-sales-tracking' ),
        'kelkoogroup_salestracking_country_render',
        'kkSalesTrackingPlugin',
        'kelkoogroup_salestracking_kkSalesTrackingPlugin_onecampaign_section'
    );

    add_settings_field(
        'kelkoogroup_salestracking_comid',
        __( 'Merchant Identifier', 'kelkoogroup-sales-tracking' ),
        'kelkoogroup_salestracking_comid_render',
        'kkSalesTrackingPlugin',
        'kelkoogroup_salestracking_kkSalesTrackingPlugin_onecampaign_section'
    );

    add_settings_section(
        'kelkoogroup_salestracking_kkSalesTrackingPlugin_multicomid_section',
        __( 'Kelkoogroup Sales tracking - multiple campaign', 'kelkoogroup-sales-tracking' ),
        'kelkoogroup_salestracking_settings_multicomid_section_callback',
        'kkSalesTrackingPlugin'
    );

     add_settings_field(
        'kelkoogroup_salestracking_multicomid',
        __( 'Multi Merchant Information', 'kelkoogroup-sales-tracking' ),
        'kelkoogroup_salestracking_multicomid_render',
        'kkSalesTrackingPlugin',
        'kelkoogroup_salestracking_kkSalesTrackingPlugin_multicomid_section'
    );

}

function kelkoogroup_salestracking_country_render(  ) {
    $options = get_option( 'kelkoogroup_salestracking_settings' );
    ?>
    <input type='text' name='kelkoogroup_salestracking_settings[kelkoogroup_salestracking_country]' value='<?php echo esc_html( $options['kelkoogroup_salestracking_country'] ); ?>'>
    <?php
}

function kelkoogroup_salestracking_comid_render(  ) {
    $options = get_option( 'kelkoogroup_salestracking_settings' );
    ?>
    <input type='text' name='kelkoogroup_salestracking_settings[kelkoogroup_salestracking_comid]' value='<?php echo esc_html( $options['kelkoogroup_salestracking_comid'] ); ?>'>
    <?php
}

function kelkoogroup_salestracking_multicomid_render(  ) {
    $options = get_option( 'kelkoogroup_salestracking_settings' );
    ?>
    <input type='text' name='kelkoogroup_salestracking_settings[kelkoogroup_salestracking_multicomid]' value='<?php echo esc_html( $options['kelkoogroup_salestracking_multicomid'] ); ?>'>
<i>{country: <strong>"</strong>nl<strong>"</strong>, merchantId: <strong>"</strong>123<strong>"</strong>}, {country: <strong>"</strong>nb<strong>"</strong>, merchantId: <strong>"</strong>345<strong>"</strong>}</i>
    <?php
}

function kelkoogroup_salestracking_settings_intro_section_callback(  ) {
    echo esc_html__( "<p>Kelkoogroup Sales Tracking requires a few configuration.</p>",'kelkoogroup-sales-tracking' );
}

function kelkoogroup_salestracking_settings_onecampaign_section_callback(  ) {
    echo esc_html__( "<p>          Merchant Identifier: This is the unique ID representing your shop within the Kelkoo system. You got it by email at your subscription, else to recover it you can ask your Kelkoogroup account manager. </p>
 <p>          Country is the 2-letter country code for the country on which your products are listed on Kelkoo:
 'at' for Austria, 'be' for Belgium, 'br' for Brazil, 'ch' for Switzerland, 'cz' for Czech Republic, 'de' for Germany,
 'dk' for Denmark, 'es' for Spain, 'fi' for Finland, 'fr' for France, 'ie ' for Ireland, 'it' for Italy, 'mx' for Mexico,
  'nb' for Flemish Belgium 'nl' for Netherlands, 'no' for Norway, 'pl' for Poland, 'pt' for Portugal, 'ru' for Russia,
  'se' for Sweden, 'uk' for United Kingdom, 'us' for United States... </p>
  <p>You can get the full list on <a href='https://github.com/KelkooGroup/woocommerce-kelkoogroup-salestracking#country' target='_blank'>https://github.com/KelkooGroup/woocommerce-kelkoogroup-salestracking#country</a> </p>",
'kelkoogroup-sales-tracking' );
}

function kelkoogroup_salestracking_settings_multicomid_section_callback(  ) {
    echo esc_html__( "<p>      Multi merchant information : If you need to configure multiple merchant information (you have multiple Merchant Identifier/Country), you can copy/paste the sample and update it.",
'kelkoogroup-sales-tracking' );
}

function kelkoogroup_salestracking_options_page(  ) {
    ?>
    <form action='options.php' method='post'>

        <h2>Kelkoogroup setting Page</h2>

        <?php
        settings_fields( 'kkSalesTrackingPlugin' );
        do_settings_sections( 'kkSalesTrackingPlugin' );
        submit_button();
        ?>

    </form>
    <?php
}
