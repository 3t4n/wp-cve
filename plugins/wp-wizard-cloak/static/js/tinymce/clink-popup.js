tinyMCEPopup.requireLangPack();

var CLinkDialog = {
	preInit : function() {
		var url;

		if (url = tinyMCEPopup.getParam("external_link_list_url"))
			document.write('<script language="javascript" type="text/javascript" src="' + tinyMCEPopup.editor.documentBaseURI.toAbsolute(url) + '"></script>');
	},

	init : function() {
		var f = document.forms[0], ed = tinyMCEPopup.editor;

		this.fillClassList('class_list');
	},

	update : function() {
		var f = document.forms[0], ed = tinyMCEPopup.editor, e;
		tinyMCEPopup.restoreSelection();
		e = ed.selection.getContent();

		if ('' != f.main.value) {
			tinyMCEPopup.execCommand("mceBeginUndoLevel");
			var html = '[clink';
			var ids = [f.main.value];
			if (f['additional[]'] instanceof NodeList) {
				for (var a = 0; a < f['additional[]'].length; a++) {
					if ('' != f['additional[]'][a].value) {
						ids.push(f['additional[]'][a].value);
					}
				}
			}
			html += ' id="' + ids.join('|') + '"';
			
			if ('' != f.target_list.value) {
				html += ' target="' + f.target_list.value +'"';
			}
			if ('' != f.class_list.value) {
				html += ' class="' + f.class_list.value +'"';
			}
			if ('' != f.linktitle.value) {
				html += ' title="' + f.linktitle.value.replace(/&/g, '&amp;').replace(/"/g, '&quot;') +'"';
			}
			if ('' != f.sub_id.value) {
				html += ' subid="' + escape(f.sub_id.value) +'"';
			}
			if (f.nofollow.checked) {
				html += ' rel="nofollow"';
			}
			if ('' != e) {
				html += ']' + e + '[/clink]';
			} else {
				html += '/]';
			}
			tinyMCEPopup.execCommand("mceInsertContent", false, html);
			tinyMCEPopup.execCommand("mceEndUndoLevel");
		}
		tinyMCEPopup.close();
	},

	fillClassList : function(id) {
		var dom = tinyMCEPopup.dom, lst = dom.get(id), v, cl;

		if (v = tinyMCEPopup.getParam('theme_advanced_styles')) {
			cl = [];

			tinymce.each(v.split(';'), function(v) {
				var p = v.split('=');

				cl.push({'title' : p[0], 'class' : p[1]});
			});
		} else
			cl = tinyMCEPopup.editor.dom.getClasses();

		if (cl.length > 0) {
			lst.options[lst.options.length] = new Option(tinyMCEPopup.getLang('not_set'), '');

			tinymce.each(cl, function(o) {
				lst.options[lst.options.length] = new Option(o.title || o['class'], o['class']);
			});
		} else
			dom.remove(dom.getParent(id, 'tr'));
	}
};

CLinkDialog.preInit();
tinyMCEPopup.onInit.add(CLinkDialog.init, CLinkDialog);
