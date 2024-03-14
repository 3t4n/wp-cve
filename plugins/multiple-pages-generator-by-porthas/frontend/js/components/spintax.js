
import { copyTextToClipboard, mpgGetState } from '../helper.js';
import { translate } from '../../lang/init.js';

const inputTextarea = jQuery('#mpg_spintax_input_textarea');
const outputTextarea = jQuery('#mpg_spintax_output_textarea');

jQuery('#mpg_spin').on('click', async function () {

    const spintaxString = inputTextarea.val();

    jQuery( this ).next('span.spinner').addClass( 'is-active' );
    jQuery( this ).attr( 'disabled', true );

    const spintaxRawResponse = await jQuery.post(ajaxurl, {
        action: 'mpg_generate_spintax',
        spintaxString,
        securityNonce: backendData.securityNonce
    });

    let spintaxResponse = JSON.parse(spintaxRawResponse)

    if (!spintaxResponse.success) {
        toastr.error(spintaxResponse.error, 'Failed');
    } else {
        outputTextarea.html(spintaxResponse.data);
    }
    jQuery( this ).next('span.spinner').removeClass( 'is-active' );
    jQuery( this ).removeAttr( 'disabled' );
});


jQuery('.copy-spintax-output').on('click', function () {

    const randomNumber = Math.floor(Math.random() * 1000) + 100;
    if (copyTextToClipboard(`[mpg_spintax  project_id="${mpgGetState('projectId')}" block_id="${randomNumber}"]${inputTextarea.val()}[/mpg_spintax]`)) {
        toastr.success(translate['Spintax code copied to clipboard!'], translate['Success'], { timeOut: 3000 });
    }
});


jQuery('.spintax-page .cache-info button').on('click', async function (e) {

    e.preventDefault();

    let decision = confirm(translate['Are you sure, that you want to flush Spintax cache for current project? This action can not be undone.']);

    if (decision) {

        let project = await jQuery.post(ajaxurl, {
            action: 'mpg_flush_spintax_cache',
            projectId: mpgGetState('projectId'),
            securityNonce: backendData.securityNonce
        });

        let projectData = JSON.parse(project)

        if (!projectData.success) {
            toastr.error(projectData.error, translate['Can not flush Spintax cache']);
        }

        toastr.success(translate['Spintax cache successfully flushed'], translate['Done!'])

        // Заполним значение для поля количества записей в БД для Спинтакс, для текущего проекта
        jQuery('.cache-info .num-rows').text(0);
    }
});