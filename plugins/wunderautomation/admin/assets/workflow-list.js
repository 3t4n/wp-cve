(function( $ ) {

    /**
     * Quick Edit for automation-workflow post type
     */
    $('#the-list').on('click', 'button.editinline', function (e) {
        if (!$('body').hasClass('post-type-automation-workflow')) { return; }

        var dataRowId = $(this).closest('tr').attr('id').replace('post-', '');
        var dataRow = '#post-' + dataRowId;

        $('.sortorder', '.inline-edit-row').val($('.column-order', dataRow).html());
        var active = $('.span-active', dataRow).data('active');
        $('.active option', '.inline-edit-row').removeAttr('selected');
        $('.active option[value="' + active + '"]', '.inline-edit-row').attr('selected', 'selected');

        $('label.inline-edit-status', '.inline-edit-row').parent().parent().parent().remove();
        $('fieldset.inline-edit-date', '.inline-edit-row').remove();
        $('label.inline-edit-private', '.inline-edit-row').parent().remove();
    });

})( jQuery );
