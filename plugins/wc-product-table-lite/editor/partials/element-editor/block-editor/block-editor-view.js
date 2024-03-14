(function($, view){

  view.parent;

  view.render = function(){
    var $be = this.parent.$elm,
        parent = this.parent,
        view = this,
        data = this.parent.model.get_data();

    $be.empty(); // make blank

    $.each( data, function( index, row ){

      // create row
      var $row = $('<div class="wcpt-block-editor-row" data-id="'+ row.id +'">').data('wcpt-data', row);

      // append elements to row
      $.each( row.elements, function( el_index, element ){
        var $element = $('<div class="wcpt-element-block" data-type="'+ element.type +'" data-id="'+ element.id +'">'+ view.get_label(element) +'</div>');
        $row.append($element.data('wcpt-data', element));
      } )

      // add element trigger
      var $add_element = $('<a href="#" class="wcpt-block-editor-add-element">+ Add Element</a>');
      $row.append($add_element);

      // edit row trigger
      if( parent.config.edit_row ){
        var icon = $('#wcpt-icon-sliders').length ? $('#wcpt-icon-sliders').text() : '*',
            $settings = $('<span class="wcpt-block-editor-edit-row" title="Edit row settings">'+ icon +'</span>');

        view.maybe_add_active_settings_class($settings, row)
        $row.append($settings);
      }

      // delete row trigger
      if( 
        parent.config.delete_row && 
        (
          data.length > 1 || // multiple rows
          (
            typeof data[0].elements !== 'undefined' &&
            data[0].elements.length
          )
        )
      ){
        var icon = $('#wcpt-icon-x').length ? $('#wcpt-icon-x').text() : 'x',
            $del = $('<span class="wcpt-block-editor-delete-row" title="Delete row">'+ icon +'</span>');
        $row.append($del);
      }

      // append row to editor
      $be.append($row);
    } );

    // add row trigger
    if( this.parent.config.add_row ){
      var $add_row = $('<a href="#" class="wcpt-block-editor-add-row">+ Add Row</a>');
      $be.append( $add_row );
    }

    // make rows sortable
    if( $('.wcpt-block-editor-row', $be).length > 1 ){
      $be.sortable({
        items: '.wcpt-block-editor-row',
        disabled: false,
      });
    }else{
      $be.sortable({
        items: '.wcpt-block-editor-row',
        disabled: true,
      });
    }

    // connect with
    var cw = this.parent.config.connect_with,
        $lb = $be.closest('.wcpt-block-editor-lightbox-screen');
    if( $lb.length ){
      cw = '[data-partial="'+ $lb.attr('data-partial') +'"].wcpt-block-editor-lightbox-screen ' + cw;
    }

    // make blocks sortable
  	$('.wcpt-block-editor-row', $be).sortable({
      items: '.wcpt-element-block',
  		connectWith: cw,
  		placeholder: 'wcpt-element-block-placeholder',
  		forcePlaceholderSize: true,
  		start: function(event, ui){
  			// helper size
  			ui.helper
  				.width( ui.helper.width() + 1 )
  				.height('');

  			// placeholder width
  			ui.placeholder
  				.width( ui.item.outerWidth() )
  				.addClass('wcpt-element-block');

        // $(this).addClass('wcpt-block-editor-sorting');
        // $(this).siblings().addClass('wcpt-block-editor-sorting');
  		},
      sort: function(event, ui) {
          var $target = $(event.target);
          if( $target.closest('.wcpt-block-editor-lightbox-screen').length ){
            if (!/html|body/i.test($target.offsetParent()[0].tagName)) {
              var top = event.pageY - $target.offsetParent().offset().top - (ui.helper.outerHeight(true) * 1.5);
              ui.helper.css({'top' : top + 'px'});
            }
          }

      }

  	});

  }

  view.maybe_add_active_settings_class = function( $settings_icon, row_data ){
    active_settings = false,
    conditions = [
      'custom_field_enabled',
      'attribute_enabled',
      'category_enabled',
      'price_enabled',
      'stock_enabled',
      'product_type_enabled',
      'store_timings_enabled',
      'user_role_enabled'
    ];

    if( row_data.condition ){
      conditions.forEach(( _condition )=>{
        if( row_data.condition[_condition] ){
          active_settings = true;
        }
      })
    }

    if( row_data.style ){
      for ( const [selector, props] of Object.entries(row_data.style) ){
        for( const [prop, value] of Object.entries( props ) ){
          if( value ){
            active_settings = true;
          }
        }
      }
    }

    if( active_settings ){
      $settings_icon.addClass('wcpt-block-editor-edit-row--has-settings');
      $settings_icon.attr('title', 'The blue indicator shows this row has active style / condition settings');

    }else{
      $settings_icon.removeClass('wcpt-block-editor-edit-row--has-settings');				
      $settings_icon.attr('title', 'This row has no active style or condition settings');
      
    }			
  }

  view.lightbox = function(options){

    var default_ops = {
      $element: null,
      duplicate_remove: true,
      attr: {}
    };

    $.extend( true, default_ops, options );

    // create
    var $lightbox       = $('<div class="wcpt-block-editor-lightbox-screen"><div class="wcpt-block-editor-lightbox-content"></div></div>'),
        $tray           = $('<div class="wcpt-block-editor-lightbox-tray"></div>'),
        done_icon       = $('#wcpt-icon-check').length ? $('#wcpt-icon-check').text() : '',
        $done           = $('<span class="wcpt-block-editor-lightbox-done" title="Done">'+ done_icon +'</span>'),
        close_icon      = $('#wcpt-icon-x').length ? $('#wcpt-icon-x').text() : '',
        $close          = $('<span class="wcpt-block-editor-lightbox-close" title="Close">'+ close_icon +'</span>'),
        remove_icon     = $('#wcpt-icon-trash').length ? $('#wcpt-icon-trash').text() : '',
        $remove         = $('<span class="wcpt-block-editor-lightbox-remove" title="Trash">'+ remove_icon +'</span>'),
        duplicate_icon  = $('#wcpt-icon-copy').length ? $('#wcpt-icon-copy').text() : '',
        $duplicate      = $('<span class="wcpt-block-editor-lightbox-duplicate" title="Clone">'+ duplicate_icon +'</span>');


    if( options.duplicate_remove ){
      $tray.append( $done );
      $tray.append( $duplicate.add($remove) );
    }else{
      $tray.append( $close );
    }

    $('> .wcpt-block-editor-lightbox-content', $lightbox).append($tray);

    $('body').append($lightbox);

    $lightbox
      .data('wcpt-block-element', options.$element ? options.$element : '')
      .attr({
        'wcpt-controller': 'edit-element-lightbox',
        'wcpt-model-key': 'block_element_data',
      })
      .attr(options.attr)
      .children('.wcpt-block-editor-lightbox-content')
        .append($('script[data-wcpt-partial="'+ options.partial +'"]').text())
        .end()
      .show();

    // add modal flag
    $('body').addClass('wcpt-be-lightbox-on');

    // destroy
    // -- via screen click
    var _ = this;
    $lightbox.on('click', function(e){
      if( $(e.target).is($lightbox) ){
        $lightbox.trigger('destroy');
      }
    })
    // -- via close 'X' click
    $( '> .wcpt-block-editor-lightbox-content > .wcpt-block-editor-lightbox-tray > .wcpt-block-editor-lightbox-close, > .wcpt-block-editor-lightbox-content > .wcpt-block-editor-lightbox-tray > .wcpt-block-editor-lightbox-done', $lightbox ).on('click', function(){
      $lightbox.trigger('destroy');
    })
    // -- destroy event handler
    $lightbox.on('destroy', function(){
      $lightbox.change();
      $lightbox.remove();

      // remove modal flag
      if( ! $( '.wcpt-block-editor-lightbox-screen' ).length ){
        $('body').removeClass('wcpt-be-lightbox-on');
      }
    })

    // search
    var $search_input = $('.wcpt-block-editor-element-type-list__search__input', $lightbox);

    $search_input.on('keyup', function(){
      var $this = $(this),
          val = $this.val().trim(),
          $list = $this.closest('.wcpt-block-editor-element-type-list'),
          $elm_button = $('.wcpt-block-editor-element-type', $list);
  
      
      if( ! val ){
        $elm_button.show();
        return;
      }

      $elm_button.each(function(){
        var $this = $(this),
            label = $this.text().toLowerCase().trim();
        if( label.indexOf(val) == -1 ){
          $this.hide();
        }else{
          $this.show();
        }     
      })
    })

    $search_input.first().focus();

    return $lightbox;
  }

  view.get_label = function(element){

    var type_unslug = element.type.replace(/(_|^)([^_]?)/g, function(_, prep, letter) {
            return (prep && ' ') + letter.toUpperCase();
        }),
        label = type_unslug;

    switch (element.type) {
      case 'attribute':
      case 'attribute_filter':
        if( element.attribute_name ){

          if( 
            element.attribute_name == '_custom' &&
            element.custom_attribute_name
          ){
            label = element.custom_attribute_name + ' (custom)';

          }else{
            label = element.attribute_name;
            if( typeof window.wcpt_attributes == 'object' ){
              $.each(window.wcpt_attributes, function(key, val){
                if( val.attribute_name == element.attribute_name ){
                  label = val.attribute_label;
                }
              })
            }
          }

          label = 'Attribute: <span>' + view.sanitize(label.charAt(0).toUpperCase() + label.substr(1)) + '</span>';
        }
        break;

      case 'custom_field':
      case 'custom_field_filter':
        if( element.field_name ){
          label = 'Custom field: <span>' + view.sanitize(element.field_name) + '</span>';
        }
        break;

      case 'taxonomy':
      case 'taxonomy_filter':
        if( element.taxonomy ){
          label = 'Taxonomy: <span>' + view.sanitize(element.taxonomy) + '</span>';
        }
        break;

      case 'text':
      case 'text__col':
        if( element.text ){
          label = view.sanitize(element.text);
          if(label.length > 30){
            label = label.substring(0, 30) + '...';
          }
          label = 'Text: <span>"' + label + '"<span>';
        }else{
          label = 'Text';
        }
        break;

      case 'html' :
      case 'html__col' :
        if( element.html ){          
          label = view.sanitize(element.html);
          if(label.length > 20){
            label = label.substring(0, 20) + '...';
          }
          label = 'HTML: <span>"' + label + '"<span>';
          
        }else{
          label = 'HTML';
        }
        
        break;

      case 'shortcode':
        if( element.shortcode ){
          var shortcode = element.shortcode;
          if(shortcode.length > 30){
            shortcode = shortcode.substring(0, 30) + '...';
          }
          
          label = 'Shortcode: <span>' + view.sanitize(shortcode) + '</span>';
        }
        break;

      case 'sku' :
        label = 'SKU';
        break;

      case 'download_csv' :
        label = 'Download CSV';
        break;

      case 'media_image' :
      case 'media_image__col' :
        label = 'Media Image';
        break;

      case 'line_separator' :
        label = '← Line separator →';
        break;

      case 'space' :
      case 'space__col' :
        label = '← Space →';
        break;

      case 'product_id' :
        label = 'Product ID';
        break;

      case 'sorting':
        if( element.orderby ){
          label = 'Sort by: <span>';

          if( element.orderby == 'meta_value_num' || element.orderby == 'meta_value' ){
            label += ' CF - ' + element.meta_key;

          }else if( element.orderby == 'id' ){
            label += ' Product ID';

          }else if( element.orderby == 'sku' ){
            label += ' SKU as text';

          }else if( element.orderby == 'sku_num' ){
            label += ' SKU as integer';

          }else if( 
            -1 !== $.inArray( element.orderby, ['attribute', 'attribute_num'] ) &&
            element.orderby_attribute
          ){
            var attribute_label = element.orderby_attribute;
            $.each( wcpt_attributes, function( index, attribute ){
              if( 'pa_' + attribute.attribute_name == element.orderby_attribute ){
                attribute_label = attribute.attribute_label;
                return false;
              }
            } )

            label += ' Attribute - ' + view.sanitize( attribute_label );

          }else{
            label += element.orderby[0].toUpperCase() + element.orderby.substring(1);

          }

          label += '</span>';

        }
        break;

      case 'search':
        if( element.target && Array.isArray( element.target ) && element.target.length ){
          if( element.target.length == 1 ){
            var field = element.target[0];
            if(
              field == 'attribute' &&
              element.attributes &&
              element.attributes.length
            ){
              field += ': ' + element.attributes.join(", ");
            }

            if(
              field == 'custom_field' &&
              element.custom_fields &&
              element.custom_fields.length
            ){
              field += ': ' + element.custom_fields.join(", ");
            }
            
            if( field == 'sku' ){
              field = 'SKU';
            }

          }else{
            var mixed_fields = element.target.join(", ");
            if( mixed_fields.length > 45 ){
              mixed_fields = mixed_fields.substring(0, 45) + '...';
            }
            var field = "mixed "+ element.target.length +" ("+ mixed_fields +")";

          }
          field = view.sanitize(field);

          if( field.length > 70 ){
            field = field.substring(0, 70) + '...';
          }

          label = 'Search: <span>' + field + '</span>';
        }

        break;

      case 'apply_reset':
        label = 'Apply / Reset';
        break;  

      case 'date':
      case 'date_picker_filter':

        if( element.type == 'date' ){
          label = 'Date';
        }else if( element.type == 'date_picker_filter' ){
          label = 'Date Picker Filter';
        }
        
        if( element.date_source ){
          if( element.date_source == 'publish_date' ){
            label += ': <span>publish date</span>';

          }else if( element.date_source == 'wordpress_custom_field' ){
            var inner = '';
            if( element.custom_field_name ){
              inner = ' ('+ view.truncate( element.custom_field_name, 15 ) +')';
            }
            label += ': <span>custom field'+ inner +'</span>';

          }else if( element.date_source == 'acf_custom_field' ){
            var inner = '';
            if( element.acf_field_name ){
              inner = ' ('+ view.truncate( element.acf_field_name, 15 ) +')';
            }            
            label += ': <span>acf'+ inner +'</span>';
          }
        }

        break;  

      case 'regular_price__on_sale':
        label = 'Regular price';
        break;        

      case 'icon':
      case 'icon__col':        
        if( element.name ){
          label = '<img class="wcpt-icon-rep" src="'+ wcpt_icons + element.name +'.svg">';
        }
        break;

      case 'dot':
      case 'dot__col':
        label = '⋅';
        break;

      case 'select_variation':
        label = 'Select variation';

        // radio single
        if( typeof element.display_type == 'undefined' || element.display_type == 'radio_single' ){

          if( element.variation_name ){
            var name = view.sanitize(element.variation_name);
            label = 'Select variation: <span>' + name + '</span>';

          }else{
            label = 'Select variation: <span>*Single variation*</span>';
          }

        // radio multiple
        }else if( element.display_type == 'radio_multiple' ){
          label = 'Select variation: <span>*Radio buttons*</span>';

        // dropdown
        }else if( element.display_type == 'dropdown' ){
          label = 'Select variation: <span>*Dropdown*</span>';
        }

        break;
    }

    if( 
      element.type.split('__').length == 2 && 
      -1 == $.inArray( element.type, ['space__col', 'text__col', 'html__col', 'media_image__col', 'icon__col', 'dot__col'] ) 
    ){
      var string = element.type.split('__')[0];
      label = string.charAt(0).toUpperCase() + string.slice(1).replace('_', ' ');

    }

    return view.sanitize_preserve_span_and_image( label );
    // return label;
  },

  view.mark_elm = function( row_index, elm_index ){
    var $be = this.parent.$elm,
        $row = $be.children( '.wcpt-block-editor-row' ).eq(row_index),
        $target;

    if( $row.length ){
      var $elm = $row.children( '.wcpt-element-block' ).eq(elm_index);

      if( $elm.length ){
        $target = $elm;
      }else{
        $target = $row;
      }
    }

    $target.addClass('wcpt-be-mark');
    setTimeout( function(){
      $target.removeClass('wcpt-be-mark');
    }, 1250 );
  }

  view.sanitize = function(str, preserve_span_and_image){
    if( preserve_span_and_image ){
      // span
      str = str.replace(/<span>/g, '{span_open}').replace(/<\/span>/g, '{span_close}');

      // image
      var regex = /<img[^<]+?>/gim,
          image_matches = str.match(regex),      
          img_placeholders = [];

      if( image_matches ){
        for( var i = 0; i < image_matches.length; i++ ){
          img_placeholders.push(image_matches[i]);
          str = str.replace(image_matches[i], '#img-placeholder-' + i + '#');
        }
      }
    }

    str = str.replace(/</g, '&lt;').replace(/>/g, '&gt;')

    if( preserve_span_and_image ){
      // span
      str = str.replace(/{span_open}/g, '<span>').replace(/{span_close}/g, '</span>');

      // image
      if( image_matches ){
        for( var i = 0; i < image_matches.length; i++ ){
          str = str.replace('#img-placeholder-' + i + '#', image_matches[i]);
        }
      }
    }

    return str;
  }

  view.sanitize_preserve_span_and_image = function(str){
    return view.sanitize( str, true );
  }

  view.truncate = function( str, limit ){
    if( ! limit ){
      limit = 20;
    }

    if(str.length > limit){
      str = str.substring(0, limit) + '...';
    }

    return str;
  }

})( jQuery, WCPT_Block_Editor.View );
