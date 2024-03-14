
import { shortCodeTabInit } from './shortcode.js';

import {
    mpgGetState,
    mpgUpdateState,
    getProjectIdFromUrl,
    urlStructureToDom,
    convertTimestampToDateTime,
    setHeaders,
    fillUrlStructureShortcodes
} from '../helper.js';

import { translate } from '../../lang/init.js';

import {
    fillCustomTypeDropdown,
    fillDataPreviewAndUrlGeneration,
} from '../models/page-builder-model.js';


(async function () {

    let projectId = getProjectIdFromUrl();

    if (projectId) {


        mpgUpdateState('projectId', projectId);

        jQuery('#mpg_project_id span').text(projectId);
        jQuery('.delete-project').show();

        let project = await jQuery.ajax({
            url: ajaxurl,
            method: 'post',
            data: {
                action: 'mpg_get_project',
                projectId
            },
            statusCode: {
                500: function (xhr) {
                    toastr.error(
                        translate['Looks like you attempt to use large source file, that reached memory allocated to PHP or reached max_post_size. Please, increase memory limit according to documentation for your web server. For additional information, check .log files of web server or'] + `<a target="_blank" style="text-decoration: underline" href="https://docs.themeisle.com/article/1443-500-internal-server-error"> ${translate['read our article']}</a>.`,
                        translate['Server settings limitation'], { timeOut: 30000 });
                }
            }
        });

        let projectData = JSON.parse(project)

        if (!projectData.success) {
            toastr.error(projectData.error, translate['Can not get project data']);
            return;
        }

        // ====================  Заполняем данные на странице ====================

        jQuery('.project-builder .project-name').val(projectData.data.name); // input

        jQuery('.project-builder #mpg_entity_type_dropdown').val(projectData.data.entity_type);

        jQuery('.project-builder #mpg_apply_condition').val(projectData.data.apply_condition);

        // checkbox
        jQuery('#mpg_exclude_template_in_robots').prop('checked', parseInt(projectData.data.exclude_in_robots));
        jQuery('#mpg_participate_in_search').prop('checked', parseInt(projectData.data.participate_in_search));
        jQuery('#mpg_participate_in_default_loop').prop('checked', parseInt(projectData.data.participate_in_default_loop));

        // Грузит список типов записей, и сами записи в них. (дропдауны сверху)
        fillCustomTypeDropdown(projectData);

        // Заполним значение для поля количества записей в БД для Спинтакс, для текущего проекта
        jQuery('.cache-info .num-rows').text(projectData.data.spintax_cached_records_count);

        if (projectData.data.sitemap_url) {
            jQuery('#mpg_sitemap_url').html(`<a target="_blank" href="${projectData.data.sitemap_url}">${projectData.data.sitemap_url}</a>`);
        }

        // ==================  Direct link & Schedule section ==================

        if (projectData.data.schedule_source_link) {
            jQuery('input[name="direct_link_input"]').val(projectData.data.schedule_source_link);
        }

        if (projectData.data.schedule_periodicity) {
            jQuery(`select[name="periodicity"] option[value="${projectData.data.schedule_periodicity}"]`).attr('selected', 'selected');
        }

        if (projectData.data.schedule_notificate_about) {
            jQuery(`select[name="notification_level"] option[value="${projectData.data.schedule_notificate_about}"]`).attr('selected', 'selected');
        }

        if (projectData.data.schedule_notification_email) {
            jQuery(`select[name="notification_email"] option[value="${projectData.data.schedule_notification_email}"]`).attr('selected', 'selected');
        }

        jQuery(`input[name="worksheet_id"]`).val(projectData.data.worksheet_id)
        jQuery('.worksheet-id').css({ opacity: 1, height: 'initial' });


        // ====================  Ставим заголовки в стейт ====================

        if (setHeaders(projectData)) {

            // Блочим вкладки, чоть у пользователя и есть проект, но нет датафайла, поэтому нечего ему там делать
            jQuery('a[href="#shortcode"], a[href="#sitemap"],  a[href="#spintax"], a[href="#cache"],  a[href="#logs"]').removeClass('disabled');

            // Заголовки в стейте храню в чистом виде, а по надобности - модифицирую, скажем прибавляя mpg
            // Это потому, что например в блоке копирования шорткодов надо иметь их оригинальный вид.
            let headers = mpgGetState('headers');

            shortCodeTabInit();

            fillDataPreviewAndUrlGeneration(projectData, headers);
        }


        // Если в проекта уже есть файл с данными, то можно сразу показывать их.
        if (projectData.data.name && projectData.data.entity_type && projectData.data.template_id) {

            if (projectData.data.source_path) {
                jQuery('#mpg_in_use_dataset_link')
                    .attr('href', `${backendData.mpgUploadDir}${projectData.data.source_path}`)
                    .removeClass('disabled')
                    .text(translate['Download'])
            }


            if (projectData.data.source_type) {

                // Открываем ту вкладку, которая соотвествует типа загрузки файла
                jQuery(`a[href="#${projectData.data.source_type}"]`).trigger('click');

                if (projectData.data.source_type === 'upload_file') {

                    jQuery('label[for="mpg_upload_file_input"]').text(projectData.data.original_file_url)

                } else if (projectData.data.source_type === 'direct_link') {

                    jQuery('input[name="direct_link_input"]').val(projectData.data.original_file_url);
                }
            }

            jQuery('.project-builder section[data-id="2"]').show();

            mpgUpdateState('separator', projectData.data.space_replacer);

            // Если есть - выводим время слкдующего выполнения крона
            if (projectData.data.nextExecutionTimestamp) {
                let dateTime = convertTimestampToDateTime(projectData.data.nextExecutionTimestamp);

                jQuery('#mpg_next_cron_execution').text(`Next scheduled execution: ${dateTime}`);
                jQuery('.use-direct-link-button').hide();
                jQuery('#mpg_next_cron_execution').parents('.row').show();
            } else {
                jQuery('#mpg_next_cron_execution').parents('.row').hide();
            }

            // =========   URL mode fill ===========
            jQuery(`#mpg_url_mode_group input[id="${projectData.data.url_mode}"]`).attr('checked', 'checked');
            mpgUpdateState('urlMode', projectData.data.url_mode);

            let urlStructureDom = projectData.data.url_structure;

            if (urlStructureDom) {
                // Берем с базы структкру УРЛа с шорткодами, и делаем из него DOM.
                jQuery('#mpg_url_constructor').html(urlStructureToDom(urlStructureDom)).trigger('mpg_render_urls', ['init']);

            } else {
                // Создает шорткоды из заголовков. Выполняется в том случае, если это первый визит после загрузки файла, и в БД нет стуркутры
                // Если же пользователь сохранил в базе свою структуру - то уже будет рендерится она, а не эта (дефолтная из первых столбцов

                const headers = mpgGetState('headers')
                if (headers) {
                    fillUrlStructureShortcodes(headers);
                }
            }

            // =========   Space replacer fill ===========
            jQuery('.spaces-replacer').removeClass('active');

            jQuery('.spaces-replacer').each((index, elem) => {
                if (jQuery(elem).html() === projectData.data.space_replacer) {
                    jQuery(elem).addClass('active');
                }
            });

            // =============== Sitemap ==========
            fillSitemapData(projectData);

            // Cache
            fillCacheData(projectData);

        } else {
            // Блочим вкладки, пока нет пользовательского файла
            jQuery('a[href="#shortcode"], a[href="#sitemap"], a[href="#spintax"], a[href="#logs"]').addClass('disabled');
        }

    } else {
        // Блочим вкладки, если нет преокта (т.е пользователь создает новый, только заполняет данные.)
        jQuery('a[href="#shortcode"], a[href="#sitemap"], a[href="#spintax"], a[href="#logs"]').addClass('disabled');
    }

})();

function fillSitemapData(projectData) {
    // Заполняем стейт, чтобы потом с него считать во вкладке Sitemap
    mpgUpdateState('sitemapUrl', projectData.data.sitemap_url);
    mpgUpdateState('sitemapFilename', projectData.data.sitemap_filename);
    mpgUpdateState('sitemapMaxUrlPerFile', projectData.data.sitemap_max_url);
    mpgUpdateState('sitemapFrequency', projectData.data.sitemap_update_frequency);
    mpgUpdateState('sitemapAddToRobotsTxt', projectData.data.sitemap_add_to_robots);
    mpgUpdateState('sitemapPriority', projectData.data.sitemap_priority);
}

function fillCacheData(projectData) {

    const cacheType = projectData.data.cache_type;

    if (cacheType !== 'none') {
        jQuery('.cache-page .buttons .btn').attr('disabled', 'disabled');
        jQuery(`.cache-page .buttons[data-cache-type=${cacheType}] .enable-cache`)
            .removeAttr('disabled')
            .removeClass('btn-success enable-cache')
            .addClass('btn-warning disable-cache')
            .text('Disable');


        jQuery(`.cache-page .buttons[data-cache-type=${cacheType}] .flush-cache`)
            .removeAttr('disabled')
            .removeClass('btn-light')
            .addClass('btn-danger');

    } else {
        jQuery('.cache-page .buttons .enable-cache').removeAttr('disabled');
    }
}

if (jQuery('.advanced-page').length) {

    jQuery.post(ajaxurl, {
        action: 'mpg_get_hook_name_and_priority',
        securityNonce: backendData.securityNonce
    }).then(hooksRawData => {

        let hooksData = JSON.parse(hooksRawData);

        if (!hooksData.success) {
            toastr.error(hooksData.error, translate['Failed']);
            return;
        } else {

            if (hooksData.data.hook_name && hooksData.data.hook_priority) {

                jQuery('#mpg_hook_name').val(hooksData.data.hook_name);
                jQuery('#mpg_hook_priority').val(hooksData.data.hook_priority);
            }
        }
    });


    jQuery.post(ajaxurl, {
        action: 'mpg_get_basepath',
        securityNonce: backendData.securityNonce
    }).then(basepathRawData => {

        let basepathData = JSON.parse(basepathRawData);

        if (!basepathData.success) {
            toastr.error(basepathData.error, translate['Failed']);
            return;
        } else {

            if (basepathData.data) {

                jQuery('.mpg-path-block select').val(basepathData.data);
            }
        }
    });

    jQuery.post(ajaxurl, {
        action: 'mpg_get_cache_hook_name_and_priority',
        securityNonce: backendData.securityNonce
    }).then(cacheHooksRawData => {

        let cacheHooksData = JSON.parse(cacheHooksRawData);

        if (!cacheHooksData.success) {
            toastr.error(cacheHooksData.error, translate['Failed']);
            return;
        } else {

            if (cacheHooksData.data.cache_hook_name && cacheHooksData.data.cache_hook_priority) {

                jQuery('#mpg_cache_hook_name').val(cacheHooksData.data.cache_hook_name);
                jQuery('#mpg_cache_hook_priority').val(cacheHooksData.data.cache_hook_priority);
            }
        }
    });

    jQuery.post(ajaxurl, {
        action: 'mpg_get_branding_position',
        securityNonce: backendData.securityNonce
    }).then(brandingPositionRawData => {
        let brandingPositionData = JSON.parse(brandingPositionRawData);

        if (!brandingPositionData.success) {
            toastr.error(brandingPositionData.error, translate['Failed']);
            return;
        } else {
            if (brandingPositionData.data) {
                jQuery('#mpg_change_branding_position').val(brandingPositionData.data);
            }
        }
    }).catch(e => {
        console.log(e);
    })

}