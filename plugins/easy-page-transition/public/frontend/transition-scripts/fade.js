jQuery(document).ready(function($){
    //Start opacity of body tag at 0 and transition it to 100% when page is loaded.
    $('body').addClass('fade');

      //When an anchor link with the class of transitionLink is clicked stop the link from linking.
      //Then remove the 'showContent' class from the body, making it fade out. Then link to the correct link.
      $("a").click(function(event){
          event.preventDefault();
          var link = $(this).attr("href");
          var target = $(this).attr("target");

          if(link.startsWith('#')){
            //Don't run animation.
          }else{
            if(target == '_blank'){
              window.open(link, '_blank');
            }else{
              $('body').removeClass('fade');
              setTimeout(function() {
                  window.location.href = link;
              }, 500);
            }
          }
      });
});
