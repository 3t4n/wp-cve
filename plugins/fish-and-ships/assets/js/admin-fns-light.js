/**
 * Lightweight JS without UI dependencies for wizard, rate, fns-news & messages. 
 * It will be loaded in the whole admin side, and is appart for performance reasons
 *
 * @package Fish and Ships
 * @version 1.5
 */

jQuery(document).ready(function($) {

	// If we have some notice to show, do scroll up if needed
	$(window).on('load', function() {
		if( $('div.wc-fns-wizard.must').length > 0 && $(window).scrollTop() > 0 )
		{
			$('html, body').animate({scrollTop:0}, 700);
		}
	});
	
	// There is a wizard message hidden by WC Admin JS?
	if ( $('.woocommerce-layout__notice-list-hide').length != 0 && $('div.wc-fns-wizard.must').length != 0 ) {

		if ( $('div.wrap.woocommerce').length != 0 ) {

			// Let's move the message to the visible wrapper	
			setTimeout(function () {
				$('div.wrap.woocommerce').prepend($('div.wc-fns-wizard.must'));
			}, 1);

		} else if ( $('div.woocommerce-layout__main').length != 0 ) {

			// Let's move the message to the visible wrapper	
			setTimeout(function () {
				$('div.woocommerce-layout__main').prepend($('div.wc-fns-wizard.must'));
			}, 1);

		} else {
			// Fallback, old style
			$('body').addClass('show_wc_fns_wizard');
			
		}
		// New releases can duplicate the wizard!
		setTimeout(function () {
			if ($('div.wc-fns-wizard.must').length > 1) {
				$('div.wc-fns-wizard.must').not(":last").remove();
			}
		}, 100);
	}
	
	// Show videos in welcome wizard
	$(document).on('click','.fns-show-videos', function() 
	{
		$('.fns-hidden-videos').slideToggle();
		return false;
	});
	
	// Ajax for wizard / star / news buttons
	$(document).on('click', 'div.wc-fns-wizard a, div.wc-fns-news a', function () {

		var html_link     = $(this).attr('href');
		var cont_wizard   = $(this).hasClass('wc-fns-continue-wizard');
		var return_value  = true; // by default, follow the link
		
		if( cont_wizard ) 
			return_value = false;

		// Ajax action (thanks-dismiss or later)
		if (typeof $(this).attr('data-kind') !== "undefined" )
		{
			    return_value  = $(this).attr('target') === "_blank";
			
			var kind 		  = $(this).attr('data-kind');
			var key 		  = $(this).attr('data-key');
			var param 		  = $(this).attr('data-param');

			$.ajax({
				url: ajaxurl,
				data: { action: 'wc_fns_wizard', kind: kind, key: key, param: param },
				error: function (xhr, status, error) {
					var errorMessage = xhr.status + ': ' + xhr.statusText
					console.log('Fish n Ships, AJAX error - ' + errorMessage);
					// fail? follow the link
					if ( html_link != '#') location.href = html_link;
				},
				success: function (data) {
					if (data != '1') 
					{
						console.log('Fish n Ships, AJAX error - ' + data);
						// fail? follow the link
						if ( html_link != '#') location.href = html_link;
					}
				},
				dataType: 'html'
			});
		}
		else
		{
			if( ! cont_wizard )
				return true;
		}
		
		jQuery(this).closest('div.wc-fns-wizard, div.wc-fns-news').slideUp(function () {
			jQuery(this).closest('div.wc-fns-wizard, div.wc-fns-news').remove();
			
			$('#activity-panel-tab-restart-fns').css('display','flex');

			if( cont_wizard ) 
				setTimeout( function() { maybe_open_pointer(); }, 100 );
		});
		
		return return_value;
		//return false;
	});
	
	// Pointers. We will open it only if there isn't any wizard shown
	setTimeout(function () {
		if( $('.wc-fns-wizard').length == 0 )
			maybe_open_pointer();
	}, 20 );
	
	function maybe_open_pointer()
	{
		if (typeof fish_and_ships_pointers !== 'undefined')
		{			
			$(fish_and_ships_pointers).each( function( idx, pointer ) 
			{
				for( var key in pointer )
				{
					if( pointer[key].auto_open ) 
					{
						open_pointer( key, pointer[key] );
						position_pointers();
						return false; // break each loop
					}
				}
			});
		}		
	}
	
	function dismiss_pointer( key ) 
	{
		var extra_action = '';
		
		if( fish_and_ships_pointers.hasOwnProperty(key) && fish_and_ships_pointers[key].hasOwnProperty('extra_action') )
		{
			extra_action = fish_and_ships_pointers[key]['extra_action'];
		}

		wizard_ajax_send( 'pointer', key, '', extra_action );
		/*
		$.ajax({
			url: ajaxurl,
			data: { action: 'wc_fns_wizard', kind: 'pointer', key: key },
			error: function (xhr, status, error) {
				var errorMessage = xhr.status + ': ' + xhr.statusText
				console.log('Fish n Ships, AJAX error - ' + errorMessage);
			},
			success: function (data) {
				if (data != '1') console.log('Fish n Ships, AJAX error - ' + data);
			},
			dataType: 'html'
		});
		*/
	}

	function remove_pointer( key )
	{
		if( fish_and_ships_pointers.hasOwnProperty(key) )
		{
			$('.fns-pointed[data-fns-key="'+key+'"]')
				.removeClass('fns-pointed')
				.removeAttr('data-fns-key')
				.removeAttr('data-fns-offset');
				
			delete fish_and_ships_pointers[key];
		}
	}
	
	function wizard_ajax_send( kind, key, param, extra_action )
	{
		$.ajax({
			url: ajaxurl,
			data: { action: 'wc_fns_wizard', kind: kind, key: key, param: param },
			error: function (xhr, status, error) {
				var errorMessage = xhr.status + ': ' + xhr.statusText
				console.log('Fish n Ships, AJAX error - ' + errorMessage);
				do_extra_action( extra_action );
			},
			success: function (data) {
				if (data != '1') 
					console.log('Fish n Ships, AJAX error - ' + data);
				do_extra_action( extra_action );
			},
			dataType: 'html'
		});
	}
	
	function do_extra_action( action )
	{
		switch( action )
		{
			case 'wizard-end':
				
				wizard_ajax_send( 'wizard', '', 'off', '' ); // Caution: another extra_action will do an infinite loop!
				/*
				$.ajax({
					url: ajaxurl,
					data: { action: 'wc_fns_wizard', kind: 'wizard', key: '', param: 'off' },
					error: function (xhr, status, error) {
						var errorMessage = xhr.status + ': ' + xhr.statusText
						console.log('Fish n Ships, AJAX error - ' + errorMessage);
					},
					success: function (data) {
						if (data != '1') 
							console.log('Fish n Ships, AJAX error - ' + data);
					},
					dataType: 'html'
				});
				*/
				break;
		}
	}
	
	function open_pointer( key, pointer )
	{
		if( Array.isArray( pointer.anchor ) )
		{
			$(pointer.anchor).each( function( idx, el ) {
				anchors = $(el);
				if( anchors.length > 0 && $(anchors[0]).is(":visible") ) 
					return false; // break loop, add pointer here
			});
		}
		else
		{
			anchors = $(pointer.anchor);
			if( anchors.length < 1) 
				return; // no element to add pointer
		}
		
		$(anchors[0]).pointer(
		{
			content: '<h3>' + pointer.title + '</h3>' + pointer.content,
			position: {
				edge:  pointer.edge,  // top | right | bottom | left
				align: pointer.align, // top | middle | bottom
			},
			close: function() {
				dismiss_pointer(key);
				remove_pointer(key);
				maybe_open_pointer(); // next?
			},
			opened: function() {

				var this_pointer = $('.wp-pointer:last');
				$(this_pointer).addClass( pointer.wrapper_class ).attr( 'data-fns-key', key) ;
				
				// Custom close button?
				if( typeof pointer.close_bt !== 'undefined')
				{
					$('.wp-pointer-buttons .close', this_pointer).addClass('custom').html( pointer.close_bt );
				}
				
				// Scroll to pointer
				var elOffset = this_pointer.offset().top;
				var elHeight = this_pointer.height();
				var windowHeight = $(window).height();

				var offset = elOffset - ((windowHeight / 2) - (elHeight / 2));
				$('html, body').animate({scrollTop:offset}, 700);
				
				// Save vertical offset with hash element
				var hashOffset = $(anchors[0]).offset().top;
				$(anchors[0])
					.addClass('fns-pointed')
					.attr('data-fns-key', key)
					.attr('data-fns-offset', Math.round( elOffset - hashOffset ) );
			},
		}).pointer('open');
	}
	
	
	// Move pointers if the position of the pointed element changes (messages on top, etc)
	var position_loop = null;
	
	function position_pointers() {
		
		// Prevent double position loop schedule
		if( position_loop !== null )
		{
			clearTimeout( position_loop );
			position_loop = null;
		}
		var some_pointer = false;
		var speed_loop   = 250;
		$('.fns-pointed').each( function(idx, pointed)
		{
			var top = $(pointed).offset().top;
			var key = $(pointed).attr('data-fns-key');
			var offset  = parseInt($(pointed).attr('data-fns-offset'), 10);
			var pointer = $('.wp-pointer[data-fns-key="'+key+'"]');
			
			if( $(pointer).hasClass('wp-pointer-top') ) offset += 10;
			var new_pos = Math.round( top + offset );
			if ( parseInt( $(pointer).css('top'), 10 ) != new_pos ) speed_loop = 25;
			
			$(pointer).css('top', new_pos);
			some_pointer = true;
		});
		if( some_pointer ) 
		{
			position_loop = setTimeout( position_pointers, speed_loop );
		}
	}
});

