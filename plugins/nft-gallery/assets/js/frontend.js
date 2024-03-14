jQuery(function ($) {
  	$(document).ready(function() {
		$('.nft').on('click', function() {
		    window.open($(this).attr('data-url'), '_blank');
		});
    });

    $('body').on('click', '.openseaBtn', function() {
        window.open($(this).attr('data-url'), '_blank');
    });

    jQuery("#lightgallery").justifiedGallery({
        captions: false,
        lastRow: "hide",
        rowHeight: 180,
        margins: 5
      }).on("jg.complete", function () {
        lightGallery(document.getElementById('lightgallery'), {
            plugins: [lgZoom, lgThumbnail],
            licenseKey: 'BFF899F7-05934613-8E2423DF-B2577DEC',
            thumbnail: true,
            mode: 'lg-fade'
        });
    });
});