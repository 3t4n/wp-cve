(function ($) {
  "use strict";

  $.widget("pektsekye.pofwProductOptions", { 
  
    optionPrices : {},
    valuePrices : {},
 
    
    _create : function () {

      $.extend(this, this.options);
      
      this.priceDiv = this.isOnSale ? $('#product-'+ this.productId +' .summary ins .woocommerce-Price-amount') : $('#product-'+ this.productId +' .summary .woocommerce-Price-amount');      
      
      var bdi = this.priceDiv.find('bdi');
      if (bdi.length){
        this.priceDiv = bdi;         
      }
            
      this.form = this.element.closest('form');
      
      this.form.on("submit", $.proxy(this.validate, this));

      this._on({
        "change .pofw-option" : $.proxy(this.updatePrice, this)
      });                
    },


    updatePrice : function(){
      var el,vId,vIds,ll;
            
      var price = this.productPrice;     

      var elements = this.element.find('.pofw-option');
      
      var l = elements.length;
      while (l--){
        el = $(elements[l]);

        if (el[0].type == 'select-one'){
          vId = el.val();
          if (vId && this.valuePrices[vId]){
            price += this.valuePrices[vId];
          }      
        } else if (el[0].type == 'select-multiple'){
          vIds = el.val();
          if (vIds){
            ll = vIds.length;
            while (ll--){
              vId = vIds[ll];
              if (this.valuePrices[vId]){
                price += this.valuePrices[vId];
              }
            }
          } 
        } else if (el[0].type == 'radio' || el[0].type == 'checkbox'){
          if (el[0].checked){
            vId = el[0].value;
            if (vId && this.valuePrices[vId]){
              price += this.valuePrices[vId];
            }          
          }               
        } else if (el[0].type == 'text' || el[0].type == 'textarea'){
          if (el.val() != ''){
            var startIndex = el[0].name.indexOf('[') + 1;
            var endIndex = el[0].name.indexOf(']');
            var oId = parseInt(el[0].name.substring(startIndex, endIndex), 10);
            if (this.optionPrices[oId]){
              price += this.optionPrices[oId];
            }
          }        
        }
      }                     
      
      var formatedPrice = price.toFixed(this.numberOfDecimals).replace('.', this.decimalSeparator);
               
      formatedPrice = this.addThousendSeparator(formatedPrice);
      
      if (this.currencyPosition == 'left_space'){
        formatedPrice = ' ' + formatedPrice;
      } else if (this.currencyPosition == 'right_space'){
        formatedPrice += ' ';      
      }
                       
      if (this.currencyPosition == 'left' || this.currencyPosition == 'left_space'){
        this.priceDiv.contents().last()[0].textContent = formatedPrice;
      } else {
        this.priceDiv.contents().first()[0].textContent = formatedPrice;      
      }      
    },
    
      
    validate : function(){
      var firstNotValidInput;
      
      var formValid = true;
      
      this.element.find('.pofw-required.pofw-not-valid').removeClass('pofw-not-valid');
      this.element.find('.pofw-required .pofw-required-text').remove();
      
      var requiredText = this.requiredText;            
      this.element.find('.pofw-required').each(function(index, el) {
        var valid = true;
        var optionRow = $(el);
        var input = optionRow.find('.pofw-option').first();
        if (input[0].type == 'select-one' || input[0].type == 'select-multiple'){
          valid = input.val() != '' && input.val() != null;
        } else if (input[0].type == 'radio' || input[0].type == 'checkbox'){
          valid = optionRow.find('.pofw-option:checked').length > 0;
        } else if (input[0].type == 'text' || input[0].type == 'textarea'){
          valid = input.val() != '';        
        }
        
        if (!valid){
          optionRow.addClass('pofw-not-valid');
          optionRow.append('<div class="pofw-required-text">'+ requiredText +'</div>');        
          if (firstNotValidInput == undefined){
            firstNotValidInput = input; 
          }
          formValid = false;          
        }        
      });
      
      if (firstNotValidInput != undefined){
        firstNotValidInput.focus();
      }
      
      return formValid;
    },   
    
    addThousendSeparator : function(nStr){
        if (this.thousandSeparator == ''){
          return nStr;
        }  
        nStr += '';
        var x = nStr.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + this.thousandSeparator + '$2');
        }
        return x1 + x2;
    }    
    	
  }); 
   
})(jQuery);
