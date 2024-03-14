(function($) {

    <?php if ( $settings->layout === '3' ) { ?>
        $('.xpro-team-layout-3').hover(function () {
            $(this).find('.xpro-team-description').slideDown(200);
        }, function () {
            $(this).find('.xpro-team-description').slideUp(200);
        });
    <?php } ?>


    <?php if ( $settings->layout === '7' ) { ?>
        $('.xpro-team-layout-7').hover(function () {
            $(this).find('.xpro-team-description').slideDown(200);
            $(this).find('.xpro-team-social-list').slideDown(250);
        }, function () {
            $(this).find('.xpro-team-description').slideUp(200);
            $(this).find('.xpro-team-social-list').slideUp(250);
        });
    <?php } ?>


    <?php if ( $settings->layout === '8' ) { ?>
        $('.xpro-team-layout-8').hover(function () {
            $(this).find('.xpro-team-content').slideDown(200);
        }, function () {
            $(this).find('.xpro-team-content').slideUp(200);
        });
    <?php } ?>


    <?php if ( $settings->layout === '9' ) { ?>
        let height = $scope.find('.xpro-team-image > img').height();
        let width = $scope.find('.xpro-team-inner-content').height();
        $('.xpro-team-inner-content').width(height);
        $('.xpro-team-inner-content').css('left', width + 'px');
    <?php } ?>

    <?php if ( $settings->layout === '14' ) { ?>
        $('.xpro-team-layout-14').hover(function () {
            $(this).find('.xpro-team-description').slideDown(200);
            $(this).find('.xpro-team-social-list').slideDown(250);
        }, function () {
            $(this).find('.xpro-team-description').slideUp(200);
            $(this).find('.xpro-team-social-list').slideUp(250);
        });
    <?php } ?>

})(jQuery);