var WCPT_Block_Editor = {};

(function($){

  WCPT_Block_Editor = {

    $elm: $(),

    Config: {
      add_row: true,
      delete_row: true,
      add_element_partial: 'add-common-element',
      connect_with: '.wcpt-block-editor-row',
    },

    Ctrl: {}, View: {}, Model: {},

    init: function(elm, options){

      // instantiate sub objects
      this.view = Object.create(WCPT_Block_Editor.View);
      this.model = Object.create(WCPT_Block_Editor.Model);
      this.ctrl = Object.create(WCPT_Block_Editor.Ctrl);
      this.config = Object.create(WCPT_Block_Editor.Config);

      // parent refrence
      this.view.parent = this;
      this.model.parent = this;
      this.ctrl.parent = this;

      // relate $elm and this
      this.$elm = $(elm);
      this.$elm.data('wcpt_block_editor', this);

      this.$elm.addClass('wcpt-block-editor');

      // update config
      if( options ){
        // from jQ init params
        $.extend(true, this.config, options);
      }else{
        // from $elm attrs
        //-- add elm
        if( this.$elm.attr('wcpt-be-add-element-partial') ){
          this.config.add_element_partial = this.$elm.attr('wcpt-be-add-element-partial');
        }
        //-- add row
        if( this.$elm.attr('wcpt-be-add-row') === '0' ){
          this.config.add_row = false;
        }
        //-- delete row
        if( this.$elm.attr('wcpt-be-delete-row') === '0' ){
          this.config.delete_row = false;
        }
        //-- edit row
        if( this.$elm.attr('wcpt-be-edit-row') ){
          this.config.edit_row = this.$elm.attr('wcpt-be-edit-row');
        }        
        //-- connect
        if( this.$elm.attr('wcpt-be-connect-with') ){
          this.config.connect_with = this.$elm.attr('wcpt-be-connect-with');
        }

      }

      // attach controller
      // -- add row
      this.$elm.on('click', '.wcpt-block-editor-add-row', this.ctrl.add_row);
      // -- sort update
      this.$elm.on('sortupdate', this.ctrl.sort_update);
      // lightbox
      // -- open
      // -- -- to edit element
      this.$elm.on('click', '.wcpt-element-block', this.ctrl.edit_element);
      // -- -- to add element
      this.$elm.on('click', '.wcpt-block-editor-add-element', this.ctrl.add_element);
      // -- -- row settings
      this.$elm.on('click', '.wcpt-block-editor-edit-row', this.ctrl.edit_row);
      // -- -- delete settings
      this.$elm.on('click', '.wcpt-block-editor-delete-row', this.ctrl.delete_row);

      // set data and render
      var data = false;

      // -- from $elm
      if( this.$elm.data('wcpt-data') ){
        data = this.$elm.data('wcpt-data');
      }

      // -- from config
      if( this.config.data ){
        data = $.extend( true, {}, this.config.data );
      }

      // -- convert text to elm
      if( typeof data == 'string' ){
        data = [{
          id: Date.now(),
          style: {},
          condition: {},
          elements: [{
            type: 'text',
            text: data,
          }],
        }];
      }
      if( typeof data !== 'object' ){
        data = [{
          id: Date.now(),
          style: {},
          condition: {},
          elements: [],
        }];
      }

      var propagation = false; // don't want to trigger parent controller unnecessarily during init
      this.model.set_data(data, propagation); // sets data and also renders
    },
  };

  $.fn.wcpt_block_editor = function(options) {
    return this.each(function() {
      if( ! $(this).data('wcpt_block_editor') ){
        // init
        var block_editor = Object.create(WCPT_Block_Editor);
        block_editor.init(this, options);
      }
    });
  };

})(jQuery)
