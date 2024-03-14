(function($){ 
	
	$(function(){ $('.fx-search-list').comboSelect() });

	(function (factory) {
		'use strict';
		if (typeof define === 'function' && define.amd) {
			/* AMD. Register as an anonymous module.*/
			define(['jquery'], factory);
		} 
		else if (typeof exports === 'object' && typeof require === 'function') {
			/* Browserify*/
			factory(require('jquery'));
		} 
		else {
			/* Browser globals*/
			factory(jQuery);
		}
	}(function ( $, undefined ) {
		var pluginName = "comboSelect",
			dataKey = 'comboselect';
		var defaults = {
			comboClass         : 'fx-combo-select',
			comboArrowClass    : 'fx-combo-arrow',
			comboDropDownClass : 'fx-combo-dropdown',
			inputClass         : 'fx-combo-input text-input',
			disabledClass      : 'fx-option-disabled',
			hoverClass         : 'fx-option-hover',
			selectedClass      : 'fx-option-selected',
			markerClass        : 'combo-marker',
			themeClass         : '',
			maxHeight          : 200,
			extendStyle        : true,
			focusInput         : true
		};

		/**
		 * Utility functions
		 */

		var keys = {
			ESC: 27,
			TAB: 9,
			RETURN: 13,
			LEFT: 37,
			UP: 38,
			RIGHT: 39,
			DOWN: 40,
			ENTER: 13,
			SHIFT: 16
		},
		/*isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));*/
		isMobile = false;

		/**
		 * Constructor
		 * @param {[Node]} element [Select element]
		 * @param {[Object]} options [Option object]
		 */
		function Plugin ( element, options ) {
			/* Name of the plugin */
			this._name = pluginName;
			/* Reverse lookup */
			this.el = element
			/* Element */
			this.$el = $(element)
			/* If multiple select: stop */
			if(this.$el.prop('multiple')) return;
			/* Settings */
			this.settings = $.extend( {}, defaults, options, this.$el.data() );
			/* Defaults */
			this._defaults = defaults;
			/* Options */
			this.$options = this.$el.find('option, optgroup')
			/* Initialize */
			this.init();
			/* Instances */
			$.fn[ pluginName ].instances.push(this);
		}

		$.extend(Plugin.prototype, {
			init: function () {
				/* Construct the comboselect */
				this._construct();
				/* Add event bindings */
				this._events();
			},
			_construct: function(){
				var self = this
				/**
				 * Add negative TabIndex to `select`
				 * Preserves previous tabindex
				 */
				this.$el.data('plugin_'+ dataKey + '_tabindex', this.$el.prop('tabindex'))
				/* Add a tab index for desktop browsers */
				!isMobile && this.$el.prop("tabIndex", -1)
				/**
				 * Wrap the Select
				 */
				this.$container = this.$el.wrapAll('<div class="' + this.settings.comboClass + ' '+ this.settings.themeClass + '" />').parent();
				/**
				 * Check if select has a width attribute
				 */
				if(this.settings.extendStyle && this.$el.attr('style')){
					this.$container.attr('style', this.$el.attr("style"))
				}

				/**
				 * Append dropdown arrow
				 */
				this.$arrow = $('<div class="'+ this.settings.comboArrowClass+ '" />').appendTo(this.$container)
				/**
				 * Append dropdown
				 */
				this.$dropdown = $('<ul class="'+this.settings.comboDropDownClass+'" />').appendTo(this.$container)
				/**
				 * Create dropdown options
				 */
				this._build();
				/**
				 * Append Input
				 */
				this.$input = $('<input type="text"' + (isMobile? 'tabindex="-1"': '') + ' placeholder="'+ this.getPlaceholder() +'" class="'+ this.settings.inputClass + '">').appendTo(this.$container)
				/* Update input text */
				this._updateInput()
			},

			getPlaceholder: function(){
				var p = '';
				this.$options.filter(function(idx, opt){
					return opt.nodeName == 'OPTION'
				}).each(function(idx, e){
					if(e.value == '') p = e.innerHTML
				});
				return p
			},

			_build: function(){
				var self = this;
				var o = '', k = 0;
				this.$options.each(function(i, e){
					if(e.nodeName.toLowerCase() == 'optgroup'){
						return o+='<li class="fx-option-group">'+this.label+'</li>'
					}
					o+='<li class="'+(this.disabled? self.settings.disabledClass : "fx-option-item") + ' ' +(k == self.$el.prop('selectedIndex')? self.settings.selectedClass : '')+ '" data-index="'+(k)+'" data-value="'+this.value+'">'+ (this.innerHTML) + '</li>'
					k++;
				})
				this.$dropdown.html(o)
				/**
				 * Items
				 */
				this.$items = this.$dropdown.children();
			},

			_events: function(){
				/* Input: focus */
				this.$container.on('focus.input', 'input', $.proxy(this._focus, this))
				/**
				 * Input: mouseup
				 * For input select() event to function correctly
				 */
				this.$container.on('mouseup.input', 'input', function(e){
					e.preventDefault()
				})
				/* Input: blur */
				this.$container.on('blur.input', 'input', $.proxy(this._blur, this))
				/* Select: change */
				this.$el.on('change.select', $.proxy(this._change, this))
				/* Select: focus */
				this.$el.on('focus.select', $.proxy(this._focus, this))
				/* Select: blur */
				this.$el.on('blur.select', $.proxy(this._blurSelect, this))
				/* Dropdown Arrow: click */
				this.$container.on('click.arrow', '.'+this.settings.comboArrowClass , $.proxy(this._toggle, this))
				/* Dropdown: close */
				this.$container.on('comboselect:close', $.proxy(this._close, this))
				/* Dropdown: open */
				this.$container.on('comboselect:open', $.proxy(this._open, this))
				/* Dropdown: update */
				this.$container.on('comboselect:update', $.proxy(this._update, this));

				/* HTML Click */
				$('html').off('click.comboselect').on('click.comboselect', function(){
					$.each($.fn[ pluginName ].instances, function(i, plugin){
						plugin.$container.trigger('comboselect:close')
					})
				});

				/* Stop `event:click` bubbling */
				this.$container.on('click.comboselect', function(e){
					e.stopPropagation();
				})
				/* Input: keydown */
				this.$container.on('keydown', 'input', $.proxy(this._keydown, this))
				/* Input: keyup */
				this.$container.on('keyup', 'input', $.proxy(this._keyup, this))
				/* Dropdown item: click */
				this.$container.on('click.item', '.fx-option-item', $.proxy(this._select, this))
			},

			_keydown: function(event){
				switch(event.which){
					case keys.UP:
						this._move('up', event)
						break;
					case keys.DOWN:
						this._move('down', event)
						break;
					case keys.TAB:
						this._enter(event)
						break;
					case keys.RIGHT:
						this._autofill(event);
						break;
					case keys.ENTER:
						this._enter(event);
						break;
					default:
						break;
				}

			},


			_keyup: function(event){
				switch(event.which){
					case keys.ESC:
						this.$container.trigger('comboselect:close')
						break;
					case keys.ENTER:
					case keys.UP:
					case keys.DOWN:
					case keys.LEFT:
					case keys.RIGHT:
					case keys.TAB:
					case keys.SHIFT:
						break;
					default:
						this._filter(event.target.value)
						break;
				}
			},

			_enter: function(event){
				var item = this._getHovered()
				item.length && this._select(item);
				/* Check if it enter key */
				if(event && event.which == keys.ENTER){
					if(!item.length) {
						/* Check if its illegal value */
						this._blur();
						return true;
					}
					event.preventDefault();
				}
			},

			_move: function(dir){
				var items = this._getVisible(),
					current = this._getHovered(),
					index = current.prevAll('.fx-option-item').filter(':visible').length,
					total = items.length

				switch(dir){
					case 'up':
						index--;
						(index < 0) && (index = (total - 1));
						break;
					case 'down':
						index++;
						(index >= total) && (index = 0);
						break;
				}

				items
					.removeClass(this.settings.hoverClass)
					.eq(index)
					.addClass(this.settings.hoverClass)
				if(!this.opened) this.$container.trigger('comboselect:open');
				this._fixScroll()
			},

			_select: function(event){
				var item = event.currentTarget? $(event.currentTarget) : $(event);
				if(!item.length) return;
				/**
	             * 1. get Index
	             */
	            var index = item.data('index');
	            this._selectByIndex(index);
	            /*this.$container.trigger('comboselect:close')*/
	            this.$input.focus();
	            this.$container.trigger('comboselect:close');
			},

			_selectByIndex: function(index){
				/**
				 * Set selected index and trigger change
				 * @type {[type]}
				 */
				if(typeof index == 'undefined'){
					index = 0
				}

				if(this.$el.prop('selectedIndex') != index){
					this.$el.prop('selectedIndex', index).trigger('change');
				}
			},

			_autofill: function(){
				var item = this._getHovered();
				if(item.length){
					var index = item.data('index')
					this._selectByIndex(index)
				}
			},

			_filter: function(search){
				var self = this,
					items = this._getAll();
					needle = $.trim(search).toLowerCase(),
					reEscape = new RegExp('(\\' + ['/', '.', '*', '+', '?', '|', '(', ')', '[', ']', '{', '}', '\\'].join('|\\') + ')', 'g'),
					pattern = '(' + search.replace(reEscape, '\\$1') + ')';
				/**
				 * Unwrap all markers
				 */
				$('.'+self.settings.markerClass, items).contents().unwrap();
				/* Search */
				if(needle){
					/* Hide Disabled and optgroups */
					this.$items.filter('.fx-option-group, .fx-option-disabled').hide();
					items
						.hide()
						.filter(function(){
							var $this = $(this),
								text = $.trim($this.text()).toLowerCase();
							/* Found */
							if(text.toString().indexOf(needle) != -1){
								/**
								 * Wrap the selection
								 */
								$this
									.html(function(index, oldhtml){
									return oldhtml.replace(new RegExp(pattern, 'gi'), '<span class="'+self.settings.markerClass+'">$1</span>')
								})
								return true
							}
						})
						.show()
				}else{
					this.$items.show();
				}
				/* Open the comboselect */
				this.$container.trigger('comboselect:open')
			},

			_highlight: function(){
				/*
				1. Check if there is a selected item
				2. Add hover class to it
				3. If not add hover class to first item
				*/
				var visible = this._getVisible().removeClass(this.settings.hoverClass),
					$selected = visible.filter('.'+this.settings.selectedClass)
				if($selected.length){
					$selected.addClass(this.settings.hoverClass);
				}
				else{
					visible
						.removeClass(this.settings.hoverClass)
						.first()
						.addClass(this.settings.hoverClass)
				}
			},

			_updateInput: function(){
				var selected = this.$el.prop('selectedIndex')
				if(this.$el.val()){
					text = this.$el.find('option').eq(selected).text()
					this.$input.val(text)
				}
				else{
					this.$input.val('')
				}

				return this._getAll()
					.removeClass(this.settings.selectedClass)
					.filter(function(){
						return $(this).data('index') == selected
					})
					.addClass(this.settings.selectedClass)
			},

			_blurSelect: function(){
				this.$container.removeClass('fx-combo-focus');
			},

			_focus: function(event){
				/* Toggle focus class */
				this.$container.toggleClass('fx-combo-focus', !this.opened);
				/* If mobile: stop */
				if(isMobile) return;
				/* Open combo */
				if(!this.opened) this.$container.trigger('comboselect:open');
				/* Select the input */
				this.settings.focusInput && event && event.currentTarget && event.currentTarget.nodeName == 'INPUT' && event.currentTarget.select();
			},

			_blur: function(){
				/**
				 * 1. Get hovered item
				 * 2. If not check if input value == select option
				 * 3. If none
				 */
				var val = $.trim(this.$input.val().toLowerCase()),
					isNumber = !isNaN(val);
				var index = this.$options.filter(function(){
					return this.nodeName == 'OPTION'
				}).filter(function(){
					var _text = this.innerText || this.textContent
					if(isNumber){
						return parseInt($.trim(_text).toLowerCase()) == val
					}
					return $.trim(_text).toLowerCase() == val
				}).prop('index')
				/* Select by Index */
				this._selectByIndex(index)
			},

			_change: function(){
				this._updateInput();
			},

			_getAll: function(){
				return this.$items.filter('.fx-option-item')
			},

			_getVisible: function(){
				return this.$items.filter('.fx-option-item').filter(':visible')
			},

			_getHovered: function(){
				return this._getVisible().filter('.' + this.settings.hoverClass);
			},

			_open: function(){
				var self = this
				this.$container.addClass('fx-combo-open')
				this.opened = true
				/* Focus input field */
				this.settings.focusInput && setTimeout(function(){ !self.$input.is(':focus') && self.$input.focus(); });
				/* Highligh the items */
				this._highlight()
				/* Fix scroll */
				this._fixScroll()
				/* Close all others */
				$.each($.fn[ pluginName ].instances, function(i, plugin){
					if(plugin != self && plugin.opened) plugin.$container.trigger('comboselect:close')
				})
			},

			_toggle: function(){
				this.opened? this._close.call(this) : this._open.call(this)
			},

			_close: function(){
				this.$container.removeClass('fx-combo-open fx-combo-focus')
				this.$container.trigger('comboselect:closed')
				this.opened = false
				/* Show all items */
				this.$items.show();
			},

			_fixScroll: function(){
				/**
				 * If dropdown is hidden
				 */
				if(this.$dropdown.is(':hidden')) return;
				/**
				 * Else
				 */
				var item = this._getHovered();
				if(!item.length) return;
				/**
				 * Scroll
				 */
				var offsetTop,
					upperBound,
					lowerBound,
					heightDelta = item.outerHeight()
				offsetTop = item[0].offsetTop;
				upperBound = this.$dropdown.scrollTop();
				lowerBound = upperBound + this.settings.maxHeight - heightDelta;
				if (offsetTop < upperBound) {
					this.$dropdown.scrollTop(offsetTop);
				} else if (offsetTop > lowerBound) {
					this.$dropdown.scrollTop(offsetTop - this.settings.maxHeight + heightDelta);
				}
			},
			/**
			 * Update API
			 */
			_update: function(){
				this.$options = this.$el.find('option, optgroup')
				this.$dropdown.empty();
				this._build();
			},
			/**
			 * Destroy API
			 */
			dispose: function(){
				/* Remove combo arrow, input, dropdown */
				this.$arrow.remove()
				this.$input.remove()
				this.$dropdown.remove()
				/* Remove tabindex property */
				this.$el
					.removeAttr("tabindex")
				/* Check if there is a tabindex set before */
				if(!!this.$el.data('plugin_'+ dataKey + '_tabindex')){
					this.$el.prop('tabindex', this.$el.data('plugin_'+ dataKey + '_tabindex'))
				}
				/* Unwrap */
				this.$el.unwrap()
				/* Remove data */
				this.$el.removeData('plugin_'+dataKey)
				/* Remove tabindex data */
				this.$el.removeData('plugin_'+dataKey + '_tabindex')
				/* Remove change event on select */
				this.$el.off('change.select focus.select blur.select');
			}
		});

		/*A really lightweight plugin wrapper around the constructor,
		preventing against multiple instantiations*/
		$.fn[ pluginName ] = function ( options, args ) {
			this.each(function() {
				var $e = $(this),
					instance = $e.data('plugin_'+dataKey)
				if (typeof options === 'string') {
					if (instance && typeof instance[options] === 'function') {
						instance[options](args);
					}
				}
				else{
					if (instance && instance.dispose) {
						instance.dispose();
					}
					$.data( this, "plugin_" + dataKey, new Plugin( this, options ) );
				}
			});
			/* chain jQuery functions*/
			return this;
		};
		$.fn[ pluginName ].instances = [];

	}));



	$(document).ready(function(){

		/* type only number */
		$(document).on('keyup focusout', '.fx-type-amount', function () {
			this.value = this.value.replace(/[^0-9.]/g,'');
			this.value = this.value.replace(/^0+/, '');
		});

		/* widget select setting show */
		function fxWidgetSelect(fx_widget_select){
			$('.fx-widget-list-main').addClass('fx-d-none');
			$('.fx-widget-setting-main, .fx-widget-switch-list-main, #fx-widget-setting-save').removeClass('fx-d-none');
			var fx_post_id = $('#post_ID').val();

			$.ajax({
	            type : "POST",
	            url : ajaxurl,
	            dataType: "json",
	            data : {
	            	action: "fxlive_preview_widget_ajax",
	            	fx_select_widget:fx_widget_select,
	            	fx_post_id:fx_post_id
	            },
	            success: function(data) {
	                if(data != data.iframe){
			    		$('#fx-show-preview').html(data.iframe);
			    		$('.fx-widget-pre-lab').html(data.label+" Widget");
			    		$('.fx-widget-setting').html(data.settings);

			    		$(function(){ $('.fx-search-list').comboSelect() });
			    		check_width_auto();
			    		
			    		$('.fx-real-url').val(data.src);
			    		$('.fx-hide-height').val(data.height);
			    		$('.fx-hide-symbol_item').val(data.symbol_item);
			    		$('.fx-hide-select-widget').val(data.label);

			    		if(data.iframe_border != '')
			    			$('.fx-hide-iframe_border').val(data.iframe_border);
			    	}
	            },
	            error: function(errorThrown){
				    alert(errorThrown);
				} 
	        });
		}


		/* select widget then next click */
		$('#fx-next').click(function(){
			var fx_widget_select = $('#fx-widget-select-list').find(":selected").val();
			$('#fx-widget-switch-list option[value="'+fx_widget_select+'"]').prop('selected', true);
			fxWidgetSelect(fx_widget_select);
		});
		/* switch widget */
		$('#fx-switch').click(function(){
			var fx_widget_select = $('#fx-widget-switch-list').find(":selected").val();
			$('#fx-widget-select-list option[value="'+fx_widget_select+'"]').prop('selected', true);
			fxWidgetSelect(fx_widget_select);
		});


		/* widget setting back click */
		$('#fx-back').click(function(){
			$('.fx-widget-setting-main, .fx-widget-switch-list-main, #fx-widget-setting-save').addClass('fx-d-none');
			$('.fx-widget-list-main').removeClass('fx-d-none');
			$('#fx-show-preview').html('');
			$('.fx-widget-pre-lab').html('');
			$('.fx-widget-setting').html('');
		});



		/* title keyup */
		$('#fx-title-widget').keyup(function() {
	        $('#titlewrap #title').val($('#fx-title-widget').val());
	    });


		/* copy shortcode */
		$('#fx-copy-code').click(function(){
			var copyText = document.getElementById("fx-shortcode");
			copyText.select();
			copyText.setSelectionRange(0, 99999);
			document.execCommand("copy");

			$('#fx-copy-code .fx-tooltiptext').text('Code copied to clipboard');
		});
		$("#fx-copy-code").mouseout(function(){
			$('#fx-copy-code .fx-tooltiptext').text('Copy to clipboard');
		});


		/* widget symbol add item */
	    $(document).on('click', '#fx-symbol-item-add', function () {
	      var symbol_id = $("#fx-get-symbol-list").val();
	      var symbol_name = $("#fx-get-symbol-list option:selected").text();

	      var input_val = symbol_id;
	      if($(".fx-hide-symbol_item").val() == "symbol")
	      	input_val = symbol_name;

	      var markup = '<div class="fx-row fx-symbol-add-item-main fx-m-b-5"><div class="fx-col-lg-11 fx-col-10"><div class="fx-symbol-id" data-symbol-id="'+symbol_id+'">'+symbol_name+'</div><input type="hidden" name="fx_widget[fx-select-list][]" value="'+input_val+'"></div><div class="fx-col-lg-1 fx-col-2"><div class="fx-square-sign fx-symbole-remove-item">-</div></div></div>';
	      $(".fx-symbol-multiple-item-add").append(markup);
	    });

	    /* widget symbol remove item */
	    $(document).on('click', '.fx-symbole-remove-item', function () {
	      $(this).parents('.fx-symbol-add-item-main').remove();
	    });



		/* autosize disable check width field show */
	    $(document).on('change', '#fx-autosize-width', function () {
	      	check_width_auto();
	    });


	    /* target select no click disabel target link */
	    $(document).on('change', '#fx-target-click', function () {
	    	console.log($('#fx-target-click').val());
	      if($('#fx-target-click').val() == 'disable'){
	        $("#fx-target-link").attr('disabled','disabled');
	      }
	      else{
	        $("#fx-target-link").removeAttr('disabled');
	      }
	    });


	    $(document).on('change', '#fx-noslide', function () {
	      if($("#fx-noslide").is(':checked')) {
	        $("#fx-speed-second").attr('disabled','disabled');
	      }
	      else{
	        $("#fx-speed-second").removeAttr('disabled');
	      }
	    });


	    /* width or auto checkbox check */
		function check_width_auto(){
			if($("#fx-autosize-width").is(':checked')) {
			  	$("#fx-width").attr('disabled','disabled');
			}
			else{
			  	$("#fx-width").removeAttr('disabled');
			}
		}


		/* widget setting apply */
	    $('#fx-widget-setting-apply').click(function(){
	      var url = $('.fx-real-url').val();
	      var height = $('.fx-hide-height').val();
	      var iframe_style = '';
	      var symbol_name_check = $('.fx-hide-symbol_item').val();

	      if($('.fx-symbol-multiple-item-add').length){
	        var temp_id = '';
	        var temp_symbol = '';
	        $('.fx-symbol-multiple-item-add .fx-symbol-add-item-main .fx-symbol-id').each(function(index, item) 
	        {
	          temp_id += $(this).attr('data-symbol-id')+',';
	          temp_symbol += $(this).html()+',';
	        });

	        if(symbol_name_check == 'symbol')
	          url += 'symbol=' + temp_symbol.replace(/^,[ ]?|,$/g,'');
	        else
	          url += 'id=' + temp_id.replace(/^,[ ]?|,$/g,'');
	      }
	      else if($('#fx-get-symbol-list').length){
	        if(symbol_name_check == 'symbol')
	          url += 'symbol=' + $('#fx-get-symbol-list option:selected').text();
	        else
	          url += 'id=' + $('#fx-get-symbol-list').val();
	      }

	      var width = '100%';
	      if($('#fx-width').length){
	        if($("#fx-autosize-width").is(':checked') == false) {
	          width = $('#fx-width').val();
	        }
	      }

	      if($('#fx-height').length){
	        height = $('#fx-height').val();
	      }

	      /* widget border hide or show */
	      if($('#fx-border-hide').length){
	        var border = 'show';
	        var temp_iframe_border = 'style="border: 1px solid #eee;"';
	        if($("#fx-border-hide").is(':checked')) {
	          border = 'hide';
	          temp_iframe_border = 'style="border: 0;"';
	        }

	        var iframe_border = $('.fx-hide-iframe_border').val();
	        if(iframe_border != '' && iframe_border == 'true')
	        {
	          iframe_style += temp_iframe_border;
	        }
	        else{
	          url += '&border='+border;
	        }
	      }

	      /* widget slider speed or stop */
	      if($('#fx-speed-second').length){
	        if($("#fx-noslide").is(':checked')) {
	          url += '&noslide';
	        }
	        else {
	          url += '&speed=' + $('#fx-speed-second').val();
	        }
	      }

	      /* widget pair bold or normal font checked */
	      if($('#fx-pair-weight').length){
	        if($("#fx-pair-weight").is(':checked')) {
	          url += '&pair_weight=normal';
	        }
	      }

	      /* paid users fcs link hide or show */
	      var fx_is_hide_logo = false;
	      if($('#fx-fcs-link').length){
	        if($("#fx-fcs-link").is(':checked')) {
	      	  fx_is_hide_logo = true;
	          url += '&fcs_link=hide';
	        }
	      }

	      /* widget pair bold or normal font checked */
	      if($('#fx-target-link').length && 
	        $('#fx-target-link').val().trim() && 
	        $('#fx-target-click').val() != 'disable')
	      {
	        url += '&target_link='+encodeURIComponent($("#fx-target-link").val().trim());
	      }

	      /* click target event */
	      if($('#fx-target-click').length){
	        url += '&click_target='+ $('#fx-target-click').val();
	      }

	      /* widget theme select */
	      if($('#fx-get-theme-list').length){
	        url += '&theme='+ $('#fx-get-theme-list').val();
	      }

	      /* widget flags style select */
	      if($('#fx-get-flags-list').length){
	        url += '&flags='+ $('#fx-get-flags-list').val();
	      }

	      /* widget value align select */
	      if($('#fx-get-value-align-list').length){
	        url += '&value_alignment='+ $('#fx-get-value-align-list').val();
	      }

	      /* user select coloumns */
	      if($('#fx-columns').length){
	        var temp_column = '';
	        $("input[class='fx-column-select']:checked").each(function (index, obj) {
	          temp_column += $(this).val()+',';
	        });

	        url += '&column='+temp_column.replace(/,\s*$/, "");
	      }

	      /* widget key set paid users */
	      var fx_is_widget_key = false;
	      if($('#fx-widget-key').length && 
	        $('#fx-widget-key').val().trim())
	      {
	      	fx_is_widget_key = true;
	        url += '&widget_key='+$("#fx-widget-key").val().trim();
	      }

	      var iframe_text = '<iframe src="'+url+'" width="'+width+'" height="'+height+'" '+iframe_style+'></iframe>';
	      var iframe_footer = '';
	      
	      if(fx_is_hide_logo && fx_is_widget_key)
	      {
	      	$('#fx-show-preview').html(iframe_text);
	      	$('.fx-hide-iframe').val(iframe_text);
	      }
	      else{
	      	$('#fx-show-preview').html(iframe_text+iframe_footer);
	      	$('.fx-hide-iframe').val(iframe_text+iframe_footer);
	      }

	      $("html, body").animate({ scrollTop: 0 }, "slow");
	    });



		/* custom publish button */
		$('#fx-widget-setting-save').click(function(){
			$('#fx-widget-setting-apply').trigger("click");
			setTimeout(function() { 
				$('#publish').trigger("click");
		    }, 100);
		});

	});

})(jQuery);
