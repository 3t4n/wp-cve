'use strict';
(function($) {
  $(function() {
    const editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
    const codemirror_gen =
        {
          'indentUnit': 4,
          'indentWithTabs': true,
          'inputStyle': 'contenteditable',
          'lineNumbers': true,
          'lineWrapping': true,
          'styleActiveLine': true,
          'continueComments': true,
          'extraKeys': {
            'Ctrl-Space': 'autocomplete',
            'Ctrl-\/': 'toggleComment',
            'Cmd-\/': 'toggleComment',
            'Alt-F': 'findPersistent',
            'Ctrl-F': 'findPersistent',
            'Cmd-F': 'findPersistent',
          },
          'direction': 'ltr',
          'gutters': ['CodeMirror-lint-markers'],
          'mode': 'css',
          'lint': true,
          'autoCloseBrackets': true,
          'autoCloseTags': true,
          'matchTags': {
            'bothTags': true,
          },
          'tabSize': 2,
        };

    if ($('#wpcf7-form').length) {
      let codemirror_el =
          {
            'tagname-lowercase': true,
            'attr-lowercase': true,
            'attr-value-double-quotes': false,
            'doctype-first': false,
            'tag-pair': true,
            'spec-char-escape': true,
            'id-unique': true,
            'src-not-empty': true,
            'attr-no-duplication': true,
            'alt-require': true,
            'space-tab-mixed-disabled': 'tab',
            'attr-unsafe-chars': true,
            'mode': 'htmlmixed',
          };

      editorSettings.codemirror = Object.assign(editorSettings.codemirror, codemirror_gen, codemirror_el);

      var editorHTML = wp.codeEditor.initialize('wpcf7-form', editorSettings);
    }


    var $wpcf7_taggen_insert = wpcf7.taggen.insert;
    wpcf7.taggen.insert = function(content) {
      insertTextAtCursor(content);
      $('#wpcf7-form').text(get_codemirror());
      $wpcf7_taggen_insert.apply(this, arguments);
    };

    function get_codemirror() {
      return editorHTML.codemirror.getValue();
    }

    function insertTextAtCursor(text) {
      var cursor = editorHTML.codemirror.getCursor();
      editorHTML.codemirror.replaceRange(text, cursor);
    }

    function sincronized_codemirror() {
      var text = editorHTML.codemirror.getValue();
      document.getElementById('wpcf7-form').value = text;
    }

    editorHTML.codemirror.on('keyup', function() {
      sincronized_codemirror();
    });

    $("#informationdiv_coder").insertAfter("#informationdiv");
    $("#informationdiv_coder").toggle();

  });
})(jQuery);