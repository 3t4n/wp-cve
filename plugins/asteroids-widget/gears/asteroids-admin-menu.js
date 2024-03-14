

  function toggleAsteroidsSlugMenu(a){
    if(jQuery("#"+a).val()=="all" || jQuery("#"+a).val()=="front"){
      jQuery("#"+a+"_slug").hide();
    }
    if(jQuery("#"+a).val()=="post" || jQuery("#"+a).val()=="page" || jQuery("#"+a).val()=="category" ){
      jQuery("#"+a+"_slug").show();
      }
  };