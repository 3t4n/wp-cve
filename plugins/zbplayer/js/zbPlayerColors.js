jQuery(function($){
  // Activate the color picker
  jQuery ('input.color')
  .each(function(){
    var $this = jQuery(this);
    var $picker = $this.parent().find('.colorpicker');
    $picker
    .farbtastic($this)
    .hide();
  });
  jQuery('input.color').click(function(){
    jQuery('.colorpicker').slideUp();
    jQuery(this).parent().find('.colorpicker').slideDown();
  });
});
