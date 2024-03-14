import { translate } from "../lang/init.js";

(function () {

    if (!localStorage.getItem('mpg_state')) {
        localStorage.setItem('mpg_state', JSON.stringify({}));
    }
})()

function mpgUpdateState(property, value) {
    let currentState = JSON.parse(localStorage.getItem('mpg_state')) || {};


    currentState[property] = value;

    localStorage.setItem('mpg_state', JSON.stringify(currentState))
}

const svgCloseIcon = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="10px" viewBox="0 0 20 20" version="1.1"><g id="surface1"><path style=" stroke:none;fill-rule:nonzero;fill:rgb(20%,20%,20%);fill-opacity:1;" d="M 19.800781 18.484375 L 11.296875 9.992188 L 19.789062 1.527344 L 18.53125 0.203125 L 10.003906 8.703125 L 1.574219 0.289062 L 0.253906 1.546875 L 8.710938 9.992188 L 0.199219 18.472656 L 1.457031 19.796875 L 10 11.28125 L 18.472656 19.742188 Z M 19.800781 18.484375 "/></g></svg>';



function mpgGetState(property) {
    if ( window.location.search ) {
        let UrlParam = new URLSearchParams( window.location.search );
        if ( UrlParam.has('action') && 'from_scratch' === UrlParam.get('action') ) {
            return null;
        }
    }

    const state = JSON.parse(localStorage.getItem('mpg_state')) || null

    return state[property] ? state[property] : null;
}

function convertHeadersToShortcodes(headers) {

    let columnsStorage = [];

    headers.forEach(column => {
        let header = '';
        if (column.startsWith('mpg_')) {
            header = column.toLowerCase();
        } else {
            header = `mpg_${column.toLowerCase()}`
        }
        columnsStorage.push({ 'title': header })
    });

    return columnsStorage;
}

function copyTextToClipboard(text) {
    let input = document.createElement('input');
    input.setAttribute('value', text);
    document.body.appendChild(input);
    input.select();
    let result = document.execCommand('copy');
    document.body.removeChild(input);
    return result;
}

function renderShortcodePill(shortcode, index) {

    let dom = `<div contenteditable="false" class="shortcode-chunk">${shortcode}<span class="close">${svgCloseIcon}</span></div>`;

    // Добавляем разделитель в виде тире между "пилами". Частями УРЛа
    return index === 0 || index === 1 ? `${dom}-` : dom;
}

function generateUrlPreview(urlBuilderString, headers, spaceReplacer, inputRow) {

    headers.forEach((rawHeader, index) => {

        let shortcode = '';

        if (rawHeader.startsWith('mpg_')) {
            shortcode = rawHeader.toLowerCase();
        } else {
            shortcode = `mpg_${rawHeader.toLowerCase()}`;
        }

        // Подкидываем строку из таблицы вместо заголовка. toString - на случай если в ячейке будет число.
        // Эта проверка нужна для того, чтобы не было ошибки при попытке .toString() если пустая ячейка.
        let neededHeaderValue = inputRow[index] ? inputRow[index].toString() : '';

        // Удалим слеши в начале и в конце строки.
        let trimedHeader = neededHeaderValue.replace(/^\/+|\/+$/g, '');

        const replacerRegEpx = new RegExp(`${shortcode}`, 'i');

        urlBuilderString = urlBuilderString.replace(replacerRegEpx, trimedHeader);
    });


    // Заменим все пробелы на требуемый заменитель пробелов (spaces replacer)
    // А все спецсимволы - на пустоту, тоесть вырежем.
    let finalPath = urlBuilderString.replace(/\s+/gm, 'mpgspaceholder');

    finalPath = finalPath
    .replace(/\//gm, 'mpgslashholder')
    .replace(/\./gm, 'mpgdotholder')
    .replace(/\-/gm, 'mpgdashholder')
    .replace(/\_/gm, 'mpglodashholder')
    .replace(/\~/gm, 'mpgtildaholder')
    .replace(/\=/gm, 'mpgequalholder');
    
    finalPath = finalPath.replace(/[^\p{L}\d]/gu, '');

    finalPath = finalPath
    .replace(/mpgspaceholder/gm, spaceReplacer)
    .replace(/mpgslashholder/gm, '/')
    .replace(/mpgdotholder/gm, '.')
    .replace(/mpgdashholder/gm, '-')
    .replace(/mpglodashholder/gm, '_')
    .replace(/mpgtildaholder/gm, '~')
    .replace(/mpgequalholder/gm, '=')

    if (finalPath) {
        finalPath = backendData.baseUrl + '/' + finalPath.toLowerCase();
        if ( backendData.lang_code != '' ) {
            finalPath = finalPath.replace( backendData.lang_code, '/' );
        }
        return `${finalPath}/`;
    } else {
        return `${backendData.baseUrl}`;
    }
}

function getProjectIdFromUrl() {
    if (location.href.includes('mpg-project-builder&action=edit_project&id=')) {

        const url = new URL(location.href);
        return url.searchParams.get('id');
    }

    return null;
}

function fillUrlStructureShortcodes(headers) {
    const urlConstructorSelector = jQuery('#mpg_url_constructor');
    // Очищаем перед добавлением новых данных.
    urlConstructorSelector.empty();

    if (headers.includes('mpg_url')) {

        urlConstructorSelector.append(renderShortcodePill('mpg_url')).trigger('input');

    } else {

        headers.forEach((header, index) => {
            if (index <= 2) {
                let shortcode = '';
                if (header.includes('mpg_')) {
                    shortcode = header.toLowerCase();
                } else {
                    shortcode = `mpg_${header.toLowerCase()}`;
                }

                // В хедерах всегда пробелы заменяем на _, а в самих URL'ах - уже на то что выбрал пользователь
                urlConstructorSelector.append(renderShortcodePill(shortcode.replace(' ', '_'), index)).trigger('input');
            }
        })
    }
}

function renderShortCodesDropdown(headers, selector) {

    let option = new Option(translate['Insert shortcode'], null, false, true);
    option.disabled = true;
    selector.append(option);

    headers.forEach((header, index) => {

        selector.append(new Option(convertStringToStortcode(header.replace(/ /g, '_')), index));

    });
}

function convertStringToStortcode(string) {

    if (string.toLowerCase().startsWith('mpg_')) {
        return string.toLowerCase();
    } else {
        return `mpg_${string.toLowerCase()}`
    }

}

function insertTextAfterCursor(areaId, text, pastePosition = null) {

    let txtarea = document.getElementById(areaId);
    let scrollPos = txtarea.scrollTop;
    let strPos = 0;
    let br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
        "ff" : (document.selection ? "ie" : false));
    if (br == "ie") {
        txtarea.focus();
        let range = document.selection.createRange();
        range.moveStart('character', -txtarea.value.length);
        strPos = range.text.length;
    }
    else if (br == "ff") {
        strPos = txtarea.selectionStart;
    }

    if (pastePosition) {
        strPos = pastePosition;
    }

    let front = (txtarea.value).substring(0, strPos);
    let back = (txtarea.value).substring(strPos, txtarea.value.length);
    txtarea.value = front + text + back;
    strPos = strPos + text.length;



    if (br == "ie") {
        txtarea.focus();
        let range = document.selection.createRange();
        range.moveStart('character', -txtarea.value.length);
        range.moveStart('character', strPos);
        range.moveEnd('character', 0);
        range.select();
    }
    else if (br == "ff") {
        txtarea.selectionStart = strPos;
        txtarea.selectionEnd = strPos;
        txtarea.focus();
    }
    txtarea.scrollTop = scrollPos;
}

function domToUrlStructure(html) {

    let urlStructure = '';
    let jqObject = jQuery.parseHTML(html);

    jQuery.each(jqObject, function (index, element) {
        let node = jQuery(element);

        if (node.hasClass('shortcode-chunk')) {
            node.find('span').remove(); // удалим крестик
            urlStructure += '{{' + node.text().replace(/ /g, '_') + '}}'; // а строку, которая была возле него - берем.
        } else {
            urlStructure += node.text();
        }
    });

    return urlStructure;
}

function convertTimestampToDateTime(unix_timestamp) {

    // Create a new JavaScript Date object based on the timestamp
    // multiplied by 1000 so that the argument is in milliseconds, not seconds.
    let date = new Date(unix_timestamp * 1000);
    // Hours part from the timestamp
    let hours = date.getHours();
    // Minutes part from the timestamp
    let minutes = "0" + date.getMinutes();
    // Seconds part from the timestamp
    let seconds = "0" + date.getSeconds();

    // Will display time in 10:30:23 format
    var formattedTime = hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);

    let month = date.getMonth() + 1 < 10 ? `0${date.getMonth() + 1}` : date.getMonth() + 1;

    let formattedDate = `${date.getFullYear()} ${month} ${date.getDate()}`;

    return `${formattedDate} ${translate['at']} ${formattedTime}`;
}


function urlStructureToDom(urlStructureString) {

    let prepend = `<div contenteditable="false" class="shortcode-chunk"><span class="close">${svgCloseIcon}</span>`;
    let postpend = `</div>`;

    return urlStructureString.replace(/{{/g, prepend).replace(/}}/g, postpend);

}

function setHeaders(sourceBlockResponse) {

    // Эта проверка нужна для обработки двух случаев: 
    // 1. Если пользователь заходит на страницу проекта, и данные берутся из БД
    // 2. Если пользователь загружает данные из файла. 
    // 1 - данные идут как джсон строка, а вот втором - как объект

    let headers;
    let rawHeaders = sourceBlockResponse.data.headers;
    if (rawHeaders) {
        if (typeof rawHeaders === 'string') {
            headers = JSON.parse(rawHeaders);
        } else {
            headers = Object.values(rawHeaders);
        }

        // Заголовки в стейте храню в чистом виде, а по надобности - модифицирую, скажем прибавляя mpg
        // Это потому, что например в блоке копирования шорткодов надо иметь их оригинальный вид.
        mpgUpdateState('headers', headers);

        return true;
    }

    return false;
}


function rebuildSandboxShortcode(customUrl = null) {

    let whereAttribute = '';

    const whereStorage = mpgGetState('where');
    if (whereStorage) {
        let condition = '';

        whereStorage.forEach(object => {
            for (let header in object) {
                condition += `${convertStringToStortcode(header)}=${object[header]};`
            }
        });

        whereAttribute = ` where="${condition}"`;
    }

    let operatorAttribute = '';
    // Если условий два, или больше, то надо дать возможность выбрать оператор - or \ and
    if (whereStorage && whereStorage.length > 1) {

        jQuery('.operator-selector-block').css('display', 'flex');

        let operator = jQuery('#mpg_operator_selector option:selected').val();
        operatorAttribute = ` operator="${operator}"`;

    } else {
        jQuery('.operator-selector-block').hide();
    }

    let link = '';
    let headers = mpgGetState('headers');

    if (headers[0] && headers[1]) {

        // Если функция вызывается при изминении УРЛа на вкладке Main
        if (customUrl) {
            link = `<a href="${domToUrlStructure(customUrl)}">{{${convertStringToStortcode(headers[1])}}}</a><br>`;
        } else {
            // Если функция вызывается из вкладки Shordcodes при клике на where дропдауны
            let url = domToUrlStructure(jQuery('#mpg_url_constructor').html());

            link = `<a href="${url}">{{${convertStringToStortcode(headers[1])}}}</a><br>`;
        }
    }

    const limit =  mpgGetState('limit');

    const direction = mpgGetState('direction') ? ` direction="${mpgGetState('direction')}"` : '';

    const orderBy = mpgGetState('order-by') ? ` order-by="${mpgGetState('order-by')}"` : '';

    const uniqueRows = mpgGetState('unique-rows') ? ` unique-rows="${mpgGetState('unique-rows')}"` : '';

    let string = `[mpg project-id="${mpgGetState('projectId')}"${operatorAttribute}${whereAttribute}${uniqueRows}${orderBy}${direction} limit="${limit}"]\n    ${link}\n[/mpg]`;

    jQuery('#mpg_shortcode_sandbox_textarea').val('');
    jQuery('#mpg_shortcode_sandbox_textarea').val(string);

}

export {
    mpgUpdateState,
    mpgGetState,
    convertHeadersToShortcodes,
    copyTextToClipboard,
    renderShortcodePill,
    generateUrlPreview,
    getProjectIdFromUrl,
    fillUrlStructureShortcodes,
    renderShortCodesDropdown,
    convertStringToStortcode,
    insertTextAfterCursor,
    domToUrlStructure,
    urlStructureToDom,
    setHeaders,
    convertTimestampToDateTime,
    rebuildSandboxShortcode
};