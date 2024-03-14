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

       var old_value = jQuery( '#_customize-input-enigma_slider_data' ).val();
       jQuery( '#_customize-input-enigma_slider_data' ).val( serialize( slider ) );
       if( old_value.length !== 0 ) {
          jQuery( '#_customize-input-enigma_slider_data' ).trigger('change');
          jQuery('body').find('#customize-save-button-wrapper #save').attr("value", "Publish");
          jQuery('body').find('#customize-save-button-wrapper #save').removeAttr("disabled");
          jQuery('body').find('#customize-save-button-wrapper #save').trigger('click');
       } else {
          jQuery( '#_customize-input-enigma_slider_data' ).trigger('change');
          jQuery('body').find('#customize-save-button-wrapper #save').trigger('click');
       }
    });

  /* Service block js */
jQuery(document).ready(function() {
    var max_fields = 20;
    var wrapper    = jQuery("#input_fields_wrap-service");
    var add_button = jQuery("#add_field_button-service");

    var x = 0;
    jQuery(add_button).click(function(e){
        e.preventDefault();
        if(x < max_fields){
            x++;
            jQuery(wrapper).append('<div class="wl-dynamic-fields"><div class="form-group"><label for="title" class="col-form-label wl-txt-label">Service Title</label><input type="text" class="form-control" id="service_title-'+x+'" name="service_title-'+x+'" placeholder="Enter title"></div><div class="form-group"><label for="service_icon" class="col-form-label wl-txt-label">Service Icon '+x+'</label><input data-placement="bottomRight" id="service_icon" name="service_icon-'+x+'" class="form-control icp icp-auto-'+x+' service_icon" value="fas fa-archive" type="text"/><span class="input-group-addon"></span></div><div class="form-group"><label for="link" class="col-form-label wl-txt-label">Service Link</label><input type="text" class="form-control" id="service_link-'+x+'" name="service_link-'+x+'" placeholder="Enter link"></div><div class="form-group"><label for="service_desc-'+x+'" class="col-form-label wl-txt-label">Service Description</label><textarea class="form-control" rows="5" id="service_desc-'+x+'" name="service_desc-'+x+'" placeholder="Description"></textarea></div><a href="#" class="btn btn-danger btn-sm remove_field">Remove</a></div>');

            jQuery('.icp-auto-'+x).iconpicker({
        inline: true,
      });
        }
    });

    jQuery(wrapper).on("click",".remove_field", function(e){
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
          data.service_link = input[3].value;
          data.service_icon = input[1].value;
          data.service_desc = textarea[0].value;
          service.push(data);
       });

       //console.log( service );

     var old_value = jQuery( '#_customize-input-enigma_service_data' ).val();
     jQuery( '#_customize-input-enigma_service_data' ).val( serialize( service ) );
     if( old_value.lenght !== 0 ) {
        jQuery( '#_customize-input-enigma_service_data' ).trigger('change');
        jQuery('body').find('#customize-save-button-wrapper #save').attr("value", "Publish");
        jQuery('body').find('#customize-save-button-wrapper #save').removeAttr("disabled");
        jQuery('body').find('#customize-save-button-wrapper #save').trigger('click');
     } else {
        jQuery( '#_customize-input-enigma_service_data' ).trigger('change');
        jQuery('body').find('#customize-save-button-wrapper #save').trigger('click');
     }
  });
});



/* Portfolio block js */

    var max_fields = 40;
    var wrapper    = jQuery("#input_fields_wrap-portfolio");
    var add_button = jQuery("#add_field_button-portfolio");

    var x = 0;
    jQuery(add_button).click(function(e){
        e.preventDefault();

        if ( jQuery(".wl-dynamic-fields").parents("#input_fields_wrap-portfolio").length == 1 ) {
          var count = jQuery("#input_fields_wrap-portfolio").children().length;
          x = count;
        }

        if(x < max_fields){
            jQuery(wrapper).append('<div class="wl-dynamic-fields"><div class="form-group"><label for="portfolio_name-'+x+'" class="col-form-label wl-txt-label">Portfolio Name</label><input type="text" class="form-control" id="portfolio_name-'+x+'" name="portfolio_name-'+x+'" placeholder="Enter Name"></div><div class="form-group"><label for="portfolio_image-'+x+'" class="col-form-label wl-txt-label">Portfolio Image</label><input type="text" name="portfolio_image-'+x+'" id="portfolio_image-'+x+'" class="form-control portfolio_image" value=""><input type="button" name="upload-btn" class="button-secondary button upload_image_btn upload_portfolio" id="upload_portfolio-'+x+'" value="Upload"></div><div class="form-group"><label for="portfolio_link" class="col-form-label wl-txt-label">Link</label><input type="text" class="form-control" id="portfolio_link-'+x+'" name="portfolio_link-'+x+'"></div><a href="#" class="btn btn-danger btn-sm remove_field">Remove</a></div>');

      jQuery('.upload_portfolio').click(function (e) {
            e.preventDefault();
            var button = this;
            var image  = wp.media({
                title: 'Upload Image',
                multiple: false
            }).open()
                .on('select', function (e) {
                    var uploaded_image = image.state().get('selection').first();
                    var old_image      = jQuery( button ).parent().find('.portfolio_image').val();
                    var location_image = uploaded_image.toJSON().url;
                    
                    if ( old_image.lenght !== 0 ){
                      jQuery('#upload_portfolio-'+x).siblings('input').val('');
                    }

                    jQuery( button ).parent().find('.portfolio_image').val(location_image);
                    jQuery( button ).parent().find('img').remove();
                    jQuery( button ).parent().prepend('<img class="wl-upload-img-tag" src="'+location_image+'" />');
                });
        });
        } else {
          alert('You can add only 10 Portfolio.')
        }
    });

    jQuery(wrapper).on("click",".remove_field", function(e){
        e.preventDefault(); jQuery(this).parent('div').remove(); x--;
    });

    jQuery("#wl-ext-submit-portfolio").click(function(e){
       e.preventDefault();

       var portfolios = [];
       jQuery('#input_fields_wrap-portfolio .wl-dynamic-fields').each(function() {
        var data = {};
          var input = jQuery(this).find('input');
          data.portfolio_name = input[0].value;
          data.portfolio_image = input[1].value;
          data.portfolio_link = input[3].value;
          portfolios.push(data);
       });

       //console.log(portfolios);

     var old_value = jQuery( '#_customize-input-enigma_portfolio_data' ).val();
     jQuery( '#_customize-input-enigma_portfolio_data' ).val( serialize( portfolios ) );
     if( old_value.length !== 0 ) {
        jQuery( '#_customize-input-enigma_portfolio_data' ).trigger('change');
        jQuery('body').find('#customize-save-button-wrapper #save').attr("value", "Publish");
        jQuery('body').find('#customize-save-button-wrapper #save').removeAttr("disabled");
        jQuery('body').find('#customize-save-button-wrapper #save').trigger('click');
     } else {
        jQuery( '#_customize-input-enigma_portfolio_data' ).trigger('change');
        jQuery('body').find('#customize-save-button-wrapper #save').trigger('click');
     }
  });

   /* Team block js */
jQuery(document).ready(function() {
    var max_fields = 20;
    var wrapper    = jQuery("#input_fields_wrap-team");
    var add_button = jQuery("#add_field_button-team");

    var x = 0;
    jQuery(add_button).click(function(e){
        e.preventDefault();

        if ( jQuery(".wl-dynamic-fields").parents("#input_fields_wrap-team").length == 1 ) {
          var count = jQuery("#input_fields_wrap-team").children().length;
          x = count;
        }

        if(x < max_fields){
            jQuery(wrapper).append('<div class="wl-dynamic-fields"><div class="form-group"><label for="title" class="col-form-label wl-txt-label">Team Member Name</label><input type="text" class="form-control" id="team_name-'+x+'" name="team_name-'+x+'" placeholder="Enter Name"></div><div class="form-group"><label for="designation" class="col-form-label wl-txt-label">Team Member Designation</label><input type="text" class="form-control" id="team_designation-'+x+'" name="team_designation-'+x+'" placeholder="Enter Designation" value=""></div><div class="form-group"><label for="team_image-'+x+'" class="col-form-label wl-txt-label">Profile Picture</label><input type="text" name="team_image-'+x+'" id="team_image-'+x+'" class="form-control team_image" value=""><input type="button" name="upload-btn" class="button-secondary button upload_image_btn upload_team" id="upload_team-'+x+'" value="Upload"></div><div class="form-group"><label for="team_btn_txt" class="col-form-label wl-txt-label">Facebook Link</label><input type="text" class="form-control" id="team_btn_txt-'+x+'" name="team_btn_txt-'+x+'" placeholder=" "></div><div class="form-group"><label for="team_btn_link" class="col-form-label wl-txt-label">Twitter Link</label><input type="text" class="form-control" id="team_btn_link-'+x+'" name="team_btn_link-'+x+'" placeholder=" "></div><div class="form-group"><label for="team_ldn_link" class="col-form-label wl-txt-label">Linkedin Link</label><input type="text" class="form-control" id="team_ldn_link-'+x+'" name="team_ldn_link-'+x+'" placeholder=" "></div><a href="#" class="btn btn-danger btn-sm remove_field">Remove</a></div>');

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

    jQuery(wrapper).on("click",".remove_field", function(e){
        e.preventDefault(); jQuery(this).parent('div').remove(); x--;
    });

    jQuery("#wl-ext-submit-team").click(function(e){
       e.preventDefault();

     var team = [];
       jQuery('#input_fields_wrap-team .wl-dynamic-fields').each(function() {
        var data       = {};
          var input      = jQuery(this).find('input');
          data.team_name = input[0].value;
          data.team_designation = input[1].value;
          data.team_image = input[2].value;
          data.team_text = input[4].value;
          data.team_link = input[5].value;
          data.team_ldn_link = input[6].value;
          team.push(data);
       });

       console.log( team );

     var old_value = jQuery( '#_customize-input-enigma_team_data' ).val();
     jQuery( '#_customize-input-enigma_team_data' ).val( serialize( team ) );
     if( old_value.length !== 0 ) {
        jQuery( '#_customize-input-enigma_team_data' ).trigger('change');
        jQuery('body').find('#customize-save-button-wrapper #save').attr("value", "Publish");
        jQuery('body').find('#customize-save-button-wrapper #save').removeAttr("disabled");
        jQuery('body').find('#customize-save-button-wrapper #save').trigger('click');
     } else {
        jQuery( '#_customize-input-enigma_team_data' ).trigger('change');
        jQuery('body').find('#customize-save-button-wrapper #save').trigger('click');
     }
  })});


  /*************** Common js *****************/
 

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

    /* Service */
    jQuery('.upload_service_c').click(function (e) {
        e.preventDefault();
        var button = this;
        var image  = wp.media({
            title: 'Upload Image',
            multiple: false
        }).open()
            .on('select', function (e) {
              var uploaded_image = image.state().get('selection').first();
                var old_image      = jQuery( button ).parent().find('.service_image').val();
                var location_image = uploaded_image.toJSON().url;

                if ( old_image.lenght !== 0 ){
                  jQuery( button ).parent().find('.service_image').val('');
                }
                
                jQuery( button ).parent().find('.service_image').val(location_image);
                jQuery( button ).parent().find('img').remove();
            jQuery( button ).parent().prepend('<img class="wl-upload-img-tag" src="'+location_image+'" />');
            });
    });

    /* Portfolio */
    jQuery('.upload_portfolio_c').click(function (e) {
        e.preventDefault();
        var button = this;
        var image  = wp.media({
            title: 'Upload Image',
            multiple: false
        }).open()
            .on('select', function (e) {
                var uploaded_image = image.state().get('selection').first();
                var old_image      = jQuery( button ).parent().find('.portfolio_image').val();
                var location_image = uploaded_image.toJSON().url;
                
                if ( old_image.lenght !== 0 ){
                  jQuery( button ).parent().find('.portfolio_image').val('');
                }

                jQuery( button ).parent().find('.portfolio_image').val(location_image);
                jQuery( button ).parent().find('img').remove();
            jQuery( button ).parent().prepend('<img class="wl-upload-img-tag" src="'+location_image+'" />');
            });
    });

});