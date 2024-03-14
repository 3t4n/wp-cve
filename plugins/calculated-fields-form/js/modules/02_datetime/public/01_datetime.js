/*
* datetime.js v0.1
* By: CALCULATED FIELD PROGRAMMERS
* The script allows operations with date and time
* Copyright 2013 CODEPEOPLE
* You may use this project under MIT or GPL licenses.
*/

;(function(root){
	var lib 		   = {},
		default_format = ( typeof window.DATETIMEFORMAT != 'undefined' ) ? window.DATETIMEFORMAT : 'yyyy-mm-dd hh:ii:ss a',
		regExp		   = '';


	Date.prototype.valid = function() {
		 return isFinite(this);
	};

	/*** PRIVATE FUNCTIONS ***/
	function _processArguments( date, format, leading_zeros ){
        if(arguments.length == 1 && (typeof date == 'boolean' || date == 0 || date == 1))
        {
            leading_zeros = date;
            date = undefined;
            format = undefined;
        }
		leading_zeros = leading_zeros || 0;
		return { date:date, format:format, leading_zeros: leading_zeros };
	}

	function _leadingZeros( n ) {
		return n < 10  ? 0 + '' + n : n;
	};

	function _getDateObj( date, format ){
		try{ if ( date instanceof Date ) return date; } catch(err) {}
		var d = new Date();

		format = format || default_format;
		if( typeof date != 'undefined' ){
			if( typeof date == 'number' ){
				// d = new Date(Math.ceil(date*86400000));
				d = new Date(date*86400000);
			}else if( typeof date == 'string' ){
				var p;
				if( null != ( p = /(\d{4})[\/\-\.](\d{1,2})[\/\-\.](\d{1,2})/.exec( date ) ) ){
					if( /y{4}[\/\-\.]m{2}[\/\-\.]d{2}/i.test( format ) ){
						d = new Date( p[ 1 ], ( p[ 2 ] - 1 ), p[ 3 ] );
					}else{
						d = new Date( p[ 1 ], ( p[ 3 ] - 1 ), p[ 2 ] );
					}
					date = date.replace( p[ 0 ], '' );
				}

				if( null != ( p = /(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})/.exec( date ) ) ){
					if( /d{2}[\/\-\.]m{2}[\/\-\.]y{4}/i.test( format ) ){
						d = new Date( p[ 3 ], ( p[ 2 ] - 1 ), p[ 1 ] );
					}else{
						d = new Date( p[ 3 ], ( p[ 1 ] - 1 ), p[ 2 ] );
					}
					date = date.replace( p[ 0 ], '' );
				}

				if( null != ( p = /(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{2})/.exec( date ) ) ){
					if( /d{2}[\/\-\.]m{2}[\/\-\.]y{2}/i.test( format ) ){
						d = new Date( 2000+p[ 3 ]*1, ( p[ 2 ] - 1 ), p[ 1 ] );
					}else if( /m{2}[\/\-\.]d{2}[\/\-\.]y{2}/i.test( format ) ){
						d = new Date( 2000+p[ 3 ]*1, ( p[ 1 ] - 1 ), p[ 2 ] );
					}else if( /y{2}[\/\-\.]d{2}[\/\-\.]m{2}/i.test( format ) ){
						d = new Date( 2000+p[ 1 ]*1, ( p[ 3 ] - 1 ), p[ 2 ] );
					}else if( /y{2}[\/\-\.]m{2}[\/\-\.]d{2}/i.test( format ) ){
						d = new Date( 2000+p[ 1 ]*1, ( p[ 2 ] - 1 ), p[ 3 ] );
					}
					date = date.replace( p[ 0 ], '' );
				}

				if( null != ( p = /(\d{1,2})[:\.](\d{1,2})([:\.](\d{1,2}))?\s*([ap]m)?/i.exec( date ) ) ){
					if(/h+/i.test( format ) ){
						if( typeof p[ 5 ] != 'undefined' && /pm/i.test( p[ 5 ] ) && p[ 1 ]*1 != 12 ) p[ 1 ] = ( p[ 1 ]*1 + 12 ) % 24;
						if( typeof p[ 5 ] != 'undefined' && /am/i.test( p[ 5 ] ) && p[ 1 ]*1 == 12 ) p[ 1 ] = 0;
						d.setHours( p[ 1 ] );
					}

					if(/i+/i.test( format ) ) d.setMinutes( p[ 2 ] );
					if(/s+/i.test( format ) && (typeof p[ 4 ] != 'undefined') ) d.setSeconds( p[ 4 ] );
				}
			}else{
				d = new Date( date );
			}

			d.setMilliseconds(0);
			if( ! /h+/i.test(format)) d.setHours(0);
			if( ! /i+/i.test(format)) d.setMinutes(0);
			if( ! /s+/i.test(format)) d.setSeconds(0);
		}
		return d;
	};

	/*** PUBLIC FUNCTIONS ***/

	lib.cf_datetime_version = '0.1';

	// DATEOBJ( date_string, date_format_string )
	lib.DATEOBJ = function( date, format ){
		var d = _getDateObj( date, format );
		if( d.valid() ) return d;
		return false;
	};

	// If X < 10, returns 0X
	lib.LEADINGZERO = function( v ) {
		if ( ! isNaN( v ) && 0 <= v ) {
			v = _leadingZeros( v );
		}
		return v;
	};

	// YEAR( date_string, date_format_string )
	lib.YEAR = function( date, format ){
		var d = _getDateObj( date, format );
		if( d.valid() ) return d.getFullYear();
		return false;
	};

	// MONTH( date_string, date_format_string )
	lib.MONTH = function( date, format, leading_zeros ){
		var tmp = _processArguments.apply( null, arguments );
        date 			= tmp[ 'date' ];
        format 			= tmp[ 'format' ];
        leading_zeros 	= tmp[ 'leading_zeros' ];

        var d = _getDateObj( date, format ), r = false;
		if( d.valid() ){
            r = d.getMonth()+1;
            if(leading_zeros) r = _leadingZeros( r );
        }
		return r;
	};

	lib.MONTHNAME = function( date, format, locale ){
		var d = lib.DATEOBJ( date, format ),
			r = false;
		if ( d ) {
			locale = locale || 'default';
			try {
				r = d.toLocaleString( locale, { month: 'long' } );
			} catch( err ) {
				r = d.toLocaleString( 'default', { month: 'long' } );
			}
		}
		return r;
	};

	lib.WEEKDAYNAME = function( date, format, locale ){
		var d = lib.DATEOBJ( date, format ),
			r = false;
		if ( d ) {
			locale = locale || 'default';
			try {
				r = d.toLocaleString( locale, { weekday: 'long' } );
			} catch( err ) {
				r = d.toLocaleString( 'default', { weekday: 'long' } );
			}
		}
		return r;
	};

	// DAY( date_string, date_format_string )
	lib.DAY = function( date, format, leading_zeros ){
		var tmp = _processArguments.apply( null, arguments );
        date 			= tmp[ 'date' ];
        format 			= tmp[ 'format' ];
        leading_zeros 	= tmp[ 'leading_zeros' ];

		var d = _getDateObj( date, format ), r = false;
		if( d.valid() )
        {
            r = d.getDate();
            if( leading_zeros ) r = _leadingZeros( r );
        }
		return r;
	};

	// WEEKDAY( date_string, date_format_string )
	lib.WEEKDAY = function( date, format, leading_zeros ){
        var tmp = _processArguments.apply( null, arguments );
        date 			= tmp[ 'date' ];
        format 			= tmp[ 'format' ];
        leading_zeros 	= tmp[ 'leading_zeros' ];

        var d = _getDateObj( date, format ), r = false;
		if( d.valid() ){
            r = d.getDay()+1;
            if( leading_zeros ) r = _leadingZeros( r );
        }
		return r;
	};

	// WEEKDAY( date_string, date_format_string )
	lib.WEEKNUM	= function( date, format, leading_zeros ){
		var tmp = _processArguments.apply( null, arguments );
        date 			= tmp[ 'date' ];
        format 			= tmp[ 'format' ];
        leading_zeros 	= tmp[ 'leading_zeros' ];

		var d   	= _getDateObj( date, format ), i, n,
			r 		= false;

		if ( d.valid() ) {

			i = new Date(d.getFullYear(), 0, 1, 0, 0, 0, 0);
			n = ( d - i ) / ( 24 * 60 * 60 * 1000 );
			r = Math.max( Math.ceil( (n+1) / 7 ), 1 );
			if ( leading_zeros ) r = _leadingZeros( r );

		}
		return r;
	};

	// HOURS( datetime_string, datetime_format_string )
	lib.HOURS = function( date, format, leading_zeros ){
		var tmp = _processArguments.apply( null, arguments );
        date 			= tmp[ 'date' ];
        format 			= tmp[ 'format' ];
        leading_zeros 	= tmp[ 'leading_zeros' ];

		var d = _getDateObj( date, format ), r = false;
		if ( d.valid() ) {
			r = d.getHours();
			if ( leading_zeros ) r = _leadingZeros( r );
		}
		return r;
	};

	// MINUTES( datetime_string, datetime_format_string )
	lib.MINUTES = function( date, format, leading_zeros ){
		var tmp = _processArguments.apply( null, arguments );
        date 			= tmp[ 'date' ];
        format 			= tmp[ 'format' ];
        leading_zeros 	= tmp[ 'leading_zeros' ];

		var d = _getDateObj( date, format ), r = false;
		if ( d.valid() ) {
			r = d.getMinutes();
			if ( leading_zeros ) r = _leadingZeros( r );
		}
		return r;
	};

	// SECONDS( datetime_string, datetime_format_string )
	lib.SECONDS = function( date, format, leading_zeros ){
		var tmp = _processArguments.apply( null, arguments );
        date 			= tmp[ 'date' ];
        format 			= tmp[ 'format' ];
        leading_zeros 	= tmp[ 'leading_zeros' ];

		var d = _getDateObj( date, format ), r = false;
		if ( d.valid() ) {
			r = d.getSeconds();
			if ( leading_zeros ) r = _leadingZeros( r );
		}
		return r;
	};

	// NOW() Return a datetime object
	lib.NOW = function(){
		return _getDateObj();
	};

	// TODAY() Return a datetime object limited to date only
	lib.TODAY = function(){
		var d = _getDateObj();
		d.setHours( 0 );
		d.setMinutes( 0 );
		d.setSeconds( 0 );
		return d;
	};

	lib.EOMONTH = function(d,n){
		n = (n || 0) + 1;
		var d1 = _getDateObj(d);
		d1.setDate(1);
		d1.setMonth(d1.getMonth()+n);
		d1.setDate(d1.getDate()-1);
		return d1;
	};

	/*
	* DATEDIFF( datetime_string, datetime_string, return_format)
	*
	* return_format:
	* d  - number of days, and remaining hours, minutes and seconds
	* m  - number of months, and remaining days, hours, minutes and seconds
	* y  - number of years, and remaining months, days, hours, minutes and seconds
	*
	* the function return an object with attributes: years, months and days depending of return_format argument
	*/
	lib.DATEDIFF = function( date_one, date_two, date_format, return_format ){
		var d1 = _getDateObj( date_one,  date_format ),
			d2 = _getDateObj( date_two, date_format ),
			diff,
			r  = {
				'years' 	: -1,
				'months'	: -1,
				'days'  	: -1,
				'hours' 	: -1,
				'minutes' 	: -1,
				'seconds'	: -1
			};

		if( d1.valid() && d2.valid() ){
			if( d1.valueOf() > d2.valueOf() ){
				d2 = _getDateObj( date_one, date_format );
				d1 = _getDateObj( date_two, date_format );
			}

			diff = d2.valueOf() - d1.valueOf();
			if( typeof return_format == 'undefined' || return_format == 'd' ){
				r.days = Math.floor( diff/86400000 );
			} else if(
				/[m,y]/i.test( return_format )
			) {
				var months,
					days,
					tmp;

				months = (d2.getFullYear() - d1.getFullYear()) * 12;
				months -= d1.getMonth() + 1;
				months += d2.getMonth() + 1;
				days = d2.getDate() - d1.getDate();

				if( days < 0 ){
					months--;
					tmp = new Date( d2.getFullYear(), d2.getMonth() );
					tmp.setDate(tmp.getDate()-1);
					tmp.setDate(d1.getDate());
					if ( d1.getDate() != tmp.getDate() ){
						tmp = new Date( d2.getFullYear(), d2.getMonth() );
						tmp.setDate(tmp.getDate()-1);
					}
					days = Math.abs( d2.valueOf() - tmp.valueOf() ) / (24*60*60*1000);
				}

				r.months = months;
				r.days = Math.floor(days);

				if( /y/i.test( return_format ) ){
					r.years = Math.floor( months/12 );
					r.months = months % 12;
				}
			}
			if( /h/i.test( return_format ) ) r.hours = Math.floor( diff/3600000 );
			else r.hours = Math.floor( diff%86400000/3600000 );

			if( /i/i.test( return_format ) ) r.minutes = Math.floor( diff/60000 );
			else r.minutes = Math.floor( diff%86400000%3600000/60000 );

			if( /s/i.test( return_format ) ) r.seconds = Math.floor( diff/1000);
			else r.seconds = Math.floor( diff%86400000%3600000%60000/1000);
		}
		return r;
	};

	if ( typeof NETWORKDAYS == 'undefined' ) {
		lib.NETWORKDAYS = lib.NETWORKDAYS = function( start_date, end_date, date_format, holidays, holidays_format ) {
			var tmp,
				result = 0,
				min_date_tmp, max_date_tmp;

			date_format = date_format || 'mm/dd/yyyy';
			holidays = holidays || [];
			holidays_format = holidays_format || date_format;
			start_date = DATEOBJ(start_date, date_format);
			end_date = DATEOBJ(end_date, date_format);

			min_date_tmp = Math.min(start_date, end_date);
			max_date_tmp = Math.max(start_date, end_date);

			start_date = new Date(min_date_tmp);
			end_date = new Date(max_date_tmp);

			if( ! Array.isArray(holidays) ) holidays = [holidays];

			for( var i = 0, h = holidays.length; i < h; i++ ) {
				holidays[i] = GETDATETIMESTRING( DATEOBJ( holidays[i], holidays_format), 'yyyy-mm-dd');
			}

			while( start_date <= end_date ) {
				tmp = start_date.getDay();
				if(0 != tmp && 6 != tmp) {
					tmp = GETDATETIMESTRING(start_date, 'yyyy-mm-dd');
					if( holidays.indexOf( tmp ) == -1 ) result++;
				}
				start_date.setDate(start_date.getDate()+1);
			}
			return result;
		};
	}

	/*
	* DATETIMESUM( datetime_string, format, number, to_increase, ignore_weekend )
	* to_increase:
	* s  - seconds
    * i  - minutes
	* h  - hours
	* d  - add the number of days,
	* m  - add the number of months,
	* y  - add the number of years
	*
	*/
	lib.DATETIMESUM = function( date, format, number, to_increase, ignore_weekend){
		var d = _getDateObj( date, format );
        ignore_weekend = ignore_weekend || false;
		if( d.valid() ){
			if( typeof number != 'number' && isNaN( parseFloat( number ) ) ) number = 0;
			else number = parseFloat( number );

			if( typeof to_increase == 'undefined' ) to_increase = 'd';


			if( /y+/i.test( to_increase ) ) 	 d.setFullYear( d.getFullYear() + number );
			else if( /d+/i.test( to_increase ) ){
              if(ignore_weekend)
              {
                  var n  = number < 0 ? Math.ceil(number) : Math.floor(number),
                      s  = number < 0 ? -1 : 1;
                  while(n)
                  {
                      d.setDate(d.getDate()+s);
                      if(0 < d.getDay() && d.getDay() < 6) n -= s;
                  }
              }
              else d.setDate( d.getDate() + number );
            }
			else if( /m+/i.test( to_increase ) ){
                var tmp = DAY(d)
                d.setDate(1);
                d.setMonth( d.getMonth() + number );
                d = EOMONTH(d);
                d.setDate(MIN(tmp,DAY(d)));
            }
			else if( /h+/i.test( to_increase ) ) d.setHours( d.getHours() + number );
			else if( /i+/i.test( to_increase ) ) d.setMinutes( d.getMinutes() + number );
			else d.setSeconds( d.getSeconds() + number );

			return d;
		}
		return false;
	};

    lib.DECIMALTOTIME = lib.decimaltotime = function(value, from_format, to_format){
        function complete(v, f)
        {
            if(1<f[0].length && v<10) v = '0'+v;
            return v;
        };

        from_format = from_format.toLowerCase();

        var y = /\by+\b/i.exec(to_format),
            m = /\bm+\b/i.exec(to_format),
            d = /\bd+\b/i.exec(to_format),
            h = /\bh+\b/i.exec(to_format),
            i = /\bi+\b/i.exec(to_format),
            s = /\bs+\b/i.exec(to_format),
            factor = 1,
            components = {};

        switch(from_format)
        {
            case 'y': factor = 365*24*60*60; break;
            case 'm': factor = 30*24*60*60; break;
            case 'd': factor = 24*60*60; break;
            case 'h': factor = 60*60; break;
            case 'i': factor = 60; break;
        }

        value *= factor;

        if(y){ components['y'] = FLOOR(value/(365*24*60*60)); value = value%(365*24*60*60);}
        if(m){ components['m'] = complete(FLOOR(value/(30*24*60*60)), m); value = value%(30*24*60*60);}
        if(d){ components['d'] = complete(FLOOR(value/(24*60*60)), d); value = value%(24*60*60);}
        if(h){ components['h'] = complete(FLOOR(value/(60*60)), h); value = value%(60*60);}
        if(i){ components['i'] = complete(FLOOR(value/60), i); value = value%60;}
        if(s){ components['s'] = complete(FLOOR(value), s);}

        for(var index in components)
        {
            to_format = to_format.replace(new RegExp('\\b'+index+'+\\b', 'i'), components[index]);
        }

        return to_format;
    };

	lib.TIMETODECIMAL = lib.timetodecimal = function(value, from_format, to_format){
        from_format = from_format.replace(/[^ymdhisa\:\s]/ig, '')
                    .replace(/^[\s\:]+/, '')
                    .replace(/[\s\:]+$/, '')
                    .replace(/[\s\:]+/g, ' ');

        value = (value+'').replace(/^[\s\:]+/, '')
                    .replace(/[\s\:]+$/, '')
                    .replace(/[\s\:]+/g, ' ');

        to_format = to_format.toLowerCase();

        var value_components = value.split(/\s+/g),
            from_components  = from_format.split(/\s+/g),
            factor = 1,
            result = 0,
			last_index = from_components.length - 1;

        for(var j in from_components)
        {
			if( ! ( j in value_components ) ) continue;

            if(/y/i.test(from_components[j])) factor = 365*24*60*60;
            else if(/m/i.test(from_components[j])) factor = 30*24*60*60;
            else if(/d/i.test(from_components[j])) factor = 24*60*60;
            else if(/h/i.test(from_components[j])) {
				factor = 60*60;
				if( last_index in value_components ) {
					let a = ( value_components[ last_index ]+'' ).toLowerCase();
					if ( a == 'pm' && value_components[j]*1 <= 12 ) {
						value_components[j] = value_components[j]*1 + 12;
					} else if ( a == 'am' && value_components[j]*1 == 12 ) {
						value_components[j] = value_components[j]*1 - 12;
					}
				}
			}
            else if(/i/i.test(from_components[j])) factor = 60;
            else if(/s/i.test(from_components[j])) factor = 1;
			else continue;
            result += value_components[j]*factor;
        }

        switch(to_format)
        {
            case 'y': factor = 365*24*60*60; break;
            case 'm': factor = 30*24*60*60; break;
            case 'd': factor = 24*60*60; break;
            case 'h': factor = 60*60; break;
            case 'i': factor = 60; break;
            case 's': factor = 1; break;
        }

        return result/factor;
    };

	// GETDATETIMESTRING( date_object, return_format ) Return the date object as a string representation determined by the return_format argument
	lib.GETDATETIMESTRING = function( date, format ){
	  if( typeof format == 'undefined' ) format = default_format;

	  if( date.valid() ){
		var m = date.getMonth() + 1,
			d = date.getDate(),
			h = date.getHours(),
			i = date.getMinutes(),
			s = date.getSeconds(),
			a = ( h >= 12 ) ? 'pm' : 'am';

		m = ( m < 10 ) ? '0'+m : m;
		d = ( d < 10 ) ? '0'+d : d;
		if( /a+/.test( format ) ){
			h = h % 12;
			h = ( h ) ? h : 12;
		}
		h = ( h < 10 ) ? '0'+h : h;
		i = ( i < 10 ) ? '0'+i : i;
		s = ( s < 10 ) ? '0'+s : s;

		return format.replace( /\by{2}\b/i, date.getFullYear()%100 )
                     .replace( /y+/i, date.getFullYear() )
					 .replace( /m+/i, m )
					 .replace( /d+/i, d )
					 .replace( /h+/i, h )
					 .replace( /i+/i, i )
					 .replace( /s+/i, s )
					 .replace( /a+/i, a );
	  }
	  return date;
	};

	root.CF_DATETIME = lib;

})(this);