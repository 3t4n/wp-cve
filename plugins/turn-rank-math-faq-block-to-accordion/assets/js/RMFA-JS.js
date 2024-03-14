jQuery(document).ready(function() {
	
	var rank_math_questions_items = jQuery('#rank-math-faq .rank-math-list-item');
	var rank_math_answer_items = jQuery('#rank-math-faq .rank-math-answer');
	var FAQ_ID;
	
	rank_math_questions_items.each(function( index, rank_math_list_item ) { //Changing Questions Sections
		
		FAQ_ID = jQuery(rank_math_list_item).attr('id');
		
		FAQ_ID = FAQ_ID.split("-")[2];
		
		var FAQ_question_inner_html = jQuery(rank_math_list_item).find('h3').html();
		var FAQ_question_button = jQuery(rank_math_list_item).find('h3').html('<button class="RMFA-quesion-button">'+FAQ_question_inner_html+'</button>');
		
		jQuery(rank_math_list_item).find('button').attr('id', 'accordion-button-'+FAQ_ID);
		jQuery(rank_math_list_item).find('button').attr('aria-controls', 'accordion-panel-'+FAQ_ID);
		jQuery(rank_math_list_item).find('button').attr('aria-expanded', 'false');
		
  	});
	
	rank_math_answer_items.each(function( index, rank_math_answer_item ) { //Changing Answers Sections
		jQuery(rank_math_answer_item).attr('id', 'accordion-panel-'+FAQ_ID);
		jQuery(rank_math_answer_item).attr('aria-labelledby', 'accordion-button-'+FAQ_ID);
	});

	jQuery('#rank-math-faq .rank-math-question').on('click', function() { //Changing on Click Events
		var schema_faq_question = jQuery('#rank-math-faq .rank-math-question');
		schema_faq_question.removeClass('faq-q-open');
		schema_faq_question.siblings('.rank-math-answer').removeClass('faq-a-open').slideUp();
		jQuery(schema_faq_question).find('button').attr('aria-expanded', 'false');
		
		if (jQuery(this).siblings('.rank-math-answer').is(':visible')) {
			jQuery(this).removeClass('faq-q-open');
			jQuery(this).siblings('.rank-math-answer').removeClass('faq-a-open').slideUp();
			jQuery(this).find('button').attr('aria-expanded', 'false');
		} 
		else {
			jQuery(this).addClass('faq-q-open');
			jQuery(this).siblings('.rank-math-answer').addClass('faq-a-open').slideDown();
			jQuery(this).find('button').attr('aria-expanded', 'true');
		}
	});
});