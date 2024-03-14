/**
 * Utility Methods
 */
window.daextlwcnfUtility = {

  /**
   * Given the name of the cookie the cookie value is returned if the cookie is found. If the cookie is not found false
   * is returned.
   *
   * @param cname The name of the cookie
   * @returns mixed The value of the cookie if the cookie is found or false if the cookie is not found
   */
  getCookie: function(cname) {

    'use strict';

    let name = cname + '=';
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return false;
  },

  /**
   * Deletes the specified cookie.
   *
   * @param name The name of the cookie
   */
  deleteCookie: function(name) {

    'use strict';

    document.cookie = name +
        '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';

  },

  /**
   * Closes the cookie notice.
   *
   * @param name
   */
  closeNotice: function(name) {

    'use strict';

    //Remove the notice from the DOM
    document.getElementById('daextlwcnf-cookie-notice-container').remove();

    //Remove the mask from the DOM
    let cm = document.getElementById('daextlwcnf-cookie-notice-container-mask');
    if (cm) {
      cm.remove();
    }

  },

  /**
   * Set a cookie based on the provided parameters.
   *
   * @param name The name of the cookie.
   * @param value The value of the cookie.
   * @param The expiration in seconds.
   */
  setCookie: function(name, value, expiration) {

    'use strict';

    let now = new Date();
    let time = now.getTime();
    let expireTime = time + (expiration * 1000);
    now.setTime(expireTime);
    let formattedExpiration = now.toUTCString();
    document.cookie = name + '=' + value + '; expires=' + formattedExpiration +
        '; path=/';

  },

  hexToRgb: function(hex) {

    'use strict';

    let result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
      r: parseInt(result[1], 16),
      g: parseInt(result[2], 16),
      b: parseInt(result[3], 16),
    } : null;


  },

  /**
   * This method does what follows:
   *
   * - Set the cookie "daextlwcnf-accepted" used to save the cookie acceptance.
   *
   * @param settings
   */
  acceptCookies: function(settings) {

    'use strict';

    //Set the cookie used to save the cookie acceptance
    this.setCookie('daextlwcnf-accepted', '1', settings.cookieExpiration);

  },

  /**
   * Reloads the current page if the "Reload Page" option is enabled.
   *
   * @param settings
   */
  reload: function(settings) {

    if (parseInt(settings.reloadPage, 10) === 1) {
      window.location.reload(false);
    }

  },

};