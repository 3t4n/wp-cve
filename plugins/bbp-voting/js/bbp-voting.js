function bbpress_post_vote_link_clicked(post_id, direction) {
    var post_clicked = jQuery('.bbp-voting.bbp-voting-post-'+post_id);
    // Validate you're allowed to vote
    if(post_clicked.hasClass('view-only')) {
        console.log('Post ID ' + post_id + ' is view only.');
        return false;
    }
    // Loading CSS
    post_clicked.css('opacity', 0.5).css('pointer-events', 'none');
    // Ajax data
    var data = {
        'action': 'bbpress_post_vote_link_clicked',
        'post_id': post_id,
        'direction': direction
    };
    jQuery.post(bbp_voting_ajax_object.ajax_url, data, function(response) {
        if(response.hasOwnProperty('error')) {
            // Error response
            console.log('Voting error:', response.error);
        } else if(!response.hasOwnProperty('score')) {
            // Catch invalid AJAX response
            console.log('SOMETHING WENT WRONG', response);
        } else {
            // Proper response that has score, direction, ups, and downs
            var score = parseInt(response.score);
            direction = parseInt(response.direction);
            var up = parseInt(response.ups);
            var down = parseInt(response.downs);
            var simple_score = up + down;
            console.log('Voted ' + direction, 'post #' + post_id, 'score: ' + score, 'ups: ' + up, 'downs: ' + down);
            jQuery('.bbp-voting.bbp-voting-post-'+post_id).each(function() {
                // Get elements
                var wrapper = jQuery(this);
                var score_el = jQuery(this).find('.score');
                var up_el = jQuery(this).find('.up');
                var down_el = jQuery(this).find('.down');
                // Set elements' html
                score_el.html(score);
                up_el.attr('data-votes', (up ? '+' + up : ''));
                down_el.attr('data-votes', (down < 0 ? down : ''));
                // Change arrow colors
                if(direction > 0) {
                    // Up vote
                    up_el.css('border-bottom-color', '#1e851e');
                    wrapper.removeClass('voted-down').addClass('voted-up');
                } else if (direction < 0) {
                    // Down vote
                    down_el.css('border-top-color', '#992121');
                    wrapper.removeClass('voted-up').addClass('voted-down');
                } else if (direction == 0) {
                    // Remove vote
                    up_el.css('border-bottom-color', 'inherit');
                    down_el.css('border-top-color', 'inherit');
                    wrapper.removeClass('voted-down').removeClass('voted-up');
                }
                wrapper.removeClass('positive').removeClass('negative').addClass(simple_score > 0 ? 'positive' : (simple_score < 0 ? 'negative' : ''));
                // Restore the CSS
                wrapper.css('opacity', 1).css('pointer-events', 'auto');
            });
        }
    });
}

function bbp_voting_select_accepted_answer(post_id) {
    // Ajax data
    var data = {
        'action': 'bbp_voting_select_accepted_answer',
        'post_id': post_id
    };
    jQuery.post(bbp_voting_ajax_object.ajax_url, data, function(response) {
        console.log('Accepted answer', response);
        if(response) window.location.reload();
    });
}


(function($) {
    // Sort by dropdown select
    // $('form.bbp-voting-sort > select').on('change', function() {
    //     $(this).parent('form.bbp-voting-sort').submit();
    // });

    // Fix for BuddyBoss theme grabbing reply excerpt text including vote buttons and score
    $( document ).on(
        'click',
        'a[data-modal-id-inline]',
        function (e) {
            e.preventDefault();
            // Use setTimeout to move the end of the call stack
            setTimeout(function() {
                var bbpress_forums_element = $( e.target ).closest( '.bb-grid' );
                var reply_excerpt_el = bbpress_forums_element.find( '.bbp-reply-form' ).find( '#bbp-reply-exerpt' );
                reply_excerpt_el.html( reply_excerpt_el.html().replace(/^.*\:\:/s, '') );
            }, 0);
        }
    );
})(jQuery);