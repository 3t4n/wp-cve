<?php
$fd_presets = get_posts([
    'post_type' => 'fundamental-data',
    'post_status' => 'publish',
    'numberposts' => -1
]);
$form_class = '.eod_shortcode_form.for_fundamental';
?>

<form class="<?= str_replace('.', ' ', $form_class) ?>">
    <div class="field">
        <label for="fd_preset" class="h">Data Preset <span class="require" title="required shortcode element">*</span></label>
        <p>The preset defines the list of data that will be displayed. You can create it on the page <a href="<?= get_admin_url() ?>edit.php?post_type=fundamental-data">Fundamental Data presets</a>.</p>
        <select id="fd_preset">
            <option disabled selected value="">Select preset</option>
            <?php foreach ($fd_presets as $preset){ ?>
                <?php $preset_type = str_replace('_', ' ', get_post_meta( $preset->ID,'_fd_type', true ) ) ?>
                <option value="<?= $preset->ID ?>" data-type="<?= $preset_type ?>">
                    <?= $preset->post_title ?> (<?= $preset_type ?>)
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="field disabled">
        <label for="esi_fd" class="h">Ticker code/name <span class="require" title="required shortcode element">*</span></label>
        <?php // TODO remove a warning msg after adding type check ?>
        <p>Warning: ticker type should be equal to the Fundamental preset type (common stock, index, fund or etf).</p>
        <div class="eod_search_box">
            <input disabled id="esi_fd" class="eod_search_input" type="text" autocomplete="off" placeholder="Find ticker by code or company name"/>
        </div>
    </div>

    <div class="field">
        <div class="h">Your shortcode:</div>
        <div class="eod_shortcode">
            <div class="eod_shortcode_result">-</div>
            <div class="copied">Copied</div>
        </div>
    </div>
</form>



<script>
    function eod_create_fundamental_shortcode(){
        let $shortcode = jQuery('<?= $form_class ?> .eod_shortcode_result'),
            EodSelector = jQuery('<?= $form_class ?> .eod_search_box').data('EodSelector'),
            ticker = EodSelector.getSelectedItem(),
            preset_id = jQuery('<?= $form_class ?> #fd_preset option:checked').val(),
            label = jQuery('<?= $form_class ?> #fd_preset option:checked').text();

        if(!ticker || !preset_id){
            $shortcode.html('-');
            jQuery('.tab.active .eod_error').remove();
            return false;
        }

        let target = ticker.data.code + '.' + ticker.data.exchange;

        $shortcode.html(
            '[eod_fundamental '
                + 'target="' + target + '" '
                + 'id="' + preset_id + '" '
                + 'preset="' + label + '"'
            + ']'
        );
    }

    jQuery(document).on('change', '<?= $form_class ?> select', function(){
        let stock_type = jQuery('<?= $form_class ?> #fd_preset option:checked').attr('data-type'),
            EodSelector = jQuery('<?= $form_class ?> .eod_search_box').data('EodSelector'),
            selected_item = EodSelector.getSelectedItem();

        // Write data for filter items by type
        EodSelector.$input.data('stock-type', stock_type ? stock_type : '');

        // Lock/unlock search input
        EodSelector.$input.prop("disabled", !stock_type);
        EodSelector.$input.closest('.field').toggleClass("disabled", !stock_type);

        // Clean search input with incompatible item
        if(!selected_item || (selected_item && selected_item.data.type.toLowerCase() !== stock_type))
            EodSelector.resetSelector();

        eod_create_fundamental_shortcode();
    });

    jQuery(function(){
        new EodSelector({
            $box:               jQuery('<?= $form_class ?> .eod_search_box '),
            hook_change:        eod_create_fundamental_shortcode,
            search_method:      'api',
        });
    });
</script>