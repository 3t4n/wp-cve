jQuery(document).ready(function($){
    $('#by-file-title').on('click',function() {
		var css = !$(this).is(':checked') ? ['1','initial'] : ['0.4','none'];
		$('#cards_title-wrp').css('opacity',css[0]).css('pointer-events',css[1]);
	});
	$('.back-default').on('click',function(event) {
		if( !$(event.target).is('input')){
			$(this).find('.def-back-card-choice').trigger('click');
		}
		$('.back-default').removeClass('active');
		$(this).addClass('active');
	});
	$('.def-back-card-choice').on('click',function() {
		if($(this).is(':checked')){
			$('.back-default').removeClass('active');
			$(this).closest('.back-default').addClass('active');			
		}
    });	
    $('#eos_cat-image').on('click',function(event) {
		event.preventDefault();
		inputElement = $(this).prev('input');
		uploadID = inputElement;
		cardPreview = $(this).closest('.img_preview');
		frame = wp.media({
			title: eos_cards_genre.upload_img_header,
			button: {
				text: eos_cards_genre.button_text_back
			},
			library: {
				type: ['image']
			},		  
			multiple: false
		});
		frame.on('select',function() {
			var attachment = frame.state().get('selection').first().toJSON();
			cardPreview.find('img').attr('src',attachment.url);
			cardPreview.find('.eos-cards-opt-radio').prop("checked", true);
			cardPreview.removeClass('no-img-preview');
			inputElement.val(attachment.id);
			cardPreview.addClass('active');
		});
		frame.open();
		return false;
    });
    $('.eos-generate-from-imgs').on('click',function(event) {
		$('.eos-cards-notice').addClass('eos-hidden');
		var fromImgsBtn = $(this);
		fromImgsBtn.addClass('eos-cards-in-progress');
		event.preventDefault();
		frame = wp.media({
			title: eos_cards_genre.generate_from_imgs_header,
			button: {
				text: eos_cards_genre.generate_from_imgs_text
			},
			library: {
				type: ['image']
			},		  
			multiple: true
		});
		frame.on('select',function() {
			var attachments = frame.state().get('selection').toJSON(),
				mainDoc = $(window.parent.document),
				ids = [],
				id = '';
			$.each(attachments,function(){
				ids.push(this.id);
			});
			$.ajax({
				type : "POST",
				url : ajaxurl,
				data : {
					"nonce" : mainDoc.find('#eos_cards_creation_nonce').val(),
					"title_by_file" : mainDoc.find('#by-file-title').is(':checked'),
					"new_deck_title" : mainDoc.find('#name').val(),
					"ids" : ids.join(','),
					"action" : "eos_create_cards_from_imgs"
				},
				success : function (response) {
					var generated_cards = parseInt(response);
					if(generated_cards > 0){
						var id = 'cards-msg-success',
							spanN = document.getElementById('eos-assigned-n');
						if(spanN){	
							spanN.innerText = parseInt(spanN.innerText) + generated_cards;
						}
					}
					else{
						var id = 'cards-msg-fail';
					}
					mainDoc.find('.tb-close-icon').trigger('click');
					mainDoc.find('#' + id).removeClass('eos-hidden');
					fromImgsBtn.removeClass('eos-cards-in-progress');
				}
			});
			return false;
		});
		frame.open();		
    });
	$('.taxonomy-decks #submit').on('click',function(){
		var deck_name = $('#tag-name').val();
		if('' !== deck_name){
			$(document).ajaxComplete(function(){
				var errors = $('#ajax-response .error');
				if(errors.length < 1 || !errors.is(':visible')){
					$('.row-title').each(function(){
						if(deck_name === $(this).text()){
							var edit_link = $(this).closest('td').find('.edit a');
							window.location.href= edit_link.attr('href');
						}
					});
				}
			});	
		}
	});
});