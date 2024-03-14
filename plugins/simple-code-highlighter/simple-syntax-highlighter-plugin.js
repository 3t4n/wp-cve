(function(){
  var doc = document;
  var scriptQuery = '';
  
  
  for (var scripts = doc.scripts, i = scripts.length; --i >= 0;) {
    var script = scripts[i];
    var match = script.src.match(
        /^[^?#]*\/ai-code-highliter_plugin\.js(\?[^#]*)?(?:#.*)?$/);
    if (match) {
      scriptQuery = match[1] || '';

      
      script.parentNode.removeChild(script);
      break;
    }
  }

  
  var plugin_folder = '';
  scriptQuery.replace(
      /[?&]([^&=]+)=([^&]+)/g,
      function (_, name, value) {
        value = decodeURIComponent(value);
        name = decodeURIComponent(name);
        if (name == 'plugin_folder')   { plugin_folder = value; }
      });



  function cleanhtml(s) {
    return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  }
  
  
    tinymce.PluginManager.add('kedinn', function( editor, url ) {
        editor.addButton( 'kedinn', {
            title: 'Insert Code',
            icon: 'icon dashicons-editor-code',
            onclick: function() {
                var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
            W = W - 40;
            H = H - 84;
            tb_show( 'Insert code', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=ai-code-highliter-form' );
              
            }
        });
    });

  

  jQuery(function(){


    var form = jQuery('<div id="ai-code-highliter-form"><textarea id="ai-code-highliter-code" name="code" style="width:100%; height:85%; margin-top:12px; margin-bottom:10px" placeholder="Paste or enter the code here. Ctrl + Intro for a code jump" />\
      <input type="button" id="ai-code-highliter-submit" class="button-primary" value="Insert Code" name="submit" style="float:right;" />\
    </div>');
    
    form.appendTo('body').hide();


    form.find('#ai-code-highliter-submit').click(function(){
      
      var shortcode = '<?prettify linenums=true?><pre class="prettyprint">\n' + cleanhtml(jQuery('#ai-code-highliter-code').val()) + '</pre>';
    

      tinyMCE.activeEditor.execCommand('mceInsertRawHTML', 0, shortcode);
      
      tb_remove();
    });
  });
})()

// creates the text button
QTags.addButton( 'ai-code-highliter_button2', 'Code Highlighter', '<?prettify linenums=true?><pre class="prettyprint">', '</pre>', '#', 'Add Highlighted Code');