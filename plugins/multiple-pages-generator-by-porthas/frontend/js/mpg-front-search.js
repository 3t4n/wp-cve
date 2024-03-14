
(async function () {

    const [rawSearchSettings, rawSerchResults] = await Promise.all([
        jQuery.post(backendData.ajaxurl, { action: 'mpg_search_settings_get_options', securityNonce: backendData.securityNonce }),
        jQuery.post(backendData.ajaxurl, {
            action: 'mpg_get_search_results',
            s: (new URL(location.href)).searchParams.get('s'),
            securityNonce: backendData.securityNonce
        })
    ]);

    let searchResults = JSON.parse(rawSerchResults);

    let searchSettings = JSON.parse(rawSearchSettings);

    if (searchResults.success && searchSettings.success) {

        const {
            mpg_ss_results_container,
            mpg_search_no_results_container,
            mpg_ss_intro_content,
            mpg_ss_result_template
        } = searchSettings.data || {};



        let pageContentSelector = jQuery(mpg_ss_results_container);
        const trimImages = true;

        console.log('pageContentSelector', pageContentSelector);
        const noResultsSelector = mpg_search_no_results_container;

        // Показываем intro текст
        if (searchResults?.data?.total && mpg_ss_intro_content) {
            pageContentSelector.append(mpg_ss_intro_content);
        }

        // console.log( JSON.parse(mpg_ss_result_template)
// console.log(mpg_ss_result_template.replace(/\\\"/g, '"'));
        searchResults.data.results.forEach(result => {

            dom = mpg_ss_result_template
                .replace(/\\\"/g, '"')
                .replace(/{{mpg_page_title}}/g, result.page_title)
                .replace(/{{mpg_page_excerpt}}/g, result.page_excerpt)
                .replace(/{{mpg_page_author_nickname}}/g, result.page_author_nickname)

                .replace(/{{mpg_page_author_email}}/g, result.page_author_email)
                .replace(/{{mpg_page_author_url}}/g, result.page_author_url)


                .replace(/{{mpg_page_url}}/g, result.page_url)
                .replace(/{{mpg_page_date}}/g, result.page_date)
                .replace(/{{mpg_featured_image_url}}/g, result.page_featured_image)

            pageContentSelector.append(dom);

        })
    }



})();