<?php
global $eod_api;
$form_class = '.eod_shortcode_form.for_converter';
?>

<form class="<?= str_replace('.', ' ', $form_class) ?>">
    <div class="field">
        <label for="esi_t1" class="h">First currency <span class="require" title="required shortcode element">*</span></label>
        <div>The main currency to be converted. Will be on the left.</div>
        <div class="currency eod_search_box first_currency">
            <input id="esi_t1" type="text" placeholder="search">
        </div>
    </div>

    <div class="field">
        <div class="h">Amount of first currency</div>
        <label>
            <input type="number" name="eod_amount" value="1" min="1">
        </label>
    </div>

    <div class="field">
        <label for="esi_t2" class="h">Second currency <span class="require" title="required shortcode element">*</span></label>
        <div>The second currency, the amount of which will need to be calculated.</div>
        <div class="currency eod_search_box second_currency">
            <input id="esi_t2" type="text" placeholder="search">
        </div>
    </div>


    <div class="field">
        <div class="h">Ability for users to change currency</div>
        <button class="eod_toggle">
            <input type="checkbox" value="off" name="possibility_to_change">
            <span>No</span>
            <input type="checkbox" value="on" checked="checked" name="possibility_to_change">
            <span>Yes</span>
        </button>
    </div>
    <div class="field whitelist_field">
        <div>You can limit the list of currencies available for changing</div>
        <div class="currency eod_search_box whitelist">
            <input type="text" placeholder="search">
        </div>
    </div>

    <div class="field">
        <div class="h">Your shortcode:</div>
        <div class="eod_shortcode">
            <div class="eod_shortcode_result">-</div>
            <svg class="copy_btn" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><path d="M448 384H256c-35.3 0-64-28.7-64-64V64c0-35.3 28.7-64 64-64H396.1c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9V320c0 35.3-28.7 64-64 64zM64 128h96v48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H256c8.8 0 16-7.2 16-16V416h48v32c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V192c0-35.3 28.7-64 64-64z"/></svg>
            <div class="copied">Copied</div>
        </div>
    </div>
</form>



<script>
function eod_create_converter_shortcode(){
    let $shortcode = jQuery('<?= $form_class ?> .eod_shortcode_result'),
        $f_currency = jQuery('<?= $form_class ?> .first_currency .selected li').eq(0),
        $s_currency = jQuery('<?= $form_class ?> .second_currency .selected li').eq(0),
        $whitelist = jQuery('<?= $form_class ?> .whitelist .selected li'),
        amount = parseFloat( jQuery('<?= $form_class ?> input[name=eod_amount]').val() ),
        possibility_to_change = jQuery('<?= $form_class ?> input[name=possibility_to_change]:checked').val() === 'on',
        whitelist = [];

    jQuery('<?= $form_class ?> .whitelist_field').toggle( possibility_to_change );

    if(!$f_currency.length || !$s_currency.length) {
        $shortcode.html('-');
        return;
    }

    let targets = [
        $f_currency.data('data').code + '.' + $f_currency.data('data').type.toUpperCase(),
        $s_currency.data('data').code + '.' + $s_currency.data('data').type.toUpperCase()
    ];

    if(possibility_to_change)
        $whitelist.each(function(){
            let data = jQuery(this).data('data');
            whitelist.push( data.code + '.' + data.type.toUpperCase() )
        });

    $shortcode.html(
        '[eod_converter target="'+targets.join(':')+'"'
        + ( amount && amount > 0 && amount !== 1 ? (' amount="'+amount+'"') : '' )
        + ( whitelist.length > 0 ? (' whitelist="'+whitelist.join(', ')+'"') : '')
        + ( possibility_to_change ? '' : (' changeable="0"') )
        + ']'
    );
}

/**
 * Converter currencies cannot be the same. In such a situation, we remove another selected currency.
 * @param _this - EodSelector object
 * @param obj   - contain display name and data of option
 */
function filter_select_converter_target( _this, obj ){
    let other_class = _this.$box.hasClass('first_currency') ? 'second' : 'first',
        $other_box = jQuery('<?= $form_class ?> .'+other_class+'_currency');

    if($other_box.length) {
        let other_EodSelector = $other_box.data('EodSelector'),
            $other_s_name = other_EodSelector.$selected.find('li .name');
        // Compare
        if( $other_s_name.html() === obj.name )
            other_EodSelector.resetSelector();
    }

    return obj;
}


/**
 * Init selectors
 **/
jQuery('<?= $form_class ?> .eod_search_box ').each(function(){
    let args = {
        $box:                   jQuery(this),
        multiple_select:        jQuery(this).hasClass('whitelist'),
        hook_change:            eod_create_converter_shortcode,
        search_method:          'currency',
    }
    // Add filter
    if(jQuery(this).hasClass('second_currency') || jQuery(this).hasClass('first_currency'))
        args.filter_select_option = filter_select_converter_target;

    new EodSelector(args);
});

/**
 * Change triggers
 **/
jQuery(document).on('click', '<?= $form_class ?> .eod_toggle', eod_create_converter_shortcode);
jQuery(document).on('change', '<?= $form_class ?> input[type=number]', eod_create_converter_shortcode);
</script>