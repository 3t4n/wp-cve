/* CLASSIC-SKIN */
var cp_skin_js = cp_skin_js || {};
cp_skin_js['device-player-skin'] = function($){
	$(window).on('load', function(){
		$('.device-player-skin.emjs-playlist li').each(function(){
			var e = $(this);
			e.html(e.html().replace(/^(&nbsp;){2}/i, ''));
		});
	});
	function resize(){
        $('.device-player-skin.emjs-playlist').each(function(){
			var e = $(this),
				c = e.closest('[id="ms_avp"]').find('.mejs-container');
			if ( c.length ) e.width( c.width() );
		});
    };
    resize();
    $(window).resize(function(){
       resize();
    });
};
/* END: CLASSIC-SKIN */
