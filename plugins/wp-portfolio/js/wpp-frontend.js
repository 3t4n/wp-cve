var $j = jQuery.noConflict();
$j(window).load(function() {
    // init Isotope
    var gridsByPortfolio = [];
    $j('.wp-portfolio-wrapper').each(function (i, elem) {
        var grids = [];
        $j(this).find('.portfolio-grid').each(function () {
            if($j(this).find('.portfolio-website[class*="wpp_columns-"]').length > 0) {
                var maxHeight = 0;
                $j(this).find('.portfolio-website').each(function () {
                    var toggled = false;
                    if ($j(this).hasClass('expanded') && $j(this).find('.expand-button').length > 0) {
                        $j(this).toggleClass('expanded');
                        toggled = true;
                    }
                    if ($j(this).outerHeight() > maxHeight) {
                        maxHeight = $j(this).outerHeight();
                    }
                    if (toggled) {
                        $j(this).toggleClass('expanded');
                    }
                });
                $j(this).find('.portfolio-website').css('min-height', maxHeight);
            }

            var grid = $j(this).isotope({
                itemSelector: '.portfolio-website',
                // layoutMode: 'fitRows',
                layoutMode: 'masonry',
                getSortData: {
                    name: function (itemElem) {
                        var websitename = $j(itemElem).find('.website-name').text();
                        return websitename;
                    },
                    description: '.website-description',
                    date: '[date] jquery parseInt',
                    grouporder: '[group-order] jquery parseInt',
                    groupname: '[group-name]',
                    groupid: '[group-id]',
                    siteorder: '[site-order] jquery parseInt'
                },
                sortAscending: {
                    name: true,
                    description: true,
                    date: false,
                    grouporder: true,
                    groupname: true,
                    groupid: true,
                    siteorder: true
                },
                transitionDuration: '0.3s'
            });
            grids.push(grid);
        });
        gridsByPortfolio[i] = grids;
        $j(this).attr('portfolio-id', i);
    });

    for (var i = 0; i < gridsByPortfolio.length; i++) {
        for (var j = 0; j < gridsByPortfolio[i].length; j++) {
            gridsByPortfolio[i][j].on('click', '.expand-button', {portfolioId: i, gridId: j}, function (e) {
                $j.when($j(this).closest('.portfolio-website').find('div:not(.website-thumbnail):not(.portfolio-website-container):not(.website-name):not(.website-clear):not(.expand-button)').css({
                    'z-index': 1,
                    'position': 'relative'
                }).toggle(200, function () {
                    $j(this).css('z-index', 'auto');
                })).then(function(){
                    $j(this).closest('.portfolio-website').toggleClass('expanded');
                    gridsByPortfolio[e.data.portfolioId][e.data.gridId].isotope('layout');
                });
            });
        }
    }

    // Sorting
    $j('.sort-by-button-group').on('click', 'button', function () {
        var portfolioId = $j(this).closest('.wp-portfolio-wrapper').attr('portfolio-id');
        if ($j(this).hasClass('shuffle-button')) {
            for (var i = 0; i < gridsByPortfolio[portfolioId].length; i++) {
                gridsByPortfolio[portfolioId][i].isotope('shuffle');
            }
        }
        else {
            var sortValue = $j(this).attr('data-sort-value');
            sortValue = sortValue.split(',');
            var sortAscending = $j(this).hasClass('desc') ? false : true;
            for (var i = 0; i < gridsByPortfolio[portfolioId].length; i++) {
                gridsByPortfolio[portfolioId][i].isotope({sortBy: sortValue, sortAscending: sortAscending});
            }
            $j(this).toggleClass('desc');
        }
    });

    // Filtering
    $j('.filters-button-group').on('click', 'button', function () {
        var portfolioId = $j(this).closest('.wp-portfolio-wrapper').attr('portfolio-id');
        var filterValue = $j(this).attr('data-filter');
        $j(this).closest('.wp-portfolio-wrapper').find('.portfolio-group-info:not(' + filterValue + ')').hide("200");
        $j(this).closest('.wp-portfolio-wrapper').find('.portfolio-group-info' + filterValue).show("200");
        for (var i = 0; i < gridsByPortfolio[portfolioId].length; i++) {
            gridsByPortfolio[portfolioId][i].isotope({filter: filterValue});
        }
    });

    $j('.button-group').each(function (i, buttonGroup) {
        var buttonGroup = $j(buttonGroup);
        buttonGroup.on('click', 'button', function () {
            buttonGroup.find('.is-checked').removeClass('is-checked');
            $j(this).addClass('is-checked');
        });
    });

    $j('.filters-button-group .button.is-checked').click();
});