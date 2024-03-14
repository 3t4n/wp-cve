var $j = jQuery.noConflict();
$j(window).load(function() {

    $j('.portfolio-grid').each(function () {
        var websitesWidth = {};
        $j(this).find('.wpp_columns-fill').each(function () {
            $j(this).find('div:not(.website-thumbnail):not(.portfolio-website-container)').each(function () {
                if ($j(this).is(':visible')) {
                    $j(this).hide().addClass('hidden-as-needed');
                }
            });

            var websiteWidth = $j(this).width();
            if (!(websiteWidth in websitesWidth)) {
                websitesWidth[websiteWidth] = {count: 0};
            }
            websitesWidth[websiteWidth].count += 1;
        });

        var maxWebsiteWidthCount = 0;
        var usedWebsiteWidth = 0;
        for (var websiteWidth in websitesWidth) {
            if (websitesWidth[websiteWidth].count > maxWebsiteWidthCount) {
                maxWebsiteWidthCount = websitesWidth[websiteWidth].count;
                usedWebsiteWidth = websiteWidth;
            }
        }

        $j(this).find('div.hidden-as-needed').removeClass('hidden-as-needed').show();
        $j(this).find('.wpp_columns-fill').width(usedWebsiteWidth);
    });
});