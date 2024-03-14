var editor = ace.edit("editor");
editor.setTheme("ace/theme/clouds");
editor.getSession().setMode("ace/mode/css");
editor.gotoLine(0);
var result = false;
var w = false;
if( window.opener) {
	w = window.opener;
}

editor.getSession().on( 'change', function(e) {
  
    if(window.opener) {
    	w.dirty_css = true;
    	w.jQuery("#simpler-css-style").text( editor.getValue() );
    	document.getElementById('msg').innerHTML =  'There are some unsaved changes';
    }
});

editor.commands.addCommand({
    name: 'Save',
    bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
    exec: function(editor) {
    	
    	save_css();
    	
    }
});

function save_css() {
	// show the image 
	
	if( typeof true_save_css != 'undefined' ) {
		document.getElementById('spinner').style.display='inline';
		
		result = true_save_css( editor.getValue() );
		
		window.setTimeout(slowAlert, 1000 );
		
		
	} else {
		
		alert(' Please close the window and start again ');
	
	}
}

function slowAlert(){
	if(  'success' == result.responseText ) {
		console.log(w.dirty_css, w.dirty);
		w.dirty_css = false;
		document.getElementById('msg').innerHTML =  '';
		document.getElementById('spinner').style.display='none';
	}
}


window.onbeforeunload = function() {
	if(w.dirty_css)
		return 'You have unsaved CSS changes.';
	
}

function close_window() {
	window.close();	
}



