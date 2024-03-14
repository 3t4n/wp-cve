/* 
 * jQuery - Conditioner - v1.0
 * http://www.aakashweb.com/
 * Copyright 2015, Aakash Chakravarthy
 * Released under the MIT License.
 */
 
;(function ( $ ) {
	
	$.fn.conditioner = function( options ){
		
		var opts = $.extend({
			events: '',
			conditions: [],
			onTrue: '',
			onFalse: '',
		}, options );
		
		function prepareConds( $ele ){
			
			$inputs = false;
			opts = $ele.data( 'conditioner-opts' );
			conds = [];
			
			// Inline options
			if( typeof $ele.data( 'condr-input' ) !== 'undefined' ){
			
				// data-condr-input="(closest::.wrap)(find::.myele)"
				inputAttr = $ele.data( 'condr-input' );
				
				$inputEle = $ele;
				if( inputAttr.indexOf( '(' ) != -1 ){ // Input selection is advanced as it has braces
				
					// Loop through the selectors and get the jQuery obj of input
					$.each( inputAttr.match( /\(.+?\)/g ), function( index, method ){
						method = method.replace( /[()]/g, '' );
						methodSplit = method.split( '::' );
						$inputEle = $inputEle[ methodSplit[0] ]( methodSplit[1] );
					});

				}else{
					$inputEle = $( inputAttr );
				}
				
				if( typeof $ele.data( 'condr-action' ) !== 'undefined' ){
					
					conds.push({
						input: $inputEle,
						value: $ele.data( 'condr-value' ),
						type: $ele.data( 'condr-action' ).split('?')[0]
					});
					
					// Add input to list
					$inputs = ( $inputs == false ) ? $inputEle : $inputs.add( $inputEle );
				}

			}
			
			// External options
			if( opts.conditions.length > 0 ){
				$.each( opts.conditions, function( index, cond ){
					
					if( cond.input != '' && typeof cond.input === 'function' ){
						$indInput = cond.input.call( $ele );
					}else if( typeof cond.input === 'string' ){
						$indInput = $( cond.input );
					}else{
						return true;
					}
					
					conds.push({
						input: $indInput,
						value: ( typeof cond.value === 'function' ) ? cond.value.call( $ele ) : cond.value,
						type: cond.type
					});
					
					$inputs = ( $inputs == false ) ? $indInput : $inputs.add( $indInput );
					
				});
			}
			
			// Attach the array which has all the conditions, value and type to the element 
			$ele.data( 'conditioner-conds', conds );
			
			return $inputs;
		}
		
		function checkCondition( $ele ){
			
			conds = $ele.data( 'conditioner-conds' );
			finalCheck = null;
			
			// Loop through each condition
			$.each( conds, function( index, cond ){
				indCheck = checkValue( cond );
				finalCheck = ( finalCheck == null ) ? indCheck : ( finalCheck && indCheck );
			});
			
			// If inline options are set
			if( typeof $ele.data( 'condr-action' ) !== 'undefined' ){

				condrAction = $ele.data( 'condr-action' );
				actionSplit = condrAction.split( '?' );
				actions = actionSplit[1].split( ':' );
				
				if( finalCheck ){
					$ele[ actions[0] ]();
				}else{
					$ele[ actions[1] ]();
				}
				
				return true;
			}
			
			// If external options are set
			if( opts.onTrue != '' && opts.onFalse != '' ){
			
				if( finalCheck ){
					if( typeof opts.onTrue === 'function' )
						opts.onTrue.call( $ele );
				}else{
					if( typeof opts.onFalse === 'function' )
						opts.onFalse.call( $ele );
				}
				
				return true;
				
			}
			
		}
		
		function checkValue( cond ){
			
			var inputVal = '';
			var checkPassed = false;
			
			if( cond.input.attr( 'type' ) == 'radio'){
				inputVal = cond.input.filter(':checked').val();
			}else if( cond.input.attr( 'type' ) == 'checkbox' ){
				if( cond.input.is( ':checked' ) ) return true;
				else return false;
			}else{
				inputVal = cond.input.val();
			}
			
			if( typeof cond.type === 'undefined' ){
				cond.type = 'simple';
			}
			
			// Check didn't pass so try others
			if( typeof cond.value !== 'undefined' && typeof inputVal !== 'undefined' ){
				if( cond.type == 'pattern' ){
					checkPassed = ( inputVal.match( new RegExp( cond.value, 'g' ) ) !== null ) ? true : false;
				}else if( cond.type == 'simple' ){
					checkPassed = ( inputVal == cond.value ) ? true : false;
				}
			}
			
			return checkPassed;
			
		}

		return this.each(function(){
	
			$ele = $(this).data( 'conditioner-opts', opts );
			$inputs = prepareConds( $ele );
			events = ( typeof $ele.data( 'condr-events' ) !== 'undefined' ) ? $ele.data( 'condr-events' ) : opts.events;
			//console.log( $inputs );
			if( $inputs.length > 0 && events != '' ){
				
				if( typeof $inputs.data( 'conditioner-ele' ) === 'undefined' ){
					$inputs.data( 'conditioner-ele', $ele );
				}else{
					$eles = $inputs.data( 'conditioner-ele' ).add( $ele );
					$inputs.data( 'conditioner-ele', $eles );
				}
				
				$inputs.on( events, function(){
					$(this).data( 'conditioner-ele' ).each(function(){
						checkCondition( $(this) );
					});
				});
				
				// Check the conditions once
				checkCondition( $ele );
			}
			
		});
		
	}
	
})( jQuery );