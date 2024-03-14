tinyMCEPopup.requireLangPack();

var SHTBADVINSERTDialog = {

	init : function() {
		var f = document.forms[0], ed = tinyMCEPopup.editor;

		if (e = ed.dom.getParent(ed.selection.getNode(), 'pre')) {
			str = ed.dom.getAttrib(e, 'class').split(";");
			f.shtb_adv_insert_language.value = str[0].replace("brush: ", "");
			if(ed.dom.getAttrib(e, 'class').match(/gutter: true/)){
				f.shtb_adv_insert_linenumbers.checked = true;
			}
			if(ed.dom.getAttrib(e, 'class').match(/gutter: false/)){
				f.shtb_adv_insert_linenumbers.checked = false;
			}
			if(ed.dom.getAttrib(e, 'class').match(/first-line:/)){
				f.shtb_adv_insert_starting_linenumber.value = str[2].replace(" first-line: ", "");
			}
			if(ed.dom.getAttrib(e, 'class').match(/highlight:/)){
				f.shtb_adv_insert_highlighted_lines.value = str[3].replace(" highlight: [", "").replace("]", "");
			}
			if(ed.dom.getAttrib(e, 'class').match(/html-script: true/)){
				f.shtb_adv_insert_html_script.checked = true;
			}
			f.insert.value=ed.getLang('update');
		}
	},
};

tinyMCEPopup.onInit.add(SHTBADVINSERTDialog.init, SHTBADVINSERTDialog);
