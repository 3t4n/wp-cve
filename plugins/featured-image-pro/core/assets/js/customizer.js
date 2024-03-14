jQuery(function($) {

    accordion = false;
    $('body').on('click', 'a.collapsed', function(e) {
        if (accordion == false) {
            $($(this).data('parent') + ' .panel').accordion({
                heightStyle: "content"
            });
            accordion = true;
            console.log('done!', e);

            $(e.target).click(); //is this still going to work?

        }
    });
});

