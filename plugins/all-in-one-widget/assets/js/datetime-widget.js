function update(widget_id, time_format, date_format) {
  var ampm = " AM";
  var now = new Date();
  var hours = now.getHours();
  var minutes = now.getMinutes();
  var seconds = now.getSeconds();
  var months = new Array("January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December");
  var $date = jQuery("#" + widget_id + " .date");
  var $time = jQuery("#" + widget_id + " .time");

  // Date
  if (date_format != "none") {
    var currentTime = new Date();
    var year = currentTime.getFullYear();
    var month = currentTime.getMonth();
    var day = currentTime.getDate();

    if (date_format == "long") {
      $date.text(months[month] + " " + day + ", " + year);
    }
    else if (date_format == "medium") {
      $date.text(months[month].substring(0, 3) + " " + day + " " + year);
    }
    else if (date_format == "short") {
      $date.text((month + 1) + "/" + day + "/" + year);
    }
    else if (date_format == "european") {
      $date.text(day + "/" + (month + 1) + "/" + year);
    }
  }

  // Time
  if (time_format != "none") {
    if (hours >= 12) {
      ampm = " PM";
    }

    if (minutes <= 9) {
      minutes = "0" + minutes;
    }

    if (seconds <= 9) {
      seconds = "0" + seconds;
    }

    if ((time_format == "12-hour") || (time_format == "12-hour-seconds")) {
      if (hours > 12) {
        hours = hours - 12;
      }

      if (hours === 0) {
        hours = 12;
      }

      if (time_format == "12-hour-seconds") {
        $time.text(hours + ":" + minutes + ":" + seconds + ampm);
      }
      else {
        $time.text(hours + ":" + minutes + ampm);
      }
    }
    else if (time_format == "24-hour-seconds") {
      $time.text(hours + ":" + minutes  + ":" + seconds);
    }
    else {
      $time.text(hours + ":" + minutes);
    }
  }

  // Update clock every second.
  if ((date_format != "none") || (time_format != "none")) {
    setTimeout(function() {
      update(widget_id, time_format, date_format);
    }, 1000);
  }
}