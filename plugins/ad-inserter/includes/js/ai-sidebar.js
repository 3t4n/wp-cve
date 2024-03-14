if (typeof sticky_widget_mode != 'undefined') {

const AI_STICKY_WIDGET_MODE_CSS       = 0;
const AI_STICKY_WIDGET_MODE_JS        = 1;
const AI_STICKY_WIDGET_MODE_CSS_PUSH  = 2;

// ***
//jQuery(document).ready(function($) {


function ai_configure_sticky_widgets () {
  // ***
//  var ai_set_sidebars = function ($) {
  var ai_set_sidebars = function () {
    // ***
//    var sticky_widget_mode   = AI_FUNC_GET_STICKY_WIDGET_MODE;
//    var sticky_widget_margin = AI_FUNC_GET_STICKY_WIDGET_MARGIN;
//    var document_width = $(document).width();

    var document_width = document.body.clientWidth;

    var ai_debug = typeof ai_debugging !== 'undefined'; // 1
//    var ai_debug = false;

    // ***
//    $(".ai-sticky-widget").each (function () {
    document.querySelectorAll (".ai-sticky-widget").forEach ((widget, i) => {

      if (sticky_widget_mode == AI_STICKY_WIDGET_MODE_CSS_PUSH) {
        var ai_sticky_block = widget.querySelector ('.' + ai_block_class_def);
        if (ai_sticky_block != null) {
          ai_sticky_block.style.position = 'sticky';
          ai_sticky_block.style.position = '-webkit-sticky';
          ai_sticky_block.style.top = sticky_widget_margin + 'px';
        }

        var ai_sticky_space = widget.querySelector ('.ai-sticky-space');
        if (ai_sticky_space != null) {
          ai_sticky_space.style.height = window.innerHeight + 'px';
        }
      } else {
      // ***
//      var widget = $(this);
//      var widget_width = widget.width();
      var widget_width = widget.clientWidth;

      if (ai_debug) console.log ('');
      // ***
//      if (ai_debug) console.log ("WIDGET:", widget.width (), widget.prop ("tagName"), widget.attr ("id"));
      if (ai_debug) console.log ("WIDGET:", widget_width, widget.tagName, widget.hasAttribute ("id") ? '#' + widget.getAttribute ("id") : '', widget.hasAttribute ("class") ? '.' + widget.getAttribute ("class").replace(/ +(?= )/g,'').split (' ').join ('.') : '');

      var already_sticky_js = false;
      // ***
//      var sidebar = widget.parent ();
      var sidebar = widget.parentElement;
      // ***
//      while (sidebar.prop ("tagName") != "BODY") {
      while (sidebar.tagName != "BODY") {

        // ***
//        if (sidebar.hasClass ('theiaStickySidebar')) {
        if (sidebar.classList.contains ('theiaStickySidebar')) {
          already_sticky_js = true;
          break;
        }

        // ***
//        if (ai_debug) console.log ("SIDEBAR:", sidebar.width (), sidebar.prop ("tagName"), sidebar.attr ("id"));
        if (ai_debug) console.log ("SIDEBAR:", sidebar.clientWidth, sidebar.clientHeight, sidebar.tagName, sidebar.hasAttribute ("id") ? '#' + sidebar.getAttribute ("id") : '', sidebar.hasAttribute ("class") ? '.' + sidebar.getAttribute ("class").replace(/ +(?= )/g,'').split (' ').join ('.') : '');

        // ***
//        var parent_element = sidebar.parent ();
        var parent_element = sidebar.parentElement;
        // ***
//        var parent_element_width = parent_element.width();
        var parent_element_width = parent_element.clientWidth;
        if (parent_element_width > widget_width * 1.2 || parent_element_width > document_width / 2) break;
        sidebar = parent_element;
      }
      if (already_sticky_js) {
        if (ai_debug) console.log ("JS STICKY SIDEBAR ALREADY SET");
        return;
      }


      // ***
//      var new_sidebar_top = sidebar.offset ().top - widget.offset ().top + sticky_widget_margin;
      var sidebar_rect = sidebar.getBoundingClientRect ();
      var widget_rect = widget.getBoundingClientRect ();

//      console.log ('sidebar_rect', sidebar_rect);
//      console.log ('widget_rect', widget_rect);

      var new_sidebar_top = sidebar_rect.top - widget_rect.top + sticky_widget_margin;

      if (ai_debug) console.log ("NEW SIDEBAR TOP:", new_sidebar_top);

      if (sticky_widget_mode == AI_STICKY_WIDGET_MODE_CSS) {
        // CSS
        // ***
//        if (sidebar.css ("position") != "sticky" || isNaN (parseInt (sidebar.css ("top"))) || sidebar.css ("top") < new_sidebar_top) {
        if (sidebar.style.position != "sticky" || isNaN (parseInt (sidebar.style.top)) || sidebar.style.top < new_sidebar_top) {
          // ***
//          sidebar.css ("position", "sticky").css ("position", "-webkit-sticky").css ("top", new_sidebar_top);
          sidebar.style.position = 'sticky';
          sidebar.style.position = '-webkit-sticky';
          sidebar.style.top = new_sidebar_top + 'px';

          if (ai_debug) console.log ("CSS STICKY SIDEBAR, TOP:", new_sidebar_top);

          if (typeof ai_no_sticky_sidebar_height == 'undefined') {
            var mainbar = sidebar;
            var paddings_margins = 0;
            while (mainbar.tagName != "BODY") {

              mainbar = mainbar.parentElement;

              if (ai_debug) console.log ("MAINBAR:", mainbar.clientWidth, mainbar.clientHeight, mainbar.tagName, mainbar.hasAttribute ("id") ? '#' + mainbar.getAttribute ("id") : '', mainbar.hasAttribute ("class") ? '.' + mainbar.getAttribute ("class").replace(/ +(?= )/g,'').split (' ').join ('.') : '');

              if ((mainbar.clientWidth > sidebar.clientWidth * 1.5 || mainbar.clientWidth > document_width / 2) && mainbar.clientHeight > sidebar.clientHeight) {
                var mainbarClientHeight = mainbar.clientHeight;
                sidebar.parentElement.style.height = mainbarClientHeight + 'px';

                var mainbarClientHeightDifference = mainbar.clientHeight - mainbarClientHeight;
                sidebar.parentElement.style.height = (mainbarClientHeight - mainbarClientHeightDifference) + 'px';

                if (ai_debug) console.log ("SIDEBAR parent element height set:", mainbar.clientHeight);
                break;
              }
            }
          }
        }
        else if (ai_debug) console.log ("CSS STICKY SIDEBAR ALREADY SET");
      } else
      if (sticky_widget_mode == AI_STICKY_WIDGET_MODE_JS) {
          if (window.jQuery && window.jQuery.fn) {

            // Javascript
            // ***  theiaStickySidebar is jQuery library
  //          sidebar.theiaStickySidebar({
            jQuery (sidebar).theiaStickySidebar({
              additionalMarginTop: new_sidebar_top,
              sidebarBehavior: 'stick-to-top',
            });

            if (ai_debug) console.log ("JS STICKY SIDEBAR, TOP:", new_sidebar_top);
          } else {
              console.error ('AI STICKY WIDGET MODE Javascript USES jQuery', '- jQuery not found');
            }
        }
        }
    });

  };

  if (typeof ai_sticky_sidebar_delay == 'undefined') {
    ai_sticky_sidebar_delay = 200;
  }

  setTimeout (function() {
    // ***
//    ai_set_sidebars ($);
    ai_set_sidebars ();
  }, ai_sticky_sidebar_delay);
// ***
//});
}

function ai_ready (fn) {
  if (document.readyState === 'complete' || (document.readyState !== 'loading' && !document.documentElement.doScroll)) {
    fn ();
  } else {
     document.addEventListener ('DOMContentLoaded', fn);
  }
}


ai_ready (ai_configure_sticky_widgets);

}
