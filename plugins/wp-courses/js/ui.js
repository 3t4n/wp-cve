/*****************************************
*********** Front-End Ajax View **********
*****************************************/

$ = jQuery.noConflict();

wpcd = {
	ajaxLinks: true,
	firstLoad: true,
	selector: '#wpc-course-app',
	sortOptions: ['default', 'newest', 'oldest'],
	user: {
		ID: null,
		loggedIn: false,
	},
	state: {
		search: null,
		sort: 'default',
		category: 'all',
		view: 'course-archive',
		page: 1,
		lesson_id: null,
		course_id: null,
		rightSidebar: false,
		bottomSidebar: false,
	},
	status: {
		bottomToggle: false,
		rightToggle: false,
		modal: false,
	}
}

jQuery(document).ready(function () {
	// Makes back and forward browser buttons work. Checks if mouse is inside WP Courses App and will only reinstantiate WPC_UI on click of back or forward .
	if ($('#wpc-course-app').length > 0) {
		document.getElementById('wpc-course-app').onmouseover = function () {
			window.innerDocClick = true;
		}

		document.getElementById('wpc-course-app').onmouseleave = function () {
			window.innerDocClick = false;
		}

		window.onhashchange = function () {
			wpcd.firstLoad = true;
						if (window.innerDocClick === false) {
				new WPC_UI({
					loggedIn: wpcd.user.loggedIn,
					userID: wpcd.user.ID,
					selector: '#wpc-course-app',
					registerEvents: false,
				});
			}
		}
	}
});

/**
* Encodes a series of get parameters using base64 so page state data can be used as a hash.  Neede for back and forward buttons to work.
* @return str Encoded base 64 get parameters
*/

function wpcPushState(params) {

	var paramStr = '?';
	var keys = Object.keys(params);

	for (i = 0; i < keys.length; i++) {
		paramStr += (i + 1) <= keys.length && i !== 0 ? '&' : '';
		paramStr += keys[i] + '=' + params[keys[i]];
	}

	params = btoa(paramStr);

	if (window.innerDocClick === true) {
		window.history.pushState({}, "Title", '#' + params);
	}
	
}

function wpcGetState(base64) {
	var get = atob(base64);
	return get;
}

function getParameters(urlString) {
	let paramString = urlString.split('?')[1];
	let queryString = new URLSearchParams(paramString);

	var toReturn = {};

	for (let pair of queryString.entries()) {
		toReturn[pair[0]] = pair[1];
	}

	return toReturn;
}

class UI_Components {
	static loader(selector) {
		let loader = '<div class="wpc-flex-loader wpc-fade"><div class="wpc-flex-loader-inner"><div class="dots-loader">Loading&#8230;</div></div></div>';
		$(selector).html(loader);
	}
	static renderProgressBar(percent, text = "", color = "#4f646d", addClass = "") {
		console.log('frontend');
		return '<div class="wpc-progress-wrapper"><div class="wpc-progress-inner ' + addClass + '" style="width: 0%; background-color: ' + color + ';" data-current-percent="0" data-percent="' + percent + '"><div class="wpc-progress-text"><span class="wpc-progress-perecent">' + percent + '</span>% ' + text + '</div></div></div>';
	}
}

class UI_Controller {

	static openModal(title, content, width = '80%') {
		$('.wpc-lightbox').css({
			width: width,
		});
		$('.wpc-lightbox-wrapper').fadeIn();
		$('.wpc-lightbox-title').html(title);
		$('.wpc-lightbox-content').html(content);
	}

	static closeModal() {
		$('.wpc-lightbox-wrapper').fadeOut();
	}

	static toggleSidebar() {
		let sidebar = $('#wpc-right-toggle-sidebar');
		if (wpcd.state.rightSidebar === false) {
			sidebar.animate({
				right: 0,
			});
			wpcd.state.rightSidebar = true;
		} else {
			sidebar.animate({
				right: '-320px',
			});
			wpcd.state.rightSidebar = false;
		}
	}

	static activeFirst() {
		$('.wpc-nav-list').children().not('.wpc-nav-list-header').first().addClass('wpc-active-nav-item');
	}

	static openSidebar() {
		let sidebar = $('#wpc-right-toggle-sidebar');

		sidebar.animate({
			right: 0,
		});

		wpcd.state.rightSidebar = true;

	}

	static closeSidebar() {
		let sidebar = $('#wpc-right-toggle-sidebar');

		sidebar.animate({
			right: '-320px',
		});

		wpcd.state.rightSidebar = false;

	}

	static openBottomSidebar() {
		$('#wpc-bottom-toggle-sidebar').animate({
			bottom: 0,
		});
	}

	static closeBottomSidebar() {
		$('#wpc-bottom-toggle-sidebar').animate({
			bottom: '-80%',
		});
	}

	static animateProgressBar(from = 0, to = null, selector = '.wpc-progress-inner') {

		$(selector).css({
			width: from + '%',
		});

		$(selector).each(function (key) {

			var width = to === null ? $(this).data('percent') + '%' : to + '%';

			$(this).animate({
				width: width,
			}, 1000);

		});
	}

	static resizeIframe(cssClass = '.wpc-vid-wrapper iframe, .wpc-lesson-content iframe') {
		let iframe = $(cssClass);
		iframe.each(function () {
			let w = $(this).parent().width();
			let h = w * 0.5625;

			$(this).width(w);
			$(this).height(h);
		});
	}

	static openBigLoader() {
		$('.wpc-lightbox-wrapper').fadeOut(); // in case it's open
		$('#wpc-full-screen-loader').fadeIn();
	}

	static closeBigLoader() {
		$('#wpc-full-screen-loader').fadeOut();
	}

}

/*let args = {
	lessonID 	: 123,
	courseID 	: 123,
}*/

class LessonUI {
	constructor(args) {
		this.lessonID = args.hasOwnProperty('lessonID') ? args.lessonID : null;
		this.courseID = args.hasOwnProperty('courseID') ? args.courseID : null;
		this.lesson = null;

		// Register All Events
		var allEvents = this.events;

		$.each(this.events, function (key, val) {
			var event = $(this)[0].event;
			var selector = $(this)[0].selector;

			$(document).on(event, selector, function (e) {

				allEvents[key].obj = $(this);
				allEvents[key].logic();
				allEvents[key].e = e;

			});
		});

	}

	static toolbar(lessonID, courseID) {

		let data = {
			'security': wpc_ajax.nonce,
			'action': 'wpc_lesson_toolbar',
			'lesson_id': lessonID,
			'course_id': courseID,
		}

		$.post(ajaxurl, data, function (response) {
			$('#wpc-toolbar-top').html(response);
		});

	}

	static lesson(lessonID, courseID) {
		wpcPushState({
			view: 'single-lesson',
			course_id: courseID,
			lesson_id: lessonID,
			page: null,
			category: null,
			orderby: null,
			search: null,
		});

		let data = {
			'security': wpc_ajax.nonce,
			'action': 'wpc_lesson',
			'lesson_id': lessonID,
			'course_id': courseID,
		}

		UI_Components.loader('#wpc-content');
		UI_Controller.closeModal();

		$.post(ajaxurl, data, function (response) {
			let json = JSON.parse(response);
			$('#wpc-content').html(json.content);
			WPC_Global_UI.containerQueries()
			UI_Controller.resizeIframe();
			UI_Controller.animateProgressBar();
			setTimeout(function () {
				$('.wpc-nav-list li[data-id=' + lessonID + '] i').remove();
				$('.wpc-nav-list li[data-id=' + lessonID + ']').prepend(json.icon);
				$('.wpc-nav-list li[data-id=' + lessonID + ']').removeClass('wpc-nav-item-success wpc-nav-item-highlight');
				$('.wpc-nav-list li[data-id=' + lessonID + ']').addClass(json.class);
			}, 250);
		});

	}

	static attachments(lessonID) {
		let data = {
			'security': wpc_ajax.nonce,
			'action': 'wpc_attachments',
			'lesson_id': lessonID,
		}

		UI_Components.loader('.wpc-toggle-sidebar-content');

		$.post(ajaxurl, data, function (response) {
			$('.wpc-toggle-sidebar-content').html(response);
		});
	}

	static submitComment(lessonID, name, email, url, comment) {

		let data = {
			'security': wpc_ajax.nonce,
			'action': 'wpc_submit_comment',
			'id': lessonID,
			'name': name,
			'email': email,
			'url': url,
			'comment': comment,
		}

		UI_Controller.openBigLoader();
		$('.wpc-alert-message').remove();

		$.post(ajaxurl, data, function (response) {
			let html = JSON.parse(response);
			$('.wpc-comments').prepend(html);
			$('.wpc-comments').children().first().fadeIn();
			UI_Controller.closeBigLoader();
		});

	}

	static markCompleted(lessonID, courseID, userID, status) {
		var startPercent = $('.wpc-progress-inner').data('percent');

		let data = {
			'security': wpc_ajax.nonce,
			'action': 'wpc_toggle_completed',
			'id': lessonID,
			'course_id': courseID,
			'status': status,
			'user_id': userID,
		}

		$('.wpc-mark-completed i').removeClass();
		$('.wpc-mark-completed i').addClass('fa-solid fa-spinner fa-spin');

		$.post(ajaxurl, data, function (response) {
			let json = JSON.parse(response);

			if (json.status === 0) {
				$('.wpc-mark-completed').removeClass('wpc-marked-completed');
				$('.wpc-mark-completed i').removeClass();
				$('.wpc-mark-completed i').addClass('fa-regular fa-square');
				$('.wpc-mark-completed').data('status', 0);
				$('.wpc-nav-list li[data-id=' + lessonID + ']').removeClass('wpc-nav-item-success');
			} else {
				$('.wpc-mark-completed').addClass('wpc-marked-completed');
				$('.wpc-mark-completed i').removeClass();
				$('.wpc-mark-completed i').addClass('fa fa-check');
				$('.wpc-mark-completed').data('status', 1);
				$('.wpc-nav-list li[data-id=' + lessonID + ']').removeClass('wpc-nav-item-highlight');
			}

			$('.wpc-nav-list li[data-id=' + lessonID + '] i').remove();

			if ($('.wpc-nav-list li[data-id=' + lessonID + ']').children().length > 0) { // checks if nav <li> has <a> inside for legacy navigation
				$('.wpc-nav-list li[data-id=' + lessonID + '] a').prepend(json.icon);
			} else {
				$('.wpc-nav-list li[data-id=' + lessonID + ']').prepend(json.icon);
			}

			$('.wpc-nav-list li[data-id=' + lessonID + ']').addClass(json.class);

			$('.wpc-progress-text').html(json.percent + '%');
			$('.wpc-complete-progress').data('percent', json.percent);
			UI_Controller.animateProgressBar(startPercent, json.percent, '.wpc-complete-progress');

			AwardsUI.getAwards();

		});
	}

	/********** LOAD LESSON **********/
	events = [
		{
			event: 'click',
			selector: '.wpc-load-lesson',
			obj: null,
			e: null,
			logic: function () {
				wpcd.state.view = 'single-lesson';
				LessonUI.lesson(this.obj.data('id'), this.obj.data('course-id'));
			}

		},
		/********** LESSON TOOLBAR **********/
		{
			event: 'click',
			selector: '.wpc-load-lesson-toolbar',
			logic: function () {
				LessonUI.toolbar(this.obj.data('id'), this.obj.data('course-id'));
			}
		},
		/********** LOAD LESSON ATTACHMENTS **********/
		{
			event: 'click',
			selector: '.wpc-load-attachments',
			obj: null,
			e: null,
			logic: function () {
				let lessonID = this.obj.data('id');
				LessonUI.attachments(lessonID);
				UI_Controller.openSidebar();
			}

		},
		/********** SUBMIT COMMENT **********/
		{
			event: 'click',
			selector: '#wpc-submit-comment',
			obj: null,
			logic: function () {
				let lessonID = this.obj.data('id');
				let name = $('#wpc-comment-name').length > 0 ? $('#wpc-comment-name').val() : null;
				let email = $('#wpc-comment-email').length > 0 ? $('#wpc-comment-email').val() : null;
				let url = $('#wpc-comment-url').length > 0 ? $('#wpc-comment-url').val() : null;
				let comment = $('#wpc-comment-textarea').length > 0 ? $('#wpc-comment-textarea').val() : null;

				LessonUI.submitComment(lessonID, name, email, url, comment);
			}
		},
		/********** SET LESSON COMPLETION STATUS **********/
		{
			event: 'click',
			selector: '.wpc-mark-completed',
			obj: null,
			logic: function () {
				LessonUI.markCompleted(this.obj.data('id'), this.obj.data('course-id'), wpcd.user.ID, this.obj.data('status'));
			},
		}]
}

class CourseUI {
	constructor(args) {
		this.courseID = args.hasOwnProperty('courseID') ? args.courseID : null;
		this.category = args.hasOwnProperty('category') ? args.category : 'all';
		this.page = args.hasOwnProperty('page') ? args.page : 1;
		this.search = args.hasOwnProperty('search') ? args.search : null;
		this.orderby = args.hasOwnProperty('orderby') ? args.orderby : null;

		// Register All Events
		var allEvents = this.events;

		$.each(this.events, function (key, val) {
			var event = $(this)[0].event;
			var selector = $(this)[0].selector;

			$(document).on(event, selector, function (e) {

				allEvents[key].obj = $(this);
				allEvents[key].logic();
				allEvents[key].e = e;

			});
		});

		let timer;
		let typingInterval = 250;

		/********** LOAD SEARCH COURSE RESULTS **********/
		$(document).on('keyup', '#wpc-course-ajax-search', function (e) {

			$('.wpc-nav-list li').removeClass('wpc-active-nav-item');
			$('.wpc-nav-list li').first().addClass('wpc-active-nav-item');

			CourseUI.category = 'all';
			CourseUI.search = $(this).val();

			var val = $(this).val();

			val = !val ? '' : val;
			CourseUI.search = val;

			// runs search on backspace of empty input
			if (e.keyCode == 8 && !$(this).val()) {
				wpcDoneTyping();
			}

			clearTimeout(timer);
			if (val) {
				timer = setTimeout(wpcDoneTyping, typingInterval);
			}

			function wpcDoneTyping() {

				wpcd.state.view = 'course-archive';
				CourseUI.page = 1;
				CourseUI.orderby = 'default';

				wpcPushState({
					view: 'course-archive',
					page: CourseUI.page,
					category: CourseUI.category,
					orderby: CourseUI.orderby,
					search: CourseUI.search,
				});

				CourseUI.archive(CourseUI.category, CourseUI.page, CourseUI.orderby, CourseUI.search);

			}
		});

	}

	static course(courseID, ajaxLinks = true) {
		let data = {
			'security': wpc_ajax.nonce,
			'action': 'wpc_course',
			'id': courseID,
			'ajax': ajaxLinks,
			'caller': wpcd.ajaxLinks ? 'wpc_courses-shortcode' : 'wpc_profile-shortcode'
		}

		UI_Controller.openBigLoader();

		$.post(ajaxurl, data, function (response) {
			UI_Controller.closeBigLoader();
			UI_Controller.openModal('', response);
			UI_Controller.resizeIframe();
			WPC_Global_UI.counter();
		});

	}

	static categories(ajax = true) {

		let data = {
			'security': wpc_ajax.nonce,
			'action': 'wpc_course_categories',
			'ajax': ajax,
		}

		$('#wpc-right-sidebar').fadeOut();
		UI_Controller.closeSidebar();
		UI_Components.loader('.wpc-toggle-bottom-sidebar-content');

		$.post(ajaxurl, data, function (response) {
			if (response.replace(/\s+/g, '') == 'false') {
				$('#wpc-content').addClass('wpc-flex-content-fw');
			} else {
				$('#wpc-left-sidebar, .wpc-toggle-bottom-sidebar-content').html(response).show();
			}

		});

	}

	static lessonNav(courseID, userID = null, selector = '#wpc-right-sidebar, .wpc-toggle-bottom-sidebar-content, .wpc-lightbox-content', ajax = true, lessonID) {

		let data = {
			'security': wpc_ajax.nonce,
			'action': 'wpc_lesson_navigation',
			'course_id': courseID,
			'user_id': userID,
			'ajax': ajax,
			'lesson_id': lessonID
		}

		UI_Components.loader(selector);
		$('#wpc-left-sidebar').hide();

		$.post(ajaxurl, data, function (response) {
			$(selector).html(response).show();
		});

	}

	static teacher(teacherID) {

		let data = {
			'security': wpc_ajax.nonce,
			'action': 'wpc_teacher',
			'id': teacherID,
		}

		UI_Controller.openBigLoader();

		$.post(ajaxurl, data, function (response) {
			UI_Controller.closeBigLoader();
			UI_Controller.openModal('', response);
		});

	}

	static toolbar() {
		let data = {
			'security': wpc_ajax.nonce,
			'action': 'wpc_course_toolbar',
			'orderby': CourseUI.orderby,
		}
		$.post(ajaxurl, data, function (response) {
			$('#wpc-toolbar-top').html(response);
		});
	}

	static archive(category, page, orderby, search) {

		wpcPushState({
			view: 'course-archive',
			page: page,
			category: category,
			orderby: orderby,
			search: search,
		});

		let data = {
			'security': wpc_ajax.nonce,
			'action': 'wpc_course_archive',
			'category': category,
			'page': page,
			'search': search,
			'orderby': orderby,
		}

		UI_Components.loader('#wpc-content');

		$.post(ajaxurl, data, function (response) {
			$('#wpc-content').html(response);
			WPC_Global_UI.containerQueries();
			UI_Controller.resizeIframe();
			UI_Controller.animateProgressBar();
			WPC_Global_UI.counter();

			setTimeout(function () { // Highlight selected course category
				$('.wpc-nav-list li').removeClass('wpc-active-nav-item');
				$('.wpc-nav-list li[data-category=' + category + ']').addClass('wpc-active-nav-item');
			}, 250);
		});
	}

	events = [
		/********** LOAD COURSE CATEGORY LIST **********/
		{
			event: 'click',
			selector: '.wpc-load-category-list',
			obj: null,
			logic: function () {
				CourseUI.categories(this.obj.data('ajax'));
			}
		},
		/********** LOAD COURSE NAV **********/
		{
			event: 'click',
			selector: '.wpc-start-course, .wpc-load-lesson-nav',
			obj: null,
			logic: function () {
				let selector = typeof this.obj.data('selector') === 'undefined' ? '#wpc-right-sidebar, .wpc-toggle-bottom-sidebar-content, .wpc-lightbox-content' : this.obj.data('selector');
				let ajax = typeof this.obj.data('ajax') === 'undefined' ? true : this.obj.data('ajax');
				CourseUI.lessonNav(this.obj.data('course-id'), wpcd.user.ID, selector, ajax, undefined);
			}
		},
		/********** LOAD SINGLE TEACHER **********/
		{
			event: 'click',
			selector: '.wpc-load-teacher',
			obj: null,
			logic: function () {
				let teacherID = this.obj.data('id');
				CourseUI.teacher(teacherID);
			}

		},
		/********** LOAD SINGLE COURSE **********/
		{
			event: 'click',
			selector: '.wpc-load-course',
			obj: null,
			logic: function () {
				CourseUI.course(this.obj.data('id'), this.obj.data('ajax'));
			}
		},
		/********** CLEAR SEARCH **********/
		{
			event: 'click',
			selector: '.wpc-clear-search',
			logic: function () {
				CourseUI.search = '';
				$('#wpc-course-ajax-search').val('');
			}
		},
		/********** LOAD COURSE ARCHIVE **********/
		{
			event: 'click',
			selector: '.wpc-load-courses',
			obj: null,
			logic: function () {

				CourseUI.category = this.obj[0].hasAttribute('data-category') ? this.obj.data('category') : 'all';
				CourseUI.page = this.obj[0].hasAttribute('data-page') ? this.obj.data('page') : 1;
				CourseUI.orderby = typeof CourseUI.orderby === 'undefined' ? 'menu_order' : CourseUI.orderby;
				CourseUI.search = typeof CourseUI.search === 'undefined' ? '' : CourseUI.search;

				CourseUI.toolbar();
				CourseUI.archive(CourseUI.category, CourseUI.page, CourseUI.orderby, CourseUI.search);

			}

		},
		/********** LOAD SORTED COURSES **********/
		{
			event: 'change',
			selector: '#wpc-ajax-course-sort',
			obj: null,
			logic: function () {
				CourseUI.orderby = this.obj.val();
				CourseUI.archive(CourseUI.category, CourseUI.page, CourseUI.orderby, CourseUI.search);
			}
		},]

}

class ProfileUI {

	constructor(args) {
		wpcd.user.ID = args.hasOwnProperty('userID') ? args.userID : null;
		this.selector = args.hasOwnProperty('selector') ? args.selector : '#wpc-profile-page';
		wpcd.ajaxLinks = args.hasOwnProperty('ajaxLinks') ? args.ajaxLinks : true;

		$('#wpc-profile-page').html(ProfileUI.render());



		// Load Navigation
		let data = {
			'security': wpc_ajax.nonce,
			'action': 'wpc_profile_nav',
			'ul_class': 'wpc-nav-list-contained',
			'li_class': '',
		}

		UI_Components.loader('.wpc-toggle-sidebar-content');

		$.post(ajaxurl, data, function (response) {
			$('#wpc-profile-nav').html(response);
			WPC_Global_UI.containerQueries();
			$('.wpc-nav-list-profile li').first().addClass('wpc-active-nav-item'); // Select first profile menu item
		});

		// Load First Profile Part
		let data2 = {
			'security': wpc_ajax.nonce,
			'action': 'wpc_profile_part',
			'name': 'purchased-courses',
			'user_id': wpcd.user.ID,
			'page': 1,
			'ajax_links': wpcd.ajaxLinks,
		}

		UI_Components.loader('.wpc-lightbox-content, #wpc-profile-content');

		$.post(ajaxurl, data2, function (response) {
			$('#wpc-profile-content').html(response);
			WPC_Global_UI.containerQueries();
		});



		// Register All Events
		var allEvents = this.events;

		$.each(this.events, function (key, val) {
			var event = $(this)[0].event;
			var selector = $(this)[0].selector;

			$(document).on(event, selector, function (e) {

				allEvents[key].obj = $(this);
				allEvents[key].logic();
				allEvents[key].e = e;

			});
		});
	}

	static render() {

		let html = '<div class="wpc-flex-container">';
		html += '<div class="wpc-flex-12">';
		html += '<div class="wpc-flex-container">';
		html += '<div id="wpc-profile-nav" class="wpc-flex-sidebar wpc-flex-sidebar-left"></div>';
		html += '<div id="wpc-profile-content" class="wpc-flex-content wpc-flex-content-right"></div>';
		html += '<div class="wpc-pagination wpc-profile-pagination"></div>';
		html += '</div>';
		html += '</div>';
		html += '</div>';

		return html;
	}

	events = [
		/********** LOAD PROFILE NAV **********/
		{
			event: 'click',
			selector: '.wpc-load-profile-nav',
			obj: null,
			logic: function () {
				let data = {
					'security': wpc_ajax.nonce,
					'action': 'wpc_profile_nav',
					'ul_class': '',
					'li_class': 'wpc-open-modal',
				}

				UI_Components.loader('.wpc-toggle-sidebar-content');

				$.post(ajaxurl, data, function (response) {
					$('.wpc-toggle-sidebar-content').html(response);
				});

			}
		},
		/********** LOAD PROFILE PART **********/
		{
			event: 'click',
			selector: '.wpc-load-profile',
			obj: null,
			logic: function () {

				let clicked = this.obj;

				let data = {
					'security': wpc_ajax.nonce,
					'action': 'wpc_profile_part',
					'name': this.obj.data('name'),
					'user_id': wpcd.user.ID,
					'page': this.obj.data('page'),
					'ajax_links': wpcd.ajaxLinks,
				}

				UI_Components.loader('.wpc-lightbox-content, #wpc-profile-content');

				$.post(ajaxurl, data, function (response) {
					$('.wpc-lightbox-content, #wpc-profile-content').html(response);
					$('.wpc-lightbox-title').html(clicked.data('title'));
				});

			}
		},
		{
			event: 'click',
			selector: '.wpc-load-profile-part-pagination',
			obj: null,
			logic: function () {

				let data = {
					'security': wpc_ajax.nonce,
					'action': 'wpc_profile_part_pagination',
					'name': this.obj.data('name'),
				}

				$('.wpc-profile-pagination, .wpc-lightbox-pagination').hide().fadeIn();

				$.post(ajaxurl, data, function (response) {
					$('.wpc-profile-pagination, .wpc-lightbox-pagination').hide().html(response).fadeIn();
				});

			}
		},
		/********** LOAD LOGIN MODAL **********/
		{
			event: 'click',
			selector: '.wpc-load-login',
			logic: function () {

				let data = {
					'security': wpc_ajax.nonce,
					'action': 'wpc_login_form',
					'redirect': window.location.href,
				}

				UI_Components.loader('.wpc-lightbox-content');

				$.post(ajaxurl, data, function (response) {
					UI_Controller.openModal('Log In', response, '420px');
				});

			}
		},
	]
}

/********** AWARDS **********/

class AwardsUI {

	constructor() {
		AwardsUI.getAwards();

		// Register All Events
		var allEvents = this.events;

		$.each(this.events, function (key, val) {
			var event = $(this)[0].event;
			var selector = $(this)[0].selector;

			$(document).on(event, selector, function (e) {

				allEvents[key].obj = $(this);
				allEvents[key].logic();
				allEvents[key].e = e;

			});
		});
	}

	static getAwards() {
		let data = {
			'security': wpc_ajax.nonce,
			'action': 'wpc_get_awards',
		}

		$.post(ajaxurl, data, function (response) {
			setTimeout(function () {
				if (response.replace(/\s+/g, '') != 'false') {
					UI_Controller.openModal('You\'ve received an award!', response);
					$(".wpc-award-slider").bxSlider(
						{
							infiniteLoop: false,
							hideControlOnEnd: true,
							touchEnabled: false
						}
					);
				}
			}, 5000);
		});
	}

	events = [
		/********** GET AWARDS **********/
		{
			event: 'click',
			selector: '.wpc-load-lesson, .wpc-load-quiz',
			obj: null,
			logic: function () {
				AwardsUI.getAwards();
			}
		},
		/********** LOAD SINGLE CERTIFICATE IN MODAL **********/
		{
			event: 'click',
			selector: '.wpc-load-certificate',
			obj: null,
			logic: function () {
				UI_Components.loader('.wpc-lightbox-content');

				let data = {
					'security': wpc_ajax.nonce,
					'action': 'wpc_certificate',
					'id': this.obj.data('id'),
					'user': wpcd.user.ID
				}

				$.post(ajaxurl, data, function (response) {
					UI_Controller.openModal('Certificate', response);
				});

			}
		},
		/********** PRINT CERTIFICATE **********/
		{
			event: 'click',
			selector: '.wpc-print-certificate',
			obj: null,
			logic: function () {
				let certificate = $('.wpc-single-certificate-wrapper');
 				certificate = certificate.clone();
				
				let w = window.open();
				w.document.body.appendChild(certificate[0]);
				w.print();
				w.close();
			}
		},
	]
}

/********** GLOBAL EVENTS **********/

class WPC_Global_UI {

	constructor() {
		// Register All Events
		var allEvents = this.events;

		$.each(this.events, function (key, val) {
			var event = $(this)[0].event;
			var selector = $(this)[0].selector;

			$(document).on(event, selector, function (e) {

				allEvents[key].obj = $(this);
				allEvents[key].logic();
				allEvents[key].e = e;

			});
		});

		/**
		* Container queries aren't supported well with CSS.  UI might be embedded in pages that don't span the full width of the screen, so media queries woudn't work very well.
		*/

		WPC_Global_UI.containerQueries();

		$(window).resize(function () {
			WPC_Global_UI.containerQueries();
			UI_Controller.resizeIframe();
		});

		$(document).ready(function () {
			WPC_Global_UI.counter();
			$('#wpc-courses-ajax').css({
				'min-height': window.innerHeight / 2 + 'px',
			});
		});

	}

	/********** COUNTER **********/
	static counter() {

		let highestCount = 0;
		let totalCount = 0;

		$('.wpc-counter').each(function (key) {
			if ($(this).data('count') > highestCount) {
				highestCount = $(this).data('count');
			}
			totalCount += $(this).data('count');
		});

		let startCount = 0;
		let count = 0;

		var a = setInterval(function () {

			$('.wpc-counter').each(function (key) {

				if ($(this).data('count') >= count) {
					$(this).html(count);
				}

				if (count === totalCount) {
					clearInterval(a);
				}

			});

			count += 1;
		}
			, 50);
	}


	static containerQueries() {
		let w = $('.wpc-wrapper').outerWidth();
		if (typeof w === 'undefined') {
			w = $(window).width();
		}

		if (w <= 400) {
			$('.wpc-flex-3').css({
				'flex-basis': '100%',
				'margin': '0 0 2% 0',
			});
		} else if (w <= 767 && w > 400) {
			$('.wpc-flex-3').css({
				'flex-basis': '48%',
			});
		} else if (w <= 1200 && w > 767) {
			$('.wpc-flex-3').css({
				'flex-basis': '48%',
			});
		} else if (w <= 2000 && w > 1200) {
			$('.wpc-flex-3').css({
				'flex-basis': '31.33%',
			});
		} else if (w > 2000) {
			$('.wpc-flex-3').css({
				'flex-basis': '23%',
			});
		}

		if (w > 400) {
			$('.wpc-flex-3').css({
				'margin': '0% 1% 2% 1%',
			});
		}

		if (w > 767) {

			$('.wpc-flex-sidebar').css({
				'flex-basis': '30%',
			});

			$('.wpc-flex-content').css({
				'flex-basis': '70%',
			});

			$('.wpc-flex-content-right').css({
				'flex-basis': '68%',
				'margin-left': '2%',
			});

			$('.wpc-flex-content-left').css({
				'flex-basis': '68%',
				'margin-right': '2%',
			});

		} else {
			$('.wpc-flex-sidebar, .wpc-flex-content').css({
				'flex-basis': '100%',
				'margin-left': 0,
				'margin-right': 0,
			});

		}

	}

	events = [
		/********** OPEN MODAL **********/
		{
			event: 'click',
			selector: '.wpc-open-modal',
			logic: function () {
				$('.wpc-lightbox-wrapper').fadeIn();
			}
		},
		/********** Toggle Right Sidebar **********/
		{
			event: 'click',
			selector: '.wpc-toggle-sidebar',
			logic: function () {
				UI_Controller.toggleSidebar();
			}
		},
		{
			event: 'click',
			selector: '#wpc-right-toggle-sidebar .wpc-load-courses',
			logic: function () {
				UI_Controller.toggleSidebar();
			}
		},
		/********** Toggle Right Sidebar **********/
		{
			event: 'click',
			selector: '.wpc-open-sidebar',
			logic: function () {
				UI_Controller.openSidebar();
			}
		},
		/********** Toggle Modules **********/
		{
			event: 'click',
			selector: '.wpc-nav-list-header',
			obj: null,
			logic: function () {
				var section = this.obj.next('.wpc-nav-list-section');
				var status = section.data('status');
				var height = status === true ? 0 : section.data('height') + 'px';

				status === true ? this.obj.children('i').removeClass('fa-angle-down').addClass('fa-angle-up') : this.obj.children('i').removeClass('fa-angle-up').addClass('fa-angle-down');

				if (height === 'nullpx') { // If modules are closed by default
					height = section.prop("scrollHeight") + 'px';
				}

				section.animate(
					{
						'height': height,
					}
				);

				section.data('status') === true ? section.data('status', false) : section.data('status', true);
				section.data('height', section.height());
			}
		},
		{
			event: 'click',
			selector: '.wpc-btn-next, .wpc-btn-prev',
			logic: function () {
				var newLesson = $('.wpc-nav-list li[data-id=' + this.obj.data('id') + ']').not('.wpc-nav-list-header');
				var newLessonParent = newLesson.parent();

				// Open module if closed
				if (newLessonParent.data('status') === false) {
					newLessonParent.prev().click();
				}

				// Highlight next/prev lesson
				$('.wpc-nav-list li').removeClass('wpc-active-nav-item');
				newLesson.addClass('wpc-active-nav-item');
			}
		},
		{
			event: 'click',
			selector: '.wpc-nav-list li:not(.wpc-nav-list-header)', // Course overview nav, Course view nav, course view profile nav, profile view nav
			logic: function () {
				if (this.obj.is('#wpc-right-toggle-sidebar *')) { // Exception for course view profile nav
					$('#wpc-right-toggle-sidebar .wpc-nav-list li').removeClass('wpc-active-nav-item');
				} else {
					$('.wpc-nav-list li').removeClass('wpc-active-nav-item');
				}

				this.obj.not('.wpc-nav-list-header').addClass('wpc-active-nav-item');
			}
		},
		/********** Hide Bottom Toggle Sidebar on Click of Lesson **********/
		{
			event: 'click',
			selector: '#wpc-bottom-toggle-sidebar .wpc-nav-list li, .wpc-close-bottom-sidebar, .wpc-bottom-toggle-sidebar-header',
			logic: function () {
				if (this.obj.hasClass('wpc-nav-list-header') == false) {
					UI_Controller.closeBottomSidebar();
				}
			}
		},
		/********** OPEN Bottom Toggle Sidebar **********/
		{
			event: 'click',
			selector: '.wpc-open-bottom-sidebar',
			logic: function () {
				UI_Controller.openBottomSidebar();
			}
		}
	]
}

// Inits main UI
class WPC_UI {

	constructor(args) {
		if (typeof args !== 'undefined') {
			wpcd.selector = args.hasOwnProperty('selector') ? args.selector : '#wpc-course-app';
			wpcd.user.loggedIn = args.hasOwnProperty('loggedIn') ? args.loggedIn : false;
			wpcd.user.ID = args.hasOwnProperty('userID') ? args.userID : null;
			wpcd.state.view = args.hasOwnProperty('view') ? args.view : 'course-archive';
			wpcd.onLoad = args.hasOwnProperty('onLoad') ? args.onLoad : true;
			wpcd.registerEvents = args.hasOwnProperty('registerEvents') ? args.registerEvents : true;
			wpcd.pushState = args.hasOwnProperty('pushState') ? args.pushState : false; // flag for when page reloaded with back/forward button to prevent infinite loop
			wpcd.ajaxLinks = args.hasOwnProperty('ajaxLinks') ? args.ajaxLinks : true; // used for setting whether or not permalinks or ajax is used for links
			wpcd.adminBar = args.hasOwnProperty('adminBar') ? args.adminBar : true; // used to tell if admin bar is showing so sticky toolbar can be offset on Y axis
			wpcd.fixedToolbar = args.hasOwnProperty('fixedToolbar') ? args.fixedToolbar : true; // sets if toolbar is fixed on scroll
			wpcd.fixedToolbarOffset = args.hasOwnProperty('fixedToolbarOffset') ? args.fixedToolbarOffset : true; // sets fixed toolbar offset to help with theme integration
		}

		let params = wpcGetState(window.location.hash.replace('#', ''));
		params = getParameters(params); // gets url params

		let category = params.hasOwnProperty('category') ? params.category : 'all';
		let lessonID = params.hasOwnProperty('lesson_id') ? parseInt(params.lesson_id) : null;
		let courseID = params.hasOwnProperty('course_id') ? parseInt(params.course_id) : null;
		let orderby = params.hasOwnProperty('orderby') ? params.orderby : 'menu_order';
		let page = params.hasOwnProperty('pg') ? parseInt(params.pg) : 1;
		let search = params.hasOwnProperty('search') ? params.search : '';

		new CourseUI({
			courseID: courseID,
			category: category,
			orderby: orderby,
			page: page,
			search: search,
		});

		new LessonUI({
			lessonID: lessonID,
			courseID: courseID,
		});

		new ProfileUI({
			userID: wpcd.user.ID,
			selector: '#wpc-profile-page',
			ajaxLinks: wpcd.ajaxLinks,
		});

		new AwardsUI();

		if (params.hasOwnProperty('view')) {
			var view = params.view;
		} else if (args.hasOwnProperty('view')) {
			var view = wpcd.state.view;
		} else {
			var view = wpcd.state.view;
		}

		if (wpcd.onLoad === true) {
			$(wpcd.selector).html(this.render()); // renders base containers

			if (view === 'course-archive') {
				CourseUI.toolbar();
				CourseUI.categories();
				CourseUI.archive(category, page, orderby, search);
			} else if (view === 'single-lesson') {
				LessonUI.toolbar(lessonID, courseID);
				CourseUI.lessonNav(courseID, wpcd.user.ID, undefined, undefined, lessonID);
				LessonUI.lesson(lessonID, courseID);
			} else if (view === 'single-quiz') {
				LessonUI.toolbar(lessonID, courseID);
				CourseUI.lessonNav(courseID, undefined, undefined, undefined, lessonID);
				WPCQ_Controller.loadQuiz(lessonID, courseID);
			}
		}

		// sticky toolbar
		if (jQuery(document).width() < 767) {
			setTimeout(function () {
				var offsetTop = $('.wpc-wrapper').offset().top;

				$(window).scroll(function () {

					var scrollTop = $(document).scrollTop();
					if ($(document).width() < 783) {

						let adminBarH = $(window).width() <= 600 ? 0 : 46;
						var top = wpcd.adminBar === true ? wpcd.fixedToolbarOffset + adminBarH : wpcd.fixedToolbarOffset;

						if (scrollTop > offsetTop - top) {
							$('#wpc-toolbar-top').css({
								position: 'fixed',
								'z-index': 9999,
								width: '100%',
								top: top + 'px',
								left: 0,
							});

							$(window).width() < 527 ? $('#wpc-courses-ajax').css('margin-top', '140px') : $('#wpc-courses-ajax').css('margin-top', '100px');

						} else {
							$('#wpc-toolbar-top').css({
								position: 'initial',
								top: 0,
							});
							$('#wpc-courses-ajax').css('margin-top', '0');
						}
					}

				});
			}, 500);
		}

	}

	render() {
		var html = '<div class="wpc-wrapper">';

		html += '<div id="wpc-toolbar-top" class="wpc-flex-toolbar"></div>';

		html += '<div id="wpc-courses-ajax" class="wpc-flex-container">';

		html += '<div id="wpc-content" class="wpc-flex-content"></div>';
		html += '<div id="wpc-left-sidebar" class="wpc-flex-sidebar"></div>';
		html += '<div id="wpc-right-sidebar" class="wpc-flex-sidebar"></div>';

		html += '</div>';

		html += '</div>';

		return html;
	}
}

jQuery(document).ready(function () {
	new WPC_Global_UI();
});
