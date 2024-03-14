(function() {
  jQuery.noConflict();
  jQuery(function() {
    var active_id, buttonID, button_name, image_url, isValidURL, pc_select_position, sp_select_position;
    if (0 < jQuery('#form-setting-panel').size()) {
      buttonID = '';
      button_name = ['simple', 'pop', 'classic'];
      jQuery('.button-box').each(function() {
        var buttontype, id;
        if (jQuery(this).hasClass('is-active')) {
          id = jQuery('button', this).data('id');
          buttonID = id;
          buttontype = jQuery('button', this).data('buttontype');
          // jQuery('#' + id + '-button-setting').css({
          //   'display': 'block'
          // });
        }
        if (jQuery('#form_setup_form_button_type').val() == '2') {
          if (jQuery('#form_setup_form_pop_button_type').val() == '') {
            return jQuery('#form_setup_form_pop_button_type').val(1);
          }
        }
      });
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
      if (jQuery('.button-title').size() > 0 && getBright(jQuery('.button-title').attr('data-color'), mod) < 0.5) {
        jQuery('rect').css({'fill': '#000000'});
      } else {
        jQuery('rect').css({'fill': '#ffffff'});
      }

      if (buttonID == 'simple') {
        jQuery("#form_setup_form_button_position_pc option[value='3']").remove();
        jQuery("#form_setup_form_button_position_pc option[value='4']").remove();
        jQuery("#form_setup_form_button_position_sp option[value='3']").remove();
        jQuery('#color-setting-area').appendTo('#simple-color-setting-area');
        jQuery('#trasnparency-setting-area').appendTo('#simple-trasnparency-setting-area');
      } else if (buttonID == 'pop') {
        jQuery("#form_setup_form_button_position_pc option[value='3']").remove();
        jQuery("#form_setup_form_button_position_pc option[value='4']").remove();
        jQuery("#form_setup_form_button_position_sp option[value='3']").remove();
        jQuery('#color-setting-area').appendTo('#pop-color-setting-area');
        jQuery('#trasnparency-setting-area').appendTo('#pop-trasnparency-setting-area');
        jQuery('.icon').each(function() {
          var popNumber;
          if (jQuery(this).hasClass('selected')) {
            popNumber = jQuery(this).data('poptype');
            if (popNumber < 10) {
              popNumber = '0' + popNumber;
            }
            return jQuery('.preview-box .pop-button').css({
              'background-image': 'url(' + tayori_plugin_url + '/images/' + popNumber + '.png)'
            });
          }
        });
      } else if (buttonID == 'classic') {
        jQuery('#color-setting-area').appendTo('#classic-color-setting-area');
        jQuery('#trasnparency-setting-area').appendTo('#classic-trasnparency-setting-area');
        bgColor = jQuery("#form_setup_form_button_font_color").parent(".area-color-box").css('background-color').toString();
        bgColor = bgColor.replace("rgb(", "");
        bgColor = bgColor.replace(")", "");
        bgColor = bgColor.split(",");
        bgColor = "#" + parseInt(bgColor[0]).toString(16) + parseInt(bgColor[1]).toString(16) + parseInt(bgColor[2]).toString(16);
        if (getBright(bgColor, mod) < 0.5) {
          jQuery('#area-modal-trigger-chevron').removeClass('chevron-white').addClass('chevron-black');
        } else {
          jQuery('#area-modal-trigger-chevron').removeClass('chevron-black').addClass('chevron-white');
        }
      }
      jQuery(document).on('click', '.p-icon-selector__list__item', function(e) {
        var popNumber, poptype;
        if (!jQuery(this).hasClass('is-focus')) {
          jQuery(".p-icon-selector__list__item").each(function() {
            return jQuery(this).removeClass('is-focus');
          });
          jQuery(this).addClass('is-focus');
          poptype = jQuery(this).data('poptype');
          popNumber = poptype;
          if (popNumber < 10) {
            popNumber = '0' + popNumber;
          }
          jQuery('#pop-button-preview-image').attr('src', tayori_plugin_url + '/images/' + popNumber + '.png');
          jQuery('#form_setup_form_pop_button_type').val(poptype);
        }
      });
      jQuery(document).on("click", ".button-box button", function() {
        var buttontype, cnt, i, id, pc_select_position, sp_select_position;
        id = jQuery(this).data('id');
        buttontype = jQuery(this).data('buttontype');
        jQuery('#form_setup_form_button_type').val(buttontype);
        if (buttontype != '2') {
          jQuery('#form_setup_form_pop_button_type').val('');
        }
        jQuery('.button-box').removeClass('is-active');
        jQuery('#' + id + '-button-box').addClass('is-active');

        jQuery('.button-panel').hide();
        if (id == 'simple') {
          jQuery('#color-setting-area').appendTo('#simple-color-setting-area');
          jQuery('#trasnparency-setting-area').appendTo('#simple-trasnparency-setting-area');
        } else if (id == 'pop') {
          jQuery('#color-setting-area').appendTo('#pop-color-setting-area');
          jQuery('#trasnparency-setting-area').appendTo('#pop-trasnparency-setting-area');
        } else if (id == 'classic') {
          jQuery('#color-setting-area').appendTo('#classic-color-setting-area');
          jQuery('#trasnparency-setting-area').appendTo('#classic-trasnparency-setting-area');
        }
        jQuery('#' + id + '-button-setting').show();

        buttonID = id;
        pc_select_position = jQuery("#form_setup_form_button_position_pc").val();
        sp_select_position = jQuery("#form_setup_form_button_position_sp").val();
        if (id == 'simple') {
          if (pc_select_position == 3 || pc_select_position == 4) {
            jQuery("#form_setup_form_button_position_pc").val("1");
          }
          if (sp_select_position == 3) {
            jQuery("#form_setup_form_button_position_sp").val("1");
          }
          jQuery("#form_setup_form_button_position_pc option[value='3']").remove();
          jQuery("#form_setup_form_button_position_pc option[value='4']").remove();
          jQuery("#form_setup_form_button_position_sp option[value='3']").remove();
          pc_select_position = jQuery("#form_setup_form_button_position_pc").val();
          sp_select_position = jQuery("#form_setup_form_button_position_sp").val();
          jQuery("#thumb_pc img").each(function(i) {
            if (parseInt(pc_select_position) + 4 != parseInt(i) + 1) {
              jQuery(this).attr("class", "hider");
            } else {
              jQuery(this).removeAttr("class");
            }
          });
          return jQuery("#thumb_sp img").each(function(i) {
            if (parseInt(pc_select_position) + 3 != parseInt(i) + 1) {
              jQuery(this).attr("class", "hider");
            } else {
              jQuery(this).removeAttr("class");
            }
          });
        } else if (id == 'pop') {
          if (pc_select_position == 3 || pc_select_position == 4) {
            jQuery("#form_setup_form_button_position_pc").val("1");
          }
          if (sp_select_position == 3) {
            jQuery("#form_setup_form_button_position_sp").val("1");
          }
          jQuery("#form_setup_form_button_position_pc option[value='3']").remove();
          jQuery("#form_setup_form_button_position_pc option[value='4']").remove();
          jQuery("#form_setup_form_button_position_sp option[value='3']").remove();
          pc_select_position = jQuery("#form_setup_form_button_position_pc").val();
          sp_select_position = jQuery("#form_setup_form_button_position_sp").val();
          jQuery("#thumb_pc img").each(function(i) {
            if (parseInt(pc_select_position) + 4 != parseInt(i) + 1) {
              jQuery(this).attr("class", "hider");
            } else {
              jQuery(this).removeAttr("class");
            }
          });
          return jQuery("#thumb_sp img").each(function(i) {
            if (parseInt(pc_select_position) + 3 != parseInt(i) + 1) {
              jQuery(this).attr("class", "hider");
            } else {
              jQuery(this).removeAttr("class");
            }
          });
        } else if (id == 'classic') {
           jQuery('#form_setup_form_button_position_pc').append(function() {
            if (jQuery('#form_setup_form_button_position_pc option[value=\'3\']').size() == 0) {
              return jQuery('<option>').val('3').text('右下');
            }
          });
          jQuery('#form_setup_form_button_position_pc').append(function() {
            if (jQuery('#form_setup_form_button_position_pc option[value=\'4\']').size() == 0) {
              return jQuery('<option>').val('4').text('左下');
            }
          });
          jQuery('#form_setup_form_button_position_sp').append(function() {
            if (jQuery('#form_setup_form_button_position_sp option[value=\'3\']').size() == 0) {
              return jQuery('<option>').val('3').text('下');
            }
          });
          jQuery("#thumb_pc img").each(function(i) {
            if (parseInt(pc_select_position) != parseInt(i) + 1) {
              jQuery(this).attr("class", "hider");
            } else {
              jQuery(this).removeAttr("class");
            }
          });
          return jQuery("#thumb_sp img").each(function(i) {
            if (parseInt(pc_select_position) != parseInt(i) + 1) {
              jQuery(this).attr("class", "hider");
            } else {
              jQuery(this).removeAttr("class");
            }
          });
        }
      });
      /*if (jQuery('input[name="form_setup_form[button_icon_type]"]:checked').val() == '1') {
        jQuery('#area-modal-trigger-icon').css('background-image', 'url(' + logo_image_url + ')');
        jQuery('.area-pick-thumb-box-logo').children('img').hide();
        jQuery('.area-pick-thumb-box-logo').css({
          'background-image': 'url(' + logo_image_url + ')',
          'background-repeat': 'no-repeat',
          'background-position': 'center',
          'background-size': 'contain'
        });
        jQuery('.area-pick-thumb-box-logo').children('img').hide();
      } else if (jQuery('input[name="form_setup_form[button_icon_type]"]:checked').val() == '2') {
        jQuery('#area-modal-trigger-icon').css('background-image', 'none');
        jQuery('.area-pick-thumb-box-logo').children('img').hide();
      } else if (jQuery('input[name="form_setup_form[button_icon_type]"]:checked').val() == '3') {
        jQuery('.area-pick-thumb-box-logo').children('img').hide();
        if (upload_image_url) {
          jQuery('.area-pick-thumb-box-logo').css({
            'background-image': 'url(' + upload_image_url + ')',
            'background-repeat': 'no-repeat',
            'background-position': 'center',
            'background-size': 'contain'
          });
          jQuery('.area-pick-thumb-box-logo').children('img').hide();
          jQuery('#area-modal-trigger-icon').css('background-image', 'url(' + upload_image_url + ')');
        }
      }*/
      // jQuery('.area-pick-thumb-box-logo').css({
      //   'display': 'block'
      // });
      jQuery(".area-color-box").colpick({
        layout: "hex",
        color: "ff8800",
        onSubmit: function(hsb, hex, rgb, el) {
          var update_id;
          update_id = jQuery(el).children('input').attr('id');
          if (update_id == 'form_setup_form_button_color') {
            // jQuery('#area-modal-trigger-all').animate({
            //   backgroundColor: "#" + hex
            // }, 400, "easeInOutSine");
            // jQuery("#area-modal-trigger-label").animate({
            //   backgroundColor: "#" + hex
            // }, 400, "easeInOutSine");
            // jQuery('.simple-button').animate({
            //   backgroundColor: "#" + hex
            // }, 400, "easeInOutSine");
            // jQuery('.pop-button').animate({
            //   backgroundColor: "#" + hex
            // }, 400, "easeInOutSine");
            jQuery('.p-btn-color-simulator').css({'backgroundColor': '#' + hex});
            jQuery('.p-btn-tab-color-simulator').css({'backgroundColor': '#' + hex});
          } else if (update_id == 'form_setup_form_button_font_color') {
          //   jQuery('#area-modal-trigger-all').children('#area-modal-trigger-label').animate({
          //     color: "#" + hex
          //   }, 400, "easeInOutSine");
          //   jQuery('.simple-button').animate({
          //     color: "#" + hex
          //   }, 400, "easeInOutSine");
          //   jQuery('.pop-button').animate({
          //     color: "#" + hex
          //   }, 400, "easeInOutSine");
          //   if (jQuery('#area-modal-trigger-chevron').attr('class') == 'chevron-white') {
          //     if (getBright("#" + hex, mod) < 0.5) {
          //       jQuery('#area-modal-trigger-chevron').fadeOut(200, 'easeInOutSine', function() {
          //         jQuery('#area-modal-trigger-chevron').removeClass('chevron-white').addClass('chevron-black');
          //         return jQuery('#area-modal-trigger-chevron').fadeIn(200, 'easeInOutSine');
          //       });
          //     }
          //   } else {
          //     if (getBright("#" + hex, mod) >= 0.5) {
          //       jQuery('#area-modal-trigger-chevron').fadeOut(200, 'easeInOutSine', function() {
          //         jQuery('#area-modal-trigger-chevron').removeClass('chevron-black').addClass('chevron-white');
          //         return jQuery('#area-modal-trigger-chevron').fadeIn(200, 'easeInOutSine');
          //       });
          //     }
          //   }
            jQuery('.button-title').css({color: '#' + hex});
            if (getBright('#' + hex, mod) < 0.5) {
              jQuery('rect').css({'fill': '#000000'});
            } else {
              jQuery('rect').css({'fill': '#ffffff'});
            }
          }
          // jQuery(el).animate({
          //   backgroundColor: "#" + hex
          // }, 400, "easeInOutSine");
          jQuery(el).find('.c-color-chip__target').css({backgroundColor: "#" + hex});
          jQuery(el).children("input").val("#" + hex);
          jQuery(el).colpickHide();
        }
      });
      // isValidURL = function(url) {
      //   var RegExp;
      //   RegExp = /(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
      //   if (RegExp.test(url)) {
      //     return true;
      //   } else {
      //     return false;
      //   }
      // };
      // jQuery("#code-setting-site-url-button").on("click", function() {
      //   var html, url;
      //   url = jQuery("#code-setting-site-url-button-text").val();
      //   if (url && isValidURL(url)) {
      //     html = '<li><p>' + url + '</p>';
      //     html = html + '<input type="hidden" name="form_setup_form[code_setting_button_site_url][]" value="' + url + '" />';
      //     html = html + '<a class="code-setting-site-url-button-delete" href="#"><span class="glyphicon glyphicon-remove"></span></a></li>';
      //     jQuery("#code-setting-site-url-button-list").append(html);
      //     jQuery("#code-setting-site-url-button-text").val('');
      //   }
      // });
      // jQuery(".code-setting-site-url-button-delete").on("click", function() {
      //   jQuery(this).parent("li").remove();
      //   return false;
      // });
      // jQuery("#code-setting-site-url-iframe").on("click", function() {
      //   var html, url;
      //   url = jQuery("#code-setting-site-url-iframe-text").val();
      //   if (url && isValidURL(url)) {
      //     html = '<li><p>' + url + '</p>';
      //     html = html + '<input type="hidden" name="form_setup_form[code_setting_iframe_site_url][]" value="' + url + '" />';
      //     html = html + '<a class="code-setting-site-url-iframe-delete" href="#"><span class="glyphicon glyphicon-remove"></span></a></li>';
      //     jQuery("#code-setting-site-url-iframe-list").append(html);
      //     jQuery("#code-setting-site-url-iframe-text").val('');
      //   }
      // });
      // jQuery(".code-setting-site-url-iframe-delete").on("click", function() {
      //   jQuery(this).parent("li").remove();
      //   return false;
      // });
      pc_select_position = jQuery("#form_setup_form_button_position_pc").val();
      jQuery("#thumb_pc img").each(function(i) {
        if (buttonID == 'classic') {
          if (parseInt(pc_select_position) != parseInt(i) + 1) {
            jQuery(this).attr("class", "hider");
          } else {
            jQuery(this).removeAttr("class");
          }
        } else {
          if (parseInt(pc_select_position) + 4 != parseInt(i) + 1) {
            jQuery(this).attr("class", "hider");
          } else {
            jQuery(this).removeAttr("class");
          }
        }
      });
      sp_select_position = jQuery("#form_setup_form_button_position_sp").val();
      jQuery("#thumb_sp img").each(function(i) {
        if (buttonID == 'classic') {
          if (parseInt(sp_select_position) != parseInt(i) + 1) {
            jQuery(this).attr("class", "hider");
          } else {
            jQuery(this).removeAttr("class");
          }
        } else {
          if (parseInt(sp_select_position) + 3 != parseInt(i) + 1) {
            jQuery(this).attr("class", "hider");
          } else {
            jQuery(this).removeAttr("class");
          }
        }
      });
      jQuery("#form_setup_form_button_position_pc").change(function() {
        pc_select_position = jQuery(this).val();
        return jQuery("#thumb_pc img").each(function(i) {
          if (buttonID == 'classic') {
            if (parseInt(pc_select_position) != parseInt(i) + 1) {
              jQuery(this).attr("class", "hider");
            } else {
              jQuery(this).removeAttr("class");
            }
          } else {
            if (parseInt(pc_select_position) + 4 != parseInt(i) + 1) {
              jQuery(this).attr("class", "hider");
            } else {
              jQuery(this).removeAttr("class");
            }
          }
        });
      });
      jQuery("#form_setup_form_button_position_sp").change(function() {
        sp_select_position = jQuery(this).val();
        return jQuery("#thumb_sp img").each(function(i) {
          if (buttonID == 'classic') {
            if (parseInt(sp_select_position) != parseInt(i) + 1) {
              jQuery(this).attr("class", "hider");
            } else {
              jQuery(this).removeAttr("class");
            }
          } else {
            if (parseInt(sp_select_position) + 3 != parseInt(i) + 1) {
              jQuery(this).attr("class", "hider");
            } else {
              jQuery(this).removeAttr("class");
            }
          }
        });
      });
      if (jQuery("#form_setup_form_form_type_sp").val() != '1') {
        jQuery("#form_sp img").each(function(i) {
          jQuery(this).attr("class", "hider");
          if (i + 1 == parseInt(jQuery("#form_setup_form_form_type_sp").val())) {
            jQuery(this).removeAttr("class");
          }
        });
      }
      jQuery("#form_setup_form_form_type_sp").change(function() {
        var sp_select_form;
        sp_select_form = jQuery(this).val();
        jQuery('input#form_setup_form_form_type_sp').val(sp_select_form);
        return jQuery("#form_sp img").each(function(i) {
          if (parseInt(sp_select_form) != parseInt(i) + 1) {
            jQuery(this).attr("class", "hider");
          } else {
            jQuery(this).removeAttr("class");
          }
        });
      });
      if (jQuery("#form_setup_form_form_type_pc").val() != '1') {
        jQuery("#form_pc img").each(function(i) {
          jQuery(this).attr("class", "hider");
          if (i + 1 == parseInt(jQuery("#form_setup_form_form_type_pc").val())) {
            jQuery(this).removeAttr("class");
          }
        });
      }
      jQuery("#form_setup_form_form_type_pc").change(function() {
        var pc_select_form;
        pc_select_form = jQuery(this).val();
        jQuery('input#form_setup_form_form_type_pc').val(pc_select_form);
        return jQuery("#form_pc img").each(function(i) {
          if (parseInt(pc_select_form) != parseInt(i) + 1) {
            jQuery(this).attr("class", "hider");
          } else {
            jQuery(this).removeAttr("class");
          }
        });
      });
      /*jQuery('input[name="form_setup_form[button_icon_type]"]').on("change", function(e) {
        var icon_type;
        icon_type = jQuery(this).val();
        if (icon_type == '1') {
          jQuery('#area-modal-trigger-icon').css('background-image', 'url(' + logo_image_url + ')');
          jQuery('.area-pick-thumb-box-logo').css('background-image', 'none');
          jQuery('.area-pick-thumb-box-logo').children('img').hide();
          jQuery('.area-pick-thumb-box-logo').css({
            'background-image': 'url(' + logo_image_url + ')',
            'background-repeat': 'no-repeat',
            'background-position': 'center',
            'background-size': 'contain'
          });
          jQuery('.area-pick-thumb-box-logo').children('img').hide();
        } else if (icon_type == '2') {
          jQuery('.area-pick-thumb-box-logo').children('img').hide();
          jQuery('#area-modal-trigger-icon').css('background-image', 'none');
          jQuery('.area-pick-thumb-box-logo').css('background-image', 'none');
        } else if (icon_type == '3') {
          jQuery('.area-pick-thumb-box-logo').children('img').hide();
          if (upload_image_url) {
            jQuery('.area-pick-thumb-box-logo').css({
              'background-image': 'url(' + upload_image_url + ')',
              'background-repeat': 'no-repeat',
              'background-position': 'center',
              'background-size': 'contain'
            });
            jQuery('#area-modal-trigger-icon').css('background-image', 'url(' + upload_image_url + ')');
          }
        }
      });*/
      jQuery('#form-button-color-select').on('change', function(e) {
        if (jQuery(this).val()) {
          button_color = getButtonColor(jQuery(this).val());
          //font_color = getFontColor(jQuery(this).val());
          if (button_color != false) {
            jQuery("#form_setup_form_button_color").val(button_color);
            jQuery("#form_setup_form_button_color").next('.c-color-chip__target').css({backgroundColor: button_color});
            jQuery('.p-btn-color-simulator').css({backgroundColor: button_color});
            jQuery('.p-btn-tab-color-simulator').css({backgroundColor: button_color});
          }
        }
      });
      // active_id = '#form-setting-panel1';
      // return jQuery('.form-setting-option-menu').on("click", function(e) {
      //   var active_id_pre;
      //   e.preventDefault();
      //   if (jQuery(this).data('id') != active_id) {
      //     active_id_pre = active_id;
      //     active_id = jQuery(this).data('id');
      //     jQuery(active_id_pre).fadeOut(200, 'easeOutSine', function() {
      //       jQuery(active_id).fadeIn(300, 'easeInSine');
      //     });
      //     jQuery('.form-setting-option-menu').each(function() {
      //       jQuery('a', this).css({
      //         'cursor': 'pointer'
      //       });
      //       jQuery(this).removeClass('active');
      //     });
      //     jQuery('a', this).css({
      //       'cursor': 'default'
      //     });
      //     return jQuery(this).addClass('active');
      //   }
      // });
      
      jQuery('#btn-submit').on('click', function(e) {
        jQuery(this).attr('disabled', 'disabled');
        jQuery('#form_setup_form').submit();
      });
    } else if (0 < jQuery('.area-pick-thumb-box-logo').size()) {
      // jQuery('#area-pick-thumb-box-logo').css('background-image', 'none');
      // image_url = jQuery('.area-pick-thumb-box-logo').children('img').attr('src');
      // jQuery('.area-pick-thumb-box-logo').css({
      //   'background-image': 'url(' + image_url + ')',
      //   'background-repeat': 'no-repeat',
      //   'background-position': 'center',
      //   'background-size': 'contain'
      // });
      // jQuery('.area-pick-thumb-box-logo').children('img').hide();
      // return jQuery('.area-pick-thumb-box-logo').css({
      //   'display': 'block'
      // });
    }
  });

  function getButtonColor(id)
  {
    if (parseInt(id) > 0 && parseInt(id) < 11) {
      index = parseInt(id) - 1;
      colors = [
        '#43bfa0',
        '#00b2ca',
        '#1d4e89',
        '#b38fb1',
        '#e73c4a',
        '#eb6440',
        '#faac2a',
        '#7da1bf',
        '#e3868f',
        '#8a8587'
      ]
      return colors[index];
    }
    return false;
  }

  function getFontColor(id)
  {
    return '#FFFFFF';
  }

}).call(this);
