<?php
defined( 'ABSPATH' ) || exit;

ob_start();
?>
    <div style="display:none;" class="xlwcty_tb_content" id="xlwcty_component_settings<?php echo $config['slug']; ?>_help">
        <h3><?php echo $config['title'] . ' ' . __( 'Component Design & Settings', 'woo-thank-you-page-nextmove-lite' ); ?></h3>
        <p class="xlwcty_center"><img src="<?php echo plugin_dir_url( XLWCTY_PLUGIN_FILE ) . 'components/map/help.jpg'; ?>"/></p>
        <table align="center" width="650" class="xlwcty_modal_table">
            <tr>
                <td width="50">1.</td>
                <td><strong>Map:</strong> You can select map style (choose from 8 styles), zoom level, marker address and marker icon.</td>
            </tr>
            <tr>
                <td>2.</td>
                <td><strong>Marker Text:</strong> Enter text you want to display inside your marker window.</td>
            </tr>
            <tr>
                <td>3.</td>
                <td><strong>Heading:</strong> Enter any heading. Customize font size and text alignment too.</td>
            </tr>
            <tr>
                <td>4.</td>
                <td><strong>Description:</strong> Enter any text here. Alignment option available here.</td>
            </tr>
            <tr>
                <td>5.</td>
                <td><strong>Border:</strong> You can add any border style, manage width or color. Or if you want to disable the border, choose border style option 'none'.</td>
            </tr>
        </table>
    </div>
<?php
return ob_get_clean();
