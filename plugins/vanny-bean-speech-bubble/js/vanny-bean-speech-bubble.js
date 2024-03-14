
jQuery(document).ready(function(){


    jQuery("img.speech-bubble").each(function(){
        var src = $(this).attr("src");
        var width = $(this).attr("width");
        var height = $(this).attr("height");
        jQuery(this).wrap('<div class="speech-bubble-wrapper" style="width:'+width+'px; height:'+height+'px;"></div>');

    })

})

jQuery(function(){
      jQuery("div.speech-bubble-wrapper").live("click", function(e){
        var x = e.pageX - this.offsetLeft;
	var y = e.pageY - this.offsetTop-35;

        jQuery(this).prepend('<div class="speech-bubble" style="margin-left:'+x+'px;margin-top:'+y+'px"><span>[click to edit]</span></div>');


        return false;
      });
});

jQuery(function(){
      jQuery("div.speech-bubble span").live("click", function(){
        if (jQuery(this).children().attr("method")!= 'post' ) {

            jQuery(this).html('');
            jQuery(this).append('<form method="post"><input type="text" name="speech" /><input type="submit" value="Save" /></form>')
        }
        return false;
      })
      return false;
});
