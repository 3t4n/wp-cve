sl = jQuery.noConflict();
sl(function ($) {
    var clipboard = new ClipboardJS('.copy-clipboard');
    clipboard.on('success', function(e) {
        $(e.trigger).after('<span class="after-copied"> Copied</span>');
        setTimeout(function() {
            $('.after-copied').remove();
        },3000);
    });
    //Actual jQuery to go in here
    $( "#how_to_find_license" ).click(function(e) {
        e.preventDefault();
        $( "#license_code" ).slideDown( "slow", function() {
            // Animation complete.
        });
    });

    // Accordion - Expand All #01
    $("#sharelink-accordion").accordion({
        collapsible: true,
        heightStyle: "content",
        autoHeight: false,
        clearStyle: true,
        active: false,
        activate: function(event, ui){
            if (ui.newPanel.length != 0) {
                ui.newPanel.find('iframe')[0].iFrameResizer.resize();
            }
        }
    });

    var icons = $( "#sharelink-accordion" ).accordion( "option", "icons" );
    $('.open').click(function () {
        $('.ui-accordion-header').removeClass('ui-corner-all').addClass('ui-accordion-header-active ui-state-active ui-corner-top').attr({
            'aria-selected': 'true',
            'tabindex': '0'
        });
        $('.ui-accordion-header-icon').removeClass(icons.header).addClass(icons.headerSelected);
        $('.ui-accordion-content').addClass('ui-accordion-content-active').attr({
            'aria-expanded': 'true',
            'aria-hidden': 'false'
        }).show();
        $(this).attr("disabled","disabled");
        $('.close').removeAttr("disabled");
        $('iframe.sharelink').each(function(i) {
            $(this)[0].iFrameResizer.resize();
        });
    });
    $('.close').click(function () {
        $('.ui-accordion-header').removeClass('ui-accordion-header-active ui-state-active ui-corner-top').addClass('ui-corner-all').attr({
            'aria-selected': 'false',
            'tabindex': '-1'
        });
        $('.ui-accordion-header-icon').removeClass(icons.headerSelected).addClass(icons.header);
        $('.ui-accordion-content').removeClass('ui-accordion-content-active').attr({
            'aria-expanded': 'false',
            'aria-hidden': 'true'
        }).hide();
        $(this).attr("disabled","disabled");
        $('.open').removeAttr("disabled");
    });
    $('.ui-accordion-header').click(function () {
        $('.open').removeAttr("disabled");
        $('.close').removeAttr("disabled");
        
    });

    hljs.initHighlightingOnLoad();

    jQuery('#check_domain').on('click', function(e) {
        var $this = $(this);

        $this.attr('disabled', 'disabled');
        var data = {
			'action': 'sharelink-check-domain'
        };
        jQuery('#invalid-domain').hide();

		jQuery.post(ajaxurl, data, function(response) {
			if (response == "success") {
                window.location.replace("/wp-admin/admin.php?page=sharelink");
            } else {
                jQuery('#invalid-domain').show();
            }

            $this.removeAttr('disabled');
		});
    });

    // jQuery('#refresh').on('click', function(e) {
    //     var $this = $(this);

    //     var data = {
	// 		'action': 'sharelink-refresh-widget'
    //     };

	// 	jQuery.post(ajaxurl, data, function(response) {
    //         jQuery("#sharelink-accordion").html(response);
            
    //         $("#sharelink-accordion").accordion({
    //             collapsible: true,
    //             heightStyle: "content",
    //             autoHeight: false,
    //             clearStyle: true,
    //             active: false
    //         });
	// 	});
    // });
});