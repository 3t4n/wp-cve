// Color Picker Styling

(function( $ ) {

    // Add Color Picker to all inputs that have 'color-field' class
    $(function() {
        $('.color-field').wpColorPicker();
    });

})( jQuery );


(function( $ ) {

	// Toggle subitems
	var $toogleItems = $( '.ucd-item-toggle' );

	if ( 1 > $toogleItems.length ) {
		return;
	}

	$toogleItems.on( 'click', function( event ) {
		event.preventDefault();

		var $menuItem = $( this ).closest( '.top-menu' );
			$subItems = $menuItem.next( '.ucd-dynamic-subitems-wrap' );

			$( this ).toggleClass( 'ucd-item-toggle-active' );
			$subItems.slideToggle();
	} );

})( jQuery );


// Copy shortcodes to clipboard

function ucdCopyToClipboard(containerid) {
    var copyText = document.getElementById(containerid);
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");

    // [site-title]
    var siteTitleTip = document.getElementById('siteTitleTip');
    siteTitleTip.innerHTML = "Copied to clipboard";
    // [site-description]
    var siteDescriptionTip = document.getElementById('siteDescriptionTip');
    siteDescriptionTip.innerHTML = "Copied to clipboard";
    // [url]
    var urlTip = document.getElementById('urlTip');
    urlTip.innerHTML = "Copied to clipboard";
    // [admin-email]
    var adminEmailTip = document.getElementById('adminEmailTip');
    adminEmailTip.innerHTML = "Copied to clipboard";

    // [username]
    var usernameTip = document.getElementById('usernameTip');
    usernameTip.innerHTML = "Copied to clipboard";
    // [email]
    var emailTip = document.getElementById('emailTip');
    emailTip.innerHTML = "Copied to clipboard";
    // [first-name]
    var firstNameTip = document.getElementById('firstNameTip');
    firstNameTip.innerHTML = "Copied to clipboard";
    // [last-name]
    var lastNameTip = document.getElementById('lastNameTip');
    lastNameTip.innerHTML = "Copied to clipboard";
    // [display-name]
    var displayNameTip = document.getElementById('displayNameTip');
    displayNameTip.innerHTML = "Copied to clipboard";

    // [year]
    var yearTip = document.getElementById('yearTip');
    yearTip.innerHTML = "Copied to clipboard";
    // [month]
    var monthTip = document.getElementById('monthTip');
    monthTip.innerHTML = "Copied to clipboard";
    // [day]
    var dayTip = document.getElementById('dayTip');
    dayTip.innerHTML = "Copied to clipboard";

    // [copyright]
    var copyrightTip = document.getElementById('copyrightTip');
    copyrightTip.innerHTML = "Copied to clipboard";
    // [registered]
    var registeredTip = document.getElementById('registeredTip');
    registeredTip.innerHTML = "Copied to clipboard";
    // [trademark]
    var trademarkTip = document.getElementById('trademarkTip');
    trademarkTip.innerHTML = "Copied to clipboard";
}

function ucdClipboardOut() {
    // [site-title]
    var siteTitleTip = document.getElementById('siteTitleTip');
    siteTitleTip.innerHTML = "Copy to clipboard";
    // [site-description]
    var siteDescriptionTip = document.getElementById('siteDescriptionTip');
    siteDescriptionTip.innerHTML = "Copy to clipboard";
    // [url]
    var urlTip = document.getElementById('urlTip');
    urlTip.innerHTML = "Copy to clipboard";
    // [admin-email]
    var adminEmailTip = document.getElementById('adminEmailTip');
    adminEmailTip.innerHTML = "Copy to clipboard";

    // [username]
    var usernameTip = document.getElementById('usernameTip');
    usernameTip.innerHTML = "Copy to clipboard";
    // [email]
    var emailTip = document.getElementById('emailTip');
    emailTip.innerHTML = "Copy to clipboard";
    // [first-name]
    var firstNameTip = document.getElementById('firstNameTip');
    firstNameTip.innerHTML = "Copy to clipboard";
    // [last-name]
    var lastNameTip = document.getElementById('lastNameTip');
    lastNameTip.innerHTML = "Copy to clipboard";
    // [display-name]
    var displayNameTip = document.getElementById('displayNameTip');
    displayNameTip.innerHTML = "Copy to clipboard";

    // [year]
    var yearTip = document.getElementById('yearTip');
    yearTip.innerHTML = "Copy to clipboard";
    // [month]
    var monthTip = document.getElementById('monthTip');
    monthTip.innerHTML = "Copy to clipboard";
    // [day]
    var dayTip = document.getElementById('dayTip');
    dayTip.innerHTML = "Copy to clipboard";

    // [copyright]
    var copyrightTip = document.getElementById('copyrightTip');
    copyrightTip.innerHTML = "Copy to clipboard";
    // [registered]
    var registeredTip = document.getElementById('registeredTip');
    registeredTip.innerHTML = "Copy to clipboard";
    // [trademark]
    var trademarkTip = document.getElementById('trademarkTip');
    trademarkTip.innerHTML = "Copy to clipboard";
}
