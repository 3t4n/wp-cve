(function($){
    $(document).ready(function(){
        /*
        * @todo load city and district select
        * */
        var dvls_city = $.parseJSON(dvls_admin.local_address);
        var citySelect = $('.dvls_city');
        var districtSelect = $('.dvls_district');
        var oldValueCity = citySelect.data('value');
        var oldValueDistrict = districtSelect.data('value');
        $(dvls_city).each(function(index, value){
            var thisChecked = '';
            if(oldValueCity == value.id) {
                thisChecked = 'selected="selected"';
                $(value.district).each(function(index, value) {
                    var thisChecked = '';
                    if(oldValueDistrict == value.id) {
                        thisChecked = 'selected="selected"';
                    }
                    $('.dvls_district').append('<option value="' + value.id + '" '+thisChecked+'>' + value.name + '</option>');
                });
            }
            $('.dvls_city').append('<option value="'+value.id+'" '+thisChecked+'>'+value.name+'</option>');
        });
        $('.dvls_city').on('change',function(){
            var thisval = $(this).val();
            $('.dvls_district').html('<option value="null">Select district</option>');
            $(dvls_city).each(function(index, value){
                if(thisval == value.id){
                    $(value.district).each(function(index, value) {
                        $('.dvls_district').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    return false;
                }
            });
        });
        //image upload
        $('body').on('click','.ireel-upload',function(e){
            // Prevents the default action from occuring.
            e.preventDefault();
            var thisUpload = $(this).parents('.svl-upload-image');
            // Sets up the media library frame
            meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
                title: 'Upload Image',
                button: { text:  'Upload Image' },
                library: { type: 'image' },
                multiple: false
            });
            // Runs when an image is selected.
            meta_image_frame.on('select', function(){
                // Grabs the attachment selection and creates a JSON representation of the model.
                var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
                // Sends the attachment URL to our custom image input field.

                if ( media_attachment.id ) {
                    var attachment_image = media_attachment.sizes && media_attachment.sizes.thumbnail ? media_attachment.sizes.thumbnail.url : media_attachment.url;

                    thisUpload.addClass('has-image');
                    thisUpload.find('input[type="hidden"]').val(media_attachment.id);
                    thisUpload.find('img.image_view').attr('src',media_attachment.url);
                }
            });
            // Opens the media library frame.
            meta_image_frame.open();
        });


        $('body').on('click','.svl-delete-image',function(){
            var parentDiv = $(this).parents('.svl-upload-image');
            parentDiv.removeClass('has-image');
            parentDiv.find('input[type="hidden"]').val('');
            return false;
        });

        var autocomplete;
        var mapDiv = $('#dvls_maps');
        function dvls_initMap() {
            var dvls_center = {lat: mapDiv.data('lat'), lng: mapDiv.data('lng')};
            var map = new google.maps.Map(document.getElementById('dvls_maps'), {
                zoom: parseInt(dvls_admin.maps_zoom),
                center: dvls_center
            });
            var marker = new google.maps.Marker({
                position: dvls_center,
                map: map
            });
            google.maps.event.addListener(map, "click", function (e) {
                marker.setPosition(e.latLng);
                var t = e.latLng;
                $('#dvls_maps_lat').val(t.lat().toFixed(6));
                $('#dvls_maps_lng').val(t.lng().toFixed(6));
                $('#dvls_maps_address').val('');
            });
            //search box
            var input = document.getElementById('dvls_maps_address');
            autocomplete = new google.maps.places.Autocomplete(input, {
                    types: [] //geocode
            });
            autocomplete.addListener('place_changed', function(){
                var place = autocomplete.getPlace();
                marker.setPosition(place.geometry.location);
                map.setCenter(place.geometry.location);
                $('#dvls_maps_lat').val(place.geometry.location.lat().toFixed(6));
                $('#dvls_maps_lng').val(place.geometry.location.lng().toFixed(6));
            });
        }
        dvls_initMap();
    });
})(jQuery);