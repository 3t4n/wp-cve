/**
 * Initiates settings for syntax highlighter for specific DOM elements 
 */
jQuery(document).ready(function() {  
	function editor(id)
	{
	    CodeMirror.fromTextArea(id, {
	        height: "dynamic",
	        continuousScanning: 500,
	        lineNumbers: true,
	        extraKeys: {"Ctrl-Space": "autocomplete"}
	    });
	}
	
	var editor = CodeMirror.fromTextArea(document.getElementById("zia3meta_custom_css"), {
	    lineNumbers: true,
	    height: "dynamic",
	    mode:  "css",
	    extraKeys: {"Ctrl-Space": "autocomplete"}
	});
	
	var editor = CodeMirror.fromTextArea(document.getElementById("zia3meta_custom_js"), {
	    lineNumbers: true,
	    height: "dynamic",
	    mode:  "javascript",
	    extraKeys: {"Ctrl-Space": "autocomplete"}
	});

	
	CodeMirror.commands.autocomplete = function(cm) {
		CodeMirror.showHint(cm, CodeMirror.javascriptHint);
	}
});	