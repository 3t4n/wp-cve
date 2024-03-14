(function( $ ){
    $('.ce-admin-tab-nav').on('click', 'li', function( e ){
        var currentTarget = $( e.currentTarget );
        var target = currentTarget.data('id');
        currentTarget.addClass('active').siblings().removeClass('active');
        $( '#' + target ).addClass('active').siblings().removeClass('active');
    })
})( jQuery )