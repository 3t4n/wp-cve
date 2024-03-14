(function () {
	var MyBookProgress = function() {};
	_.extend(MyBookProgress.prototype, Backbone.Events, {
		preload: function(promise) {
			if(!this.preload_list) { this.preload_list = []; }
			this.preload_list.push(promise);
		},
		do_preload: function(argument) {
			return jQuery.when.apply(jQuery, this.preload_list);
		}
	});
	window.mybookprogress = new MyBookProgress();

	jQuery(document).ready(function() {
		jQuery('#wpbody').mbp_loading();
		mybookprogress.preload(mybookprogress.utils.google_load("visualization", "1.0", {"packages":["corechart"]}));
		mybookprogress.trigger('load_models');
		mybookprogress.do_preload().done(function() {
			jQuery('#wpbody').mbp_loading('destroy');
			mybookprogress.trigger('models_loaded');
			mybookprogress.admin = new mybookprogress.AdminView();
			mybookprogress.trigger('loaded');
		});
	});
})();
(function () {
	var settings = null;

	mybookprogress.on('models_loaded', function() {
		settings = mybookprogress.settings;
	});

	var Utility = function() {};
	_.extend(Utility.prototype, {
		capitalize: function(string) {
			return string.charAt(0).toUpperCase() + string.slice(1);
		},

		uncapitalize: function(string) {
			return string.toLowerCase();
		},

		color_is_bright: function(color) {
			var rgbcolor = jQuery.colpick.hexToRgb(color);
			var luma = 0.2126 * rgbcolor.r + 0.7152 * rgbcolor.g + 0.0722 * rgbcolor.b;
			return luma > 128;
		},

		media_selector: function(title, callback) {
			if(!this._media_frame) {
				this._media_frame = wp.media.frames.media_selector = wp.media({
					multiple: false
				});
			}

			var callback_wrapper = function() {
				attachment = this._media_frame.state().get('selection').first().toJSON();
				callback(attachment);
			}

			var callback_remover = function() {
				setTimeout(_.bind(function() { this._media_frame.off('select', callback_wrapper); }, this), 0);
				this._media_frame.off('close', callback_remover);
			}

			this._media_frame.on('select', _.bind(callback_wrapper, this));
			this._media_frame.on('close', _.bind(callback_remover, this));

			this._media_frame.open();

			this._media_frame.state().set('title', title)._title();
		},

		modal: function(content, options) {
			if(!this.modal_id_counter) {
				this.modal_id_counter = 0;
			}

			var holder = jQuery('<div/>');
			holder.addClass('mbp-modal-holder');
			holder.attr('id', 'mbp-modal-'+this.modal_id_counter++);
			jQuery('#mbp-admin-page').append(holder);
			holder.append(content);

			var options = jQuery.extend({
				title: '',
				width: 600,
				height: holder.height()+5,
			}, options);

			holder.css('display', 'none');

			var old_tb_remove = window.tb_remove;
			window.tb_remove = function() {
				window.tb_remove = old_tb_remove;
				window.tb_remove(window);
				if(options.close) { options.close(); }
			};

			var width_units = 'px', height_units = 'px';
			if(typeof options.height === 'string') {
				if(options.height.indexOf('%') !== -1) { height_units = '%'; }
				options.height = parseFloat(options.height.replace(/[^0-9\.]/g, ''));
			}
			if(typeof options.width === 'string') {
				if(options.width.indexOf('%') !== -1) { width_units = '%'; }
				options.width = parseFloat(options.width.replace(/[^0-9\.]/g, ''));
			}

			tb_show(options.title, '#TB_inline?inlineId='+holder.attr('id'));

			if(height_units === '%') {
				jQuery('#TB_window').css({'margin-top': 0, 'top': (50-options.height*0.5).toString()+'%', 'height': options.height.toString()+'%'});
				jQuery('#TB_ajaxContent').css({'height': 'auto', 'position': 'absolute', 'top': '30px', 'bottom': '0'});
			} else {
				jQuery('#TB_window').css({'margin-top': '-'+(options.height*0.5).toString()+height_units});
				jQuery('#TB_ajaxContent').css({'height': options.height.toString()+height_units});
			}
			if(width_units === '%') {
				jQuery('#TB_window').css({'margin-left': 0, 'left': (50-options.width*0.5).toString()+'%', 'width': options.width.toString()+'%'});
			} else {
				jQuery('#TB_window').css({'margin-left': '-'+(options.width*0.5).toString()+width_units, 'width': options.width.toString()+width_units});
			}
			jQuery('#TB_ajaxContent').css({'width': '100%', 'padding': 0});

			holder.remove();
		},

		do_mailchimp_query: function(method, data) {
			return mybookprogress.WPQuery('mbp_do_mailchimp_query', {apikey: settings.get('mailchimp_apikey'), method: method, data: JSON.stringify(data || {})});
		},

		datepicker: function(element, options) {
			element.datepicker(jQuery.extend({
				dateFormat: settings.get('date_format'),
				beforeShow: function(input, inst) { jQuery('#ui-datepicker-div').addClass('mbp-ui-datepicker'); },
			}, options));
		},

		tooltip: function(element, message, options) {
			element.tooltip(jQuery.extend({
				tooltipClass: 'mbp-ui-tooltip',
				items: element,
				content: message,
				position: {
					my: "left top+15",
					at: "left+10 bottom",
					collision: "none",
					using: function(position, feedback) {
						jQuery(this).css(position);
						jQuery("<div>").addClass("arrow left top").appendTo(this);
					}
				},
			}, options));
		},

		pointer: function(content, options) {
			if(!this.pointer_id_counter) {
				this.pointer_id_counter = 0;
			}
			var id = this.pointer_id_counter++;

			jQuery('#wpadminbar').pointer(jQuery.extend({
				position: {edge: 'top', align: 'center'},
				buttons: function() {}
			}, options, {
				pointerClass: 'mbp-pointer mbp-pointer-'+id + (options.pointerClass ? ' '+options.pointerClass : ''),
				content: ' ',
			})).pointer('open');
			jQuery('.mbp-pointer-'+id+' .wp-pointer-content').empty().append(content);
		},

		utcdate: function(date) {
			return {
				getDate: date.getUTCDate.bind(date),
				getDay: date.getUTCDay.bind(date),
				getFullYear: date.getUTCFullYear.bind(date),
				getHours: date.getUTCHours.bind(date),
				getMilliseconds: date.getUTCMilliseconds.bind(date),
				getMinutes: date.getUTCMinutes.bind(date),
				getMonth: date.getUTCMonth.bind(date),
				getSeconds: date.getUTCSeconds.bind(date),
				getTime: date.getTime.bind(date),
			};
		},

		format_date: function(date) {
			if(typeof date === 'number') { date = new Date(date*1000); }
			return jQuery.datepicker.formatDate(settings.get('date_format'), this.utcdate(date));
		},

		parse_date: function(date) {
			try {
				var date = jQuery.datepicker.parseDate(settings.get('date_format'), date);
				return Date.UTC(date.getFullYear(),date.getMonth(),date.getDate())*0.001;
			} catch(e) {
				return e;
			}
		},

		unix_timestamp: function(date) {
			return date.getTime()*0.001;
		},

		unix_timestamp_now: function() {
			return this.unix_timestamp(new Date());
		},


		month_name: function(month) {
			var months = [
				mybookprogress_i18n.january,
				mybookprogress_i18n.february,
				mybookprogress_i18n.march,
				mybookprogress_i18n.april,
				mybookprogress_i18n.may,
				mybookprogress_i18n.june,
				mybookprogress_i18n.july,
				mybookprogress_i18n.august,
				mybookprogress_i18n.september,
				mybookprogress_i18n.october,
				mybookprogress_i18n.november,
				mybookprogress_i18n.december,
			];

			return months[month];
		},

		human_time_diff: function(start, finish) {
			var diff = Math.abs(start-finish);

			var DAY_IN_SECONDS = 24*60*60;

			if(diff < 31*DAY_IN_SECONDS) {
				return Math.floor(diff/DAY_IN_SECONDS)+' '+mybookprogress_i18n.days;
			} else if(diff < 90*DAY_IN_SECONDS) {
				return Math.floor(diff/(7*DAY_IN_SECONDS))+' '+mybookprogress_i18n.weeks;
			} else {
				return Math.floor(diff/(30*DAY_IN_SECONDS))+' '+mybookprogress_i18n.months;
			}
		},

		google_load: function(moduleName, moduleVersion, optionalSettings) {
			var defer = jQuery.Deferred();

			var callback = function() { defer.resolve(); }
			optionalSettings.callback = callback;

			google.load(moduleName, moduleVersion, optionalSettings);

			return defer.promise();
		},

		progress_format: function(progress) {
			if(progress >= 10) { return this.number_format(progress); }
			return this.number_format(progress, 1);
		},

		number_format: function(number, decimal_places) {
			if(typeof decimal_places === 'undefined') { decimal_places = 0; }
			var formatted_number = number.toFixed(decimal_places);
			if(decimal_places > 0) {
				while(formatted_number.slice(-1) == '0') { formatted_number = formatted_number.slice(0, -1); }
				if(formatted_number.slice(-1) == '.') { formatted_number = formatted_number.slice(0, -1); }
			}
			return formatted_number;
		},

		template: function(template, data) {
			var data = jQuery.extend({
				evaluate: /\{\{(.+?)\}\}/g,
				interpolate: /\{\{=(.+?)\}\}/g,
				escape: /\{\{-(.+?)\}\}/g,
			}, data);
			return _.template(template, data);
		},

		get_query_var: function(variable) {
			var query = window.location.search.substring(1);
			var vars = query.split("&");
			for(var i = 0; i<vars.length; i++) {
				var pair = vars[i].split("=");
				if(pair[0] == variable) { return pair[1]; }
			}
			return false;
		},
	});
	var utils = mybookprogress.utils = new Utility();

	mybookprogress.WPQuery = function(action, data) {
		//console.log('action', action, data);
		var defer = jQuery.Deferred();

		var query = jQuery.post(ajaxurl, jQuery.extend({}, data, {action: action}));
		query.done(function(response) {
			//console.log('response', action, response);
			var result = null;
			if(response === '') {
				result = {};
			} else {
				try {
					result = JSON.parse(response);
				} catch(e) {
					defer.reject(e);
					return;
				}
			}
			defer.resolve(result);
		});
		query.fail(function(e) {
			defer.reject(e);
		});

		return defer.promise();
	};

	var mbp_loading_num = 1;
	jQuery.widget('custom.mbp_loading', {
		options: {},
		_create: function() {
			if(this.element.data('mbp_loading_data')) { return; }
			this.element.data('mbp_loading_data', jQuery('<div class="mbp-loading" id="mbp-loading-'+(mbp_loading_num++)+'"></div>').appendTo(document.body).css({
				position: 'absolute',
				left: this.element.offset().left,
				top: this.element.offset().top,
				height: this.element.outerHeight(),
				width: this.element.outerWidth(),
			}));
		},
		_destroy: function() {
			this.element.data('mbp_loading_data').remove();
			this.element.removeData('mbp_loading_data');
		}
	});
})();
(function () {
	/*---------------------------------------------------------*/
	/* Numeric Progress                                        */
	/*---------------------------------------------------------*/

	var numeric_progress_editor_template = function(data) {
		var element = jQuery(mybookprogress.utils.template(jQuery('#book_progress_type_numeric_editor_template').html())(data));
		element.on('input', '[name="progress"]', function(e) {
			var max = parseInt(element.find('[name="target"]').val());
			var val = parseInt(jQuery(e.target).val());
			if(isNaN(val)) { jQuery(e.target).val(1); }
			else if(val > max) { jQuery(e.target).val(max); }
			else if(val < 1) { jQuery(e.target).val(1); }
			else if(val.toFixed(0) !== jQuery(e.target).val()) { jQuery(e.target).val(val.toFixed(0)); }
		});
		return element;
	};
	var numeric_progress_display_template = function(data) {
		return jQuery(mybookprogress.utils.template(jQuery('#book_progress_type_numeric_display_template').html())(data));
	};
	var numeric_progress_save = function(form_data) {
		return {
			progress: parseInt(form_data.progress)/parseInt(form_data.target),
			target: parseInt(form_data.target),
		};
	};

	/*---------------------------------------------------------*/
	/* Percentage Progress                                     */
	/*---------------------------------------------------------*/

	var percent_progress_editor_template = function(data) {
		var element = jQuery(mybookprogress.utils.template(jQuery('#book_progress_type_percent_editor_template').html())(data));
		element.find('.mbp-slider').slider({animate: "fast", value:data.progress, min: 0, max: 1, step: 0.01, slide: function(event, ui) {
			element.find('[name="progress"]').val(Math.round(ui.value*100)).trigger('change');
		}});
		element.find('[name="progress"]').val(Math.round(data.progress*100)).on('input', function() {
			var val = jQuery(this).val();
			if(!val.match(/^[0-9]*$/)) { val = val.replace(/[^0-9]/g, ''); jQuery(this).val(val); }
			if(val == '') {
				val = 0;
			} else {
				val = parseInt(val)*0.01;
			}
			if(val < 0) { val = 0; jQuery(this).val(Math.round(val*100)); }
			if(val > 1) { val = 1; jQuery(this).val(Math.round(val*100)); }

			element.find('.mbp-slider').slider('value', val);
		});
		return element;
	};
	var percent_progress_display_template = function(data) {
		return jQuery(mybookprogress.utils.template(jQuery('#book_progress_type_percent_display_template').html())(data));
	};
	var percent_progress_save = function(form_data) {
		return {
			progress: form_data.progress == '' ? 0 : parseInt(form_data.progress)*0.01,
			target: 100,
		};
	};

	mybookprogress.progress_types = mybookprogress.progress_types || {};
	mybookprogress.default_progress_type = 'words';
	mybookprogress.progress_types.chapters = {name: mybookprogress_i18n.chapters_name, unit: mybookprogress_i18n.chapters_unit, units: mybookprogress_i18n.chapters_units, default_target: 15, editor: numeric_progress_editor_template, display: numeric_progress_display_template, save: numeric_progress_save};
	mybookprogress.progress_types.pages = {name: mybookprogress_i18n.pages_name, unit: mybookprogress_i18n.pages_unit, units: mybookprogress_i18n.pages_units, default_target: 200, editor: numeric_progress_editor_template, display: numeric_progress_display_template, save: numeric_progress_save};
	mybookprogress.progress_types.words = {name: mybookprogress_i18n.words_name, unit: mybookprogress_i18n.words_unit, units: mybookprogress_i18n.words_units, default_target: 50000, editor: numeric_progress_editor_template, display: numeric_progress_display_template, save: numeric_progress_save};
	mybookprogress.progress_types.scenes = {name: mybookprogress_i18n.scenes_name, unit: mybookprogress_i18n.scenes_unit, units: mybookprogress_i18n.scenes_units, default_target: 100, editor: numeric_progress_editor_template, display: numeric_progress_display_template, save: numeric_progress_save};
	mybookprogress.progress_types.percent = {name: mybookprogress_i18n.percent_name, unit: mybookprogress_i18n.percent_unit, units: mybookprogress_i18n.percent_units, hide_target_editor: true, default_target: 100, editor: percent_progress_editor_template, display: percent_progress_display_template, save: percent_progress_save};
})();
(function () {
	var blog_button = function(model) {
		return model.get('blog_post_id') ? mybookprogress_i18n.edit_blog_post : mybookprogress_i18n.make_blog_post;
	};

	var blog_share = function(model, button) {
		if(button.hasClass('mbp-disabled')) { return; }
		if(model.get('blog_post_id')) {
			model.verify_blog_post(function(verified) { if(!verified) { button.text(mybookprogress_i18n.make_blog_post); } });
			return window.open(window.location.href.substring(0, window.location.href.indexOf('wp-admin/')+9)+'post.php?post='+model.get('blog_post_id')+'&action=edit', '_blank');
		}
		button.addClass('mbp-disabled');

		var book = model.get_book();
		var phase = book.get_phase(model.get('phase_id'));
		var shortcode_attrs = {};
		shortcode_attrs.progress = model.get('progress');
		if(phase) { shortcode_attrs.phase_name = phase['name']; }
		if(phase && phase['deadline']) { shortcode_attrs.deadline = phase['deadline']; }
		shortcode_attrs.book = book.id;
		shortcode_attrs.book_title = book.get_title();
		shortcode_attrs.bar_color = book.get('display_bar_color');
		if(book.get('display_cover_image')) { shortcode_attrs.cover_image = book.get('display_cover_image'); }
		if(book.get('mbt_book')) { shortcode_attrs.mbt_book = book.get('mbt_book'); }
		var shortcode = '';
		for(attr in shortcode_attrs) {
			shortcode += ' '+attr+'="'+shortcode_attrs[attr]+'"';
		}
		shortcode = '[mybookprogress'+shortcode+']';

		mybookprogress.WPQuery('mbp_progress_sharing_blog', {title: model.get_book().get_title(), message: model.get('notes')+'\n'+shortcode}).then(function(response) {
			if(response === null || typeof response !== 'object' || 'error' in response) { response = {post_id: null}; }
			if(response.post_id) {
				window.open(window.location.href.substring(0, window.location.href.indexOf('wp-admin/')+9)+'post.php?post='+response.post_id+'&action=edit', '_blank');
				model.save({blog_post_id: response.post_id});
				button.text(mybookprogress_i18n.edit_blog_post);
			}
			button.removeClass('mbp-disabled');
		});
	};

	var twitter_share = function(model, button) {
		if(button.hasClass('mbp-disabled')) { return; }
		button.addClass('mbp-disabled');
		model.get_sharing_url(function(url) {
			button.removeClass('mbp-disabled');
			if(url) {
				window.open('https://twitter.com/intent/tweet?url='+encodeURIComponent(url)+'&text='+encodeURIComponent(model.get('notes')), '_blank');
			} else {
				window.open('https://twitter.com/intent/tweet?text='+encodeURIComponent(model.get('notes')), '_blank');
			}
		});
	};

	var facebook_share = function(model, button) {
		if(button.hasClass('mbp-disabled')) { return; }
		button.addClass('mbp-disabled');
		model.get_sharing_url(function(url) {
			button.removeClass('mbp-disabled');
			var title = model.get_book().get_title();
			title = (title ? title+' ' : '') + mybookprogress_i18n.progress_update;
			if(url) {
				window.open('http://www.facebook.com/dialog/feed?app_id=134530986736267&redirect_uri=http%3A%2F%2Fwww.facebook.com%2F&name='+encodeURIComponent(title)+'&link='+encodeURIComponent(url)+'&description='+encodeURIComponent(model.get('notes')), '_blank');
			} else {
				window.open('http://www.facebook.com/dialog/feed?app_id=134530986736267&redirect_uri=http%3A%2F%2Fwww.facebook.com%2F&name='+encodeURIComponent(title)+'&link='+encodeURIComponent(location.origin)+'&description='+encodeURIComponent(model.get('notes')), '_blank');
			}
		});
	};

	mybookprogress.progress_sharing_types = mybookprogress.progress_sharing_types || {};
	mybookprogress.progress_sharing_types.blog = {button: blog_button, share: blog_share, default: true};
	mybookprogress.progress_sharing_types.facebook = {button: mybookprogress_i18n.share_on_facebook, share: facebook_share, default: true};
	mybookprogress.progress_sharing_types.twitter = {button: mybookprogress_i18n.share_on_twitter, share: twitter_share, default: true};
})();
(function () {
	var WPSync = mybookprogress.WPSync = function(method, model, options) {
		options = options || {};

		var data = null;
		switch(method) {
			case 'create':
			case 'update':
				data = model;
				break;
			case 'read':
			case 'delete':
				data = {id: model.id};
				break;
			case 'patch':
				data = jQuery.extend({id: model.id}, options.attrs);
				break;
		}
		if(!data) {
			if(options.error) { options.error(); }
			return false;
		}

		var query = mybookprogress.WPQuery(this.action+'_'+method, jQuery.extend({object: JSON.stringify(data)}, options.extra_data));
		query.done(function(response) {
			if(response === null || typeof response !== 'object') {
				if(options.error) { options.error('invalid response'); }
				return;
			}
			if('error' in response) {
				if(options.error) { options.error(response.error); }
			} else {
				switch(method) {
					case 'create':
						model.set('id', response.id);
						if(options.success) { options.success(); }
						break;
					case 'read':
						if(options.success) { options.success(response.object); }
						break;
					case 'patch':
					case 'update':
					case 'delete':
						if(options.success) { options.success(); }
						break;
					default:
						if(options.error) { options.error(); }
						break;
				}
			}
		});
		query.fail(function() {
			if(options.error) { options.error(); }
		});
		return query;
	};

	mybookprogress.WPModel = Backbone.Model.extend({
		sync: WPSync
	});

	mybookprogress.WPCollection = Backbone.Collection.extend({
		sync: WPSync
	});

	mybookprogress.VirtualModel = Backbone.Model.extend({
		sync: function(method, model, options) { options.success(); }
	});

	mybookprogress.VirtualCollection = Backbone.Collection.extend({
		sync: function(method, model, options) { options.success(); }
	});
})();
(function () {
	mybookprogress.on('load_models', function() {
		mybookprogress.mbt_books = new mybookprogress.MyBookTable_Books();
		mybookprogress.preload(mybookprogress.mbt_books.fetch());
	});

	var MyBookTable_Book = mybookprogress.MyBookTable_Book = mybookprogress.WPModel.extend({
		action: 'mbp_mbt_book',
		defaults: {
			title: '',
			mbp_book: null,
		},
		set_book: function(mbp_book_id) {
			if(mbp_book_id !== this.get('mbp_book')) {
				this.save({mbp_book: mbp_book_id});
			}
		},
		get_title: function() {
			var title = this.get('title');
			return title ? title : '('+mybookprogress_i18n.no_title+')';
		},
	});

	var MyBookTable_Books = mybookprogress.MyBookTable_Books = mybookprogress.WPCollection.extend({
		model: mybookprogress.MyBookTable_Book,
		action: 'mbp_mbt_books',
		update_book: function(mbp_book) {
			var mbt_book_id = mbp_book.get('mbt_book');
			this.each(_.bind(function(book) {
				if(book.id === mbt_book_id) {
					book.set_book(mbp_book.id);
				} else if(book.get('mbp_book') === mbp_book.id) {
					book.set_book(null);
				}
			}, this));
		},
	});
})();
(function () {
	var phase_templates = null;
	var mbt_books = null;
	var utils = mybookprogress.utils;

	mybookprogress.on('models_loaded', function() {
		phase_templates = mybookprogress.phase_templates;
		mbt_books = mybookprogress.mbt_books;
	});

	var WPModel = mybookprogress.WPModel;
	var WPCollection = mybookprogress.WPCollection;

	mybookprogress.on('load_models', function() {
		mybookprogress.books = new mybookprogress.Books();
		mybookprogress.preload(mybookprogress.books.fetch());
	});

	mybookprogress.Book = WPModel.extend({
		action: 'mbp_book',
		defaults: {
			title: '',
			phases_status: {},
			phases: null,
			display_bar_color: 'CB3301',
			display_cover_image: 0,
			created: null,
			mbt_book: null,
		},

		initialize: function(model, options) {
			if(!this.get('created')) { this.set('created', utils.unix_timestamp_now()); }
			this.on('change', this.on_changed, this);
			this.on('sync', this.on_synced, this);
		},

		on_changed: function() {
			this.is_dirty = true;
		},

		on_synced: function() {
			this.is_dirty = false;
		},

		get_title: function() {
			var title = this.get('title');
			if(!title && this.get('mbt_book')) {
				var book = mbt_books.get(this.get('mbt_book'));
				if(book) { title = book.get('title'); }
			}
			return title ? title : mybookprogress_i18n.untitled_book;
		},

		get_progress: function() {
			if(!this.progress) {
				this.progress = new mybookprogress.BookProgress(null, {book_id: this.id});
				this.progress.fetch({reset: true});
				this.phases_progress = {};
				this.fetch_phases_progress();
				this.listenTo(this.progress, 'add change remove', this.schedule_fetch_phases_progress);
			}
			return this.progress;
		},

		get_phases: function() {
			var phases = this.get('phases');
			if(typeof phases === 'string') {
				var template = phase_templates.get(phases);
				phases = template ? template.get('phases') : null;
			}
			if(!phases) { phases = []; }
			return jQuery.extend(true, [], phases);
		},

		get_phase: function(phase_id) {
			var phases = this.get_phases();
			for(var i = phases.length - 1; i >= 0; i--) {
				if(phases[i].id == phase_id) {
					return phases[i];
				}
			}
		},

		update_phase: function(phase) {
			var phases = this.get_phases();
			for(var i = phases.length - 1; i >= 0; i--) {
				if(phases[i].id == phase.id && !_.isEqual(phases[i], phase)) {
					phases[i] = _.clone(phase);
					break;
				}
			}
			this.set('phases', phases);
		},

		get_phase_status: function(phase_id) {
			var phases_status = this.get('phases_status');
			return phases_status[phase_id] ? phases_status[phase_id] : '';
		},

		update_phase_status: function(phase_id, status) {
			var phases_status = jQuery.extend(true, {}, this.get('phases_status'));
			phases_status[phase_id] = status;
			this.set('phases_status', phases_status);
		},

		get_phase_progress: function(phase_id) {
			var phases_progress = this.get_phases_progress();
			return phases_progress[phase_id] ? phases_progress[phase_id] : 0;
		},

		get_phases_progress: function(phase_id) {
			if(!this.phases_progress) {
				this.get_progress();
			}
			return this.phases_progress;
		},

		schedule_fetch_phases_progress: function(model) {
			this.stopListening(model);
			this.listenToOnce(model, 'sync', _.bind(function() { this.fetch_phases_progress() }, this));
		},

		fetch_phases_progress: function(callback) {
			mybookprogress.WPQuery('mbp_get_book_phases_progress', {book_id: this.id}).then(_.bind(function(response) {
				if(response === null || typeof response !== 'object' || 'error' in response) { response = null; }
				if(response) { this.phases_progress = response; this.trigger('change:phases_progress'); }
			}, this));
		},

		get_next_phase: function() {
			var first_uncomplete = null;
			var first_working = null;

			var phases = this.get_phases();
			for(var i = 0; i < phases.length; i++) {
				var phase_status = this.get_phase_status(phases[i].id);
				var phase_progress = this.get_phase_progress(phases[i].id);
				if(phase_status !== 'complete') {
					if(!first_uncomplete) { first_uncomplete = phases[i]; }
					if(phase_progress > 0) {
						first_working = phases[i];
						break;
					}
				}
			}

			return first_working ? first_working : first_uncomplete;
		},

		is_complete: function() {
			var phases = this.get_phases();
			for(var i = phases.length - 1; i >= 0; i--) {
				if(this.get_phase_status(phases[i].id) !== 'complete') { return false; }
			}
			return true;
		},
	});

	mybookprogress.Books = WPCollection.extend({
		model: mybookprogress.Book,
		action: 'mbp_books'
	});
})();
(function () {
	mybookprogress.on('load_models', function() {
		mybookprogress.phase_templates = new mybookprogress.PhaseTemplates();
		mybookprogress.preload(mybookprogress.phase_templates.fetch());
	});

	mybookprogress.PhaseTemplate = mybookprogress.VirtualModel.extend({
		defaults: {
			name: '',
			phases: [],
			default: false,
		},
		sync: function(method, model, options) {
			setTimeout(_.bind(function(collection) { collection.save(); }, this, this.collection), 0);
			options.success();
		}
	});

	mybookprogress.PhaseTemplates = mybookprogress.WPCollection.extend({
		model: mybookprogress.PhaseTemplate,
		action: 'mbp_phase_templates',

		save: function() {
			this.sync('update', this);
		},
	});
})();
(function () {
	var books = null;
	var settings = null;

	mybookprogress.on('models_loaded', function() {
		books = mybookprogress.books;
		settings = mybookprogress.settings;
	});

	var WPModel = mybookprogress.WPModel;
	var WPCollection = mybookprogress.WPCollection;

	mybookprogress.ProgressEntry = WPModel.extend({
		action: 'mbp_progress_entry',
		defaults: {
			book_id: 0,
			phase_id: 0,
			phase_name: '',
			phase_complete: false,
			timestamp: 0,
			progress_type: '',
			progress: null,
			target: null,
			notes: '',
		},

		initialize: function() {
			if(this.collection) { this.set('book_id', this.collection.book_id); }
			this.check_phase_name();
			this.listenTo(this.get_book(), 'change:phases', this.check_phase_name);
		},

		check_phase_name: function() {
			var phase = this.get_book().get_phase(this.get('phase_id'));
			if(phase) {
				if(phase.name !== this.get('phase_name')) {
					this.set('phase_name', phase.name);
					if(!this.isNew()) { this.save(); }
				}
			}
		},

		get_book: function() {
			return books.get(this.get('book_id'));
		},

		get_sharing_url: function(callback) {
			var post_id = this.get('blog_post_id');
			if(!post_id && settings.get('mybooktable_social_media_link')) {
				var book = this.get_book();
				if(book && book.get('mbt_book')) {
					post_id = book.get('mbt_book');
				}
			}

			if(post_id) {
				mybookprogress.WPQuery('mbp_get_post_permalink', {post_id: post_id}).then(function(response) {
					if(typeof response !== 'string') { response = null; }
					callback(response);
				});
			} else {
				callback(null);
			}

			this.verify_blog_post();
		},

		verify_blog_post: function(callback) {
			callback = callback || function(){};
			var post_id = this.get('blog_post_id');
			if(!post_id) { return callback(false); }
			var self = this;
			mybookprogress.WPQuery('mbp_get_post_permalink', {post_id: post_id}).then(function(response) {
				if(typeof response === 'string') {
					callback(true);
				} else {
					self.save({'blog_post_id': null});
					callback(false);
				}
			});
		},
	});

	mybookprogress.BookProgress = WPCollection.extend({
		model: mybookprogress.ProgressEntry,
		action: 'mbp_book_progress',
		comparator: function(m) { return m.mbp_is_new ? -Math.pow(10, 10) : -m.get('timestamp'); },

		initialize: function(models, options) {
			if(typeof options.book_id !== 'undefined') { this.book_id = options.book_id; }
			if(!this.book_id) { throw 'must provide book id'; }

			this.last = 0;
			this.has_more = true;
		},

		get_book: function() {
			return books.get(this.book_id);
		},

		next_page: function() {
			if(this.fetching || !this.has_more_pages()) { return; }
			this.fetching = true;
			this.fetch({success: _.bind(function() { this.fetching = false; }, this)});
		},

		has_more_pages: function() {
			return this.has_more;
		},

		sync: function(method, model, options) {
			options.remove = false;
			var query = mybookprogress.WPQuery(this.action+'_'+method, {book_id: this.book_id, before: this.last});
			query.done(_.bind(function(response) {
				if(response === null || typeof response !== 'object') {
					options.error('invalid response');
					return;
				}
				if('error' in response) {
					options.error(response.error);
				} else {
					switch(method) {
						case 'read':
							this.last = response.object.last;
							this.has_more = response.object.has_more;
							if(options.success) { options.success(response.object.entries); }
							break;
						default:
							options.error();
							break;
					}
				}
			}, this));
			query.fail(function() {
				options.error();
			});
			return query;
		},
	});
})();
(function () {
	var books = null;

	mybookprogress.on('models_loaded', function() {
		books = mybookprogress.books;
	});

	var WPModel = mybookprogress.WPModel;

	mybookprogress.on('load_models', function() {
		mybookprogress.settings = new mybookprogress.Settings();
		mybookprogress.preload(mybookprogress.settings.fetch());
	});

	mybookprogress.Settings = WPModel.extend({
		action: 'mbp_settings',

		initialize: function() {
			this.on('change', this.onchange, this);
		},

		onchange: function(model, options) {
			if(!options || !options.no_save) { this.save(model.changedAttributes(), {patch: true}); }
		},

		fetch: function(options) {
			return WPModel.prototype.fetch.call(this, jQuery.extend(options, {no_save: true}));
		},
	});
})();
(function () {
	mybookprogress.on('load_models', function() {
		mybookprogress.style_packs = new mybookprogress.StylePacks();
		mybookprogress.preload(mybookprogress.style_packs.fetch());
	});

	mybookprogress.StylePack = mybookprogress.WPModel.extend({
		action: 'mbp_style_pack',
		defaults: {
			name: '',
			stylepack_uri: '',
			version: '',
			desc: '',
			author: '',
			author_uri: '',
			supports: [],
		}
	});

	mybookprogress.StylePacks = mybookprogress.WPCollection.extend({
		model: mybookprogress.StylePack,
		action: 'mbp_style_packs'
	});
})();
(function () {
	var utils = mybookprogress.utils;

	var View = mybookprogress.View = Backbone.View.extend({
		render: function() {
			this.$el.empty();
			if(this.template) { this.$el.html(this.template()); }
			this.render_subviews();
			this.render_bindings();
			return this;
		},

		render_subviews: function() {
			if(!this.subviews) { return; }
			_.each(this.subviews, this._render_subview, this);
		},

		render_subview: function(selector) {
			if(!this.subviews) { throw 'selector not found'; }
			var view = this.subviews[selector];
			if(!view) { throw 'selector not found'; }
			this._render_subview(view, selector);
			return this;
		},

		_render_subview: function(view, selector) {
			view.setElement(this.$(selector)).render();
		},

		set_subview: function(selector, view) {
			if(typeof selector !== 'string') { throw 'invalid selector'; }
			this.subviews = this.subviews || {};
			if(selector in this.subviews) { this.remove_subview(selector); }
			this.subviews[selector] = view;
			if(this.$(selector).length) { this._render_subview(view, selector); }
		},

		get_subview: function(selector) {
			return this.subviews[selector];
		},

		remove_subview: function(selector) {
			if(typeof this.subviews[selector] === 'undefined') { throw 'selector not found'; }
			this.subviews[selector].off();
			this.subviews[selector].stopListening();
			this.subviews[selector].undelegateEvents();
			delete this.subviews[selector];
			this.$(selector).empty();
		},

		render_bindings: function() {
			if(!this._binding_data) { return this; }
			for(var i = this._binding_data.length - 1; i >= 0; i--) {
				this._on_binding_change(this._binding_data[i].data, null, null, {});
			}
			return this;
		},

		_on_binding_input: function(binding_data, event) {
			this.model.set(binding_data.attribute, binding_data.from_input(jQuery(event.target).val(), this.model.get(binding_data.attribute)), {no_render: true});
		},

		_on_binding_change: function(binding_data, model, value, options) {
			if(options.no_render) { return; }
			var elements = binding_data.selector ? this.$(binding_data.selector) : this.$el;
			elements.val(binding_data.to_input(this.model.get(binding_data.attribute)));
		},

		delegateEvents: function(events) {
			Backbone.View.prototype.delegateEvents.call(this, events);
			var bindings = _.result(this, 'bindings');
			if(!bindings || !this.model) { return this; }
			var splitter_regex = /^(\S+)\s*(.*)$/;
			this._binding_data = [];
			for(var key in bindings) {
				var binding_data = {};

				var methods = bindings[key];
				if(!methods) {
					binding_data.from_input = _.identity;
					binding_data.to_input = _.identity;
				} else if(typeof methods === 'string') {
					var match = methods.match(splitter_regex);
					if(match) {
						binding_data.from_input = match[1];
						binding_data.to_input = match[2];
					}
				} else {
					binding_data.from_input = methods.from_input;
					binding_data.to_input = methods.to_input;
				}
				if(typeof binding_data.from_input === 'string') { binding_data.from_input = this[binding_data.from_input]; }
				if(typeof binding_data.to_input === 'string') { binding_data.to_input = this[binding_data.to_input]; }

				if(!_.isFunction(binding_data.to_input)) { binding_data.to_input = _.identity; }
				if(!_.isFunction(binding_data.from_input)) { throw 'invalid methods for binding'; }
				binding_data.to_input = _.bind(binding_data.to_input, this);
				binding_data.from_input = _.bind(binding_data.from_input, this);

				var match = key.match(splitter_regex);
				if(!match) { throw 'invalid attribute/selector for binding'; }

				binding_data.attribute = match[1];
				binding_data.selector = match[2];

				var on_input = _.bind(this._on_binding_input, this, binding_data);
				var input_event = 'input.delegateEvents' + this.cid;
				var change_event = 'change.delegateEvents' + this.cid;
				if(binding_data.selector === '') {
					this.$el.on(input_event, on_input);
					this.$el.on(change_event, on_input);
				} else {
					this.$el.on(input_event, binding_data.selector, on_input);
					this.$el.on(change_event, binding_data.selector, on_input);
				}

				var on_change = _.bind(this._on_binding_change, this, binding_data);
				var change_event = 'change:'+binding_data.attribute;
				this.listenTo(this.model, change_event, on_change);

				this._binding_data.push({data: binding_data, model: this.model, event: change_event, on_change: on_change});
			}
			return this;
		},

		undelegateEvents: function() {
			Backbone.View.prototype.undelegateEvents.call(this);
			if(!this._binding_data) { return this; }
			for(var i = this._binding_data.length - 1; i >= 0; i--) {
				var binding_data = this._binding_data[i];
				this.stopListening(binding_data.model, binding_data.event, binding_data.on_change);
			}
			this._binding_data = null;
			return this;
		},
	});

	mybookprogress.CollectionView = Backbone.View.extend({
		initialize: function(options) {
			options = options || {};
			if(typeof options.item_view !== 'undefined') { this.item_view = options.item_view; }
			this.sortable = this.sortable || options.sortable || false;

			if(!this.item_view) { throw 'no item view constructor provided'; }
			if(this.sortable && !('sorting_attr' in this.sortable)) {
				throw('sortable collection must have model sorting attribute provided');
			}

			if(this.sortable) {
				this.collection.sortBy(function(m) { return m.get(this.sortable['sorting_attr']); }, this);
			}

			this.item_views = [];
			this.collection.each(function(model, i) {
				var new_view = new this.item_view({model: model, parent: this});
				this.item_views.push(new_view);
				if(this.sortable) { model.set(this.sortable['sorting_attr'], i); }
			}, this);

			this.collection.on('add', this.add, this);
			this.collection.on('remove', this.remove, this);
			this.collection.on('sort', this.onsort, this);
			this.collection.on('reset', this.onreset, this);
		},

		add: function(model) {
			var new_view = new this.item_view({model: model});
			var index = null;

			if(this.sortable) {
				this.item_views.push(new_view);
				this.item_views = _.sortBy(this.item_views, function(view) { return view.model.get(this.sortable['sorting_attr']); }, this);
				_.each(this.item_views, function(view, i) { view.model.set(this.sortable['sorting_attr'], i); }, this);
				this.collection.sortBy(function(model) { return model.get(this.sortable['sorting_attr']); }, this);
				index = _.indexOf(this.item_views, new_view);
			} else {
				index = _.indexOf(this.collection.models, model);
				if(this.collection.models.length > this.item_views.length + 1) {
					if(index === this.collection.models.length-1) {
						index = this.item_views.length;
					} else {
						index = this.item_views.length;
						for(var i = 0; i < this.item_views.length; i++) {
							if(this.item_views[i].model === this.collection.models[index+1]) {
								index = i;
							}
						}
					}
				}
				this.item_views.splice(index, 0, new_view);
			}

			if(index == 0) {
				this.$el.prepend(new_view.render().el);
			} else {
				this.item_views[index-1].$el.after(new_view.render().el);
			}
		},

		remove: function(model) {
			var old_view = _(this.item_views).select(function(view) { return view.model === model; })[0];
			this.item_views = _(this.item_views).without(old_view);
			old_view.remove();

			if(this.sortable) {
				this.sortupdate();
			}
		},

		render: function() {
			this.$el.empty();

			_.each(this.item_views, function(view) {
				this.$el.append(view.setElement(view.el).render().el);
			}, this);

			if(this.sortable && !this.$el.data("ui-sortable")) {
				this.$el.sortable(this.sortable).on('sortupdate', _.bind(this.sortupdate, this));
			}

			return this;
		},

		onsort: function() {
			if(this.collection.comparator && _.isFunction(this.collection.comparator) && this.collection.comparator.length === 1) {
				this.item_views = _.sortBy(this.item_views, function(v) { return this.collection.comparator(v.model); }, this);
			}

			this.render();
		},

		onreset: function() {
			this.item_views = [];
			this.collection.each(function(model, i) {
				var new_view = new this.item_view({model: model});
				this.item_views.push(new_view);
				if(this.sortable) { model.set(this.sortable['sorting_attr'], i); }
			}, this);
			this.render();
		},

		sortupdate: function() {
			_.each(this.item_views, function(view) { view.model.set(this.sortable['sorting_attr'], view.$el.index()); }, this);
			this.item_views = _.sortBy(this.item_views, function(view) { return view.model.get(this.sortable['sorting_attr']); }, this);
			this.collection.models = _.sortBy(this.collection.models, function(model) { return model.get(this.sortable['sorting_attr']); }, this);
			this.collection.trigger('sort');
		},

		model_view: function(model) {
			for(var i = this.item_views.length - 1; i >= 0; i--) {
				if(this.item_views[i].model == model) { return this.item_views[i]; }
			}
			return null;
		},

		each: function(iteratee, context) {
			return _.each(this.item_views, iteratee, context);
		},
	});

	mybookprogress.TabsView = View.extend({
		add_tab: function(slug, name, view) {
			this.tabs = this.tabs || [];
			this.tabs.push({slug: slug, name: name, view: view});
		},

		remove_tab: function(slug) {
			for(var i = this.tabs.length - 1; i >= 0; i--) {
				if(this.tabs[i].slug == slug) {
					this.item_views.splice(i, 1);
					return;
				}
			}
		},

		get_tab: function(slug) {
			for(var i = this.tabs.length - 1; i >= 0; i--) {
				if(this.tabs[i].slug == slug) {
					return this.tabs[i].view;
				}
			}
		},

		render: function() {
			View.prototype.render.call(this);
			this.render_tabs();
			return this;
		},

		render_tabs: function() {
			if(!this.tabs) { return; }

			var root = this.get_root();
			var nav = this.get_nav();
			var content = this.get_content();

			_.each(this.tabs, function(tab) {
				nav.append(jQuery('<li><a href="#'+tab.slug+'">'+tab.name+'</a></li>'));
				content.append(tab.view.setElement(tab.view.el).render().$el.attr('id', tab.slug));
			}, this);

			root.tabs({activate: _.bind(this.on_tabsactivate, this)});
			this.tabs_initialized = true;
			if(this.activatetab) { this.focus_tab(this.activatetab); this.activatetab = null; }
		},

		on_tabsactivate: function(event, ui) {
			this.trigger('activatetab', ui.newPanel.attr('id'));
		},

		focus_tab: function(slug) {
			if(!this.tabs_initialized) { this.activatetab = slug; }
			for(var i = this.tabs.length - 1; i >= 0; i--) {
				if(this.tabs[i].slug == slug) {
					this.get_root().tabs({active: i});
				}
			}
		},

		get_root: function() {
			return this.tabs_root ? this.$(this.tabs_root) : this.$el;
		},

		get_nav: function() {
			return this.get_root().find(this.tabs_nav ? this.tabs_nav : 'ol, ul').eq(0);
		},

		get_content: function() {
			return this.get_root().find(this.tabs_content ? this.tabs_content : 'div').eq(0);
		},
	});

	mybookprogress.ModalView = View.extend({
		initialize: function(options) {
			this.open(options);
		},

		open: function(options) {
			utils.modal(this.render().$el, options);
		},

		close: function() {
			tb_remove();
		},
	});

	mybookprogress.HiddenView = View.extend({
		render: function() {
			this.$el.hide();
			return this;
		},
	});
})();
(function () {
	mybookprogress.AdminView = mybookprogress.TabsView.extend({
		el: jQuery('#mbp-admin-page'),
		template: mybookprogress.utils.template(jQuery('#admin_template').html()),
		initialize: function() {
			this.tabs_root = '.mbp-admin-tabs';
			this.tabs_content = '.mbp-admin-tabs-content';
			this.add_tab('mbp-progress-tab', mybookprogress_i18n.progress, mybookprogress.progress_tab = new mybookprogress.ProgressTabView());
			this.add_tab('mbp-promote-tab', mybookprogress_i18n.promote, mybookprogress.promote_tab = new mybookprogress.PromoteTabView());
			this.add_tab('mbp-style-tab', mybookprogress_i18n.style, mybookprogress.style_tab = new mybookprogress.StyleTabView());
			this.add_tab('mbp-display-tab', mybookprogress_i18n.display, mybookprogress.display_tab = new mybookprogress.DisplayTabView());
			this.add_tab('mbp-upgrade-tab', mybookprogress_i18n.upgrade, mybookprogress.upgrade_tab = new mybookprogress.UpgradeTabView());
			this.set_subview('.mbp-help', mybookprogress.help = new mybookprogress.HelpView());
			this.set_subview('.mbp-sir-walter', mybookprogress.sir_walter = new mybookprogress.SirWalterView());
			this.render();

			if(mybookprogress.utils.get_query_var('tab')) { this.focus_tab(mybookprogress.utils.get_query_var('tab')); }
			this.on('activatetab', function(slug) { mbp_track_event('view_'+slug.replace(/-/g, '_')); });
		}
	});
})();
(function () {
	var settings = null;
	var utils = mybookprogress.utils;

	mybookprogress.on('models_loaded', function() {
		settings = mybookprogress.settings;
	});

	var View = mybookprogress.View;

	mybookprogress.HelpView = View.extend({
		template: mybookprogress.utils.template(jQuery('#help_template').html()),

		events: {
			'click .mbp-help-button': 'toggle_help',
			'click .mbp-help-box-close': 'toggle_help',
		},

		initialize: function() {
			this.show_search();
		},

		render: function() {
			View.prototype.render.call(this);
			return this;
		},

		toggle_help: function() {
			if(this.$('.mbp-help-box').is(':visible')) {
				this.$('.mbp-help-box').hide(300);
			} else {
				this.$('.mbp-help-box').show(300);
				this.show_search();
			}
		},

		show_topic: function(topic, ref_data) {
			var topic = mybookprogress.help_topics.get(topic);
			if(topic) {
				if(typeof ref_data === 'string') { ref_data = {type: 'string', string: ref_data}; }

				//check for refrence loops
				if(ref_data.ref) {
					var ref = ref_data.ref;
					while(ref) {
						if(ref.type === 'topic' && ref.topic === topic.slug) {
							ref_data = ref.ref;
						}
						ref = ref.ref;
					}
				}

				this.set_subview('.mbp-help-box-content', new mybookprogress.HelpTopicView({topic: topic, ref_data: ref_data}));
			}
		},

		show_search: function(search) {
			this.set_subview('.mbp-help-box-content', new mybookprogress.HelpSearchView({search: search}));
		},
	});

	mybookprogress.HelpSearchView = View.extend({
		template: mybookprogress.utils.template(jQuery('#help_search_template').html()),

		events: {
			'input .mbp-help-search': 'update_search',
			'click .mbp-help-topic-link': 'topic_clicked',
		},

		initialize: function(options) {
			this.search = '';
			if(options && options.search) { this.search = options.search; }
		},

		render: function() {
			View.prototype.render.call(this);
			this.$('.mbp-help-search').val(this.search);
			this.render_search();
			this.render_common();
		},

		topic_clicked: function(e) {
			this.track_search();
			if(jQuery(e.target).parents('.mbp-help-common').length) {
				mybookprogress.help.show_topic(jQuery(e.target).attr('data-mbp-help-topic'), 'common-topics');
			} else {
				mybookprogress.help.show_topic(jQuery(e.target).attr('data-mbp-help-topic'), {type: 'search', search: this.search});
			}
		},

		update_search: function() {
			this.search = this.$('.mbp-help-search').val();
			this.render_search();
			this.schedule_track_search();
		},

		render_search: function() {
			if(this.search) {
				var help_topics = [];
				for(slug in mybookprogress.help_topics) {
					help_topics.push(mybookprogress.help_topics.get(slug));
				}

				try {
					var search_topics = new Fuse(help_topics, {
						keys: ['title', 'keywords'],
					}).search(this.search);
					search_topics = _.first(search_topics, 6);
				} catch(e) {
					search_topics = [];
				}

				this.$('.mbp-help-search-results ul').empty();
				if(search_topics.length) {
					_.each(search_topics, function(topic) {
						this.$('.mbp-help-search-results ul').append(jQuery('<li><span class="mbp-help-topic-link" data-mbp-help-topic="'+topic.slug+'">'+topic.title+'</span></li>'));
					}, this);
				} else {
					this.$('.mbp-help-search-results ul').append(jQuery('<li class="no-results">No results</li>'));
				}

				if(this.$('.mbp-help-common').is(':visible')) { this.$('.mbp-help-common').hide(300); }
			}
		},

		render_common: function() {
			var common_topics = [];
			for(slug in mybookprogress.help_topics) {
				if(mybookprogress.help_topics.get(slug).common === true) {
					common_topics.push(mybookprogress.help_topics.get(slug));
				}
			}

			this.$('.mbp-help-common ul').empty();
			_.each(common_topics, function(topic) {
				this.$('.mbp-help-common ul').append(jQuery('<li><span class="mbp-help-topic-link" data-mbp-help-topic="'+topic.slug+'">'+topic.title+'</span></li>'));
			}, this);
		},

		schedule_track_search: function() {
			if(this.track_search_timer) { clearTimeout(this.track_search_timer); }
			this.track_search_timer = setTimeout(_.bind(this.track_search, this), 2000);
		},

		track_search: function() {
			if(this.track_search_timer) {
				clearTimeout(this.track_search_timer);
				this.track_search_timer = null;
				mbp_track_event('help_search', {search: this.search});
				//console.log(this.search);
			}
		},
	});

	mybookprogress.HelpTopicView = View.extend({
		template: mybookprogress.utils.template(jQuery('#help_topic_template').html()),

		events: {
			'click .mbp-help-search-link': 'search_clicked',
			'click .mbp-help-topic-link': 'topic_clicked',
			'click .mbp-help-topic-feedback-yes': 'feedback_helpful',
			'click .mbp-help-topic-feedback-no': 'feedback_unhelpful',
		},

		initialize: function(options) {
			this.topic = options.topic;
			this.ref_data = options.ref_data;
		},

		render: function(options) {
			View.prototype.render.call(this);
			this.render_breadcrumbs();
		},

		search_clicked: function(e) {
			mybookprogress.help.show_search(jQuery(e.target).attr('data-mbp-help-search'));
		},

		topic_clicked: function(e) {
			mybookprogress.help.show_topic(jQuery(e.target).attr('data-mbp-help-topic'), {type:'topic', topic: this.topic.slug, ref:this.ref_data});
		},

		render_breadcrumbs: function() {
			this.$('.mbp-help-topic-breadcrumbs').empty();
			var ref = this.ref_data;
			while(ref) {
				if(ref.type === 'search') {
					var search = ref.search.length > 8 ? ref.search.substring(0, 8)+'...' : ref.search;
					var link = jQuery('<span class="mbp-help-search-link"></span>');
					link.attr('data-mbp-help-search', ref.search);
					link.text('Search: "'+search+'"');
					var new_breadcrumb = jQuery('<span class="mbp-breadcrumb"></span>').append(link);
					this.$('.mbp-help-topic-breadcrumbs').prepend(jQuery('<span class="mbp-separator">&raquo;</span>'));
					this.$('.mbp-help-topic-breadcrumbs').prepend(new_breadcrumb);
				} else if(ref.type === 'topic') {
					var topic = mybookprogress.help_topics.get(ref.topic);
					var new_breadcrumb = jQuery('<span class="mbp-breadcrumb"><span class="mbp-help-topic-link" data-mbp-help-topic="'+ref.topic+'">'+topic.short_title+'</span></span>');
					this.$('.mbp-help-topic-breadcrumbs').prepend(jQuery('<span class="mbp-separator">&raquo;</span>'));
					this.$('.mbp-help-topic-breadcrumbs').prepend(new_breadcrumb);
				}
				ref = ref.ref;
			}
			if(!this.$('.mbp-help-topic-breadcrumbs').find('.mbp-help-search-link').length) {
				this.$('.mbp-help-topic-breadcrumbs').prepend(jQuery('<span class="mbp-separator">&raquo;</span>'));
				this.$('.mbp-help-topic-breadcrumbs').prepend(jQuery('<span class="mbp-breadcrumb"><span class="mbp-help-search-link" data-mbp-help-search="">'+mybookprogress_i18n.search+'</span></span>'));
			}
			this.$('.mbp-help-topic-breadcrumbs').append(jQuery('<span class="mbp-breadcrumb">'+this.topic.short_title+'</span>'));
		},

		feedback_helpful: function() {
			if(this.left_feedback) { return; }

			var track_helpful = _.bind(function() {
				mbp_track_event('help_feedback', {helpful: true, topic: this.topic.slug, ref:this.ref_data});
				this.show_feedback_message();
			}, this);

			this.confirm_enable_tracking(track_helpful);
		},

		feedback_unhelpful: function() {
			if(this.left_feedback) { return; }

			var track_unhelpful = _.bind(function() {
				mbp_track_event('help_feedback', {helpful: false, topic: this.topic.slug, ref:this.ref_data});
				this.show_feedback_message();
			}, this);

			this.confirm_enable_tracking(track_unhelpful);
		},

		confirm_enable_tracking: function(on_confirm) {
			if(settings.get('allow_tracking') !== 'yes') {
				var enable_tracking_modal = jQuery(mybookprogress.utils.template(jQuery('#help_enable_tracking_modal_template').html())());
				enable_tracking_modal.on('click', '.mbp-yes', _.bind(function() {
					settings.set('allow_tracking', 'yes');
					on_confirm();
					tb_remove();
				}, this));
				enable_tracking_modal.on('click', '.mbp-no', tb_remove);
				utils.modal(enable_tracking_modal);
			} else {
				on_confirm();
			}
		},

		show_feedback_message: function() {
			var message = mybookprogress_i18n.thank_you_for_feedback;
			var options = {
				position: {
					my: "right top+15",
					at: "right bottom",
					collision: "none",
					using: function(position, feedback) {
						jQuery(this).css(position);
						jQuery("<div>").addClass("arrow right top").appendTo(this);
					}
				}
			};
			var elements = this.$('.mbp-help-topic-feedback-yes, .mbp-help-topic-feedback-no');
			this.left_feedback = true;
			mybookprogress.utils.tooltip(elements, message, options);
		},
	});

	mybookprogress.help_topics = mybookprogress.help_topics || {};
	mybookprogress.help_topics.get = function(slug) {
		var topic = mybookprogress.help_topics[slug];
		if(topic) {
			topic = _.clone(topic);
			topic.slug = slug;
			return topic;
		}
	}

	mybookprogress.help_topics.book_setup = {
		common: true,
		short_title: 'Book Setup',
		title: 'How do I set up my book?',
		keywords: 'setup, edit, change, update, modify, book, title, phase, deadline, goal, target',
		content: ' ' +
'<ol> ' +
'<li>Click the "Setup" button next to the title of your book at the top of the Progress tab.</li> ' +
'<li>Enter the title of your book.</li> ' +
'<li>Select a template for your book <span class="mbp-help-topic-link" data-mbp-help-topic="phases">Phases</span>.</li> ' +
'<li>Click on each <span class="mbp-help-topic-link" data-mbp-help-topic="phases">Phase</span> to customize its deadline and goals. (optional)</li> ' +
'<li>Click the "Save Book" button at the bottom of the page.</li> ' +
'</ol> ' +
'',
	};
	mybookprogress.help_topics.phases = {
		short_title: 'Phases',
		title: 'What are book phases?',
		keywords: 'book, phase, sections',
		content: ' ' +
'<p> ' +
'Publishing a book requires more than just typing into Microsoft Word. Phases allow you to track and show progress across the entire publishing process, from outlining to proofreading. ' +
'</p> ' +
'<p> ' +
'Different kinds of books track progress in different ways. We have created some templates to get you started. You can customize any of these templates, by adding, removing, and modifying the phases. ' +
'</p> ' +
'<p> ' +
'You can track your progress within each phase by chapters, pages, words, scenes, or with a percentage slider. ' +
'</p> ' +
'',
	};
	mybookprogress.help_topics.add_books = {
		short_title: 'Add Books',
		title: 'How do I add a second book?',
		keywords: 'add, new, additional, create, make, book',
		content: ' ' +
'<p> ' +
'<em>To add multiple books you will need to <span class="mbp-help-topic-link" data-mbp-help-topic="upgrades">upgrade</span> to the professional version of MyBookProgress.</em> ' +
'</p> ' +
'<p> ' +
'To add an additional book, select the "Add New Book" option from the "Books" drop down menu at the top of the Progress tab on your MyBookProgress page. ' +
'This will take you to the <span class="mbp-help-topic-link" data-mbp-help-topic="book_setup">Book Setup</span> page which will allow you to customize its title, phases, etc. ' +
'</p> ' +
'',
	};
	mybookprogress.help_topics.update_progress = {
		short_title: 'Updating Progress',
		title: 'How do I update my progress?',
		keywords: 'add, new, additional, create, make, update, progress',
		content: ' ' +
'<ol> ' +
'<li>On the Progress tab, scroll down to the section that says "Update Progress".</li> ' +
'<li>Enter your total progress that you have made so far. So if you had already written 2000 words, and wrote 1500 more words today, you will want to type "3500" into the current progress field.</li> ' +
'<li>Click "Save progress"</li> ' +
'</ol> ' +
'<p> ' +
'See also: <span class="mbp-help-topic-link" data-mbp-help-topic="edit_progress">Editing Progress</span> ' +
'</p> ' +
'',
	};
	mybookprogress.help_topics.edit_progress = {
		short_title: 'Edit Progress',
		title: 'How do I edit my progress?',
		keywords: 'edit, change, update, modify, progress, date, time, value',
		content: ' ' +
'<ol> ' +
'<li>Scroll down on the Progress Tab to the Progress Timeline under the Update Progress section.</li> ' +
'<li>Click on the progress entry you want to edit.</li> ' +
'<li>Click the "Edit" button.</li> ' +
'<li>To save your changes, click away from that entry. Once it collapses back down to its normal state, your changes have been saved.</li> ' +
'</ol> ' +
'<p> ' +
'See also: <span class="mbp-help-topic-link" data-mbp-help-topic="share_progress">Sharing Progress</span> ' +
'</p> ' +
'',
	};
	mybookprogress.help_topics.share_progress = {
		short_title: 'Share Progress',
		title: 'How do I share my progress?',
		keywords: 'facebook, twitter, blog, share, social, media, progress',
		content: ' ' +
'<ol> ' +
'<li>Scroll down on the Progress Tab to the Progress Timeline under the Update Progress section.</li> ' +
'<li>Click on the progress entry you want to share.</li> ' +
'<li>Enter a message about your progress in the text box that appears.</li> ' +
'<li>Click on one of the social sharing buttons below the text box to share it to your blog, facebook, or twitter.</li> ' +
'</ol> ' +
'<p> ' +
'See also: <span class="mbp-help-topic-link" data-mbp-help-topic="edit_progress">Editing Progress</span> ' +
'</p> ' +
'',
	};
	mybookprogress.help_topics.style_packs = {
		short_title: 'Style Packs',
		title: 'What are Style Packs?',
		keywords: 'look, display, change, modify',
		content: ' ' +
'<p> ' +
'Style packs change the look and feel of your progress bar. MyBookTable comes with two style packs by default. If you are a savvy web developer, you can make your own style pack. ' +
'You can also get more style packs by upgrading to MyBookProgress Pro (coming soon). ' +
'</p> ' +
'',
	};
	mybookprogress.help_topics.mybooktable_integration = {
		short_title: 'MyBookTable Integration',
		title: 'What is MyBookTable Integration?',
		keywords: 'my, book, table, booktable, mybooktable, integration',
		content: ' ' +
'<p> ' +
'MyBookTable is the #1 Bookstore Plugin for WordPress. MyBookTable allows you to create a Google-friendly book page for your book with Pre-Order buttons, product description, and more. ' +
'</p> ' +
'<p> ' +
'With MyBookTable integration, you can connect your Progress Bar with your MyBookTable Book page. ' +
'</p> ' +
'',
	};
	mybookprogress.help_topics.linking_back = {
		short_title: 'Linking Back',
		title: 'Why should I link back to Author Media?',
		keywords: 'what, link, back, linkback, share, the, love',
		content: ' ' +
'<p> ' +
'This helps more people discover MyBookProgress and saves you from answering emails from people asking you what plugin you are using for your progress bar. ' +
'</p> ' +
'',
	};
	mybookprogress.help_topics.mailchimp_settings = {
		short_title: 'MailChimp Settings',
		title: 'How do I set up MailChimp?',
		keywords: 'mailchimp, mailing, list, setup',
		content: ' ' +
'<ol> ' +
'<li>Log into your MailChimp account and copy your MailChimp API Key. For steps on how to do this, see <a href="http://kb.mailchimp.com/accounts/management/about-api-keys" target="_blank">How to Find Your MailChimp API Key</a>.</li> ' +
'<li>Go to the Promote tab on your MyBookProgress page</li> ' +
'<li>Select the "MailChimp" option as your mailing list service.</li> ' +
'<li>Paste your MailChimp API Key from step 1 into the field labeled "Enter MailChimp API Key:"</li> ' +
'<li>Click the refresh button next to the MailChimp API Key field. If your key shows up as red, that means that it is invalid. Check to make sure you did not paste in an extra space at the beginning or end of the API Key.</li> ' +
'<li>Select the MailChimp list that you want your users to be subscribed to.</li> ' +
'</ol> ' +
'',
	};
	mybookprogress.help_topics.other_mailinglist_settings = {
		short_title: 'Other Mailinglist',
		title: 'How do I use the "Other" mailing list?',
		keywords: 'other, mailing, list, setup',
		content: ' ' +
'<p> ' +
'The "Other" option on the <span class="mbp-help-topic-link" data-mbp-help-topic="mailing_lists">Mailing List Settings</span> section of the Promote tab is used when you have a mailing list service that is not supported by MyBookProgress, ' +
'or you simply want to redirect users to a page instead of using a mailing list service. You can input any URL into the "list subscribe URL" field, and your users will be redirected there when they choose to recieve your updates. ' +
'</p> ' +
'',
	};
	mybookprogress.help_topics.mailing_lists = {
		common: true,
		short_title: 'Mailing Lists',
		title: 'How do I set up my mailing list?',
		keywords: 'mailing, list, service, provider, signup, setup, change',
		content: ' ' +
'<ol> ' +
'<li>Go to the Promote tab on your MyBookProgress page</li> ' +
'<li>Choose which mailing list service you are using. If you want to simply redirect users to a page instead of using a mailing list service, choose "Other".</li> ' +
'<li>Configure your mailing list service settings. See <span class="mbp-help-topic-link" data-mbp-help-topic="mailchimp_settings">"MailChimp" Settings</span> and ' +
'<span class="mbp-help-topic-link" data-mbp-help-topic="other_mailinglist_settings">"Other" Settings</span>.</li> ' +
'</ol> ' +
'',
	};
	mybookprogress.help_topics.widgets = {
		common: true,
		short_title: 'Widgets',
		title: 'What widget options are available?',
		keywords: 'widget, show, progress, display',
		content: ' ' +
'<p> ' +
'Go to the "Widgets" page, under "Appearance" on your WordPress admin menu. You should then see the "MyBookProgress" option in the "Available Widgets" section. ' +
'Drag it over to the appropriate widget section where you would like it to display (footer, sidebar, homepage, etc). ' +
'If there is a place on your website you want to add a widget and don’t see it as an option, talk to your theme developer about adding a widgetized zone or use a shortcode. ' +
'</p> ' +
'<p> ' +
'Once you have created the MyBookProgress widget, you can then customize its settings by clicking on it to expand the settings box. The MyBookProgress widget is capable of displaying the progress of all your books or only a single one. ' +
'</p> ' +
'',
	};
	mybookprogress.help_topics.shortcodes = {
		common: true,
		short_title: 'Shortcodes',
		title: 'What shortcode options are available?',
		keywords: 'shortcode, show, progress, display',
		content: ' ' +
'<p> ' +
'MyBookProgress has a simple shortcode wizard that helps you easily add MyBookTable shortcodes to your site. ' +
'Just click the "Insert Shortcode" button above the post content editor when editing a post or page and the wizard will appear and walk you through the shortcode options. ' +
'</p> ' +
'<p> ' +
'All of the MyBookProgress shortcodes use the [mybookprogress] core. Inserting [mybookprogress] by itself will show all of your books. ' +
'</p> ' +
'<p> ' +
'[mybookprogress]<br> ' +
'Show progress for all books ' +
'</p> ' +
'<p> ' +
'[mybookprogress book="1"] ' +
'Show book progress for book 1. The first book you add will be book 1, the second book will be book 2, and so on. ' +
'</p> ' +
'<p> ' +
'[mybookprogress book="1" showsubscribe="false"] ' +
'Show progress for book 1 but do not show the subscribe button. If you want to show the subscribe button, replace “false” with “true.” ' +
'</p> ' +
'<p> ' +
'[mybookprogress book="1" showsubscribe="true" simplesubscribe="false"] ' +
'Show progress for book 1. Show the subscribe button. Do not use the simple subscribe form. ' +
'</p> ' +
'<p> ' +
'[mybookprogress progress_id="21"] ' +
'Show progress at a specific point in time. The only way to use this shortcode is to either create it through the wizard or to click the "make blog post" button in the Progress tab of MyBookProgress. ' +
'This will create a snapshot of the current moment in time that won\'t change as you make more progress in the future. ' +
'</p> ' +
'',
	};
	mybookprogress.help_topics.upgrades = {
		common: true,
		short_title: 'Upgrades',
		title: 'What is an upgrade?',
		keywords: 'purchase, upgrade, nudges, email updates, better, faster, stronger',
		content: ' ' +
'<p> ' +
'Selling upgrades is the primary way we fund the development of MyBookProgress. By purchasing an upgrade you help make MyBookProgress even better. ' +
'</p> ' +
'<p> ' +
'To purchase an upgrade, go to <a href="http://www.mybookprogress.com" target="_blank">www.mybookprogress.com</a> and purchase an Upgrade License Key. ' +
'This License Key will allow you to download and use your MyBookProgress Upgrade plugin. You must enter this Key on the Upgrade tab of your MyBookProgress page to enable your Upgrade. ' +
'</p> ' +
'',
	};
})();
(function () {
	var settings = null;

	mybookprogress.on('models_loaded', function() {
		settings = mybookprogress.settings;
	});

	var View = mybookprogress.View;

	mybookprogress.SirWalterView = View.extend({
		events: {
			'click .mbp-sw-speech-response': 'respond',
		},

		initialize: function() {
			this.state = _.clone(settings.get('sir_walter_state')) || {};
			this.show_greeting();
		},

		find_new_index: function(list, last_index) {
			do { var index = Math.floor(Math.random()*list.length); } while(index === last_index);
			return index;
		},

		save_state: function() {
			settings.set('sir_walter_state', _.clone(this.state));
		},

		show_greeting: function() {
			var greeting_num = this.find_new_index(mybookprogress.sir_walter_text.greetings, this.state.last_greeting);
			var greeting = mybookprogress.sir_walter_text.greetings[greeting_num];

			this.state.last_greeting = greeting_num;
			this.save_state();

			this.show_text(greeting.text, greeting.responses);
		},

		show_random: function() {
			var random_text_num = this.find_new_index(mybookprogress.sir_walter_text.random_texts, this.state.last_random_text);
			var random_text = mybookprogress.sir_walter_text.random_texts[random_text_num];

			var random_response_num = this.find_new_index(mybookprogress.sir_walter_text.random_responses, this.state.last_random_response);
			var random_response = mybookprogress.sir_walter_text.random_responses[random_response_num];

			this.state.last_random_text = random_text_num;
			this.state.last_random_response = random_response_num;
			this.save_state();

			this.show_text(random_text, [random_response]);
		},

		respond: function() {
			this.show_random();
		},

		show_text: function(text, responses) {
			if(!_.isString(text)) { text = ''; }
			if(!_.isArray(responses)) { responses = []; }
			this.text = text;
			this.responses = responses;
			this.render();
		},

		render: function() {
			var new_speech = '<div class="mbp-sw-speech-text"><div class="mbp-sw-speech-text-inner">'+this.text+'</div></div>';
			if(this.responses.length) {
				new_speech += '<div class="mbp-sw-speech-responses">';
				for(i in this.responses) {
					new_speech += '<div class="mbp-sw-speech-response">'+this.responses[i]+'</div>';
				}
				new_speech += '</div>';
			}
			this.$('.mbp-sw-speech').empty().append(jQuery(new_speech));
			return this;
		},
	});

	mybookprogress.sir_walter_text = {
		greetings: [
			{
				text: '<p>Good day, my dear friend!</p><p>Hast thou made progress on thine book lately?</p>',
				responses: ['Good day, sir!'],
			},
			{
				text: '<p>Delightful to see you again!</p><p>You do look splendid today, if I may be so bold.</p>',
				responses: ['Why, thank you!'],
			},
			{
				text: '<p>Hast thou made progress on thine book? Jolly good!</p>',
				responses: ['Indeed, good sir!'],
			},
		],
		random_texts: [
			'<p>There is no such thing as an &#8220;aspiring writer.&#8221; If you write you are a writer.</p>',
			'<p>Don\'t fear writing. Fear <em>not writing</em>, a vile beast indeed. You slay that monster every time you lay pen to paper.</p>',
			'<p>Write through the pain. Readers connect with pain better than they do with perfection.</p>',
			'<p class="mbp-small-text">&#8220;Keep writing. Keep doing it and doing it. Even in the moments when it\'s so hurtful to think about writing.&#8221;</p><p class="mbp-small-text">&#126;Don Draper</p>',
			'<p>&#8220;You can\'t wait for inspiration. You have to go after it with a club.&#8221;</p><p>&#126;Jack London</p>',
			'<p class="mbp-small-text">&#8220;If you want to be a writer, you must do two things above all others: read a lot and write a lot.&#8221;</p><p class="mbp-small-text">&#126;Stephen King</p>',
			'<p>&#8220;A professional writer is an amateur who didn\'t quit.&#8221;</p><p>&#126;Richard Bach</p>',
			'<p>&#8220;Writing is an act of faith, not a trick of grammar.&#8221;</p><p>&#126;E. B. White</p>',
			'<p class="mbp-small-text">&#8220;Opportunities are usually disguised as hard work, so most people don\'t recognize them.&#8221;</p><p class="mbp-small-text">&#126;Ann Landers</p>',
			'<p class="mbp-small-text">&#8220;If my doctor told me I had only six minutes to live, I wouldn\'t brood. I\'d type a little faster.&#8221;</p><p class="mbp-small-text">&#126;Isaac Asimov</p>',
			'<p>&#8220;You don\'t make art out of good intentions.&#8221;</p><p>&#126;Gustave Flaubert</p>',
			'<p>&#8220;Only a mediocre person is always at his best.&#8221;</p><p>&#126;W. Somerset Maugham</p>',
			'<p>&#8220;Make it simple but significant.&#8221;</p><p>&#126;Don Draper</p>',
			'<p class="mbp-small-text">&#8220;Working on the right thing is usually more important than working hard.&#8221;</p><p class="mbp-small-text">&#126;Caterina Fake</p>',
			'<p>&#8220;Creativity is a drug I cannot live without.&#8221;</p><p>&#126;Cecil B. DeMille</p>',
		],
		random_responses: [
			'Go on, sir&hellip;',
			'Tell me more&hellip;',
			'You don\'t say!',
		],
	};

})();
(function () {
	var utils = mybookprogress.utils;
	var settings = null;
	var books = null;
	var progress_tab = null;

	mybookprogress.on('models_loaded', function() {
		settings = mybookprogress.settings;
		books = mybookprogress.books;
	});
	mybookprogress.on('loaded', function() {
		progress_tab = mybookprogress.progress_tab;
	});

	var View = mybookprogress.View;
	var CollectionView = mybookprogress.CollectionView;
	var TabsView = mybookprogress.TabsView;
	var VirtualModel = mybookprogress.VirtualModel;
	var VirtualCollection = mybookprogress.VirtualCollection;

	/*---------------------------------------------------------*/
	/* Book Progress View                                      */
	/*---------------------------------------------------------*/

	mybookprogress.BookProgressView = View.extend({
		template: mybookprogress.utils.template(jQuery('#book_progress_template').html()),

		initialize: function() {
			this.set_subview('.mbp-progress-view-container', new mybookprogress.BookProgressViewView({model: this.model}));
			this.set_subview('.mbp-book-tabs', new mybookprogress.BookProgressTabsView({parent: this, model: this.model}));
			this.set_subview('.mbp-create-progress-container', new mybookprogress.BookProgressCreateView({parent: this, model: this.model}));
			this.set_subview('.mbp-progress-timeline', new mybookprogress.BookProgressTimelineView({model: this.model}));
		},
	});

	/*---------------------------------------------------------*/
	/* Book Progress View Selector                             */
	/*---------------------------------------------------------*/

	mybookprogress.BookProgressViewView = View.extend({
		template: mybookprogress.utils.template(jQuery('#book_progress_view_template').html()),

		events: {
			'change .mbp-progress-view': 'progress_view_change',
			'click .mbp-setup-book-button': 'open_setup',
		},

		render: function() {
			View.prototype.render.call(this);
			this.render_options();
			return this;
		},

		render_options: function() {
			var select = this.$('.mbp-progress-view');
			select.empty();
			select.append(jQuery('<option value="overview">All Books</option>'));
			books.each(function(book) {
				select.append(jQuery('<option value="'+book.id+'"'+(progress_tab.currentbook && progress_tab.currentbook.id == book.id ? ' selected="selected"' : '')+'>'+book.get_title()+'</option>'));
			}, this);
			select.append(jQuery('<option value="new-book">-- Add New Book --</option>'));

			if(!progress_tab.currentbook) { this.$('.mbp-setup-book-button').addClass('mbp-disabled'); } else { this.$('.mbp-setup-book-button').removeClass('mbp-disabled'); }
		},

		progress_view_change: function(e) {
			book_id = jQuery(e.target).val();
			if(book_id == 'overview') {
				mbp_track_event('view_books_overview');
				progress_tab.show_overview();
			} else if(book_id == 'new-book') {
				progress_tab.show_book();
			} else {
				progress_tab.show_book(book_id);
			}
		},

		open_setup: function() {
			progress_tab.toggle_setup();
		},
	});

	/*---------------------------------------------------------*/
	/* Book Phase Display                                      */
	/*---------------------------------------------------------*/

	mybookprogress.BookPhasesItemView = View.extend({
		className: 'mbp-book-phase',
		template: mybookprogress.utils.template(jQuery('#book_phase_item_template').html()),

		events: {
			'click': 'select',
		},

		initialize: function(options) {
			this.parent = options.parent;
		},

		render: function() {
			View.prototype.render.call(this);
			var status = this.parent.model.get_phase_status(this.model.id);
			if(status === 'complete') {
				this.$el.addClass('mbp-phase-done');
			} else if(status === 'working') {
				this.$el.addClass('mbp-phase-working');
			}
			return this;
		},

		select: function() {
			this.model.trigger('select', this);
		},
	});

	mybookprogress.BookPhasesView = CollectionView.extend({
		className: 'mbp-book-phases',
		item_view: mybookprogress.BookPhasesItemView,
		initialize: function(options) {
			this.collection = new VirtualCollection(this.model.get_phases());
			CollectionView.prototype.initialize.call(this, options);
			this.listenTo(this.model, 'change:phases_status', this.render);
		},

		render: function() {
			CollectionView.prototype.render.call(this);
			if(this.collection.length > 4) { this.$el.addClass('mbp-small-phases'); } else { this.$el.removeClass('mbp-small-phases'); }
		},
	});

	/*---------------------------------------------------------*/
	/* Statistics Tab                                          */
	/*---------------------------------------------------------*/

	mybookprogress.BookProgressStatisticsTabView = View.extend({
		className: 'mbp-book-tab',
		template: mybookprogress.utils.template(jQuery('#book_progress_statistics_tab_template').html()),

		initialize: function() {
			if(this.model) {
				this.show_book_stats();
			} else {
				this.show_global_stats();
			}
		},

		fetch_stats: function() {
			if(this.fetching_stats == this.current_view) { return; }
			var fetching_stats = this.fetching_stats = this.current_view;

			var action = this.current_view == 'global' ? 'mbp_get_global_stats' : (this.current_view == 'book' ? 'mbp_get_book_stats' : 'mbp_get_phase_stats');
			var data = this.current_view == 'global' ? {} : (this.current_view == 'book' ? {book_id: this.model.id} : {book_id: this.model.id, phase_id: this.phase_id})

			mybookprogress.WPQuery(action, data).then(_.bind(function(response) {
				if(fetching_stats != this.current_view) { return; }
				if(response === null || typeof response !== 'object' || 'error' in response) { response = null; }

				this.fetching_stats = false;
				this.stats = response;
				this.render();
			}, this));
		},

		show_book_stats: function() {
			if(this.current_view == 'book') { return; }
			this.current_view = 'book';
			if(!this.listening_to_book) {
				this.listenTo(this.model, 'sync', this.fetch_stats);
				this.listenTo(this.model.get_progress(), 'sync', this.fetch_stats);
				this.listening_to_book = true;
			}
			this.fetch_stats();
		},

		show_global_stats: function() {
			if(this.current_view == 'global') { return; }
			if(this.listening_to_book) {
				this.stopListening(this.model);
				this.stopListening(this.model.get_progress());
				this.listening_to_book = false;
			}
			this.current_view = 'global';
			this.fetch_stats();
		},

		show_phase_stats: function(phase_id) {
			if(this.current_view == 'phase_'+phase_id) { return; }
			if(!this.listening_to_book) {
				this.listenTo(this.model, 'sync', this.fetch_stats);
				this.listenTo(this.model.get_progress(), 'sync', this.fetch_stats);
				this.listening_to_book = true;
			}
			this.current_view = 'phase_'+phase_id;
			this.phase_id = phase_id;
			this.fetch_stats();
		},

		render: function() {
			View.prototype.render.call(this);

			var stats = this.stats;
			if(!stats) {
				this.$('.mbp-stats-tab').addClass('mbp-no-data');
				stats = {
					progress_type: 'percent',
					progress_target: 100,
					current_per_day: 2.2,
					current_per_week: 15,
					current_per_month: 54,
					needed_per_day: 2,
					needed_per_week: 14,
					needed_per_month: 50,
					graph_data: [
						[utils.unix_timestamp_now(), 0.10],
						[utils.unix_timestamp_now()+86400*2.8, 0.64],
						[utils.unix_timestamp_now()+86400*3.5, 0.50],
						[utils.unix_timestamp_now()+86400*5, 0.90],
					],
					phases: {'Phase': 1},
				};
			} else {
				this.$('.mbp-stats-tab').removeClass('mbp-no-data');
			}

			if(stats.current_per_day) {
				this.$('.mbp-other-statistics-table').hide();
				this.$('.mbp-pace-statistics-table').removeAttr('style');

				var progress_units = mybookprogress_i18n.progress;
				if(mybookprogress.progress_types[stats.progress_type]) {
					var progress_units = mybookprogress.progress_types[stats.progress_type].units;
				}

				this.$('.mbp-unit-per-day .mbp-title-col').text(progress_units+'/'+mybookprogress_i18n.day);
				this.$('.mbp-unit-per-week .mbp-title-col').text(progress_units+'/'+mybookprogress_i18n.week);
				this.$('.mbp-unit-per-month .mbp-title-col').text(progress_units+'/'+mybookprogress_i18n.month);

				this.$('.mbp-unit-per-day .mbp-current-col').text(utils.progress_format(stats.current_per_day));
				this.$('.mbp-unit-per-week .mbp-current-col').text(utils.progress_format(stats.current_per_week));
				this.$('.mbp-unit-per-month .mbp-current-col').text(utils.progress_format(stats.current_per_month));
				if(stats.needed_per_day) {
					this.$('.mbp-needed-col').show();
					this.$('.mbp-unit-per-day .mbp-needed-col').text(utils.progress_format(stats.needed_per_day));
					this.$('.mbp-unit-per-week .mbp-needed-col').text(utils.progress_format(stats.needed_per_week));
					this.$('.mbp-unit-per-month .mbp-needed-col').text(utils.progress_format(stats.needed_per_month));
					if(stats.current_per_day > stats.needed_per_day) { this.$('.mbp-unit-per-day .mbp-current-col').addClass('mbp-on-pace'); } else { this.$('.mbp-unit-per-day .mbp-current-col').removeClass('mbp-on-pace'); }
					if(stats.current_per_week > stats.needed_per_week) { this.$('.mbp-unit-per-week .mbp-current-col').addClass('mbp-on-pace'); } else { this.$('.mbp-unit-per-week .mbp-current-col').removeClass('mbp-on-pace'); }
					if(stats.current_per_month > stats.needed_per_month) { this.$('.mbp-unit-per-month .mbp-current-col').addClass('mbp-on-pace'); } else { this.$('.mbp-unit-per-month .mbp-current-col').removeClass('mbp-on-pace'); }
				} else {
					this.$('.mbp-needed-col').hide();
				}
			} else {
				this.$('.mbp-other-statistics-table').removeAttr("style");
				this.$('.mbp-pace-statistics-table').hide();

				this.$('.mbp-most-productive-day .mbp-value-col').text(stats.most_productive_day);
			}

			var graph_data = jQuery.extend(true, [], stats.graph_data);
			if(graph_data.length) {
				if(stats.books) {
					var min = -1;
					var max = -1;
					for(var i = graph_data.length - 1; i >= 0; i--) {
						if(max == -1 || graph_data[i][0] > max) { max = graph_data[i][0]; }
						if(min == -1 || graph_data[i][0] < min) { min = graph_data[i][0]; }
						graph_data[i][0] = new Date(graph_data[i][0]*1000);
					}
					var duration = max-min;

					var width = this.$('.mbp-statistics-graph').width();
					var height = this.$('.mbp-statistics-graph').height();

					var options = {
						width: width,
						height: height,
						backgroundColor: 'transparent',
						chartArea: {'top': 5, 'left': 45, 'width': width-135, 'height': height-35},
						vAxis: { format:'#%', viewWindow: { min: 0, max: 1 } },
						hAxis: { viewWindow: { min: new Date((min-0.1*duration)*1000), max: new Date((max+0.1*duration)*1000) } },
						legend: {'position': 'right'},
						enableInteractivity: false,
					};

					var data = new google.visualization.DataTable();
					data.addColumn('datetime', mybookprogress_i18n.time);
					for(book in stats.books) {
						data.addColumn('number', stats.books[book]);
					}
					data.addRows(graph_data);

					var chart = new google.visualization.LineChart(this.$('.mbp-statistics-graph')[0]);
					chart.draw(data, options);
				} else if(stats.phases) {
					var min = -1;
					var max = -1;
					for(var i = graph_data.length - 1; i >= 0; i--) {
						if(max == -1 || graph_data[i][0] > max) { max = graph_data[i][0]; }
						if(min == -1 || graph_data[i][0] < min) { min = graph_data[i][0]; }
						graph_data[i][0] = new Date(graph_data[i][0]*1000);
					}
					var duration = max-min;

					var width = this.$('.mbp-statistics-graph').width();
					var height = this.$('.mbp-statistics-graph').height();

					var options = {
						width: width,
						height: height,
						backgroundColor: 'transparent',
						chartArea: {'top': 5, 'left': 35, 'width': width-35, 'height': height-35},
						vAxis: { format:'#%', viewWindow: { min: 0, max: 1 } },
						hAxis: { viewWindow: { min: new Date((min-0.1*duration)*1000), max: new Date((max+0.1*duration)*1000) } },
						legend: {'position': 'none'},
						enableInteractivity: false,
						lineWidth: 4,
						pointSize: 6,
					};

					var data = new google.visualization.DataTable();
					data.addColumn('datetime', mybookprogress_i18n.time);
					for(phase in stats.phases) {
						data.addColumn('number', stats.phases[phase]);
					}
					data.addRows(graph_data);

					var chart = new google.visualization.AreaChart(this.$('.mbp-statistics-graph')[0]);
					chart.draw(data, options);
				} else {
					var start = graph_data[0][0];
					var end = graph_data[graph_data.length-1][0];
					if(stats.deadline && stats.deadline > end) { end = stats.deadline; }
					var duration = end-start;
					var min_bound = new Date((start-0.1*duration)*1000);
					var middle = new Date((start+0.5*duration)*1000);
					var max_bound = new Date((end+0.1*duration)*1000);

					for(var i = graph_data.length - 1; i >= 0; i--) {
						graph_data[i] = graph_data[i];
						graph_data[i][0] = new Date(graph_data[i][0]*1000);
						graph_data[i].splice(1, 0, null);
						graph_data[i].push(null);
						graph_data[i].push(null);
					}

					var width = this.$('.mbp-statistics-graph').width();
					var height = this.$('.mbp-statistics-graph').height();

					var options = {
						width: width,
						height: height,
						backgroundColor: 'transparent',
						chartArea: {'top': 5, 'left': 50, 'width': width-50, 'height': height-35},
						legend: {'position': 'none'},
						series: {
							0: {color: '#CB3301', lineWidth: 4, pointSize: 6},
							1: {color: '#B5B5B5', lineWidth: 1.5, areaOpacity: 0},
						},
						vAxis: { viewWindow: { min: 0, max: stats.progress_target ? stats.progress_target*1.2 : 100 } },
						hAxis: { viewWindow: { min: min_bound, max: max_bound } },
						annotation: { 1 : { style: 'line' }, 4: { style: 'point' }, },
						annotations: { datum: { stemColor: 'transparent', stemLength: 5, textStyle: {color: 'black'} } },
						enableInteractivity: false,
					};

					if(stats.deadline) {
						graph_data.push([new Date(stats.deadline*1000), mybookprogress_i18n.deadline, null, null, null]);
					}

					if(stats.progress_target) {
						graph_data.push([min_bound, null, null, stats.progress_target, null]);
						graph_data.push([middle, null, null, stats.progress_target, 'Target']);
						graph_data.push([max_bound, null, null, stats.progress_target, null]);
					}

					var data = new google.visualization.DataTable();
					data.addColumn('datetime', mybookprogress_i18n.time);
					data.addColumn({type: 'string', role: 'annotation'});
					data.addColumn('number', mybookprogress_i18n.progress);
					data.addColumn('number', mybookprogress_i18n.target);
					data.addColumn({type: 'string', role: 'annotation'});
					data.addRows(graph_data);

					var chart = new google.visualization.AreaChart(this.$('.mbp-statistics-graph')[0]);
					chart.draw(data, options);
				}
			}

			return this;
		},
	});

	/*---------------------------------------------------------*/
	/* Nudges Tab                                              */
	/*---------------------------------------------------------*/

	mybookprogress.NudgeItemView = View.extend({
		className: 'mbp-nudge',
		template: mybookprogress.utils.template(jQuery('#book_progress_nudge_item_template').html()),

		events: {
			'mouseover': 'mouseover',
			'mouseout': 'mouseout',
			'click': 'clicked',
			'click .mbp-nudge-delete': 'delete',
		},

		render: function() {
			this.$el.html(this.template());
			if(!this.model.get('viewed')) { this.$el.addClass('mbp-nudge-new'); } else { this.$el.removeClass('mbp-nudge-new'); }
			return this;
		},

		delete: function() {
			this.model.destroy();
		},

		mouseover: function() {
			this.hover_timer = setTimeout(_.bind(this.set_viewed, this), 2000);
		},

		mouseout: function() {
			if(this.hover_timer) { clearTimeout(this.hover_timer); this.hover_timer = null; }
		},

		clicked: function() {
			this.set_viewed();
		},

		set_viewed: function() {
			if(this.model.get('viewed')) { return; }
			if(this.hover_timer) { clearTimeout(this.hover_timer); this.hover_timer = null; }
			this.model.set_viewed();
			this.render();
		},

		format_date: function() {
			return utils.format_date(this.model.get('timestamp'));
		},

		format_name: function() {
			var name = this.model.get('name');
			if(!name) { name = mybookprogress_i18n.anonymous; }
			if(this.model.get('email') && ['thomas@authormedia.com', 'tim@authormedia.com', 'support@authormedia.com'].indexOf(this.model.get('email')) == -1) {
				name = '<a href="mailto:'+this.model.get('email')+'?subject=Thank you for the nudge!">'+name+'</a>';
			}
			if(this.model.get('avatar')) { name = '<div class="mbp-nudge-author-image"><img src="'+this.model.get('avatar')+'"></div>'+name; }
			return name;
		},
	});

	mybookprogress.BookProgressNudgesTabView = View.extend({
		className: 'mbp-book-tab',
		template: mybookprogress.utils.template(jQuery('#book_progress_nudges_tab_template').html()),

		events: {
			'click .mbp-upsell-button': 'clicked_upsell_button',
		},

		initialize: function() {
			this.nudges = new VirtualCollection([
				{
					timestamp: utils.unix_timestamp_now(),
					book_id: 0,
					text: 'I come to your website every day to see your progress. Keep up the great work!',
					name: 'Reina Simmons',
					viewed: true,
				},
				{
					timestamp: utils.unix_timestamp_now(),
					book_id: 0,
					text: 'I can\'t wait for your next book. I hope you finish it soon!',
					name: 'Daniel White',
					viewed: true,
				},
				{
					timestamp: utils.unix_timestamp_now(),
					book_id: 0,
					text: 'I just noticed that you\'ve started on a new book, I\'m totally hyped!',
					name: 'Shelly Watson',
					viewed: true,
				},
			]);
			this.set_subview('.mbp-nudges-container', new CollectionView({item_view: mybookprogress.NudgeItemView, collection: this.nudges}));
		},

		render: function() {
			View.prototype.render.call(this);
			this.$el.addClass('mbp-disabled');
			var container = this.$('.mbp-nudges-container');
			container.scroll(_.bind(function() {
				var height = container.prop('scrollHeight') - container.height();
				if(height - container.scrollTop() < 50) {
					this.nudges.next_page();
				}
			}, this));
			return this;
		},

		clicked_upsell_button: function() {
			jQuery('.mbp-admin-tabs > .ui-tabs-nav a[href="#mbp-upgrade-tab"]').click();
		},
	});

	/*---------------------------------------------------------*/
	/* Email Updates Tab                                       */
	/*---------------------------------------------------------*/

	mybookprogress.EmailUpdatesTabView = View.extend({
		className: 'mbp-book-tab',
		template: mybookprogress.utils.template(jQuery('#book_progress_email_updates_tab_template').html()),

		events: {
			'input .mbp-email-updates-email': 'email_changed',
			'click .mbp-email-updates-test-email-button': 'send_test_email',
			'click .mbp-email-updates-period-button': 'update_period',
			'click .mbp-upsell-button': 'clicked_upsell_button',
		},

		initialize: function() {
			this.default_period = 'weekly';
			this.period_types = {
				daily: { name: mybookprogress_i18n.daily },
				weekly: { name: mybookprogress_i18n.weekly },
				monthly: { name: mybookprogress_i18n.monthly },
				never: { name: mybookprogress_i18n.never },
			};
			this.listenTo(this.model, 'change:email_updates_period', this.render_periods);
			this.editing_timer = null;
			this.email_status = null;
		},

		render: function() {
			this.$el.html(this.template());
			this.$el.addClass('mbp-disabled');
			this.render_email();
			this.render_periods();
			return this;
		},

		render_email: function() {
			var email = this.model.get('email_updates_email');
			if(!email) {
				email = this.$('.mbp-email-updates-email').attr('data-default');
				this.$('.mbp-email-updates-email').val(email);
				this.update_email();
			} else {
				this.$('.mbp-email-updates-email').val(email);
				if(this.verify_email()) {
					this.email_status = 'good';
				} else {
					this.email_status = 'bad';
				}
				this.render_feedback();
			}
		},

		render_feedback: function() {
			this.$('.mbp-email-updates-email').attr('class', 'mbp-email-updates-email');
			this.$('.mbp-email-updates-email').removeAttr('disabled');
			this.$('.mbp-email-updates-email-setting .mbp-setting-feedback').attr('class', 'mbp-setting-feedback');
			if(this.email_status == 'editing') {
				this.$('.mbp-email-updates-email-setting .mbp-setting-feedback').addClass('checking');
			} else if(this.email_status == 'good') {
				this.$('.mbp-email-updates-email').addClass('mbp-correct');
				this.$('.mbp-email-updates-email-setting .mbp-setting-feedback').addClass('good');
			} else if(this.email_status == 'bad') {
				this.$('.mbp-email-updates-email').addClass('mbp-error');
				this.$('.mbp-email-updates-email-setting .mbp-setting-feedback').addClass('bad');
			}
		},

		email_changed: function() {
			this.email_status = 'editing';
			if(this.editing_timer) { clearInterval(this.editing_timer); }
			this.editing_timer = setTimeout(_.bind(this.update_email, this), 1000);
			this.render_feedback();
		},

		update_email: function() {
			if(this.editing_timer) { clearInterval(this.editing_timer); this.editing_timer = null; }
			if(this.verify_email()) {
				this.email_status = 'editing';
				this.model.set('email_updates_email', this.$('.mbp-email-updates-email').val());
				this.model.save(null, {success: _.bind(this.email_updated, this)});
			} else {
				this.email_status = 'bad';
			}
			this.render_feedback();
		},

		email_updated: function() {
			if(!this.editing_timer) {
				this.email_status = 'good';
				this.render_feedback();
			}
		},

		verify_email: function() {
			return this.$('.mbp-email-updates-email').val().match(/^[^@]+@[^@]+$/);
		},

		send_test_email: function() {
			var button = this.$('.mbp-email-updates-test-email-button');
			if(button.hasClass('mbp-disabled')) { return; }
			button.addClass('mbp-disabled');
			mybookprogress.WPQuery('mbp_email_updates_send_test', {email: this.model.get('email_updates_email'), book: this.model.id, period: this.model.get('email_updates_period')}).then(function(response) {
				button.removeClass('mbp-disabled');
			});
		},

		render_periods: function() {
			var current_period = this.model.get('email_updates_period');
			if(!current_period) {
				current_period = this.default_period;
				this.model.set('email_updates_period', current_period);
				this.model.save();
			}
			this.$('.mbp-email-updates-periods').empty();
			for(type in this.period_types) {
				var new_button = jQuery('<div class="mbp-setting-button mbp-email-updates-period-button"></div>');
				new_button.attr('data-mbp-email-updates-period', type);
				new_button.text(this.period_types[type].name);
				if(type === current_period) { new_button.addClass('mbp-selected'); }
				this.$('.mbp-email-updates-periods').append(new_button);
			}
		},

		update_period: function(e) {
			this.model.set('email_updates_period', jQuery(e.target).attr('data-mbp-email-updates-period'));
			this.model.save();
		},

		clicked_upsell_button: function() {
			jQuery('.mbp-admin-tabs > .ui-tabs-nav a[href="#mbp-upgrade-tab"]').click();
		},
	});

	/*---------------------------------------------------------*/
	/* Book Tabs                                               */
	/*---------------------------------------------------------*/

	mybookprogress.BookProgressTabsView = TabsView.extend({
		template: mybookprogress.utils.template(jQuery('#book_progress_tabs_template').html()),

		initialize: function(options) {
			this.add_tab('mbp-stats-tab', this.model ? mybookprogress_i18n.pace : mybookprogress_i18n.stats, options.parent.stats_tab = new mybookprogress.BookProgressStatisticsTabView({model: this.model}));
			this.add_tab('mbp-nudges-tab', mybookprogress_i18n.nudges, options.parent.nudges_tab = new mybookprogress.BookProgressNudgesTabView({model: this.model}));
			if(this.model) {
				this.add_tab('mbp-email-updates-tab', mybookprogress_i18n.email_updates, options.parent.email_updates_tab = new mybookprogress.EmailUpdatesTabView({model: this.model}));
				if(window.location.hash.substr(1) == 'email-updates-settings') { this.focus_tab('mbp-email-updates-tab'); }
			}

			this.on('activatetab', function(slug) { mbp_track_event('view_'+slug.replace(/-/g, '_')); });
		},
	});

	/*---------------------------------------------------------*/
	/* Book Progress                                           */
	/*---------------------------------------------------------*/

	mybookprogress.BookProgressDisplay = View.extend({
		render: function() {
			var progress_type = this.get_progress_type();
			if(!progress_type) { return; }
			this.$el.empty().append(progress_type.display(this.get_progress_data()));
			return this;
		},

		get_progress_type: function() {
			return mybookprogress.progress_types[this.model.get('progress_type')];
		},

		get_progress_data: function() {
			return {utils: utils, progress_type: this.get_progress_type(), progress: this.model.get('progress'), target: this.model.get('target')};
		},
	});

	mybookprogress.BookProgressEditor = View.extend({
		events: {
			'input input': 'schedule_save',
			'change input': 'schedule_save',
		},

		render: function() {
			var progress_type = this.get_progress_type();
			if(!progress_type) { return; }
			this.$el.empty().append(progress_type.editor(this.get_progress_data()));
			return this;
		},

		schedule_save: function() {
			if(this.save_timer) { clearInterval(this.save_timer); }
			this.save_timer = setTimeout(_.bind(this.save, this), 10);
		},

		save: function() {
			if(this.save_timer) { clearInterval(this.save_timer); this.save_timer = null; }

			var progress_type = this.get_progress_type();
			var form_data = {};
			var form_inputs = _.map(this.$('input, textarea, select'), jQuery);
			for (var i = form_inputs.length - 1; i >= 0; i--) {
				form_data[form_inputs[i].attr('name')] = form_inputs[i].val();
			};
			form_data.progress_type = progress_type;
			form_data.utils = utils;
			var progress_data = progress_type.save(form_data);
			this.model.set({progress: progress_data.progress, target: progress_data.target});
		},

		get_progress_type: function() {
			return mybookprogress.progress_types[this.model.get('progress_type')];
		},

		get_progress_data: function() {
			return {utils: utils, progress_type: this.get_progress_type(), progress: this.model.get('progress'), target: this.model.get('target')};
		},
	});

	/*---------------------------------------------------------*/
	/* Progress Creation                                       */
	/*---------------------------------------------------------*/

	mybookprogress.BookProgressCreateView = View.extend({
		template: mybookprogress.utils.template(jQuery('#book_progress_create_template').html()),

		events: {
			'change .mbp-progress-today': 'update_progress_today',
			'click .mbp-create-progress-button': 'save_progress',
			'change .mbp-create-progress-date': 'update_progress_date',
			'change .mbp-phase-complete': 'update_phase_progress',
			'click .mbp-phase-complete-button': 'complete_phase',
		},

		initialize: function(options) {
			this.parent = options.parent;
			this.book_phases = new mybookprogress.BookPhasesView({model: this.model});
			this.set_subview('.mbp-book-phases-container .mbp-book-phases', this.book_phases);
			this.listenTo(this.book_phases.collection, 'select', this.change_current_phase);
			this.listenTo(this.model, 'change:phases_progress', this.determine_current_phase);
		},

		render: function() {
			View.prototype.render.call(this);
			utils.datepicker(this.$('.mbp-create-progress-date'));
			this.determine_current_phase();
			this.setup_complete_button();
			return this;
		},

		setup_complete_button: function() {
			var message = mybookprogress_i18n.complete_this_phase;
			var options = {
				position: {
					my: "center bottom-15",
					at: "center top",
					collision: "none",
					using: function(position, feedback) {
						jQuery(this).css(position);
						jQuery("<div>").addClass("arrow center bottom").appendTo(this);
					}
				}
			};
			mybookprogress.utils.tooltip(this.$('.mbp-phase-complete-button'), message, options);
		},

		complete_phase: function() {
			if(this.$('.mbp-phase-complete-button').hasClass('mbp-disabled')) { return; }

			var complete_confirm = jQuery(mybookprogress.utils.template(jQuery('#book_progress_phase_complete_confirm_modal_template').html())({phase_name: this.current_phase.name}));
			complete_confirm.on('click', '.mbp-cancel-complete', function() { tb_remove(); });
			complete_confirm.on('click', '.mbp-confirm-complete', _.bind(function() {
				tb_remove();
				this.$('.mbp-create-progress-button').addClass('mbp-disabled');
				this.$('.mbp-phase-complete-button').addClass('mbp-disabled');
				this.model.update_phase_status(this.current_phase.id, 'complete');
				if(this.model.is_complete()) {
					this.display_fireworks('You finished your book!');
				} else {
					this.display_fireworks('You finished the '+this.current_phase.name+' phase!');
				}
				this.model.save(null, {success: _.bind(function () {
					this.$('.mbp-create-progress-button').removeClass('mbp-disabled');
					this.$('.mbp-phase-complete-button').removeClass('mbp-disabled');
					this.determine_current_phase();
				}, this)});
			}, this));
			utils.modal(complete_confirm);
		},

		determine_current_phase: function() {
			this.current_phase = null;
			var last_progress_entry = this.model.get_progress().at(0);
			if(last_progress_entry) {
				this.current_phase = this.model.get_phase(last_progress_entry.get('phase_id'));
				if(this.current_phase) {
					var phase_status = this.model.get_phase_status(this.current_phase.id);
					if(phase_status == 'complete') { this.current_phase = null; }
				}
			}
			if(!this.current_phase) {
				this.current_phase = this.model.get_next_phase();
			}
			if(!this.current_phase) { return this.$el.hide(); }
			this.current_phase_status = this.model.get_phase_status(this.current_phase.id);
			this.current_phase_progress = this.model.get_phase_progress(this.current_phase.id);

			this.make_new_entry();
		},

		make_new_entry: function() {
			if(this.parent.stats_tab) {
				this.parent.stats_tab.show_phase_stats(this.current_phase['id']);
			}

			var target = this.current_phase.progress_target;
			var progress = this.current_phase_progress;

			if(this.progress_entry) { this.stopListening(this.progress_entry); }
			this.progress_entry = new mybookprogress.ProgressEntry({
				timestamp: utils.unix_timestamp_now(),
				book_id: this.model.id,
				phase_id: this.current_phase.id,
				progress_type: this.current_phase.progress_type,
				progress: progress,
				target: target,
			});
			this.listenTo(this.progress_entry, 'change:progress', this.update_phase_progress);
			this.listenTo(this.progress_entry, 'change:progress', this.validate_progress);

			this.progress_editor = new mybookprogress.BookProgressEditor({model: this.progress_entry});
			this.set_subview('.mbp-progress-editor-container', this.progress_editor);

			this.$('.mbp-progress-today').prop('checked', true);
			this.update_progress_today();

			this.$('.mbp-phase-complete').prop('checked', true);
			this.$('.mbp-reduce-progress').prop('checked', false);
			this.update_phase_progress();

			this.render_current_phase_indicator();
		},

		save_progress: function() {
			if(this.$('.mbp-create-progress-button').hasClass('mbp-disabled')) { return; }
			if(!this.validate_progress()) { return; }
			this.$('.mbp-create-progress-button').addClass('mbp-disabled');
			this.$('.mbp-phase-complete-button').addClass('mbp-disabled');
			this.$('.mbp-create-progress-button').mbp_loading();

			var status = this.progress_entry.get('phase_complete') == true ? 'complete' : 'working';

			if(status !== this.current_phase_status) {
				this.model.update_phase_status(this.current_phase.id, status);
			}

			var notes = '';
			var book_title = this.model.get_title();
			var phase = this.model.get_phase(this.progress_entry.get('phase_id'));

			if(status == 'complete') {
				if(this.model.is_complete()) {
					notes = 'I just finished '+phase['name']+'! Woohoo!';
					this.display_fireworks('You finished your book!', 'large');
				} else {
					var next_phase = this.model.get_next_phase();
					notes = 'I just completed the '+phase['name']+' phase of '+book_title+'! Now onto '+next_phase['name']+'!';
					this.display_fireworks('You finished the '+this.current_phase.name+' phase!', 'large');
				}
			} else {
				var phase_progress = utils.progress_format(this.progress_entry.get('progress')*100);
				notes = 'I just made progress on '+book_title+'! So far I\'m '+phase_progress+'% complete on the '+phase['name']+' phase.';
				if(phase['deadline']) {	notes += ' '+utils.human_time_diff(utils.unix_timestamp_now()-((new Date()).getTimezoneOffset()*60), phase['deadline'])+' remain until the deadline.'; }

				var progress_diff = this.progress_entry.get('progress')*this.progress_entry.get('target') - this.current_phase_progress*this.current_phase.progress_target;
				var progress_type = mybookprogress.progress_types[this.progress_entry.get('progress_type')];
				var message = '';
				if(progress_type && progress_diff > 0) {
					var progress_diff_format = utils.progress_format(progress_diff);
					var progress_units = progress_diff_format == '1' ? utils.uncapitalize(progress_type.unit) : utils.uncapitalize(progress_type.units);
					message = 'You are '+progress_diff_format+' '+progress_units+' closer to your goal!';
				}
				this.display_fireworks(message);

				if(this.progress_entry.get('target') != this.current_phase.progress_target) {
					this.current_phase.progress_target = this.progress_entry.get('target');
					this.model.update_phase(this.current_phase);
				}
			}

			this.progress_entry.set('notes', notes);

			var finish = _.bind(function () {
				this.progress_entry.save(null, {success: _.bind(function() {
					this.progress_entry.mbp_is_new = true;
					this.model.get_progress().add(this.progress_entry);
					this.$('.mbp-create-progress-button').removeClass('mbp-disabled');
					this.$('.mbp-phase-complete-button').removeClass('mbp-disabled');
					this.$('.mbp-create-progress-button').mbp_loading('destroy');
					this.determine_current_phase();
				}, this)});
			}, this);

			if(this.model.is_dirty) { this.model.save(null, {success: finish}); } else { finish(); }
		},

		change_current_phase: function(view) {
			var new_phase_status = this.model.get_phase_status(view.model.id);
			if(new_phase_status !== 'complete') {
				this.current_phase = this.model.get_phase(view.model.id);
				this.current_phase_status = new_phase_status;
				this.current_phase_progress = this.model.get_phase_progress(view.model.id);
				this.make_new_entry();
			}
		},

		validate_progress: function() {
			var progress_equal = utils.number_format(this.current_phase_progress, 7) === utils.number_format(this.progress_entry.get('progress'), 7);
			var target_equal = utils.number_format(this.current_phase.progress_target, 7) === utils.number_format(this.progress_entry.get('target'), 7);

			if(this.progress_entry.get('progress') < 1 && target_equal && progress_equal) {
				this.$('.mbp-create-progress-errors').text('Please input your new progress value');
				this.$('.mbp-create-progress-errors').show();
				return false;
			}

			if(target_equal && this.current_phase_progress > this.progress_entry.get('progress') && !this.$('.mbp-reduce-progress').is(':checked')) {
				this.$('.mbp-create-progress-errors').hide();
				return false;
			}

			this.$('.mbp-create-progress-errors').hide();
			return true;
		},

		render_current_phase_indicator: function() {
			var current_phase_view = null;
			for(var i = this.book_phases.item_views.length - 1; i >= 0; i--) {
				if(this.book_phases.item_views[i].model.id == this.current_phase.id) { current_phase_view = this.book_phases.item_views[i]; break; }
			}
			if(current_phase_view) {
				setTimeout(_.bind(function() {
					this.$('.mbp-book-phase-indicator').show().css({
						top: this.$('.mbp-create-progress-section').offset().top-this.$el.offset().top,
						left: Math.max(window.innerWidth <= 1500 ? 100 : 120, current_phase_view.$el.offset().left-this.$el.offset().left+current_phase_view.$el.outerWidth()*0.5),
					});
				}, this), 0);
			}
		},

		update_progress_today: function() {
			if(this.$('.mbp-progress-today').is(':checked')) {
				this.progress_entry.set('timestamp', utils.unix_timestamp_now());
				this.$('.mbp-create-progress-date').removeClass('error');
				this.$('.mbp-create-progress-date-error').empty();
				this.$('.mbp-create-progress-date-container').hide(300);
			} else {
				this.$('.mbp-create-progress-date').val(utils.format_date(this.progress_entry.get('timestamp')));
				this.$('.mbp-create-progress-date-container').show(300);
			}
		},

		update_phase_progress: function() {
			if(this.progress_entry.get('progress') == 1) {
				if(!this.$('.mbp-phase-complete-container').is(':visible')) { this.$('.mbp-phase-complete-container').show(300); }
				if(this.$('.mbp-phase-complete').is(':checked')) {
					this.$('.mbp-phase-complete-container').removeClass('mbp-not-complete');
					this.progress_entry.set('phase_complete', true);
				} else {
					this.$('.mbp-phase-complete-container').addClass('mbp-not-complete');
					this.progress_entry.set('phase_complete', false);
				}
			} else {
				if(this.$('.mbp-phase-complete-container').is(':visible')) { this.$('.mbp-phase-complete-container').hide(300); }
				this.$('.mbp-phase-complete-container').removeClass('mbp-not-complete');
				this.progress_entry.set('phase_complete', false);
			}

			var target_equal = utils.number_format(this.current_phase.progress_target, 7) === utils.number_format(this.progress_entry.get('target'), 7);
			if(target_equal && this.current_phase_progress > this.progress_entry.get('progress')) {
				if(!this.$('.mbp-reduce-progress-container').is(':visible')) { this.$('.mbp-reduce-progress-container').show(300); }
			} else {
				if(this.$('.mbp-reduce-progress-container').is(':visible')) { this.$('.mbp-reduce-progress-container').hide(300); }
			}
		},

		update_progress_date: function() {
			var error = '';
			var date = utils.parse_date(this.$('.mbp-create-progress-date').val());
			if(typeof date === 'number') {
				if(date > utils.unix_timestamp_now()) {
					error = 'Don\'t get ahead of yourself!<br>Choose a date in the past.';
				} else {
					this.progress_entry.set('timestamp', date);
					this.$('.mbp-create-progress-date').removeClass('mbp-error');
					this.$('.mbp-create-progress-date-error').empty();
				}
			} else {
				error = date;
			}

			if(error) {
				this.$('.mbp-create-progress-date').addClass('mbp-error');
				this.$('.mbp-create-progress-date-error').html(error);
			}
		},

		display_fireworks: function(message, size) {
			var launch_rate = {min: 5, max: 60};
			var time = 3000;
			if(size === 'large') {
				launch_rate = 60;
				time = 6000;
			}

			var el = jQuery('<canvas class="mbp-admin-page-fireworks"></canvas>');
			jQuery('body').append(el);
			el.fireworks({launch_rate: launch_rate});
			setTimeout(function() {
				el.fireworks('stop');
			}, time);
			setTimeout(function() {
				el.fireworks('destroy');
				el.remove();
			}, time + 3000);

			if(message) {
				var message_el = jQuery('<div class="mbp-admin-page-fireworks-message"><div class="mbp-admin-page-fireworks-message-congrats">Congratulations!</div><div class="mbp-admin-page-fireworks-message-text"></div></div>');
				message_el.find('.mbp-admin-page-fireworks-message-text').text(message);
				jQuery('body').append(message_el);
				message_el.fadeIn(500);
				setTimeout(function() {
					message_el.fadeOut(500);
				}, time + 1000);
				setTimeout(function() {
					message_el.remove();
				}, time + 1500);
			}
		},
	});

	/*---------------------------------------------------------*/
	/* Progress Timeline                                       */
	/*---------------------------------------------------------*/

	mybookprogress.BookProgressTimelineEntry = View.extend({
		className: 'mbp-progress mbp-progress-entry',
		template: mybookprogress.utils.template(jQuery('#book_progress_entry_template').html()),

		events: {
			'click .mbp-progress-inner': 'select',
			'input .mbp-progress-notes': 'notes_updated',
			'click .mbp-edit-progress-button': 'start_editing',
			'click .mbp-delete-progress-button': 'delete',
			'click .mbp-save-progress-button': 'stop_editing',
			'click .mbp-share-progress-more-button': 'toggle_share_more',
			'click .mbp-share-progress-button:not(.mbp-share-progress-more-button)': 'share_progress',
			'change .mbp-progress-date-editor': 'date_updated',
			'input .mbp-progress-date-editor': 'date_updated',
		},

		initialize: function() {
			this.set_subview('.mbp-progress-display-container', new mybookprogress.BookProgressDisplay({model: this.model}));

			this.selected = false;
			if(this.model.mbp_is_new) { this.selected = true; }

			this.notes_timer = null;
			this.editing = false;
		},

		render: function() {
			this.$el.html(this.template());
			this.render_subviews();
			this.render_extra();
			this.render_selected();
			this.render_progress_sharing_buttons();
			return this;
		},

		render_extra: function() {
			if(this.model.get('phase_complete')) {
				this.$el.addClass('mbp-finished-phase');
				this.$('.mbp-progress-extra').html('Finished<br>'+this.model.get('phase_name')+'!');
			} else {
				this.$el.removeClass('mbp-finished-phase');
			}
		},

		render_selected: function() {
			if(this.selected) {
				this.$el.addClass('mbp-progress-selected');
				if(this.model.mbp_is_new) { this.$('.mbp-progress-sharing-message').show(); }
				this.$('.mbp-edit-progress-button').show();
				this.$('.mbp-progress-sharing').show();
			} else {
				this.$el.removeClass('mbp-progress-selected');
				this.$('.mbp-progress-sharing-message').hide();
				this.$('.mbp-edit-progress-button').hide();
				this.$('.mbp-progress-sharing').hide();
				this.$('.mbp-share-progress-more-menu').hide();
			}
		},

		render_progress_sharing_buttons: function() {
			this.$('.mbp-share-progress-buttons').empty();
			var normals = _.clone(mybookprogress.progress_sharing_types);

			var more = jQuery('<div class="mbp-share-progress-more"><div class="mbp-share-progress-button mbp-share-progress-more-button">'+mybookprogress_i18n.more+'</div><div class="mbp-share-progress-more-menu"></div></div>');
			var hasmore = false;

			for(type_name in mybookprogress.progress_sharing_types) {
				var type = mybookprogress.progress_sharing_types[type_name];
				var parent = type.default ? this.$('.mbp-share-progress-buttons') : more.find('.mbp-share-progress-more-menu');
				if(!type.default) { hasmore = true; }
				var text = typeof type.button === 'string' ? type.button : type.button(this.model, type);
				parent.append('<div class="mbp-share-progress-button" data-mbp-progress-sharing-type="'+type_name+'">'+text+'</div>');
			}

			if(hasmore) {
				this.$('.mbp-share-progress-buttons').append(more);
			}
		},

		share_progress: function(e) {
			this.stop_editing();
			var button = jQuery(e.target);
			var type_name = button.attr('data-mbp-progress-sharing-type');
			var type = mybookprogress.progress_sharing_types[type_name];
			if(type) { type.share(this.model, button, type); }
		},

		select: function() {
			if(this.selected) { return; }
			this.selected = true;
			this.render_selected();
		},

		deselect: function(e) {
			if(!this.selected) { return; }
			if(this.$('.mbp-progress-inner').is(':hover')) { return; }
			this.selected = false;
			if(this.model.mbp_is_new) { delete this.model.mbp_is_new; }
			this.stop_editing();
			this.render_selected();
		},

		delete: function() {
			if(this.$('.mbp-delete-progress-button').hasClass('mbp-disabled')) { return; }
			var delete_confirm = jQuery(mybookprogress.utils.template(jQuery('#book_progress_delete_confirm_modal_template').html())());
			delete_confirm.on('click', '.mbp-cancel-delete', function() { tb_remove(); });
			delete_confirm.on('click', '.mbp-confirm-delete', _.bind(function() {
				tb_remove();
				this.$('.mbp-delete-progress-button').addClass('mbp-disabled');
				this.$('.mbp-delete-progress-button').mbp_loading();
				this.model.destroy();
			}, this));
			utils.modal(delete_confirm);
		},

		start_editing: function() {
			if(!this.selected || this.editing) { return; }
			this.editing = true;
			this.$('.mbp-progress-edit').html('<div class="mbp-save-progress-button">'+mybookprogress_i18n.save+'</div><div class="mbp-delete-progress-button">&nbsp;</div>');
			this.set_subview('.mbp-progress-display-container', new mybookprogress.BookProgressEditor({model: this.model}));

			var width = this.$('.mbp-progress-date').width();
			this.$('.mbp-progress-date').html('<input type="text" class="mbp-progress-date-editor">');
			var dateeditor = this.$('.mbp-progress-date-editor');
			dateeditor.val(utils.format_date(this.model.get('timestamp'))).width(width);
			utils.datepicker(dateeditor);
		},

		stop_editing: function() {
			if(!this.editing) { return; }

			this.editing = false;
			this.save_model();

			this.$('.mbp-progress-date').text(this.format_date());
			this.$('.mbp-progress-edit').html('<div class="mbp-edit-progress-button">'+mybookprogress_i18n.edit+'</div>');
			this.set_subview('.mbp-progress-display-container', new mybookprogress.BookProgressDisplay({model: this.model}));
		},

		date_updated: function() {
			var dateeditor = this.$('.mbp-progress-date-editor');
			var date = utils.parse_date(dateeditor.val());
			if(typeof date === 'string') {
				dateeditor.addClass('mbp-error');
			} else {
				this.model.set('timestamp', date);
				dateeditor.removeClass('mbp-error');
			}
		},

		notes_updated: function() {
			this.model.set('notes', this.$('.mbp-progress-notes').val());
			if(this.save_timer) { clearInterval(this.save_timer); }
			this.save_timer = setTimeout(_.bind(this.save_model, this), 1000);
		},

		save_model: function() {
			if(this.save_timer) { clearInterval(this.save_timer); this.save_timer = null; }
			this.model.save();
		},

		format_date: function() {
			return utils.format_date(this.model.get('timestamp'));
		},

		format_phase: function() {
			return this.model.get('phase_name');
		},

		toggle_share_more: function() {
			if(this.$('.mbp-share-progress-more-menu').is(':visible')) {
				this.$('.mbp-share-progress-more-menu').hide(300);
			} else {
				this.$('.mbp-share-progress-more-menu').show(300);
			}
		},
	});

	mybookprogress.BookProgressTimelineMoreButton = View.extend({
		className: 'mbp-progress mbp-progress-more-button',
		template: mybookprogress.utils.template(jQuery('#book_progress_more_template').html()),

		events: {
			'click': 'clicked',
		},

		clicked: function() {
			if(this.loading) { return; }
			this.loading = true;
			this.$el.addClass('mbp-loading');
			this.collection.next_page();
		}
	});

	mybookprogress.BookProgressTimelineHeader = View.extend({
		className: 'mbp-progress mbp-progress-header',
		template: mybookprogress.utils.template(jQuery('#book_progress_header_template').html()),

		initialize: function(options) {
			this.message = options.message;
		}
	});

	mybookprogress.BookProgressTimelineNewBook = View.extend({
		className: 'mbp-progress mbp-progress-new-book',
		template: mybookprogress.utils.template(jQuery('#book_progress_newbook_template').html()),

		format_date: function() {
			return utils.format_date(this.model.get('created'));
		},
	});

	mybookprogress.BookProgressTimelineView = CollectionView.extend({
		item_view: mybookprogress.BookProgressTimelineEntry,

		initialize: function(options) {
			this.collection = this.model.get_progress();
			CollectionView.prototype.initialize.call(this, options);
			jQuery(document).on('click', _.bind(this.deselect, this));
		},

		render: function() {
			this.$el.empty();

			var current_month = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
			var num_since_header = 0;

			_.each(this.item_views, function(view) {
				if(view.model.get('timestamp') < utils.unix_timestamp(current_month)) {
					var year = current_month.getFullYear();
					var month = current_month.getMonth()-1;
					if(month < 0) { year -= 1; month += 12; }
					current_month = new Date(year, month, 1);
					if(num_since_header >= 3) {
						num_since_header = 0;
						var with_year = new Date().getFullYear() !== year;
						this.$el.append(new mybookprogress.BookProgressTimelineHeader({message: utils.month_name(month)+(with_year ? ' '+year : '')}).render().el);
					}
				}
				num_since_header++;
				this.$el.append(view.setElement(view.el).render().el);
			}, this);

			if(this.collection.has_more_pages()) {
				this.$el.append(new mybookprogress.BookProgressTimelineMoreButton({collection: this.collection}).render().el);
			} else if(this.collection.length) {
				this.$el.append(new mybookprogress.BookProgressTimelineNewBook({model: this.model}).render().el);
			}

			return this;
		},

		deselect: function(e) {
			if(jQuery('.mbp-ui-datepicker').length && jQuery('.mbp-ui-datepicker').is(':hover')) { return; }
			_.each(this.item_views, function(view) { view.deselect(e); });
		},
	});

	/*---------------------------------------------------------*/
	/* Overview                                                */
	/*---------------------------------------------------------*/

	mybookprogress.OverviewBookView = View.extend({
		className: 'mbp-book-phases-container',
		template: mybookprogress.utils.template(jQuery('#book_overview_book_template').html()),

		initialize: function() {
			this.set_subview('.mbp-book-phases', new mybookprogress.BookPhasesView({model: this.model}));
		},
	});

	mybookprogress.OverviewView = View.extend({
		template: mybookprogress.utils.template(jQuery('#book_overview_template').html()),

		initialize: function() {
			this.set_subview('.mbp-progress-view-container', new mybookprogress.BookProgressViewView());
			this.set_subview('.mbp-book-tabs', new mybookprogress.BookProgressTabsView({parent: this}));
			this.set_subview('.mbp-all-books-section .mbp-section-content', new CollectionView({collection: books, item_view: mybookprogress.OverviewBookView}));
		},
	});

})();
(function () {
	var utils = mybookprogress.utils;
	var settings = null;
	var books = null;
	var phase_templates = null;
	var progress_tab = null;
	var mbt_books = null;

	mybookprogress.on('models_loaded', function() {
		settings = mybookprogress.settings;
		books = mybookprogress.books;
		phase_templates = mybookprogress.phase_templates;
		mbt_books = mybookprogress.mbt_books;
	});
	mybookprogress.on('loaded', function() {
		progress_tab = mybookprogress.progress_tab;
	});

	var View = mybookprogress.View;
	var CollectionView = mybookprogress.CollectionView;
	var ModalView = mybookprogress.ModalView;
	var VirtualModel = mybookprogress.VirtualModel;
	var VirtualCollection = mybookprogress.VirtualCollection;

	/*---------------------------------------------------------*/
	/* Book Setup View                                         */
	/*---------------------------------------------------------*/

	mybookprogress.BookSetupView = View.extend({
		template: mybookprogress.utils.template(jQuery('#book_setup_template').html()),

		events: {
			'click .mbp-save-book-button': 'save_book',
			'click .mbp-back-button': 'discard_book',
			'click .mbp-delete-book-button': 'delete_book',
		},

		initialize: function() {
			this.editing_model = new mybookprogress.Book(jQuery.extend(true, {}, this.model.attributes));
			this.set_subview('.mbp-mybooktable-step', new mybookprogress.BookSetupMyBookTableView({model: this.editing_model}));
			this.set_subview('.mbp-book-title-step', new mybookprogress.BookSetupTitleView({model: this.editing_model}));
			this.set_subview('.mbp-book-phases-step', new mybookprogress.BookSetupPhasesView({model: this.editing_model}));
			this.set_subview('.mbp-book-display-step', new mybookprogress.BookSetupDisplayView({model: this.editing_model}));
		},

		render: function() {
			this.$el.html(this.template());
			this.render_subviews();
			this.render_buttons();
			return this;
		},

		render_buttons: function() {
			if(books.length === 0) {
				this.$('.mbp-back-button-container').hide();
			}
			if(this.model.isNew()) {
				this.$('.mbp-delete-book-button-container').hide();
			} else {
				this.$('.mbp-delete-book-button-container').show();
			}
		},

		delete_book: function() {
			if(this.$('.mbp-delete-book-button').hasClass('mbp-disabled')) { return; }
			var delete_confirm = jQuery(mybookprogress.utils.template(jQuery('#book_setup_delete_confirm_modal_template').html())());
			delete_confirm.on('click', '.mbp-cancel-delete', function() { tb_remove(); });
			delete_confirm.on('click', '.mbp-confirm-delete', _.bind(function() {
				tb_remove();
				this.$('.mbp-back-button').addClass('mbp-disabled');
				this.$('.mbp-save-book-button').addClass('mbp-disabled');
				this.$('.mbp-delete-book-button').addClass('mbp-disabled');
				this.$('.mbp-delete-book-button').mbp_loading();
				this.model.destroy({success: function() {
					progress_tab.show_current_book();
					window.scrollTo(0, 0);
				}});
			}, this));
			utils.modal(delete_confirm);
		},

		discard_book: function() {
			if(this.$('.mbp-back-button').hasClass('mbp-disabled')) { return; }
			if(this.editing_model.is_dirty) {
				var discard_confirm = jQuery(mybookprogress.utils.template(jQuery('#book_setup_discard_confirm_modal_template').html())());
				discard_confirm.on('click', '.mbp-save-changes', _.bind(function() {
					tb_remove();
					this.save_book();
				}, this));
				discard_confirm.on('click', '.mbp-discard-changes', _.bind(function() {
					tb_remove();
					this.book_discarded();
				}, this));
				utils.modal(discard_confirm);
			} else {
				this.book_discarded();
			}
		},

		book_discarded: function() {
			if(this.model.isNew()) {
				progress_tab.show_current_book();
			} else {
				progress_tab.toggle_setup();
			}
		},

		save_book: function() {
			if(this.$('.mbp-save-book-button').hasClass('mbp-disabled')) { return; }
			this.$('.mbp-back-button').addClass('mbp-disabled');
			this.$('.mbp-save-book-button').addClass('mbp-disabled');
			this.$('.mbp-delete-book-button').addClass('mbp-disabled');
			this.$('.mbp-save-book-button').mbp_loading();
			var callback = this.model.isNew() ? this.book_created : this.book_saved;
			this.model.save(this.editing_model.attributes, {success: _.bind(callback, this)});
			mbt_books.update_book(this.model);
		},

		book_created: function(book) {
			books.add(book);
			progress_tab.show_book(book.id);
		},

		book_saved: function() {
			progress_tab.toggle_setup();
			window.scrollTo(0, 0);
		},
	});

	/*---------------------------------------------------------*/
	/* MyBookTable Step                                        */
	/*---------------------------------------------------------*/

	mybookprogress.BookSetupMyBookTableView = View.extend({
		template: mybookprogress.utils.template(jQuery('#book_setup_mybooktable_step_template').html()),

		events: {
			'change .mbp-mybooktable-books': 'update_book',
		},

		render: function() {
			this.$el.html(this.template());
			this.render_books();
			return this;
		},

		render_books: function() {
			var books = this.$('.mbp-mybooktable-books');
			books.append(jQuery('<option value="0">-- '+mybookprogress_i18n.choose_one+' --</option>'));
			var current_book = this.model.get('mbt_book');
			var has_books = false;
			mbt_books.each(_.bind(function(book) {
				if(book.get('mbp_book') && book.get('mbp_book') !== this.model.id) { return; }
				has_books = true;
				books.append(jQuery('<option value="'+book.id+'"'+(current_book === book.id ? ' selected="selected"' : '')+'>'+book.get_title()+'</option>'));
			}, this));
			if(!has_books) {
				this.$el.hide();
			} else {
				this.$el.show();
			}
		},

		update_book: function() {
			var value = parseInt(this.$('.mbp-mybooktable-books').val());
			this.model.set('mbt_book', value > 0 ? value : null);
		},
	});

	/*---------------------------------------------------------*/
	/* Title Step                                              */
	/*---------------------------------------------------------*/

	mybookprogress.BookSetupTitleView = View.extend({
		template: mybookprogress.utils.template(jQuery('#book_setup_title_step_template').html()),

		events: {
			'input .mbp-book-title': 'update_title',
		},

		initialize: function() {
			this.listenTo(this.model, 'change:mbt_book', this.update_mbt_book);
		},

		render: function() {
			this.$el.html(this.template());
			this.render_title();
			return this;
		},

		render_title: function() {
			this.$('.mbp-book-title').val(this.model.get('title'));
		},

		update_mbt_book: function() {
			var title = this.model.get('title');

			var old_mbt_book = this.model.previous('mbt_book') ? mbt_books.get(this.model.previous('mbt_book')) : null;
			var old_mbt_book_title = old_mbt_book ? old_mbt_book.get('title') : null;

			var mbt_book = this.model.get('mbt_book') ? mbt_books.get(this.model.get('mbt_book')) : null;
			var mbt_book_title = mbt_book ? mbt_book.get('title') : null;

			if(!title || title == old_mbt_book_title) {
				title = mbt_book_title;
				this.model.set('title', title);
				this.render_title();
			}
		},

		update_title: function() {
			this.model.set('title', this.$('.mbp-book-title').val());
		},
	});

	/*---------------------------------------------------------*/
	/* Book Phases Step                                        */
	/*---------------------------------------------------------*/

	mybookprogress.BookSetupPhasesView = View.extend({
		template: mybookprogress.utils.template(jQuery('#book_setup_phase_step_template').html()),

		initialize: function() {
			this.set_subview('.mbp-book-phase-template-container', new mybookprogress.BookSetupPhaseTemplatesView({model: this.model}));
			this.phase_editor = new mybookprogress.BookSetupPhaseEditorView({model: this.model});
			this.listenTo(this.phase_editor, 'change_current_phase', this.change_current_phase);
			this.listenTo(this.phase_editor, 'change_phases_length', this.render_current_phase_indicator);
			this.set_subview('.mbp-book-phases-editor', this.phase_editor);
			this.change_current_phase();
		},

		change_current_phase: function() {
			this.set_subview('.mbp-book-phase-details-section', new mybookprogress.BookSetupPhaseDetailView({model: this.phase_editor.current_phase, book: this.model}));
			this.render_current_phase_indicator();
		},

		render_current_phase_indicator: function() {
			var current_phase_view = this.phase_editor.book_phases_view.model_view(this.phase_editor.current_phase);
			if(current_phase_view) {
				setTimeout(_.bind(function() {
					this.$('.mbp-book-phase-indicator').show().css({
						top: this.$('.mbp-book-phase-details-section').offset().top-this.$el.offset().top,
						left: Math.max(window.innerWidth <= 1500 ? 80 : 100, current_phase_view.$el.offset().left-this.$el.offset().left+current_phase_view.$el.outerWidth()*0.5),
					});
				}, this), 0);
			}
		},

		render: function() {
			View.prototype.render.call(this);
			this.render_current_phase_indicator();
			return this;
		},
	});

	/*---------------------------------------------------------*/
	/* Phase Template Selector                                 */
	/*---------------------------------------------------------*/

	mybookprogress.BookSetupPhaseTemplatesView = View.extend({
		template: mybookprogress.utils.template(jQuery('#book_setup_phase_template_template').html()),

		events: {
			'change .mbp-book-phase-template': 'phase_template_change',
			'click .mbp-template-manager-button': 'manage_templates',
		},

		initialize: function() {
			this.listenTo(this.model, 'change:phases', this.render_options);
			this.listenTo(phase_templates, 'change add remove reset sort', this.render_options);
		},

		render: function() {
			this.$el.html(this.template());
			this.render_options();
			return this;
		},

		render_options: function() {
			var template_id = this.model.get('phases');
			if(typeof template_id !== 'string') {
				template_id = null;
			} else if(!phase_templates.get(template_id)) {
				template_id = phase_templates.at(0).id;
				this.model.set({phases: phase_templates.at(0).id, phases_status: {}});
			}

			var select = this.$('.mbp-book-phase-template');
			select.empty();
			select.append(jQuery('<option value="custom" class="mbp-book-phase-template-custom" '+(template_id ? '' : ' selected="selected"')+'>'+mybookprogress_i18n.custom+'</option>'));
			phase_templates.each(function(template) {
				select.append(jQuery('<option value="'+template.id+'"'+(template_id === template.id ? ' selected="selected"' : '')+'>'+template.get('name')+'</option>'));
			}, this);
		},

		phase_template_change: function() {
			var template = this.$('.mbp-book-phase-template').val();
			if(template === 'custom') {
				if(typeof this.model.get('phases') === 'string') {
					this.model.set({phases: phase_templates.get(this.model.get('phases')).get('phases')});
				}
			} else {
				this.model.set({phases: template, phases_status: {}});
			}
		},

		manage_templates: function() {
			var modal = new mybookprogress.PhaseTemplateManagerView({model: this.model});
		},
	});

	/*---------------------------------------------------------*/
	/* Phase Template Manager                                  */
	/*---------------------------------------------------------*/

	mybookprogress.PhaseTemplateManagerView = ModalView.extend({
		template: mybookprogress.utils.template(jQuery('#book_setup_manage_templates_modal_template').html()),

		events: {
			'click .mbp-save-current': 'save_current',
			'click .mbp-close': 'close',
		},

		initialize: function(options) {
			ModalView.prototype.initialize.call(this, options);

			this.set_subview('.mbp-phase-templates', new CollectionView({collection: phase_templates, item_view: mybookprogress.PhaseTemplate}));
		},

		save_current: function() {
			var new_name = mybookprogress_i18n.new_template;
			var i = 2;
			while(phase_templates.where({name: new_name}).length) {
				new_name = mybookprogress_i18n.new_template+' '+i++;
			}

			var new_id = new Date().getTime();
			while(phase_templates.get(new_id.toString())) { new_id++; }
			new_id = new_id.toString();

			phase_templates.create({id: new_id, name: new_name, phases: this.model.get_phases()});
			this.model.set('phases', new_id);
		},
	});

	mybookprogress.PhaseTemplate = View.extend({
		className: 'mbp-phase-template',
		template: mybookprogress.utils.template(jQuery('#book_setup_phase_template_item_template').html()),

		events: {
			'click .mbp-template-delete': 'destroy',
			'input .mbp-template-name': 'updated',
			'blur .mbp-template-name': 'save',
		},

		bindings: {
			'name .mbp-template-name' : '',
		},

		updated: function() {
			if(this.update_timeout) { clearTimeout(this.update_timeout); }
			this.update_timeout = setTimeout(_.bind(this.save, this), 1000);
		},

		save: function() {
			if(this.update_timeout) {
				clearTimeout(this.update_timeout);
				this.update_timeout = null;
				this.model.save();
			}
		},

		destroy: function() {
			this.model.destroy();
		},
	});

	/*---------------------------------------------------------*/
	/* Phase Editor                                            */
	/*---------------------------------------------------------*/

	mybookprogress.BookSetupPhaseEditorView = View.extend({
		template: mybookprogress.utils.template(jQuery('#book_setup_phase_editor_template').html()),

		events: {
			'click .mbp-book-phase-adder': 'add_phase',
		},

		initialize: function() {
			this.max_phases = 7;
			if(!this.model.get_phases().length) { this.model.set({phases: phase_templates.at(0).id, phases_status: {}}); }
			this.load_phases();
			this.listenTo(this.model, 'change:phases', this.updated_phases);
		},

		render: function() {
			View.prototype.render.call(this);
			this.render_phases_length();
		},

		render_phases_length: function() {
			if(this.phase_collection.length > 4) {
				this.$('.mbp-book-phases').addClass('mbp-small-phases');
				this.$('.mbp-book-phase-adder').addClass('mbp-small-phases');
			 } else {
			 	this.$('.mbp-book-phases').removeClass('mbp-small-phases');
				this.$('.mbp-book-phase-adder').removeClass('mbp-small-phases');
			 }
			if(this.phase_collection.length >= this.max_phases) {
				this.$('.mbp-book-phase-adder').addClass('mbp-disabled');
			} else {
				this.$('.mbp-book-phase-adder').removeClass('mbp-disabled');
			}
		},

		load_phases: function() {
			var is_new = !Boolean(this.phase_collection);
			if(!is_new) {
				this.stopListening(this.phase_collection);
				this.stopListening(this.current_phase);
			}

			this.phase_collection = new VirtualCollection(this.model.get_phases(), {model: mybookprogress.BookSetupPhaseItemModel, comparator: 'index'});
			if(!this.phase_collection.length) { this.add_phase(); this.update_phases(); }

			this.book_phases_view = new CollectionView({
				collection: this.phase_collection,
				item_view: mybookprogress.BookSetupPhaseItemView,
				sortable: {
					sorting_attr: 'index',
					handle: '.mbp-book-phase-mover',
					placeholder: 'mbp-book-phase-placeholder'
				},
			});
			this.set_subview('.mbp-book-phases', this.book_phases_view);

			this.listenTo(this.phase_collection, 'change add remove sort', this.update_phases);
			this.listenTo(this.phase_collection, 'select', this.phase_selected);
			this.listenTo(this.phase_collection, 'change add remove', this.change_phases_length);
			this.change_phases_length();

			this.current_phase = null;
			for(var i = 0; i < this.phase_collection.models.length; i++) {
				var status = this.model.get_phase_status(this.phase_collection.models[i].id);
				if(status !== 'complete') {
					this.current_phase = this.phase_collection.models[i];
					break;
				}
			}
			if(!this.current_phase) { this.current_phase = this.phase_collection.at(0); }
			this.listenTo(this.current_phase, 'destroy', this.current_phase_destroyed);
			this.trigger('change_current_phase');
		},

		phase_selected: function(view) {
			this.update_current_phase(view.model.get('index'));
		},

		change_phases_length: function() {
			this.render_phases_length();
			this.trigger('change_phases_length');
		},

		update_current_phase: function(new_phase_index) {
			this.stopListening(this.current_phase);
			this.current_phase = this.phase_collection.at(new_phase_index);
			this.listenTo(this.current_phase, 'destroy', this.current_phase_destroyed);
			this.trigger('change_current_phase');
		},

		current_phase_destroyed: function() {
			this.update_current_phase(this.current_phase.get('index') == 0 ? this.current_phase.get('index')+1 : this.current_phase.get('index')-1);
		},

		add_phase: function() {
			if(this.phase_collection.length < this.max_phases) {
				this.phase_collection.create({index: this.phase_collection.length});
			}
		},

		update_phases: function() {
			var phases = [];
			var phase_ids = [];
			this.phase_collection.each(function(phase) {
				var phase = _.clone(phase.attributes);
				delete phase.index;
				phases.push(phase);
				phase_ids.push(phase.id);
			});

			var phases_status = jQuery.extend(true, {}, this.model.get('phases_status'));
			for(phase_id in phases_status) {
				if(!_.indexOf(phase_ids, phase_id)) { delete phases_status[phase_id]; }
			}
			this.model.set('phases_status', phases_status);

			this.model.set({phases: phases}, {from_phase_editor: true});
		},

		updated_phases: function(model, value, options) {
			if(options.from_phase_editor) { return; }
			this.load_phases();
		},
	});

	mybookprogress.BookSetupPhaseItemView = View.extend({
		className: 'mbp-book-phase',
		template: mybookprogress.utils.template(jQuery('#book_setup_phase_item_template').html()),
		events: {
			'click': 'select',
			'click .mbp-book-phase-remover': 'remover',
			'input .mbp-book-phase-name': 'update_name',
		},

		remover: function() {
			if(this.model.collection.length > 1) {
				this.model.destroy();
			}
		},

		update_name: function() {
			this.model.set('name', this.$('.mbp-book-phase-name').val());
		},

		select: function() {
			this.model.trigger('select', this);
		},
	});

	mybookprogress.BookSetupPhaseItemModel = VirtualModel.extend({
		defaults: {
			index: 0,
			name: '',
			deadline: null,
			progress_type: null,
			progress_target: 1,
		},

		initialize: function() {
			if(!mybookprogress.progress_types[this.get('progress_type')]) {
				this.change_progress_type(mybookprogress.default_progress_type);
			}
			if(!this.get('id')) { this.set('id', this.gen_id()); }
		},

		gen_id: function() {
			var id = new Date().getTime();
			while(this.collection.get(id)) { id++; }
			return id;
		},

		change_progress_type: function(progress_type) {
			var new_progress_type = mybookprogress.progress_types[progress_type];
			if(new_progress_type.default_target) {
				var old_progress_type = mybookprogress.progress_types[this.get('progress_type')];
				if(!old_progress_type || (old_progress_type.default_target && this.get('progress_target') === old_progress_type.default_target)) {
					this.set('progress_target', new_progress_type.default_target);
				}
			}

			this.set('progress_type', progress_type);
		},
	});

	/*---------------------------------------------------------*/
	/* Phase Details                                           */
	/*---------------------------------------------------------*/

	mybookprogress.BookSetupPhaseDetailView = View.extend({
		template: mybookprogress.utils.template(jQuery('#book_setup_phase_details_template').html()),
		events: {
			'click .mbp-phase-progress-type-button': 'update_progress_type',
			'click .mbp-phase-complete': 'update_phase_complete',
		},

		bindings: {
			'deadline .mbp-phase-deadline': 'unformat_deadline format_deadline',
			'progress_target .mbp-phase-progress-target': 'unformat_progress_target',
		},

		initialize: function(options) {
			this.book = options.book;
			this.listenTo(this.model, 'change:name', this.render);
			this.listenTo(this.model, 'change:progress_type', this.render_progress_types);
			this.listenTo(this.model, 'change:progress_type', this.render_progress_target);
		},

		render: function() {
			this.$el.html(this.template());
			this.render_bindings();

			this.render_phase_complete();
			this.render_controls();
			this.render_progress_types();
			this.render_progress_target();
			return this;
		},

		update_phase_complete: function() {
			status = this.$('.mbp-phase-complete').is(':checked') ? 'complete' : '';
			this.book.update_phase_status(this.model.id, status);
		},

		render_phase_complete: function() {
			var status = this.book.get_phase_status(this.model.id);
			this.$('.mbp-phase-complete').prop('checked', status == 'complete');
		},

		render_controls: function() {
			utils.datepicker(this.$('.mbp-phase-deadline'));
		},

		render_progress_types: function() {
			this.$('.mbp-phase-progress-types').empty();
			for(type in mybookprogress.progress_types) {
				var new_button = jQuery('<div class="mbp-setting-button mbp-phase-progress-type-button"></div>');
				new_button.attr('data-mbp-progress-type', type);
				new_button.text(mybookprogress.progress_types[type].name);
				if(type === this.model.get('progress_type')) { new_button.addClass('mbp-selected'); }
				this.$('.mbp-phase-progress-types').append(new_button);
			}
		},

		render_progress_target: function() {
			var progress_type = mybookprogress.progress_types[this.model.get('progress_type')];
			this.$('.mbp-phase-progress-target-units').text(progress_type.units);
			if(progress_type.hide_target_editor) { this.$('.mbp-phase-progress-target-setting').hide(); }
			else { this.$('.mbp-phase-progress-target-setting').show(); }
		},

		format_deadline: function(value) {
			if(value === null) { return ''; }
			return utils.format_date(value);
		},

		unformat_deadline: function(input, prev_value) {
			if(!input) { return null; }
			var date = utils.parse_date(input);
			if(typeof date === 'number') {
				this.$('.mbp-phase-deadline').removeClass('mbp-error');
				this.$('.mbp-phase-deadline-setting .mbp-setting-error').empty();
				return date;
			} else {
				this.$('.mbp-phase-deadline').addClass('mbp-error');
				this.$('.mbp-phase-deadline-setting .mbp-setting-error').text(date);
				return prev_value;
			}
		},

		update_progress_type: function(e) {
			this.model.change_progress_type(jQuery(e.target).attr('data-mbp-progress-type'));
		},

		unformat_progress_target: function(input, old_value) {
			if(!_.isFinite(parseInt(input))) { return old_value; }
			return Math.max(1, parseInt(input));
		},
	});

	/*---------------------------------------------------------*/
	/* Book Display                                            */
	/*---------------------------------------------------------*/

	mybookprogress.BookSetupDisplayView = View.extend({
		template: mybookprogress.utils.template(jQuery('#book_setup_display_step_template').html()),

		events: {
			'click .mbp-book-cover-image-button': 'clicked_cover_image',
		},

		initialize: function() {
			this.model.on('change:display_bar_color', this.render_colorpicker, this);
			this.model.on('change:display_cover_image', this.render_cover_image_button, this);

			settings.on('change:style_pack', this.render_preview, this);
			this.model.on('change:title', this.render_preview, this);
			this.model.on('change:phases', this.render_preview, this);
			this.model.on('change:display_bar_color', this.render_preview, this);
			this.model.on('change:display_cover_image', this.render_preview, this);
		},

		render: function() {
			this.$el.html(this.template());
			this.render_preview();
			this.render_controls();
			this.render_colorpicker();
			this.render_cover_image_button();
			return this;
		},

		render_preview: function() {
			var values = {
				style_pack: settings.get('style_pack'),
				title: this.model.get_title(),
				bar_color: this.model.get('display_bar_color'),
				cover_image: this.model.get('display_cover_image'),
				mbt_book: this.model.get('mbt_book'),
			};
			mybookprogress.WPQuery('mbp_get_preview', {values: JSON.stringify(values)}).then(_.bind(function(result) {
				this.$('.mbp-book-preview').html(result.output);
			}, this));
		},

		render_controls: function() {
			var barcolor = this.$('.mbp-book-bar-color');
			barcolor.colpick({layout: 'hex', submit: false, onChange: _.bind(this.barcolor_change, this)});
			barcolor.on('keydown', function(e) {
				if(e.keyCode === 8 || e.keyCode === 46) {
					return false;
				}
			});
			barcolor.on('keypress', function(e) {
				return false;
			});
			barcolor.on('paste', function(e) {
				var text;
				var clp = (e.originalEvent || e).clipboardData;
				if (clp === undefined || clp === null) {
					text = window.clipboardData.getData("text") || "";
				} else {
					text = clp.getData('text/plain') || "";
				}
				if(text !== "" && !/[a-fA-F0-9]{6}/.test(text)) {
					return false;
				}
			});
		},

		clicked_cover_image: function() {
			if(this.model.get('display_cover_image')) {
				this.model.set('display_cover_image', 0);
			} else {
				utils.media_selector(mybookprogress_i18n.cover_image, _.bind(this.cover_image_change, this));
			}
		},

		render_colorpicker: function() {
			var bar_color = this.model.get('display_bar_color');
			var color = bar_color.length == 6 ? bar_color : 'ffffff';
			var picker = this.$('.mbp-book-bar-color');
			picker.css('background', "#"+color);
			picker.css('color', utils.color_is_bright(color) ? 'black' : 'white');
			picker.colpickSetColor(color);
		},

		render_cover_image_button: function() {
			if(this.model.get('display_cover_image')) {
				this.$('.mbp-book-cover-image-button').addClass('has-image');
				this.$('.mbp-book-cover-image-button').text(mybookprogress_i18n.remove);
			} else {
				this.$('.mbp-book-cover-image-button').removeClass('has-image');
				this.$('.mbp-book-cover-image-button').text(mybookprogress_i18n.choose);
			}
		},

		barcolor_change: function(hsb, hex, rgb, el, bySetColor) {
			if(bySetColor) { return; }
			jQuery(el).val(hex);
			this.model.set('display_bar_color', hex);
		},

		cover_image_change: function(image) {
			this.model.set('display_cover_image', image['id']);
		},
	});

})();
(function () {
	var settings = null;

	mybookprogress.on('models_loaded', function() {
		settings = mybookprogress.settings;
	});

	var View = mybookprogress.View;

	mybookprogress.DisplayTabView = View.extend({
		className: 'mbp-admin-tab',
		template: mybookprogress.utils.template(jQuery('#display_tab_template').html()),

		events: {
			'change .mbp-widget-sidebar': 'render_button',
			'click .mbp-widget-button': 'clicked_widget_button',
			'click .mbp-alert-message': 'clicked_mailinglist_warning',
		},

		initialize: function() {
			settings.on('change:mailinglist_type', this.render_mailinglist_warning, this);
			settings.on('change:mailchimp_list', this.render_mailinglist_warning, this);
			settings.on('change:other_subscribe_url', this.render_mailinglist_warning, this);
		},

		render: function() {
			this.$el.html(this.template());
			this.render_button();
			this.render_mailinglist_warning();
			return this;
		},

		render_button: function() {
			var has_widget = this.$('.mbp-widget-sidebar option:selected').attr('data-has-widget');
			if(has_widget === 'yes') {
				this.$('.mbp-widget-button').addClass('mbp-edit-widget');
				this.$('.mbp-widget-button').html(mybookprogress_i18n.edit_widget);
			} else {
				this.$('.mbp-widget-button').removeClass('mbp-edit-widget');
				this.$('.mbp-widget-button').html(mybookprogress_i18n.add_widget);
			}
		},

		render_mailinglist_warning: function() {
			if(settings.get('mailinglist_type') === 'mailchimp') {
				if(!settings.get('mailchimp_list')) {
					this.$('.mbp-alert-message-container').show();
				} else {
					this.$('.mbp-alert-message-container').hide();
				}
			} else if(settings.get('mailinglist_type') === 'other') {
				if(!settings.get('other_subscribe_url')) {
					this.$('.mbp-alert-message-container').show();
				} else {
					this.$('.mbp-alert-message-container').hide();
				}
			} else {
				this.$('.mbp-alert-message-container').show();
			}
		},

		clicked_widget_button: function() {
			if(this.$('.mbp-widget-button').hasClass('mbp-edit-widget')) {
				return true;
			} else if(!this.$('.mbp-widget-button').hasClass('mbp-disabled')) {
				this.$('.mbp-widget-button').addClass('mbp-disabled');
				this.$('.mbp-widget-sidebar').attr('disabled', 'disabled');

				mybookprogress.WPQuery('mbp_add_sidebar', {sidebar: this.$('.mbp-widget-sidebar').val()}).then(_.bind(function(response) {
					this.$('.mbp-widget-button').removeClass('mbp-disabled');
					this.$('.mbp-widget-sidebar').removeAttr('disabled');
					this.$('.mbp-widget-sidebar option:selected').attr('data-has-widget', 'yes');
					this.render_button();
				}, this));
			}
			return false;
		},

		clicked_mailinglist_warning: function() {
			jQuery('.mbp-admin-tabs > .ui-tabs-nav a[href="#mbp-promote-tab"]').click();
		},
	});
})();
(function () {
	var settings = null;
	var books = null;

	mybookprogress.on('models_loaded', function() {
		settings = mybookprogress.settings;
		books = mybookprogress.books;
	});

	mybookprogress.ProgressTabView = mybookprogress.View.extend({
		className: 'mbp-admin-tab',

		currentview: null,
		currentbook: null,
		inbooksetup: false,

		initialize: function() {
			mybookprogress.on('loaded', function() {
				if(mybookprogress.utils.get_query_var('book')) {
					if(this.show_book(mybookprogress.utils.get_query_var('book'))) { return; }
				}
				if(mybookprogress.utils.get_query_var('books')) {
					var books = mybookprogress.utils.get_query_var('books').split(',');
					if(books.length && this.show_book(parseInt(books[0]))) { return; }
				}
				this.show_current_book();
			}, this);
		},

		toggle_setup: function() {
			if(!this.currentbook) { return; }
			if(this.inbooksetup) {
				this.change_view(new mybookprogress.BookProgressView({model:this.currentbook}));
				this.inbooksetup = false;
			} else {
				this.change_view(new mybookprogress.BookSetupView({model:this.currentbook}));
				this.inbooksetup = true;
			}
		},

		render: function() {
			this.$el.empty();
			if(this.currentview) { this.$el.append(this.currentview.render().$el); }
			return this;
		},

		change_view: function(view) {
			if(this.currentview) { this.currentview.remove(); }
			this.currentview = view;
			this.render();
		},

		show_overview: function() {
			this.currentbook = null;
			this.change_view(new mybookprogress.OverviewView());
			this.update_current_book();
		},

		show_book: function(book_id) {
			book_id = typeof book_id === 'number' ? book_id : (typeof book_id === 'string' ? parseInt(book_id) : 0);
			var book = null;
			if(book_id > 0) {
				var new_book = books.get(book_id);
				if(!new_book) { return false; }
				this.currentbook = new_book;
				this.update_current_book();
				this.change_view(new mybookprogress.BookProgressView({model:this.currentbook}));
				this.inbooksetup = false;
			} else {
				this.currentbook = new mybookprogress.Book();
				this.change_view(new mybookprogress.BookSetupView({model:this.currentbook}));
				this.inbooksetup = true;
			}
			return true;
		},

		show_current_book: function() {
			var current_book = this.get_current_book();
			if(current_book == 'overview') {
				this.show_overview();
			} else {
				this.show_book(current_book);
			}
		},

		update_current_book: function() {
			if(this.currentbook) {
				settings.set('current_book', this.currentbook.id);
			} else {
				settings.set('current_book', 'overview');
			}
		},

		get_current_book: function() {
			var val = settings.get('current_book');
			if(val !== 'overview' && !books.get(val)) {
				if(books.length > 0) {
					val = books.at(0).id;
					settings.set('current_book', val);
				} else {
					val = -1;
					settings.set('current_book', val);
				}
			}
			return val;
		},
	});
})();
(function () {
	var settings = null;
	var utils = mybookprogress.utils;

	mybookprogress.on('models_loaded', function() {
		settings = mybookprogress.settings;
	});

	var View = mybookprogress.View;

	mybookprogress.PromoteTabView = View.extend({
		className: 'mbp-admin-tab',
		template: mybookprogress.utils.template(jQuery('#promote_tab_template').html()),
		initialize: function() {
			this.set_subview('.mbp-mailinglist-section', new mybookprogress.SetupMailinglistView());
			this.set_subview('.mbp-mybooktable-section', new mybookprogress.SetupMyBookTableView());
			this.set_subview('.mbp-linkback-button-container', new mybookprogress.SetupLinkbackView());
		}
	});

	mybookprogress.SetupMyBookTableView = View.extend({
		template: mybookprogress.utils.template(jQuery('#setup_mybooktable_template').html()),
		events: {
			'change .mbp-mybooktable-social-media-link': 'changed_mybooktable_social_media_link',
			'change .mbp-mybooktable-frontend-link': 'changed_mybooktable_frontend_link',
			'click .mbp-install-mybooktable-button': 'install_mybooktable',
		},
		render: function() {
			View.prototype.render.call(this);
			if(!settings.get('mybooktable_installed')) { this.$el.addClass('mbp-disabled'); } else { this.$el.removeClass('mbp-disabled'); }
			this.$('.mbp-mybooktable-social-media-link').prop('checked', settings.get('mybooktable_social_media_link'));
			this.$('.mbp-mybooktable-frontend-link').prop('checked', settings.get('mybooktable_frontend_link'));
			return this;
		},
		changed_mybooktable_social_media_link: function() {
			settings.set('mybooktable_social_media_link', this.$('.mbp-mybooktable-social-media-link').is(':checked'));
		},
		changed_mybooktable_frontend_link: function() {
			settings.set('mybooktable_frontend_link', this.$('.mbp-mybooktable-frontend-link').is(':checked'));
		},
		install_mybooktable: function() {
			var content = jQuery('<iframe></iframe>');
			content.attr('src', this.$('.mbp-install-mybooktable-button').attr('data-mbp-link'));
			content.css({width: '100%', height: '99%'});
			utils.modal(content, {width: 800, height: '90%'});
		}
	});

	mybookprogress.SetupMailChimpView = View.extend({
		template: mybookprogress.utils.template(jQuery('#setup_mailchimp_template').html()),

		events: {
			'click .mbp-mailchimp-apikey-setting .mbp-setting-feedback': 'clicked_feedback',
			'input .mbp-mailchimp-apikey': 'changed_apikey',
			'change .mbp-mailchimp-list': 'changed_mailing_list',
		},

		initialize: function() {
			this.verify_apikey();
		},

		render: function() {
			this.$el.html(this.template());
			this.$('.mbp-mailchimp-apikey').val(settings.get('mailchimp_apikey'));
			this.render_controls();
			return this;
		},

		render_controls: function() {
			this.$('.mbp-mailchimp-apikey').attr('class', 'mbp-mailchimp-apikey');
			this.$('.mbp-mailchimp-apikey').removeAttr('disabled');
			this.$('.mbp-mailchimp-apikey-setting .mbp-setting-feedback').attr('class', 'mbp-setting-feedback');
			if(this.apikey_status == 'checking') {
				this.$('.mbp-mailchimp-apikey').attr('disabled', 'disabled');
				this.$('.mbp-mailchimp-apikey-setting .mbp-setting-feedback').addClass('checking');
			} else if(this.apikey_status == 'good') {
				this.$('.mbp-mailchimp-apikey').addClass('mbp-correct');
				this.$('.mbp-mailchimp-apikey-setting .mbp-setting-feedback').addClass('good');
			} else if(this.apikey_status == 'bad') {
				this.$('.mbp-mailchimp-apikey').addClass('mbp-error');
				this.$('.mbp-mailchimp-apikey-setting .mbp-setting-feedback').addClass('bad');
			} else if(this.apikey_status == 'editing') {
				this.$('.mbp-mailchimp-apikey-setting .mbp-setting-feedback').addClass('refresh');
			} else if(this.apikey_status == 'empty') {
				this.$('.mbp-mailchimp-apikey-setting .mbp-setting-feedback').addClass('help');
			}

			this.$('.mbp-mailchimp-list').attr('class', 'mbp-mailchimp-list');
			this.$('.mbp-mailchimp-list').removeAttr('disabled');
			this.$('.mbp-mailchimp-list-setting .mbp-setting-feedback').attr('class', 'mbp-setting-feedback');
			this.$('.mbp-mailchimp-list-setting .mbp-setting-feedback .mbp-setting-feedback-text').empty();
			if(this.apikey_status == 'good') {
				if(this.mailing_lists_status == 'checking') {
					this.$('.mbp-mailchimp-list').attr('disabled', 'disabled');
					this.$('.mbp-mailchimp-list').html('<option> '+mybookprogress_i18n.loading+'... </option>');
					this.$('.mbp-mailchimp-list-setting .mbp-setting-feedback').addClass('checking');
				} else {
					var mailchimp_list = settings.get('mailchimp_list');
					if(this.mailing_lists.length == 0) {
						this.$('.mbp-mailchimp-list').attr('disabled', 'disabled');
						this.$('.mbp-mailchimp-list').html('<option> -- '+mybookprogress_i18n.no_lists_available+' -- </option>');
						this.$('.mbp-mailchimp-list-setting .mbp-setting-feedback').addClass('none');
					} else {
						this.$('.mbp-mailchimp-list').empty();
						if(!mailchimp_list) {
							this.$('.mbp-mailchimp-list').html('<option value=""> -- '+mybookprogress_i18n.choose_one+' -- </option>');
							this.$('.mbp-mailchimp-list').addClass('mbp-error');
							this.$('.mbp-mailchimp-list-setting .mbp-setting-feedback').addClass('bad');
						} else {
							var subscribers = this.mailing_list.stats.member_count;
							this.$('.mbp-mailchimp-list').addClass('mbp-correct');
							this.$('.mbp-mailchimp-list-setting .mbp-setting-feedback .mbp-setting-feedback-text').html(subscribers > 0 ? subscribers+' '+mybookprogress_i18n.subscribers : '');
							this.$('.mbp-mailchimp-list-setting .mbp-setting-feedback').addClass('good');
						}
						for(var i = this.mailing_lists.length - 1; i >= 0; i--) {
							var is_current_list = this.mailing_lists[i].id === mailchimp_list;
							this.$('.mbp-mailchimp-list').append('<option value="'+this.mailing_lists[i].id+'"'+(is_current_list ? ' selected="selected"' : '')+'>'+this.mailing_lists[i].name+'</option>');
						}
					}
				}
			} else {
				this.$('.mbp-mailchimp-list').attr('disabled', 'disabled');
				this.$('.mbp-mailchimp-list').html('<option> -- '+mybookprogress_i18n.no_lists_available+' -- </option>');
				this.$('.mbp-mailchimp-list-setting .mbp-setting-feedback').addClass('none');
			}
		},

		clicked_feedback: function() {
			if(this.apikey_status == 'editing') {
				settings.set({mailchimp_apikey: this.$('.mbp-mailchimp-apikey').val(), mailchimp_list: '', mailchimp_subscribe_url: ''});
				this.verify_apikey();
			} else if(this.apikey_status == 'empty') {
				window.open('http://kb.mailchimp.com/accounts/management/about-api-keys', '_blank').focus();
			}
		},

		changed_apikey: function() {
			this.apikey_status = 'editing';
			this.render_controls();
		},

		changed_mailing_list: function() {
			var mailchimp_list = this.$('.mbp-mailchimp-list').val();
			var mailchimp_subscribe_url = settings.get('mailchimp_subscribe_url');
			for(var i = this.mailing_lists.length - 1; i >= 0; i--) {
				if(this.mailing_lists[i].id == mailchimp_list) {
					mailchimp_subscribe_url = this.mailing_lists[i].subscribe_url_long;
					this.mailing_list = this.mailing_lists[i];
				}
			}
			settings.set({mailchimp_list: mailchimp_list, mailchimp_subscribe_url: mailchimp_subscribe_url});
			this.render_controls();
		},

		verify_apikey: function() {
			if(settings.get('mailchimp_apikey') === '') {
				this.apikey_status = 'empty';
			} else {
				this.apikey_status = 'checking';
				utils.do_mailchimp_query('ping').done(_.bind(this.verified_apikey, this));
				// WAS utils.do_mailchimp_query('helper/ping').done(_.bind(this.verified_apikey, this)); in api 2.0
			}
			this.render_controls();
		},

		verified_apikey: function(response) {
			//var verified = response && response.msg;
			var verified = response;
			this.apikey_status = verified ? 'good' : 'bad';
			if(this.apikey_status == 'good') {
				this.mailing_lists_status = 'checking'; 
				utils.do_mailchimp_query('lists').done(_.bind(this.fetched_mailing_lists, this));
				// method was lists/list in MC api 2.0
			} else if(settings.get('mailchimp_list')) {
				settings.set({mailchimp_list: '', mailchimp_subscribe_url: ''});
			}
			this.render_controls();
		},

		fetched_mailing_lists: function(response) {
			this.mailing_lists_status = 'done';
			if(!response || response.error || !response.lists || !_.isArray(response.lists)) {
				this.mailing_lists = [];
				settings.set({mailchimp_list: '', mailchimp_subscribe_url: ''});
			} else {
				this.mailing_lists = response.lists;
			//console.log('Jim Was Here');
			//console.log(settings.get('mailchimp_list'));				
				if(settings.get('mailchimp_list')) {
					var found_current_list = false;
					for(var i = this.mailing_lists.length - 1; i >= 0; i--) {
						if(this.mailing_lists[i].id == settings.get('mailchimp_list')) {
							found_current_list = true;
							if(this.mailing_lists[i].subscribe_url_long !== settings.get('mailchimp_subscribe_url')) {
								settings.set('mailchimp_subscribe_url', this.mailing_lists[i].subscribe_url_long);
							}
							this.mailing_list = this.mailing_lists[i];
							break;
						}
					}
					if(!found_current_list) {
						settings.set({mailchimp_list: '', mailchimp_subscribe_url: ''});
					}
				}
			}
			this.mailing_lists_status = settings.get('mailchimp_list') === '' ? 'bad' : 'good';
			this.render_controls();
		},
	});

	mybookprogress.SetupOtherView = View.extend({
		template: mybookprogress.utils.template(jQuery('#setup_other_template').html()),

		events: {
			'input .mbp-other-mailinglist-url': 'update_mailinglist',
			'blur .mbp-other-mailinglist-url': 'save_mailinglist',
			'click .mbp-other-mailinglist-setting .mbp-setting-feedback': 'clicked_feedback',
		},

		initialize: function() {
			this.editing = false;
		},

		render: function() {
			this.$el.html(this.template());
			this.$('.mbp-other-mailinglist-url').val(settings.get('other_subscribe_url'));
			this.render_feedback();
			return this;
		},

		update_mailinglist: function() {
			if(this.update_timer) { clearTimeout(this.update_timer); }
			this.update_timer = setTimeout(_.bind(this.save_mailinglist, this), 1000);
			this.editing = true;
			this.render_feedback();
		},

		save_mailinglist: function() {
			if(this.update_timer) { clearTimeout(this.update_timer); this.update_timer = null; }
			settings.set('other_subscribe_url', this.$('.mbp-other-mailinglist-url').val());
			this.editing = false;
			this.render_feedback();
		},

		clicked_feedback: function() {
			if(this.$('.mbp-other-mailinglist-setting .mbp-setting-feedback').hasClass('help')) {
				window.open('http://www.google.com/', '_blank').focus();
			}
		},

		render_feedback: function() {
			// url matching regex from http://codegolf.stackexchange.com/questions/464/shortest-url-regex-match-in-javascript
			var url_regex = /^https?:\/\/[\w-]+(\.[\w-]+)+\.?(:\d+)?(\/\S*)?$/i;

			var url = settings.get('other_subscribe_url') || '';
			this.$('.mbp-other-mailinglist-url').attr('class', 'mbp-other-mailinglist-url')
			this.$('.mbp-other-mailinglist-setting .mbp-setting-feedback').attr('class', 'mbp-setting-feedback');
			if(this.editing) {
				this.$('.mbp-other-mailinglist-setting .mbp-setting-feedback').addClass('checking');
			} else if(url == '') {
				this.$('.mbp-other-mailinglist-setting .mbp-setting-feedback').addClass('help');
			} else if(url.match(url_regex)) {
				this.$('.mbp-other-mailinglist-setting .mbp-setting-feedback').addClass('good');
				this.$('.mbp-other-mailinglist-url').addClass('mbp-correct');
			} else {
				this.$('.mbp-other-mailinglist-setting .mbp-setting-feedback').addClass('bad');
				this.$('.mbp-other-mailinglist-url').addClass('mbp-error');
			}
		},
	});

	mybookprogress.SetupMailinglistView = View.extend({
		template: mybookprogress.utils.template(jQuery('#setup_mailinglist_template').html()),

		events: {
			'click .mbp-mailinglist-type-button': 'update_mailinglist_type',
		},

		mailinglist_types: {
			mailchimp: {
				name: mybookprogress_i18n.mailchimp,
				view: mybookprogress.SetupMailChimpView,
			},
			other: {
				name: mybookprogress_i18n.other,
				view: mybookprogress.SetupOtherView,
			},
		},

		initialize: function() {
			settings.on('change:mailinglist_type', this.render_mailinglist_types, this);
		},

		render: function() {
			this.$el.html(this.template());
			this.render_mailinglist_types();
			return this;
		},

		render_mailinglist_types: function() {
			var current_type = settings.get('mailinglist_type');
			this.$('.mbp-mailinglist-types').empty();
			for(type in this.mailinglist_types) {
				var new_button = jQuery('<div class="mbp-setting-button mbp-mailinglist-type-button"></div>');
				new_button.attr('data-mbp-mailinglist-type', type);
				new_button.text(this.mailinglist_types[type].name);
				if(type === current_type) { new_button.addClass('mbp-selected'); }
				this.$('.mbp-mailinglist-types').append(new_button);
			}
			if(current_type in this.mailinglist_types) {
				this.set_subview('.mbp-mailinglist-setup', new this.mailinglist_types[current_type].view());
			}
		},

		update_mailinglist_type: function(e) {
			settings.set('mailinglist_type', jQuery(e.target).attr('data-mbp-mailinglist-type'));
		},
	});

	mybookprogress.SetupLinkbackView = View.extend({
		template: mybookprogress.utils.template(jQuery('#setup_linkback_template').html()),

		events: {
			'click .mbp-linkback-button': 'clicked',
			'mouseleave .mbp-linkback-button': 'reset',
		},

		render: function() {
			this.$el.html(this.template());
			if(!settings.get('enable_linkback')) { this.$('.mbp-linkback-heart').addClass('broken'); }
			this.set_message(settings.get('enable_linkback') ? 'enabled' : 'disabled', false);
			return this;
		},

		set_message: function(message, animate) {
			if(typeof animate === 'undefined') { animate = true; }

			var old_messages = this.$('.mbp-linkback-messages .mbp-linkback-message:not(.mbp-hidden)');
			if(animate) {
				old_messages.stop().animate({opacity: 0}, {duration: 500}).addClass('mbp-hidden');
			} else {
				old_messages.css({opacity: 0}).addClass('mbp-hidden');
			}

			var shown_message = this.$('.mbp-linkback-message-'+message);
			shown_message.removeClass('mbp-hidden');
			if(animate) {
				shown_message.stop().animate({opacity: 1}, {duration: 500});
			} else {
				shown_message.css({opacity: 1});
			}
		},

		clicked: function() {
			if(this.$('.mbp-linkback-button').hasClass('inactive')) { return; }
			settings.set('enable_linkback', !settings.get('enable_linkback'));
			this.$('.mbp-enable-linkback').prop("checked", settings.get('enable_linkback'));
			this.$('.mbp-linkback-button').addClass('inactive');
			if(settings.get('enable_linkback')) {
				this.$('.mbp-linkback-heart').removeClass('broken');
				this.set_message('enable');
				if(this.linkback_timer) { window.clearTimeout(this.linkback_timer); }
				this.linkback_timer = window.setTimeout(_.bind(function() {
					this.set_message('enabled');
					this.linkback_timer = null;
				}, this), 2000);
			} else {
				this.$('.mbp-linkback-heart').addClass('broken');
				this.set_message('disable');
				if(this.linkback_timer) { window.clearTimeout(this.linkback_timer); }
				this.linkback_timer = window.setTimeout(_.bind(function() {
					this.set_message('disabled');
					this.linkback_timer = null;
				}, this), 2000);
			}
		},

		reset: function() {
			this.$('.mbp-linkback-button').removeClass('inactive');
		},
	});
})();
(function () {
	var settings = null;
	var style_packs = null;
	var books = null;
	var utils = mybookprogress.utils;

	mybookprogress.on('models_loaded', function() {
		settings = mybookprogress.settings;
		style_packs = mybookprogress.style_packs;
		books = mybookprogress.books;
	});

	var View = mybookprogress.View;
	var CollectionView = mybookprogress.CollectionView;

	mybookprogress.StyleTabView = View.extend({
		className: 'mbp-admin-tab',
		template: mybookprogress.utils.template(jQuery('#style_tab_template').html()),

		events: {
			'click .mbp-upload-stylepack-button': 'upload_stylepack',
		},

		initialize: function() {
			this.set_subview('.mbp-style-packs', new mybookprogress.StylePacksView());
			this.set_subview('.mbp-book-style-sections', new mybookprogress.BookStylesView());
		},

		upload_stylepack: function() {
			utils.media_selector(mybookprogress_i18n.style_pack, _.bind(this.uploaded_stylepack, this));
		},

		uploaded_stylepack: function(pack) {
			mybookprogress.WPQuery('mbp_upload_stylepack', {stylepack_id: pack['id']}).then(_.bind(function(response) {
				style_packs.fetch();
			}, this));
		},
	});

	mybookprogress.StylePackView = View.extend({
		template: mybookprogress.utils.template(jQuery('#style_tab_style_pack_template').html()),

		events: {
			'click': 'activate',
		},

		initialize: function() {
			this.model.on('change:active', this.render_active, this);
		},

		render: function() {
			this.setElement(jQuery(this.template()));
			this.render_active();
			return this;
		},

		render_active: function() {
			if(this.model.get('active')) {
				this.$el.addClass('active');
			} else {
				this.$el.removeClass('active');
			}
		},

		activate: function() {
			if(!this.model.get('active')) {
				this.model.trigger('activate', this.model);
			}
		},
	});

	mybookprogress.StylePacksView = CollectionView.extend({
		item_view: mybookprogress.StylePackView,

		initialize: function(options) {
			this.collection = style_packs;
			CollectionView.prototype.initialize.call(this, options);

			this.style_pack = null;

			this.collection.each(function(pack) {
				if(pack.id === settings.get('style_pack')) {
					this.style_pack = pack;
					pack.set('active', true);
				} else {
					pack.set('active', false);
				}
			}, this);

			if(!this.style_pack) {
				this.style_pack = this.collection.at(0);
				this.style_pack.set('active', true);
				settings.set('style_pack', this.style_pack.id);
			}

			this.collection.on('activate', function(pack) {
				this.style_pack.set('active', false);
				this.style_pack = pack;
				this.style_pack.set('active', true);

				settings.set('style_pack', this.style_pack.id);
			}, this);
		},
	});

	mybookprogress.BookStyleView = View.extend({
		template: mybookprogress.utils.template(jQuery('#style_tab_book_style_template').html()),

		events: {
			'click .mbp-book-cover-image-button': 'clicked_cover_image',
		},

		initialize: function() {
			this.model.on('change:title', this.render_title, this);
			this.model.on('change:display_bar_color', this.render_colorpicker, this);
			this.model.on('change:display_cover_image', this.render_cover_image_button, this);

			settings.on('change:style_pack', this.render_preview, this);
			this.model.on('change:title', this.render_preview, this);
			this.model.on('change:phases', this.render_preview, this);
			this.model.on('change:display_bar_color', this.render_preview, this);
			this.model.on('change:display_cover_image', this.render_preview, this);
		},

		render: function() {
			this.$el.html(this.template());
			this.render_preview();
			this.render_controls();
			this.render_colorpicker();
			this.render_cover_image_button();
			this.render_title();
			return this;
		},

		render_title: function() {
			this.$('.mbp-section-header').text(this.model.get_title());
		},

		render_preview: function() {
			var values = {
				style_pack: settings.get('style_pack'),
				title: this.model.get_title(),
				bar_color: this.model.get('display_bar_color'),
				cover_image: this.model.get('display_cover_image'),
				mbt_book: this.model.get('mbt_book'),
			};
			mybookprogress.WPQuery('mbp_get_preview', {values: JSON.stringify(values)}).then(_.bind(function(result) {
				this.$('.mbp-book-preview').html(result.output);
			}, this));

			var style_pack = style_packs.get(settings.get('style_pack'));
			if(style_pack.get('supports').indexOf('bar-color') === -1) {
				this.$('.mbp-book-bar-color-setting').css('display', 'none');
			} else {
				this.$('.mbp-book-bar-color-setting').css('display', '');
			}
		},

		render_controls: function() {
			var barcolor = this.$('.mbp-book-bar-color');
			barcolor.colpick({layout: 'hex', submit: false, onChange: _.bind(this.barcolor_change, this)});
			barcolor.on('keydown', function(e) {
				if(e.keyCode === 8 || e.keyCode === 46) {
					return false;
				}
			});
			barcolor.on('keypress', function(e) {
				return false;
			});
			barcolor.on('paste', function(e) {
				var text;
				var clp = (e.originalEvent || e).clipboardData;
				if (clp === undefined || clp === null) {
					text = window.clipboardData.getData("text") || "";
				} else {
					text = clp.getData('text/plain') || "";
				}
				if(text !== "" && !/[a-fA-F0-9]{6}/.test(text)) {
					return false;
				}
			});
		},

		clicked_cover_image: function() {
			if(this.model.get('display_cover_image')) {
				this.model.set('display_cover_image', 0);
				this.model.save();
			} else {
				utils.media_selector(mybookprogress_i18n.cover_image, _.bind(this.cover_image_change, this));
			}
		},

		render_colorpicker: function() {
			var bar_color = this.model.get('display_bar_color');
			var color = bar_color.length == 6 ? bar_color : 'ffffff';
			var picker = this.$('.mbp-book-bar-color');
			picker.css('background', "#"+color);
			picker.css('color', utils.color_is_bright(color) ? 'black' : 'white');
			picker.colpickSetColor(color);
		},

		render_cover_image_button: function() {
			if(this.model.get('display_cover_image')) {
				this.$('.mbp-book-cover-image-button').addClass('has-image');
				this.$('.mbp-book-cover-image-button').text(mybookprogress_i18n.remove);
			} else {
				this.$('.mbp-book-cover-image-button').removeClass('has-image');
				this.$('.mbp-book-cover-image-button').text(mybookprogress_i18n.choose);
			}
		},

		barcolor_change: function(hsb, hex, rgb, el, bySetColor) {
			if(bySetColor) { return; }
			jQuery(el).val(hex);
			this.model.set('display_bar_color', hex);
			this.model.save();
		},

		cover_image_change: function(image) {
			this.model.set('display_cover_image', image['id']);
			this.model.save();
		},
	});

	mybookprogress.BookStylesView = CollectionView.extend({
		item_view: mybookprogress.BookStyleView,
		initialize: function(options) {
			this.collection = books;
			CollectionView.prototype.initialize.call(this, options);
		},
	});
})();
(function () {
	var settings = null;

	mybookprogress.on('models_loaded', function() {
		settings = mybookprogress.settings;
	});

	var View = mybookprogress.View;

	mybookprogress.UpgradeTabView = View.extend({
		className: 'mbp-admin-tab',
		template: mybookprogress.utils.template(jQuery('#upgrade_tab_template').html()),

		events: {
			'click .mbp-upgrade-upsell-button': 'upgrade',
			'click .mbp-upgrade-moreinfo-button': 'moreinfo',
			'click .mbp-alert-message': 'click_alert',
		},

		initialize: function() {
			this.set_subview('.mbp-apikey-section', new mybookprogress.APIKeyView());
			this.listenTo(settings, 'change:upgrade_enabled change:upgrade_exists', this.detect_alert);
			setTimeout(_.bind(function() { this.detect_alert(); }, this), 0);
		},

		render: function() {
			View.prototype.render.call(this);
			this.render_alert();
			return this;
		},

		render_alert: function() {
			if(this.alert) {
				if(!jQuery('.mbp-admin-tabs > .ui-tabs-nav a[href="#mbp-upgrade-tab"] .mbp-alert').length) {
					jQuery('.mbp-admin-tabs > .ui-tabs-nav a[href="#mbp-upgrade-tab"]').append(jQuery('<div class="mbp-alert"></div>'));
				}
				if(this.alert == 'no_upgrade_plugin') {
					this.$('.mbp-alert-message').html(mybookprogress_i18n.upgrade_plugin_not_installed_error);
					this.$('.mbp-alert-message').removeClass('mbp-disabled');
				} else if(this.alert == 'no_apikey') {
					this.$('.mbp-alert-message').html(mybookprogress_i18n.problem_with_apikey_error);
					this.$('.mbp-alert-message').addClass('mbp-disabled');
				}
				this.$('.mbp-alert-message-container').show();
			} else {
				jQuery('.mbp-admin-tabs > .ui-tabs-nav a[href="#mbp-upgrade-tab"] .mbp-alert').remove();
				this.$('.mbp-alert-message-container').hide();
			}
		},

		click_alert: function() {
			if(this.alert == 'no_upgrade_plugin') {
				window.location.href = window.location.href.substring(0, window.location.href.indexOf('wp-admin/')+9)+'admin.php?page=mybookprogress&subpage=mbp_get_upgrade_page';
			}
		},

		detect_alert: function() {
			if(settings.get('upgrade_enabled') !== false && settings.get('upgrade_exists') !== settings.get('upgrade_enabled')) {
				this.alert = 'no_upgrade_plugin';
			} else if(settings.get('upgrade_enabled') === false && settings.get('upgrade_exists') !== false) {
				this.alert = 'no_apikey';
			} else {
				this.alert = '';
			}

			this.render_alert();
		},

		upgrade: function() {
			mbp_track_event('upgrade_tab_upgrade_button_clicked');
			window.open('https://gumroad.com/l/MyBookProgressPro', '_blank').focus();
		},

		moreinfo: function() {
			mbp_track_event('upgrade_tab_moreinfo_button_clicked');
			window.open('http://www.authormedia.com/all-products/mybookprogress/pricing/', '_blank').focus();
		},
	});

	mybookprogress.APIKeyView = View.extend({
		template: mybookprogress.utils.template(jQuery('#upgrade_apikey_template').html()),

		events: {
			'input .mbp-apikey': 'changed_apikey',
			'click .mbp-apikey-setting .mbp-setting-feedback': 'clicked_feedback',
			'click .mbp-upgrade-enter-apikey-button': 'enter_apikey',
		},

		initialize: function() {
			this.verified_apikey();
			this.has_apikey = (settings.get('apikey') !== '' || settings.get('upgrade_exists') !== false);
		},

		render: function() {
			View.prototype.render.call(this);
			this.$('.mbp-apikey').val(settings.get('apikey'));
			this.render_view();
			this.render_feedback();
			return this;
		},

		render_view: function() {
			if(this.has_apikey) {
				this.$('.mbp-upgrade-buynow').hide();
				this.$('.mbp-upgrade-enter-apikey').show();
			} else {
				this.$('.mbp-upgrade-buynow').show();
				this.$('.mbp-upgrade-enter-apikey').hide();
			}
		},

		enter_apikey: function() {
			this.has_apikey = true;
			this.render_view();
		},

		clicked_feedback: function() {
			if(this.apikey_status == 'editing') {
				settings.set({apikey: this.$('.mbp-apikey').val()}, {no_save: true});
				this.verify_apikey();
			} else if(this.apikey_status == 'empty') {
				window.open('http://www.mybookprogress.com', '_blank').focus();
			}
		},

		changed_apikey: function() {
			this.apikey_status = 'editing';
			this.render_feedback();
		},

		verify_apikey: function() {
			this.apikey_status = 'checking';
			mybookprogress.WPQuery('mbp_update_apikey', {apikey: settings.get('apikey')}).then(_.bind(this.verified_apikey, this));
			this.render_feedback();
		},

		verified_apikey: function(response) {
			if(typeof response !== 'undefined' && response !== null && typeof response === 'object' && !('error' in response)) {
				settings.set({
					apikey_status: response.apikey_status,
					apikey_message: response.apikey_message,
					upgrade_enabled: response.upgrade_enabled,
					upgrade_exists: response.upgrade_exists,
				}, {no_save: true});
			}

			this.apikey_status = settings.get('apikey_status') == 0 ? 'empty' : (settings.get('apikey_status') > 0 ? 'good' : 'bad');
			this.render_feedback();
		},

		render_feedback: function() {
			this.$('.mbp-apikey').attr('class', 'mbp-apikey');
			this.$('.mbp-apikey').removeAttr('disabled');
			this.$('.mbp-apikey-setting .mbp-setting-feedback').attr('class', 'mbp-setting-feedback');
			if(this.apikey_status == 'checking') {
				this.$('.mbp-apikey').attr('disabled', 'disabled');
				this.$('.mbp-apikey-setting .mbp-setting-feedback').addClass('checking');
			} else if(this.apikey_status == 'good') {
				this.$('.mbp-apikey').addClass('mbp-correct');
				this.$('.mbp-apikey-setting .mbp-setting-feedback').addClass('good');
			} else if(this.apikey_status == 'bad') {
				this.$('.mbp-apikey').addClass('mbp-error');
				this.$('.mbp-apikey-setting .mbp-setting-feedback').addClass('bad');
			} else if(this.apikey_status == 'editing') {
				this.$('.mbp-apikey-setting .mbp-setting-feedback').addClass('refresh');
			} else if(this.apikey_status == 'empty') {
				this.$('.mbp-apikey-setting .mbp-setting-feedback').addClass('help');
			}
		},
	});

})();
