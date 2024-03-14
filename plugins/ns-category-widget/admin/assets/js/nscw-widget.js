jQuery(document).ready( function($){

  $('body').on( 'click', '.btn-show-advanced-tree-settings', function(e){
    e.preventDefault();
    $(this).parent().parent().find('.advanced-tree-settings-wrap').fadeToggle();

  })

  jQuery('body').on('change','.nscw-taxonomy',function(){

    var tthis = $(this);

    var our_data = new Object();
    our_data.action = 'populate_categories';
    our_data.taxonomy = $(this).val();
    our_data.name = $(this).data('name');
    our_data.id = $(this).data('id');

    jQuery.ajax({
      url: ns_category_widget_ajax_object.ajaxurl,
      type: 'POST',
      data: our_data,
      success: function( result ){
        if ( 1 == result.status) {
          our_html = result.html;
          var target = $(tthis).parent().parent().find('.nscw-cat-list');
          $(target).html(our_html);
        }
      }
    });

  }); // end change .nscw-taxonomy

//////////////////////////////
});
