
if (typeof ai_lists != 'undefined') {

function prevAll (element, selector) {
  var result = [];

  while (element = element.previousElementSibling) {
    if (typeof selector == 'undefined' || element.matches (selector)) {
      result.push (element);
    }
  }

  return result;
}

function nextAll (element, selector) {
  var result = [];

  while (element = element.nextElementSibling) {
    if (typeof selector == 'undefined' || element.matches (selector)) {
      result.push (element);
    }
  }

  return result;
}

// ***
//jQuery (function ($) {

  // ***
//  if (!Array.prototype.includes) {
//    //or use Object.defineProperty
//    Array.prototype.includes = function(search){
//     return !!~this.indexOf(search);
//    }
//  }

  // To prevent replacement of regexp pattern with CDN url (CDN code bug)
  var host_regexp = new RegExp (':' + '\\/' + '\\/(.[^/:]+)', 'i');

  function getHostName (url) {
//    var match = url.match (/:\/\/(.[^/:]+)/i);
    var match = url.match (host_regexp);
    if (match != null && match.length > 1 && typeof match [1] === 'string' && match [1].length > 0) {
      return match [1].toLowerCase();
    } else {
        return null;
      }
  }

  function ai_get_time (time_string) {
    if (time_string.includes (':')) {
      var time_parts = time_string.split (':');
      return ((parseInt (time_parts [0]) * 3600 + parseInt (time_parts [1]) * 60 + parseInt (time_parts [2])) * 1000);
    }

    return null;
  }

  function ai_get_date (date_time_string) {
    var date_time;

    try {
      date_time = Date.parse (date_time_string);
      if (isNaN (date_time)) date_time = null;
    } catch (error) {
      date_time = null;
    }

    // Try to parse separately date and time
    if (date_time == null && date_time_string.includes (' ')) {
      var date_time_parts = date_time_string.split (' ');

      try {
        date_time = Date.parse (date_time_parts [0]);
        date_time += ai_get_time (date_time_parts [1])

        if (isNaN (date_time)) date_time = null;
      } catch (error) {
        date_time = null;
      }
    }

    return date_time;
  }

  function ai_install_tcf_callback_useractioncomplete () {
    var ai_debug = typeof ai_debugging !== 'undefined'; // 1
//    var ai_debug = false;

    // ***
//    if ((jQuery('#ai-iab-tcf-bar').length || jQuery('.ai-list-manual').length) && typeof __tcfapi == 'function' && typeof ai_load_blocks == 'function' && typeof ai_iab_tcf_callback_installed == 'undefined') {
    if ((document.querySelector ('#ai-iab-tcf-bar') != null || document.querySelector ('.ai-list-manual') != null) && typeof __tcfapi == 'function' && typeof ai_load_blocks == 'function' && typeof ai_iab_tcf_callback_installed == 'undefined') {

      function ai_iab_tcf_callback (tcData, success) {
        if (ai_debug) console.log ("AI LISTS ai_iab_tcf_callback", success, tcData);

        if (success) {
          if (tcData.eventStatus === 'useractioncomplete') {
            ai_tcData = tcData;

            if (ai_debug) console.log ("AI LISTS ai_load_blocks ()");

            ai_load_blocks ();

            // ***
//            jQuery('#ai-iab-tcf-status').text ('IAB TCF 2.0 DATA LOADED');
//            jQuery('#ai-iab-tcf-bar').addClass ('status-ok').removeClass ('status-error');
            var iab_tcf_status = document.querySelector ('#ai-iab-tcf-status');
            if (iab_tcf_status != null) {
              iab_tcf_status.textContent = 'IAB TCF 2.0 DATA LOADED';
            }
            var iab_tcf_bar = document.querySelector ('#ai-iab-tcf-bar');
            if (iab_tcf_bar != null) {
              iab_tcf_bar.classList.remove ('status-error');
              iab_tcf_bar.classList.add ('status-ok');
            }
          }
        }
      }

      __tcfapi ('addEventListener', 2, ai_iab_tcf_callback);

      ai_iab_tcf_callback_installed = true;
    }
  }

  ai_process_lists = function (ai_list_blocks) {

    function ai_structured_data_item (indexes, data, value) {

      var ai_debug = typeof ai_debugging !== 'undefined'; // 2
//      var ai_debug = false;

      if (ai_debug) console.log ('');
      if (ai_debug) console.log ("AI LISTS COOKIE SELECTOR INDEXES", indexes);

      if (indexes.length == 0) {
        if (ai_debug) console.log ("AI LISTS COOKIE TEST ONLY PRESENCE", value == '!@!');

        if (value == '!@!') return true;

//        if (ai_debug) console.log ("AI LISTS COOKIE TEST VALUE", data, '==', value, '?', data == value);

        var check = data == value;

        var new_value = false;
        if (!check) {
          if (value.toLowerCase () == 'true') {
            value = true;
            new_value = true;
          } else
          if (value.toLowerCase () == 'false') {
            value = false;
            new_value = true;
          }

          if (new_value) {
//            if (ai_debug) console.log ("AI LISTS COOKIE TEST VALUE", data, '==', value, '?', data == value);
            check = data == value;
          }
        }

        if (ai_debug) console.log ("AI LISTS COOKIE TEST VALUE", data, '==', value, '?', data == value);

        return data == value;
      }

      if (typeof data != 'object' && typeof data != 'array') return false;

      var index = indexes [0];
      // Do not change indexes
      var new_indexes = indexes.slice (1);

      if (ai_debug) console.log ("AI LISTS COOKIE SELECTOR INDEX", index);

      if (index == '*') {
        for (let [data_index, data_item] of Object.entries (data)) {
          if (ai_debug) console.log ("AI LISTS COOKIE SELECTOR *", `${data_index}: ${data_item}`);

          if (ai_structured_data_item (new_indexes, data_item, value)) return true;
        }
      }
      else if (index in data) {
        if (ai_debug) console.log ('AI LISTS COOKIE SELECTOR CHECK [' + index + ']');

        return ai_structured_data_item (new_indexes, data [index], value);
      }

      if (ai_debug) console.log ("AI LISTS COOKIE SELECTOR NOT FOUND", index, 'in', data);
      if (ai_debug) console.log ('');

      return false;
    }

    function ai_structured_data (data, selector, value) {
      if (typeof data != 'object') return false;
      if (selector.indexOf ('[') == - 1) return false;

      var indexes = selector.replace (/]| /gi, '').split ('[');

      return ai_structured_data_item (indexes, data, value);
    }

    function call__tcfapi () {

      var ai_debug = typeof ai_debugging !== 'undefined'; // 3
//      var ai_debug = false;

      if (typeof __tcfapi == 'function') {

        if (ai_debug) console.log ("AI LISTS COOKIE tcf-v2: calling __tcfapi getTCData");

        // ***
//        $('#ai-iab-tcf-status').text ('IAB TCF 2.0 DETECTED');
        var iab_tcf_status = document.querySelector ('#ai-iab-tcf-status');
        var iab_tcf_bar    = document.querySelector ('#ai-iab-tcf-bar');
        if (iab_tcf_status != null) {
          iab_tcf_status.textContent = 'IAB TCF 2.0 DETECTED';
        }

        __tcfapi ('getTCData', 2, function (tcData, success) {
          if (success) {
            // ***
//            $('#ai-iab-tcf-bar').addClass ('status-ok');
            if (iab_tcf_bar != null) {
              iab_tcf_bar.classList.add ('status-ok');
            }

            if (tcData.eventStatus == 'tcloaded' || tcData.eventStatus == 'useractioncomplete') {
              ai_tcData = tcData;

              if (!tcData.gdprApplies) {
                // ***
//                jQuery('#ai-iab-tcf-status').text ('IAB TCF 2.0 GDPR DOES NOT APPLY');
                if (iab_tcf_status != null) {
                  iab_tcf_status.textContent = 'IAB TCF 2.0 GDPR DOES NOT APPLY';
                }
              } else {
                  // ***
//                  $('#ai-iab-tcf-status').text ('IAB TCF 2.0 DATA LOADED');
                  if (iab_tcf_status != null) {
                    iab_tcf_status.textContent = 'IAB TCF 2.0 DATA LOADED';
                  }
                }
              // ***
//              $('#ai-iab-tcf-bar').addClass ('status-ok').removeClass ('status-error');
              if (iab_tcf_bar != null) {
                iab_tcf_bar.classList.remove ('status-error');
                iab_tcf_bar.classList.add ('status-ok');
              }

              setTimeout (function () {ai_process_lists ();}, 10);

              if (ai_debug) console.log ("AI LISTS COOKIE tcf-v2: __tcfapi getTCData success", ai_tcData);
            } else
            if (tcData.eventStatus == 'cmpuishown') {
              ai_cmpuishown = true;

              if (ai_debug) console.log ("AI LISTS COOKIE __tcfapi cmpuishown");

              // ***
//              $('#ai-iab-tcf-status').text ('IAB TCF 2.0 CMP UI SHOWN');
//              $('#ai-iab-tcf-bar').addClass ('status-ok').removeClass ('status-error');
              if (iab_tcf_status != null) {
                iab_tcf_status.textContent = 'IAB TCF 2.0 CMP UI SHOWN';
              }
              if (iab_tcf_bar != null) {
                iab_tcf_bar.classList.remove ('status-error');
                iab_tcf_bar.classList.add ('status-ok');
              }

            } else {
                if (ai_debug) console.log ("AI LISTS COOKIE tcf-v2: __tcfapi getTCData, invalid status", tcData.eventStatus);
              }
          } else {
              if (ai_debug) console.log ("AI LISTS COOKIE tcf-v2: __tcfapi getTCData failed");

              // ***
//              $('#ai-iab-tcf-status').text ('IAB TCF 2.0 __tcfapi getTCData failed');
//              $('#ai-iab-tcf-bar').removeClass ('status-ok').addClass ('status-error');
              if (iab_tcf_status != null) {
                iab_tcf_status.textContent = 'IAB TCF 2.0 __tcfapi getTCData failed';
              }
              if (iab_tcf_bar != null) {
                iab_tcf_bar.classList.remove ('status-ok');
                iab_tcf_bar.classList.add ('status-error');
              }
            }
        });
      }
    }

    function check_and_call__tcfapi (show_error) {

      var ai_debug = typeof ai_debugging !== 'undefined'; // 4
//      var ai_debug = false;

      if (typeof __tcfapi == 'function') {
        ai_tcfapi_found = true;

        if (typeof ai_iab_tcf_callback_installed == 'undefined') {
          if (ai_debug) console.log ("AI LISTS COOKIE tcf-v2: callback for useractioncomplete not installed yet");

          ai_install_tcf_callback_useractioncomplete ();
        }

        if (typeof ai_tcData_requested == 'undefined') {
          ai_tcData_requested = true;

          call__tcfapi ();

          cookies_need_tcData = true;
        } else {
            if (ai_debug) console.log ("AI LISTS COOKIE tcf-v2: tcData already requested");
          }
      } else {
          if (show_error) {
            if (ai_debug) console.log ("AI LISTS COOKIE tcf-v2: __tcfapi function not found");

            if (typeof ai_tcfapi_found == 'undefined') {
              ai_tcfapi_found = false;
              setTimeout (function () {ai_process_lists ();}, 10);
            }

            // ***
//            $('#ai-iab-tcf-bar').addClass ('status-error').removeClass ('status-ok');
//            $('#ai-iab-tcf-status').text ('IAB TCF 2.0 MISSING: __tcfapi function not found');
            var iab_tcf_status = document.querySelector ('#ai-iab-tcf-status');
            if (iab_tcf_status != null) {
              iab_tcf_status.textContent = 'IAB TCF 2.0 MISSING: __tcfapi function not found';
            }
            var iab_tcf_bar = document.querySelector ('#ai-iab-tcf-bar');
            if (iab_tcf_bar != null) {
              iab_tcf_bar.classList.remove ('status-ok');
              iab_tcf_bar.classList.add ('status-error');
            }

          }
        }
    }

    if (ai_list_blocks == null) {
      // ***
//      ai_list_blocks = $("div.ai-list-data, meta.ai-list-data");
      ai_list_blocks = document.querySelectorAll ("div.ai-list-data, meta.ai-list-data");
    } else {
        // Temp fix for jQuery elements
        // ***
        if (window.jQuery && window.jQuery.fn && ai_list_blocks instanceof jQuery) {
          // Convert jQuery object to array
          ai_list_blocks = Array.prototype.slice.call (ai_list_blocks);
        }

        // ***
//        ai_list_blocks = ai_list_blocks.filter ('.ai-list-data');
        var filtered_elements = [];
        ai_list_blocks.forEach ((element, i) => {
          if (element.matches ('.ai-list-data')) {
            filtered_elements.push (element);
          } else {
              var list_data_elements = element.querySelectorAll ('.ai-list-data');
              if (list_data_elements.length) {
                list_data_elements.forEach ((list_element, i2) => {
                  filtered_elements.push (list_element);
                });
              }
            }
        });
        ai_list_blocks = filtered_elements;
      }

    var ai_debug = typeof ai_debugging !== 'undefined'; // 5
//    var ai_debug = false;

    if (!ai_list_blocks.length) return;

    if (ai_debug) console.log ("AI LISTS:", ai_list_blocks.length, 'blocks');

    // Mark lists as processed
//    ai_list_blocks.removeClass ('ai-list-data');
    ai_list_blocks.forEach ((element, i) => {
      element.classList.remove ('ai-list-data');
    });


    var url_parameters = getAllUrlParams (window.location.search);
    if (url_parameters ['referrer'] != null) {
      var referrer = url_parameters ['referrer'];
    } else {
        var referrer = document.referrer;
        if (referrer != '') referrer = getHostName (referrer);
      }

    var user_agent = window.navigator.userAgent;
    var user_agent_lc = user_agent.toLowerCase ();

    var language = navigator.language;
    var language_lc = language.toLowerCase ();

    if (typeof MobileDetect !== "undefined") {
      var md = new MobileDetect (user_agent);
    }

    // ***
//    ai_list_blocks.each (function () {
    ai_list_blocks.forEach ((el, i) => {

      // Reload cookies as pervious blocks might create some
      var cookies  = document.cookie.split (";");
      cookies.forEach (function (cookie, index) {
        cookies [index] = cookie.trim();
      });

//      var block_wrapping_div = $(this).closest ('div.AI_FUNCT_GET_BLOCK_CLASS_NAME');
      var block_wrapping_div = el.closest ('div.' + ai_block_class_def);

      // ***
//      if (ai_debug) console.log ('AI LISTS BLOCK', block_wrapping_div != null && block_wrapping_div.hasAttribute ("class") ? block_wrapping_div.attr ('class'));
      if (ai_debug) console.log ('AI LISTS BLOCK', block_wrapping_div != null && block_wrapping_div.hasAttribute ("class") ? block_wrapping_div.getAttribute ('class') : '');

      var enable_block = true;

      // ***
//      var referer_list = $(this).attr ("referer-list");
//      if (typeof referer_list != "undefined") {
      if (el.hasAttribute ("referer-list")) {
        var referer_list = el.getAttribute ("referer-list");

        var referer_list_array  = b64d (referer_list).split (",");
        // ***
//        var referers_list_type  = $(this).attr ("referer-list-type");
        var referers_list_type  = el.getAttribute ("referer-list-type");

        if (ai_debug) console.log ("AI LISTS referer:     ", referrer);
        if (ai_debug) console.log ("AI LISTS referer list:", b64d (referer_list), referers_list_type);

        var referrer_found = false;

        // ***
//        $.each (referer_list_array, function (index, list_referer) {
        referer_list_array.every ((list_referer, index) => {

          list_referer = list_referer.trim ();

          if (list_referer == '') return true;

          if (list_referer.charAt (0) == "*") {
            if (list_referer.charAt (list_referer.length - 1) == "*") {
              list_referer = list_referer.substr (1, list_referer.length - 2);
              if (referrer.indexOf (list_referer) != - 1) {
                referrer_found = true;
                return false;
              }
            } else {
                list_referer = list_referer.substr (1);
                if (referrer.substr (- list_referer.length) == list_referer) {
                  referrer_found = true;
                  return false;
                }
              }
          }
          else if (list_referer.charAt (list_referer.length - 1) == "*") {
            list_referer = list_referer.substr (0, list_referer.length - 1);
            if (referrer.indexOf (list_referer) == 0) {
              referrer_found = true;
              return false;
            }
          }
          else if (list_referer == '#') {
            if (referrer == "") {
              referrer_found = true;
              return false;
            }
          }
          else if (list_referer == referrer) {
            referrer_found = true;
            return false;
          }

          return true;
        });

        var list_passed = referrer_found;

        switch (referers_list_type) {
          case "B":
            if (list_passed) enable_block = false;
            break;
          case "W":
            if (!list_passed) enable_block = false;
            break;
        }

        if (ai_debug) console.log ("AI LISTS referrer found", referrer_found);
        if (ai_debug && !enable_block) console.log ("AI LISTS block enabled", enable_block);
        if (ai_debug && !enable_block) console.log ("");
      }

      if (enable_block) {
        // ***
//        var client_list = $(this).attr ("client-list");
//        if (typeof client_list != "undefined" && typeof md !== "undefined") {
        if (el.hasAttribute ("client-list") && typeof md !== "undefined") {
          var client_list = el.getAttribute ("client-list");

          var client_list_array  = b64d (client_list).split (",");
          // ***
//          var clients_list_type  = $(this).attr ("client-list-type");
          var clients_list_type  = el.getAttribute ("client-list-type");

          if (ai_debug) console.log ("AI LISTS client:     ", window.navigator.userAgent);
          if (ai_debug) console.log ("AI LISTS language:   ", navigator.language);
          if (ai_debug) console.log ("AI LISTS client list:", b64d (client_list), clients_list_type);

          list_passed = false;
          // ***
//          $.each (client_list_array, function (index, list_client_term) {
          client_list_array.every ((list_client_term, index) => {

            if (list_client_term.trim () == '') return true;

            var client_list_array_term = list_client_term.split ("&&");
            // ***
//            $.each (client_list_array_term, function (index, list_client) {
            client_list_array_term.every ((list_client, index) => {

              var result = true;
              var check_language = false;

              list_client = list_client.trim ();

              var list_client_org = list_client;

              while (list_client.substring (0, 2) == '!!') {
                result = !result;
                list_client = list_client.substring (2);
              }

              if (list_client.substring (0, 9) == 'language:') {
                check_language = true;
                list_client = list_client.substring (9).toLowerCase ();
              }

              if (ai_debug) console.log ("");
              if (ai_debug) console.log ("AI LISTS item check", list_client_org);

              var client_found = false;

              if (check_language) {
                if (list_client.charAt (0) == "*") {
                  if (list_client.charAt (list_client.length - 1) == "*") {
                    list_client = list_client.substr (1, list_client.length - 2).toLowerCase ();
                    if (language_lc.indexOf (list_client) != - 1) {
                      if (ai_debug) console.log ("AI LISTS FOUND: language:" + list_client);

                      client_found = true;
                    }
                  } else {
                      list_client = list_client.substr (1).toLowerCase ();
                      if (language_lc.substr (- list_client.length) == list_client) {
                        if (ai_debug) console.log ("AI LISTS FOUND: language:" + list_client);

                        client_found = true;
                      }
                    }
                }
                else if (list_client.charAt (list_client.length - 1) == "*") {
                  list_client = list_client.substr (0, list_client.length - 1).toLowerCase ();
                  if (language_lc.indexOf (list_client) == 0) {
                    if (ai_debug) console.log ("AI LISTS FOUND: language:" + list_client);

                    client_found = true;
                  }
                }
                else if (list_client == language_lc) {
                  if (ai_debug) console.log ("AI LISTS FOUND: language:" + list_client);

                  client_found = true;
                }
              } else {
                  if (list_client.charAt (0) == "*") {
                    if (list_client.charAt (list_client.length - 1) == "*") {
                      list_client = list_client.substr (1, list_client.length - 2).toLowerCase ();
                      if (user_agent_lc.indexOf (list_client) != - 1) {
                        if (ai_debug) console.log ("AI LISTS FOUND:", list_client);

                        client_found = true;
                      }
                    } else {
                        list_client = list_client.substr (1).toLowerCase ();
                        if (user_agent_lc.substr (- list_client.length) == list_client) {
                          if (ai_debug) console.log ("AI LISTS FOUND:", list_client);

                          client_found = true;
                        }
                      }
                  }
                  else if (list_client.charAt (list_client.length - 1) == "*") {
                    list_client = list_client.substr (0, list_client.length - 1).toLowerCase ();
                    if (user_agent_lc.indexOf (list_client) == 0) {
                      if (ai_debug) console.log ("AI LISTS FOUND:", list_client);

                      client_found = true;
                    }
                  }
                  else if (md.is (list_client)) {
                    if (ai_debug) console.log ("AI LISTS FOUND:", list_client);

                    client_found = true;
                  }
                }


              if (ai_debug) console.log ("AI LISTS CLIENT", list_client, 'found: ', client_found);

              if (client_found) {
                list_passed = result;
              } else list_passed = !result;

              if (!list_passed) {
                if (ai_debug) console.log ("");
                if (ai_debug) console.log ("AI LISTS term FAILED:", list_client_term);

                return false;  // End && check
              }

              if (ai_debug) console.log ("AI LISTS CLIENT PASSED", list_client);

              return true;
            }); // &&

            if (list_passed) {
              return false;  // End list check
            }

            return true;
          });

          switch (clients_list_type) {
            case "B":
              if (list_passed) enable_block = false;
              break;
            case "W":
              if (!list_passed) enable_block = false;
              break;
          }

          if (ai_debug) console.log ("");
          if (ai_debug) console.log ("AI LISTS list passed", list_passed);
          if (ai_debug) console.log ("AI LISTS block enabled", enable_block);
          if (ai_debug) console.log ("");
        }
      }

      var cookies_manual_loading = false;
      var cookies_no_ai_tcData_yet = false;
      var cookies_need_tcData = false;


      // Check for cookies and cookies in the url parameters list
      for (var list = 1; list <= 2; list ++) {

        if (enable_block) {
          switch (list) {
            case 1:
              // ***
//              var cookie_list = $(this).attr ("cookie-list");
              var cookie_list = el.getAttribute ("cookie-list");
              break
            case 2:
              // ***
//              var cookie_list = $(this).attr ("parameter-list");
              var cookie_list = el.getAttribute ("parameter-list");
              break
          }

          // ***
//          if (typeof cookie_list != "undefined") {
          if (cookie_list != null) {
            var cookie_list = b64d (cookie_list);

            switch (list) {
              case 1:
                // ***
//                var cookie_list_type  = $(this).attr ("cookie-list-type");
                var cookie_list_type  = el.getAttribute ("cookie-list-type");
                break
              case 2:
                // ***
//                var cookie_list_type  = $(this).attr ("parameter-list-type");
                var cookie_list_type  = el.getAttribute ("parameter-list-type");
                break
            }


            if (ai_debug) console.log ('');
            if (ai_debug) console.log ("AI LISTS found cookies:       ", cookies);
//            if (ai_debug) console.log ("AI LISTS parameter list:", cookie_list, cookie_list_type);

            if (ai_debug)
              switch (list) {
                case 1:
                  if (ai_debug) console.log ("AI LISTS cookie list:", cookie_list, cookie_list_type);
                  break
                case 2:
                  if (ai_debug) console.log ("AI LISTS parameter list:", cookie_list, cookie_list_type);
                  break
              }

            cookie_list = cookie_list.replace ('tcf-gdpr',        'tcf-v2[gdprApplies]=true');
            cookie_list = cookie_list.replace ('tcf-no-gdpr',     'tcf-v2[gdprApplies]=false');
            cookie_list = cookie_list.replace ('tcf-google',      'tcf-v2[vendor][consents][755]=true && tcf-v2[purpose][consents][1]=true');
            cookie_list = cookie_list.replace ('tcf-no-google',   '!!tcf-v2[vendor][consents][755]');
            cookie_list = cookie_list.replace ('tcf-media.net',   'tcf-v2[vendor][consents][142]=true && tcf-v2[purpose][consents][1]=true');
            cookie_list = cookie_list.replace ('tcf-no-media.net','!!tcf-v2[vendor][consents][142]');
            cookie_list = cookie_list.replace ('tcf-amazon',      'tcf-v2[vendor][consents][793]=true && tcf-v2[purpose][consents][1]=true');
            cookie_list = cookie_list.replace ('tcf-no-amazon',   '!!tcf-v2[vendor][consents][793]');
            cookie_list = cookie_list.replace ('tcf-ezoic',       'tcf-v2[vendor][consents][347]=true && tcf-v2[purpose][consents][1]=true');
            cookie_list = cookie_list.replace ('tcf-no-ezoic',    '!!tcf-v2[vendor][consents][347]');

            if (ai_debug) console.log ("AI LISTS cookie list:", cookie_list, cookie_list_type);

            var cookie_list_array = cookie_list.split (",");

            var cookie_array = new Array ();
            cookies.forEach (function (cookie) {
              var cookie_data = cookie.split ("=");

              try {
                  var cookie_object = JSON.parse (decodeURIComponent (cookie_data [1]));
              } catch (e) {
                  var cookie_object = decodeURIComponent (cookie_data [1]);
              }

              cookie_array [cookie_data [0]] = cookie_object;
            });


            if (ai_debug) console.log ("AI LISTS COOKIE ARRAY", cookie_array);

            var list_passed = false;
            // ***
//            var block_div = $(this);
            var block_div = el;
            // ***
//            $.each (cookie_list_array, function (index, list_cookie_term) {
            cookie_list_array.every ((list_cookie_term, index) => {

              var cookie_list_array_term = list_cookie_term.split ("&&");
              // ***
//              $.each (cookie_list_array_term, function (index, list_cookie) {
              cookie_list_array_term.every ((list_cookie, index) => {

                var result = true;

                list_cookie = list_cookie.trim ();

                var list_parameter_org = list_cookie;

                while (list_cookie.substring (0, 2) == '!!') {
                  result = !result;
                  list_cookie = list_cookie.substring (2);
                }

                if (ai_debug) console.log ("");
                if (ai_debug) console.log ("AI LISTS item check", list_parameter_org);

                var cookie_name   = list_cookie;
                var cookie_value  = '!@!';
                var ai_tcfapi     = cookie_name == 'tcf-v2' && cookie_value  == '!@!';

                // General check
                var structured_data     = list_cookie.indexOf ('[') != - 1;
                var euconsent_v2        = (list_cookie.indexOf ('tcf-v2') == 0 || list_cookie.indexOf ('euconsent-v2') == 0);
                var euconsent_v2_check  = euconsent_v2 && (structured_data || ai_tcfapi);

                if (list_cookie.indexOf ('=') != - 1) {
                  var list_parameter_data = list_cookie.split ("=");
                  cookie_name  = list_parameter_data [0];
                  cookie_value = list_parameter_data [1];
                  // Check again only cookie name (no value)
                  structured_data     = cookie_name.indexOf ('[') != - 1;
                  euconsent_v2        = (cookie_name.indexOf ('tcf-v2') == 0 || cookie_name.indexOf ('euconsent-v2') == 0);
                  euconsent_v2_check  = euconsent_v2 && (structured_data || ai_tcfapi);
                }

                if (euconsent_v2_check) {
                  // IAB Europe Transparency and Consent Framework (TCF v2)
                  if (ai_debug) console.log ("AI LISTS COOKIE tcf-v2");

                  // ***
//                  $('#ai-iab-tcf-bar').show ();
                  var iab_tcf_status = document.querySelector ('#ai-iab-tcf-status');
                  var iab_tcf_bar = document.querySelector ('#ai-iab-tcf-bar');
                  if (iab_tcf_bar != null) {
                    iab_tcf_bar.style.display = 'block';
                  }

                  if (ai_tcfapi && typeof ai_tcfapi_found == 'boolean') {
                    if (ai_debug) console.log ("");
                    if (ai_debug) console.log ("AI LISTS __tcfapi STATUS KNOWN");
                    if (ai_debug) console.log ("AI LISTS __tcfapi FOUND", ai_tcfapi_found);

                    if (ai_tcfapi_found) {
                      list_passed = result;
                    } else list_passed = !result;

                  } else


                  if (typeof ai_tcData == 'object') {
                    if (ai_debug) console.log ("AI LISTS COOKIE tcf-v2: ai_tcData set");

                    // ***
//                    $('#ai-iab-tcf-bar').addClass ('status-ok');
                    if (iab_tcf_bar != null) {
                      iab_tcf_bar.classList.add ('status-ok');
                    }

                    var indexes = cookie_name.replace (/]| /gi, '').split ('[');
                    // Remove cookie name (tcf-v2)
                    indexes.shift ();

                    if (ai_debug) console.log ("AI LISTS COOKIE tcf-v2: tcData", ai_tcData);

                    var structured_data_found = ai_structured_data_item (indexes, ai_tcData, cookie_value);

                    if (ai_debug) console.log ("AI LISTS COOKIE", cookie_value == '!@!' ? cookie_name : cookie_name + '=' + cookie_value, structured_data_found);

                    if (structured_data_found) {
                      list_passed = result;
                    } else list_passed = !result;

                  } else {
                      // Wait only when __tcfapi staus is unknown
                      if (typeof ai_tcfapi_found == 'undefined') {
                        // Mark this list as unprocessed - will be processed later when __tcfapi callback function is called
                        // ***
  //                      block_div.addClass ('ai-list-data');
                        block_div.classList.add ('ai-list-data');

                        cookies_no_ai_tcData_yet = true;

                        if (typeof __tcfapi == 'function') {
                          // Already available
                          check_and_call__tcfapi (false)
                        } else {
                            if (typeof ai_tcData_retrying == 'undefined') {
                              ai_tcData_retrying  = true;

                              if (ai_debug) console.log ("AI LISTS COOKIE tcf-v2: __tcfapi not found 1, waiting...");

                              setTimeout (function() {
                                if (ai_debug) console.log ("AI LISTS COOKIE tcf-v2: checking again for __tcfapi");

                                if (typeof __tcfapi == 'function') {
                                  check_and_call__tcfapi (false);
                                } else {
                                    if (ai_debug) console.log ("AI LISTS COOKIE tcf-v2: __tcfapi not found 2, waiting...");

                                    setTimeout (function() {
                                      if (typeof __tcfapi == 'function') {
                                        check_and_call__tcfapi (false);
                                      } else {
                                          if (ai_debug) console.log ("AI LISTS COOKIE tcf-v2: __tcfapi not found 3, waiting...");

                                          setTimeout (function() {
                                            check_and_call__tcfapi (true);
                                          }, 3000);
                                        }

                                    }, 1000);
                                  }
                              }, 600);
                            } else {
                                if (ai_debug) console.log ("AI LISTS COOKIE tcf-v2: __tcfapi still waiting...");
                              }
                        }
                      }
                    }
                } else

                if (structured_data) {
                  var structured_data_found = ai_structured_data (cookie_array, cookie_name, cookie_value);

                  if (ai_debug) console.log ("AI LISTS COOKIE", cookie_value == '!@!' ? cookie_name : cookie_name + '=' + cookie_value, 'found: ', structured_data_found);

                  if (structured_data_found) {
                    list_passed = result;
                  } else list_passed = !result;
                } else {
                    var cookie_found = false;
                    if (cookie_value == '!@!') {
                      // Check only cookie presence
                      cookies.every (function (cookie) {
                        var cookie_data = cookie.split ("=");

                        if (cookie_data [0] == list_cookie) {
                          cookie_found = true;
                          return false; // exit from cookies.every
                        }

                        return true; // Next loop iteration
                      });
                    } else {
                      // Check cookie with value
                        cookie_found = cookies.indexOf (list_cookie) != - 1;
                      }

                    if (ai_debug) console.log ("AI LISTS COOKIE", list_cookie, 'found: ', cookie_found);

                    if (cookie_found) {
                      list_passed = result;
                    } else list_passed = !result;
                  }

                if (!list_passed) {
                  if (ai_debug) console.log ("AI LISTS term FAILED", list_cookie_term);

                  return false;  // End && check
                }

                if (ai_debug) console.log ("AI LISTS COOKIE PASSED", list_cookie);

                return true;
              }); // &&

              if (list_passed) {
                return false;  // End list check
              }

              return true;
            });

            if (list_passed) {
              // List passed, no need to check ai_tcData again
              cookies_no_ai_tcData_yet = false;

              // List passed, mark it as processed (in case it was marked as unprocessed - __tcfapi not available)
              block_div.classList.remove ('ai-list-data');
            }

            switch (cookie_list_type) {
              case "B":
                if (list_passed) enable_block = false;
                break;
              case "W":
                if (!list_passed) enable_block = false;
                break;
            }

            if (ai_debug) console.log ("AI LISTS list passed", list_passed);
            if (ai_debug) console.log ("AI LISTS =================");
            if (ai_debug) console.log ("AI LISTS block enabled", enable_block);
            if (ai_debug) console.log ("");
          }
        }

      } // for list


      // ***
//      if ($(this).hasClass ('ai-list-manual')) {
      if (el.classList.contains ('ai-list-manual')) {

        if (!enable_block) {
          // Manual load AUTO
          cookies_manual_loading = true;
          // ***
//          block_div.addClass ('ai-list-data');
          block_div.classList.add ('ai-list-data');
        } else {
            // ***
//            block_div.removeClass ('ai-list-data');
//            block_div.removeClass ('ai-list-manual');
            block_div.classList.remove ('ai-list-data');
            block_div.classList.remove ('ai-list-manual');
          }
      }

      if (enable_block || !cookies_manual_loading && !cookies_no_ai_tcData_yet) {
        // ***
//        var debug_info = $(this).data ('debug-info');
//        if (typeof debug_info != 'undefined') {
        if (el.hasAttribute ('data-debug-info')) {
          var debug_info = el.dataset.debugInfo;

          // ***
//          var debug_info_element = $('.' + debug_info);
          var debug_info_element = document.querySelector ('.' + debug_info);

          // ***
//          if (debug_info_element.length != 0) {
          if (debug_info_element != null) {
            // ***
//            var debug_bar = debug_info_element.parent ();
            var debug_bar = debug_info_element.parentElement;

            // ***
//            if (debug_bar.hasClass ('ai-debug-info')) {
            if (debug_bar != null && debug_bar.classList.contains ('ai-debug-info')) {
              debug_bar.remove ();
            }
          }
        }
      }


      // Cookies or Url parameters need tcData
      if (!enable_block && cookies_need_tcData) {
        if (ai_debug) console.log ("AI LISTS NEED tcData, NO ACTION");
        return true; // Continue ai_list_blocks.each
      }

      // ***
//      var debug_bar = $(this).prevAll ('.ai-debug-bar.ai-debug-lists');
      var debug_bars = prevAll (el, '.ai-debug-bar.ai-debug-lists');

      var referrer_text = referrer == '' ? '#' : referrer;
      // ***
//      debug_bar.find ('.ai-debug-name.ai-list-info').text (referrer_text).attr ('title', user_agent + "\n" + language);
//      debug_bar.find ('.ai-debug-name.ai-list-status').text (enable_block ? ai_front.visible : ai_front.hidden);

      if (debug_bars.length != 0) {
        debug_bars.forEach ((debug_bar, i) => {
          var debug_bar_data = debug_bar.querySelector ('.ai-debug-name.ai-list-info');
          if (debug_bar_data != null) {
            debug_bar_data.textContent = referrer_text;
            debug_bar_data.title = user_agent + "\n" + language;
          }
          debug_bar_data = debug_bar.querySelector ('.ai-debug-name.ai-list-status');
          if (debug_bar_data != null) {
            debug_bar_data.textContent = enable_block ? ai_front.visible : ai_front.hidden;
          }
        });
      }

      var scheduling = false;
      if (enable_block) {
        // ***
//        var scheduling_start = $(this).attr ("scheduling-start");
//        var scheduling_end   = $(this).attr ("scheduling-end");
//        var scheduling_days  = $(this).attr ("scheduling-days");
//        if (typeof scheduling_start != "undefined" && typeof scheduling_end != "undefined" && typeof scheduling_days != "undefined") {
        if (el.hasAttribute ("scheduling-start") && el.hasAttribute ("scheduling-end") && el.hasAttribute ("scheduling-days")) {
          var scheduling_start = el.getAttribute ('scheduling-start');
          var scheduling_end   = el.getAttribute ('scheduling-end');
          var scheduling_days  = el.getAttribute ('scheduling-days');

          var scheduling = true;

          var scheduling_start_string = b64d (scheduling_start);
          var scheduling_end_string   = b64d (scheduling_end);

          // ***
//          var scheduling_fallback = parseInt ($(this).attr ("scheduling-fallback"));
          var scheduling_fallback = parseInt (el.getAttribute ("scheduling-fallback"));
          // ***
//          var gmt = parseInt ($(this).attr ("gmt"));
          var gmt = parseInt (el.getAttribute ("gmt"));

          if (!scheduling_start_string.includes ('-') && !scheduling_end_string.includes ('-')) {
            var scheduling_start_date = ai_get_time (scheduling_start_string);
            var scheduling_end_date   = ai_get_time (scheduling_end_string);
            scheduling_start_date ??= 0;
            scheduling_end_date   ??= 0;
          } else {
              var scheduling_start_date = ai_get_date (scheduling_start_string) + gmt;
              var scheduling_end_date   = ai_get_date (scheduling_end_string) + gmt;
              scheduling_start_date ??= 0;
              scheduling_end_date   ??= 0;
            }

          var scheduling_days_array = b64d (scheduling_days).split (',');
          // ***
//          var scheduling_type  = $(this).attr ("scheduling-type");
          var scheduling_type  = el.getAttribute ("scheduling-type");

          var current_time = new Date ().getTime () + gmt;
          var date = new Date (current_time);
          var current_day = date.getDay ();
          // Set 0 for Monday, 6 for Sunday
          if (current_day == 0) current_day = 6; else current_day --;

          if (!scheduling_start_string.includes ('-') && !scheduling_end_string.includes ('-')) {
            var current_time_date_only = new Date (date.getFullYear (), date.getMonth (), date.getDate ()).getTime () + gmt;
            current_time -= current_time_date_only;
            if (current_time < 0) {
              current_time += 24 * 3600 * 1000;
            }
          }

          scheduling_start_date_ok = current_time >= scheduling_start_date;
          scheduling_end_date_ok   = scheduling_end_date == 0 || current_time < scheduling_end_date;

          if (ai_debug) console.log ('');
          if (ai_debug) console.log ("AI SCHEDULING:", b64d (scheduling_start), ' ', b64d (scheduling_end), ' ', b64d (scheduling_days), ' ', scheduling_type == 'W' ? 'IN' : 'OUT');
          if (ai_debug) console.log ("AI SCHEDULING current time", current_time);
          if (ai_debug) console.log ("AI SCHEDULING start date", scheduling_start_date, scheduling_start_date_ok);
          if (ai_debug) console.log ("AI SCHEDULING end date  ", scheduling_end_date, scheduling_end_date_ok);
          if (ai_debug) console.log ("AI SCHEDULING days", scheduling_days_array, scheduling_days_array.includes (current_day.toString ()));

          var scheduling_ok = scheduling_start_date_ok && scheduling_end_date_ok && scheduling_days_array.includes (current_day.toString ());

          switch (scheduling_type) {
            case "B":
              scheduling_ok = !scheduling_ok;
              break;
          }

          if (!scheduling_ok) {
            enable_block = false;
          }

          var date_time_string = date.toISOString ().split ('.');
          var date_time = date_time_string [0].replace ('T', ' ');

          // ***
//          var debug_bar = $(this).prevAll ('.ai-debug-bar.ai-debug-scheduling');
          var debug_bars = prevAll (el, '.ai-debug-bar.ai-debug-scheduling');

//          debug_bar.find ('.ai-debug-name.ai-scheduling-info').text (date_time + ' ' + current_day +
//          ' current_time:' + Math.floor (current_time.toString () / 1000) + ' ' +
//          ' start_date:' + Math.floor (scheduling_start_date / 1000).toString () +
//          ' =' + (scheduling_start_date_ok).toString () +
//          ' end_date:' + Math.floor (scheduling_end_date / 1000).toString () +
//          ' =:' + (scheduling_end_date_ok).toString () +
//          ' days:' + scheduling_days_array.toString () +
//          ' =:' + scheduling_days_array.includes (current_day.toString ()).toString ());

//          debug_bar.find ('.ai-debug-name.ai-scheduling-status').text (enable_block ? ai_front.visible : ai_front.hidden);

          if (debug_bars.length != 0) {
            debug_bars.forEach ((debug_bar, i) => {
              var debug_bar_data = debug_bar.querySelector ('.ai-debug-name.ai-scheduling-info');
              if (debug_bar_data != null) {
                debug_bar_data.textContent = date_time + ' ' + current_day +
                  ' current_time: ' + Math.floor (current_time.toString () / 1000) + ' ' +
                  ' start_date:' + Math.floor (scheduling_start_date / 1000).toString () +
                  '=>' + (scheduling_start_date_ok).toString () +
                  ' end_date:' + Math.floor (scheduling_end_date / 1000).toString () +
                  '=>' + (scheduling_end_date_ok).toString () +
                  ' days:' + scheduling_days_array.toString () +
                  '=>' + scheduling_days_array.includes (current_day.toString ()).toString ();
              }
              debug_bar_data = debug_bar.querySelector ('.ai-debug-name.ai-scheduling-status');
              if (debug_bar_data != null) {
                debug_bar_data.textContent = enable_block ? ai_front.visible : ai_front.hidden;
              }

              if (!enable_block && scheduling_fallback != 0) {
                // ***
    //            debug_bar.removeClass ('ai-debug-scheduling').addClass ('ai-debug-fallback');
    //            debug_bar.find ('.ai-debug-name.ai-scheduling-status').text (ai_front.fallback + ' = ' + scheduling_fallback);

                debug_bar.classList.remove ('ai-debug-scheduling');
                debug_bar.classList.add ('ai-debug-fallback');
                var debug_bar_data = debug_bar.querySelector ('.ai-debug-name.ai-scheduling-status');
                if (debug_bar_data != null) {
                  debug_bar_data.textContent = ai_front.fallback + ' = ' + scheduling_fallback;
                }
              }
            });
          }

          if (ai_debug) console.log ("AI SCHEDULING:", date_time + ' ' + current_day);
          if (ai_debug) console.log ("AI SCHEDULING pass", scheduling_ok);
          if (ai_debug) console.log ("AI LISTS list pass", enable_block);

          if (!enable_block && scheduling_fallback != 0) {
            // ***
//            debug_bar.removeClass ('ai-debug-scheduling').addClass ('ai-debug-fallback');
//            debug_bar.find ('.ai-debug-name.ai-scheduling-status').text (ai_front.fallback + ' = ' + scheduling_fallback);
            // Above in the loop

            if (ai_debug) console.log ("AI SCHEDULING fallback block", scheduling_fallback);
          }
        }
      }

      // Cookie list not passed and has manual loading set to Auto
      if (cookies_manual_loading) {
        if (ai_debug) console.log ("AI LISTS MANUAL LOADING, NO ACTION");
        return true; // Continue ai_list_blocks.each
      }

      // Cookie list not passed and no ai_tcData yet
      if (!enable_block && cookies_no_ai_tcData_yet) {
        if (ai_debug) console.log ("AI LISTS IAB TCF, NO ai_tcData YET");
        return true; // Continue ai_list_blocks.each
      }


      //
//      $(this).css ({"visibility": "", "position": "", "width": "", "height": "", "z-index": ""});
      el.style.visibility = '';
      el.style.position = '';
      el.style.width = '';
      el.style.height = '';
      el.style.zIndex = '';

//      if (ai_iab_tcf_2_bar) {
//        var debug_bar = $(this).prevAll ('.ai-debug-bar.ai-debug-iab-tcf-2');
//        debug_bar.removeClass ('ai-debug-display-none');
//        debug_bar.find ('.ai-debug-name.ai-cookie-info').text (ai_iab_tcf_2_info);
//        debug_bar.find ('.ai-debug-name.ai-cookie-status').text (ai_iab_tcf_2_status);
//      }


      if (!enable_block) {
        if (scheduling && !scheduling_ok && scheduling_fallback != 0) {
          if (block_wrapping_div != null) {
            // ***
  //          block_wrapping_div.css ({"visibility": ""});
            block_wrapping_div.style.visibility = '';

            // ***
//            if (block_wrapping_div.hasClass ('ai-remove-position')) {
            if (block_wrapping_div.classList.contains ('ai-remove-position')) {
              block_wrapping_div.css ({"position": ""});
            }
          }

          // ***
//          var fallback_div = $(this).next ('.ai-fallback');
//          fallback_div.removeClass ('ai-fallback');  // Make it visible
          var fallback_divs = nextAll (el, '.ai-fallback');
          if (fallback_divs.length != 0) {
            fallback_divs.forEach ((fallback_div, i) => {
              fallback_div.classList.remove ('ai-fallback');  // Make it visible
            });
          }

          // ***
//          if (typeof $(this).data ('fallback-code') != 'undefined') {
          if (el.hasAttribute ('data-fallback-code')) {
            // ***
//            var block_code = b64d ($(this).data ('fallback-code'));
            var block_code = b64d (el.dataset.fallbackCode);
            // ***
//            $(this).append (block_code);

            var range = document.createRange ();
            var fragment_ok = true;
            try {
              var fragment = range.createContextualFragment (block_code);
            }
            catch (err) {
              var fragment_ok = false;
              if (ai_debug) console.log ('AI LIST', 'range.createContextualFragment ERROR:', err);
            }

            if (fragment_ok) {
              el.append (fragment);
            }

            // ***
//            if (ai_debug) console.log ('AI INSERT CODE', block_wrapping_div.attr ('class'));
            if (ai_debug) console.log ('AI INSERT CODE', block_wrapping_div != null && block_wrapping_div.hasAttribute ("class") ? block_wrapping_div.getAttribute ('class') : '');
            if (ai_debug) console.log ('');

            // ***
//            ai_process_element_lists (this);
            ai_process_element_lists (el);
          }  else {
                // ***
//               $(this).hide (); // .ai-list-data
               el.style.display = 'none'; // .ai-list-data

               // ***
//               if (!block_wrapping_div.find ('.ai-debug-block').length && block_wrapping_div [0].hasAttribute ('style') && block_wrapping_div.attr ('style').indexOf ('height:') == - 1) {
               if (block_wrapping_div != null && block_wrapping_div.querySelector ('.ai-debug-block') == null && block_wrapping_div.hasAttribute ('style') && block_wrapping_div.getAttribute ('style').indexOf ('height:') == - 1) {
                  // ***
//                 block_wrapping_div.hide ();
                 block_wrapping_div.style.display = 'none';
               }
             }

          // ***
//          var tracking_data = block_wrapping_div.attr ('data-ai');
//          if (typeof tracking_data !== typeof undefined && tracking_data !== false) {
          if (block_wrapping_div != null && block_wrapping_div.hasAttribute ('data-ai')) {
            var tracking_data = block_wrapping_div.getAttribute ('data-ai');

            // ***
//            var fallback_tracking_data = $(this).attr ('fallback-tracking');
//            if (typeof fallback_tracking_data !== typeof undefined && fallback_tracking_data !== false) {
            if (el.hasAttribute ('fallback-tracking')) {
              var fallback_tracking_data = el.getAttribute ('fallback-tracking');
              // ***
//              block_wrapping_div.attr ('data-ai-' + $(this).attr ('fallback_level'), fallback_tracking_data);
              block_wrapping_div.setAttribute ('data-ai-' + el.getAttribute ('fallback_level'), fallback_tracking_data);

              if (ai_debug) console.log ("AI SCHEDULING tracking updated to fallback block", b64d (fallback_tracking_data));
            }
          }
        } else {
//            $(this).hide (); // .ai-list-data
            el.style.display = 'none';  // .ai-list-data

//            if (block_wrapping_div.length) {
            if (block_wrapping_div != null) {
              // ***
//              block_wrapping_div.removeAttr ('data-ai').removeClass ('ai-track');
              block_wrapping_div.removeAttribute ('data-ai');
              block_wrapping_div.classList.remove ('ai-track');

//              if (block_wrapping_div.find ('.ai-debug-block').length) {
              if (block_wrapping_div.querySelector (".ai-debug-block") != null) {
                // ***
//                block_wrapping_div.css ({"visibility": ""}).removeClass ('ai-close');
                block_wrapping_div.style.visibility = '';
                block_wrapping_div.classList.remove ('ai-close');

                // ***
//                if (block_wrapping_div.hasClass ('ai-remove-position')) {
                if (block_wrapping_div.classList.contains ('ai-remove-position')) {
                  // ***
//                  block_wrapping_div.css ({"position": ""});
                  block_wrapping_div.style.position = '';
                }
              } else
              // ***
//              if (block_wrapping_div [0].hasAttribute ('style') && block_wrapping_div.attr ('style').indexOf ('height:') == - 1) {
              if (block_wrapping_div.hasAttribute ('style') && block_wrapping_div.getAttribute ('style').indexOf ('height:') == - 1) {
                // ***
//                block_wrapping_div.hide ();
                block_wrapping_div.style.display = 'none';
              }
            }
          }
      } else {
          // ***
//          block_wrapping_div.css ({"visibility": ""});
          if (block_wrapping_div != null) {
            block_wrapping_div.style.visibility = '';

            // ***
  //          if (block_wrapping_div.hasClass ('ai-remove-position')) {
            if (block_wrapping_div.classList.contains ('ai-remove-position')) {
              // ***
  //            block_wrapping_div.css ({"position": ""});
              block_wrapping_div.style.position = '';
            }
          }

          // ***
//          if (typeof $(this).data ('code') != 'undefined') {
          if (el.hasAttribute ('data-code')) {

            // ***
//            var block_code = b64d ($(this).data ('code'));
            var block_code = b64d (el.dataset.code);

            var range = document.createRange ();
            var fragment_ok = true;
            try {
              var fragment = range.createContextualFragment (block_code);
            }
            catch (err) {
              var fragment_ok = false;
              if (ai_debug) console.log ('AI LISTS', 'range.createContextualFragment ERROR:', err);
            }

//            if ($(this).closest ('head').length != 0) {
//              $(this).after (block_code);
//              if (!ai_debug) $(this).remove ();
//            } else $(this).append (block_code);

            if (fragment_ok) {
              if (el.closest ('head') != null) {
                el.parentNode.insertBefore (fragment, el.nextSibling);
                if (!ai_debug) el.remove ();
              } else el.append (fragment);
            }

            // ***
//            if (ai_debug) console.log ('AI INSERT CODE', block_wrapping_div.attr ('class'));
            if (ai_debug) console.log ('AI INSERT CODE', block_wrapping_div != null && block_wrapping_div.hasAttribute ("class") ? block_wrapping_div.getAttribute ('class') : '');

            if (ai_debug) console.log ('');

            // ***
//            ai_process_element_lists (this);
            ai_process_element_lists (el);
          }
        }

      if (!ai_debug) {
        // ***
//        $(this).attr ('data-code', '');
//        $(this).attr ('data-fallback-code', '');
        el.setAttribute ('data-code', '');
        el.setAttribute ('data-fallback-code', '');
      }

      // ***
//      block_wrapping_div.removeClass ('ai-list-block');
      if (block_wrapping_div != null) {
        block_wrapping_div.classList.remove ('ai-list-block');
      }
    });
  }

  function get_cookie (name) {
    // Does not work in older browsers (iOS)
//    return document.cookie.split (';').some (c => {
//      return c.trim().startsWith (name + '=');
//    });

    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
  }

  function delete_cookie (name, path, domain) {
    if (get_cookie (name)) {
      document.cookie = name + "=" +
        ((path) ? ";path=" + path : "") +
        ((domain) ? ";domain=" + domain : "") +
        ";expires=Thu, 01 Jan 1970 00:00:01 GMT";
    }
  }

  function ai_delete_cookie (name) {
    if (get_cookie (name)) {
      delete_cookie (name, '/', window.location.hostname);
      document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }
  }

function ai_ready (fn) {
  if (document.readyState === 'complete' || (document.readyState !== 'loading' && !document.documentElement.doScroll)) {
    fn ();
  } else {
     document.addEventListener ('DOMContentLoaded', fn);
  }
}


//  $(document).ready(function($) {
function ai_configure_tcf_events () {

    var ai_debug = typeof ai_debugging !== 'undefined'; // 6
//    var ai_debug = false;

    setTimeout (function() {
      ai_process_lists ();

      setTimeout (function() {
        ai_install_tcf_callback_useractioncomplete ();

        if (typeof ai_load_blocks == 'function') {
          // https://adinserter.pro/faq/gdpr-compliance-cookies-consent#manual-loading
          // ***
//          jQuery(document).on ("cmplzEnableScripts", ai_cmplzEnableScripts);
          document.addEventListener ('cmplzEnableScripts', ai_cmplzEnableScripts);

          // Complianz Privacy Suite
          // ***
//          jQuery(document).on ("cmplz_event_marketing", ai_cmplzEnableScripts);
          document.addEventListener ('cmplz_event_marketing', ai_cmplzEnableScripts);

          function ai_cmplzEnableScripts (consentData) {
            if (ai_debug) console.log ("AI LISTS ai_cmplzEnableScripts", consentData);

            if (consentData.type == 'cmplzEnableScripts' || consentData.consentLevel === 'all'){
              if (ai_debug) console.log ("AI LISTS ai_load_blocks ()");

              ai_load_blocks ();
            }
          }
        }
      }, 50);

      // ***
//      jQuery(".ai-debug-page-type").dblclick (function () {
//        jQuery('#ai-iab-tcf-status').text ('CONSENT COOKIES');
//        jQuery("#ai-iab-tcf-bar").show ();
//      });

      var debug_bar = document.querySelector ('.ai-debug-page-type');
      if (debug_bar != null)
        debug_bar.addEventListener ('dblclick', (e) => {
          var iab_tcf_status = document.querySelector ('#ai-iab-tcf-status');
          if (iab_tcf_status != null) {
            iab_tcf_status.textContent = 'CONSENT COOKIES';
          }
          var iab_tcf_bar = document.querySelector ('#ai-iab-tcf-bar');
          if (iab_tcf_bar != null) {
            iab_tcf_bar.style.display = 'block';
          }
        });

      // ***
//      jQuery("#ai-iab-tcf-bar").click (function () {
      debug_bar = document.querySelector ('#ai-iab-tcf-bar');
      if (debug_bar != null)
        debug_bar.addEventListener ('click', (e) => {

          ai_delete_cookie ('euconsent-v2');

          // Clickio GDPR Cookie Consent
          ai_delete_cookie ('__lxG__consent__v2');
          ai_delete_cookie ('__lxG__consent__v2_daisybit');
          ai_delete_cookie ('__lxG__consent__v2_gdaisybit');

          // Cookie Law Info
          ai_delete_cookie ('CookieLawInfoConsent');
          ai_delete_cookie ('cookielawinfo-checkbox-advertisement');
          ai_delete_cookie ('cookielawinfo-checkbox-analytics');
          ai_delete_cookie ('cookielawinfo-checkbox-necessary');

          // Complianz GDPR/CCPA
          ai_delete_cookie ('complianz_policy_id');
          ai_delete_cookie ('complianz_consent_status');
          ai_delete_cookie ('cmplz_marketing');
          ai_delete_cookie ('cmplz_consent_status');
          ai_delete_cookie ('cmplz_preferences');
          ai_delete_cookie ('cmplz_statistics-anonymous');
          ai_delete_cookie ('cmplz_choice');

          // Complianz Privacy Suite (GDPR/CCPA) premium
          ai_delete_cookie ('cmplz_banner-status');
          ai_delete_cookie ('cmplz_functional');
          ai_delete_cookie ('cmplz_policy_id');
          ai_delete_cookie ('cmplz_statistics');

          // GDPR Cookie Compliance (CCPA ready)
          ai_delete_cookie ('moove_gdpr_popup');

          // Real Cookie Banner PRO
          ai_delete_cookie ('real_cookie_banner-blog:1-tcf');
          ai_delete_cookie ('real_cookie_banner-blog:1');

          if (ai_debug) console.log ("AI LISTS clear consent cookies", window.location.hostname);

          // ***
//          jQuery('#ai-iab-tcf-status').text ('CONSENT COOKIES DELETED');
          var iab_tcf_status = document.querySelector ('#ai-iab-tcf-status');
          if (iab_tcf_status != null) {
            iab_tcf_status.textContent = 'CONSENT COOKIES DELETED';
          }
        });

    }, 5);
// ***
//  });
  }
// ***
//});


ai_ready (ai_configure_tcf_events);


function ai_process_element_lists (element) {
  setTimeout (function() {
    if (typeof ai_process_rotations_in_element == 'function') {
      ai_process_rotations_in_element (element);
    }

    if (typeof ai_process_lists == 'function') {
//      ai_process_lists (jQuery (".ai-list-data", element));
      ai_process_lists ();
    }

    if (typeof ai_process_ip_addresses == 'function') {
//      ai_process_ip_addresses (jQuery (".ai-ip-data", element));
      ai_process_ip_addresses ();
    }

    if (typeof ai_process_filter_hooks == 'function') {
//      ai_process_filter_hooks (jQuery (".ai-filter-check", element));
      ai_process_filter_hooks ();
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

function getAllUrlParams (url) {

  // get query string from url (optional) or window
  var queryString = url ? url.split('?')[1] : window.location.search.slice(1);

  // we'll store the parameters here
  var obj = {};

  // if query string exists
  if (queryString) {

    // stuff after # is not part of query string, so get rid of it
    queryString = queryString.split('#')[0];

    // split our query string into its component parts
    var arr = queryString.split('&');

    for (var i=0; i<arr.length; i++) {
      // separate the keys and the values
      var a = arr[i].split('=');

      // in case params look like: list[]=thing1&list[]=thing2
      var paramNum = undefined;
      var paramName = a[0].replace(/\[\d*\]/, function(v) {
        paramNum = v.slice(1,-1);
        return '';
      });

      // set parameter value (use 'true' if empty)
//      var paramValue = typeof(a[1])==='undefined' ? true : a[1];
      var paramValue = typeof(a[1])==='undefined' ? '' : a[1];

      // (optional) keep case consistent
      paramName = paramName.toLowerCase();
      paramValue = paramValue.toLowerCase();

      // if parameter name already exists
      if (obj[paramName]) {
        // convert value to array (if still string)
        if (typeof obj[paramName] === 'string') {
          obj[paramName] = [obj[paramName]];
        }
        // if no array index number specified...
        if (typeof paramNum === 'undefined') {
          // put the value on the end of the array
          obj[paramName].push(paramValue);
        }
        // if array index number specified...
        else {
          // put the value at that index number
          obj[paramName][paramNum] = paramValue;
        }
      }
      // if param name doesn't exist yet, set it
      else {
        obj[paramName] = paramValue;
      }
    }
  }

  return obj;
}

}
