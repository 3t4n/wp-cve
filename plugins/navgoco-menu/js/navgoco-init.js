
jQuery(document).ready(function($) {
        $(navgocoVars.ng_navgo.ng_menu_selection).navgoco({
        	accordion: navgocoVars.ng_navgo.ng_menu_accordion,
        	openClass: 'vert-open',
        	caretHtml: navgocoVars.ng_navgo.ng_menu_html_carat,
        	slide: {
                  duration: navgocoVars.ng_navgo.ng_slide_duration,
                  easing: navgocoVars.ng_navgo.ng_slide_easing
              },
            save: navgocoVars.ng_navgo.ng_menu_save,
            cookie: {
	            name: 'navgoco',
	            expires: false,
	            path: '/'
        	},
        }); 
        //add in a unique class to add some default styling from Navgoco
        $( navgocoVars.ng_navgo.ng_menu_selection).addClass( "navgoco" );
    });


