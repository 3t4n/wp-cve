(function($) {

  'use strict';

  $(document).ready(function() {

    'use strict';

    $('#task').select2();

    $('#execute-task').click(function(event) {
      event.preventDefault();
      $('#dialog-confirm').dialog('open');
    });

  });

  $(function() {
    $('#dialog-confirm').dialog({
      autoOpen: false,
      resizable: false,
      height: 'auto',
      width: 340,
      modal: true,
      buttons: {
        [window.objectL10n.deleteText]: function() {
          $('#form-maintenance').submit();
        },
        [window.objectL10n.cancelText]: function() {
          $(this).dialog('close');
        },
      },
    });
  });

}(window.jQuery));

