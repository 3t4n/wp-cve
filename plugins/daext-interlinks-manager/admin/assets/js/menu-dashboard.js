jQuery(document).ready(function($) {

  'use strict';

  $(document.body).on('click', '#update-archive', function() {

    'use strict';

    //if another request is processed right now do not proceed with another ajax request
    if ($('#ajax-request-status').val() == 'processing') {return;}

    //prepare ajax request
    const data = {
      'action': 'update_interlinks_archive',
      'security': window.DAEXTINMA_PARAMETERS.nonce,
    };

    //show the ajax loader
    $('#ajax-loader').show();

    //set the ajax request status
    $('#ajax-request-status').val('processing');

    //send ajax request
    $.post(window.DAEXTINMA_PARAMETERS.ajax_url, data, function(data) {

      'use strict';

      //reload the dashboard menu ----------------------------------------
      window.location.replace(window.DAEXTINMA_PARAMETERS.admin_url +
          'admin.php?page=daextinma-dashboard');

    });

  });

});