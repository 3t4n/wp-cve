(function (d, s, id) {
  var js,
    fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s);
  js.id = id;
  js.src =
    "https://cdn.channelize.io/live-broadcast/wrappers/woocommerce/prod/client.js";
  fjs.parentNode.insertBefore(js, fjs);
})(document, "script", "channelize-wordpress-livebroadcast-id");

var { settings = {}, ...rest } = window.initChannelizeLivebroadcastWCOptions || {} ;
window.initChannelizeLivebroadcastWCOptions = {
  userId: php_vars.userId,
  accessToken: php_vars.accessToken,
  publicKey: php_vars.publicKey,
  settings: {
    locale: php_vars.locale,
    currency: php_vars.currency,
    cartUrl: php_vars.cartUrl,
    enableMiniPlayer: php_vars.enableMiniPlayer == 'true' ? true : false,
    share: {
      baseUrl: php_vars.streamPageUrl,
    },
    ...settings,
  },
  ajaxApi: {
    baseUrl: php_vars.url,
  },
  whenNotAllowedToWatch: function (liveBroadcast) {
    // Redirect to login URL
    window.location =
      php_vars.loginUrl +
      "?redirect_to=" +
      php_vars.streamPageUrl +
      liveBroadcast.id;
  },
  ...rest,
};
