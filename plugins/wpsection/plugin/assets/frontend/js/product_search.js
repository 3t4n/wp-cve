(function ($) {
    ("use strict");


    $(document).ready(function() {

      $(".vehicle-list-wapper .select-year-parts").change(function(){
        $('.vehicle-list-wapper .select-make-parts').prop('disabled', false);
      });

      $(".vehicle-list-wapper .select-make-parts").change(function(){
        $('.vehicle-list-wapper .select-model-parts').prop('disabled', false);
      });

      $(".vehicle-list-wapper .select-model-parts").change(function(){
        $('.vehicle-list-wapper .select-engine-parts').prop('disabled', false);
      });

      $(".wpsection-parts-search-box-area .select-year-parts").change(function(){
        $('.wpsection-parts-search-box-area .select-make-parts').prop('disabled', false);
      });

      $(".wpsection-parts-search-box-area .select-make-parts").change(function(){
        $('.wpsection-parts-search-box-area .select-model-parts').prop('disabled', false);
      });

      $(".wpsection-parts-search-box-area .select-model-parts").change(function(){
        $('.wpsection-parts-search-box-area .select-engine-parts').prop('disabled', false);
      });

    });


  })(jQuery);
