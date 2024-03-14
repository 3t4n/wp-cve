(function() {
	tinymce.create('tinymce.plugins.ChangeCase', {
        init : function(ed, url) {
			
			// Register the commands
			ed.addCommand('nocaps', function() {
				String.prototype.lowerCase = function() {
					return this.toLowerCase();
				}
            var sel = ed.dom.decode(ed.selection.getContent());
            sel = sel.lowerCase();
            ed.selection.setContent(sel);
            ed.save();
            ed.isNotDirty = true;
        });	

			ed.addCommand('allcaps', function() {
					String.prototype.upperCase = function() {
    return this.toUpperCase();
}
            var sel = ed.dom.decode(ed.selection.getContent());
            sel = sel.upperCase();
            ed.selection.setContent(sel);
            ed.save();
            ed.isNotDirty = true;
        });	
			
			ed.addCommand('sentencecase', function() {
					String.prototype.sentenceCase = function() {
    return this.toLowerCase().replace(/(^\s*\w|[\.\!\?]\s*\w)/g, function(c)
	{
		return c.toUpperCase()
	});
}
            var sel = ed.dom.decode(ed.selection.getContent());
            sel = sel.sentenceCase();
            ed.selection.setContent(sel);
            ed.save();
            ed.isNotDirty = true;
        });	
			
			ed.addCommand('titlecase', function() {
					String.prototype.titleCase = function() {
    return this.toLowerCase().replace(/(^|[^a-z])([a-z])/g, function(m, p1, p2)
    {
        return p1 + p2.toUpperCase();
    });
}
            var sel = ed.dom.decode(ed.selection.getContent());
            sel = sel.titleCase();
            ed.selection.setContent(sel);
            ed.save();
            ed.isNotDirty = true;
        });	
			
			// Register Keyboard Shortcuts
			ed.addShortcut('meta+shift+l','Lowercase', ['nocaps', false, 'Lowercase'], this);
			ed.addShortcut('meta+shift+u','Uppercase', ['allcaps', false, 'Uppercase'], this);
			ed.addShortcut('meta+shift+s','Sentence Case', ['sentencecase', false, 'Sentence Case'], this);
			ed.addShortcut('meta+shift+t','Title Case', ['titlecase', false, 'Lowercase'], this);
			
			// Register the buttons
            ed.addButton('nocaps', {
                title : 'Lowercase (Ctrl+Shift+L)',
                image : url+'/nc.png',
				cmd : 'nocaps',
            });
            ed.addButton('allcaps', {
                title : 'Uppercase (Ctrl+Shift+U)',
                image : url+'/ac.png',
				cmd : 'allcaps',
            });
            ed.addButton('sentencecase', {
                title : 'Sentence Case (Ctrl+Shift+S)',
                image : url+'/sc.png',
				cmd : 'sentencecase',
            });
			ed.addButton('titlecase', {
                title : 'Title Case (Ctrl+Shift+T)',
                image : url+'/tc.png',
				cmd : 'titlecase',
            });
        },
        getInfo : function() {
            return {
				longname  : 'Change Text Case',
				author 	  : 'Michael Aronoff',
				authorurl : 'https://www.ciic.com',
				infourl   : 'https://wordpress.org/plugins/change-case-for-tinymce/',
				version   : '2.1'
            };
        }
    });

	tinymce.PluginManager.add('ChangeCase', tinymce.plugins.ChangeCase);
})();