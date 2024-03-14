jQuery( document ).ready(function() {
    // console.log( "ready!" );
    $x = 1;
    jQuery('.wpcf7-select').each(function(index, value) {
      var name = jQuery(this).attr("name");
      // var namess = jQuery(this).attr("name");
      if($x == 1){
          jQuery("select[name="+ name +"]:eq(0)").select2();
      } else {
          jQuery("select[name="+ name +"]").select2('destroy');
      }
      $x++;
    });

});