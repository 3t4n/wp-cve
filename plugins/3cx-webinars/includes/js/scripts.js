(function ($) {

  var methods = {

    init: function (options, tcxWebinarOptions) {
      return this.each(function () {

        var dialog,
        form,
        // From http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#e-mail-state-%28type=email%29
        emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
        name = '',
        email = '',
        meetingid = '',
        session = '',
        allFields = [],
        tips = '',
        webinarElement;

        function updateTips(t) {
          tips.text(t).addClass("ui-state-highlight");
          tips.show();
        }

        function checkLength(o, n, min, max) {
          if (o.val().length > max || o.val().length < min) {
            o.addClass("ui-state-error");
            updateTips(tcxmsg.errorLength.replace('%1$s', n).replace('%2$s', min).replace('%3$s', max));
            return false;
          } else {
            return true;
          }
        }

        function checkRegexp(o, regexp, n) {
          if (!(regexp.test(o.val()))) {
            o.addClass("ui-state-error");
            updateTips(n);
            return false;
          } else {
            return true;
          }
        }

        function subscribeWebinar(elem) {
          allFields.removeClass("ui-state-error");
          tips.hide();
          var valid = true;
          valid = valid && checkLength(name, tcxmsg.nameField, 3, 64);
          valid = valid && checkLength(email, tcxmsg.emailField, 6, 150);
          valid = valid && checkRegexp(name, new RegExp("[^0-9]"), tcxmsg.errorName);
          valid = valid && checkRegexp(email, emailRegex, tcxmsg.errorEmail);
          if (valid) {
            var id = elem.parent().parent().attr('id').substring(19);
            var postdata = {
              subscribe: meetingid,
              id: id,
              email: email.val(),
              name: name.val()
            };
            var targetUrl = elem.parent().parent().attr('tcxtarget');
            $.post(targetUrl, postdata)
            .done(function (data) {
              if (data && data.result) {
								$("body").remove('#tcxdialogmessage');
								$("body").append(tcxtemplate.messagebox);
                $("#tcxdialogmessage").dialog({
                  modal: true,
									open: function () {
									 $('.ui-dialog').css('z-index',999999);
									 $('.ui-widget-overlay').css('z-index',999998);
									},									
                  buttons: {
                    Ok: function () {
                      $(this).dialog("close");
                      dialog.dialog('close');
                      return true;
                    }
                  }
                });
              } else {
                if (data.error){
                  updateTips(data.error);
                }
                else {
                  updateTips(tcxmsg.errorRequestFailed);
                }
              }
            })
            .fail(function (data) {
              if (data.responseJSON && data.responseJSON.error){
                updateTips(data.responseJSON.error);
              }
              else {
                updateTips(tcxmsg.errorRequestFailed);
              }
            });
          }
          return valid;
        }
				
				function updateWebinarElement(elem_a) {
          $(elem_a).find('.tcxwebinartitle a').each(function() {
            dt = new Date($(this).attr('tcxdatetime'));
            var dtf = dt.toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) + ', ' + dt.toLocaleTimeString(undefined, { hour: '2-digit', minute: '2-digit' });
            $(this).parent().parent().find('.tcxwebinardatelocale').text(dtf);
          });          
          $(elem_a).find('.tcxwebinartitle a').click(function () {
            var elem = $(this).parent().parent();
            meetingid = $(this).attr("tcxmeetingid");
            $(this).remove('#tcxdialog');
            var newDialog = $(this).append(tcxtemplate.dialog);
            newDialog.find('#tcxdialog').attr('title', $(this).text());
            newDialog.find('.tcxwebinardialogstartdate').text(elem.find('.tcxwebinardatelocale').text() + elem.find('.tcxwebinarduration').text());
            newDialog.find('.tcxwebinardialogdescr').text(elem.find('.tcxwebinardescr').text());
            form = $("#tcxform");
            name = $("#tcxname");
            email = $("#tcxemail");
            tips = $("#tcxtips");
            allFields = $([]).add(name).add(email);
            dialog = $("#tcxdialog").dialog({
                modal: true,
                open: function (event, ui) {
                  $('.ui-dialog').css('z-index',999991);
                  $('.ui-widget-overlay').css('z-index',999990);
                },											
                height: 'auto',
                width: 800,
                buttons: [
                  { text: tcxmsg.cancelButton, click: function() { dialog.dialog("close"); } }, 
                  { text: tcxmsg.subscribeButton, click: function() { subscribeWebinar(elem); } }
                ],
                close: function () {
                form[0].reset();
                  allFields.removeClass("ui-state-error");
                }
              });
            return false;
          })
				}

        // Setup the default options
        var settings = $.extend({
            onFormCancel: null,
            onFormError: null,
            inputerrorClass: 'inputtexterror'
          }, options);

        // Merge the user-defined options with the defaults
        if (tcxWebinarOptions) {
          settings = $.extend(settings, tcxWebinarOptions);
        }
				
				webinarElement = this;
				updateWebinarElement(webinarElement);

      })
    },

    clear: function () {
      return this.each(function () {
        $('.' + $(this).attr('errorFieldClass')).text('');
        $(this).find('input').removeClass($(this).attr('inputerrorClass'));
      })
    }

  }

  $.fn.tcxWebinar = function (method) {

    if (methods[method]) {
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    } else if (typeof method === 'object' || !method) {
      return methods.init.apply(this, arguments);
    } else {
      $.error('The method ' + method + ' does not exist in $.tcxWebinar');
    }
  }

}
(jQuery));