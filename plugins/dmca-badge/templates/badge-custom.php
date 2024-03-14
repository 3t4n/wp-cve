<?php
/**
 * Custom Badge Template
 */

$settings        = dmca_get_option( 'dmca_badge_settings' );
$badge_settings  = isset( $settings->values['badge'] ) ? $settings->values['badge'] : array();
$badge_selection = isset( $badge_settings['badge_selection'] ) ? $badge_settings['badge_selection'] : 'regular';
$badge_selection = $badge_selection === 'custom' ? 'selected' : '';

?>

<table class="dmca-badge-wrap dmca-custom-badge <?php echo esc_attr( $badge_selection ); ?>">

    <tr class="dmca-color-boxes boxes-left">
        <td class="color-box color-box-for">
            <span><?php esc_html_e( 'Left Box', 'dmca-badge' ) ?></span>
        </td>
        <td class="color-box color-box-background">
            <span><?php esc_html_e( 'Background', 'dmca-badge' ) ?></span>
            <span class="cp"></span>
            <input type="hidden" field="left_background" name="dmca_badge_settings[badge][left_background]"
                   value="<?php echo isset( $badge_settings['left_background'] ) ? $badge_settings['left_background'] : '#8dc642'; ?>">
        </td>
        <td class="color-box color-box-text">
            <span><?php esc_html_e( 'Text', 'dmca-badge' ) ?></span>
            <span class="cp"></span>
            <input type="hidden" field="left_text" name="dmca_badge_settings[badge][left_text]"
                   value="<?php echo isset( $badge_settings['left_text'] ) ? $badge_settings['left_text'] : '#fff'; ?>">
        </td>
        <td class="color-box color-box-border">
            <span><?php esc_html_e( 'Border', 'dmca-badge' ) ?></span>
            <span class="cp"></span>
            <input type="hidden" field="left_border" name="dmca_badge_settings[badge][left_border]"
                   value="<?php echo isset( $badge_settings['left_border'] ) ? $badge_settings['left_border'] : '#fff'; ?>">
        </td>
        <td rowspan="2" class="dmca-custom-badge-preview">
            <h3>Badge Preview</h3>
            <p>A preview of your custom badge.</p>
            <div class="badge-buttons">
                <script></script>
                <script type="text/javascript">(function () {
                        var c = document.createElement('link');
                        c.type = 'text/css';
                        c.rel = 'stylesheet';
                        c.href = 'https://images.dmca.com/badges/dmca.css?ID=735ae8ee-d035-4afd-9bcc-eb9ebaef1cb8';
                        var h = document.getElementsByTagName("head")[0];
                        h.appendChild(c);
                    })();</script>
                <div id="DMCA-badge" style="display: flex;justify-content: center;align-items: center;width: 100% !important;">
                    <div class="dm-1 dm-1-b" style="left: 0; position: relative !important;"><a style="line-height: 21px; display: inherit;" href="https://www.dmca.com/" class="dmca-badge" title="DMCA">DMCA</a></div>
                    <div class="dm-2 dm-2-b" style="position: relative !important; left:0px !important; display: flex;"><a style="line-height: 21px; display: inherit;" href="<?php echo esc_url( dmca_badge_get_status_url() ); ?>" title="PROTECTED">PROTECTED</a></div>
                </div>
            </div>
        </td>
    </tr>

    <tr class="dmca-color-boxes boxes-right">
        <td class="color-box color-box-for">
            <span><?php esc_html_e( 'Right Box', 'dmca-badge' ) ?></span>
        </td>
        <td class="color-box color-box-background">
            <span><?php esc_html_e( 'Background', 'dmca-badge' ) ?></span>
            <span class="cp"></span>
            <input type="hidden" field="right_background" name="dmca_badge_settings[badge][right_background]"
                   value="<?php echo isset( $badge_settings['right_background'] ) ? $badge_settings['right_background'] : '#221e1f'; ?>">
        </td>
        <td class="color-box color-box-text">
            <span><?php esc_html_e( 'Text', 'dmca-badge' ) ?></span>
            <span class="cp"></span>
            <input type="hidden" field="right_text" name="dmca_badge_settings[badge][right_text]"
                   value="<?php echo isset( $badge_settings['right_text'] ) ? $badge_settings['right_text'] : '#fff'; ?>">
        </td>
        <td class="color-box color-box-border">
            <span><?php esc_html_e( 'Border', 'dmca-badge' ) ?></span>
            <span class="cp"></span>
            <input type="hidden" field="right_border" name="dmca_badge_settings[badge][right_border]"
                   value="<?php echo isset( $badge_settings['right_border'] ) ? $badge_settings['right_border'] : '#fff'; ?>">
        </td>
    </tr>

    <tr>
        <td colspan="6">
            <h2>Copy the code from here:</h2>
            <textarea name="dmca_badge_settings[badge][custom_badge]" class="copy-code-section" readonly id="" cols="30" rows="5"></textarea>
        </td>
    </tr>
</table>

<script>
    ;(function ($, window, document) {

        let customBadgePanel = $('.dmca-custom-badge'),
            copySectionArea = customBadgePanel.find('textarea.copy-code-section'),
            colorPickers = customBadgePanel.find('tr > td > .cp'),
            previewPanel = customBadgePanel.find('.dmca-custom-badge-preview .badge-buttons'),
            buttonDMCA = previewPanel.find('.dm-1'),
            buttonProtected = previewPanel.find('.dm-2'),
            staticScriptSrc = 'https://images.dmca.com/Badges/DMCABadgeHelper.min.js';


        colorPickers.loads({
            layout: 'rgbhex',
            flat: false,
            enableAlpha: false,
            compactLayout: true,
            onLoaded: function (ev) {
                let element = $(ev.el),
                    inputField = element.parent().find('input'),
                    previewPanelHTML = $.trim(previewPanel.html());

                element.setColor(inputField.val());
                copySectionArea.val(previewPanelHTML.replace('script>', 'script src="' + staticScriptSrc + '">'));
            },
            onChange: function (ev) {
                let previewPanelHTML = $.trim(previewPanel.html());

                populateColors($(ev.el), ev.hex);
                copySectionArea.val(previewPanelHTML.replace('script>', 'script src="' + staticScriptSrc + '">'));
            },
            onHide: function (ev) {
                $(ev.el).parent().find('input').val(RGBToHex($(ev.el).parent().find('.cp').css('background-color')));
            },
        });

        function populateColors(element, colorCode) {
            let inputField = element.parent().find('input');

            element.css('background-color', '#' + colorCode);

            switch (inputField.attr('field')) {
                case 'left_background':
                    buttonDMCA.css('background-color', '#' + colorCode);
                    break;

                case 'left_text':
                    buttonDMCA.find('a').css('color', '#' + colorCode);
                    break;

                case 'left_border':
                    buttonDMCA.css('border-color', '#' + colorCode);
                    break;

                case 'right_background':
                    buttonProtected.css('background-color', '#' + colorCode);
                    break;

                case 'right_text':
                    buttonProtected.find('a').css('color', '#' + colorCode);
                    break;

                case 'right_border':
                    buttonProtected.css('border-color', '#' + colorCode);
                    break;
            }
        }

        function RGBToHex(rgb) {
            // Choose correct separator
            let sep = rgb.indexOf(",") > -1 ? "," : " ";
            // Turn "rgb(r,g,b)" into [r,g,b]
            rgb = rgb.substr(4).split(")")[0].split(sep);

            let r = (+rgb[0]).toString(16),
                g = (+rgb[1]).toString(16),
                b = (+rgb[2]).toString(16);

            if (r.length == 1)
                r = "0" + r;
            if (g.length == 1)
                g = "0" + g;
            if (b.length == 1)
                b = "0" + b;

            return "#" + r + g + b;
        }

    })(jQuery, window, document);
</script>