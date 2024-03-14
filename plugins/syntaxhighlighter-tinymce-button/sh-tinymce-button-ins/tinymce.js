function init() {
	tinyMCEPopup.resizeToInnerSize();
}

function getCheckedValue(radioObj) {
	if(!radioObj)
		return "";
	var radioLength = radioObj.length;
	if(radioLength == undefined)
		if(radioObj.checked)
			return radioObj.value;
		else
			return "";
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "";
}

function insertSHTBADVINSERTcode() {

	ed = tinyMCEPopup.editor
	var langname_ddb = document.getElementById('shtb_adv_insert_language');
	var langname = langname_ddb.value;
	var linenumbers = document.getElementById('shtb_adv_insert_linenumbers').checked;
	var starting_linenumber = document.getElementById('shtb_adv_insert_starting_linenumber').value;
	var highlight_lines = document.getElementById('shtb_adv_insert_highlighted_lines').value;
	var html_script = document.getElementById('shtb_adv_insert_html_script').checked;
	var html = tinyMCE.activeEditor.selection.getContent();
	html = html.replace(/<p>/g,"").replace(/<\/p>/g,"<br \/>");

	var tagtext = '<pre class="brush: ';
	classAttribs = langname;

	if (linenumbers)
		classAttribs = classAttribs + '; gutter: true';
	else
		classAttribs = classAttribs + '; gutter: false';

	if (starting_linenumber)
		classAttribs = classAttribs + '; first-line: ' + starting_linenumber;
	else
		classAttribs = classAttribs + '; first-line: 1';

	if (highlight_lines)
		classAttribs = classAttribs + '; highlight: [' + highlight_lines + ']';
	else
		classAttribs = classAttribs;

	if (html_script)
		classAttribs = classAttribs + '; html-script: true';
	else
		classAttribs = classAttribs;

	if(starting_linenumber.match(/[^0-9]+/)){
			alert("Please input number to the 'Starting Line Number' column");
			return;
	} else if(highlight_lines != "" && highlight_lines.match(/[^,0-9]/)){
			alert("Please input a linenumber or comma-separated linenumbers to the 'Highlighted Lines' column");
			return;
	} else if(e = ed.dom.getParent(ed.selection.getNode(), 'pre')){
		ed.dom.setAttribs(e, {class : 'brush: '+classAttribs});
	} else {
	tinyMCEPopup.editor.execCommand('mceInsertContent', false, tagtext+classAttribs+'">'+html+'</pre>');
	}
	tinyMCEPopup.close();
	return;
}
