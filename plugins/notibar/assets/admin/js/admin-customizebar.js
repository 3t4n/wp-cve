(function ($) {
  // Option Alignment
  wp.customize("njt_nofi_alignment", function (value) {
    value.bind(function (to) {
      if (to == 'center') {
        jQuery(".njt-nofi-container .njt-nofi-align-content").css({
          'justify-content': 'center'
        })
      }

      if (to == 'right') {
        jQuery(".njt-nofi-container .njt-nofi-align-content").css({
          'justify-content': 'flex-end'
        })
      }

      if (to == 'left') {
        jQuery(".njt-nofi-container .njt-nofi-align-content").css({
          'justify-content': 'flex-start'
        })
      }

      if (to == 'space_around') {
        jQuery(".njt-nofi-container .njt-nofi-align-content").css({
          'justify-content': 'space-around'
        })
      }
    })
  })
  // Hide/Close Button (No button, Toggle button, Close button)
  wp.customize("njt_nofi_hide_close_button", function (value) {
    value.bind(function (newValue, oldValue) {
      if (newValue == 'no_button') {
        jQuery(".njt-nofi-toggle-button").css({
          'display': 'none',
        })
        jQuery(".njt-nofi-close-button").css({
          'display': 'none',
        })
      }

      if (newValue == 'toggle_button') {
        jQuery(".njt-nofi-toggle-button").css({
          'display': 'block',
        })
        jQuery(".njt-nofi-close-button").css({
          'display': 'none',
        })
      }

      if (newValue == 'close_button') {
        jQuery(".njt-nofi-close-button").css({
          'display': 'block',
        })
        jQuery(".njt-nofi-toggle-button").css({
          'display': 'none',
        })
      }

      jQuery('body').animate({ top: 0 }, 1000)
      jQuery('.njt-nofi-display-toggle').css({
        'display': 'none',
        'top': 0,
      })
      if (jQuery(".njt-nofi-container").css('position') == 'fixed') {
        const wpAdminBarHeight = jQuery('#wpadminbar').length > 0  ? jQuery('#wpadminbar').outerHeight() : 0;
        jQuery('.njt-nofi-container').animate({ top: wpAdminBarHeight }, 1000)
      }
    });
  });

  //Content Width (px)
  wp.customize("njt_nofi_content_width", function (value) {
    value.bind(function (to) {
      if (to) {
        jQuery(".njt-nofi-notification-bar .njt-nofi-content").css({
          'width': to + 'px',
        })
      } else {
        jQuery(".njt-nofi-notification-bar .njt-nofi-content").css({
          'width': '100%',
        })
      }
    });
  });

  //Position Type
  wp.customize("njt_nofi_position_type", function (value) {
    value.bind(function (to) {
      if (to == 'fixed') {
        jQuery(".njt-nofi-container").css({
          'position': 'fixed',
        })

      } else {
        jQuery(".njt-nofi-container").css({
          'position': 'absolute',
        })
      }
    });
  });

  /*Content option*/
  //Text
  wp.customize("njt_nofi_text", function (value) {
    value.bind(function (to) {
      if (to.match(/\[([a-z0-9_]+)\]/g)) {
        jQuery.ajax({
          dataType: 'json',
          url: wpData.admin_ajax,
          type: "post",
          data: {
            action: "njt_nofi_text",
            nonce: wpData.nonce,
            text: to 
          },
        })
        .done(function (result) {
          console.log(result.data)
          jQuery('.njt-nofi-content-deskop .njt-nofi-text').html(result.data);
        })
        .fail(function (res) {
          console.log(res.responseText);
        });
      } else {
        jQuery('.njt-nofi-content-deskop .njt-nofi-text').html(to);
      }
      
      jQuery("body").on('DOMSubtreeModified', ".njt-nofi-content-deskop .njt-nofi-text", function () {
        var barHeight = jQuery('.njt-nofi-notification-bar').outerHeight();
        jQuery('body').css({
          'padding-top': barHeight,
          'position': 'relative'
        })
      });
    })
  })

  //Customize Button
  wp.customize("njt_nofi_handle_button", function (value) {
    value.bind(function (to) {
      const lbColorNotification = wp.customize.value('njt_nofi_lb_color')()
      if (to == 1) {
        jQuery('.njt-nofi-content-deskop .njt-nofi-button').show()
        jQuery('.njt-nofi-content-deskop .njt-nofi-button .njt-nofi-button-text').css({
          'background': lbColorNotification,
          'border-radius': '5px'
        })
      } else {
        jQuery('.njt-nofi-content-deskop .njt-nofi-button').hide()
      }
      var barHeight = jQuery('.njt-nofi-notification-bar').outerHeight();
      jQuery('body').css({
        'padding-top': barHeight,
        'position': 'relative'
      })
    })
  })


  //Link/Button Text
  wp.customize("njt_nofi_lb_text", function (value) {
    value.bind(function (to) {
      jQuery('.njt-nofi-content-deskop .njt-nofi-button-text').text(to);
      jQuery("body").on('DOMSubtreeModified', ".njt-nofi-content-deskop .njt-nofi-button-text", function () {
        var barHeight = jQuery('.njt-nofi-notification-bar').outerHeight();
        jQuery('body').css({
          'padding-top': barHeight,
          'position': 'relative'
        })
      });
    })
  })

  //Link/Button URL
  wp.customize("njt_nofi_lb_url", function (value) {
    value.bind(function (to) {
      jQuery('.njt-nofi-content-deskop .njt-nofi-button-text').attr('href', to);
    })
  })

  //Link/Button Font Weight
  wp.customize("njt_nofi_lb_font_weight", function (value) {
    value.bind(function (to) {
      jQuery('.njt-nofi-content-deskop .njt-nofi-button-text').css({
        'font-weight': to,
      })
    })
  })

  //You want different content for mobile
  wp.customize("njt_nofi_content_mobile", function (value) {
    value.bind(function (to) {
      if (to) {
        jQuery('.njt-nofi-content-deskop').addClass('njt-display-deskop')
        jQuery('.njt-nofi-content-mobile').addClass('njt-display-mobile')
      } else {
        jQuery('.njt-nofi-content-deskop').removeClass('njt-display-deskop')
        jQuery('.njt-nofi-content-mobile').removeClass('njt-display-mobile')
      }
      var barHeight = jQuery('.njt-nofi-notification-bar').outerHeight();
      jQuery('body').css({
        'padding-top': barHeight,
        'position': 'relative'
      })
    })
  })

  //Link/Button Text mobile 
  wp.customize("njt_nofi_text_mobile", function (value) {
    value.bind(function (to) {
      if (to.match(/\[([a-z0-9_]+)\]/g)) {
        jQuery.ajax({
          dataType: 'json',
          url: wpData.admin_ajax,
          type: "post",
          data: {
            action: "njt_nofi_text",
            nonce: wpData.nonce,
            text: to 
          },
        })
        .done(function (result) {
          console.log(result.data)
          jQuery('.njt-nofi-content-mobile .njt-nofi-text').html(result.data);
        })
        .fail(function (res) {
          console.log(res.responseText);
        });
      } else {
        jQuery('.njt-nofi-content-mobile .njt-nofi-text').html(to);
      }

      jQuery("body").on('DOMSubtreeModified', ".njt-display-mobile .njt-nofi-text", function () {
        var barHeight = jQuery('.njt-nofi-notification-bar').outerHeight();
        jQuery('body').css({
          'padding-top': barHeight,
          'position': 'relative'
        })
      });
    })
  })

  //Customize Button mobile
  wp.customize("njt_nofi_handle_button_mobile", function (value) {
    value.bind(function (to) {

      const lbColorNotification = wp.customize.value('njt_nofi_lb_color')()
      if (to == 1) {
        jQuery('.njt-nofi-content-mobile .njt-nofi-button').show()
        jQuery('.njt-nofi-content-mobile .njt-nofi-button .njt-nofi-button-text').css({
          'background': lbColorNotification,
          'border-radius': '5px'
        })

      } else {
        jQuery('.njt-nofi-content-mobile .njt-nofi-button').hide()
      }

      var barHeight = jQuery('.njt-nofi-notification-bar').outerHeight();
      jQuery('body').css({
        'padding-top': barHeight,
        'position': 'relative'
      })
    })
  })

  //Link/Button Text mobile
  wp.customize("njt_nofi_lb_text_mobile", function (value) {
    value.bind(function (to) {
      jQuery('.njt-nofi-content-mobile .njt-nofi-button-text').text(to);
      jQuery("body").on('DOMSubtreeModified', ".njt-nofi-content-mobile .njt-nofi-button-text", function () {
        var barHeight = jQuery('.njt-nofi-notification-bar').outerHeight();
        jQuery('body').css({
          'padding-top': barHeight,
          'position': 'relative'
        })
      });
    })
  })

  //Link/Button URL
  wp.customize("njt_nofi_lb_url_mobile", function (value) {
    value.bind(function (to) {
      jQuery('.njt-nofi-content-mobile .njt-nofi-button-text').attr('href', to);
    })
  })

  //Link/Button Font Weight
  wp.customize("njt_nofi_lb_font_weight_mobile", function (value) {
    value.bind(function (to) {
      jQuery('.njt-nofi-content-mobile .njt-nofi-button-text').css({
        'font-weight': to,
      })
    })
  })
  /*Style Option*/

  //Text Color
  wp.customize("njt_nofi_text_color", function (value) {
    value.bind(function (to) {
      jQuery(".njt-nofi-container .njt-nofi-text-color").css({
        'color': to
      })
    });
  });

  //Background Color
  wp.customize("njt_nofi_bg_color", function (value) {
    value.bind(function (to) {
      console.log(to)
      jQuery(".njt-nofi-container .njt-nofi-bgcolor-notification").css({
        'background': to
      })
    })
  })

  //Link/Button Color 
  wp.customize("njt_nofi_lb_color", function (value) {
    value.bind(function (to) {
      const presetColor = wp.customize.value('njt_nofi_preset_color')()
      jQuery(".njt-nofi-notification-bar .njt-nofi-button .njt-nofi-button-text").css({
        'background': to
      })
    })
  })

  //Button Text Color 
  wp.customize("njt_nofi_lb_text_color", function (value) {
    value.bind(function (to) {
      console.log(to);
      jQuery(".njt-nofi-notification-bar .njt-nofi-button-text").css({
        'color': to
      })
    })
  })

  //Font Size (px)
  wp.customize("njt_nofi_font_size", function (value) {
    value.bind(function (to) {
      jQuery(".njt-nofi-notification-bar .njt-nofi-content").css({
        'font-size': to + 'px'
      })
    })
  })
  /* Display Option*/

  //Select devices want to display
  wp.customize("njt_nofi_devices_display", function (value) {
    value.bind(function (to) {
      if (to == 'desktop') {
        jQuery(".njt-nofi-container-content").addClass('diplay-device-deskop')
        jQuery(".njt-nofi-container-content").removeClass('diplay-device-mobile')
      } else if (to == 'mobile') {
        jQuery(".njt-nofi-container-content").addClass('diplay-device-mobile')
        jQuery(".njt-nofi-container-content").removeClass('diplay-device-deskop')
      } else {
        jQuery(".njt-nofi-container-content").removeClass('diplay-device-deskop')
        jQuery(".njt-nofi-container-content").removeClass('diplay-device-mobile')
      }
      var barHeight = jQuery('.njt-nofi-notification-bar').outerHeight();
      jQuery('body').css({
        'padding-top': barHeight,
        'position': 'relative'
      })
    })
  })


  function checkDisplay(logicDisplayPage, arrDisplayPage, logicDisplayPost, arrDisplayPost) {
    const strCheckDisplayReview = jQuery('#njt_nofi_checkDisplayReview').attr('value')
    const arrCheckDisplayReview = JSON.parse(strCheckDisplayReview)
    const listDisplayPage = arrDisplayPage.split(',')
    const listDisplayPost = arrDisplayPost.split(',')

    if (logicDisplayPage == 'dis_selected_page') {
      if( (jQuery.inArray('home_page', listDisplayPage) != -1) && arrCheckDisplayReview.is_home ) return true;
    }

    if (logicDisplayPage == 'hide_selected_page') {
      if( (jQuery.inArray('home_page', listDisplayPage) != -1) && arrCheckDisplayReview.is_home ) return false;
    }
    
    if (arrCheckDisplayReview.is_page || arrCheckDisplayReview.is_home ) {
      if(logicDisplayPage == 'dis_all_page') return true;
      if(logicDisplayPage == 'hide_all_page') return false;
      if (logicDisplayPage == 'dis_selected_page') {
        if(jQuery.inArray(arrCheckDisplayReview.id_page.toString(), listDisplayPage) != -1) return true;
        return false;
      }
      if (logicDisplayPage == 'hide_selected_page') {
        if(jQuery.inArray(arrCheckDisplayReview.id_page.toString(), listDisplayPage) != -1) return false;
        return true;
      }
    }

    if (arrCheckDisplayReview.is_single) {
      if(logicDisplayPost == 'dis_all_post') return true;
      if(logicDisplayPost == 'hide_all_post') return false;
      if (logicDisplayPost == 'dis_selected_post' ) {
        if(jQuery.inArray(arrCheckDisplayReview.id_page.toString(), listDisplayPost) != -1) return true;
        return false;
      }
      if (logicDisplayPost == 'hide_selected_post') {
        if(jQuery.inArray(arrCheckDisplayReview.id_page.toString(), listDisplayPost) != -1) return false;
        return true;
      }
    }

    return false;
  }

 function processDisplay(isDisplay) {
  const barHeight = jQuery('.njt-nofi-notification-bar').outerHeight();
  if (!isDisplay) {
    jQuery('body').animate({ top: -barHeight }, 1000)
    jQuery('body').css({
      'position': 'relative',
    })
    if (jQuery(".njt-nofi-container").css('position') == 'fixed') {
      const wpAdminBarHeight = jQuery('#wpadminbar').length > 0  ? jQuery('#wpadminbar').outerHeight() : 0;
      const a = wpAdminBarHeight - barHeight
      jQuery('.njt-nofi-container').animate({ top: a + "px" }, 1000)
    }
    jQuery('.njt-nofi-display-toggle').css({
      'display': 'none',
    })
  } else {
    jQuery('body').animate({ top: 0 }, 1000)
    jQuery('.njt-nofi-display-toggle').css({
      'display': 'none',
      'top': 0,
    })
    if (jQuery(".njt-nofi-container").css('position') == 'fixed') {
      const wpAdminBarHeight = jQuery('#wpadminbar').length > 0  ? jQuery('#wpadminbar').outerHeight() : 0;
      jQuery('.njt-nofi-container').animate({ top: wpAdminBarHeight }, 1000)
    }
  }
 }

  wp.customize("njt_nofi_logic_display_page", function (value) {
    value.bind(function (to) {
      const logicDisplayPage = to
      const listDisplayPage = wp.customize.value('njt_nofi_list_display_page')()
      const logicDisplayPost = wp.customize.value('njt_nofi_logic_display_post')()
      const listDisplayPost = wp.customize.value('njt_nofi_list_display_post')()
      const isDisplay = checkDisplay(logicDisplayPage, listDisplayPage, logicDisplayPost, listDisplayPost);
      processDisplay(isDisplay)
    })
  })

  wp.customize("njt_nofi_list_display_page", function (value) {
    value.bind(function (to) {
      const logicDisplayPage = wp.customize.value('njt_nofi_logic_display_page')()
      const listDisplayPage = to
      const logicDisplayPost = wp.customize.value('njt_nofi_logic_display_post')()
      const listDisplayPost = wp.customize.value('njt_nofi_list_display_post')()
      const isDisplay = checkDisplay(logicDisplayPage, listDisplayPage, logicDisplayPost, listDisplayPost);
      processDisplay(isDisplay)
    })
  })

  wp.customize("njt_nofi_logic_display_post", function (value) {
    value.bind(function (to) {
      const logicDisplayPage = wp.customize.value('njt_nofi_logic_display_page')()
      const listDisplayPage = wp.customize.value('njt_nofi_list_display_page')()
      const logicDisplayPost = to
      const listDisplayPost = wp.customize.value('njt_nofi_list_display_post')()
      const isDisplay = checkDisplay(logicDisplayPage, listDisplayPage, logicDisplayPost, listDisplayPost);
      processDisplay(isDisplay)
    })
  })

  wp.customize("njt_nofi_list_display_post", function (value) {
    value.bind(function (to) {
      const logicDisplayPage = wp.customize.value('njt_nofi_logic_display_page')()
      const listDisplayPage = wp.customize.value('njt_nofi_list_display_page')()
      const logicDisplayPost = wp.customize.value('njt_nofi_logic_display_post')()
      const listDisplayPost = to
      const isDisplay = checkDisplay(logicDisplayPage, listDisplayPage, logicDisplayPost, listDisplayPost);
      processDisplay(isDisplay)
    })
  })

})(jQuery);
