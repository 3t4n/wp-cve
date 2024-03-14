/**
 * This file is used to generate the cookie settings and handle its events.
 */
window.daextlwcnfCookieSettings = (function(utility, revisitCookieConsent) {

  'use strict';

  //This object is used to save all the settings -----------------------------------------------------------------------
  let settings = {};

  /**
   * Add the cookie settings HTML at the end of the body.
   */
  function addToDOM() {

    'use strict';

    let html = '';

    html += '<div id="daextlwcnf-cookie-settings-blurred-header"></div>';
    html += '<div id="daextlwcnf-cookie-settings-header">';

    if (settings.cookieSettingsLogoUrl.length > 0) {
      html += '<img id="daextlwcnf-cookie-settings-logo" src="' +
          settings.cookieSettingsLogoUrl + '">';
    }
    html += '<div id="daextlwcnf-cookie-settings-title">' +
        settings.cookieSettingsTitle + '</div>';
    html += '</div>'; // #daextlwcnf-cookie-settings-header

    html += '<div id="daextlwcnf-cookie-settings-body">';
    html += '<div id="daextlwcnf-cookie-settings-description">' +
        settings.cookieSettingsDescription + '</div>';

    html += '<div id="daextlwcnf-cookie-settings-blurred-footer"></div>';
    html += '<div id="daextlwcnf-cookie-settings-footer">';
    html += '<div id="daextlwcnf-cookie-settings-buttons-container">';
    if (parseInt(settings.cookieSettingsButton1Action) !== 0) {
      html += '<div id="daextlwcnf-cookie-settings-button-1" class="daextlwcnf-cookie-settings-button">' +
          settings.cookieSettingsButton1Text + '</div>';
    }
    if (parseInt(settings.cookieSettingsButton2Action) !== 0) {
      html += '<div id="daextlwcnf-cookie-settings-button-2" class="daextlwcnf-cookie-settings-button">' +
          settings.cookieSettingsButton2Text + '</div>';
    }
    html += '</div>'; // #daextlwcnf-cookie-settings-buttons-container
    html += '</div>'; // #daextlwcnf-cookie-settings-footer
    html += '</div>';

    //Add the cookie settings modal window HTML to the DOM
    let cookieSettings = document.createElement('div');
    cookieSettings.id = 'daextlwcnf-cookie-settings-container';
    cookieSettings.innerHTML = html;
    document.body.appendChild(cookieSettings);

    //Add the cookie settings mask if enabled
    if (parseInt(settings.cookieSettingsMask, 10) === 1) {
      let cookieSettingsMask = document.createElement('div');
      cookieSettingsMask.id = 'daextlwcnf-cookie-settings-mask';
      document.body.appendChild(cookieSettingsMask);
    }

  }

  /**
   * Apply the CSS style to the cookie settings HTML.
   */
  function applyStyle() {

    'use strict';

    let css = '';
    let declarationSuffix = '';
    if (parseInt(settings.forceCssSpecificity, 10) === 1) {
      declarationSuffix = ' !important';
    }

    // #daextlwcnf-cookie-settings-mask
    css += '#daextlwcnf-cookie-settings-mask{';
    css += 'background: ' + settings.cookieSettingsMaskColor +
        declarationSuffix + ';';
    css += 'opacity: ' + parseFloat(settings.cookieSettingsMaskOpacity) +
        declarationSuffix + ';';
    css += 'width: 100%' + declarationSuffix + ';';
    css += 'position: fixed' + declarationSuffix + ';';
    css += 'height: 100%' + declarationSuffix + ';';
    css += 'left: 0' + declarationSuffix + ';';
    css += 'top: 0' + declarationSuffix + ';';
    css += 'z-index: 999999998' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-container, #daextlwcnf-container *
    css += '#daextlwcnf-cookie-settings-container, #daextlwcnf-cookie-settings-container *{';
    css += 'box-sizing: content-box' + declarationSuffix + ';';
    css += '-webkit-touch-callout: none' + declarationSuffix + ';';
    css += '-webkit-user-select: none' + declarationSuffix + ';';
    css += '-khtml-user-select: none' + declarationSuffix + ';';
    css += '-moz-user-select: none' + declarationSuffix + ';';
    css += '-ms-user-select: none' + declarationSuffix + ';';
    css += 'user-select: none' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-container
    css += '#daextlwcnf-cookie-settings-container{';
    css += 'position: fixed' + declarationSuffix + ';';
    let partialTop = 300 +
        parseInt(settings.cookieSettingsContainerBorderWidth, 10);
    css += 'top: calc(50% - ' + partialTop + 'px)' + declarationSuffix + ';';
    let partialLeft = 300 +
        parseInt(settings.cookieSettingsContainerBorderWidth, 10);
    css += 'left: calc(50% - ' + partialLeft + 'px)' + declarationSuffix + ';';
    css += 'z-index: 999999999' + declarationSuffix + ';';
    css += 'height: 568px' + declarationSuffix + ';';
    css += 'width: 600px' + declarationSuffix + ';';
    css += 'background: ' + settings.cookieSettingsContainerBackgroundColor +
        declarationSuffix + ';';
    css += 'opacity: ' + parseFloat(settings.cookieSettingsContainerOpacity) +
        declarationSuffix + ';';
    css += 'border-width: ' + settings.cookieSettingsContainerBorderWidth +
        'px' + declarationSuffix + ';';
    css += 'border-color: rgba(' +
        utility.hexToRgb(settings.cookieSettingsContainerBorderColor).r + ',' +
        utility.hexToRgb(settings.cookieSettingsContainerBorderColor).g + ',' +
        utility.hexToRgb(settings.cookieSettingsContainerBorderColor).b + ', ' +
        parseFloat(settings.cookieSettingsContainerBorderOpacity) + ')' +
        declarationSuffix + ';';
    css += 'border-style: solid' + declarationSuffix + ';';
    css += 'color: #000' + declarationSuffix + ';';
    css += 'z-index: 999999999' + declarationSuffix + ';';
    css += 'font-size: 13px' + declarationSuffix + ';';
    css += 'padding: 16px 0' + declarationSuffix + ';';
    css += 'border-radius: ' + parseInt(settings.containersBorderRadius, 10) +
        'px' + declarationSuffix + ';';
    let drop_shadow_value = 'none';
    if (parseInt(settings.cookieSettingsContainerDropShadow, 10) === 1) {
      drop_shadow_value = 'rgba(' +
          utility.hexToRgb(settings.cookieSettingsContainerDropShadowColor).r +
          ', ' +
          utility.hexToRgb(settings.cookieSettingsContainerDropShadowColor).g +
          ', ' +
          utility.hexToRgb(settings.cookieSettingsContainerDropShadowColor).b +
          ', 0.08) 0px 0px 0px 1px, rgba(' +
          utility.hexToRgb(settings.cookieSettingsContainerDropShadowColor).r +
          ', ' +
          utility.hexToRgb(settings.cookieSettingsContainerDropShadowColor).g +
          ', ' +
          utility.hexToRgb(settings.cookieSettingsContainerDropShadowColor).b +
          ', 0.08) 0px 2px 1px, rgba(' +
          utility.hexToRgb(settings.cookieSettingsContainerDropShadowColor).r +
          ', ' +
          utility.hexToRgb(settings.cookieSettingsContainerDropShadowColor).g +
          ', ' +
          utility.hexToRgb(settings.cookieSettingsContainerDropShadowColor).b +
          ', 0.31) 0px 0px 20px -6px' + declarationSuffix + ';';
    }
    css += 'box-shadow: ' + drop_shadow_value + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-blurred-header
    css += '#daextlwcnf-cookie-settings-blurred-header{';
    css += 'width: 568px' + declarationSuffix + ';';
    css += 'height: 0px' + declarationSuffix + ';';
    css += 'position: absolute' + declarationSuffix + ';';
    css += 'left: 16px' + declarationSuffix + ';';
    css += 'top: 80px' + declarationSuffix + ';';
    css += 'box-shadow: 0px 0px 6px 6px ' +
        settings.cookieSettingsContainerBackgroundColor + '' +
        declarationSuffix + ';';
    css += 'z-index: 0' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-header
    css += '#daextlwcnf-cookie-settings-header{';
    css += 'height: 80px' + declarationSuffix + ';';
    css += 'display: flex' + declarationSuffix + ';';
    css += 'position: absolute' + declarationSuffix + ';';
    css += 'top: 0' + declarationSuffix + ';';
    css += 'width: 552px' + declarationSuffix + ';';
    css += 'padding: 0 24px' + declarationSuffix + ';';
    css += 'background: ' + settings.cookieSettingsContainerBackgroundColor +
        '' + declarationSuffix + ';';
    css += 'z-index: 999999999' + declarationSuffix + ';';
    css += 'border-bottom: 1px solid #ebecf0' + declarationSuffix + ';';
    css += 'border-radius: ' + parseInt(settings.containersBorderRadius, 10) +
        'px ' + parseInt(settings.containersBorderRadius, 10) + 'px 0 0' +
        declarationSuffix + ';';
    css += '}';

    if (settings.cookieSettingsLogoUrl.length > 0) {
      css += '#daextlwcnf-cookie-settings-logo{';
      css += 'height: 32px' + declarationSuffix + ';';
      css += 'margin: 24px 0' + declarationSuffix + ';';
      css += '}';
    }

    // #daextlwcnf-cookie-settings-title
    css += '#daextlwcnf-cookie-settings-title{';
    css += 'font-size: 20px' + declarationSuffix + ';';
    css += 'color: ' + settings.cookieSettingsHeadingsFontColor +
        declarationSuffix + ';';
    css += 'line-height: 80px' + declarationSuffix + ';';
    css += 'margin: 0' + declarationSuffix + ';';
    if (settings.cookieSettingsLogoUrl.length > 0) {
      css += 'padding: 0 24px 0 8px' + declarationSuffix + ';';
    } else {
      css += 'padding: 0 24px 0 0' + declarationSuffix + ';';
    }
    css += 'font-family: ' + settings.headingsFontFamily + '' +
        declarationSuffix + ';';
    css += 'font-weight: ' + settings.headingsFontWeight + '' +
        declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-description
    css += '#daextlwcnf-cookie-settings-description{';
    css += 'font-size: 13px' + declarationSuffix + ';';
    css += 'color: ' + settings.cookieSettingsParagraphsFontColor +
        declarationSuffix + ';';
    css += 'margin-top: 21px' + declarationSuffix + ';';
    css += 'margin-bottom: 18px' + declarationSuffix + ';';
    css += 'line-height: 20px' + declarationSuffix + ';';
    css += 'padding: 0 24px' + declarationSuffix + ';';
    css += 'font-family: ' + settings.paragraphsFontFamily + '' +
        declarationSuffix + ';';
    css += 'font-weight: ' + parseInt(settings.paragraphsFontWeight, 10) + '' +
        declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-description strong
    css += '#daextlwcnf-cookie-settings-description strong{';
    css += 'font-weight: ' + parseInt(settings.strongTagsFontWeight, 10) +
        declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-description a
    css += '#daextlwcnf-cookie-settings-description a{';
    css += 'color: ' + settings.cookieSettingsLinksFontColor +
        declarationSuffix + ';';
    css += 'font-family: ' + settings.paragraphsFontFamily + '' +
        declarationSuffix + ';';
    css += 'font-weight: ' + parseInt(settings.paragraphsFontWeight, 10) + '' +
        declarationSuffix + ';';
    css += 'line-height: 20px' + declarationSuffix + ';';
    css += 'font-size: 13px' + declarationSuffix + ';';
    css += 'text-decoration: none' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-description a:hover
    css += '#daextlwcnf-cookie-settings-description a:hover{';
    css += 'text-decoration: underline' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-body
    // Ref: https://stackoverflow.com/questions/16670931/hide-scroll-bar-but-while-still-being-able-to-scroll/38994837#38994837
    css += '#daextlwcnf-cookie-settings-body{';
    css += 'height: 440px' + declarationSuffix + ';';
    css += 'width: 600px' + declarationSuffix + ';';
    css += 'margin-top: 64px' + declarationSuffix + ';';
    css += 'overflow-y: scroll' + declarationSuffix + ';';
    css += 'scrollbar-width: none' + declarationSuffix + ';'; //Firefox
    css += '-ms-overflow-style: none' + declarationSuffix + ';'; //Internet Explorer 10+
    css += '}';

    // #daextlwcnf-cookie-settings-body
    css += '#daextlwcnf-cookie-settings-body::-webkit-scrollbar{';
    css += 'width: 0' + declarationSuffix + ';';
    css += 'height: 0' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-blurred-footer
    css += '#daextlwcnf-cookie-settings-blurred-footer{';
    css += 'width: 568px' + declarationSuffix + ';';
    css += 'height: 0px' + declarationSuffix + ';';
    css += 'position: absolute' + declarationSuffix + ';';
    css += 'left: 16px' + declarationSuffix + ';';
    css += 'bottom: 80px' + declarationSuffix + ';';
    css += 'box-shadow: 0px 0px 6px 6px ' +
        settings.cookieSettingsContainerBackgroundColor + '' +
        declarationSuffix + ';';
    css += 'z-index: 0' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-footer
    css += '#daextlwcnf-cookie-settings-footer{';
    css += 'position: absolute' + declarationSuffix + ';';
    css += 'left: 0' + declarationSuffix + ';';
    css += 'bottom: 0' + declarationSuffix + ';';
    css += 'display: flex' + declarationSuffix + ';';
    css += 'width: 552px' + declarationSuffix + ';';
    css += 'padding: 20px 24px' + declarationSuffix + ';';
    css += 'border-top: 1px solid #ebecf0' + declarationSuffix + ';';
    css += 'background: ' + settings.cookieSettingsContainerBackgroundColor +
        '' + declarationSuffix + ';';
    css += 'border-radius: 0 0 ' +
        parseInt(settings.containersBorderRadius, 10) + 'px ' +
        parseInt(settings.containersBorderRadius, 10) + 'px' +
        declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-buttons-container
    css += '#daextlwcnf-cookie-settings-buttons-container{';
    css += 'margin-left: auto' + declarationSuffix + ';';
    css += 'line-height: 38px' + declarationSuffix + ';';
    css += 'height: 40px' + declarationSuffix + ';';
    css += '}';

    // .daextlwcnf-cookie-settings-button
    css += '.daextlwcnf-cookie-settings-button{';
    css += 'padding: 0 46px' + declarationSuffix + ';';
    css += 'font-size: 13px' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-button-2
    css += '#daextlwcnf-cookie-settings-button-2{';
    css += 'margin-left: 8px' + declarationSuffix + ';';
    css += '}';

    /* Buttons */

    // #daextlwcnf-button-1
    css += '#daextlwcnf-cookie-settings-button-1{';
    css += 'width: auto' + declarationSuffix + ';';
    css += 'background: ' + settings.cookieSettingsButton1BackgroundColor +
        declarationSuffix + ';';
    css += 'font-family: ' + settings.buttonsFontFamily + '' +
        declarationSuffix + ';';
    css += 'font-weight: ' + parseInt(settings.buttonsFontWeight, 10) + '' +
        declarationSuffix + ';';
    css += 'font-style: normal' + declarationSuffix + ';';
    css += 'color: ' + settings.cookieSettingsButton1FontColor +
        declarationSuffix + ';';
    css += 'display: inline-block' + declarationSuffix + ';';
    css += 'cursor: pointer' + declarationSuffix + ';';
    css += 'border: 1px solid ' + settings.cookieSettingsButton1BorderColor +
        declarationSuffix + ';';
    css += 'border-radius: ' + settings.buttonsBorderRadius + 'px' +
        declarationSuffix + ';';
    css += 'text-align: center' + declarationSuffix + ';';
    css += 'padding: 0 10px' + declarationSuffix + ';';
    css += 'width: 158px' + declarationSuffix + ';';
    css += '}';

    css += '#daextlwcnf-cookie-settings-button-1:hover{';
    css += 'background: ' + settings.cookieSettingsButton1BackgroundColorHover +
        declarationSuffix + ';';
    css += 'border-color: ' + settings.cookieSettingsButton1BorderColorHover +
        declarationSuffix + ';';
    css += 'color: ' + settings.cookieSettingsButton1FontColorHover +
        declarationSuffix + ';';
    css += '}';

    css += '#daextlwcnf-cookie-settings-button-2:hover{';
    css += 'background: ' + settings.cookieSettingsButton2BackgroundColorHover +
        declarationSuffix + ';';
    css += 'border-color: ' + settings.cookieSettingsButton2BorderColorHover +
        declarationSuffix + ';';
    css += 'color: ' + settings.cookieSettingsButton2FontColorHover +
        declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-button-2
    css += '#daextlwcnf-cookie-settings-button-2{';
    css += 'width: auto' + declarationSuffix + ';';
    css += 'background: ' + settings.cookieSettingsButton2BackgroundColor +
        declarationSuffix + ';';
    css += 'font-family: ' + settings.buttonsFontFamily + '' +
        declarationSuffix + ';';
    css += 'font-weight: ' + parseInt(settings.buttonsFontWeight, 10) + '' +
        declarationSuffix + ';';
    css += 'font-style: normal' + declarationSuffix + ';';
    css += 'color: ' + settings.cookieSettingsButton2FontColor +
        declarationSuffix + ';';
    css += 'display: inline-block' + declarationSuffix + ';';
    css += 'cursor: pointer' + declarationSuffix + ';';
    css += 'border: 1px solid ' + settings.cookieSettingsButton2BorderColor +
        declarationSuffix + ';';
    css += 'border-radius: ' + parseInt(settings.buttonsBorderRadius, 10) +
        'px' + declarationSuffix + ';';
    css += 'text-align: center' + declarationSuffix + ';';
    css += 'padding: 0 10px' + declarationSuffix + ';';
    css += 'width: 158px' + declarationSuffix + ';';
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

    //a
    css += '#daextlwcnf-cookie-settings-description a{';
    css += 'color: ' + settings.cookieSettingsLinksFontColor +
        declarationSuffix + ';';
    css += 'font-size: 13px' + declarationSuffix + ';';
    css += 'line-height: 20px' + declarationSuffix + ';';
    css += 'font-family: ' + settings.paragraphsFontFamily + '' +
        declarationSuffix + ';';
    css += 'font-weight: ' + parseInt(settings.paragraphsFontWeight, 10) + '' +
        declarationSuffix + ';';
    css += 'text-decoration: none' + declarationSuffix + ';';
    css += '}';

    //a:hover
    css += '#daextlwcnf-cookie-settings-description a:hover{';
    css += 'text-decoration: underline' + declarationSuffix + ';';
    css += '}';

    //li
    css += '#daextlwcnf-cookie-settings-description li{';
    css += 'margin: 0' + declarationSuffix + ';';
    css += 'font-size: 13px' + declarationSuffix + ';';
    css += 'color: ' + settings.cookieSettingsParagraphsFontColor +
        declarationSuffix + ';';
    css += 'line-height: 20px' + declarationSuffix + ';';
    css += 'font-family: ' + settings.paragraphsFontFamily + '' +
        declarationSuffix + ';';
    css += 'font-weight: ' + parseInt(settings.paragraphsFontWeight, 10) + '' +
        declarationSuffix + ';';
    css += '}';

    //p
    css += '#daextlwcnf-cookie-settings-description p{';
    css += 'margin: 0 0 20px 0' + declarationSuffix + ';';
    css += 'font-size: 13px' + declarationSuffix + ';';
    css += 'color: ' + settings.cookieSettingsParagraphsFontColor +
        declarationSuffix + ';';
    css += 'line-height: 20px' + declarationSuffix + ';';
    css += 'font-family: ' + settings.paragraphsFontFamily + '' +
        declarationSuffix + ';';
    css += 'font-weight: ' + parseInt(settings.paragraphsFontWeight, 10) + '' +
        declarationSuffix + ';';
    css += '}';

    css += '#daextlwcnf-cookie-settings-description p:last-child{';
    css += 'margin: 0' + declarationSuffix + ';';
    css += '}';

    //strong
    css += '#daextlwcnf-cookie-settings-description strong{';
    css += 'font-weight: ' + parseInt(settings.strongTagsFontWeight, 10) +
        declarationSuffix + ';';
    css += 'color: ' + settings.cookieSettingsHeadingsFontColor +
        declarationSuffix + ';';
    css += '}';

    //ol
    css += '#daextlwcnf-cookie-settings-description ol{';
    css += 'margin: 0 0 20px 20px' + declarationSuffix + ';';
    css += 'list-style: decimal outside none' + declarationSuffix + ';';
    css += 'padding: 0' + declarationSuffix + ';';
    css += '}';

    css += '#daextlwcnf-cookie-settings-description ol:last-child{';
    css += 'margin: 0 0 0 20px' + declarationSuffix + ';';
    css += '}';

    //ul
    css += '#daextlwcnf-cookie-settings-description ul{';
    css += 'margin: 0 0 20px 20px' + declarationSuffix + ';';
    css += 'list-style: disc outside none' + declarationSuffix + ';';
    css += 'padding: 0' + declarationSuffix + ';';
    css += '}';

    css += '#daextlwcnf-cookie-settings-description ul:last-child{';
    css += 'margin: 0 0 0 20px' + declarationSuffix + ';';
    css += '}';

    //li
    css += '#daextlwcnf-cookie-settings-description li{';
    css += 'margin: 0' + declarationSuffix + ';';
    css += 'font-size: 13px' + declarationSuffix + ';';
    css += 'color: ' + settings.cookieSettingsParagraphsFontColor +
        declarationSuffix + ';';
    css += 'line-height: 20px' + declarationSuffix + ';';
    css += 'font-family: ' + settings.paragraphsFontFamily + '' +
        declarationSuffix + ';';
    css += 'font-weight: ' + parseInt(settings.paragraphsFontWeight, 10) + '' +
        declarationSuffix + ';';
    css += '}';

    //End

    // #media query
    css += '@media only screen and (max-width: ' +
        settings.responsiveBreakpoint + 'px),';
    css += 'screen and (max-height: 640px){';

    // #daextlwcnf-cookie-settings-container
    css += '#daextlwcnf-cookie-settings-container{';
    css += 'top: 0' + declarationSuffix + ';';
    css += 'left: 0' + declarationSuffix + ';';
    css += 'width: 100%' + declarationSuffix + ';';
    css += 'height: calc(100% - 32px)' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-body
    css += '#daextlwcnf-cookie-settings-body{';
    css += 'width: 100%' + declarationSuffix + ';';
    css += 'height: calc(100% - 187px)' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-footer
    css += '#daextlwcnf-cookie-settings-footer{';
    css += 'width: calc(100% - 48px)' + declarationSuffix + ';';
    css += 'height: 100px' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-buttons-container
    css += '#daextlwcnf-cookie-settings-buttons-container{';
    css += 'margin: 0' + declarationSuffix + ';';
    css += 'width: 100%' + declarationSuffix + ';';
    css += 'height: 100px' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-blurred-footer
    css += '#daextlwcnf-cookie-settings-blurred-footer{';
    css += 'bottom: 140px' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-button
    css += '#daextlwcnf-cookie-settings-buttons-container .daextlwcnf-cookie-settings-button{';
    css += 'padding: 0' + declarationSuffix + ';';
    css += 'width: calc(100% - 2px)' + declarationSuffix + ';';
    css += 'display: block' + declarationSuffix + ';';
    css += 'text-align: center' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-button-1
    css += '#daextlwcnf-cookie-settings-button-1{';
    css += 'margin-bottom: 20px' + declarationSuffix + ';';
    css += '}';

    // #daextlwcnf-cookie-settings-button-2
    css += '#daextlwcnf-cookie-settings-button-2{';
    css += 'margin: 0' + declarationSuffix + ';';
    css += '}';

    css += '}';

    //Add the style element to the DOM
    let style = document.createElement('style');
    style.innerHTML = css;
    style.id = 'daextlwcnf-cookie-settings-style';
    document.head.appendChild(style);

  }

  /**
   * Bind all the event listeners.
   */
  function bindEventListeners() {

    'use strict';

    //Add click event listener on the button 1
    let bt1 = document.getElementById('daextlwcnf-cookie-settings-button-1');
    if (bt1) {
      bt1.addEventListener('click', function() {
        switch (parseInt(settings.cookieSettingsButton1Action, 10)) {
          case 1:
            utility.acceptCookies(settings);
            utility.reload(settings);
            closeCookieSettings();
            utility.closeNotice();
            revisitCookieConsent.initialize(settings);
            break;
          case 2:
            closeCookieSettings();
            break;
          case 3:
            window.location.href = settings.cookieSettingsButton1Url;
            break;
        }
      });
    }

    //Add click event listener on the button 2
    let bt2 = document.getElementById('daextlwcnf-cookie-settings-button-2');
    if (bt2) {
      bt2.addEventListener('click', function() {
        switch (parseInt(settings.cookieSettingsButton2Action, 10)) {
          case 1:
            utility.acceptCookies(settings);
            utility.reload(settings);
            closeCookieSettings();
            utility.closeNotice();
            revisitCookieConsent.initialize(settings);
            break;
          case 2:
            closeCookieSettings();
            break;
          case 3:
            window.location.href = settings.cookieSettingsButton2Url;
            break;
        }
      });
    }

  }

  /**
   * Removes the cookie settings modal window and the cookie settings mask from the DOM.
   */
  function closeCookieSettings() {

    'use strict';

    //Remove the cookie settings window from the DOM
    document.getElementById('daextlwcnf-cookie-settings-container').remove();

    //Remove the cookie settings mask from the DOM
    let mm = document.getElementById('daextlwcnf-cookie-settings-mask');
    if (mm) {
      document.getElementById('daextlwcnf-cookie-settings-mask').remove();
    }

  }

  //Return an object exposed to the public -----------------------------------------------------------------------------
  return {

    initialize: function(configuration) {

      //Merge the custom configuration provided by the user with the default configuration
      settings = configuration;

      //Add the news ticker to the DOM
      addToDOM();

      //Apply the style available in the settings
      applyStyle();

      //Bind event listeners
      bindEventListeners();

    },

  };

}(daextlwcnfUtility, daextlwcnfRevisitCookieConsent));