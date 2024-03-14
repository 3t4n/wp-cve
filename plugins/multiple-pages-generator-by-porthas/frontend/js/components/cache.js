import { mpgGetState } from '../helper.js';
import { translate } from '../../lang/init.js';

jQuery('#cache').on('click', '.card .enable-cache', async function () {

    const cacheType = jQuery(this).parent().attr('data-cache-type');

    if (!['disk', 'database'].includes(cacheType)) {
        console.error('Passed unsupported type of cache');
    }

    let rawCacheEnablingStatus = await jQuery.post(ajaxurl, {
        action: 'mpg_enable_cache',
        projectId: mpgGetState('projectId'),
        type: cacheType,
        securityNonce: backendData.securityNonce
    });

    let cacheEnablingStatus = JSON.parse(rawCacheEnablingStatus)

    if (!cacheEnablingStatus.success) {
        toastr.error(cacheEnablingStatus.error, translate['Failed']);
    }

    toastr.success(cacheEnablingStatus.data, translate['Success'])

    jQuery('.cache-page .buttons .btn')
        .attr('disabled', 'disabled');

    jQuery(`.cache-page .buttons[data-cache-type=${cacheType}] .enable-cache`)
        .removeAttr('disabled')
        .removeClass('btn-success enable-cache')
        .addClass('btn-warning disable-cache')
        .text('Disable');

    jQuery(`.cache-page .buttons[data-cache-type=${cacheType}] .flush-cache`)
        .removeAttr('disabled')
        .removeClass('btn-light')
        .addClass('btn-danger');

    await getActualCacheStat();

});

jQuery('#cache').on('click', '.card .disable-cache', async function () {

    const cacheType = jQuery(this).parent().attr('data-cache-type');

    if (!['disk', 'database'].includes(cacheType)) {
        console.error('Passed unsupported type of cache');
    }

    let rawCahceDisablingStatus = await jQuery.post(ajaxurl, {
        action: 'mpg_disable_cache',
        projectId: mpgGetState('projectId'),
        type: cacheType,
        securityNonce: backendData.securityNonce
    });

    let cahceDisablingStatus = JSON.parse(rawCahceDisablingStatus)

    if (!cahceDisablingStatus.success) {
        toastr.error(cahceDisablingStatus.error, translate['Failed']);
    }

    toastr.success(cahceDisablingStatus.data, translate['Success!'])

    jQuery('.cache-page .buttons .btn')
        .removeAttr('disabled');

    jQuery(`.cache-page .buttons .disable-cache`)
        .addClass('btn-success enable-cache')
        .removeClass('btn-warning disable-cache')
        .text('Enable');

    jQuery(`.cache-page .buttons .flush-cache`)
        .attr('disabled', 'disabled')
        .addClass('btn-light')
        .removeClass('btn-danger');

    await getActualCacheStat();
});


jQuery(`.cache-page .buttons .flush-cache`).on('click', async function () {

    let decision = confirm(translate['Are you sure, that you want to flush cache? This action can not be undone.']);

    if (decision) {
        const cacheType = jQuery(this).parent().attr('data-cache-type');

        let rawCacheFlushStatus = await jQuery.post(ajaxurl, {
            action: 'mpg_flush_cache',
            projectId: mpgGetState('projectId'),
            type: cacheType,
            securityNonce: backendData.securityNonce
        });

        let cacheFlushStatus = JSON.parse(rawCacheFlushStatus)

        if (!cacheFlushStatus.success) {
            toastr.error(cacheFlushStatus.error, translate['Failed']);
        }

        toastr.success(cacheFlushStatus.data, translate['Success']);

        await getActualCacheStat();
    }
});

jQuery('#cache-tab').on('click', getActualCacheStat);

async function getActualCacheStat() {

    if (jQuery('.cache-page .buttons .btn.disable-cache').length) {

        const cacheType = jQuery('.disable-cache').parent().attr('data-cache-type');

        console.log('cacheType', cacheType);

        if (!['disk', 'database'].includes(cacheType)) {
            console.error('Passed unsupported type of cache');
        }

        let rawCacheStats = await jQuery.post(ajaxurl, {
            action: 'mpg_cache_statistic',
            projectId: mpgGetState('projectId'),
            type: cacheType,
            securityNonce: backendData.securityNonce
        });

        let cacheStats = JSON.parse(rawCacheStats)

        if (!cacheStats.success) {
            toastr.error(cacheStats.error, translate['Failed']);
        } else {

            jQuery('.cache-page .pages-in-cache, .cache-page .cache-size').text('N/A');

            jQuery(`.cache-page .${cacheType} .pages-in-cache`).text(cacheStats.data.pagesCount);
            jQuery(`.cache-page .${cacheType} .cache-size`).text(cacheStats.data.pagesSize);
        }

    } else {
        // Если все типы кеша выключены
        jQuery('.cache-page .pages-in-cache, .cache-page .cache-size').text('N/A');

    }
}