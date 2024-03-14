jQuery(
	function ($) {

		"use strict";

		$( document ).ready(
			function(){

				var industry = 'all',
				category     = 'all',
				version      = 'all',
				tags         = 'all';

				filterItems();

				$( window ).on(
					'scroll',
					function () {

						if ($( this ).scrollTop() > 230) {
							$( '#xpro-cloud-templates-inner' ).addClass( 'header-appear' );
						} else {
							$( '#xpro-cloud-templates-inner' ).removeClass( 'header-appear' );
						}

					}
				);

				$( 'ul.xpro-filter-links li a' ).on(
					'click',
					function(e){

						e.preventDefault();

						var tab_id = $( this ).attr( 'href' );

						$( 'ul.xpro-filter-links li a' ).removeClass( 'active' );
						$( '.xpro-dashboad-tab-content' ).removeClass( 'active' );

						$( this ).addClass( 'active' );
						$( tab_id ).addClass( 'active' );

						tags = 'all';

						$( '.xpro-demo-theme-tags .owl-item' ).siblings().find( 'span' ).removeClass( 'active' );
						$( '.xpro-demo-theme-tags .owl-item' ).find( '[data-tag-filter="all"]' ).addClass( 'active' );

						filterItems();

					}
				);

				$( '[data-popup-id]' ).on(
					'click',
					function (e) {

						e.preventDefault();

						let id = $( this ).attr( 'data-popup-id' );

						$( '.xpro-bb-popup-wrapper' ).removeClass( 'active' );

						$( '[data-popup-type=' + id + ']' ).addClass( 'active' );

					}
				);

				$( '.xpro-bb-popup-close-btn' ).on(
					'click',
					function (e) {

						e.preventDefault();

						$( '.xpro-bb-popup-wrapper' ).removeClass( 'active' );

					}
				);

				/* ===================================
				Tags Filter
				====================================== */

				$( ".xpro-demo-theme-tags" ).each(
					function() {
						var $this = $( this );
						$this.owlCarousel(
							{
								loop: false,
								autoWidth: true,
								nav: true,
								margin: 25,
								dots: false,
							}
						);
					}
				);

				$( ".xpro-demo-theme-tags .owl-item span" ).on(
					'click',
					function (){

						$( this ).parents( '.owl-item' ).siblings().find( 'span' ).removeClass( 'active' );
						$( this ).addClass( 'active' );

						tags = $( this ).data( 'tag-filter' );

						$( '.tnit-demo-search' ).val( '' );

						filterItems( 'tags' );

					}
				);

				$( '.xpro-header-toggle' ).on(
					'click',
					function () {

						$( '.xpro-demo-header-wrapper' ).toggleClass( 'active' );

					}
				);

				$( '.xpro-demo-industry > i' ).on(
					'click',
					function () {

						$( '.xpro-demo-industry' ).toggleClass( 'active' );

					}
				);

				$( document ).on(
					'click',
					'.xpro-demo-dropdown-list > li > input',
					function() {

						$( '.xpro-demo-dropdown-list > li' ).find( 'input[type="checkbox"]' ).prop( 'checked', false );

						$( this ).prop( 'checked', true );

						industry = $( this ).val();

						if (industry === 'all') {
							$( '.xpro-demo-industry-label' ).text( 'Industries' );
						} else {
							$( '.xpro-demo-industry-label' ).text( industry );
						}

						filterItems();
					}
				);

				$( document ).on(
					'click',
					'.xpro-demo-version-list > span > input',
					function() {

						$( '.xpro-demo-version-list > span' ).find( 'input[type="checkbox"]' ).prop( 'checked', false );

						$( this ).prop( 'checked', true );

						version = $( this ).val();

						filterItems();
					}
				);

				$( '.xpro-filter-template-search' ).on(
					'change',
					function(){

						let value = $( this ).val().replace( /[_\W]+/g, "" );

						if (value) {
							tags = value;
						} else {
							tags = 'all';
						}

						$( '.xpro-demo-theme-tags .owl-item' ).siblings().find( 'span' ).removeClass( 'active' );
						$( '.xpro-demo-theme-tags .owl-item' ).find( '[data-tag-filter="all"]' ).addClass( 'active' );

						filterItems( 'tags' );

					}
				);

				function filterItems($type = 'all'){

					var filterIndustry = '';
					var filterVersion  = '';
					var filterTags     = '';

					if (industry === 'all') {
						filterIndustry = '';
					} else {
						filterIndustry = '[class*=' + industry + ']';
					}

					if (version === 'all') {
						filterVersion = '';
					} else {
						filterVersion = '[class$=' + version + ']';
					}

					if (tags === 'all') {
						filterTags = '';
					} else {
						filterTags = '[class*=' + tags + ']';
					}

					$( '.xpro-dashboad-tab-content' ).each(
						function() {
							var $grid   = $( this ).find( '.xpro-templates-grid' ).isotope(
								{
									itemSelector: '.xpro-template-block',
									percentPosition: true,
									masonry: {
										columnWidth: '.xpro-template-block'
									},
									filter: filterIndustry + filterVersion + filterTags,
									visibleStyle: { transform: 'translateY(0)', opacity: 1 },
									hiddenStyle: { transform: 'translateY(100px)', opacity: 0 },
								}
							);
							let $iso    = $grid.data( 'isotope' );
							let $length = $iso.filteredItems.length;
							let $id     = $( this ).attr( 'id' );
							if ($type !== 'tags') {
								$( 'a[href*=' + $id + ']' ).find( '.xpro-count' ).text( $length );
							}
						}
					);

				}

				/* ===================================
				Preview Popup
				====================================== */

				var preview = null;

				/*
				* Open preview.
				*/
				function OpenPreview( $this, e ) {
					let name = $( $this ).parents( '.xpro-template-block' ).find( '.xpro-template-name' ).text();
					preview  = $( $this ).parents( '.xpro-template-block' ).find( '[data-src-preview]' ).data( 'src-preview' );

					if ( 'false' === preview ) {
						return;
					}

					// Remove current class from siblings items.
					$( $this ).parents( '.xpro-template-block' ).siblings().removeClass( 'xpro-preview-demo-item-open' );

					// Current item.
					$( $this ).parents( '.xpro-template-block' ).addClass( 'xpro-preview-demo-item-open' );

					// Prev Next Buttons.
					$( '.xpro-preview' ).find( '.xpro-preview-prev-demo, .xpro-preview-next-demo' ).removeClass( 'xpro-preview-inactive' );

					let prev = $( $this ).parents( '.xpro-template-block' ).prev().find( '[data-src-preview]' );

					if ( prev.length <= 0 ) {
						$( '.xpro-preview .xpro-preview-prev-demo' ).addClass( 'xpro-preview-inactive' );
					}

					let next = $( $this ).parents( '.xpro-template-block' ).next().find( '[data-src-preview]' );
					if ( next.length <= 0 ) {
						$( '.xpro-preview .xpro-preview-next-demo' ).addClass( 'xpro-preview-inactive' );
					}

					// Reset header info.
					$( '.xpro-preview .xpro-preview-header-info' ).html( '' );

					// Add name to info.
					if ( name ) {
						$( '.xpro-preview .xpro-preview-header-info' ).append( ` <div class="xpro-preview-demo-name"> ${name} </div> ` );
					}

					// Set url in iframe.
					$( '.xpro-preview .xpro-preview-iframe' ).attr( 'src', preview );

					// Body preview.
					$( 'body' ).addClass( 'xpro-preview-active' );
				}

				/*
				* Open preview demo.
				*/
				$( document ).on(
					'click',
					'[data-src-preview]',
					function( e ) {

						if ( ! $( e.target ).is( '.xpro-preview-demo-import-open' ) ) {
							OpenPreview( this, e );
							e.preventDefault();
						}
					}
				);

				/*
				* Open preview prev demo.
				*/
				$( document ).on(
					'click',
					'.xpro-preview-prev-demo',
					function( e ) {

						var prev = $( '.xpro-preview-demo-item-open' ).prev().find( '[data-src-preview]' );

						if ( prev.length > 0 ) {

							OpenPreview( prev, e );
						}

						e.preventDefault();
					}
				);

				/*
				* Open preview next demo.
				*/
				$( document ).on(
					'click',
					'.xpro-preview-next-demo',
					function( e ) {

						var next = $( '.xpro-preview-demo-item-open' ).next().find( '[data-src-preview]' );

						if ( next.length > 0 ) {

							OpenPreview( next, e );
						}

						e.preventDefault();
					}
				);

				/*
				* Close preview.
				*/
				$( document ).on(
					'click',
					'.xpro-preview-close',
					function( e ) {

						// Remove current class from items.
						$( '.xpro-template-block' ).removeClass( 'xpro-preview-demo-item-open' );

						// Remove preview from body.
						$( 'body' ).removeClass( 'xpro-preview-active' );

						// Remove url from iframe.
						$( '.xpro-preview .xpro-preview-iframe' ).removeAttr( 'src' );

						e.preventDefault();
					}
				);

				/*
				* Devices Toggle.
				*/
				$( document ).on(
					'click',
					'.xpro-preview-header-devices li:not(.active)',
					function( e ) {

						var device = $( this ).data( 'device' );

						$( this ).siblings().removeClass( 'active' );
						$( this ).addClass( 'active' );

						$( '.xpro-preview' ).removePrefixedClasses( 'xpro-device-' );

						$( '.xpro-preview' ).addClass( 'xpro-device-' + device );

						$( '.xpro-preview .xpro-preview-iframe' ).attr( 'src', preview );

						e.preventDefault();

					}
				);

				$.fn.removePrefixedClasses = function (prefix) {
					var classNames = $( this ).attr( 'class' ).split( ' ' ),
					className,
					newClassNames  = [],
					i;
					//loop class names
					for (i = 0; i < classNames.length; i++) {
						className = classNames[i];
						// if prefix not found at the beggining of class name
						if (className.indexOf( prefix ) !== 0) {
							newClassNames.push( className );
							continue;
						}
					}
					// write new list excluding filtered classNames
					$( this ).attr( 'class', newClassNames.join( ' ' ) );
				};

			}
		);

		/* ===============================
		Nav Welcome Dashboard
		=============================== */

		$( '.xpro-bb-tabs > li > a' ).on(
			'click',
			function (e) {

				e.preventDefault();

				$( this ).parent().siblings().removeClass( 'active' );
				$( this ).parent().addClass( 'active' );

				var tabID = $( this ).attr( 'href' );
				$( '.xpro-bb-tab-content' ).removeClass( 'active' );
				$( tabID ).addClass( 'active' );

			}
		);

		$( ".xpro-tab-toggle" ).click(
			function(){
				$( ".xpro-bb-tabs li" ).slideToggle();
			}
		);

		// Owl Carousel
		$( '.owl-carousel1' ).owlCarousel(
			{
				loop:true,
				stagePadding: 0,
				autoplayHoverPause:true,
				dots: false,
				nav: true,
				navText: [
				'<i class="xi xi-chevron-left" aria-hidden="true"></i>', '<i class="xi xi-chevron-right" aria-hidden="true"></i>'
				],
				responsive:{
					0:{
						items: 1,
						margin: 0,
					},
					600:{
						items: 1,
						margin: 0,
					},
					1300:{
						items: 1,
						margin: -30,
					},
					1400:{
						items: 2,
						margin: -30,
					}
				}
			}
		)

		// Owl Carousel
		$( '.owl-carousel2' ).owlCarousel(
			{
				items: 1,
				loop:true,
				// autoplay:true,
				// autoplayTimeout: 3000,
				autoplayHoverPause: true,
				dots: false,
				nav: true,
				navText: [
				'<i class="xi xi-chevron-left" aria-hidden="true"></i>', '<i class="xi xi-chevron-right" aria-hidden="true"></i>'
				],
				responsive:{
					0:{
						items:1,
					},
					600:{
						items:1,
					},
					1000:{
						items:1,
					}
				}
			}
		)

		/* ===============================
		Scroll Load Image
		=============================== */
		inView( '.xpro-template-block figure' ).on(
			'enter',
			function( figure ) {

				var img = figure.querySelector( 'img' ); // 1

				if (  'undefined' !== typeof img.dataset.src ) { // 2

					figure.classList.add( 'is-loading' ); // 3

					// 4
					let newImg = new Image();
					newImg.src = img.dataset.src;

					newImg.addEventListener(
						'load',
						function() {

							figure.innerHTML = ''; // 5
							figure.appendChild( this );

							// 6
							setTimeout(
								function() {
									figure.classList.remove( 'is-loading' );
									figure.classList.add( 'is-loaded' );
								},
								300
							);
						}
					);
				}
			}
		);

		/* ===============================
		Dashboard Ajax
		=============================== */

		$( "#xpro-dashboard-settings-form" ).on(
			"submit",
			function (e) {
				e.preventDefault();

				var data = $( this ).serialize();
				$( '.xpro-dashboard-save-button > i' ).addClass( 'xpro-spin' );

				$.post(
					$( this ).attr( 'action' ),
					data,
					function (e) {
						$( '.xpro-dashboard-save-button > i' ).removeClass( 'xpro-spin' );
						// location.reload();
					}
				);

			}
		);

		$( '.xpro-bb-content-type-pro-disabled' ).on(
			'click',
			function (e) {
				e.preventDefault();
				$( '.xpro-dashboard-popup-wrapper' ).addClass( 'active' );
			}
		);

		$( '.xpro-dashboard-popup-close-btn' ).on(
			'click',
			function (e) {
				e.preventDefault();
				$( '.xpro-dashboard-popup-wrapper' ).removeClass( 'active' );
			}
		);

		$( '#xpro-bb-dashboard-widget-control-input' ).change(
			function () {
				if ($( this ).is( ':checked' )) {
					$( ".xpro-bb-dashboard-tab-modules-content input:not(:disabled)" ).prop( 'checked', true );
				} else {
					$( ".xpro-bb-dashboard-tab-modules-content input:not(:disabled)" ).prop( 'checked', false );
				}
			}
		);

		$( '#xpro-dashboard-feature-control-input' ).change(
			function () {
				if ($( this ).is( ':checked' )) {
					$( ".xpro-bb-dashboard-tab-features-content input:not(:disabled)" ).prop( 'checked', true );
				} else {
					$( ".xpro-bb-dashboard-tab-features-content input:not(:disabled)" ).prop( 'checked', false );
				}
			}
		);

	}
);
