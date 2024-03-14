jQuery(document).ready(function() { 
	authorsure_set_author_options();
	authorsure_set_archive_options();
	jQuery('input[name="authorsure_author_rel"]').change( function() { authorsure_set_author_options(); });
	jQuery('input[name="authorsure_archive_link"]').change( function() { authorsure_set_archive_options(); });
});

function authorsure_set_author_options() {
	arel = jQuery('input[name="authorsure_author_rel"]:checked').val();
	jQuery('#author_footnote').css("display",arel=="footnote"?"block":"none");	
	jQuery('#author_box').css("display",arel=="box"?"block":"none");	
	jQuery('#author_menu').css("display",arel=="menu"?"block":"none");
}

function authorsure_set_archive_options() {
	aarc = jQuery('input[name="authorsure_archive_link"]:checked').val();
	jQuery('#archive_settings').css("display",aarc=="publisher"?"none":"block");	
}

function authorsure_validate_form(frm){
    var firstname = frm.elements["firstname"];
	if ((firstname.value==null)||(firstname.value=="")){
		alert("Please enter your First Name")
		firstname.focus();
		return false;
	}
    var email = frm.elements["email"];
	if ((email.value==null)||(email.value==""))
		alert("Please enter your Email Address")
    else {
        if (authorsure_validate_email(email.value))
           return true;
	    else
	  	   alert('Please provide a valid email address');
        }
	email.focus();
	return false;
 }

function authorsure_validate_email(emailaddress) {
    var filter = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
    return filter.test(emailaddress);
}