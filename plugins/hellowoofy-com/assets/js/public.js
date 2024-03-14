jQuery(document).ready(function($){
  $("#mwsBtn").click(function(){
    $("#mwsModal").css("display","block");
  });
  $(".mwsClose").click(function(){
    $("#mwsModal").css("display","none");
  });
  var mybutton = document.getElementById("mwsBtn");
  window.onscroll = function() {scrollFunction()};
  function scrollFunction() {
	if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
	  mybutton.style.display = "block";
	} else {
	  mybutton.style.display = "none";
	}
  }
  function topFunction() {
	document.body.scrollTop = 0;
	document.documentElement.scrollTop = 0;
  }
});

