jQuery(function($) {
	// Lookup for editor instances
	var editors = [];
	
	/**
	 * Used to set the textarea cursor 
	 */
	function setCursorPosition($el, row, col) {
		// Get the number of lines in the textarea
		var rows = $el.val().split(/\r*\n/);
		var pos = 0;
		
		// Covnvert row / col to textare position
		$.each(rows, function(i, v) {
			if (i < row) {
				pos += v.length+1;
			}else if (i == row) {
				pos += col;
				return;
			}
		});
		
		if ($el[0].setSelectionRange) {
	    	//input.focus();
	    	$el[0].setSelectionRange(pos, pos);
	  	}
	}
		
	$(document).ready(function() {
		var theme = 'monokai';
		var texareas = ['#wpcf7-form', '#wpcf7-mail-body', '#wpcf7-mail-2-body'];
		
		
		$.each(texareas, function(i, v) {
			var textarea = $(v);

			var editDiv = $('<div>', {
	            position: 'absolute',
	            //width: '100%',
	            height: '500px',
	            'class': textarea.attr('class')
	        }).insertBefore(textarea);
	        
	        textarea.hide();

	        var editor = ace.edit(editDiv[0]);
	        editor.renderer.setShowGutter(false);
	        editor.setTheme("ace/theme/"+theme);
	        editor.getSession().setValue(textarea.val());
	        editor.getSession().setMode("ace/mode/html");
	        editor.setShowPrintMargin(false);
	        
	        editDiv[0].style.fontSize = '13px';
	        
	        // Register editor instance
	        editors[v] = editor;

			// copy back to textarea on form submit...
	        textarea.closest('form').submit(function () {
	        	textarea.show();
	            textarea.val(editor.getSession().getValue());
	        });
		});
	});
	
	// Update textarea when the tag editor is opened
	$('#tag-generator-list a').on('click', function() {
		var editor = editors['#wpcf7-form'];
		var textarea = $('#wpcf7-form');
		var position = editor.getCursorPosition();
		
		// Update text area
		textarea.val(editor.getSession().getValue());
		
		// Set cursor
		setCursorPosition(textarea, position.row, position.column);
	});
	
	// Update the editor if the form generator updates the textfield
	$('input.insert-tag').on('click', function(event) {
		var editor = editors['#wpcf7-form'];
		var textarea = $('#wpcf7-form');
		editor.getSession().setValue(textarea.val());
	});
}); 