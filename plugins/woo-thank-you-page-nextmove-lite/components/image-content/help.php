<?php
defined( 'ABSPATH' ) || exit;

ob_start();
?>
    <div style="display:none;" class="xlwcty_tb_content" id="xlwcty_component_settings<?php echo $config['slug']; ?>_help">
        <h3><?php echo $config['title'] . ' ' . __( 'Component Design & Settings', 'woo-thank-you-page-nextmove-lite' ); ?></h3>
        <p class="xlwcty_center"><img src="<?php echo plugin_dir_url( XLWCTY_PLUGIN_FILE ) . 'components/image-content/help.jpg'; ?>"/></p>
        <table align="center" width="650" class="xlwcty_modal_table">
            <tr>
                <td width="50">1.</td>
                <td><strong>Heading:</strong> Enter any heading. Customize font size and text alignment too.</td>
            </tr>
            <tr>
                <td>2.</td>
                <td><strong>Description:</strong> Enter any text here. Alignment option available here.</td>
            </tr>
            <tr>
                <td>3.</td>
                <td><strong>Layout:</strong> Plugin has 4 layouts `Single Image`, `Two Images`, `Left Image Text` & `Text Right Image`.<br/>Single Image - This has a single image upload and link
                    option.<br/>Two Images - Two Images with link in 50/50 ratio.<br/>Left Image Text - Choose Image Content ratio with other options to add image, link and text.<br/>Text Right Image
                    - Choose Image Content ratio with other options to add image, link and text.
                </td>
            </tr>
            <tr>
                <td>4.</td>
                <td><strong>Button:</strong> If you wish to display button, choose 'Yes' option. Further has link option with other CSS options.</td>
            </tr>
            <tr>
                <td>5.</td>
                <td><strong>Border:</strong> You can add any border style, manage width or color. Or if you want to disable the border, choose border style option 'none'.</td>
            </tr>
        </table>
    </div>
<?php
return ob_get_clean();
