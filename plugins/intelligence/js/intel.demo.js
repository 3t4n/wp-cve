var _ioq = _ioq || [];

function L10iDemo(_ioq, config) {
  var ioq = _ioq;
  var io = _ioq.io;
  var gaCheckInterval;
  var gaCheckIntervalCount = 0;
  var gaEmbedded = 0;

  this.init = function init() {
    ioq.log(ioq.name + ':demo.init()');//

    // check if ga-embed notice exists
    var $gaEmbedError = jQuery('.intel-notice.ga-embed-error');

    // set interval to check for ga existance and test to make sure callbacks are being executed.
    if ($gaEmbedError.length) {
      gaCheckInterval = setInterval(function(){
        if (typeof window.ga === "function") {
          ga(function(tracker) {
            gaEmbedded = 1;
            clearInterval(gaCheckInterval);
            $gaEmbedError.hide();
          });
        }
        if(gaCheckIntervalCount == 1) {
          $gaEmbedError.show();
        }
        gaCheckIntervalCount++;
      }, 1000);
    }


  };

  this.isGaEmbedded = function () {
    return gaEmbedded;
  };

  this.clearVisitor = function clearVisitor() {
    var i, v, a, b;
    var cookies = document.cookie.split(';');
    for (i = 0; i < cookies.length; i++) {
      v = cookies[i].trim();

      if (v.substr(0, 4) == 'l10i') {

        a = v.split('=');
        // deletes standard domain=.[cookieDomain]
        ioq.deleteCookie(a[0]);
        // deletes domain=[cookieDomain]
        b = a[0] + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/;domain=' + ioq.cookieDomain + ';';
        document.cookie = b;
      }
    }
  };

  //this.init();

  _ioq.push(['addCallback', 'domReady', this.init(), this]);
}

_ioq.push(['providePlugin', 'demo', L10iDemo, {}]);