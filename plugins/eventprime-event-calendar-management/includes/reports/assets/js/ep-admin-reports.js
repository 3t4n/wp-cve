jQuery(function($){
    var date_format = 'yy-mm-dd';
    if( eventprime.global_settings.datepicker_format ) {
        settings_date_format = eventprime.global_settings.datepicker_format;
        if( settings_date_format ) {
            settings_date_format = settings_date_format.split( '&' )[0];
            if( settings_date_format ) {
                date_format = settings_date_format;
            }
        }
    }
    //Modifying Date Formate for Date Picker Range
    var lates = 'DD-MM-YYYY';
    
    var date = new Date();
    
    var start = moment(date.setDate(date.getDate() - 6));
    var end = moment(date.setDate(date.getDate() + 7));
    cb(start,end);
    function cb(start, end) {
        jQuery('#ep-reports-datepicker-div input').val(start.format(lates) + ' - ' + end.format(lates));
    }

    jQuery('#ep-reports-datepicker-div').daterangepicker({
        startDate: start,
        endDate: end,
        maxDate: new Date(),
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    $( document ).on( 'click', '#ep_booking_filter', function() {
        let dates = $('#ep-reports-datepicker').val();
        let event_id = $('#ep_event_id').val();
        if( dates ) {
            let data = { 
                action      : 'ep_eventprime_reports_filter',
                ep_filter_date : dates,
                event_id    : event_id,
            };
            $.ajax({
                type        : "POST",
                url         : ajaxurl,
                data        : data,
                success     : function( response ) {
                    $('#ep_booking_stat_container').html(response.data.stat_html);
                    $('.ep-report-booking-list').html(response.data.booking_html);
                    if(response.data.chart.length){
                        drawBookingsChart(response.data.chart);
                    }
                }
            });
        }
    });

    $( document ).on( 'click', '#ep-loadmore-report-bookings', function() {
        $('.ep-spinner').addClass('ep-is-active');
        let dates = $('#ep-reports-datepicker').val();
        let event_id = $('#ep_event_id').val();
        let paged = $('#ep-report-booking-paged').val();
        var max_page = $( this ).attr('data-max');
        if( dates ) {
            let data = { 
                action      : 'ep_eventprime_reports_filter',
                ep_filter_date : dates,
                event_id    : event_id,
                paged       : paged,
                ep_report_action_type : 'load_more'
            };
            $.ajax({
                type        : "POST",
                url         : ajaxurl,
                data        : data,
                success     : function( response ) {
                    $('.ep-spinner').removeClass('ep-is-active');
                    $('.ep-report-booking-list table tbody').append(response.data.booking_html);
                    let new_page = parseInt(paged, 10) + parseInt(1, 10);
                    $('#ep-report-booking-paged').val(new_page);
                    if(new_page >= parseInt(max_page,10)){
                        $('.ep-reports-boooking-load-more').hide();
                    }
                }
            });
        }
    });
     
});
function drawBookingsChart(arrData) {
    var data = new google.visualization.DataTable();
    data.addColumn('date',   'Time of Day');
    data.addColumn('number', 'Booking');
  
    const arrDataMap = arrData.map((val, key) => {
        return [new Date(val.date), val.booking];
    });
    data.addRows(arrDataMap);

    var options = {
        title: 'Booking count as per date',
        'height': 500,
        colors: ['#2271b1', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6'],
        legend: {
            position: 'bottom'
        },
        hAxis: {
            format: 'MMM d, yyyy',
            baselineColor: 'transparent',
            gridlines: {
                color: 'transparent',
                count: 25
            }
        },
        vAxis: {
            baselineColor: '#DDD',
            gridlines: {
                color: '#DDD'
            },
            minValue: 0,
            title: 'Bookings'
        }
    };
  
    var chart = new google.visualization.LineChart(document.getElementById('ep_bookings_chart'));
  
    chart.draw(data, options);
}