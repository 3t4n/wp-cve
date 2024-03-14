//easypack-widget
//select-link

jQuery(document).ready(function(){
    jQuery( '#parcel_machine_id' ).bind( 'change', function() {
        jQuery('.easypack-widget').hide();
    });
});

function getAddressByPoint($pointObject)
{
    var $output = '<span class="parcel-machine-desc">';
    $output  += $pointObject.name;
    $output  += '<br>';
    $output  += $pointObject.address.line1;
    $output  += '<br>';
    $output  += $pointObject.address.line2;
    $output += '</span>';

    return $output;
}

