jQuery(document).ready(function ($) {

    $("#cancel-msg").hide();
    var cancel_btn = $("button.cancel-import-schedules");

    cancel_btn.on("click",function () {
        var consent = confirm('Are you sure you want to cancel import?');
        if (!consent) {
            return;
        }

        location.reload();
        return;
    });

    function initBatch(action, container, getParameters, onComplete) {

        var $container = $(container);
        var count = {};
        var ids = [];

        function doBatch(lastId, parameters) {
            var queryStringData = {
                'action': action,
                'last_id': lastId
            };


            if (lastId !== 0 && action === "autoship_import_schedule_data") {

                cancel_btn.removeAttr("disabled");

                ids.push(lastId);

                uniqueCount = ids;

                uniqueCount.forEach(function (i) {
                    count[i] = (count[i] || 0) + 1;
                });

                if (count[lastId] >= 100) {
                    $('.batch-button').removeAttr('disabled');
                    cancel_btn.attr("disabled", "disabled");
                    $("#cancel-msg").show();
                    return;
                }
            }


            if (parameters) {
                for (var name in parameters) {
                    queryStringData[name] = parameters[name];
                }
            }
            var queryStringPairs = [];
            for (var name in queryStringData) {
                var pair = encodeURIComponent(name)
                    + '='
                    + encodeURIComponent(queryStringData[name]);
                queryStringPairs.push(pair);
            }

            var endpoint = ajaxurl + '?' + queryStringPairs.join('&');

            $.get(endpoint, function success(response) {
                updateProgress(response);

                if (response.result === 1) {

                  if (response.count < response.total) {
                      doBatch(response.last_id, parameters);
                  } else {
                      $('.batch-button').removeAttr('disabled');
                      $("button.cancel-import-schedules").attr("disabled", "disabled");
                  }

                }
            });
        }

        function updateProgress(data) {
            var percentProgress = Math.floor( data.complete_percent );
            var $batchProgress = $container.find('.batch-progress');
            $batchProgress.find('.meter').css('width', percentProgress + '%');
            if (percentProgress < 100) {
                $batchProgress.find('.readout').text(percentProgress + '%');
            } else {
                $batchProgress.find('.readout').text(percentProgress + '% Finished!');
                if (onComplete) {
                    onComplete.call($container, data);
                }
            }

            var $batchFailures = $container.find('.batch-failures');
            for (var i in data.failed) {
                $batchFailures.append(
                    '<li>Error: #' + data.failed[i][0] + ' with message: ' + data.failed[i][1] + '</li>'
                );
            }
        }

        function resetProgress() {
            var $batchProgress = $container.find('.batch-progress');
            $batchProgress.addClass('active');
            $batchProgress.find('.meter').css('width', '0%');
            $batchProgress.find('.readout').text('0%');
            $container.find('.batch-failures').empty();
        }

        $container.find('.batch-button').on('click', function () {
            var consent = confirm('Are you sure you want to continue? This action cannot be undone.');
            if (!consent) {
                return;
            }
            var parameters = null;
            if (getParameters) {
                parameters = getParameters.call($container);
                if (null === parameters) {
                    return;
                }
            }

            $(this).attr('disabled', 'disabled');


            resetProgress();
            doBatch(0, parameters);
        });
    }

    initBatch('autoship_import_schedule_data', '#autoship-import-schedules');
    initBatch('autoship_import_product_settings', '#autoship-import-product-settings');

});

jQuery(function ($) {

  var targets = [];

  function schedule_processor ( targets ) {

    function ajax_handler(){

      $.ajax({
        type: 'POST',
        dataType: 'json',
        data: targets.data,
        url: ajaxurl + '?action=' + targets.action,
        async: true,
        success: function(response) {

          targets.target_end = new Date();
          targets.response = response;

          target_render_handler( response );

          if ( targets.form.hasClass('active') && true == response.success && response.current_count < targets.total_count ){

            targets.data.current_page ++;
            targets.data.current_count = response.current_count;
            targets.target_start = new Date();

            ajax_handler();

          } else {

            response.success = false;
            targets.form.removeClass('active');
            targets.target_meter_bar.css( 'width' , '5%');
            target_render_handler( response );
            target_reset_handler( targets.form );

          }

          $( document ).trigger( 'autoshipBulkActionComplete', [ targets ] );
          $( document ).trigger( 'autoship_' + targets.batch_action + '_BulkActionComplete', [ targets ] );

        },
        failure: function(response) {

          response.success = false;
          targets.form.removeClass('active');
          targets.target_meter_bar.css( 'width' , '5%');
          target_render_handler( response );
          target_reset_handler( targets.form );

          $( document ).trigger( 'autoshipBulkActionFailed', [ targets ] );
          $( document ).trigger( 'autoship_' + targets.batch_action + '_BulkActionFailed', [ targets ] );
        }
      });

    }
    ajax_handler();

  }

  function target_reset_handler( form ){

    form.find('input[name=current_page]').val( 1 );
    form.find('input[name=current_count]').val( 0 );

  }

  function target_handler( form ){

    targets = [];
    targets.form = form;
    targets.target_total_counters = form.find('.total-toggle-counters');
    targets.target_btn = form.find('button.action');
    targets.target_cancel_btn = form.find('button.cancel-action');
    targets.target_meter = form.find('.autoship-meter');
    targets.target_meter_bar = targets.target_meter.find('span');
    targets.target_notice = form.find('.autoship-bulk-notice');
    targets.target_subnotice = form.find('.autoship-bulk-subnotice');

    targets.target_page_counter = form.find('input[name=current_page]');
    targets.target_page_count = targets.target_page_counter.val();
    targets.batch_action = form.find('input[name=batch_action]').val();
    targets.action = form.find('input[name=autoship-action]').val();
    targets.current_page = form.find('input[name=current_page]').val();
    targets.batch_size = form.find('input[name=batch_size]').val();
    targets.current_count = form.find('input[name=current_count]').val();
    targets.total_count = form.find('input[name=total_count]').val();

    targets.data = {};
    var inputs = form.find('input:not(:checkbox)');
    inputs.each( function(index, el) {

      key = $(this).attr('name');
      val = $(this).val();
      targets.data[key] = val;

    });
    var inputs = form.find('input:checked');
    inputs.each( function(index, el) {

      key = $(this).attr('name');
      val = $(this).val();
      targets.data[key] = val;

    });

    return targets;
  }

  function target_render_handler( response ){

    if ( true == response.success ){

        var average_string = '( ~' + ( targets.target_end - targets.target_start ) / 1000 + ' Sec Per Batch )...';

        targets.target_page_counter.val( response.page );
        targets.target_meter_bar.animate({
          width: response.total_pct + '%',
        }, 1200);
        targets.target_notice.fadeOut('fast', function(e) {
          targets.target_notice.html( response.notice ).fadeIn('slow');
        });
        targets.target_subnotice.fadeOut('fast', function(e) {
          targets.target_subnotice.html( 'Processing Batch ' + targets.data.current_page + ' Started ' + average_string ).fadeIn('slow');
        });

        if ( response.total_pct == 100 ){

          targets.target_meter_bar.animate({
            width: '5%',
          }, 1200);

        }

        return response.total_pct != 100;

    } else {

      targets.target_meter_bar.animate({
        width: '5%',
      }, 1200);
      targets.target_meter.fadeOut('slow');
      targets.target_notice.fadeOut('slow', function(e) {
        $(this).html( response.notice ).fadeIn('slow');
      });

      return false;

    }

  }

  var autoship_bulk_action_cancel = function(e){

    e.preventDefault();

    var form = $(this).closest('form');
    form.removeClass('active');

    targets.target_meter_bar.animate({
      width: '5%',
    }, 1200);
    targets.target_meter.fadeOut('slow');
    targets.target_notice.fadeOut('fast', function(e) {
      targets.target_notice.html( 'Batch Processing Cancelled.' ).fadeIn('slow');
    });
    targets.target_subnotice.fadeOut().html('');


  };

  $('.autoship-bulk-action').on( 'click', 'button.autoship-cancel-action', autoship_bulk_action_cancel );

  var autoship_bulk_action = function(e){

    e.preventDefault();

    var form = $(this).closest('.autoship-bulk-action');
    form.addClass('active');

    targets = target_handler( form );
    targets.target_notice.html('Batch Processing Started');
    targets.target_subnotice.html( 'Processing Batch ' + targets.data.current_page + ' Started...' ).fadeIn('slow');
    targets.target_meter.fadeIn();
    targets.target_start = new Date();

    schedule_processor ( targets );
    return false;

  };

  $('.autoship-bulk-action').on( 'click', 'button.autoship-action', autoship_bulk_action );

  var autoship_bulk_toggle = function(e){

    var count = $(this).attr('data-adjust-batch-total');
    var msg = $(this).attr('data-adjust-batch-notice');
    var form = $(this).closest('.autoship-bulk-action');
    form.find('.total-toggle-counters:not(input)').text( count );
    form.find('input.total-toggle-counters').val( count );
    form.find('.autoship-bulk-notice').html( msg );

  };

  $(".autoship-bulk-action").on( 'click', '.batch-total-toggle', autoship_bulk_toggle );

});
