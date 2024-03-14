(function ( $ ) {
  $.fn.cf7_gr_change_select_fieldsx = function() {
   
      return this.each(function() {
        var $this = $(this);
        var dValue = $this.data('value');
        var newvalue = $this.data('newvalue');
        $this.find('option').each( function(){


          if( $(this).attr('value') == newvalue  ){
            $(this).attr('selected','selected');
          }
          else if( $(this).attr('value') == dValue ){
            $(this).attr('selected','selected');
          }

        });
      });
   
  };
}( jQuery ));

// Define string match function
RegExp.prototype.execAll = function(string) {
var match = null;
var matches = new Array();
while (match = this.exec(string)) {
        var matchArray = [];
        for (i in match) {
            if (parseInt(i) == i) {
                matchArray.push(match[i]);
            }
        }
        matches.push(matchArray);
    }
    return matches;
}

function cf7_gr_change_select_fields(){

  var str = jQuery('textarea#wpcf7-form').val();
  var res = /[[a-z\*]+\s([a-z0-9\*-_]+)+(?:.*\])/g.execAll(str);
  var sHtml = "<option value=''>Select Field</option>";
  jQuery.each( res, function( index, val ){
      var name = val[1];
      sHtml+="<option value='["+name+"]'>"+name+"</option>";
  });
  jQuery('.field-names').html( sHtml )
  jQuery('.field-names').cf7_gr_change_select_fieldsx();
}


jQuery( function( $ ){


      cf7_gr_change_select_fields( );

      $('textarea#wpcf7-form').focusout( function(){

        cf7_gr_change_select_fields( );
          
      });

      $('body').on( 'change','.field-names', function(){

        $(this).data( 'newvalue', $(this).val());
          
      });


      // Add now custom field on button click
      $( '#cf7-gs-add-custom-field' ).click( function(){

          // check the limit
          if( $("tr.custom-field-single").length > 19 ){
            alert('Max limit of custom fields is 20' );
            return false;
          }

          // Count number of current custom fields
          var countCustomFields = $("tr.custom-field-single:last").data( "cfid" );

          // Id for next custom field
          var nextCustomFieldId = parseInt( countCustomFields )+1;

          // Clone template
          var cloneTemplate = $( "tr.custom-field-template" ).clone();

          var newElement = cloneTemplate[0]['outerHTML'].replace( /{{ID}}/g, nextCustomFieldId );
          newElement = newElement.replace( /{{CFV_FIELD_ID}}/g, "cf7-gs-custom-value"+nextCustomFieldId );
          newElement = newElement.replace( /{{CFV_FIELD_NAME}}/g, "cf7-gs[custom_value]["+nextCustomFieldId+"]" );
          newElement = newElement.replace( /{{CFK_FIELD_ID}}/g, "cf7-gs-custom-key"+nextCustomFieldId );
          newElement = newElement.replace( /{{CFK_FIELD_NAME}}/g, "cf7-gs[custom_key]["+nextCustomFieldId+"]" );

          // Change cloned element values
          $( newElement ).insertAfter("tr.custom-field-single:last"  ).addClass( 'newClonedCustomFields' );

          var gr_field_names = $("select.gr-field-names").first().html();
          $( '.newClonedCustomFields' ).find( 'select.gr-field-names' )
                                       .html( gr_field_names )
                                       .children('option')
                                       .removeAttr( 'selected' );

          $( ".newClonedCustomFields" ).removeClass( 'custom-field-template' )
                                       .addClass( 'custom-field-single' ).fadeIn('slow');

          $( "tr.custom-field-single:last" ).removeClass( 'newClonedCustomFields' );

          return false;

      });

      // remvoe Custom Fields
      $( 'body' ).on( 'click', 'a.remove-custom-field', function(){

        if( confirm( cf7_options.messages.remove_alert ) ){

          var cfid = $(this).data('cfid');
          $( "tr[data-cfid="+cfid+"]").slideUp( 'slow', function(){
            $(this).remove();
          });

          return false;

        }
        return false;
      });


      $( 'body' ).on( 'click', 'a#cf7-gs-ext-update-camp', function(){
        $( 'a#cf7-gs-ext-update-camp' ).html( '<span class="spinner" style="visibility:visible;float: none;"></span> Loading...' );
        $.post( cf7_options.ajax_url,{action:'gs_update_camp'}, function(data){
            if( '' != data && 0 != data ){
              $( 'a#cf7-gs-ext-update-camp' ).html( '<span class="dashicons dashicons-yes"></span> Updated');
            }
            else{
              $( 'a#cf7-gs-ext-update-camp' ).html( '<span class="dashicons dashicons-no-alt"></span> Error Updating');
            }
        });
        return false;
      } );

      $( 'body' ).on( 'click', 'a#cf7-gs-ext-update-select-camp', function(){
        var $this = $(this);
        $this.html( '<span class="spinner" style="visibility:visible;float: none;"></span>' );
        $.post( cf7_options.ajax_url,{action:'gr_update_camp'}, function(data){
            if( '' != data && 0 != data ){
              $this.html( '<span class="dashicons dashicons-yes"></span>');
              // var campCount = _.size(data.gs_camp);
              var oHTML = '<option value="">' + cf7_options.messages.select_campaign + '</option>';

              $.each( data.gs_camp, function( key, value ) {
                  oHTML+= '<option value="'+value.campaignId+'">'+value.name+'</option>';
              });

              $this.prev().prev().html( oHTML );
            }
            else{
              $this.html( '<span class="dashicons dashicons-no-alt"></span>');
            }
        }, 'json');
        return false;
      } );

      $( 'body' ).on( 'click', 'a.cf7-gs-ext-update-custom-fields', function(){
        var $this = $(this);
        var select_field = "#cf7-gs-custom-key"+$(this).attr('data-cfid');
        $this.html( '<span class="spinner" style="visibility:visible;float: none;"></span>' );
        $.post( cf7_options.ajax_url,{action:'gr_update_custom_field'}, function(data){
            if( '' != data && 0 != data ){
              $this.html( '<span class="dashicons dashicons-yes"></span>');
              // var campCount = _.size(data.gs_camp);
              var oHTML = '<option value="">'+cf7_options.messages.select_custom_fields+'</option>';

              $.each( data.gs_custom_fields, function( key, value ) {
                  oHTML+= '<option value="'+value.customFieldId+'">'+value.name+'</option>';
              });

              $(select_field).html( oHTML );
            }
            else{
              $this.html( '<span class="dashicons dashicons-no-alt"></span>');
            }
        }, 'json');
        return false;
      } );
});
