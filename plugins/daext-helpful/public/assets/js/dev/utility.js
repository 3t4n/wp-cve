/**
 * Utility Methods
 */
window.daexthefuUtility = {

  /**
   * The own properties of object b are copied in object a. Object a is then returned.
   *
   * @param a The target object
   * @param b The source object
   * @returns a The object with the properties extended
   */
  extend: function(a, b) {

    'use strict';

    for (let key in b) {
      if (b.hasOwnProperty(key)) {
        a[key] = b[key];
      }
    }
    return a;

  },

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

};