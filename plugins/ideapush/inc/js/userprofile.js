jQuery(document).ready(function ($) {    
    
        
    //makes image upload field 
   $('#wpwrap').on("click",".idea_push_custom_user_profile_image", function(event){
        event.preventDefault();
       
        //console.log('I was clicked');
        
        var previousInput = $(this).prev(); 
       
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            
            previousInput.val(image_url);
            
            
            //update user profile image with new image
            
            $('#ideaPushImagePreview').attr('src',image_url);
            

        });
    });
    
    
    
    
    
    
    
    
});    