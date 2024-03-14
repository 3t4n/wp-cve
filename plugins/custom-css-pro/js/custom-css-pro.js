(function ($) {
  "use strict";

  // After main page load, go to loading iframe.
  $(document).ready(function () {
    window.ccp_count = 0;

    var s = $("#ccp-iframe").attr("data-href");
    $("#ccp-iframe").attr("src", s);

    $("#ccp-iframe").on("load", function () {
      window.ccp_main();
    });
  });

  // Call drawing function.
  window.ccp_main = function () {
    $("#ccp-bg").hide();

    // Ace Editor Set Up
    ace.require("ace/ext/language_tools");
    var editor = ace.edit("ccp-data");
    editor.getSession().setMode("ace/mode/css");
    editor.setTheme("ace/theme/twilight");
    editor.getSession().setUseWrapMode(true);
    editor.$blockScrolling = Infinity;

    // enable autocompletion and snippets
    editor.setOptions({
      fontSize: "16px",
      enableBasicAutocompletion: false,
      enableSnippets: false,
      enableLiveAutocompletion: false,
    });

    // Focus to last line in editor as default
    editor.focus();
    var session = editor.getSession();
    var count = session.getLength();
    editor.gotoLine(count, session.getLine(count - 1).length);

    // hide ace editor warnings, show only errors.
    window.originalChange = true;
    session.on("changeAnnotation", function () {
      if (window.originalChange) {
        var annotations = session.getAnnotations() || [],
          i = annotations.length;

        while (i--) {
          if (
            annotations[i].type == "warning" ||
            annotations[i].type == "info"
          ) {
            annotations.splice(i, 1);
          } else {
            // Getting value of error
            var line = annotations[i].row;
            var errorLine = editor.session.getLine(line);

            // Skip CSS Var error
            if (
              /^(\s+)?\-\-\w/g.test(errorLine) == true ||
              /var\(\-\-/g.test(errorLine) == true
            ) {
              // delete annotations
              annotations.splice(i, 1);
            }
          }
        }

        window.originalChange = false;
        session.setAnnotations(
          Object.keys(annotations).reduce(function (array, key) {
            return array.concat(annotations[key]);
          }, [])
        );
        window.originalChange = true;
      }
    });

    // Set iframe.
    var iframe = $($("#ccp-iframe").contents().get(0));
    var iframeHead = iframe.find("head");

    // Update title
    var title = iframeHead.find("title").html();
    $("head title").html(title);

    // Adding <style> area to head section.
    iframeHead.append("<style id='ccp-live-css'></style>");

    // Live CSS
    var lastValue = editor.getValue();
    $("#ccp-data").on("keydown keyup", function (e) {
      // Get current value
      var v = editor.getValue();

      // If really changed.
      if (lastValue != v) {
        // Live update
        iframe.find("#ccp-live-css").text(v);

        if ($("#ccp-save").hasClass("active") == false) {
          $("#ccp-save").addClass("active").text("Save Changes");
        }
      }

      // Update last value
      lastValue = v;
    });

    // Live update
    iframe.find("#ccp-live-css").text(editor.getValue());

    // Surf in the iframe
    iframe.on("click", "a[href]", function (evt) {
      // Stop events
      evt.stopPropagation();
      evt.preventDefault();

      // Get URL
      var href = get_absolute_path($(this).attr("href"));

      // If no ccp-iframe
      if (href.indexOf("ccp-iframe=true") == -1) {
        // Add and redirect
        document.getElementById("ccp-iframe").contentWindow.location.href =
          ccp_add_query_arg(href, "ccp-iframe", "true");
      }
    });

    // Keys
    iframe.on("keyup keydown keypress", function (e) {
      // Getting current tag name.
      var tag = e.target.tagName.toLowerCase();

      // Getting Keycode.
      var key = e.keyCode || e.which;

      // Control
      var controlKey = false;
      var isInput = false;
      var shifted = e.shiftKey;

      // Stop If CTRL Keys hold.
      if (key === true || key === true) {
        controlKey = true;
      }

      // Stop if this target is input or textarea.
      if (tag == "input" || tag == "textarea") {
        isInput = true;
      }

      // Backspace
      if (key == 8 && isInput == false && controlKey == false) {
        e.preventDefault();
        return false;
      }
    });

    // Run only one time
    if (window.ccp_count == 0) {
      // Close button width is same with gutter: ready
      var x = $(".ace_gutter").width();
      $(".ccp-close").width(x);

      // Close button width is same with gutter: Keydown
      $("#ccp-data").on("keydown", function () {
        var x = $(".ace_gutter").width();
        $(".ccp-close").width(x);
      });

      // Keys
      $(document).on("keyup keydown keypress", function (e) {
        // Getting current tag name.
        var tag = e.target.tagName.toLowerCase();

        // Getting Keycode.
        var key = e.keyCode || e.which;

        // Control
        var controlKey = false;
        var isInput = false;
        var shifted = e.shiftKey;

        // Stop If CTRL Keys hold.
        if (key === true || key === true) {
          controlKey = true;
        }

        // Stop if this target is input or textarea.
        if (tag == "input" || tag == "textarea") {
          isInput = true;
        }

        // Backspace
        if (key == 8 && isInput == false && controlKey == false) {
          e.preventDefault();
          return false;
        }
      });

      // Check close btn
      $(".ccp-close").click(function (e) {
        if ($("#ccp-save").hasClass("active")) {
          if (!confirm("Do you want to close without saving the changes?")) {
            e.preventDefault();
            return false;
          }
        }
      });

      // Save changes
      $("#ccp-save").on("click", function () {
        var t = $(this);
        var v = editor.getValue();

        // Check if has any change.
        if (t.hasClass("active") == false) {
          return false;
        }

        // Saving
        t.text("Saving...").removeClass("active");

        // Post
        $.ajax({
          type: "POST",
          url: window.ccp_ajax_url,
          data: {
            action: "ccp_save_data",
            _wpnonce: $("#ccp-save").attr("data-nonce"),
            data: v,
          },
        }).done(function () {
          t.text("Saved");
        });
      });
    }

    window.ccp_count++;
  };

  // Adding query to URL
  function ccp_add_query_arg(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf("?") !== -1 ? "&" : "?";
    if (uri.match(re)) {
      return uri.replace(re, "$1" + key + "=" + value + "$2");
    } else {
      return uri + separator + key + "=" + value;
    }
  }

  // Convert patchs to URL
  var get_absolute_path = function (href) {
    var link = document
      .getElementById("ccp-iframe")
      .contentWindow.document.createElement("a");
    link.href = href;
    return (
      link.protocol + "//" + link.host + link.pathname + link.search + link.hash
    );
  };
})(jQuery);
