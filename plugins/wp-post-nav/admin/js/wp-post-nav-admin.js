/*
 * The Admin JS file of WP Post Nav
 *
 * @link:      https://en-gb.wordpress.org/plugins/wp-post-nav/
 * @since      0.0.1
 * @package    wp_post_nav
 */
(function( $ ) {
	'use strict';
	 $(function() {	

	 	//setup the js for the tabbed navigation
 		var 
      headings     = $('.settings-container > h2, .settings-container > h3'),
		  paragraphs   = $('.settings-container > p'),
		  tables       = $('.settings-container > table'),
		  triggers     = $('.settings-tabs a'),
      instructions = $('#wp-post-nav-instructions'),
      submit       = $('#wp-post-nav-submit');

      submit.hide();

		triggers.each(function(i){
			triggers.eq(i).on('click', function(e){
				e.preventDefault();
				triggers.removeClass('nav-tab-active');
				headings.hide();
				paragraphs.hide();
				tables.hide();
        instructions.hide();
        submit.show();

				triggers.eq(i).addClass('nav-tab-active');
				headings.eq(i).show();
				paragraphs.eq(i).show();
				tables.eq(i).show();

        //if were on the instruction page, show the instruction panel
        if ($('#instructions').hasClass('nav-tab-active')) {
          instructions.show();
          submit.hide();
        }
			});
		})

		triggers.eq(0).click(); 
		
 		//setup the default optionds for each colorpicker
		$('.color-field').each(function(){
			//get the default colour which we defined in the admin page setup
			var default_colour = $(this).attr("default");
      $(this).wpColorPicker({
        // you can declare a default color here,
        // or in the data-default-color attribute on the input
        defaultColor: default_colour,

        // a callback to fire whenever the color changes to a valid color
        change: function(event, ui){},
        // a callback to fire when the input is emptied or an invalid color
        clear: function() {
        	 defaultColor: default_colour

        },
        //additional colorpicker options - not used
        // hide the color picker controls on load
        //hide: true,
        // set  total width
        //width : 200,
        // show a group of common colors beneath the square
        // or, supply an array of colors to customize further
        //palettes: ['#444444','#ff2255','#559999','#99CCFF','#00c1e8','#F9DE0E','#111111','#EEEEDD']
      });
    })
	});
})( jQuery );