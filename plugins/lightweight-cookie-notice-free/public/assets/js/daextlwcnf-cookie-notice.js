/**
 * This file is used to generate the cookie notice and handle its events.
 */
window.daextlwcnfCookieNotice = (function(utility, cookieSettings, revisitCookieConsent) {

  'use strict';

  //This object is used to save all the settings -----------------------------------------------------------------------
  let settings = {};

  //This object is used to save all the variable states of the cookie notice ---------------------------------------------
  let states = {
    vibrating: false,
  };

  /**
   * Add the cookie notice HTML at the end of the body.
   */
  function addToDOM() {

    'use strict';

    let html = '';

    html += '<div id="daextlwcnf-cookie-notice-wrapper">';
    html += '<div id="daextlwcnf-cookie-notice-message">' +
        settings.cookieNoticeMainMessageText + '</div>';

    html += '<div id="daextlwcnf-cookie-notice-button-container">';
    if (parseInt(settings.cookieNoticeButton1Action, 10) !== 0) {
      html += '<div id="daextlwcnf-cookie-notice-button-1">' +
          settings.cookieNoticeButton1Text + '</div>';
    }

    if (parseInt(settings.cookieNoticeButton2Action, 10) !== 0) {
      html += '<div id="daextlwcnf-cookie-notice-button-2">' +
          settings.cookieNoticeButton2Text + '</div>';
    }
    if (parseInt(settings.cookieNoticeButtonDismissAction, 10) !== 0) {
      html += '<div id="daextlwcnf-cookie-notice-button-dismiss"></div>';
    }
    html += '</div>'; // #daextlwcnf-button-container
    html += '</div>'; // #daextlwcnf-wrapper

    //Add the cookie notice HTML block
    let cookieNotice = document.createElement('div');
    cookieNotice.id = 'daextlwcnf-cookie-notice-container';
    cookieNotice.innerHTML = html;
    document.body.appendChild(cookieNotice);

    //Add the mask HTML if enabled
    if (parseInt(settings.cookieNoticeMask, 10) === 1) {
      let cookieNoticeMask = document.createElement('div');
      cookieNoticeMask.id = 'daextlwcnf-cookie-notice-container-mask';
      document.body.appendChild(cookieNoticeMask);
    }

  }

  /**
   * Apply the style defined in the settings to the cookie notice.
   */
  function applyStyle() {

    'use strict';

    let css = '';
    let declarationSuffix = '';
    if (parseInt(settings.forceCssSpecificity, 10) === 1) {
      declarationSuffix = ' !important';
    }

    // #daextlwcnf-container-mask
    css += '#daextlwcnf-cookie-notice-container-mask{';
    css += 'background: ' + settings.cookieNoticeMaskColor + declarationSuffix +
        ';';
    css += 'opacity: ' + parseFloat(settings.cookieNoticeMaskOpacity) +
        declarationSuffix + ';';
    css += 'width: 100%' + declarationSuffix + ';';
    css += 'position: fixed' + declarationSuffix + ';';
    css += 'height: 100%' + declarationSuffix + ';';
    css += 'top: 0' + declarationSuffix + ';';
    css += 'left: 0' + declarationSuffix + ';';
    css += 'z-index: 999999996' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-container, #daextlwcnf-container *
    css += '#daextlwcnf-cookie-notice-container, #daextlwcnf-cookie-notice-container *{';
    css += 'box-sizing: content-box' + declarationSuffix + ';';
    css += '-webkit-touch-callout: none' + declarationSuffix + ';';
    css += '-webkit-user-select: none' + declarationSuffix + ';';
    css += '-khtml-user-select: none' + declarationSuffix + ';';
    css += '-moz-user-select: none' + declarationSuffix + ';';
    css += '-ms-user-select: none' + declarationSuffix + ';';
    css += 'user-select: none' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-container
    css += '#daextlwcnf-cookie-notice-container{';
    css += 'position: fixed' + declarationSuffix + ';';
    css += 'bottom: 0' + declarationSuffix + ';';

    css += 'z-index: 999999999' + declarationSuffix + ';';
    css += 'height: fit-content' + declarationSuffix + ';';
    if (parseInt(settings.cookieNoticeContainerPosition, 10) === 0 ||
        parseInt(settings.cookieNoticeContainerPosition, 10) === 2) {
      css += 'left: -10px;';
    } else {
      css += 'left: 0;';
    }

    css += 'background: ' + settings.cookieNoticeContainerBackgroundColor +
        declarationSuffix + ';';
    css += 'color: #ffffff' + declarationSuffix + ';';
    css += 'opacity: ' + parseFloat(settings.cookieNoticeContainerOpacity) +
        declarationSuffix + ';';
    css += 'border-color: rgba(' +
        utility.hexToRgb(settings.cookieNoticeContainerBorderColor).r + ',' +
        utility.hexToRgb(settings.cookieNoticeContainerBorderColor).g + ',' +
        utility.hexToRgb(settings.cookieNoticeContainerBorderColor).b + ', ' +
        parseFloat(settings.cookieNoticeContainerBorderOpacity) + ')' +
        declarationSuffix + ';';
    css += 'border-style: solid' + declarationSuffix + ';';
    css += 'z-index: 999999997' + declarationSuffix + ';';
    let drop_shadow_value = 'none';
    if (parseInt(settings.cookieNoticeContainerDropShadow, 10) === 1) {
      drop_shadow_value = 'rgba(' +
          utility.hexToRgb(settings.cookieNoticeContainerDropShadowColor).r +
          ', ' +
          utility.hexToRgb(settings.cookieNoticeContainerDropShadowColor).g +
          ', ' +
          utility.hexToRgb(settings.cookieNoticeContainerDropShadowColor).b +
          ', 0.08) 0px 0px 0px 1px, rgba(' +
          utility.hexToRgb(settings.cookieNoticeContainerDropShadowColor).r +
          ', ' +
          utility.hexToRgb(settings.cookieNoticeContainerDropShadowColor).g +
          ', ' +
          utility.hexToRgb(settings.cookieNoticeContainerDropShadowColor).b +
          ', 0.08) 0px 2px 1px, rgba(' +
          utility.hexToRgb(settings.cookieNoticeContainerDropShadowColor).r +
          ', ' +
          utility.hexToRgb(settings.cookieNoticeContainerDropShadowColor).g +
          ', ' +
          utility.hexToRgb(settings.cookieNoticeContainerDropShadowColor).b +
          ', 0.31) 0px 0px 20px -6px' + declarationSuffix + ';';
    }
    css += 'box-shadow: ' + drop_shadow_value + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-message
    css += '#daextlwcnf-cookie-notice-wrapper{';
    css += 'max-width: calc(' +
        parseInt(settings.cookieNoticeContainerWidth, 10) + 'px)' +
        declarationSuffix + ';';
    css += 'margin: 0 auto' + declarationSuffix + ';';
    css += 'display: flex' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-notice-message
    css += '#daextlwcnf-cookie-notice-message{';
    css += 'padding: 0' + declarationSuffix + ';';
    css += 'font-size: 13px' + declarationSuffix + ';';
    css += 'line-height: 20px' + declarationSuffix + ';';
    css += 'font-style: normal' + declarationSuffix + ';';
    css += 'color: ' + settings.cookieNoticeMainMessageFontColor +
        declarationSuffix + ';';
    css += 'width: calc(100% - 410px)' + declarationSuffix + ';';
    css += 'font-family: ' + settings.paragraphsFontFamily + declarationSuffix +
        ';';
    css += 'font-weight: ' + parseInt(settings.paragraphsFontWeight, 10) +
        declarationSuffix + ';';
    css += '}';

    /**
     * Apply a style for the HTML tags allowed with wp_kses():
     *
     * - a
     * - p
     * - strong
     * - br
     * - ol
     * - ul
     * - li
     */

    css += '#daextlwcnf-cookie-notice-message  a{';
    css += 'text-decoration: none' + declarationSuffix + ';';
    css += 'font-size: 13px' + declarationSuffix + ';';
    css += 'font-family: ' + settings.paragraphsFontFamily + declarationSuffix +
        ';';
    css += 'font-weight: 400' + declarationSuffix + ';';
    css += 'font-style: normal' + declarationSuffix + ';';
    css += 'color: ' + settings.cookieNoticeMainMessageLinkFontColor +
        declarationSuffix + ';';
    css += '}';

    css += '#daextlwcnf-cookie-notice-message a:hover{';
    css += 'text-decoration: underline' + declarationSuffix + ';';
    css += '}';

    css += '#daextlwcnf-cookie-notice-message p{';
    css += 'margin: 0 0 20px 0' + declarationSuffix + ';';
    css += 'font-size: 13px' + declarationSuffix + ';';
    css += 'font-family: ' + settings.paragraphsFontFamily + declarationSuffix +
        ';';
    css += 'font-weight: 400' + declarationSuffix + ';';
    css += 'font-style: normal' + declarationSuffix + ';';
    css += 'color: ' + settings.cookieNoticeMainMessageFontColor +
        declarationSuffix + ';';
    css += '}';

    css += '#daextlwcnf-cookie-notice-message strong{';
    css += 'font-weight: ' + parseInt(settings.strongTagsFontWeight, 10) +
        declarationSuffix + ';';
    css += 'font-size: 13px' + declarationSuffix + ';';
    css += 'font-family: ' + settings.paragraphsFontFamily + declarationSuffix +
        ';';
    css += 'font-style: normal' + declarationSuffix + ';';
    css += 'color: ' + settings.cookieNoticeMainMessageFontColor +
        declarationSuffix + ';';
    css += '}';

    css += '#daextlwcnf-cookie-notice-message p:last-child{';
    css += 'margin: 0' + declarationSuffix + ';';
    css += '}';

    css += '#daextlwcnf-cookie-notice-message ol{';
    css += 'margin: 0 0 20px 20px' + declarationSuffix + ';';
    css += 'list-style: decimal outside none' + declarationSuffix + ';';
    css += 'padding: 0' + declarationSuffix + ';';
    css += '}';

    css += '#daextlwcnf-cookie-notice-message ol:last-child{';
    css += 'margin: 0 0 0 20px' + declarationSuffix + ';';
    css += '}';

    css += '#daextlwcnf-cookie-notice-message ul{';
    css += 'margin: 0 0 20px 20px' + declarationSuffix + ';';
    css += 'list-style: disc outside none' + declarationSuffix + ';';
    css += 'padding: 0' + declarationSuffix + ';';
    css += '}';

    css += '#daextlwcnf-cookie-notice-message ul:last-child{';
    css += 'margin: 0 0 0 20px' + declarationSuffix + ';';
    css += '}';

    css += '#daextlwcnf-cookie-notice-message li{';
    css += 'margin: 0' + declarationSuffix + ';';
    css += 'line-height: 20px' + declarationSuffix + ';';
    css += 'font-size: 13px' + declarationSuffix + ';';
    css += 'font-family: ' + settings.paragraphsFontFamily + declarationSuffix +
        ';';
    css += 'font-weight: 400' + declarationSuffix + ';';
    css += 'font-style: normal' + declarationSuffix + ';';
    css += 'color: ' + settings.cookieNoticeMainMessageFontColor +
        declarationSuffix + ';';
    css += '}';

    // End

    // #daextlwcnf-cookie-notice-button-container
    css += '#daextlwcnf-cookie-notice-button-container{';
    css += 'margin-left: 40px' + declarationSuffix + ';';
    css += 'display: flex' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-notice-button-1
    css += '#daextlwcnf-cookie-notice-button-1{';
    css += 'padding: 10px' + declarationSuffix + ';';
    css += 'width: 180px' + declarationSuffix + ';';
    css += 'text-align: center' + declarationSuffix + ';';
    css += 'background: ' + settings.cookieNoticeButton1BackgroundColor +
        declarationSuffix + ';';
    css += 'font-size: 13px' + declarationSuffix + ';';
    css += 'font-family: ' + settings.buttonsFontFamily + declarationSuffix +
        ';';
    css += 'font-weight: ' + parseInt(settings.buttonsFontWeight, 10) +
        declarationSuffix + ';';
    css += 'font-style: normal' + declarationSuffix + ';';
    css += 'color: ' + settings.cookieNoticeButton1FontColor +
        declarationSuffix + ';';
    css += 'display: inline-block' + declarationSuffix + ';';
    css += 'cursor: pointer' + declarationSuffix + ';';
    css += 'border-radius: ' + parseInt(settings.buttonsBorderRadius, 10) +
        'px' + declarationSuffix + ';';
    css += 'border: 1px solid ' + settings.cookieNoticeButton1BorderColor +
        declarationSuffix + ';';
    css += 'width: calc(50% - 5px)' + declarationSuffix + ';';
    css += 'line-height: 18px' + declarationSuffix + ';';
    css += 'height: 18px' + declarationSuffix + ';';
    css += 'white-space: nowrap' + declarationSuffix + ';';
    css += 'overflow: hidden' + declarationSuffix + ';';
    css += '}';

    css += '#daextlwcnf-cookie-notice-button-1:hover{';
    css += 'background: ' + settings.cookieNoticeButton1BackgroundColorHover +
        declarationSuffix + ';';
    css += 'border: 1px solid ' + settings.cookieNoticeButton1BorderColorHover +
        declarationSuffix + ';';
    css += 'color: ' + settings.cookieNoticeButton1FontColorHover +
        declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-notice-button-2
    css += '#daextlwcnf-cookie-notice-button-2{';
    css += 'padding: 10px' + declarationSuffix + ';';
    css += 'margin-left: 10px' + declarationSuffix + ';';
    css += 'width: 180px' + declarationSuffix + ';';
    css += 'text-align: center' + declarationSuffix + ';';
    css += 'background: ' + settings.cookieNoticeButton2BackgroundColor +
        declarationSuffix + ';';
    css += 'font-size: 13px' + declarationSuffix + ';';
    css += 'font-family: ' + settings.buttonsFontFamily + declarationSuffix +
        ';';
    css += 'font-weight: ' + parseInt(settings.buttonsFontWeight, 10) +
        declarationSuffix + ';';
    css += 'font-style: normal' + declarationSuffix + ';';
    css += 'color: ' + settings.cookieNoticeButton2FontColor +
        declarationSuffix + ';';
    css += 'display: inline-block' + declarationSuffix + ';';
    css += 'cursor: pointer' + declarationSuffix + ';';
    css += 'border-radius: ' + parseInt(settings.buttonsBorderRadius, 10) +
        'px' + declarationSuffix + ';';
    css += 'border: 1px solid ' + settings.cookieNoticeButton2BorderColor +
        declarationSuffix + ';';
    css += 'width: calc(50% - 5px)' + declarationSuffix + ';';
    css += 'line-height: 18px' + declarationSuffix + ';';
    css += 'height: 18px' + declarationSuffix + ';';
    css += 'white-space: nowrap' + declarationSuffix + ';';
    css += 'overflow: hidden' + declarationSuffix + ';';
    css += '}';

    css += '#daextlwcnf-cookie-notice-button-2:hover{';
    css += 'background: ' + settings.cookieNoticeButton2BackgroundColorHover +
        declarationSuffix + ';';
    css += 'border: 1px solid ' + settings.cookieNoticeButton2BorderColorHover +
        declarationSuffix + ';';
    css += 'color: ' + settings.cookieNoticeButton2FontColorHover +
        declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-notice-button-dismiss
    css += '#daextlwcnf-cookie-notice-button-dismiss{';
    css += 'width: 20px' + declarationSuffix + ';';
    css += 'height: 20px' + declarationSuffix + ';';
    css += 'display: inline-block' + declarationSuffix + ';';
    css += 'margin: 10px 0 10px 10px' + declarationSuffix + ';';
    css += 'cursor: pointer' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-notice-button-dismiss > svg
    css += '#daextlwcnf-cookie-notice-button-dismiss > svg{';
    css += 'display: block' + declarationSuffix + ';';
    css += '}';

    switch (parseInt(settings.cookieNoticeContainerPosition)) {

        //Top
      case 0:
        css += '#daextlwcnf-cookie-notice-container{';
        css += 'padding: 20px 34px' + declarationSuffix + ';';
        css += 'width: calc(100% - 48px)' + declarationSuffix + ';';
        css += 'top: 0' + declarationSuffix + ';';
        css += 'border-width: 0 0 ' +
            parseInt(settings.cookieNoticeContainerBorderWidth, 10) + 'px 0' +
            declarationSuffix + ';';

        css += '}';
        break;

        //Center
      case 1:
        css += '#daextlwcnf-cookie-notice-container{';
        css += 'width: 300px' + declarationSuffix + ';';
        css += 'height: fit-content' + declarationSuffix + ';';
        css += 'left: calc(50% - 174px);';
        css += 'border-width: 0' + declarationSuffix + ';';
        css += 'padding: 20px 24px' + declarationSuffix + ';';
        css += 'border-radius: ' +
            parseInt(settings.containersBorderRadius, 10) + 'px' +
            declarationSuffix + ';';
        css += '}';

        //#daextlwcnf-cookie-notice-wrapper
        css += '#daextlwcnf-cookie-notice-wrapper{';
        css += 'flex-direction: column' + declarationSuffix + ';';
        css += '}';

        css += '#daextlwcnf-cookie-notice-container #daextlwcnf-cookie-notice-message{';
        css += 'width: 100%' + declarationSuffix + ';';
        css += '}';

        css += '#daextlwcnf-cookie-notice-button-container{';
        css += 'width: 100%' + declarationSuffix + ';';
        css += '}';

        css += '#daextlwcnf-cookie-notice-container #daextlwcnf-cookie-notice-button-container{';
        css += 'width: 100%' + declarationSuffix + ';';
        css += '}';

        css += '#daextlwcnf-cookie-notice-button-container{';
        css += 'margin: 0' + declarationSuffix + ';';
        css += '}';

        css += '#daextlwcnf-cookie-notice-button-container{';
        css += 'display: block' + declarationSuffix + ';';
        css += '}';

        css += '#daextlwcnf-cookie-notice-container #daextlwcnf-cookie-notice-button-1{';
        css += 'display: block' + declarationSuffix + ';';
        css += 'width: calc(100% - 22px)' + declarationSuffix + ';';
        css += 'margin: 20px 0 0 0' + declarationSuffix + ';';
        css += '}';

        css += '#daextlwcnf-cookie-notice-container #daextlwcnf-cookie-notice-button-2{';
        css += 'display: block' + declarationSuffix + ';';
        css += 'width: calc(100% - 22px)' + declarationSuffix + ';';
        css += 'margin: 20px 0 0 0' + declarationSuffix + ';';
        css += '}';

        // #daextlwcnf-button-dismiss
        css += '#daextlwcnf-cookie-notice-button-dismiss{';
        css += 'position: absolute' + declarationSuffix + ';';
        css += 'top: 0' + declarationSuffix + ';';
        css += 'right: 0' + declarationSuffix + ';';
        css += 'margin: 4px 4px 0 0' + declarationSuffix + ';';
        css += '}';

        // #media query
        css += '@media only screen and (max-width: ' +
            settings.responsiveBreakpoint + 'px){';

        css += '#daextlwcnf-cookie-notice-container{';
        css += 'width: calc(100% - 48px)' + declarationSuffix + ';';
        css += 'padding: 20px 34px' + declarationSuffix + ';';
        css += 'height: fit-content' + declarationSuffix + ';';
        css += 'left: -10px' + ';';
        css += 'border-radius: 0' + declarationSuffix + ';';
        css += '}';

        css += '}';

        break;

        //Bottom
      case 2:
        css += '#daextlwcnf-cookie-notice-container{';
        css += 'padding: 20px 34px' + declarationSuffix + ';';
        css += 'width: calc(100% - 48px)' + declarationSuffix + ';';
        css += 'bottom: 0' + declarationSuffix + ';';
        css += 'border-width: ' +
            parseInt(settings.cookieNoticeContainerBorderWidth, 10) + 'px 0 0' +
            declarationSuffix + ';';
        css += '}';
        break;

    }

    //CSS changes associated with the presence of the buttons

    /**
     * Button 1: Active
     * Button 2: Inactive
     * Close Button: Inactive
     *
     * or
     *
     * Button 1: Inactive
     * Button 2: Active
     * Close Button: Inactive
     */
    if ((parseInt(settings.cookieNoticeButton1Action, 10) !== 0 &&
        parseInt(settings.cookieNoticeButton2Action, 10) === 0 &&
        parseInt(settings.cookieNoticeButtonDismissAction, 10) === 0) ||
        (parseInt(settings.cookieNoticeButton1Action, 10) === 0 &&
            parseInt(settings.cookieNoticeButton2Action, 10) !== 0 &&
            parseInt(settings.cookieNoticeButtonDismissAction, 10) === 0)
    ) {
      css += '#daextlwcnf-cookie-notice-button-container{';
      css += 'width: 180px' + declarationSuffix + ';';
      css += '}';

      css += '#daextlwcnf-cookie-notice-message{';
      css += 'width: calc(100% - 220px)' + declarationSuffix + ';';
      css += '}';

      css += '#daextlwcnf-cookie-notice-button-1,';
      css += '#daextlwcnf-cookie-notice-button-2{';
      css += 'width: 158px' + declarationSuffix + ';';
      css += 'margin-left: 0' + declarationSuffix + ';';
      css += '}';

      css += '#daextlwcnf-cookie-notice-button-dismiss{';
      css += 'display: none' + declarationSuffix + ';';
      css += '}';
    }

    /**
     * Button 1: Active
     * Button 2: Inactive
     * Close Button: Active
     *
     * or
     *
     * Button 1: Inactive
     * Button 2: Active
     * Close Button: Active
     */
    if ((parseInt(settings.cookieNoticeButton1Action, 10) !== 0 &&
        parseInt(settings.cookieNoticeButton2Action, 10) === 0 &&
        parseInt(settings.cookieNoticeButtonDismissAction, 10) !== 0) ||
        (parseInt(settings.cookieNoticeButton1Action, 10) === 0 &&
            parseInt(settings.cookieNoticeButton2Action, 10) !== 0 &&
            parseInt(settings.cookieNoticeButtonDismissAction, 10) !== 0)
    ) {
      css += '#daextlwcnf-cookie-notice-button-container{';
      css += 'width: 210px' + declarationSuffix + ';';
      css += '}';

      css += '#daextlwcnf-cookie-notice-message{';
      css += 'width: calc(100% - 250px)' + declarationSuffix + ';';
      css += '}';

      css += '#daextlwcnf-cookie-notice-button-1,';
      css += '#daextlwcnf-cookie-notice-button-2{';
      css += 'width: 158px' + declarationSuffix + ';';
      css += 'margin-left: 0' + declarationSuffix + ';';
      css += '}';
    }

    /**
     * Button 1: Active
     * Button 2: Active
     * Close Button: Inactive
     */
    if (parseInt(settings.cookieNoticeButton1Action, 10) !== 0 &&
        parseInt(settings.cookieNoticeButton2Action, 10) !== 0 &&
        parseInt(settings.cookieNoticeButtonDismissAction, 10) === 0) {
      css += '#daextlwcnf-cookie-notice-button-container{';
      css += 'width: 370px' + declarationSuffix + ';';
      css += '}';

      css += '#daextlwcnf-cookie-notice-message{';
      css += 'width: calc(100% - 410px)' + declarationSuffix + ';';
      css += '}';

      css += '#daextlwcnf-cookie-notice-button-1,';
      css += '#daextlwcnf-cookie-notice-button-2{';
      css += 'width: 158px' + declarationSuffix + ';';
      css += '}';

      css += '#daextlwcnf-cookie-notice-button-dismiss{';
      css += 'display: none' + declarationSuffix + ';';
      css += '}';

    }

    /**
     * Button 1: Active
     * Button 2: Active
     * Close Button: Active
     */
    if (parseInt(settings.cookieNoticeButton1Action, 10) !== 0 &&
        parseInt(settings.cookieNoticeButton2Action, 10) !== 0 &&
        parseInt(settings.cookieNoticeButtonDismissAction, 10) !== 0) {
      css += '#daextlwcnf-cookie-notice-button-container{';
      css += 'width: 400px' + declarationSuffix + ';';
      css += '}';

      css += '#daextlwcnf-cookie-notice-message{';
      css += 'width: calc(100% - 440px)' + declarationSuffix + ';';
      css += '}';

      css += '#daextlwcnf-cookie-notice-button-1,';
      css += '#daextlwcnf-cookie-notice-button-2{';
      css += 'width: 158px' + declarationSuffix + ';';
      css += '}';

    }

    /**
     * Button 1: Inactive
     * Button 2: Inactive
     * Close Button: Active
     */
    if (parseInt(settings.cookieNoticeButton1Action, 10) === 0 &&
        parseInt(settings.cookieNoticeButton2Action, 10) === 0 &&
        parseInt(settings.cookieNoticeButtonDismissAction, 10) !== 0) {
      css += '#daextlwcnf-cookie-notice-message{';
      css += 'width: calc(100% - 70px)' + declarationSuffix + ';';
      css += '}';

      css += '#daextlwcnf-cookie-notice-button-container{';
      css += 'width: 30px' + declarationSuffix + ';';
      css += '}';

      css += '#daextlwcnf-cookie-notice-message{';
      css += 'width: calc(100% - 70px)' + declarationSuffix + ';';
      css += '}';
    }

    /**
     * Button 1: Inactive
     * Button 2: Inactive
     * Close Button: Inactive
     */
    if (parseInt(settings.cookieNoticeButton1Action, 10) === 0 &&
        parseInt(settings.cookieNoticeButton2Action, 10) === 0 &&
        parseInt(settings.cookieNoticeButtonDismissAction, 10) === 0) {
      css += '#daextlwcnf-cookie-notice-message{';
      css += 'width: 100%' + declarationSuffix + ';';
      css += '}';

      css += '#daextlwcnf-cookie-notice-button-container{';
      css += 'width: 0' + declarationSuffix + ';';
      css += 'margin-left: 0' + declarationSuffix + ';';
      css += '}';
    }

    // #media query
    css += '@media only screen and (max-width: ' +
        parseInt(settings.responsiveBreakpoint, 10) + 'px){';

    // #daextlwcnf-message
    css += '#daextlwcnf-cookie-notice-message{';
    css += 'width: 100%' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-button-container
    css += '#daextlwcnf-cookie-notice-button-container{';
    css += 'width: 100%' + declarationSuffix + ';';
    css += 'margin: 0' + declarationSuffix + ';';
    css += 'text-align: center' + declarationSuffix + ';';
    css += 'display: block' + declarationSuffix + ';';
    css += '}';

    //#daextlwcnf-cookie-notice-wrapper
    css += '#daextlwcnf-cookie-notice-wrapper{';
    css += 'flex-direction: column' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-button-1
    css += '#daextlwcnf-cookie-notice-button-1{';
    css += 'display: block' + declarationSuffix + ';';
    css += 'width: calc(100% - 22px)' + declarationSuffix + ';';
    css += 'margin: 20px 0 0 0' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-button-2
    css += '#daextlwcnf-cookie-notice-button-2{';
    css += 'display: block' + declarationSuffix + ';';
    css += 'width: calc(100% - 22px)' + declarationSuffix + ';';
    css += 'margin: 20px 0 0 0' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-button-dismiss
    css += '#daextlwcnf-cookie-notice-button-dismiss{';
    css += 'position: absolute' + declarationSuffix + ';';
    css += 'top: 0' + declarationSuffix + ';';
    css += 'right: 0' + declarationSuffix + ';';
    css += 'margin: 4px 14px 0 0' + declarationSuffix + ';';
    css += '}';

    css += '}';

    //Add the style element to the DOM
    let style = document.createElement('style');
    style.innerHTML = css;
    style.id = 'daextlwcnf-cookie-notice-style';
    document.head.appendChild(style);

    //Add the cookie icon inside the container
    let iconContainer = document.getElementById(
        'daextlwcnf-cookie-notice-button-dismiss');

    //iterate over iconContainer
    if(iconContainer !== null){
      iconContainer.appendChild(generateCrossIconSVG());
    }

    performStyleAdaptations();

  }

  /**
   *
   * Create the SVG  of the cross icon.
   *
   * @returns {{}|SVGElement|Element|SVGSVGElement|HTMLElement}
   */
  function generateCrossIconSVG() {

    'use strict';

    let svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('viewBox', '0 0 20 20');
    svg.setAttribute('version', '1.1');

    const style = document.createElementNS('http://www.w3.org/2000/svg',
        'style');
    style.append(document.createTextNode('.st0{display:none;}.cross-st1{fill:' + settings.cookieNoticeButtonDismissColor + ';}'));

    svg.appendChild(style);

    const path = document.createElementNS('http://www.w3.org/2000/svg',
        'polygon');
    path.setAttribute('class', 'cross-st1');
    path.setAttribute('points',
        '15.3,6.8 13.2,4.7 10,7.9 6.8,4.7 4.7,6.8 7.9,10 4.7,13.2 6.8,15.3 10,12.1 13.2,15.3 15.3,13.2 12.1,10');

    svg.appendChild(path);

    return svg;

  }

  /**
   * Bind the event listeners.
   */
  function bindEventListeners() {

    'use strict';

    //Add click event listener on the button 1
    let bt1 = document.getElementById('daextlwcnf-cookie-notice-button-1');
    if (bt1) {
      bt1.addEventListener('click', function() {
        switch (parseInt(settings.cookieNoticeButton1Action, 10)) {
          case 1:
            cookieSettings.initialize(settings);
            break;
          case 2:
            utility.acceptCookies(settings);
            utility.reload(settings);
            utility.closeNotice();
            revisitCookieConsent.initialize(settings);
            break;
          case 3:
            utility.closeNotice();
            revisitCookieConsent.initialize(settings);
            break;
          case 4:
            window.location.href = settings.cookieNoticeButton1Url;
            break;
        }
      });
    }

    //Add click event listener on the button 2
    let bt2 = document.getElementById('daextlwcnf-cookie-notice-button-2');
    if (bt2) {
      bt2.addEventListener('click', function() {
        switch (parseInt(settings.cookieNoticeButton2Action, 10)) {
          case 1:
            cookieSettings.initialize(settings);
            break;
          case 2:
            utility.acceptCookies(settings);
            utility.reload(settings);
            utility.closeNotice();
            revisitCookieConsent.initialize(settings);
            break;
          case 3:
            utility.closeNotice();
            revisitCookieConsent.initialize(settings);
            break;
          case 4:
            window.location.href = settings.cookieNoticeButton2Url;
            break;
        }
      });
    }

    //Add click event listener on the dismiss button
    let bd = document.getElementById('daextlwcnf-cookie-notice-button-dismiss');
    if (bd) {
      bd.addEventListener('click', function() {
        switch (parseInt(settings.cookieNoticeButtonDismissAction, 10)) {
          case 1:
            cookieSettings.initialize(settings);
            break;
          case 2:
            utility.acceptCookies(settings);
            utility.reload(settings);
            utility.closeNotice();
            revisitCookieConsent.initialize(settings);
            break;
          case 3:
            utility.closeNotice();
            revisitCookieConsent.initialize(settings);
            break;
          case 4:
            window.location.href = settings.cookieNoticeButtonDismissUrl;
            break;
        }
      });
    }

    //Perform style adaptations when the viewport size changes
    window.addEventListener('resize', function() {
      performStyleAdaptations();
    });

    //Add click event listener on the cookie notice mask to vibrate the cookie notice
    if (parseInt(settings.cookieNoticeMask, 10) === 1 &&
        parseInt(settings.cookieNoticeShakeEffect, 10) === 1) {
      let cn = document.getElementById('daextlwcnf-cookie-notice-container-mask');
      cn.addEventListener('click', function() {
        vibrateCookieNotice();
      });
    }

  }

  /**
   * Vibrates the cookie notice.
   *
   * Ref: https://medium.com/dev-genius/how-to-make-javascript-sleep-or-wait-d95d33c99909
   */
  function vibrateCookieNotice() {

    'use strict';

    //Do not proceed if the cookie notice is already vibrating
    if (states.vibrating) {
      return;
    } else {
      states.vibrating = true;
    }

    const sleep = (delay) => new Promise(
        (resolve) => {setTimeout(resolve, delay);});

    const vibrate = async () => {

      let cookieNoticeContainer = document.getElementById(
          'daextlwcnf-cookie-notice-container');
      let compStyles = null;
      let c1, c2, c3;
      let delayInMilliseconds = 1;
      let sizeValue = 8;

      for (let size = sizeValue; size > 0; size = size - 2) {

        //To the extreme left
        for (c1 = 0; c1 < size; c1++) {
          await sleep(delayInMilliseconds);
          compStyles = window.getComputedStyle(cookieNoticeContainer);
          cookieNoticeContainer.style.left = parseInt(
              compStyles.getPropertyValue('left'), 10) - 1 + 'px';
        }

        //To the extreme right
        for (c2 = 0; c2 < size * 2; c2++) {
          await sleep(delayInMilliseconds);
          compStyles = window.getComputedStyle(cookieNoticeContainer);
          cookieNoticeContainer.style.left = parseInt(
              compStyles.getPropertyValue('left'), 10) + 1 + 'px';
        }

        //To the center
        for (c3 = 0; c3 < size; c3++) {
          await sleep(delayInMilliseconds);
          compStyles = window.getComputedStyle(cookieNoticeContainer);
          cookieNoticeContainer.style.left = parseInt(
              compStyles.getPropertyValue('left'), 10) - 1 + 'px';
        }

      }

      states.vibrating = false;

    };

    vibrate();

  }

  /**
   * Perform style adaptations.
   */
  function performStyleAdaptations() {

    'use strict';

    //Adapt cookie notice position while in center
    if (parseInt(settings.cookieNoticeContainerPosition, 10) === 1) {

      let cookieNotice = document.getElementById(
          'daextlwcnf-cookie-notice-container');
      let height = window.innerHeight / 2 - cookieNotice.offsetHeight / 2;
      cookieNotice.style.top = height + 'px';

      let cookieNoticeContainer = document.getElementById(
          'daextlwcnf-cookie-notice-container');
      if (parseInt(window.innerWidth, 10) >
          parseInt(settings.responsiveBreakpoint, 10)) {
        cookieNoticeContainer.style.left = 'calc(50% - 174px)';
      } else {
        cookieNoticeContainer.style.left = '-10px';
      }

    }

  }

  /**
   * Add the cookie notice to the DOM and add the event listeners.
   */
  function bootstrap() {

    'use strict';

    //Add the cookie notice to the DOM
    addToDOM();

    //Apply the style available in the settings to the cookie notice
    applyStyle();

    //Bind the event listeners
    bindEventListeners();

  }

  //Return an object exposed to the public -----------------------------------------------------------------------------
  return {

    initialize: function(configuration) {

      'use strict';

      //Merge the custom configuration provided by the user with the default configuration
      settings = configuration;

      //Do not proceed if the cookies have been already accepted. (the 'daextlwcnf-accepted' cookie value is verified)
      if (utility.getCookie('daextlwcnf-accepted') === '1') {

        //Display the revisit cookie consent button
        revisitCookieConsent.initialize(settings);

        //Do not proceed
        return;

      }
      /**
       * Display the cookie notice if:
       *
       * - The Geolocation is not enabled.
       * - The Geolocation is enabled and the current user is located in one of the countries defined in the
       * "Geolocation Locale" option.
       */
      if (parseInt(settings.enableGeolocation, 10) === 1) {

        /**
         * Switch between the available geolocation services.
         */
        switch (parseInt(settings.geolocationService, 10)) {

            /**
             * Geolocation with HostIP.info
             *
             * An AJAX request to https://api.hostip.info/get_json.php with the IP of the user as a parameter is
             * performed.
             *
             * Doc: https://www.hostip.info/use.php
             */
          case 0:

            //Get the ISO country code with a request to hostip.info
            let oReq0 = new XMLHttpRequest();
            oReq0.addEventListener('load', function() {

              let response = null;
              let result = false;

              try {
                response = JSON.parse(this.response);
              } catch (e) {
                return true;
              }

              let hostIPInfoCountryCode = response.country_code;

              //Display the cookie notice if the country associated with the IP can't be identified
              if (hostIPInfoCountryCode == 'XX') {result = true;}

              //Compare the ISO country code with the ones available in the "Geolocation Locale" option
              let geolocationLocale = settings.geolocationLocale;
              geolocationLocale.forEach(function(countryCode) {
                if (countryCode.toLowerCase() ===
                    hostIPInfoCountryCode.toLowerCase()) {
                  result = true;
                }
              });

              //If the country is included display the cookie notice
              if (result) {
                bootstrap();
              }

            });
            oReq0.open('GET', 'https://api.hostip.info/get_json.php', true);
            oReq0.send();

            break;

            /**
             * Geolocation with AJAX request to WordPress.
             *
             * WordPress responds with "1" if the country associated with the IP address is available in the
             * "Geolocation Locale" list.
             *
             * This response is generated with PHP and makes use of geolocation database available in the server
             * where WordPress is installed.
             */
          case 1:

            let oReq1 = new XMLHttpRequest();
            oReq1.addEventListener('load', function() {

              //Display the cookie notice if "1" is returned
              if (parseInt(this.response, 10) === 1) {
                bootstrap();
              }

            });
            oReq1.open('POST', window.DAEXTLWCNF_PHPDATA.ajaxUrl, true);
            let formData = new FormData();
            formData.append('action', 'daextlwcnf_geolocate_user');
            formData.append('security', window.DAEXTLWCNF_PHPDATA.nonce);
            oReq1.send(formData);

            break;

        }

      } else {

        bootstrap();

      }

    },

  };

}(daextlwcnfUtility, daextlwcnfCookieSettings, daextlwcnfRevisitCookieConsent));