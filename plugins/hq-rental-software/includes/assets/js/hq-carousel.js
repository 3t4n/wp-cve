var carouselSelector = '#hq-carousel'
jQuery(document).ready(function(){
  jQuery(carouselSelector).owlCarousel({
    loop:true,
    nav:true,
    items: 1,
    dots: false,
    responsiveClass: true,
    navText: ['',''],
    mouseDrag: false,
    autoHeight: true,
    autoplay: true,
    autoplayTimeout: 5000
  })
  jQuery('.hq-tab').on('click',function (e){
    e.preventDefault()
    var pos = jQuery(this).attr('data-position')
    jQuery('.hq-tab').removeClass('active')
    jQuery(this).addClass('active')
    jQuery(carouselSelector).trigger("to.owl.carousel", [pos, 1])
  })
  jQuery(carouselSelector).on('changed.owl.carousel', function(event) {
    jQuery('.hq-tab').removeClass('active')
    jQuery('.hq-tap-pos-'+event.page.index).addClass('active')
  })
  var form = jQuery('.hq-reservation-form-wrapper')

  if(form.length){
    try{
      var scroll = new SmoothScroll('a[href*="#"]', {
        speed: 1000
      })
      return scroll
    }catch (e) {
      //nothing
    }
  }
})
