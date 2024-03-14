export const scrollTo = (element) => {
  jQuery('html,body').animate({
    scrollTop: jQuery(element).offset().top - 100,
  }, 'slow');
};