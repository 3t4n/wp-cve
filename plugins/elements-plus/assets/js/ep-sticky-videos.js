var isAdminBar		= false,
	isEditMode		= false;
	
(function($) {

	var getElementSettings = function( $element ) {
		var elementSettings = {},
			modelCID 		= $element.data( 'model-cid' );

		if ( isEditMode && modelCID ) {
			var settings 		= elementorFrontend.config.elements.data[ modelCID ],
				settingsKeys 	= elementorFrontend.config.elements.keys[ settings.attributes.widgetType || settings.attributes.elType ];

			jQuery.each( settings.getActiveControls(), function( controlKey ) {
				if ( -1 !== settingsKeys.indexOf( controlKey ) ) {
					elementSettings[ controlKey ] = settings.attributes[ controlKey ];
				}
			} );
		} else {
			elementSettings = $element.data('settings') || {};
		}

		return elementSettings;
	}

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/video.default', function($scope) {

			var sticky = getElementSettings( $scope ).ep_sticky_video;

			if ( document.documentElement.clientWidth < 1024 || 'on' !== sticky ) {
				return;
			}

			var $window = $(window);
			var $videoWrap = $($scope).find('.elementor-widget-container');
			var $video = $($scope).find('.elementor-wrapper');
			var videoHeight = $video.outerHeight();

			$window.on('scroll',  function() {
				var windowScrollTop = $window.scrollTop();
				var videoBottom = videoHeight + $videoWrap.offset().top;
				
				if (windowScrollTop > videoBottom ) {
					var video = $($scope).find('.elementor-wrapper');
					if ( ! video.hasClass('closed') ) {
						$videoWrap.height(videoHeight).addClass('ep-sticky-wrapper');
						video.addClass('stuck');
						video.append('<i class="fas fa-times close"></i>');
						$close = $(video).find('.close');
						$close.on('click', function() {
							video.removeClass('stuck');
							video.addClass('closed');
						});
					}					
				} else {
					$videoWrap.height('auto').removeClass('ep-sticky-wrapper');
					$video.removeClass('stuck');
				}
			});

		});
	});
})( jQuery );