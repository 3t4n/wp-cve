/*
Version: 1.0
Author: alado
Author URI: https://flammlin.com/
*/

(function() {
tinymce.create('tinymce.plugins.WPNoteButtons', {
init : function(ed, url) {
  
ed.addButton('nelp', {
title : 'Help',
image : url + '/../images/help.png',
onclick : function() {
ed.selection.setContent('[help]' + ed.selection.getContent() + '[/help]');
}
});

ed.addButton('important', {
title : 'Important',
image : url + '/../images/important.png',
onclick : function() {
ed.selection.setContent('[important]' + ed.selection.getContent() + '[/important]');
}
});

ed.addButton('note', {
title : 'Note',
image : url + '/../images/note.png',
onclick : function() {
ed.selection.setContent('[note]' + ed.selection.getContent() + '[/note]');
}
});

ed.addButton('tip', {
title : 'Tip',
image : url + '/../images/tip.png',
onclick : function() {
ed.selection.setContent('[tip]' + ed.selection.getContent() + '[/tip]');
}
});

ed.addButton('warning', {
title : 'Warning',
image : url + '/../images/warning.png',
onclick : function() {
ed.selection.setContent('[warning]' + ed.selection.getContent() + '[/warning]');
}
});

},
createControl : function(n, cm) {
return null;
},
});
tinymce.PluginManager.add('my_button_script', tinymce.plugins.WPNoteButtons);
})();

