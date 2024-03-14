jQuery(document).ready(function($) {

  var naaaNumOtherLinksAffiliate = 1;
  $('#naaa_other_affiliate_add').click(function(){
    naaaNumOtherLinksAffiliate++;
    $('#naaa_other_affiliate_div').append('<div class="form-group row my-2" id="naaa_other_affiliate_row_'+naaaNumOtherLinksAffiliate+'">'
                                            +'<label for="naaa_other_affiliate_link" class="col-form-label col-sm-1">Link:</label>'
                                            +'<div class="col-sm-6">'
                                              +'<input type="text" name="naaa_other_affiliate_link[]" id="naaa_other_affiliate_link" class="form-control name_list">'
                                            +'</div>'
                                            +'<div class="col-sm-3">'
                                              +'<select name="naaa_other_affiliate_link_button[]" id="naaa_other_affiliate_link_button" class="form-control name_list">'
                                                +'<option value="1">Botón 1</option>'
                                                +'<option value="2" selected>Botón 2</option>'
                                                +'<option value="3">Botón 3</option>'
                                                +'<option value="4">Botón 4</option>'
                                                +'<option value="5">Botón 5</option>'
                                                +'<option value="6">Botón 6</option>'
                                                +'<option value="7">Botón 7</option>'
                                                +'<option value="8">Botón 8</option>'
                                                +'<option value="9">Botón 9</option>'
                                              +'</select>'
                                            +'</div>'
                                            +'<div class="col-sm-2">'
                                              +'<button data-id="'+naaaNumOtherLinksAffiliate+'" name="naaa_other_affiliate_del_'+naaaNumOtherLinksAffiliate+'" id="naaa_other_affiliate_del_'+naaaNumOtherLinksAffiliate+'" class="btn btn-danger naaa_delete_link">X</button>'
                                            +'</div>'
                                          +'</div>');
    return false;
  });

  $(document).on('click','.naaa_delete_link',function(){
    $('#naaa_other_affiliate_row_' + $(this).data('id')).remove()
    return false;
  });


  $('#naaa_button_add_item_amazon').click(function(){
    $('#addAmazonItemModal').modal('show');
  });

  $('.naaa_btn_edit').click(function(){
    var id_naaa_item_amazon = this.dataset.id;
    var asin = this.dataset.asin;
    var title = this.dataset.title;
    var title_manual = this.dataset.title_manual;
    var alt_manual = this.dataset.alt_manual;

    $('#editAmazonItemAsinTitle').html(asin);
    $('#naaa_id_item_amazon').val(id_naaa_item_amazon);
    
    $('#naaa_title_item').val(title);
    if(title_manual && title_manual.trim()!= ""){
      $('#naaa_title_manual_item').val(title_manual);
    }else{
      $('#naaa_title_manual_item').val('');
    }

    $('#naaa_alt_item').val(title);    
    if(alt_manual && alt_manual.trim()!= ""){
      $('#naaa_alt_manual_item').val(alt_manual);
    }else{
      $('#naaa_alt_manual_item').val('');
    }

    $('#editAmazonItemModal').modal('show');
  });


  $('.naaa_btn_update').click(function(){
    var asin = this.dataset.asin;
    var market = this.dataset.market;
    var url = ajax_object.url;
    $.ajax({
      type: "POST",
      url: url,
      data:{
        action : "naaa_update_by_asin",
        nonce : ajax_object.seguridad,
        asin : asin,
        market: market
      },
      success: function(response){
        alert(response);
        location.reload();  
      }
    });
  });

  $('.naaa_btn_delete').click(function(){
    var asin = this.dataset.asin;
    var market = this.dataset.market;
    var url = ajax_object.url;
    $.ajax({
      type: "POST",
      url: url,
      data:{
        action : "naaa_delete_by_asin",
        nonce : ajax_object.seguridad,
        asin : asin,
        market : market
      },
      success: function(response){
        alert(response);
        location.reload();  
      }
    });
  });

});