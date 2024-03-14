(function($) {
   'use strict';

   $(function() {

      accordion_setup();

      if (typeof ajax_vars !== 'undefined') {
         //console.log(ajax_vars);
         var post_search_iterator = 0;
         var post_search_array_length = 50; // How many posts to search in for each ajax call
         $('.shortcodes_result').css('opacity', '.4');
         processRoutine();
      }

      function accordion_setup() {
         var acc = document.getElementsByClassName("shortcode_accordion_button");
         var i;

         for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
               this.classList.toggle("shortcode_accordion_active");
               var panel = this.nextElementSibling;
               if (panel.style.display === "block") {
                  panel.style.display = "none";
               } else {
                  panel.style.display = "block";
               }
            });
         }
      }

      function processRoutine() {
         var action = '';
         switch (ajax_vars.action) {
            case 'find_content':
               action = 'shortcodes_finder_content_search_process';
               break;
            case 'find_unused':
               action = 'shortcodes_finder_unused_search_process';
               break;
         }
         if (action.length == 0) return;

         // Take a portion of the full array
         var array_to_pass = ajax_vars.posts.slice(post_search_iterator, post_search_iterator + post_search_array_length);

         if ((typeof ajax_vars.posts[post_search_iterator] == 'undefined')) {
            $('.progress-bar').css('width', '100%');
            $('.progress-label-value').html(100);
            $('.shortcodes_result').css('opacity', '1');

            switch (ajax_vars.action) {
               case 'find_unused':

                  var accordions_html = '';
                  for (var shortcode_name in find_unused_associative_array) {

                     // Jump system parameters; they are not really shortcodes
                     if (shortcode_name.charAt(0) == '~') continue;

                     var shortcode_uses = find_unused_associative_array[shortcode_name];

                     accordions_html += '<div class="shortcode_accordion">' +
                        '<button class="shortcode_accordion_button"><span class="shortcode_counter">' + shortcode_uses.length + '</span>' + shortcode_name + '</button>' +
                        '<div class="shortcode_accordion_panel">';

                     var i;
                     for (i = 0; i < shortcode_uses.length; i++) {
                        var shortcode_use = shortcode_uses[i];

                        accordions_html += '<div class="shortcode_use shortcode_use_status_' + shortcode_use['post']['status'] + '">' +
                           '<p class="shortcode_code">' + shortcode_use['code'] + '</p>' +
                           '<a href="' + shortcode_use['post']['permalink'] + '">' + shortcode_use['post']['title'] + '</a>' +
                           '<a href="' + shortcode_use['post']['edit_post_link'] + '">' + img_edit_post + '</a>' +
                           '</div>';
                     }

                     accordions_html += '</div>' +
                        '</div>';
                  }

                  $('.shortcodes_result').html(accordions_html);
                  break;
            }

            accordion_setup();
            return;
         } else {
            var percentage = Math.round(post_search_iterator * 100 / ajax_vars.posts.length);
            $('.progress-bar').css('width', percentage + '%');
            $('.progress-label-value').html(percentage);
         }

         $.post(
            ajax_vars.ajax_url, {
               'action': action,
               'posts': array_to_pass
            },
            function(response) {

               if (response === '-1') {
                  console.log('Failed to process routine for these posts: ' + array_to_pass + '.');
               } else {
                  switch (ajax_vars.action) {
                     case 'find_content':
                        manage_result_find_content(response);
                        break;
                     case 'find_unused':
                        manage_result_find_unused(response);
                        break;
                     default:
                        manage_result_default(response);
                        break;
                  }
               }

               post_search_iterator += post_search_array_length;
               processRoutine();
            }
         );
      }

      function manage_result_find_content(result) {
         $('.shortcodes_result').append(result);
      }

      var find_unused_associative_array = Object();
      var img_edit_post;

      function manage_result_find_unused(result) {

         //$('.shortcodes_result').append(result);

         var result_array = JSON.parse(result);

         for (var key in result_array) {

            if (key == '~img_edit_post~') {
               if (img_edit_post == null) img_edit_post = result_array['~img_edit_post~'];
               continue;
            }

            var value = result_array[key];

            if (!Array.isArray(find_unused_associative_array[key]))
               find_unused_associative_array[key] = new Array();

            if (Array.isArray(value)) {
               var k;
               for (k = 0; k < value.length; k++)
                  find_unused_associative_array[key].push(value[k]);
            } else
               find_unused_associative_array[key].push(value);
         }
      }

      function manage_result_default(result) {
         $('.shortcodes_result').append('Invalid call<br/>');
         console.log('Invalid call: ' + result);
      }

      function set_settings_existing_shortcodes_state(el) {
         if (el.checked) {
            var div = $('#sf_settings_existing_shortcodes');
            div.css('height', 'auto');
            var autoHeight = div.height();
            div.height(0).animate({
               height: autoHeight
            }, 1000);
            div.stop(true, false).animate({
               height: autoHeight
            }, 'fast');
         } else {
            $('#sf_settings_existing_shortcodes').stop(true, false).animate({
               height: '0'
            }, 'fast');
         }
      }

      //set_settings_existing_shortcodes_state($('#sf_settings_disable_existing_shortcodes'));
      $('#sf_settings_disable_existing_shortcodes').on('change', (function() {
         set_settings_existing_shortcodes_state(this);
      })).trigger('change');

      if (location.hash.length !== 0) {
         if ($(location.hash).length)
            $(location.hash).parent().addClass('shortcode_use_highlighted');
      }

   });

})(jQuery);

function selectText(el) {
   if (document.selection) { // IE
      var range = document.body.createTextRange();
      range.moveToElementText(el);
      range.select();
   } else if (window.getSelection) {
      var range = document.createRange();
      range.selectNode(el);
      window.getSelection().removeAllRanges();
      window.getSelection().addRange(range);
   }
}

function clearSelection() {
   if (document.selection) { // IE
      document.selection.empty();
   } else if (window.getSelection) {
      window.getSelection().removeAllRanges();
   }
}

function copyContentToClipboard(el, confirm_text) {

   selectText(el);

   /* Copy the text inside the text field */
   document.execCommand("copy");

   /* Alert the copied text */
   alert(confirm_text);

   clearSelection();
}