jQuery( document ).ready(function() {
    // console.log( "country ready!" );
    
    jQuery('.country_select_class').each(function(index, value) {

          var id =  jQuery(this).attr("id");
          var default_country =  jQuery(this).attr("default_country").trim();

          jQuery(jQuery(this)).countrySelect({
               defaultCountry: default_country,
               responsiveDropdown: true,
            });

        });

});