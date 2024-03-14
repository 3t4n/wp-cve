import { translate } from "../lang/init.js";

jQuery(document).ready(async function () {

    const search = await jQuery.post(ajaxurl, {
        action: 'mpg_search_settings_get_options',
        securityNonce: backendData.securityNonce
    });

    let searchData = JSON.parse(search);

    if (!searchData.success) {
        toastr.error(searchData.error, translate['Failed']);
    } else {

        const template = searchData?.data?.mpg_ss_result_template;
        jQuery('#mpg_search_settings_result_template').val(template?.replace(/\\\"/g, '"'))

        jQuery('#mpg_ss_intro_content').val(searchData?.data?.mpg_ss_intro_content?.replace(/\\\"/g, '"'));

        jQuery('#mpg_ss_results_container').val(searchData?.data?.mpg_ss_results_container);
        jQuery('#mpg_ss_excerpt_length').val(searchData?.data?.mpg_ss_excerpt_length);

        jQuery('#mpg_ss_results_count').val(searchData?.data?.mpg_ss_results_count);
        jQuery('#mpg_ss_is_case_sensitive').prop('checked', searchData?.data.mpg_ss_is_case_sensitive);
        jQuery('#mpg_ss_featured_image_url').val(searchData?.data?.mpg_ss_featured_image_url)
    }


    jQuery('#mpg_search_settings_form').on('submit', async function (e) {

        e.preventDefault();

        const searchSettingsResultTemplate = jQuery('#mpg_search_settings_result_template').val();
        const searchSettingsIntroContent = jQuery('#mpg_ss_intro_content').val();
        const searchSettingsResultsContainer = jQuery('#mpg_ss_results_container').val();
        const searchSettingsExcerptLength = jQuery('#mpg_ss_excerpt_length').val();
        const searchSettingsResultsCount = jQuery('#mpg_ss_results_count').val();
        const searchSettingsIsCaseSensitive = jQuery('#mpg_ss_is_case_sensitive').prop('checked');
        const searchSettingsFeaturedImageUrl = jQuery('#mpg_ss_featured_image_url').val();

        const search = await jQuery.post(ajaxurl, {
            action: 'mpg_search_settings_upset_options',
            'mpg_search_settings_result_template': searchSettingsResultTemplate,
            'mpg_ss_intro_content': searchSettingsIntroContent,
            'mpg_ss_results_container': searchSettingsResultsContainer,
            'mpg_ss_excerpt_length': searchSettingsExcerptLength,
            'mpg_ss_results_count': searchSettingsResultsCount,
            'mpg_ss_is_case_sensitive': searchSettingsIsCaseSensitive,
            'mpg_ss_featured_image_url': searchSettingsFeaturedImageUrl,
            'securityNonce': backendData.securityNonce
        });

        let searchData = JSON.parse(search);

        if (!searchData.success) {
            toastr.error(searchData.error, translate['Failed']);
        } else {
            toastr.success(translate['Success'], { timeOut: 5000 });
        }
    });
});
