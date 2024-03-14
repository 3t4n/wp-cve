/* jshint asi: true */
jQuery(document).ready( function($) {
	
	//CHECK FOR ANY 'ACTIVE' OPTINS ( BARS AND SLIDE INS ) THAT SHOULD ALREADY BE SHOWING
	function check_for_active_popups() {
		var activeOptins =  JSON.parse( get_cookie( 'fca_eoi_active_optins' ) )
		if ( activeOptins.length > 0 ) {
			for ( var id in activeOptins ) {
				show_banner( activeOptins[id], true )
			}
		}
	}
	
	if ( typeof(fcaEoiTargetingData) !== 'undefined' ) {
		for ( var id in fcaEoiTargetingData.popups ) {
			
			//TEST COOKIES (SUCCESS, FREQUENCY, PAGECOUNT
			var successOK = test_success( id, fcaEoiTargetingData.popups[id] )
			var frequencyOK = test_frequency( id, fcaEoiTargetingData.popups[id].show_every )
			var pageviewsOK = test_pageviews( fcaEoiTargetingData.popups[id] )
			
			if ( frequencyOK && pageviewsOK && successOK ) {
				var waitTime = parse_time( fcaEoiTargetingData.popups[id] )
				var scrollPercent = parse_scroll( fcaEoiTargetingData.popups[id] )
				var exitIntervention = parse_exit_intervention( fcaEoiTargetingData.popups[id] )
				
				popup_handler( id, waitTime, scrollPercent, exitIntervention )
				
			} 
		}
	}
	
	if ( typeof(fcaEoiBannerTargetingData) !== 'undefined' ) {
		for ( var id in fcaEoiBannerTargetingData.banners ) {

			//TEST COOKIES (SUCCESS, FREQUENCY, PAGECOUNT
			var successOK = test_success( id, fcaEoiBannerTargetingData.banners[id] )
			var frequencyOK = test_frequency( id, fcaEoiBannerTargetingData.banners[id].show_every )
			var pageviewsOK = test_pageviews( fcaEoiBannerTargetingData.banners[id] )

			if ( frequencyOK && pageviewsOK && successOK ) {
				var waitTime = parse_time( fcaEoiBannerTargetingData.banners[id] )
				var scrollPercent = parse_scroll( fcaEoiBannerTargetingData.banners[id] )
				var exitIntervention = parse_exit_intervention( fcaEoiBannerTargetingData.banners[id] )
				
				banner_handler( id, waitTime, scrollPercent, exitIntervention )
				
			} else {
				$('#fca_eoi_form_' + id)[0].remove()
			}
		}
	}
	
	function banner_handler( id, waitTime, scrollPercent, exitIntervention ) {
		window.setTimeout( function(){
			$( window ).scroll( function() {
				if ( scrollPercent <= scrolled_percent() ) {
					if ( exitIntervention ) {
						$(document).on('mouseleave', function(e) {
							if( e.clientY < 0 ) {
								show_banner( id, false )
							}
						})
					} else {
						show_banner( id, false )	
					}
				}
			}).scroll()
		}, waitTime * 1000 )
	}

	function popup_handler( id, waitTime, scrollPercent, exitIntervention ) {
		window.setTimeout( function(){
			$( window ).scroll( function() {
				if ( scrollPercent <= scrolled_percent() ) {
					if ( exitIntervention ) {
						$(document).on('mouseleave', function(e) {
							if( e.clientY < 0 ) {
								show_lightbox( id )
							}
						})
					} else {
						show_lightbox( id )	
					}
				}
			}).scroll()
		}, waitTime * 1000 )
	}
		
	function test_success( id, popup ) {
		if ( popup.hasOwnProperty( 'success_duration' ) ) {
			if ( get_cookie( 'fca_eoi_success_' + id ) ) {
				return false
			}
		}
		return true
	}
	
	function test_pageviews( popup ) {
		var condition = popup.conditions.filter( function( value ){
			return value.parameter === 'pageviews'
		})

		if ( condition.length === 0 ){
			return true
		} else {
			var sessionViews = parseInt( get_cookie( 'fca_eoi_pagecount' ) )
			
			return sessionViews >= condition[0].value		
		}	
		
	}

	function test_frequency( id, frequency ) {

		var lastShown = get_cookie( 'fca_eoi_frequency_' + id ) 

		if ( !lastShown ) {
			return true	
		}

		var difference = Math.floor(Date.now() / 1000) - lastShown
				
		switch( frequency ) {
			case 'always':
				return true
					
			case 'session':
				return false
								
			case 'day':
				return difference >= 86400
								
			case 'once':
				return false
								
			case 'month':
				return difference >= 2592000
							
			default: 
				return true
		}
		
	}
	
	var fca_eoi_popups_shown = []
	function show_lightbox( id ) {
		if ( fca_eoi_popups_shown.indexOf( id ) === -1 && id > 0 ) {
			fca_eoi_popups_shown.push( id )
			add_impression( id, fcaEoiTargetingData )
			var $lightbox =  $( '#fca_eoi_lightbox_' + id )
			$.featherlight( $lightbox, { variant: 'fca_eoi_featherlight',  closeOnClick: false, afterOpen: function(){
				var $instance = this.$instance

				//SET TABINDEX TO 0
				$('.fca_eoi_form_input_element').attr('tabindex', 0)
				$('.fca_eoi_form_button_element').attr('tabindex', 0)
				$('.fca_eoi_gdpr_consent').attr('tabindex', 0)
				
				//BACKWARDS COMPATIBILITY CODE - REMOVE OLD HACK CLOSE BUTTON
				$instance.find('.fca_eoi_layout_popup_close').hide()
				
				var cookieDuration = fcaEoiTargetingData.popups[id].show_every === 'session' ? 0 : 365
				set_cookie( 'fca_eoi_frequency_' + id, Math.floor(Date.now() / 1000), cookieDuration )
				if ( $lightbox.find('.fca_eoi_layout_popup').hasClass('animated') ) {
					setTimeout( function(){
						$instance.find('span.featherlight-close-icon.featherlight-close').show()
						$instance.find('.fca_eoi_form_input_element:visible').first().focus()
					}, 1000)
				} else {
					$instance.find('span.featherlight-close-icon.featherlight-close').show()
					$instance.find('.fca_eoi_form_input_element:visible').first().focus()
				}
			}})
		}
	}
	
	var fca_eoi_banners_shown = []
	function show_banner( id, disableAnim ) {
		if ( fca_eoi_banners_shown.indexOf( id ) === -1 && typeof fcaEoiBannerTargetingData !== 'undefined' ) {
			
			if ( typeof fcaEoiBannerTargetingData.banners[id] !== 'undefined' ) {
				fca_eoi_banners_shown.push( id )
				add_impression( id, fcaEoiBannerTargetingData )
				if ( disableAnim ) {
					$( '#fca_eoi_banner_' + id ).find('form').removeClass('animated')
				}
				$( '#fca_eoi_banner_' + id ).show().load( maybe_push_page( id ) )
				
				$( '#fca_eoi_banner_' + id ).find('.fca_eoi_form_input_element:visible').first().focus()
				var cookieDuration = fcaEoiBannerTargetingData.banners[id].show_every === 'session' ? 0 : 365
				set_active_optins ( id )
				set_cookie( 'fca_eoi_frequency_' + id, Math.floor(Date.now() / 1000), cookieDuration )
			}
		}
	}
	
	function maybe_push_page ( id ) {
		var pushPage = $( '#fca_eoi_banner_' + id ).find('form').data('fca_eoi_push_page')
		
		if ( pushPage === 'down' ) {
			$('body').css('margin-top', $( '#fca_eoi_banner_' + id ).find('form').outerHeight() )
		}
		
		if ( pushPage === 'up' ) {
			$('body').css('margin-bottom', $( '#fca_eoi_banner_' + id ).find('form').outerHeight() )
		}
	}

	//MAKE THE CLOSE BUTTONS FOR BANNERS & SCROLLBOXES REMOVE THE COOKIE FOR IT
	$('.fca_eoi_banner_close_btn, .fca_eoi_scrollbox_close_btn').click(function(){
		
		var thisFormId = $(this).siblings( '#fca_eoi_form_id' ).val()
		var activeOptins = JSON.parse( get_cookie( 'fca_eoi_active_optins' ) )
		
		if ( !Array.isArray( activeOptins ) ) {
			activeOptins = []
		}
		var index = activeOptins.indexOf( thisFormId )
		
		if ( index !== -1 ) {
			activeOptins.splice( index, 1 )
		}
		set_cookie ( 'fca_eoi_active_optins', JSON.stringify ( activeOptins ) )
		
	})
	
	function set_active_optins( id ) {
		var activeOptins = JSON.parse( get_cookie( 'fca_eoi_active_optins' ) )
		
		if ( !Array.isArray( activeOptins ) ) {
			activeOptins = []
		}
		
		var index = activeOptins.indexOf( id )
		
		if ( index === -1 ) {
			activeOptins.push( id )
			set_cookie ( 'fca_eoi_active_optins', JSON.stringify ( activeOptins ) )
		}
				
	}
	
	function add_impression( id, adminData ) {
		$.ajax({
			url: adminData.ajax_url,
			type: 'POST',
			data: {
				nonce: adminData.nonce,
				form_id: id,
				action: 'fca_eoi_activity'
			}
		})
	}
	
	function scrolled_percent() {
		var top = $( window ).scrollTop()
		var height = $( document ).height() - $( window ).height()
		if ( height == 0 ) {
			return 100
		}
		return 100 * ( top / height )
	}
		
	function parse_exit_intervention( popup ) {
		return popup.conditions.filter( function( value ){
			return value.parameter === 'exit_intervention'
		}).length === 1
	}


	function parse_scroll( popup ) {
		var condition = popup.conditions.filter( function( value ){
			return value.parameter === 'scrolled_percent'
		})

		if ( condition.length === 0 ){
			return 0
		} else {
			return condition[0].value		
		}	
		
	}
		
	function parse_time( popup ) {
		
		var condition = popup.conditions.filter( function( value ){
			return value.parameter === 'time_on_page'
		})

		if ( condition.length === 0 ){
			return 0
		} else {
			return condition[0].value		
		}
		
	}	

	function set_cookie( name, value, exdays ) {
		if ( exdays === 0 ) {
			document.cookie = name + "=" + value + "" + ";" + "path=/;"
		} else {
			var d = new Date()
			d.setTime( d.getTime() + ( exdays*24*60*60*1000 ) )
			document.cookie = name + "=" + value + "" + ";" + "path=/;" + "expires=" + d.toUTCString()
		}
	}

	function get_cookie( name ) {
		var value = "; " + document.cookie
		var parts = value.split( "; " + name + "=" )

		if ( parts.length === 2 ) {
			return parts.pop().split(";").shift()
		} else {
			return false
		}
	}

	check_for_active_popups()

})