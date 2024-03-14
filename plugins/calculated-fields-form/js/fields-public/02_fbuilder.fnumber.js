	$.fbuilder.controls[ 'fnumber' ] = function(){};
	$.extend(
		$.fbuilder.controls[ 'fnumber' ].prototype,
		$.fbuilder.controls[ 'ffields' ].prototype,
		{
			title:"Number",
			ftype:"fnumber",
			predefined:"",
			predefinedClick:false,
			required:false,
			readonly:false,
            numberpad:false,
			spinner:false,
			size:"small",
			prefix:"",
			postfix:"",
			thousandSeparator:"",
			decimalSymbol:".",
			min:"",
			max:"",
			step:1,
			formatDynamically:false,
			dformat:"digits",
			set_step:function(v, rmv)
				{
					var e = $('[id="'+this.name+'"]');
					if(rmv) e.removeAttr('step');
					else {
						var vb = e.val();
						e.removeAttr('value');
						if(!isNaN(v*1)) e.attr('step', Math.abs(v*1 ? v : 1));
						e.val(vb);
					}
                    if(!e.hasClass('cpefb_error')) e.removeClass('required');
					e.valid();
                    if(this.required) e.addClass('required');
				},
			set_min:function(v, rmv)
				{
					var e = $('[id="'+this.name+'"]');
					if(rmv) e.removeAttr('min');
					else e.attr('min', v);
					if(!e.hasClass('cpefb_error')) e.removeClass('required');
					e.valid();
                    if(this.required) e.addClass('required');
				},
			set_max:function(v, rmv)
				{
					var e = $('[id="'+this.name+'"]');
					if(rmv) e.removeAttr('max');
					else e.attr('max', v);
					if(!e.hasClass('cpefb_error')) e.removeClass('required');
					e.valid();
                    if(this.required) e.addClass('required');
				},
			getFormattedValue:function(value)
				{
					if(value == '') return value;
					var ts = this.thousandSeparator,
						ds = ((ds=String(this.decimalSymbol).trim()) !== '') ? ds : '.',
						v = $.fbuilder.parseVal(value, ts, ds),
						s = '',
						counter = 0,
						str = '',
						parts = [],
						step  = $('[id="'+this.name+'"]').attr('step'),
						prefix  = this.dformat == 'number' ? this.prefix : '',
						postfix = this.dformat == 'number' ? this.postfix : '';

					if(!isNaN(v))
					{
						if(v < 0) s = '-';
						v = ABS(v);
						parts = v.toString().split(".");

						for(var i = parts[0].length-1; i >= 0; i--){
							counter++;
							str = parts[0][i]+str;
							if(counter%3 == 0 && i != 0) str = ts+str;

						}
						parts[0]  = str;
						if(
							typeof parts[1] != 'undefined' &&
							parts[1]*1 &&
							typeof step != 'undefined' &&
							! isNaN(step*1)
						){
							var l = (new String(step)).split('.');
							if(l.length == 2){
								l = Math.max(l.length-(new String(parts[1])).length, 0);
								for(var i = 0; i < l; i++) parts[1] += '0';
							}
						}
						return prefix+s+parts.join(ds)+((this.dformat == 'percent') ? '%':'')+postfix;
					}
					else
					{
						return value;
					}
				},
			init:function()
				{
					if(!/^\s*$/.test(this.min)) this._setHndl('min');
					if(!/^\s*$/.test(this.max)) this._setHndl('max');
					if(!/^\s*$/.test(this.step)) this._setHndl('step');
					else this.step = 1;
				},
			show:function()
				{
					var _type = (
							this.dformat == 'digits' ||
							(
								this.dformat != 'percent' &&
								this.prefix == '' &&
								this.postfix == '' &&
								this.thousandSeparator == '' &&
								/^\s*(\.\s*)?$/.test(this.decimalSymbol)
							)
						) ? 'number' : 'text';

                    if(this.dformat == 'digits') $(document).on('keydown', '#'+this.name, function(evt){if(/^[\-,\+,e,\.,\,]$/i.test(evt.key)){evt.preventDefault(); return false;}});

                    this.predefined = this._getAttr('predefined', true);
					return '<div class="fields '+cff_esc_attr(this.csslayout)+' '+(this.spinner ? 'cff-spinner ' : '')+this.name+' cff-number-field" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield">'+
					(this.spinner ? '<div class="cff-spinner-components-container '+this.size+'"><button type="button" class="cff-spinner-down">-</button>' : '')+
					'<input '+((this.numberpad) ? 'inputmode="decimal"' : '')+' aria-label="'+cff_esc_attr(this.title)+'" id="'+this.name+'" name="'+this.name+'" '+((!/^\s*$/.test(this.min)) ? 'min="'+cff_esc_attr($.fbuilder.parseVal(this._getAttr('min'), this.thousandSeparator, this.decimalSymbol))+'" ' : '')+((!/^\s*$/.test(this.max)) ? ' max="'+cff_esc_attr($.fbuilder.parseVal(this._getAttr('max'), this.thousandSeparator, this.decimalSymbol))+'" ' : '')+((!/^\s*$/.test(this.step)) ? ' step="'+cff_esc_attr(this._getAttr('step', true))+'" ' : '')+' class="field '+this.dformat+((this.dformat == 'percent') ? ' number' : '')+' '+(this.spinner ? 'large' : this.size)+((this.required)?" required":"")+'" type="'+_type+'" value="'+cff_esc_attr((this.formatDynamically) ? this.getFormattedValue(this.predefined) : this.predefined)+'" '+((this.readonly)?'readonly':'')+' />'+
					(this.spinner ? '<button type="button" class="cff-spinner-up">+</button></div>' : '')+
					'<span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';
				},
			after_show:function()
				{
					var me = this;

					if((me.formatDynamically && me.dformat != 'digits') ||  me.dformat == 'percent')
					{
						$(document).on('change', '[name="'+me.name+'"]', function(){
							this.value = me.getFormattedValue(this.value);
						});
					}
					$('#'+me.name).rules('add', {'step':false});
				},
			val:function(raw,no_quotes)
				{
					raw = raw || false;
                    no_quotes = no_quotes || false;
					var e = $('[id="'+this.name+'"]:not(.ignore)');
					if(e.length)
					{
						var v = String(e.val()).trim();
						if(raw) return ($.fbuilder.isNumeric(v) && this.thousandSeparator != '.') ? v : $.fbuilder.parseValStr(v, raw, no_quotes);

						v = v.replace(new RegExp($.fbuilder[ 'escapeSymbol' ](this.prefix), 'g'), '')
						     .replace(new RegExp($.fbuilder[ 'escapeSymbol' ](this.postfix), 'g'), '');

						v = $.fbuilder.parseVal(v, this.thousandSeparator, this.decimalSymbol, no_quotes);
						return (this.dformat == 'percent') ? v/100 : v;
					}
					return 0;
				}
		}
	);