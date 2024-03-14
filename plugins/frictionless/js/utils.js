const loadChat = () => {
  if (frictionless_chat_settings.se_chat_enable === 'true') {
    WebChat.loadChat({
      apiKey: frictionless_chat_settings.se_chat_api_key,
      agentBubbleBackgroundColor: frictionless_chat_settings.se_chat_agent_bubble_background_color,
      agentBubbleTextColor: frictionless_chat_settings.se_chat_agent_bubble_text_color,
      autoOpen: frictionless_chat_settings.se_chat_auto_open,
      autoOpenDelay: frictionless_chat_settings.se_chat_auto_open_delay,
      autoOpenExpiration: frictionless_chat_settings.se_chat_auto_open_expiration,
      autoOpenMobile: frictionless_chat_settings.se_chat_auto_open_mobile,
      autoOpenMobileDelay: frictionless_chat_settings.se_chat_auto_open_mobile_delay,
      backgroundColor: frictionless_chat_settings.se_chat_background_color,
      bubbleBackgroundColor: frictionless_chat_settings.se_chat_bubble_background_color,
      bubbleTextColor: frictionless_chat_settings.se_chat_bubble_text_color,
      showButton: frictionless_chat_settings.se_chat_show_button,
      showButtonMobile: frictionless_chat_settings.se_chat_show_button_mobile,
      buttonBackgroundColor: frictionless_chat_settings.se_chat_button_background_color,
      buttonText: frictionless_chat_settings.se_chat_button_text,
      buttonTextColor: frictionless_chat_settings.se_chat_button_text_color,
      height: frictionless_chat_settings.se_chat_height,
      initialMessage: frictionless_chat_settings.se_chat_initial_message,
      mobileHeightPercentage: frictionless_chat_settings.se_chat_mobile_height_percentage,
      modal: frictionless_chat_settings.se_chat_modal,
      modalTransparency: frictionless_chat_settings.se_chat_modal_transparency,
      position: frictionless_chat_settings.se_chat_position,
      primaryAccentColor: frictionless_chat_settings.se_chat_primary_accent_color,
      primaryAccentTextColor: frictionless_chat_settings.se_chat_primary_accent_text_color,
      sendButtonBackgroundColor: frictionless_chat_settings.se_chat_send_button_background_color,
      sendButtonTextColor: frictionless_chat_settings.se_chat_send_button_text_color,
      suggestedResponseTextColor: frictionless_chat_settings.se_chat_suggested_response_text_color,
      rememberState: frictionless_chat_settings.se_chat_remember_state,
      title: frictionless_chat_settings.se_chat_title,
      width: frictionless_chat_settings.se_chat_width,
      env: envSettings.env,
      logoUrl: frictionless_chat_settings.se_chat_logo_url,
    });
  }
};

const startBooking = () => {
  ScheduleEngine.show();
};

const setupBookingWidget = () => {
  if (frictionless_booking_settings.se_booking_enable === 'true') {
    loadScript(
      envSettings.bookingUrl,
      [
        {
          key: 'data-api-key',
          value: frictionless_booking_settings.se_booking_api_key,
        },
        {
          key: 'id',
          value: 'se-widget-embed',
        },
        {
          key: 'data-defer',
          value: 'true',
        },
      ],
      setBookEvent,
    );
  }
};

const setBookEvent = () => {
  const bubble = document.getElementsByClassName('booking-widget');
  if (bubble) {
    for (i = 0;i < bubble.length;i++) {
      bubble[i].classList.add('show');
      bubble[i].addEventListener('click', startBooking);
    }
  }
  const selectorClass = frictionless_booking_settings.se_booking_selector_class;
  if (selectorClass) {
    var buttons = document.querySelectorAll(selectorClass);

    for (i = 0;i < buttons.length;i++) {
      buttons[i].addEventListener('click', startBooking);
    }
  }
  const selectorIds = frictionless_booking_settings.se_booking_selector_id.split();
  if (selectorIds) {
    for (let i = 0;i < selectorIds.length;i++) {
      if (document.getElementById(selectorIds[i])) {
        document.getElementById(selectorIds[i]).addEventListener('click', startBooking);
      }
    }
  }
};

/**
 * Load JavaScript from a URL
 *
 * @param {string} url - URL of the script to load
 * @param {Object[]} attrs - Add attributes to the script tag
 * @callback cb - Callback to run after the script has loaded
 */
var loadScript = function (url, attrs, cb) {
  var s = document.createElement('script');
  s.src = url;
  if (attrs) {
    attrs.forEach(function (attr) {
      s.setAttribute(attr.key, attr.value);
    });
  }
  document.head.appendChild(s);
  if (cb) {
    if (s.readyState) {
      // IE
      s.onreadystatechange = function () {
        if (s.readyState === 'loaded' || s.readyState === 'complete') {
          s.onreadystatechange = null;
          cb();
        }
      };
    } else {
      s.onload = function () {
        // Other browsers
        setTimeout(cb, 1000);
      };
    }
  }
};

document.addEventListener('DOMContentLoaded', setupBookingWidget);
document.addEventListener('DOMContentLoaded', loadChat);
