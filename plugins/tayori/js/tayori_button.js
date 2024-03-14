(function() {
  var isMobile, json, modalPage, modal_flag, modal_open, process, scriptHandler, setJS, tayoriButtonType, timer, ua, $iframe;
  modalPage = false;
  json = null;
  timer = null;
  modal_flag = false;
  modal_open = false;
  tayoriButtonType = '';
  ua = navigator.userAgent.toLowerCase();
  isMobile = ua.indexOf('iphone') != -1 || ua.indexOf('ipad') != -1 || ua.indexOf('android') != -1;
  scriptHandler = function() {
    jQuery.noConflict();
    process();
  };
  process = function() {
    return jQuery(document).ready(function($) {
      var checkCount, count, startButton;
      count = 0;
      checkCount = function() {
        count++;
        if (count > 3) {
          startButton();
        }
      };
      if (window.TweenMax) {
        checkCount();
      } else {
        jQuery.getScript('//cdnjs.cloudflare.com/ajax/libs/gsap/1.17.0/TweenMax.min.js', function() {
          return checkCount();
        });
      }
      if (jQuery.ui) {
        if (jQuery.ui.draggable) {
          jQuery.getScript('//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js', function() {
            return checkCount();
          });
        } else {
          jQuery.getScript('https://s3-ap-northeast-1.amazonaws.com/tayori/js/jquery/jquery.ui.widget.js', function() {
            return jQuery.getScript('https://s3-ap-northeast-1.amazonaws.com/tayori/js/jquery/jquery.ui.mouse.js', function() {
              return jQuery.getScript('https://s3-ap-northeast-1.amazonaws.com/tayori/js/jquery/jquery.ui.draggable.js', function() {
                return jQuery.getScript('//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js', function() {
                  return checkCount();
                });
              });
            });
          });
        }
      } else {
        jQuery.getScript('//code.jquery.com/ui/1.11.1/jquery-ui.min.js', function() {
          return jQuery.getScript('//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js', function() {
            return checkCount();
          });
        });
      }
      if (jQuery.easing) {
        checkCount();
      } else {
        jQuery.getScript('//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js', function() {
          checkCount();
        });
      }
      if (jQuery.transit) {
        checkCount();
      } else {
        jQuery.getScript('//cdnjs.cloudflare.com/ajax/libs/jquery.transit/0.9.12/jquery.transit.min.js', function() {
          checkCount();
        });
      }
      return startButton = function() {
        var bindClick, getBright, makeElement, mod, modalCloseClick, request, scrollEvent;
        
        jQuery(window).load(function () {
          jQuery.getJSON(myScript.plugins_Url + '/tayori/json/button.json', function(result){
            json = result;
            makeElement();
          });
        });
        
        makeElement = function() {
          var buttonDefaultOpacity, buttonPosition, buttonStartPosition, buttontype, css_template, dragging, draggingTime, dragtimer, e, fontcolor, iconType, left, popNumber, t_template_classic, t_template_pop, t_template_simple, template, top, trigger;
          css_template = "#tayori-container {\n  font-family: \"Hiragino Kaku Gothic ProN\", Meiryo, Arial, Verdana, 'Helvetica Neue', Helvetica, sans-serif;\n}\n\n#tayori-trigger-simple {\n  position: fixed;\n  cursor: pointer;\n  width: 56px;\n  height: 56px;\n  overflow: hidden;\n  -webkit-user-select: none;\n  -moz-user-select: none;\n  -ms-user-select: none;\n  user-select: none;\n  z-index: 2147483000;\n  top: 0;\n  left: 0;\n  background-color: "+json.button_color+";\n  -webkit-border-radius: 50%;\n  -moz-border-radius: 50%;\n  -ms-border-radius: 50%;\n  -o-border-radius: 50%;\n  border-radius: 50%;\n  background-image: url(\""+myScript.plugins_Url+"/tayori/images/simple-button-icon.png\") !important;\n  background-repeat: no-repeat;\n  background-position: center;\n  box-shadow: 0 0 2px 2px rgba(100,100,100,0.3);\n  display: none;\n}\n#tayori-trigger-simple.opacity {\n  filter:alpha(opacity=50);\n	-moz-opacity: 0.5;\n	            opacity: 0.5;\n}\n\n#tayori-trigger-pop {\n  position: fixed;\n  cursor: pointer;\n  width: 56px;\n  height: 56px;\n  overflow: hidden;\n  -webkit-user-select: none;\n  -moz-user-select: none;\n  -ms-user-select: none;\n  user-select: none;\n  z-index: 2147483000;\n  top: 0;\n  left: 0;\n  background-color: "+json.button_color+";\n  -webkit-border-radius: 50%;\n  -moz-border-radius: 50%;\n  -ms-border-radius: 50%;\n  -o-border-radius: 50%;\n  border-radius: 50%;\n  background-size: 56px 56px;\n  background-repeat: no-repeat;\n  background-position: center;\n  box-shadow: 0 0 2px 2px rgba(100,100,100,0.3);\n  display: none;\n}\n#tayori-trigger-pop.opacity {\n  filter:alpha(opacity=50);\n	-moz-opacity: 0.5;\n	            opacity: 0.5;\n}\n\n#tayori-trigger-classic {\n  position: fixed;\n  cursor: pointer;\n  background-color: "+json.button_color+";\n  overflow: hidden;\n  -webkit-user-select: none;\n  -moz-user-select: none;\n  -ms-user-select: none;\n  user-select: none;\n  z-index: 2147483000;\n  -webkit-transition: all 0.1s cubic-bezier(0.39, 0.575, 0.565, 1);\n  -moz-transition: all 0.1s cubic-bezier(0.39, 0.575, 0.565, 1);\n  -ms-transition: all 0.1s cubic-bezier(0.39, 0.575, 0.565, 1);\n  -o-transition: all 0.1s cubic-bezier(0.39, 0.575, 0.565, 1);\n  transition: all 0.1s cubic-bezier(0.39, 0.575, 0.565, 1);\n  word-break: break-all;\n  display: none;\n}\n\n#tayori-trigger-classic:hover {\n  -webkit-filter:brightness(95%);\n  filter:brightness(95%);\n  box-shadow: 0 0 8px 3px rgba(100,100,100,0.3);\n}\n#tayori-trigger-classic:active {\n  -webkit-transform: scale(0.98);\n  -moz-transform: scale(0.98);\n  -ms-transform: scale(0.98);\n  -o-transform: scale(0.98);\n  transform: scale(0.98);\n  box-shadow: 0 0 2px 2px rgba(100,100,100,0.3);\n}\n#tayori-trigger-classic.opacity {\n  filter:alpha(opacity=50);\n	-moz-opacity: 0.5;\n	            opacity: 0.5;\n}\n#tayori-trigger-classic.position-pc-right-bottom {\n  right: 5%;\n  bottom: -2px;\n  height: 38px;\n  border-radius: 6px 6px 0 0;\n}\n#tayori-trigger-classic.position-pc-left-bottom {\n  left: 5%;\n  bottom: -2px;\n  height: 38px;\n  border-radius: 6px 6px 0 0;\n}\n#tayori-trigger-classic.position-pc-right {\n  right: -2px;\n  bottom: 5%;\n  width: 38px;\n  border-radius: 6px 0 0 6px;\n}\n#tayori-trigger-classic.position-pc-left {\n  left: -2px;\n  bottom: 5%;\n  width: 38px;\n  border-radius: 0 6px 6px 0;\n}\n#tayori-trigger-classic.position-sp-right {\n  right: 0;\n  bottom: 5%;\n  width: 38px;\n  border-radius: 6px 0 0 6px;\n}\n#tayori-trigger-classic.position-sp-left {\n  left: 0;\n  bottom: 5%;\n  width: 38px;\n  border-radius: 0 6px 6px 0;\n}\n#tayori-trigger-classic.position-sp-bottom {\n  right: 5%;\n  bottom: -2px;\n  height: 38px;\n  border-radius: 6px 6px 0 0;\n}\n#tayori-trigger-icon {\n  background-repeat: no-repeat;\n  background-position: center;\n  width: 26px;\n  height: 26px;\n  margin: 10px 5px 10px 5px;\n  float: left;\n  overflow: hidden;\n}\n\n.logo-tayori {\n  background-image: url(\""+myScript.plugins_Url+"/tayori/images/logo.svg\");\n}\n.logo-white {\n  background-image: url(\""+myScript.plugins_Url+"/tayori/images/logo-white.svg\") !important;\n}\n.logo-black {\n  background-image: url(\""+myScript.plugins_Url+"/tayori/images/logo-black.svg\") !important;\n}\n#tayori-trigger-icon:before, #tayori-trigger-chevron:before {\n  content: \"\";\n  display: block;\n  width: 0;\n  height: 100%;\n}\n#tayori-trigger-label {\n  color: "+json.button_font_color+" !important;\n  line-height: 1.1em;\n  text-align: center;\n  font-size: 13px !important;\n}\n#tayori-trigger-classic.position-pc-right #tayori-trigger-label,\n#tayori-trigger-classic.position-pc-left #tayori-trigger-label {\n  float: inherit;\n  width: 13px;\n  margin: 0 10px;\n  text-orientation: upright;\n  direction: ltr;\n}\n#tayori-trigger-classic.position-sp-right #tayori-trigger-label,\n#tayori-trigger-classic.position-sp-left #tayori-trigger-label {\n  float: inherit;\n  width: 11px;\n  margin: 0 10px;\n  text-orientation: upright;\n  direction: ltr;\n}\n\n#tayori-trigger-chevron {\n  margin: 3px 12px 8px;\n  height: 19px;\n  width: 12px;\n  background-repeat: no-repeat;\n  background-position: center center;\n  background-size: 100%;\n}\n.chevron-white {\n  background-image: url(\""+myScript.plugins_Url+"/tayori/images/area-modal-trigger-chevron-white.svg\");\n}\n.chevron-black {\n  background-image: url(\""+myScript.plugins_Url+"/tayori/images/area-modal-trigger-chevron-black.svg\");\n}\n#tayori-trigger-classic.position-pc-right #tayori-trigger-chevron,\n#tayori-trigger-classic.position-pc-left #tayori-trigger-chevron {\n  border-left: none;\n}\n#tayori-trigger-classic.position-pc-right #tayori-trigger-chevron, #tayori-trigger-classic.position-sp-right #tayori-trigger-chevron {\n  -moz-transform: rotate(-90deg);\n  -webkit-transform: rotate(-90deg);\n  transform: rotate(-90deg);\n}\n#tayori-trigger-classic.position-pc-left #tayori-trigger-chevron, #tayori-trigger-classic.position-sp-left #tayori-trigger-chevron {\n  -moz-transform: rotate(90deg);\n  -webkit-transform: rotate(90deg);\n  transform: rotate(90deg);\n}\n#tayori-trigger-classic.mobile.position-sp-right #tayori-trigger-chevron,\n#tayori-trigger-classic.mobile.position-sp-left #tayori-trigger-chevron {\n  display: none;\n}\n\n\n.position-pc-right-bottom #tayori-trigger-icon, .position-pc-left-bottom #tayori-trigger-icon {\n  float: left;\n  margin: 5px 10px;\n}\n\n.position-pc-right-bottom #tayori-trigger-label, .position-pc-left-bottom #tayori-trigger-label {\n  float: left;\n  white-space: nowrap;\n  height: 15px;\n  margin: 10px 0;\n}\n\n.position-pc-right-bottom #tayori-trigger-chevron, .position-pc-left-bottom #tayori-trigger-chevron {\n  float: left;\n  background-size: 75%;\n  width: 19px;\n  height: 12px;\n  margin: 12px 10px;\n}\n\n.position-sp-bottom #tayori-trigger-label {\n  float: left;\n  white-space: nowrap;\n  height: 15px;\n  margin: 10px 0;\n}\n\n.position-sp-bottom #tayori-trigger-icon {\n  float: left;\n  margin: 5px 10px;\n}\n\n.position-sp-bottom #tayori-trigger-chevron {\n  float: left;\n  background-size: 75%;\n  width: 19px;\n  height: 12px;\n  margin: 12px 10px;\n}\n#tayori-modal #tayori-header #tayori-header-close-button{\n  height: 0;\n  background-repeat: no-repeat;\n  background-position: 0 0;\n  overflow: hidden;\n}\n.talk .status-close {\n  position: absolute;\n  width: 25%;\n  left: 75%;\n  top: 14px;\n  text-align: center;\n}\n.talk .status-close a {\n  font-size: 26px;\n  font-weight: bold;\n  color: #627373;\n  text-decoration: none;\n}\n#tayori-modal {\n  visibility: hidden;\n  background-color: #637a91;\n  border-radius: 6px;\n  position: fixed;\n  z-index: 2147483010;\n  top: 10%;\n  left: 50%;\n  height: 80%;\n  width: 780px;\n  margin-left: -390px;\n  -webkit-box-shadow: 0px 3px 8px 0px rgba(0,0,0,0.26);\n  box-shadow: 0px 3px 8px 0px rgba(0,0,0,0.26);\n  opacity: 0;\n  -webkit-overflow-scrolling: touch;\n background-color: #ffffff;\n}\n\n@media screen and (max-width: 800px) {\n  #tayori-modal {\n    width: 100%;\n    left: 0;\n    right: 0;\n    margin-left: 0;\n    padding-left: 0;\n    padding-right: 0;\n  }\n}\n#tayori-modal.mobile {\n  width: 100%;\n  height: 100%;\n  left: 0;\n  top: 0;\n  margin: 0;\n  -webkit-box-shadow: none;\n  box-shadow: none;\n  position: fixed;\n  opacity: 1;\n}\n#tayori-modal.tayori-modal--expand {\n  top: 0;\n  height: 100%;\n}\n#tayori-modal #tayori-header {\n  position: relative;\n  padding-top: 12px;\n  padding-bottom: 6px;\n  background-color: #f0f0f0;\n}\n\n#tayori-modal #tayori-header #tayori-header-title {\n  font-size: 16px;\n  font-weight: bold;\n  text-align: center;\n  color: #fff;\n  padding: 0;\n  margin: 0 0 8px 0;\n}\n#tayori-modal #tayori-header #tayori-header-close-button {\n  position: absolute;\n  right:    10px;\n  top: 50%;\n  background-image: url(\""+myScript.plugins_Url+"/tayori/images/customer_close.png\");\n  background-position: center center;\n  width: 44px;\n  padding-top: 44px;\n  margin-top: -30px;\n  margin-left: -13px;\n  margin-right: -13px;\n}\n@media screen and (max-width: 800px) {\n  #tayori-modal #tayori-header #tayori-header-close-button {\n    right: 10px;\n  }\n}\n#tayori-modal.mobile #tayori-header #tayori-header-close-button {\n  right: auto;\n  left: 10px;\n}\n#tayori-modal #tayori-modal-content-container {\n  position: absolute;\n  top: 58px;\n  bottom: 38px;\n  left: 10px;\n  right: 10px;\n  -webkit-overflow-scrolling: touch;\n}\n#tayori-modal #tayori-modal-content-container.talk {\n  top: 0;\n  bottom: 0;\n  left: 0;\n  right: 0;\n}\n#tayori-modal.mobile #tayori-modal-content-container {\n  overflow: hidden;\n}\n#tayori-modal #tayori-modal-content-container #tayori-modal-content {\n  background-color: #fff;\n  height: 100%;\n  width: 100%;\n  position: relative;\n  vertical-align: bottom;\n}\n@media screen and (max-width: 800px) {\n  #tayori-modal #tayori-modal-content-container #tayori-modal-content {\n    width: 100%;\n  }\n}\n#tayori-modal #tayori-modal-content-container #tayori-modal-content .customer-container {\n  width: 620px;\n}\n#tayori-modal #tayori-modal-content-container #tayori-modal-content .customer-container .contact-container {\n  border-radius: 0;\n  margin-top: 0;\n}\n#tayori-modal #tayori-footer {\n  background-color: #f0f0f0;\n  text-align:  center;\n  position:    absolute;\n  bottom:      0;\n  left:        0;\n  right:       0;\n  padding-top: 12px;\n  padding-bottom: 12px;\n}\n#tayori-modal #tayori-footer a{\n  background-image:  url(\""+myScript.plugins_Url+"/tayori/images/icn_gray.png\");\n  background-repeat: no-repeat;\n  background-size:   16px 16px;\n  background-position: left center;\n  padding-left:      23px;\n  color:             #9da6a6;\n  font-size:         x-small;\n  text-decoration:   none;\n  padding-top: 15px;\n  padding-bottom: 15px;\n}\n\n#tayori-footer.tayori-footer-mobile a{\n  background-size:   18px 18px !important;\n  padding-left:      25px !important;\n}\n\n#tayori-modal #tayori-footer a:hover{\n  background-image:  url(\""+myScript.plugins_Url+"/tayori/images/icn_green.png\");\n  color:             #43BFA0;\n}\n\n#tayori-container #tayori-modal {\n  background-color: #ffffff;\n}\n\n#tayori-container #tayori-header #tayori-header-title {\n  color: #444444;\n}\n\n.pc-chat {\n  border: 0;\n  width: 500px !important;\n  height: 480px !important;\n  margin-left: -250px !important;\n}\n\n.pc-chat #tayori-modal-content-container {\n  top: 0 !important;\n}\n\n.pc-chat #tayori-modal-content {\n  height: 480px !important;\n  background-color: transparent !important;\n}\n\n.pc-chat #tayori-header {\n  background-color: transparent !important;\n}\n\n\n.pc-chat #tayori-header #tayori-header-close-button {\n  z-index: 10;\n  margin-top: -15px !important;\n}";
          if (json.button_type == 1) {
            buttontype = "simple";
          } else if (json.button_type == 2) {
            buttontype = "pop";
          } else {
            buttontype = "classic";
          }
          tayoriButtonType = buttontype;
          trigger_class = '';
          if(isMobile){
            if(json.button_position_sp == 1){
              trigger_class += "position-sp-right";
            }else if(json.button_position_sp == 2){
              trigger_class += "position-sp-left";
            }else if(json.button_position_sp == 3){
              trigger_class += "position-sp-bottom";
            }else if(json.button_position_sp == 4){
              trigger_class += "position-sp-bottom";
            }
          }else{
            if(json.button_position_pc == 1){
              trigger_class += "position-pc-right";
            }else if(json.button_position_pc == 2){
              trigger_class += "position-pc-left";
            }else if(json.button_position_pc == 3){
              trigger_class += "position-pc-right-bottom";
            }else if(json.button_position_pc == 4){
              trigger_class += "position-pc-left-bottom";
            }
          }
          if(json.button_icon_transparent_type == 2){
            trigger_class += " opacity";
          }
          t_template_classic = '<div id="tayori-trigger-classic" unselectable="on" class="';
          t_template_classic += trigger_class;
          t_template_classic += '">';
          t_template_classic += '<div id="tayori-trigger-icon"></div>';
          t_template_classic += '<div id="tayori-trigger-label">' + json.button_title + '</div>';
          t_template_classic += '<div id="tayori-trigger-chevron"></div>';
          t_template_classic += '</div>';
          t_template_simple = '<div id="tayori-trigger-simple" unselectable="on" class="';
          t_template_simple += trigger_class;
          t_template_simple += '">';
          t_template_simple += '</div>';
          t_template_pop = '<div id="tayori-trigger-pop" unselectable="on" class="';
          t_template_pop += trigger_class;
          t_template_pop += '">';
          t_template_pop += '</div>';
          
          jQuery("#tayori-container").append("<style>" + css_template + "</style>");
          if (buttontype == 'simple') {
            jQuery("#tayori-container").append(t_template_simple);
          } else if (buttontype == 'pop') {
            jQuery("#tayori-container").append(t_template_pop);
          } else if (buttontype == 'classic') {
            jQuery("#tayori-container").append(t_template_classic);
          }
          if (buttontype == 'pop') {
            popNumber = json.pop_button_type;
            if (popNumber < 10) {
              popNumber = '0' + popNumber;
            }
            jQuery('#tayori-trigger-pop').css({
              'background-image': 'url('+myScript.plugins_Url+'/tayori/images/' + popNumber + '.png)'
            });
          }
          if (buttontype != 'classic') {
            if (jQuery('#tayori-trigger-' + buttontype).hasClass('position-pc-left') || jQuery('#tayori-trigger-' + buttontype).hasClass('position-pc-left-bottom') || jQuery('#tayori-trigger-' + buttontype).hasClass('position-sp-left')) {
              left = 10;
              top = jQuery(window).height() - jQuery('#tayori-trigger-' + buttontype).height() - 20;
            } else {
              left = jQuery(window).width() - jQuery('#tayori-trigger-' + buttontype).width() - 10;
              top = jQuery(window).height() - jQuery('#tayori-trigger-' + buttontype).height() - 20;
            }
            jQuery('#tayori-trigger-' + buttontype).css({
              'top': top + 'px',
              'left': left + 'px'
            });
          }
          buttonDefaultOpacity = 1;
          if (json.button_icon_transparent_type == 2 || json.button_icon_transparent_type == 3) {
            buttonDefaultOpacity = 0.7;
          }
          jQuery('#tayori-trigger-' + buttontype).css({
            'display': 'block',
            'opacity': buttonDefaultOpacity
          });
          
          if (buttontype == 'simple' || buttontype == 'pop') {
            draggingTime = 0;
            dragging = false;
            dragtimer = null;
            buttonStartPosition = [];
            buttonPosition = [0, 0];
            jQuery('#tayori-trigger-' + buttontype).draggable({
              scroll: false
            }).on({
              'click': function(e) {},
              'dragstart': function(e) {
                if (!modal_open) {
                  dragging = true;
                  draggingTime = 0;
                  buttonStartPosition = [jQuery(this).position().top, jQuery(this).position().left];
                  dragtimer = setInterval(function() {
                    draggingTime++;
                  }, 10);
                }
              },
              'drag': function(e) {
                if (!modal_open) {
                  jQuery(this).stop();
                  buttonPosition = [jQuery(this).position().top, jQuery(this).position().left];
                }
              },
              'dragstop': function(e) {
                var abs_x, abs_y, dist, posX, posY, speed, time, tl, x, y;
                if (!modal_open) {
                  dragging = false;
                  clearInterval(dragtimer);
                  buttonPosition = [jQuery(this).position().top, jQuery(this).position().left];
                  if (draggingTime < 20) {
                    x = buttonPosition[1] - buttonStartPosition[1];
                    abs_x = Math.abs(x);
                    y = buttonPosition[0] - buttonStartPosition[0];
                    abs_y = Math.abs(y);
                    dist = Math.sqrt(Math.pow(abs_x, 2) + Math.pow(abs_y, 2));
                    if (dist > 20) {
                      speed = dist / draggingTime;
                      time = draggingTime * 20 / 1000;
                      posX = buttonStartPosition[1] + x * 1.8;
                      posY = buttonStartPosition[0] + y * 1.8;
                      tl = new TimelineMax();
                      tl.to(jQuery(this), time, {
                        top: posY + 'px',
                        left: posX + 'px',
                        opacity: 0,
                        ease: Quad.easeOut
                      }).set(jQuery(this), {
                        display: 'none'
                      });
                    } else {
                      tl = new TimelineMax();
                      tl.to(jQuery(this), 0.15, {
                        scale: 1.05,
                        opacity: buttonDefaultOpacity * 0.8,
                        ease: Sine.easeInOut
                      }).to(jQuery(this), 0.02, {}).to(jQuery(this), 0.25, {
                        scale: 1,
                        opacity: buttonDefaultOpacity,
                        ease: Sine.easeInOut
                      });
                    }
                  } else {
                    tl = new TimelineMax();
                    tl.to(jQuery(this), 0.15, {
                      scale: 1.05,
                      opacity: buttonDefaultOpacity * 0.8,
                      ease: Sine.easeInOut
                    }).to(jQuery(this), 0.02, {}).to(jQuery(this), 0.25, {
                      scale: 1,
                      opacity: buttonDefaultOpacity,
                      ease: Sine.easeInOut
                    });
                  }
                }
              },
              'mouseenter': function(e) {
                var tl;
                if (!modal_open) {
                  if (!dragging && !isMobile) {
                    tl = new TimelineMax();
                    tl.to(jQuery(this), 0.2, {
                      scale: 1.1,
                      opacity: buttonDefaultOpacity * 0.8,
                      ease: Sine.easeInOut
                    }).to(jQuery(this), 0.02, {}).to(jQuery(this), 0.3, {
                      scale: 1,
                      opacity: buttonDefaultOpacity,
                      ease: Sine.easeInOut
                    });
                  }
                }
              },
              'mouseleave': function(e) {},
              'mousedown': function(e) {
                var tl;
                if (!modal_open) {
                  tl = new TimelineMax();
                  tl.to(jQuery(this), 0.05, {
                    scale: 0.95,
                    opacity: buttonDefaultOpacity * 0.9,
                    ease: Sine.easeInOut
                  });
                }
              },
              'mouseup': function(e) {
                var tl;
                if (!modal_open) {
                  if (!dragging) {
                    tl = new TimelineMax().to(jQuery(this), 0.05, {
                      scale: 1,
                      opacity: buttonDefaultOpacity,
                      ease: Sine.easeInOut
                    });
                  }
                }
              }
            });
          } else {
            fontcolor = json.button_font_color;
            iconType = json.button_icon_type;
            if (getBright(fontcolor, mod) < 0.5) {
              document.getElementById('tayori-trigger-chevron').className = 'chevron-black';
            } else {
              document.getElementById('tayori-trigger-chevron').className = 'chevron-white';
            }
            if (iconType == 1) {
              document.getElementById('tayori-trigger-icon').className = 'logo-tayori';
            }
            jQuery('#tayori-trigger-' + buttontype).on({
              'mouseenter': function(e) {
                var tl;
                if (!modal_open) {
                  if (jQuery(this).hasClass('position-pc-right')) {
                    tl = new TimelineMax();
                    tl.to(jQuery(this), 0.05, {
                      right: 0,
                      opacity: 1,
                      ease: Sine.easeInOut
                    }).to(jQuery('#tayori-trigger-chevron'), 0.16, {
                      rotationX: 360,
                      ease: Sine.easeInOut
                    }, 0).to(jQuery('#tayori-trigger-chevron'), 0.08, {
                      scaleY: 0.3,
                      ease: Sine.easeIn
                    }, 0).to(jQuery('#tayori-trigger-chevron'), 0.08, {
                      scaleY: 1,
                      ease: Sine.easeOut
                    }).set(jQuery('#tayori-trigger-chevron'), {
                      rotationX: 0
                    });
                  } else if (jQuery(this).hasClass('position-pc-left')) {
                    tl = new TimelineMax();
                    tl.to(jQuery(this), 0.05, {
                      left: 0,
                      opacity: 1,
                      ease: Sine.easeInOut
                    }).to(jQuery('#tayori-trigger-chevron'), 0.16, {
                      rotationX: -360,
                      ease: Sine.easeInOut
                    }, 0).to(jQuery('#tayori-trigger-chevron'), 0.08, {
                      scaleY: 0.3,
                      ease: Sine.easeIn
                    }, 0).to(jQuery('#tayori-trigger-chevron'), 0.08, {
                      scaleY: 1,
                      ease: Sine.easeOut
                    }).set(jQuery('#tayori-trigger-chevron'), {
                      rotationX: 0
                    });
                  } else if (jQuery(this).hasClass('position-pc-right-bottom') || jQuery(this).hasClass('position-pc-left-bottom')) {
                    tl = new TimelineMax();
                    tl.to(jQuery(this), 0.05, {
                      bottom: 0,
                      opacity: 1,
                      ease: Sine.easeInOut
                    }).to(jQuery('#tayori-trigger-chevron'), 0.16, {
                      rotationY: 360,
                      ease: Sine.easeInOut
                    }, 0).to(jQuery('#tayori-trigger-chevron'), 0.08, {
                      scaleX: 0.3,
                      ease: Sine.easeIn
                    }, 0).to(jQuery('#tayori-trigger-chevron'), 0.08, {
                      scaleX: 1,
                      ease: Sine.easeOut
                    }).set(jQuery('#tayori-trigger-chevron'), {
                      rotationY: 0
                    });
                  }
                }
              },
              'mouseleave': function(e) {
                var tl;
                if (!modal_open) {
                  if (jQuery(this).hasClass('position-pc-right')) {
                    tl = new TimelineMax();
                    tl.to(jQuery(this), 0.05, {
                      right: '-2px',
                      opacity: buttonDefaultOpacity,
                      ease: Sine.easeInOut
                    });
                  } else if (jQuery(this).hasClass('position-pc-left')) {
                    tl = new TimelineMax();
                    tl.to(jQuery(this), 0.05, {
                      left: '-2px',
                      opacity: buttonDefaultOpacity,
                      ease: Sine.easeInOut
                    });
                  } else if (jQuery(this).hasClass('position-pc-right-bottom') || jQuery(this).hasClass('position-pc-left-bottom')) {
                    tl = new TimelineMax();
                    tl.to(jQuery(this), 0.05, {
                      bottom: '-2px',
                      opacity: buttonDefaultOpacity,
                      ease: Sine.easeInOut
                    });
                    return;
                  }
                }
              }
            });
          }
          if (json.button_icon_transparent_type == 3) {
            try {
              window.addEventListener('scroll', scrollEvent, false);
            } catch (_error) {
              e = _error;
              window.attachEvent('onscroll', scrollEvent);
            }
          }
          return jQuery('#tayori-trigger-' + buttontype).on({
            'click': function(e) {
              var hgt, m_template, modal, scl, tl, wh, wid;
              e.preventDefault();
              modal_open = true;
              $('#tayori-modal').css('display', 'block')
              if ((buttontype == 'simple' || buttontype == 'pop') && !isMobile) {
                if (json.form_type_pc == 1) {
                  wid = 780;
                  hgt = '80%';
                } else {
                  wid = 500;
                  hgt = '480px';
                }
                tl = new TimelineMax();
                return tl.set(jQuery(this), {
                  scale: 1
                }).to(jQuery(this), 0.5, {
                  rotation: 180,
                  opacity: 1,
                  width: wid + 'px',
                  height: hgt,
                  borderRadius: '5px',
                  left: (jQuery(window).width() - wid) * 0.5 + 'px',
                  top: jQuery(window).height() * 0.2 * 0.5 + 'px',
                  backgroundColor: '#fff',
                  scale: 1.05,
                  ease: Sine.easeInOut
                }).to(jQuery(this), 0.06, {}).set(jQuery(this), {
                  rotation: 0,
                  backgroundImage: ''
                }).to(jQuery(this), 0.2, {
                  scale: 1,
                  ease: Quad.easeInOut,
                  onComplete: function() {
                    var m_template, modal;
                    if (json.form_type_pc == 2) {
                      m_template = "<div id=\"tayori-modal\" class=\"pc-chat\">\n  <div id=\"tayori-header\">\n    <a href=\"javascript:void(0);\" id=\"tayori-header-close-button\">x</a>\n  </div>\n  <div id=\"tayori-modal-content-container\">\n    <iframe id=\"tayori-modal-content\" frameborder=\"0\" scrolling=\"auto\" allowtransparency=\"true\" src=\""+myScript.plugins_Url+"/tayori/includes/form_chat_pc.php\"></iframe>\n  </div>\n</div>";
                    } else {
                      m_template = "<div id=\"tayori-modal\">\n  <div id=\"tayori-header\">\n    <div id=\"tayori-header-title\">お問い合わせ</div>\n      <a href=\"javascript:void(0);\" id=\"tayori-header-close-button\">x</a>\n  </div>\n  <div id=\"tayori-modal-content-container\">\n    <iframe id=\"tayori-modal-content\" frameborder=\"0\" scrolling=\"auto\" allowtransparency=\"true\" src=\""+myScript.plugins_Url+"/tayori/includes/form.php\"></iframe>\n  </div>\n</div>";
                    }
                    if (!modal_flag) {
                      jQuery("#tayori-container").append(m_template);
                      jQuery('#tayori-modal-content').on('load', function(e) {
                        $iframe = this.contentWindow.document;
                        $('#form-close', $iframe).on({
                          'click': function(e) {
                            modalCloseClick(e, modal);
                          }
                        });
                      });
                      bindClick(document.getElementById('tayori-header-close-button'), function(e) {
                        return modalCloseClick(e, modal);
                      });
                      modal_flag = true;
                    }
                    tl = new TimelineMax();
                    tl.fromTo(jQuery('#tayori-modal'), 0.4, {
                      autoAlpha: 0,
                      visibility: 'visible'
                    }, {
                      autoAlpha: 1,
                      ease: Sine.easeInOut
                    }).fromTo(jQuery('#tayori-trigger-' + buttontype), 0.3, {
                      autoAlpha: 1,
                      visibility: 'visible'
                    }, {
                      autoAlpha: 0,
                      visibility: 'hidden',
                      ease: Sine.easeInOut
                    }, 0.4);
                  }
                });
              } else if ((buttontype == 'simple' || buttontype == 'pop') && isMobile) {
                $('html,body').css({height:'100%', overflow:'hidden'})

                wh = Math.max(jQuery(window).width() * 2, jQuery(window).height() * 2);
                scl = wh / jQuery(this).width();
                tl = new TimelineMax();
                return tl.to(jQuery(this), 0.45, {
                  rotation: 180,
                  opacity: 1,
                  scale: scl,
                  left: (jQuery(window).width() - jQuery(this).width()) * 0.5 + 'px',
                  top: (jQuery(window).height() - jQuery(this).height()) * 0.5 + 'px',
                  backgroundColor: '#fff',
                  ease: Sine.easeInOut
                }).set(jQuery(this), {
                  backgroundImage: '',
                  rotation: 0,
                  onComplete: function() {
                    var m_template, modal;
                    if (json.form_type_sp == 2) {
                      m_template = "<div id=\"tayori-modal\" class=\"mobile\">\n  <div id=\"tayori-modal-content-container\" class=\"talk\">\n    <iframe id=\"tayori-modal-content\" frameborder=\"0\" scrolling=\"auto\" allowtransparency=\"true\" src=\""+myScript.plugins_Url+"/tayori/includes/form_chat_sp.php\"></iframe>\n    <div class=\"status-close\"><a href=\"javascript:void(0);\" id=\"tayori-header-close-button\">x</a></div>\n  </div>\n</div>";
                    } else {
                      m_template = "<div id=\"tayori-modal\" class=\"mobile\">\n  <div id=\"tayori-header\">\n    <div id=\"tayori-header-title\">お問い合わせ</div>\n      <a href=\"javascript:void(0);\" id=\"tayori-header-close-button\">x</a>\n  </div>\n  <div id=\"tayori-modal-content-container\">\n    <iframe id=\"tayori-modal-content\" frameborder=\"0\" scrolling=\"auto\" allowtransparency=\"true\" src=\""+myScript.plugins_Url+"/tayori/includes/form.php\"></iframe>\n  </div>\n</div>";
                    }
                    if (!modal_flag) {
                      jQuery("#tayori-container").append(m_template);
                      jQuery('#tayori-modal-content').on('load', function(e) {
                        $iframe = this.contentWindow.document;
                        $('#form-close', $iframe).on({
                          'click': function(e) {
                            modalCloseClick(e, modal);
                          }
                        });
                      });
                      bindClick(document.getElementById('tayori-header-close-button'), function(e) {
                        return modalCloseClick(e, modal);
                      });
                      modal_flag = true;
                    }
                    tl = new TimelineMax();
                    tl.fromTo(jQuery('#tayori-modal'), 0.6, {
                      autoAlpha: 0,
                      visibility: 'visible'
                    }, {
                      autoAlpha: 1,
                      ease: Sine.easeInOut
                    });
                  }
                });
              } else if (!isMobile) {
                if (json.form_type_pc == 2) {
                  m_template = "<div id=\"tayori-modal\" class=\"pc-chat\">\n  <div id=\"tayori-header\">\n    <a href=\"javascript:void(0);\" id=\"tayori-header-close-button\">x</a>\n  </div>\n  <div id=\"tayori-modal-content-container\">\n    <iframe id=\"tayori-modal-content\" frameborder=\"0\" scrolling=\"auto\" allowtransparency=\"true\" src=\""+myScript.plugins_Url+"/tayori/includes/form_chat_pc.php\"></iframe>\n  </div>\n</div>";
                } else {
                  m_template = "<div id=\"tayori-modal\">\n  <div id=\"tayori-header\">\n    <div id=\"tayori-header-title\">お問い合わせ</div>\n      <a href=\"javascript:void(0);\" id=\"tayori-header-close-button\">x</a>\n  </div>\n  <div id=\"tayori-modal-content-container\">\n    <iframe id=\"tayori-modal-content\" frameborder=\"0\" scrolling=\"auto\" allowtransparency=\"true\" src=\""+myScript.plugins_Url+"/tayori/includes/form.php\"></iframe>\n  </div>\n</div>";
                }
                if (!modal_flag) {
                  jQuery("#tayori-container").append(m_template);
                  jQuery('#tayori-modal-content').on('load', function(e) {
                    $iframe = this.contentWindow.document;
                    $('#form-close', $iframe).on({
                      'click': function(e) {
                        modalCloseClick(e, modal);
                      }
                    });
                  });
                  bindClick(document.getElementById('tayori-header-close-button'), function(e) {
                    return modalCloseClick(e, modal);
                  });
                  modal_flag = true;
                }
                tl = new TimelineMax();
                return tl.set(jQuery('#tayori-modal'), {
                  transformPerspective: 600
                }).fromTo(jQuery('#tayori-modal'), 0.8, {
                  rotationX: -30,
                  autoAlpha: 0,
                  visibility: 'visible'
                }, {
                  rotationX: 0,
                  autoAlpha: 1,
                  ease: Back.easeOut
                }).to(jQuery('#tayori-trigger-' + buttontype), 0.4, {
                  autoAlpha: 0,
                  ease: Sine.easeInOut,
                  onComplete: function() {
                    if (jQuery('#tayori-trigger-' + buttontype).hasClass('position-pc-right')) {
                      tl = new TimelineMax();
                      return tl.set(jQuery('#tayori-trigger-' + buttontype), {
                        right: '-2px'
                      });
                    } else if (jQuery('#tayori-trigger-' + buttontype).hasClass('position-pc-left')) {
                      tl = new TimelineMax();
                      return tl.set(jQuery('#tayori-trigger-' + buttontype), {
                        left: '-2px'
                      });
                    } else if (jQuery('#tayori-trigger-' + buttontype).hasClass('position-pc-right-bottom') || jQuery('#tayori-trigger-' + buttontype).hasClass('position-pc-left-bottom')) {
                      tl = new TimelineMax();
                      return tl.set(jQuery('#tayori-trigger-' + buttontype), {
                        bottom: '-2px'
                      });
                    }
                  }
                }, 0);
              } else {
                $('html,body').css({height:'100%', overflow:'hidden'})
                if (json.form_type_sp == 2) {
                  m_template = "<div id=\"tayori-modal\" class=\"mobile\">\n  <div id=\"tayori-modal-content-container\" class=\"talk\">\n    <iframe id=\"tayori-modal-content\" frameborder=\"0\" scrolling=\"auto\" allowtransparency=\"true\" src=\""+myScript.plugins_Url+"/tayori/includes/form_chat_sp.php\"></iframe>\n    <div class=\"status-close\"><a href=\"javascript:void(0);\" id=\"tayori-header-close-button\">x</a></div>\n  </div>\n</div>";
                } else {
                  m_template = "<div id=\"tayori-modal\" class=\"mobile\">\n  <div id=\"tayori-header\">\n    <div id=\"tayori-header-title\">お問い合わせ</div>\n      <a href=\"javascript:void(0);\" id=\"tayori-header-close-button\">x</a>\n  </div>\n  <div id=\"tayori-modal-content-container\">\n    <iframe id=\"tayori-modal-content\" frameborder=\"0\" scrolling=\"auto\" allowtransparency=\"true\" src=\""+myScript.plugins_Url+"/tayori/includes/form.php\"></iframe>\n  </div>\n</div>";
                }
                if (!modal_flag) {
                  jQuery("#tayori-container").append(m_template);
                  jQuery('#tayori-modal-content').on('load', function(e) {
                    $iframe = this.contentWindow.document;
                    $('#form-close', $iframe).on({
                      'click': function(e) {
                        modalCloseClick(e, modal);
                      }
                    });
                  });
                  bindClick(document.getElementById('tayori-header-close-button'), function(e) {
                    return modalCloseClick(e, modal);
                  });
                  modal_flag = true;
                }
                tl = new TimelineMax();
                return tl.set(jQuery('#tayori-modal'), {
                  transformPerspective: 600
                }).fromTo(jQuery('#tayori-modal'), 1, {
                  rotationX: 90,
                  autoAlpha: 0,
                  visibility: 'visible'
                }, {
                  rotationX: 0,
                  autoAlpha: 1,
                  ease: Back.easeInOut
                }).to(jQuery('#tayori-trigger-' + buttontype), 0.2, {
                  autoAlpha: 0,
                  ease: Sine.easeInOut
                }, 0);
              }
            }
          });
        };
        bindClick = function(elem, handler) {
          if (elem.addEventListener) {
            return elem.addEventListener('click', handler, false);
          } else if (elem.attachEvent) {
            return elem.attachEvent('onclick', handler);
          }
        };
        modalCloseClick = function(e, popup) {
          var button_opacity, left, tl, top;
          e.preventDefault();
          if (json.button_icon_transparent_type == 1) {
            button_opacity = 1;
          } else {
            button_opacity = 0.7;
          }
          if ((tayoriButtonType == 'simple' || tayoriButtonType == 'pop') && !isMobile) {
            if (jQuery('#tayori-trigger-' + tayoriButtonType).hasClass('position-pc-left') || jQuery('#tayori-trigger-' + tayoriButtonType).hasClass('position-pc-left-bottom')) {
              left = 10;
              top = jQuery(window).height() - 56 - 20;
            } else {
              left = jQuery(window).width() - 56 - 10;
              top = jQuery(window).height() - 56 - 20;
            }
            var button_image = "";
            if (tayoriButtonType == 'pop') {
              popNumber = json.pop_button_type;
              if (popNumber < 10) {
                popNumber = '0' + popNumber;
              }
              button_image = myScript.plugins_Url+'/tayori/images/' + popNumber + '.png';
            }else{
              button_image = myScript.plugins_Url+'/tayori/images/simple-button-icon.png';
            }
            tl = new TimelineMax();
            return tl.to(jQuery('#tayori-trigger-' + tayoriButtonType), 0.2, {
              autoAlpha: 1,
              visibility: 'visible',
              ease: Sine.easeInOut
            }).to(jQuery('#tayori-modal'), 0.2, {
              autoAlpha: 0,
              visibility: 'hidden',
              ease: Sine.easeInOut
            }, 0.15).to(jQuery('#tayori-trigger-' + tayoriButtonType), 0.05, {}).to(jQuery('#tayori-trigger-' + tayoriButtonType), 0.15, {
              scale: 1.05,
              ease: Sine.easeOut
            }).to(jQuery('#tayori-trigger-' + tayoriButtonType), 0.05, {}).fromTo(jQuery('#tayori-trigger-' + tayoriButtonType), 0.6, {
              rotation: 180,
              backgroundImage: 'url(' + button_image + ')'
            }, {
              scale: 0.9,
              rotation: 0,
              width: '56px',
              height: '56px',
              borderRadius: '50%',
              left: left + 'px',
              top: top + 'px',
              opacity: button_opacity,
              backgroundColor: json.button_color,
              ease: Sine.easeInOut
            }).to(jQuery('#tayori-trigger-' + tayoriButtonType), 0.1, {}).to(jQuery('#tayori-trigger-' + tayoriButtonType), 0.15, {
              scale: 1,
              ease: Sine.easeInOut,
              onComplete: function() {
                return modal_open = false;
              }
            });
          } else if ((tayoriButtonType == 'simple' || tayoriButtonType == 'pop') && isMobile) {
            if (jQuery('#tayori-trigger-' + tayoriButtonType).hasClass('position-sp-left')) {
              left = 10;
              top = jQuery(window).height() - jQuery('#tayori-trigger-' + tayoriButtonType).height() - 20;
            } else {
              left = jQuery(window).width() - jQuery('#tayori-trigger-' + tayoriButtonType).width() - 10;
              top = jQuery(window).height() - jQuery('#tayori-trigger-' + tayoriButtonType).height() - 20;
            }
            $('html,body').css({height:'', overflow:''})
            var button_image = "";
            if (tayoriButtonType == 'pop') {
              popNumber = json.pop_button_type;
              if (popNumber < 10) {
                popNumber = '0' + popNumber;
              }
              button_image = myScript.plugins_Url+'/tayori/images/' + popNumber + '.png';
            }else{
              button_image = myScript.plugins_Url+'/tayori/images/simple-button-icon.png';
            }
            tl = new TimelineMax();
            return tl.to(jQuery('#tayori-modal'), 0.3, {
              autoAlpha: 0,
              display: 'none',
              visibility: 'hidden',
              ease: Sine.easeInOut
            }).fromTo(jQuery('#tayori-trigger-' + tayoriButtonType), 0.6, {
              rotation: 180,
              backgroundImage: 'url(' + button_image + ')'
            }, {
              rotation: 0,
              scale: 0.9,
              left: left + 'px',
              top: top + 'px',
              opacity: button_opacity,
              backgroundColor: json.button_color,
              ease: Sine.easeInOut
            }).to(jQuery('#tayori-trigger-' + tayoriButtonType), 0.1, {}).to(jQuery('#tayori-trigger-' + tayoriButtonType), 0.15, {
              scale: 1,
              ease: Sine.easeInOut,
              onComplete: function() {
                return modal_open = false;
              }
            });
          } else if (!isMobile) {
            tl = new TimelineMax();
            return tl.to(jQuery('#tayori-modal'), 0.6, {
              rotationX: -30,
              autoAlpha: 0,
              ease: Back.easeIn
            }).to(jQuery('#tayori-trigger-' + tayoriButtonType), 0.4, {
              autoAlpha: 1,
              ease: Sine.easeInOut,
              onComplete: function() {
                return modal_open = false;
              }
            }, 0.5);
          } else {
            $('html,body').css({height:'', overflow:''})
            tl = new TimelineMax();
            tl.to(jQuery('#tayori-modal'), 0.6, {
              rotationX: 30,
              autoAlpha: 0,
              display: 'none',
              ease: Back.easeIn
            }).to(jQuery('#tayori-trigger-' + tayoriButtonType), 0.4, {
              autoAlpha: 1,
              ease: Sine.easeInOut,
              onComplete: function() {
                return modal_open = false;
              }
            }, 0.5);
          }
        };
        timer = null;
        scrollEvent = function() {
          var buttonDefaultOpacity, tl;
          buttonDefaultOpacity = 1;
          if (json.button_icon_transparent_type == 2 || json.button_icon_transparent_type == 3) {
            buttonDefaultOpacity = 0.7;
          }
          if (timer != null) {
            clearTimeout(timer);
          } else {
            tl = new TimelineMax();
            tl.to(jQuery('#tayori-trigger-' + tayoriButtonType), 0.04, {
              opacity: 1,
              ease: Sine.easeInOut
            });
          }
          timer = setTimeout((function() {
            tl = new TimelineMax();
            tl.to(jQuery('#tayori-trigger-' + tayoriButtonType), 0.08, {
              opacity: buttonDefaultOpacity,
              ease: Sine.easeInOut
            });
            timer = null;
          }), 500);
        };
        getBright = function(colorcode, mod) {
          var bmod, bright, digitNumber, gmod, i, rgb, rmod;
          if (colorcode.match(/^#/)) {
            colorcode = colorcode.slice(1);
          }
          digitNumber = Math.floor(colorcode.length / 3);
          if (digitNumber < 1) {
            return false;
          }
          rgb = [];
          i = 0;
          while (i < 3) {
            rgb.push(parseInt(colorcode.slice(digitNumber * i, digitNumber * (i + 1)), 16));
            i++;
          }
          rmod = mod.r || 1;
          gmod = mod.g || 1;
          bmod = mod.b || 1;
          bright = Math.max(rgb[0] * rmod, rgb[1] * gmod, rgb[2] * bmod) / 255;
          return bright;
        };
        mod = {
          r: 0.9,
          g: 0.8,
          b: 0.4
        };
        jQuery(window).on({
          'resize':function(){
            var hgt, wid;
            if (modal_open) {
              if ((tayoriButtonType == 'simple' || tayoriButtonType == 'pop') && !isMobile) {
                if (json.form_type_pc == 1) {
                  wid = 780;
                  hgt = '80%';
                } else {
                  wid = 500;
                  hgt = '480px';
                }
                jQuery('#tayori-trigger-' + tayoriButtonType).css({
                  'height': hgt,
                  'left': (jQuery(window).width() - wid) * 0.5 + 'px',
                  'top': jQuery(window).height() * 0.2 * 0.5 + 'px'
                });
              }
            }
          }
        });
        return window.onmousewheel = function(e) {
          if (modalPage) {
            e = e || window.event;
            if (e.preventDefault) {
              e.preventDefault();
            }
            return e.returnValue = false;
          }
        };
      };
    });
  };
  setJS = function() {
    var tayori_script;
    tayori_script = document.createElement('script');
    tayori_script.type = 'text/javascript';
    if (tayori_script.readyState) {
      tayori_script.onreadystatechange = function() {
        if (tayori_script.readyState == 'loaded' || tayori_script.readyState == 'complete') {
          tayori_script.onreadystatechange = null;
          scriptHandler();
        }
      };
    } else {
      tayori_script.onload = function() {
        scriptHandler();
      };
    }
    tayori_script.src = '//code.jquery.com/jquery-1.11.3.min.js';
    document.body.appendChild(tayori_script);
    return (document.getElementsByTagName('head')[0] || document.documentElement).appendChild(tayori_script);
  };
  if (window.jQuery == void 0) {
    setJS();
  } else {
    var jqueryver = window.jQuery.fn.jquery.split(".");
    if(parseFloat(jqueryver[0]) >= 2 || (parseFloat(jqueryver[0]) == 1 && parseFloat(jqueryver[1]) >= 7)) {
      scriptHandler();
    }else{
      setJS();
    }
  }
  
}).call(this);
