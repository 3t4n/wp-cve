jQuery(document).ready(function ($) {
  const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  const errorClass = "sg-marketing-form--error";
  const requiredFieldMessage = wpData.errors.default;
  const invalidEmailMessage = wpData.errors.email;

  function handleInputValidation($input) {
    if (!$input.parent().hasClass("sg-input-container")) {
      return;
    }

    const $sublabel = $input.siblings(".sg-marketing-form-sublabel");

    if ($input.val() === "") {
      $input.addClass(errorClass);
      $sublabel.addClass(errorClass).text(requiredFieldMessage);
    } else {
      $input.removeClass(errorClass);
      $sublabel.removeClass(errorClass).text("");
    }

    if ($input.attr("type") === "email" && !emailRegex.test($input.val())) {
      $input.addClass(errorClass);
      $sublabel.addClass(errorClass).text(invalidEmailMessage);
    }
  }

  function handleResize(entries, $button) {
    entries.forEach(function (entry) {
      const width = $(entry.target).width();
      const $target = $(entry.target);

      if ($target.hasClass("sg-marketing-form-container-column")) {
        $button.css("width", width <= 550 ? "100%" : "auto");
      } else if ($target.hasClass("sg-marketing-form-container-row")) {
        $button.css("width", width <= 540 ? "100%" : "auto");
      }
    });
  }

  function initializeForm($form) {
    const $container = $form.find(".sg-marketing-form-container");
    const $button = $container.find("button");
    const $inputs = $container.find("input");

    $inputs.on("input", function () {
      handleInputValidation($(this));
    });

    const resizeObserver = new ResizeObserver(function (entries) {
      handleResize(entries, $button);
    });

    resizeObserver.observe($container.get(0));
  }

  // Check for form existence every 100ms
  const checkExist = setInterval(function () {
    const $forms = $(".sg-marketing-form");
    if ($forms.length) {
      $forms.each(function () {
        initializeForm($(this));
      });
      clearInterval(checkExist);
    }

    window.handleInputValidation = handleInputValidation;
  }, 100);
});
