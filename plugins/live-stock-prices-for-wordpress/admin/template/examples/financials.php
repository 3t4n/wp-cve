<?php
$fd_presets = get_posts([
    'post_type' => 'financials',
    'post_status' => 'publish',
    'numberposts' => -1
]);
$form_class = '.eod_shortcode_form.for_financials';
?>

<form class="<?= str_replace('.', ' ', $form_class) ?>">
    <div class="field">
        <label for="esi_fin" class="h">Ticker code/name <span class="require" title="required shortcode element">*</span></label>
        <div class="eod_search_box">
            <input id="esi_fin" class="eod_search_input" type="text" autocomplete="off" placeholder="Find ticker by code or company name"/>
        </div>
    </div>

    <div class="field">
        <label for="fd_preset" class="h">Data Preset <span class="require" title="required shortcode element">*</span></label>
        <p>The preset defines the list of data that will be displayed. You can create it on the page <a href="<?= get_admin_url() ?>edit.php?post_type=financials">Financials presets</a>.</p>
        <select id="fd_preset">
            <option value="">Select preset</option>
            <?php foreach ($fd_presets as $preset){ ?>
                <?php $preset_type = str_replace('->', ' - ', get_post_meta( $preset->ID,'_financial_group', true ) ) ?>
                <option value="<?= $preset->ID ?>"><?= $preset->post_title ?> (<?= $preset_type ?>)</option>
            <?php } ?>
        </select>
    </div>


    <div class="field">
        <label for="fd_preset" class="h">Years interval</span></label>
        <div class="flex">
            <input type="number" name="year_from" min="0" placeholder="from">
            <span> - </span>
            <input type="number" name="year_to" min="0" placeholder="to">
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
function eod_create_financial_shortcode(){
    let $shortcode = jQuery('<?= $form_class ?> .eod_shortcode_result'),
        EodSelector = jQuery('<?= $form_class ?> .eod_search_box').data('EodSelector'),
        ticker = EodSelector.getSelectedItem(),
        preset_id = jQuery('<?= $form_class ?> #fd_preset option:checked').val(),
        year_from = jQuery('<?= $form_class ?> input[name=year_from]').val(),
        year_to = jQuery('<?= $form_class ?> input[name=year_to]').val(),
        label = jQuery('<?= $form_class ?> #fd_preset option:checked').text();

    if(!ticker || !preset_id){
        $shortcode.html('-');
        jQuery('.tab.active .eod_error').remove();
        return false;
    }

    let target = ticker.data.code + '.' + ticker.data.exchange;

    // Year interval
    let years = '';
    if(year_from > 0 || year_to > 0)
        years = [year_from > 0 ? year_from : '', year_to > 0 ? year_to : '']

    $shortcode.html(
        '[eod_financials '
        + 'target="' + target + '" '
        + 'id="' + preset_id + '" '
        + 'preset="' + label + '" '
        + (years ? ('years="' + years.join('-') + '"') : '')
        + ']'
    );
}


/**
 * Trigger events of changing
 */
jQuery(document).on('change', '<?= $form_class ?> select, <?= $form_class ?> input[type=number]', eod_create_financial_shortcode);

jQuery(function(){
    new EodSelector({
        $box:               jQuery('<?= $form_class ?> .eod_search_box '),
        hook_change:        eod_create_financial_shortcode,
        search_method:      'api',
    });
});
</script>