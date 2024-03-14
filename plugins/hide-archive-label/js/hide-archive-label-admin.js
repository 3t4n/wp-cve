jQuery(function($) {
    $(function() {

        /* For Input Switch */
        $( '.shm-input-switch' ).on( 'click', function() {
            
            var main_control = $( this );

            var attribute = $( this ).is( ':checked' );

            if ( true == attribute ) {
                main_control.parent().parent().addClass( 'active' );
                main_control.parent().parent().removeClass( 'inactive' );
            } else if( false == attribute ) {
                main_control.parent().parent().addClass( 'inactive' );
                main_control.parent().parent().removeClass( 'active' );
            }
                
        });
        /* For Input Switch End */
    });

    // Tabs
    $('.catchp_widget_settings .nav-tab-wrapper a').on('click', function(e){
        e.preventDefault();
        
        if( !$(this).hasClass('ui-state-active') ) {
            $('.nav-tab').removeClass('nav-tab-active');
            $('.wpcatchtab').removeClass('active').fadeOut(0);

            $(this).addClass('nav-tab-active');

            var anchorAttr = $(this).attr('href');

            $(anchorAttr).addClass('active').fadeOut(0).fadeIn(500);
        }

    });

    // jQuery Match Height init for sidebar spots
    $(document).ready(function() {
        $('.catchp-sidebar-spot .sidebar-spot-inner, .col-2 .catchp-lists li, .col-3 .catchp-lists li').matchHeight();
    });
     // jQuery UI Tooltip initializaion
    $(document).ready(function() {
        $('.tooltip').tooltip();
    });
    
});