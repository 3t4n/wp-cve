var carouselSelector = '#hq-carousel'
jQuery(function(){
    jQuery('#reservation-form__car-select').on('change',function(){
        var owl = jQuery(carouselSelector);
        owl.owlCarousel();
        var selectedVehicleClassId = jQuery('#reservation-form__car-select').val();
        var selectedVehicleSlide = jQuery('#hq-vehicle-wheelsberry-' + selectedVehicleClassId);
        owl.trigger('to.owl.carousel',[ parseInt(selectedVehicleSlide.attr('data-vehicle-class-index')) ,250, true]);
        refreshAutoplayTimeout();
    });
});
function refreshAutoplayTimeout(){
    try {
        var owl = jQuery(carouselSelector);
        owl.data('owlCarousel').options.autoplayTimeout = 10000;
        owl.trigger('refresh.owl.carousel');
    }catch (e) {
        // do nothing
    }

}