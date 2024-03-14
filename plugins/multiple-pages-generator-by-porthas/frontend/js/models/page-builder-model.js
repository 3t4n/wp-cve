import { convertHeadersToShortcodes, mpgUpdateState, renderShortCodesDropdown, } from "../helper.js";
import { translate } from "../../lang/init.js";
import { mpgGetState } from '../helper.js';



async function fillCustomTypeDropdown(projectData) {
    let customTypes = await jQuery.post(ajaxurl, {
        action: 'mpg_get_posts_by_custom_type',
        custom_type_name: projectData.data.entity_type,
        template_id: projectData.data.template_id,
        securityNonce: backendData.securityNonce
    });

    let postsData = JSON.parse(customTypes)

    if (postsData.success !== true) {
        throw postsData.error;
    }

    let setTemplateDropdown = jQuery('#mpg_set_template_dropdown');

    // Очищаем выпадающий список перед тем, как кидать туда новые сущности
    setTemplateDropdown.empty();

    postsData.data.forEach((entity) => {
        //  ставим selected для предварительно выбранного шаблона.
        if (entity.id === parseInt(projectData.data.template_id)) {
            setTemplateDropdown.append(new Option(entity.title, entity.id, false, true));
        } else {

            if (entity.is_home) {
                let option = new Option(`${entity.title} (${translate['Front page']})`, entity.id);
                option.disabled = true;
                setTemplateDropdown.append(option);
            } else {
                setTemplateDropdown.append(new Option(entity.title, entity.id));
            }
        }
    });

    // каждый дропдаун добавляем ссылку на добавление нового поста, страницы или кастом типа. Просто для удобства.
    if (projectData.data.entity_type === 'post') {
        setTemplateDropdown.append(new Option(translate['+ Add new post'], `${backendData.mpgAdminPageUrl}post-new.php`));
    } else if (projectData.data.entity_type) {
        setTemplateDropdown.append(new Option(`${translate['+ Add new']} ${projectData.data.entity_type}`, `${backendData.mpgAdminPageUrl}post-new.php?post_type=${projectData.data.entity_type}`));
    }
    // Получав ссылку из value - делаем на нее редирект
    setTemplateDropdown.on('change', function () {
        if (jQuery(this).val().includes('post-new')) {
            location.href = jQuery(this).val();
        }
    });

    if (projectData.data.source_type === 'direct_link') {
        jQuery('#direct_link').click();
    }
    setTemplateDropdown.select2({
        width: '415px',
        minimumInputLength: 3,
        ajax: {
            delay: 250,
            url: ajaxurl,
            dataType: 'json',
            method: 'post',
            data: function( term ) {
                return {
                    action: 'mpg_get_posts_by_custom_type',
                    custom_type_name: projectData.data.entity_type,
                    q: term,
                    securityNonce: backendData.securityNonce
                }
            },
            processResults: function (res) {
                if (projectData.data.entity_type === 'post') {
                    res.data.push(
                        {
                            id: backendData.mpgAdminPageUrl + 'post-new.php',
                            title: translate['+ Add new post']
                        }
                    );
                } else if (projectData.data.entity_type) {
                    res.data.push(
                        {
                            id: backendData.mpgAdminPageUrl + 'post-new.php?post_type=' + projectData.data.entity_type,
                            title: translate['+ Add new'] + ' ' + projectData.data.entity_type
                        }
                    ); 
                }
                return {
                    results: jQuery.map( res.data, function( obj ) {
                        return {
                            id: obj.id,
                            text: obj.title,
                            disabled: obj.is_home || false,
                        }
                    } )
                }
            }
        }
    });
}

function fillDataPreviewAndUrlGeneration(project, headers) {
    // Поскольку обработка пользователского файла прошла усепшно, то можно показывать вторую секцию, с данными.
    jQuery('section[data-id="2"]').show();

    // Достаем из ответа, и ставим в стейт первый ряд данных, чтобы сформировать превью для url.
    mpgUpdateState('datasetFirstRow', project.data.rows[0]);

    const summaryBlock = jQuery('section[data-id="2"] .summary');
    const summaryBlockContent = summaryBlock.text();
    //  Ставим правильное значение для количества рядов и заголовков в файле
    summaryBlock.text(summaryBlockContent.replace('[rows]', project.data.totalRows).replace('[headers]', headers.length))


    // ['Url']  => [{title: 'mpg_url'}]
    let columnsStorage = convertHeadersToShortcodes(headers);

    const dataTableContainer = jQuery('#mpg_dataset_limited_rows_table');

    const initObject = {
        data: project.data.rows,
        columns: columnsStorage,
        paging: false,
        searching: false,
        ordering: false,
        retrieve: true
    };

    // Перед тем как отрисовать новую таблицу, сначала удалим старую
    dataTableContainer.DataTable(initObject).clear().destroy();
    dataTableContainer.empty();
    let table = dataTableContainer.DataTable(initObject);

    {
        // Прячем колонки, которые не помищеются, чтобы небыло скрола.
        try {
            let tableContainer = jQuery('.data-table-container');
            let containerWidth = tableContainer.width();
            let widthStorage = 0;
            let tableHeaders = jQuery('#mpg_dataset_limited_rows_table thead th');
            let columnsToHide = [];

            jQuery.each(tableHeaders, function (index, elem) {
                widthStorage += jQuery(elem).outerWidth();

                if (widthStorage > containerWidth) {
                    columnsToHide.push(index); // например 5, 6, 7 ..., потому что первых 4 помещаются.
                }
            });
            table.columns(columnsToHide).visible(false);
        } catch (err) {
            console.error(err);
        }
    }




    // Insert shortcodes
    const insertShorecodeDropdown = jQuery('#mpg_main_tab_insert_shortcode_dropdown');

    insertShorecodeDropdown.empty();

    if (headers) {
        renderShortCodesDropdown(headers, insertShorecodeDropdown);
    }

    // Перерисовка поля с превью url
    jQuery('#mpg_url_constructor').trigger('input');

    insertShorecodeDropdown.select2({
        width: '200px'
    });
}


function renderTableWithAllURLs(e) {

    e.preventDefault();

    jQuery('#mpg_preview_all_urls').modal();

    const projectId = mpgGetState('projectId');
    const previewTabTableContainer = jQuery('#mpg_mpg_preview_all_urls_table');

    const initObject = {
        serverSide: true,
        columns: [{ 'title': 'mpg_url' }],
        retrieve: true,
        ajax: {
            "url": `${ajaxurl}?action=mpg_preview_all_urls&projectId=${projectId}`,
            "type": "POST",
            // success: function (res) {  Может пригодится чтобы прятать лоадер }
        }
    };
    // Перед тем как отрисовать новую таблицу, сначала удалим старую
    previewTabTableContainer.DataTable(initObject).clear().destroy();
    previewTabTableContainer.empty();
    previewTabTableContainer.DataTable(initObject);
}


export { fillCustomTypeDropdown, fillDataPreviewAndUrlGeneration, renderTableWithAllURLs }
