jQuery(document).ready(function($){

    // Set the country code (That will display the CF)
    var countryCode = 'IT';

    $('select#billing_country').change(function()
    {
       selectedCountry = $('select#billing_country').val();
         
       if( selectedCountry == countryCode )
        {
            
            $('#billing_cod_fisc_field').show(function(){
                
                $('span[class^="optional"]', this).remove();

                $(this).children('label').append( ' <abbr class="required" title="obbligatorio">*</abbr>' );
                $(this).addClass("validate-required");
            
            });
        }
        else if ( selectedCountry !== countryCode ) 
        {
            $('#billing_cod_fisc_field').hide(function(){
                $('.required',this).remove();
                $(this).removeClass("validate-required");
                $(this).removeClass("woocommerce-validated");
                
            });
        }

    });
       
});