
(function ($) {

  $(document).ready(function(){

    $('.available-options-btn button[scrollto^="#"]').on('click', function(event) {

        var target = $( $(this).attr('scrollto') );

        if( target.length ) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top-20
            }, 500);
        }

    });

    if( !pre_info.pre_installed ){

      // console.log(pre_info);

      $('table.variant')
      .on('click', 'th:not(.fancySearchRow th)', function () {
        var index = $(this).index(),
            rows = [],
            thColHead = $(this).hasClass( 'price_html' ) ? 'price' : $(this).attr('class');
            thClass = $(this).hasClass('asc') ? 'dsc active' : 'asc active'
            pvt_table = $(this).closest('table');
            
        $('.variant th').not('.image_link, .quantity, .action, .check_all').addClass('asc').removeClass('active dsc');
        $(this).removeClass('active asc dsc');
        $(this).not('.image_link, .quantity, .action, .check_all').addClass(thClass);

        pvt_table.find('tbody tr').each(function (index, row) {
          rows.push($(row).detach());
        });

        rows.sort(function (a, b) {
          var aValue = $(a).find('td').eq(index).text(),
              bValue = $(b).find('td').eq(index).text();

          // Checking Currency, if found then remove the currency symbol
          if( aValue.includes(pre_info.woo_curr) || bValue.includes(pre_info.woo_curr) ){
            aValue = aValue.replace(pre_info.woo_curr,'');
            bValue = bValue.replace(pre_info.woo_curr,'');
          }
          // Checking Decimal Separator, if found then remove the decimal separator
          if( aValue.includes(pre_info.decimal_sep) || bValue.includes(pre_info.decimal_sep) ){
            aValue = aValue.replace(pre_info.decimal_sep,'');
            bValue = bValue.replace(pre_info.decimal_sep,'');
          }
          // Checking Thousand Separator, if found then remove the thousand separator
          if( aValue.includes(pre_info.thousand_sep) || bValue.includes(pre_info.thousand_sep) ){
            aValue = aValue.replace(pre_info.thousand_sep,'');
            bValue = bValue.replace(pre_info.thousand_sep,'');
          }
          // console.log(thColHead);
          return pvtIsNumeric(aValue) && pvtIsNumeric(bValue) ? 
          aValue - bValue : aValue.toString().localeCompare(bValue);
          // Previous Code
          // return aValue > bValue
          //      ? 1
          //      : aValue < bValue
          //      ? -1
          //      : 0;

        });

        if ($(this).hasClass('dsc')) {
          rows.reverse();
        }

        $.each(rows, function (index, row) {
          // $('.variant tbody').append(row);
          pvt_table.find('tbody').append(row);
        });
      });

    }

    // Custom function to check a numeric value
    // @Note: It was written because $.isNumeric() is depracated in jQuery version 3.0
    function pvtIsNumeric(n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
    }

    // +/- button JS

    var QtyInput = (function () {
      var $qtyInputs = $(".pvt-qty-input");

      if (!$qtyInputs.length) {
        return;
      }

      var $inputs = $qtyInputs.find("input.input-text.qty.text");
      var $countBtn = $qtyInputs.find(".qty-count");
      /*
       * Removing these global variables and keeping it as comment for future reference 
       * 
       * ============================================
       * var qtyMin = parseInt($inputs.attr("min")); 
       * var qtyMax = parseInt($inputs.attr("max"));
       * var qtyStep = parseInt($inputs.attr("step"));
       * 
       * @since 1.4.18
       */

      $inputs.change(function () {
        var $this = $(this);
        var $minusBtn = $this.siblings(".qty-count--minus");
        var $addBtn = $this.siblings(".qty-count--add");
        var qty = parseInt($this.val());

        /*
         * These are the local variables to target the corresponding +/- buttons of inputs
         * 
         * @note here $this means this input and declared inside the $inputs.change function
         * 
         * @since 1.4.18
         */
        var qtyMin = parseInt($this.attr("min"));
        var qtyMax = parseInt($this.attr("max"));

        if (isNaN(qty) || qty <= qtyMin) {
          $this.val(qtyMin);
          $minusBtn.attr("disabled", true);
        } else {
          $minusBtn.attr("disabled", false);
          
          if(qty >= qtyMax){
            $this.val(qtyMax);
            $addBtn.attr('disabled', true);
          } else {
            $this.val(qty);
            $addBtn.attr('disabled', false);
          }
        }
      });

      $countBtn.click(function () {
        var operator = this.dataset.action;
        var $this = $(this);
        var $input = $this.closest(".pvt-qty-input").find("input.input-text.qty.text");
        var qty = parseInt($input.val());

        /*
         * These are the local variables to target the corresponding +/- buttons of inputs
         * 
         * @note here $input means this closest .pvt-qty-input wrapper input and declared inside the $countBtn.click function
         * 
         * @since 1.4.18
         */
        var qtyMin = parseInt($input.attr("min"));
        var qtyMax = parseInt($input.attr("max"));
        var qtyStep = parseInt($input.attr("step"));

        if (operator == "add") {
          qty += qtyStep;
          if (qty >= qtyMin + qtyStep) {
            $this.siblings(".qty-count--minus").attr("disabled", false);
          }

          if (qty >= qtyMax) {
            $this.attr("disabled", true);
          }
        } else {
          qty = qty <= qtyMin ? qtyMin : (qty -= qtyStep);
          
          if (qty == qtyMin) {
            $this.attr("disabled", true);
          }

          if (qty < qtyMax) {
            $this.siblings(".qty-count--add").attr("disabled", false);
          }
        }

        $input.val(qty);
        // Keeping a trigger that can be fired for the bulk cart button option
        $this.closest(".pvt-qty-input").find("input.input-text.qty.text").trigger("change");
      });
    })();

  });

}(jQuery));
