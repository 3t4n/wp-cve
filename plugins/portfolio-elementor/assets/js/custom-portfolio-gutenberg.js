var $grid, $packery;

function startPowerfolioForGutenberg() {
  // Isotope Layout
  $grid = jQuery(".elpt-portfolio-content-isotope").isotope({
    layoutMode: "masonry",
    itemSelector: ".portfolio-item-wrapper",
  });

  $grid.isotope("layout");

  // Packery Layout
  $packery = jQuery(".elpt-portfolio-content-packery").isotope({
    layoutMode: "packery",
    itemSelector: ".portfolio-item-wrapper",
  });

  $packery.isotope("layout");
}

// Use event delegation for click event on '.elpt-portfolio-filter'
jQuery(document).on("click", ".elpt-portfolio-filter button", function () {
  jQuery(".elpt-portfolio-filter button").removeClass("item-active");
  jQuery(this).addClass("item-active");
  var filterValue = jQuery(this).attr("data-filter");
  $grid.isotope({
    filter: filterValue,
  });
  $packery.isotope({
    filter: filterValue,
  });
});

// Call startPowerfolioForGutenberg when the block is rendered
wp.domReady(function () {
  startPowerfolioForGutenberg();

  // Call the function periodically to ensure it's applied to any newly added blocks
  setInterval(function () {
    startPowerfolioForGutenberg();
  }, 1000);
});