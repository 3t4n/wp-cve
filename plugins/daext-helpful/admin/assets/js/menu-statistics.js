jQuery(document).ready(function($) {

  'use strict';

  //Init select2 -------------------------------------------------------------
  $('#pfr').select2();
  $('#sb').select2();
  $('#or').select2();

  $(document.body).on('click', '#update-archive', function() {

    'use strict';

    //if another request is processed right now do not proceed with another ajax request
    if ($('#ajax-request-status').val() == 'processing') {
      return;
    }

    //prepare ajax request
    const data = {
      'action': 'daexthefu_update_feedback_archive',
      'security': DAEXTHEFU_PARAMETERS.nonce,
    };

    //show the ajax loader
    $('#ajax-loader').show();

    //set the ajax request status
    $('#ajax-request-status').val('processing');

    //send ajax request
    $.post(DAEXTHEFU_PARAMETERS.ajaxUrl, data, function(data) {

      'use strict';

      //reload the statistics menu ----------------------------------------
      window.location.replace(DAEXTHEFU_PARAMETERS.adminUrl +
          'admin.php?page=daexthefu-statistics');

    });

  });

  $(document.body).on('click', '.open-post-data-modal-window', function() {

    'use strict';

    event.preventDefault();

    if (!$(this).hasClass('menu-icon-disabled')) {

      const post_id = parseInt($(this).attr('data-post-id'), 10);
      update_post_data_modal_window(post_id, 1);

    }

  });

  $(document.body).on('click', '.download-post-statistics-csv', function() {

    'use strict';

    if ($(this).hasClass('menu-icon-disabled')) {

      event.preventDefault();

    }

  });

  /**
   * Original Version (not compatible with pre-ES5 browser)
   */
  $(function() {

    'use strict';

    $('#dialog-post-feedback').dialog({
      autoOpen: false,
      resizable: false,
      width: 960,
      height: 520,
      modal: true,
      buttons: {
        [window.objectL10n.closeText]: function() {
          $(this).dialog('close');
          $('input.menu-icon').blur();
        },
      },
    });

  });

  /**
   * Update the content of the modal window.
   *
   * @param post_id
   * @param current_page
   */
  function update_post_data_modal_window(post_id, current_page) {

    'use strict';

    //if another request is processed right now do not proceed with another ajax request
    if ($('#ajax-request-status').val() == 'processing') {
      return;
    }

    //prepare ajax request
    const data = {
      'action': 'daexthefu_generate_post_data_modal_window_data',
      'security': DAEXTHEFU_PARAMETERS.nonce,
      post_id: post_id,
      current_page: current_page,
    };

    //set the ajax request status
    $('#ajax-request-status').val('processing');

    //send ajax request
    $.post(DAEXTHEFU_PARAMETERS.ajaxUrl, data, function(data_json) {

      'use strict';

      //open the modal window
      $('#dialog-post-feedback').dialog('open');

      //Remove focus from all the buttons included in the dialog
      $('.ui-dialog :button').blur();

      //add data to the modal window
      try {

        const data_a = JSON.parse(data_json);

        //Set the modal window title
        $('#dialog-post-feedback').dialog('option', 'title', data_a.title);

        //Delete the existing HTML and generate a new empty table
        $('#dialog-post-feedback').empty();
        const table_html = '<div class="daext-items-container"><table class="daext-items feedback-list"><thead></thead><tbody></tbody></table></div>';
        $('#dialog-post-feedback').append(table_html);

        //Generate the head html
        let thead_html = '<tr>';
        thead_html += '<th><div></div><div class="help-icon" title=""></div></th>';
        thead_html += '<th><div></div><div class="help-icon" title=""></div></th>';
        thead_html += '<th><div></div><div class="help-icon" title=""></div></th>';
        thead_html += '</tr>';

        //Add the empty thead html to the DOM
        $('#dialog-post-feedback table thead').append(thead_html);

        //Safely add the elements text and attributes
        $('#dialog-post-feedback table thead tr:nth-child(1) th:nth-child(1) div:nth-child(1)').
            text('Date');
        $('#dialog-post-feedback table thead tr:nth-child(1) th:nth-child(1) div:nth-child(2)').
            attr('title', objectL10n.dateTooltipText);
        $('#dialog-post-feedback table thead tr:nth-child(1) th:nth-child(2) div:nth-child(1)').
            text('Rating');
        $('#dialog-post-feedback table thead tr:nth-child(1) th:nth-child(2) div:nth-child(2)').
            attr('title', objectL10n.ratingTooltipText);
        $('#dialog-post-feedback table thead tr:nth-child(1) th:nth-child(3) div:nth-child(1)').
            text('Comment');
        $('#dialog-post-feedback table thead tr:nth-child(1) th:nth-child(3) div:nth-child(2)').
            attr('title', objectL10n.commentTooltipText);

        //generate the table body html
        let tr_html = '';
        $.each(data_a.body, function(index, value) {

          'use strict';

          //Set the empty tr html
          tr_html = '<tr>';
          tr_html += '<td></td>';
          tr_html += '<td></td>';
          tr_html += '<td></td>';
          tr_html += '</tr>';

          //Add the empty tr html to the DOM
          $('#dialog-post-feedback table tbody').append(tr_html);

          //Safely add the elements text and attributes
          $('#dialog-post-feedback table tbody tr:nth-child(' + (index + 1) +
              ') td:nth-child(1)').text(value.date);
          $('#dialog-post-feedback table tbody tr:nth-child(' + (index + 1) +
              ') td:nth-child(2)').
              text(parseInt(value.value) === 0 ? 'Negative' : 'Positive');
          $('#dialog-post-feedback table tbody tr:nth-child(' + (index + 1) +
              ') td:nth-child(3)').
              text(value.description.length === 0 ? 'N/A' : value.description);

        });

        //Add the HTML of the pagination
        $('#dialog-post-feedback').append(generate_pagination_html(data_a));

        //Init the tooltips present in the modal window
        $('.ui-dialog .help-icon').tooltip({show: false, hide: false});

        //Add the click event listener on the pagination pages
        $(document.body).
            on('click',
                '#dialog-post-feedback .daext-tablenav-pages a:not(.disabled)',
                function(event) {

                  'use strict';

                  event.preventDefault();
                  const current_page = parseInt($(this).attr('data-page'), 10);
                  if (typeof (current_page) !== 'undefined') {
                    update_post_data_modal_window(post_id, current_page);
                  }

                });

      } catch (e) {

        //do nothing

      }

      $('#ajax-request-status').val('done');

    });

  }

  /**
   * Generate the HTML of the pagination based on the data available in the provided array.
   *
   * @param data_a An array with the data of the pagination
   * @returns {string} The HTML of the pagination
   */
  function generate_pagination_html(data_a) {

    'use strict';

    let pagination_html = '';
    pagination_html += '<span class="daext-displaying-num">' +
        data_a.total_items + ' ' + objectL10n.itemsText + '</span>';

    $.each(data_a.pagination, function(index, item) {

      let class_name = null;

      switch (item.type) {

        case 'prev':
          class_name = item.disabled ? 'disabled' : 'prev';
          pagination_html += '<a data-page="' +
              parseInt(item.destination_page, 10) +
              '" href="javascript: void(0)" class="' + class_name +
              '">&#171</a>';
          break;

        case 'next':

          class_name = item.disabled ? 'disabled' : 'prev';
          pagination_html += '<a data-page="' +
              parseInt(item.destination_page, 10) +
              '" href="javascript: void(0)" class="' + class_name +
              '">&#187</a>';

          break;

        case 'ellipses':

          pagination_html += '<span>...</span>';

          break;

        case 'number':

          const class_name_value = item.disabled ? 'class="disabled"' : '';
          pagination_html += '<a data-page="' +
              parseInt(item.destination_page, 10) +
              '" href="javascript: void(0)" ' + class_name_value + '>' +
              parseInt(item.destination_page, 10) + '</a>';

          break;

      }

    });

    return '<div class="daext-tablenav daext-clearfix"><div class="daext-tablenav-pages">' +
        pagination_html + '</div></div>';

  }

});