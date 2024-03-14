import { translate } from '../../lang/init.js';
import { mpgGetState, mpgUpdateState } from '../helper.js';

jQuery('#sitemap-tab').on('click', function () {

    let filename = jQuery('input[name="sitemap_filename_input"]')
    let maxUrlPerFile = jQuery('input[name="sitemap_max_urls_input"]');
    let frequency = jQuery('select[name="sitemap_frequency_input"]');
    let addToRobotsTxt = jQuery('input[name="sitemap_robot"]');
    let siteMapPriority = jQuery('input[name="sitemap_priority"]');

    siteMapPriority.val( mpgGetState('sitemapPriority') || 1 );

    filename.val(mpgGetState('sitemapFilename'));
    maxUrlPerFile.val(mpgGetState('sitemapMaxUrlPerFile') || 50000);

    let checkboxValue = mpgGetState('sitemapAddToRobotsTxt');

    frequency.find(`option[value="${mpgGetState('sitemapFrequency')}"]`).prop('selected', true)

    addToRobotsTxt.prop('checked', parseInt(checkboxValue));

    if (mpgGetState('sitemapUrl')) {
        jQuery('#mpg_sitemap_url').html(`<a target="_blank" href="${mpgGetState('sitemapUrl')}">${filename.val()}</a>`);
    } else {
        jQuery('#mpg_sitemap_url').html(translate['Not created yet']);
    }
});

async function mpgGenerateSitempa(filename, maxUrlPerFile, frequency, addToRobotsTxt, priority) {
    const sitemap = await jQuery.post(ajaxurl, {
        action: 'mpg_generate_sitemap',
        projectId: mpgGetState('projectId'),
        filename,
        maxUrlPerFile,
        frequency,
        addToRobotsTxt,
        previousSitemapName: mpgGetState('sitemapFilename'),
        priority: priority,
        securityNonce: backendData.securityNonce
    });

    let sitemapData = JSON.parse(sitemap);

    if (!sitemapData.success) {
        toastr.error(sitemapData.error, translate['Failed']);
    } else {
        // Безем название карты сайта из ссылки, которая пришла из сервака. Там может быть просто file.xml, а может быть file-index.xml
        // В случае, если в дотасете данных больше чем установлен лимит.
        mpgUpdateState('sitemapFilename', sitemapData.data.split('/').pop().replace('.xml', ''));

        jQuery('#mpg_sitemap_url').html(`<a target="_blank" href="${sitemapData.data}">${sitemapData.data}</a>`);

        toastr.success(sitemapData.data, translate['Success'], { timeOut: 5000 });
    }
}

jQuery('#sitemap-form').on('submit', async function (event) {

    event.preventDefault();

    let filename = jQuery('input[name="sitemap_filename_input"]').val();
    let maxUrlPerFile = jQuery('input[name="sitemap_max_urls_input"]').val();
    let frequency = jQuery('select[name="sitemap_frequency_input"] option:checked').val();
    let addToRobotsTxt = jQuery('input[name="sitemap_robot"]').is(':checked')
    let priority = jQuery('input[name="sitemap_priority"]').val();

    const isSitemapNameUniq = await jQuery.post(ajaxurl, {
        action: 'mpg_check_is_sitemap_name_is_uniq',
        filename,
        securityNonce: backendData.securityNonce
    });

    let iSNiU = JSON.parse(isSitemapNameUniq)

    if (!iSNiU.success) {
        toastr.error(iSNiU.error, translate['Failed']);
    }

    if (iSNiU.unique) {

        await mpgGenerateSitempa(filename, maxUrlPerFile, frequency, addToRobotsTxt, priority)

    } else {
        if (confirm(`"${filename}" ${translate['is already in use. Click "Ok" to override the sitemap, or "Cancel" to change name']}`)) {
            await mpgGenerateSitempa(filename, maxUrlPerFile, frequency, addToRobotsTxt, priority)

        }
    }
});
