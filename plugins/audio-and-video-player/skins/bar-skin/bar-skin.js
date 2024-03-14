/* BAR-SKIN */
var cp_skin_js = cp_skin_js || {};
cp_skin_js['bar-skin'] = function($){
    function resize(){
        $('.mejs-container.bar-skin').each(function(){
            var me = $(this);
			setTimeout(function(){
				me.find('.mejs-controls').width(Math.max(me.width()-50, 0));
				var ct = me.find('.mejs-currenttime-container'),
					ct_o = ct.offset(),
					dc = me.find('.mejs-duration-container'),
					dc_o = dc.offset(),
					w = Math.max(dc_o['left'] - ( ct_o['left'] + ct.width() ), 0)+'px';
				me.find('.mejs-time-rail,.mejs-time-total').css({'maxWidth':w,'width':w});
			}, 200);

        });
    };
    resize();
    $(window).resize(function(){
       resize();
    });

};
/* END: BAR-SKIN */
