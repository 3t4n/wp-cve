<?php defined( 'ABSPATH' ) || exit; ?>
<div class="wrap">
    <h1>Shortcode Button</h1>
    <hr>
    <p>If you want to add a widget somewhere else in your site (specific pages) you can generate your own code.</p>
    <table class="form-table" class="tochatbe-setting-table">
        <tbody>
            <tr>
                <th scope="row">
                    <label for="">Button Background Color</label>
                </th>
                <td>
                    <input type="text" id="tochatbe-button-bg-color" class="tochatbe-color-picker" value="#075367">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="">Button Text Color</label>
                </th>
                <td>
                    <input type="text" id="tochatbe-button-text-color" class="tochatbe-color-picker" value="#ffffff">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="">Button Text</label>
                </th>
                <td>
                    <input type="text" id="tochatbe-button-text" class="regular-text" value="How can we help?">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="">Agent Number</label>
                </th>
                <td>
                    <input type="number" id="tochatbe-agent-number" class="regular-text" step="1" value="1234567890">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="">Pre Defined Message</label>
                </th>
                <td>
                    <textarea id="tochatbe-pre-defined-message" class="regular-text" style="height:120px;"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2" id="tochatbe-shortcode"></td>
            </tr>
        </tbody>
    </table>
    <a href="javascript" id="tochatbe-generate-shortcode" class="button button-primary">Generate Button</a>

</div>

<?php require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/notifications/html-purchase-premium.php'; ?>