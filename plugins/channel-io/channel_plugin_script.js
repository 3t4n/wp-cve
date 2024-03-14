;
function ch_parseInt(data) {
  if (!data) { return undefined; }
  try {
    return parseInt(data);
  } catch (e) {
    return undefined;
  }
}

window.chSettings = {
  "pluginKey": channel_io_options.channel_io_plugin_key,
  "hideChannelButtonOnBoot": channel_io_options.channel_io_hide_default_launcher === 'on',
  "customLauncherSelector": channel_io_options.channel_io_custom_launcher_selector,
  "mobileMessengerMode": channel_io_options.channel_io_mobile_messenger_mode === 'on' ? 'iframe' : undefined,
  "zIndex": ch_parseInt(channel_io_options.channel_io_z_index),
};
if (channel_io_options.login) {
  chSettings.memberId = channel_io_options.id,
  chSettings.memberHash = channel_io_options.channel_io_member_hash,
  chSettings.profile = {
    "name": channel_io_options.display_name,
    "email": channel_io_options.user_email,
    "mobileNumber": channel_io_options.mobile_number
  }
}
(function() {
  var w = window;
    if (w.ChannelIO) {
    return (window.console.error || window.console.log || function(){})('ChannelIO script included twice.');
  }
  var d = window.document;
  var ch = function() {
    ch.c(arguments);
  };
  ch.q = [];
  ch.c = function(args) {
    ch.q.push(args);
  };
  w.ChannelIO = ch;
  function l() {
    if (w.ChannelIOInitialized) {
      return;
    }
    w.ChannelIOInitialized = true;
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.src = 'https://cdn.channel.io/plugin/ch-plugin-web.js';
    s.charset = 'UTF-8';
    var x = document.getElementsByTagName('script')[0];
    x.parentNode.insertBefore(s, x);
  }
  if (document.readyState === 'complete') {
    l();
  } else if (window.attachEvent) {
    window.attachEvent('onload', l);
  } else {
    window.addEventListener('DOMContentLoaded', l, false);
    window.addEventListener('load', l, false);
  }

  ChannelIO('boot', chSettings);
})();
