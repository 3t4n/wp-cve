(function ($) {
  "use strict";

  $.widget("pektsekye.pofwProductOptions", { 
  
    lastOptionId  : 0,
    lastSortOrder : 0,  		    	
    lastValueId : 0,        
    lastValueSortOrder : {},
    
    
    _create : function () {

      $.extend(this, this.options);
      
      this.restrictionArea = $('#pf_restriction');
      this.searchField = $('#pf_search_field');    		
      this.notFoundMessage = $('#pf_not_found')
      this.resultSelect = $('.pf-result-select');    
      this.addButton = $('.pf-add-button');
                          
      this._on({                      
        "click button.pofw-delete-option-button": $.proxy(this.deleteOption, this),
        "click button.pofw-add-option-button": $.proxy(this.addOption, this),   
        "change select.pofw-option-type-select": $.proxy(this.onTypeChange, this),
        "click button.pofw-add-option-value-button": $.proxy(this.addRow, this),
        "click button.pofw-delete-option-value-button": $.proxy(this.deleteRow, this)                                                                         
      });              

      $('.product_options_for_woocommerce_tab').click($.proxy(this.loadOptions, this));              
    },

  
    loadOptions : function(){
      if (this.optionIds && !this.optionsLoaded){ 
        var l = this.optionIds.length;
        for (var i=0;i<l;i++){
          this.addOption({}, this.optionIds[i]);
        }
        this.optionsLoaded = true;       
      }    
    },
    
      
    addOption : function(e, optionId){
      var data;
       
      if (optionId){
        data = this.optionsData[optionId];
        data.id = data.option_id;
      } else {
        data = {};
        data.id = this.lastOptionId + 1;
        data.option_id = -1;      
        data.sort_order = this.lastSortOrder + 1;
        data.required = 1;
        this.lastOptionId++;
        this.lastSortOrder++;                      
      }    
              
      var template = wp.template('pofw-custom-option-base');
      $('#pofw_product_options_container').append(template(data));
      
      if (optionId){
        $('#pofw_option_'+optionId+'_type').val(data.type).change();
      }      
    },


    onTypeChange : function(e){ 
      var currentElement = $(e.target);
      var group = currentElement.find('[value="' + currentElement.val() + '"]').closest('optgroup').attr('data-optgroup-name');
      if (!group){
        return;
      }
      
      var parentId = '#' + currentElement.closest('.fieldset-alt').attr('id');      
      var id = parseInt($(parentId + '_id').val());  
      var prevGroup = $(parentId + '_group').val();
            
      var data;
      if (this.optionsData && this.optionsData[id]){
        data = $.extend({}, this.optionsData[id]);
        data.id = data.option_id;
        data.price = data.price != 0 ? data.price.toFixed(2) : '';                
      } else {
        data = {};      
        data.id = id;
      }
             
      if (group == prevGroup){
        return;
      } else if (prevGroup != ''){
        $('#pofw_option_'+id+'_type_'+prevGroup).remove();
      }

      var template = wp.template('custom-option-'+group+'-type');
      $('#pofw_option_'+id).append(template(data));
      $(parentId + '_group').val(group);
      
      if (group == 'select'){
        if (this.optionsData && this.optionsData[id] && this.optionsData[id]['values']){
          var l = this.optionsData[id]['values'].length;
          for (var i=0;i<l;i++){
            this.addRow({}, id, this.optionsData[id]['values'][i]);
          }
        } else {
          this.addRow({}, id);
        }
      }  
    },
    

    addRow : function(e, id, rowData){ 
    
      if (!id){
        var currentElement = $(e.target);      
        var parentId = '#' + currentElement.closest('.fieldset-alt').attr('id');    
        id = parseInt($(parentId + '_id').val());
      }
      
      var data;
      if (rowData){
        data = $.extend({}, rowData);
        data.id = id;
        data.vid = data.value_id;
        data.price = data.price != 0 ? data.price.toFixed(2) : '';        
      } else {
        data = {};
        data.id = id;   
        data.vid = this.lastValueId + 1;
        if (!this.lastValueSortOrder[id])
          this.lastValueSortOrder[id] = 0;
        data.sort_order = this.lastValueSortOrder[id] + 1;        
        data.value_id = -1;
        this.lastValueId++;
        this.lastValueSortOrder[id]++;        
      }
           
      var template = wp.template('custom-option-select-type-row');
      $('#pofw_select_option_type_row_'+id).append(template(data));              
    },    
          
      
    deleteOption : function(e){        
      var optionWrapper = $(e.target).closest('.fieldset-wrapper');
      var parentId = '#' + optionWrapper.find('.fieldset-alt').attr('id');    
     
      $(parentId + '_is_delete').val(1);          
      optionWrapper.hide();
    },
          
      
    deleteRow : function(e){        
      var tr = $(e.target).closest('tr');  
      var parentId = '#' + tr.attr('id');    
     
      $(parentId + '_is_delete').val(1);          
      tr.hide();
    }    
    	
  }); 
   
})(jQuery);
