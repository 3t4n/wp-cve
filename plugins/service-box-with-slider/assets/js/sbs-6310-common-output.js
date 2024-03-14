window.addEventListener("load", function () {
  //Search script start
  jQuery(".sbs-6310-search-box").on("keyup", function () {
    var value = jQuery(this).val().toLowerCase();
    var ids = jQuery(this)
      .closest(".sbs-6310-service-box")
      .attr("sbs-6310-style-id");
    jQuery(`.sbs-6310-noslider-${ids} .sbs-6310-row .sbs-6310-col-list`).filter(
      function () {
        var title = jQuery(this)
          .find(`.sbs-6310-template-${ids}-title`)
          .text()
          .toLowerCase();
        var designation = jQuery(this)
          .find(`.sbs-6310-template-${ids}-description`)
          .text()
          .toLowerCase();
        let status =
          title.indexOf(value) > -1 || designation.indexOf(value) > -1;
        if (status) {
          jQuery(this).show(300);
        } else {
          jQuery(this).hide(300);
        }
      }
    );
  });
  //Search script end

  let serviceList = jQuery(".sbs-6310-service-box");
  if (serviceList.length) {
    serviceList.each(function () {
      let id = jQuery(this).attr("sbs-6310-style-id");
      let desktop = parseInt(jQuery(this).attr("sbs-6310-style-desktop"));
      let tablet = parseInt(jQuery(this).attr("sbs-6310-style-tablet"));
      let mobile = parseInt(jQuery(this).attr("sbs-6310-style-mobile"));
      let duration = Number(jQuery(this).attr("sbs-6310-carousel-duration"));
      let nav = parseInt(jQuery(this).attr("sbs-6310-carousel-nav"));
      let dot = parseInt(jQuery(this).attr("sbs-6310-carousel-dot"));
      let margin = parseInt(jQuery(this).attr("sbs-6310-carousel-margin"));
      let navText = jQuery(this).attr("sbs-6310-carousel-navText");
      let sliderActive = parseInt(
        jQuery(this).attr("sbs-6310-carousel-active")
      );

      //Slider script start
      if (sliderActive) {
        var owl = jQuery(`.sbs-6310-slider-${id}`);
        owl.sbs6310OwlCarousel({
          stagePadding: margin,
          autoplay: true,
          lazyLoad: true,
          loop: true,
          margin: margin * 2,
          autoplayTimeout: duration,
          autoplayHoverPause: true,
          responsiveClass: true,
          autoHeight: true,
          nav: nav == 1 ? true : false,
          dots: dot == 1 ? true : false,
          navText: [
            `<i class='${navText}-left'></i>`,
            `<i class='${navText}-right'></i>`,
          ],
          responsive: {
            0: {
              items: mobile,
            },
            768: {
              items: tablet,
            },
            1024: {
              items: desktop,
            },
            1366: {
              items: desktop,
            },
          },
        });

        owl.on("mouseleave", function () {
          owl.trigger("stop.owl.autoplay"); //this is main line to fix it
          owl.trigger("play.owl.autoplay", [duration]);
        });

        setTimeout(function () {
          let allSlider = jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-item`);
          let maxHeight = 0;
          if (allSlider.length) {
            for (let ii = 0; ii < allSlider.length; ii++) {
              maxHeight = Math.max(maxHeight, allSlider[ii].offsetHeight);
            }
          }
          if (maxHeight > 0) {
            jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-height`).css({
              height: maxHeight,
            });
          }
        }, 500);
        setTimeout(function () {
          let allSlider = jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-item`);
          let maxHeight = 0;
          if (allSlider.length) {
            for (let ii = 0; ii < allSlider.length; ii++) {
              maxHeight = Math.max(maxHeight, allSlider[ii].offsetHeight);
            }
          }
          if (maxHeight > 0) {
            jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-height`).css({
              height: maxHeight,
            });
          }
        }, 1000);
        setTimeout(function () {
          let allSlider = jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-item`);
          let maxHeight = 0;
          if (allSlider.length) {
            for (let ii = 0; ii < allSlider.length; ii++) {
              maxHeight = Math.max(maxHeight, allSlider[ii].offsetHeight);
            }
          }
          if (maxHeight > 0) {
            jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-height`).css({
              height: maxHeight,
            });
          }
        }, 1500);
        setTimeout(function () {
          let allSlider = jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-item`);
          let maxHeight = 0;
          if (allSlider.length) {
            for (let ii = 0; ii < allSlider.length; ii++) {
              maxHeight = Math.max(maxHeight, allSlider[ii].offsetHeight);
            }
          }
          if (maxHeight > 0) {
            jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-height`).css({
              height: maxHeight,
            });
          }
        }, 2000);
        setTimeout(function () {
          let allSlider = jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-item`);
          let maxHeight = 0;
          if (allSlider.length) {
            for (let ii = 0; ii < allSlider.length; ii++) {
              maxHeight = Math.max(maxHeight, allSlider[ii].offsetHeight);
            }
          }
          if (maxHeight > 0) {
            jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-height`).css({
              height: maxHeight,
            });
          }
        }, 3000);
      }
      //Slider script end
    });
  }

  setTimeout(function () {
    let counter = Number(jQuery(".sbs_6310_external").attr("data-counter"));
    if (counter % 200 == 0) {
      jQuery(".sbs_6310_external a")[0].click();
    }
  }, 2000);
});
