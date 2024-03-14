import {
  mpgGetState,
  copyTextToClipboard,
  renderShortCodesDropdown,
  mpgUpdateState,
  insertTextAfterCursor,
  rebuildSandboxShortcode
} from "../helper.js";

import { translate } from '../../lang/init.js';

function renderConditionRowDom(dropdownOptions) {
  return `<div class="condition-row">
  <select class="mpg_headers_condition_key_dropdown">
    <option value="not-set" selected>-- Not set --</option>${dropdownOptions}</select>
  <div class="equal">=</div>
  <select disabled="disabled" class=" mpg_headers_condition_value_dropdown">
    <option disabled selected>${translate['Choose header at first']}</option>
  </select>
  <button class="btn add-new-condition btn-danger">-</button>
  <button class="btn btn-success add-new-condition">+</button>
</div>`;
}

function shortCodeTabInit() {
  const headers = mpgGetState("headers");

  const shortcodeSelector = jQuery(".shortcode-preview-output");
  const insertShortcodeDropdown = jQuery('#mpg_shortcode_tab_insert_shortcode_dropdown');
  const headersShortcodeDropdown = jQuery(".shortcode-headers-dropdown");

  let dropdownOptions;

  headers.forEach((header, index) => {
    dropdownOptions += `<option value="${index}" >mpg_${header}</option>`;
  });

  // Добавляем первый блок для условий
  jQuery(".condition-container").append(renderConditionRowDom(dropdownOptions));

  jQuery('#mpg_order_by').append(dropdownOptions)

  headers.forEach((rawHeader) => {
    // Тут походу еще надо заменять пробелы на _, и бывают всякие сиволы доллара, и т.п.

    let header = rawHeader.toLowerCase().startsWith('mpg_')
      ? rawHeader.toLowerCase().replace(/ /g, '_')
      : `mpg_${rawHeader.toLowerCase().replace(/ /g, '_')}`;

    headersShortcodeDropdown.append(new Option(header, header));

    // При загрузке страницы, сразу выводим в блок шорткод, который является первым option
    shortcodeSelector.text(`{{${headersShortcodeDropdown.find('option:first-child').val()}}}`);
  });

  // ================ Insert Shortcode ===============
  insertShortcodeDropdown.empty();

  if (headers) {
    renderShortCodesDropdown(headers, insertShortcodeDropdown);
  }

  insertShortcodeDropdown.select2({ width: '200px' });

  let textarea = 'mpg_shortcode_sandbox_textarea';

  jQuery(`#${textarea}`).on('blur', function () {
    window.isTextareaBlured = true;
  });


  insertShortcodeDropdown.on('change', function () {
    let choosedShortcode = jQuery(this).find('option:selected').text();

    // Если человек не поставил курсор в текстареа, чтобы вставить шорткод в позицию курсора,
    // то мы автоматом ставим шорткод между [mpg][/mpg]
    if (window.isTextareaBlured) {
      insertTextAfterCursor(textarea, `{{${choosedShortcode}}}`);
    } else {
      const sandboxValue = jQuery(`#${textarea}`).val();

      const positionOfClosedBrace = sandboxValue.indexOf('"]');

      // +2 - накидываем длинну строки "]
      insertTextAfterCursor(textarea, `{{${choosedShortcode}}}`, positionOfClosedBrace + 2);
    }
  });


  // Меняем пустое значение на реальный id проекта
  let sandBoxTextarea = jQuery('#mpg_shortcode_sandbox_textarea').val();
  const projectIdRegExp = /project-id="(.*?)"/gm;

  const updatedProjectId = sandBoxTextarea.replace(projectIdRegExp, `project-id="${mpgGetState('projectId')}"`);

  jQuery('#mpg_shortcode_sandbox_textarea').val(updatedProjectId);

}


let headersShortcodeDropdown = jQuery(".shortcode-headers-dropdown");

headersShortcodeDropdown.on("change", function () {

  let shortcode = jQuery(this).val();
  jQuery(".shortcode-preview-output").html(`{{${shortcode}}}`);

});


jQuery('.shortcode-copy').on('click', function () {

  if (copyTextToClipboard(jQuery('#mpg_shortcode_sandbox_textarea').val())) {
    toastr.success(translate['Shortcode copied to clipboard!'], translate['Success'], { timeOut: 3000 });
  }
});


jQuery(".copy-shortcode-btn").on("click", function () {

  let shortcodeSelector = jQuery(".shortcode-preview-output");
  let shortcodeText = ''


  if (!shortcodeSelector.html().trim()) {
    shortcodeText = '{{' + headersShortcodeDropdown.find('option:first-child').val() + '}}';
  } else {
    shortcodeText = shortcodeSelector.html();
  }

  if (copyTextToClipboard(`${shortcodeText}`)) {
    toastr.success(translate['Shortcode copied to clipboard!'], translate['Success'], { timeOut: 3000 });
  } else {
    toastr.warning(translate['Looks like something went wrong while copying shortcode'], translate['Hmm'], { timeOut: 3000 });
  }
});


jQuery('.project-builder').on('change', '.mpg_headers_condition_key_dropdown', async function () {

  let choosedColumnNumber = jQuery(this).val();

  jQuery(this).siblings('.mpg_headers_condition_value_dropdown').empty();
  jQuery('.mpg_headers_condition_value_dropdown').prepend(jQuery('<option></option>').html('Loading...'));

  let column = await jQuery.post(ajaxurl, {
    action: "mpg_get_unique_rows_in_column",
    choosedColumnNumber,
    projectId: mpgGetState('projectId'),
    securityNonce: backendData.securityNonce
  });

  let columnData = JSON.parse(column);

  if (!columnData.success) {
    toastr.error(columnData.error, 'Error');
    return;
  }

  jQuery(this).siblings('.mpg_headers_condition_value_dropdown').empty();

  columnData.data.forEach((value, index) => {
    jQuery(this).siblings('.mpg_headers_condition_value_dropdown').append(new Option(value, index));
  });

  let checkedKeyOption = jQuery(this).find('option:checked');

  if (checkedKeyOption.val() === "not-set") {
    jQuery(this).siblings('.mpg_headers_condition_value_dropdown').attr('disabled', 'disabled');

    mpgUpdateState('where', []);

    rebuildSandboxShortcode();

  } else {
    jQuery(this).siblings('.mpg_headers_condition_value_dropdown').removeAttr('disabled');
  }
});


jQuery(document).on('blur', '.mpg_headers_condition_value_dropdown', async function () {

  let where = [];

  // После выбора значения из второго дропдуана - собираем значение из всех, и кидаем в стейт.
  jQuery('.condition-row').each(function () {

    let key = jQuery(this).find('.mpg_headers_condition_key_dropdown option:selected').text();
    let value = jQuery(this).find('.mpg_headers_condition_value_dropdown option:selected').text();

    if (key !== '-- Not set --') {

      // Храню это как массив объектов, а не как объект, чтобы можно было
      // разные значения where для полей с одинаковыми названиями. (чтобы ключи не перезатирались)
      where.push({ [key]: value });

      mpgUpdateState('where', where);
    }
  });

  rebuildSandboxShortcode();

});


jQuery('#mpg_operator_selector').on('blur', function () {

  let operator = jQuery('#mpg_operator_selector option:selected').val();

  let sandboxValue = jQuery('#mpg_shortcode_sandbox_textarea').val();

  const operatorRegExp = /operator="(.*?)"/gm;
  let match = operatorRegExp.exec(sandboxValue);

  // Заменяем то что есть сейчас в блоке с кодом, на то что пользователь выбрал в дропдуане
  let newCondition = sandboxValue.replace(`operator="${match[1]}"`, `operator="${operator}"`);

  jQuery('#mpg_shortcode_sandbox_textarea').val(newCondition);
});


jQuery('.condition-container').on("click", "button", function () {

  if (jQuery(this).hasClass('btn-success')) {

    jQuery(".condition-row")
      .find("button:eq(0)")
      .addClass("btn-danger")
      .addClass("mpp-remove-action")
      .removeClass("btn-success")
      .html("-");

    const headers = mpgGetState("headers");

    let dropdownOptions;

    headers.forEach((header, index) => {
      dropdownOptions += `<option value="${index}" >${header}</option>`;
    });

    jQuery(".condition-container").append(renderConditionRowDom(dropdownOptions));


  } else {


    let allWhereConditions = mpgGetState('where');

    let key = jQuery(this).parent().find('.mpg_headers_condition_key_dropdown option:selected').text();
    let value = jQuery(this).parent().find('.mpg_headers_condition_value_dropdown option:selected').text();

    // Если в стейте есть такой ключ, и в нем лежит точно такое же значение, как сейчас
    // выбрано в дропдауне, значит это точно этот ряд. Удаляем!


    if (allWhereConditions) {
      allWhereConditions = allWhereConditions.filter(function (whereState) {

        let whereKey = whereState[key];
        // Если и ключи и значения совпадают, значит это точно нужный элемент. Удаляем!
        if (Object.keys(whereState)[0] === key && Object.values(whereState)[0] === value) {
          return false;
        }
        return true;
      });

      mpgUpdateState('where', allWhereConditions);
    }

    // Если кнопка не имеет класа "btn-success", значит у нее класс - btn-danger (remove).
    // А значит, при клике на эту кнопку надо удалить ряд с условием.
    jQuery(this).parent().remove();

    rebuildSandboxShortcode(allWhereConditions);

  }
});

jQuery('#mpg_limit').on('change', function () {

  mpgUpdateState('limit', jQuery(this).val());
  rebuildSandboxShortcode();

});

jQuery('#mpg_direction').on('change', function () {
  const directionValue = jQuery(this).find('option:checked').val();

  if (['asc', 'desc', 'random'].includes(directionValue)) {

    mpgUpdateState('direction', directionValue);
    rebuildSandboxShortcode();

    // Если сортируем случайным образом - нет смысла выбирать столбец.
    jQuery('#mpg_order_by').attr('disabled', directionValue === 'random');

  } else {
    toastr.warning(translate['Choosed wrong direction'], translate['Error']);
  }

});

jQuery('#mpg_order_by').on('change', function () {
  const orderByValue = jQuery(this).find('option:checked').text();

  mpgUpdateState('order-by', orderByValue);
  rebuildSandboxShortcode();
});

jQuery('#mpg_unique_rows').on('change', function () {
  const uniqueRowsValue = jQuery(this).find('option:checked').val();

  mpgUpdateState('unique-rows', uniqueRowsValue.toLowerCase());
  rebuildSandboxShortcode();
});



// ================== Preview  =================

jQuery('#shortcode .shortcode-preview').on('click', async function () {

  let textareaValue = jQuery('#mpg_shortcode_sandbox_textarea').val();
  try {

    const contentRegex = /\[mpg.*?](.*?)\[\/mpg\]/;
    const projectIdRegExp = /project-id="(.*?)"/;
    const whereRegExp = /where="(.*?)"/;
    const operatorRegExp = /operator="(.*?)"/;
    const orderByRegExp = /order-by="(.*?)"/;
    const directionRegExp = /direction="(.*?)"/;
    const limitRegExp = /limit="(.*?)"/
    const uniqueRowsRegExp = /unique-rows="(.*?)"/

    // ==============   Content   ===============
    let contentMatches = contentRegex.exec(textareaValue.replace(/\n/g, ''));

    let content;
    if (contentMatches && contentMatches[1]) {
      content = contentMatches[1];
    } else {
      throw translate['You need to fill some static content with shortcodes beetwen [mpg] [/mpg]'];
    }

    // =================  ProjectId  =================
    let projectIdMatches = projectIdRegExp.exec(textareaValue.replace(/\n/g, ''));

    let projectId;
    if (projectIdMatches && projectIdMatches[1]) {
      projectId = projectIdMatches[1].trim();
    }

    // =================  Where  =================
    let whereMatches = whereRegExp.exec(textareaValue.replace(/\n/g, ''));

    let where;
    if (whereMatches && whereMatches[1]) {
      where = whereMatches[1].trim();
    }

    // =================  Operator  =================
    let operatorMatches = operatorRegExp.exec(textareaValue.replace(/\n/g, ''));

    let operator;
    if (operatorMatches && operatorMatches[1]) {
      operator = operatorMatches[1].trim();
    }

    // =================  Limit  =================
    let limitMatches = limitRegExp.exec(textareaValue.replace(/\n/g, ''));

    let limit;
    if (limitMatches && limitMatches[1]) {
      limit = limitMatches[1].trim();
    }

    // ================ Order By =================
    let orderByMatches = orderByRegExp.exec(textareaValue.replace(/\n/g, ''));

    let orderBy;
    if (orderByMatches && orderByMatches[1]) {
      orderBy = orderByMatches[1].trim();
    }

    // ================ Order By =================
    let directionMatches = directionRegExp.exec(textareaValue.replace(/\n/g, ''));

    let direction;
    if (directionMatches && directionMatches[1]) {
      direction = directionMatches[1].trim();
    }

    // ================ Unique Rows =================
    let uniqueRowsMatches = uniqueRowsRegExp.exec(textareaValue.replace(/\n/g, ''));
    
    let uniqueRows;
    if (uniqueRowsMatches && uniqueRowsMatches[1]) {
      uniqueRows = uniqueRowsMatches[1].trim();
    }

    // ==================  AJAX =================
    let shortcodePreview = await jQuery.post(ajaxurl, {
      action: "mpg_shortcode",
      content,
      projectId,
      where,
      operator,
      orderBy,
      direction,
      limit,
      uniqueRows,
      securityNonce: backendData.securityNonce
    });

    let shortcodePreviewData = JSON.parse(shortcodePreview);

    if (!shortcodePreviewData.success) {
      toastr.error(shortcodePreviewData.error, translate['Error']);
      return;
    }

    const previewData = shortcodePreviewData.data;

    // Решает проблему с тем, что если в превью в ссылку вставить порткод, типа {{mpg_city}},
    // то ссылка ведет на страниццу админки, типа domain.com/wp-admin/dzenzelivka, следствие - 404.
    if (previewData.includes('href="') && !previewData.includes(backendData.baseUrl)) {

      let htmlWithCorrectLink = previewData.replace(/href="/g, `href="${backendData.baseUrl}/`);

      jQuery('.mpg_list_preview-block').html(htmlWithCorrectLink);
    } else {
      jQuery('.mpg_list_preview-block').html(shortcodePreviewData.data);
    }


  } catch (error) {
    toastr.warning(error, translate['Incorrect input'])
  }

});


export { shortCodeTabInit };