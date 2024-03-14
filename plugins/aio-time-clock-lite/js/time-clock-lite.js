jQuery(document).ready(function () {

  if (jQuery("#aio_time_clock").length > 0) {
    employee = jQuery("#employee").val();
    jQuery.ajax({
      type: "post",
      dataType: "json",
      url: timeClockAjax.ajaxurl,
      data: {
        action: "aio_time_clock_lite_js",
        clock_action: "check_shifts",
        employee: employee,
        nonce: timeClockAjax.Nonce
      },
      success: function (response) {        
        var o_shift = response["open_shift_id"];
        var is_clocked_in = response["is_clocked_in"];
        var new_clock_action = "";
        if (is_clocked_in){
          new_clock_action = "clock_out";
          jQuery("#open_shift_id").val(o_shift);
          jQuery("#clock_action").val(new_clock_action);
          jQuery("#aio_clock_button").html(timeClockAjax.clockOut);
          jQuery("#clockMessage").html(
            '<i>' + timeClockAjax.isClockedIn + '</i><br /><strong>' + timeClockAjax.clockInTime + ':</strong> ' +  response['employee_clock_in_time']
          );

        }
        else{
          new_clock_action = "clock_in";
          jQuery("#clock_action").val(new_clock_action);
          jQuery("#open_shift_id").val("");
          jQuery("#aio_clock_button").html(timeClockAjax.clockIn);
          jQuery("#clockMessage").html(timeClockAjax.clockInMessage);
        }
      }
    });
  }

  if (jQuery("#aio_time_clock_widget").length > 0) {
    if (!jQuery("#aio_time_clock").length) {
      employee = jQuery("#employee").val();
      jQuery.ajax({
        type: "post",
        dataType: "json",
        url: timeClockAjax.ajaxurl,
        data: {
          action: "aio_time_clock_lite_js",
          clock_action: "check_shifts",
          employee: employee,
          nonce: timeClockAjax.Nonce
        },
        success: function (response) {          
          var o_shift = response["open_shift_id"];
          var is_clocked_in = response["is_clocked_in"];
          var new_clock_action = "";
          if (is_clocked_in){
            new_clock_action = "clock_out";
            jQuery("#open_shift_id").val(o_shift);
            jQuery("#clock_action").val(new_clock_action);
            jQuery("#aio_clock_button").html(timeClockAjax.clockOut);
            jQuery("#clockMessage").html(
              '<i>' + timeClockAjax.isClockedIn + '</i><br /><strong>' + timeClockAjax.clockInTime + ':</strong> ' +  response['employee_clock_in_time']
            );
  
          }
          else{
            new_clock_action = "clock_in";
            jQuery("#clock_action").val(new_clock_action);
            jQuery("#open_shift_id").val("");
            jQuery("#aio_clock_button").html(timeClockAjax.clockIn);
            jQuery("#clockMessage").html('<i>' + timeClockAjax.clockInMessage + '</i>');
          }
        }
      });
    }
    else{
      jQuery("#aio_time_clock_widget").html(timeClockAjax.TimeClockDetected);
    }    
  }

  if( jQuery('#jsTimer').length ) {
    var myTimerVar = setInterval(myTimer, 1000);
  }

  jQuery(".aioUserButton").click(function (e) {
    e.preventDefault();
    var aio_link = jQuery(this).attr("href");
    window.location = aio_link;
  });

  jQuery("#aio_clock_button").click(function (e) {
    var now = new Date();
    e.preventDefault();
    jQuery("#aio_clock_button").html('<div class="aio-spinner"></div>');
    employee = jQuery(this).attr("data-employee");
    clock_action = jQuery("#clock_action").val();
    open_shift_id = jQuery("#open_shift_id").val();

    jQuery.ajax({
      type: "post",
      dataType: "json",
      url: timeClockAjax.ajaxurl,
      data: {
        action: "aio_time_clock_lite_js",
        clock_action: clock_action,
        open_shift_id: open_shift_id,
        employee: employee,
        device_time: now.toLocaleString(),
        nonce: timeClockAjax.Nonce
      },
      success: function (response) {        
        var open_shift_id = response["open_shift_id"];
        var is_clocked_in = response["is_clocked_in"];
        var new_clock_action = "";
        if (is_clocked_in){
          new_clock_action = "clock_out";
          jQuery("#open_shift_id").val(open_shift_id);
          jQuery("#clock_action").val(new_clock_action);
          jQuery("#aio_clock_button").html(timeClockAjax.clockOut);
          jQuery("#clockMessage").html(
            '<i>' + timeClockAjax.isClockedIn + '</i>' + '<br /><strong>' + timeClockAjax.clockInTime + ':</strong> ' + response['employee_clock_in_time']
          );

        }
        else{
          new_clock_action = "clock_in";
          jQuery("#open_shift_id").val("");
          jQuery("#clock_action").val(new_clock_action);
          jQuery("#aio_clock_button").html(timeClockAjax.clockIn);
          jQuery("#clockMessage").html('<i>' + timeClockAjax.clockedOutMessage + '</i> <br /><strong>'+timeClockAjax.TotalShiftTime+':</strong> ' + response["time_total"]);
        }
      }
    });
  });  
});

function myTimer() {
  var d = new Date();
  document.getElementById("jsTimer").innerHTML = "<strong>"+timeClockAjax.currentTime+":</strong> " + d.toLocaleTimeString();
}

function employeProfileSearch() {
  // Declare variables 
  var input, filter, table, tr, td, i;
  input = document.getElementById("employeeProfileInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("employeeProfileTable");
  tr = table.getElementsByTagName("tr");
  
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    } 
  }
}