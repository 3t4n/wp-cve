jQuery( function( $ ) {

	function init() {
		load_maps();
		load_slider();
	}

	function load_imagesLoaded( callback ) {
		if ( 'undefined' === typeof $.fn.imagesLoaded ) {
			$.getScript( themifyShortcodes.includesURL + 'js/imagesloaded.min.js', function() {
				callback();
			} );
		} else {
			callback();
		}
	}

	function load_slider() {
		if ( $( '.themify-shortcodes-slider' ).length ) {
			load_imagesLoaded( function() {
				if ( 'undefined' === typeof $.fn.carouFredSel ) {
					$.getScript( themifyShortcodes.url + 'assets/carousel.min.js', function() {
						do_slider();
					} );
				} else {
					do_slider();
				}
			} );
		}
	}

	function do_slider() {
		var sliders = $( '.themify-shortcodes-slider .slides[data-slider]' );
		sliders.each(function () {
			if($(this).closest('.carousel-ready').length>0){
				return true;
			}
			$(this).find('> br, > p').remove();
			var $this = $(this),
				data = JSON.parse( atob( $(this).data('slider') ) ),
				height = typeof data.height === 'undefined'? 'auto' : data.height,
				slideContainer = undefined !== data.custom_numsldr ? '#' + data.custom_numsldr : '#slider-' + data.numsldr,
				speed = data.speed >= 1000 ? data.speed : 1000 * data.speed,
				args = {
					responsive: true,
					swipe: true,
					circular: data.wrapvar,
					infinite: data.wrapvar,
					auto: {
						play: data.auto != 0,
						timeoutDuration: data.auto >= 1000 ? data.auto : 1000 * data.auto,
						duration: speed,
						pauseOnHover: data.pause_hover
					},
					scroll: {
						items: parseInt(data.scroll),
						duration: speed,
						fx: data.effect
					},
					items: {
						visible: {
							min: 1,
							max: parseInt(data.visible)
						},
						width: 120,
						height: height
					},
					onCreate: function (items) {
						$this.closest('.caroufredsel_wrapper').outerHeight($this.outerHeight(true));
						$(slideContainer).css({'visibility': 'visible', 'height': 'auto'});
						$this.closest( '.carousel-wrap' ).addClass( 'carousel-ready' );
					}
				};

			if (data.slider_nav) {
				args.prev = slideContainer + ' .carousel-prev';
				args.next = slideContainer + ' .carousel-next';
			}
			if (data.pager) {
				args.pagination = slideContainer + ' .carousel-pager';
			}
			$this.imagesLoaded().always(function () {
				$this.carouFredSel(args);
			});
		});

		$(window).off('tfsmartresize.tfcarousel').on('tfsmartresize.tfcarousel', function () {
			sliders.each(function () {
				var heights = [],
						newHeight,
						$self = $(this);
				$self.find('li').each(function () {
					heights.push($(this).outerHeight(true));
				});
				newHeight = Math.max.apply(Math, heights);
				$self.outerHeight(newHeight);
				$self.parent().outerHeight(newHeight);
			});
		});
	}

	function load_maps() {
		if ( $( '.themify-shortcodes-map' ).length > 0 ) {
			setTimeout( function () {
				if ( typeof google !== 'object' || typeof google.maps !== 'object' ) {
					$.getScript( '//maps.googleapis.com/maps/api/js?v=3.exp&key=' + themifyShortcodes.map_key, function() {
						MapCallback();
					} );
				} else {
					MapCallback();
				}
			}, 500 );
		}
	}

	function MapCallback() {
		$( '.themify-shortcodes-map' ).each(function (i) {
			var $this = $( this ),
				address = $this.data( 'address' ),
				zoom = parseInt( $this.data( 'zoom' ) ),
				type = $this.data( 'type' ),
				scroll = $this.data( 'scroll' ) === 1,
				dragMe = $this.data( 'drag' ) === 1,
				controls = $this.data( 'control' ) === 1,
				delay = i * 1000;
			setTimeout(function () {
				var geo = new google.maps.Geocoder(),
						latlng = new google.maps.LatLng(-34.397, 150.644),
						mapOptions = {
							zoom: zoom,
							center: latlng,
							mapTypeId: google.maps.MapTypeId.ROADMAP,
							scrollwheel: scroll,
							draggable: dragMe,
							disableDefaultUI: controls
						};
				switch (type.toUpperCase()) {
					case 'ROADMAP':
						mapOptions.mapTypeId = google.maps.MapTypeId.ROADMAP;
						break;
					case 'SATELLITE':
						mapOptions.mapTypeId = google.maps.MapTypeId.SATELLITE;
						break;
					case 'HYBRID':
						mapOptions.mapTypeId = google.maps.MapTypeId.HYBRID;
						break;
					case 'TERRAIN':
						mapOptions.mapTypeId = google.maps.MapTypeId.TERRAIN;
						break;
				}

				var map = new google.maps.Map( $this[0], mapOptions ),
					revGeocoding = $this.data( 'reverse-geocoding' ) ? true : false;

				/* store a copy of the map object in the dom node, for future reference */
				$this.data('gmap_object', map);

				if (revGeocoding) {
					var latlngStr = address.split(',', 2),
							lat = parseFloat(latlngStr[0]),
							lng = parseFloat(latlngStr[1]),
							geolatlng = new google.maps.LatLng(lat, lng),
							geoParams = {'latLng': geolatlng};
				} else {
					var geoParams = {'address': address};
				}
				geo.geocode(geoParams, function (results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						var position = revGeocoding ? geolatlng : results[0].geometry.location;
						map.setCenter(position);
						var marker = new google.maps.Marker({
								map: map,
								position: position
							}),
							info = $this.data('info-window');
						if (undefined !== info) {
							var contentString = '<div class="themify_builder_map_info_window">' + info + '</div>',
									infowindow = new google.maps.InfoWindow({
										content: contentString
									});

							google.maps.event.addListener(marker, 'click', function () {
								infowindow.open(map, marker);
							});
						}
					}
				});
			}, delay );
		});
	}

	init();

} );