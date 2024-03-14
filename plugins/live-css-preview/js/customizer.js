jQuery(document).ready(function($){
	var editor = ace.edit("lct-editor");
    editor.setTheme("ace/theme/monokai");
    editor.getSession().setMode("ace/mode/css");
    
	var textarea = $('#dojodigital_live_css-editor').hide();
	editor.getSession().setValue(textarea.val());
	editor.getSession().setUseWrapMode(true);
	editor.getSession().on('change', function(){
  		textarea.val(editor.getSession().getValue()).change();
	});
	
	$('#accordion-section-dojodigital_live_css_section .accordion-section-content').css('padding','0');
	
	var palletteIcon = $('<span class="dashicons dashicons-art"></span>').css('margin-top','2px');
	$('#accordion-section-dojodigital_live_css_section .accordion-section-title').prepend(palletteIcon);
	
});