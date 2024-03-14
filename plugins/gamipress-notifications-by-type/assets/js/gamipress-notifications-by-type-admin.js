(function( $ ) {

    // Hide achievement notification
    $('#_gamipress_notifications_by_type_disable_achievements').on('change', function(e) {
        var target = $('.cmb2-id--gamipress-notifications-by-type-achievement-title-pattern, .cmb2-id--gamipress-notifications-by-type-achievement-content-pattern');

        if( ! $(this).prop('checked') ) {
            target.slideDown().removeClass('cmb2-tab-ignore');
        } else {
            target.slideUp().addClass('cmb2-tab-ignore');
        }
    });

    if( $('#_gamipress_notifications_by_type_disable_achievements').prop('checked') )
        $('.cmb2-id--gamipress-notifications-by-type-achievement-title-pattern, .cmb2-id--gamipress-notifications-by-type-achievement-content-pattern').hide().addClass('cmb2-tab-ignore');

    // Override achievement output
    if( $('#_gamipress_notifications_by_type_override_achievement_template_args').length ) {
        var achievement_fields_selector = '';

        $.each( gamipress_notifications_by_type.achievement_fields, function( index, field ) {
            // Replace _ to -
            var field_selector = field.replace(/_/g, '-');
            achievement_fields_selector += '.cmb2-id--gamipress-notifications-by-type-' + field_selector + ', '
        });

        // Replace last comma
        achievement_fields_selector = achievement_fields_selector.replace(/,\s*$/, '');

        $('#_gamipress_notifications_by_type_override_achievement_template_args').on('change', function(e) {
            var target = $(achievement_fields_selector);

            if( $(this).prop('checked') ) {
                target.slideDown().removeClass('cmb2-tab-ignore');
            } else {
                target.slideUp().addClass('cmb2-tab-ignore');
            }
        });

        if( ! $('#_gamipress_notifications_by_type_override_achievement_template_args').prop('checked') )
            $(achievement_fields_selector).hide().addClass('cmb2-tab-ignore');

    }

    // Hide step notification
    $('#_gamipress_notifications_by_type_disable_steps').on('change', function(e) {
        var target = $('.cmb2-id--gamipress-notifications-by-type-step-title-pattern, .cmb2-id--gamipress-notifications-by-type-step-content-pattern');

        if( ! $(this).prop('checked') ) {
            target.slideDown().removeClass('cmb2-tab-ignore');
        } else {
            target.slideUp().addClass('cmb2-tab-ignore');
        }
    });

    if( $('#_gamipress_notifications_by_type_disable_steps').prop('checked') )
        $('.cmb2-id--gamipress-notifications-by-type-step-title-pattern, .cmb2-id--gamipress-notifications-by-type-step-content-pattern').hide().addClass('cmb2-tab-ignore');

    // Hide points award notification
    $('#_gamipress_notifications_by_type_disable_points_awards').on('change', function(e) {
        var target = $('.cmb2-id--gamipress-notifications-by-type-points-award-title-pattern, .cmb2-id--gamipress-notifications-by-type-points-award-content-pattern');

        if( ! $(this).prop('checked') ) {
            target.slideDown().removeClass('cmb2-tab-ignore');
        } else {
            target.slideUp().addClass('cmb2-tab-ignore');
        }
    });

    if( $('#_gamipress_notifications_by_type_disable_points_awards').prop('checked') )
        $('.cmb2-id--gamipress-notifications-by-type-points-award-title-pattern, .cmb2-id--gamipress-notifications-by-type-points-award-content-pattern').hide().addClass('cmb2-tab-ignore');

    // Hide points deduct notification
    $('#_gamipress_notifications_by_type_disable_points_deducts').on('change', function(e) {
        var target = $('.cmb2-id--gamipress-notifications-by-type-points-deduct-title-pattern, .cmb2-id--gamipress-notifications-by-type-points-deduct-content-pattern');

        if( ! $(this).prop('checked') ) {
            target.slideDown().removeClass('cmb2-tab-ignore');
        } else {
            target.slideUp().addClass('cmb2-tab-ignore');
        }
    });

    if( $('#_gamipress_notifications_by_type_disable_points_deducts').prop('checked') )
        $('.cmb2-id--gamipress-notifications-by-type-points-deduct-title-pattern, .cmb2-id--gamipress-notifications-by-type-points-deduct-content-pattern').hide().addClass('cmb2-tab-ignore');

    // Hide rank notification
    $('#_gamipress_notifications_by_type_disable_ranks').on('change', function(e) {
        var target = $('.cmb2-id--gamipress-notifications-by-type-rank-title-pattern, .cmb2-id--gamipress-notifications-by-type-rank-content-pattern');

        if( ! $(this).prop('checked') ) {
            target.slideDown().removeClass('cmb2-tab-ignore');
        } else {
            target.slideUp().addClass('cmb2-tab-ignore');
        }
    });

    if( $('#_gamipress_notifications_by_type_disable_ranks').prop('checked') )
        $('.cmb2-id--gamipress-notifications-by-type-rank-title-pattern, .cmb2-id--gamipress-notifications-by-type-rank-content-pattern').hide().addClass('cmb2-tab-ignore');

    // Override rank output
    if( $('#_gamipress_notifications_by_type_override_rank_template_args').length ) {

        var rank_fields_selector = '';

        $.each( gamipress_notifications_by_type.rank_fields, function( index, field ) {
            // Replace _ to -
            var field_selector = field.replace(/_/g, '-');
            rank_fields_selector += '.cmb2-id--gamipress-notifications-by-type-' + field_selector + ', '
        });

        // Replace last comma
        rank_fields_selector = rank_fields_selector.replace(/,\s*$/, '');

        $('#_gamipress_notifications_by_type_override_rank_template_args').on('change', function(e) {
            var target = $(rank_fields_selector);

            if( $(this).prop('checked') ) {
                target.slideDown().removeClass('cmb2-tab-ignore');
            } else {
                target.slideUp().addClass('cmb2-tab-ignore');
            }
        });

        if( ! $('#_gamipress_notifications_by_type_override_rank_template_args').prop('checked') )
            $(rank_fields_selector).hide().addClass('cmb2-tab-ignore');

    }

    // Hide rank requirement notification
    $('#_gamipress_notifications_by_type_disable_rank_requirements').on('change', function(e) {
        var target = $('.cmb2-id--gamipress-notifications-by-type-rank-requirement-title-pattern, .cmb2-id--gamipress-notifications-by-type-rank-requirement-content-pattern');

        if( ! $(this).prop('checked') ) {
            target.slideDown().removeClass('cmb2-tab-ignore');
        } else {
            target.slideUp().addClass('cmb2-tab-ignore');
        }
    });

    if( $('#_gamipress_notifications_by_type_disable_rank_requirements').prop('checked') )
        $('.cmb2-id--gamipress-notifications-by-type-rank-requirement-title-pattern, .cmb2-id--gamipress-notifications-by-type-rank-requirement-content-pattern').hide().addClass('cmb2-tab-ignore');

})( jQuery );