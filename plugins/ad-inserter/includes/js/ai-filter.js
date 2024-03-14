if (typeof ai_filter != 'undefined') {

  function prev (el, selector) {
    if (selector) {
      let previous = el.previousElementSibling;
      while (previous && !previous.matches (selector)) {
        previous = previous.previousElementSibling;
      }
      return previous;
    } else {
      return el.previousElementSibling;
    }
  }

//jQuery (function ($) {
// ***
//  function ai_random_parameter () {
//    var current_time = new Date ().getTime ();
//    return '&ver=' + current_time + '-' + Math.round (Math.random () * 100000);
//  }
  function ai_random_parameter () {
    var current_time = new Date ().getTime ();
    return current_time + '-' + Math.round (Math.random () * 100000);
  }

  function process_filter_hook_data (ai_filter_hook_blocks) {

    var ai_debug = typeof ai_debugging !== 'undefined'; // 1
//    var ai_debug = false;

//    ai_filter_hook_blocks.removeClass ('ai-filter-check');
    // ***
    ai_filter_hook_blocks.forEach ((el, i) => {
      el.classList.remove ('ai-filter-check');
    });

    var enable_block = false;

    if (ai_debug) console.log ('');
    if (ai_debug) console.log ("AI FILTER HOOK DATA: " + ai_filter_hook_data);

    if (ai_filter_hook_data == '') {
      if (ai_debug) console.log ('AI FILTER HOOK DATA EMPTY');
      return;
    }
    try {
      var filter_hook_data_array = JSON.parse (ai_filter_hook_data);

    } catch (error) {
        if (ai_debug) console.log ('AI FILTER HOOK DATA JSON ERROR');
        return;
    }

//    if (filter_hook_data_array != null) ai_filter_hook_blocks.each (function () {
    // ***
    if (filter_hook_data_array != null) ai_filter_hook_blocks.forEach ((el, index) => {

//      var block_wrapping_div = $(this).closest ('div.AI_FUNCT_GET_BLOCK_CLASS_NAME');
      // ***
      var block_wrapping_div = el.closest ('div.' + ai_block_class_def);
//      var block = parseInt ($(this).data ('block'));
      // ***
      var block = parseInt (el.dataset.block);

//      if (ai_debug) console.log ('AI FILTER HOOK BLOCK', block_wrapping_div.attr ('class'));
      // ***
      if (ai_debug) console.log ('AI FILTER HOOK BLOCK', block_wrapping_div != null && block_wrapping_div.hasAttribute ('class') ? block_wrapping_div.getAttribute ('class') : '');

      enable_block = false;

      if (typeof filter_hook_data_array !== 'undefined') {
        if (filter_hook_data_array.includes ('*')) {
          enable_block = true;
          if (filter_hook_data_array.includes (- block)) {
            enable_block = false;
          }
        }
        else if (filter_hook_data_array.includes (block)) enable_block = true;
      }

      if (ai_debug) console.log ('AI FILTER HOOK BLOCK', block, enable_block ? 'ENABLED' : 'DISABLED');

//      $(this).css ({"visibility": "", "position": "", "width": "", "height": "", "z-index": ""});
      // ***
      el.style.visibility = '';
      el.style.position = 'none';
      el.style.width = '';
      el.style.height = '';
      el.style.zIndex = '';

      var comments = '';
      var comments_decoded = JSON.parse (ai_filter_hook_comments);
      if (typeof comments_decoded == 'string') {
        comments = comments_decoded;
      }
      else if (typeof comments_decoded == 'object') {
        comments = '';
        for (const [key, value] of Object.entries (comments_decoded)) {
          comments = comments + `${key}: ${value}\n`;
        }
      }
      else comments = ai_filter_hook_comments;

      if (typeof ai_front != 'undefined') {
  //      var debug_bar = $(this).prev ('.ai-debug-bar');
        // ***
        var debug_bar = prev (el, '.ai-debug-bar');
        if (debug_bar != null) {
    //      debug_bar.find ('.ai-status').text (enable_block ? ai_front.visible : ai_front.hidden);
          // ***
          debug_bar.querySelectorAll ('.ai-status').forEach ((element, index) => {
            element.textContent = enable_block ? ai_front.visible : ai_front.hidden;
          });

    //      debug_bar.find ('.ai-filter-data').attr ('title', comments);
          // ***
          debug_bar.querySelectorAll ('.ai-filter-data').forEach ((element, index) => {
            element.setAttribute ('title', comments);
          });
        }
      }

      if (!enable_block) {
//        $(this).hide (); // .ai-filter-check
        // ***
        el.style.display = 'none'; // .ai-filter-check

//        if (!block_wrapping_div.find ('.ai-debug-block').length) {
        // ***
        if (block_wrapping_div != null) {
          if (!block_wrapping_div.querySelector ('.ai-debug-block') != null) {
  //          block_wrapping_div.hide ();
            // ***
            block_wrapping_div.style.display = 'none'; // .ai-filter-check
          }

  //        block_wrapping_div.removeAttr ('data-ai');
          // ***
          block_wrapping_div.removeAttribute ('data-ai');

  //        if (block_wrapping_div.find ('.ai-debug-block')) {
          // ***
          if (block_wrapping_div.querySelector('.ai-debug-block') != null) {
  //          block_wrapping_div.css ({"visibility": ""}).removeClass ('ai-close');
            // ***
            block_wrapping_div.style.visibility = '';
            block_wrapping_div.classList.remove ('ai-close');

  //          if (block_wrapping_div.hasClass ('ai-remove-position')) {
            // ***
            if (block_wrapping_div.classList.contains ('ai-remove-position')) {
  //            block_wrapping_div.css ({"position": ""});
              block_wrapping_div.style.position = '';
            }

            // In case client-side insert is used and lists will not be processed
  //          if (typeof $(this).data ('code') != 'undefined') {
            // ***
            if ('code' in el.dataset) {
              // Remove ai-list-block to show debug info
  //            block_wrapping_div.removeClass ('ai-list-block');
  //            block_wrapping_div.removeClass ('ai-list-block-ip');
              // ***
              block_wrapping_div.classList.remove ('ai-list-block');
              block_wrapping_div.classList.remove ('ai-list-block-ip');

              // Remove also 'NOT LOADED' bar if it is there
  //            if (block_wrapping_div.prev ().hasClass ('ai-debug-info')) {
              // ***
              if (prev (block_wrapping_div) != null && prev (block_wrapping_div).classList.contains ('ai-debug-info')) {
  //              block_wrapping_div.prev ().remove ();
                // ***
                prev (block_wrapping_div).remove ();
              }
            }

  //        } else block_wrapping_div.hide ();
          // ***
          } else block_wrapping_div.style.display = 'none';;
        }
      } else {
//          block_wrapping_div.css ({"visibility": ""});
          // ***
          if (block_wrapping_div != null) {
            block_wrapping_div.style.visibility = '';

  //          if (block_wrapping_div.hasClass ('ai-remove-position')) {
            // ***
            if (block_wrapping_div.classList.contains ('ai-remove-position')) {
  //            block_wrapping_div.css ({"position": ""});
              // ***
              block_wrapping_div.style.position = '';
            }
          }
//          if (typeof $(this).data ('code') != 'undefined') {
          // ***
          if ('code' in el.dataset) {
//            var block_code = b64d ($(this).data ('code'));
            var block_code = b64d (el.dataset.code);

            var template = document.createElement ('div');
            template.innerHTML = block_code;

            var range = document.createRange ();

            var fragment_ok = true;
            try {
              var fragment = range.createContextualFragment (template.innerHTML);
            }
            catch (err) {
              var fragment_ok = false;
              if (ai_debug) console.log ('AI INSERT', 'range.createContextualFragment ERROR:', err.message);
            }

//            if ($(this).closest ('head').length != 0) {
            // ***
            if (el.closest ('head') != null) {
//              $(this).after (block_code);
              // ***
              el.insertBefore (fragment, null);

//              if (!ai_debug) $(this).remove ();
              // ***
              if (!ai_debug) el.remove ();
//            } else $(this).append (block_code);
            // ***
            } else el.parentNode.insertBefore (fragment, el.nextSibling);

//                if (!ai_debug)
//            $(this).attr ('data-code', '');
            // ***
            el.setAttribute ('data-code', '');

//            if (ai_debug) console.log ('AI INSERT CODE', $(block_wrapping_div).attr ('class'));
            // ***
            if (ai_debug) console.log ('AI INSERT CODE', block_wrapping_div != null && block_wrapping_div.hasAttribute ('class') ? block_wrapping_div.getAttribute ('class') : '');
            if (ai_debug) console.log ('');

//            ai_process_element (this);
            // ***
//            ai_process_element (el);
            ai_process_element (el.parentElement);
          }
        }

//      block_wrapping_div.removeClass ('ai-list-block-filter');
      if (block_wrapping_div != null) {
        block_wrapping_div.classList.remove ('ai-list-block-filter');
      }
    });
  }

//  ai_process_filter_hooks = function (ai_filter_hook_blocks) {
  // ***
  ai_process_filter_hooks = function (element) {

    var ai_debug = typeof ai_debugging !== 'undefined'; // 2
//    var ai_debug = false;

    if (element == null) {
//      ai_filter_hook_blocks = $("div.ai-filter-check, meta.ai-filter-check");
      // ***
      ai_filter_hook_blocks = document.querySelectorAll ("div.ai-filter-check, meta.ai-filter-check");
    } else {
        // Temp fix for jQuery elements
        // ***
        if (window.jQuery && window.jQuery.fn && element instanceof jQuery) {
          // Convert jQuery object to array
          ai_filter_hook_blocks = Array.prototype.slice.call (element);
        }

        // ***
//        ai_filter_hook_blocks = ai_filter_hook_blocks.filter ('.ai-filter-check');
        var filtered_elements = [];
        ai_filter_hook_blocks.forEach ((element, i) => {
          if (element.matches ('.ai-filter-check')) {
            filtered_elements.push (element);
          } else {
              var list_data_elements = element.querySelectorAll ('.ai-filter-check');
              if (list_data_elements.length) {
                list_data_elements.forEach ((list_element, i2) => {
                  filtered_elements.push (list_element);
                });
              }
            }
        });
        ai_filter_hook_blocks = filtered_elements;
      }

    if (!ai_filter_hook_blocks.length) return;

    if (ai_debug) console.log ("AI PROCESSING FILTER HOOK:", ai_filter_hook_blocks.length, "blocks");

    if (typeof ai_filter_hook_data != 'undefined') {
      if (ai_debug) console.log ("SAVED FILTER HOOK DATA:", ai_filter_hook_data);
      process_filter_hook_data (ai_filter_hook_blocks);
      return;
    }

    if (typeof ai_filter_hook_data_requested != 'undefined') {
      if (ai_debug) console.log ("FILTER HOOK DATA ALREADY REQUESTED, STILL WAITING...");
      return;
    }

    var user_agent = window.navigator.userAgent;
    var language = navigator.language;

    if (ai_debug) console.log ("REQUESTING FILTER HOOK DATA");
    if (ai_debug) console.log ("USER AGENT:", user_agent);
    if (ai_debug) console.log ("LANGUAGE:", language);

    ai_filter_hook_data_requested = true;

//    var page = site_url+"/wp-admin/admin-ajax.php?action=ai_ajax&filter-hook-data=all&ai_check=" + ai_data_id + '&http_user_agent=' + encodeURIComponent (user_agent) + '&http_accept_language=' + encodeURIComponent (language) + ai_random_parameter ();
//    $.get (page, function (filter_hook_data) {
    // ***
    var url_data = {
      action: "ai_ajax",
      'filter-hook-data': 'all',
      check: ai_data_id,
      http_user_agent: encodeURIComponent (user_agent),
      http_accept_language: encodeURIComponent (language),
      ver: ai_random_parameter ()
    };

    var formBody = [];
    for (var property in url_data) {
      var encodedKey = encodeURIComponent (property);
      var encodedValue = encodeURIComponent (url_data [property]);
      formBody.push (encodedKey + "=" + encodedValue);
    }
    formBody = formBody.join ("&");

    async function ai_filter_check () {
      const response = await fetch (ai_ajax_url + '?' + formBody, {
        method: 'GET',
      });

//      if (!response.ok) {
////        throw new Error(`HTTP error! status: ${response.status}`);
//        if (ai_debug) console.log ("Ajax call failed, Status: " + response.status + ", Error: " + response.statusText);
//      }

      const text = await response.text ();

      return text;
    }

    ai_filter_check ().then (filter_hook_data => {

      if (filter_hook_data == '') {
        var error_message = 'AI FILTER HOOK Ajax request returned empty data, filter hook checks not processed';
        console.error (error_message);

        if (typeof ai_js_errors != 'undefined') {
          ai_js_errors.push ([error_message, page, 0]);
        }
      } else {
          try {
            var filter_hook_data_test = JSON.parse (filter_hook_data);
          } catch (error) {
            var error_message = 'AI FILTER HOOK Ajax call returned invalid data, filter hook checks not processed';
            console.error (error_message);

            if (typeof ai_js_errors != 'undefined') {
              ai_js_errors.push ([error_message, page, 0]);
            }
          }
        }

      ai_filter_hook_data = JSON.stringify (filter_hook_data_test ['blocks']);
      ai_filter_hook_comments = JSON.stringify (filter_hook_data_test ['comments']);

      if (ai_debug) console.log ('');
      if (ai_debug) console.log ("AI FILTER HOOK RETURNED DATA:", ai_filter_hook_data);
      if (ai_debug) console.log ("AI FILTER HOOK RETURNED COMMENTS:", filter_hook_data_test ['comments']);

      // Check blocks again - some blocks might get inserted after the filte hook data was requested
//      ai_filter_hook_blocks = $("div.ai-filter-check, meta.ai-filter-check");
      ai_filter_hook_blocks = document.querySelectorAll ("div.ai-filter-check, meta.ai-filter-check");

      if (ai_debug) console.log ("AI FILTER HOOK BLOCKS:", ai_filter_hook_blocks.length);

      process_filter_hook_data (ai_filter_hook_blocks);
//    }).fail (function(jqXHR, status, err) {
    // ***
    }).catch ((error) => {
//      if (ai_debug) console.log ("Ajax call failed, Status: " + status + ", Error: " + err);
      // ***
      if (ai_debug) console.error ("AI FILTER ERROR:", error);
//      $("div.ai-filter-check").each (function () {
      document.querySelectorAll ('div.ai-filter-check').forEach ((el, index) => {
//        $(this).css ({"display": "none", "visibility": "", "position": "", "width": "", "height": "", "z-index": ""}).removeClass ('ai-filter-check').hide ();
        el.style.display = 'none';
        el.style.visibility = '';
        el.style.position = '';
        el.style.width = '';
        el.style.height = '';
        el.style.zIndex = '';

        el.classList.remove ('ai-filter-check');
        el.style.display = 'none';
      });
    });
  }


//  $(document).ready (function($) {
//    setTimeout (function () {ai_process_filter_hooks ()}, 3);
//  });
// ***
function ai_ready (fn) {
  if (document.readyState === 'complete' || (document.readyState !== 'loading' && !document.documentElement.doScroll)) {
    fn ();
  } else {
     document.addEventListener ('DOMContentLoaded', fn);
  }
}

function ai_check_filter_hooks () {
  setTimeout (function () {ai_process_filter_hooks ()}, 3);
}

ai_ready (ai_check_filter_hooks);

//});
// ***

function ai_process_element (element) {
  setTimeout (function() {
    if (typeof ai_process_rotations_in_element == 'function') {
      ai_process_rotations_in_element (element);
    }

    if (typeof ai_process_lists == 'function') {
      // ***
//      ai_process_lists (jQuery (".ai-list-data", element));
      ai_process_lists ();
    }

    if (typeof ai_process_ip_addresses == 'function') {
      // ***
//      ai_process_ip_addresses (jQuery (".ai-ip-data", element));
      ai_process_ip_addresses ();
    }

    if (typeof ai_process_filter_hooks == 'function') {
//      ai_process_filter_hooks (jQuery (".ai-filter-check", element));
      // ***
      ai_process_filter_hooks (element);
    }

    if (typeof ai_adb_process_blocks == 'function') {
      ai_adb_process_blocks (element);
    }

    if (typeof ai_process_impressions == 'function' && ai_tracking_finished == true) {
      ai_process_impressions ();
    }
    if (typeof ai_install_click_trackers == 'function' && ai_tracking_finished == true) {
      ai_install_click_trackers ();
    }

    if (typeof ai_install_close_buttons == 'function') {
      ai_install_close_buttons (document);
    }
  }, 5);
}

}

