<?php

// Prevent public access to this script

use Wpo\Core\WordPress_Helpers;

defined('ABSPATH') or die();

?>

<div class="notice notice-<?php echo esc_html($notice_type) ?>" style="margin-left: 2px;">
    <table style="border: 0; border-collapse: collapse; width: 100%; max-width: 1024px;">
        <tbody>
            <tr>
                <td colspan="2">
                    <h3 style="margin: 15px 0px"><?php echo wp_kses($title, WordPress_Helpers::get_allowed_html()) ?></h3>
                </td>
            </tr>
            <tr>
                <td>
                    <p>
                        <?php echo wp_kses($message, WordPress_Helpers::get_allowed_html()) ?>
                    </p>
                </td>
                <td>
                    <div style="padding: 0px 15px; display: <?php echo (empty($hide_image) ? 'initial' : 'none') ?>">
                        <a href="https://www.wpo365.com/" target="_blank">
                            <img style="width: 100%; height: auto; max-width: 80px; min-width: 48px; border: 0px;" src="https://www.wpo365.com/wp-content/uploads/2021/07/icon-128x128-1-128x128.png?notification">
                        </a>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p style="margin-bottom: 15px;">
                        <?php echo wp_kses($footer, WordPress_Helpers::get_allowed_html()) ?>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</div>