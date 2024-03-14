jQuery(window).on('em:init', function(e) {
    window.easymeConnect.app.setConfig($cfg$)
} );
jQuery(document).ready(function() {

    jQuery(window).on('em:ready', function() {
	
	var _em = window.easymeConnect.app.modules;
	var _lr = function() {
	    return _em.util.ensureLogin({
		callback: function() {
		    window.location.reload(true);
		}
	    });
	};

	jQuery("a[href='https://ezme.io/wp/autologin']").each(function(idx) {
	    if(0 === idx) {
		_lr();
	    }
	});
	
	jQuery("a[href='https://ezme.io/wp/login']").on('click', function(e) {
	    e.preventDefault();
	    _lr();
	    return false;
	});

	jQuery("a[href='https://ezme.io/wp/profile']").on('click', function(e) {
	    e.preventDefault();
	    _em.profile.open();
	    return false;
	});

	jQuery("a[href='https://ezme.io/wp/logout']").on('click', function(e) {
	    e.preventDefault();
	    _em.oauth.logout();
	    return false;
	});

    });
    
});
