
/*global jQuery, document, redux*/

(function ($) {
  'use strict';

  redux.field_objects = redux.field_objects || {};
  redux.field_objects.animation = redux.field_objects.animation || {};

  $(document).ready(
          function () {
            //redux.field_objects.animation.init();
          }
  );

  redux.field_objects.animation.init = function (selector) {

    if (!selector) {
      selector = $(document).find('.redux-container-animation:visible');
    }
    $(selector).each(
            function () {
              var el = $(this);
              var parent = el;
              if (!el.hasClass('redux-field-container')) {
                parent = el.parents('.redux-field-container:first');
              }
              if (parent.is(':hidden')) { // Skip hidden fields
                return;
              }
              if (parent.hasClass('redux-field-init')) {
                parent.removeClass('redux-field-init');
              } else {
                return;
              }
              var default_params = {
                width: 'resolve',
                triggerChange: true,
                allowClear: true
              };

              var select2_handle = el.find('.select2_params');

              if (select2_handle.size() > 0) {
                var select2_params = select2_handle.val();

                select2_params = JSON.parse(select2_params);
                default_params = $.extend({}, default_params, select2_params);
              }

              el.find('.redux-animation-options').select2(default_params);

              el.find('.redux-animation-action').select2(default_params);

              el.find('.redux-animation-speed').select2(default_params);


              el.on('change', '.redux-animation-options,.redux-animation-action,.redux-animation-speed', function (e) {

                redux_change($(this));

              });

            }
    );


  };
})(jQuery);