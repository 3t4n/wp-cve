jQuery(document).ready(function($) {
  let paymentWindow;

  function openPaymentWindow(urlP) {
    paymentWindow = window.open(urlP, '_self');
    /*let interval = setInterval(function(){
      if(paymentWindow.closed){
        clearInterval(interval);
        jQuery.ajax({
          type: "POST",
          url: my_plugin_data.ajax_url,
          dataType: 'json',
          data: {action : 'get_redirect_link'},
          success: function(json) {
            console.log(json);
              if (json.redirect_link) {
                  // Redirect user to the URL in the response
                  window.location.replace(json.redirect_link);
              }
          },
          error: function(xhr, status, error) {
            console.log(xhr);
            console.log(xhr.responseText);
          }
      });
      }
    }, 1000)*/
  }
  

  // Check if payment redirect URL is present in query string
  var redirectUrl = getParameterByName('pisp_payment_redirect');

  // If redirect URL is present, open payment window and pass redirect URL as parameter
  if (redirectUrl) {
    openPaymentWindow(redirectUrl);
  }

  function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
  }
});


