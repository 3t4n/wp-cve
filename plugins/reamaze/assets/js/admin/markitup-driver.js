!function($) {
  $(function() {
    var buildOptions = function($form, $textarea) {
      var markupSet = [
            {
              name: 'Heading',
              className: 'heading',
              key: 'H',
              openWith: '### ',
              closeWith: ' ###',
              placeHolder: 'Your title here...'
            },

            {
              name: 'Bold',
              className: 'bold',
              key:'B',
              openWith:'**',
              closeWith:'**'
            },
            {
              name:'Italics',
              className:'italic',
              key:'I',
              openWith:'_',
              closeWith:'_'
            },

            {
              name:"Unordered List",
              className:"ul fa hidden-phone",
              openWith:'* ',
              multiline: true,
              placeHolder: 'Line item ...'
            },
            {
              name:"Ordered List",
              className:"ol fa hidden-phone",
              openWith:function(markItUp) {
                return markItUp.line+'. ';
              },
              multiline: true,
              placeHolder: 'Line item ...'
            },

            {
              name:"Link",
              className: 'link fa',
              key:'L',
              openWith:'[',
              closeWith:']([![URL:!:http://]!])',
              placeHolder:'Your text to link here...'
            },
            {
              name:"Quotes",
              className:"quote fa",
              openWith:'> ',
              placeHolder: 'Quotation ...'
            }
          ],
          DEFAULTS = {
            onShiftEnter: {keepDefault: false, openWith: '\n\n'},
            resizeHandle: false //we already have the autosize plugin doing resizing
          },
          customPreview = $textarea.data('markitup-preview'),
          $previewWrap  = customPreview ? $(customPreview) : $form.find('.markitup-preview');

      if ($previewWrap.length > 0) {
        var $previewElement = $previewWrap.find('.preview'),
            $refreshWrap    = $previewWrap.find('.status'),
            $okText         = $refreshWrap.find('.ok'),
            $errorText      = $refreshWrap.find('.error'),
            $ajaxText       = $refreshWrap.find('.ajax'),

            inPlaceEdit     = !!$previewWrap.data('in-place');

        var fetchPreview = function(text) {
          $refreshWrap.addClass('loading');

          $okText.addClass('hide');
          $errorText.addClass('hide');
          $ajaxText.removeClass('hide');

          var ajaxData = {
            val: text
          };

          if ($previewWrap.data('preview-filter'))
            ajaxData.filter = 1;

          if (inPlaceEdit) {
            ajaxData.edit = 1;
            $previewWrap.prev('.input-frame').addClass('hide');
          }

          //if preview never opened, do open-drawer effect
          if (!$previewWrap.hasClass('show')) {
            $previewWrap.addClass('show');
          }

          $.get('/preview', ajaxData, 'html')
              .done(function(html) {
                $okText.removeClass('hide');
                $previewElement.html(html);
              })
              .fail(function(o) {
                $errorText.removeClass('hide');
              })
              .always(function() {
                $refreshWrap.removeClass('loading');
                $ajaxText.addClass('hide');
              });
        };

        DEFAULTS.previewAutoRefresh = false;
        DEFAULTS.previewHandler = function(text) {
          if (!$previewWrap.hasClass('show')) {
            fetchPreview(text);
          } else {
            $previewWrap.removeClass('show');
            $previewWrap.find('.preview').html('');
          }
        };

        markupSet.push({
          name:"Preview Markdown",
          className:"preview fa",
          call:'preview'
        });

        $previewWrap.find('.msg').on('click', function() {
          if (inPlaceEdit) {
            $previewWrap.removeClass('show').prev('.input-frame').removeClass('hide');
          }
          else {
            fetchPreview($textarea.val());
          }
        });
      }

      DEFAULTS.markupSet = markupSet;

      return DEFAULTS;
    };

    $(window).on('reamaze:markitup', function(e, $form) {
      if ($form.data('markitupDone'))
        return;

      var selector = $form.data('markitup');

      $form
          .data('markitupDone', true)
          .find(selector)
          .not(function() {
            //don't apply markitup to the ghost textareas created by jquery.autoresize
            var $this = $(this),
                css = $this.css(['position', 'left']),
                tidx = $this.attr('tabindex');

            return css.position == 'absolute'
                && css.left     == '-9999px'
                && tidx         == -1;
          })
          .each(function(i, textarea) {
            var $textarea = $(textarea);
            $textarea.markItUp(buildOptions($form, $textarea));
          });
    });

    var init = function() {
      $('form[data-markitup]').each(function() {
        $(window).trigger('reamaze:markitup', [$(this)]);
      });
    };

    init();
  });
}(window.jQuery);
