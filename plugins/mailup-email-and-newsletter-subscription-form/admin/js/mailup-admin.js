(function ($) {
  "use strict";
  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   */

  $(function () {
    $('.mailup-section-header__label').click(function () {
      if ($('.mailup-section-content__box > form').valid()) {
        $('.mailup-section-header__label, .mailup-section-content__box').removeClass('active');
        var $active = $(this).attr('data');
        window.location.hash = 'tab-' + $active;
        $(this).addClass('active');
        $('.mailup-section-content__box#' + $active).addClass('active');
      }
    });

    $("form#mailup-form").validate({
      rules: {
        sel_group: {
          invalid_char: true,
        }
      },
      invalidHandler: function (event, validator) {
        event.preventDefault();
        $('html, body').animate({ scrollTop: $(validator.errorList[0].element).offset().top - 200 }, 250);
      }
    });

    $("form#mailup-form-fields").validate(
      {
        focusInvalid: false,
        rules: {
          must_have: {
            required: true,
          },
          field_type: {
            must_have_field: true,
          },
        },
        highlight: function (element) {
          if ($(element).is("input[name*='must_have']")) {
            $(element).next().addClass('error');
            $("input[name*='must_have']").addClass("error");
            $("input[name*='must_have']").nextAll('span').addClass("error");
          }
          $(element).addClass("error");
        },
        unhighlight: function (element) {
          if ($(element).is("input[name*='must_have']")) {
            $(element).next().removeClass('error');
            $("input[name*='must_have']").removeClass("error");
            $("input[name*='must_have']").nextAll('span').removeClass('error');
          }
          $(element).removeClass("error");
        },
        invalidHandler: function () {
          $('html, body').animate({ scrollTop: $("form#mailup-form").offset().top }, 0);
        }
      });

    $.validator.addMethod('must_have_field', function (value, element) {
      var $fields = $('.mup_field_type option:selected').map((i, e) => e.value).get();
      $fields.pop();
      if ($fields.includes('email') || $fields.includes('phone')) {
        return true;
      }
      return false;
    }, mailup_params.messages.must_have);

    $.validator.addMethod('invalid_char', function (value, element) {
      var re = new RegExp(/[\"[\]`';%&$]/);
      return !re.test(value);
    }, mailup_params.messages.invalid_char);
  });

  /*
   * When the window is loaded:
   */
  $(window).load(function () {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    if (urlParams.has('code')) {
      urlParams.delete('code');
      var clean_uri = window.location.toString().replace(queryString, '?' + urlParams.toString());
      window.history.replaceState({}, document.title, clean_uri);
    }

    var hash = window.location.hash;
    if (hash) {
      var cur_tab = hash.replace('tab', 'btn');
      $(cur_tab).trigger('click');
    }
    else {
      $('div.mailup-section-header__label:first').addClass('active');
      $('div.mailup-section-content__box:first').addClass('active');
    }

    $('#sel-group').on('input', function () {
      console.log($(this).valid());
      if ($(this).valid() == true) {
        console.log('call autocomplete');
        $(this).autocomplete({
          minLength: 1,
          source: function (request, response) {
            var list_id = this.element.parent().find('#lists option:selected').val();
            $.ajax({
              url: mailup_params.ajax_url,
              type: "POST",
              data: {
                action: "autocomplete_group",
                ajaxNonce: mailup_params.ajaxNonce,
                group: request.term,
                list_id: list_id,
              },
              success: function (res) {
                response(res.data);
              },
              error: function (xhr) {
                var res = xhr.responseJSON;
                console.log(res);
              }
            });
          }
        });
      }

    });

    $('form').on('submit', function (e) {
      var $form_id = $(this).attr('id');
      if ($form_id == 'mup-reset')
        return;

      e.preventDefault();
      if ($form_id == 'mailup-form-fields')
        prepareValidationFields($(this));

      if ($(this).valid()) {
        var parameters = {};
        parameters['form'] = prepareGeneralSettings($("form#mailup-form"));
        parameters.form['fields'] = prepareFormFields($("form#mailup-form-fields"));
        parameters['terms'] = prepareTerms($("form#mailup-form-fields"));
        parameters['messages'] = $("form#mailup-form-advanced-settings > .messages").find(":input").serializeArray();
        parameters['settings'] = prepareSettings($("form#mailup-form-advanced-settings"));

        submitForm(parameters, $(this));
      }
    });

    $(".mup_new_field_type").change(function (e) {

      var optionSelected = $("option:selected", this);
      var row_add = this.closest("tr");
      var sel_type = $(row_add).find("select.mup_field_type_type");
      var chk_required = $(row_add).find(".chk-required");
      var optionSelectedTextCapitalized = $.trim(optionSelected.text()).substr(0, 1).toUpperCase() + $.trim(optionSelected.text()).substr(1);
      var input = $(row_add).find(".label-field");
      $(input).attr("placeholder", optionSelectedTextCapitalized);
      $(input).val(optionSelectedTextCapitalized);

      if (optionSelected.val() != '' && !$.isNumeric(optionSelected.val())) {
        var field_value = optionSelected.val() == 'phone' ? 'number' : optionSelected.val();
        sel_type.val(field_value).prop('selected', true);
        sel_type.prop('disabled', true);
        chk_required.prop("checked", true);
        chk_required.prop("name", 'must_have');
      }
      else {
        sel_type.val('text').prop('selected', true);
        sel_type.removeAttr('disabled');
        chk_required.prop("name", '');
      }
    });

    $(".add-field").on("click", function (e) {
      var row_add = this.closest("tr");
      var row_add_sel = $(row_add).find("select.mup_field_type");
      var row_required = $(row_add).find(".chk-required");
      var $ix_last_field = $('.custom-fields tr.data-row-field').length;

      $("#new_field-error").remove();

      row_add_sel.rules("add", "required");
      row_add_sel.rules("remove", "must_have_field");

      if (!row_add_sel.valid()) {
        return;
      }

      var $tr = $(row_add).clone(true, true);

      $(this).attr("value", mailup_params.fields_text.remove);
      $(this).attr("name", "remove-field");
      row_add_sel.removeClass('mup_new_field_type');
      row_required.prop('id', row_required.prop('id').replace(/.$/, $ix_last_field));
      row_required.closest('label').prop('for', row_required.prop('id'));
      row_add_sel.prop("disabled", true);
      $(this).unbind();
      $(this).bind("click", $(this), removeField);

      var $options = $("select.mup_field_type")
        .map(function () {
          return this.value;
        })
        .get();

      $.each($options, function (index, value) {
        $($tr)
          .find(".mup_field_type option[value='" + value + "']")
          .remove();
      });
      var reqs = $(".chk-required:not(:last)");
      var name_req = $(reqs[reqs.length - 1]).attr('id');
      if (!name_req) {
        name_req = 'req_0';
      }
      name_req.replace(/.$/, parseInt(name_req.slice(name_req.length - 1)) + 1);
      var chk_req = $($tr).find('input[type="checkbox"]');
      $($tr).find(".mup_field_type").attr("name", 'new_field');
      $(chk_req).removeAttr("checked");
      $(chk_req).prop("name", name_req);
      $(chk_req).parent().prop("for", name_req);
      $($tr).find(".label-field").prop("value", "");
      $($tr).find('.mup_field_type_type').removeAttr('disabled');
      $(".custom-fields tr:last").after($tr);
      $(".custom-fields tr:last").find("select").trigger("change");
    });

    $(".remove-field").on("click", function (e) {
      removeField(e);
    });

    $('.chk-required').on('change', function (e) {
      if ($(this).is(':checked')) {
        $(this).closest('tr').find('.chk-show').prop("checked", true);
      }
    });

    $('.chk-show').on('change', function (e) {
      if ($(this).not(':checked')) {
        $(this).closest('tr').find('.chk-required').prop("checked", false);
      }
    });

    function removeField(e) {
      var element = e.target;
      var $el_removed = $(element).closest("tr").find(".mup_field_type option:selected");

      $(element).closest("tr").remove();
      var $new_option = $(".custom-fields tr:last").find(".mup_field_type option");

      var $ix_opt =
        $el_removed.attr("value") > 1 ? $el_removed.attr("value") - 1 : 1;
      $new_option
        .eq($ix_opt)
        .before(
          $("<option></option>")
            .val($el_removed.attr("value"))
            .text($el_removed.text())
        );
    }
  });

  function prepareValidationFields($form) {
    var last_row_select_ID = $form.find(".form-table > tbody > tr.data-row-field:last-child > td > select.mup_new_field_type");
    $(last_row_select_ID).rules("remove", "required");
    $(last_row_select_ID).rules("add", "must_have_field");
    $(last_row_select_ID).removeClass("error");
  }

  function prepareGeneralSettings($form) {
    var form = {};
    form["list_id"] = $form.find("#lists option:selected").val();
    form["group"] = $form.find("#sel-group").val();
    form["title"] = $form.find("#title-form").val();
    form["description"] = $form.find('#form-description').val();
    form['submit_text'] = $form.find("#submit-text").val();
    return form;
  }

  function prepareFormFields($form) {
    var $fields = [];
    var table_fields = $form.find("table.custom-fields > tbody > tr:not(:first,:last-child)");
    $(table_fields).each(function (
      index,
      tr
    ) {
      var $required_b = $(tr).find("input.chk-required").prop("checked");
      var $sel_id_row = $(tr).find(".mup_field_type option:selected");
      var $sel_type_row = $(tr).find(".mup_field_type_type option:selected");
      var $name_field = $(tr).find(".label-field");

      var $field = {
        id: $sel_id_row.attr("value"),
        name:
          $name_field.prop("value") === ""
            ? $sel_id_row.text()
            : $name_field.prop("value"),
        required: $required_b,
        type: $sel_type_row.prop("value"),
      };
      $fields.push($field);

    });

    return $fields;
  }

  function prepareTerms($form) {
    var $box_terms = $form.find('.terms-and-condition');
    var $terms = [];
    $box_terms.each(function (index, elem) {
      $terms.push(
        {
          'id': index + 1,
          'show': Boolean($(elem).find(".chk-show").prop("checked")),
          'required': Boolean($(elem).find(".chk-required").prop("checked")),
          'text': $(elem).find('.wp-editor-area').val(),
        }
      )
    });

    return $terms;

  }

  function prepareSettings($form) {
    return {
      'confirm': $form.find("input#email-comfirmation").prop("checked"),
      'placeholder': $form.find("input#placeholders-no-labels").prop("checked"),
      'custom_css': $form.find("textarea#custom-css").val(),
    };
  }

  function submitForm(params, form) {
    $.ajax({
      type: "POST",
      url: mailup_params.ajax_url,
      data: {
        action: "save_forms",
        ajaxNonce: mailup_params.ajaxNonce,
        form: params.form,
        terms: params.terms,
        messages: params.messages,
        settings: params.settings
      },
      beforeSend: function () {
        form.find('.spinner').addClass('is-active');
        form.find(".feedback").removeClass('error');
        $(":submit", form).prop('disabled', true);
      },
      success: function (res) {
        form.find(".feedback").text(res.data);
        $("form#mailup-form-fields").find('.info > b > span').text($('#sel-group').val());

      },
      error: function (xhr) {
        var res = xhr.responseJSON;
        form.find(".feedback").addClass('error').text(res.data);
      },
      complete: function (data) {
        form.find(".spinner").removeClass("is-active");
        $(":submit", form).prop('disabled', false);
        form.find(".feedback").fadeIn().delay(5000).queue(function (n) {
          $(this).fadeOut("slow");
          n();
        });
      }
    });
  }
})(jQuery);
