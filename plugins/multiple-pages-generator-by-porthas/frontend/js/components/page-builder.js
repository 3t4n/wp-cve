import {
    mpgUpdateState,
    mpgGetState,
    convertHeadersToShortcodes,
    renderShortcodePill,
    generateUrlPreview,
    fillUrlStructureShortcodes,
    domToUrlStructure,
    setHeaders,
    rebuildSandboxShortcode
} from '../helper.js';
import { translate } from '../../lang/init.js';
import { Upload } from '../../libs/jquery.ajaxFileUpload.js';
import {
    fillCustomTypeDropdown,
    fillDataPreviewAndUrlGeneration,
    renderTableWithAllURLs
} from '../models/page-builder-model.js';


// При переходе на определенный проект = надо грузить конфиг с БД и закидывать в стейт именно для него.
// А если проект новый - то загружать дефолтный конфиг.
(function mpg_init() {
    mpgUpdateState('separator', '-');  // @todo: првоерить что это нормально работает.
})();

jQuery(window).on('beforeunload', function () {
    localStorage.removeItem('mpg_state');
});

// ========  Delete project  ========
jQuery('.delete-project').on('click', async function (e) {

    e.preventDefault();

    let decision = confirm(translate['Are you sure, that you want to delete project? This action can not be undone.']);

    if (decision) {

        let project = await jQuery.post(ajaxurl, {
            action: 'mpg_delete_project',
            projectId: mpgGetState('projectId'),
            securityNonce: backendData.securityNonce
        });

        let projectData = JSON.parse(project);

        if (!projectData.success) {
            toastr.error(projectData.error, 'Can not delete project');
        }

        toastr.success(translate['Your project was successfully deleted'], translate['Deleted!'])

        setTimeout(() => {
            location.href = backendData.datasetLibraryUrl;
        }, 3000);
    }
});

jQuery('select[name="periodicity"]').on('change', function () {

    let value = jQuery(this).children("option:selected").val();

    let remoteUrl = jQuery('.direct-link-schedule-form input[name="datetime_upload_remote_file"]');
    let notificationLevel = jQuery('.direct-link-schedule-form select[name="notification_level"]');
    let notificationEmail = jQuery('.direct-link-schedule-form input[name="notification_email"]');

    if ( value !== 'now' && value !== 'once' ) {
        // Делаем остальные поля доступными.
        remoteUrl.removeClass('disabled').attr('required', 'required');
        notificationLevel.removeClass('disabled').attr('required', 'required');
        notificationEmail.removeClass('disabled').attr('required', 'required');
    } else {
        remoteUrl.addClass('disabled').removeAttr('required');
        notificationLevel.addClass('disabled').removeAttr('required');
        notificationEmail.addClass('disabled').removeAttr('required');
    }

})


// Подгружаем посты (сущности) которые есть в выбраном кастом типе.
jQuery(document).on('change', '#mpg_entity_type_dropdown', async function () {

    let customType = jQuery(this).val();

    await fillCustomTypeDropdown({ data: { entity_type: customType } })
});


// Обрабатываем выбраный файл как источник. 
jQuery('input[name="mpg_upload_file_input"]').on('change', async function () {

    var file = jQuery(this)[0].files[0];
    var upload = new Upload(file);

    // Если загружаем файл, значит надо очистить инпут для ссылки на файл. (в соседней вкладке)
    jQuery('#direct_link input[name="direct_link_input"]').val('');

    // try {
    let uploadFileRawResponse = await upload.doUpload();

    let uploadFileResponse = JSON.parse(uploadFileRawResponse);

    if (!uploadFileResponse.success) {
        throw uploadFileResponse.error;
    }

    mpgUpdateState('source', { type: 'upload_file', path: uploadFileResponse.data.path });

    toastr.success(translate['We will use this file as source'], translate['Got it!'], { timeOut: 5000 });

    let sourceBlockRawResponse = await jQuery.post(ajaxurl, {
        action: 'mpg_upsert_project_source_block',
        projectId: mpgGetState('projectId'),
        path: uploadFileResponse.data.path,
        securityNonce: backendData.securityNonce
    });

    let sourceBlockResponse = JSON.parse(sourceBlockRawResponse)

    if (!sourceBlockResponse.success) {
        toastr.error(translate['Something went wrong while saving project data. Try reload page'], translate['Can not update project']);
    }

    if (!setHeaders(sourceBlockResponse)) {
        throw translate['Can not get headers form source file'];
    }

    const headers = mpgGetState('headers');

    fillDataPreviewAndUrlGeneration(sourceBlockResponse, headers);

    if (!sourceBlockResponse.data.url_structure) {
        fillUrlStructureShortcodes(headers);
    }

    jQuery('a[href="#shortcode"], a[href="#sitemap"],  a[href="#spintax"]').removeClass('disabled');

});

jQuery('input[name="direct_link_input"]').on('input', function () {

    const fieldValue = jQuery(this).val();

    if (fieldValue && fieldValue.includes('google.com')) {
        jQuery('.worksheet-id').css({ opacity: 1, height: 'initial' });
    } else {
        jQuery('.worksheet-id').css({ opacity: 0, height: 0 });
    }

});

jQuery('input[name="worksheet_id"]').on('input', function () {

    const fieldValue = jQuery(this).val();

    if(fieldValue === '0'){
        toastr.warning(
            translate['Worksheet ID cannot be zero. If your document has one sheet or you would like to use the first sheet - just keep this field empty'], 
            translate['Wrong worksheet id'],
            {timeOut: 10000});
    }

});


// При клике на таб загрузки файла - скрываем прогресс-бар загрузки.
jQuery('#upload_file_tab').on('click', function () {
    jQuery('#progress-wrp').hide();
});


jQuery('#mpg_url_mode_group input').on('click', function () {
    const urlMode = jQuery(`#mpg_url_mode_group input:checked`).attr('id');

    mpgUpdateState('urlMode', urlMode);

    jQuery('#mpg_url_constructor').trigger('mpg_render_urls');
});

jQuery('.project-builder section[data-id="2"] .save-changes').on('click', async function () {

    const urlStructureField = jQuery('#mpg_url_constructor').html();
    let parsedUrlStructure = '';

    if (urlStructureField) {
        parsedUrlStructure = domToUrlStructure(urlStructureField);
    }

    const replacer = jQuery('.spaces-replacer.active').html();

    if (!parsedUrlStructure.includes('{{mpg_')) {

        toastr.warning(translate['Your URL must contain at least one shortcode'], translate['Wrong URL structure']);
        return;
    }

    jQuery( this ).next('span.spinner').addClass( 'is-active' );
    jQuery( this ).attr( 'disabled', true );

    let dataObject = {
        action: 'mpg_upsert_project_url_block',
        projectId: mpgGetState('projectId'),
        urlStructure: parsedUrlStructure.toLowerCase(),
        replacer,
        urlMode: mpgGetState('urlMode')
    }

    if (mpgGetState('source')) {
        dataObject.sourceType = mpgGetState('source').type;
    }


    let directLink = jQuery('input[name="direct_link_input"]').val();
    let periodicity = jQuery('select[name="periodicity"]').val();

    if (directLink && periodicity && periodicity !== 'now' && periodicity !== 'once') {

        // Да, у нас есть path, но по нему файл не скачаешь. Надо  иметь url.
        dataObject.directLink = directLink

        dataObject.timezone = jQuery('input[name="mpg_timezone_name"]').val();
        dataObject.fetchDateTime = jQuery('input[name="datetime_upload_remote_file"]').val();
        dataObject.notificateAbout = jQuery('select[name="notification_level"]').val();
        dataObject.notificationEmail = jQuery('input[name="notification_email"]').val();
    }
    dataObject.periodicity = periodicity;
    if (jQuery(`input[name="worksheet_id"]`).val()) {
        dataObject.worksheetId = jQuery('input[name="worksheet_id"]').val();
    } else {
        dataObject.worksheetId = null
    }
    dataObject.securityNonce = backendData.securityNonce;

    let response = await jQuery.post(ajaxurl, dataObject);

    let project = JSON.parse(response)

    if (!project.success) {
        toastr.error(translate['Something went wrong while saving project data. Try reload page'], translate['Can not update project']);
    }

    toastr.success(translate['Your changes was saved'], translate['Success']);

    setTimeout(() => { location.reload() }, 1000);

});


jQuery('#mpg_preview_modal_link').on('click', function (e) {
    e.preventDefault();

    jQuery('#mpg_preview_modal').modal();

    const headers = mpgGetState('headers');
    const projectId = mpgGetState('projectId');
    const previewTabTableContainer = jQuery('#mpg_data_full_preview_table');


    const initObject = {
        serverSide: true,
        columns: convertHeadersToShortcodes(headers),
        retrieve: true,
        ajax: {
            "url": `${ajaxurl}?action=mpg_get_data_for_preview&projectId=${projectId}`,
            "type": "POST",
            // success: function (res) {  Может пригодится чтобы прятать лоадер }
        }
    };

    // Перед тем как отрисовать новую таблицу, сначала удалим старую
    previewTabTableContainer.DataTable(initObject).clear().destroy();
    previewTabTableContainer.empty();
    previewTabTableContainer.DataTable(initObject);

});


jQuery('.project-builder .spaces-replacer').on('click', function () {

    jQuery('.project-builder .spaces-replacer').removeClass('active');

    jQuery(this).addClass('active');

    mpgUpdateState('separator', jQuery(this).text());
    jQuery('#mpg_url_constructor').trigger('mpg_render_urls');

});

// При выборе шорткода из выпадающего списка, вставляем его в поле билдера для url.
jQuery('#mpg_main_tab_insert_shortcode_dropdown').on('change', function () {
    let shortcode = jQuery('#mpg_main_tab_insert_shortcode_dropdown option:selected').text();

    jQuery('#mpg_url_constructor')
        .append(renderShortcodePill(shortcode))
        .trigger('mpg_render_urls');
});

// Удаляем блок при клике на крестик.
jQuery('#mpg_url_constructor').on('click', '.shortcode-chunk .close', function () {
    jQuery(this).parent().remove();
    jQuery('#mpg_url_constructor').trigger('mpg_render_urls');
});


jQuery('#mpg_url_constructor').on('keydown', function (event) {

    const deniedChars = ['<', '(', '[', '{', '\\', '^', '=', '$', '!', '|', ']', '}', ')', '?', '*', '+', '>', '@', '#', '%', ':', ';', '&', '`', "'", ','];

    toastr.options.preventDuplicates = true;
    if (deniedChars.includes(event.key)) {
        toastr.warning(translate['Unsupported char. Supported only _, -, /, ~, ., ='], 'Warning',);
        return false;
    }
});

// Если изменяется что-то в url билдере - надо "перерисовать" preview url.
jQuery('#mpg_url_constructor').on('mpg_render_urls input', function (e, action) {

    //  Если  будут изменения в структуре url'а - надо делать ссылку не кликабельной.
    let inputHtml = jQuery(this).text();

    // Когда человек собирает УРЛ во вкладке Main, мы ему этот же УРЛ подкидываем в shortcodes preview, для удобства
    rebuildSandboxShortcode(jQuery(this).html());

    const headers = mpgGetState('headers');
    const spaceReplacer = mpgGetState('separator');

    let linksAccumulator = '<ul>';
    const row = mpgGetState('datasetFirstRow'); // первый ряд

    let link = generateUrlPreview(inputHtml, headers, spaceReplacer, row);

    if (mpgGetState('urlMode') === 'without-trailing-slash') {
        link = link.replace(/\/$/, '');
    }

    if (action === 'init') {
        linksAccumulator += `<li><a target="_blank" href="${link}">${link}</a></li>`;
        jQuery('#mpg_preview_all_urls_link').removeClass('disabled-link')

    } else {
        linksAccumulator += `<li>${link}</li>`;
        jQuery('#mpg_preview_all_urls_link').addClass('disabled-link');
    }

    linksAccumulator += '</ul>';

    jQuery('#mpg_preview_url_list').html(linksAccumulator);

});

jQuery('#mpg_preview_all_urls_link').on('click', renderTableWithAllURLs);

jQuery('#mpg_upload_file_input').on('change', function () {
    //get the file name
    var fileName = jQuery(this).val();
    //replace the "Choose a file" label
    jQuery(this).next('.mpg_upload_file-label').html(fileName);
})

jQuery('#mpg_unschedule_task').on('click', async function () {

    let decision = confirm(translate['Are you sure, that you want to unschedule task?']);

    if (decision) {

        let project = await jQuery.post(ajaxurl, {
            action: 'mpg_unschedule_cron_task',
            projectId: mpgGetState('projectId'),
            securityNonce: backendData.securityNonce
        });

        let projectData = JSON.parse(project)

        if (!projectData.success) {
            toastr.error(projectData.error, translate['Can not unschedule task']);
            return false;
        }

        toastr.success(translate['Task was successfully unschedule'], translate['Unscheduled!'])

        setTimeout(() => {
            location.href = `${backendData.projectPage}&action=edit_project&id=${mpgGetState('projectId')}`;
        }, 1000);
    }
})

jQuery('.direct-link-schedule-form').on('submit', async function (e) {

    e.preventDefault();  // Это для того, чтобы сработала вализация на поля

    // Обрабатываем вставку ссылки на удаленный файл, который выбран как источник данных
    jQuery('#upload_file input[name="mpg_upload_file_input"]').val('');

    const fileUrl = jQuery('#direct_link input[name="direct_link_input"]').val();

    if (!fileUrl) {
        toastr.warning(translate['You need to paste link to file before using it'], translate['Missing URL']);
        return;
    }

    const projectId = mpgGetState('projectId');

    const worksheetId = jQuery('input[name="worksheet_id"').val().length ? jQuery('input[name="worksheet_id"').val() : null;

    // При клике на кнопку- делаем ajax запрос, по ссылке скачиваем файл, ложим его в папку temp и возвращаем на фронт path
    let uploadFileRawResponse = await jQuery.post(ajaxurl, {
        action: 'mpg_download_file_by_url',
        projectId,
        fileUrl,
        worksheetId,
        securityNonce: backendData.securityNonce
    });

    let uploadFileResponse = JSON.parse(uploadFileRawResponse)

    if (uploadFileResponse.success !== true) {
        throw uploadFileResponse.error;
    }

    mpgUpdateState('source', { type: 'direct_link', path: uploadFileResponse.data.path });

    toastr.success(translate['We will use this link  to file as source'], translate['Uploaded successfully!'], { timeOut: 5000 });

    let sourceBlockRawResponse = await jQuery.post(ajaxurl, {
        action: 'mpg_upsert_project_source_block',
        projectId: projectId,
        path: uploadFileResponse.data.path,
        securityNonce: backendData.securityNonce

    });

    

    let sourceBlockResponse = JSON.parse(sourceBlockRawResponse)

    if (!sourceBlockResponse.success) {
        toastr.error(translate['Something went wrong while saving project data. Try reload page'], translate['Can not update project']);
        return;
    }

    if (setHeaders(sourceBlockResponse)) {

        const headers = mpgGetState('headers')

        fillDataPreviewAndUrlGeneration(sourceBlockResponse, headers);

        if (!sourceBlockResponse.data.url_structure) {
            fillUrlStructureShortcodes(headers);
        }

        jQuery('a[href="#shortcode"], a[href="#sitemap"], a[href="#spintax"]').removeClass('disabled');
    }

    // need to show block
    jQuery('#mpg_next_cron_execution').text(sourceBlockResponse.data.nextExecutionTimestamp)
});


// Передача данных с первой секции на сервер
jQuery('.main-template-info').on('submit', async function (e) {

    e.preventDefault();  // Это для того, чтобы сработала вализация на поля

    const projectName = jQuery('.project-name').val();
    const entityType = jQuery('#mpg_entity_type_dropdown').val();
    const templateId = jQuery('#mpg_set_template_dropdown').val();
    const applyCondition = jQuery('#mpg_apply_condition').val();
    const submitButton = jQuery( this ).find( 'button' );
    submitButton.next('span.spinner').addClass( 'is-active' );
    submitButton.attr( 'disabled', true );
    let response = await jQuery.post(ajaxurl, {
        action: 'mpg_upsert_project_main',
        // null - это знак, что надо создавать новй проект, а если projectId есть, то обновляем
        projectId: mpgGetState('projectId') ? mpgGetState('projectId') : null,
        projectName,
        entityType,
        templateId,
        applyCondition,
        excludeInRobots: jQuery('#mpg_exclude_template_in_robots').is(':checked'),
        participateInSearch: jQuery('#mpg_participate_in_search').is(':checked'),
        participateInDefaultLoop: jQuery('#mpg_participate_in_default_loop').is(':checked'),
        securityNonce: backendData.securityNonce
    });

    let project = JSON.parse(response)

    if (!project.success) {
        toastr.error(translate['Something went wrong while saving project data. Details:'] + project.error, translate['Can not update project']);
    }

    let { projectId } = project.data;

    toastr.success(translate['Project saved sucessully'], translate['Success']);

    setTimeout(() => {
        location.href = `${backendData.projectPage}&action=edit_project&id=${projectId}`;
    }, 1000);

});
