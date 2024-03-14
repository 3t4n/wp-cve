(function($) {

  $(window).scroll(function() {
    var sticky_apply = ind.sticky_header;
    if(sticky_apply != true) { return false;}
    if ($(this).scrollTop() > 68) {
      $('header .navbar ').addClass("sticky");
    } else {
      $('header .navbar ').removeClass("sticky");
    }
  });
  
  /*============= home slider =======================*/
  
  
  var Slider = new Swiper('main .swiper-container', {    
    direction: 'horizontal',
    slidesPerView: 1,
	    autoHeight: true,
    paginationClickable: true,
    spaceBetween: 0,
	    speed: 2200,
		    autoplay: 1,
    grabCursor: true,   
    loop: true,
    speed: 400,
    effect: 'slide',
    keyboardControl: true,
    hashnav: true,
    useCSS3Transforms: false,
	    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true,
    },   
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
});
  

  
  var Slider = new Swiper('main .swiper-cgggontainer', {    
    autoHeight: true,
    loop: true,
    keyboardControl: true,
    speed: 2200,
    autoplay: 1,
    grabCursor: true,   
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true,
    },   
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  });   
})(jQuery);