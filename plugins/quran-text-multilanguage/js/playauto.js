
 jQuery(function($) { 

        // Setup the player to autoplay the next track
        var a = audiojs.createAll({
          trackEnded: function() {
		  
            var next = $('ol li.playing').next();

            if (!next.length) next = $('ol li').first();
			
			var qurandata = $('a', next).attr('verset-data');	

			
			var verset = $('.verset'+qurandata+'');
			
			var reset_quran = qurandata - 1;
			
			reset_quran = $('.verset'+reset_quran+'');
			var ayabloc = $('#ayabloc'+qurandata+'');
			var trans = $('.trans'+qurandata+'');
			var reset_trans = qurandata - 1;	
			reset_trans = $('.trans'+reset_trans+'');
if(navigator.userAgent.search("mobile")>0 ){
      $('html,body').animate({scrollTop: $("#kv"+qurandata).offset().top  -500}, 'fast');
}
      else{
			$('html,body').animate({scrollTop: $("#kv"+qurandata).offset().top  -200}, 'fast');
			}
			reset_quran.removeAttr("style");	
			reset_trans.removeAttr("style");

			verset.css('background-color', 'rgb(87, 87, 87)');
			verset.css('color', '#fff');
			trans.css('background-color', 'rgb(224, 175, 138)');
			trans.css('color', 'rgb(41, 41, 41)');			
            next.addClass('playing').siblings().removeClass('playing');
			verset.removeClass('verset');

            audio.load($('a', next).attr('data-src'));
            audio.play();
          					
          }
        });
        //Lecture automatique

        var audio = a[0];
            first = $('ol a').attr('data-src');
			
        $('ol li').first().addClass('playing');		
        audio.load(first);

                // Load in a track on click
        $('ol li').click(function(e) {
          e.preventDefault();
          $(this).addClass('playing').siblings().removeClass('playing');
          audio.load($('a', this).attr('data-src'));
          audio.play();
        });	

      });
