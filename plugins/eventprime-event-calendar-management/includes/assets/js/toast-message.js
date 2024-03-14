// show toast message
function show_toast( type, message, heading = true ) {
    if( type == 'error' ) {
        heading_data = '';
        if( heading ) {
            heading_data = 'Error';
        }
        jQuery.toast({
            heading: heading_data,
            text: message,
            position: 'top-right',
            stack: false,
            hideAfter: 5000,
            bgColor: '#dc3545',
            textColor: 'white'
        });
    }
    if( type == 'success' ) {
        heading_data = '';
        if( heading ) {
            heading_data = 'Success';
        }
        jQuery.toast({
            heading: heading_data,
            text: message,
            position: 'top-right',
            stack: false,
            hideAfter: 5000,
            bgColor: '#218838',
            textColor: 'white'
        });
    }
    if( type == 'warning' ) {
        heading_data = '';
        if( heading ) {
            heading_data = 'Warning';
        }
        jQuery.toast({
            heading: heading_data,
            text: message,
            position: 'top-right',
            stack: false,
            hideAfter: 5000,
            bgColor: '#d39e00',
            textColor: '#212529'
        });
    }
}

// show toast message
function show_admin_toast( type, message, heading = true ) {
    if( type == 'error' ) {
        heading_data = '';
        if( heading ) {
            heading_data = 'Error';
        }
        jQuery.toast({
            heading: heading_data,
            text: message,
            position: 'bottom-left',
            stack: false,
            hideAfter: 5000,
            bgColor: '#dc3545',
            textColor: 'white'
        });
    }
    if( type == 'success' ) {
        heading_data = '';
        if( heading ) {
            heading_data = 'Success';
        }
        jQuery.toast({
            heading: heading_data,
            text: message,
            position: 'bottom-left',
            stack: false,
            hideAfter: 5000,
            bgColor: '#218838',
            textColor: 'white'
        });
    }
    if( type == 'warning' ) {
        heading_data = '';
        if( heading ) {
            heading_data = 'Warning';
        }
        jQuery.toast({
            heading: heading_data,
            text: message,
            position: 'bottom-left',
            stack: false,
            hideAfter: 5000,
            bgColor: '#d39e00',
            textColor: '#212529'
        });
    }
}