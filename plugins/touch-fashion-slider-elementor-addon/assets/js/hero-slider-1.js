// Swiper
var mySwiper = new Swiper(".tfsel-hero-1", {
   effect: "",
   loop: false,
   speed: 800,
   navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev"
   },
   pagination: {
      el: ".swiper-pagination",
      type: "bullets",
      clickable: "true"
   }
});

 //transitions
 var btnNext = document.querySelector(".swiper-button-next"),
     btnPrev = document.querySelector(".swiper-button-prev"),
     sliderText = document.querySelectorAll(".slide-text"),
     sliderNumber = document.querySelectorAll(".swiper-slide .number");

      btnNext.addEventListener("click", transitionSlideNext);
      btnPrev.addEventListener("click", transitionSlidePrev);
 
 function transitionSlideNext() {
    var slideActiveText = document.querySelector(".swiper-slide-active .slide-text"),
        slidePrevText = document.querySelector(".swiper-slide-prev .slide-text");
    
    slidePrevText.classList.remove("playText");
    slideActiveText.classList.add("playText");
 }
 
 function transitionSlidePrev() {
    var slideActiveText = document.querySelector(".swiper-slide-active .slide-text"),
        slideNextText = document.querySelector(".swiper-slide-next .slide-text");
    
    slideNextText.classList.remove("playText");
    slideActiveText.classList.add("playText");
 }