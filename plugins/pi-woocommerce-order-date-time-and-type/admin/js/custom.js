jQuery(function ($) {
  $(
    "#pi_delivery_start_time, #pi_pickup_start_time, #pi_pickup_end_time, #pi_delivery_end_time"
  ).timepicker({ scrollbar: true, dynamic: false });

  $("#pi_delivery_end_time").timepicker({
    minTime: $("#pi_delivery_start_time").val()
  });

  $("#pi_pickup_end_time").timepicker({
    minTime: $("#pi_pickup_start_time").val()
  });

  $("#pi_delivery_start_time").timepicker("option", "change", function () {
    $("#pi_delivery_end_time").val("");
    $("#pi_delivery_end_time").timepicker(
      "option",
      "minTime",
      $("#pi_delivery_start_time").val()
    );
  });

  $("#pi_pickup_start_time").timepicker("option", "change", function () {
    $("#pi_pickup_end_time").val("");
    $("#pi_pickup_end_time").timepicker(
      "option",
      "minTime",
      $("#pi_pickup_start_time").val()
    );
  });

  $("body").on("focus", ".pisol-date-picker", function () {
    $.datepicker.setDefaults($.datepicker.regional["en"]);
    $(this).datepicker({
      dateFormat: "yy/mm/dd"
    });
  });

  $("#pi_order_preparation_days").on("change", function () {
    var value = parseInt($(this).val());
    if (value > 0) {
      preparationHours(false);
    } else {
      preparationHours(true);
    }

    if (value >= 2) {
      nextDayCutOffTime(false);
    } else {
      nextDayCutOffTime(true);
    }

    if (value == 0) {
      sameDayCutOffTime(true);
    } else {
      sameDayCutOffTime(false);
    }

  });

  function nextDayCutOffTime(show) {
    if (show) {
      jQuery("#row_pi_next_day_delivery_cutoff_time-pro").fadeIn();
      jQuery("#row_pi_next_day_pickup_cutoff_time-pro").fadeIn();
    } else {
      jQuery("#row_pi_next_day_delivery_cutoff_time-pro").fadeOut();
      jQuery("#row_pi_next_day_pickup_cutoff_time-pro").fadeOut();
    }
  }

  function sameDayCutOffTime(show) {
    if (show) {
      jQuery("#row_pi_same_day_delivery_cutoff_time-pro").fadeIn();
      jQuery("#row_pi_same_day_pickup_cutoff_time-pro").fadeIn();
    } else {
      jQuery("#row_pi_same_day_delivery_cutoff_time-pro").fadeOut();
      jQuery("#row_pi_same_day_pickup_cutoff_time-pro").fadeOut();
    }
  }

  $("#pi_order_preparation_days").trigger("change");

  function preparationHours(state) {
    if (state) {
      $("#row_pi_order_preparation_hours").fadeIn();
    } else {
      $("#row_pi_order_preparation_hours").fadeOut();
    }
  }
  var ids = ["pi_delivery_start_time", "pi_delivery_end_time", "pi_pickup_start_time", "pi_pickup_end_time", "pi_delivery_sunday_start_time", "pi_delivery_sunday_end_time", "pi_pickup_sunday_start_time", "pi_pickup_sunday_end_time", "pi_delivery_monday_start_time", "pi_delivery_monday_end_time", "pi_pickup_monday_start_time", "pi_pickup_monday_end_time", "pi_delivery_tuesday_start_time", "pi_delivery_tuesday_end_time", "pi_pickup_tuesday_start_time", "pi_pickup_tuesday_end_time", "pi_delivery_wednesday_start_time", "pi_delivery_wednesday_end_time", "pi_pickup_wednesday_start_time", "pi_pickup_wednesday_end_time", "pi_delivery_thursday_start_time", "pi_delivery_thursday_end_time", "pi_pickup_thursday_start_time", "pi_pickup_thursday_end_time", "pi_delivery_friday_start_time", "pi_delivery_friday_end_time", "pi_pickup_friday_start_time", "pi_pickup_friday_end_time", "pi_delivery_saturday_start_time", "pi_delivery_saturday_end_time", "pi_pickup_saturday_start_time", "pi_pickup_saturday_end_time"];
  $.each(ids, function (index, value) {
    $("#" + value).css("text-align", "left");
    clearValue(value);
  });

  function clearValue(id) {
    $("<a class='pi-clear-value btn btn-danger text-light'>Clear Value</a>").insertAfter("#" + id);
  }
  $(".pi-clear-value").on("click", function () {
    $("input", $(this).parent()).val("");
  });

  var pickup_time_ids = ["#row_pi_pickup_start_time", "#row_pi_pickup_end_time", "#row_pi_pickup_sunday_start_time", "#row_pi_pickup_sunday_end_time", "#row_pi_pickup_monday_start_time", "#row_pi_pickup_monday_end_time", "#row_pi_pickup_tuesday_start_time", "#row_pi_pickup_tuesday_end_time", "#row_pi_pickup_wednesday_start_time", "#row_pi_pickup_wednesday_end_time", "#row_pi_pickup_thursday_start_time", "#row_pi_pickup_thursday_end_time", "#row_pi_pickup_friday_start_time", "#row_pi_pickup_friday_end_time", "#row_pi_pickup_saturday_start_time", "#row_pi_pickup_saturday_end_time"];

  var delivery_time_ids = ["#row_pi_delivery_start_time", "#row_pi_delivery_end_time", "#row_pi_delivery_sunday_start_time", "#row_pi_delivery_sunday_end_time", "#row_pi_delivery_monday_start_time", "#row_pi_delivery_monday_end_time", "#row_pi_delivery_tuesday_start_time", "#row_pi_delivery_tuesday_end_time", "#row_pi_delivery_wednesday_start_time", "#row_pi_delivery_wednesday_end_time", "#row_pi_delivery_thursday_start_time", "#row_pi_delivery_thursday_end_time", "#row_pi_delivery_friday_start_time", "#row_pi_delivery_friday_end_time", "#row_pi_delivery_saturday_start_time", "#row_pi_delivery_saturday_end_time"];

  hide_time_based_on_delivery_type(pickup_time_ids, delivery_time_ids);

  function hide_time_based_on_delivery_type(pickup_time_ids, delivery_time_ids) {
    var type = pi_dtt_settings.delivery_type;
    if (type == 'Both') return;

    if (type == 'Delivery') {
      pi_hide_rows(pickup_time_ids);
    }

    if (type == 'Pickup') {
      pi_hide_rows(delivery_time_ids);
    }
  }

  function pi_hide_rows($ids) {
    var $ = jQuery;
    $.each($ids, function (index, value) {
      $(value).fadeOut();
    });
  }

  function hideProFeature() {
    var load_status = localStorage.getItem('pisol-dtt-pro-feature-state');
    if (load_status == '' || load_status == undefined || load_status == 'show') {
      jQuery("#hid-pro-feature").html('Hide Pro feature');
      jQuery(".free-version, #promotion-sidebar, .hide-pro").fadeIn();
    } else {
      jQuery("#hid-pro-feature").html('Show Pro feature');
      jQuery(".free-version, #promotion-sidebar, .hide-pro").fadeOut();
    }

    jQuery("#hid-pro-feature").on("click", function () {
      var state = localStorage.getItem('pisol-dtt-pro-feature-state');
      if (state == '' || state == undefined || state == 'show') {
        localStorage.setItem('pisol-dtt-pro-feature-state', 'hidden');
        jQuery("#hid-pro-feature").html('Show Pro feature');
        jQuery(".free-version, #promotion-sidebar, .hide-pro").fadeOut();
      } else {
        localStorage.setItem('pisol-dtt-pro-feature-state', 'show');
        jQuery("#hid-pro-feature").html('Hide Pro feature');
        jQuery(".free-version, #promotion-sidebar, .hide-pro").fadeIn();
      }
    });
  }

  hideProFeature();
  /**
   * selectwo0
   */
  jQuery("#pi_delivery_days, #pi_pickup_days, #pi_dtt_remove_billing_when_pickup-pro, #pi_dtt_remove_billing_when_delivery-pro").selectWoo();

});
