jQuery(document).ready(function($) {



    // console.log('wp-tools - OK!');


/*
          // Randomly add a data point every 500ms
          var random = new TimeSeries();
          setInterval(function() {
            random.append(new Date().getTime(), Math.random() * 10000);
          }, 500);
    
          function createTimeline() {
            var chart = new SmoothieChart({responsive: true});
            chart.addTimeSeries(random, { strokeStyle: 'rgba(0, 255, 0, 1)', fillStyle: 'rgba(0, 255, 0, 0.2)', lineWidth: 4 });
            chart.streamTo(document.getElementById("chart"), 500);
          }

          createTimeline();
*/



    $(document).on('click', '#wptools_an1 .notice-dismiss', function( event ) {
        //alert('1');
        //console.log('OK11!')

        /*
        data = {
            action : 'wptools_dismissible_notice',
        };
        $.post(ajaxurl, data, function (response) {
            // console.log(response, 'DONE!');
        });
        */

        jQuery.ajax({
            url: ajaxurl,
            data: {
              action : 'wptools_dismissible_notice',
            },
            success: function (data) {
                // This outputs the result of the ajax request
                //console.log('OK');
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });




    });

    $(document).on('click', '#wptools_an2 .notice-dismiss', function( event ) {

        //alert('1');
        //console.log('OK11!')

        jQuery.ajax({
            url: ajaxurl,
            data: {
            action : 'wptools_dismissible_notice2',
            },
            success: function (data) {
                // This outputs the result of the ajax request
                //console.log('OK');
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });

    });

    $("#bill_javascript_status").html("OK. Javascript is Working on this page and with your Browser.");
});