jQuery(function () {
    /**
     * Vertical Button
     */
    wp.customize(
            'cta_vertical_button', function (value) {
                value.bind(
                        function (newVal) {
                            if ('' === newVal) {
                                jQuery('.vertical a').hide();
                            } else {
                                jQuery('.vertical a').show();
                            }
                            jQuery('.vertical a').text(newVal);
                        }
                );
            }
    );
    /**
     * Vertical Button Link
     */
    wp.customize(
            'cta_vertical_button_link', function (value) {
                value.bind(
                        function (newLink) {
                            if ('' === newLink) {
                                jQuery('.vertical a').hide();
                            } else {
                                jQuery('.vertical a').show();
                            }
                            jQuery('.vertical a').attr('href', newLink);
                        }
                );
            }
    );
    /**
     * Horizontal Button
     */
    wp.customize(
            'cta_horizontal_button', function (value) {
                value.bind(
                        function (newVal) {
                            if ('' === newVal) {
                                jQuery('.horizontal a').hide();
                            } else {
                                jQuery('.horizontal a').show();
                            }
                            jQuery('.horizontal a').text(newVal);
                        }
                );
            }
    );
    /**
     * Horizontal Button Link
     */
    wp.customize(
            'cta_horizontal_button_link', function (value) {
                value.bind(
                        function (newLink) {
                            if ('' === newLink) {
                                jQuery('.horizontal a').hide();
                            } else {
                                jQuery('.horizontal a').show();
                            }
                            jQuery('.horizontal a').attr('href', newLink);
                        }
                );
            }
    );

});

(function( $ ) {
    "use strict";

    wp.customize('about_count', function(value) {
        value.bind(function(valueControl) {
            if(valueControl==0){
				$('#page > div.section.about.pdt0 > div > div > div:nth-child(1) > div > div').hide();
			}else{
                $('#page > div.section.about.pdt0 > div > div > div:nth-child(1) > div > div').show();
            }
        });
    });

    wp.customize('about_tagline', function(value) {
        value.bind(function(valueControl) {
            if(valueControl==''){
				$('#page > div.section.about.pdt0 > div > div > div:nth-child(1) > div > div').hide();
			}else{
                $('#page > div.section.about.pdt0 > div > div > div:nth-child(1) > div > div').show();
            }
        });
    });

    /**
     * Display Price
     */
    wp.customize(
        'price_display', function (value) {
            value.bind(
                    function (price_display) {
                        if (false === price_display) {
                            jQuery('#page > div.section.plans').hide();
                        } else {
                            jQuery('#page > div.section.plans').show();
                        }
                    }
            );
        }
);


})( jQuery );


