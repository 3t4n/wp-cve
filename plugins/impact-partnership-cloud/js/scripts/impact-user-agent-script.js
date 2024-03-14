var impactHelpers = {
  getDate: function () {
    var n = new Date;
    n.setTime(n.getTime() + 24 * 60 * 60 * 60 * 1e3);
    return "expires=" + n.toUTCString();
  }
};

!(function() {
  var value = ('; ' + document.cookie).split(`; customer_user_agent=`).pop().split(';')[0];
  if (value) {
    return;
  }
  var c = impactHelpers.getDate();
  document.cookie = "customer_user_agent=" + escape(window.navigator.userAgent) + ";SameSite=None;" + c + ";path=/;secure";
})();

!(function () {
  jQuery.get("https://api64.ipify.org/?format=json", function(response) {
    if (!response) {
      return;
    }
    var value = ('; ' + document.cookie).split(`; customer_ip_address=`).pop().split(';')[0];
    if (value) {
      return
    }
    var c = impactHelpers.getDate();
    document.cookie = "customer_ip_address=" + escape(response.ip) + ";SameSite=None;" + c + ";path=/;secure";
  }).catch(function (err) {
    console.error(err);
  });
})();

