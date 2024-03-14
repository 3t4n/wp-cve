/*****************************
*********** QUIZZES **********
*****************************/



wpcqData = {
	selector 			: '#zesty-quiz',
	currentQuestion		: 0,
	percentComplete		: 0,
	attemptsRemaining 	: false,
	firstAttempt		: true,
	render 				: 'quiz', // "editor" to render quiz editor
	currentQuestion 	: 0,
	questionType 		: 'multiple-choice',
	started 			: false,
	expanded 			: false,
}

/********** QUIZ MEDIA UPLOADER **********/

var quizMedia = {
	mediaUploader: function(){
		var mediaUploader;
		jQuery('#wpc-quiz-question-img-button').on("click", function(e) {
			//buttonId = this.id;
			//e.preventDefault();
			// If the uploader object has already been created, reopen the dialog
			if (mediaUploader) {
			  mediaUploader.open();
			  return;
			}
			// Extend the wp.media object
			mediaUploader = wp.media.frames.file_frame = wp.media({
			  title: 'Choose Image',
			  button: {
			  text: 'Choose Image'
			}, multiple: false });
			// When a file is selected, grab the URL and set it as the text field's value
			mediaUploader.on('select', function() {
			  	attachment = mediaUploader.state().get('selection').first().toJSON();
			  	jQuery('#wpc-question-img-' + wpcqData.currentQuestion).val(attachment.url);
				quiz.questions[wpcqData.currentQuestion].imageURL = attachment.url;
			});
			// Open the uploader dialog
			mediaUploader.open();
		});
	}
}

/********** QUIZ FUNCTIONS **********/

function wpcqHasCompletedQuiz(quiz){
	for(q=0; q<quiz.questions.length; q++){
		if(quiz.questions[q].submittedAnswer.length < 1 ){
			return false;
		}
	}
	return true;
}

function gradeQuizQuestion(rightAnswer, submittedAnswer, questionType){
	var correct = false;
	if(questionType == 'multiple-answer') {
		for(a=0; a<rightAnswer.length; a++) {
			if(rightAnswer[a] == submittedAnswer[a]){
				correct = true;
			} else {
				correct = false;
				break;
			}
		}
	} else if(questionType == 'multiple-choice'){
		if(rightAnswer === submittedAnswer){
			correct = true;
		}
	}
	return correct;
}

function gradeQuizPercent(quiz){

	var correctAnswerCount = 0;

	for(i=0; i<quiz.questions.length; i++){
		var correctAnswer = quiz.questions[i].correctAnswer;
		var submittedAnswer = quiz.questions[i].submittedAnswer;
		var correct = gradeQuizQuestion(correctAnswer, submittedAnswer, quiz.questions[i].type);
		if(correct == true){
			correctAnswerCount++;
		}
	}

	var result = Math.round((correctAnswerCount / quiz.questions.length) * 100);

	return result;
}

function wpcQuizHasImages(quiz) {
	for(q = 0; q<quiz.questions.length; q++) {
		if(quiz.questions[q].hasOwnProperty('imageURL')){
			if(quiz.questions[q].imageURL.length > 1){
				return true;
			}
		}
	}
	return false;
}

function wpcqStripTags(string){
	let cleanString = string.replace(/(<([^>]+)>)/gi, "");
	return cleanString;
}

function wpcqScrollToSelected(){
	var container = jQuery('.wpc-quiz-question-list');
    var scrollTo = jQuery('.wpc-active-admin-nav-item');

    var position = scrollTo.position().top 
            - container.offset().top 
            + container.scrollTop();

    container.scrollTop(position);
}

/********** QUIZ RENDERING **********/

WPCQ = {

	renderQuizNav : function(view){

		var icon = view === 'editor' ? '<i class="fa-solid fa-grip wpc-grab"></i> ' : '';
		var classWrapper = view === 'editor' ? 'wpc-nav-list-contained' : '';

		renderData = '<ol class="wpc-quiz-question-list wpc-admin-nav-list wpc-nav-list-contained ' + classWrapper + '">';

			for(i=0; i<quiz.questions.length; i++) {

				var thisClass = i == wpcqData.currentQuestion ? 'wpc-active-admin-nav-item' : '';

				if(view === 'editor'){
					var check = '';
					renderData += '<li data-question="' + i + '" class="' + thisClass + '">' + icon + ' ' + check + (quiz.questions[i].question == '' ? '(question is empty)' : wpcqStripTags(quiz.questions[i].question).substring(0, 80) ) + '</li>';
				} else if(view === 'quiz') {
					if(quiz.questions[i].type === 'multiple-choice'){
						var check = quiz.questions[i].submittedAnswer === '' ? '<i class="fa-regular fa-square"></i>' : '<i class="fa fa-check"></i>';
					} else {
						var check = quiz.questions[i].submittedAnswer.length <= 0 ? '<i class="fa-regular fa-square"></i>' : '<i class="fa fa-check"></i>';
					}
					renderData += '<li data-question="' + i + '" class="' + thisClass + '">Question ' + (i + 1) + '</li>';
				}

			}

		renderData += '</ol>';

		return renderData;

	},

	renderQuiz : function(){

		let checked = '';

		if(wpcqData.attemptsRemaining === 0) {
			return '<div class="wpc-error-message wpc-quiz-error-message">' + WPCQuizTranslations.noAttemptsRemaining + '</div>';
		}

		if( quiz.welcomeMessage !== false && wpcqData.started === false ) {
			var welcome = '<h1 class="wpc-quiz-title wpc-h1">' + quiz.title + '</h1><div class="wpc-quiz-message">' + quiz.welcomeMessage + '</div><button class="wpcq-nav-bottom wpcq-start-quiz wpc-btn" data-button="start">' + WPCQuizTranslations.startQuiz + '</button>';
			welcome += wpcqData.attemptsRemaining !== false ? '<div class="wpc-quiz-meta">' + WPCQuizTranslations.attemptsRemaining + ': ' + wpcqData.attemptsRemaining + '</div>' : '';
			welcome += quiz.content;
			return welcome;
		}

		var questionType = quiz.questions[wpcqData.currentQuestion].type;
		var percentComplete = wpcqData.percentComplete;
		percentComplete = Math.round(percentComplete);
					
		wpcqData.currentQuestion = parseInt(wpcqData.currentQuestion, 10);

		if(quiz.questions[wpcqData.currentQuestion].hasOwnProperty('imageURL') && quiz.questions[wpcqData.currentQuestion].imageURL){
			var image = quiz.questions[wpcqData.currentQuestion].imageURL;
			image = '<img src="' + image + '" class="wpc-quiz-img"/>';
		} else {
			var image = '';
		}

		renderData = quiz.showProgress === true ? UI_Components.renderProgressBar(wpcqData.percentComplete, "", "rgb(79, 100, 109)", 'wpc-quiz-progress-inner') : '';

		renderData += wpcqData.currentQuestion != 0 ? '<button class="wpcq-nav wpcq-nav-top wpcq-nav-prev wpc-btn" data-button="prev"><i class="fa fa-arrow-left"></i> ' + WPCQuizTranslations.prevQuestion + '</button>' : '';
		renderData += wpcqData.currentQuestion < quiz.questions.length - 1 ? '<button class="wpcq-nav wpcq-nav-top wpcq-nav-next wpc-btn" data-button="next">' + WPCQuizTranslations.nextQuestion + ' <i class="fa fa-arrow-right"></i> </button>' : '';
		renderData += '<button class="wpcq-nav wpcq-nav-top wpcq-submit-quiz wpc-btn wpc-btn-solid" data-button="submit">' + WPCQuizTranslations.submitQuiz + '</button>';

		renderData += '<div style="clear:both;"></div>';

		renderData += '<div class="wpc-flex-container wpc-fe-quiz-question-wrapper">';
		
			renderData += '<div class="wpc-flex-content wpc-flex-content-left wpc-fe-quiz-question">';

				renderData += '<h3>' + WPCQuizTranslations.question + ' ' + (wpcqData.currentQuestion + 1) + '</h3>' + image + '<p>' + quiz.questions[wpcqData.currentQuestion].question + '</p>';

				for(a=0; a<quiz.questions[wpcqData.currentQuestion].answers.length; a++){

					if(questionType == 'multiple-answer'){
						var type = 'checkbox';
						if(quiz.questions[wpcqData.currentQuestion].submittedAnswer.length > 0){
							checked = quiz.questions[wpcqData.currentQuestion].submittedAnswer.includes(a.toString()) && quiz.questions[wpcqData.currentQuestion].submittedAnswer != '' ? 'checked' : '';

						}
					} else if (questionType == 'multiple-choice') {		
						var type = 'radio';
						checked = quiz.questions[wpcqData.currentQuestion].submittedAnswer == a && quiz.questions[wpcqData.currentQuestion].submittedAnswer != '' ? 'checked' : '';
					}
				
					renderData += '<div class="wpc-quiz-answers">';
						renderData += '<div class="wpc-single-answer"><input id="label-id-' + a + '" type="' + type + '" class="wpc-selected-answer" value="' + a + '" name="selected-answer-' + wpcqData.currentQuestion + '" data-question-id="' + wpcqData.currentQuestion + '" ' + checked + '/><label class="wpc-label wpc-metabox-label" for="label-id-' + a + '" data-answer-id="' + a + '" data-question-id="' + wpcqData.currentQuestion + '">' + quiz.questions[wpcqData.currentQuestion].answers[a] + '</label></div>';
					renderData += '</div>';
				}

			renderData += '</div>'; // end wpc-flex-content

			renderData += '<div class="wpc-flex-sidebar">' + WPCQ.renderQuizNav('quiz') + '</div>';

		renderData += '</div>';

		renderData += '<div style="clear:both;"></div>';

		if (wpcqData.attemptsRemaining !== false) {
			var text =  WPCQuizTranslations.attemptsRemaining + ': ' + wpcqData.attemptsRemaining;

			renderData += '<div class="wpc-quiz-meta">';
			renderData += text;
			renderData += '</div>';
		}

		return renderData;

	},

	/*args = {
		showRestart : true,
	}*/

	renderResults : function(quiz, args = {}) {

		var hasImages = wpcQuizHasImages(quiz);

		args.hasOwnProperty('showRestart') ? args.showRestart = args.showRestart : args.showRestart = true;
		args.hasOwnProperty('showBackToResultsButton') ? args.showBackToResultsButton = args.showBackToResultsButton : args.showBackToResultsButton = true;

		var multipleAnswerChoices = '';
		var multipleAnswerSubmitted = '';
		var percent = gradeQuizPercent(quiz);

		let renderData = args.showBackToResultsButton === true ? '<button class="wpc-btn wpc-btn-sm wpc-btn-nav wpc-load-profile wpc-load-profile-part-pagination" data-page="1" data-name="quiz-results" data-title="' + WPCQuizTranslations.quizResults + '"><i class="fa fa-arrow-left"></i> ' + WPCQuizTranslations.back + '</button>' : '';

		renderData += quiz.showScore === true ? '<div style="margin-bottom: 20px">' + UI_Components.renderProgressBar(percent, text="", color="#4f646d") + '</div>' : '';

		renderData += '<table class="wpc-table">';
		renderData += '<thead><tr>';

		renderData += quiz.showAnswers === true ? '<th></th>' : '';
		renderData += hasImages === true ? '<th>Image</th>' : '';
		renderData += '<th>' + WPCQuizTranslations.question + '</th>';
		renderData += '<th>' + WPCQuizTranslations.yourAnswer + '</th>'
		renderData += quiz.showAnswers === true ? '<th>' + WPCQuizTranslations.correctAnswer + '</th>' : '';

		renderData += '</tr><thead>';

		renderData += '<tbody>';

		for(i=0; i<quiz.questions.length; i++){

			if(quiz.questions[i].hasOwnProperty('imageURL') && quiz.questions[i].imageURL){
				var image = quiz.questions[i].imageURL;
				image = '<div class="wpc-quiz-question-wrapper"><img src="' + image + '" class="wpc-quiz-question-img"/></div>';
			} else {
				var image = '';
			}

			var answer = parseInt(quiz.questions[i].correctAnswer, 10);
			var submittedAnswer = parseInt(quiz.questions[i].submittedAnswer, 10);

			if(quiz.questions[i].type === 'multiple-answer'){
				for(a=0; a<quiz.questions[i].correctAnswer.length; a++) {
					if(quiz.questions[i].correctAnswer[a] == quiz.questions[i].submittedAnswer[a]){
						var iconClass = 'fa-solid fa-circle-check wpc-correct-i';
					} else {
						var iconClass = 'fa-solid fa-circle-xmark wpc-incorrect-i';
						break;
					}
				}
				multipleAnswerChoices = '<ul>';
				for(a=0; a<quiz.questions[i].correctAnswer.length; a++) {
					multipleAnswerChoices += '<li>' + quiz.questions[i].answers[parseInt(quiz.questions[i].correctAnswer[a])] + '</li>';
				}
				multipleAnswerChoices += '</ul>';

				multipleAnswerSubmitted = '<ul>';
				for(a=0; a<quiz.questions[i].submittedAnswer.length; a++) {
					multipleAnswerSubmitted += '<li>' + quiz.questions[i].answers[parseInt(quiz.questions[i].submittedAnswer[a])] + '</li>';
				}
				multipleAnswerSubmitted += '</ul>';

				renderData += '<tr>';
					renderData += quiz.showAnswers === true ? '<td><i class="' + iconClass + '"></i></td>' : '';
					renderData += hasImages === true ? '<td>' + image + '</td>' : '';
					renderData += '<td>' + quiz.questions[i].question + '</td>';
					renderData += '<td>';
						renderData += typeof multipleAnswerSubmitted !== 'undefined' ? multipleAnswerSubmitted : '';
					renderData += '</td>';
					renderData += quiz.showAnswers === true ? '<td>' +  multipleAnswerChoices + '</td>' : '';
				renderData += '</tr>';

			} else if(quiz.questions[i].type === 'multiple-choice'){
				if(answer == submittedAnswer){
					var iconClass = 'fa-solid fa-circle-check wpc-correct-i';
				} else {
					var iconClass = 'fa-solid fa-circle-xmark wpc-incorrect-i';
				}
				renderData += '<tr>';
					renderData += quiz.showAnswers === true ? '<td><i class="' + iconClass + '"></i></td>' : '';
					renderData += hasImages === true ? '<td>' + image + '</td>' : '';
					renderData += '<td>' + quiz.questions[i].question + '</td>';
					renderData += '<td>';
						renderData += typeof quiz.questions[i].answers[submittedAnswer] !== 'undefined' ? quiz.questions[i].answers[submittedAnswer] : '';
					renderData += '</td>';
					renderData += quiz.showAnswers === true ? '<td>' +  quiz.questions[i].answers[answer] + '</td>' : '';
				renderData += '</tr>';
			}
		}

		renderData += '</tbody>';
		renderData += '</table>';

		if(args.showRestart === true) {
			renderData += '<button class="wpcq-nav-bottom wpcq-restart-quiz wpc-btn" data-button="restart">' + WPCQuizTranslations.restartQuiz + '</button>';
		}
		
		return renderData;
	},

	renderEditor : function(){

		var checked = '';
		var questionType = quiz.questions[wpcqData.currentQuestion].type;

		jQuery('#wpcq-question-type-select').val(questionType);

		var image = quiz.questions[wpcqData.currentQuestion].hasOwnProperty('imageURL') ? quiz.questions[wpcqData.currentQuestion].imageURL : '';

		renderData = '<div class="wpc-flex-container wpc-metabox-container">';
			renderData += '<div class="wpc-quiz-question wpc-flex-content wpc-metabox-content" id="wpc-question-text-' + wpcqData.currentQuestion + '">';
			renderData += '<label class="wpc-label wpc-metabox-label">Image URL</label><br><input disabled class="wpc-input wpc-input-fw wpc-question-img" type="text" value="' + image + '" placeholder="Image URL" data-question-id="' + wpcqData.currentQuestion + '" id="wpc-question-img-' + wpcqData.currentQuestion + '"/><br>';
			renderData += '<label class="wpc-label wpc-metabox-label">Question ' + (wpcqData.currentQuestion + 1) + ':</label><textarea class="wpc-textarea wpc-question-text" data-question-id="' + wpcqData.currentQuestion + '">' + quiz.questions[wpcqData.currentQuestion].question + '</textarea>';
			renderData += '<div class="wpc-answers"><label class="wpc-label wpc-metabox-label">' + WPCQuizTranslations.answers + ':</label>';

			for(a=0; a<quiz.questions[wpcqData.currentQuestion].answers.length; a++){

				if(questionType == 'multiple-answer'){
					var type = 'checkbox';
					if (typeof quiz.questions[wpcqData.currentQuestion].correctAnswer !== 'undefined') {
						checked = quiz.questions[wpcqData.currentQuestion].correctAnswer.includes(a.toString()) ? 'checked' : '';
					} else {
						checked = '';
					}
				} else if (questionType == 'multiple-choice') {		
					var type = 'radio';
					checked = quiz.questions[wpcqData.currentQuestion].correctAnswer == a ? 'checked' : '';
				}

				renderData += '<div class="wpc-metabox-item">';
					renderData += '<input class="wpc-' + type + ' wpc-correct-answer" type="' + type  + '" value="' + a + '" name="correct-answer-' + wpcqData.currentQuestion + '" data-question-id="' + wpcqData.currentQuestion + '"' + checked + '/><input data-answer-id="' + a + '" data-question-id="' + wpcqData.currentQuestion + '" type="text" class="wpc-answer wpc-input" value="' + quiz.questions[wpcqData.currentQuestion].answers[a] + '"/><button class="wpc-btn wpc-btn-sm wpc-btn-input-action wpc-remove-answer" data-answer-id="' + a + '" data-question-id="' + wpcqData.currentQuestion + '"><i class="fa fa-trash"></i></button><br>';
				renderData += '</div>';

			}

			//renderData += '</div>'; // answers

			renderData += '<div class="wpc-metabox-item"><button id="wpc-add-answer" class="wpc-btn" data-answer-id="' + a + '" data-question-id="' + wpcqData.currentQuestion + '"><i class="fa fa-plus"></i> ' + WPCQuizTranslations.addAnswer + '</button></div>';

			renderData += '</div></div>'; // quiz

			renderData += '<div class="wpc-flex-sidebar wpc-metabox-content">';
				renderData += WPCQ.renderQuizNav('editor');
			renderData += '</div>'; // wpc-flex-sidebar

		renderData += '</div>'; // wpc-flex-content

		if(quiz.questions.length > 1) {
			jQuery("#wpc-delete-question").removeAttr('disabled');
		} else {
			jQuery("#wpc-delete-question").attr('disabled', 'disabled');
		}

		return renderData;
	},

	renderError : function(){
		return '<div class="wpc-error-message">' + wpcqData.errorMessage + '</div><button class="wpcq-nav-bottom wpcq-continue-quiz wpc-btn" data-button="continue">' + WPCQuizTranslations.continue + '</button>';
	},

	renderSubmit : function(){
		return '<div class="wpc-alert-message">' + wpcqData.errorMessage + '</div><button class="wpcq-nav-bottom wpcq-continue-quiz wpc-btn" style="margin-right: 10px;" data-button="continue">' + WPCQuizTranslations.backToQuiz + '</button><button class="wpcq-nav-bottom wpcq-final-submit-quiz wpc-btn" data-button="final-submit">' + WPCQuizTranslations.submitQuiz + '</button>';
	},

	render : function(){

		if(wpcqData.render === 'quiz') {
			var toRender = this.renderQuiz();
		} else if(wpcqData.render === 'editor') {
			var toRender = this.renderEditor();
		} else if(wpcqData.render === 'results') {
			var toRender = this.renderResults(quiz);
		} else if(wpcqData.render === 'error') {
			var toRender = this.renderError();
		} else if(wpcqData.render === 'submit') {
			var toRender = this.renderSubmit();
		}

		if(wpcqData.selector.length > 1) {
			jQuery(wpcqData.selector).html(toRender);
		}
	},

	transitionContent : function(){
		jQuery('.wpc-fe-quiz-question').css({
			opacity 		: 0,
		});

		jQuery('.wpc-fe-quiz-question').fadeTo(1000, 1);
	},

	smoothRender : function(timeSeconds = 1000, loader = '<span class="gauge-loader">Loading&#8230;</span>'){
		UI_Components.loader('#wpc-fe-quiz-container');

		setTimeout(function(){
			jQuery('#wpc-fe-quiz-container').fadeTo(timeSeconds / 2, 0);
		}, timeSeconds / 2);

		setTimeout(function(){

			WPCQ.render();

			jQuery('#wpc-fe-quiz-container').css({
				opacity 	: 0,
			});

			jQuery('#wpc-fe-quiz-container').fadeTo(timeSeconds / 2, 1);

			UI_Controller.animateProgressBar(0, gradeQuizPercent(quiz), '#wpc-fe-quiz-container .wpc-progress-inner');

		}, timeSeconds);
	}

}

/********** QUIZ CONTROLLER **********/

// Call once to instantiate all event listeners and events that happen when they're triggered
class WPCQ_Controller {

	constructor() {

		var allEvents = this.events;

		jQuery.each(this.events, function(key, val){
			var event = jQuery(this)[0].event;
			var selector = jQuery(this)[0].selector;

			jQuery(document).on(event, selector, function(e){

				allEvents[key].obj = jQuery(this);
				allEvents[key].logic();
				allEvents[key].e = e;
				
			});
		});

	}

	static loadQuiz(quizID, courseID){
		wpcd.currentQuestion = 0;
		wpcd.percentComplete = 0;
		wpcd.state.view = 'single-quiz';

		var data = {
			'security'	: wpc_ajax.nonce,
			'action'	: 'wpc_get_quiz',
			'id'		: quizID,
			'course_id'	: courseID,
			'user_id'	: wpcd.user.ID,
		};

		$('.wpc-lightbox-wrapper').fadeOut();
		$('#wpc-content').hide();
		UI_Components.loader('#wpc-content');
		$('#wpc-content').fadeIn();
		$('#wpc-toolbar-bottom').hide();

		$.post(ajaxurl, data, function(response) {

			wpcPushState({
				view 		: 'single-quiz',
				course_id 	: courseID,
				lesson_id 	: quizID,
				search 		: null,

			});

			$('#wpc-content').css('opacity', 0);

			var json = JSON.parse(response);

			let args = {
				quiz 				: json.quiz,
				selector 			: '#wpc-fe-quiz-container',
				maxAttempts 		: json.max_attempts,
				content 			: json.content,
				title 				: json.title,
				attemptsRemaining	: json.attempts_remaining,
				showScore 	 		: json.show_score,
				showAnswers 		: json.show_answers,
				allowEmptyAnswers	: json.allow_empty_answers,
				showProgress 		: json.show_progress,
				firstAttempt 		: json.first_attempt,
				render 				: 'quiz',
				welcomeMessage 		: json.welcome_message,
				userID 				: json.user_id,
				quizID 				: json.quiz_id,
				courseID 			: json.course_id,
			}

			$('#wpc-content').html('<div class="wpc-flex-container"><div class="wpc-flex-12 wpc-flex-no-margin"><div id="wpc-material-content" class="wpc-material wpc-material-content">' + json.content + '</div></div></div>');

			WPCQInit(args);

			$('#wpc-content').fadeTo(1000, 1);

			/* LessonUI.lesson = json;
			toolbar = LessonUI.renderToolbar();
			$('#wpc-toolbar-top').html(toolbar); */

			let currentLi = $('.wpc-nav-list li[data-id=' + quizID + ']');
			currentLi.children('i').remove();
			currentLi.prepend(json.icon);

			if(currentLi.children('i').hasClass('fa-play') || currentLi.children('i').hasClass('fa-eye')){
				currentLi.addClass('wpc-nav-item-highlight');
			}

			UI_Controller.animateProgressBar();

		});
	}

	events = [
		{
			event 		: 'click',
			selector 	: '.wpcq-continue-quiz',
			obj 		: null,
			logic 		: function(){

				wpcqData.render = 'quiz';
				WPCQ.render();
				UI_Controller.animateProgressBar(0, wpcqData.percentComplete, '.wpc-quiz-progress-inner');

			}
		},
		{
			event 		: 'click',
			selector 	: '.wpcq-nav',
			obj 		: null,
			logic 		: function(){

				// increment question
				if(this.obj.data('button') === 'next') {
					wpcqData.currentQuestion = wpcqData.currentQuestion + 1;
				} else if(this.obj.data('button') === 'prev') {
					wpcqData.currentQuestion = wpcqData.currentQuestion - 1;
				}

				var prevPercent = wpcqData.percentComplete;
				wpcqData.percentComplete = (parseInt(wpcqData.currentQuestion) / parseInt(quiz.questions.length)) * 100;
				wpcqData.percentComplete = Math.round(wpcqData.percentComplete);

				WPCQ.render();
				WPCQ.transitionContent();

				UI_Controller.animateProgressBar(prevPercent, wpcqData.percentComplete, '.wpc-quiz-progress-inner');

			}
		},
		{
			event 		: 'click',
			selector 	: '.wpcq-start-quiz',
			obj 		: null,
			logic 		: function(){

				wpcqData.currentQuestion = 0;
				wpcqData.render = 'quiz';
				wpcqData.started = true;

				WPCQ.smoothRender(2000);

			}
		},
		{
			event 		: 'click',
			selector 	: '.wpcq-submit-quiz',
			obj 		: null,
			logic 		: function(){

				var completed = wpcqHasCompletedQuiz(quiz);

				if(quiz.allowEmptyAnswers === true && completed === false) { 
					wpcqData.errorMessage = WPCQuizTranslations.emptyAnswers;
					wpcqData.render = 'submit';
				} else if(quiz.allowEmptyAnswers === true && completed === true || quiz.allowEmptyAnswers === false && completed === true) {
					wpcqData.errorMessage = WPCQuizTranslations.areYouSure;
					wpcqData.render = 'submit';
				} else if(completed === false) {
					wpcqData.errorMessage = WPCQuizTranslations.answerAllQuestions;
					wpcqData.render = 'error';
				}

				WPCQ.smoothRender(1000);

			}
		},
		{
			event 		: 'click',
			selector 	: '.wpcq-final-submit-quiz',
			obj 		: null,
			logic 		: function(){
				wpcqData.render = 'results';
				WPCQ.smoothRender(3000);
			}
		},
		{
			event 		: 'click',
			selector 	: '.wpcq-restart-quiz',
			obj 		: null,
			logic 		: function(){

				// clear answers
				for(i=0; i<quiz.questions.length; i++){
					quiz.questions[i].submittedAnswer = '';
				}

				if(wpcqData.attemptsRemaining !== false && wpcqData.attemptsRemaining > 0) {
					wpcqData.attemptsRemaining = wpcqData.attemptsRemaining - 1;
				}

				wpcqData.percentComplete = 0;
				wpcqData.currentQuestion = 0;
				wpcqData.render = 'quiz';
				WPCQ.smoothRender(2000);

			}

		},
		{
			event 		: 'click',
			selector 	: '#wpc-add-question',
			obj 		: null,
			logic 		: function(){

				quiz.questions.push(		
					{
						type: 'multiple-choice',
						question: '',
						imageURL: '',
						correctAnswer: 0,
						submittedAnswer: '',
						answers: [
							'',
							'',
							'',
							''
						],
					},
				);

				wpcqData.currentQuestion = quiz.questions.length - 1;
				
				WPCQ.render();
				wpcqScrollToSelected();

			}
		}, 
		{
			event 		: 'mousedown',
			selector 	: '.wpc-quiz-question-list li',
			obj 		: null,
			logic 		: function(){

				jQuery('.wpc-fe-quiz-question-container').fadeTo(1000, 0);

				wpcqData.currentQuestion = this.obj.data('question');

				jQuery(this.selector).removeClass('mm');
				this.obj.addClass('wpc-active-admin-nav-item');

			}
		},
		{
			event 		: 'click',
			selector 	: '#wpc-delete-question',
			obj 		: null,
			logic 		: function(){

				quiz.questions.splice(wpcqData.currentQuestion, 1);
				wpcqData.currentQuestion = wpcqData.currentQuestion != 0 ? wpcqData.currentQuestion - 1 : 0;

				WPCQ.render();
				wpcqScrollToSelected();

			}
		},
		{
			event 		: 'click',
			selector 	: '.wpc-remove-answer',
			obj 		: null,
			logic 		: function(){

				var questionID = this.obj.data('question-id');
				var answerID = this.obj.data('answer-id');

				if(quiz.questions[questionID].answers.length < 3){
					alert(WPCQuizTranslations.twoAnswers);
				} else {
					quiz.questions[questionID].answers.splice(answerID, 1);			
				}

				WPCQ.render();

			}
		},
		{
			event 		: 'click',
			selector 	: '#wpc-add-answer',
			obj 		: null,
			logic 		: function(){

				var questionID = this.obj.data('question-id');

				if(quiz.questions[questionID].answers.length > 12){
					alert(WPCQuizTranslations.twelveAnswers);
				} else {
					quiz.questions[questionID].answers.push('');
				}

				WPCQ.render();

			}
		},
		{
			event 		: 'keyup',
			selector 	: '.wpc-question-text',
			obj 		: null,
			logic 		: function(){

				var questionID = this.obj.data('question-id');
				quiz.questions[questionID].question = this.obj.val();

				jQuery(".wpc-quiz-question-list li[data-question=" + wpcqData.currentQuestion + "]").html('<i class="fa fa-bars wpc-grab"></i>' +  wpcqStripTags(quiz.questions[questionID].question).substring(0, 80) );

			}
		},
		{
			event 		: 'keyup',
			selector 	: '.wpc-question-img',
			obj 		: null,
			logic 		: function(){

				var questionID = this.obj.data('question-id');
				quiz.questions[questionID].imageURL = this.obj.val();

			}
		},
		{
			event 		: 'keyup',
			selector 	: '.wpc-question-img',
			obj 		: null,
			logic 		: function(){

				var questionID = this.obj.data('question-id');
				quiz.questions[questionID].imageURL = this.obj.val();

			}
		},
		{
			event 		: 'click',
			selector 	: '.wpc-correct-answer',
			obj 		: null,
			logic 		: function(){

				var questionID = wpcqData.currentQuestion;
				var radioValue = this.obj.val();

				if(quiz.questions[wpcqData.currentQuestion].type == 'multiple-answer') {

					var allAnswers = [];
					var radioValue = jQuery(".wpc-correct-answer:checked").each(function(){
					    allAnswers.push( jQuery(this).val() );
					});

					quiz.questions[questionID].correctAnswer = allAnswers;

				} else {
					quiz.questions[questionID].correctAnswer = radioValue;
				}

			}
		},
		{
			event 		: 'click',
			selector 	: '.wpc-selected-answer',
			obj 		: null,
			logic 		: function(){

				var questionID = wpcqData.currentQuestion;
				var radioValue = this.obj.val();

				if(quiz.questions[questionID].type == 'multiple-answer') {

					var allAnswers = [];

					var radioValue = jQuery(".wpc-selected-answer:checked").each(function(){
					    allAnswers.push( jQuery(this).val() );
					});

					quiz.questions[questionID].submittedAnswer = allAnswers;


					if(quiz.questions[questionID].submittedAnswer.length > 0) {
						jQuery('.wpc-quiz-question-list li[data-question=' + wpcqData.currentQuestion + '] i').removeClass('fa-regular fa-square').addClass('fa fa-check');
					} else {
						jQuery('.wpc-quiz-question-list li[data-question=' + wpcqData.currentQuestion + '] i').removeClass('fa fa-check').addClass('fa-regular fa-square');
					}
					

				} else {
					quiz.questions[questionID].submittedAnswer = radioValue;
					if(quiz.questions[questionID].submittedAnswer !== ''){
						jQuery('.wpc-quiz-question-list li[data-question=' + wpcqData.currentQuestion + '] i').removeClass('fa-regular fa-square').addClass('fa fa-check');
					} else {
						jQuery('.wpc-quiz-question-list li[data-question=' + wpcqData.currentQuestion + '] i').removeClass('fa fa-check').addClass('fa-regular fa-square');
					}
				}

			}
		},
		{
			event 		: 'keyup',
			selector 	: '.wpc-answer',
			obj 		: null,
			logic 		: function(){

				var questionID = this.obj.data('question-id');
				var answerID = this.obj.data('answer-id');

				quiz.questions[questionID].answers[answerID] = this.obj.val();

			}
		},
		{
			event 		: 'change',
			selector 	: '#wpcq-question-type-select',
			obj 		: null,
			logic 		: function(){

				var questionType = this.obj.val();

				wpcqData.questionType = this.obj.val();

				if(questionType == 'multiple-answer') {
					quiz.questions[wpcqData.currentQuestion].correctAnswer = [];
				} else if(questionType == 'multiple-choice') {
					quiz.questions[wpcqData.currentQuestion].correctAnswer = 0;
				}

				quiz.questions[wpcqData.currentQuestion].type = questionType;

				WPCQ.render();

			}
		},
		/********** SORTING **********/
		{
			event 		: 'mousedown',
			selector 	: '.wpc-quiz-question-list .wpc-grab',
			obj 		: null,
			e 			: null,
			logic 		: function(){

				wpcqData.grab = true;
				wpcqData.grabbedID = this.obj.parent().data('question');
				wpcqData.grabbedElement = this.obj.parent();

			}
		},
		/********** SORTING **********/
		{
			event 		: 'mouseup',
			selector 	: '.wpc-quiz-question-list li',
			obj 		: null,
			e 			: null,
			logic 		: function(){

				if(wpcqData.mouseOverID !== null && wpcqData.grab === true){

					var moved = JSON.parse(JSON.stringify(quiz.questions[wpcqData.grabbedID]));
					quiz.questions.splice(wpcqData.grabbedID, 1);
					quiz.questions.splice(wpcqData.mouseOverID, 0, moved);
					
				}

				wpcqData.grab = false;
				wpcqData.grabbedElement = null;

				var prevPercent = wpcqData.percentComplete;
				wpcqData.percentComplete = (parseInt(wpcqData.currentQuestion) / parseInt(quiz.questions.length)) * 100;

				WPCQ.render();

				jQuery('.wpc-fe-quiz-question-container').css({
					opacity 		: 0,
				});

				jQuery('.wpc-fe-quiz-question-container').fadeTo(1000, 1);

				UI_Controller.animateProgressBar(prevPercent, wpcqData.percentComplete, '.wpc-quiz-progress-inner');

				wpcqScrollToSelected();
				
			}
		},
		/********** SORTING **********/
		{
			event 		: 'mousemove',
			selector 	: 'body',
			obj 		: null,
			e 			: null,
			logic 		: function(){

				if( this.e != null && wpcqData.grab === true ) {

					var width = jQuery('.wpc-quiz-question-list li').first().width();
					var left = jQuery('.wpc-quiz-question-list li').offset().left;

					wpcqData.mouseY = this.e.pageY;
					wpcqData.mouseX = this.e.pageX;

					var li = jQuery('.wpc-quiz-question-list li').not(".wpc-quiz-question-list li[data-question=" + wpcqData.grabbedID + "]");

					jQuery('.wpc-sorting-block').remove();

					jQuery.each(li, function(key, val){

						var offset = jQuery(this).offset();
						var height = jQuery(this).outerHeight();
						var offsetY = offset.top;
						var offsetYBottom = offset.top + height;

						if(wpcqData.mouseY > offsetY && wpcqData.mouseY < offsetYBottom){

							wpcqData.mouseOverID = key;

							if(wpcqData.mouseOverID < wpcqData.grabbedID){
								wpcqData.mouseOverID = key;
								wpcqData.currentQuestion = key;
								jQuery(this).before('<div class="wpc-sorting-block"></div>');
							} else {
								wpcqData.mouseOverID = key + 1;
								wpcqData.currentQuestion = key + 1;
								jQuery(this).after('<div class="wpc-sorting-block"></div>');
							}
							
							return false;

						}

					});	

					jQuery(".wpc-quiz-question-list li[data-question=" + wpcqData.grabbedID + "]").css({
						position 	: 'fixed',
						top 		: (wpcqData.mouseY - jQuery(window).scrollTop()) - (jQuery('.wpc-quiz-question-list li').first().outerHeight() / 2),
						left 		: left + 'px',
						width 		: width + 'px',
						opacity 	: 0.8,
					});

					var list = jQuery('.wpc-quiz-question-list');
					var listHeight = list.outerHeight();
					var listOffset = list.offset();
					var listTop = listOffset.top;
					var listBottom = listOffset.top + listHeight;

					var scrollTo = jQuery('.wpc-active-admin-nav-item');
					if(wpcqData.mouseY > listTop && wpcqData.mouseY < listTop + (listHeight * 0.2)){
					    list.scrollTop(list.scrollTop() - 15 );
					} else if(wpcqData.mouseY < listBottom && wpcqData.mouseY > (listBottom - (listHeight * 0.2))){
						list.scrollTop(list.scrollTop() + 15 );
					}

				}
				
			}
		},
		/********** AJAX GET QUIZ RESULT TABLE **********/
		{
			event 		: 'click',
			selector 	: '.wpc-load-quiz-result',
			obj 		: null,
			e 			: null,
			logic 		: function(){

				var showScore = this.obj.data('score');
				var showAnswers = this.obj.data('answers');;

				var data = {
					'security'	: wpc_ajax.nonce,
					'action'		: 'wpcq_get_quiz_result',
					'resultID' 		: this.obj.data('id'),
				};

				jQuery('.wpc-lightbox-content').hide();
				UI_Components.loader('.wpc-lightbox-content');
				jQuery('.wpc-lightbox-content').fadeIn();
				
				jQuery.post(ajaxurl, data, function(response) {

					let quiz = JSON.parse( response );

					//var quiz = JSON.parse(response);

					quiz.showScore = showScore;
					quiz.showAnswers = showAnswers;

					var args = {
						showRestart 			: false,
						showBackToResultsButton : false,
					}

					var html = WPCQ.renderResults(quiz, args); 
					jQuery('.wpc-lightbox-title').html('Quiz Result');
					jQuery('.wpc-lightbox-content').html(html);
					jQuery('.wpc-lightbox-wrapper').fadeIn();

					UI_Controller.animateProgressBar(0, gradeQuizPercent(quiz), '.wpc-lightbox-content .wpc-progress-inner');

				});

			}
		},
		/********** AJAX SAVE QUIZ RESULT **********/
		{
			event 		: 'click',
			selector 	: '.wpcq-final-submit-quiz',
			obj 		: null,
			e 			: null,
			logic 		: function(){

				var data = {
					'security'	: wpc_ajax.nonce,
					'action'		: 'wpcq_save_quiz_results_action',
					'quiz'			: quiz,
					'scorePercent'	: gradeQuizPercent(quiz),
					'userID' 		: wpcqData.userID,
					'quizID' 		: wpcqData.quizID,
					'courseID'		: wpcqData.courseID,
				};
				
				jQuery.post(ajaxurl, data, function(response) {
					AwardsUI.getAwards();
				});

			}
		},
		/********** AJAX SAVE QUIZ BACK-END **********/
		{
			event 		: 'click',
			selector 	: '#wpc-save-question, .post-type-wpc-quiz #save-post, .post-type-wpc-quiz #publish', // "Save Quiz", "Save draft", "Publish" button for quizzes
			obj 		: null,
			e 			: null,
			logic 		: function(event){

				var data = {
					'security'	: wpc_ajax.nonce,
					'action': 'wpcq_save_quiz_action',
					'quiz': quiz,
					'quizID': wpcqData.quizID,
				};

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function(response) {
					wpcHideAjaxIcon();
				});
			}

		},
		/********** AJAX LOAD QUIZ FRONT-END **********/
		{
			event 		: 'click',
			selector	: '.wpc-load-quiz',
			logic 		: function(){
				WPCQ_Controller.loadQuiz(this.obj.data('id'), this.obj.data('course-id'));
			}
		}
	]
}

jQuery(document).ready(function(){
	quizMedia.mediaUploader(); // Initialize logic for "Add Image" button

	new WPCQ_Controller(); // Delay call until dom ready to avoid jQuery does not exist error
});




/********** QUIZ INIT **********/



/*

args = {
	selector 				: '#wpc-fe-quiz-container',
	quiz 					: {}, // quiz json
	attemptsRemaining		: 9, // false for unlimited
	showScore				: true,
	showAnswers 			: true,
	showProgress			: true,
	allowEmptyAnswers		: true,
	firstAttempt 			: true,
	render 					: 'quiz' // or "editor"
	welcomeMessage 			: false,
	completeBeforeSubmit	: false, // user must fill in all answers before submit
	showListNav 			: true,
	userID 					: null,
	quizID 					: null,
	courseID 				: null,
	title 					: (string),
	content 				: (string), // used for displaying content restriction messages
}

*/

function WPCQInit(args) {

	wpcqData.selector = args.hasOwnProperty('selector') ? args.selector : '#zesty-quiz';
	wpcqData.attemptsRemaining = args.hasOwnProperty('attemptsRemaining') ? args.attemptsRemaining : false;
	wpcqData.firstAttempt = args.hasOwnProperty('firstAttempt') ? args.firstAttempt : true;
	wpcqData.render = args.hasOwnProperty('render') ? args.render : 'quiz';
	wpcqData.userID = args.hasOwnProperty('userID') ? args.userID : null;
	wpcqData.quizID = args.hasOwnProperty('quizID') ? args.quizID : null;
	wpcqData.courseID = args.hasOwnProperty('courseID') ? args.courseID : null;

	if(args.quiz === null) {
		quiz = {
			status: 'published',
			currentQuestion: 0,
			questions: [
				{
					type: 'multiple-choice',
					question: '',
					imageURL: '',
					correctAnswer: 0,
					submittedAnswer: '',
					answers: [
						'',
						'',
						'',
						''
					],
				},
			],
		}
	} else {
		quiz = args.quiz;
	}

	quiz.showAnswers = args.hasOwnProperty('showAnswers') ? args.showAnswers : true;
	quiz.showScore = args.hasOwnProperty('showScore') ? args.showScore : true;
	quiz.showProgress = args.hasOwnProperty('showProgress') ? args.showProgress : true;
	quiz.allowEmptyAnswers = args.hasOwnProperty('allowEmptyAnswers') ? args.allowEmptyAnswers : true;
	quiz.completeBeforeSubmit = args.hasOwnProperty('completeBeforeSubmit') ? args.completeBeforeSubmit : false;
	quiz.showListNav = args.hasOwnProperty('showListNav') ? args.showListNav : true;
	quiz.welcomeMessage = args.hasOwnProperty('welcomeMessage') ? args.welcomeMessage : false;
	quiz.title = args.hasOwnProperty('title') ? args.title : null;
	quiz.content = args.hasOwnProperty('content') ? args.content : null;

	wpcqData.started = quiz.welcomeMessage === false ? true : false;

	WPCQ.render();
	
}