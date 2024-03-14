jQuery(document).ready(function ($) {
  const { id, url, code_setting } = data;

  const currencies_ids = $(`#woocommerce_${id}_currencies_ids_field`);
  const currencies_option = currencies_ids.data("currencies");
  const currencies_list = currencies_ids.data("currencies_list");
  const add_currencies_button = $(`<div style='margin-left:auto;' class='button-primary woocommerce-save-button'>Add currency</div>`)
  const currencies_group = $(
    `<div style='width:400px;display:flex;flex-direction:column;gap:10px'>
        <div id='currencies_container'></div>
    </div>`
  )

  const genInputs = (key = null, index = 0) => {
    let options = ''
    currencies_list.forEach(i => {
      options += `<option ${(key && key == i) ? 'selected' : ''} value='${i}'>${i}</option>`
    })

    return $(
      `<div style='display:flex;margin-top:10px;flex-wrap:wrap'>
          <select name='currencies_ids[${index}][name]' style='width:100px'>
            <option selected disabled>Currency</option>
            ${options}
          </select>
          <input name='currencies_ids[${index}][value]' value='${(key && currencies_option[key]) ? currencies_option[key] : ''}' 
          style='width:300px' type='text' placeholder='Entity ID'/>
          <small style='display:block;margin-left:105px'>Leave it blank and save to remove</small>
      </div>`)
  }


  Object.keys(currencies_ids.data("currencies")).forEach((key,index) => {
    currencies_group.find('#currencies_container').append(genInputs(key, index))
  })

  currencies_group.append(add_currencies_button)
  currencies_ids.parent().append(currencies_group)

  add_currencies_button.on('click', function () {
    let index = $("#currencies_container").children().length
    $('#currencies_container').append(genInputs(null,index))
  })



  // Convert TextArea for custom css field on admin settings
  wp.codeEditor.initialize($(`#woocommerce_${id}_custom_style`), code_setting);

  /**
   * Make Validation before submit settings via send request 
   * to prepare checkout to make sure the access token and entity id is valid 
   * @if return success response @then => resume submitting @else print error msg and preventDefault
   * 
   * @returns {boolean} if valid or not
   * 
   */
  const from = $("#mainform");

  from.on('submit', function (event) {

    $('.input-error-msg').remove();


    let valid = false

    const accesstoken = $(this).find(`input[name='woocommerce_${id}_accesstoken']`);
    const entityId = $(this).find(`input[name='woocommerce_${id}_entityId']`);

    const dataToSend = {
      entityId: entityId.val(),
    };

    $.ajax({
      type: 'POST',
      async: false,
      headers: {
        Authorization: `Bearer ${accesstoken.val()}`
      },
      url: url,
      data: dataToSend,
      dataType: "json",
      success: function () {
        valid = true
      },
      error: function (resultData) {
        valid = false;
        accesstoken.addClass('input-error').after(`<p class='input-error-msg'>${resultData.responseJSON.result.description}</p>`);
        entityId.addClass('input-error').after(`<p class='input-error-msg'>${resultData.responseJSON.result.description}</p>`);
        $('html, body').animate({
          scrollTop: accesstoken.offset().top - 200
        }, 200);
      },

    });
    return valid;
  })

});