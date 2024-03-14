window.RecaptureAdmin = window.RecaptureAdmin || {};

window.RecaptureAdmin.monitorAccountConnection = function () {
  /*
  Every 2.5 seconds check if we are now connected to Recapture, if so
  refresh the page to show the updated contents
  */
  function checkStatus() {
    jQuery.ajax({
      type: 'POST',
      url: ___recapture.ajax,
      data: {
        action: 'recapture_connection_status',
      },
      success: function (res) {
        if (res.data) {
          location.reload();
        }
      }
    });
  }

  setInterval(checkStatus, 2500);
};
