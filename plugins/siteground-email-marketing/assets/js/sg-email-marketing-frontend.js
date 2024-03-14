jQuery(document).ready(function ($) {
  $(".sg-marketing-form").submit(function (e) {
    const errorClass = "sg-marketing-form--error";

    e.preventDefault();

    var form = $(this);
    var formData = form.serialize();
    var originalHeight = form.height(); 
    var formFieldSet = form.find('fieldset')
    var _wpnonce = form.find('#_wpnonce').first();

    form.find("input").each(function () {
      window.handleInputValidation($(this));
    });

    if (form.find("input").is("." + errorClass)) {
      return;
    }
    $.ajax({
      type: "POST",
      url: ajaxData.url,
      data: {
        action: "sg_mail_marketing_form_submission",
        form_data: formData,
        wpnonce: _wpnonce.val(),
      },
      success: function (response) {
        form.find('.sg-marketing-form-submit_message--success').removeClass( 'sg-marketing-form-submit_message--hidden' );
        formFieldSet.remove()
        form.height(originalHeight);
        form.trigger("reset");
      },
      error: function (response) {
        form.find('.sg-marketing-form-submit_message--error').removeClass( 'sg-marketing-form-submit_message--hidden' );
        formFieldSet.remove()
        form.height(originalHeight);
        form.trigger("reset");
      },
    });
  });
});


jQuery(document).on('elementor/popup/show', (event, id, instance) => {
    jQuery('.elementor-popup-modal .sg-marketing-form').each((index, formElement) => {
      var $form = jQuery(formElement);
      var errorClass = "sg-marketing-form--error";
      var originalHeight = $form.height();
      var formFieldSet = $form.find('fieldset');
      var _wpnonce = $form.find('#_wpnonce').first();

      // Attach the submit event to the form
      $form.submit(function (e) {
        e.preventDefault();

        $form.find("input").each(function () {
          window.handleInputValidation(jQuery(this));
        });

        if ($form.find("input").is("." + errorClass)) {
          return;
        }

        var formData = $form.serialize();

        jQuery.ajax({
          type: "POST",
          url: ajaxData.url,
          data: {
            action: "sg_mail_marketing_form_submission",
            form_data: formData,
            wpnonce: _wpnonce.val(),
          },
          success: function (response) {
            $form.find('.sg-marketing-form-submit_message--success').removeClass('sg-marketing-form-submit_message--hidden');
            formFieldSet.remove();
            $form.height(originalHeight);
            $form.trigger("reset");
          },
          error: function (response) {
            $form.find('.sg-marketing-form-submit_message--error').removeClass('sg-marketing-form-submit_message--hidden');
            formFieldSet.remove();
            $form.height(originalHeight);
            $form.trigger("reset");
          },
        });
      });

      // Add a class to avoid re-initializing the form
      $form.addClass('elementor-initialized');
    });
});