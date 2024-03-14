if (typeof ai_rotation_triggers != 'undefined') {

// ***
//jQuery (function ($) {


  ai_process_rotation = function (rotation_block) {
    var ai_debug = typeof ai_debugging !== 'undefined'; // 1
//    var ai_debug = false;

    var multiple_elements = typeof rotation_block.length == 'number';

    // Temp fix for jQuery elements
    // ***
    if (window.jQuery && window.jQuery.fn && rotation_block instanceof jQuery) {
      if (multiple_elements) {
        // Convert jQuery object to array
        rotation_block = Array.prototype.slice.call (rotation_block);
      } else rotation_block = rotation_block [0];
    }


//    if (ai_debug) console.log ('#', rotation_block.classList.contains ('ai-unprocessed'));

    // ***
//    if (!$(rotation_block).hasClass ('ai-unprocessed') && !$(rotation_block).hasClass ('ai-timer')) return;
//    $(rotation_block).removeClass ('ai-unprocessed').removeClass ('ai-timer');

    if (multiple_elements) {
      var class_found = false;
      rotation_block.forEach ((el, i) => {
        if (el.classList.contains ('ai-unprocessed') || el.classList.contains ('ai-timer')) {
          class_found = true;
        }
      });
      if (!class_found) return;

      rotation_block.forEach ((el, index) => {
        el.classList.remove ('ai-unprocessed');
        el.classList.remove ('ai-timer');
      });
    } else {
        if (!rotation_block.classList.contains ('ai-unprocessed') && !rotation_block.classList.contains ('ai-timer')) return;
        rotation_block.classList.remove ('ai-unprocessed');
        rotation_block.classList.remove ('ai-timer');
      }


    if (ai_debug) console.log ('');

    var ai_rotation_triggers_found = false;
    // ***
//    if (typeof $(rotation_block).data ('info') != 'undefined') {
    if (multiple_elements) {
      var info_found = rotation_block [0].hasAttribute ('data-info');
    } else {
        var info_found = rotation_block.hasAttribute ('data-info');
      }

    if (info_found) {
      // ***
//      var block_info = JSON.parse (atob ($(rotation_block).data ('info')));
      if (multiple_elements) {
        var block_info = JSON.parse (atob (rotation_block [0].dataset.info));
      } else {
          var block_info = JSON.parse (atob (rotation_block.dataset.info));
        }

      var rotation_id = block_info [0];
      var rotation_selector = "div.ai-rotate.ai-" + rotation_id;

      if (ai_rotation_triggers.includes (rotation_selector)) {
        ai_rotation_triggers.splice (ai_rotation_triggers.indexOf (rotation_selector), 1);
        ai_rotation_triggers_found = true;

        if (ai_debug) console.log ('AI TIMED ROTATION TRIGGERS', ai_rotation_triggers);
      }
    }

//    if (typeof rotation_block.length == 'number') {
    if (multiple_elements) {
      if (ai_debug) console.log ('AI ROTATE process rotation:', rotation_block.length, 'rotation blocks');
      for (var index = 0; index < rotation_block.length; index ++) {
        if (ai_debug) console.log ('AI ROTATE process rotation block index:', index);

        if (ai_debug) console.log ('AI ROTATE process rotation block:', rotation_block [index]);

        if (index == 0) ai_process_single_rotation (rotation_block [index], true); else ai_process_single_rotation (rotation_block [index], false);
      }
    } else {
        if (ai_debug) console.log ('AI ROTATE process rotation: 1 rotation block');

        ai_process_single_rotation (rotation_block, !ai_rotation_triggers_found);
      }
  }

  ai_process_single_rotation = function (rotation_block, trigger_rotation) {
    var ai_debug = typeof ai_debugging !== 'undefined'; // 2
//    var ai_debug = false;

    // ***
//    var rotate_options = $(rotation_block).children (".ai-rotate-option");
    var rotate_options = [];

    Array.from (rotation_block.children).forEach ((element, i) => {
      if (element.matches ('.ai-rotate-option')) {
        rotate_options.push (element);
      }
    });

    if (rotate_options.length == 0) return;

    if (ai_debug) {
      console.log ('AI ROTATE process single rotation, trigger rotation', trigger_rotation);

      var block_wrapping_div = rotation_block.closest ('div.' + ai_block_class_def);
      if (block_wrapping_div != null) {
        console.log ('AI ROTATE block', (block_wrapping_div.hasAttribute ("class") ? block_wrapping_div.getAttribute ('class') : ''));
      }

      // ***
//      console.log ('AI ROTATE', 'block', $(rotation_block).attr ('class') + ',', rotate_options.length, 'options');
      console.log ('AI ROTATE wrapper', (rotation_block.hasAttribute ("class") ? rotation_block.getAttribute ('class') : '') + ',', rotate_options.length, 'options');
    }

    // ***
//    rotate_options.hide ();
    rotate_options.forEach ((element, i) => {
      element.style.display = 'none';
    });

//    rotate_options.css ({"visibility": "hidden"});

//    rotate_options.animate ({
//        opacity: 0,
//      }, 500, function() {
//    });

    // **
//    if (typeof $(rotation_block).data ('next') == 'undefined') {
//      if (typeof $(rotate_options [0]).data ('group') != 'undefined') {
    if (!rotation_block.hasAttribute ('data-next')) {
      if (rotate_options [0].hasAttribute ('data-group')) {
        var random_index = - 1;
        // ***
//        var all_ai_groups = $('span[data-ai-groups]');
        var all_ai_groups = document.querySelectorAll ('span[data-ai-groups]');
        var ai_groups = [];

        // ***
//        all_ai_groups.each (function (index) {
        all_ai_groups.forEach ((el, index) => {
          // ***
//          var visible = !!($(this)[0].offsetWidth || $(this)[0].offsetHeight || $(this)[0].getClientRects().length);
          var visible = !!(el.offsetWidth || el.offsetHeight || el.getClientRects ().length);

          if (visible) {
            // ***
//            ai_groups.push (this);
            ai_groups.push (el);
          }
        });

        if (ai_debug) console.log ('AI ROTATE GROUPS:', ai_groups.length, 'group markers found');

        if (ai_groups.length >= 1) {
//          var groups = JSON.parse (b64d ($(ai_groups).first ().data ('ai-groups')));
          timed_groups = [];
          groups = [];
          ai_groups.forEach (function (group_data, index) {
            // ***
//            active_groups = JSON.parse (b64d ($(group_data).data ('ai-groups')));
            active_groups = JSON.parse (b64d (group_data.dataset.aiGroups));

            var timed_group = false;
            var rotate_div = group_data.closest ('.ai-rotate');
            if (rotate_div != null && rotate_div.classList.contains ('ai-timed-rotation')) {
              timed_group = true;
            }

            active_groups.forEach (function (active_group, index2) {
              groups.push (active_group);
              if (timed_group) {
                timed_groups.push (active_group);
              }
            });
          });

          if (ai_debug) console.log ('AI ROTATE ACTIVE GROUPS:', groups);
          if (ai_debug && timed_groups.length) console.log ('AI ROTATE TIMED GROUPS:', timed_groups);

          groups.forEach (function (group, index2) {

            if (random_index == - 1)
//              rotate_options.each (function (index) {
              rotate_options.forEach ((el, index) => {
                // ***
//                var option_group = b64d ($(this).data ('group'));
                var option_group = b64d (el.dataset.group);
                option_group_items = option_group.split (",");

                option_group_items.forEach (function (option_group_item, index3) {
                  if (random_index == - 1) {
                    if (option_group_item.trim () == group) {
                      random_index = index;

                      // Mark it as timed rotation - only the first impression of active option will be tracked
                      // Solution - track timed group activations instead
                      if (timed_groups.includes (option_group)) {
                        rotation_block.classList.add ('ai-timed-rotation');
                      }
                    }
                  }
                });
              });
          });
        }
      } else {
        // ***
//          var thresholds_data = $(rotation_block).data ('shares');
//          if (typeof thresholds_data === 'string') {
          if (rotation_block.hasAttribute ('data-shares')) {
            var thresholds_data = rotation_block.dataset.shares;
            var thresholds = JSON.parse (atob (thresholds_data));
            var random_threshold = Math.round (Math.random () * 100);
            for (var index = 0; index < thresholds.length; index ++) {
              var random_index = index;
              if (thresholds [index] < 0) continue;
              if (random_threshold <= thresholds [index]) break;
            }
          } else {
              // ***
//              var unique = $(rotation_block).hasClass ('ai-unique');
              var unique = rotation_block.classList.contains ('ai-unique');
              var d = new Date();

              if (unique) {
                 if (typeof ai_rotation_seed != 'number') {
                   ai_rotation_seed = (Math.floor (Math.random () * 1000) + d.getMilliseconds()) % rotate_options.length;
                 }

                 // Calculate actual seed for the block - it may have fewer options than the first one which sets ai_rotation_seed
                 var ai_rotation_seed_block = ai_rotation_seed;
                 if (ai_rotation_seed_block > rotate_options.length) {
                   ai_rotation_seed_block = ai_rotation_seed_block % rotate_options.length;
                 }

                 // ***
//                 var block_counter = $(rotation_block).data ('counter');
                 var block_counter = parseInt (rotation_block.dataset.counter);

                 if (ai_debug) console.log ('AI ROTATE SEED:', ai_rotation_seed_block, ' COUNTER:', block_counter);

                 if (block_counter <= rotate_options.length) {
//                  var random_index = parseInt (ai_rotation_seed_block + block_counter);
                  var random_index = parseInt (ai_rotation_seed_block + block_counter - 1);
                  if (random_index >= rotate_options.length) random_index -= rotate_options.length;
                 } else random_index = rotate_options.length // forced no option selected
              } else {
                  var random_index = Math.floor (Math.random () * rotate_options.length);
                  var n = d.getMilliseconds();
                  if (n % 2) random_index = rotate_options.length - random_index - 1;
                }
            }
        }
    } else {
        // ***
//        var random_index = parseInt ($(rotation_block).attr ('data-next'));
        var random_index = parseInt (rotation_block.getAttribute ('data-next'));

        if (ai_debug) console.log ('AI TIMED ROTATION next index:', random_index);

        // ***
//        var option = $(rotate_options [random_index]);
        var option = rotate_options [random_index];

        // ***
//        if (typeof option.data ('code') != 'undefined') {
        if (option.hasAttribute ('data-code')) {
          // ***
//          option = $(b64d (option.data ('code')));
          var range = document.createRange ();
          var fragment_ok = true;
          try {
            var fragment = range.createContextualFragment (b64d (option.dataset.code));
          }
          catch (err) {
            var fragment_ok = false;
            if (ai_debug) console.log ('AI ROTATE', 'range.createContextualFragment ERROR:', err);
          }

          // if !fragment_ok option remains div with encoded option code
          if (fragment_ok) {
            option = fragment;
          }
        }

        // ***
//        var group_markers = option.find ('span[data-ai-groups]').addBack ('span[data-ai-groups]');
        var group_markers = option.querySelectorAll ('span[data-ai-groups]');

        if (group_markers.length != 0) {
          if (ai_debug) {
            // ***
//            var next_groups = JSON.parse (b64d (group_markers.first ().data ('ai-groups')));
            var next_groups = JSON.parse (b64d (group_markers [0].dataset.aiGroups));
            console.log ('AI TIMED ROTATION next option sets groups', next_groups);
          }

          // ***
//          var group_rotations = $('.ai-rotation-groups');
          var group_rotations = document.querySelectorAll ('.ai-rotation-groups');
          if (group_rotations.length != 0) {
            setTimeout (function() {ai_process_group_rotations ();}, 5);
          }
        }
      }

    // ***
//    if ($(rotation_block).hasClass ('ai-rotation-scheduling')) {
    if (rotation_block.classList.contains ('ai-rotation-scheduling')) {
      random_index = - 1;
//      var gmt = $(rotation_block).data ('gmt');

//      if (ai_debug) console.log ('AI SCHEDULED ROTATION, GMT:', gmt / 1000);

      for (var option_index = 0; option_index < rotate_options.length; option_index ++) {
        // ***
//        var option = $(rotate_options [option_index]);
        var option = rotate_options [option_index];
//        var option_data = option.data ('scheduling');
//        if (typeof option_data != 'undefined') {
        if (option.hasAttribute ('data-scheduling')) {
          var option_data = option.dataset.scheduling;
          var scheduling_data = b64d (option_data);

          var result = true;
          if (scheduling_data.indexOf ('^') == 0) {
            result = false;
            scheduling_data = scheduling_data.substring (1);
          }

          var scheduling_data_array = scheduling_data.split ('=');

          if (scheduling_data.indexOf ('%') != -1) {
            var scheduling_data_time = scheduling_data_array [0].split ('%');
          } else var scheduling_data_time = [scheduling_data_array [0]];

          var time_unit = scheduling_data_time [0].trim ().toLowerCase ();

          var time_division = typeof scheduling_data_time [1] != 'undefined' ? scheduling_data_time [1].trim () : 0;
          var scheduling_time_option = scheduling_data_array [1].replace (' ', '');

          if (ai_debug) console.log ('');
          if (ai_debug) console.log ('AI SCHEDULED ROTATION OPTION', option_index + (!result ? ' INVERTED' : '') + ':', time_unit + (time_division != 0 ? '%' + time_division : '') + '=' + scheduling_time_option);

          var current_time = new Date ().getTime ();
          var date = new Date (current_time);

          var time_value = 0;
          switch (time_unit) {
            case 's':
              time_value = date.getSeconds ();
              break;
            case 'i':
              time_value = date.getMinutes ();
              break;
            case 'h':
              time_value = date.getHours ();
              break;
            case 'd':
              time_value = date.getDate ();
              break;
            case 'm':
              time_value = date.getMonth ();
              break;
            case 'y':
              time_value = date.getFullYear ();
              break;
            case 'w':
              time_value = date.getDay ();
              if (time_value == 0) time_value = 6; else time_value = time_value - 1;
          }

          var time_modulo = time_division != 0 ? time_value % time_division : time_value;

          if (ai_debug) {
            if (time_division != 0) {
              console.log ('AI SCHEDULED ROTATION TIME VALUE:', time_value, '%', time_division, '=', time_modulo);
            } else console.log ('AI SCHEDULED ROTATION TIME VALUE:', time_value);
          }

          var scheduling_time_options = scheduling_time_option.split (',');

          var option_selected = !result;

          for (var time_option_index = 0; time_option_index < scheduling_time_options.length; time_option_index ++) {
            var time_option = scheduling_time_options [time_option_index];

            if (ai_debug) console.log ('AI SCHEDULED ROTATION TIME ITEM', time_option);

            if (time_option.indexOf ('-') != - 1) {
              var time_limits = time_option.split ('-');

              if (ai_debug) console.log ('AI SCHEDULED ROTATION TIME ITEM LIMITS', time_limits [0], '-', time_limits [1]);

              if (time_modulo >= time_limits [0] && time_modulo <= time_limits [1]) {
                option_selected = result;
                break
              }
            } else
            if (time_modulo == time_option) {
              option_selected = result;
              break
            }
          }

          if (option_selected) {
            random_index = option_index;

            if (ai_debug) console.log ('AI SCHEDULED ROTATION OPTION', random_index , 'SELECTED');

            break;
          }
        }
      }
    }

    if (random_index < 0 || random_index >= rotate_options.length) {
      if (ai_debug) console.log ('AI ROTATE no option selected');
      return;
    }

    // ***
//    var option = $(rotate_options [random_index]);
    var option = rotate_options [random_index];
    var option_time_text = '';


    var timed_rotation = rotation_block.classList.contains ('ai-timed-rotation'); // Set when the option iactivated by a group and group activation is timed
    rotate_options.forEach ((element, i) => {                                     // Normal timed options
      if (element.hasAttribute ('data-time')) timed_rotation = true;
    });


    // ***
//    if (typeof option.data ('time') != 'undefined') {
    if (option.hasAttribute ('data-time')) {
      // ***
//      var rotation_time = atob (option.data ('time'));
      var rotation_time = atob (option.dataset.time);

      if (ai_debug) {
        // ***
//        var option_index = option.data ('index');
//        var option_name = b64d (option.data ('name'));
        var option_index = parseInt (option.dataset.index);
        var option_name = b64d (option.dataset.name);
        console.log ('AI TIMED ROTATION index:', random_index + ' ['+ option_index + '],', 'name:', '"'+option_name+'",', 'time:', rotation_time);
      }

      if (rotation_time == 0 && rotate_options.length > 1) {
        var next_random_index = random_index;
        do {
          next_random_index++;
          if (next_random_index >= rotate_options.length) next_random_index = 0;

          // ***
//          var next_option = $(rotate_options [next_random_index]);
          var next_option = rotate_options [next_random_index];
          // ***
//          if (typeof next_option.data ('time') == 'undefined') {
          if (!next_option.hasAttribute ('data-time')) {
            random_index = next_random_index;
            // ***
//            option = $(rotate_options [random_index]);
            option = rotate_options [random_index];
            rotation_time = 0;

            if (ai_debug) console.log ('AI TIMED ROTATION next option has no time: ', next_random_index);

            break;
          }
          // ***
//          var next_rotation_time = atob (next_option.data ('time'));
          var next_rotation_time = atob (next_option.dataset.time);

          if (ai_debug) console.log ('AI TIMED ROTATION check:', next_random_index, 'time:', next_rotation_time);
        } while (next_rotation_time == 0 && next_random_index != random_index);

        if (rotation_time != 0) {
          random_index = next_random_index;
          // ***
//          option = $(rotate_options [random_index]);
          option = rotate_options [random_index];
          // ***
//          rotation_time = atob (option.data ('time'));
          rotation_time = atob (option.dataset.time);
        }

        if (ai_debug) console.log ('AI TIMED ROTATION index:', random_index, 'time:', rotation_time);
      }

      if (rotation_time > 0) {
        var next_random_index = random_index + 1;
        if (next_random_index >= rotate_options.length) next_random_index = 0;

        // ***
//        if (typeof $(rotation_block).data ('info') != 'undefined') {
        if (rotation_block.hasAttribute ('data-info')) {
          // ***
//          var block_info = JSON.parse (atob ($(rotation_block).data ('info')));
          var block_info = JSON.parse (atob (rotation_block.dataset.info));
          var rotation_id = block_info [0];

          // ***
//          $(rotation_block).attr ('data-next', next_random_index);
          rotation_block.setAttribute ('data-next', next_random_index);
          var rotation_selector = "div.ai-rotate.ai-" + rotation_id;

          if (ai_rotation_triggers.includes (rotation_selector)) {
            var trigger_rotation = false;
          }

          if (trigger_rotation) {
            ai_rotation_triggers.push (rotation_selector);

            // ***
//            setTimeout (function() {$(rotation_selector).addClass ('ai-timer'); ai_process_rotation ($(rotation_selector));}, rotation_time * 1000);
            setTimeout (function() {
              var next_elements = document.querySelectorAll (rotation_selector);
              next_elements.forEach ((el, index) => {
                el.classList.add ('ai-timer');
              });
              ai_process_rotation (next_elements);
            }, rotation_time * 1000);
          }
          option_time_text = ' (' + rotation_time + ' s)';
        }
      }
    }
    // ***
//    else if (typeof option.data ('group') != 'undefined') {
    else if (option.hasAttribute ('data-group')) {
      if (ai_debug) {
        // ***
//        var option_index = option.data ('index');
//        var option_name = b64d (option.data ('name'));
        var option_index = parseInt (option.dataset.index);
        var option_name = b64d (option.dataset.name);
        console.log ('AI ROTATE GROUP', '"' + option_name + '",', 'index:', random_index, '[' + option_index + ']');
      }
    }
    else {
      // Remove unused options
      if (!ai_debug) {
        // ***
//        rotate_options.each (function (index) {
        rotate_options.forEach ((el, index) => {
          if (index != random_index) el.remove ();
        });
      }

      if (ai_debug) console.log ('AI ROTATE no time');
      if (ai_debug) console.log ('AI ROTATE index:', random_index);
    }


    // ***
//    option.css ({"display": "", "visibility": "", "position": "", "width": "", "height": "", "top": "", "left": ""}).removeClass ('ai-rotate-hidden').removeClass ('ai-rotate-hidden-2');

    option.style.display = '';
    option.style.visibility = '';
    option.style.position = '';
    option.style.width = '';
    option.style.height = '';
    option.style.top = '';
    option.style.left = '';
    option.classList.remove ('ai-rotate-hidden');
    option.classList.remove ('ai-rotate-hidden-2');

    // ***
//    $(rotation_block).css ({"position": ""});
    rotation_block.style.position = '';

//    option.css ({"visibility": "visible"});

//    option.stop ().animate ({
//        opacity: 1,
//      }, 500, function() {
//    });

    // ***
//    if (typeof option.data ('code') != 'undefined') {
    if (option.hasAttribute ('data-code')) {
      // ***
//      rotate_options.empty();
      rotate_options.forEach ((el, index) => {
        el.innerText = '';
      });

      if (ai_debug) console.log ('AI ROTATE CODE');

      // ***
//      var option_code = b64d (option.data ('code'));
      var option_code = b64d (option.dataset.code);

      var range = document.createRange ();
      var fragment_ok = true;
      try {
        var fragment = range.createContextualFragment (option_code);
      }
      catch (err) {
        var fragment_ok = false;
        if (ai_debug) console.log ('AI ROTATE', 'range.createContextualFragment ERROR:', err);
      }

      // ***
//      option.append (option_code);
      option.append (fragment);

      ai_process_elements ();
    }

    // ***
//    var option_index = option.data ('index');
//    var option_name = b64d (option.data ('name'));
//    var debug_block_frame = $(rotation_block).closest ('.ai-debug-block');
    var option_index = parseInt (option.dataset.index);
    var option_name = b64d (option.dataset.name);
    var debug_block_frame = rotation_block.closest ('.ai-debug-block');
    // ***
//    if (debug_block_frame.length != 0) {
    if (debug_block_frame != null) {
      // ***
//      var name_tag = debug_block_frame.find ('kbd.ai-option-name');
      var name_tag = debug_block_frame.querySelectorAll ('kbd.ai-option-name');
      // Do not set option name in nested debug blocks
      // ***
//      var nested_debug_block = debug_block_frame.find ('.ai-debug-block');
//      if (typeof nested_debug_block != 'undefined') {
      var nested_debug_block = debug_block_frame.querySelectorAll ('.ai-debug-block');
      if (nested_debug_block.length != 0) {
        // ***
//        var name_tag2 = nested_debug_block.find ('kbd.ai-option-name');
        var name_tag2 = [];
        nested_debug_block.forEach ((el, index) => {
          var nested_option_names = el.querySelectorAll ('kbd.ai-option-name');
          nested_option_names.forEach ((option_name, index) => {
            name_tag2.push (option_name);
          });
        });

        // Convert nodeList to Array
        var name_tag = Array.from (name_tag);
        name_tag = name_tag.slice (0, name_tag.length - name_tag2.length);
      }
      // ***
//      if (typeof name_tag != 'undefined') {
      if (name_tag.length != 0) {
        // ***
//        var separator = name_tag.first ().data ('separator');
//        if (typeof separator == 'undefined') separator = '';
        if (name_tag [0].hasAttribute ('data-separator')) {
          separator = name_tag [0].dataset.separator;
        } else separator = '';
        // ***
//        name_tag.html (separator + option_name + option_time_text);
        name_tag.forEach ((el, index) => {
          el.innerText = separator + option_name + option_time_text;
        });

      }
    }

    var tracking_updated = false;
    // ****
//    var adb_show_wrapping_div = $(rotation_block).closest ('.ai-adb-show');
    var adb_show_wrapping_div = rotation_block.closest ('.ai-adb-show');
    // ***
//    if (adb_show_wrapping_div.length != 0) {
    if (adb_show_wrapping_div != null) {
      // ***
//      if (adb_show_wrapping_div.attr ("data-ai-tracking")) {
      if (adb_show_wrapping_div.hasAttribute ("data-ai-tracking")) {
        // ***
//        var data = JSON.parse (b64d (adb_show_wrapping_div.attr ("data-ai-tracking")));
        var data = JSON.parse (b64d (adb_show_wrapping_div.getAttribute ("data-ai-tracking")));
        if (typeof data !== "undefined" && data.constructor === Array) {
//          data [1] = random_index + 1;
          data [1] = option_index;
          data [3] = option_name ;

          // ***
//          if (ai_debug) console.log ('AI ROTATE TRACKING DATA ', b64d (adb_show_wrapping_div.attr ("data-ai-tracking")), ' <= ', JSON.stringify (data));
          if (ai_debug) console.log ('AI ROTATE TRACKING DATA ', b64d (adb_show_wrapping_div.getAttribute ("data-ai-tracking")), ' <= ', JSON.stringify (data));

          // ***
//          adb_show_wrapping_div.attr ("data-ai-tracking", b64e (JSON.stringify (data)))
          adb_show_wrapping_div.setAttribute ("data-ai-tracking", b64e (JSON.stringify (data)))

          // Inserted code may need click trackers
          // ***
//          adb_show_wrapping_div.addClass ('ai-track');
          adb_show_wrapping_div.classList.add ('ai-track');
          if (timed_rotation && ai_tracking_finished) {
            // Prevent pageview trackign for timed rotations
            adb_show_wrapping_div.classList.add ('ai-no-pageview');
          }

          tracking_updated = true;
        }
      }
    }

    if (!tracking_updated) {
      // ***
//      var wrapping_div = $(rotation_block).closest ('div[data-ai]');
      var wrapping_div = rotation_block.closest ('div[data-ai]');
      // ***
//      if (typeof wrapping_div.attr ("data-ai") != "undefined") {
      if (wrapping_div != null && wrapping_div.hasAttribute ("data-ai")) {
        // ***
//        var data = JSON.parse (b64d (wrapping_div.attr ("data-ai")));
        var data = JSON.parse (b64d (wrapping_div.getAttribute ("data-ai")));
        if (typeof data !== "undefined" && data.constructor === Array) {
//          data [1] = random_index + 1;
          data [1] = option_index;
          data [3] = option_name;
          // ***
//          wrapping_div.attr ("data-ai", b64e (JSON.stringify (data)))
          wrapping_div.setAttribute ("data-ai", b64e (JSON.stringify (data)))

          // Inserted code may need click trackers
          // ***
//          wrapping_div.addClass ('ai-track');
          wrapping_div.classList.add ('ai-track');
          if (timed_rotation && ai_tracking_finished) {
            // Prevent pageview trackign for timed rotations
            wrapping_div.classList.add ('ai-no-pageview');
          }

          // ***
//          if (ai_debug) console.log ('AI ROTATE TRACKING DATA ', b64d (wrapping_div.attr ("data-ai")));
          if (ai_debug) console.log ('AI ROTATE TRACKING DATA ', b64d (wrapping_div.getAttribute ("data-ai")));

        }
      }
    }
  }

  ai_process_rotations = function () {
    // ***
//    $("div.ai-rotate").each (function (index, element) {
//      ai_process_rotation (this);
    document.querySelectorAll ("div.ai-rotate").forEach ((el, index) => {
      ai_process_rotation (el);
    });
  }

  function ai_process_group_rotations () {
//    $("div.ai-rotate.ai-rotation-groups").each (function (index, element) {
//      $(this).addClass ('ai-timer');
//      ai_process_rotation (this);
    document.querySelectorAll ("div.ai-rotate.ai-rotation-groups").forEach ((el, index) => {
      el.classList.add ('ai-timer');
      ai_process_rotation (el);
    });
  }

  ai_process_rotations_in_element = function (el) {
//    $("div.ai-rotate", el).each (function (index, element) {
//      ai_process_rotation (this);
    el.querySelectorAll ("div.ai-rotate").forEach ((element, index) => {
      ai_process_rotation (element);
    });
  }

  // ***
//  $(document).ready (function($) {
//    setTimeout (function() {ai_process_rotations ();}, 10);
//  });

function ai_delay_and_process_rotations () {
  setTimeout (function() {ai_process_rotations ();}, 10);
}

function ai_ready (fn) {
  if (document.readyState === 'complete' || (document.readyState !== 'loading' && !document.documentElement.doScroll)) {
    fn ();
  } else {
     document.addEventListener ('DOMContentLoaded', fn);
  }
}

ai_ready (ai_delay_and_process_rotations);

//});

ai_process_elements_active = false;

function ai_process_elements () {
  if (!ai_process_elements_active)
    setTimeout (function() {
      ai_process_elements_active = false;

      if (typeof ai_process_rotations == 'function') {
        ai_process_rotations ();
      }

      if (typeof ai_process_lists == 'function') {
//        ai_process_lists (jQuery (".ai-list-data"));
        ai_process_lists ();
      }

      if (typeof ai_process_ip_addresses == 'function') {
//        ai_process_ip_addresses (jQuery (".ai-ip-data"));
        ai_process_ip_addresses ();
      }

      if (typeof ai_process_filter_hooks == 'function') {
//        ai_process_filter_hooks (jQuery (".ai-filter-check"));
        ai_process_filter_hooks ();
      }

      if (typeof ai_adb_process_blocks == 'function') {
        ai_adb_process_blocks ();
      }

      //?? duplicate down
//      if (typeof ai_install_click_trackers == 'function' && ai_tracking_finished == true) {
//        ai_install_click_trackers ();
//      }

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
  ai_process_elements_active = true;
}

}

