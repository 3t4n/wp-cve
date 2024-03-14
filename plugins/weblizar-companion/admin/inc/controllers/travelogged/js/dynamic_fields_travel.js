/** 
 *  Custom Fields 
 *  Copyright 2016-2018 
 *  Licensed under the MIT license
 *  Developed By CP at weblizar  
 */
function serialize (mixedValue) {
  var val, key, okey
  var ktype = ''
  var vals = ''
  var count = 0

  var _utf8Size = function (str) {
    return ~-encodeURI(str).split(/%..|./).length
  }

  var _getType = function (inp) {
    var match
    var key
    var cons
    var types
    var type = typeof inp

    if (type === 'object' && !inp) {
      return 'null'
    }

    if (type === 'object') {
      if (!inp.constructor) {
        return 'object'
      }
      cons = inp.constructor.toString()
      match = cons.match(/(\w+)\(/)
      if (match) {
        cons = match[1].toLowerCase()
      }
      types = ['boolean', 'number', 'string', 'array']
      for (key in types) {
        if (cons === types[key]) {
          type = types[key]
          break
        }
      }
    }
    return type
  }

  var type = _getType(mixedValue)

  switch (type) {
    case 'function':
      val = ''
      break
    case 'boolean':
      val = 'b:' + (mixedValue ? '1' : '0')
      break
    case 'number':
      val = (Math.round(mixedValue) === mixedValue ? 'i' : 'd') + ':' + mixedValue
      break
    case 'string':
      val = 's:' + _utf8Size(mixedValue) + ':"' + mixedValue + '"'
      break
    case 'array':
    case 'object':
      val = 'a'

      for (key in mixedValue) {
        if (mixedValue.hasOwnProperty(key)) {
          ktype = _getType(mixedValue[key])
          if (ktype === 'function') {
            continue
          }

          okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key)
          vals += serialize(okey) + serialize(mixedValue[key])
          count++
        }
      }
      val += ':' + count + ':{' + vals + '}'
      break
    case 'undefined':
    default:
      val = 'N'
      break
  }
  if (type !== 'object' && type !== 'array') {
    val += ';'
  }

  return val
}

wp.customize.bind( 'ready', function() {
    wp.customize.state( 'saved' ).set( false );
} );

/* Generate dynamic bloks */
jQuery(document).ready(function() {

  /* Slider options */
  var slider_max_fields = 20;
  var slider_wrapper    = jQuery("#input_fields_wrap-slider");
  var slider_add_button = jQuery("#add_field_button-slider");

  var x = 0;
    jQuery(slider_add_button).click(function(e){
        e.preventDefault();

        if ( jQuery(".wl-dynamic-fields").parents("#input_fields_wrap-slider").length == 1 ) {
          var count = jQuery("#input_fields_wrap-slider").children().length;
          x = count;
        }

        if(x < slider_max_fields){
            jQuery(slider_wrapper).append('<div class="wl-dynamic-fields"><div class="form-group"><label for="title" class="col-form-label wl-txt-label">Title</label><input type="text" class="form-control" id="slider_name-'+x+'" name="slider_name-'+x+'" placeholder="Slide title"></div><div class="form-group"><label for="slider_desc-'+x+'" class="col-form-label wl-txt-label">Description</label><textarea class="form-control" rows="5" id="slider_desc-'+x+'" name="slider_desc-'+x+'" placeholder="Description"></textarea></div><div class="form-group"><label for="slider_image-'+x+'" class="col-form-label wl-txt-label">Slide Image</label><input type="text" name="slider_image-'+x+'" id="slider_image-'+x+'" class="form-control slider_image" value=""><input type="button" name="upload-btn" class="button-secondary button upload_image_btn upload_slider" id="upload_slider-'+x+'" value="Upload"></div><div class="form-group"><label for="slider_btn_txt" class="col-form-label wl-txt-label">Button text</label><input type="text" class="form-control" id="slider_btn_txt-'+x+'" name="slider_btn_txt-'+x+'" placeholder=" View Profile"></div><div class="form-group"><label for="slider_btn_link" class="col-form-label wl-txt-label">Button Link</label><input type="text" class="form-control" id="slider_btn_link-'+x+'" name="slider_btn_link-'+x+'" placeholder="https://example.com"></div><a href="#" class="btn btn-danger btn-sm remove_field">Remove</a></div>');

      jQuery('.upload_slider').click(function (e) {
            e.preventDefault();
            var button = this;
            var image  = wp.media({
                title: 'Upload Image',
                multiple: false
            }).open()
                .on('select', function (e) {
                  var uploaded_image = image.state().get('selection').first();
                    var old_image      = jQuery( button ).parent().find('.slider_image').val();
                    var location_image = uploaded_image.toJSON().url;

                    if ( old_image.lenght !== 0 ){
                      jQuery('#upload_slider-'+x).siblings('input').val('');
                    }
                    
                    jQuery( button ).parent().find('.slider_image').val(location_image);
                    jQuery( button ).parent().find('img').remove();
                    jQuery( button ).parent().prepend('<img class="wl-upload-img-tag" src="'+location_image+'" />');
                });
        });
        }
    });

    jQuery(slider_wrapper).on("click",".remove_field", function(e){
        e.preventDefault(); jQuery(this).parent('div').remove(); x--;
    });

    jQuery("#wl-ext-submit-slider").click(function(e){
       e.preventDefault();

     var slider = [];
       jQuery('#input_fields_wrap-slider .wl-dynamic-fields').each(function() {
        var data       = {};
          var input      = jQuery(this).find('input');
          var textarea   = jQuery(this).find('textarea');
          data.slider_name = input[0].value;
          data.slider_desc = textarea[0].value;
          data.slider_image = input[1].value;
          data.slider_text = input[3].value;
          data.slider_link = input[4].value;
          slider.push(data);
       });

       console.log( slider );

     var old_value = jQuery( '#_customize-input-travelogged_slider_data' ).val();
     jQuery( '#_customize-input-travelogged_slider_data' ).val( serialize( slider ) );
     if( old_value.length !== 0 ) {
        jQuery( '#_customize-input-travelogged_slider_data' ).trigger('change');
        jQuery('body').find('#customize-save-button-wrapper #save').attr("value", "Publish");
        jQuery('body').find('#customize-save-button-wrapper #save').removeAttr("disabled");
        jQuery('body').find('#customize-save-button-wrapper #save').trigger('click');
     } else {
        jQuery( '#_customize-input-travelogged_slider_data' ).trigger('change');
        jQuery('body').find('#customize-save-button-wrapper #save').trigger('click');
     }
    });

    /* Service options */
    var service_max_fields = 20;
    var service_wrapper    = jQuery("#input_fields_wrap-service");
    var service_add_button = jQuery("#add_field_button-service");

    var x = 0;
    jQuery(service_add_button).click(function(e){
        e.preventDefault();
        if(x < service_max_fields){
            x++;
            jQuery(service_wrapper).append('<div class="wl-dynamic-fields"><div class="form-group"><label for="title" class="col-form-label wl-txt-label">Service Title</label><input type="text" class="form-control" id="service_title-'+x+'" name="service_title-'+x+'" placeholder="Enter title"></div><div class="form-group"><label for="service_icon" class="col-form-label wl-txt-label">Service Icon '+x+'</label><input data-placement="bottomRight" id="service_icon" name="service_icon-'+x+'" class="form-control icp icp-auto-'+x+' service_icon" value="fas fa-archive" type="text"/><span class="input-group-addon"></span></div><div class="form-group"><label for="service_desc-'+x+'" class="col-form-label wl-txt-label">Service Description</label><textarea class="form-control" rows="5" id="service_desc-'+x+'" name="service_desc-'+x+'" placeholder="Description"></textarea></div><a href="#" class="btn btn-danger btn-sm remove_field">Remove</a></div>');

            jQuery('.icp-auto-'+x).iconpicker({
        inline: true,
      });
        }
    });

    jQuery(service_wrapper).on("click",".remove_field", function(e){
        e.preventDefault(); jQuery(this).parent('div').remove(); x--;
    });

    jQuery("#wl-ext-submit-service").click(function(e){
       e.preventDefault();

     var service = [];
       jQuery('#input_fields_wrap-service .wl-dynamic-fields').each(function() {
        var data = {};
          var input    = jQuery(this).find('input');
          var textarea = jQuery(this).find('textarea');
          data.service_name = input[0].value;
          data.service_icon = input[1].value;
          data.service_desc = textarea[0].value;
          service.push(data);
       });

       //console.log( service );

     var old_value = jQuery( '#_customize-input-travelogged_service_data' ).val();
     jQuery( '#_customize-input-travelogged_service_data' ).val( serialize( service ) );
     if( old_value.lenght !== 0 ) {
        jQuery( '#_customize-input-travelogged_service_data' ).trigger('change');
        jQuery('body').find('#customize-save-button-wrapper #save').attr("value", "Publish");
        jQuery('body').find('#customize-save-button-wrapper #save').removeAttr("disabled");
        jQuery('body').find('#customize-save-button-wrapper #save').trigger('click');
     } else {
        jQuery( '#_customize-input-travelogged_service_data' ).trigger('change');
        jQuery('body').find('#customize-save-button-wrapper #save').trigger('click');
     }
  });

  /* Team options */
    var team_max_fields = 20;
    var team_wrapper    = jQuery("#input_fields_wrap-team");
    var team_add_button = jQuery("#add_field_button-team");

    var x = 0;
    jQuery(team_add_button).click(function(e){
        e.preventDefault();

        if ( jQuery(".wl-dynamic-fields").parents("#input_fields_wrap-team").length == 1 ) {
          var count = jQuery("#input_fields_wrap-team").children().length;
          x = count;
        }

        if(x < team_max_fields){
            jQuery(team_wrapper).append('<div class="wl-dynamic-fields"><div class="form-group"><label for="title" class="col-form-label wl-txt-label">Team Member Name</label><input type="text" class="form-control" id="team_name-'+x+'" name="team_name-'+x+'" placeholder="Enter Name"></div><div class="form-group"><label for="team_desc-'+x+'" class="col-form-label wl-txt-label">Designation</label><input class="form-control" id="team_desc-'+x+'" name="team_desc-'+x+'" placeholder="Designation"></div><div class="form-group"><label for="team_image-'+x+'" class="col-form-label wl-txt-label">Profile Picture</label><input type="text" name="team_image-'+x+'" id="team_image-'+x+'" class="form-control team_image" value=""><input type="button" name="upload-btn" class="button-secondary button upload_image_btn upload_team" id="upload_team-'+x+'" value="Upload"></div><div class="form-group"><label for="fb_link" class="col-form-label wl-txt-label">FB Link</label><input type="text" class="form-control" id="fb_link-'+x+'" name="fb_link-'+x+'" placeholder="https://example.com"></div><div class="form-group"><label for="twitter_link" class="col-form-label wl-txt-label">Twitter Link</label><input type="text" class="form-control" id="twitter_link-'+x+'" name="twitter_link-'+x+'" placeholder="https://example.com"></div><div class="form-group"><label for="insta_link" class="col-form-label wl-txt-label">Instagram Link</label><input type="text" class="form-control" id="insta_link-'+x+'" name="insta_link-'+x+'" placeholder="https://example.com"></div><div class="form-group"><label for="google_plus_link" class="col-form-label wl-txt-label">Google Plus Link</label><input type="text" class="form-control" id="google_plus_link-'+x+'" name="google_plus_link-'+x+'" placeholder="https://example.com"></div><div class="form-group"><label for="youtube_link" class="col-form-label wl-txt-label">Youtube Link</label><input type="text" class="form-control" id="youtube_link-'+x+'" name="youtube_link-'+x+'" placeholder="https://example.com"></div><a href="#" class="btn btn-danger btn-sm remove_field">Remove</a></div>');

      jQuery('.upload_team').click(function (e) {
            e.preventDefault();
            var button = this;
            var image  = wp.media({
                title: 'Upload Image',
                multiple: false
            }).open()
                .on('select', function (e) {
                  var uploaded_image = image.state().get('selection').first();
                    var old_image      = jQuery( button ).parent().find('.team_image').val();
                    var location_image = uploaded_image.toJSON().url;

                    if ( old_image.lenght !== 0 ){
                      jQuery('#upload_team-'+x).siblings('input').val('');
                    }
                    
                    jQuery( button ).parent().find('.team_image').val(location_image);
                    jQuery( button ).parent().find('img').remove();
                    jQuery( button ).parent().prepend('<img class="wl-upload-img-tag" src="'+location_image+'" />');
                });
        });
        }
    });

    jQuery(team_wrapper).on("click",".remove_field", function(e){
        e.preventDefault(); jQuery(this).parent('div').remove(); x--;
    });

    jQuery("#wl-ext-submit-team").click(function(e){
       e.preventDefault();

     var team = [];
       jQuery('#input_fields_wrap-team .wl-dynamic-fields').each(function() {
        var data                = {};
          var input             = jQuery(this).find('input');
          data.team_name        = input[0].value;
          data.team_designation = input[1].value;
          data.team_image       = input[2].value;
          data.fb_link          = input[4].value;
          data.twitter_link     = input[5].value;
          data.insta_link       = input[6].value;
          data.google_plus_link = input[7].value;
          data.youtube_link     = input[8].value;
          team.push(data);
       });

     var old_value = jQuery( '#_customize-input-travelogged_team_data' ).val();
     jQuery( '#_customize-input-travelogged_team_data' ).val( serialize( team ) );
     if( old_value.length !== 0 ) {
        jQuery( '#_customize-input-travelogged_team_data' ).trigger('change');
        jQuery('body').find('#customize-save-button-wrapper #save').attr("value", "Publish");
        jQuery('body').find('#customize-save-button-wrapper #save').removeAttr("disabled");
        jQuery('body').find('#customize-save-button-wrapper #save').trigger('click');
     } else {
        jQuery( '#_customize-input-travelogged_team_data' ).trigger('change');
        jQuery('body').find('#customize-save-button-wrapper #save').trigger('click');
     }
  });


  /* Destination options */
    var desti_max_fields = 20;
    var desti_wrapper    = jQuery("#input_fields_wrap-destination");
    var desti_add_button = jQuery("#add_field_button-destination");

    var x = 0;
    jQuery(desti_add_button).click(function(e){
        e.preventDefault();

        if ( jQuery(".wl-dynamic-fields").parents("#input_fields_wrap-destination").length == 1 ) {
          var count = jQuery("#input_fields_wrap-destination").children().length;
          x = count;
        }

        if(x < desti_max_fields ){
            jQuery(desti_wrapper).append('<div class="wl-dynamic-fields"><div class="form-group"><label for="title" class="col-form-label wl-txt-label">DestinationName</label><input type="text" class="form-control" id="desti_name-'+x+'" name="desti_name-'+x+'" placeholder="Enter Name"></div><div class="form-group"><label for="desti_desc-'+x+'" class="col-form-label wl-txt-label">Description</label><textarea class="form-control" rows="5" id="team_desc-'+x+'" name="desti_desc-'+x+'" placeholder="Description"></textarea></div><div class="form-group"><label for="desti_image-'+x+'" class="col-form-label wl-txt-label">Destination Picture</label><input type="text" name="desti_image-'+x+'" id="desti_image-'+x+'" class="form-control desti_image" value=""><input type="button" name="upload-btn" class="button-secondary button upload_image_btn upload_desti" id="upload_desti-'+x+'" value="Upload"></div><div class="form-group"><label for="ratings" class="col-form-label wl-txt-label">Ratings</label><select class="form-control" id="ratings-'+x+'" name="ratings-'+x+'" required><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></div><div class="form-group"><label for="desti_duration" class="col-form-label wl-txt-label">Package Duration</label><input type="text" class="form-control" id="desti_duration-'+x+'" name="desti_duration-'+x+'" placeholder="4 Days-5 Nights"></div><div class="form-group"><label for="btn_text" class="col-form-label wl-txt-label">Button Text</label><input type="text" class="form-control" id="btn_text-'+x+'" name="btn_text-'+x+'" placeholder="Readmore"></div><div class="form-group"><label for="btn_link" class="col-form-label wl-txt-label">Button Link</label><input type="text" class="form-control" id="btn_link-'+x+'" name="btn_link-'+x+'" placeholder="https://example.com"></div><a href="#" class="btn btn-danger btn-sm remove_field">Remove</a></div>');

      jQuery('.upload_desti').click(function (e) {
            e.preventDefault();
            var button = this;
            var image  = wp.media({
                title: 'Upload Image',
                multiple: false
            }).open()
                .on('select', function (e) {
                  var uploaded_image = image.state().get('selection').first();
                  var old_image      = jQuery( button ).parent().find('.desti_image').val();
                  var location_image = uploaded_image.toJSON().url;

                  if ( old_image.lenght !== 0 ){
                    jQuery('#upload_desti-'+x).siblings('input').val('');
                  }
                    
                  jQuery( button ).parent().find('.desti_image').val(location_image);
                  jQuery( button ).parent().find('img').remove();
                  jQuery( button ).parent().prepend('<img class="wl-upload-img-tag" src="'+location_image+'" />');
            });
        });
        }
    });

    jQuery(desti_wrapper).on("click",".remove_field", function(e){
        e.preventDefault(); jQuery(this).parent('div').remove(); x--;
    });

    jQuery("#wl-ext-submit-destination").click(function(e){
       e.preventDefault();

     var destination = [];
       jQuery('#input_fields_wrap-destination .wl-dynamic-fields').each(function() {
          var data            = {};
          var input           = jQuery(this).find('input');
          var textarea        = jQuery(this).find('textarea');
          var select          = jQuery(this).find('select');
          data.desti_name     = input[0].value;
          data.desti_desc     = textarea[0].value;
          data.desti_image    = input[1].value;
          data.desti_rating   = select[0].value;
          data.desti_duration = input[3].value;
          data.desti_text     = input[4].value;
          data.desti_link     = input[5].value;
          destination.push(data);
       });

     var old_value = jQuery( '#_customize-input-travelogged_destination_data' ).val();
     jQuery( '#_customize-input-travelogged_destination_data' ).val( serialize( destination ) );
     if( old_value.length !== 0 ) {
        jQuery( '#_customize-input-travelogged_destination_data' ).trigger('change');
        jQuery('body').find('#customize-save-button-wrapper #save').attr("value", "Publish");
        jQuery('body').find('#customize-save-button-wrapper #save').removeAttr("disabled");
        jQuery('body').find('#customize-save-button-wrapper #save').trigger('click');
     } else {
        jQuery( '#_customize-input-travelogged_destination_data' ).trigger('change');
        jQuery('body').find('#customize-save-button-wrapper #save').trigger('click');
     }
  });




  /*************** Common js *****************/
  /* Destination */
    jQuery('.upload_desti_c').click(function (e) {
        e.preventDefault();
        var button = this;
        var image  = wp.media({
            title: 'Upload Image',
            multiple: false
        }).open()
            .on('select', function (e) {
              var uploaded_image = image.state().get('selection').first();
                var old_image      = jQuery( button ).parent().find('.desti_image').val();
                var location_image = uploaded_image.toJSON().url;

                if ( old_image.lenght !== 0 ){
                  jQuery( button ).parent().find('.desti_image').val('');
                }
                
                jQuery( button ).parent().find('.desti_image').val(location_image);
                jQuery( button ).parent().find('img').remove();
            jQuery( button ).parent().prepend('<img class="wl-upload-img-tag" src="'+location_image+'" />');
            });
    });

  /* Team */
    jQuery('.upload_team_c').click(function (e) {
        e.preventDefault();
        var button = this;
        var image  = wp.media({
            title: 'Upload Image',
            multiple: false
        }).open()
            .on('select', function (e) {
              var uploaded_image = image.state().get('selection').first();
                var old_image      = jQuery( button ).parent().find('.team_image').val();
                var location_image = uploaded_image.toJSON().url;

                if ( old_image.lenght !== 0 ){
                  jQuery( button ).parent().find('.team_image').val('');
                }
                
                jQuery( button ).parent().find('.team_image').val(location_image);
                jQuery( button ).parent().find('img').remove();
            jQuery( button ).parent().prepend('<img class="wl-upload-img-tag" src="'+location_image+'" />');
            });
    });

    /* Slider */
    jQuery('.upload_slider_c').click(function (e) {
        e.preventDefault();
        var button = this;
        var image  = wp.media({
            title: 'Upload Image',
            multiple: false
        }).open()
            .on('select', function (e) {
              var uploaded_image = image.state().get('selection').first();
                var old_image      = jQuery( button ).parent().find('.slider_image').val();
                var location_image = uploaded_image.toJSON().url;

                if ( old_image.lenght !== 0 ){
                  jQuery( button ).parent().find('.slider_image').val('');
                }
                
                jQuery( button ).parent().find('.slider_image').val(location_image);
                jQuery( button ).parent().find('img').remove();
            jQuery( button ).parent().prepend('<img class="wl-upload-img-tag" src="'+location_image+'" />');
            });
    });

});