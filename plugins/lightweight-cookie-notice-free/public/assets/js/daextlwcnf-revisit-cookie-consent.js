/**
 * This file is used to generate the revisit consent button and handle its
 * events.
 */
window.daextlwcnfRevisitCookieConsent = (function(utility) {

  'use strict';

  //This object is used to save all the settings -----------------------------------------------------------------------
  let settings = {};

  /**
   * Add the cookie settings HTML at the end of the body.
   */
  function addToDOM() {

    'use strict';

    let html = '';

    html += '<div id="daextlwcnf-revisit-cookie-consent"></div>';

    //Add the revisit cookie button HTML to the DOM
    let revisitCookieConsent = document.createElement('div');
    revisitCookieConsent.id = 'daextlwcnf-revisit-cookie-consent-container';
    if (settings.revisitConsentButtonTooltipText.length > 0) {
      revisitCookieConsent.title = settings.revisitConsentButtonTooltipText;
    }
    revisitCookieConsent.innerHTML = html;
    document.body.appendChild(revisitCookieConsent);

    //Add the cookie icon inside the container
    let iconContainer = document.getElementById(
        'daextlwcnf-revisit-cookie-consent');
    iconContainer.appendChild(generateIconSVG());

  }

  /**
   *
   * Create the SVG  of the revisit cookie icon.
   *
   * @returns {{}|SVGElement|Element|SVGSVGElement|HTMLElement}
   */
  function generateIconSVG() {

    'use strict';

    let svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('viewBox', '0 0 28 28');
    svg.setAttribute('version', '1.0');

    const path1 = document.createElementNS('http://www.w3.org/2000/svg',
        'path');
    path1.setAttribute('class', 'daextlwcnf-icon');
    path1.setAttribute('d',
        'M7,12c-.55,0-1,.45-1,1s.45,1,1,1,1-.45,1-1-.45-1-1-1h0Z');

    const path2 = document.createElementNS('http://www.w3.org/2000/svg',
        'path');
    path2.setAttribute('class', 'daextlwcnf-icon');
    path2.setAttribute('d',
        'M11,6c-.55,0-1,.45-1,1s.45,1,1,1,1-.45,1-1-.45-1-1-1h0Z');

    const path3 = document.createElementNS('http://www.w3.org/2000/svg',
        'path');
    path3.setAttribute('class', 'daextlwcnf-icon');
    path3.setAttribute('d',
        'M21,16c-.55,0-1,.45-1,1s.45,1,1,1,1-.45,1-1-.45-1-1-1h0Z');

    const path4 = document.createElementNS('http://www.w3.org/2000/svg',
        'path');
    path4.setAttribute('class', 'daextlwcnf-icon');
    path4.setAttribute('d',
        'M15,20c-.55,0-1,.45-1,1s.45,1,1,1,1-.45,1-1-.45-1-1-1h0Z');

    const path5 = document.createElementNS('http://www.w3.org/2000/svg',
        'path');
    path5.setAttribute('class', 'daextlwcnf-icon');
    path5.setAttribute('d',
        'M15,12c-.55,0-1,.45-1,1s.45,1,1,1,1-.45,1-1-.45-1-1-1h0Z');

    const path6 = document.createElementNS('http://www.w3.org/2000/svg',
        'path');
    path6.setAttribute('class', 'daextlwcnf-icon');
    path6.setAttribute('d',
        'M9,18c-.55,0-1,.45-1,1s.45,1,1,1,1-.45,1-1-.45-1-1-1h0Z');

    const path7 = document.createElementNS('http://www.w3.org/2000/svg',
        'path');
    path7.setAttribute('class', 'daextlwcnf-icon');
    path7.setAttribute('d',
        'M12.29,2.12c.63,2.27,2.25,4.19,4.42,5.17,.8,1.76,2.24,3.2,4,4,.98,2.17,2.9,3.79,5.17,4.42-.83,5.81-5.84,10.29-11.88,10.29-6.62,0-12-5.38-12-12C2,7.96,6.48,2.95,12.29,2.12m1.71-2.12C6.27,0,0,6.27,0,14s6.27,14,14,14,14-6.27,14-14c-2.71,0-5-1.8-5.74-4.26-1.91-.57-3.43-2.09-4-4-2.46-.74-4.26-3.03-4.26-5.74h0Z');

    svg.appendChild(path1);
    svg.appendChild(path2);
    svg.appendChild(path3);
    svg.appendChild(path4);
    svg.appendChild(path5);
    svg.appendChild(path6);
    svg.appendChild(path7);

    return svg;

  }

  /**
   * Apply the CSS style to the revisit cookie button HTML.
   */
  function applyStyle() {

    'use strict';

    let css = '';
    let declarationSuffix = '';
    if (parseInt(settings.forceCssSpecificity, 10) === 1) {
      declarationSuffix = ' !important';
    }

    // #daextlwcnf-container, #daextlwcnf-container *
    css += '#daextlwcnf-revisit-cookie-consent-container, #daextlwcnf-revisit-cookie-consent-container *{';
    css += 'box-sizing: content-box' + declarationSuffix + ';';
    css += '-webkit-touch-callout: none' + declarationSuffix + ';';
    css += '-webkit-user-select: none' + declarationSuffix + ';';
    css += '-khtml-user-select: none' + declarationSuffix + ';';
    css += '-moz-user-select: none' + declarationSuffix + ';';
    css += '-ms-user-select: none' + declarationSuffix + ';';
    css += 'user-select: none' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-revisit-cookie-consent-container
    css += '#daextlwcnf-revisit-cookie-consent-container{';
    css += 'width: 44px' + declarationSuffix + ';';
    css += 'height: 44px' + declarationSuffix + ';';
    css += 'background: ' + settings.revisitConsentButtonBackgroundColor +
        declarationSuffix + ';';
    css += 'z-index: 999999' + declarationSuffix + ';';
    css += 'position: fixed' + declarationSuffix + ';';
    css += settings.revisitConsentButtonPosition + ': 20px' +
        declarationSuffix + ';';
    css += 'bottom: 20px' + declarationSuffix + ';';
    css += 'cursor: pointer' + declarationSuffix + ';';
    css += 'border-radius: 50%' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-revisit-cookie-consent
    css += '#daextlwcnf-revisit-cookie-consent{';
    css += 'width: 28px' + declarationSuffix + ';';
    css += 'position: absolute' + declarationSuffix + ';';
    css += 'top: 8px' + declarationSuffix + ';';
    css += 'left: 8px' + declarationSuffix + ';';
    css += '}';

    // .daextlwcnf-icon
    css += '.daextlwcnf-icon{';
    css += 'fill: ' + settings.revisitConsentButtonIconColor +
        declarationSuffix + ';';
    css += '}';

    //Add the style element to the DOM
    let style = document.createElement('style');
    style.innerHTML = css;
    style.id = 'daextlwcnf-revisit-cookie-consent-style';
    document.head.appendChild(style);

  }

  /**
   * Bind all the event listeners.
   */
  function bindEventListeners() {

    'use strict';

    //Add click event listener on the revisit cookie button
    let bt = document.getElementById(
        'daextlwcnf-revisit-cookie-consent-container');
    if (bt) {
      bt.addEventListener('click', function() {

        //Clear the cookies
        utility.deleteCookie('daextlwcnf-accepted');

        //Reload the page
        window.location.reload(false);

      });
    }

  }

  //Return an object exposed to the public -----------------------------------------------------------------------------
  return {

    initialize: function(configuration) {

      //Merge the custom configuration provided by the user with the default configuration
      settings = configuration;

      //Do not continue if the revisit cookie consent is disabled
      if (parseInt(settings.revisitConsentButtonEnable, 10) === 0) {
        return;
      }

      //Add the news ticker to the DOM
      addToDOM();

      //Apply the style available in the settings
      applyStyle();

      //Bind event listeners
      bindEventListeners();

    },

  };

}(daextlwcnfUtility));