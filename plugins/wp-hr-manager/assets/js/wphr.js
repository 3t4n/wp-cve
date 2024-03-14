/* jshint devel:true */
/* global wpHrCountries */
/* global wp */
/* global wpHr */

window.wphr = window.wphr || {};

;(function($) {
    'use strict';

    wphr.template = function ( id ) {
        var options = {
            evaluate:    /<#([\s\S]+?)#>/g,
            interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
            escape:      /\{\{([^\}]+?)\}\}(?!\})/g,
            variable:    'data'
        };

        return function ( data ) {
            return _.template( $( '#tmpl-' + id ).html(), null, options )( data );
        };
    };

    /**
     * Load script after dom replace
     *
     * @return {void}
     */
    wphr.scriptReload =  function( action, id ) {
        wp.ajax.send( {
            data: {
                action: action,
            },
            success: function(res) {
                $('#'+id).html(res.content);
            },
            error: function(error) {
                alert( error );
            }
        });
    };

    /**
     * Set Time Format from date
     *
     * @return {void}
     */
    wphr.timeFormat = function( date ) {
        if ( date == null ) return;
        var d = date.toString();
        date = new Date( d.substr(0, 4), d.substr(5, 2) - 1, d.substr(8, 2), d.substr(11, 2), d.substr(14, 2), d.substr(17, 2) );
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0'+minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + ampm;
        return strTime;
    };

    wphr.dateFormat = function ( date, format ) {
        if ( date == null ) return;
        var d = date.toString();
        date = new Date( d.substr(0, 4), d.substr(5, 2)-1, d.substr(8, 2), d.substr(11, 2), d.substr(14, 2), d.substr(17, 2) );
        var month = ("0" + (date.getMonth() + 1)).slice(-2),
            day   = ("0" + date.getDate()).slice(-2),
            year  = date.getFullYear(),
            monthArray = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ],
            monthShortArray = [ "Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec" ],
            monthName = monthArray[date.getMonth()],
            monthShortName = monthShortArray[date.getMonth()];
        var pattern = {
            Y: year,
            m: month,
            F: monthName,
            M: monthShortName,
            d: day,
            j: day
        };

        var dateStr = format.replace(/Y|m|d|j|M|F/gi, function( matched ){
            return pattern[matched];
        });

        return dateStr;
    };

    wphr.parseCondition = function( value ) {
        var obj = {};
        var res = value.split(/([a-zA-Z0-9\s\-\_\+\.\,\:]+)/);
        if ( res[0] == '' ) {
            obj.condition = '';
            obj.val = res[1];
        } else {
            obj.condition = res[0];
            obj.val = res[1];
        }

        return obj;
    };


    wphr.wphr_parse_str = function(str, array) {
        var strArr = String(str)
            .replace(/^&/, '')
            .replace(/^\?/, '')
            .replace(/&$/, '')
            .split('&'),
            sal = strArr.length,
            i, j, ct, p, lastObj, obj, lastIter, undef, chr, tmp, key, value,
            postLeftBracketPos, keys, keysLen,
            fixStr = function(str) {
                return decodeURIComponent(str.replace(/\+/g, '%20'));
            };
        if (!array) {
            array = this.window;
        }
        for (i = 0; i < sal; i++) {
            tmp = strArr[i].split('=');
            key = fixStr(tmp[0]);
            value = (tmp.length < 2) ? '' : fixStr(tmp[1]);

            while (key.charAt(0) === ' ') {
                key = key.slice(1);
            }
            if (key.indexOf('\x00') > -1) {
                key = key.slice(0, key.indexOf('\x00'));
            }
            if (key && key.charAt(0) !== '[') {
                keys = [];
                postLeftBracketPos = 0;
                for (j = 0; j < key.length; j++) {
                    if (key.charAt(j) === '[' && !postLeftBracketPos) {
                        postLeftBracketPos = j + 1;
                    } else if (key.charAt(j) === ']') {
                        if (postLeftBracketPos) {
                            if (!keys.length) {
                                keys.push(key.slice(0, postLeftBracketPos - 1));
                            }
                            keys.push(key.substr(postLeftBracketPos, j - postLeftBracketPos));
                            postLeftBracketPos = 0;
                            if (key.charAt(j + 1) !== '[') {
                                break;
                            }
                        }
                    }
                }
                if (!keys.length) {
                    keys = [key];
                }
                for (j = 0; j < keys[0].length; j++) {
                    chr = keys[0].charAt(j);
                    if (chr === ' ' || chr === '.' || chr === '[') {
                        keys[0] = keys[0].substr(0, j) + '_' + keys[0].substr(j + 1);
                    }
                    if (chr === '[') {
                        break;
                    }
                }

                obj = array;
                for (j = 0, keysLen = keys.length; j < keysLen; j++) {
                    key = keys[j].replace(/^['"]/, '')
                        .replace(/['"]$/, '');
                    lastIter = j !== keys.length - 1;
                    lastObj = obj;
                    if ((key !== '' && key !== ' ') || j === 0) {
                        if (obj[key] === undef) {
                            obj[key] = {};
                        }
                        obj = obj[key];
                    } else {
                        // To insert new dimension
                        ct = -1;
                        for (p in obj) {
                            if (obj.hasOwnProperty(p)) {
                                if (+p > ct && p.match(/^\d+$/g)) {
                                    ct = +p;
                                }
                            }
                        }
                        key = ct + 1;
                    }
                }
                lastObj[key] = value;
            }
        }
    };

    wphr.wphrGetParamByName = function( name, url ) {
        url = url.toLowerCase();
        name = name.replace(/[\[\]]/g, "\\$&").toLowerCase();

        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);

        if ( !results ) {
            return null;
        }

        if ( !results[2] ) {
            return '';
        }
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    };

    wphr.wphrRemoveURLParameter = function ( url, parameter ) {
        //prefer to use l.search if you have a location/link object
        var urlparts= url.split('?');
        if (urlparts.length>=2) {

            var prefix= encodeURIComponent(parameter)+'=';
            var pars= urlparts[1].split(/[&;]/g);

            //reverse iteration as may be destructive
            for (var i= pars.length; i-- > 0;) {
                //idiom for string.startsWith
                if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                    pars.splice(i, 1);
                }
            }

            url= urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
            return url;
        } else {
            return url;
        }
    };

    wphr.swalSpinnerVisible = function() {
        swal({
            title: '',
            html: true,
            showCancelButton: false,
            showConfirmButton: false,
        });

        $('.la-ball-fall').css({
            'opacity' : 1,
            'visibility' : 'visible',
            'top' : '-17px',
            'color' : '#008ec2'
        });
    };

    wphr.swalSpinnerHidden = function() {
        $('.la-ball-fall').css({
            'opacity' : 0,
            'visibility' : 'hidden',
        });
    };

    var clsWP_HR = {

        /**
         * Initialize the events
         *
         * @return {void}
         */
        initialize: function() {

            $( '#postimagediv').on( 'click', '#set-company-thumbnail', this.setCompanyLogo );
            $( '#postimagediv').on( 'click', 'a.remove-logo', this.removeCompanyLogo );

            $( 'body').on( 'click', 'a#wphr-company-new-location', this.newCompanyLocation );
            $( '.wphr-company-single').on( 'click', 'a.edit-location', this.editCompanyLocation );
            $( '.wphr-company-single').on( 'click', 'a.remove-location', this.removeCompanyLocation );

            // on popup, country change event
            $( 'body' ).on('change', 'select.wphr-country-select', this.populateState );

            $( 'body' ).on( 'wphr-hr-after-new-location', this.afterNewLocation );

            // $('select.wphr-country-select').trigger('change');

            $( '.wphr-hr-audit-log' ).on( 'click', 'a.wphr-audit-log-view-changes', this.viewLogChanges );
            $( 'body').on( 'change', '#filter_duration', this.customFilter );

          //  $( 'body' ).on( 'click', '#dactivatedata_id', this.deactivationConfirmation );
            
            this.initFields();
        },

        afterNewLocation: function(e, res) {
            wphr.scriptReload( 'wphr_hr_script_reload', 'tmpl-wphr-new-employee' );
            $('.wphr-hr-location-drop-down').append('<option selected="selected" value="'+res.id+'">'+res.title+'</option>');
            $('.wphr-hr-location-drop-down').select2("val", res.id);
        },

        initFields: function() {
            $( '.wphr-date-field').datepicker({
                dateFormat: wpHr.date_format,
                changeMonth: true,
                changeYear: true,
                yearRange: '-100:+5',
            });

            $( ".wphr-date-picker-from" ).datepicker({
                dateFormat: wpHr.date_format,
                changeYear: true,
                changeMonth: true,
                numberOfMonths: 1,
                onClose: function( selectedDate ) {
                    $( ".wphr-date-picker-to" ).datepicker( "option", "minDate", selectedDate );
                }
            });

            $( ".wphr-date-picker-to" ).datepicker({
                dateFormat: wpHr.date_format,
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                onClose: function( selectedDate ) {
                    $( ".wphr-date-picker-from" ).datepicker( "option", "maxDate", selectedDate );
                }
            });

            $( '.wphr-select2' ).select2({
                placeholder: $(this).attr('data-placeholder')
            });
        },

        viewLogChanges: function(e) {
            e.preventDefault();
            var self = $(this);

            wp.ajax.send( 'wphr_audit_log_view', {
                data: {
                    id : self.data( 'id' ),
                    _wpnonce: wpHr.nonce
                },
                success: function(res) {
                    // console.log( res );
                    $.wphrPopup({
                        title: res.title,
                        button: '',
                        id: 'wphr-audit-log-popup',
                        content: res.content,
                        extraClass: 'midium',
                    });
                },
                error: function(error) {
                    alert( error );
                }
            });

        },

        /**
         * Upload and set company logo
         *
         * @param {event}
         */
        setCompanyLogo: function(e) {
            e.preventDefault();
            e.stopPropagation();

            var frame;

            if ( frame ) {
                frame.open();
                return;
            }

            frame = wp.media({
                title: wpHr.upload_logo,
                button: { text: wpHr.set_logo }
            });

            frame.on('select', function() {
                var selection = frame.state().get('selection');

                selection.map( function( attachment ) {
                    attachment = attachment.toJSON();

                    var html = '<img src="' + attachment.url + '" alt="" />';
                        html += '<input type="hidden" name="company_logo_id" value="' + attachment.id + '" />';
                        html += '<a href="#" class="remove-logo">' + wpHr.remove_logo + '</a>';

                    $( '.inside', '#postimagediv' ).html( html );
                });
            });

            frame.open();
        },

        /**
         * Remove the company logo
         *
         * @param  {event}
         *
         * @return {void}
         */
        removeCompanyLogo: function(e) {
            e.preventDefault();

            var html = '<a href="#" id="set-company-thumbnail" class="thickbox">' + wpHr.upload_logo + '</a>';

            $( '.inside', '#postimagediv' ).html( html );
        },

        /**
         * Populate the state dropdown based on selected country
         *
         * @return {void}
         */
        populateState: function() {

            if ( typeof wpHrCountries === 'undefined' ) {
                return false;
            }


            var self = $(this),
                country = self.val(),
                parent = self.closest( self.data('parent') ),
                empty = '<option value="-1">- Select -</option>';

            if ( wpHrCountries[ country ] ) {
                var options = '',
                    state = wpHrCountries[ country ];

                for ( var index in state ) {
                    options = options + '<option value="' + index + '">' + state[ index ] + '</option>';
                }

                if ( $.isArray( wpHrCountries[ country ] ) ) {
                    parent.find('select.wphr-state-select').html( empty );
                } else {
                    parent.find('select.wphr-state-select').html( options );
                }

            } else {
                parent.find('select.wphr-state-select').html( empty );
            }
        },

        /**
         * date filter on audit log
         */
        customFilter: function () {
            if ( 'custom' != this.value ) {
                $( '#custom-input' ).remove();
            } else {
                var element = '<span id="custom-input"><span>From </span><input name="start" class="wphr-date-field" type="text">&nbsp;<span>To </span><input name="end" class="wphr-date-field" type="text"></span>&nbsp;';
                $( '#filter_duration' ).after( element );
                clsWP_HR.initFields();
            }
        },

        newCompanyLocation: function(e) {
            e.preventDefault();

            var self = $(this);

            $.wphrPopup({
                title: self.data('title'),
                button: wpHr.create,
                id: 'wphr-new-location',
                content: wp.template( 'wphr-address' )({ company_id: self.data('id') }).trim(),
                extraClass: 'medium',
                onReady: function() {
                    $( '.select2' ).select2();
                },
                onSubmit: function(modal) {
                    wp.ajax.send( {
                        data: this.serialize(),
                        success: function(res) {
                            $('#company-locations').load( window.location.href + ' #company-locations-inside' );
                            if ( ! self.hasClass('wphr-add-new-location') ) {
                                $('body').trigger( 'wphr-hr-after-new-location', [res]);
                            };
                            modal.closeModal();
                        },
                        error: function(error) {
                            modal.showError( error );
                        }
                    });
                }
            });
        },

        editCompanyLocation: function(e) {
            e.preventDefault();

            var self = $(this);

            $.wphrPopup({
                title: wpHr.update_location,
                button: wpHr.update_location,
                id: 'wphr-edit-location',
                content: wp.template( 'wphr-address' )( self.data('data') ),
                extraClass: 'medium',
                onReady: function() {
                    $( '.select2' ).select2();
					var selected = '';
                    $( 'li[data-selected]', this ).each(function() {
                        var self = $(this),
                            selected = self.data('selected');

                        if ( selected !== '' ) {
                            self.find( 'select' ).val( selected );
                        }
                    });

                    $( 'select.wphr-country-select').change();
                    if ( selected !== '' ) {
						setTimeout(function(){$( 'select.wphr-state-select').val(selected)}, 1000);
					}
                },
                onSubmit: function(modal) {
                    wp.ajax.send( {
                        data: this.serializeObject(),
                        success: function() {
                            $('#company-locations').load( window.location.href + ' #company-locations-inside' );
                            modal.closeModal();
                        },
                        error: function(error) {
                            modal.showError( error );
                        }
                    });
                }
            });
        },

        removeCompanyLocation: function(e) {
            e.preventDefault();

            if ( confirm( wpHr.confirm ) ) {
                wp.ajax.send( 'wphr-delete-comp-location', {
                    data: {
                        id: $(this).data('id'),
                        _wpnonce: wpHr.nonce
                    },
                    success: function() {
                        $('#company-locations').load( window.location.href + ' #company-locations-inside' );
                    }
                });
            }
        },

        deactivationConfirmation: function(){
            if($('.wphr-settings').length > 0 ){
                var el = $(this);
                var checked = $(this).is(':checked');
                if(checked == true){
                    swal({   
                        title: wpHr.confirmMsg_1,   
                        text: "",   
                        type: "warning",   
                        showCancelButton: true,      
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "Yes",   
                        cancelButtonText: "No",   
                        closeOnConfirm: true,   
                        closeOnCancel: true,
                        customClass: "Custom_Cancel"
                    }, 
                    function(isConfirm){   
                       if (isConfirm == true) {
                            el.prop("checked", true);     
                        }else {
                            el.prop("checked", false);      
                        } 
                  });
                }
            }
        }
    };

    $(function() {
        clsWP_HR.initialize();
    });

})(jQuery, this);

/**
 * A nifty plugin to converty form to serialize object
 *
 * @link http://stackoverflow.com/questions/1184624/convert-form-data-to-js-object-with-jquery
 */
jQuery.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    jQuery.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

// wphrDropdown forked version of bootstrap dropdown
+function ($) {
    'use strict';

    // DROPDOWN CLASS DEFINITION
    // =========================

    var backdrop = '.wphr-dropdown-backdrop'
    var toggle   = '[data-toggle="wphr-dropdown"]'
    var WPHRDropdown = function (element) {
        $(element).on('click.wphr.dropdown', this.toggle)
    }

    WPHRDropdown.VERSION = '1.0.0' // bootstrap dropdown 3.3.7

    function getParent($this) {
        var selector = $this.attr('data-target')

        if (!selector) {
            selector = $this.attr('href')
            selector = selector && /#[A-Za-z]/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
        }

        var $parent = selector && $(selector)

        return $parent && $parent.length ? $parent : $this.parent()
    }

    function clearMenus(e) {
        if (e && e.which === 3) return
        $(backdrop).remove()
        $(toggle).each(function () {
            var $this         = $(this)
            var $parent       = getParent($this)
            var relatedTarget = { relatedTarget: this }

            if (!$parent.hasClass('open')) return

            if (e && e.type == 'click' && /input|textarea/i.test(e.target.tagName) && $.contains($parent[0], e.target)) return

            $parent.trigger(e = $.Event('hide.wphr.dropdown', relatedTarget))

            if (e.isDefaultPrevented()) return

            $this.attr('aria-expanded', 'false')
            $parent.removeClass('open').trigger($.Event('hidden.wphr.dropdown', relatedTarget))
        })
    }

    WPHRDropdown.prototype.toggle = function (e) {
        var $this = $(this)

        if ($this.is('.disabled, :disabled')) return

        var $parent  = getParent($this)
        var isActive = $parent.hasClass('open')

        clearMenus()

        if (!isActive) {
            if ('ontouchstart' in document.documentElement && !$parent.closest('.navbar-nav').length) {
                // if mobile we use a backdrop because click events don't delegate
                $(document.createElement('div'))
                    .addClass('wphr-dropdown-backdrop')
                    .insertAfter($(this))
                    .on('click', clearMenus)
            }

            var relatedTarget = { relatedTarget: this }
            $parent.trigger(e = $.Event('show.wphr.dropdown', relatedTarget))

            if (e.isDefaultPrevented()) return

            $this
                .trigger('focus')
                .attr('aria-expanded', 'true')

            $parent
                .toggleClass('open')
                .trigger($.Event('shown.wphr.dropdown', relatedTarget))
        }

        return false
    }

    WPHRDropdown.prototype.keydown = function (e) {
        if (!/(38|40|27|32)/.test(e.which) || /input|textarea/i.test(e.target.tagName)) return

        var $this = $(this)

        e.preventDefault()
        e.stopPropagation()

        if ($this.is('.disabled, :disabled')) return

        var $parent  = getParent($this)
        var isActive = $parent.hasClass('open')

        if (!isActive && e.which != 27 || isActive && e.which == 27) {
            if (e.which == 27) $parent.find(toggle).trigger('focus')
            return $this.trigger('click')
        }

        var desc = ' li:not(.disabled):visible a'
        var $items = $parent.find('.wphr-dropdown-menu' + desc)

        if (!$items.length) return

        var index = $items.index(e.target)

        if (e.which == 38 && index > 0)                 index--         // up
        if (e.which == 40 && index < $items.length - 1) index++         // down
        if (!~index)                                    index = 0

        $items.eq(index).trigger('focus')
    }


    // DROPDOWN PLUGIN DEFINITION
    // ==========================

    function Plugin(option) {
        return this.each(function () {
            var $this = $(this)
            var data  = $this.data('wphr.dropdown')

            if (!data) $this.data('wphr.dropdown', (data = new WPHRDropdown(this)))
            if (typeof option == 'string') data[option].call($this)
        })
    }

    var old = $.fn.wphrDropdown

    $.fn.wphrDropdown             = Plugin
    $.fn.wphrDropdown.Constructor = WPHRDropdown


    // DROPDOWN NO CONFLICT
    // ====================

    $.fn.wphrDropdown.noConflict = function () {
        $.fn.wphrDropdown = old
        return this
    }


    // APPLY TO STANDARD DROPDOWN ELEMENTS
    // ===================================

    $(document)
        .on('click.wphr.dropdown.data-api', clearMenus)
        .on('click.wphr.dropdown.data-api', '.wphr-dropdown form', function (e) { e.stopPropagation() })
        .on('click.wphr.dropdown.data-api', toggle, WPHRDropdown.prototype.toggle)
        .on('keydown.wphr.dropdown.data-api', toggle, WPHRDropdown.prototype.keydown)
        .on('keydown.wphr.dropdown.data-api', '.wphr-dropdown-menu', WPHRDropdown.prototype.keydown)
	
}(jQuery);

jQuery(function(){
	if(jQuery(".meta-box-sortables").length)
		jQuery(".meta-box-sortables").sortable();
	
	jQuery(".button-link").click(function(e){
		
		if(jQuery(this).parent().hasClass("closed"))
		{
			jQuery(this).parent().removeClass("closed");
			jQuery(this).attr("aria-expanded","true");
		}
		else
		{
			jQuery(this).parent().addClass("closed");
			jQuery(this).attr("aria-expanded","false");
		}
	});
});
