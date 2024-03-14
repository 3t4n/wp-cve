<?php
defined( 'ABSPATH' ) || exit;

ob_start();
?>
    <div style="display:none;" class="xlwcty_tb_content" id="xlwcty_component_settings<?php echo $config['slug']; ?>_help">
        <h3><?php echo $config['title'] . ' ' . __( 'Component Design & Settings', 'woo-thank-you-page-nextmove-lite' ); ?></h3>
        <p class="xlwcty_center"><img src="//storage.googleapis.com/xl-nextmove/additional-info.jpg"/></p>
        <table align="center" width="650" class="xlwcty_modal_table">
            <tr>
                <td>1.</td>
                <td><strong>Content:</strong> WooCommerce or other plugins sometimes add additional information to native Thank You pages. This component will show those additional information blocks.
                    For example WooCommerce displays additional information for payment gateways such as BACS, Cheque or Cash On Delivery.
                </td>
            </tr>
            <tr>
                <td width="50">2.</td>
                <td><strong>Border:</strong> You can add any border style, manage width or color. Or if you want to disable the border, choose border style option 'none'.</td>
            </tr>


        </table>
    </div>
<?php
return ob_get_clean();
