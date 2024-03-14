/*
* managing_fields.js v0.1
* By: CALCULATED FIELD PROGRAMMERS
* The script allows managing fields
* Copyright 2015 CODEPEOPLE
* You may use this project under MIT or GPL licenses.
*/

;(function(root){
	var lib = {
		formsDependency:{}
	},
	elements_equations = {};
	lib.cf_processing_version = '0.1';

	/*** PRIVATE FUNCTIONS ***/

	function _getForm(_form)
	{
		if(typeof _form == 'undefined' || _form == null){
			if('currentFormId' in fbuilderjQuery.fbuilder) _form = fbuilderjQuery.fbuilder.currentFormId;
			else return '_1';
		}
		if(/^_\d*$/.test(_form)) return _form;
		if(/^\d*$/.test(_form)) return '_'+_form;
		return $( $(_form).length ? _form : '#'+_form ).find('[name="cp_calculatedfieldsf_pform_psequence"]').val();
	}

	function _getField( _field, _form )
	{
        try
        {
            if(typeof _field == 'undefined') return false;
            if(typeof _field == 'object')
            {
                if('ftype' in _field) return _field;
                if('jquery' in _field)
                {
                    if(_field.length) _field = _field[0];
                    else return false;
                }

                if('getAttribute' in _field)
                {
					_form  = $(_field).closest('form');
                    var to_check = _field.getAttribute('class').match(/fieldname\d+/);
					if(to_check) _field = to_check[0];
					else {
						_field = _field.getAttribute('name').match(/fieldname\d+/)[0];
					}
                }
                else return false;
            }
			if(typeof _form == 'undefined' && typeof _field == 'string' && _field.match(/fieldname\d+(_\d+)/)) _form = _field.match(/fieldname\d+(_\d+)/)[1];
            return $.fbuilder['forms'][_getForm(_form)].getItem(_field);
        } catch (err) { return false; }
	}

	function _fillElementsArray( e ) {
		if ( ! ( e in elements_equations ) ) {
			elements_equations[e] = {};
			$( e ).on( 'change keyup', function() {
				for ( let i in elements_equations[e] ) {
					EVALEQUATION( i );
				}
			} );
		}

		if ( 'currentEq' in $.fbuilder ) {
			if ( ! ( $.fbuilder['currentEq']['result'] in elements_equations[e] ) ) {
				elements_equations[e][ $.fbuilder['currentEq']['result'] ] = 1;
			}
		}
	}

	/*** PUBLIC FUNCTIONS ***/

	lib.ELEMENTINFO = function( selector, to_get ){
		let e = $( selector ),
			r = [];

		to_get = ( new String( to_get || 'value' ) )
			.toLowerCase()
			.replace( /^\s*/, '')
			.replace( /\s*$/, '');

		to_get = [ 'html', 'text' ].indexOf( to_get ) != -1 ? to_get : 'val';

		if (e.length) {
			_fillElementsArray( selector );
			e.each( function( i, e ){
				r.push( $( e )[to_get]() );
			} );
		}

		return r.length == 0 ? null : ( r.length ==  1 ? r[0] : r );
	};

    lib.getField = function(_field, _form)
    {
		// To eval equations with fields from other forms
		var _fi = _getField(_field, _form), _fo;
		if(_fi){
			try{
				_fo = _fi['form_identifier'];
				if(
					_fo &&
					_fi &&
					'currentFormId' in $.fbuilder &&
					'currentEq' in $.fbuilder &&
					$.fbuilder['currentEq']['identifier'] != _fo
				){
					var _eqResultField = $.fbuilder[ 'currentEq' ][ 'result' ];
					if ( !( _fi.name in lib.formsDependency ) ) {
						lib.formsDependency[ _fi.name ] = {};
						$( '[name *= "' + _fi.name + '"]' ).on( 'change keyup depEvent', function(){
							var _fname = $(this).attr('name').match( /fieldname\d+_\d+/ )[0];
							if( _fname in lib.formsDependency ) {
								for( var i in lib.formsDependency[ _fname ] ) {
									EVALEQUATION(i, lib.formsDependency[ _fname ][ i ])
								}
							}
						} );
					}
					lib.formsDependency[ _fi.name ][ _eqResultField ] = $.fbuilder['currentFormId'];
				}
			} catch(err){}
		} else if(typeof _form != 'undefined') {
			if(
				'currentFormId' in $.fbuilder &&
				'currentEq' in $.fbuilder
			){
				var _eqResultField = $.fbuilder[ 'currentEq' ][ 'result' ];
				$(_form).on('change keyup depEvent', '[name*="'+_field+'"]', (
				    function(_field){
					    return function(){
						    EVALEQUATION(_field);
					    };
				    })(_eqResultField)
			    );
			}
		}
		// End

        return _fi;
    };

	lib.validform = lib.VALIDFORM = lib.ValidForm = function( _form, _silent ){
		_silent = _silent || false;
		var o = _getForm(_form), f;
		if(o){
			f = $('[id="'+$.fbuilder.forms[o]['formId']+'"]');
			if(f.length) return _silent ? f.validate().checkForm()  : f.valid();
		}
		return false;
	};

	lib.validfield = lib.VALIDFIELD = lib.ValidField = function( _field, _form, _silent ){
		_silent = _silent || false;
		var o = _getForm(_form), f = _getField(_field, _form), j;
		if(f){
			j = f.jQueryRef().find(':input');
			if(j.length)
				return _silent ? j.closest('form').validate().check( j ) : j.valid();
		}
		return false;
	};

	lib.activatefield = lib.ACTIVATEFIELD = function( _field, _form )
	{
		var o = _getForm(_form), f = _getField(_field, _form), j;
		if(f)
		{
			j = f.jQueryRef();
            j.removeClass('ignorefield');
			if(j.find('[id*="'+f.name+'"]').hasClass('ignore'))
			{
                j.add(j.find('.fields')).show();
				if(f.name in $.fbuilder.forms[o].toHide) delete $.fbuilder.forms[o].toHide[f.name];
				if(!(f.name in $.fbuilder.forms[o].toShow)) $.fbuilder.forms[o].toShow[f.name] = {'ref': {}};
				j.find('[id*="'+f.name+'"]').removeClass('ignore').trigger('change');
				$.fbuilder.showHideDep({'formIdentifier':o,'fieldIdentifier':f.name});
			}
		}
	};

	lib.ignorefield = lib.IGNOREFIELD = function( _field, _form )
	{
		var o = _getForm(_form), f = _getField(_field, _form), j;
		if(f)
		{
			j = f.jQueryRef();
            j.addClass('ignorefield');
			if(!j.find('[id*="'+f.name+'"]').hasClass('ignore'))
			{
				j.add(j.find('.fields')).hide();
				if(!(f.name in $.fbuilder.forms[o].toHide)) $.fbuilder.forms[o].toHide[f.name] = {};
				if(f.name in $.fbuilder.forms[o].toShow) delete $.fbuilder.forms[o].toShow[f.name];
				j.find('[id*="'+f.name+'"]').addClass('ignore').trigger('change');
				$.fbuilder.showHideDep({'formIdentifier':o,'fieldIdentifier':f.name});
			}
		}
	};

    lib.isignored = lib.ISIGNORED = function( _field, _form )
	{
		var o = _getForm(_form), f = _getField(_field, _form), j;
		if(f) return 0 < f.jQueryRef().find('.ignore').length;
        return false;
	};

    lib.showfield = lib.SHOWFIELD = function( _field, _form )
    {
        var f = _getField(_field, _form), j;
		if(f)
		{
			j = f.jQueryRef();
            if(!j.find('[id*="'+f.name+'"]').hasClass('ignore'))
                j.removeClass('hide-strong hide').show();
		}
    };

	lib.hidefield = lib.HIDEFIELD = function( _field, _form )
    {
        var f = _getField(_field, _form);
		if(f)
		{
            j = f.jQueryRef();
            if(!j.find('[id*="'+f.name+'"]').hasClass('ignore'))
                f.jQueryRef().addClass('hide-strong');
		}
    };

	lib.ishidden = lib.ISHIDDEN = function( _field, _form )
	{
		var o = _getForm(_form), f = _getField(_field, _form), j;
		if(f) return f.jQueryRef().is(':hidden');
        return true;
	};

    lib.disableequations = lib.DISABLEEQUATIONS = function(f)
	{
		fbuilderjQuery(f || '[id*="cp_calculatedfieldsf_pform_"]').attr('data-evalequations',0);
	};

	lib.enableequations = lib.ENABLEEQUATIONS = function(f)
	{
		fbuilderjQuery(f || '[id*="cp_calculatedfieldsf_pform_"]').attr('data-evalequations',1);
	};

	lib.EVALEQUATIONS = lib.evalequations = function(f)
	{
		if( typeof f != 'undefined') {
			fbuilderjQuery.fbuilder.calculator.defaultCalc(f, false, true);
		} else {
			for( var i in fbuilderjQuery.fbuilder.forms ) {
				fbuilderjQuery.fbuilder.calculator.defaultCalc(fbuilderjQuery('[id="'+fbuilderjQuery.fbuilder.forms[i].formId+'"]'), false, true);
			}
		}
	};

	lib.EVALEQUATION = lib.evalequation = function( _field, _form )
	{
        try
        {
            /* For compatibility with function( _form, _field ) */
            if(typeof _field == 'object' && 'tagName' in _field && _field.tagName == 'FORM')
                [_field, _form] = [_form, _field];

            var c = fbuilderjQuery.fbuilder.calculator;

            if(typeof _field == 'undefined') c.defaultCalc(_form);

            var f = _getField(_field, _form),
                o = f.jQueryRef().closest('form')[0];

            for(i in o.equations)
            {
                if(o.equations[i].result == f.name){
                    c.enqueueEquation(f.form_identifier, [o.equations[i]]);
                    c.processQueue(f.form_identifier);
                    return;
                }
            }
        }
        catch(err){if('console' in window) console.log(err);}
    };


    lib.COPYFIELDVALUE = lib.copyfieldvalue = function(_field, _form)
    {
        var f = _getField(_field, _form), j;
		if(f)
		{
			j = f.jQueryRef().find(':input:eq(0)');
			if(j.length)
			{
				try
				{
					if(j.is(':visible')) {
						j.select();
						document.execCommand('copy');
					} else navigator.clipboard.writeText(j.val());
				} catch(err){}
			}
		}
    };

	function copyToClipboard(_field, _form, _html){
		_html = _html || false;
		if ( ! ( 'ClipboardItem' in window ) ) return;
        var f = _getField(_field, _form), h;
		if(f) {
			h = f.jQueryRef()[_html ? 'html' : 'text']();
		} else {
			try {
				f = $( _field );
				if ( f.length ) h = f[_html ? 'html' : 'text']();
			} catch ( err ) {}
		}

		if(h) {
			const clipboardItem = new ClipboardItem({'text/html':  new Blob([h],{type: 'text/html'}), 'text/plain': new Blob([h], {type: 'text/plain'})});
    		navigator.clipboard.write([clipboardItem]).then(
				_ => { if( 'console' in window ) console.log("clipboard.write() Ok") },
				error => { if( 'console' in window ) console.log(error) }
			);
		}
	};

    lib.COPYTEXT = lib.copytext = function(_field, _form){ copyToClipboard(_field, _form, false); };
    lib.COPYHTML = lib.copyhtml = function(_field, _form){ copyToClipboard(_field, _form, true); };

    lib.gotopage = lib.GOTOPAGE = lib.goToPage = function(p, f)
    {
        try
        {
            var o = $('#'+$.fbuilder['forms'][_getForm(f)].formId), c;
            if(o.length)
            {
                c = o.find('.pbreak:visible').attr('page');
                $.fbuilder.goToPage({'form':o,'from':c,'to':p, 'forcing' : true});
            }
        } catch(err) { if('console' in window) console.log(err); }
    };

    lib.gotofield = lib.GOTOFIELD = lib.goToField = function(e, f)
    {
        try
        {
            var o = $('#'+$.fbuilder['forms'][_getForm(f)].formId), p, c;
			if(o.length)
            {
				e = o.find('[id*="'+(Number.isInteger(e) ? 'fieldname'+e : e)+'_"]');
				if(e.length)
				{
					c = o.find('.pbreak:visible').attr('page');
					p = e.closest('.pbreak').attr('page');
					$(document).one('cff-gotopage', function(evt, arg){
						if(e.is(':visible'))
							$('html,body').animate({scrollTop:e.offset().top});
					});
					$.fbuilder.goToPage({'form':o,'from':c,'to':p, 'forcing' : true});
				}
            }
        } catch(err) { if('console' in window) console.log(err); }
    };

    if(window.PRINTFORM == undefined)
    {
        lib.printform = lib.PRINTFORM = function(show_pages, f)
        {
			f = _getForm(f);
			function addRemoveClasses(add){
				var o = $('#'+$.fbuilder['forms'][f].formId),
					m = add ? 'addClass' : 'removeClass';
				if(o.length)
				{
					o[m]('cff-print');
					if(!!show_pages) o.find('.pbreak')[m]('cff-print');
					while(o.length)
					{
						o.siblings()[m]('cff-no-print');
						o = o.parent();
					}
				}

			};
			addRemoveClasses(true);
            window.print();
            setTimeout(function(){
				addRemoveClasses(false);
            }, 5000);
        };

    }

	lib.RESETFORM = lib.resetform = function(f)
	{
		function _reset(f){
			try {
				if ( f.length && f[0].tagName == 'FORM' ) {
					if ( ! f.hasClass( 'cff-reset-form' ) ) {
						let seq = f.find('[name="cp_calculatedfieldsf_pform_psequence"]');
						if( seq.length ) {
							let fid = seq.val();
							f.find('.cff-calculated-field input').removeAttr('value');
							f[0].reset();
							setTimeout(
								function()
								{
									var id = f.attr('id');
									if( 'validate' in f ) f.validate().resetForm();
									f.find('.select2-hidden-accessible').trigger('change');
									f.find(':data(manually)').removeData('manually');
									$.fbuilder['showHideDep']({ 'formIdentifier' : fid });
									$.fbuilder.forms[fid]['currentPage'] = 0;
									$("#fieldlist"+fid+" .pbreak").css("display","none");
									$("#fieldlist"+fid+" .pbreak").find(".field").addClass("ignorepb");
									$("#fieldlist"+fid+" .pb0").css("display","block");

									if ($("#fieldlist"+fid+" .pb0").find(".field").length>0)
									{
										$("#fieldlist"+fid+" .pb0").find(".field").removeClass("ignorepb");
										try
										{
											let first_field = $("#fieldlist"+fid+" .pb0").find(".field:eq(0)");
											if ( first_field.hasClass('hasDatepicker') ) {
												first_field.datepicker( 'option', 'showOn', 'none' );
												first_field.focus();
												first_field.datepicker( 'option', 'showOn', 'focus' );
												first_field.blur();
											} else  first_field.focus();
										}
										catch(e){}
									}
									if(f.attr('data-evalequations')*1) {
										f.addClass( 'cff-reset-form' );
										$.fbuilder['calculator'].defaultCalc('#'+id, false);
										f.removeClass( 'cff-reset-form' );
									}
								},
								50
							);

						}
					} else {
						setTimeout( function(){ f.removeClass( 'cff-reset-form' ); }, 500 );
					}
				}
			} catch ( err ) {}
		}

		if( typeof f != 'undefined') {
			_reset(fbuilderjQuery(f));
		} else {
			for( var i in fbuilderjQuery.fbuilder.forms ) {
				_reset(fbuilderjQuery('[id="'+fbuilderjQuery.fbuilder.forms[i].formId+'"]'));
			}
		}
	};

	if ( window.cffsanitize == undefined ) {
		lib.cffsanitize = lib.CFFSANITIZE = function( v, script_only ) {
			script_only = script_only || 0;

			if (
				Array.isArray( v ) ||
				(
					typeof v === 'object' &&
					! Array.isArray( v ) &&
					v !== null
				)
			) {
				for ( var i in v ) {
					v[ i ] = lib.CFFSANITIZE( v[ i ], script_only );
				}
			} else {
				if ( typeof v == 'string' ) {
					if ( script_only ) {
						v = v.replace(/<script\b.*\bscript>/ig, '')
							 .replace(/<script[^>]*>/ig, '')
							 .replace(/(\b)(on[a-z]+)\s*=/ig, "$1_$2=");
					} else {
						v = fbuilderjQuery( '<p>' ).text( v ).html();
					}
				}
			}

			return v;
		};
	}

	root.CF_FIELDS_MANAGEMENT = lib;

})(this);