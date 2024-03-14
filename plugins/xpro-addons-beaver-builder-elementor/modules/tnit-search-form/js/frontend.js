jQuery( document ).ready(
	function($) {
		"use strict";
		/* Search Outer Jquery Code*/
		if ($( '#tnit-trigger-btn' ).length) {
			$( '#tnit-trigger-btn' ).on(
				'click',
				function(event){
					$( '.tnit-search-animated-form' ).css(
						{
							width: '100%',
							opacity: '1',
							visibility: 'visible',
						}
					);;
					event.preventDefault();
					event.stopPropagation();
				}
			);
			$( document ).on(
				"click",
				function(event){
					var $trigger = $( ".tnit-search-animated-form" );
					if ($trigger !== event.target && ! $trigger.has( event.target ).length) {
						$( '.tnit-search-animated-form' ).css(
							{
								width: '0',
								opacity: '0',
								visibility: 'hidden',
							}
						);;
					}
				}
			);
		}

		/*
		Search Outer Open/Close*/
		if ($( '#trigger-tnit-search' ).length) {
			$( "#trigger-tnit-search" ).on(
				'click',
				function(e){
					$( '.tnit-search-outer' ).removeClass( 'open' )
					$( '.tnit-search-outer' ).addClass( 'open' );
				}
			);
			$( "#tnit-btn-close" ).on(
				'click',
				function(e){
					$( '.tnit-search-outer' ).removeClass( 'open' );
					e.preventDefault();
					e.stopPropagation();
				}
			);
		}
	}
);
