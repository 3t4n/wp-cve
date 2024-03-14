//
(function($){
	var recordsets = {},
		links = {},
		url = document.location.href,
		current_form = null;

	function log(err)
	{
		if('cf7_debug' in window && 'console' in window && err != '') console.log(err);
	}

    function delay(callback, ms)
    {
        var timer = 0;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            }, ms || 0);
        };
    }

    function is_template(v)
    {
        return /^\{\s*template\.[^\}]+\}\s*$/i.test(v);
    }

    function template_replacement(t, record)
    {
        // template id
        var _match = /^\{\s*template\.([^\}]+)\}\s*$/i.exec(t),
            _tpl,
            _html,
            _valid = false,
            tmp;

        if(_match == null) throw 'Invalid template identifier: '+t;
        _tpl = document.getElementById(_match[1]);
        if(_tpl == null) throw 'Non-existing template: '+_match[1];
        _html = _tpl.innerHTML;

        while(_match = /\{attribute.([^\}]+)\}/.exec(_html))
        {
            try
            {
                tmp = record[_match[1]] || eval(_match[1]);
            }catch(err){}

            if(tmp)
            {
                _valid = true;
                _html = _html.replace(_match[0], tmp);
            }
            else
            {
                _html = _html.replace(_match[0], '');
            }
        }

        if(_valid) return _html;
        throw ''; // There are no attributes in the record that satisfy the structure of the template.
    }

    function populate_recordset(recordset)
	{
		function cf7_trigger_event(recordset)
		{
			if(typeof CustomEvent != 'undefine')
			{
				try
				{
					var evt = new CustomEvent(
						'cf7-recordset',
						{
							detail:
							{
								'recordset-id': recordset,
								'recordset-data': recordsets[recordset]['data']
							}
						}
					);
					document.dispatchEvent(evt);
				}
				catch(err){log(err);}
			}
			$(document).trigger('cf7-recordset', [recordset, recordsets[recordset]['data']]);
		};

		var settings = recordsets[recordset]['settings'],
			data = {'cf7_recordset':recordset, 'cf7' : settings.cf7, 'cf7_ds_post' : recordsets[recordset]['post']},
			v, f;

		if('connection' in recordsets[recordset]) recordsets[recordset].connection.abort();

		if('variables' in settings && settings.variables.length)
		{
			data['variables'] = {};
			for(var i in settings.variables)
			{
				v = get_variable(settings.variables[i]);
				if(typeof v != 'undefined') data['variables'][settings.variables[i]] = v;
			}
		}

		if('fields' in settings && settings.fields.length)
		{
			data['fields'] = {};
			for(var i in settings.fields)
			{
				f = get_field(settings.fields[i]);
				if(f) data['fields'][settings.fields[i]] = get_value(f);
			}
		}

		if(
			'type' in settings &&
			(
				settings['type'] == 'url-parameters' ||
				settings['type'] == 'client'
			)
		) {
			if(
				settings['type'] == 'url-parameters'
			) {
				if( 'URLSearchParams' in window ) { // URL parameters data-source
					var urlParams = new URLSearchParams( document.location.search),
						parameters = {},
						flag = false;
					urlParams.forEach(function(value, key){ flag = true; parameters[key] = value;});
					if(flag) {
						recordsets[recordset]['data'] = [$.extend({},parameters)];
					}
				}
			} else if( 'function' in settings ) { // Javascript function data-source
				var function_name = String( settings['function'] ).trim(),
					function_arguments = [];

				if ( function_name.length && function_name in window && typeof window[ function_name] == 'function' ) {
					if ( 'parameters' in settings ) {
						let parameters = String( settings['parameters'] ).trim(),
							_match;
						if ( parameters.length ) {
							// Get parameter values
							while ( _match = /\{\s*([^\}]+)\s*\}/.exec( parameters ) ){
								if ( /^field\./i.test( _match[1] ) ) {
									_match[1] = _match[1].substr(6);
									if( 'fields' in data && _match[1] in data['fields'] ) {
										function_arguments.push( data['fields'][ _match[1] ] );
									} else {
										function_arguments.push(null);
									}
								} else if ( /^var\./i.test( _match[1] ) ) {
									_match[1] = _match[1].substr(4);
									if( 'variables' in data && _match[1] in data['variables'] ) {
										function_arguments.push( data['variables'][ _match[1] ] );
									} else {
										function_arguments.push(null);
									}
								} else if( /^value\./i.test( _match[1] ) ) {
									_match[1] = _match[1].substr(6);
									if ( isNaN( parseFloat( _match[1] ) ) ) {
										function_arguments.push( _match[1] );
									} else {
										function_arguments.push( parseFloat( _match[1] ) );
									}
								}
								parameters = parameters.replace(_match[0], '');
							}
						}
					}

					// Eval function
					recordsets[recordset]['data'] = window[ function_name ].apply(null, function_arguments);
					flag = true;
				}
			}

			if ( flag ) {
				if('callback' in settings && settings['callback'] in window && typeof window[settings['callback']] == 'function') recordsets[recordset]['data'] = window[settings['callback']]( recordsets[recordset]['data'] );

				cf7_trigger_event(recordset);
			}

		} else { // Server-side data-sources (DB, CSV, JSON, SERVER-SIDE, ACF)
			recordsets[recordset]['connection'] = $.ajax(
				{
					dataType : 'json',
					url : url,
					cache : false,
					data : data,
					success : (function( recordset ){
						return function( response ){
							if(!('error' in response))
							{
								var settings = recordsets[recordset]['settings'];
								recordsets[recordset]['data'] = response.data;

								if('callback' in settings && settings['callback'] in window && typeof window[settings['callback']] == 'function') recordsets[recordset]['data'] = window[settings['callback']]( recordsets[recordset]['data'] );

								cf7_trigger_event(recordset);
							}
							else log('Populate Recordset: '+response['error']);
						};
					})(recordset)
				}
			);
		}
	}

	function populate_field(recordset, field_name)
	{
		var field = get_field(field_name);
		if(field)
		{
			var settings = links[recordset][field_name],
				options  = ( 'options' in links[recordset][field_name] ) ? links[recordset][field_name]['options'] : false,
				data 	 = recordsets[recordset]['data'],
				type 	 = get_type(field);

			// Get valid data
			var key, in_array = {},
                records=[],
				record,
				t 	= ('text' in settings) ? String( settings.text  ).trim() : '',
				v 	= ('value' in settings) ? String( settings.value  ).trim() : '',
				c 	= ('condition' in settings) ? String( settings.condition  ).trim() : '',
                l   = ('limit' in settings &&
                        !isNaN(settings.limit) &&
                        parseInt(settings.limit)
                      ) ? parseInt(settings.limit) : Number.MAX_SAFE_INTEGER,
                tmp;

			if( options ) {
				records = records.concat( options );
			}

			if( c != '' ) c = replace_variables(c);

			if (
				typeof data != 'undefined' &&
				data != null
			) {
				if( ! Array.isArray( data ) ) {
					data = [data];
				}
				for( var i in data )
				{
					record = data[i];
					try{
						if((c == '' || eval(c.replace('{record.index}', i))) && i < l)
						{
							var item = {}, from_tmp;
							if(t != '')
							{
								if(is_template(t))
								{
									try
									{
										from_tmp = template_replacement(t, record, settings);
										item['text'] = from_tmp;
									}catch(tmp_err){log(tmp_err);}
								}
								else
								{
									try
									{
										tmp = record[t] || eval(t);
										if(tmp) item['text'] = tmp;
									}catch(err){}
								}
							}
							if(v != '')
							{
								if(is_template(v))
								{
									try
									{
										from_tmp = template_replacement(v, record, settings);
										item['value'] = from_tmp;
									}catch(tmp_err){log(tmp_err);}
								}
								else
								{
									try
									{
										tmp = record[v] || eval(v);
										if(tmp) item['value'] = tmp;
									}catch(err){}
								}
							}
							if(!$.isEmptyObject(item))
							{
								key = JSON.stringify(item);
								item['record'] = record;
								if(!(key in in_array)) records.push( item );
								in_array[key] = 1;
							}
						}
					}catch(err){log(err);}
				}
			}

			// Remove duplicate records.
			recordsJSON = records.map( JSON.stringify );
			recordsSet = new Set( recordsJSON );
			records = Array.from( recordsSet ).map( JSON.parse );

			switch(type)
			{
				case 'plain'    : populate_plain(field, records, settings); break;
				case 'simple'   : populate_simple(field, records, settings); break;
				case 'checkbox' : populate_checkbox(field, records, settings); break;
				case 'radio'    : populate_radio(field, records, settings); break;
				case 'list'     : populate_list(field, records, settings); break;
			}

			field = get_field(field_name);
			if(field) field.trigger('change', ['cf7-ds-fill']);
		}
	}

	function replace_variables(str)
	{
		var field_regexp = new RegExp('\{field\.([^\}]+)\}','i'),
			var_regexp   = new RegExp('\{var\.([^\}]+)\}','i'),
			match, field, value;

		while(match = field_regexp.exec(str))
		{
			value = '';
			field = get_field(match[1]);
			if(field) value = get_value(field);
			str = str.replace( match[0], value );
		}

		while(match = var_regexp.exec(str))
		{
			value = get_variable(match[1]);
			value = value || '';
			str = str.replace( match[0], value );
		}

		return str;
	}

	function replace_properties( str,  data ) {
		let _match, _regexp = /\{([^\}]*)\}/;
		while ( _match = _regexp.exec( str ) ) {
			str = str.replace( _match[0], '"' + ( new String( ( 'record' in data && data['record'][ _match[1] ] ) || '' ) ).replace( /\"/g, '\\"' ) + '"' );
		}

		return str;
	}

	function get_field(field_name)
	{
		var _p = ( current_form != null ) ? current_form : document,
			field = $('[name="'+field_name+'"]', _p);
		if(field.length == 0) field = $('[name="'+field_name+'[]"]', _p); // Checkbox and radio buttons.
		if(field.length == 0) field = $('.'+field_name, _p); // Theh field's container.
		if(field.length == 0) field = $('[id="'+field_name+'"]', _p);
		if(field.length == 0) field = $('[data-name="'+field_name+'"]');
		return (field.length) ? field : false;
	}

	function get_type(field)
	{
		var tagName = field[0].tagName.toUpperCase(),
			type = 'plain';
		if(tagName == 'INPUT' || tagName == 'SPAN')
		{
			if(tagName == 'INPUT') type = 'simple';
			var t = field.attr('type');
            if(typeof t != 'undefined')
            {
                t = t.toLowerCase();
                if(t == 'checkbox' || t == 'radio')
                {
                    type = t;
                }
            }
            else if($(field).find('.wpcf7-checkbox').length) type = 'checkbox';
            else if($(field).find('.wpcf7-radio').length) type = 'radio';
		}
		else if(tagName == 'SELECT') type = 'list';
		return type;
	}

	function get_value(field)
	{
		var type = get_type(field);
		switch(type)
		{
			case 'simple': return field.val();
			case 'radio': return field.filter(':checked').val();
			case 'checkbox':
				var values = [];
				field.filter(':checked').each(function(){values.push(this.value);});
				return values;
			case 'list':
				var values = [];
				field.find(':selected').each(function(){values.push(this.value);});
				return values;
		}
	}

	function get_options(field_name)
	{
		var field = get_field(field_name);
		if ( field ) {
			var type = get_type(field), options = [];
			switch(type)
			{
				case 'radio':
				case 'checkbox':
					field.each( function() {
						options.push( { 'text' : this.value, 'value' : this.value } );
					} );
					break;
				case 'list':
					field.find( 'option' ).each( function() {
						options.push( { 'text' : $( this ).text(), 'value' : $( this ).val() } );
					} );
					break;
			}
			return options.length ? options : false;
		}
	}

	function get_variable(variable)
	{
		if(variable in window) return window[variable];
		return undefined;
	}

	function populate_plain(field, data, settings)
	{
		var value = '';
        for(var i in data)
        {
            if(data.length && 'value' in data[i])
            {
                value += ''+(data[i].value || '');
            }
        }
		field.html(value);
	}

	function populate_simple(field, data, settings)
	{
		var value = '';

		if(data.length && 'value' in data[0])
		{
			var attr_str   	= replace_properties(settings['other-attributes'] || '', data[0]),
				input_tmp  	= '<input '+attr_str+'>',
				attributes 	= $(input_tmp)[0].attributes;

			value = data[0].value || '';
		}
		field.val(value);
		for(var i in attributes) {
			field.attr(attributes[i]['name'], attributes[i]['value']);
		}
	}

	function populate_radio(field, data, settings)
	{
		var checked_value = (field.attr('cf7-ds-filled') == undefined && settings['default'] !== '') ? settings['default'] : get_value(field),
			name = field.attr('name') || field.attr('data-name') || String(field.attr('class').replace(/wpcf7\-form\-control\-wrap/i, '')).trim(),
			parent = (field.is('span') || field.hasClass('wpcf7-form-control-wrap')) ? field.find('.wpcf7-radio') : field.closest('.wpcf7-radio'),
            label  = field.parent('label').length,
			radio = '', value, position, checked,
			h = data.length;

		if(h)
		{
			for(var i = 0; i < h; i++)
			{
				position  = (i == 0) ? 'first' : '';
                position += (i == h-1) ? (position.length ? ' ' : '')+'last' : '';
				value = (new  String(data[i]['value'] || '')).replace(/\"/g, '\\"');
				checked = (value == checked_value) ? 'CHECKED' : '';
				radio += '<span class="wpcf7-list-item '+position+'">'+
                    (label ? '<label>' : '')+
					'<input type="radio" name="'+name+'" value="'+value+'" '+checked+' '+replace_properties(settings['other-attributes'] || '', data[i])+'>'+
					'<span class="wpcf7-list-item-label">'+(data[i]['text'] || '')+'</span>'+
                    (label ? '</label>' : '')+
                    '</span>';
			}
		}
		else radio += '<span class="wpcf7-list-item first"><input type="radio" name="'+name+'" disabled></span>';
		parent.html(radio);
        parent.add(parent.find('input')).attr('cf7-ds-filled', 1);
	}

	function populate_checkbox(field, data, settings)
	{
		var checked_values = (field.attr('cf7-ds-filled') == undefined && settings['default'] !== '') ? settings['default'] : get_value(field),
			name   = field.attr('name') || ( field.attr('data-name') || String(field.attr('class').replace(/wpcf7\-form\-control\-wrap/i, '')).trim() )+'[]',
			parent = (field.is('span') || field.hasClass('wpcf7-form-control-wrap')) ? field.find('.wpcf7-checkbox') : field.closest('.wpcf7-checkbox'),
            label  = field.parent('label').length,
			checkbox = '', value, position, checked,
			h = data.length;

		if(h)
		{
			for(var i = 0; i < h; i++)
			{
				position  = (i == 0) ? 'first' : '';
                position += (i == h-1) ? (position.length ? ' ' : '')+'last' : '';
				value = (new  String(data[i]['value'] || '')).replace(/\"/g, '\\"');
				checked = (checked_values.indexOf(value) != -1) ? 'CHECKED' : '';
				checkbox += '<span class="wpcf7-list-item '+position+'">'+
                    (label ? '<label>' : '')+
					'<input type="checkbox" name="'+name+'" value="'+value+'" '+checked+' '+replace_properties(settings['other-attributes'] || '', data[i])+'>'+
					'<span class="wpcf7-list-item-label">'+(data[i]['text'] || '')+'</span>'+
                    (label ? '</label>' : '')+
                    '</span>';
			}
		}
		else checkbox += '<span class="wpcf7-list-item first"><input type="checkbox" name="'+name+'" disabled></span>';
		parent.html(checkbox);
        parent.add(parent.find('input')).attr('cf7-ds-filled', 1)
	}

	function populate_list(field, data, settings)
	{
		var selected_values = (field.attr('cf7-ds-filled') == undefined && settings['default'] !== '') ? settings['default'] : get_value(field),
        options = '', selected, value;

		for(var i in data)
		{
			value = (new String(data[i]['value'] || '')).replace(/\"/g, '\\"');
			selected = (selected_values.indexOf(value) != -1) ? 'SELECTED' : '';
			options += '<option value="'+value+'" '+selected+' '+replace_properties(settings['other-attributes'] || '', data[i])+'>'+(data[i]['text'] || '')  +'</option>';
		}
		field.attr('cf7-ds-filled', 1).html(options);
	}

	function fields_to_recordset(recordset, settings)
	{
		if('fields' in settings)
		{
			for(var i in settings.fields)
			{
				$(document).on(
					'keyup change',
					'[name="'+settings.fields[i]+'"]',
					(function(recordset) {
						var timer = 0;
						return function(evt){
							try {
								current_form = this.form;
							} catch (err) {
								current_form = null;
							}

							clearTimeout(timer);
							timer = setTimeout( function(){
								let tg = $(evt.target);
								if( tg.length ) {
									if ( tg.data('cf7-ds-previous') == undefined || tg.data('cf7-ds-previous') != tg.val() )
									populate_recordset(recordset);
									if(evt.type == 'change') tg.removeData('cf7-ds-previous');
									else tg.data('cf7-ds-previous', tg.val());
								}
							}, evt.type == 'keyup' ? 1000 : 0);
						};
					})(recordset)
				);
			}
		}
		populate_recordset(recordset);
	}

	function recordset_to_field(recordset, field)
	{
		$(document).on('cf7-recordset', (function(recordset, field){
			return function(evt, name, data){
				if(recordset == name)
				{
					populate_field(recordset, field);
				}
			};
		})(recordset, field));
	}

	function field_to_field(field, fields_list, recordset)
	{
        var _linked = {};
		for(var i in fields_list)
		{
            if(fields_list[i] in _linked) continue;
            _linked[fields_list[i]] = 1;

			$(document).on(
				'change',
				'[name="'+fields_list[i]+'"],[name="'+fields_list[i]+'[]"]',
				(function(recordset, field){
						return function(evt){
							try {
								current_form = this.form;
							} catch (err) {
								current_form = null;
							}
							populate_field(recordset, field);
						};
                })(recordset, field)
            ).on(
                'keyup',
                '[name="'+fields_list[i]+'"],[name="'+fields_list[i]+'[]"]',
                delay(
                    (function(recordset, field){
                        return function(evt){
							try {
								current_form = this.form;
							} catch (err) {
								current_form = null;
							}
							populate_field(recordset, field);
                        };
                    })(recordset, field),500)
            );
		}
	}

	function unescape_settings(obj)
	{
		var d = $('<div></div>');
		if(typeof obj == 'object') for(var i in obj) obj[i] = unescape_settings(obj[i]);
		else obj = d.html(obj).text();
		return obj;
	}

	function process_recordsets_and_links()
	{
		var e;
		if(typeof cf7_datasource_links != 'undefined')
			while(cf7_datasource_links.length)
			{
				e = cf7_datasource_links.shift();
				try{
					cf7_datasource_register_link(e['settings'], e['post']);
				} catch (err) {console.log(err); }
			}

		if(typeof cf7_datasource_recordsets != 'undefined')
			while(cf7_datasource_recordsets.length)
			{
				e = cf7_datasource_recordsets.shift();
				try{
					cf7_datasource_register_recordset(e['id'], e['settings'], e['post']);
				} catch (err) {console.log(err); }
			}
	}

	window['cf7_datasource_register_recordset'] = function(id, settings, post){
		settings = unescape_settings(settings);
		recordsets[id] = {settings:settings, 'post': post};
		fields_to_recordset(id, settings);
	};

	window['cf7_datasource_register_link'] = function(settings, post){
		settings = unescape_settings(settings);
		if(
			'recordset' in settings &&
			'field' in settings
		)
		{
			var rs = settings['recordset'],
				f  = settings['field'],
				k  = ( 'keep-options' in settings && settings['keep-options'] * 1 ) ? 1 : 0;

			if(!(rs in links)) links[rs] = {};
			links[rs][f] = settings;
            links[rs]['post'] = post;
			if ( k ) {
				links[rs][f]['options'] = get_options( f );
			}

			recordset_to_field(rs, f);
			field_to_field(f, settings['fields'], rs);
		}
		else log('Register Link: recordset and field are required');
	};

    window['cf7_datasource_get_recordset_data'] = function(id){
        return (id in recordsets) ? recordsets[id]['data'] : null;
    };

    window['cf7_datasource_set_recordset_data'] = function(id, data){
        if(id in recordsets)
        {
            recordsets[id]['data'] = data;
            $(document).trigger('cf7-recordset', [id, recordsets[id]['data']]);
            return true;
        }
        return false;
    };

	window['cf7_datasource_recordset_reload'] = function(id){
        if(id in recordsets)
        {
            populate_recordset(id);
            return true;
        }
        return false;
    };

	window['cf7_datasource_field_reload'] = function(field_name, recordset_id){
		recordset_id = recordset_id || false;

		if( ! recordset_id ){
			for( let i in links ) {
				if( field_name in links[i] ) {
					recordset_id = i;
					break;
				}
			}
		}

        if(
			recordset_id &&
			recordset_id in links &&
			field_name in links[recordset_id]
		)
        {
            populate_field(recordset_id, field_name)
            return true;
        }

        return false;
    };

	// Main
	function check_for_wpcf7_cached() {
		if('wpcf7' in window && 'cached' && wpcf7 && wpcf7.cached) {
			$(document).one('reset', function(){
				setTimeout(
					function(){
						process_recordsets_and_links();
					}, 50
				);
			});
			return;
		}
		process_recordsets_and_links();
	};

	$(function(){ check_for_wpcf7_cached(); });
	$(window).on('load', function(){ check_for_wpcf7_cached(); });
})(jQuery)