<?php
defined( 'ABSPATH' ) || exit;

ob_start();
?>
    <div style="display:none;" class="xlwcty_tb_content" id="xlwcty_component_settings<?php echo $config['slug']; ?>_help">
        <h3><?php echo $config['title'] . ' ' . __( 'Component Design & Settings', 'woo-thank-you-page-nextmove-lite' ); ?></h3>
        <p class="xlwcty_center"><img src="//storage.googleapis.com/xl-nextmove/order-details.jpg"/></p>
        <table align="center" width="650" class="xlwcty_modal_table">
            <tr>
                <td width="50">1.</td>
                <td><strong>Heading:</strong> Enter any Heading. Customize font size and text alignment too.</td>
            </tr>
            <tr>
                <td>2.</td>
                <td><strong>Layout</strong> Plugin has 2 layouts `Advanced` or `Native WooCommerce`.<br/>Advanced - Cleaner look. Select to see the output.<br/>Native WooCommerce - Default WooCommerce
                    table layout.
                </td>
            </tr>
            <tr>
                <td>3.</td>
                <td><strong>Display Images</strong> Choose 'Yes' option to display product images otherwise choose 'No'.</td>
            </tr>
            <tr>
                <td>4.</td>
                <td><strong>Border</strong> You can add any border style, manage width or color. Or if you want to disable the border, choose border style option 'none'.</td>
            </tr>
        </table>
    </div>
<?php
return ob_get_clean();
