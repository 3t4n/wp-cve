jQuery(document).ready(function(){
    //showPosition();
    jQuery("#api_form").submit(function(){
        if(jQuery("#api_key").val() == ''){
            jQuery(".red-text").html('Please Enter Api Key');
            jQuery(".red-text").show();
            jQuery(".success-msg").hide();
        }else{
            jQuery(".red-text").hide();
            jQuery(".success-msg").show();
        }
        jQuery.ajax({
            url:ajaxVar.ajaxurl,
            type:"post",
            dataType:'json',
            data:jQuery('#api_form').serialize(),
            success:function(res){
                var successFlag = JSON.stringify(res.success);
                if(successFlag === 'true'){
                    jQuery(".success").show();
                    jQuery(".red-text").hide();
                    jQuery('.success-msg').show();
                }else{
                    jQuery(".red-text").html('Your API key is wrong & Please, Enter valid API key.');
                    jQuery(".red-text").show();
                    jQuery(".success").hide();
                    jQuery('.success-msg').hide();
                }
            }
        })
        return false;
    })
})
/*function showPosition() {
    
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                localStorage.setItem("lat", position.coords.latitude);
                createCookie("lat", position.coords.latitude, "10"); 
                createCookie("long", position.coords.longitude, "10"); 
                localStorage.setItem("long", position.coords.longitude);      
            });
        }
}*/

// Function to create the cookie 
function createCookie(name, value, days) { 
    var expires; 
      
    if (days) { 
        var date = new Date(); 
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); 
        expires = "; expires=" + date.toGMTString(); 
    } 
    else { 
        expires = ""; 
    } 
      
    document.cookie = escape(name) + "=" +  
        escape(value) + expires + "; path=/"; 
} 