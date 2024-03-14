jQuery(function($) {
	var selecter = '#bulk_term_editor';
	var elm = '<textarea name="terms_plain_text" cols="100" rows="20" type="text" id="fld_terms_plain_text"></textarea>';
	
	bte_get_bte_field = function(taxonomy) {
		$('#bte_field textarea', selecter).addClass('preloader');
		
		$('input.return', selecter).off('click.bte_return').on('click.bte_return', function() {
			bte_get_bte_field(taxonomy);
			return false;
		});
		
		$.ajax({
			url : BTEAjaxObject.ajax_url,
            type : 'POST',
			dataType : 'json',
			data : {
				action : 'bulk_term_editor',
				taxonomy : taxonomy
			},
			async : false
		}).done(function(data){
			$('#bte_field', selecter).html($(elm).html(data)[0]);
		}).fail(function(){
			$('#bte_field', selecter).html($(elm).html('Loading Failed')[0]);
		});
	}
	
	$('select#fld_taxonomy', selecter).change(function() {
		var taxonomy = $(this).val();
		if (taxonomy)
			bte_get_bte_field(taxonomy);
	});
	
	$(document).on('click', selecter + ' input.regex', function() {
		var reg = new RegExp($('input#fld_regex').val(), 'g');
		var textareaObj = $('#bte_field textarea', selecter);
		var html = textareaObj.html();
		textareaObj.html(html.replace(reg, $('input#fld_characters').val()));
		return false;
	});
});