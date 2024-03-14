jQuery(function ($) {
	$(".wrap").find(".tab").click(function () {
    var href = $(this).attr("href");
    $(".pane").hide();
    $(href).fadeIn("fast");
    $(".tab").removeClass("current");
    $(this).addClass("current");
  });
  var hash = window.location.hash;
  if (hash) {
  	$(".wrap .tab[href='"+hash+"']").click();
  }
});