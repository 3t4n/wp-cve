jQuery(document).ready(function($){
  
  //Initialize
  $('.naaa_color_field').wpColorPicker();
  checkButtonGradientOption(0);
  checkButtonShadowOption(0);
  checkValoracionShowOption(0);
  checkComentariosShowOption(0);
  checkDiscountShowOption(0);
  checkMinWidthShowOption(0);
  checkColorProductBoxOption(0);

  //Events
  $("#naaa_button_bg_color2_show").change( function(){
    checkButtonGradientOption(500);
  });

  $("#naaa_button_shadow_show").change( function(){
    checkButtonShadowOption(500);
  });

  $("#naaa_valoracion_show").change( function(){
    checkValoracionShowOption(500);
  });

  $("#naaa_comentarios_show").change( function(){
    checkComentariosShowOption(500);
  });

  $("#naaa_discount_show").change( function(){
    checkDiscountShowOption(500);
  });

  $("#naaa_responsive").change( function(){
    checkMinWidthShowOption(500);
  });

  $("#naaa_product_color_show").change( function(){
    checkColorProductBoxOption(500);
  });
  

  //Functions
  function checkButtonGradientOption(time) {
    if($('#naaa_button_bg_color2_show').is(":checked")){
      $('#naaa_button_bg_color2_box').show(time);
    }else{
      $('#naaa_button_bg_color2_box').hide(time);
    }
  }

  function checkButtonShadowOption(time) {
    if($('#naaa_button_shadow_show').is(":checked")){
      $('#naaa_button_bg_color_shadow_box').show(time);
    }else{
      $('#naaa_button_bg_color_shadow_box').hide(time);
    }
  }

  function checkValoracionShowOption(time) {
    if($('#naaa_valoracion_show').is(":checked")){
      $('#naaa_label_valoracion_desc_show').show(time);
    }else{
      $('#naaa_label_valoracion_desc_show').hide(time);
    }
  }

  function checkComentariosShowOption(time) {
    if($('#naaa_comentarios_show').is(":checked")){
      $('#naaa_comentarios_text_group').show(time);
    }else{
      $('#naaa_comentarios_text_group').hide(time);
    }
  }

  function checkDiscountShowOption(time){
    if($('#naaa_discount_show').is(":checked")){
      $('#naaa_discount_bg_color_box').show(time);
      $('#naaa_discount_text_color_box').show(time);
    }else{
      $('#naaa_discount_bg_color_box').hide(time);
      $('#naaa_discount_text_color_box').hide(time);
    }
  }

  function checkMinWidthShowOption(time){
    if($('#naaa_responsive').is(":checked")){
      $('#naaa_min_width_gridbox_group').show(time);
      $('#naaa_min_width_gridbox_group').show(time);
    }else{
      $('#naaa_min_width_gridbox_group').hide(time);
      $('#naaa_min_width_gridbox_group').hide(time);
    }
  }

  function checkColorProductBoxOption(time){
    if($('#naaa_product_color_show').is(":checked")){
      $('#naaa_product_color_box').show(time);
      $('#naaa_product_color_box').show(time);
    }else{
      $('#naaa_product_color_box').hide(time);
      $('#naaa_product_color_box').hide(time);
    }
  }

});

