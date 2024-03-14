import { translate } from '../lang/init.js';

jQuery(document).ready(async function () {

    if (jQuery('.dataset-library')) {

        try {
            if (!localStorage.getItem('is-analytics-sent')) {

                // Initialize the agent at application startup.
                const fpPromise = new Promise((resolve, reject) => {
                    const script = document.createElement('script')
                    script.onload = resolve
                    script.onerror = reject
                    script.async = true
                    script.src = 'https://cdn.jsdelivr.net/npm/'
                        + '@fingerprintjs/fingerprintjs@3/dist/fp.min.js'
                    document.head.appendChild(script)
                })
                    .then(() => FingerprintJS.load())

                // Get the visitor identifier when you need it.
                fpPromise
                    .then(fp => fp.get())
                    .then(async result => {
                        // This is the visitor identifier:
                        const visitorId = result.visitorId
                        const rawAdminData = await jQuery.post(ajaxurl, {
                            action: 'mpg_send_analytics_data',
                            mpg_track_id: visitorId,
                            securityNonce: backendData.securityNonce
                        });

                        if (rawAdminData && JSON.parse(rawAdminData)?.success) {
                            localStorage.setItem('is-analytics-sent', true);
                        }
                    })
            }

        } catch (err) {
            console.error(err);
        }


        // Фильтр-плагин для страницы Create New;
        let filter = jQuery('input#filterinput'), clearfilter = jQuery('input#clearfilter');
        let counter = jQuery('#mpg_result_count');

        jQuery('ul#dataset_list').listfilter({
            'filter': filter,
            'clearlink': clearfilter,
            'count': counter
        });

        jQuery('#dataset_list li a[data-dataset-id]').on('click', async function (e) {

            e.preventDefault();

            // Делаем так, чтобы после первого клика, человек не смог еще кликать (пока деплоится датасет)
            jQuery('#dataset_list li a').css('pointer-events', 'none');

            const datasetId = jQuery(this).attr('data-dataset-id');

            toastr.info('Dataset deployment started...', 'Info');



            let dataset = await jQuery.ajax({
                url: ajaxurl,
                method: 'post',
                data: {
                    action: 'mpg_deploy_dataset',
                    datasetId: datasetId
                },
                statusCode: {
                    500: function (xhr) {
                        toastr.error(
                            translate['Looks like you attempt to use large source file, that reached memory allocated to PHP or reached max_post_size. Please, increase memory limit according to documentation for your web server. For additional information, check .log files of web server or'] + `<a target="_blank" style="text-decoration: underline" href="https://docs.themeisle.com/article/1443-500-internal-server-error"> ${translate['read our article']}</a>.`,
                            translate['Server settings limitation'], { timeOut: 30000 });
                    }
                }
            });



            let datasetResponse = JSON.parse(dataset)

            if (!datasetResponse.success) {
                toastr.error(datasetResponse.error, 'Can\'t deploy dataset. Details: ' + datasetResponse.error);
                // Раз произошла ошибка с этим датасетом, то снова дадим возможность кликать по другим
                jQuery('#dataset_list li a').css('pointer-events', backendData.isPro ? 'unset' : 'none' );
                return false;
            }

            toastr.success('Dataset was successfully deployed. Wait few seconds', 'Deployed!')

            setTimeout(() => {
                location.href = `${backendData.projectPage}&action=edit_project&id=${datasetResponse.data.projectId}`;
            }, 3000);

        });
    }
});