<?php

namespace WPDeskFIVendor;

/**
 * File: parts/header.php
 */
?>
<div id="header">
    <table class="borders">
        <tbody>
        <tr>
            <td style="width:50%; text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
?>;">
                <?php 
if (!empty($owner->get_logo())) {
    ?>
                    <img alt="" src="<?php 
    echo \esc_url($owner->get_logo());
    ?>"/>
                <?php 
}
?>
            </td>

            <td style="width:50%; text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;">
                <?php 
require __DIR__ . '/dates.php';
?>
            </td>
        </tr>
        </tbody>
    </table>

    <table>
        <tr>
            <td style="width:50%; text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
?>;">
                <?php 
require \dirname(__DIR__, 1) . '/header-parts/seller.php';
?>
            </td>
            <td style="width:25%; text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
?>;">
                <?php 
require \dirname(__DIR__, 1) . '/header-parts/buyer.php';
?>
            </td>
            <td style="width:25%; text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;">
                <?php 
require \dirname(__DIR__, 1) . '/header-parts/recipient.php';
?>
            </td>
        </tr>
    </table>
</div>
<h1 style="text-align: center;"><?php 
echo \esc_html($correction->get_formatted_number());
?></h1>
<?php 
