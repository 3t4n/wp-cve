//font ranger
var rangeSlider = function(){
  var slider = jQuery('.wcatcbll_range_slider'),
      range = jQuery('.wcatcbll_range_slider_range'),
      value = jQuery('.wcatcbll_range_slider_value');
    
  slider.each(function(){

    value.each(function(){
      var label = jQuery(this).prev().attr('id');
      var value1 = jQuery(this).prev().attr('value');      
      jQuery(this).html(value1+'px');
      if(label == 'catcbll_btn_fsize'){         
        jQuery('#btn_prvw').css('font-size',value1+'px');         
      }else if(label == 'catcbll_border_size'){
        jQuery('#btn_prvw').css('border',value1+'px solid');
      }else if(label == 'catcbll_btn_radius'){
        jQuery('#btn_prvw').css('border-radius',value1+'px');
      }
    });

    range.on('input', function(){
      jQuery(this).next().html(this.value+'px');
      var style_val = jQuery(this).val();
	  var styl_lbl = jQuery(this).attr('id');
		var brdr_clr = jQuery('#catcbll_btn_border_clr').val();
      if(styl_lbl == 'catcbll_btn_fsize'){         
        jQuery('#btn_prvw').css('font-size',style_val+'px');         
      }else if(styl_lbl == 'catcbll_border_size'){
        jQuery('#btn_prvw').css('border',style_val+'px solid '+brdr_clr);
      }else if(styl_lbl == 'catcbll_btn_radius'){
        jQuery('#btn_prvw').css('border-radius',style_val+'px');
      }
    });
  });
};

rangeSlider();

//ranger fill by color
jQuery('input[type="range"]').on("change mousemove", function () {
    var val = (jQuery(this).val() - jQuery(this).attr('min')) / (jQuery(this).attr('max') - jQuery(this).attr('min'));
    jQuery(this).css('background-image',
                '-webkit-gradient(linear, left top, right top, '
                + 'color-stop(' + val + ', #2f466b), '
                + 'color-stop(' + val + ', #d3d3db)'
                + ')'
                );
});