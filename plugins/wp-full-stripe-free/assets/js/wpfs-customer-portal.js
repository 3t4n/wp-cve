jQuery.noConflict();
(function ($) {
  "use strict";

  $(function () {
    const INVOICE_DISPLAY_MODE_FEW = 0;
    const INVOICE_DISPLAY_MODE_HEAD = 1;
    const INVOICE_DISPLAY_MODE_ALL = 2;

    var reCAPTCHAWidgetId = null;
    var googleReCAPTCHA = null;
    var debugLog = false;

    window.addEventListener(
      "load",
      function () {
        var emailFormCAPTCHA = document.getElementById(
          "wpfs-enter-email-address-form-recaptcha"
        );
        //noinspection JSUnresolvedVariable
        if (window.grecaptcha !== "undefined" && emailFormCAPTCHA !== null) {
          //noinspection JSUnresolvedVariable
          googleReCAPTCHA = window.grecaptcha;
          //noinspection JSUnresolvedVariable
          var parameters = {
            sitekey: wpfsCustomerPortalSettings.googleReCaptchaSiteKey,
          };
          reCAPTCHAWidgetId = googleReCAPTCHA.render(
            emailFormCAPTCHA,
            parameters
          );
        }
      },
      true
    );

    function scrollToElement($anElement) {
      if ($anElement && $anElement.offset() && $anElement.offset().top) {
        $("html, body").animate(
          {
            scrollTop: $anElement.offset().top - 100,
          },
          1000
        );
      }
    }

    function logError(handlerName, jqXHR, textStatus, errorThrown) {
      if (window.console) {
        console.log(handlerName + ".error(): textStatus=" + textStatus);
        console.log(handlerName + ".error(): errorThrown=" + errorThrown);
        if (jqXHR) {
          console.log(handlerName + ".error(): jqXHR.status=" + jqXHR.status);
          console.log(
            handlerName + ".error(): jqXHR.responseText=" + jqXHR.responseText
          );
        }
      }
    }

    function resetCaptcha() {
      if (googleReCAPTCHA != null && reCAPTCHAWidgetId != null) {
        googleReCAPTCHA.reset(reCAPTCHAWidgetId);
      }
    }

    function showLoadingIcon($form) {
      $form.find("button").addClass("wpfs-btn-primary--loader");
    }

    function hideLoadingIcon($form) {
      $form.find("button").removeClass("wpfs-btn-primary--loader");
    }

    function disableSubmitButton($form) {
      $form.find("button").prop("disabled", true);
    }

    function enableSubmitButton($form) {
      $form.find("button").prop("disabled", false);
    }

    function handleSetupIntentAction($form, card, data) {
      if (stripe != null) {
        stripe
          .handleCardSetup(data.setupIntentClientSecret)
          .then(function (result) {
            // console.log('handleSetupIntentAction(): result=' + JSON.stringify(result));
            if (result.error) {
              logError(
                "handleSetupIntentAction",
                null,
                result.error.message,
                result.error
              );
              showFormFeedBackError($form, result.error.message);
            } else {
              disableSubmitButton($form);
              showLoadingIcon($form);
              submitCardData(
                $form,
                card,
                result.setupIntent.payment_method,
                result.setupIntent.id
              );
            }
          });
      }
    }

    function getCacheFriendlyUrlPath(path) {
      return path + "?t=" + Date.now();
    }

    function submitCardData($form, card, paymentMethodId, setupIntentId) {
      // console.log('submitCardData(): CALLED, params: paymentMethodId=' + paymentMethodId + ', setupIntentId=' + setupIntentId);
      clearFormFeedBack($form);
      clearFieldErrors($form);
      $.ajax({
        type: "POST",
        url: wpfsCustomerPortalSettings.ajaxUrl,
        data: {
          action: "wp_full_stripe_update_card",
          sessionId: wpfsCustomerPortalSettings.sessionData.sessionId,
          paymentMethodId: paymentMethodId,
          setupIntentId: setupIntentId,
        },
        cache: false,
        dataType: "json",
        success: function (data) {
          // console.log('submitCardData(): data=' + JSON.stringify(data));
          if (data.success) {
            showFormFeedBackSuccess($form, data.message);
            setTimeout(function () {
              window.location = getCacheFriendlyUrlPath(
                window.location.pathname
              );
            }, 1000);
          } else if (data.requiresAction) {
            handleSetupIntentAction($form, card, data);
          } else if (data.ex_message) {
            showFormFeedBackError($form, data.ex_message);
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          logError("submitCardData", jqXHR, textStatus, errorThrown);
        },
        complete: function () {
          hideLoadingIcon($form);
          enableSubmitButton($form);
        },
      });
    }

    function showFieldError($field, fieldErrorMessage) {
      $field.addClass("wpfs-form-control--error");
      var $fieldError = $("<div>", {
        class: "wpfs-form-error-message",
      }).html(fieldErrorMessage);
      $fieldError.insertAfter($field);
    }

    function clearFieldErrors($form) {
      $(".wpfs-form-control--error", $form).removeClass(
        "wpfs-form-control--error"
      );
      $("div.wpfs-form-error-message", $form).remove();
    }

    function getParentForm(element) {
      return $(element).parents("form:first");
    }

    function clearFormFeedBack($form) {
      var $formFeedBack = $form.prev(".wpfs-form-message");
      if ($formFeedBack.length > 0) {
        $formFeedBack.remove();
      }
    }

    function showFormFeedBackSuccess($form, message) {
      var $formFeedBack = $("<div>", {
        class:
          "wpfs-form-message wpfs-form-message--correct wpfs-form-message--sm-icon ",
      }).html(message);
      $formFeedBack.insertBefore($form);
    }

    function showFormFeedBackError($form, message) {
      var $formFeedBack = $("<div>", {
        class:
          "wpfs-form-message wpfs-form-message--incorrect wpfs-form-message--sm-icon ",
      }).html(message);
      $formFeedBack.insertBefore($form);
    }

    function updateCancelSubscriptionSubmitButton() {
      var selectedSubscriptionCount = $(
        ".wpfs-form-check-input:checked"
      ).length;
      var cancelSubscriptionSubmitButtonCaption = null;
      if (selectedSubscriptionCount > 0) {
        $("#wpfs-button-cancel-subscription").prop("disabled", false);
        if (selectedSubscriptionCount == 1) {
          //noinspection JSUnresolvedVariable
          if (wpfsCustomerPortalSettings.sessionData !== "undefined") {
            //noinspection JSUnresolvedVariable
            cancelSubscriptionSubmitButtonCaption =
              wpfsCustomerPortalSettings.sessionData.i18n
                .cancelSubscriptionSubmitButtonCaptionSingular;
            $("#wpfs-button-cancel-subscription").html(
              cancelSubscriptionSubmitButtonCaption
            );
          }
        } else {
          //noinspection JSUnresolvedVariable
          if (wpfsCustomerPortalSettings.sessionData !== "undefined") {
            //noinspection JSUnresolvedVariable
            cancelSubscriptionSubmitButtonCaption = vsprintf(
              wpfsCustomerPortalSettings.sessionData.i18n
                .cancelSubscriptionSubmitButtonCaptionPlural,
              [selectedSubscriptionCount]
            );
            $("#wpfs-button-cancel-subscription").html(
              cancelSubscriptionSubmitButtonCaption
            );
          }
        }
      } else {
        $("#wpfs-button-cancel-subscription").prop("disabled", true);
        //noinspection JSUnresolvedVariable
        if (wpfsCustomerPortalSettings.sessionData !== "undefined") {
          //noinspection JSUnresolvedVariable
          cancelSubscriptionSubmitButtonCaption =
            wpfsCustomerPortalSettings.sessionData.i18n
              .cancelSubscriptionSubmitButtonCaptionDefault;
          $("#wpfs-button-cancel-subscription").html(
            cancelSubscriptionSubmitButtonCaption
          );
        }
      }
    }

    //noinspection JSUnresolvedVariable
    var stripe = null;
    try {
      if (
        wpfsCustomerPortalSettings.stripeAccountId !== null &&
        wpfsCustomerPortalSettings.stripeAccountId.trim() !== ""
      ) {
        stripe = Stripe(wpfsCustomerPortalSettings.stripeKey, {
          stripeAccount: wpfsCustomerPortalSettings.stripeAccountId,
        });
      } else {
        stripe = Stripe(wpfsCustomerPortalSettings.stripeKey);
      }
    } catch (err) {
      var $form = $("#wpfs-default-card-form");
      var message = vsprintf(
        wpfsCustomerPortalSettings.sessionData.i18n
          .stripeInstantiationErrorMessage,
        [err.message]
      );
      showFormFeedBackError($form, message);
      scrollToElement($form);
      console.log("Cannot instantiate Stripe: " + err.message);
    }

    var WPFS = {};

    WPFS.initSelectmenu = function () {
      $.widget("custom.wpfsSelectmenu", $.ui.selectmenu, {
        _renderItem: function (ul, item) {
          var $li = $("<li>");
          var wrapper = $("<div>", {
            class: "menu-item-wrapper ui-menu-item-wrapper",
            text: item.label,
          });

          if (item.disabled) {
            $li.addClass("ui-state-disabled");
          }

          return $li.append(wrapper).appendTo(ul);
        },
      });

      var $selectmenus = $('[data-toggle="selectmenu"]');
      $selectmenus.each(function () {
        if (typeof $(this).select2 === "function") {
          try {
            $(this).select2("destroy");
          } catch (err) {}
        }

        var $selectmenu = $(this).wpfsSelectmenu({
          classes: {
            "ui-selectmenu-button": "wpfs-form-control wpfs-selectmenu-button",
            "ui-selectmenu-menu": "wpfs-ui wpfs-selectmenu-menu",
          },
          icons: {
            button: "wpfs-icon-arrow",
          },
          create: function () {
            var $this = $(this);
            var $selectMenuButton = $this.next();
            $selectMenuButton.addClass($this.attr("class"));
            if ($this.find("option:selected:disabled").length > 0) {
              $selectMenuButton.addClass("ui-state-placeholder");
            }
          },
          open: function () {
            var $this = $(this);
            var $button = $this.data("custom-wpfsSelectmenu").button;
            $button.removeClass("ui-selectmenu-button-closed");
            $button.addClass("ui-selectmenu-button-open");
            var selectedClass = "ui-state-selected";
            var selectedIndex = $this
              .find("option")
              .index($this.find("option:selected"));
            $(".ui-selectmenu-open .ui-menu-item-wrapper").removeClass(
              selectedClass
            );
            var $menuItem = $(".ui-selectmenu-open .ui-menu-item").eq(
              selectedIndex
            );
            if (!$menuItem.hasClass("ui-state-disabled")) {
              $menuItem.find(".ui-menu-item-wrapper").addClass(selectedClass);
            }
          },
          close: function () {
            var $this = $(this);
            var $button = $this.data("custom-wpfsSelectmenu").button;
            $button.removeClass("ui-selectmenu-button-open");
            $button.addClass("ui-selectmenu-button-closed");
          },
          change: function () {
            var $this = $(this);
            var $button = $(this).data("custom-wpfsSelectmenu").button;
            $button.removeClass("ui-state-placeholder");
            $this.trigger("selectmenuchange");
          },
        });

        var $selectmenuParent = $selectmenu.parent();
        $selectmenuParent
          .find(".ui-selectmenu-button")
          .addClass("wpfs-form-control")
          .addClass("wpfs-selectmenu-button")
          .addClass("ui-button");

        $selectmenu
          .data("custom-wpfsSelectmenu")
          .menuWrap.addClass("wpfs-ui")
          .addClass("wpfs-selectmenu-menu");
      });
    };
    WPFS.initStepper = function () {
      var $stepper = $('[data-toggle="stepper"]');
      $stepper.each(function () {
        var $this = $(this);
        var defaultValue = $this.data("defaultValue") || 1;

        if ($this.val() === "") {
          $this.val(defaultValue);
        }

        $this
          .spinner({
            min: $this.data("min") || 1,
            max: $this.data("max") || 9999,
            icons: {
              down: "wpfs-icon-decrease",
              up: "wpfs-icon-increase",
            },
            checkButtons: function ($this, currentValue) {
              var uiSpinner = $this.data("uiSpinner");
              var min = uiSpinner.options.min;
              var max = uiSpinner.options.max;
              var $container = $this.parent();
              var disabledClassName = "ui-state-disabled";
              var up = $container.find(".ui-spinner-up");
              var down = $container.find(".ui-spinner-down");

              up.removeClass(disabledClassName);
              down.removeClass(disabledClassName);

              if (currentValue === max) {
                up.addClass(disabledClassName);
              }

              if (currentValue === min) {
                down.addClass(disabledClassName);
              }
            },
            change: function (e, ui) {
              var $this = $(this);
              if ($this.spinner("isValid")) {
                defaultValue = $this.val();
              } else {
                $this.val(defaultValue);
              }
              $this
                .spinner("instance")
                .options.checkButtons($this, $this.val());
            },
            spin: function (e, ui) {
              var $this = $(this);
              $this.spinner("instance").options.checkButtons($this, ui.value);
            },
          })
          .parent()
          .find(".ui-icon")
          .text("");
      });
    };
    WPFS.initEnterEmailAddressForm = function () {
      $("#wpfs-enter-email-address-form").submit(function (e) {
        e.preventDefault();

        var $form = $(this);

        clearFieldErrors($form);
        disableSubmitButton($form);
        showLoadingIcon($form);

        var emailAddress = $form.find('input[name="wpfs-email-address"]').val();
        var googleReCAPTCHAResponse = $form
          .find('textarea[name="g-recaptcha-response"]')
          .val();

        $.ajax({
          type: "POST",
          url: wpfsCustomerPortalSettings.ajaxUrl,
          data: {
            action: "wp_full_stripe_create_card_update_session",
            emailAddress: emailAddress,
            googleReCAPTCHAResponse: googleReCAPTCHAResponse,
          },
          cache: false,
          dataType: "json",
          success: function (data) {
            if (data.success) {
              window.location = getCacheFriendlyUrlPath(
                window.location.pathname
              );
            } else {
              var $field;
              if (data.fieldError && "emailAddress" === data.fieldError) {
                $field = $('input[name="wpfs-email-address"]', $form);
              } else if (
                data.fieldError &&
                "googleReCAPTCHAResponse" === data.fieldError
              ) {
                $field = $(
                  "div#wpfs-enter-email-address-form-recaptcha",
                  $form
                );
              }
              showFieldError($field, data.message);
              resetCaptcha();
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            logError(
              "wpfs-enter-email-address-form.submit",
              jqXHR,
              textStatus,
              errorThrown
            );
          },
          complete: function () {
            enableSubmitButton($form);
            hideLoadingIcon($form);
          },
        });

        return false;
      });
    };
    WPFS.initEnterSecurityCodeForm = function () {
      $(".wpfs-nav-back-to-email-address").click(function (e) {
        e.preventDefault();

        var $form = getParentForm(this);

        disableSubmitButton($form);
        showLoadingIcon($form);

        $.ajax({
          type: "POST",
          url: wpfsCustomerPortalSettings.ajaxUrl,
          data: {
            action: "wp_full_stripe_reset_card_update_session",
            sessionId: wpfsCustomerPortalSettings.sessionData.sessionId,
          },
          cache: false,
          dataType: "json",
          success: function (data) {
            window.location = getCacheFriendlyUrlPath(window.location.pathname);
          },
          error: function (jqXHR, textStatus, errorThrown) {
            logError(
              ".wpfs-nav-back-to-email-address.click",
              jqXHR,
              textStatus,
              errorThrown
            );
          },
          complete: function () {
            enableSubmitButton($form);
            hideLoadingIcon($form);
          },
        });

        return false;
      });
      $("#wpfs-enter-security-code-form").submit(function (e) {
        e.preventDefault();

        var $form = $(this);

        disableSubmitButton($form);
        clearFieldErrors($form);
        showLoadingIcon($form);

        var securityCode = $('input[name="wpfs-security-code"]', $form).val();

        //noinspection JSUnresolvedVariable
        $.ajax({
          type: "POST",
          url: wpfsCustomerPortalSettings.ajaxUrl,
          data: {
            action: "wp_full_stripe_validate_security_code",
            sessionId: wpfsCustomerPortalSettings.sessionData.sessionId,
            securityCode: securityCode,
          },
          cache: false,
          dataType: "json",
          success: function (data) {
            if (data.success) {
              window.location = getCacheFriendlyUrlPath(
                window.location.pathname
              );
            } else {
              showFieldError(
                $('input[name="wpfs-security-code"]', $form),
                data.message
              );
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            logError(
              "#wpfs-enter-security-code-form.submit",
              jqXHR,
              textStatus,
              errorThrown
            );
          },
          complete: function () {
            enableSubmitButton($form);
            hideLoadingIcon($form);
          },
        });

        return false;
      });
    };
    WPFS.initUpdateCardForm = function () {
      // init stripe payment element
      var $cards = $('#wpfs-update-card-form [data-toggle="card"]');

      if ($cards.length === 0) {
        return;
      }

      $cards.each(async function (index) {
        var cardElement;
        if (stripe != null) {
          // tnagy get references for form, formId
          var $form = getParentForm(this);
          var elementsLocale = $form.data("wpfs-preferred-language");

          const clientSecret = await getSetupIntentClientSecret();

          var appearance = {
            theme: $form.data("wpfs-elements-theme"),
          };

          if ($form.data("wpfs-elements-font")) {
            appearance = {
              ...appearance,
              variables: {
                fontFamily: $form.data("wpfs-elements-font"),
              },
            };
          }
          // create Stripe Payments Element
          var elements = stripe.elements({
            locale: elementsLocale,
            loader: "always",
            clientSecret,
            appearance,
          });
          cardElement = elements.create("payment");
          cardElement.mount('#wpfs-update-card-form [data-toggle="card"]');

          $("#wpfs-anchor-logout").click(function (e) {
            e.preventDefault();
            //noinspection JSUnresolvedVariable
            $.ajax({
              type: "POST",
              url: wpfsCustomerPortalSettings.ajaxUrl,
              data: {
                action: "wp_full_stripe_reset_card_update_session",
                sessionId: wpfsCustomerPortalSettings.sessionData.sessionId,
              },
              cache: false,
              dataType: "json",
              success: function (data) {
                window.location = getCacheFriendlyUrlPath(
                  window.location.pathname
                );
              },
              error: function (jqXHR, textStatus, errorThrown) {
                logError(
                  "#wpfs-anchor-logout.click",
                  jqXHR,
                  textStatus,
                  errorThrown
                );
              },
              complete: function () {},
            });
          });
          $("#wpfs-anchor-update-card").click(function () {
            if (cardElement != null) {
              cardElement.clear();
            }
            $("#wpfs-default-card-form").hide();
            $("#wpfs-update-card-form").show();
            cardElement.focus();
          });
          $("#wpfs-anchor-discard-card-changes").click(function () {
            if (cardElement != null) {
              cardElement.clear();
            }
            $("#wpfs-default-card-form").show();
            $("#wpfs-update-card-form").hide();
          });
          $("#wpfs-update-card-form").submit(function (e) {
            e.preventDefault();

            var $form = $(this);

            disableSubmitButton($form);
            showLoadingIcon($form);
            clearFormFeedBack($form);

            if (debugLog) {
              console.log("form.submit(): " + "Creating PaymentMethod...");
            }

            if (stripe != null) {
              stripe
                .createPaymentMethod("card", cardElement, {})
                .then(function (createPaymentMethodResult) {
                  if (debugLog) {
                    console.log(
                      "form.submit(): " +
                        "PaymentMethod creation result=" +
                        JSON.stringify(createPaymentMethodResult)
                    );
                  }
                  clearFieldErrors($form);
                  if (createPaymentMethodResult.error) {
                    enableSubmitButton($form);
                    hideLoadingIcon($form);
                    showFieldError(
                      $("#wpfs-card", $form),
                      createPaymentMethodResult.error.message
                    );
                  } else {
                    var paymentMethodId = null;
                    if (
                      typeof createPaymentMethodResult !== "undefined" &&
                      createPaymentMethodResult.hasOwnProperty(
                        "paymentMethod"
                      ) &&
                      createPaymentMethodResult.paymentMethod.hasOwnProperty(
                        "id"
                      )
                    ) {
                      paymentMethodId =
                        createPaymentMethodResult.paymentMethod.id;
                    }
                    submitCardData($form, cardElement, paymentMethodId);
                  }
                });
            } else {
              // todo tnagy show error message
            }

            return false;
          });
        } else {
          // todo tnagy show error message
        }
      });
    };

    function attachCancelSubscriptionFormEvents() {
      $("#wpfs-cancel-subscription-form").submit(function (e) {
        e.preventDefault();

        var $form = $(this);

        disableSubmitButton($form);
        showLoadingIcon($form);
        clearFormFeedBack($form);

        // tnagy create form data array
        var data = $form.serializeArray();
        // tnagy add action and session ID
        data.push({
          name: "action",
          value: "wp_full_stripe_cancel_my_subscription",
        });
        //noinspection JSUnresolvedVariable
        data.push({
          name: "sessionId",
          value: wpfsCustomerPortalSettings.sessionData.sessionId,
        });

        // tnagy collect selected subscription IDs
        var selectedSubscriptionIds = [];
        for (var i = 0; i < data.length; i++) {
          var item = data[i];
          if (item && item.name && item.name == "wpfs-subscription-id[]") {
            selectedSubscriptionIds.push(item.value);
          }
        }

        // tnagy validate selection
        var valid = true;
        if (selectedSubscriptionIds.length == 0) {
          valid = false;
          //noinspection JSUnresolvedVariable
          showFormFeedBackError(
            $form,
            wpfsCustomerPortalSettings.sessionData.i18n
              .selectAtLeastOneSubscription
          );
        }

        if (valid) {
          //noinspection JSUnresolvedVariable
          var confirmationResult = confirm(
            wpfsCustomerPortalSettings.sessionData.i18n
              .confirmSubscriptionCancellationMessage
          );

          if (confirmationResult == true) {
            //noinspection JSUnresolvedVariable
            $.ajax({
              type: "POST",
              url: wpfsCustomerPortalSettings.ajaxUrl,
              data: $.param(data),
              cache: false,
              dataType: "json",
              success: function (data) {
                if (data.success) {
                  showFormFeedBackSuccess($form, data.message);
                  setTimeout(function () {
                    window.location = getCacheFriendlyUrlPath(
                      window.location.pathname
                    );
                  }, 1000);
                } else {
                  showFormFeedBackError($form, data.message);
                }
              },
              error: function (jqXHR, textStatus, errorThrown) {
                logError(
                  "#wpfs-cancel-subscription-form.submit",
                  jqXHR,
                  textStatus,
                  errorThrown
                );
              },
              complete: function () {
                enableSubmitButton($form);
                hideLoadingIcon($form);
              },
            });
          } else {
            enableSubmitButton($form);
            hideLoadingIcon($form);
          }
        } else {
          enableSubmitButton($form);
          hideLoadingIcon($form);
        }

        return false;
      });
    }

    WPFS.initCancelSubscriptionForm = function () {
      if (
        wpfsCustomerPortalSettings.preferences.letSubscribersCancelSubscriptions
      ) {
        attachCancelSubscriptionFormEvents();
      } else {
        $("#wpfs-subscriptions-actions").hide();
      }
    };

    function submitInvoiceViewToggle() {
      $.ajax({
        type: "POST",
        url: wpfsCustomerPortalSettings.ajaxUrl,
        data: {
          action: "wp_full_stripe_toggle_invoice_view",
          sessionId: wpfsCustomerPortalSettings.sessionData.sessionId,
        },
        cache: false,
        dataType: "json",
        success: function (data) {
          if (data.success) {
            window.location = getCacheFriendlyUrlPath(window.location.pathname);
          } else {
            // How we should display invoice view toggle errors?
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          logError("submitCardData", jqXHR, textStatus, errorThrown);
        },
        complete: function () {
          // Nop so far
        },
      });
    }

    WPFS.initManagedSubscriptions = function () {
      function initCheckboxEventHandlers() {
        $(".wpfs-form-check-input")
          .off("change")
          .on("change", function (e) {
            updateCancelSubscriptionSubmitButton();
          })
          .change();
      }

      var WPFS_MS = {};
      WPFS_MS.debugMode = false;
      WPFS_MS.isDebugEnabled = function () {
        return WPFS_MS.debugMode;
      };

      if (
        wpfsCustomerPortalSettings.preferences.showSubscriptionsSection &&
        $("#wpfs-subscriptions-table").length == 1
      ) {
        WPFS_MS.Subscription = Backbone.Model.extend({
          defaults: {
            id: "",
            idAttribute: "",
            nameAttribute: "",
            planId: "",
            planName: "",
            planQuantity: 1,
            allowMultipleSubscriptions: false,
            maximumPlanQuantity: 0,
            planLabel: "",
            status: "",
            statusClass: "",
            priceAndIntervalLabel: "",
            summaryLabelSingular: "",
            summaryLabelPlural: "",
            created: "",
            newPlanId: "",
            availablePlans: [],
          },
        });
        WPFS_MS.SubscriptionList = Backbone.Collection.extend({
          model: WPFS_MS.Subscription,
          url: function () {
            return (
              wpfsCustomerPortalSettings.restUrl +
              "wp-full-stripe/v1" +
              "/manage-subscriptions" +
              "/subscription"
            );
          },
        });
        WPFS_MS.subscriptionList = new WPFS_MS.SubscriptionList(
          wpfsCustomerPortalSettings.sessionData.stripe.subscriptions
        );
        WPFS_MS.UpdateSubscriptionSuccessMessageView = Backbone.View.extend({
          tagName: "div",
          className:
            "wpfs-form-message wpfs-form-message--correct wpfs-form-message--sm-icon",
          template: _.template(
            $("#wpfs-subscription-update-success-message").html()
          ),
          render: function () {
            this.$el.html(this.template());
            return this;
          },
        });
        WPFS_MS.UpdateSubscriptionErrorMessageView = Backbone.View.extend({
          tagName: "div",
          className:
            "wpfs-form-message wpfs-form-message--incorrect wpfs-form-message--sm-icon",
          template: _.template(
            $("#wpfs-subscription-update-error-message").html()
          ),
          render: function () {
            this.$el.html(this.template());
            return this;
          },
        });
        WPFS_MS.CancelSubscriptionSuccessMessageView = Backbone.View.extend({
          tagName: "div",
          className:
            "wpfs-form-message wpfs-form-message--correct wpfs-form-message--sm-icon",
          template: _.template(
            $("#wpfs-subscription-cancel-success-message").html()
          ),
          render: function () {
            this.$el.html(this.template());
            return this;
          },
        });
        WPFS_MS.ActivateSubscriptionSuccessMessageView = Backbone.View.extend({
          tagName: "div",
          className:
            "wpfs-form-message wpfs-form-message--correct wpfs-form-message--sm-icon",
          template: _.template(
            $("#wpfs-subscription-activate-success-message").html()
          ),
          render: function () {
            this.$el.html(this.template());
            return this;
          },
        });
        WPFS_MS.CancelSubscriptionErrorMessageView = Backbone.View.extend({
          tagName: "div",
          className:
            "wpfs-form-message wpfs-form-message--incorrect wpfs-form-message--sm-icon",
          template: _.template(
            $("#wpfs-subscription-cancel-error-message").html()
          ),
          render: function () {
            this.$el.html(this.template());
            return this;
          },
        });
        WPFS_MS.ActivateSubscriptionErrorMessageView = Backbone.View.extend({
          tagName: "div",
          className:
            "wpfs-form-message wpfs-form-message--incorrect wpfs-form-message--sm-icon",
          template: _.template(
            $("#wpfs-subscription-activate-error-message").html()
          ),
          render: function () {
            this.$el.html(this.template());
            return this;
          },
        });

        WPFS_MS.EmptySubscriptionListView = Backbone.View.extend({
          tagName: "div",
          className: "wpfs-no-subscription",
          template: _.template(
            $("#wpfs-subscription-empty-subscription-list").html()
          ),
          render: function () {
            this.$el.html(this.template());
            return this;
          },
        });
        WPFS_MS.SubscriptionView = Backbone.View.extend({
          tagName: "div",
          className: "wpfs-subscription",
          template: _.template($("#wpfs-subscription-show-row").html()),
          updateSubscriptionTemplate: _.template(
            $("#wpfs-subscription-update-row").html()
          ),
          render: function () {
            var printPriceAndIntervalLabel = function (id) {
              console.log("baha");
            };
            var modelAsJSON = this.model.toJSON();
            modelAsJSON = _.extend(modelAsJSON, printPriceAndIntervalLabel);
            if (WPFS_MS.isDebugEnabled()) {
              console.log(
                "SubscriptionView.render(): modelAsJSON=" +
                  JSON.stringify(modelAsJSON)
              );
            }
            this.$el.html(this.template(modelAsJSON));
            return this;
          },
          initialize: function () {
            this.model.on("change", this.render, this);
          },
          events: {
            "click a.wpfs-subscription-update-action": "updateSubscription",
            "click a.wpfs-subscription-cancel-action": "cancelSubscription",
            "click a.wpfs-subscription-dont-cancel-action":
              "activateSubscription",
            "click button.wpfs-subscription-button-update":
              "saveSubscriptionUpdate",
            "click a.wpfs-subscription-link-cancel-update":
              "cancelUpdateSubscription",
          },
          updateSubscription: function (e) {
            function disableUpdateButton() {
              $(".wpfs-subscription-button-update").attr("disabled", true);
            }

            function enableUpdateButton() {
              $(".wpfs-subscription-button-update").attr("disabled", false);
            }

            function getPlanIdFromSelect(view) {
              var planId = view.model.get("planId");
              if (
                view.$el.find('select[name="wpfs-subscription-plan-name"]')
                  .length > 0
              ) {
                planId = view.$el
                  .find('select[name="wpfs-subscription-plan-name"]')
                  .val();
              }

              return planId;
            }

            function getPlanQuantityFromSpinner(view) {
              var quantity = 1;
              if (
                view.$el.find('input[name="wpfs-subscription-plan-quantity"]')
                  .length > 0
              ) {
                quantity = view.$el
                  .find('input[name="wpfs-subscription-plan-quantity"]')
                  .spinner("value");
              }

              return quantity;
            }

            function getSummaryLabelSingular(view) {
              var label;
              if (
                view.$el.find('select[name="wpfs-subscription-plan-name"]')
                  .length > 0
              ) {
                label = view.$el
                  .find(
                    'select[name="wpfs-subscription-plan-name"] option:selected'
                  )
                  .data("wpfs-plan-summary-label-singular");
              } else {
                label = view.$el
                  .find('input[name="wpfs-subscription-plan-current"]')
                  .data("wpfs-plan-summary-label-singular");
              }

              return label;
            }

            function getSummaryLabelPlural(view) {
              var label;
              if (
                view.$el.find('select[name="wpfs-subscription-plan-name"]')
                  .length > 0
              ) {
                label = view.$el
                  .find(
                    'select[name="wpfs-subscription-plan-name"] option:selected'
                  )
                  .data("wpfs-plan-summary-label-plural");
              } else {
                label = view.$el
                  .find('input[name="wpfs-subscription-plan-current"]')
                  .data("wpfs-plan-summary-label-plural");
              }

              return label;
            }

            function showPlanSummary(view, planQuantity) {
              var summaryLabel;
              if (planQuantity > 1) {
                summaryLabel = getSummaryLabelPlural(view).replace(
                  "@QUANTITY@",
                  planQuantity
                );
              } else {
                summaryLabel = getSummaryLabelSingular(view);
              }

              var $summary = view.$el.find(".wpfs-subscription-summary");
              var $summaryDescription = view.$el.find(
                ".wpfs-subscription-summary-description"
              );

              $summary.removeClass("wpfs-subscription-summary--higher");
              $summary.removeClass("wpfs-subscription-summary--lower");
              $summary.removeClass("wpfs-subscription-summary--hide");
              $summaryDescription.text(summaryLabel);
              $summaryDescription.show();
            }

            function hidePlanSummary(view) {
              var $summary = view.$el.find(".wpfs-subscription-summary");
              $summary.addClass("wpfs-subscription-summary--hide");
            }

            function refreshSubscriptionUpdatePane(view, plan, quantity) {
              var planOld = view.model.get("planId");
              var quantityOld = view.model.get("planQuantity");

              if (plan === planOld && quantity === quantityOld) {
                disableUpdateButton();
                hidePlanSummary(view);
              } else {
                enableUpdateButton();
                showPlanSummary(view, quantity);
              }
            }

            function onPlanChange(view) {
              return function (e) {
                var plan = getPlanIdFromSelect(view);
                var quantity = getPlanQuantityFromSpinner(view);
                refreshSubscriptionUpdatePane(view, plan, quantity);
              };
            }

            function onSpinChange(view) {
              return function (e, ui) {
                var plan = getPlanIdFromSelect(view);
                var quantity = getPlanQuantityFromSpinner(view);
                refreshSubscriptionUpdatePane(view, plan, quantity);
              };
            }

            function onSpin(view) {
              return function (e, ui) {
                var plan = getPlanIdFromSelect(view);
                var quantity = ui.value;
                refreshSubscriptionUpdatePane(view, plan, quantity);
              };
            }

            e.preventDefault();

            this.$el.addClass("wpfs-subscription--update-quantity");
            this.$el.html(this.updateSubscriptionTemplate(this.model.toJSON()));
            WPFS.initStepper();
            WPFS.initSelectmenu();

            this.$el
              .find('select[name="wpfs-subscription-plan-name"]')
              .on("selectmenuchange", onPlanChange(this));
            this.$el
              .find('[data-toggle="stepper"]')
              .on("spinchange", onSpinChange(this));
            this.$el.find('[data-toggle="stepper"]').on("spin", onSpin(this));

            disableUpdateButton();

            return this;
          },
          cancelUpdateSubscription: function (e) {
            e.preventDefault();
            this.$el.removeClass("wpfs-subscription--update-quantity");
            this.render();

            initCheckboxEventHandlers();

            return this;
          },
          saveSubscriptionUpdate: function (e) {
            function getPlanName(view) {
              var planName;
              if (
                view.$el.find(
                  'select[name="wpfs-subscription-plan-name"] option:selected'
                ).length > 0
              ) {
                planName = view.$el
                  .find(
                    'select[name="wpfs-subscription-plan-name"] option:selected'
                  )
                  .data("wpfs-plan-name");
              } else {
                planName = view.$el
                  .find('input[name="wpfs-subscription-plan-current"]')
                  .data("wpfs-plan-name");
              }

              return planName;
            }

            function getPriceAndIntervalLabel(view) {
              var label;
              if (
                view.$el.find(
                  'select[name="wpfs-subscription-plan-name"] option:selected'
                ).length > 0
              ) {
                label = view.$el
                  .find(
                    'select[name="wpfs-subscription-plan-name"] option:selected'
                  )
                  .data("wpfs-price-and-interval-label");
              } else {
                label = view.$el
                  .find('input[name="wpfs-subscription-plan-current"]')
                  .data("wpfs-price-and-interval-label");
              }

              return label;
            }

            function getPlanId(view) {
              var planId = view.model.get("planId");
              if (
                view.$el.find('select[name="wpfs-subscription-plan-name"]')
                  .length > 0
              ) {
                planId = view.$el
                  .find('select[name="wpfs-subscription-plan-name"]')
                  .val();
              }

              return planId;
            }

            function getPlanQuantityFromSpinner(view) {
              var quantity = 1;
              if (
                view.$el.find('input[name="wpfs-subscription-plan-quantity"]')
                  .length > 0
              ) {
                quantity = view.$el
                  .find('input[name="wpfs-subscription-plan-quantity"]')
                  .spinner("value");
              }

              return quantity;
            }

            e.preventDefault();
            $(".wpfs-form-message").remove();

            var newPlanId = getPlanId(this);
            var newPlanName = getPlanName(this);
            var newPriceAndIntervalLabel = getPriceAndIntervalLabel(this);
            var newPlanQuantity = getPlanQuantityFromSpinner(this);

            // tnagy update plan label to reflect changes properly in the model and view
            var newPlanLabel;
            if (newPlanQuantity > 1) {
              newPlanLabel = sprintf(
                "%d%s %s",
                newPlanQuantity,
                "x",
                newPlanName
              );
            } else {
              newPlanLabel = newPlanName;
            }
            var newAttributes = {
              newPlanId: newPlanId,
              planName: newPlanName,
              planQuantity: newPlanQuantity,
              planLabel: newPlanLabel,
              priceAndIntervalLabel: newPriceAndIntervalLabel,
            };
            this.model.save(newAttributes, {
              wait: true,
              success: function (model, response) {
                var successMessage =
                  new WPFS_MS.UpdateSubscriptionSuccessMessageView();
                $("#wpfs-subscriptions-subtitle").after(
                  successMessage.render().el
                );
                setTimeout(function () {
                  window.location = getCacheFriendlyUrlPath(
                    window.location.pathname
                  );
                }, 1000);
              },
              error: function (model, error) {
                console.log(
                  "SubscriptionView.model.save().error(): CALLED, error=" +
                    error +
                    ", model=" +
                    JSON.stringify(model)
                );
                var errorMessage =
                  new WPFS_MS.UpdateSubscriptionErrorMessageView();
                $("#wpfs-subscriptions-subtitle").after(
                  errorMessage.render().el
                );
              },
            });

            this.render();
            this.$el.removeClass("wpfs-subscription--update-quantity");

            return this;
          },
          cancelSubscription: function (e) {
            e.preventDefault();
            $(".wpfs-form-message").remove();

            //noinspection JSUnresolvedVariable
            var confirmationResult = confirm(
              wpfsCustomerPortalSettings.sessionData.i18n
                .confirmSingleSubscriptionCancellationMessage
            );

            if (confirmationResult == true) {
              var newAttributes = {
                action: "cancel",
              };
              this.model.save(newAttributes, {
                wait: true,
                success: function (model, response) {
                  var successMessage =
                    new WPFS_MS.CancelSubscriptionSuccessMessageView();
                  $("#wpfs-subscriptions-subtitle").after(
                    successMessage.render().el
                  );
                  setTimeout(function () {
                    window.location = getCacheFriendlyUrlPath(
                      window.location.pathname
                    );
                  }, 1000);
                },
                error: function (model, error) {
                  console.log(
                    "SubscriptionView.model.save().error(): CALLED, error=" +
                      error +
                      ", model=" +
                      JSON.stringify(model)
                  );
                  var errorMessage =
                    new WPFS_MS.CancelSubscriptionErrorMessageView();
                  $("#wpfs-subscriptions-subtitle").after(
                    errorMessage.render().el
                  );
                },
              });

              this.render();
            }

            return this;
          },
          activateSubscription: function (e) {
            e.preventDefault();
            $(".wpfs-form-message").remove();

            //noinspection JSUnresolvedVariable
            var confirmationResult = confirm(
              wpfsCustomerPortalSettings.sessionData.i18n
                .confirmSingleSubscriptionActivationMessage
            );

            if (confirmationResult == true) {
              var newAttributes = {
                action: "activate",
              };
              this.model.save(newAttributes, {
                wait: true,
                success: function (model, response) {
                  var successMessage =
                    new WPFS_MS.ActivateSubscriptionSuccessMessageView();
                  $("#wpfs-subscriptions-subtitle").after(
                    successMessage.render().el
                  );
                  setTimeout(function () {
                    window.location = getCacheFriendlyUrlPath(
                      window.location.pathname
                    );
                  }, 1000);
                },
                error: function (model, error) {
                  console.log(
                    "SubscriptionView.model.save().error(): CALLED, error=" +
                      error +
                      ", model=" +
                      JSON.stringify(model)
                  );
                  var errorMessage =
                    new WPFS_MS.ActivateSubscriptionErrorMessageView();
                  $("#wpfs-subscriptions-subtitle").after(
                    errorMessage.render().el
                  );
                },
              });

              this.render();
            }

            return this;
          },
        });
        WPFS_MS.SubscriptionsTableView = Backbone.View.extend({
          initialize: function () {
            WPFS_MS.subscriptionList.on("add", this.addOne, this);
            WPFS_MS.subscriptionList.on("reset", this.addAll, this);
            WPFS_MS.subscriptionList.on("all", this.render, this);
            // tnagy create views for subscriptionList elements
            this.addAll();
          },
          render: function () {
            initCheckboxEventHandlers();
          },
          clearContent: function () {
            this.$el.empty();
          },
          addOne: function (subscription) {
            var showSubscriptionView = new WPFS_MS.SubscriptionView({
              model: subscription,
            });
            this.$el.append(showSubscriptionView.render().el);
            this.checkCancelButtonVisibility();
          },
          addAll: function () {
            this.clearContent();
            if (WPFS_MS.subscriptionList.length === 0) {
              var emptySubscriptionListView =
                new WPFS_MS.EmptySubscriptionListView();
              $("#wpfs-subscriptions-table").append(
                emptySubscriptionListView.render().el
              );
              $("#wpfs-subscriptions-actions").hide();
            } else {
              WPFS_MS.subscriptionList.each(this.addOne, this);
            }

            initCheckboxEventHandlers();
          },
          checkCancelButtonVisibility: function () {
            if (WPFS_MS.subscriptionList.length === 0) {
              $("#wpfs-button-cancel-subscription").css("visibility", "hidden");
            } else {
              $("#wpfs-button-cancel-subscription").css(
                "visibility",
                "visible"
              );
            }
          },
        });
        WPFS_MS.subscriptionsTableView = new WPFS_MS.SubscriptionsTableView({
          el: $("#wpfs-subscriptions-table"),
        });
      } else {
        $("#wpfs-subscriptions-subtitle").hide();
        $("#wpfs-cancel-subscription-form").hide();
      }

      if (
        wpfsCustomerPortalSettings.preferences.showInvoicesSection &&
        $("#wpfs-invoices-table").length == 1
      ) {
        WPFS_MS.Invoice = Backbone.Model.extend({
          defaults: {
            id: "",
            planName: "",
            planQuantity: 1,
            priceLabel: "",
            created: "",
            invoiceNumber: "",
            invoiceUrl: "",
          },
        });
        WPFS_MS.InvoiceList = Backbone.Collection.extend({
          model: WPFS_MS.Invoice,
        });
        WPFS_MS.invoiceList = new WPFS_MS.InvoiceList(
          wpfsCustomerPortalSettings.sessionData.stripe.invoices
        );
        WPFS_MS.InvoiceView = Backbone.View.extend({
          tagName: "div",
          className: "wpfs-invoice",
          template: _.template($("#wpfs-invoice-show-row").html()),
          render: function () {
            var modelAsJSON = this.model.toJSON();
            if (WPFS_MS.isDebugEnabled()) {
              console.log(
                "InvoiceView.render(): modelAsJSON=" +
                  JSON.stringify(modelAsJSON)
              );
            }
            this.$el.html(this.template(modelAsJSON));
            return this;
          },
          initialize: function () {
            this.model.on("change", this.render, this);
          },
        });
        WPFS_MS.EmptyInvoiceListView = Backbone.View.extend({
          tagName: "div",
          className: "wpfs-no-subscription",
          template: _.template(
            $("#wpfs-subscription-empty-invoice-list").html()
          ),
          render: function () {
            this.$el.html(this.template());
            return this;
          },
        });
        WPFS_MS.InvoicesTableView = Backbone.View.extend({
          initialize: function () {
            WPFS_MS.invoiceList.on("add", this.addOne, this);
            WPFS_MS.invoiceList.on("reset", this.addAll, this);
            WPFS_MS.invoiceList.on("all", this.render, this);
            // tnagy create views for subscriptionList elements
            this.addAll();
          },
          render: function () {},
          clearContent: function () {
            this.$el.empty();
          },
          addOne: function (invoice) {
            var showInvoiceView = new WPFS_MS.InvoiceView({ model: invoice });
            this.$el.append(showInvoiceView.render().el);
          },
          addAll: function () {
            this.clearContent();
            if (WPFS_MS.invoiceList.length === 0) {
              var emptyInvoicesListView = new WPFS_MS.EmptyInvoiceListView();
              $("#wpfs-invoices-table").append(
                emptyInvoicesListView.render().el
              );
            } else {
              WPFS_MS.invoiceList.each(this.addOne, this);
            }
          },
        });
        WPFS_MS.invoiceTableView = new WPFS_MS.InvoicesTableView({
          el: $("#wpfs-invoices-table"),
        });

        WPFS_MS.ShowAllInvoicesView = Backbone.View.extend({
          tagName: "div",
          id: "wpfs-invoices-actions",
          className: "wpfs-invoices-actions",
          template: _.template($("#wpfs-invoices-actions-show-all").html()),
          render: function () {
            this.$el.html(this.template());
            return this;
          },
        });
        WPFS_MS.ShowAllInvoicesLoadingView = Backbone.View.extend({
          tagName: "div",
          id: "wpfs-invoices-actions",
          className: "wpfs-invoices-actions",
          template: _.template(
            $("#wpfs-invoices-actions-show-all-loading").html()
          ),
          render: function () {
            this.$el.html(this.template());
            return this;
          },
        });
        WPFS_MS.ShowLatestInvoicesView = Backbone.View.extend({
          tagName: "div",
          id: "wpfs-invoices-actions",
          className: "wpfs-invoices-actions",
          template: _.template($("#wpfs-invoices-actions-show-latest").html()),
          render: function () {
            this.$el.html(this.template());
            return this;
          },
        });
        WPFS_MS.ShowLatestInvoicesLoadingView = Backbone.View.extend({
          tagName: "div",
          id: "wpfs-invoices-actions",
          className: "wpfs-invoices-actions",
          template: _.template(
            $("#wpfs-invoices-actions-show-latest-loading").html()
          ),
          render: function () {
            this.$el.html(this.template());
            return this;
          },
        });

        var invoiceActionsView = null;
        switch (wpfsCustomerPortalSettings.preferences.invoiceDisplayMode) {
          case INVOICE_DISPLAY_MODE_HEAD:
            invoiceActionsView = new WPFS_MS.ShowAllInvoicesView();
            break;

          case INVOICE_DISPLAY_MODE_ALL:
            invoiceActionsView = new WPFS_MS.ShowLatestInvoicesView();
            break;

          case INVOICE_DISPLAY_MODE_FEW:
          default:
            // Hide the button, no template
            break;
        }

        if (invoiceActionsView) {
          $("#wpfs-invoices-table").after(invoiceActionsView.render().el);
        }

        $("#wpfs-invoices-view-toggle").click(function (e) {
          e.preventDefault();

          var invoicesLoadingView = null;
          switch (wpfsCustomerPortalSettings.preferences.invoiceDisplayMode) {
            case INVOICE_DISPLAY_MODE_HEAD:
              invoicesLoadingView = new WPFS_MS.ShowAllInvoicesLoadingView();
              break;

            case INVOICE_DISPLAY_MODE_ALL:
              invoicesLoadingView = new WPFS_MS.ShowLatestInvoicesLoadingView();
              break;

            case INVOICE_DISPLAY_MODE_FEW:
            default:
              // Hide the button, no template
              break;
          }

          $("#wpfs-invoices-actions").remove();
          if (invoicesLoadingView) {
            $("#wpfs-invoices-table").after(invoicesLoadingView.render().el);
          }

          submitInvoiceViewToggle();

          return false;
        });
      } else {
        $("#wpfs-invoices-subtitle").hide();
        $("#wpfs-view-invoices-form").hide();
      }

      Backbone.history.start();
    };

    WPFS.initSelectAccount = function () {
      $(".wpfs-account-selector").click(function (e) {
        e.preventDefault();

        var stripeCustomerId = $(this).data("customer-id");

        $.ajax({
          type: "POST",
          url: wpfsCustomerPortalSettings.ajaxUrl,
          data: {
            action: "wp_full_stripe_select_customer_portal_account",
            customerId: stripeCustomerId,
          },
          cache: false,
          dataType: "json",
          success: function (data) {
            window.location = getCacheFriendlyUrlPath(window.location.pathname);
          },
          error: function (jqXHR, textStatus, errorThrown) {
            logError(
              ".wpfs-account-selector.click",
              jqXHR,
              textStatus,
              errorThrown
            );
          },
          complete: function () {},
        });

        return false;
      });

      $("#wpfs-anchor-select-account").click(function (e) {
        e.preventDefault();

        $.ajax({
          type: "POST",
          url: wpfsCustomerPortalSettings.ajaxUrl,
          data: {
            action: "wp_full_stripe_show_customer_portal_account_selector",
          },
          cache: false,
          dataType: "json",
          success: function (data) {
            window.location = getCacheFriendlyUrlPath(window.location.pathname);
          },
          error: function (jqXHR, textStatus, errorThrown) {
            logError(
              "#wpfs-anchor-select-account",
              jqXHR,
              textStatus,
              errorThrown
            );
          },
          complete: function () {},
        });

        return false;
      });
    };

    function setCookie(name, value, days) {
      var expires = "";
      if (days) {
        var date = new Date();
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
        expires = "; expires=" + date.toUTCString();
      }
      document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function eraseCookie(name) {
      document.cookie = name + "=; Max-Age=-99999999;";
    }

    WPFS.runCookieAction = function () {
      if (
        typeof wpfsCustomerPortalSettings.sessionData.action !== "undefined"
      ) {
        switch (wpfsCustomerPortalSettings.sessionData.action) {
          case "setCookie":
            setCookie(
              wpfsCustomerPortalSettings.sessionData.cookieName,
              wpfsCustomerPortalSettings.sessionData.sessionId,
              wpfsCustomerPortalSettings.sessionData.cookieValidUntilHours
            );
            break;
          case "removeCookie":
            eraseCookie(wpfsCustomerPortalSettings.sessionData.cookieName);
            break;
          default:
            break;
        }
      }
    };

    function handleScrolling() {
      if (wpfsCustomerPortalSettings.preferences.scrollingPaneIntoView) {
        // tnagy scroll to forms gently
        var $wpfsEnterEmailAddressForm = $("#wpfs-enter-email-address-form");
        var $wpfsEnterSecurityCodeForm = $("#wpfs-enter-security-code-form");
        var $wpfsSelectAccountForm = $("#wpfs-select-account-container");
        var $wpfsManageSubscriptionsContainer = $(
          "#wpfs-manage-subscriptions-container"
        );
        if ($wpfsEnterEmailAddressForm.length > 0) {
          scrollToElement($wpfsEnterEmailAddressForm);
        }
        if ($wpfsEnterSecurityCodeForm.length > 0) {
          scrollToElement($wpfsEnterSecurityCodeForm);
        }
        if ($wpfsSelectAccountForm.length > 0) {
          scrollToElement($wpfsSelectAccountForm);
        }
        if ($wpfsManageSubscriptionsContainer.length > 0) {
          scrollToElement($wpfsManageSubscriptionsContainer);
        }
      }
    }

    function getSetupIntentClientSecret() {
      return new Promise((resolve, reject) => {
        if (debugLog) {
          console.log("getSetupIntentClientSecret", "CALLED");
        }

        $.ajax({
          type: "POST",
          url: wpfsCustomerPortalSettings.ajaxUrl,
          data: {
            action: "wp_get_Setup_Intent_Client_Secret",
          },
          cache: false,
          dataType: "json",
          success: function (data) {
            if (debugLog) {
              console.log(
                "getSetupIntentClientSecret",
                "SUCCESS response=" + JSON.stringify(data)
              );
            }
            resolve(data.clientSecret);
          },
          error: function (jqXHR, textStatus, errorThrown) {
            logError("submitPaymentData", jqXHR, textStatus, errorThrown);
            showFormFeedBackError($form, errorThrown.message);
            reject(errorThrown);
          },
        });
      });
    }

    WPFS.ready = function () {
      handleScrolling();
    };

    WPFS.initEnterEmailAddressForm();
    WPFS.initEnterSecurityCodeForm();
    WPFS.initUpdateCardForm();
    WPFS.initCancelSubscriptionForm();
    WPFS.initManagedSubscriptions();
    WPFS.initSelectAccount();
    WPFS.runCookieAction();

    WPFS.ready();
  });
})(jQuery);
