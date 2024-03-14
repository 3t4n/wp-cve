; (function ($) {
	const hash = window.location.hash.replace('#', '');
	// Help page tab menu script.
	$('.sp-team-help').on('click', '.spwpteam-header-nav-menu a', function(e) {
		if( $(this).hasClass('active') ) {
			return;
		}
		let tabId = $(this).attr('data-id');
		$('.spwpteam-header-nav-menu a').each((i, item) => {
			$(item).removeClass('active');
			$('#' + $(item).attr('data-id')).css('display', 'none');
		})
		$(this).addClass('active');

		$('#' + tabId).css('display', 'block');

		if('recommended-tab' === tabId){
			$('#menu-posts-sptp_member ul li').each((i, item) => {
				$(item).removeClass('current');
			})
			$('#menu-posts-sptp_member ul li:nth-child(7)').addClass('current');
		}
		if('lite-to-pro-tab' === tabId){
			$('#menu-posts-sptp_member ul li').each((i, item) => {
				$(item).removeClass('current');
			})
			$('#menu-posts-sptp_member ul li:nth-child(8)').addClass('current');
		}
		if(('get-start-tab' === tabId || 'about-us-tab' === tabId)){
			$('#menu-posts-sptp_member ul li').each((i, item) => {
				$(item).removeClass('current');
			})
			$('#menu-posts-sptp_member ul li:nth-child(9)').addClass('current');
		}
	})

	$('#menu-posts-sptp_member').on('click', 'ul li a', (e) => {
		var element = e.target.closest('a');

		if( 'edit.php?post_type=sptp_member&page=team_help' === $(element).attr('href') ) {
			$(element).attr('href', '#get-start');
		}

		setTimeout(() => {
			var hashValue = window.location.hash.replace('#', '');
			if(hashValue) {
				$('#menu-posts-sptp_member ul li').each((i, item) => {
					$(item).removeClass('current');
				})
			}
			if( 'recommended' === hashValue ) {
				$('.sp-team-help .spwpteam-header-nav-menu a[data-id="recommended-tab"]').trigger('click');
				$(element).parent().addClass('current')
			}
			if( 'lite-to-pro' === hashValue ) {
				$('.sp-team-help .spwpteam-header-nav-menu a[data-id="lite-to-pro-tab"]').trigger('click');
				$(element).parent().addClass('current')
			}
			if( 'get-start' === hashValue ) {
				$('.sp-team-help .spwpteam-header-nav-menu a[data-id="get-start-tab"]').trigger('click');
				$(element).parent().addClass('current')
			}
		}, 0);
	})

	if( 'recommended' === hash ) {
		$('.sp-team-help .spwpteam-header-nav-menu a[data-id="recommended-tab"]').trigger('click');
		$('#menu-posts-sptp_member ul li:nth-child(9)').removeClass('current');
		$('#menu-posts-sptp_member ul li:nth-child(7)').addClass('current');
	}
	if( 'lite-to-pro' === hash ) {
		$('.sp-team-help .spwpteam-header-nav-menu a[data-id="lite-to-pro-tab"]').trigger('click');
		$('#menu-posts-sptp_member ul li:nth-child(9)').removeClass('current');
		$('#menu-posts-sptp_member ul li:nth-child(8)').addClass('current');
	}
	if( 'about-us' === hash ) {
		$('.sp-team-help .spwpteam-header-nav-menu a[data-id="about-us-tab"]').trigger('click');
	}
	
	$('body').on('click', '.install-now', function(e) {
		var _this = $(this);
		var _href = _this.attr('href');
	
		_this.addClass('updating-message').html('Installing...');
	
		$.get(_href, function(data) {
		  location.reload();
		});
	
		e.preventDefault();
	});

})(jQuery);